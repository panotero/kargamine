<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Clients Master Data</h1>
            <p class="text-zinc-500">Manage company master file records</p>
        </div>
        <button id="btnNewClient" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            + New Client
        </button>
    </div>

    {{-- Status count cards --}}
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div class="clientStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer ring-2 ring-blue-500"
                data-status="all">
                <div class="w-full py-1 rounded-full bg-blue-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ALL</p>
                <p class="text-2xl font-bold text-black" id="countAll">0</p>
            </div>
            <div class="clientStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="complete">
                <div class="w-full py-1 rounded-full bg-green-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">COMPLETE</p>
                <p class="text-2xl font-bold text-black" id="countComplete">0</p>
            </div>
            <div class="clientStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="incomplete">
                <div class="w-full py-1 rounded-full bg-amber-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">INCOMPLETE</p>
                <p class="text-2xl font-bold text-black" id="countIncomplete">0</p>
            </div>
        </div>
    </section>

    <x-table id="tableClientMasters" />
</div>

{{-- Complete-client detail modal - single scrolling page, no tabs --}}
<x-modal id="ClientDetailModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold" id="cdClientName">-</p>
            <p class="text-xs text-zinc-400" id="cdClientCode">-</p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="max-h-[75vh] overflow-y-auto p-5 space-y-6">

        {{-- ================= CLIENT INFORMATION ================= --}}
        <section class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <div class="flex justify-between items-center mb-3">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Client Information</p>
                <div class="flex gap-2">
                    <button type="button" id="cdEditInfoBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 dark:text-zinc-200">✎ Edit</button>
                    <button type="button" id="cdSaveInfoBtn"
                        class="hidden text-xs px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save</button>
                    <button type="button" id="cdCancelInfoBtn"
                        class="hidden text-xs px-3 py-1.5 rounded-lg border dark:text-zinc-200">Cancel</button>
                </div>
            </div>

            {{-- ---------- READ-ONLY VIEW ---------- --}}
            <div id="cdInfoReadView" class="space-y-4 text-sm">
                <div id="cdInfoContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>

                <div class="border-t pt-3">
                    <p class="text-[11px] font-semibold text-zinc-400 uppercase mb-2">Addresses</p>
                    <div id="cdAddressesReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                </div>

                <div class="border-t pt-3">
                    <p class="text-[11px] font-semibold text-zinc-400 uppercase mb-2">Contacts</p>
                    <div id="cdContactsReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                </div>

                <div class="border-t pt-3">
                    <p class="text-[11px] font-semibold text-zinc-400 uppercase mb-2">Trade / Bank References</p>
                    <div id="cdTradeRefsReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                </div>

                <div class="border-t pt-3">
                    <p class="text-[11px] font-semibold text-zinc-400 uppercase mb-2">Finance &amp; Billing</p>
                    <div id="cdFinanceReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                </div>
            </div>

            {{-- ---------- EDIT VIEW ---------- --}}
            <div id="cdInfoEditView" class="hidden space-y-6 text-sm">

                {{-- Company Information --}}
                <div>
                    <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Company Information</p>
                    <form id="cdStage1Form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Customer Code</label>
                            <input type="text" name="customer_code" readonly
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 bg-zinc-50 text-zinc-600 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Company Name</label>
                            <input type="text" name="company_name" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Contact Number (Primary)</label>
                            <input type="text" name="contact_number_1" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Contact Number (Secondary)</label>
                            <input type="text" name="contact_number_2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Industry</label>
                            <input type="text" name="industry" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Type of Organization</label>
                            <input type="text" name="organization_type" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">TIN</label>
                            <input type="text" name="tin" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Business Established Date</label>
                            <input type="date" name="business_start_date" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Estimated Annual Revenue</label>
                            <input type="number" step="0.01" name="estimated_annual_revenue" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Company URL</label>
                            <input type="url" name="company_url" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                    </form>
                </div>

                {{-- Addresses --}}
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-zinc-700 dark:text-zinc-300">Address(es) *</p>
                        <button type="button" id="cdAddAddressBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+ Add Address</button>
                    </div>
                    <div id="cdAddressesEditContainer" class="space-y-4"></div>
                </div>

                {{-- Contacts --}}
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-zinc-700 dark:text-zinc-300">Contacts</p>
                        <button type="button" id="cdAddContactBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+ Add Contact</button>
                    </div>
                    <div id="cdContactsEditContainer" class="space-y-3"></div>
                </div>

                {{-- Trade / Bank References --}}
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-zinc-700 dark:text-zinc-300">Trade / Bank Reference</p>
                        <button type="button" id="cdAddTradeRefBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+ Add Reference</button>
                    </div>
                    <div id="cdTradeRefsEditContainer" class="space-y-3"></div>
                </div>

                {{-- Finance & Billing --}}
                <div class="border-t pt-4">
                    <form id="cdStage3Form" class="space-y-6">
                        <div>
                            <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Company Finance</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Credit Terms</label>
                                    <input type="text" name="finance[credit_terms]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Payment Mode</label>
                                    <input type="text" name="finance[payment_mode]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <label class="flex items-center gap-2 md:col-span-2">
                                    <input type="checkbox" name="finance[standard_billing_service]" value="1">
                                    <span class="text-sm dark:text-zinc-200">Standard Billing Service</span>
                                </label>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Invoice Submission</p>
                            <div class="flex gap-4 mb-3">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="finance[invoice_submission]" value="electronic" class="cdInvoiceSubmissionRadio">
                                    <span class="text-sm dark:text-zinc-200">Electronic</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="finance[invoice_submission]" value="courier" class="cdInvoiceSubmissionRadio">
                                    <span class="text-sm dark:text-zinc-200">Via Courier</span>
                                </label>
                            </div>
                            <div id="cdInvoiceElectronicFields" class="hidden">
                                <label class="text-xs font-medium text-zinc-400 uppercase">Invoice Email Address</label>
                                <input type="email" name="finance[invoice_email_address]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                            </div>
                            <div id="cdInvoiceCourierFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Recipient Name</label>
                                    <input type="text" name="finance[invoice_courier_recipient]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Recipient Contact</label>
                                    <input type="text" name="finance[invoice_courier_contact]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Courier Address</label>
                                    <textarea name="finance[invoice_courier_address]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Payment Method</p>
                            <div class="flex gap-4 mb-3">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="finance[payment_method]" value="check_pickup" class="cdPaymentMethodRadio">
                                    <span class="text-sm dark:text-zinc-200">Check Pickup</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="finance[payment_method]" value="direct_remittance" class="cdPaymentMethodRadio">
                                    <span class="text-sm dark:text-zinc-200">Direct Remittance to Bank</span>
                                </label>
                            </div>
                            <div id="cdCheckPickupFields" class="hidden">
                                <label class="text-xs font-medium text-zinc-400 uppercase">Pickup Address</label>
                                <textarea name="finance[check_pickup_address]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900"></textarea>
                            </div>
                            <div id="cdDirectRemittanceFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Bank Name</label>
                                    <input type="text" name="finance[bank_name]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Bank Account Number</label>
                                    <input type="text" name="finance[bank_account_number]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Additional Billing Service Request</p>
                            <div class="flex gap-6 mb-3">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="finance[document_handling]" value="1">
                                    <span class="text-sm dark:text-zinc-200">Document Handling</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="finance[billing_summary_report]" value="1">
                                    <span class="text-sm dark:text-zinc-200">Billing Summary Report</span>
                                </label>
                            </div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Others</label>
                            <textarea name="finance[other_requests]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900"></textarea>
                        </div>

                        <div class="border-t pt-4">
                            <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Billing Details</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Billed To</label>
                                    <input type="text" name="billing[billed_to]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Company Name</label>
                                    <input type="text" name="billing[company_name]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Address</label>
                                    <textarea name="billing[address]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900"></textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">TIN</label>
                                    <input type="text" name="billing[tin]" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- ================= CONTRACTS ================= --}}
        <section class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <div class="flex justify-between items-center mb-3">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Contracts</p>
                <button id="cdAddContractBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ Add
                    Contract</button>
            </div>
            <div id="cdContractsContainer" class="space-y-2"></div>
        </section>

        {{-- ================= PROPOSALS ================= --}}
        <section class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <div class="flex justify-between items-center mb-3">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Proposals</p>
                <button id="cdAddProposalBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ Add
                    Proposal</button>
            </div>
            <div id="cdProposalsContainer" class="space-y-3"></div>
            <div id="cdProposalsPagination"></div>
        </section>

    </div>
</x-modal>

{{-- Create Contract modal - same Accepted-gated, rate-override-confirm flow
     as proposals.blade.php's createContractModal, ported here rather than
     using the old Approved-gated RateContractModal (which posted rates[]
     directly) so both pages create a contract the same way. --}}
