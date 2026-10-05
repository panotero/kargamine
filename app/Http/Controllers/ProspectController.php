<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesProposalRequestAccess;
use App\Models\ListOfValue;
use App\Models\ProposalRequest;
use App\Models\Prospect;
use App\Models\ProspectCompanyInfo;
use App\Models\ProspectNote;
use App\Services\ActivityService;
use App\Services\FileUploadService;
use App\Services\TeamNotifier;
use App\Services\TeamService;
use App\Support\RoleHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProspectController extends Controller
{
    use AuthorizesProposalRequestAccess;

    protected $fileUploadService;

    protected ActivityService $activityService;

    public function __construct(FileUploadService $fileUploadService, ActivityService $activityService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->activityService = $activityService;
    }

    //
    public function index(Request $request)
    {
        // Team-scoped visibility: a regular member only sees leads assigned
        // to them; a team leader sees leads assigned to anyone in their
        // team's subtree (their team's members, and any descendant team's
        // members/leaders). superadmin bypasses this entirely.
        $visibleUserIds = RoleHelper::hasAnyRole($request->user(), ['superadmin'])
            ? null
            : TeamService::accessibleUserIds($request->user());

        $leads = Prospect::query()
            ->select(
                'id',
                'uuid',
                'title',
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'mobile',
                'status',
                'assigned_to',
                'created_at',
                'updated_at'
            )
            ->with([
                'company:id,prospect_id,company_name',
                'crmStatus:id,status',
                'user:id,name',
            ])
            ->withMax('activities as last_activity_at', 'created_at')
            ->when($visibleUserIds !== null, function ($q) use ($visibleUserIds) {
                $q->whereIn('assigned_to', $visibleUserIds);
            })

            // Assigned To filter
            ->when($request->filled('assigned_to'), function ($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            })

            // Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($q) use ($search) {
                            $q->where('company_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('crmStatus', function ($q) use ($search) {
                            $q->where('status', 'like', "%{$search}%");
                        });
                });
            })

            // Status filter
            ->when(
                $request->filled('status') && strtoupper($request->status) !== 'ALL',
                function ($q) use ($request) {
                    $q->whereHas('crmStatus', function ($q) use ($request) {
                        $q->where('status', strtoupper($request->status));
                    });
                }
            )

            // Needs Attention filter - no activity ever, or none in the last
            // 14+ days, matching the red-dot threshold in
            // logic_crm.js's renderLastActivityCell().
            ->when($request->boolean('needs_attention'), function ($q) {
                $q->havingRaw('last_activity_at IS NULL OR last_activity_at < ?', [now()->subDays(14)]);
            })

            ->orderByDesc('updated_at')
            ->paginate($request->get('per_page', 25))
            ->appends($request->query());

        $allLeads = Prospect::with('crmStatus')
            ->withMax('activities as last_activity_at', 'created_at')
            ->when($visibleUserIds !== null, function ($q) use ($visibleUserIds) {
                $q->whereIn('assigned_to', $visibleUserIds);
            })
            ->get();

        $statusCounts = $allLeads
            ->groupBy(fn ($lead) => optional($lead->crmStatus)->status)
            ->map(fn ($group) => $group->count());

        $needsAttentionCount = $allLeads
            ->filter(fn ($lead) => is_null($lead->last_activity_at) || $lead->last_activity_at < now()->subDays(14))
            ->count();

        return response()->json([
            'success' => true,
            'data' => $leads,
            'status_counts' => [
                'ALL' => $allLeads->count(),
                'PROSPECT' => $statusCounts->get('PROSPECT', 0),
                'QUALIFIED' => $statusCounts->get('QUALIFIED', 0),
                'OPPORTUNITY' => $statusCounts->get('OPPORTUNITY', 0),
                'NEGOTIATION' => $statusCounts->get('NEGOTIATION', 0),
                'WIN' => $statusCounts->get('WIN', 0),
                'LOST' => $statusCounts->get('LOST', 0),
            ],
            'needs_attention_count' => $needsAttentionCount,
        ]);
    }

    public function assignableUsers(Request $request)
    {
        $userIds = RoleHelper::hasAnyRole($request->user(), ['superadmin', 'admin'])
            ? null
            : TeamService::accessibleUserIds($request->user());

        $users = \App\Models\User::query()
            ->select('id', 'name')
            ->when($userIds !== null, fn ($q) => $q->whereIn('id', $userIds))
            ->orderBy('name')
            ->get();

        return response()->json(['success' => true, 'data' => $users]);
    }

    /**
     * Management-only action - assigns the CSR (assigned_to) and
     * Relationship Manager (relationship_manager_id) that unlock the RFP
     * wizard endpoints for this prospect (see AuthorizesProposalRequestAccess).
     * Also flips this prospect's own 'pending' Proposal Request(s) to
     * 'assigned' - in practice there's normally just one at this point.
     */
    public function assignOwners(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'assigned_to' => ['required', 'integer', 'exists:users,id'],
            'relationship_manager_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $lead->assigned_to = $validated['assigned_to'];
        $lead->relationship_manager_id = $validated['relationship_manager_id'];
        $lead->save();

        $lead->proposalRequests()->where('status', 'pending')->update(['status' => 'assigned']);

        try {
            $csr = \App\Models\User::find($validated['assigned_to']);
            $rm = \App\Models\User::find($validated['relationship_manager_id']);
            $this->activityService->create(
                $lead->id,
                'prospect_assigned',
                'Assigned to '.($csr->name ?? 'a CSR').' (CSR) and '.($rm->name ?? 'a Relationship Manager').' (Relationship Manager).',
                $request->user()?->id
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'success' => true,
            'data' => $lead->fresh()->load('user:id,name', 'relationshipManager:id,name'),
        ]);
    }

    public function saveStage1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:50'],
            // Contact-person fields (first/last/mobile) used to be required
            // here, but they now live on the separate Contacts tab
            // (prospect_contacts) - the Identity tab no longer collects
            // them. Kept nullable (not removed) for back-compat with any
            // caller that still sends them.
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:'.implode(',', Prospect::GENDERS)],
            'position' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'mobile_type' => ['nullable', 'in:personal,business'],
            'landline_number' => ['nullable', 'string', 'max:50'],
            'landline_type' => ['nullable', 'in:personal,business'],
            'email' => ['nullable', 'email', 'max:255'],
            'email_type' => ['nullable', 'in:personal,business'],
            'source' => ['required', 'string', 'max:255'],
            'requires_proposal' => ['nullable', 'boolean'],

            'company_name' => ['required', 'string', 'max:255'],
            'type_of_business' => ['required', 'string', 'max:255'],
            'industry_description' => ['nullable', 'string'],

            'authorized_signatory_title' => ['nullable', 'string', 'max:50'],
            'authorized_signatory_first_name' => ['nullable', 'string', 'max:255'],
            'authorized_signatory_middle_name' => ['nullable', 'string', 'max:255'],
            'authorized_signatory_last_name' => ['nullable', 'string', 'max:255'],
            'authorized_signatory_gender' => ['nullable', 'in:'.implode(',', Prospect::GENDERS)],
            'authorized_signatory_position' => ['nullable', 'string', 'max:255'],
            'authorized_signatory_mobile' => ['nullable', 'string', 'max:50'],
            'authorized_signatory_mobile_type' => ['nullable', 'in:personal,business'],
            'authorized_signatory_landline' => ['nullable', 'string', 'max:50'],
            'authorized_signatory_landline_type' => ['nullable', 'in:personal,business'],
            'authorized_signatory_email' => ['nullable', 'email', 'max:255'],
            'authorized_signatory_email_type' => ['nullable', 'in:personal,business'],

            'addresses' => ['required', 'array', 'min:1'],
            'addresses.*.address_type' => ['nullable', 'string', 'max:255'],
            'addresses.*.is_primary' => ['nullable', 'boolean'],
            'addresses.*.address_no' => ['nullable', 'string', 'max:100'],
            'addresses.*.address_building' => ['nullable', 'string', 'max:255'],
            'addresses.*.address_street' => ['nullable', 'string', 'max:255'],
            'addresses.*.address_barangay' => ['nullable', 'string', 'max:255'],
            'addresses.*.address_town_city' => ['nullable', 'string', 'max:255'],
            'addresses.*.address_province' => ['nullable', 'string', 'max:255'],
            'addresses.*.address_country' => ['nullable', 'string', 'max:255'],
            'addresses.*.address_postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $isNew = false;

        $lead = DB::transaction(function () use ($data, &$isNew) {
            $lead = ! empty($data['uuid'])
                ? Prospect::where('uuid', $data['uuid'])->firstOrFail()
                : new Prospect(['uuid' => (string) Str::uuid()]);

            $isNew = ! $lead->exists;

            // Corporate/Individual is no longer a manual toggle - it's
            // derived from whichever "Type of Business" value was picked
            // (see BusinessTypeSeeder's lov_is_individual flag).
            $isIndividualBusinessType = (bool) ListOfValue::where('lov_name', $data['type_of_business'])
                ->whereHas('option', fn ($q) => $q->where('option_name', 'Type of Business'))
                ->value('lov_is_individual');

            $lead->fill([
                'client_type' => $isIndividualBusinessType ? 'individual' : 'corporate',
                'title' => $data['title'] ?? null,
                'first_name' => $data['first_name'] ?? null,
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'position' => $data['position'] ?? null,
                'mobile' => $data['mobile'] ?? null,
                'mobile_type' => $data['mobile_type'] ?? null,
                'landline_number' => $data['landline_number'] ?? null,
                'landline_type' => $data['landline_type'] ?? null,
                'email' => $data['email'] ?? null,
                'email_type' => $data['email_type'] ?? null,
                'source' => $data['source'],
                'requires_proposal' => $data['requires_proposal'] ?? true,
            ]);

            if ($isNew) {
                $lead->assigned_to = auth()->id();
                $lead->status = \App\Models\CrmStatus::where('status', 'PROSPECT')->first()?->id ?? 1;
                $lead->status_updated_at = now();
            }

            $lead->current_stage = max($lead->current_stage ?? 1, 1);
            $lead->save();

            \App\Models\ProspectCompanyInfo::updateOrCreate(
                ['prospect_id' => $lead->id],
                [
                    'company_name' => $data['company_name'],
                    'type_of_business' => $data['type_of_business'] ?? null,
                    'industry_description' => $data['industry_description'] ?? null,
                    'authorized_signatory_title' => $data['authorized_signatory_title'] ?? null,
                    'authorized_signatory_first_name' => $data['authorized_signatory_first_name'] ?? null,
                    'authorized_signatory_middle_name' => $data['authorized_signatory_middle_name'] ?? null,
                    'authorized_signatory_last_name' => $data['authorized_signatory_last_name'] ?? null,
                    'authorized_signatory_gender' => $data['authorized_signatory_gender'] ?? null,
                    'authorized_signatory_position' => $data['authorized_signatory_position'] ?? null,
                    'authorized_signatory_mobile' => $data['authorized_signatory_mobile'] ?? null,
                    'authorized_signatory_mobile_type' => $data['authorized_signatory_mobile_type'] ?? null,
                    'authorized_signatory_landline' => $data['authorized_signatory_landline'] ?? null,
                    'authorized_signatory_landline_type' => $data['authorized_signatory_landline_type'] ?? null,
                    'authorized_signatory_email' => $data['authorized_signatory_email'] ?? null,
                    'authorized_signatory_email_type' => $data['authorized_signatory_email_type'] ?? null,
                ]
            );

            // Whole-stage save - same replace strategy as saveStage2 for containers.
            $lead->addresses()->delete();

            $addresses = $data['addresses'];
            if (! collect($addresses)->contains(fn ($a) => ! empty($a['is_primary']))) {
                $addresses[0]['is_primary'] = true;
            }

            foreach ($addresses as $address) {
                $lead->addresses()->create($address);
            }

            $lead->recomputeCompletion();

            return $lead;
        });

        if ($isNew) {
            // The new Identity tab no longer collects a contact-person name
            // up front (that moved to the Contacts tab), so contact_name is
            // often just '-' at creation time - prefer the company name,
            // which the Identity tab does always collect.
            $leadLabel = $lead->company->company_name ?? $lead->contact_name;

            try {
                $this->activityService->create($lead->id, 'prospect_created', 'Prospect record created.');
            } catch (\Throwable $e) {
                report($e);
            }

            TeamNotifier::notify(TeamNotifier::directLeaderIds($request->user()), [
                'type' => 'crm.lead_created',
                'title' => 'New lead created',
                'message' => "{$request->user()->name} added a new lead — {$leadLabel}.",
                'from_user_id' => $request->user()->id,
                'notifiable' => $lead,
                'link' => ['title' => 'View in CRM', 'url' => '/page_crm'],
                'email_subject' => "New Lead — {$leadLabel}",
            ]);
        }

        return response()->json(['success' => true, 'data' => $lead->load('company', 'addresses')]);
    }

    public function saveStage2(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'containers' => ['nullable', 'array'],
            'containers.*.container_type' => ['nullable', 'in:CV,FR,RF,LC,RC'],
            'containers.*.origin_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'containers.*.destination_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'containers.*.booking_unit_type' => ['nullable', 'string', 'max:255'],
            'containers.*.container_class_id' => ['nullable', 'integer', 'exists:container_class,id'],
            'containers.*.container_size_id' => ['nullable', 'integer', 'exists:container_size,id'],
            'containers.*.minimum_temperature' => ['nullable', 'numeric'],
            'containers.*.quantity' => ['nullable', 'integer', 'min:0'],
            'containers.*.estimated_cbm' => ['nullable', 'numeric', 'min:0'],
            'containers.*.estimated_ton' => ['nullable', 'numeric', 'min:0'],
            'containers.*.declared_value_per_unit' => ['nullable', 'numeric', 'min:0'],
            'containers.*.frequency' => ['nullable', 'string', 'max:255'],
            'containers.*.general_cargo_description' => ['nullable', 'string'],
            'containers.*.cargo_type' => ['nullable', 'string'],
            'containers.*.service_mode_origin' => ['nullable', 'in:PIER,DOOR'],
            'containers.*.service_mode_destination' => ['nullable', 'in:PIER,DOOR'],
            'containers.*.service_mode' => ['nullable', 'in:PIER,DOOR'],
            'containers.*.dangerous_cargo' => ['nullable', 'boolean'],
            'containers.*.dg_documentary_requirement' => ['nullable', 'string', 'max:255'],
            'containers.*.special_requirements' => ['nullable', 'string'],
            'containers.*.special_notes' => ['nullable', 'string'],
        ]);

        $rowErrors = [];
        foreach ($validated['containers'] ?? [] as $i => $c) {
            $rowErrors = array_merge($rowErrors, $this->containerRowErrors($c, $i + 1));
        }

        if ($rowErrors) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $rowErrors),
                'errors' => $rowErrors,
            ], 422);
        }

        $promoted = false;

        DB::transaction(function () use ($lead, $validated, &$promoted) {
            // Simple replace strategy - same as your ClientMaster stage2.
            $lead->containers()->delete();

            foreach ($validated['containers'] ?? [] as $c) {
                $lead->containers()->create($c);
            }

            $lead->current_stage = max($lead->current_stage, 2);
            $lead->save();
            $promoted = $lead->recomputeCompletion();
        });

        $lead = $lead->fresh()->load('containers', 'company', 'addresses');

        return response()->json([
            'success' => true,
            'data' => $lead,
            'moved_to_opportunity' => $promoted,
            'missing_requirements' => $lead->is_complete ? [] : $lead->missingRequirements(),
        ]);
    }

    /**
     * Appends ONE container requirement to a lead that already has some
     * (used by the "+ Add Container" button on the Lead Info Modal),
     * unlike saveStage2 above which wipes and replaces the whole list.
     */
    public function storeContainer(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'container_type' => ['nullable', 'in:CV,FR,RF,LC,RC'],
            'origin_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'destination_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'booking_unit_type' => ['nullable', 'string', 'max:255'],
            'container_class_id' => ['nullable', 'integer', 'exists:container_class,id'],
            'container_size_id' => ['nullable', 'integer', 'exists:container_size,id'],
            'minimum_temperature' => ['nullable', 'numeric'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'estimated_cbm' => ['nullable', 'numeric', 'min:0'],
            'estimated_ton' => ['nullable', 'numeric', 'min:0'],
            'declared_value_per_unit' => ['nullable', 'numeric', 'min:0'],
            'frequency' => ['nullable', 'string', 'max:255'],
            'general_cargo_description' => ['nullable', 'string'],
            'cargo_type' => ['nullable', 'string'],
            'service_mode_origin' => ['nullable', 'in:PIER,DOOR'],
            'service_mode_destination' => ['nullable', 'in:PIER,DOOR'],
            'service_mode' => ['nullable', 'in:PIER,DOOR'],
            'dangerous_cargo' => ['nullable', 'boolean'],
            'dg_documentary_requirement' => ['nullable', 'string', 'max:255'],
            'special_requirements' => ['nullable', 'string'],
            'special_notes' => ['nullable', 'string'],
        ]);

        $rowErrors = $this->containerRowErrors($validated, 1);

        if ($rowErrors) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $rowErrors),
                'errors' => $rowErrors,
            ], 422);
        }

        $container = DB::transaction(function () use ($lead, $validated) {
            $container = $lead->containers()->create($validated);

            $lead->current_stage = max($lead->current_stage ?? 1, 2);
            $lead->save();
            $lead->recomputeCompletion();

            return $container;
        });

        return response()->json([
            'success' => true,
            'data' => $container->load([
                'originPort:port_id,location_id,name',
                'originPort.location:location_id,name',
                'destinationPort:port_id,location_id,name',
                'destinationPort.location:location_id,name',
                'containerClass:id,class',
                'containerSize:id,size',
            ]),
        ]);
    }

    /**
     * Contacts tab - replace-all save, same strategy as saveStage1's
     * addresses and saveStage2's containers. Deleting a prospect's
     * existing prospect_contacts cascades (DB FK) to their channels and
     * addresses, so we don't need to clean those up manually.
     */
    public function saveContacts(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'contacts' => ['nullable', 'array'],
            'contacts.*.title' => ['nullable', 'string', 'max:50'],
            'contacts.*.first_name' => ['required', 'string', 'max:255'],
            'contacts.*.middle_name' => ['nullable', 'string', 'max:255'],
            'contacts.*.last_name' => ['required', 'string', 'max:255'],
            'contacts.*.position' => ['nullable', 'string', 'max:255'],

            'contacts.*.channels' => ['nullable', 'array'],
            'contacts.*.channels.*.channel_type' => ['required', 'in:mobile,landline,email'],
            'contacts.*.channels.*.value' => ['required', 'string', 'max:255'],
            'contacts.*.channels.*.contact_type' => ['required', 'in:personal,business'],

            'contacts.*.location_ids' => ['nullable', 'array'],
            'contacts.*.location_ids.*' => [
                'integer',
                Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id)),
            ],
        ]);

        $contactIds = DB::transaction(function () use ($lead, $validated) {
            $lead->contacts()->delete();

            $ids = [];

            foreach ($validated['contacts'] ?? [] as $c) {
                $contact = $lead->contacts()->create([
                    'title' => $c['title'] ?? null,
                    'first_name' => $c['first_name'],
                    'middle_name' => $c['middle_name'] ?? null,
                    'last_name' => $c['last_name'],
                    'position' => $c['position'] ?? null,
                ]);

                foreach ($c['channels'] ?? [] as $channel) {
                    $contact->channels()->create($channel);
                }

                foreach ($c['location_ids'] ?? [] as $locationId) {
                    $contact->addresses()->create(['prospect_location_id' => $locationId]);
                }

                $ids[] = $contact->id;
            }

            $lead->recomputeCompletion();

            return $ids;
        });

        $contacts = \App\Models\ProspectContact::with('channels', 'addresses')
            ->whereIn('id', $contactIds)
            ->get()
            ->each(function ($contact) {
                $contact->setAttribute('location_ids', $contact->addresses->pluck('prospect_location_id')->values());
            });

        return response()->json([
            'success' => true,
            'data' => ['contacts' => $contacts],
        ]);
    }

    /**
     * Appends ONE container requirement row directly to this prospect
     * (prospect_id) - a "prospect product," independent of any Proposal
     * Request. Unlike a stage2-style whole-list replace, this only ever
     * appends.
     */
    public function storeProposalRequestContainer(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'container_type' => ['nullable', 'in:CV,FR,RF,LC,RC'],
            'delivery_type_id' => ['nullable', 'integer', 'exists:delivery_types,delivery_type_id'],
            'service_type' => ['nullable', 'string', Rule::in(['Door - Door', 'Door - Pier', 'Pier - Door', 'Pier - Pier'])],
            'origin_location_id' => ['nullable', 'integer', 'exists:locations,location_id'],
            'destination_location_id' => ['nullable', 'integer', 'exists:locations,location_id'],
            'booking_unit_type' => ['nullable', 'string', 'max:255'],
            'container_size_id' => ['nullable', 'integer', 'exists:container_size,id'],
            'minimum_temperature' => ['nullable', 'numeric'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'revenue_ton' => ['nullable', 'numeric', 'min:0'],
            'cargo_measurement' => ['nullable', 'string', 'max:255'],
            'declared_value_per_unit' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', 'in:kg,mt'],
            'frequency' => ['nullable', 'string', 'max:255'],
            'general_cargo_description' => ['nullable', 'string'],
            'cargo_type' => ['nullable', 'string'],
            'special_requirements' => ['nullable', 'string'],
        ]);

        $rowErrors = $this->proposalRequestContainerRowErrors($validated, 1);

        if ($rowErrors) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $rowErrors],
            ], 422);
        }

        $container = DB::transaction(function () use ($lead, $validated) {
            $container = $lead->requirementContainers()->create($validated);

            $lead->recomputeCompletion();

            return $container;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'container' => $container->load($this->proposalRequestContainerRelations()),
            ],
        ]);
    }

    /**
     * Removes one requirement container row - verifies it belongs to this
     * prospect before deleting so one prospect can't delete another's row
     * via a guessed id.
     */
    public function destroyProposalRequestContainer($uuid, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $container = $lead->requirementContainers()->where('id', $id)->firstOrFail();
        $container->delete();

        $lead->recomputeCompletion();

        return response()->json(['success' => true]);
    }

    /**
     * Appends ONE trucking requirement row directly to this prospect - same
     * shape as storeProposalRequestContainer().
     */
    public function storeProposalRequestTrucking(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'trucking_cargo_type' => ['nullable', 'string', 'max:255'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'frequency' => ['nullable', 'string', 'max:255'],
            'dispatch_mode' => ['nullable', 'in:single,tandem'],
            'origin_location_id' => ['nullable', 'integer', 'exists:locations,location_id'],
            'destination_location_id' => ['nullable', 'integer', 'exists:locations,location_id'],
            'declared_value_per_unit' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', 'in:kg,mt'],
            'cargo_type' => ['nullable', 'string'],
            'general_cargo_description' => ['nullable', 'string'],
            'special_requirements' => ['nullable', 'string'],
        ]);

        $rowErrors = $this->truckingRowErrors($validated, 1);

        if ($rowErrors) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $rowErrors],
            ], 422);
        }

        $trucking = DB::transaction(function () use ($lead, $validated) {
            $trucking = $lead->requirementTruckings()->create($validated);

            $lead->recomputeCompletion();

            return $trucking;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'trucking' => $trucking->load($this->proposalRequestTruckingRelations()),
            ],
        ]);
    }

    /**
     * Removes one requirement trucking row.
     */
    public function destroyProposalRequestTrucking($uuid, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $trucking = $lead->requirementTruckings()->where('id', $id)->firstOrFail();
        $trucking->delete();

        $lead->recomputeCompletion();

        return response()->json(['success' => true]);
    }

    /**
     * Creates ONE charter booking directly under this prospect, with its
     * nested cargo rows and port calls in a single transaction - unlike
     * containers/truckings, a charter isn't a flat row; it owns its own
     * repeatable cargo list and port list (see
     * ProposalRequestCharter::cargoItems()/ports()).
     */
    public function storeProposalRequestCharter(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'charter_start_date' => ['nullable', 'date'],
            'charter_end_date' => ['nullable', 'date', 'after_or_equal:charter_start_date'],
            'declared_value' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'weight_unit' => ['nullable', 'in:kg,mt'],

            'cargo' => ['nullable', 'array'],
            'cargo.*.cargo_type' => ['nullable', 'string'],
            'cargo.*.general_cargo_description' => ['nullable', 'string'],
            'cargo.*.special_requirements' => ['nullable', 'string'],

            'ports' => ['nullable', 'array'],
            'ports.*.port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
        ]);

        $rowErrors = $this->charterRowErrors($validated);

        if ($rowErrors) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $rowErrors],
            ], 422);
        }

        $charter = DB::transaction(function () use ($lead, $validated) {
            $charter = $lead->requirementCharters()->create([
                'charter_start_date' => $validated['charter_start_date'] ?? null,
                'charter_end_date' => $validated['charter_end_date'] ?? null,
                'declared_value' => $validated['declared_value'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'weight_unit' => $validated['weight_unit'] ?? null,
            ]);

            // Drop stray fully-blank rows (an "+ Add Cargo" click left
            // untouched) - only rows with at least a cargo type are real,
            // matching what charterRowErrors() already validated.
            foreach ($validated['cargo'] ?? [] as $cargo) {
                if (empty($cargo['cargo_type'])) {
                    continue;
                }
                $charter->cargoItems()->create($cargo);
            }

            // Same "drop stray blank rows" rule as cargo above.
            $realPorts = array_values(array_filter(
                $validated['ports'] ?? [],
                fn ($port) => ! empty($port['port_id'])
            ));
            foreach ($realPorts as $index => $port) {
                $charter->ports()->create([
                    'port_id' => $port['port_id'],
                    'sort_order' => $index,
                ]);
            }

            $lead->recomputeCompletion();

            return $charter;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'charter' => $charter->load($this->proposalRequestCharterRelations()),
            ],
        ]);
    }

    /**
     * Removes one requirement charter booking - cascades to its cargo rows
     * and port rows via the FK cascadeOnDelete().
     */
    public function destroyProposalRequestCharter($uuid, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        $charter = $lead->requirementCharters()->where('id', $id)->firstOrFail();
        $charter->delete();

        $lead->recomputeCompletion();

        return response()->json(['success' => true]);
    }

    /**
     * Starts a brand-new Proposal Request (fresh RQ code, status: pending -
     * i.e. awaiting CSR/RM assignment) - what the Proposal tab's "Request
     * for Proposal" button calls before opening the wizard. Deliberately
     * NOT a get-or-create - clicking this always starts something new,
     * independent of any other request already in progress for this
     * prospect.
     */
    public function storeProposalRequestNew(Request $request, $uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());

        $proposalRequest = DB::transaction(function () use ($lead, $request) {
            return ProposalRequest::create([
                'code' => ProposalRequest::generateNextCode(),
                'prospect_id' => $lead->id,
                'status' => 'pending',
                'created_by' => $request->user()?->id,
            ]);
        });

        $leadLabel = $lead->company?->company_name ?? $lead->contact_name;

        try {
            $this->activityService->create(
                $lead->id,
                'rfp_requested',
                'Requested a proposal ('.$proposalRequest->code.').',
                $request->user()?->id
            );
        } catch (\Throwable $e) {
            report($e);
        }

        TeamNotifier::notify(RoleHelper::userIdsWithAnyRole(['superadmin', 'admin']), [
            'type' => 'proposal_request.needs_assignment',
            'title' => 'Proposal request needs assignment',
            'message' => ($request->user()?->name ?? 'A user')." requested a proposal for {$leadLabel} ({$proposalRequest->code}).",
            'from_user_id' => $request->user()?->id,
            'notifiable' => $proposalRequest,
            'link' => ['title' => 'Assign', 'url' => '/page_proposal_requests'],
            'email_subject' => "Proposal request needs assignment — {$proposalRequest->code}",
        ]);

        return response()->json([
            'success' => true,
            'data' => $proposalRequest->load($this->proposalRequestRelations()),
        ]);
    }

    /**
     * Deletes a Proposal Request outright - only before it's been submitted
     * (status pending or assigned; the Proposal tab only ever shows the
     * Delete button on those cards, but this is enforced here too rather
     * than trusting the client). Cascades to this request's own data
     * (company details/signatories/
     * Origin & Destination locations/Products-tab rows) via each table's
     * cascadeOnDelete() FK. The original Requirements tab's Freight/
     * Trucking/Charter rows ("prospect products") are owned directly by
     * the prospect (prospect_id), not by any Proposal Request, so they're
     * never touched by this - no relocation or replacement row needed.
     */
    public function destroyProposalRequest(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        if (! in_array($proposalRequest->status, ['pending', 'assigned'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Only a request that hasn\'t been submitted yet can be deleted.',
            ], 422);
        }

        $proposalRequest->delete();

        return response()->json(['success' => true]);
    }

    /**
     * A specific Proposal Request, scoped to this prospect - used by the
     * wizard's own endpoints below, which always address an exact request
     * by id, since several requests (at any status) can exist for the
     * same prospect at once.
     */
    private function findProposalRequestFor(Prospect $lead, $proposalRequestId): ProposalRequest
    {
        return $lead->proposalRequests()->where('id', $proposalRequestId)->firstOrFail();
    }

    /**
     * Upserts the Company Details tab's single row (company name snapshot +
     * a live reference to one of the prospect's own addresses) - not an
     * append-one endpoint like the product rows above, since there's only
     * ever one per Proposal Request.
     */
    public function storeProposalRequestCompanyDetails(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'prospect_location_id' => [
                'nullable',
                'integer',
                Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id)),
            ],
        ]);

        $companyDetail = \App\Models\ProposalRequestCompanyDetail::updateOrCreate(
            ['proposal_request_id' => $proposalRequest->id],
            $validated
        );

        return response()->json([
            'success' => true,
            'data' => $companyDetail->load('prospectLocation'),
        ]);
    }

    /**
     * Appends ONE authorized signatory - a live reference to one of the
     * prospect's own contacts, not a re-entered name. "Add Authorized" can
     * be clicked repeatedly (multiple signatories per request).
     */
    public function storeProposalRequestSignatory(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'prospect_contact_id' => [
                'required',
                'integer',
                Rule::exists('prospect_contacts', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id)),
            ],
        ]);

        $signatory = $proposalRequest->signatories()->firstOrCreate($validated);

        return response()->json([
            'success' => true,
            'data' => $signatory->load('prospectContact'),
        ]);
    }

    /**
     * Removes one authorized signatory - verifies it belongs to this
     * specific Proposal Request before deleting.
     */
    public function destroyProposalRequestSignatory(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->signatories()->where('id', $id)->firstOrFail()->delete();

        return response()->json([
            'success' => true,
            'data' => $proposalRequest->fresh()->load($this->proposalRequestRelations()),
        ]);
    }

    /**
     * Appends ONE Origin & Destination entry - its own address snapshot
     * (the "Add" form is fully editable, so whatever's in it at Save time is
     * what gets stored), with an optional prospect_location_id kept only as
     * a "seeded from" audit reference. Duplicates (same seed address behind
     * more than one entry, or even identical snapshots) are legitimate here
     * - each Add always creates a new row, never dedupes.
     */
    public function storeProposalRequestLocation(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $validated = $request->validate([
            'prospect_location_id' => [
                'nullable',
                'integer',
                Rule::exists('prospect_locations', 'id')->where(fn ($q) => $q->where('prospect_id', $lead->id)),
            ],
            'address_type' => ['nullable', 'string', 'max:255'],
            'address_no' => ['nullable', 'string', 'max:100'],
            'address_building' => ['nullable', 'string', 'max:255'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'address_barangay' => ['nullable', 'string', 'max:255'],
            'address_town_city' => ['nullable', 'string', 'max:255'],
            'address_province' => ['nullable', 'string', 'max:255'],
            'address_country' => ['nullable', 'string', 'max:255'],
            'address_postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $location = $proposalRequest->locations()->create($validated);

        return response()->json([
            'success' => true,
            'data' => $location->load('prospectLocation'),
        ]);
    }

    /**
     * Removes one Origin & Destination location - verifies it belongs to
     * this specific Proposal Request before deleting.
     */
    public function destroyProposalRequestLocation(Request $request, $uuid, $proposalRequestId, $id)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        $proposalRequest->locations()->where('id', $id)->firstOrFail()->delete();

        return response()->json([
            'success' => true,
            'data' => $proposalRequest->fresh()->load($this->proposalRequestRelations()),
        ]);
    }

    /**
     * Final "Confirm & Submit Request" action on the wizard's Confirmation
     * tab - requires Company Details (name + location) and at least one
     * signatory and one Origin & Destination location. Products is
     * intentionally not required yet (still a placeholder tab). Only legal
     * from 'assigned' (CSR+RM already set - see assignOwners()); moves
     * status to for_approval - no further resubmission is expected, so
     * reaching this again (if it ever does) just overwrites the
     * timestamp/actor.
     */
    public function submitProposalRequest(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId)->load($this->proposalRequestRelations());

        $errors = [];

        if ($proposalRequest->status !== 'assigned') {
            $errors[] = 'This request must be assigned by Management before it can be submitted.';
        }

        $companyDetail = $proposalRequest->companyDetail;
        if (! $companyDetail || ! $companyDetail->company_name || ! $companyDetail->prospect_location_id) {
            $errors[] = 'Company Details (company name and location address) must be completed first.';
        }
        if ($proposalRequest->signatories->isEmpty()) {
            $errors[] = 'At least one Authorized Signatory is required.';
        }
        if ($proposalRequest->locations->isEmpty()) {
            $errors[] = 'At least one Origin & Destination location is required.';
        }

        if ($errors) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $errors),
                'errors' => $errors,
            ], 422);
        }

        $proposalRequest->status = 'for_approval';
        $proposalRequest->submitted_at = now();
        $proposalRequest->submitted_by = $request->user()?->id;
        $proposalRequest->save();

        try {
            $this->activityService->create(
                $lead->id,
                'rfp_submitted',
                'Proposal request '.$proposalRequest->code.' submitted for review.',
                $request->user()?->id
            );
        } catch (\Throwable $e) {
            report($e);
        }

        TeamNotifier::notify(RoleHelper::userIdsWithAnyRole(['superadmin', 'admin']), [
            'type' => 'proposal_request.submitted',
            'title' => 'Proposal request submitted',
            'message' => "{$proposalRequest->code} was submitted for review.",
            'from_user_id' => $request->user()?->id,
            'notifiable' => $proposalRequest,
            'link' => ['title' => 'View', 'url' => '/page_proposal_requests'],
            'email_subject' => "Proposal request submitted — {$proposalRequest->code}",
        ]);

        return response()->json([
            'success' => true,
            'data' => $proposalRequest->fresh()->load($this->proposalRequestRelations()),
        ]);
    }

    /**
     * Cancels a submitted (for_approval) Proposal Request - a soft terminal
     * state (status = 'cancelled'), NOT a hard delete. Only a for_approval
     * request can be cancelled (pending/assigned requests are hard-deleted
     * via destroyProposalRequest() instead; already-cancelled or otherwise
     * non-for_approval requests can't be re-cancelled). No approval workflow
     * is attached yet - roles are not finalized.
     */
    public function cancelProposalRequest(Request $request, $uuid, $proposalRequestId)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();
        $this->authorizeProspectOwnership($lead, $request->user());
        $this->authorizeProspectFullyAssigned($lead);
        $proposalRequest = $this->findProposalRequestFor($lead, $proposalRequestId);

        if (! in_array($proposalRequest->status, ['assigned', 'for_approval'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Only an assigned or pending-approval request can be cancelled.',
            ], 422);
        }

        $proposalRequest->status = 'cancelled';
        $proposalRequest->save();

        try {
            $this->activityService->create(
                $lead->id,
                'rfp_cancelled',
                'Proposal request '.$proposalRequest->code.' cancelled.',
                $request->user()?->id
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'success' => true,
            'data' => $proposalRequest->fresh()->load($this->proposalRequestRelations()),
        ]);
    }

    /**
     * Eager-load set to call on a ProposalRequest instance - namespaced
     * under `containers.*`.
     */
    private function proposalRequestRelations(): array
    {
        return [
            'companyDetail.prospectLocation',
            'signatories.prospectContact',
            'locations.prospectLocation',
        ];
    }

    /**
     * Same container lookups as proposalRequestRelations(), but bare (no
     * `containers.` prefix) for calling on a single
     * ProposalRequestContainer instance.
     */
    private function proposalRequestContainerRelations(): array
    {
        return [
            'deliveryType:delivery_type_id,code,name',
            'originLocation:location_id,name',
            'destinationLocation:location_id,name',
            'containerSize:id,size',
        ];
    }

    /**
     * Same shape as proposalRequestContainerRelations(), for a single
     * ProposalRequestTrucking instance.
     */
    private function proposalRequestTruckingRelations(): array
    {
        return [
            'originLocation:location_id,name',
            'destinationLocation:location_id,name',
        ];
    }

    /**
     * Same shape, for a single ProposalRequestCharter instance.
     */
    private function proposalRequestCharterRelations(): array
    {
        return [
            'cargoItems',
            'ports.port:port_id,location_id,name',
            'ports.port.location:location_id,name',
        ];
    }

    /**
     * A Prospect Requirements booking-requirement row is only meaningful
     * once its core fields are filled in - an all-blank row (added then
     * left untouched) must never be allowed to save silently. Which extra
     * fields count as "required" depends on the container type, mirroring
     * TYPE_FIELD_VISIBILITY in the prospect modal's JS. Simplified field
     * set vs. the legacy containerRowErrors() below (no ports/service
     * mode/dangerous goods - see the Prospect Requirements form brief).
     */
    private function proposalRequestContainerRowErrors(array $c, int $index): array
    {
        // CV/FR/RF are sized-container types; RF additionally needs a
        // minimum temperature. RC/LC have no fixed size and are priced by
        // Revenue Ton instead.
        $typeFlags = [
            'CV' => ['size' => true, 'temp' => false, 'revenueTon' => false],
            'FR' => ['size' => true, 'temp' => false, 'revenueTon' => false],
            'RF' => ['size' => true, 'temp' => true, 'revenueTon' => false],
            'LC' => ['size' => false, 'temp' => false, 'revenueTon' => true],
            'RC' => ['size' => false, 'temp' => false, 'revenueTon' => true],
        ];

        $errors = [];
        $type = $c['container_type'] ?? null;

        if (empty($type) || ! isset($typeFlags[$type])) {
            $errors[] = "Booking requirement #{$index}: container type is required.";

            return $errors;
        }

        $flags = $typeFlags[$type];

        if (empty($c['quantity']) || (int) $c['quantity'] < 1) {
            $errors[] = "Booking requirement #{$index}: quantity is required.";
        }
        if (empty($c['general_cargo_description'])) {
            $errors[] = "Booking requirement #{$index}: cargo description is required.";
        }
        if ($flags['size'] && empty($c['container_size_id'])) {
            $errors[] = "Booking requirement #{$index}: size is required.";
        }
        if ($flags['temp'] && ($c['minimum_temperature'] ?? null) === null) {
            $errors[] = "Booking requirement #{$index}: minimum temperature is required.";
        }
        if ($flags['revenueTon'] && ($c['revenue_ton'] ?? null) === null) {
            $errors[] = "Booking requirement #{$index}: revenue ton is required.";
        }
        if (empty($c['service_type'])) {
            $errors[] = "Booking requirement #{$index}: service type is required.";
        }
        if (empty($c['origin_location_id'])) {
            $errors[] = "Booking requirement #{$index}: origin is required.";
        }
        if (empty($c['destination_location_id'])) {
            $errors[] = "Booking requirement #{$index}: destination is required.";
        }

        return $errors;
    }

    /**
     * Same "no silent all-blank save" guard as proposalRequestContainerRowErrors()
     * above, for a trucking row.
     */
    private function truckingRowErrors(array $t, int $index): array
    {
        $errors = [];

        if (empty($t['trucking_cargo_type'])) {
            $errors[] = "Trucking requirement #{$index}: trucking cargo type is required.";
        }
        if (empty($t['quantity']) || (int) $t['quantity'] < 1) {
            $errors[] = "Trucking requirement #{$index}: quantity is required.";
        }
        if (empty($t['dispatch_mode'])) {
            $errors[] = "Trucking requirement #{$index}: dispatch mode is required.";
        }
        if (empty($t['origin_location_id'])) {
            $errors[] = "Trucking requirement #{$index}: origin is required.";
        }
        if (empty($t['destination_location_id'])) {
            $errors[] = "Trucking requirement #{$index}: destination is required.";
        }
        if (empty($t['general_cargo_description'])) {
            $errors[] = "Trucking requirement #{$index}: cargo description is required.";
        }

        return $errors;
    }

    /**
     * A charter booking needs its dates plus at least one real cargo row
     * and at least one real port - a "row" with every sub-field blank
     * (added then left untouched, same failure mode the container/trucking
     * guards above prevent) must not save silently either.
     */
    private function charterRowErrors(array $c): array
    {
        $errors = [];

        if (empty($c['charter_start_date'])) {
            $errors[] = 'Charter start date is required.';
        }
        if (empty($c['charter_end_date'])) {
            $errors[] = 'Charter end date is required.';
        }

        $cargo = collect($c['cargo'] ?? [])->filter(fn ($row) => ! empty($row['cargo_type']));
        if ($cargo->isEmpty()) {
            $errors[] = 'At least one cargo row (with a cargo type) is required.';
        }

        $ports = collect($c['ports'] ?? [])->filter(fn ($row) => ! empty($row['port_id']));
        if ($ports->isEmpty()) {
            $errors[] = 'At least one port is required.';
        }

        return $errors;
    }

    /**
     * Legacy row validation for prospect_containers_legacy (saveStage2()/
     * storeContainer() below) - currently unreachable from the frontend
     * (the modal that called storeContainer() was removed in the CRM
     * rebuild), kept only so that frozen legacy endpoint doesn't 500 if
     * ever hit directly. Not used by the Prospect Requirements tab.
     */
    private function containerRowErrors(array $c, int $index): array
    {
        // Reefer Van has no ConVan class field at all (hidden on the form) -
        // and every type now splits Service Mode into origin/destination,
        // Loose Cargo and Rolling Cargo included.
        $typeFlags = [
            'CV' => ['size' => true, 'temp' => false, 'split' => true],
            'FR' => ['size' => false, 'temp' => false, 'split' => true],
            'RF' => ['size' => false, 'temp' => true, 'split' => true],
            'LC' => ['size' => false, 'temp' => false, 'split' => true],
            'RC' => ['size' => false, 'temp' => false, 'split' => true],
        ];

        $errors = [];
        $type = $c['container_type'] ?? null;

        if (empty($type) || ! isset($typeFlags[$type])) {
            $errors[] = "Booking requirement #{$index}: container type is required.";

            return $errors;
        }

        $flags = $typeFlags[$type];

        if (empty($c['origin_port_id'])) {
            $errors[] = "Booking requirement #{$index}: origin port is required.";
        }
        if (empty($c['destination_port_id'])) {
            $errors[] = "Booking requirement #{$index}: destination port is required.";
        }
        if (empty($c['quantity']) || (int) $c['quantity'] < 1) {
            $errors[] = "Booking requirement #{$index}: quantity is required.";
        }
        // if (empty($c['frequency'])) {
        //     $errors[] = "Booking requirement #{$index}: frequency is required.";
        // }
        if (empty($c['general_cargo_description'])) {
            $errors[] = "Booking requirement #{$index}: cargo description is required.";
        }
        if ($flags['size'] && empty($c['container_size_id'])) {
            $errors[] = "Booking requirement #{$index}: ConVan size is required.";
        }
        if ($flags['temp'] && ($c['minimum_temperature'] ?? null) === null) {
            $errors[] = "Booking requirement #{$index}: minimum temperature is required.";
        }
        if ($flags['split']) {
            if (empty($c['service_mode_origin'])) {
                $errors[] = "Booking requirement #{$index}: service mode (origin) is required.";
            }
            if (empty($c['service_mode_destination'])) {
                $errors[] = "Booking requirement #{$index}: service mode (destination) is required.";
            }
        } elseif (empty($c['service_mode'])) {
            $errors[] = "Booking requirement #{$index}: service mode is required.";
        }

        return $errors;
    }

    /**
     * Uploads a single DG (dangerous goods) supporting document and
     * returns its stored path. The Stage 2 form calls this as soon as
     * a file is chosen for a container row, then submits the returned
     * path as `dg_documentary_requirement` in the normal stage2 payload.
     */
    public function uploadDgDocument(Request $request)
    {
        $validated = $request->validate([
            'dg_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ]);

        $paths = $this->fileUploadService->uploadFile(
            [$validated['dg_document']],
            'uploads/crm/dg-documents'
        );

        return response()->json([
            'success' => true,
            'data' => ['path' => $paths[0] ?? null],
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $lead = Prospect::create([

                ...Prospect::splitFullName($request->contact_name),
                'email' => $request->email,
                'mobile' => $request->mobile,
                'position' => $request->position ?? null,
                'status' => $request->status,
                'source' => $request->source,
                'assigned_to' => auth()->id(),
                'estimated_value' => $request->est_value,
                'expected_close_date' => Carbon::now()->addWeek(),
                'status_updated_at' => now(),
            ]);

            ProspectCompanyInfo::create([
                'prospect_id' => $lead->id,
                'company_name' => $request->company_name,
            ]);
            if (isset($request->notes)) {

                ProspectNote::create([
                    'prospect_id' => $lead->id,
                    'note' => $request->notes,
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data' => $lead->load('company', 'notes'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Relation suffixes (everything after "proposalRequest.") needed for
     * a full Proposal Request tree - reused under BOTH the singular
     * "currently being worked on" accessor (proposalRequest.*, still read by
     * the original Requirements tab) and the plural proposalRequests.* (every
     * request, for the Proposal tab's card list, at any status) so
     * the two eager-load sets can never drift apart.
     */
    private function proposalRequestEagerLoadSuffixes(): array
    {
        return [
            'companyDetail.prospectLocation',
            'signatories.prospectContact',
            'locations.prospectLocation',
            'productContainers.containerSize:id,size',
            'productContainers.deliveryType:delivery_type_id,code,name',
            'productContainers.originLocation',
            'productContainers.destinationLocation',
            'productContainers.originPort:port_id,location_id,name',
            'productContainers.destinationPort:port_id,location_id,name',
            'productContainers.ancillaryServices.cargoYard',
            'productRollingCargo.deliveryType:delivery_type_id,code,name',
            'productRollingCargo.originLocation',
            'productRollingCargo.destinationLocation',
            'productRollingCargo.originPort:port_id,location_id,name',
            'productRollingCargo.destinationPort:port_id,location_id,name',
            'productRollingCargo.ancillaryServices.cargoYard',
            'productRollingCargo.topLoadCargo',
            'productLooseCargo.deliveryType:delivery_type_id,code,name',
            'productLooseCargo.originLocation',
            'productLooseCargo.destinationLocation',
            'productLooseCargo.originPort:port_id,location_id,name',
            'productLooseCargo.destinationPort:port_id,location_id,name',
            'productLooseCargo.ancillaryServices.cargoYard',
            'productTruckings.originLocation',
            'productTruckings.destinationLocation',
            'productTruckings.ancillaryServices.cargoYard',
            'productCharters.cargoItems',
            'productCharters.ports.port:port_id,location_id,name',
            'productCharters.ports.port.location:location_id,name',
        ];
    }

    public function show($uuid)
    {
        $prSuffixes = $this->proposalRequestEagerLoadSuffixes();

        $lead = Prospect::with(array_merge(
            [
                'company',
                'addresses',
                'clientMaster:id,prospect_id,customer_code',
                'notes.user',
                'activities.user',
                'crmStatus:id,status',
                'user',
                'relationshipManager:id,name',
                'containers.originPort:port_id,location_id,name',
                'containers.originPort.location:location_id,name',
                'containers.destinationPort:port_id,location_id,name',
                'containers.destinationPort.location:location_id,name',
                'containers.containerClass:id,class',
                'containers.containerSize:id,size',
                'contacts.channels',
                'contacts.addresses',
                // "Prospect products" - owned directly by the prospect,
                // independent of any Proposal Request (see
                // Prospect::requirementContainers()/etc.).
                'requirementContainers.deliveryType:delivery_type_id,code,name',
                'requirementContainers.originLocation:location_id,name',
                'requirementContainers.destinationLocation:location_id,name',
                'requirementContainers.containerSize:id,size',
                'requirementTruckings.originLocation:location_id,name',
                'requirementTruckings.destinationLocation:location_id,name',
                'requirementCharters.cargoItems',
                'requirementCharters.ports.port:port_id,location_id,name',
                'requirementCharters.ports.port.location:location_id,name',
            ],
            array_map(fn ($s) => "proposalRequest.{$s}", $prSuffixes),
            array_map(fn ($s) => "proposalRequests.{$s}", $prSuffixes),
            [
                'clientProposals',
                'proposalRequests.clientProposals:id,uuid,code,status,proposal_request_id,signed_document_path',
            ]
        ))->where('uuid', $uuid)->firstOrFail();

        // Computed once here (not on Prospect itself) so the paginated
        // /api/crm/prospects listing doesn't take an extra query per row.
        $lead->setAttribute('has_accepted_proposal', $lead->hasAcceptedProposal());
        $lead->setAttribute('can_convert_to_client', $lead->canConvertToClient());
        $lead->setAttribute('stage_completion', $lead->stageCompletionFlags());

        // Flatten each contact's addresses into a plain location_ids list
        // for the frontend, mirroring the shape it submits to
        // saveContacts().
        $lead->contacts->each(function ($contact) {
            $contact->setAttribute('location_ids', $contact->addresses->pluck('prospect_location_id')->values());
        });

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    /**
     * Reserves (or returns the already-reserved) customer code for this
     * lead, so it's locked/stable across the whole "Create Client Master"
     * flow - called before opening the client master form, from both the
     * Lead Info Modal's "+ Record" button and the Proposals page's
     * "Create Client Master" action.
     */
    public function getOrGenerateCustomerCode($uuid)
    {
        $lead = Prospect::where('uuid', $uuid)->firstOrFail();

        if (! $lead->customer_code) {
            $lead->customer_code = \App\Models\ClientMaster::generateNextCustomerCode();
            $lead->save();
        }

        return response()->json([
            'success' => true,
            'data' => ['customer_code' => $lead->customer_code],
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $updatePayload = [
            ...Prospect::splitFullName($request->contact_name),
            'email' => $request->contact_email,
            'mobile' => $request->contact_mobile,
        ];
        try {
            DB::beginTransaction();

            Prospect::where('uuid', $uuid)->firstOrFail()->update($updatePayload);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }

        return response()->json([

            'success' => true,
            'message' => 'updated!',
        ]);
    }

    public function destroy($id)
    {
        Prospect::findOrFail($id)->delete();

        return response()->json([

            'success' => true,
            'message' => 'Deleted',
        ]);
    }
}
