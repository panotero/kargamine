<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerValidationService
{
    public static function validateStore(array $data)
    {
        $validator = Validator::make($data, [

            /*
        |--------------------------------------------------------------------------
        | MAIN COMPANY
        |--------------------------------------------------------------------------
        */

            'customer_code' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'registered_address' => 'required|string|max:1000',

            'contact_number_1' => 'required|string|max:255',
            'contact_number_2' => 'nullable|string|max:255',

            'industry' => 'required|string|max:255',
            'organization_type' => 'required|string|max:255',

            'tax_identification_number' => 'required|string|max:255',

            'business_start_date' => 'required|date',

            'number_of_employees' => 'nullable|string|max:255',

            'synkar' => 'nullable|string|max:255',

            'estimated_annual_revenue' => 'nullable|string|max:255',
            'estimated_annual_net_income' => 'nullable|string|max:255',

            'company_url' => 'nullable|url',

            'customer_type' => 'required|in:shipper,consignee,both',

            /*
        |--------------------------------------------------------------------------
        | CONTACTS
        |--------------------------------------------------------------------------
        */

            'contacts' => 'nullable|array',

            'contacts.*.contact_name' => 'nullable|string|max:255',
            'contacts.*.contact_number' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.role' => 'nullable|string|max:255',
            'contacts.*.position' => 'nullable|string|max:255',

            /*
        |--------------------------------------------------------------------------
        | TRADE REFERENCES
        |--------------------------------------------------------------------------
        */

            'trade_references' => 'nullable|array',

            'trade_references.*.business_name' => 'nullable|string|max:255',
            'trade_references.*.relationship' => 'nullable|string|max:255',
            'trade_references.*.business_address' => 'nullable|string|max:1000',

            'trade_references.*.contact_person_name' => 'nullable|string|max:255',
            'trade_references.*.contact_phone' => 'nullable|string|max:255',

            'trade_references.*.contact_email' => 'nullable|email|max:255',

            /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

            'services' => 'nullable|array',

            'services.*.product' => 'nullable|string|max:255',
            'services.*.origin' => 'nullable|string|max:255',
            'services.*.destination' => 'nullable|string|max:255',

            /*
        |--------------------------------------------------------------------------
        | FINANCE
        |--------------------------------------------------------------------------
        */

            'finance' => 'nullable|array',

            'finance.credit_terms' => 'nullable|string|max:255',
            'finance.payment_mode' => 'nullable|string|max:255',

            'finance.invoice_submission' => 'nullable|in:Email,Courier',

            'finance.invoice_email_address' => 'nullable|email|max:255',

            /*
        |--------------------------------------------------------------------------
        | FINANCE - COURIER
        |--------------------------------------------------------------------------
        */

            'finance.courier' => 'nullable|array',

            'finance.courier.recepient_name' => 'nullable|string|max:255',
            'finance.courier.recepient_contact' => 'nullable|string|max:255',
            'finance.courier.recepient_address' => 'nullable|string|max:1000',

            /*
        |--------------------------------------------------------------------------
        | FINANCE - ADDITIONAL BILLING SERVICE REQUEST
        |--------------------------------------------------------------------------
        */

            'finance.additional_billing_service_request' => 'nullable|array',

            'finance.additional_billing_service_request.document_handling'
            => 'nullable|string|max:255',

            'finance.additional_billing_service_request.billing_summary_report'
            => 'nullable|string|max:255',

            'finance.additional_billing_service_request.other'
            => 'nullable|string|max:255',

            /*
        |--------------------------------------------------------------------------
        | BILLING
        |--------------------------------------------------------------------------
        */

            'billing' => 'nullable|array',

            'billing.billed_to' => 'nullable|string|max:255',
            'billing.company_name' => 'nullable|string|max:255',
            'billing.address' => 'nullable|string|max:1000',
            'billing.tin_no' => 'nullable|string|max:255',

            /*
        |--------------------------------------------------------------------------
        | SALES
        |--------------------------------------------------------------------------
        */

            'sales' => 'nullable|array',

            'sales.account_owner' => 'nullable|string|max:255',

            /*
        |--------------------------------------------------------------------------
        | STAGES
        |--------------------------------------------------------------------------
        */

            'stages' => 'nullable|array',

            'stages.stage' => 'nullable|string|max:255',

            'stages.proposal_requested_stage_date' => 'nullable|date',
            'stages.proposal_submitted_stage_date' => 'nullable|date',
            'stages.negotiation_stage_date' => 'nullable|date',
            'stages.won_awarded_stage_date' => 'nullable|date',
            'stages.lost_closed_stage_date' => 'nullable|date',

            'stages.monthly_sales_forecasting' => 'nullable|string|max:255',

            'stages.forecasting_transaction_month_date' => 'nullable|date',

            'stages.potential_vol_month_portlink' => 'nullable|string|max:255',

            'stages.remarks' => 'nullable|string|max:2000',

        ]);

        if ($validator->fails()) {

            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
