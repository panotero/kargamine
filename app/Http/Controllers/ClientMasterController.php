<?php


namespace App\Http\Controllers;

use App\Models\ClientMaster;
use App\Models\ClientContact;
use App\Models\ClientTradeReference;
use App\Models\ClientFinance;
use App\Models\ClientBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $client = ClientMaster::with(['contacts', 'tradeReferences', 'finance', 'billing', 'salesRep'])
            ->where('uuid', $uuid)->firstOrFail();

        return response()->json(['success' => true, 'data' => $client]);
    }

    /**
     * Stage 1 - create (no uuid yet) or update company info.
     */
    public function saveStage1(Request $request)
    {
        $validated = $request->validate([
            'uuid' => ['nullable', 'string'],
            'customer_code' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'registered_address' => ['nullable', 'string'],
            'contact_number_1' => ['nullable', 'string', 'max:50'],
            'contact_number_2' => ['nullable', 'string', 'max:50'],
            'industry' => ['nullable', 'string', 'max:255'],
            'organization_type' => ['nullable', 'string', 'max:255'],
            'tin' => ['nullable', 'string', 'max:255'],
            'business_start_date' => ['nullable', 'date'],
            'estimated_annual_revenue' => ['nullable', 'numeric'],
            'company_url' => ['nullable', 'url'],
        ]);

        $client = DB::transaction(function () use ($validated, $request) {
            $client = !empty($validated['uuid'])
                ? ClientMaster::where('uuid', $validated['uuid'])->firstOrFail()
                : new ClientMaster(['uuid' => (string) Str::uuid(), 'created_by' => $request->user()?->id]);

            unset($validated['uuid']);
            $client->fill($validated);
            $client->current_stage = max($client->current_stage ?? 1, 1);
            $client->save();
            $client->recomputeCompletion();

            return $client;
        });

        return response()->json(['success' => true, 'data' => $client], 201);
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
            'contacts.*.contact_email' => ['nullable', 'email', 'max:255'],
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
            'sales_rep_id' => ['nullable', 'integer', 'exists:users,id'],

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
            $client->sales_rep_id = $validated['sales_rep_id'] ?? $client->sales_rep_id;
            $client->current_stage = 3;
            $client->save();

            if (!empty($validated['finance'])) {
                $client->finance()->updateOrCreate(['client_id' => $client->id], $validated['finance']);
            }

            if (!empty($validated['billing'])) {
                $client->billing()->updateOrCreate(['client_id' => $client->id], $validated['billing']);
            }

            $client->recomputeCompletion();
        });

        return response()->json(['success' => true, 'data' => $client->load('finance', 'billing')]);
    }

    public function destroy($uuid)
    {
        ClientMaster::where('uuid', $uuid)->firstOrFail()->delete();

        return response()->json(['success' => true, 'message' => 'Client master data deleted']);
    }
}
