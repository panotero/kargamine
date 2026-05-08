<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyInfoMaster;
use App\Models\ContactInfo;
use App\Models\TradeReference;
use App\Models\ServicesInfo;
use App\Models\CompanyFinance;
use App\Models\BilledDetail;
use App\Models\SalesInfo;
use App\Models\StagesInfo;
use App\Services\CustomerValidationService;
use Illuminate\Validation\ValidationException;
use App\Models\EInvoice;
use App\Models\CourierInvoice;

class CompanyController extends Controller
{
    /**
     * GET /companies
     */
    public function index()
    {
        $data = CompanyInfoMaster::with([
            'contacts',
            'tradeReferences',
            'services',
            'finance',
            'billing',
            'sales',
            'stages'
        ])->get();

        return response()->json($data);
    }

    /**
     * POST /companies
     */
    public function store(Request $request)
    {

        // dd($request->all());
        try {
            $validated = CustomerValidationService::validateStore(
                $request->all()
            );
            $result = DB::transaction(function () use ($request) {

                /*
    |--------------------------------------------------------------------------
    | MAIN COMPANY
    |--------------------------------------------------------------------------
    */

                $company = CompanyInfoMaster::create([
                    'customer_code' => $request->customer_code,
                    'company_name' => $request->company_name,
                    'registered_address' => $request->registered_address,

                    'contact_number_1' => $request->contact_number_1,
                    'contact_number_2' => $request->contact_number_2,

                    'industry' => $request->industry,
                    'organization_type' => $request->organization_type,

                    'tax_identification_number' => $request->tax_identification_number,

                    'business_start_date' => $request->business_start_date,

                    'number_of_employees' => $request->number_of_employees,

                    'synkar' => $request->synkar ?? false,

                    'estimated_annual_revenue' => $request->estimated_annual_revenue,
                    'estimated_annual_net_income' => $request->estimated_annual_net_income,

                    'company_url' => $request->company_url,

                    'customer_type' => $request->customer_type,
                ]);

                /*
    |--------------------------------------------------------------------------
    | CONTACTS
    |--------------------------------------------------------------------------
    */

                if (!empty($request->contacts)) {

                    foreach ($request->contacts as $contact) {

                        // skip fully empty rows
                        if (
                            empty($contact['contact_name']) &&
                            empty($contact['contact_number']) &&
                            empty($contact['email']) &&
                            empty($contact['role']) &&
                            empty($contact['position'])
                        ) {
                            continue;
                        }

                        ContactInfo::create([
                            'company_id' => $company->id,

                            'contact_name' => $contact['contact_name'] ?? null,
                            'contact_number' => $contact['contact_number'] ?? null,
                            'email' => $contact['email'] ?? null,

                            'role' => $contact['role'] ?? null,
                            'position' => $contact['position'] ?? null,
                        ]);
                    }
                }

                /*
    |--------------------------------------------------------------------------
    | TRADE REFERENCES
    |--------------------------------------------------------------------------
    */

                if (!empty($request->trade_references)) {

                    foreach ($request->trade_references as $ref) {

                        // skip fully empty rows
                        if (
                            empty($ref['business_name']) &&
                            empty($ref['relationship']) &&
                            empty($ref['business_address']) &&
                            empty($ref['contact_person_name']) &&
                            empty($ref['contact_phone']) &&
                            empty($ref['contact_email'])
                        ) {
                            continue;
                        }

                        TradeReference::create([
                            'company_id' => $company->id,

                            'business_name' => $ref['business_name'] ?? null,
                            'relationship' => $ref['relationship'] ?? null,
                            'business_address' => $ref['business_address'] ?? null,

                            'contact_person_name' => $ref['contact_person_name'] ?? null,
                            'contact_phone' => $ref['contact_phone'] ?? null,
                            'contact_email' => $ref['contact_email'] ?? null,
                        ]);
                    }
                }

                /*
    |--------------------------------------------------------------------------
    | SERVICES
    |--------------------------------------------------------------------------
    */

                if (!empty($request->services)) {

                    foreach ($request->services as $service) {

                        // skip fully empty rows
                        if (
                            empty($service['product']) &&
                            empty($service['origin']) &&
                            empty($service['destination'])
                        ) {
                            continue;
                        }

                        ServicesInfo::create([
                            'company_id' => $company->id,

                            'product' => $service['product'] ?? null,
                            'origin' => $service['origin'] ?? null,
                            'destination' => $service['destination'] ?? null,
                        ]);
                    }
                }

                /*
    |--------------------------------------------------------------------------
    | FINANCE
    |--------------------------------------------------------------------------
    */

                if (!empty($request->finance)) {

                    $finance = $request->finance;

                    CompanyFinance::create([
                        'company_id' => $company->id,

                        'credit_terms' => $finance['credit_terms'] ?? null,
                        'payment_mode' => $finance['payment_mode'] ?? null,
                        'invoice_mode' => $finance['invoice_submission'] ?? null,

                        'document_handling'
                        => !empty($finance['additional_billing_service_request']['document_handling']),

                        'billing_summary_report'
                        => !empty($finance['additional_billing_service_request']['billing_summary_report']),

                        'other_requests'
                        => $finance['additional_billing_service_request']['other'] ?? null,
                    ]);

                    /*
        |--------------------------------------------------------------------------
        | E-INVOICE
        |--------------------------------------------------------------------------
        */

                    if (
                        ($finance['invoice_submission'] ?? null) === 'Email'
                    ) {

                        EInvoice::create([
                            'company_id' => $company->id,

                            'invoice_email_address'
                            => $finance['invoice_email_address'] ?? null,

                            'invoice_email_cc_address' => null,
                            'invoice_email_bcc_address' => null,
                        ]);
                    }

                    /*
        |--------------------------------------------------------------------------
        | COURIER INVOICE
        |--------------------------------------------------------------------------
        */

                    if (
                        ($finance['invoice_submission'] ?? null) === 'Courier'
                    ) {

                        CourierInvoice::create([
                            'company_id' => $company->id,

                            'invoice_contact'
                            => $finance['courier']['recepient_name'] ?? null,

                            'invoice_contact_number'
                            => $finance['courier']['recepient_contact'] ?? null,

                            'invoice_courier_address'
                            => $finance['courier']['recepient_address'] ?? null,
                        ]);
                    }
                }

                /*
    |--------------------------------------------------------------------------
    | BILLING
    |--------------------------------------------------------------------------
    */

                if (!empty($request->billing)) {

                    BilledDetail::create([
                        'company_id' => $company->id,

                        'billed_to' => $request->billing['billed_to'] ?? null,
                        'company_name' => $request->billing['company_name'] ?? null,
                        'address' => $request->billing['address'] ?? null,
                        'tin_no' => $request->billing['tin_no'] ?? null,
                    ]);
                }

                /*
    |--------------------------------------------------------------------------
    | SALES
    |--------------------------------------------------------------------------
    */

                if (!empty($request->sales)) {

                    SalesInfo::create([
                        'company_id' => $company->id,

                        'account_owner'
                        => $request->sales['account_owner'] ?? null,
                    ]);
                }

                /*
    |--------------------------------------------------------------------------
    | STAGES
    |--------------------------------------------------------------------------
    */

                if (!empty($request->stages)) {

                    StagesInfo::create([
                        'company_id' => $company->id,

                        'stage' => $request->stages['stage'] ?? null,

                        'proposal_requested_date'
                        => $request->stages['proposal_requested_stage_date'] ?? null,

                        'proposal_submitted_date'
                        => $request->stages['proposal_submitted_stage_date'] ?? null,

                        'negotiation_date'
                        => $request->stages['negotiation_stage_date'] ?? null,

                        'won_awarded_date'
                        => $request->stages['won_awarded_stage_date'] ?? null,

                        'lost_closed_date'
                        => $request->stages['lost_closed_stage_date'] ?? null,

                        'monthly_sales_forecast'
                        => $request->stages['monthly_sales_forecasting'] ?? null,

                        'forecast_transaction_month'
                        => $request->stages['forecasting_transaction_month_date'] ?? null,

                        'potential_volume_month'
                        => $request->stages['potential_vol_month_portlink'] ?? null,

                        'remarks'
                        => $request->stages['remarks'] ?? null,
                    ]);
                }

                return $company;
            });

            return response()->json([

                'success' => true,
                'message' => 'Company created successfully',
                'data' => $result->load([
                    'contacts',
                    'tradeReferences',
                    'services',
                    'finance',
                    'billing',
                    'sales',
                    'stages'
                ]),
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /companies/{id}
     */
    public function show($id)
    {
        $company = CompanyInfoMaster::with([
            'contacts',
            'tradeReferences',
            'services',
            'finance',
            'billing',
            'sales',
            'stages'
        ])->findOrFail($id);

        return response()->json($company);
    }

    /**
     * PUT /companies/{id}
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $company = CompanyInfoMaster::findOrFail($id);

            $company->update($request->only([
                'company_name',
                'registered_address',
                'contact_number_1',
                'contact_number_2',
                'industry',
                'organization_type',
                'tax_identification_number',
                'business_start_date',
                'number_of_employees',
                'synkar',
                'estimated_annual_revenue',
                'estimated_annual_net_income',
                'company_url',
                'customer_type'
            ]));

            // Simple strategy: delete & reinsert children

            ContactInfo::where('company_id', $id)->delete();
            if ($request->contacts) {
                foreach ($request->contacts as $contact) {
                    ContactInfo::create(['company_id' => $id] + $contact);
                }
            }

            TradeReference::where('company_id', $id)->delete();
            if ($request->trade_references) {
                foreach ($request->trade_references as $ref) {
                    TradeReference::create(['company_id' => $id] + $ref);
                }
            }

            ServicesInfo::where('company_id', $id)->delete();
            if ($request->services) {
                foreach ($request->services as $service) {
                    ServicesInfo::create(['company_id' => $id] + $service);
                }
            }

            CompanyFinance::where('company_id', $id)->delete();
            if ($request->finance) {
                CompanyFinance::create(['company_id' => $id] + $request->finance);
            }

            BilledDetail::where('company_id', $id)->delete();
            if ($request->billing) {
                BilledDetail::create(['company_id' => $id] + $request->billing);
            }

            SalesInfo::where('company_id', $id)->delete();
            if ($request->sales) {
                SalesInfo::create(['company_id' => $id] + $request->sales);
            }

            StagesInfo::where('company_id', $id)->delete();
            if ($request->stages) {
                StagesInfo::create(['company_id' => $id] + $request->stages);
            }

            DB::commit();

            return response()->json(['message' => 'Updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /companies/{id}
     */
    public function destroy($id)
    {
        CompanyInfoMaster::findOrFail($id)->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
