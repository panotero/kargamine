<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Clients Master Data</h1>
            <p class="text-zinc-500">Manage company master file records</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400">Incomplete</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countIncompleteStat">0</p>
            </div>
            <button id="btnNewClient" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                + New Client
            </button>
        </div>
    </div>

    {{-- Status filter chips - a 2-state (Complete/Incomplete) binary, so a
         directional stepper (like Proposals' 5-status workflow strip) would
         be dishonest about what the data represents. --}}
    <section class="w-full my-5">
        <div class="flex items-center flex-wrap gap-2">
            <button type="button"
                class="clientStatusBtn rounded-full px-3 py-1.5 text-xs font-semibold border border-dashed border-zinc-300 text-zinc-500 dark:border-zinc-600 dark:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1"
                data-status="all">
                All <span id="countAll">0</span>
            </button>
            <button type="button"
                class="clientStatusBtn rounded-full px-3 py-1.5 text-xs font-semibold border border-green-300 text-green-600 dark:border-green-700 dark:text-green-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1"
                data-status="complete">
                Complete <span id="countComplete">0</span>
            </button>
            <button type="button"
                class="clientStatusBtn rounded-full px-3 py-1.5 text-xs font-semibold border border-amber-300 text-amber-600 dark:border-amber-700 dark:text-amber-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-1"
                data-status="incomplete">
                Incomplete <span id="countIncomplete">0</span>
            </button>
        </div>
    </section>

    <x-table id="tableClientMasters" />
</div>

{{-- Complete-client detail modal - left rail (read-only reference facts) +
     right pane with a tab bar. Company Info & Addresses is the initial tab. --}}
<x-modal id="ClientDetailModal" maxWidth="lg:max-w-[78vw]">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold" id="cdClientName">-</p>
            <p class="text-xs text-zinc-400" id="cdClientCode">-</p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="flex" style="max-height: 75vh;">

        {{-- ================= LEFT RAIL - read-only reference data ================= --}}
        {{-- Company section (Mnemonic/Category/Classification/Industry) removed -
             it duplicated the editable Company Info & Addresses tab, which is now
             the modal's initial tab. Ownership (CSR/Account Manager) stays - it's
             not shown anywhere else in this modal. --}}
        <div class="w-64 shrink-0 border-r border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4 space-y-6 overflow-y-auto">
            <div>
                <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-widest mb-3">Ownership</p>
                <div id="cdRailOwnership" class="space-y-3"></div>
            </div>
        </div>

        {{-- ================= RIGHT PANE - tab bar + panes ================= --}}
        <div class="flex-1 min-w-0 flex flex-col">
            <div class="flex gap-1 border-b border-zinc-200 dark:border-zinc-700 px-3 pt-2 shrink-0">
                <button type="button" data-tab="company"
                    class="cd-tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">Company
                    Info &amp; Addresses</button>
                <button type="button" data-tab="proposals"
                    class="cd-tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">Proposals</button>
                <button type="button" data-tab="contracts"
                    class="cd-tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">Contracts</button>
                <button type="button" data-tab="contacts"
                    class="cd-tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">Contacts</button>
                <button type="button" data-tab="finance"
                    class="cd-tab-btn px-3 py-2 text-sm font-medium border-b-2 border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200">Finance
                    &amp; Commodity</button>
            </div>

            <div class="flex-1 overflow-y-auto p-5">

                {{-- ================= TAB: PROPOSALS ================= --}}
                <div class="cd-tab-pane hidden" data-tab-pane="proposals">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Proposals</p>
                        <button id="cdAddProposalBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ Add
                            Proposal</button>
                    </div>
                    <div id="cdProposalsContainer" class="space-y-3"></div>
                    <div id="cdProposalsPagination"></div>
                </div>

                {{-- ================= TAB: CONTRACTS ================= --}}
                <div class="cd-tab-pane hidden" data-tab-pane="contracts">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Contracts</p>
                        <button id="cdAddContractBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ Add
                            Contract</button>
                    </div>
                    <div id="cdContractsContainer" class="space-y-2"></div>
                </div>

                {{-- ================= TAB: COMPANY INFO & ADDRESSES ================= --}}
                <div class="cd-tab-pane" data-tab-pane="company">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Company Info &amp;
                            Addresses</p>
                        <div class="flex gap-2">
                            <button type="button" id="ciEditBtn"
                                class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 dark:text-zinc-200">✎
                                Edit</button>
                            <button type="button" id="ciSaveBtn"
                                class="hidden text-xs px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save</button>
                            <button type="button" id="ciCancelBtn"
                                class="hidden text-xs px-3 py-1.5 rounded-lg border dark:text-zinc-200">Cancel</button>
                        </div>
                    </div>

                    {{-- ---------- READ-ONLY VIEW ---------- --}}
                    <div id="ciReadView" class="space-y-4 text-sm">
                        <div id="ciCompanyReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>

                        <div class="border-t pt-3">
                            <p class="text-[11px] font-semibold text-zinc-400 uppercase mb-2">Addresses</p>
                            <div id="cdAddressesReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                        </div>
                    </div>

                    {{-- ---------- EDIT VIEW ---------- --}}
                    <div id="ciEditView" class="hidden space-y-6 text-sm">

                        {{-- Company Information --}}
                        <div>
                            <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Company Information</p>
                            <form id="cdStage1Form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Client Code</label>
                                    <input type="text" name="customer_code" readonly
                                        class="w-full border rounded-lg px-3 py-2 text-sm mt-1 bg-zinc-50 text-zinc-600 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Client Mnemonic</label>
                                    <input type="text" name="client_mnemonic"
                                        class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Client/Business
                                        Name</label>
                                    <input type="text" name="company_name"
                                        class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Client Category</label>
                                    <select name="client_category"
                                        class="cdClientCategorySelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                        <option value="">Select Client Category</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Client
                                        Classification</label>
                                    <select name="client_classification"
                                        class="cdClientClassificationSelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                        <option value="">Select Client Classification</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Client Industry</label>
                                    <select name="industry"
                                        class="cdIndustrySelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                        <option value="">Select Industry</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-zinc-400 uppercase">Industry
                                        Sub-Category</label>
                                    <select name="industry_subcategory" disabled
                                        class="cdIndustrySubcategorySelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900 disabled:opacity-50 disabled:bg-zinc-100">
                                        <option value="">Select Industry First</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        {{-- Addresses --}}
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <p class="font-semibold text-zinc-700 dark:text-zinc-300">Address(es) <span
                                        class="req-asterisk">*</span></p>
                                <button type="button" id="cdAddAddressBtn"
                                    class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+
                                    Add Address</button>
                            </div>
                            <div id="cdAddressesEditContainer" class="space-y-4"></div>
                        </div>
                    </div>
                </div>

                {{-- ================= TAB: CONTACTS ================= --}}
                <div class="cd-tab-pane hidden" data-tab-pane="contacts">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Contacts</p>
                        <div class="flex gap-2">
                            <button type="button" id="ctEditBtn"
                                class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 dark:text-zinc-200">✎
                                Edit</button>
                            <button type="button" id="ctSaveBtn"
                                class="hidden text-xs px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save</button>
                            <button type="button" id="ctCancelBtn"
                                class="hidden text-xs px-3 py-1.5 rounded-lg border dark:text-zinc-200">Cancel</button>
                        </div>
                    </div>

                    {{-- ---------- READ-ONLY VIEW ---------- --}}
                    <div id="ctReadView" class="text-sm">
                        <div id="cdContactsReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                    </div>

                    {{-- ---------- EDIT VIEW ---------- --}}
                    <div id="ctEditView" class="hidden text-sm">
                        <div class="flex justify-end mb-3">
                            <button type="button" id="cdAddContactBtn"
                                class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+
                                Add Contact</button>
                        </div>
                        <div id="cdContactsEditContainer" class="space-y-3"></div>
                    </div>
                </div>

                {{-- ================= TAB: FINANCE & COMMODITY ================= --}}
                <div class="cd-tab-pane hidden" data-tab-pane="finance">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Finance &amp;
                            Commodity</p>
                        <div class="flex gap-2">
                            <button type="button" id="fnEditBtn"
                                class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 dark:text-zinc-200">✎
                                Edit</button>
                            <button type="button" id="fnSaveBtn"
                                class="hidden text-xs px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save</button>
                            <button type="button" id="fnCancelBtn"
                                class="hidden text-xs px-3 py-1.5 rounded-lg border dark:text-zinc-200">Cancel</button>
                        </div>
                    </div>

                    {{-- ---------- READ-ONLY VIEW ---------- --}}
                    <div id="fnReadView" class="space-y-4 text-sm">
                        <div>
                            <p class="text-[11px] font-semibold text-zinc-400 uppercase mb-2">Finance</p>
                            <div id="cdFinanceReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                        </div>
                        <div class="border-t pt-3">
                            <p class="text-[11px] font-semibold text-zinc-400 uppercase mb-2">Commodity Type &amp;
                                Maximum Declared Value</p>
                            <div id="cdCommodityReadContainer" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                        </div>
                    </div>

                    {{-- ---------- EDIT VIEW ---------- --}}
                    <div id="fnEditView" class="hidden space-y-6 text-sm">
                        <form id="cdStage3Form" class="space-y-6">
                            <div>
                                <p class="font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Finance</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Client Business
                                            Name</label>
                                        <input type="text" name="finance[client_business_name]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">TIN Number</label>
                                        <input type="text" name="finance[tin_number]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-xs font-medium text-zinc-400 uppercase">TIN Registered
                                            Address</label>
                                        <textarea name="finance[tin_registered_address]" rows="2"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900"></textarea>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Registered Tax
                                            Type</label>
                                        <select name="finance[registered_tax_type]"
                                            class="cdRegisteredTaxTypeSelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                            <option value="">— Select —</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Withholding Tax
                                            Code</label>
                                        <input type="text" name="finance[withholding_tax_code]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Trade Name</label>
                                        <input type="text" name="finance[trade_name]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">TIN Registration
                                            Date</label>
                                        <input type="date" name="finance[tin_registration_date]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Line of
                                            Business</label>
                                        <input type="text" name="finance[line_of_business]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Tax Percent</label>
                                        <input type="number" step="0.01" name="finance[tax_percent]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Withholding Tax
                                            Percent</label>
                                        <input type="number" step="0.01" name="finance[withholding_tax_percent]"
                                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                    </div>
                                    <div id="cdModeOfPaymentField">
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Mode of
                                            Payment</label>
                                        <select name="finance[mode_of_payment]"
                                            class="cdModeOfPaymentSelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                            <option value="">Select Mode of Payment</option>
                                        </select>
                                    </div>
                                    <div id="cdCreditTermsField" class="hidden">
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Credit
                                            Terms</label>
                                        <select name="finance[credit_terms]"
                                            class="cdCreditTermsSelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                            <option value="">Select Credit Terms</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-zinc-400 uppercase">Cargo Release
                                            Order (CRO)</label>
                                        <select name="finance[cro]"
                                            class="cdCroSelect w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                            <option value="">Select CRO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <p class="font-semibold text-zinc-700 dark:text-zinc-300">Commodity Type &amp;
                                        Maximum Declared Value</p>
                                    <button type="button" id="cdAddCommodityBtn"
                                        class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+
                                        Add Commodity</button>
                                </div>
                                <div id="cdCommodityEditContainer" class="space-y-3"></div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
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
            <p class="text-xs text-zinc-400">From proposal <span id="ccProposalCode">-</span> &middot; <span
                    id="ccClientName">-</span></p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="max-h-[65vh] overflow-y-auto p-5 space-y-5">
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Valid From</label>
                <input type="date" id="ccValidFrom"
                    class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Valid To</label>
                <input type="date" id="ccValidTo"
                    class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Signed Date</label>
                <input type="date" id="ccSignedDate"
                    class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
        </div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 mb-2">Rate Lines</p>
            <p class="text-xs text-zinc-400 mb-2">Copied from the accepted proposal. Click <span
                    class="font-medium">✎</span> on a line to correct it before saving.</p>
            <table class="w-full text-xs">
                <thead class="text-zinc-400 uppercase">
                    <tr>
                        <th class="text-left py-1 px-2">Route</th>
                        <th class="text-left py-1 px-2">Container</th>
                        <th class="text-right py-1 px-2">Min Qty</th>
                        <th class="text-right py-1 px-2">Base Rate</th>
                        <th class="text-right py-1 px-2">Adjustment</th>
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

{{-- Terminate Contract reason modal - replaces window.prompt() for a styled,
     app-consistent confirmation flow. --}}
<x-modal id="terminateContractReasonModal">
    <div class="p-5 border-b flex justify-between items-center">
        <p class="text-lg font-semibold">Terminate Contract</p>
        <button class="modal-close">✕</button>
    </div>

    <div class="p-5 space-y-2">
        <label class="text-xs font-medium text-zinc-400 uppercase" for="tcrReasonInput">Reason for
            Termination</label>
        <textarea id="tcrReasonInput" rows="3"
            class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900"
            placeholder="Explain why this contract is being terminated..."></textarea>
    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
        <button id="tcrConfirmBtn" class="px-4 py-2 text-sm rounded-lg bg-red-600 hover:bg-red-700 text-white">
            Terminate
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
                <label class="text-xs font-medium text-zinc-400 uppercase">Valid From <span
                        class="req-asterisk">*</span></label>
                <input type="date" id="acValidFrom"
                    class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
            </div>
            <div>
                <label class="text-xs font-medium text-zinc-400 uppercase">Valid To <span
                        class="req-asterisk">*</span></label>
                <input type="date" id="acValidTo"
                    class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
            </div>
        </div>
        <div>
            <label class="text-xs font-medium text-zinc-400 uppercase">Signed Date</label>
            <input type="date" id="acSignedDate"
                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
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
        fillCdStage1Lovs();

        // Same hardcoded lists as clientMasterForm.blade.php - keep in sync.
        const CD_MODE_OF_PAYMENT_OPTIONS = ['Advance Payment', 'Payment Prior to Release', 'Credit Account'];
        const CD_CREDIT_TERMS_OPTIONS = ['Net 7', 'Net 15', 'Net 30', 'Net 45', 'Net 60', 'Net 90'];
        const CD_CRO_OPTIONS = ['Auto Approval', 'Manual Approval'];

        // { tax_type: rate_percent } lookup built from the active vatRates rows,
        // used to auto-fill Tax Percent when Registered Tax Type changes.
        let cdTaxTypeRatesMap = {};

        // Populated with the full lov_id-carrying list (not just lov_name) so
        // the Industry Sub-Category select can cascade off whichever
        // Industry row is picked - see populateIndustrySubcategoryOptions().
        let cdIndustryData = [];

        async function fillCdStage1Lovs() {
            const fill = (selector, list) => {
                const select = document.querySelector(selector);
                if (select && Array.isArray(list)) {
                    select.insertAdjacentHTML('beforeend', list.map((lov) =>
                        `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join(''));
                }
            };
            const industryList = await apiCall({
                mode: 'GET',
                url: '/api/listofval/industry'
            });
            cdIndustryData = Array.isArray(industryList) ? industryList : [];
            fill('.cdIndustrySelect', cdIndustryData);
            fill('.cdClientCategorySelect', await apiCall({
                mode: 'GET',
                url: '/api/listofval/clientcategory'
            }));
            fill('.cdClientClassificationSelect', await apiCall({
                mode: 'GET',
                url: '/api/listofval/clientclassification'
            }));

            const fillPlain = (selector, list) => {
                const select = document.querySelector(selector);
                if (select) {
                    select.insertAdjacentHTML('beforeend', list.map((v) =>
                        `<option value="${v}">${v}</option>`).join(''));
                }
            };
            fillPlain('.cdModeOfPaymentSelect', CD_MODE_OF_PAYMENT_OPTIONS);
            fillPlain('.cdCreditTermsSelect', CD_CREDIT_TERMS_OPTIONS);
            fillPlain('.cdCroSelect', CD_CRO_OPTIONS);

            const vatRatesResponse = await apiCall({
                mode: 'GET',
                url: '/api/vatRates?per_page=1000'
            });
            if (vatRatesResponse.success && Array.isArray(vatRatesResponse.data?.data)) {
                // Only active rows represent a currently selectable tax type.
                vatRatesResponse.data.data
                    .filter((vr) => vr.is_active)
                    .forEach((vr) => {
                        cdTaxTypeRatesMap[vr.tax_type] = vr.rate_percent;
                    });
                fillPlain('.cdRegisteredTaxTypeSelect', Object.keys(cdTaxTypeRatesMap));
            }
        }

        // Cascades off whichever Industry row is selected, same shape as the
        // origin-location -> origin-port cascade elsewhere in the app.
        async function populateIndustrySubcategoryOptions(select, parentLovId, selected) {
            if (!select) return;
            select.innerHTML = '<option value="">Select Sub-Category</option>';
            select.disabled = !parentLovId;
            if (!parentLovId) return;

            const list = await apiCall({
                mode: 'GET',
                url: `/api/listofval/industrysubcategory?parent_lov_id=${parentLovId}`
            });
            if (Array.isArray(list)) {
                select.insertAdjacentHTML('beforeend', list.map((lov) =>
                    `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join(''));
            }
            if (selected) select.value = selected;
        }

        document.querySelector('.cdIndustrySelect').addEventListener('change', function() {
            const industry = cdIndustryData.find((lov) => lov.lov_name === this.value);
            populateIndustrySubcategoryOptions(
                document.querySelector('.cdIndustrySubcategorySelect'),
                industry?.lov_id ?? null
            );
        });

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
            document.getElementById('countIncompleteStat').textContent = response.counts.incomplete;
        }

        document.getElementById('btnNewClient').addEventListener('click', function() {
            window.clientMasterFormUuid = null;
            loadPage({
                title: 'New Client',
                link: '/page_clientMasterForm'
            });
        });

        // Next-step hint shown on incomplete rows' Stage column - stage4
        // (Ancillary Services) has no UI anywhere in this file, but is
        // handled here gracefully in case a record ever reaches it.
        const CLIENT_NEXT_STAGE_LABEL = {
            1: 'Company Info',
            2: 'Contacts',
            3: 'Finance',
            4: 'Ancillary Services',
        };

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
                    title: 'CSR',
                    key: 'sales_rep.name',
                    render: (r) => r.sales_rep?.name ?? '-'
                },
                {
                    title: 'Stage',
                    key: 'current_stage',
                    render: (r) => {
                        if (r.is_complete) return '4 / 4';
                        const nextStep = CLIENT_NEXT_STAGE_LABEL[r.current_stage] ?? '-';
                        return `
                            <p>Stage ${r.current_stage} / 4</p>
                            <p class="text-[11px] text-amber-600 dark:text-amber-400">Next: ${nextStep} &middot; Resume &rarr;</p>
                        `;
                    }
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
                emptyMessage: () => {
                    const filter = document.querySelector('.clientStatusBtn.ring-2')?.dataset.status ??
                        'all';
                    if (filter === 'all') return 'No clients yet.';
                    return `No ${filter} clients.`;
                },
                afterRenderFunction: (row) => {
                    const data = JSON.parse(row.dataset.row);

                    // Baseline border so incomplete rows' amber cue doesn't
                    // shift layout relative to complete rows.
                    row.classList.add('border-l-2', 'border-transparent');
                    if (!data.is_complete) {
                        row.classList.remove('border-transparent');
                        row.classList.add('border-amber-400', 'dark:border-amber-600');
                    }

                    row.addEventListener('click', function() {
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
                    'ring-2', 'ring-orange-500'));
                this.classList.add('ring-2', 'ring-orange-500');
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
            const c = currentClientData;

            document.getElementById('cdClientName').textContent = c.company_name ?? '-';
            document.getElementById('cdClientCode').textContent = c.customer_code ?? '-';

            renderClientRail(c);
            renderCompanyInfoReadView(c);
            renderContactsReadView(c);
            renderFinanceReadView(c);
            exitCompanyInfoEditMode();
            exitContactsEditMode();
            exitFinanceEditMode();

            switchClientDetailTab('company');

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

        // ================= TAB SWITCHING =================
        // Tab switching only toggles which pane has the `hidden` class - it
        // never touches any tab's own edit/read toggle state, so an
        // in-progress edit on another tab is left alone in the background
        // rather than silently discarded when the user switches tabs.
        function switchClientDetailTab(tab) {
            document.querySelectorAll('.cd-tab-btn').forEach((btn) => {
                const active = btn.dataset.tab === tab;
                btn.classList.toggle('border-orange-500', active);
                btn.classList.toggle('text-orange-600', active);
                btn.classList.toggle('dark:text-orange-400', active);
                btn.classList.toggle('border-transparent', !active);
                btn.classList.toggle('text-zinc-500', !active);
                btn.classList.toggle('dark:text-zinc-400', !active);
            });
            document.querySelectorAll('.cd-tab-pane').forEach((pane) => {
                pane.classList.toggle('hidden', pane.dataset.tabPane !== tab);
            });
        }

        document.querySelectorAll('.cd-tab-btn').forEach((btn) => {
            btn.addEventListener('click', () => switchClientDetailTab(btn.dataset.tab));
        });

        // ================= LEFT RAIL - read-only reference facts =================
        function railFact(label, value) {
            return `
                <div>
                    <p class="text-[11px] text-zinc-400 uppercase">${label}</p>
                    <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">${value ?? '-'}</p>
                </div>`;
        }

        function renderClientRail(c) {
            document.getElementById('cdRailOwnership').innerHTML = [
                railFact('CSR', c.sales_rep?.name),
                railFact('Account Manager', c.account_manager?.name),
            ].join('');
        }

        // ================= TAB: COMPANY INFO & ADDRESSES =================
        function renderCompanyInfoReadView(c) {
            document.getElementById('ciCompanyReadContainer').innerHTML = `
                <p><span class="text-zinc-400">Client Code:</span> ${c.customer_code ?? '-'}</p>
                <p><span class="text-zinc-400">Client Mnemonic:</span> ${c.client_mnemonic ?? '-'}</p>
                <p class="md:col-span-2"><span class="text-zinc-400">Client/Business Name:</span> ${c.company_name ?? '-'}</p>
                <p><span class="text-zinc-400">Client Category:</span> ${c.client_category ?? '-'}</p>
                <p><span class="text-zinc-400">Client Classification:</span> ${c.client_classification ?? '-'}</p>
                <p><span class="text-zinc-400">Client Industry:</span> ${c.industry ?? '-'}</p>
                <p><span class="text-zinc-400">Industry Sub-Category:</span> ${c.industry_subcategory ?? '-'}</p>
            `;

            renderAddressesReadView(c.addresses ?? []);
        }

        function addressSummaryLine(a) {
            return [a.address_no, a.address_building, a.address_street, a.address_barangay,
                a.address_town_city, a.address_province, a.address_country, a.address_postal_code
            ].filter(Boolean).join(', ') || '-';
        }

        function setAddressReadExpanded(card, expanded) {
            if (!card) return;
            card.querySelector('.address-read-body')?.classList.toggle('hidden', !expanded);
            card.querySelector('.address-read-chevron')?.classList.toggle('rotate-180', expanded);
        }

        // Exclusive-expand read display: 1 address stays plain (no collapse
        // chrome, nothing to scan yet); 2+ collapse to a one-line summary
        // (type + Primary tag + concatenated address string), matching the
        // booking-requirement card pattern (VISUALS.md).
        function renderAddressesReadView(addresses) {
            const container = document.getElementById('cdAddressesReadContainer');

            if (!addresses.length) {
                container.innerHTML = `<p class="text-xs text-zinc-400">No addresses on file.</p>`;
                return;
            }

            const exclusive = addresses.length > 1;
            container.innerHTML = addresses.map((a) => {
                const label =
                    `${a.address_type ?? 'Address'}${a.is_primary ? ' <span class="text-orange-500">(Primary)</span>' : ''}`;
                const line = addressSummaryLine(a);

                if (!exclusive) {
                    return `
                        <div class="border rounded-lg p-3 text-xs">
                            <p class="font-semibold mb-1">${label}</p>
                            <p class="text-zinc-500">${line}</p>
                        </div>`;
                }

                return `
                    <div class="address-read-card border rounded-lg text-xs">
                        <button type="button" class="address-read-toggle w-full flex items-center justify-between gap-2 p-3 text-left">
                            <span class="truncate"><span class="font-semibold">${label}</span> <span class="text-zinc-500">&mdash; ${line}</span></span>
                            <span class="address-read-chevron text-zinc-400 transition-transform duration-200 shrink-0">▼</span>
                        </button>
                        <div class="address-read-body hidden px-3 pb-3 text-zinc-500">${line}</div>
                    </div>`;
            }).join('');
        }

        document.getElementById('cdAddressesReadContainer').addEventListener('click', (e) => {
            const toggleBtn = e.target.closest('.address-read-toggle');
            if (!toggleBtn) return;
            const card = toggleBtn.closest('.address-read-card');
            const willExpand = card.querySelector('.address-read-body').classList.contains('hidden');
            document.querySelectorAll('#cdAddressesReadContainer .address-read-card').forEach((other) => {
                if (other !== card) setAddressReadExpanded(other, false);
            });
            setAddressReadExpanded(card, willExpand);
        });

        function enterCompanyInfoEditMode() {
            document.getElementById('ciReadView').classList.add('hidden');
            document.getElementById('ciEditView').classList.remove('hidden');
            document.getElementById('ciEditBtn').classList.add('hidden');
            document.getElementById('ciSaveBtn').classList.remove('hidden');
            document.getElementById('ciCancelBtn').classList.remove('hidden');
            hydrateCompanyInfoEditForm(currentClientData);
        }

        function exitCompanyInfoEditMode() {
            document.getElementById('ciReadView').classList.remove('hidden');
            document.getElementById('ciEditView').classList.add('hidden');
            document.getElementById('ciEditBtn').classList.remove('hidden');
            document.getElementById('ciSaveBtn').classList.add('hidden');
            document.getElementById('ciCancelBtn').classList.add('hidden');
        }

        document.getElementById('ciEditBtn').addEventListener('click', enterCompanyInfoEditMode);
        document.getElementById('ciCancelBtn').addEventListener('click', () => {
            renderCompanyInfoReadView(currentClientData);
            exitCompanyInfoEditMode();
        });

        function hydrateCompanyInfoEditForm(c) {
            const stage1Form = document.getElementById('cdStage1Form');
            stage1Form.reset();
            Object.entries(c).forEach(([key, val]) => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = val ?? '';
            });

            const industry = cdIndustryData.find((lov) => lov.lov_name === c.industry);
            populateIndustrySubcategoryOptions(
                document.querySelector('.cdIndustrySubcategorySelect'),
                industry?.lov_id ?? null,
                c.industry_subcategory
            );

            document.getElementById('cdAddressesEditContainer').innerHTML = '';
            const addresses = (c.addresses && c.addresses.length) ? c.addresses : [{
                is_primary: true
            }];
            addAddressCardsFrom(addresses);
        }

        document.getElementById('ciSaveBtn').addEventListener('click', async function() {
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

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: stage1Data,
                url: '/api/clientMasters/stage1',
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save company information',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Company information updated'
            });

            // Merge, don't replace - stage1's response only eager-loads
            // `addresses`, so a wholesale replace would silently drop
            // sales_rep/account_manager/contacts/finance already held in
            // currentClientData from the initial full GET.
            currentClientData = { ...currentClientData, ...response.data };
            document.getElementById('cdClientName').textContent = currentClientData.company_name ?? '-';
            document.getElementById('cdClientCode').textContent = currentClientData.customer_code ?? '-';
            renderClientRail(currentClientData);
            renderCompanyInfoReadView(currentClientData);
            exitCompanyInfoEditMode();
            renderTable().reload();
        });

        // ================= TAB: CONTACTS =================
        function renderContactsReadView(c) {
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
        }

        function enterContactsEditMode() {
            document.getElementById('ctReadView').classList.add('hidden');
            document.getElementById('ctEditView').classList.remove('hidden');
            document.getElementById('ctEditBtn').classList.add('hidden');
            document.getElementById('ctSaveBtn').classList.remove('hidden');
            document.getElementById('ctCancelBtn').classList.remove('hidden');
            hydrateContactsEditForm(currentClientData);
        }

        function exitContactsEditMode() {
            document.getElementById('ctReadView').classList.remove('hidden');
            document.getElementById('ctEditView').classList.add('hidden');
            document.getElementById('ctEditBtn').classList.remove('hidden');
            document.getElementById('ctSaveBtn').classList.add('hidden');
            document.getElementById('ctCancelBtn').classList.add('hidden');
        }

        document.getElementById('ctEditBtn').addEventListener('click', enterContactsEditMode);
        document.getElementById('ctCancelBtn').addEventListener('click', () => {
            renderContactsReadView(currentClientData);
            exitContactsEditMode();
        });

        function hydrateContactsEditForm(c) {
            document.getElementById('cdContactsEditContainer').innerHTML = '';
            (c.contacts || []).forEach((contact) => {
                document.getElementById('cdContactsEditContainer').insertAdjacentHTML('beforeend',
                    contactRowHtml());
                const row = document.getElementById('cdContactsEditContainer').lastElementChild;
                row.querySelectorAll('[data-field]').forEach((input) => input.value = contact[input
                    .dataset.field] ?? '');
            });
        }

        // A blank type next to a filled-in value is ambiguous (mobile or
        // landline? business or personal?) - checked per row just before save.
        const CONTACT_ROW_VALUE_TYPE_PAIRS = [
            ['contact_number', 'contact_number_type', 'Contact Number'],
            ['contact_email', 'contact_email_type', 'Email'],
        ];

        function findMissingTypeFields(data, pairs) {
            return pairs
                .filter(([valueField, typeField]) => data[valueField] && !data[typeField])
                .map(([, , label]) => label);
        }

        document.getElementById('ctSaveBtn').addEventListener('click', async function() {
            if (!currentClientUuid) return;

            const contacts = collectRows('cdContactsEditContainer', 'contact-row');
            for (let i = 0; i < contacts.length; i++) {
                const missingTypeFields = findMissingTypeFields(contacts[i], CONTACT_ROW_VALUE_TYPE_PAIRS);
                if (missingTypeFields.length) {
                    const name = contacts[i].contact_name || `Contact #${i + 1}`;
                    showMessage({
                        status: 'error',
                        title: 'Select a type for each filled-in field',
                        message: `${name}: choose a type for ${missingTypeFields.join(', ')}.`,
                    });
                    return;
                }
            }

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    contacts,
                },
                url: `/api/clientMasters/${currentClientUuid}/stage2`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save contacts / trade references',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Contacts updated'
            });

            // Merge, don't replace - stage2's response doesn't re-eager-load
            // addresses/finance/sales_rep/account_manager, only contacts and
            // trade_references.
            currentClientData = { ...currentClientData, ...response.data };
            renderContactsReadView(currentClientData);
            exitContactsEditMode();
            renderTable().reload();
        });

        // ================= TAB: FINANCE & COMMODITY =================
        function renderFinanceReadView(c) {
            const f = c.finance ?? {};
            document.getElementById('cdFinanceReadContainer').innerHTML = `
                <p><span class="text-zinc-400">Client Business Name:</span> ${f.client_business_name ?? '-'}</p>
                <p><span class="text-zinc-400">TIN Number:</span> ${f.tin_number ?? '-'}</p>
                <p class="md:col-span-2"><span class="text-zinc-400">TIN Registered Address:</span> ${f.tin_registered_address ?? '-'}</p>
                <p><span class="text-zinc-400">Registered Tax Type:</span> ${f.registered_tax_type ?? '-'}</p>
                <p><span class="text-zinc-400">Withholding Tax Code:</span> ${f.withholding_tax_code ?? '-'}</p>
                <p><span class="text-zinc-400">Trade Name:</span> ${f.trade_name ?? '-'}</p>
                <p><span class="text-zinc-400">TIN Registration Date:</span> ${f.tin_registration_date ?? '-'}</p>
                <p><span class="text-zinc-400">Line of Business:</span> ${f.line_of_business ?? '-'}</p>
                <p><span class="text-zinc-400">Tax Percent:</span> ${f.tax_percent ?? '-'}</p>
                <p><span class="text-zinc-400">Withholding Tax Percent:</span> ${f.withholding_tax_percent ?? '-'}</p>
                <p><span class="text-zinc-400">Mode of Payment:</span> ${f.mode_of_payment ?? '-'}</p>
                ${f.mode_of_payment === 'Credit Account' ? `<p><span class="text-zinc-400">Credit Terms:</span> ${f.credit_terms ?? '-'}</p>` : ''}
                <p><span class="text-zinc-400">Cargo Release Order (CRO):</span> ${f.cro ?? '-'}</p>
            `;

            const commodities = c.commodity_declared_values ?? [];
            document.getElementById('cdCommodityReadContainer').innerHTML = commodities.length ?
                commodities.map((cv) => `
                    <div class="border rounded-lg p-3 text-xs">
                        <p class="font-semibold mb-1">${cv.commodity_type ?? '-'}</p>
                        <p class="text-zinc-500">Max Declared Value: ${money(cv.max_declared_value)}</p>
                    </div>
                `).join('') :
                `<p class="text-xs text-zinc-400">No commodity declared values on file.</p>`;
        }

        function enterFinanceEditMode() {
            document.getElementById('fnReadView').classList.add('hidden');
            document.getElementById('fnEditView').classList.remove('hidden');
            document.getElementById('fnEditBtn').classList.add('hidden');
            document.getElementById('fnSaveBtn').classList.remove('hidden');
            document.getElementById('fnCancelBtn').classList.remove('hidden');
            hydrateFinanceEditForm(currentClientData);
        }

        function exitFinanceEditMode() {
            document.getElementById('fnReadView').classList.remove('hidden');
            document.getElementById('fnEditView').classList.add('hidden');
            document.getElementById('fnEditBtn').classList.remove('hidden');
            document.getElementById('fnSaveBtn').classList.add('hidden');
            document.getElementById('fnCancelBtn').classList.add('hidden');
        }

        document.getElementById('fnEditBtn').addEventListener('click', enterFinanceEditMode);
        document.getElementById('fnCancelBtn').addEventListener('click', () => {
            renderFinanceReadView(currentClientData);
            exitFinanceEditMode();
        });

        function hydrateFinanceEditForm(c) {
            const stage3Form = document.getElementById('cdStage3Form');
            stage3Form.reset();

            if (c.finance) {
                Object.entries(c.finance).forEach(([key, val]) => {
                    const el = stage3Form.querySelector(`[name="finance[${key}]"]`);
                    if (el) el.value = val ?? '';
                });
            }
            applyCdModeOfPaymentVisibility();

            document.getElementById('cdCommodityEditContainer').innerHTML = '';
            (c.commodity_declared_values || []).forEach((row) => {
                document.getElementById('cdCommodityEditContainer').insertAdjacentHTML('beforeend',
                    cdCommodityRowHtml());
                const el = document.getElementById('cdCommodityEditContainer').lastElementChild;
                el.querySelectorAll('[data-field]').forEach((input) => {
                    const val = row[input.dataset.field] ?? '';
                    input.value = input.classList.contains('currency-input') ?
                        formatCurrencyDisplay(val) : val;
                });
            });
        }

        document.getElementById('fnSaveBtn').addEventListener('click', async function() {
            if (!currentClientUuid) return;

            const stage3Payload = formToNestedPayload(document.getElementById('cdStage3Form'));
            stage3Payload.commodity_declared_values = collectCdCommodityDeclaredValues();

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: stage3Payload,
                url: `/api/clientMasters/${currentClientUuid}/stage3`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save finance',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Finance & commodity updated'
            });

            // Merge, don't replace - stage3's response doesn't re-eager-load
            // the plain `addresses` relation (only contacts.addresses).
            currentClientData = { ...currentClientData, ...response.data };
            renderFinanceReadView(currentClientData);
            exitFinanceEditMode();
            renderTable().reload();
        });

        // -------- Finance: Mode of Payment -> Credit Terms (mirrors clientMasterForm.blade.php) --------
        function applyCdModeOfPaymentVisibility() {
            const mode = document.querySelector('#cdStage3Form [name="finance[mode_of_payment]"]')?.value;
            document.getElementById('cdCreditTermsField').classList.toggle('hidden', mode !== 'Credit Account');
        }

        document.querySelector('#cdStage3Form [name="finance[mode_of_payment]"]')
            .addEventListener('change', applyCdModeOfPaymentVisibility);

        // -------- Finance: Registered Tax Type -> Tax Percent auto-fill --------
        document.querySelector('#cdStage3Form [name="finance[registered_tax_type]"]')
            .addEventListener('change', function() {
                const rate = cdTaxTypeRatesMap[this.value];
                if (rate === undefined || rate === null) return;
                const taxPercentInput = document.querySelector('#cdStage3Form [name="finance[tax_percent]"]');
                if (taxPercentInput) taxPercentInput.value = rate;
            });

        // -------- Commodity Type / Max Declared Value: repeatable (mirrors clientMasterForm.blade.php) --------
        function cdCommodityRowHtml() {
            return `
            <div class="commodity-row grid grid-cols-1 md:grid-cols-3 gap-2 border rounded-lg p-3 relative">
                <input type="text" data-field="commodity_type" placeholder="Commodity Type" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <input type="text" inputmode="decimal" data-field="max_declared_value" placeholder="Maximum Declared Value" class="currency-input border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <div class="flex justify-end">
                    <button type="button" class="remove-commodity text-red-500 px-2 text-xs font-medium">✕ Remove</button>
                </div>
            </div>`;
        }

        document.getElementById('cdAddCommodityBtn').addEventListener('click', () => {
            document.getElementById('cdCommodityEditContainer').insertAdjacentHTML('beforeend',
                cdCommodityRowHtml());
        });

        function collectCdCommodityDeclaredValues() {
            return Array.from(document.querySelectorAll('#cdCommodityEditContainer .commodity-row')).map((row) => {
                const obj = {};
                row.querySelectorAll('[data-field]').forEach((input) => {
                    obj[input.dataset.field] = input.classList.contains('currency-input') ?
                        parseCurrencyValue(input.value) : input.value;
                });
                return obj;
            });
        }

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
                        <option value="">-</option>
                        <option value="Business">Business</option>
                        <option value="Personal">Personal</option>
                    </select>
                </div>
                <input type="text" data-field="role" placeholder="Role" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <div class="flex gap-2">
                    <input type="text" data-field="position" placeholder="Position" class="border rounded-lg px-2 py-1.5 text-sm flex-1 dark:text-zinc-900">
                    <button type="button" class="remove-row text-red-500 px-2">✕</button>
                </div>
            </div>`;
        }

        document.getElementById('cdAddContactBtn').addEventListener('click', () => {
            document.getElementById('cdContactsEditContainer').insertAdjacentHTML('beforeend',
                contactRowHtml());
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.contact-row')?.remove();
            }
            if (e.target.classList.contains('remove-commodity')) {
                e.target.closest('.commodity-row')?.remove();
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
                // See clientMasterForm.blade.php's populateSelect() for why
                // this trims: the PSGC API returns inconsistent trailing
                // whitespace, and Laravel trims it server-side on save, so
                // an untrimmed value here would never re-match on hydration.
                const name = (item.name || '').trim();
                option.value = name;
                option.textContent = name;
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
        <div class="flex justify-between items-center gap-2">
            <button type="button" class="address-card-toggle flex items-center gap-2 min-w-0 flex-1 text-left">
                <span class="address-card-chevron text-xs text-zinc-400 transition-transform duration-200 rotate-180 shrink-0">▼</span>
                <span class="address-card-summary text-sm font-medium text-zinc-600 dark:text-zinc-300 truncate">New Address</span>
            </button>
            <select data-field="address_type" class="w-auto max-w-[10rem] border rounded-lg px-3 py-2 text-sm font-semibold dark:text-zinc-900 shrink-0">
                ${addressTypeOptionsHtml}
            </select>
            <label class="flex items-center gap-1.5 text-xs text-zinc-500 whitespace-nowrap shrink-0">
                <input type="radio" name="cd_address_primary_radio" class="primary-radio">
                Primary
            </label>
            <button type="button" class="remove-address text-red-500 text-xs font-medium shrink-0">✕ Remove</button>
        </div>

        <div class="address-card-body grid grid-cols-1 md:grid-cols-2 gap-3">
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

        function addressCardSummaryText(card) {
            const get = (field) => card.querySelector(`[data-field="${field}"]`)?.value || '';
            const typeSelect = card.querySelector('[data-field="address_type"]');
            const typeLabel = typeSelect?.options[typeSelect.selectedIndex]?.textContent || 'Address';
            const isPrimary = card.querySelector('.primary-radio')?.checked;
            const line = [get('address_no'), get('address_building'), get('address_street'),
                get('address_barangay'), get('address_town_city'), get('address_province'),
                get('address_country'), get('address_postal_code')
            ].filter(Boolean).join(', ') || '-';
            return `${typeLabel}${isPrimary ? ' (Primary)' : ''} — ${line}`;
        }

        function updateAddressCardSummary(card) {
            const summaryEl = card.querySelector('.address-card-summary');
            if (summaryEl) summaryEl.textContent = addressCardSummaryText(card);
        }

        function setAddressCardExpanded(card, expanded) {
            card.querySelector('.address-card-body')?.classList.toggle('hidden', !expanded);
            card.querySelector('.address-card-chevron')?.classList.toggle('rotate-180', expanded);
        }

        // 1 address = always expanded, no collapse chrome (nothing to scan
        // yet); 2+ = collapse chrome becomes active on every card.
        function applyAddressCardChrome() {
            const wrap = document.getElementById('cdAddressesEditContainer');
            const cards = Array.from(wrap.querySelectorAll('.address-card'));
            const exclusive = cards.length > 1;
            cards.forEach((card) => {
                card.querySelector('.address-card-toggle').classList.toggle('pointer-events-none', !
                    exclusive);
                card.querySelector('.address-card-chevron').classList.toggle('invisible', !exclusive);
                if (!exclusive) setAddressCardExpanded(card, true);
            });
        }

        document.getElementById('cdAddressesEditContainer').addEventListener('input', (e) => {
            const card = e.target.closest('.address-card');
            if (card) updateAddressCardSummary(card);
        });
        document.getElementById('cdAddressesEditContainer').addEventListener('change', (e) => {
            const card = e.target.closest('.address-card');
            if (!card) return;
            if (e.target.classList.contains('primary-radio')) {
                // Changing which card is primary can affect every card's
                // "(Primary)" tag, not just the one that was clicked.
                document.querySelectorAll('#cdAddressesEditContainer .address-card').forEach(
                    updateAddressCardSummary);
            } else {
                updateAddressCardSummary(card);
            }
        });

        async function addAddressCard() {
            const wrap = document.getElementById('cdAddressesEditContainer');
            const index = wrap.children.length;
            wrap.insertAdjacentHTML('beforeend', addressCardHtml(index));
            const card = wrap.lastElementChild;

            card.querySelector('.remove-address').addEventListener('click', () => {
                card.remove();
                applyAddressCardChrome();
            });
            if (index === 0) card.querySelector('.primary-radio').checked = true;

            card.querySelector('.address-card-toggle').addEventListener('click', () => {
                if (wrap.querySelectorAll('.address-card').length <= 1) return;
                const expand = card.querySelector('.address-card-body').classList.contains('hidden');
                if (expand) {
                    wrap.querySelectorAll('.address-card').forEach((other) => {
                        if (other !== card) setAddressCardExpanded(other, false);
                    });
                }
                setAddressCardExpanded(card, expand);
            });

            // Exclusive expand also applies when a new card is added, not
            // just on manual toggle clicks - otherwise adding a 2nd/3rd
            // address leaves every prior card open, recreating the "wall of
            // fields" the collapse/summary treatment exists to prevent. The
            // newly-added card keeps its own default-expanded state.
            wrap.querySelectorAll('.address-card').forEach((other) => {
                if (other !== card) setAddressCardExpanded(other, false);
            });

            await initializePhilippineAddress(card);
            updateAddressCardSummary(card);
            applyAddressCardChrome();
            return card;
        }

        document.getElementById('cdAddAddressBtn').addEventListener('click', () => addAddressCard());

        async function addAddressCardsFrom(addresses) {
            for (const address of addresses) {
                const card = await addAddressCard();
                await hydrateAddressCard(card, address);
                updateAddressCardSummary(card);
                // Existing addresses can look identical until scanned
                // field-by-field - collapse each to its summary line by
                // default once hydrated (mirrors the container-card hydrate
                // pattern in crmLeadForm.blade.php); applyAddressCardChrome()
                // below re-expands the lone card if there's only one.
                setAddressCardExpanded(card, false);
            }
            applyAddressCardChrome();
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

                const cities = await psgcRequest(
                    `${PSGC_API}/provinces/${provinceCode}/cities-municipalities`);
                cities.sort((a, b) => a.name.localeCompare(b.name));
                populateSelect(city, cities, 'Select Town/City');
            }

            async function loadBarangaysForCity(cityCode) {
                resetSelect(barangay, 'Loading...');

                if (!cityCode) {
                    resetSelect(barangay, 'Select Barangay');
                    return;
                }

                const barangays = await psgcRequest(
                    `${PSGC_API}/cities-municipalities/${cityCode}/barangays`);
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
                finance: {}
            };
            for (const [key, value] of fd.entries()) {
                const match = key.match(/^finance\[(.+)\]$/);
                if (match) {
                    payload.finance[match[1]] = value;
                } else {
                    payload[key] = value;
                }
            }
            return payload;
        }

        // ================= CONTRACTS LIST =================
        const CONTRACT_STATUS_MAPPING = {
            1: {
                label: 'Draft',
                classes: 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300'
            },
            2: {
                label: 'Active',
                classes: 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400'
            },
            3: {
                label: 'Expired',
                classes: 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400'
            },
            4: {
                label: 'Terminated',
                classes: 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400'
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
                        ${c.status === 1 && c.can_approve ? `
                            <button type="button" class="contract-approve-btn shrink-0 text-[11px] px-2 py-1 rounded-md border border-green-200 dark:border-green-900 text-green-600 hover:bg-green-50 dark:hover:bg-green-950/40" data-contract-id="${c.id}" title="Approve this contract">
                                Approve
                            </button>
                        ` : ''}
                    </div>

                    ${(c.rates ?? []).length ? `
                        <div class="mt-2 pt-2 border-t border-zinc-100 dark:border-zinc-800 space-y-1">
                            ${c.rates.map((r) => `
                                <div class="flex justify-between items-center gap-3 text-xs">
                                    <span class="text-zinc-500 dark:text-zinc-400 truncate">${r.origin_port ? (r.origin_port.location?.name ?? '-') + ' - ' + r.origin_port.name : '-'} → ${r.destination_port ? (r.destination_port.location?.name ?? '-') + ' - ' + r.destination_port.name : '-'} · ${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</span>
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

            document.querySelectorAll('.contract-approve-btn').forEach((btn) => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    approveContractFromCard(Number(this.dataset.contractId), uuid, this);
                });
            });
        }

        // Opens the styled terminate-reason modal instead of window.prompt() -
        // the actual validation + POST now live in the tcrConfirmBtn handler
        // below, which also gets a button loading-state guard.
        let terminateContractContext = {
            contractId: null,
            uuid: null
        };

        function terminateContractFromCard(contractId, uuid) {
            terminateContractContext = {
                contractId,
                uuid
            };
            document.getElementById('tcrReasonInput').value = '';
            initModal({
                modalId: 'terminateContractReasonModal'
            });
        }

        document.getElementById('tcrConfirmBtn').addEventListener('click', async function() {
            const reason = document.getElementById('tcrReasonInput').value;

            if (!reason.trim()) {
                showMessage({
                    status: 'error',
                    title: 'Reason required',
                    message: 'Please provide a reason to terminate this contract.'
                });
                return;
            }

            const {
                contractId,
                uuid
            } = terminateContractContext;

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    reason: reason.trim()
                },
                url: `/api/clientContracts/${contractId}/terminate`,
                button: this,
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
            closeModal('terminateContractReasonModal');
            loadContracts(uuid);
        });

        async function approveContractFromCard(contractId, uuid, button) {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/clientContracts/${contractId}/approve`,
                button,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to approve contract',
                    message: response.message ?? 'An unexpected error occurred.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Contract approved'
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
                        <th class="text-right py-1">Min Qty</th>
                        <th class="text-right py-1">Base Rate</th>
                        <th class="text-right py-1">Adjustment</th>
                        <th class="text-right py-1">Final Rate</th>
                        <th class="text-right py-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    ${p.rates.map((r) => `
                        <tr class="border-t" data-rate-id="${r.id}">
                            <td class="py-1.5">${r.origin_port ? (r.origin_port.location?.name ?? '-') + ' - ' + r.origin_port.name : '-'} → ${r.destination_port ? (r.destination_port.location?.name ?? '-') + ' - ' + r.destination_port.name : '-'}</td>
                            <td class="py-1.5">${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</td>
                            <td class="py-1.5 text-right">${r.min_van_qty ?? '-'}</td>
                            <td class="py-1.5 text-right">${Number(r.base_rate).toLocaleString()}</td>
                            <td class="py-1.5 text-right">${adjustmentDisplay(r.discount_type, r.discount_value)}</td>
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
                    <button type="button" class="cpm-create-contract-btn text-xs px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white" data-proposal-id="${p.id}">
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
                btn.addEventListener('click', function() {
                    proposalDecisionAction(uuid, this.dataset.proposalId, 'approve',
                        'Proposal approved', this);
                });
            });
            document.querySelectorAll('.cpm-disapprove-btn').forEach((btn) => {
                btn.addEventListener('click', function() {
                    proposalDecisionAction(uuid, this.dataset.proposalId, 'disapprove',
                        'Proposal disapproved', this);
                });
            });
            document.querySelectorAll('.cpm-reject-btn').forEach((btn) => {
                btn.addEventListener('click', async function() {
                    const confirmed = await customConfirm(
                        'Reject this proposal? This cannot be undone.');
                    if (confirmed) proposalDecisionAction(uuid, this.dataset.proposalId,
                        'reject', 'Proposal rejected', this);
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

        async function proposalDecisionAction(uuid, proposalId, action, successTitle, button) {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/clientProposals/${proposalId}/${action}`,
                button,
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
            // This modal is always opened from within a specific client's own
            // detail modal, so currentClientData is always available here -
            // unlike proposals.blade.php, where the client name comes off the
            // proposal object itself.
            document.getElementById('ccClientName').textContent = currentClientData?.company_name ?? '-';
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
                min_van_qty: rate.min_van_qty ?? null,
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

        function adjustmentDisplay(type, value) {
            if (!type) return '-';
            const labels = {
                percentage: 'Discount',
                fixed: 'Discount',
                increase_percentage: 'Increase',
                increase_fixed: 'Increase',
            };
            const isPercent = type === 'percentage' || type === 'increase_percentage';
            const amount = isPercent ? `${value}%` : Number(value).toLocaleString();
            return `${labels[type] ?? type} ${amount}`;
        }

        function ccDiscountDisplay(values) {
            return adjustmentDisplay(values.discount_type, values.discount_value);
        }

        function describeRateChange(rate) {
            const original = ccOriginalValues(rate);
            const current = ccRateOverrides[rate.id];
            if (!current) return '';
            const fieldLabels = {
                min_van_qty: 'Min Qty',
                base_rate: 'Base Rate',
                discount_type: 'Adjustment Type',
                discount_value: 'Adjustment Value',
                final_rate: 'Final Rate'
            };
            const diffs = [];
            for (const key of Object.keys(fieldLabels)) {
                if (original[key] !== current[key]) {
                    diffs.push(`${fieldLabels[key]}: ${original[key] ?? '-'} → ${current[key] ?? '-'}`);
                }
            }
            return diffs.join(', ');
        }

        function renderCcRateRow(rate, editing) {
            const lane = `${rate.origin_port ? (rate.origin_port.location?.name ?? '-') + ' - ' + rate.origin_port.name : '-'} → ${rate.destination_port ? (rate.destination_port.location?.name ?? '-') + ' - ' + rate.destination_port.name : '-'}`;
            const variant =
                `${rate.container?.name ?? '-'} / ${rate.container_class?.class ?? '-'} / ${rate.container_size?.size ?? '-'}`;
            const values = ccCurrentValues(rate);
            const edited = Boolean(ccRateOverrides[rate.id]);

            if (!editing) {
                return `
                    <tr data-rate-id="${rate.id}" class="border-l-2 border-transparent hover:border-orange-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                        <td class="py-1.5 px-2">${lane}</td>
                        <td class="py-1.5 px-2">${variant}</td>
                        <td class="py-1.5 px-2 text-right">${values.min_van_qty ?? '-'}</td>
                        <td class="py-1.5 px-2 text-right">${Number(values.base_rate).toLocaleString()}</td>
                        <td class="py-1.5 px-2 text-right">${ccDiscountDisplay(values)}</td>
                        <td class="py-1.5 px-2 text-right font-semibold">
                            ${Number(values.final_rate).toLocaleString()}
                            ${edited ? `<span class="ml-1 text-[10px] font-normal text-amber-600 cursor-help" title="${describeRateChange(rate).replace(/"/g, '&quot;')}">(edited)</span>` : ''}
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
                        <input type="number" min="1" step="1" placeholder="None" class="cc-input-minqty w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.min_van_qty ?? ''}">
                    </td>
                    <td class="py-1.5 px-2">
                        <input type="text" inputmode="decimal" class="cc-input-base currency-input w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.base_rate)}">
                    </td>
                    <td class="py-1.5 px-2">
                        <div class="flex items-center gap-1 justify-end">
                            <select class="cc-input-disctype border rounded px-1 py-1 text-xs dark:text-zinc-900">
                                <option value="" ${!values.discount_type ? 'selected' : ''}>None</option>
                                <option value="percentage" ${values.discount_type === 'percentage' ? 'selected' : ''}>Discount (%)</option>
                                <option value="fixed" ${values.discount_type === 'fixed' ? 'selected' : ''}>Discount (Fixed)</option>
                                <option value="increase_percentage" ${values.discount_type === 'increase_percentage' ? 'selected' : ''}>Increase (%)</option>
                                <option value="increase_fixed" ${values.discount_type === 'increase_fixed' ? 'selected' : ''}>Increase (Fixed)</option>
                            </select>
                            <input type="text" inputmode="decimal" class="cc-input-discval currency-input w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.discount_value)}">
                        </div>
                    </td>
                    <td class="py-1.5 px-2">
                        <input type="text" inputmode="decimal" class="cc-input-final currency-input w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.final_rate)}">
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
            document.getElementById('ccRatesBody').innerHTML = (currentProposalForContract?.rates ?? [])
                .map((r) => renderCcRateRow(r, ccEditingRateId === r.id))
                .join('');
        }

        // Base rate / discount type / discount value all feed Final Rate, same
        // as the Add Proposal / Add Contract row builders elsewhere - without
        // this, editing the discount changed nothing the user could see or
        // save, since Final Rate is what actually gets sent as the override.
        function recomputeCcFinalRate(row) {
            const base = parseFloat(parseCurrencyValue(row.querySelector('.cc-input-base').value)) || 0;
            const type = row.querySelector('.cc-input-disctype').value;
            const value = parseFloat(parseCurrencyValue(row.querySelector('.cc-input-discval').value)) || 0;
            const finalInput = row.querySelector('.cc-input-final');

            let final = base;
            if (type === 'percentage') final = Math.max(0, base - (base * value / 100));
            if (type === 'fixed') final = Math.max(0, base - value);
            if (type === 'increase_percentage') final = base + (base * value / 100);
            if (type === 'increase_fixed') final = base + value;

            finalInput.value = formatCurrencyDisplay(final.toFixed(2));
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
                const minQtyRaw = row.querySelector('.cc-input-minqty').value;
                const newValues = {
                    min_van_qty: minQtyRaw === '' ? null : Number(minQtyRaw),
                    base_rate: Number(parseCurrencyValue(row.querySelector('.cc-input-base')
                        .value)),
                    discount_type: row.querySelector('.cc-input-disctype').value || null,
                    discount_value: Number(parseCurrencyValue(row.querySelector('.cc-input-discval')
                        .value) || 0),
                    final_rate: Number(parseCurrencyValue(row.querySelector('.cc-input-final')
                        .value)),
                };
                const original = ccOriginalValues(rate);
                const changed = newValues.min_van_qty !== original.min_van_qty ||
                    newValues.base_rate !== original.base_rate ||
                    newValues.discount_type !== original.discount_type ||
                    newValues.discount_value !== original.discount_value ||
                    newValues.final_rate !== original.final_rate;

                if (changed) {
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
            closeModal('createContractModal');
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
        let cpLocationsOptionsHtml = '';
        let cpPortsData = [];
        let cpContainerVariantsData = [];
        let cpLookupsLoaded = false;

        async function loadCpContainerLookups() {
            if (cpLookupsLoaded) return;

            const [portsRes, locationsRes, variantsRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/locations?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containers/variants'
                }),
            ]);

            if (portsRes.success) {
                cpPortsData = portsRes.data.data;
                cpPortsOptionsHtml = cpPortsData
                    .map((p) => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`)
                    .join('');
            }
            if (locationsRes.success) {
                cpLocationsOptionsHtml = locationsRes.data.data
                    .map((l) => `<option value="${l.location_id}">${l.name}</option>`)
                    .join('');
            }
            if (variantsRes.success) {
                cpContainerVariantsData = variantsRes.data;
            }
            cpLookupsLoaded = true;
        }

        function cpPortOptionsForLocation(locationId) {
            const ports = locationId ?
                cpPortsData.filter((p) => String(p.location_id) === String(locationId)) :
                cpPortsData;
            return ports.map((p) => `<option value="${p.port_id}">${p.name}</option>`).join('');
        }

        function refreshSearchable(el) {
            el?._searchableSelect?.refresh();
        }

        function classIdForPayload(sel) {
            const v = sel.value;
            return (v === '' || v === '__base__') ? null : v;
        }

        function sizeIdForPayload(sel) {
            const id = sel.options[sel.selectedIndex]?.dataset.sizeId;
            return id ? id : null;
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
                        <label class="text-[11px] text-zinc-400 uppercase">Origin Location</label>
                        <select class="origin-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">All Locations</option>${cpLocationsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Origin Port</label>
                        <select data-field="origin_port_id" disabled class="origin-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                            <option value="">Select</option>${cpPortsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination Location</label>
                        <select class="destination-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">All Locations</option>${cpLocationsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination Port</label>
                        <select data-field="destination_port_id" disabled class="destination-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
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
                        <label class="text-[11px] text-zinc-400 uppercase">Min Qty (for discount)</label>
                        <input type="number" min="1" step="1" data-field="min_van_qty" placeholder="No minimum" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Rate (FRT)</label>
                        <input type="text" inputmode="decimal" data-field="base_rate" readonly class="base-rate currency-input w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" value="0.00">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Adjustment Type</label>
                        <select data-field="discount_type" class="discount-type w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">None</option>
                            <option value="percentage">Discount (%)</option>
                            <option value="fixed">Discount (Fixed)</option>
                            <option value="increase_percentage">Increase (%)</option>
                            <option value="increase_fixed">Increase (Fixed)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Adjustment Value</label>
                        <input type="text" inputmode="decimal" data-field="discount_value" class="discount-value currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" value="0">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Final Rate</label>
                        <input type="text" inputmode="decimal" data-field="final_rate" readonly class="final-rate currency-input w-full border border-blue-200 dark:border-blue-800 rounded-lg px-2 py-1.5 text-sm bg-blue-50 dark:bg-blue-900/40 text-zinc-900 dark:text-blue-100 font-semibold" value="0.00">
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
            const originLocationSel = row.querySelector('.origin-location-select');
            const destLocationSel = row.querySelector('.destination-location-select');
            [originLocationSel, originSel, destLocationSel, destSel].forEach((el) => makeSearchableSelect(el));
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
                const variantsForContainer = cpContainerVariantsData.filter((v) => String(v.container
                    .id) === containerId);
                const classes = [...new Map(
                    variantsForContainer
                    .filter((v) => v.container_class)
                    .map((v) => [v.container_class.id, v.container_class])
                ).values()];
                const hasBase = variantsForContainer.some((v) => !v.container_class);

                classSel.innerHTML = `<option value="">Select</option>` +
                    (hasBase ? `<option value="__base__">Base (No Class)</option>` : '') +
                    classes.map((c) => `<option value="${c.id}">${c.class}</option>`).join('');
                sizeSel.innerHTML = `<option value="">Select class first</option>`;
                variantInput.value = '';
                resetCpRate(baseRateInput, finalRateInput);
            });

            classSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const classId = classSel.value;
                const sizes = cpContainerVariantsData.filter(
                    (v) => String(v.container.id) === containerId && (classId === '__base__' ? !v
                        .container_class : String(v.container_class?.id) === classId)
                );

                sizeSel.innerHTML = `<option value="">Select</option>` +
                    sizes.map((v) =>
                        `<option value="${v.id}" data-variant-id="${v.id}" data-size-id="${v.container_size?.id ?? ''}">${v.container_size?.size ?? 'N/A (no fixed size)'}</option>`
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

            originLocationSel.addEventListener('change', () => {
                originSel.innerHTML = `<option value="">Select</option>${cpPortOptionsForLocation(originLocationSel.value)}`;
                originSel.disabled = !originLocationSel.value;
                refreshSearchable(originSel);
                lookupCpRate(row);
            });
            destLocationSel.addEventListener('change', () => {
                destSel.innerHTML = `<option value="">Select</option>${cpPortOptionsForLocation(destLocationSel.value)}`;
                destSel.disabled = !destLocationSel.value;
                refreshSearchable(destSel);
                lookupCpRate(row);
            });

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

            baseRateInput.value = formatCurrencyDisplay(Number(response.data.frt).toFixed(2));
            recomputeCpFinalRate(row);
        }

        function recomputeCpFinalRate(row) {
            const base = parseFloat(parseCurrencyValue(row.querySelector('.base-rate').value)) || 0;
            const type = row.querySelector('.discount-type').value;
            const value = parseFloat(parseCurrencyValue(row.querySelector('.discount-value').value)) || 0;
            const finalRateInput = row.querySelector('.final-rate');

            let final = base;
            if (type === 'percentage') final = Math.max(0, base - (base * value / 100));
            if (type === 'fixed') final = Math.max(0, base - value);
            if (type === 'increase_percentage') final = base + (base * value / 100);
            if (type === 'increase_fixed') final = base + value;

            finalRateInput.value = formatCurrencyDisplay(final.toFixed(2));
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
                container_class_id: classIdForPayload(row.querySelector('[data-field="container_class_id"]')),
                container_size_id: sizeIdForPayload(row.querySelector('[data-field="container_size_id"]')),
                container_variant_id: row.querySelector('[data-field="container_variant_id"]').value,
                min_van_qty: row.querySelector('[data-field="min_van_qty"]').value || null,
                base_rate: parseFloat(parseCurrencyValue(row.querySelector('.base-rate').value)) || 0,
                discount_type: row.querySelector('.discount-type').value || null,
                discount_value: parseFloat(parseCurrencyValue(row.querySelector('.discount-value')
                    .value)) || 0,
                final_rate: parseFloat(parseCurrencyValue(row.querySelector('.final-rate').value)) || 0,
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
