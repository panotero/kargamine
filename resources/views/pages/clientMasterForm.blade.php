<div class="container mx-auto p-5 max-w-5xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" id="formPageTitle">New Client Master Data</h1>
            <p class="text-zinc-500 text-sm">Fill in each stage. You may save and continue later.</p>
        </div>
        <button id="btnBackToList"
            class="border border-zinc-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100">
            ← Back to List
        </button>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center gap-2 mb-6" id="stageStepper">
        <button type="button" class="stage-btn flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2"
            data-stage="1">
            1. Company Information
        </button>
        <button type="button" class="stage-btn flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2"
            data-stage="2">
            2. Contacts & References
        </button>
        <button type="button" class="stage-btn flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2"
            data-stage="3">
            3. Finance & Billing
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

        {{-- ===================== STAGE 1 ===================== --}}
        <div class="stage-panel" data-panel="1">
            <form id="stage1Form" class="space-y-6">

                <div>
                    <p class="font-semibold text-zinc-700 mb-3">Company Information</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Customer Code</label>
                            <input type="text" name="customer_code" readonly placeholder="Generated on save"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 bg-zinc-50 text-zinc-600 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Company Name</label>
                            <input type="text" name="company_name" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Contact Number
                                (Primary)</label>
                            <input type="text" name="contact_number_1"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Contact Number
                                (Secondary)</label>
                            <input type="text" name="contact_number_2"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Industry</label>
                            <input type="text" name="industry" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Type of Organization</label>
                            <input type="text" name="organization_type"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">TIN</label>
                            <input type="text" name="tin" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Business Established
                                Date</label>
                            <input type="date" name="business_start_date"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Estimated Annual Revenue</label>
                            <input type="number" step="0.01" name="estimated_annual_revenue"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Company URL</label>
                            <input type="url" name="company_url" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                    </div>
                </div>

                {{-- Addresses - repeatable, one per address type --}}
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-zinc-700">Address(es) *</p>
                        <button type="button" id="addAddressBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add
                            Address</button>
                    </div>
                    <div id="addressesContainer" class="space-y-4"></div>
                </div>
            </form>
            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button id="saveStage1Btn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Save & Continue
                </button>
            </div>
        </div>

        {{-- ===================== STAGE 2 ===================== --}}
        <div class="stage-panel hidden" data-panel="2">

            <div class="flex justify-between items-center mb-3">
                <p class="font-semibold text-zinc-700">Contacts</p>
                <button type="button" id="addContactBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add Contact</button>
            </div>
            <div id="contactsContainer" class="space-y-3 mb-6"></div>

            <div class="flex justify-between items-center mb-3">
                <p class="font-semibold text-zinc-700">Trade / Bank Reference</p>
                <button type="button" id="addTradeRefBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add Reference</button>
            </div>
            <div id="tradeRefsContainer" class="space-y-3"></div>

            <div class="flex justify-between gap-2 mt-6 pt-4 border-t">
                <button class="stage-prev border px-5 py-2 rounded-lg text-sm font-medium"
                    data-target="1">Previous</button>
                <button id="saveStage2Btn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Save & Continue
                </button>
            </div>
        </div>

        {{-- ===================== STAGE 3 ===================== --}}
        <div class="stage-panel hidden" data-panel="3">
            <form id="stage3Form" class="space-y-6">

                <div>
                    <p class="font-semibold text-zinc-700 mb-3">Company Finance</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Credit Terms</label>
                            <input type="text" name="finance[credit_terms]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Payment Mode</label>
                            <input type="text" name="finance[payment_mode]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <label class="flex items-center gap-2 md:col-span-2">
                            <input type="checkbox" name="finance[standard_billing_service]" value="1">
                            <span class="text-sm">Standard Billing Service</span>
                        </label>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <p class="font-semibold text-zinc-700 mb-3">Invoice Submission</p>
                    <div class="flex gap-4 mb-3">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="finance[invoice_submission]" value="electronic"
                                class="invoiceSubmissionRadio">
                            <span class="text-sm">Electronic</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="finance[invoice_submission]" value="courier"
                                class="invoiceSubmissionRadio">
                            <span class="text-sm">Via Courier</span>
                        </label>
                    </div>
                    <div id="invoiceElectronicFields" class="hidden">
                        <label class="text-xs font-medium text-zinc-400 uppercase">Invoice Email Address</label>
                        <input type="email" name="finance[invoice_email_address]"
                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                    </div>
                    <div id="invoiceCourierFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Recipient Name</label>
                            <input type="text" name="finance[invoice_courier_recipient]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Recipient Contact</label>
                            <input type="text" name="finance[invoice_courier_contact]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Courier Address</label>
                            <textarea name="finance[invoice_courier_address]" rows="2"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <p class="font-semibold text-zinc-700 mb-3">Payment Method</p>
                    <div class="flex gap-4 mb-3">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="finance[payment_method]" value="check_pickup"
                                class="paymentMethodRadio">
                            <span class="text-sm">Check Pickup</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="finance[payment_method]" value="direct_remittance"
                                class="paymentMethodRadio">
                            <span class="text-sm">Direct Remittance to Bank</span>
                        </label>
                    </div>
                    <div id="checkPickupFields" class="hidden">
                        <label class="text-xs font-medium text-zinc-400 uppercase">Pickup Address</label>
                        <textarea name="finance[check_pickup_address]" rows="2"
                            class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                    </div>
                    <div id="directRemittanceFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Bank Name</label>
                            <input type="text" name="finance[bank_name]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Bank Account Number</label>
                            <input type="text" name="finance[bank_account_number]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <p class="font-semibold text-zinc-700 mb-3">Additional Billing Service Request</p>
                    <div class="flex gap-6 mb-3">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="finance[document_handling]" value="1">
                            <span class="text-sm">Document Handling</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="finance[billing_summary_report]" value="1">
                            <span class="text-sm">Billing Summary Report</span>
                        </label>
                    </div>
                    <label class="text-xs font-medium text-zinc-400 uppercase">Others</label>
                    <textarea name="finance[other_requests]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                </div>

                <div class="border-t pt-4">
                    <p class="font-semibold text-zinc-700 mb-3">Billing Details</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Billed To</label>
                            <input type="text" name="billing[billed_to]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Company Name</label>
                            <input type="text" name="billing[company_name]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Address</label>
                            <textarea name="billing[address]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">TIN</label>
                            <input type="text" name="billing[tin]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                    </div>
                </div>
            </form>

            <div class="flex justify-between gap-2 mt-6 pt-4 border-t">
                <button class="stage-prev border px-5 py-2 rounded-lg text-sm font-medium"
                    data-target="2">Previous</button>
                <button id="saveStage3Btn"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Save & Finish
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        let clientUuid = window.clientMasterFormUuid || null;
        window.clientMasterFormUuid = null;

        let leadId = window.clientMasterFormLeadId || null;
        window.clientMasterFormLeadId = null;

        let prefillData = window.clientMasterFormPrefill || null;
        window.clientMasterFormPrefill = null;

        let currentStage = 1;

        // -------------------- STEPPER --------------------
        function showStage(stage) {
            currentStage = stage;
            document.querySelectorAll('.stage-panel').forEach(p => {
                p.classList.toggle('hidden', Number(p.dataset.panel) !== stage);
            });
            document.querySelectorAll('.stage-btn').forEach(b => {
                const active = Number(b.dataset.stage) === stage;
                b.classList.toggle('border-orange-500', active);
                b.classList.toggle('text-orange-600', active);
                b.classList.toggle('border-zinc-200', !active);
                b.classList.toggle('text-zinc-400', !active);
            });
        }

        document.querySelectorAll('.stage-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Only allow jumping to a stage that's reachable (existing client or already visited)
                if (clientUuid || Number(btn.dataset.stage) === 1) {
                    showStage(Number(btn.dataset.stage));
                }
            });
        });

        document.querySelectorAll('.stage-prev').forEach(btn => {
            btn.addEventListener('click', () => showStage(Number(btn.dataset.target)));
        });

        // -------------------- STAGE 1 --------------------
        function collectAddresses() {
            return Array.from(document.querySelectorAll('.address-card')).map(card => {
                const obj = {};
                card.querySelectorAll('[data-field]').forEach(el => {
                    obj[el.dataset.field] = el.value;
                });
                obj.is_primary = card.querySelector('.primary-radio')?.checked ?? false;
                return obj;
            });
        }

        document.getElementById('saveStage1Btn').addEventListener('click', async function() {
            const form = document.getElementById('stage1Form');
            const data = Object.fromEntries(new FormData(form).entries());
            if (clientUuid) data.uuid = clientUuid;
            if (leadId) data.lead_id = leadId;

            const addresses = collectAddresses();
            if (!addresses.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one address.'
                });
                return;
            }
            data.addresses = addresses;

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: data,
                url: '/api/clientMasters/stage1',
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving',
                    message: 'Please contact the system administrator.'
                });
                return;
            }

            clientUuid = response.data.uuid;
            document.querySelector('#stage1Form [name="customer_code"]').value = response.data.customer_code ?? '';
            showMessage({
                status: 'success',
                title: 'Company Information Saved'
            });
            showStage(2);
        });

        function resetFormToBlank() {
            document.getElementById('formPageTitle').textContent = 'New Client Master Data';
            document.getElementById('stage1Form').reset();
            document.getElementById('stage3Form').reset();
            document.getElementById('contactsContainer').innerHTML = '';
            document.getElementById('tradeRefsContainer').innerHTML = '';
            document.getElementById('addressesContainer').innerHTML = '';
            document.getElementById('invoiceElectronicFields').classList.add('hidden');
            document.getElementById('invoiceCourierFields').classList.add('hidden');
            document.getElementById('checkPickupFields').classList.add('hidden');
            document.getElementById('directRemittanceFields').classList.add('hidden');

            // Prefill from a CRM lead, if this form was opened from one.
            if (prefillData) {
                const stage1Form = document.getElementById('stage1Form');
                Object.entries(prefillData).forEach(([key, val]) => {
                    if (key === 'addresses') return;
                    const el = stage1Form.querySelector(`[name="${key}"]`);
                    if (el && val) el.value = val;
                });
                document.getElementById('formPageTitle').textContent = 'New Client Master Data (from Lead)';
            }

            const addresses = prefillData?.addresses?.length ? prefillData.addresses : [{
                is_primary: true
            }];
            addAddressCardsFrom(addresses);
        }

        async function addAddressCardsFrom(addresses) {
            for (const address of addresses) {
                const card = await addAddressCard();
                await hydrateAddressCard(card, address);
            }
        }

        // -------------------- STAGE 2: dynamic rows --------------------
        function contactRowHtml() {
            return `
            <div class="contact-row grid grid-cols-1 md:grid-cols-5 gap-2 border rounded-lg p-3 relative">
                <input type="text" data-field="contact_name" placeholder="Contact Name" class="border rounded-lg px-2 py-1.5 text-sm">
                <div class="flex gap-1">
                    <input type="text" data-field="contact_number" placeholder="Contact Number" class="border rounded-lg px-2 py-1.5 text-sm flex-1 min-w-0">
                    <select data-field="contact_number_type" class="border rounded-lg px-1 py-1.5 text-xs w-24 shrink-0">
                        <option value="">Type</option>
                        <option value="mobile">Mobile</option>
                        <option value="landline">Landline</option>
                    </select>
                </div>
                <div class="flex gap-1">
                    <input type="email" data-field="contact_email" placeholder="Email" class="border rounded-lg px-2 py-1.5 text-sm flex-1 min-w-0">
                    <select data-field="contact_email_type" class="border rounded-lg px-1 py-1.5 text-xs w-24 shrink-0">
                        <option value="">Type</option>
                        <option value="personal">Personal</option>
                        <option value="business">Business</option>
                    </select>
                </div>
                <input type="text" data-field="role" placeholder="Role" class="border rounded-lg px-2 py-1.5 text-sm">
                <div class="flex gap-2">
                    <input type="text" data-field="position" placeholder="Position" class="border rounded-lg px-2 py-1.5 text-sm flex-1">
                    <button type="button" class="remove-row text-red-500 px-2">✕</button>
                </div>
            </div>`;
        }

        function tradeRefRowHtml() {
            return `
            <div class="trade-ref-row grid grid-cols-1 md:grid-cols-3 gap-2 border rounded-lg p-3 relative">
                <input type="text" data-field="business_name" placeholder="Business Name" class="border rounded-lg px-2 py-1.5 text-sm">
                <input type="text" data-field="relationship" placeholder="Nature of Relationship" class="border rounded-lg px-2 py-1.5 text-sm">
                <input type="text" data-field="contact_person_name" placeholder="Contact Person" class="border rounded-lg px-2 py-1.5 text-sm">
                <input type="text" data-field="contact_person_phone" placeholder="Phone" class="border rounded-lg px-2 py-1.5 text-sm">
                <input type="text" data-field="contact_person_mobile" placeholder="Mobile" class="border rounded-lg px-2 py-1.5 text-sm">
                <div class="flex gap-2">
                    <input type="email" data-field="contact_person_email" placeholder="Email" class="border rounded-lg px-2 py-1.5 text-sm flex-1">
                    <button type="button" class="remove-row text-red-500 px-2">✕</button>
                </div>
            </div>`;
        }

        document.getElementById('addContactBtn').addEventListener('click', () => {
            document.getElementById('contactsContainer').insertAdjacentHTML('beforeend', contactRowHtml());
        });
        document.getElementById('addTradeRefBtn').addEventListener('click', () => {
            document.getElementById('tradeRefsContainer').insertAdjacentHTML('beforeend',
                tradeRefRowHtml());
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('.contact-row, .trade-ref-row').remove();
            }
        });

        function collectRows(containerId, rowClass) {
            return Array.from(document.querySelectorAll(`#${containerId} .${rowClass}`)).map(row => {
                const obj = {};
                row.querySelectorAll('[data-field]').forEach(input => {
                    obj[input.dataset.field] = input.value;
                });
                return obj;
            });
        }

        document.getElementById('saveStage2Btn').addEventListener('click', async function() {
            if (!clientUuid) {
                showMessage({
                    status: 'error',
                    title: 'Save Stage 1 first'
                });
                return;
            }

            const payload = {
                contacts: collectRows('contactsContainer', 'contact-row'),
                trade_references: collectRows('tradeRefsContainer', 'trade-ref-row'),
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/clientMasters/${clientUuid}/stage2`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Contacts & References Saved'
            });
            showStage(3);
        });

        // -------------------- STAGE 3 --------------------
        document.querySelectorAll('.invoiceSubmissionRadio').forEach(r => {
            r.addEventListener('change', function() {
                document.getElementById('invoiceElectronicFields').classList.toggle('hidden', this
                    .value !== 'electronic');
                document.getElementById('invoiceCourierFields').classList.toggle('hidden', this
                    .value !== 'courier');
            });
        });

        document.querySelectorAll('.paymentMethodRadio').forEach(r => {
            r.addEventListener('change', function() {
                document.getElementById('checkPickupFields').classList.toggle('hidden', this
                    .value !== 'check_pickup');
                document.getElementById('directRemittanceFields').classList.toggle('hidden', this
                    .value !== 'direct_remittance');
            });
        });

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
            // checkboxes not checked won't appear in FormData - normalize booleans
            ['standard_billing_service', 'document_handling', 'billing_summary_report'].forEach(f => {
                payload.finance[f] = form.querySelector(`[name="finance[${f}]"]`)?.checked ?? false;
            });
            return payload;
        }

        document.getElementById('saveStage3Btn').addEventListener('click', async function() {
            if (!clientUuid) {
                showMessage({
                    status: 'error',
                    title: 'Save previous stages first'
                });
                return;
            }

            const form = document.getElementById('stage3Form');
            const payload = formToNestedPayload(form);

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/clientMasters/${clientUuid}/stage3`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: leadId ?
                    'Client Master Data Completed! Lead moved to Opportunity.' :
                    'Client Master Data Completed!'
            });

            if (leadId) {
                loadPage({
                    title: 'CRM Leads',
                    link: '/page_crm'
                });
            } else {
                loadPage({
                    title: 'Client Masters',
                    link: '/page_clientMasters'
                });
            }
        });

        document.getElementById('btnBackToList').addEventListener('click', () => {
            loadPage({
                title: 'Client Masters',
                link: '/page_clientMasters'
            });
        });

        // -------------------- EDIT MODE: hydrate existing record --------------------
        async function hydrateExisting() {
            if (!clientUuid) return;

            document.getElementById('formPageTitle').textContent = 'Edit Client Master Data';

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/${clientUuid}`
            });
            if (!response.success) return;
            const c = response.data;

            const stage1Form = document.getElementById('stage1Form');
            Object.entries(c).forEach(([key, val]) => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = val ?? '';
            });

            document.getElementById('addressesContainer').innerHTML = '';
            const addresses = (c.addresses && c.addresses.length) ? c.addresses : [{
                is_primary: true
            }];
            await addAddressCardsFrom(addresses);

            document.getElementById('contactsContainer').innerHTML = '';
            (c.contacts || []).forEach(contact => {
                document.getElementById('contactsContainer').insertAdjacentHTML('beforeend',
                    contactRowHtml());
                const row = document.getElementById('contactsContainer').lastElementChild;
                row.querySelectorAll('[data-field]').forEach(input => input.value = contact[input
                    .dataset.field] ?? '');
            });

            document.getElementById('tradeRefsContainer').innerHTML = '';
            (c.trade_references || []).forEach(ref => {
                document.getElementById('tradeRefsContainer').insertAdjacentHTML('beforeend',
                    tradeRefRowHtml());
                const row = document.getElementById('tradeRefsContainer').lastElementChild;
                row.querySelectorAll('[data-field]').forEach(input => input.value = ref[input.dataset
                    .field] ?? '');
            });

            if (c.finance) {
                const stage3Form = document.getElementById('stage3Form');
                Object.entries(c.finance).forEach(([key, val]) => {
                    const el = stage3Form.querySelector(`[name="finance[${key}]"]`);
                    if (!el) return;
                    if (el.type === 'checkbox') el.checked = Boolean(val);
                    else if (el.type === 'radio') {
                        if (el.value === val) el.checked = true;
                    } else el.value = val ?? '';
                });
                document.querySelector(`.invoiceSubmissionRadio[value="${c.finance.invoice_submission}"]`)
                    ?.dispatchEvent(new Event('change'));
                document.querySelector(`.paymentMethodRadio[value="${c.finance.payment_method}"]`)
                    ?.dispatchEvent(new Event('change'));
            }

            if (c.billing) {
                const stage3Form = document.getElementById('stage3Form');
                Object.entries(c.billing).forEach(([key, val]) => {
                    const el = stage3Form.querySelector(`[name="billing[${key}]"]`);
                    if (el) el.value = val ?? '';
                });
            }

            showStage(c.current_stage || 1);
        }

        // -------------------- INIT --------------------
        showStage(1);

        fillAddressTypeOptions().then(() => {
            if (clientUuid) {
                hydrateExisting();
            } else {
                resetFormToBlank();
            }
        });


        const API = "https://psgc.cloud/api";

        async function request(url) {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`Failed to fetch ${url}`);
            }

            return response.json();
        }

        function resetSelect(select, placeholder) {

            select.innerHTML = "";

            const option = document.createElement("option");
            option.value = "";
            option.textContent = placeholder;

            select.appendChild(option);
            select.disabled = true;

        }

        function populateSelect(select, items, placeholder) {

            resetSelect(select, placeholder);

            items.forEach(item => {

                const option = document.createElement("option");

                option.value = item.name;
                option.textContent = item.name;

                option.dataset.code = item.code;

                select.appendChild(option);

            });

            select.disabled = false;

        }

        // -------------------- ADDRESSES: repeatable cards --------------------
        const COUNTRIES = [
            'Philippines', 'United States', 'Singapore', 'Hong Kong', 'China', 'Japan',
            'South Korea', 'Malaysia', 'Indonesia', 'Thailand', 'Vietnam', 'Taiwan',
            'Australia', 'United Kingdom', 'Canada', 'United Arab Emirates', 'Other',
        ];
        const countryOptionsHtml = COUNTRIES
            .map(c => `<option value="${c}" ${c === 'Philippines' ? 'selected' : ''}>${c}</option>`)
            .join('');

        let addressTypeOptionsHtml = '<option value="">Select Address Type</option>';

        async function fillAddressTypeOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/addresstype'
            });
            if (!Array.isArray(response)) return;
            addressTypeOptionsHtml += response.map(lov =>
                `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join('');
        }

        function addressCardHtml(index) {
            return `
    <div class="address-card border rounded-xl p-4 space-y-3" data-index="${index}">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <select data-field="address_type" class="w-full border rounded-lg px-3 py-2 text-sm font-semibold">
                    ${addressTypeOptionsHtml}
                </select>
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 whitespace-nowrap">
                    <input type="radio" name="address_primary_radio" class="primary-radio">
                    Primary
                </label>
            </div>
            <button type="button" class="remove-address text-red-500 text-xs font-medium">✕ Remove</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">No.</label>
                <input type="text" data-field="address_no" class="w-full border rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Building</label>
                <input type="text" data-field="address_building" class="w-full border rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Street</label>
                <input type="text" data-field="address_street" class="w-full border rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Country</label>
                <select data-field="address_country" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    ${countryOptionsHtml}
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Province</label>
                <select data-field="address_province" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Province</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Town/City</label>
                <select data-field="address_town_city" class="w-full border rounded-lg px-2 py-1.5 text-sm" disabled>
                    <option value="">Select Town/City</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Barangay</label>
                <select data-field="address_barangay" class="w-full border rounded-lg px-2 py-1.5 text-sm" disabled>
                    <option value="">Select Barangay</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Postal Code</label>
                <input type="text" data-field="address_postal_code" class="w-full border rounded-lg px-2 py-1.5 text-sm">
            </div>
        </div>
    </div>`;
        }

        async function addAddressCard() {
            const wrap = document.getElementById('addressesContainer');
            const index = wrap.children.length;
            wrap.insertAdjacentHTML('beforeend', addressCardHtml(index));
            const card = wrap.lastElementChild;

            card.querySelector('.remove-address').addEventListener('click', () => card.remove());
            if (index === 0) card.querySelector('.primary-radio').checked = true;

            await initializePhilippineAddress(card);
            return card;
        }

        document.getElementById('addAddressBtn').addEventListener('click', () => addAddressCard());

        async function hydrateAddressCard(card, address) {
            ['address_no', 'address_building', 'address_street', 'address_postal_code']
            .forEach(field => {
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

            resetSelect(city, "Select Town/City");
            resetSelect(barangay, "Select Barangay");

            async function loadCitiesForProvince(provinceCode) {
                resetSelect(city, "Loading...");
                resetSelect(barangay, "Select Barangay");

                if (!provinceCode) {
                    resetSelect(city, "Select Town/City");
                    return;
                }

                const cities = await request(
                    `${API}/provinces/${provinceCode}/cities-municipalities`
                );
                cities.sort((a, b) => a.name.localeCompare(b.name));
                populateSelect(city, cities, "Select Town/City");
            }

            async function loadBarangaysForCity(cityCode) {
                resetSelect(barangay, "Loading...");

                if (!cityCode) {
                    resetSelect(barangay, "Select Barangay");
                    return;
                }

                const barangays = await request(
                    `${API}/cities-municipalities/${cityCode}/barangays`
                );
                barangays.sort((a, b) => a.name.localeCompare(b.name));
                populateSelect(barangay, barangays, "Select Barangay");
            }

            // Load Provinces
            const provinces = await request(`${API}/provinces`);
            provinces.sort((a, b) => a.name.localeCompare(b.name));
            populateSelect(province, provinces, "Select Province");

            province.addEventListener("change", function() {
                const provinceCode = this.selectedOptions[0]?.dataset.code;
                loadCitiesForProvince(provinceCode);
            });

            city.addEventListener("change", function() {
                const cityCode = this.selectedOptions[0]?.dataset.code;
                loadBarangaysForCity(cityCode);
            });

            // Exposed so hydrateAddressCard() can cascade a saved province/city
            // selection exactly the way a manual selection would, without
            // re-registering listeners or re-fetching the province list.
            container._addressLookups = {
                loadCitiesForProvince,
                loadBarangaysForCity
            };
        }

    })();
</script>