<x-modal id="createContractModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold">Create Contract</p>
            <p class="text-xs text-zinc-400">From proposal <span id="ccProposalCode">-</span></p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="max-h-[65vh] overflow-y-auto p-5 space-y-5">
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Valid From</label>
                <input type="date" id="ccValidFrom" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Valid To</label>
                <input type="date" id="ccValidTo" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Signed Date</label>
                <input type="date" id="ccSignedDate" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
        </div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 mb-2">Rate Lines</p>
            <p class="text-xs text-zinc-400 mb-2">Copied from the accepted proposal. Click <span class="font-medium">✎</span> on a line to correct it before saving.</p>
            <table class="w-full text-xs">
                <thead class="text-zinc-400 uppercase">
                    <tr>
                        <th class="text-left py-1 px-2">Route</th>
                        <th class="text-left py-1 px-2">Container</th>
                        <th class="text-right py-1 px-2">Base Rate</th>
                        <th class="text-right py-1 px-2">Discount</th>
                        <th class="text-right py-1 px-2">Final Rate</th>
                        <th class="py-1 px-2"></th>
                    </tr>
                </thead>
                <tbody id="ccRatesBody" class="divide-y divide-zinc-100"></tbody>
            </table>
        </div>
    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
        <button id="ccSaveBtn" class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
            Create Contract
        </button>
    </div>
</x-modal>

{{-- Add Proposal side-modal - repeatable container-line row builder, same pattern
     as crm.blade.php's LeadAddProposalModal. Handles both create (brand new
     proposal for this client) and append (add lines to an existing Pending one). --}}
<x-side-modal id="AddClientProposalModal">
    <div
        class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
        <p class="text-lg font-semibold dark:text-white">New Proposal</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div class="p-5">
        <div class="flex justify-between items-center mb-3">
            <p class="font-semibold text-zinc-700 dark:text-zinc-300 text-sm">Container Lines</p>
            <button type="button" id="cpAddRowBtn"
                class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+
                Add Container</button>
        </div>
        <div id="cpRatesContainer" class="space-y-3"></div>
    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
        <button type="button"
            class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">Cancel</button>
        <button type="button" id="cpSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save Proposal</button>
    </div>
</x-side-modal>

{{-- Add Contract side-modal - same repeatable container-line row builder as
     Add Proposal, but posts straight to ClientContractController::store()
     with no proposal behind it. Only available here in the Client Master
     modal - not on the Proposals or Contracts pages, which only create a
     contract by converting an Accepted proposal. --}}
<x-side-modal id="AddClientContractModal">
    <div
        class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
        <p class="text-lg font-semibold dark:text-white">New Contract</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div class="p-5 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-medium text-zinc-400 uppercase">Valid From *</label>
                <input type="date" id="acValidFrom" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
            </div>
            <div>
                <label class="text-xs font-medium text-zinc-400 uppercase">Valid To *</label>
                <input type="date" id="acValidTo" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
            </div>
        </div>
        <div>
            <label class="text-xs font-medium text-zinc-400 uppercase">Signed Date</label>
            <input type="date" id="acSignedDate" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
        </div>

        <div class="border-t pt-4">
            <div class="flex justify-between items-center mb-3">
                <p class="font-semibold text-zinc-700 dark:text-zinc-300 text-sm">Container Lines</p>
                <button type="button" id="acAddRowBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+
                    Add Container</button>
            </div>
            <div id="acRatesContainer" class="space-y-3"></div>
        </div>
    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
        <button type="button"
            class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">Cancel</button>
        <button type="button" id="acSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save Contract</button>
    </div>
</x-side-modal>


