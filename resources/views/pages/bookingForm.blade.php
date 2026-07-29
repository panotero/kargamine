<div class="container mx-auto p-5 max-w-6xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" id="formPageTitle">New Booking</h1>
            <p class="text-zinc-500 text-sm">A booking is saved as Draft until you confirm it. Each cargo line has its own route and delivery mode - a single booking can ship to more than one destination.</p>
        </div>
        <button id="btnBackToList" type="button"
            class="border border-zinc-300 px-4 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100">
            ← Back to List
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-5">

        {{-- MAIN FORM --}}
        <div class="space-y-5">

            {{-- Client --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
                <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Client</p>
                <input type="hidden" id="clientId">
                <div id="clientSelectedDisplay" class="hidden flex items-center justify-between bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm">
                    <span id="clientSelectedName" class="font-medium"></span>
                    <button type="button" id="clientChangeBtn" class="text-xs text-blue-600 hover:underline">Change</button>
                </div>
                <div id="clientSearchWrap" class="relative">
                    <input type="text" id="clientSearchInput" placeholder="Search client by company name or code..."
                        class="w-full border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
                    <div id="clientSearchResults"
                        class="hidden absolute z-20 mt-1 w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-56 overflow-y-auto"></div>
                </div>

                {{-- Contracted lanes/containers for the selected client - quick-add shortcuts --}}
                <div id="contractSuggestions" class="hidden mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                    <p class="text-[11px] font-medium text-zinc-400 uppercase mb-1.5">Contracted Lanes — click to add a line</p>
                    <div id="contractSuggestionsChips" class="flex flex-wrap gap-1.5"></div>
                </div>
            </div>

            {{-- Booking date --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
                <label class="text-xs font-medium text-zinc-400 uppercase">Booking Date</label>
                <input type="date" id="bookingDate" class="w-full md:w-64 border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
            </div>

            {{-- Cargo lines --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
                <div class="flex justify-between items-center mb-3">
                    <p class="font-semibold text-zinc-700 dark:text-zinc-200">Cargo Lines</p>
                    <button type="button" id="addLineBtn" class="text-xs font-medium text-blue-600 hover:underline">+ Add Line</button>
                </div>
                <div id="cargoLinesContainer" class="space-y-4"></div>
            </div>
        </div>

        {{-- QUOTE SUMMARY --}}
        <div class="space-y-4">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-5 sticky top-4">
                <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Price Preview</p>
                <div id="quoteBody" class="text-sm text-zinc-500">
                    Fill in a client and at least one cargo line to see pricing.
                </div>
                <button type="button" id="saveDraftBtn"
                    class="mt-5 w-full px-4 py-2.5 text-sm font-medium rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                    Save as Draft
                </button>
                <button type="button" id="confirmBookingBtn"
                    class="hidden mt-2 w-full px-4 py-2.5 text-sm font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                    Confirm Booking
                </button>
                <p id="confirmBookingHint" class="hidden text-xs text-zinc-400 mt-2">
                    Locks in pricing and generates the Invoice + Bill of Lading. Save any changes first.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        let isEdit = false;
        let bookingUuid = null;
        let ports = [];
        let deliveryTypes = [];
        let containerVariants = [];
        let quoteDebounce = null;
        let lineCounter = 0;

        function optionsHtml(items, valueKey, labelFn, placeholder) {
            return `<option value="">${placeholder}</option>` +
                items.map(i => `<option value="${i[valueKey]}">${labelFn(i)}</option>`).join('');
        }

        // -----------------------------------------------------------------
        // Reference data
        // -----------------------------------------------------------------
        async function loadReferenceData() {
            const [portsRes, deliveryRes, variantsRes] = await Promise.all([
                apiCall({ mode: 'GET', url: '/api/ports?per_page=500' }),
                apiCall({ mode: 'GET', url: '/api/deliveryTypes?per_page=100' }),
                apiCall({ mode: 'GET', url: '/api/containers/variants' }),
            ]);

            ports = portsRes?.success ? (portsRes.data?.data ?? []) : [];
            deliveryTypes = deliveryRes?.success ? (deliveryRes.data?.data ?? []) : [];
            containerVariants = variantsRes?.success ? (variantsRes.data ?? []) : [];
        }

        function portOptionsHtml() {
            return optionsHtml(ports, 'port_id', (p) => `${p.name} (${p.code})`, 'Select port');
        }

        async function loadAreasForElement(selectEl, portId) {
            if (!portId) {
                selectEl.innerHTML = '<option value="">Select port first</option>';
                return;
            }

            const response = await apiCall({ mode: 'GET', url: `/api/serviceableAreas?port_id=${portId}&per_page=200` });
            const areas = response?.success ? (response.data?.data ?? []) : [];
            selectEl.innerHTML = optionsHtml(areas, 'area_id', (a) => a.area_name, 'Select area');
        }

        document.getElementById('bookingDate').addEventListener('change', scheduleQuote);

        // -----------------------------------------------------------------
        // Client picker
        // -----------------------------------------------------------------
        let clientSearchDebounce = null;

        document.getElementById('clientSearchInput').addEventListener('input', function() {
            clearTimeout(clientSearchDebounce);
            const term = this.value.trim();
            const resultsEl = document.getElementById('clientSearchResults');

            if (term.length < 2) {
                resultsEl.classList.add('hidden');
                return;
            }

            clientSearchDebounce = setTimeout(async () => {
                const response = await apiCall({ mode: 'GET', url: `/api/clientMasters?search=${encodeURIComponent(term)}&per_page=10` });
                const clients = response?.success ? (response.data?.data ?? []) : [];

                if (!clients.length) {
                    resultsEl.innerHTML = '<div class="px-3 py-2 text-xs text-zinc-400">No clients found.</div>';
                } else {
                    resultsEl.innerHTML = clients.map(c => `
                        <div class="client-result px-3 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 cursor-pointer" data-id="${c.id}" data-uuid="${c.uuid}" data-name="${c.company_name}">
                            <div class="font-medium">${c.company_name}</div>
                            <div class="text-xs text-zinc-400">${c.customer_code ?? '-'}</div>
                        </div>
                    `).join('');
                }

                resultsEl.classList.remove('hidden');
            }, 350);
        });

        document.getElementById('clientSearchResults').addEventListener('click', function(e) {
            const item = e.target.closest('.client-result');
            if (!item) return;
            selectClient(item.dataset.id, item.dataset.name, item.dataset.uuid);
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('clientSearchWrap').contains(e.target)) {
                document.getElementById('clientSearchResults').classList.add('hidden');
            }
        });

        function selectClient(id, name, uuid) {
            document.getElementById('clientId').value = id;
            document.getElementById('clientSelectedName').textContent = name;
            document.getElementById('clientSelectedDisplay').classList.remove('hidden');
            document.getElementById('clientSearchWrap').classList.add('hidden');
            document.getElementById('clientSearchResults').classList.add('hidden');
            document.getElementById('clientSearchInput').value = '';
            scheduleQuote();
            loadContractSuggestions(uuid);
        }

        document.getElementById('clientChangeBtn').addEventListener('click', function() {
            document.getElementById('clientId').value = '';
            document.getElementById('clientSelectedDisplay').classList.add('hidden');
            document.getElementById('clientSearchWrap').classList.remove('hidden');
            hideContractSuggestions();
        });

        // -----------------------------------------------------------------
        // Contract-aware quick-add suggestions
        // -----------------------------------------------------------------
        function hideContractSuggestions() {
            document.getElementById('contractSuggestions').classList.add('hidden');
            document.getElementById('contractSuggestionsChips').innerHTML = '';
        }

        async function loadContractSuggestions(clientUuid) {
            hideContractSuggestions();
            if (!clientUuid) return;

            const response = await apiCall({ mode: 'GET', url: `/api/clientMasters/${clientUuid}/contracts` });
            const contracts = response?.success ? (response.data ?? []) : [];

            // status 2 = ClientContract::STATUS_ACTIVE
            const activeRates = contracts
                .filter(c => Number(c.status) === 2)
                .flatMap(c => c.rates ?? []);

            if (!activeRates.length) return;

            const chipsEl = document.getElementById('contractSuggestionsChips');
            chipsEl.innerHTML = activeRates.map((rate, i) => `
                <button type="button" class="contract-chip text-xs px-2.5 py-1.5 rounded-full border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/40"
                    data-index="${i}"
                    data-origin-port-id="${rate.origin_port_id}"
                    data-destination-port-id="${rate.destination_port_id}"
                    data-container-variant-id="${rate.container_variant_id}"
                    data-min-van-qty="${rate.min_van_qty ?? ''}">
                    ${rate.origin_port?.code ?? '?'} &rarr; ${rate.destination_port?.code ?? '?'}
                    &middot; ${rate.container?.name ?? '-'}/${rate.container_class?.class ?? '-'}/${rate.container_size?.size ?? '-'}
                    &middot; &#8369;${money(rate.final_rate)}
                    ${rate.min_van_qty ? `&middot; min ${rate.min_van_qty} for discount` : ''}
                </button>
            `).join('');

            document.getElementById('contractSuggestions').classList.remove('hidden');
        }

        document.getElementById('contractSuggestionsChips').addEventListener('click', function(e) {
            const chip = e.target.closest('.contract-chip');
            if (!chip) return;

            // Adds a whole new line pre-set to this contracted lane +
            // container - route/mode live per line now, not on a shared
            // header, so a chip can't just "set the form's route" anymore.
            const card = addLine();

            const originSelect = card.querySelector('.line-origin-port');
            originSelect.value = chip.dataset.originPortId;
            originSelect.dispatchEvent(new Event('change'));

            const destinationSelect = card.querySelector('.line-destination-port');
            destinationSelect.value = chip.dataset.destinationPortId;
            destinationSelect.dispatchEvent(new Event('change'));

            const variantSelect = card.querySelector('[data-field="container_variant_id"]');
            variantSelect.value = chip.dataset.containerVariantId;
            variantSelect.dispatchEvent(new Event('change'));

            // Preset quantity to the contract's minimum so the discount
            // actually applies instead of silently falling back to the
            // standard tariff - the user can still lower it, they'll just
            // see the price change accordingly.
            const minVanQty = Number(chip.dataset.minVanQty || 0);
            if (minVanQty > 1) {
                const quantityInput = card.querySelector('[data-field="quantity"]');
                quantityInput.value = minVanQty;
                quantityInput.dispatchEvent(new Event('change'));
            }
        });

        // -----------------------------------------------------------------
        // Cargo line cards
        // -----------------------------------------------------------------
        function variantLabel(v) {
            return `${v.container?.name ?? '-'} / ${v.container_class?.class ?? '-'} / ${v.container_size?.size ?? '-'}`;
        }

        function lineCardHtml(index) {
            const variantOptions = optionsHtml(containerVariants, 'id', variantLabel, 'Select container');
            const portOptions = portOptionsHtml();

            return `
            <div class="cargo-line-card border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 space-y-3" data-line-index="${index}">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">Cargo Line</p>
                    <button type="button" class="remove-line text-red-500 text-xs font-medium">✕ Remove</button>
                </div>

                <div class="border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <label class="text-[11px] text-zinc-400 uppercase block mb-1.5">Route &amp; Delivery for this cargo</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Origin Port</label>
                            <select data-field="origin_port_id" class="line-origin-port w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                ${portOptions}
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Destination Port</label>
                            <select data-field="destination_port_id" class="line-destination-port w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                ${portOptions}
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Origin Area</label>
                            <select data-field="origin_area_id" class="line-origin-area w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                <option value="">Select origin port first</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Destination Area</label>
                            <select data-field="destination_area_id" class="line-destination-area w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                <option value="">Select destination port first</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Origin Mode</label>
                            <select data-field="origin_mode" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                <option value="door">Door</option>
                                <option value="pier">Pier</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Destination Mode</label>
                            <select data-field="destination_mode" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                <option value="door">Door</option>
                                <option value="pier">Pier</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="md:col-span-2">
                        <label class="text-[11px] text-zinc-400 uppercase">Container</label>
                        <select data-field="container_variant_id" class="line-variant w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            ${variantOptions}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                        <input type="number" min="1" value="1" data-field="quantity" class="line-quantity w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Description</label>
                        <input type="text" data-field="description" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Weight (kg)</label>
                        <input type="number" step="0.01" min="0" data-field="weight_kg" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Volume (m&sup3;)</label>
                        <input type="number" step="0.01" min="0" data-field="volume_cbm" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div class="flex items-center gap-4 md:col-span-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" data-field="is_hazardous"> Hazardous
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" data-field="is_fragile"> Fragile
                        </label>
                    </div>
                </div>

                <div class="border-t border-zinc-100 dark:border-zinc-800 pt-3">
                    <label class="text-[11px] text-zinc-400 uppercase block mb-1.5">
                        Transaction Details
                        <span class="normal-case text-zinc-300">— fill in to move this line from Tentative to Live on the Cargo Build-Up board</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Name</label>
                            <input type="text" data-field="consignee_name" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Contact Person</label>
                            <input type="text" data-field="consignee_contact_person" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Address</label>
                            <input type="text" data-field="consignee_address" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Contact Number</label>
                            <input type="text" data-field="consignee_contact_number" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Cargo Type / Content</label>
                            <input type="text" data-field="cargo_type" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] text-zinc-400 uppercase">Other Cargo Details</label>
                            <textarea data-field="other_cargo_details" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900"></textarea>
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Booking Declared Value</label>
                            <input type="number" step="0.01" min="0" data-field="declared_value" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Delivery Date</label>
                            <input type="date" data-field="delivery_date" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] text-zinc-400 uppercase">Notes for Delivery Date</label>
                            <input type="text" data-field="delivery_date_notes" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">First Delivery Date</label>
                            <input type="date" data-field="first_delivery_date" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div>
                            <label class="text-[11px] text-zinc-400 uppercase">Last Delivery Date</label>
                            <input type="date" data-field="last_delivery_date" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                    </div>
                </div>

                <div class="border-t border-zinc-100 dark:border-zinc-800 pt-3">
                    <label class="text-[11px] text-zinc-400 uppercase block mb-1.5">Container Assignment</label>
                    <div class="flex gap-4 text-sm mb-2">
                        <label class="flex items-center gap-1.5">
                            <input type="radio" name="assign-mode-${index}" class="assign-mode" value="auto" checked> Auto-assign
                        </label>
                        <label class="flex items-center gap-1.5">
                            <input type="radio" name="assign-mode-${index}" class="assign-mode" value="manual"> Choose specific container(s)
                        </label>
                    </div>
                    <div class="manual-containers hidden text-xs space-y-1 max-h-40 overflow-y-auto border border-zinc-100 dark:border-zinc-800 rounded-lg p-2"></div>
                </div>
            </div>`;
        }

        function addLine() {
            const index = lineCounter++;
            document.getElementById('cargoLinesContainer').insertAdjacentHTML('beforeend', lineCardHtml(index));
            const card = document.querySelector(`.cargo-line-card[data-line-index="${index}"]`);
            wireLineCard(card);
            return card;
        }

        function wireLineCard(card) {
            card.querySelector('.remove-line').addEventListener('click', () => {
                card.remove();
                scheduleQuote();
            });

            card.querySelectorAll('input, select').forEach((el) => {
                el.addEventListener('change', scheduleQuote);
            });

            card.querySelector('.line-variant').addEventListener('change', () => refreshManualContainers(card));
            card.querySelector('.line-quantity').addEventListener('change', () => refreshManualContainers(card));

            card.querySelector('.line-origin-port').addEventListener('change', function() {
                loadAreasForElement(card.querySelector('.line-origin-area'), this.value);
                refreshManualContainers(card);
            });

            card.querySelector('.line-destination-port').addEventListener('change', function() {
                loadAreasForElement(card.querySelector('.line-destination-area'), this.value);
            });

            card.querySelectorAll('.assign-mode').forEach((radio) => {
                radio.addEventListener('change', () => {
                    const manualBox = card.querySelector('.manual-containers');
                    const isManual = card.querySelector('.assign-mode:checked').value === 'manual';
                    manualBox.classList.toggle('hidden', !isManual);
                    if (isManual) refreshManualContainers(card);
                    scheduleQuote();
                });
            });
        }

        async function refreshManualContainers(card) {
            const isManual = card.querySelector('.assign-mode:checked')?.value === 'manual';
            if (!isManual) return;

            const variantId = card.querySelector('.line-variant').value;
            const quantity = Number(card.querySelector('.line-quantity').value || 1);
            const originPortId = card.querySelector('.line-origin-port').value;
            const box = card.querySelector('.manual-containers');

            if (!variantId || !originPortId) {
                box.innerHTML = '<p class="text-zinc-400">Select the container type and this line\'s origin port first.</p>';
                return;
            }

            const response = await apiCall({
                mode: 'GET',
                url: `/api/container-assets/available?container_variant_id=${variantId}&origin_port_id=${originPortId}`,
            });
            const assets = response?.success ? (response.data ?? []) : [];

            if (!assets.length) {
                box.innerHTML = '<p class="text-zinc-400">No available containers of this type right now.</p>';
                return;
            }

            box.innerHTML = assets.map(a => `
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="manual-asset-checkbox" value="${a.id}">
                    ${a.container_no} — ${a.current_port?.name ?? 'Location unknown'}
                </label>
            `).join('') + `<p class="text-zinc-400 mt-1">Pick exactly ${quantity} container(s).</p>`;

            box.querySelectorAll('.manual-asset-checkbox').forEach((cb) => {
                cb.addEventListener('change', () => {
                    const checked = box.querySelectorAll('.manual-asset-checkbox:checked');
                    if (checked.length > quantity) cb.checked = false;
                    scheduleQuote();
                });
            });
        }

        document.getElementById('addLineBtn').addEventListener('click', addLine);

        // -----------------------------------------------------------------
        // Payload + quote + save
        // -----------------------------------------------------------------
        function collectLines() {
            return Array.from(document.querySelectorAll('.cargo-line-card')).map((card) => {
                const get = (field) => card.querySelector(`[data-field="${field}"]`);
                const isManual = card.querySelector('.assign-mode:checked')?.value === 'manual';

                const line = {
                    origin_port_id: Number(get('origin_port_id').value) || null,
                    destination_port_id: Number(get('destination_port_id').value) || null,
                    origin_area_id: Number(get('origin_area_id').value) || null,
                    destination_area_id: Number(get('destination_area_id').value) || null,
                    origin_mode: get('origin_mode').value,
                    destination_mode: get('destination_mode').value,
                    container_variant_id: Number(get('container_variant_id').value) || null,
                    quantity: Number(get('quantity').value) || 1,
                    description: get('description').value || null,
                    weight_kg: get('weight_kg').value || null,
                    volume_cbm: get('volume_cbm').value || null,
                    is_hazardous: get('is_hazardous').checked,
                    is_fragile: get('is_fragile').checked,
                    consignee_name: get('consignee_name').value || null,
                    consignee_address: get('consignee_address').value || null,
                    consignee_contact_person: get('consignee_contact_person').value || null,
                    consignee_contact_number: get('consignee_contact_number').value || null,
                    cargo_type: get('cargo_type').value || null,
                    other_cargo_details: get('other_cargo_details').value || null,
                    declared_value: get('declared_value').value || null,
                    delivery_date: get('delivery_date').value || null,
                    delivery_date_notes: get('delivery_date_notes').value || null,
                    first_delivery_date: get('first_delivery_date').value || null,
                    last_delivery_date: get('last_delivery_date').value || null,
                    auto_assign: !isManual,
                };

                if (isManual) {
                    line.container_asset_ids = Array.from(card.querySelectorAll('.manual-asset-checkbox:checked')).map(cb => Number(cb.value));
                }

                return line;
            }).filter(l => l.container_variant_id && l.origin_port_id && l.destination_port_id);
        }

        function collectPayload() {
            return {
                client_id: document.getElementById('clientId').value || null,
                booking_date: document.getElementById('bookingDate').value || null,
                lines: collectLines(),
            };
        }

        function payloadIsQuotable(payload) {
            return payload.client_id && payload.lines.length > 0 && payload.lines.every(
                l => l.origin_area_id && l.destination_area_id
            );
        }

        function scheduleQuote() {
            clearTimeout(quoteDebounce);
            quoteDebounce = setTimeout(refreshQuote, 400);
        }

        function money(v) {
            return Number(v ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        async function refreshQuote() {
            const payload = collectPayload();
            const quoteBody = document.getElementById('quoteBody');

            if (!payloadIsQuotable(payload)) {
                quoteBody.innerHTML = '<p class="text-zinc-500">Fill in a client and at least one complete cargo line to see pricing.</p>';
                return;
            }

            const response = await apiCall({ mode: 'POST', isJson: true, payload, url: '/api/bookings/quote' });

            if (!response.success) {
                quoteBody.innerHTML = `<p class="text-red-500">${response.message ?? 'Unable to price this booking yet.'}</p>`;
                return;
            }

            const b = response.data;
            const portById = Object.fromEntries(ports.map(p => [String(p.port_id), p]));
            const lineRows = (b.lines ?? []).map(l => {
                const origin = portById[String(l.origin_port_id)]?.code ?? '?';
                const destination = portById[String(l.destination_port_id)]?.code ?? '?';

                return `
                <div class="flex justify-between text-xs py-1 border-b border-zinc-50 dark:border-zinc-800">
                    <span class="text-zinc-500">${origin} &rarr; ${destination} &middot; ${l.quantity} &times; ${money(l.frt_after_discount)}</span>
                    <span class="font-medium">${money(l.line_total)}</span>
                </div>
            `;
            }).join('');

            quoteBody.innerHTML = `
                <div class="space-y-1 mb-3">${lineRows}</div>
                <div class="flex justify-between text-xs py-1"><span class="text-zinc-500">Lines Total</span><span>${money(b.lines_total)}</span></div>
                <div class="flex justify-between text-xs py-1"><span class="text-zinc-500">Port Charges</span><span>${money(b.port_charges?.total)}</span></div>
                <div class="flex justify-between text-xs py-1"><span class="text-zinc-500">Handling</span><span>${money(b.handling?.total)}</span></div>
                <div class="flex justify-between text-xs py-1"><span class="text-zinc-500">Trucking</span><span>${money(b.trucking?.total)}</span></div>
                <div class="flex justify-between text-xs py-1"><span class="text-zinc-500">VAT (${b.vat_rate_percent}%)</span><span>${money(b.vat_amount)}</span></div>
                <div class="flex justify-between text-sm font-bold pt-2 mt-1 border-t border-zinc-200 dark:border-zinc-700"><span>Grand Total</span><span>${money(b.grand_total)}</span></div>
            `;
        }

        document.getElementById('saveDraftBtn').addEventListener('click', async function() {
            const payload = collectPayload();

            if (!payload.client_id) {
                showMessage({ status: 'error', title: 'Select a client first' });
                return;
            }

            if (!payload.lines.length) {
                showMessage({ status: 'error', title: 'Add at least one cargo line' });
                return;
            }

            const response = await apiCall({
                mode: isEdit ? 'PUT' : 'POST',
                isJson: true,
                payload,
                url: isEdit ? `/api/bookings/${bookingUuid}` : '/api/bookings',
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save booking',
                    message: response.invalid_fields ? Object.values(response.invalid_fields).flat().join(' ') : (response.message ?? ''),
                });
                return;
            }

            showMessage({ status: 'success', title: isEdit ? 'Booking updated' : 'Booking saved as Draft' });
            goBackToList();
        });

        document.getElementById('confirmBookingBtn').addEventListener('click', async function() {
            const response = await apiCall({
                mode: 'POST',
                url: `/api/bookings/${bookingUuid}/confirm`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to confirm booking',
                    message: response.message ?? '',
                });
                return;
            }

            showMessage({ status: 'success', title: 'Booking confirmed' });
            goBackToList();
        });

        function goBackToList() {
            loadPage({ title: 'Bookings', link: '/page_booking' });
        }

        document.getElementById('btnBackToList').addEventListener('click', goBackToList);

        // -----------------------------------------------------------------
        // Edit mode - prefill from an existing Draft booking
        // -----------------------------------------------------------------
        async function loadForEdit() {
            const response = await apiCall({ mode: 'GET', url: `/api/bookings/${bookingUuid}` });
            if (!response.success) return;

            const b = response.data;
            document.getElementById('formPageTitle').textContent = `Edit Booking — ${b.code}`;
            selectClient(b.client_id, b.client?.company_name ?? 'Client', b.client?.uuid);
            document.getElementById('bookingDate').value = b.booking_date ?? '';

            for (const line of (b.lines ?? [])) {
                const card = addLine();

                const originSelect = card.querySelector('.line-origin-port');
                originSelect.value = line.origin_port_id ?? '';
                await loadAreasForElement(card.querySelector('.line-origin-area'), line.origin_port_id);
                card.querySelector('[data-field="origin_area_id"]').value = line.origin_area_id ?? '';

                const destinationSelect = card.querySelector('.line-destination-port');
                destinationSelect.value = line.destination_port_id ?? '';
                await loadAreasForElement(card.querySelector('.line-destination-area'), line.destination_port_id);
                card.querySelector('[data-field="destination_area_id"]').value = line.destination_area_id ?? '';

                if (line.delivery_type) {
                    card.querySelector('[data-field="origin_mode"]').value = line.delivery_type.includes_origin_trucking ? 'door' : 'pier';
                    card.querySelector('[data-field="destination_mode"]').value = line.delivery_type.includes_destination_trucking ? 'door' : 'pier';
                }

                card.querySelector('[data-field="container_variant_id"]').value = line.container_variant_id;
                card.querySelector('[data-field="quantity"]').value = line.quantity;
                card.querySelector('[data-field="description"]').value = line.description ?? '';
                card.querySelector('[data-field="weight_kg"]').value = line.weight_kg ?? '';
                card.querySelector('[data-field="volume_cbm"]').value = line.volume_cbm ?? '';
                card.querySelector('[data-field="is_hazardous"]').checked = !!line.is_hazardous;
                card.querySelector('[data-field="is_fragile"]').checked = !!line.is_fragile;
                card.querySelector('[data-field="consignee_name"]').value = line.consignee_name ?? '';
                card.querySelector('[data-field="consignee_address"]').value = line.consignee_address ?? '';
                card.querySelector('[data-field="consignee_contact_person"]').value = line.consignee_contact_person ?? '';
                card.querySelector('[data-field="consignee_contact_number"]').value = line.consignee_contact_number ?? '';
                card.querySelector('[data-field="cargo_type"]').value = line.cargo_type ?? '';
                card.querySelector('[data-field="other_cargo_details"]').value = line.other_cargo_details ?? '';
                card.querySelector('[data-field="declared_value"]').value = line.declared_value ?? '';
                card.querySelector('[data-field="delivery_date"]').value = line.delivery_date ?? '';
                card.querySelector('[data-field="delivery_date_notes"]').value = line.delivery_date_notes ?? '';
                card.querySelector('[data-field="first_delivery_date"]').value = line.first_delivery_date ?? '';
                card.querySelector('[data-field="last_delivery_date"]').value = line.last_delivery_date ?? '';
                // Container assignment defaults back to Auto-assign on edit -
                // update() re-reserves from scratch regardless, so this keeps
                // the form simple rather than reconstructing prior manual picks.
            }

            scheduleQuote();
        }

        // -----------------------------------------------------------------
        // Init
        // -----------------------------------------------------------------
        async function init() {
            bookingUuid = window.bookingFormUuid || null;
            isEdit = Boolean(bookingUuid);
            window.bookingFormUuid = null;

            document.getElementById('bookingDate').value = new Date().toISOString().slice(0, 10);

            await loadReferenceData();

            if (isEdit) {
                await loadForEdit();
                document.getElementById('confirmBookingBtn').classList.remove('hidden');
                document.getElementById('confirmBookingHint').classList.remove('hidden');
            } else {
                addLine();
            }
        }

        init();
    })();
</script>
