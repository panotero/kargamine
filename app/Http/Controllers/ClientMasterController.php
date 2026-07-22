<?php

namespace App\Http\Controllers;

use App\Models\ClientMaster;
use App\Models\CrmLead;
use App\Models\CrmStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClientMasterController extends Controller
{
    public function index(Request $request)
    {
        $clients = ClientMaster::query()
            ->select(
                'id',
                'uuid',
                'customer_code',
                'company_name',
                'industry',
                'current_stage',
                'is_complete',
                'created_at'
            )
            ->with('salesRep:id,name')
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
                'all' => ClientMaster::count(),
                'complete' => ClientMaster::where('is_complete', true)->count(),
                'incomplete' => ClientMaster::where('is_complete', false)->count(),
            ],
        ]);
    }

    public function show($uuid)
    {
        $client = ClientMaster::with(['contacts', 'tradeReferences', 'finance', 'billing', 'salesRep', 'addresses'])
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
            'contact_number_1' => ['nullable', 'string', 'max:255'],
            'contact_number_2' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:255'],
            'tin' => ['nullable', 'string', 'max:255'],
            'business_start_date' => ['nullable', 'date'],
            'estimated_annual_revenue' => ['nullable', 'numeric'],
            'company_url' => ['nullable', 'string', 'max:255'],

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
            'contacts.*.contact_name' => ['nullable', 'string', 'max:255'],
            'contacts.*.contact_number' => ['nullable', 'string', 'max:255'],
            'contacts.*.contact_number_type' => ['nullable', 'in:mobile,landline'],
            'contacts.*.contact_email' => ['nullable', 'email', 'max:255'],
            'contacts.*.contact_email_type' => ['nullable', 'in:personal,business'],
            'contacts.*.role' => ['nullable', 'string', 'max:255'],
            'contacts.*.position' => ['nullable', 'string', 'max:255'],

            'trade_references' => ['nullable', 'array'],
            'trade_references.*.business_name' => ['nullable', 'string', 'max:255'],
            'trade_references.*.relationship' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_name' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_phone' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_mobile' => ['nullable', 'string', 'max:255'],
            'trade_references.*.contact_person_email' => ['nullable', 'email', 'max:255'],
        ]);

        DB::transaction(function () use ($client, $validated) {
            $client->contacts()->delete();
            foreach ($validated['contacts'] ?? [] as $c) {
                if (array_filter($c)) {
                    $client->contacts()->create($c);
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

        return response()->json(['success' => true, 'data' => $client->load('contacts', 'tradeReferences')]);
    }

    /**
     * Stage 3 - finance, billing, sales rep.
     */
    public function saveStage3(Request $request, $uuid)
    {
        $client = ClientMaster::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'finance.credit_terms' => ['nullable', 'string', 'max:255'],
            'finance.payment_mode' => ['nullable', 'string', 'max:255'],
            'finance.standard_billing_service' => ['nullable', 'boolean'],
            'finance.invoice_submission' => ['nullable', 'in:electronic,courier'],
            'finance.invoice_email_address' => ['nullable', 'email', 'max:255'],
            'finance.invoice_courier_recipient' => ['nullable', 'string', 'max:255'],
            'finance.invoice_courier_contact' => ['nullable', 'string', 'max:255'],
            'finance.invoice_courier_address' => ['nullable', 'string'],
            'finance.payment_method' => ['nullable', 'in:check_pickup,direct_remittance'],
            'finance.check_pickup_address' => ['nullable', 'string'],
            'finance.bank_name' => ['nullable', 'string', 'max:255'],
            'finance.bank_account_number' => ['nullable', 'string', 'max:255'],
            'finance.document_handling' => ['nullable', 'boolean'],
            'finance.billing_summary_report' => ['nullable', 'boolean'],
            'finance.other_requests' => ['nullable', 'string'],

            'billing.billed_to' => ['nullable', 'string', 'max:255'],
            'billing.company_name' => ['nullable', 'string', 'max:255'],
            'billing.address' => ['nullable', 'string'],
            'billing.tin' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($client, $validated) {

            if (! empty($validated['finance'])) {
                $client->finance()->updateOrCreate(
                    ['client_id' => $client->id],
                    $validated['finance']
                );
            }

            if (! empty($validated['billing'])) {
                $client->billing()->updateOrCreate(
                    ['client_id' => $client->id],
                    $validated['billing']
                );
            }

            $client->current_stage = 3;
            $client->is_complete = true;
            $client->save();

            $client->recomputeCompletion();
            // Lead status is already flipped to WIN as soon as the Client
            // Master record was first created (saveStage1) - nothing further
            // to do here.
        });

        $client->load([
            'contacts',
            'tradeReferences',
            'finance',
            'billing',
            'salesRep',
        ]);

        return response()->json([
            'success' => true,
            'data' => $client,
        ]);
    }

    public function destroy($uuid)
    {
        ClientMaster::where('uuid', $uuid)->firstOrFail()->delete();

        return response()->json(['success' => true, 'message' => 'Client master data deleted']);
    }
}
