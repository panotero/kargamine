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


        try {
            $validated = CustomerValidationService::validateStore(
                $request->all()
            );
            $result = DB::transaction(function () use ($request) {
                // MAIN
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

                // CONTACTS
                if ($request->contacts) {
                    foreach ($request->contacts as $contact) {
                        ContactInfo::create([
                            'company_id' => $company->id,
                            'contact_name' => $contact['contact_name'],
                            'contact_number' => $contact['contact_number'] ?? null,
                            'email' => $contact['email'] ?? null,
                            'role' => $contact['role'] ?? null,
                            'position' => $contact['position'] ?? null,
                        ]);
                    }
                }

                // TRADE REFERENCES
                if ($request->trade_references) {
                    foreach ($request->trade_references as $ref) {
                        TradeReference::create([
                            'company_id' => $company->id,
                            'business_name' => $ref['business_name'],
                            'relationship' => $ref['relationship'] ?? null,
                            'business_address' => $ref['business_address'] ?? null,
                            'contact_person_name' => $ref['contact_person_name'] ?? null,
                            'contact_phone' => $ref['contact_phone'] ?? null,
                            'contact_email' => $ref['contact_email'] ?? null,
                        ]);
                    }
                }

                // SERVICES
                if ($request->services) {
                    foreach ($request->services as $service) {
                        ServicesInfo::create([
                            'company_id' => $company->id,
                            'product' => $service['product'] ?? null,
                            'origin' => $service['origin'] ?? null,
                            'destination' => $service['destination'] ?? null,
                        ]);
                    }
                }

                // FINANCE (single)
                if ($request->finance) {
                    CompanyFinance::create(array_merge(
                        ['company_id' => $company->id],
                        $request->finance
                    ));
                }

                // BILLING (single)
                if ($request->billing) {
                    BilledDetail::create(array_merge(
                        ['company_id' => $company->id],
                        $request->billing
                    ));
                }

                // SALES (single)
                if ($request->sales) {
                    SalesInfo::create(array_merge(
                        ['company_id' => $company->id],
                        $request->sales
                    ));
                }

                // STAGES (single or array depending design)
                if ($request->stages) {
                    StagesInfo::create(array_merge(
                        ['company_id' => $company->id],
                        $request->stages
                    ));
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
