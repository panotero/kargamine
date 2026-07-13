<div class="container mx-auto p-5 max-w-5xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" id="formPageTitle">New Lead</h1>
            <p class="text-zinc-500 text-sm">Fill in each stage. You may save and continue later.</p>
        </div>
        <button id="btnBackToList"
            class="border border-zinc-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100">
            ← Back to List
        </button>
    </div>

    <div class="flex items-center gap-2 mb-6" id="stageStepper">
        <button type="button" class="stage-btn flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2"
            data-stage="1">
            1. Contact & Company Information
        </button>
        <button type="button" class="stage-btn flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2"
            data-stage="2">
            2. Container Requirements
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">

        {{-- ===================== STAGE 1 ===================== --}}
        <div class="stage-panel" data-panel="1">
            <form id="stage1Form" class="space-y-6">

                <div>
                    <p class="font-semibold text-zinc-700 mb-3">Contact Information</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Contact Name *</label>
                            <input type="text" name="contact_name" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Position</label>
                            <input type="text" name="position"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Mobile Number *</label>
                            <input type="text" name="mobile" required
                                class="format-mobile w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Email</label>
                            <input type="email" name="email"
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Source *</label>
                            <input type="text" name="source" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <p class="font-semibold text-zinc-700 mb-3">Company Information</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Company Name *</label>
                            <input type="text" name="company_name" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Company Address (Full) *</label>
                            <textarea name="company_address" rows="2" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">No. *</label>
                            <input type="text" name="address_no" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Building *</label>
                            <input type="text" name="address_building" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Street *</label>
                            <input type="text" name="address_street" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Barangay *</label>
                            <input type="text" name="address_barangay" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Town/City *</label>
                            <input type="text" name="address_town_city" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Province *</label>
                            <input type="text" name="address_province" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Country *</label>
                            <input type="text" name="address_country" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-zinc-400 uppercase">Postal Code *</label>
                            <input type="text" name="address_postal_code" required
                                class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-zinc-400 uppercase">Type of Business *</label>
                            <select name="type_of_business" required
                                class="typeOfBusinessDropdown w-full border rounded-lg px-3 py-2 text-sm mt-1">
                                <option value="">Select Type of Business</option>
                            </select>
                        </div>
                    </div>
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
                <p class="font-semibold text-zinc-700">Container Requirements</p>
                <button type="button" id="addContainerBtn"
                    class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add Container</button>
            </div>
            <div id="containersContainer" class="space-y-4"></div>

            <div class="flex justify-between gap-2 mt-6 pt-4 border-t">
                <button class="stage-prev border px-5 py-2 rounded-lg text-sm font-medium"
                    data-target="1">Previous</button>
                <button id="saveStage2Btn"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Save & Finish
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        let leadUuid = window.crmLeadFormUuid || null;
        window.crmLeadFormUuid = null;
        let currentStage = 1;

        const CONTAINER_TYPES = [{
                value: 'CV',
                label: 'Container Van (CV)'
            },
            {
                value: 'FR',
                label: 'Flatrack (FR)'
            },
            {
                value: 'RF',
                label: 'Reefer Van (RF)'
            },
            {
                value: 'LC',
                label: 'Loose Cargo (LC)'
            },
            {
                value: 'RC',
                label: 'Rolling Cargo (RC)'
            },
        ];

        // Which extra field groups show for each container type.
        const TYPE_FIELD_VISIBILITY = {
            CV: {
                convanClass: true,
                convanSize: true,
                temperature: false,
                cbmTon: false,
                splitServiceMode: true
            },
            FR: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: false,
                splitServiceMode: true
            },
            RF: {
                convanClass: true,
                convanSize: false,
                temperature: true,
                cbmTon: false,
                splitServiceMode: true
            },
            LC: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: true,
                splitServiceMode: false
            },
            RC: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: true,
                splitServiceMode: false
            },
        };

        // -------------------- STEPPER --------------------
        function showStage(stage) {
            currentStage = stage;
            document.querySelectorAll('.stage-panel').forEach(p => p.classList.toggle('hidden', Number(p.dataset
                .panel) !== stage));
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
                if (leadUuid || Number(btn.dataset.stage) === 1) showStage(Number(btn.dataset
                    .stage));
            });
        });
        document.querySelectorAll('.stage-prev').forEach(btn => {
            btn.addEventListener('click', () => showStage(Number(btn.dataset.target)));
        });

        // -------------------- TYPE OF BUSINESS DROPDOWN --------------------
        async function fillTypeOfBusiness() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/listofval/typeofbusiness'
            });
            const select = document.querySelector('.typeOfBusinessDropdown');
            if (!select || !Array.isArray(response)) return;
            response.forEach(lov => {
                select.insertAdjacentHTML('beforeend',
                    `<option value="${lov.lov_name}">${lov.lov_name}</option>`);
            });
        }

        // -------------------- STAGE 1 --------------------
        document.getElementById('saveStage1Btn').addEventListener('click', async function() {
            const form = document.getElementById('stage1Form');
            const data = Object.fromEntries(new FormData(form).entries());
            if (leadUuid) data.uuid = leadUuid;

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: data,
                url: '/api/crm/leads/stage1',
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving',
                    message: response.message ?? 'Please check the required fields.'
                });
                return;
            }

            leadUuid = response.data.uuid;
            showMessage({
                status: 'success',
                title: 'Contact & Company Information Saved'
            });
            showStage(2);
        });

        // -------------------- STAGE 2: dynamic container cards --------------------
        function containerCardHtml(index) {
            return `
            <div class="container-card border rounded-xl p-4 space-y-3" data-index="${index}">
                <div class="flex justify-between items-center">
                    <select data-field="container_type" class="type-select border rounded-lg px-3 py-2 text-sm font-semibold">
                        ${CONTAINER_TYPES.map(t => `<option value="${t.value}">${t.label}</option>`).join('')}
                    </select>
                    <button type="button" class="remove-container text-red-500 text-xs font-medium">✕ Remove</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Origin</label>
                        <input type="text" data-field="origin" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination</label>
                        <input type="text" data-field="destination" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>

                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Booking Unit Type</label>
                        <input type="text" data-field="booking_unit_type" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>

                    <div class="field-convan-class hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">ConVan Class</label>
                        <input type="text" data-field="convan_class" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="field-convan-size hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">ConVan Size</label>
                        <input type="text" data-field="convan_size" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="field-temperature hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Required Temperature (°C)</label>
                        <input type="number" step="0.1" data-field="required_temperature" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>

                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                        <input type="number" data-field="quantity" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>

                    <div class="field-cbm-ton hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Estimated CBM/s</label>
                        <input type="number" step="0.01" data-field="estimated_cbm" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="field-cbm-ton hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Estimated Ton/s</label>
                        <input type="number" step="0.01" data-field="estimated_ton" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>

                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                        <input type="number" step="0.01" data-field="declared_value_per_unit" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                        <input type="text" data-field="frequency" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[11px] text-zinc-400 uppercase">General Cargo Description</label>
                        <textarea data-field="general_cargo_description" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm"></textarea>
                    </div>

                    <div class="field-split-service hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Service Mode - Pier (Origin)</label>
                        <input type="text" data-field="service_mode_origin" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="field-split-service hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Service Mode - Pier (Destination)</label>
                        <input type="text" data-field="service_mode_destination" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div class="field-single-service hidden md:col-span-2">
                        <label class="text-[11px] text-zinc-400 uppercase">Service Mode</label>
                        <input type="text" data-field="service_mode" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" data-field="dangerous_cargo" class="dg-checkbox">
                            <span class="text-sm">Dangerous Cargo (DG)</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[11px] text-zinc-400 uppercase">DG Documentary Requirement</label>
                        <textarea data-field="dg_documentary_requirement" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                        <textarea data-field="special_requirements" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[11px] text-zinc-400 uppercase">Special Notes</label>
                        <textarea data-field="special_notes" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm"></textarea>
                    </div>
                </div>
            </div>`;
        }

        function applyTypeVisibility(card) {
            const type = card.querySelector('.type-select').value;
            const flags = TYPE_FIELD_VISIBILITY[type];

            card.querySelector('.field-convan-class').classList.toggle('hidden', !flags.convanClass);
            card.querySelector('.field-convan-size').classList.toggle('hidden', !flags.convanSize);
            card.querySelector('.field-temperature').classList.toggle('hidden', !flags.temperature);
            card.querySelectorAll('.field-cbm-ton').forEach(el => el.classList.toggle('hidden', !flags.cbmTon));
            card.querySelectorAll('.field-split-service').forEach(el => el.classList.toggle('hidden', !flags
                .splitServiceMode));
            card.querySelector('.field-single-service').classList.toggle('hidden', flags.splitServiceMode);
        }

        function addContainerCard() {
            const wrap = document.getElementById('containersContainer');
            const index = wrap.children.length;
            wrap.insertAdjacentHTML('beforeend', containerCardHtml(index));
            const card = wrap.lastElementChild;

            card.querySelector('.type-select').addEventListener('change', () => applyTypeVisibility(card));
            card.querySelector('.remove-container').addEventListener('click', () => card.remove());
            applyTypeVisibility(card);
        }

        document.getElementById('addContainerBtn').addEventListener('click', addContainerCard);

        function collectContainers() {
            return Array.from(document.querySelectorAll('.container-card')).map(card => {
                const obj = {};
                card.querySelectorAll('[data-field]').forEach(el => {
                    if (el.type === 'checkbox') {
                        obj[el.dataset.field] = el.checked;
                    } else {
                        obj[el.dataset.field] = el.value;
                    }
                });
                return obj;
            });
        }

        document.getElementById('saveStage2Btn').addEventListener('click', async function() {
            if (!leadUuid) {
                showMessage({
                    status: 'error',
                    title: 'Save Stage 1 first'
                });
                return;
            }

            const containers = collectContainers();
            if (!containers.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one container.'
                });
                return;
            }

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    containers
                },
                url: `/api/crm/leads/${leadUuid}/stage2`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Lead moved to Opportunity!'
            });
            loadPage({
                title: 'CRM Leads',
                link: '/page_crm'
            });
        });

        document.getElementById('btnBackToList').addEventListener('click', () => {
            loadPage({
                title: 'CRM Leads',
                link: '/page_crm'
            });
        });

        // -------------------- HYDRATE EXISTING (edit / resume) --------------------
        async function hydrateExisting() {
            if (!leadUuid) return;

            document.getElementById('formPageTitle').textContent = 'Edit Lead';

            const response = await apiCall({
                mode: 'GET',
                url: `/api/crm/leads/${leadUuid}`
            });
            if (!response.success) return;
            const lead = response.data;
            const company = lead.company ?? {};

            const stage1Form = document.getElementById('stage1Form');
            ['contact_name', 'position', 'mobile', 'email', 'source'].forEach(key => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = lead[key] ?? '';
            });
            [
                'company_name', 'company_address', 'address_no', 'address_building',
                'address_street', 'address_barangay', 'address_town_city',
                'address_province', 'address_country', 'address_postal_code', 'type_of_business',
            ].forEach(key => {
                const el = stage1Form.querySelector(`[name="${key}"]`);
                if (el) el.value = company[key] ?? '';
            });

            document.getElementById('containersContainer').innerHTML = '';
            (lead.containers || []).forEach(c => {
                addContainerCard();
                const card = document.getElementById('containersContainer').lastElementChild;
                Object.entries(c).forEach(([key, val]) => {
                    const el = card.querySelector(`[data-field="${key}"]`);
                    if (!el) return;
                    if (el.type === 'checkbox') el.checked = Boolean(val);
                    else el.value = val ?? '';
                });
                card.querySelector('.type-select').value = c.container_type;
                applyTypeVisibility(card);
            });

            showStage(lead.current_stage || 1);
        }

        // -------------------- INIT --------------------
        showStage(1);
        fillTypeOfBusiness().then(() => {
            if (leadUuid) {
                hydrateExisting();
            } else {
                addContainerCard(); // start with one blank container row
            }
        });
    })();
</script>
