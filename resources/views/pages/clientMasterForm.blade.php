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
            3. Finance, Billing & Sales
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

        {{-- ===================== STAGE 1 ===================== --}}
        <div class="stage-panel" data-panel="1">
            <form id="stage1Form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-medium text-zinc-400 uppercase">Customer Code</label>
                    <input type="text" name="customer_code" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs font-medium text-zinc-400 uppercase">Company Name</label>
                    <input type="text" name="company_name" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs font-medium text-zinc-400 uppercase">Registered Company Address</label>
                    <textarea name="registered_address" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                </div>
                <div>
                    <label class="text-xs font-medium text-zinc-400 uppercase">Contact Number (Primary)</label>
                    <input type="text" name="contact_number_1"
                        class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                </div>
                <div>
                    <label class="text-xs font-medium text-zinc-400 uppercase">Contact Number (Secondary)</label>
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
                    <label class="text-xs font-medium text-zinc-400 uppercase">Business Established Date</label>
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
                    <label class="text-xs font-medium text-zinc-400 uppercase">Sales Representative</label>
                    <select name="sales_rep_id"
                        class="salesRepDropdown w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        <option value="">Select Sales Rep</option>
                    </select>
                </div>

                <div class="border-t pt-4">
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
        const params = new URLSearchParams(window.location.search);
        let clientUuid = window.clientMasterFormUuid || null;
        window.clientMasterFormUuid = null;
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
        document.getElementById('saveStage1Btn').addEventListener('click', async function() {
            const form = document.getElementById('stage1Form');
            const data = Object.fromEntries(new FormData(form).entries());
            if (clientUuid) data.uuid = clientUuid;

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
            // REMOVED: history.replaceState(...) — no longer needed, and was
            // the thing polluting the browser URL for the next page load.
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
            document.getElementById('invoiceElectronicFields').classList.add('hidden');
            document.getElementById('invoiceCourierFields').classList.add('hidden');
            document.getElementById('checkPickupFields').classList.add('hidden');
            document.getElementById('directRemittanceFields').classList.add('hidden');
        }

        // -------------------- STAGE 2: dynamic rows --------------------
        function contactRowHtml() {
            return `
            <div class="contact-row grid grid-cols-1 md:grid-cols-5 gap-2 border rounded-lg p-3 relative">
                <input type="text" data-field="contact_name" placeholder="Contact Name" class="border rounded-lg px-2 py-1.5 text-sm">
                <input type="text" data-field="contact_number" placeholder="Contact Number" class="border rounded-lg px-2 py-1.5 text-sm">
                <input type="email" data-field="contact_email" placeholder="Email" class="border rounded-lg px-2 py-1.5 text-sm">
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
                title: 'Client Master Data Completed!'
            });
            loadPage({
                title: 'Client Masters',
                link: '/page_clientMasters'
            });
        });

        document.getElementById('btnBackToList').addEventListener('click', () => {
            loadPage({
                title: 'Client Masters',
                link: '/page_clientMasters'
            });
        });

        // -------------------- SALES REP DROPDOWN --------------------
        async function fillSalesRepDropdown() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/users'
            });
            const select = document.querySelector('.salesRepDropdown');
            if (!select || !Array.isArray(response)) return;
            response.forEach(u => {
                select.insertAdjacentHTML('beforeend', `<option value="${u.id}">${u.name}</option>`);
            });
        }

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

            if (c.sales_rep_id) {
                document.querySelector('.salesRepDropdown').value = c.sales_rep_id;
            }

            showStage(c.current_stage || 1);
        }

        // -------------------- INIT --------------------
        showStage(1);
        if (clientUuid) {
            fillSalesRepDropdown().then(hydrateExisting);
        } else {
            resetFormToBlank();
            fillSalesRepDropdown();
        }
        fillSalesRepDropdown().then(hydrateExisting);
    })();
</script>
