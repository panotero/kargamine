<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Models\CrmCompanyInfo;
use App\Models\CrmNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\CrmLeadContainer;
use Illuminate\Support\Facades\Validator;
use App\Services\FileUploadService;

class CrmLeadController extends Controller
{


    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    //
    public function index(Request $request)
    {
        $leads = CrmLead::query()
            ->select(
                'id',
                'uuid',
                'contact_name',
                'email',
                'mobile',
                'status',
                'assigned_to',
                'created_at',
                'updated_at'
            )
            ->with([
                'company:id,lead_id,company_name',
                'crmStatus:id,status',
                'user:id,name',
            ])

            // Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($q) use ($search) {
                    $q->where('contact_name', 'like', "%{$search}%")
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

            ->orderByDesc('updated_at')
            ->paginate($request->get('per_page', 25))
            ->appends($request->query());

        $allLeads = CrmLead::with('crmStatus')->get();

        $statusCounts = $allLeads
            ->groupBy(fn($lead) => optional($lead->crmStatus)->status)
            ->map(fn($group) => $group->count());

        return response()->json([
            'success' => true,
            'data' => $leads,
            'status_counts' => [
                'ALL' => $allLeads->count(),
                'LEAD' => $statusCounts->get('LEAD', 0),
                'QUALIFIED' => $statusCounts->get('QUALIFIED', 0),
                'OPPORTUNITY' => $statusCounts->get('OPPORTUNITY', 0),
                'NEGOTIATION' => $statusCounts->get('NEGOTIATION', 0),
                'WIN' => $statusCounts->get('WIN', 0),
                'LOST' => $statusCounts->get('LOST', 0),
            ],
        ]);
    }


    public function saveStage1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => ['nullable', 'string'],
            'contact_name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['required', 'string', 'max:255'],

            'company_name' => ['nullable', 'string', 'max:255'],
            'address_no' => ['nullable', 'string', 'max:100'],
            'address_building' => ['nullable', 'string', 'max:255'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'address_barangay' => ['nullable', 'string', 'max:255'],
            'address_town_city' => ['nullable', 'string', 'max:255'],
            'address_province' => ['nullable', 'string', 'max:255'],
            'address_country' => ['nullable', 'string', 'max:255'],
            'address_postal_code' => ['nullable', 'string', 'max:20'],
            'type_of_business' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $lead = DB::transaction(function () use ($data) {
            $lead = !empty($data['uuid'])
                ? CrmLead::where('uuid', $data['uuid'])->firstOrFail()
                : new CrmLead(['uuid' => (string) Str::uuid()]);

            $isNew = !$lead->exists;

            $lead->fill([
                'contact_name' => $data['contact_name'],
                'position' => $data['position'] ?? null,
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?? null,
                'source' => $data['source'],
            ]);

            if ($isNew) {
                $lead->assigned_to = auth()->id();
                $lead->status = \App\Models\CrmStatus::where('status', 'LEAD')->first()?->id ?? 1;
                $lead->status_updated_at = now();
            }

            $lead->current_stage = max($lead->current_stage ?? 1, 1);
            $lead->save();

            $companyPayload = collect($data)->only([
                'company_name',
                'address_no',
                'address_building',
                'address_street',
                'address_barangay',
                'address_town_city',
                'address_province',
                'address_country',
                'address_postal_code',
                'type_of_business',
            ])->toArray();

            \App\Models\CrmCompanyInfo::updateOrCreate(
                ['lead_id' => $lead->id],
                $companyPayload
            );

            $lead->recomputeCompletion();

            return $lead;
        });

        return response()->json(['success' => true, 'data' => $lead->load('company')]);
    }

    public function saveStage2(Request $request, $uuid)
    {
        $lead = CrmLead::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'containers' => ['nullable', 'array', 'min:1'],
            'containers.*.container_type' => ['nullable', 'in:CV,FR,RF,LC,RC'],
            'containers.*.origin_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'containers.*.destination_port_id' => ['nullable', 'integer', 'exists:ports,port_id'],
            'containers.*.booking_unit_type' => ['nullable', 'string', 'max:255'],
            'containers.*.container_class_id' => ['nullable', 'integer', 'exists:container_class,id'],
            'containers.*.container_size_id' => ['nullable', 'integer', 'exists:container_size,id'],
            'containers.*.required_temperature' => ['nullable', 'numeric'],
            'containers.*.quantity' => ['nullable', 'integer', 'min:0'],
            'containers.*.estimated_cbm' => ['nullable', 'numeric', 'min:0'],
            'containers.*.estimated_ton' => ['nullable', 'numeric', 'min:0'],
            'containers.*.declared_value_per_unit' => ['nullable', 'numeric', 'min:0'],
            'containers.*.frequency' => ['nullable', 'string', 'max:255'],
            'containers.*.general_cargo_description' => ['nullable', 'string'],
            'containers.*.service_mode_origin' => ['nullable', 'in:PIER,DOOR'],
            'containers.*.service_mode_destination' => ['nullable', 'in:PIER,DOOR'],
            'containers.*.service_mode' => ['nullable', 'in:PIER,DOOR'],
            'containers.*.dangerous_cargo' => ['nullable', 'boolean'],
            'containers.*.dg_documentary_requirement' => ['nullable', 'string', 'max:255'],
            'containers.*.special_requirements' => ['nullable', 'string'],
            'containers.*.special_notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($lead, $validated) {
            // Simple replace strategy - same as your ClientMaster stage2.
            $lead->containers()->delete();

            foreach ($validated['containers'] as $c) {
                $lead->containers()->create($c);
            }

            $lead->current_stage = max($lead->current_stage, 2);
            $lead->save();
            $lead->recomputeCompletion();
        });

        return response()->json(['success' => true, 'data' => $lead->fresh()->load('containers')]);
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
            $lead = CrmLead::create([

                'contact_name' => $request->contact_name,
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

            CrmCompanyInfo::create([
                'lead_id' => $lead->id,
                'company_name' => $request->company_name,
            ]);
            if (isset($request->notes)) {

                CrmNote::create([
                    'lead_id' => $lead->id,
                    'note' => $request->notes,
                    'created_by' => auth()->id(),
                ]);
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully',
                'data' => $lead->load('company', 'notes')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create lead',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($uuid)
    {
        $lead = CrmLead::with(
            'company',
            'notes.user',
            'activities.user',
            'crmStatus:id,status',
            'user',
            'proposals.status',
            'containers.originPort:port_id,code,name',
            'containers.destinationPort:port_id,code,name',
            'containers.containerClass:id,class',
            'containers.containerSize:id,size'
        )->where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $updatePayload = [
            'contact_name' => $request->contact_name,
            'email' => $request->contact_email,
            'mobile' => $request->contact_mobile,
        ];
        try {
            DB::beginTransaction();

            CrmLead::where('uuid', $uuid)->firstOrFail()->update($updatePayload);
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
            'message' => 'updated!'
        ]);
    }

    public function destroy($id)
    {
        CrmLead::findOrFail($id)->delete();
        return response()->json([

            'success' => true,
            'message' => 'Deleted'
        ]);
    }
}