<script>
    (function() {
        renderTable().load(1);
        loadCounts();

        function statusBadge(isComplete) {
            return isComplete ?
                `<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Complete</span>` :
                `<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-600"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Incomplete</span>`;
        }

        async function loadCounts() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/clientMasters'
            });
            if (!response.success) return;
            document.getElementById('countAll').textContent = response.counts.all;
            document.getElementById('countComplete').textContent = response.counts.complete;
            document.getElementById('countIncomplete').textContent = response.counts.incomplete;
        }

        document.getElementById('btnNewClient').addEventListener('click', function() {
            window.clientMasterFormUuid = null;
            loadPage({
                title: 'New Client',
                link: '/page_clientMasterForm'
            });
        });

        function renderTable() {
            const thead = [{
                    title: 'Customer Code',
                    key: 'customer_code',
                    render: (r) => r.customer_code ?? '-'
                },
                {
                    title: 'Company Name',
                    key: 'company_name',
                    render: (r) => r.company_name ?? '-'
                },
                {
                    title: 'Industry',
                    key: 'industry',
                    render: (r) => r.industry ?? '-'
                },
                {
                    title: 'Sales Rep',
                    key: 'sales_rep.name',
                    render: (r) => r.sales_rep?.name ?? '-'
                },
                {
                    title: 'Stage',
                    key: 'current_stage',
                    render: (r) => `${r.current_stage} / 3`
                },
                {
                    title: 'Status',
                    key: 'is_complete',
                    render: (r) => statusBadge(r.is_complete)
                },
                {
                    title: 'Last Updated',
                    key: 'created_at',
                    render: (r) => formatDateTime(r.created_at)
                },
            ];

            return renderRemoteTable({
                url: '/api/clientMasters',
                tableId: 'tableClientMasters',
                thead: thead,
                afterRenderFunction: (row) => {
                    row.addEventListener('click', function() {
                        const data = JSON.parse(row.dataset.row);
                        if (data.is_complete) {
                            openClientDetailModal(data.uuid);
                        } else {
                            window.clientMasterFormUuid = data.uuid;
                            loadPage({
                                title: 'Edit Client',
                                link: '/page_clientMasterForm'
                            });
                        }
                    });
                },
            });
        }

        document.querySelectorAll('.clientStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.clientStatusBtn').forEach((c) => c.classList.remove(
                    'ring-2', 'ring-blue-500'));
                this.classList.add('ring-2', 'ring-blue-500');
                renderTable().setFilter('status', this.dataset.status);
            });
        });

        // ================= CLIENT DETAIL MODAL =================
        let currentClientUuid = null;
        let currentClientData = null;

        // Exposed globally so callers on other pages (e.g. the Dashboard's
        // Top Clients widget) can navigate here then open a specific
        // client's modal, same pattern as notificationController.js's
        // data.modal_fn hook.
        window.openClientDetailModal = openClientDetailModal;

        async function openClientDetailModal(uuid) {
            currentClientUuid = uuid;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/${uuid}`
            });
            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to load this client.'
                });
                return;
            }

            currentClientData = response.data;
            renderClientInfoReadView(currentClientData);
            exitInfoEditMode();

            const c = currentClientData;
            document.getElementById('cdClientName').textContent = c.company_name ?? '-';
            document.getElementById('cdClientCode').textContent = c.customer_code ?? '-';

            loadProposals(uuid, 1);
            loadContracts(uuid);

            initModal({
                modalId: 'ClientDetailModal'
            });
        }

        function money(v) {
            return v === null || v === undefined || v === '' ? '-' :
                Number(v).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
        }

        function renderClientInfoReadView(c) {
            document.getElementById('cdInfoContainer').innerHTML = `
                <p><span class="text-zinc-400">Contact No. 1:</span> ${c.contact_number_1 ?? '-'}</p>
                <p><span class="text-zinc-400">Contact No. 2:</span> ${c.contact_number_2 ?? '-'}</p>
                <p><span class="text-zinc-400">Industry:</span> ${c.industry ?? '-'}</p>
                <p><span class="text-zinc-400">Organization Type:</span> ${c.organization_type ?? '-'}</p>
                <p><span class="text-zinc-400">TIN:</span> ${c.tin ?? '-'}</p>
                <p><span class="text-zinc-400">Business Established:</span> ${c.business_start_date ?? '-'}</p>
                <p><span class="text-zinc-400">Est. Annual Revenue:</span> ${money(c.estimated_annual_revenue)}</p>
                <p><span class="text-zinc-400">Company URL:</span> ${c.company_url ?? '-'}</p>
                <p><span class="text-zinc-400">Sales Rep:</span> ${c.sales_rep?.name ?? '-'}</p>
            `;

            const addresses = c.addresses ?? [];
            document.getElementById('cdAddressesReadContainer').innerHTML = addresses.length ?
                addresses.map((a) => `
                    <div class="border rounded-lg p-3 text-xs">
                        <p class="font-semibold mb-1">${a.address_type ?? 'Address'} ${a.is_primary ? '<span class=\"text-orange-500\">(Primary)</span>' : ''}</p>
                        <p class="text-zinc-500">${[a.address_no, a.address_building, a.address_street, a.address_barangay, a.address_town_city, a.address_province, a.address_country, a.address_postal_code].filter(Boolean).join(', ') || '-'}</p>
                    </div>
                `).join('') :
                `<p class="text-xs text-zinc-400">No addresses on file.</p>`;

            const contacts = c.contacts ?? [];
            document.getElementById('cdContactsReadContainer').innerHTML = contacts.length ?
                contacts.map((ct) => `
                    <div class="border rounded-lg p-3 text-xs">
                        <p class="font-semibold mb-1">${ct.contact_name ?? '-'} ${ct.position ? `<span class="text-zinc-400">(${ct.position})</span>` : ''}</p>
                        <p class="text-zinc-500">${ct.contact_number ?? '-'} ${ct.contact_number_type ? `(${ct.contact_number_type})` : ''}</p>
                        <p class="text-zinc-500">${ct.contact_email ?? '-'} ${ct.contact_email_type ? `(${ct.contact_email_type})` : ''}</p>
                        ${ct.role ? `<p class="text-zinc-500">Role: ${ct.role}</p>` : ''}
                    </div>
                `).join('') :
                `<p class="text-xs text-zinc-400">No contacts on file.</p>`;

            const tradeRefs = c.trade_references ?? [];
            document.getElementById('cdTradeRefsReadContainer').innerHTML = tradeRefs.length ?
                tradeRefs.map((t) => `
                    <div class="border rounded-lg p-3 text-xs">
                        <p class="font-semibold mb-1">${t.business_name ?? '-'} <span class="text-zinc-400">(${t.relationship ?? '-'})</span></p>
                        <p class="text-zinc-500">${t.contact_person_name ?? '-'}</p>
                        <p class="text-zinc-500">${t.contact_person_phone ?? '-'} / ${t.contact_person_mobile ?? '-'}</p>
                        <p class="text-zinc-500">${t.contact_person_email ?? '-'}</p>
                    </div>
                `).join('') :
                `<p class="text-xs text-zinc-400">No trade references on file.</p>`;

            const f = c.finance ?? {};
            const b = c.billing ?? {};
            document.getElementById('cdFinanceReadContainer').innerHTML = `
                <p><span class="text-zinc-400">Credit Terms:</span> ${f.credit_terms ?? '-'}</p>
                <p><span class="text-zinc-400">Payment Mode:</span> ${f.payment_mode ?? '-'}</p>
                <p><span class="text-zinc-400">Standard Billing Service:</span> ${f.standard_billing_service ? 'Yes' : 'No'}</p>
                <p><span class="text-zinc-400">Invoice Submission:</span> ${f.invoice_submission ?? '-'}</p>
                ${f.invoice_submission === 'electronic' ? `<p><span class="text-zinc-400">Invoice Email:</span> ${f.invoice_email_address ?? '-'}</p>` : ''}
                ${f.invoice_submission === 'courier' ? `<p><span class="text-zinc-400">Courier Recipient:</span> ${f.invoice_courier_recipient ?? '-'} (${f.invoice_courier_contact ?? '-'})</p>` : ''}
                <p><span class="text-zinc-400">Payment Method:</span> ${f.payment_method ?? '-'}</p>
                ${f.payment_method === 'direct_remittance' ? `<p><span class="text-zinc-400">Bank:</span> ${f.bank_name ?? '-'} - ${f.bank_account_number ?? '-'}</p>` : ''}
                <p><span class="text-zinc-400">Document Handling:</span> ${f.document_handling ? 'Yes' : 'No'}</p>
                <p><span class="text-zinc-400">Billing Summary Report:</span> ${f.billing_summary_report ? 'Yes' : 'No'}</p>
                <p class="md:col-span-2"><span class="text-zinc-400">Other Requests:</span> ${f.other_requests ?? '-'}</p>
                <p><span class="text-zinc-400">Billed To:</span> ${b.billed_to ?? '-'}</p>
                <p><span class="text-zinc-400">Billing Company:</span> ${b.company_name ?? '-'}</p>
                <p class="md:col-span-2"><span class="text-zinc-400">Billing Address:</span> ${b.address ?? '-'}</p>
                <p><span class="text-zinc-400">Billing TIN:</span> ${b.tin ?? '-'}</p>
            `;
        }

        // ================= INFO SECTION: EDIT MODE =================
        function enterInfoEditMode() {
            document.getElementById('cdInfoReadView').classList.add('hidden');
            document.getElementById('cdInfoEditView').classList.remove('hidden');
            document.getElementById('cdEditInfoBtn').classList.add('hidden');
            document.getElementById('cdSaveInfoBtn').classList.remove('hidden');
            document.getElementById('cdCancelInfoBtn').classList.remove('hidden');
            hydrateInfoEditForm(currentClientData);
        }

        function exitInfoEditMode() {
            document.getElementById('cdInfoReadView').classList.remove('hidden');
            document.getElementById('cdInfoEditView').classList.add('hidden');
            document.getElementById('cdEditInfoBtn').classList.remove('hidden');
            document.getElementById('cdSaveInfoBtn').classList.add('hidden');
            document.getElementById('cdCancelInfoBtn').classList.add('hidden');
        }

        document.getElementById('cdEditInfoBtn').addEventListener('click', enterInfoEditMode);
        document.getElementById('cdCancelInfoBtn').addEventListener('click', () => {
            renderClientInfoReadView(currentClientData);
            exitInfoEditMode();
        });

        function hydrateInfoEditForm(c) {
            const stage1Form = document.getElementById('cdStage1Form');
            stage1Form.reset();
            Object.entries(c).forEach(([key, val]) => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = val ?? '';
            });

            document.getElementById('cdAddressesEditContainer').innerHTML = '';
            const addresses = (c.addresses && c.addresses.length) ? c.addresses : [{
                is_primary: true
            }];
            addAddressCardsFrom(addresses);

            document.getElementById('cdContactsEditContainer').innerHTML = '';
            (c.contacts || []).forEach((contact) => {
                document.getElementById('cdContactsEditContainer').insertAdjacentHTML('beforeend',
                    contactRowHtml());
                const row = document.getElementById('cdContactsEditContainer').lastElementChild;
                row.querySelectorAll('[data-field]').forEach((input) => input.value = contact[input
                    .dataset.field] ?? '');
            });

            document.getElementById('cdTradeRefsEditContainer').innerHTML = '';
            (c.trade_references || []).forEach((ref) => {
                document.getElementById('cdTradeRefsEditContainer').insertAdjacentHTML('beforeend',
                    tradeRefRowHtml());
                const row = document.getElementById('cdTradeRefsEditContainer').lastElementChild;
                row.querySelectorAll('[data-field]').forEach((input) => input.value = ref[input
                    .dataset.field] ?? '');
            });

            const stage3Form = document.getElementById('cdStage3Form');
            stage3Form.reset();
            document.getElementById('cdInvoiceElectronicFields').classList.add('hidden');
            document.getElementById('cdInvoiceCourierFields').classList.add('hidden');
            document.getElementById('cdCheckPickupFields').classList.add('hidden');
            document.getElementById('cdDirectRemittanceFields').classList.add('hidden');

            if (c.finance) {
                Object.entries(c.finance).forEach(([key, val]) => {
                    const el = stage3Form.querySelector(`[name="finance[${key}]"]`);
                    if (!el) return;
                    if (el.type === 'checkbox') el.checked = Boolean(val);
                    else if (el.type === 'radio') {
                        if (el.value === val) el.checked = true;
                    } else el.value = val ?? '';
                });
                document.querySelector(`.cdInvoiceSubmissionRadio[value="${c.finance.invoice_submission}"]`)
                    ?.dispatchEvent(new Event('change'));
                document.querySelector(`.cdPaymentMethodRadio[value="${c.finance.payment_method}"]`)
                    ?.dispatchEvent(new Event('change'));
            }

            if (c.billing) {
                Object.entries(c.billing).forEach(([key, val]) => {
                    const el = stage3Form.querySelector(`[name="billing[${key}]"]`);
                    if (el) el.value = val ?? '';
                });
            }
        }

        document.querySelectorAll('.cdInvoiceSubmissionRadio').forEach((r) => {
            r.addEventListener('change', function() {
                document.getElementById('cdInvoiceElectronicFields').classList.toggle('hidden', this
                    .value !== 'electronic');
                document.getElementById('cdInvoiceCourierFields').classList.toggle('hidden', this
                    .value !== 'courier');
            });
        });

        document.querySelectorAll('.cdPaymentMethodRadio').forEach((r) => {
            r.addEventListener('change', function() {
                document.getElementById('cdCheckPickupFields').classList.toggle('hidden', this.value !==
                    'check_pickup');
                document.getElementById('cdDirectRemittanceFields').classList.toggle('hidden', this
                    .value !== 'direct_remittance');
            });
        });

        // -------- Contacts / trade-ref repeatable rows (mirrors clientMasterForm.blade.php) --------
        function contactRowHtml() {
            return `
            <div class="contact-row grid grid-cols-1 md:grid-cols-5 gap-2 border rounded-lg p-3 relative">
                <input type="text" data-field="contact_name" placeholder="Contact Name" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <div class="flex gap-1">
                    <input type="text" data-field="contact_number" placeholder="Contact Number" class="border rounded-lg px-2 py-1.5 text-sm flex-1 min-w-0 dark:text-zinc-900">
                    <select data-field="contact_number_type" class="border rounded-lg px-1 py-1.5 text-xs w-24 shrink-0 dark:text-zinc-900">
                        <option value="">Type</option>
                        <option value="mobile">Mobile</option>
                        <option value="landline">Landline</option>
                    </select>
                </div>
                <div class="flex gap-1">
                    <input type="email" data-field="contact_email" placeholder="Email" class="border rounded-lg px-2 py-1.5 text-sm flex-1 min-w-0 dark:text-zinc-900">
                    <select data-field="contact_email_type" class="border rounded-lg px-1 py-1.5 text-xs w-24 shrink-0 dark:text-zinc-900">
                        <option value="">Type</option>
                        <option value="personal">Personal</option>
                        <option value="business">Business</option>
                    </select>
                </div>
                <input type="text" data-field="role" placeholder="Role" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <div class="flex gap-2">
                    <input type="text" data-field="position" placeholder="Position" class="border rounded-lg px-2 py-1.5 text-sm flex-1 dark:text-zinc-900">
                    <button type="button" class="remove-row text-red-500 px-2">✕</button>
                </div>
            </div>`;
        }

        function tradeRefRowHtml() {
            return `
            <div class="trade-ref-row grid grid-cols-1 md:grid-cols-3 gap-2 border rounded-lg p-3 relative">
                <input type="text" data-field="business_name" placeholder="Business Name" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <input type="text" data-field="relationship" placeholder="Nature of Relationship" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <input type="text" data-field="contact_person_name" placeholder="Contact Person" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <input type="text" data-field="contact_person_phone" placeholder="Phone" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <input type="text" data-field="contact_person_mobile" placeholder="Mobile" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <div class="flex gap-2">
                    <input type="email" data-field="contact_person_email" placeholder="Email" class="border rounded-lg px-2 py-1.5 text-sm flex-1 dark:text-zinc-900">
                    <button type="button" class="remove-row text-red-500 px-2">✕</button>
                </div>
            </div>`;
        }

        document.getElementById('cdAddContactBtn').addEventListener('click', () => {
            document.getElementById('cdContactsEditContainer').insertAdjacentHTML('beforeend',
                contactRowHtml());
        });
        document.getElementById('cdAddTradeRefBtn').addEventListener('click', () => {
            document.getElementById('cdTradeRefsEditContainer').insertAdjacentHTML('beforeend',
                tradeRefRowHtml());
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.contact-row, .trade-ref-row').remove();
            }
        });

        function collectRows(containerId, rowClass) {
            return Array.from(document.querySelectorAll(`#${containerId} .${rowClass}`)).map((row) => {
                const obj = {};
                row.querySelectorAll('[data-field]').forEach((input) => {
                    obj[input.dataset.field] = input.value;
                });
                return obj;
            });
        }

        // -------- Addresses: repeatable cards with PSGC cascade (mirrors clientMasterForm.blade.php) --------
        const PSGC_API = 'https://psgc.cloud/api';

        async function psgcRequest(url) {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Failed to fetch ${url}`);
            return response.json();
        }

        function resetSelect(select, placeholder) {
            select.innerHTML = '';
            const option = document.createElement('option');
            option.value = '';
            option.textContent = placeholder;
            select.appendChild(option);
            select.disabled = true;
        }

        function populateSelect(select, items, placeholder) {
            resetSelect(select, placeholder);
            items.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.name;
                option.textContent = item.name;
                option.dataset.code = item.code;
                select.appendChild(option);
            });
            select.disabled = false;
        }

        const COUNTRIES = [
            'Philippines', 'United States', 'Singapore', 'Hong Kong', 'China', 'Japan',
            'South Korea', 'Malaysia', 'Indonesia', 'Thailand', 'Vietnam', 'Taiwan',
            'Australia', 'United Kingdom', 'Canada', 'United Arab Emirates', 'Other',
        ];
        const countryOptionsHtml = COUNTRIES
            .map((c) => `<option value="${c}" ${c === 'Philippines' ? 'selected' : ''}>${c}</option>`)
            .join('');

        let addressTypeOptionsHtml = '<option value="">Select Address Type</option>';
        let addressTypeOptionsLoaded = false;

        async function fillAddressTypeOptions() {
            if (addressTypeOptionsLoaded) return;
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/addresstype'
            });
            if (Array.isArray(response)) {
                addressTypeOptionsHtml += response.map((lov) =>
                    `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join('');
            }
            addressTypeOptionsLoaded = true;
        }

        function addressCardHtml(index) {
            return `
    <div class="address-card border rounded-xl p-4 space-y-3" data-index="${index}">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <select data-field="address_type" class="w-full border rounded-lg px-3 py-2 text-sm font-semibold dark:text-zinc-900">
                    ${addressTypeOptionsHtml}
                </select>
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 whitespace-nowrap">
                    <input type="radio" name="cd_address_primary_radio" class="primary-radio">
                    Primary
                </label>
            </div>
            <button type="button" class="remove-address text-red-500 text-xs font-medium">✕ Remove</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">No.</label>
                <input type="text" data-field="address_no" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Building</label>
                <input type="text" data-field="address_building" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Street</label>
                <input type="text" data-field="address_street" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Country</label>
                <select data-field="address_country" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    ${countryOptionsHtml}
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Province</label>
                <select data-field="address_province" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    <option value="">Select Province</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Town/City</label>
                <select data-field="address_town_city" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900" disabled>
                    <option value="">Select Town/City</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Barangay</label>
                <select data-field="address_barangay" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900" disabled>
                    <option value="">Select Barangay</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Postal Code</label>
                <input type="text" data-field="address_postal_code" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
        </div>
    </div>`;
        }

        async function addAddressCard() {
            const wrap = document.getElementById('cdAddressesEditContainer');
            const index = wrap.children.length;
            wrap.insertAdjacentHTML('beforeend', addressCardHtml(index));
            const card = wrap.lastElementChild;

            card.querySelector('.remove-address').addEventListener('click', () => card.remove());
            if (index === 0) card.querySelector('.primary-radio').checked = true;

            await initializePhilippineAddress(card);
            return card;
        }

        document.getElementById('cdAddAddressBtn').addEventListener('click', () => addAddressCard());

        async function addAddressCardsFrom(addresses) {
            for (const address of addresses) {
                const card = await addAddressCard();
                await hydrateAddressCard(card, address);
            }
        }

        async function hydrateAddressCard(card, address) {
            ['address_no', 'address_building', 'address_street', 'address_postal_code'].forEach((
                field) => {
                const el = card.querySelector(`[data-field="${field}"]`);
                if (el) el.value = address[field] ?? '';
            });

            const typeSelect = card.querySelector('[data-field="address_type"]');
            if (typeSelect) typeSelect.value = address.address_type ?? '';

            const countrySelect = card.querySelector('[data-field="address_country"]');
            if (countrySelect) countrySelect.value = address.address_country || 'Philippines';

            card.querySelector('.primary-radio').checked = Boolean(address.is_primary);

            const {
                loadCitiesForProvince,
                loadBarangaysForCity
            } = card._addressLookups ?? {};
            const provinceSelect = card.querySelector('[data-field="address_province"]');
            const citySelect = card.querySelector('[data-field="address_town_city"]');
            const barangaySelect = card.querySelector('[data-field="address_barangay"]');

            if (address.address_province && provinceSelect) {
                provinceSelect.value = address.address_province;
                const provinceCode = provinceSelect.selectedOptions[0]?.dataset.code;

                if (loadCitiesForProvince) await loadCitiesForProvince(provinceCode);

                if (address.address_town_city && citySelect) {
                    citySelect.value = address.address_town_city;
                    const cityCode = citySelect.selectedOptions[0]?.dataset.code;

                    if (loadBarangaysForCity) await loadBarangaysForCity(cityCode);

                    if (address.address_barangay && barangaySelect) {
                        barangaySelect.value = address.address_barangay;
                    }
                }
            }
        }

        async function initializePhilippineAddress(container) {
            const province = container.querySelector('[data-field="address_province"]');
            const city = container.querySelector('[data-field="address_town_city"]');
            const barangay = container.querySelector('[data-field="address_barangay"]');

            if (!province || !city || !barangay) return;

            resetSelect(city, 'Select Town/City');
            resetSelect(barangay, 'Select Barangay');

            async function loadCitiesForProvince(provinceCode) {
                resetSelect(city, 'Loading...');
                resetSelect(barangay, 'Select Barangay');

                if (!provinceCode) {
                    resetSelect(city, 'Select Town/City');
                    return;
                }

                const cities = await psgcRequest(`${PSGC_API}/provinces/${provinceCode}/cities-municipalities`);
                cities.sort((a, b) => a.name.localeCompare(b.name));
                populateSelect(city, cities, 'Select Town/City');
            }

            async function loadBarangaysForCity(cityCode) {
                resetSelect(barangay, 'Loading...');

                if (!cityCode) {
                    resetSelect(barangay, 'Select Barangay');
                    return;
                }

                const barangays = await psgcRequest(`${PSGC_API}/cities-municipalities/${cityCode}/barangays`);
                barangays.sort((a, b) => a.name.localeCompare(b.name));
                populateSelect(barangay, barangays, 'Select Barangay');
            }

            const provinces = await psgcRequest(`${PSGC_API}/provinces`);
            provinces.sort((a, b) => a.name.localeCompare(b.name));
            populateSelect(province, provinces, 'Select Province');

            province.addEventListener('change', function() {
                const provinceCode = this.selectedOptions[0]?.dataset.code;
                loadCitiesForProvince(provinceCode);
            });

            city.addEventListener('change', function() {
                const cityCode = this.selectedOptions[0]?.dataset.code;
                loadBarangaysForCity(cityCode);
            });

            container._addressLookups = {
                loadCitiesForProvince,
                loadBarangaysForCity
            };
        }

        function collectAddresses() {
            return Array.from(document.querySelectorAll('#cdAddressesEditContainer .address-card')).map((
                card) => {
                const obj = {};
                card.querySelectorAll('[data-field]').forEach((el) => {
                    obj[el.dataset.field] = el.value;
                });
                obj.is_primary = card.querySelector('.primary-radio')?.checked ?? false;
                return obj;
            });
        }

        function formToNestedPayload(form) {
            const fd = new FormData(form);
            const payload = {
                finance: {},
                billing: {}
            };
            for (const [key, value] of fd.entries()) {
                const match = key.match(/^(finance|billing)\[(.+)\]$/);
                if (match) {
                    payload[match[1]][match[2]] = value;
                } else {
                    payload[key] = value;
                }
            }
            ['standard_billing_service', 'document_handling', 'billing_summary_report'].forEach((f) => {
                payload.finance[f] = form.querySelector(`[name="finance[${f}]"]`)?.checked ?? false;
            });
            return payload;
        }

        // -------- Save: always re-submits all 3 stages together --------
        document.getElementById('cdSaveInfoBtn').addEventListener('click', async function() {
            if (!currentClientUuid) return;

            const addresses = collectAddresses();
            if (!addresses.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one address.'
                });
                return;
            }

            const stage1Form = document.getElementById('cdStage1Form');
            const stage1Data = Object.fromEntries(new FormData(stage1Form).entries());
            stage1Data.uuid = currentClientUuid;
            stage1Data.addresses = addresses;

            const stage1Response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: stage1Data,
                url: '/api/clientMasters/stage1',
                button: this,
            });

            if (!stage1Response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save company information',
                    message: stage1Response.message ?? ''
                });
                return;
            }

            const stage2Response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    contacts: collectRows('cdContactsEditContainer', 'contact-row'),
                    trade_references: collectRows('cdTradeRefsEditContainer', 'trade-ref-row'),
                },
                url: `/api/clientMasters/${currentClientUuid}/stage2`,
            });

            if (!stage2Response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save contacts / trade references'
                });
                return;
            }

            const stage3Payload = formToNestedPayload(document.getElementById('cdStage3Form'));
            const stage3Response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: stage3Payload,
                url: `/api/clientMasters/${currentClientUuid}/stage3`,
            });

            if (!stage3Response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save finance / billing'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Client information updated'
            });

            currentClientData = stage3Response.data;
            renderClientInfoReadView(currentClientData);
            exitInfoEditMode();
            renderTable().reload();
        });

        // ================= CONTRACTS LIST =================
        const CONTRACT_STATUS_MAPPING = {
            1: {
                label: 'Draft',
                classes: 'bg-zinc-100 text-zinc-600'
            },
            2: {
                label: 'Active',
                classes: 'bg-emerald-50 text-emerald-700'
            },
            3: {
                label: 'Expired',
                classes: 'bg-amber-50 text-amber-700'
            },
            4: {
                label: 'Terminated',
                classes: 'bg-red-50 text-red-700'
            },
        };

        function contractStatusBadge(status) {
            const meta = CONTRACT_STATUS_MAPPING[status] ?? {
                label: 'Unknown',
                classes: 'bg-zinc-100 text-zinc-500'
            };
            return `<span class="inline-flex items-center rounded-full ${meta.classes} px-2 py-0.5 text-xs font-medium">${meta.label}</span>`;
        }

        async function loadContracts(uuid) {
            const container = document.getElementById('cdContractsContainer');
            container.innerHTML = `<p class="text-sm text-zinc-400">Loading...</p>`;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/${uuid}/contracts`
            });
            if (!response.success || !response.data.length) {
                container.innerHTML = `<p class="text-sm text-zinc-400 text-center py-6">No contracts yet.</p>`;
                return;
            }

            container.innerHTML = response.data.map((c) => `
                <div class="contract-row border rounded-xl p-4 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800" data-contract-id="${c.id}">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-sm">${c.code}</p>
                                ${contractStatusBadge(c.status)}
                            </div>
                            <p class="text-xs text-zinc-400">${c.valid_from} → ${c.valid_to}</p>
                        </div>
                        ${c.status === 2 ? `
                            <button type="button" class="contract-terminate-btn shrink-0 text-[11px] px-2 py-1 rounded-md border border-red-200 dark:border-red-900 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40" data-contract-id="${c.id}" title="Terminate this contract">
                                Terminate
                            </button>
                        ` : ''}
                    </div>

                    ${(c.rates ?? []).length ? `
                        <div class="mt-2 pt-2 border-t border-zinc-100 dark:border-zinc-800 space-y-1">
                            ${c.rates.map((r) => `
                                <div class="flex justify-between items-center gap-3 text-xs">
                                    <span class="text-zinc-500 dark:text-zinc-400 truncate">${r.origin_port?.code ?? '-'} → ${r.destination_port?.code ?? '-'} · ${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</span>
                                    <span class="font-medium text-zinc-700 dark:text-zinc-200 shrink-0">${Number(r.final_rate).toLocaleString()}</span>
                                </div>
                            `).join('')}
                        </div>
                    ` : `<p class="text-xs text-zinc-400 mt-2">No rate lines.</p>`}
                </div>
            `).join('');

            document.querySelectorAll('.contract-row').forEach((row) => {
                row.addEventListener('click', function() {
                    window.contractsOpenId = Number(this.dataset.contractId);
                    closemodals();
                    loadPage({
                        title: 'Contracts',
                        link: '/page_contracts'
                    });
                });
            });

            document.querySelectorAll('.contract-terminate-btn').forEach((btn) => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    terminateContractFromCard(Number(this.dataset.contractId), uuid);
                });
            });
        }

        async function terminateContractFromCard(contractId, uuid) {
            const reason = window.prompt('Reason for terminating this contract:');
            if (reason === null) return;

            if (!reason.trim()) {
                showMessage({
                    status: 'error',
                    title: 'Reason required',
                    message: 'Please provide a reason to terminate this contract.'
                });
                return;
            }

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    reason: reason.trim()
                },
                url: `/api/clientContracts/${contractId}/terminate`,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to terminate contract',
                    message: response.message ?? 'An unexpected error occurred.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Contract terminated'
            });
            loadContracts(uuid);
        }

        // ================= PROPOSALS LIST =================
        const PROPOSAL_STATUS_LABEL = {
            1: 'Pending',
            2: 'Approved',
            3: 'Disapproved',
            4: 'Accepted',
            5: 'Rejected'
        };
        const PROPOSAL_STATUS_BADGE = {
            1: 'bg-amber-100 text-amber-600',
            2: 'bg-green-100 text-green-700',
            3: 'bg-red-100 text-red-600',
            4: 'bg-blue-100 text-blue-700',
            5: 'bg-zinc-200 text-zinc-600',
        };

        let currentProposalsPage = 1;
        let currentProposalsList = [];

        async function loadProposals(uuid, page = 1) {
            currentProposalsPage = page;
            const container = document.getElementById('cdProposalsContainer');
            container.innerHTML = `<p class="text-sm text-zinc-400">Loading...</p>`;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/${uuid}/proposals?page=${page}&per_page=5`
            });

            if (!response.success) {
                container.innerHTML =
                    `<p class="text-sm text-red-400 text-center py-6">Unable to load proposals.</p>`;
                return;
            }

            const meta = response.data;
            const proposals = meta.data ?? [];
            currentProposalsList = proposals;

            if (!proposals.length) {
                container.innerHTML =
                    `<p class="text-sm text-zinc-400 text-center py-6">No proposals yet.</p>`;
                renderProposalsPagination(null);
                return;
            }

            container.innerHTML = proposals.map((p) => buildProposalCard(p)).join('');
            wireProposalCards(uuid);
            renderProposalsPagination(meta);
        }

        function buildProposalCard(p) {
            const badgeClass = PROPOSAL_STATUS_BADGE[p.status] ?? 'bg-zinc-100 text-zinc-500';
            const isPending = p.status === 1;
            const hasActiveContract = Boolean(p.active_contract);

            return `
        <div class="border rounded-xl p-4" data-proposal-id="${p.id}" data-proposal-status="${p.status}">
            <div class="flex justify-between items-center mb-2 gap-3">
                <div class="flex items-center gap-2">
                    <p class="font-semibold text-sm">${p.code}</p>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full ${badgeClass}">${PROPOSAL_STATUS_LABEL[p.status] ?? 'Unknown'}</span>
                </div>
                <p class="text-xs text-zinc-400 whitespace-nowrap">${formatDateTime(p.created_at)}</p>
            </div>

            ${p.decided_by ? `
                <p class="text-xs text-zinc-500 mb-2">${PROPOSAL_STATUS_LABEL[p.status]} by ${p.decided_by.name} on ${formatDateTime(p.decided_at)}${p.decision_remarks ? ' — ' + p.decision_remarks : ''}</p>
            ` : ''}

            <table class="w-full text-xs">
                <thead class="text-zinc-400 uppercase">
                    <tr>
                        <th class="text-left py-1">Route</th>
                        <th class="text-left py-1">Container</th>
                        <th class="text-right py-1">Base Rate</th>
                        <th class="text-right py-1">Discount</th>
                        <th class="text-right py-1">Final Rate</th>
                        <th class="text-right py-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    ${p.rates.map((r) => `
                        <tr class="border-t" data-rate-id="${r.id}">
                            <td class="py-1.5">${r.origin_port?.code ?? '-'} → ${r.destination_port?.code ?? '-'}</td>
                            <td class="py-1.5">${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</td>
                            <td class="py-1.5 text-right">${Number(r.base_rate).toLocaleString()}</td>
                            <td class="py-1.5 text-right">${r.discount_type ? (r.discount_type === 'percentage' ? r.discount_value + '%' : Number(r.discount_value).toLocaleString()) : '-'}</td>
                            <td class="py-1.5 text-right font-semibold">${Number(r.final_rate).toLocaleString()}</td>
                            <td class="py-1.5 text-right whitespace-nowrap">
                                ${isPending ? `<button type="button" class="rate-delete-btn text-zinc-400 hover:text-red-600 font-medium" data-rate-id="${r.id}">Delete</button>` : ''}
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>

            ${p.status === 2 && p.can_upload_signed ? `
                <div class="flex items-center gap-2 mt-3 pt-3 border-t">
                    <input type="file" class="cpm-signed-file flex-1 border rounded-lg px-2 py-1.5 text-xs dark:text-zinc-900" accept=".pdf,.jpg,.jpeg,.png">
                    <button type="button" class="cpm-upload-signed-btn text-xs px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white shrink-0" data-proposal-id="${p.id}">
                        Upload &amp; Accept
                    </button>
                </div>
            ` : ''}

            <div class="flex justify-end flex-wrap gap-2 mt-3 pt-3 border-t">
                ${isPending ? `
                    <button type="button" class="add-container-btn text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100" data-proposal-id="${p.id}">
                        + Add Container
                    </button>
                ` : ''}
                ${[2, 4].includes(p.status) ? `
                    <a href="/api/clientProposals/${p.id}/pdf" target="_blank"
                       class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100 text-zinc-700">
                        Download
                    </a>
                ` : ''}
                ${isPending && p.can_approve ? `
                    <button type="button" class="cpm-disapprove-btn text-xs px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white" data-proposal-id="${p.id}">
                        Disapprove
                    </button>
                    <button type="button" class="cpm-approve-btn text-xs px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white" data-proposal-id="${p.id}">
                        Approve
                    </button>
                ` : ''}
                ${[1, 2].includes(p.status) && p.can_reject ? `
                    <button type="button" class="cpm-reject-btn text-xs px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white" data-proposal-id="${p.id}">
                        Reject
                    </button>
                ` : ''}
                ${p.status === 4 && !hasActiveContract ? `
                    <button type="button" class="cpm-create-contract-btn text-xs px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white" data-proposal-id="${p.id}">
                        Create Contract
                    </button>
                ` : ''}
                ${hasActiveContract ? `
                    <button type="button" class="cpm-view-contract-btn text-xs px-3 py-1.5 rounded-lg border" data-contract-id="${p.active_contract.id}">
                        View Contract
                    </button>
                ` : ''}
            </div>
        </div>
    `;
        }

        function wireProposalCards(uuid) {
            document.querySelectorAll('.rate-delete-btn').forEach((btn) => {
                btn.addEventListener('click', async function() {
                    const confirmed = await customConfirm(
                        'Remove this container from the proposal?');
                    if (!confirmed) return;

                    const response = await apiCall({
                        mode: 'DELETE',
                        isJson: true,
                        payload: {},
                        url: `/api/clientMasters/proposals/rates/${this.dataset.rateId}`,
                    });

                    if (!response.success) {
                        showMessage({
                            status: 'error',
                            title: 'Error',
                            message: 'Unable to delete this container.'
                        });
                        return;
                    }

                    showMessage({
                        status: 'success',
                        title: 'Container removed'
                    });
                    loadProposals(uuid, currentProposalsPage);
                });
            });

            document.querySelectorAll('.add-container-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    openAddContainerModal(this.dataset.proposalId);
                });
            });

            document.querySelectorAll('.cpm-approve-btn').forEach((btn) => {
                btn.addEventListener('click', () => proposalDecisionAction(uuid, btn.dataset
                    .proposalId, 'approve', 'Proposal approved'));
            });
            document.querySelectorAll('.cpm-disapprove-btn').forEach((btn) => {
                btn.addEventListener('click', () => proposalDecisionAction(uuid, btn.dataset
                    .proposalId, 'disapprove', 'Proposal disapproved'));
            });
            document.querySelectorAll('.cpm-reject-btn').forEach((btn) => {
                btn.addEventListener('click', async function() {
                    const confirmed = await customConfirm(
                        'Reject this proposal? This cannot be undone.');
                    if (confirmed) proposalDecisionAction(uuid, this.dataset.proposalId,
                        'reject', 'Proposal rejected');
                });
            });

            document.querySelectorAll('.cpm-upload-signed-btn').forEach((btn) => {
                btn.addEventListener('click', async function() {
                    const card = this.closest('[data-proposal-id]');
                    const fileInput = card.querySelector('.cpm-signed-file');

                    if (!fileInput.files.length) {
                        showMessage({
                            status: 'error',
                            title: 'Select a file first'
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('signed_document', fileInput.files[0]);

                    const response = await apiCall({
                        mode: 'POST',
                        isJson: false,
                        payload: formData,
                        url: `/api/clientProposals/${this.dataset.proposalId}/attachSigned`,
                        button: this,
                    });

                    if (!response.success) {
                        showMessage({
                            status: 'error',
                            title: 'Error',
                            message: response.message ?? 'Upload failed.'
                        });
                        return;
                    }

                    showMessage({
                        status: 'success',
                        title: 'Signed document uploaded — proposal accepted!'
                    });
                    loadProposals(uuid, currentProposalsPage);
                });
            });

            document.querySelectorAll('.cpm-create-contract-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    openCreateContractModal(this.dataset.proposalId);
                });
            });

            document.querySelectorAll('.cpm-view-contract-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    window.contractsOpenId = Number(this.dataset.contractId);
                    closemodals();
                    loadPage({
                        title: 'Contracts',
                        link: '/page_contracts'
                    });
                });
            });
        }

        async function proposalDecisionAction(uuid, proposalId, action, successTitle) {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/clientProposals/${proposalId}/${action}`,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: response.message ?? 'Action failed.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: successTitle
            });
            loadProposals(uuid, currentProposalsPage);
        }

        function renderProposalsPagination(meta) {
            const el = document.getElementById('cdProposalsPagination');
            if (!el) return;

            if (!meta || meta.last_page <= 1) {
                el.innerHTML = '';
                return;
            }

            el.innerHTML = `
        <div class="flex items-center justify-between px-1 py-2">
            <p class="text-xs text-zinc-400">Showing ${meta.from ?? 0}-${meta.to ?? 0} of ${meta.total ?? 0}</p>
            <div class="flex items-center gap-1">
                <button type="button" id="proposalsPrevBtn" ${meta.prev_page_url ? '' : 'disabled'}
                    class="px-2 py-1 text-xs rounded-md text-zinc-600 hover:bg-zinc-100 disabled:opacity-30">Prev</button>
                <span class="text-xs text-zinc-500 px-1">${meta.current_page} / ${meta.last_page}</span>
                <button type="button" id="proposalsNextBtn" ${meta.next_page_url ? '' : 'disabled'}
                    class="px-2 py-1 text-xs rounded-md text-zinc-600 hover:bg-zinc-100 disabled:opacity-30">Next</button>
            </div>
        </div>
    `;

            document.getElementById('proposalsPrevBtn')?.addEventListener('click', () => {
                if (meta.prev_page_url) loadProposals(currentClientUuid, meta.current_page - 1);
            });
            document.getElementById('proposalsNextBtn')?.addEventListener('click', () => {
                if (meta.next_page_url) loadProposals(currentClientUuid, meta.current_page + 1);
            });
        }

        // ================= CREATE CONTRACT (Accepted proposal, rate-override-confirm) =================
        // Same flow as proposals.blade.php's createContractModal - rate lines
        // are copied from the proposal read-only by default; a pencil unlocks
        // a row for a confirmed edit before saving.
        let currentProposalForContract = null;
        let ccRateOverrides = {};
        let ccEditingRateId = null;

        function openCreateContractModal(proposalId) {
            currentProposalForContract = currentProposalsList.find((p) => String(p.id) === String(
                proposalId));
            if (!currentProposalForContract) return;

            ccRateOverrides = {};
            ccEditingRateId = null;

            document.getElementById('ccProposalCode').textContent = currentProposalForContract.code;
            document.getElementById('ccValidFrom').value = '';
            document.getElementById('ccValidTo').value = '';
            document.getElementById('ccSignedDate').value = '';
            renderContractRatesTable();

            initModal({
                modalId: 'createContractModal'
            });
        }

        function ccOriginalValues(rate) {
            return {
                base_rate: Number(rate.base_rate),
                discount_type: rate.discount_type ?? null,
                discount_value: Number(rate.discount_value ?? 0),
                final_rate: Number(rate.final_rate),
            };
        }

        function ccCurrentValues(rate) {
            return ccRateOverrides[rate.id] ? {
                ...ccRateOverrides[rate.id]
            } : ccOriginalValues(rate);
        }

        function ccDiscountDisplay(values) {
            if (!values.discount_type) return '-';
            return values.discount_type === 'percentage' ?
                `${values.discount_value}%` :
                Number(values.discount_value).toLocaleString();
        }

        function renderCcRateRow(rate, editing) {
            const lane = `${rate.origin_port?.code ?? '-'} → ${rate.destination_port?.code ?? '-'}`;
            const variant = `${rate.container?.name ?? '-'} / ${rate.container_class?.class ?? '-'} / ${rate.container_size?.size ?? '-'}`;
            const values = ccCurrentValues(rate);
            const edited = Boolean(ccRateOverrides[rate.id]);

            if (!editing) {
                return `
                    <tr data-rate-id="${rate.id}">
                        <td class="py-1.5 px-2">${lane}</td>
                        <td class="py-1.5 px-2">${variant}</td>
                        <td class="py-1.5 px-2 text-right">${Number(values.base_rate).toLocaleString()}</td>
                        <td class="py-1.5 px-2 text-right">${ccDiscountDisplay(values)}</td>
                        <td class="py-1.5 px-2 text-right font-semibold">
                            ${Number(values.final_rate).toLocaleString()}
                            ${edited ? '<span class="ml-1 text-[10px] font-normal text-amber-600">(edited)</span>' : ''}
                        </td>
                        <td class="py-1.5 px-2 text-right">
                            <button type="button" class="cc-edit-btn text-base leading-none px-1.5 py-1 rounded text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Edit this rate">✎</button>
                        </td>
                    </tr>`;
            }

            return `
                <tr data-rate-id="${rate.id}">
                    <td class="py-1.5 px-2">${lane}</td>
                    <td class="py-1.5 px-2">${variant}</td>
                    <td class="py-1.5 px-2">
                        <input type="number" step="0.01" min="0" class="cc-input-base w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.base_rate}">
                    </td>
                    <td class="py-1.5 px-2">
                        <div class="flex items-center gap-1 justify-end">
                            <select class="cc-input-disctype border rounded px-1 py-1 text-xs dark:text-zinc-900">
                                <option value="" ${!values.discount_type ? 'selected' : ''}>None</option>
                                <option value="percentage" ${values.discount_type === 'percentage' ? 'selected' : ''}>%</option>
                                <option value="fixed" ${values.discount_type === 'fixed' ? 'selected' : ''}>Fixed</option>
                            </select>
                            <input type="number" step="0.01" min="0" class="cc-input-discval w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.discount_value}">
                        </div>
                    </td>
                    <td class="py-1.5 px-2">
                        <input type="number" step="0.01" min="0" class="cc-input-final w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.final_rate}">
                    </td>
                    <td class="py-1.5 px-2 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button" class="cc-apply-btn text-base leading-none px-1.5 py-1 rounded text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-950/40" title="Apply">✓</button>
                            <button type="button" class="cc-cancel-btn text-base leading-none px-1.5 py-1 rounded text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Cancel">✕</button>
                        </div>
                    </td>
                </tr>`;
        }

        function renderContractRatesTable() {
            document.getElementById('ccRatesBody').innerHTML = (currentProposalForContract?.rates ??
                    [])
                .map((r) => renderCcRateRow(r, ccEditingRateId === r.id))
                .join('');
        }

        // Base rate / discount type / discount value all feed Final Rate, same
        // as the Add Proposal / Add Contract row builders elsewhere - without
        // this, editing the discount changed nothing the user could see or
        // save, since Final Rate is what actually gets sent as the override.
        function recomputeCcFinalRate(row) {
            const base = parseFloat(row.querySelector('.cc-input-base').value) || 0;
            const type = row.querySelector('.cc-input-disctype').value;
            const value = parseFloat(row.querySelector('.cc-input-discval').value) || 0;
            const finalInput = row.querySelector('.cc-input-final');

            let final = base;
            if (type === 'percentage') final = base - (base * value / 100);
            if (type === 'fixed') final = Math.max(0, base - value);

            finalInput.value = final.toFixed(2);
        }

        document.getElementById('ccRatesBody').addEventListener('input', function(e) {
            if (e.target.matches('.cc-input-base, .cc-input-discval')) {
                recomputeCcFinalRate(e.target.closest('tr'));
            }
        });

        document.getElementById('ccRatesBody').addEventListener('change', function(e) {
            if (e.target.matches('.cc-input-disctype')) {
                recomputeCcFinalRate(e.target.closest('tr'));
            }
        });

        document.getElementById('ccRatesBody').addEventListener('click', async function(e) {
            const row = e.target.closest('tr');
            if (!row) return;

            const rateId = Number(row.dataset.rateId);
            const rate = (currentProposalForContract?.rates ?? []).find((r) => r.id === rateId);
            if (!rate) return;

            if (e.target.closest('.cc-edit-btn')) {
                ccEditingRateId = rateId;
                renderContractRatesTable();
                return;
            }

            if (e.target.closest('.cc-cancel-btn')) {
                ccEditingRateId = null;
                renderContractRatesTable();
                return;
            }

            if (e.target.closest('.cc-apply-btn')) {
                const newValues = {
                    base_rate: Number(row.querySelector('.cc-input-base').value),
                    discount_type: row.querySelector('.cc-input-disctype').value || null,
                    discount_value: Number(row.querySelector('.cc-input-discval').value || 0),
                    final_rate: Number(row.querySelector('.cc-input-final').value),
                };
                const original = ccOriginalValues(rate);
                const changed = newValues.base_rate !== original.base_rate ||
                    newValues.discount_type !== original.discount_type ||
                    newValues.discount_value !== original.discount_value ||
                    newValues.final_rate !== original.final_rate;

                if (changed) {
                    const confirmed = await customConfirm('Apply this rate change?');
                    if (!confirmed) return;
                    ccRateOverrides[rateId] = newValues;
                } else {
                    delete ccRateOverrides[rateId];
                }

                ccEditingRateId = null;
                renderContractRatesTable();
            }
        });

        document.getElementById('ccSaveBtn').addEventListener('click', async function() {
            const validFrom = document.getElementById('ccValidFrom').value;
            const validTo = document.getElementById('ccValidTo').value;

            if (!validFrom || !validTo) {
                showMessage({
                    status: 'error',
                    title: 'Valid From and Valid To are required'
                });
                return;
            }

            const payload = {
                signed_date: document.getElementById('ccSignedDate').value || null,
                valid_from: validFrom,
                valid_to: validTo,
                rate_overrides: ccRateOverrides,
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/clientProposals/${currentProposalForContract.id}/contract`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to create contract',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Contract created'
            });
            closemodals();
            loadProposals(currentClientUuid, currentProposalsPage);
            loadContracts(currentClientUuid);
        });

        // ================= ADD / APPEND PROPOSAL (row builder) =================
        // Mirrors crm.blade.php's LeadAddProposalModal row builder exactly -
        // same container/class/size cascade and rate auto-lookup, adapted to
        // post against the client-scoped endpoints instead of the lead ones.
        let proposalModalContext = {
            mode: 'create',
            proposalId: null
        };

        let cpPortsOptionsHtml = '';
        let cpContainerVariantsData = [];
        let cpLookupsLoaded = false;

        async function loadCpContainerLookups() {
            if (cpLookupsLoaded) return;

            const [portsRes, variantsRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containers/variants'
                }),
            ]);

            if (portsRes.success) {
                cpPortsOptionsHtml = portsRes.data.data
                    .map((p) => `<option value="${p.port_id}">${p.code} - ${p.name}</option>`)
                    .join('');
            }
            if (variantsRes.success) {
                cpContainerVariantsData = variantsRes.data;
            }
            cpLookupsLoaded = true;
        }

        function cpUniqueContainerOptions() {
            const seen = new Set();
            return cpContainerVariantsData
                .filter((v) => {
                    if (seen.has(v.container.id)) return false;
                    seen.add(v.container.id);
                    return true;
                })
                .map((v) => `<option value="${v.container.id}">${v.container.name}</option>`)
                .join('');
        }

        function addCpProposalRow(containerId = 'cpRatesContainer') {
            const wrap = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'border rounded-lg p-3 space-y-2';
            div.dataset.row = '';
            div.innerHTML = `
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Origin</label>
                        <select data-field="origin_port_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${cpPortsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination</label>
                        <select data-field="destination_port_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${cpPortsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Container</label>
                        <select data-field="container_id" class="container-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${cpUniqueContainerOptions()}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Class</label>
                        <select data-field="container_class_id" class="class-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select container first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Size</label>
                        <select data-field="container_size_id" class="size-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select class first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Rate (FRT)</label>
                        <input type="text" data-field="base_rate" readonly class="base-rate w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" value="0.00">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Discount Type</label>
                        <select data-field="discount_type" class="discount-type w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">None</option>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Discount Value</label>
                        <input type="number" step="0.01" min="0" data-field="discount_value" class="discount-value w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" value="0">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Final Rate</label>
                        <input type="text" data-field="final_rate" readonly class="final-rate w-full border border-blue-200 dark:border-blue-800 rounded-lg px-2 py-1.5 text-sm bg-blue-50 dark:bg-blue-900/40 text-zinc-900 dark:text-blue-100 font-semibold" value="0.00">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="remove-row text-red-500 text-xs">✕ Remove container</button>
                </div>
                <input type="hidden" data-field="container_variant_id">
            `;
            wrap.appendChild(div);
            wireCpRow(div);
        }

        function wireCpRow(row) {
            const originSel = row.querySelector('[data-field="origin_port_id"]');
            const destSel = row.querySelector('[data-field="destination_port_id"]');
            const containerSel = row.querySelector('.container-select');
            const classSel = row.querySelector('.class-select');
            const sizeSel = row.querySelector('.size-select');
            const variantInput = row.querySelector('[data-field="container_variant_id"]');
            const baseRateInput = row.querySelector('.base-rate');
            const discountTypeSel = row.querySelector('.discount-type');
            const discountValueInput = row.querySelector('.discount-value');
            const finalRateInput = row.querySelector('.final-rate');

            containerSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const classes = [...new Map(
                    cpContainerVariantsData
                    .filter((v) => String(v.container.id) === containerId)
                    .map((v) => [v.container_class.id, v.container_class])
                ).values()];

                classSel.innerHTML = `<option value="">Select</option>` +
                    classes.map((c) => `<option value="${c.id}">${c.class}</option>`).join('');
                sizeSel.innerHTML = `<option value="">Select class first</option>`;
                variantInput.value = '';
                resetCpRate(baseRateInput, finalRateInput);
            });

            classSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const classId = classSel.value;
                const sizes = cpContainerVariantsData.filter(
                    (v) => String(v.container.id) === containerId && String(v.container_class.id) ===
                    classId
                );

                sizeSel.innerHTML = `<option value="">Select</option>` +
                    sizes.map((v) =>
                        `<option value="${v.container_size.id}" data-variant-id="${v.id}">${v.container_size.size}</option>`
                    ).join('');
                variantInput.value = '';
                resetCpRate(baseRateInput, finalRateInput);
            });

            sizeSel.addEventListener('change', () => {
                const selected = sizeSel.options[sizeSel.selectedIndex];
                variantInput.value = selected?.dataset.variantId ?? '';
                lookupCpRate(row);
            });

            [originSel, destSel].forEach((sel) => sel.addEventListener('change', () => lookupCpRate(
                row)));
            discountTypeSel.addEventListener('change', () => recomputeCpFinalRate(row));
            discountValueInput.addEventListener('input', () => recomputeCpFinalRate(row));

            row.querySelector('.remove-row').addEventListener('click', () => row.remove());

            function resetCpRate(baseEl, finalEl) {
                baseEl.value = '0.00';
                finalEl.value = '0.00';
            }
        }

        async function lookupCpRate(row) {
            const originId = row.querySelector('[data-field="origin_port_id"]').value;
            const destId = row.querySelector('[data-field="destination_port_id"]').value;
            const variantId = row.querySelector('[data-field="container_variant_id"]').value;
            const baseRateInput = row.querySelector('.base-rate');

            if (!originId || !destId || !variantId) return;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientProposals/rateLookup?origin_port_id=${originId}&destination_port_id=${destId}&container_variant_id=${variantId}`,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Rate Not Found',
                    message: response.message ?? 'No rate configured for this combination.'
                });
                baseRateInput.value = '0.00';
                recomputeCpFinalRate(row);
                return;
            }

            baseRateInput.value = Number(response.data.frt).toFixed(2);
            recomputeCpFinalRate(row);
        }

        function recomputeCpFinalRate(row) {
            const base = parseFloat(row.querySelector('.base-rate').value) || 0;
            const type = row.querySelector('.discount-type').value;
            const value = parseFloat(row.querySelector('.discount-value').value) || 0;
            const finalRateInput = row.querySelector('.final-rate');

            let final = base;
            if (type === 'percentage') final = base - (base * value / 100);
            if (type === 'fixed') final = Math.max(0, base - value);

            finalRateInput.value = final.toFixed(2);
        }

        document.getElementById('cpAddRowBtn').addEventListener('click', addCpProposalRow);

        document.getElementById('cdAddProposalBtn').addEventListener('click', async function() {
            proposalModalContext = {
                mode: 'create',
                proposalId: null
            };
            document.getElementById('cpRatesContainer').innerHTML = '';
            await loadCpContainerLookups();
            addCpProposalRow();
            initSideModal({
                modalId: 'AddClientProposalModal'
            });
        });

        function openAddContainerModal(proposalId) {
            proposalModalContext = {
                mode: 'append',
                proposalId
            };
            document.getElementById('cpRatesContainer').innerHTML = '';
            loadCpContainerLookups().then(() => addCpProposalRow());
            initSideModal({
                modalId: 'AddClientProposalModal'
            });
        }

        function collectCpRateRows(containerId) {
            const rows = Array.from(document.querySelectorAll(`#${containerId} [data-row]`));

            return rows.map((row) => ({
                origin_port_id: row.querySelector('[data-field="origin_port_id"]').value,
                destination_port_id: row.querySelector('[data-field="destination_port_id"]').value,
                container_id: row.querySelector('[data-field="container_id"]').value,
                container_class_id: row.querySelector('[data-field="container_class_id"]').value,
                container_size_id: row.querySelector('[data-field="container_size_id"]').value,
                container_variant_id: row.querySelector('[data-field="container_variant_id"]').value,
                base_rate: parseFloat(row.querySelector('.base-rate').value) || 0,
                discount_type: row.querySelector('.discount-type').value || null,
                discount_value: parseFloat(row.querySelector('.discount-value').value) || 0,
                final_rate: parseFloat(row.querySelector('.final-rate').value) || 0,
            }));
        }

        function rateRowsIncomplete(rates) {
            return rates.some((r) => !r.origin_port_id || !r.destination_port_id || !r
                .container_variant_id);
        }

        document.getElementById('cpSaveBtn').addEventListener('click', async function() {
            const rates = collectCpRateRows('cpRatesContainer');

            if (!rates.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one container line.'
                });
                return;
            }

            if (rateRowsIncomplete(rates)) {
                showMessage({
                    status: 'error',
                    title: 'Incomplete',
                    message: 'Complete origin, destination, and container for every line.'
                });
                return;
            }

            const url = proposalModalContext.mode === 'append' ?
                `/api/clientProposals/${proposalModalContext.proposalId}/rates` :
                `/api/clientMasters/${currentClientUuid}/proposals`;

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    rates
                },
                url,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: response.message ? 'Error' : 'Error Saving Proposal',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: proposalModalContext.mode === 'append' ? 'Container(s) added!' :
                    'Proposal saved!'
            });
            closeSideModal('AddClientProposalModal');
            loadProposals(currentClientUuid, proposalModalContext.mode === 'append' ?
                currentProposalsPage : 1);
        });

        // ================= ADD CONTRACT (row builder, no proposal behind it) =================
        // Only reachable from this modal - the Proposals/Contracts pages only
        // ever create a contract by converting an Accepted proposal.
        document.getElementById('acAddRowBtn').addEventListener('click', () => addCpProposalRow(
            'acRatesContainer'));

        document.getElementById('cdAddContractBtn').addEventListener('click', async function() {
            document.getElementById('acValidFrom').value = '';
            document.getElementById('acValidTo').value = '';
            document.getElementById('acSignedDate').value = '';
            document.getElementById('acRatesContainer').innerHTML = '';
            await loadCpContainerLookups();
            addCpProposalRow('acRatesContainer');
            initSideModal({
                modalId: 'AddClientContractModal'
            });
        });

        document.getElementById('acSaveBtn').addEventListener('click', async function() {
            const validFrom = document.getElementById('acValidFrom').value;
            const validTo = document.getElementById('acValidTo').value;

            if (!validFrom || !validTo) {
                showMessage({
                    status: 'error',
                    title: 'Valid From and Valid To are required'
                });
                return;
            }

            const rates = collectCpRateRows('acRatesContainer');

            if (!rates.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one container line.'
                });
                return;
            }

            if (rateRowsIncomplete(rates)) {
                showMessage({
                    status: 'error',
                    title: 'Incomplete',
                    message: 'Complete origin, destination, and container for every line.'
                });
                return;
            }

            const payload = {
                client_proposal_id: null,
                signed_date: document.getElementById('acSignedDate').value || null,
                valid_from: validFrom,
                valid_to: validTo,
                rates,
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/clientMasters/${currentClientUuid}/contracts`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to create contract',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Contract created!'
            });
            closeSideModal('AddClientContractModal');
            loadContracts(currentClientUuid);
        });

        // ================= INIT =================
        fillAddressTypeOptions();
    })();
</script>
