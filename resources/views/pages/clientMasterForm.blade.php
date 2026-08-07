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
            3. Finance
        </button>
        <button type="button" class="stage-btn flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2"
            data-stage="4">
            4. Ancillary Services
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
                            <label class="text-xs font-medium text-zinc-400 uppercase">Client Code</label>
                            <input type="text" name="customer_code" readonly placeholder="Generated on save"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 bg-zinc-50 text-zinc-600 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Client Mnemonic</label>
                            <input type="text" name="client_mnemonic"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Client/Business Name</label>
                            <input type="text" name="company_name"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Client Category</label>
                            <select name="client_category"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                <option value="">Select Client Category</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Client Classification</label>
                            <select name="client_classification"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                <option value="">Select Client Classification</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Client Industry</label>
                            <select name="industry"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                <option value="">Select Industry</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">CSR</label>
                            <input type="text" id="csrDisplay" readonly
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 bg-zinc-50 text-zinc-600 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Account Manager</label>
                            <input type="text" id="accountManagerDisplay" readonly
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 bg-zinc-50 text-zinc-600 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Addresses - repeatable, one per address type --}}
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-zinc-700">Address(es) <span class="req-asterisk">*</span>
                        </p>
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
            <div id="contactsContainer" class="space-y-4 mb-6"></div>

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
                    <p class="font-semibold text-zinc-700 mb-3">Finance</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Client Business Name</label>
                            <input type="text" name="finance[client_business_name]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">TIN Number</label>
                            <input type="text" name="finance[tin_number]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-zinc-400 uppercase">TIN Registered Address</label>
                            <textarea name="finance[tin_registered_address]" rows="2"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Registered Tax Type</label>
                            <select name="finance[registered_tax_type]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                <option value="">— Select —</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Withholding Tax Code</label>
                            <input type="text" name="finance[withholding_tax_code]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Trade Name</label>
                            <input type="text" name="finance[trade_name]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">TIN Registration Date</label>
                            <input type="date" name="finance[tin_registration_date]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Line of Business</label>
                            <input type="text" name="finance[line_of_business]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Tax Percent</label>
                            <input type="number" step="0.01" name="finance[tax_percent]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Withholding Tax Percent</label>
                            <input type="number" step="0.01" name="finance[withholding_tax_percent]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                        </div>
                        <div id="modeOfPaymentField">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Mode of Payment</label>
                            <select name="finance[mode_of_payment]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                <option value="">Select Mode of Payment</option>
                            </select>
                        </div>
                        <div id="creditTermsField" class="hidden">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Credit Terms</label>
                            <select name="finance[credit_terms]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                <option value="">Select Credit Terms</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Cargo Release Order
                                (CRO)</label>
                            <select name="finance[cro]"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                                <option value="">Select CRO</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Commodity Type / Maximum Declared Value - repeatable --}}
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-zinc-700">Commodity Type &amp; Maximum Declared Value</p>
                        <button type="button" id="addCommodityBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add
                            Commodity</button>
                    </div>
                    <div id="commodityContainer" class="space-y-3"></div>
                </div>
            </form>

            <div class="flex justify-between gap-2 mt-6 pt-4 border-t">
                <button class="stage-prev border px-5 py-2 rounded-lg text-sm font-medium"
                    data-target="2">Previous</button>
                <button id="saveStage3Btn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Save & Continue
                </button>
            </div>
        </div>

        {{-- ===================== STAGE 4 ===================== --}}
        <div class="stage-panel hidden" data-panel="4">

            <div class="flex justify-between items-center mb-3">
                <p class="font-semibold text-zinc-700">Ancillary Services</p>
                <button type="button" id="addAncillaryBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add Service</button>
            </div>
            <div id="ancillaryContainer" class="space-y-4"></div>

            <div class="flex justify-between gap-2 mt-6 pt-4 border-t">
                <button class="stage-prev border px-5 py-2 rounded-lg text-sm font-medium"
                    data-target="3">Previous</button>
                <button id="saveStage4Btn"
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

        const MODE_OF_PAYMENT_OPTIONS = ['Cash', 'Credit'];
        // Hardcoded for now - swap for an LOV-backed dropdown later if the
        // business ends up needing non-standard credit terms.
        const CREDIT_TERMS_OPTIONS = ['COD', '7 Days', '15 Days', '30 Days', '45 Days', '60 Days', '90 Days'];
        // CRO = Cargo Release Order, not a Credit Officer user.
        const CRO_OPTIONS = ['Manual', 'Automatic'];

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

            if (stage === 3) {
                // Prefill Client Business Name from Company Name the first time
                // the Finance stage is populated for a new record.
                const businessNameInput = document.querySelector(
                    '#stage3Form [name="finance[client_business_name]"]');
                if (businessNameInput && !businessNameInput.value) {
                    businessNameInput.value = document.querySelector(
                        '#stage1Form [name="company_name"]')?.value || '';
                }
                applyModeOfPaymentVisibility();
            }
        }

        // -------------------- STAGE 3: Mode of Payment -> Credit Terms --------------------
        function applyModeOfPaymentVisibility() {
            const mode = document.querySelector('#stage3Form [name="finance[mode_of_payment]"]')?.value;
            document.getElementById('creditTermsField').classList.toggle('hidden', mode !== 'Credit');
        }

        document.querySelector('#stage3Form [name="finance[mode_of_payment]"]')
            .addEventListener('change', applyModeOfPaymentVisibility);

        // -------------------- STAGE 3: Registered Tax Type -> Tax Percent auto-fill --------------------
        let taxTypeRatesMap = {};

        document.querySelector('#stage3Form [name="finance[registered_tax_type]"]')
            .addEventListener('change', function() {
                const rate = taxTypeRatesMap[this.value];
                if (rate === undefined || rate === null) return;
                const taxPercentInput = document.querySelector('#stage3Form [name="finance[tax_percent]"]');
                if (taxPercentInput) taxPercentInput.value = rate;
            });

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
        let mnemonicTouched = false;

        function deriveMnemonic(name) {
            return (name || '')
                .split(/\s+/)
                .map(word => word.replace(/[^a-zA-Z]/g, ''))
                .filter(word => word.length > 0)
                .map(word => word[0].toUpperCase())
                .join('');
        }

        const companyNameInput = document.querySelector('#stage1Form [name="company_name"]');
        const mnemonicInput = document.querySelector('#stage1Form [name="client_mnemonic"]');

        companyNameInput.addEventListener('input', () => {
            if (mnemonicTouched) return;
            mnemonicInput.value = deriveMnemonic(companyNameInput.value);
        });

        mnemonicInput.addEventListener('input', () => {
            mnemonicTouched = true;
        });

        function collectAddressesFrom(container) {
            return Array.from(container.querySelectorAll('.address-card')).map(card => {
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

            const addresses = collectAddressesFrom(document.getElementById('addressesContainer'));
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
            document.querySelector('#stage1Form [name="customer_code"]').value = response.data
                .customer_code ?? '';
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
            document.getElementById('addressesContainer').innerHTML = '';
            document.getElementById('commodityContainer').innerHTML = '';
            document.getElementById('ancillaryContainer').innerHTML = '';
            document.getElementById('csrDisplay').value = '—';
            document.getElementById('accountManagerDisplay').value = '—';
            contactCounter = 0;
            mnemonicTouched = false;
            applyModeOfPaymentVisibility();

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

        async function addAddressCardsFrom(addresses, container = document.getElementById(
            'addressesContainer'), groupName = 'address_primary_radio') {
            for (const address of addresses) {
                const card = await addAddressCard(container, groupName);
                await hydrateAddressCard(card, address);
            }
        }

        // -------------------- STAGE 2: dynamic rows --------------------
        let contactCounter = 0;

        function typeSelectHtml(field) {
            return `<select data-field="${field}" class="border rounded-lg px-1 py-1.5 text-xs w-24 shrink-0 dark:text-zinc-900">
                <option value="">Type</option>
                <option value="personal">Personal</option>
                <option value="business">Business</option>
            </select>`;
        }

        function contactCardHtml(idx) {
            return `
            <div class="contact-card border rounded-xl p-4 space-y-4 relative" data-contact-index="${idx}">
                <button type="button" class="remove-contact absolute top-3 right-3 text-red-500 text-xs font-medium">✕ Remove</button>
                <div class="contact-fields grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Department</label>
                        <input type="text" data-field="contact_department" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Title</label>
                        <select data-field="title" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            <option value="">Select Title</option>
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Miss">Miss</option>
                            <option value="Dr.">Dr.</option>
                            <option value="Engr.">Engr.</option>
                            <option value="Atty.">Atty.</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Gender</label>
                        <select data-field="gender" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Rather not say">Rather not say</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">First Name</label>
                        <input type="text" data-field="first_name" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Last Name</label>
                        <input type="text" data-field="last_name" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Position</label>
                        <input type="text" data-field="position" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Landline</label>
                        <div class="flex gap-1">
                            <input type="text" data-field="landline_number" class="border rounded-lg px-2 py-1.5 text-sm flex-1 min-w-0 dark:text-zinc-900">
                            ${typeSelectHtml('landline_type')}
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Mobile</label>
                        <div class="flex gap-1">
                            <input type="text" data-field="mobile" class="border rounded-lg px-2 py-1.5 text-sm flex-1 min-w-0 dark:text-zinc-900">
                            ${typeSelectHtml('mobile_type')}
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Email</label>
                        <div class="flex gap-1">
                            <input type="email" data-field="email" class="border rounded-lg px-2 py-1.5 text-sm flex-1 min-w-0 dark:text-zinc-900">
                            ${typeSelectHtml('email_type')}
                        </div>
                    </div>
                </div>

                <div class="border-t pt-3">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-500">Address(es)</p>
                        <button type="button" class="add-contact-address-btn text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add Address</button>
                    </div>
                    <div class="contact-addresses-container space-y-3"></div>
                </div>
            </div>`;
        }

        function addContactCard() {
            const container = document.getElementById('contactsContainer');
            const idx = contactCounter++;
            container.insertAdjacentHTML('beforeend', contactCardHtml(idx));
            return container.lastElementChild;
        }

        document.getElementById('addContactBtn').addEventListener('click', async () => {
            const card = addContactCard();
            await addAddressCardsFrom([{
                    is_primary: true
                }], card.querySelector('.contact-addresses-container'),
                `address_primary_${card.dataset.contactIndex}`);
        });
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-contact')) {
                e.target.closest('.contact-card')?.remove();
            }
            if (e.target.classList.contains('add-contact-address-btn')) {
                const card = e.target.closest('.contact-card');
                addAddressCard(card.querySelector('.contact-addresses-container'),
                    `address_primary_${card.dataset.contactIndex}`);
            }
            if (e.target.classList.contains('remove-commodity')) {
                e.target.closest('.commodity-row')?.remove();
            }
        });

        function collectContacts() {
            return Array.from(document.querySelectorAll('#contactsContainer .contact-card')).map(card => {
                const obj = {};
                card.querySelectorAll('.contact-fields [data-field]').forEach(input => {
                    obj[input.dataset.field] = input.value;
                });
                obj.addresses = collectAddressesFrom(card.querySelector('.contact-addresses-container'));
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
                contacts: collectContacts(),
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
            payload.commodity_declared_values = collectCommodityDeclaredValues();

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
                title: 'Finance Saved'
            });
            showStage(4);
        });

        // -------------------- Commodity Type / Max Declared Value: repeatable --------------------
        function commodityRowHtml() {
            return `
            <div class="commodity-row grid grid-cols-1 md:grid-cols-3 gap-2 border rounded-lg p-3 relative">
                <input type="text" data-field="commodity_type" placeholder="Commodity Type" class="border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <input type="text" inputmode="decimal" data-field="max_declared_value" placeholder="Maximum Declared Value" class="currency-input border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <div class="flex justify-end">
                    <button type="button" class="remove-commodity text-red-500 px-2 text-xs font-medium">✕ Remove</button>
                </div>
            </div>`;
        }

        document.getElementById('addCommodityBtn').addEventListener('click', () => {
            document.getElementById('commodityContainer').insertAdjacentHTML('beforeend',
                commodityRowHtml());
        });

        function collectCommodityDeclaredValues() {
            return Array.from(document.querySelectorAll('#commodityContainer .commodity-row')).map(row => {
                const obj = {};
                row.querySelectorAll('[data-field]').forEach(input => {
                    obj[input.dataset.field] = input.classList.contains('currency-input') ?
                        parseCurrencyValue(input.value) : input.value;
                });
                return obj;
            });
        }

        // -------------------- STAGE 4: Ancillary Services --------------------
        // Just a list of special charges the client is availing - no rates,
        // no calculation, no payment info. Each row is Special Charge / CY /
        // Unit / Quantity only.
        function ancillaryRowHtml() {
            return `
            <div class="ancillary-row border rounded-xl p-4 space-y-3 relative">
                <button type="button" class="remove-ancillary absolute top-3 right-3 text-red-500 text-xs font-medium">✕ Remove</button>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Special Charge</label>
                        <select data-field="required_service" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            ${specialChargeOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">CY</label>
                        <select data-field="location" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            ${cargoYardOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Unit</label>
                        <select data-field="unit" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            ${unitOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                        <input type="number" step="0.01" data-field="quantity" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                </div>
            </div>`;
        }

        function addAncillaryRow() {
            document.getElementById('ancillaryContainer').insertAdjacentHTML('beforeend',
                ancillaryRowHtml());
            return document.getElementById('ancillaryContainer').lastElementChild;
        }

        document.getElementById('addAncillaryBtn').addEventListener('click', () => addAncillaryRow());

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-ancillary')) {
                e.target.closest('.ancillary-row')?.remove();
            }
        });

        function collectAncillaryServices() {
            return Array.from(document.querySelectorAll('#ancillaryContainer .ancillary-row')).map(
                row => {
                    const obj = {};
                    ['required_service', 'location', 'unit', 'quantity'].forEach(field => {
                        const el = row.querySelector(`[data-field="${field}"]`);
                        obj[field] = el ? el.value : '';
                    });
                    return obj;
                });
        }

        document.getElementById('saveStage4Btn').addEventListener('click', async function() {
            if (!clientUuid) {
                showMessage({
                    status: 'error',
                    title: 'Save previous stages first'
                });
                return;
            }

            const payload = {
                ancillary_services: collectAncillaryServices()
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/clientMasters/${clientUuid}/stage4`,
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
            mnemonicTouched = true;

            document.getElementById('csrDisplay').value = c.sales_rep?.name ?? '—';
            document.getElementById('accountManagerDisplay').value = c.account_manager?.name ?? '—';

            document.getElementById('addressesContainer').innerHTML = '';
            const addresses = (c.addresses && c.addresses.length) ? c.addresses : [{
                is_primary: true
            }];
            await addAddressCardsFrom(addresses);

            document.getElementById('contactsContainer').innerHTML = '';
            contactCounter = 0;
            for (const contact of (c.contacts || [])) {
                const card = addContactCard();
                card.querySelectorAll('.contact-fields [data-field]').forEach(el => {
                    el.value = contact[el.dataset.field] ?? '';
                });
                await addAddressCardsFrom(contact.addresses || [], card.querySelector(
                    '.contact-addresses-container'), `address_primary_${card.dataset.contactIndex}`);
            }

            if (c.finance) {
                const stage3Form = document.getElementById('stage3Form');
                Object.entries(c.finance).forEach(([key, val]) => {
                    const el = stage3Form.querySelector(`[name="finance[${key}]"]`);
                    if (el) el.value = val ?? '';
                });
            }

            document.getElementById('commodityContainer').innerHTML = '';
            (c.commodity_declared_values || []).forEach(row => {
                document.getElementById('commodityContainer').insertAdjacentHTML('beforeend',
                    commodityRowHtml());
                const el = document.getElementById('commodityContainer').lastElementChild;
                el.querySelectorAll('[data-field]').forEach(input => {
                    const val = row[input.dataset.field] ?? '';
                    input.value = input.classList.contains('currency-input') ?
                        formatCurrencyDisplay(val) : val;
                });
            });

            document.getElementById('ancillaryContainer').innerHTML = '';
            (c.ancillary_services || []).forEach(svc => {
                const row = addAncillaryRow();
                ['required_service', 'location', 'unit', 'quantity'].forEach(field => {
                    const el = row.querySelector(`[data-field="${field}"]`);
                    if (el) el.value = svc[field] ?? '';
                });
            });

            showStage(c.current_stage || 1);
        }

        // -------------------- INIT --------------------
        showStage(1);

        Promise.all([
            fillAddressTypeOptions(),
            fillIndustryOptions(),
            fillClientCategoryOptions(),
            fillClientClassificationOptions(),
            fillModeOfPaymentOptions(),
            fillCreditTermsOptions(),
            fillCroOptions(),
            fillRegisteredTaxTypeOptions(),
            fillSpecialChargeOptions(),
            fillCargoYardOptions(),
            fillUnitOptions(),
        ]).then(() => {
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

                // The PSGC API returns some province/city/barangay names with
                // inconsistent trailing whitespace. Laravel's TrimStrings
                // middleware trims it server-side on save, so an untrimmed
                // option value here would never match the trimmed value
                // that comes back on hydration - the dropdown would silently
                // fail to re-select, breaking the city/barangay cascade.
                const name = (item.name || '').trim();
                option.value = name;
                option.textContent = name;

                option.dataset.code = item.code;

                select.appendChild(option);

            });

            select.disabled = false;

        }

        // -------------------- STAGE 1 LOVs --------------------
        async function fillIndustryOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/industry'
            });
            if (!Array.isArray(response)) return;
            const select = document.querySelector('#stage1Form [name="industry"]');
            select.insertAdjacentHTML('beforeend', response.map(lov =>
                `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join(''));
        }

        async function fillClientCategoryOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/clientcategory'
            });
            if (!Array.isArray(response)) return;
            const select = document.querySelector('#stage1Form [name="client_category"]');
            select.insertAdjacentHTML('beforeend', response.map(lov =>
                `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join(''));
        }

        async function fillClientClassificationOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/clientclassification'
            });
            if (!Array.isArray(response)) return;
            const select = document.querySelector('#stage1Form [name="client_classification"]');
            select.insertAdjacentHTML('beforeend', response.map(lov =>
                `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join(''));
        }

        async function fillModeOfPaymentOptions() {
            const select = document.querySelector('#stage3Form [name="finance[mode_of_payment]"]');
            select.insertAdjacentHTML('beforeend', MODE_OF_PAYMENT_OPTIONS.map(v =>
                `<option value="${v}">${v}</option>`).join(''));
        }

        async function fillCreditTermsOptions() {
            const select = document.querySelector('#stage3Form [name="finance[credit_terms]"]');
            select.insertAdjacentHTML('beforeend', CREDIT_TERMS_OPTIONS.map(v =>
                `<option value="${v}">${v}</option>`).join(''));
        }

        async function fillCroOptions() {
            const select = document.querySelector('#stage3Form [name="finance[cro]"]');
            select.insertAdjacentHTML('beforeend', CRO_OPTIONS.map(v =>
                `<option value="${v}">${v}</option>`).join(''));
        }

        async function fillRegisteredTaxTypeOptions() {
            const select = document.querySelector('#stage3Form [name="finance[registered_tax_type]"]');
            const response = await apiCall({
                mode: 'GET',
                url: '/api/vatRates?per_page=1000'
            });
            if (!response.success || !Array.isArray(response.data?.data)) return;

            // Only active rows represent a currently selectable tax type; also
            // build the { tax_type: rate_percent } lookup used to auto-fill
            // Tax Percent on selection.
            response.data.data
                .filter(vr => vr.is_active)
                .forEach(vr => {
                    taxTypeRatesMap[vr.tax_type] = vr.rate_percent;
                });

            select.insertAdjacentHTML('beforeend', Object.keys(taxTypeRatesMap).map(v =>
                `<option value="${v}">${v}</option>`).join(''));
        }

        // -------------------- STAGE 4 LOVs (Special Charges / CY / Unit) --------------------
        let specialChargeOptionsHtml = '<option value="">Select Special Charge</option>';
        let cargoYardOptionsHtml = '<option value="">Select CY</option>';
        let unitOptionsHtml = '<option value="">Select Unit</option>';

        async function fillSpecialChargeOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/specialCharges?per_page=1000'
            });
            if (!response.success || !Array.isArray(response.data?.data)) return;
            response.data.data.forEach(sc => {
                specialChargeOptionsHtml += `<option value="${sc.name}">${sc.name}</option>`;
            });
        }

        async function fillCargoYardOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/cargoYards?per_page=1000'
            });
            if (!response.success || !Array.isArray(response.data?.data)) return;
            response.data.data.forEach(cy => {
                cargoYardOptionsHtml += `<option value="${cy.name}">${cy.name}</option>`;
            });
        }

        async function fillUnitOptions() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/unit'
            });
            if (!Array.isArray(response)) return;
            response.forEach(lov => {
                unitOptionsHtml += `<option value="${lov.lov_name}">${lov.lov_name}</option>`;
            });
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
                <select data-field="address_type" class="w-full border rounded-lg px-3 py-2 text-sm font-semibold dark:text-zinc-900">
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

        async function addAddressCard(container = document.getElementById('addressesContainer'),
            groupName = 'address_primary_radio') {
            const index = container.children.length;
            container.insertAdjacentHTML('beforeend', addressCardHtml(index));
            const card = container.lastElementChild;

            const radio = card.querySelector('.primary-radio');
            radio.name = groupName;

            card.querySelector('.remove-address').addEventListener('click', () => card.remove());
            if (index === 0) radio.checked = true;

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
