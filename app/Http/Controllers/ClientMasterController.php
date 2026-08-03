<?php

namespace App\Http\Controllers;

use App\Models\ClientMaster;
use App\Models\ClientProposal;
use App\Models\CrmLead;
use App\Models\CrmStatus;
use App\Services\TeamService;
use App\Support\RoleHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClientMasterController extends Controller
{
    public function index(Request $request)
    {
        // Team-scoped visibility, same rule as CRM leads/Proposals: a member
        // only sees their own clients, a team leader sees their subtree,
        // superadmin sees everything.
        $visibleUserIds = RoleHelper::hasAnyRole($request->user(), ['superadmin'])
            ? null
            : TeamService::accessibleUserIds($request->user())->all();

        $clients = ClientMaster::query()
            ->select(
                'id',
                'uuid',
                'customer_code',
                'company_name',
                'industry',
                'current_stage',
                'is_complete',
                'sales_rep_id',
                'lead_id',
                'created_at'
            )
            ->with('salesRep:id,name')
            ->visibleTo($visibleUserIds)
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($q) use ($s) {
                    $q->where('company_name', 'like', "%{$s}%")
                        ->orWhere('customer_code', 'like', "%{$s}%");
                });
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
                $q->where('is_complete', $request->status === 'complete');
            })
            ->orderByDesc('updated_at')
            ->paginate($request->get('per_page', 25))
            ->appends($request->query());

        return response()->json([
            'success' => true,
            'data' => $clients,
            'counts' => [
                'all' => ClientMaster::visibleTo($visibleUserIds)->count(),
                'complete' => ClientMaster::visibleTo($visibleUserIds)->where('is_complete', true)->count(),
                'incomplete' => ClientMaster::visibleTo($visibleUserIds)->where('is_complete', false)->count(),
            ],
        ]);
    }

    public function show($uuid)
    {
        $client = ClientMaster::with([
            'contacts.addresses',
            'tradeReferences',
            'finance',
            'salesRep:id,name',
            'accountManager:id,name',
            'addresses',
            'ancillaryServices',
        ])
            ->where('uuid', $uuid)->firstOrFail();

        $client->setAttribute('primary_address_text', $client->formattedPrimaryAddress());

        return response()->json(['success' => true, 'data' => $client]);
    }

    /**
     * Stage 1 - create (no uuid yet) or update company info.
     */
    public function saveStage1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => ['nullable', 'exists:client_masters,uuid'],
            'lead_id' => ['nullable', 'exists:crm_leads,id'],
            'customer_code' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'client_mnemonic' => [
                'required',
                'string',
                'max:255',
                Rule::unique('client_masters', 'client_mnemonic')->ignore($request->input('uuid'), 'uuid'),
            ],
            'industry' => ['nullable', 'string', 'max:255'],
            'client_category' => ['nullable', 'string', 'max:255'],
            'client_classification' => ['nullable', 'string', 'max:255'],

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
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $client = DB::transaction(function () use ($data) {

            $client = ! empty($data['uuid'])
                ? ClientMaster::where('uuid', $data['uuid'])->firstOrFail()
                : new ClientMaster([
                    'uuid' => (string) Str::uuid(),
                ]);

            $isNew = ! $client->exists;

            $lead = null;

            if ($isNew) {
                if (! empty($data['lead_id'])) {
                    $client->lead_id = $data['lead_id'];
                    $lead = CrmLead::find($data['lead_id']);
                }

                // The code is either already reserved on the lead (locked
                // there the moment the "Create Client Master" flow was
                // opened - see CrmLeadController::getOrGenerateCustomerCode)
                // or generated fresh right here for a lead-less client.
                $client->customer_code = $lead?->customer_code
                    ?? $data['customer_code']
                    ?? ClientMaster::generateNextCustomerCode();

                $client->sales_rep_id = auth()->id();
                $client->created_by = auth()->id();

                // One-time snapshot of the CSR's team leader at creation
                // time - not a live-updating relation, so a later change of
                // leader/team doesn't silently move existing clients.
                $csr = auth()->user();
                $leader = \App\Models\User::where('team_id', $csr->team_id)->where('is_team_leader', true)->first();
                $client->account_manager_id = $leader?->id;
            }

            $client->fill(
                collect($data)
                    ->except(['uuid', 'lead_id', 'customer_code', 'addresses'])
                    ->toArray()
            );

            $client->current_stage = max($client->current_stage ?? 1, 1);

            $client->save();

            $client->addresses()->delete();
            $addresses = $data['addresses'];
            if (! collect($addresses)->contains(fn ($a) => ! empty($a['is_primary']))) {
                $addresses[0]['is_primary'] = true;
            }
            foreach ($addresses as $address) {
                $client->addresses()->create($address);
            }

            $client->recomputeCompletion();

            // A Client Master record existing at all - even incomplete - is
            // the deal being won; the lead doesn't wait for stage 2/3 to
            // reflect that.
            if ($isNew && $lead) {
                $winStatus = CrmStatus::where('status', 'WIN')->first();

                if ($winStatus) {
                    $lead->update([
                        'status' => $winStatus->id,
                        'status_updated_at' => now(),
                    ]);
                }

                // Any proposal created while this was still just a lead
                // (client_id null, lead_id set) now belongs to the client
                // it just became - otherwise it stays invisible to every
                // client-scoped proposal list (Client Master modal) despite
                // still showing up on the global Proposals page.
                ClientProposal::where('lead_id', $lead->id)
                    ->whereNull('client_id')
                    ->update(['client_id' => $client->id]);
            }

            return $client;
        });

        return response()->json([
            'success' => true,
            'data' => $client->load('addresses'),
        ]);
    }

    /**
     * Stage 2 - replace contacts + trade references wholesale (simple strategy,
     * matches CompanyController's delete-and-reinsert approach).
     */
    public function saveStage2(Request $request, $uuid)
    {
        $client = ClientMaster::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'contacts' => ['nullable', 'array'],
            'contacts.*.contact_department' => ['nullable', 'string', 'max:255'],
            'contacts.*.title' => ['nullable', 'string', 'max:255'],
            'contacts.*.first_name' => ['nullable', 'string', 'max:255'],
            'contacts.*.last_name' => ['nullable', 'string', 'max:255'],
            'contacts.*.gender' => ['nullable', 'string', 'max:255'],
            'contacts.*.position' => ['nullable', 'string', 'max:255'],
            'contacts.*.landline_number' => ['nullable', 'string', 'max:255'],
            'contacts.*.landline_type' => ['nullable', 'in:personal,business'],
            'contacts.*.mobile' => ['nullable', 'string', 'max:255'],
            'contacts.*.mobile_type' => ['nullable', 'in:personal,business'],
            'contacts.*.email' => ['nullable', 'email', 'max:255'],
            'contacts.*.email_type' => ['nullable', 'in:personal,business'],

            'contacts.*.addresses' => ['nullable', 'array'],
            'contacts.*.addresses.*.address_type' => ['nullable', 'string', 'max:255'],
            'contacts.*.addresses.*.is_primary' => ['nullable', 'boolean'],
            'contacts.*.addresses.*.address_no' => ['nullable', 'string', 'max:100'],
            'contacts.*.addresses.*.address_building' => ['nullable', 'string', 'max:255'],
            'contacts.*.addresses.*.address_street' => ['nullable', 'string', 'max:255'],
            'contacts.*.addresses.*.address_barangay' => ['nullable', 'string', 'max:255'],
            'contacts.*.addresses.*.address_town_city' => ['nullable', 'string', 'max:255'],
            'contacts.*.addresses.*.address_province' => ['nullable', 'string', 'max:255'],
            'contacts.*.addresses.*.address_country' => ['nullable', 'string', 'max:255'],
            'contacts.*.addresses.*.address_postal_code' => ['nullable', 'string', 'max:20'],

            'trade_references' => ['nullable', 'array'],
            'trade_references.*.business_name' => ['nullable', 'string', 'max:255'],
            'trade_references.*.relationship' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_name' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_phone' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_mobile' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_email' => ['nullable', 'email', 'max:255'],
        ]);

        DB::transaction(function () use ($client, $validated) {
            // Deleting a contact cascades to its client_contact_addresses
            // rows (FK cascadeOnDelete) - no separate address cleanup needed.
            $client->contacts()->delete();
            foreach ($validated['contacts'] ?? [] as $c) {
                $addresses = $c['addresses'] ?? [];
                unset($c['addresses']);
                if (array_filter($c)) {
                    $contact = $client->contacts()->create($c);
                    foreach ($addresses as $a) {
                        if (array_filter($a)) {
                            $contact->addresses()->create($a);
                        }
                    }
                }
            }

            $client->tradeReferences()->delete();
            foreach ($validated['trade_references'] ?? [] as $t) {
                if (array_filter($t)) {
                    $client->tradeReferences()->create($t);
                }
            }

            $client->current_stage = max($client->current_stage, 2);
            $client->save();
            $client->recomputeCompletion();
        });

        return response()->json(['success' => true, 'data' => $client->load('contacts.addresses', 'tradeReferences')]);
    }

    /**
     * Stage 3 - finance, billing, sales rep.
     */
    public function saveStage3(Request $request, $uuid)
    {
        $client = ClientMaster::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'finance.client_business_name' => ['nullable', 'string', 'max:255'],
            'finance.tin_number' => ['nullable', 'string', 'max:255'],
            'finance.tin_registered_address' => ['nullable', 'string'],
            'finance.tax_status' => ['nullable', 'string', 'max:255'],
            'finance.withholding_tax_code' => ['nullable', 'string', 'max:255'],
            'finance.trade_name' => ['nullable', 'string', 'max:255'],
            'finance.tin_registration_date' => ['nullable', 'date'],
            'finance.line_of_business' => ['nullable', 'string', 'max:255'],
            'finance.tax_percent' => ['nullable', 'numeric'],
            'finance.withholding_tax_percent' => ['nullable', 'numeric'],
            'finance.credit_terms' => ['nullable', 'string', 'max:255'],
            'finance.mode_of_payment' => ['nullable', 'string', 'max:255'],
            'finance.cro_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'finance.max_declared_value' => ['nullable', 'numeric'],
        ]);

        DB::transaction(function () use ($client, $validated) {

            if (! empty($validated['finance'])) {
                $client->finance()->updateOrCreate(
                    ['client_id' => $client->id],
                    $validated['finance']
                );
            }

            $client->current_stage = max($client->current_stage, 3);
            $client->save();

            $client->recomputeCompletion();
            // Lead status is already flipped to WIN as soon as the Client
            // Master record was first created (saveStage1) - nothing further
            // to do here.
        });

        $client->load([
            'contacts.addresses',
            'tradeReferences',
            'finance',
            'salesRep',
            'accountManager',
            'ancillaryServices',
        ]);

        return response()->json([
            'success' => true,
            'data' => $client,
        ]);
    }

    /**
     * Stage 4 - ancillary services. Optional (zero rows is a valid save) -
     * stageCompletionFlags() always marks this stage complete.
     */
    public function saveStage4(Request $request, $uuid)
    {
        $client = ClientMaster::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'ancillary_services' => ['nullable', 'array'],
            'ancillary_services.*.required_service' => ['nullable', 'string', 'max:255'],
            'ancillary_services.*.location' => ['nullable', 'string', 'max:255'],
            'ancillary_services.*.unit' => ['nullable', 'string', 'max:255'],
            'ancillary_services.*.unit_rate_vat_ex' => ['nullable', 'numeric'],
            'ancillary_services.*.mode_of_payment' => ['nullable', 'string', 'max:255'],
            'ancillary_services.*.remarks' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($client, $validated) {
            $snapshotBusinessName = $client->finance?->client_business_name ?: $client->company_name;

            $client->ancillaryServices()->delete();

            foreach ($validated['ancillary_services'] ?? [] as $row) {
                if (array_filter($row)) {
                    // Snapshot columns are server-set from the parent client,
                    // ignoring any values the client may have sent for them.
                    $client->ancillaryServices()->create(array_merge($row, [
                        'client_code' => $client->customer_code,
                        'client_mnemonic' => $client->client_mnemonic,
                        'client_business_name' => $snapshotBusinessName,
                    ]));
                }
            }

            $client->current_stage = max($client->current_stage, 4);
            $client->save();
            $client->recomputeCompletion();
        });

        return response()->json(['success' => true, 'data' => $client->load('ancillaryServices')]);
    }

    public function destroy($uuid)
    {
        ClientMaster::where('uuid', $uuid)->firstOrFail()->delete();

        return response()->json(['success' => true, 'message' => 'Client master data deleted']);
    }
}
