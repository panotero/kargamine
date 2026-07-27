<div class="container mx-auto p-5 max-w-6xl">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" id="formPageTitle">New Booking</h1>
            <p class="text-zinc-500 text-sm">A booking is saved as Draft until you confirm it.</p>
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
            </div>

            {{-- Route & delivery --}}
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
                <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Route &amp; Delivery</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-zinc-400 uppercase">Origin Port</label>
                        <select id="originPort" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                            <option value="">Select port</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-zinc-400 uppercase">Destination Port</label>
                        <select id="destinationPort" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                            <option value="">Select port</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-zinc-400 uppercase">Origin Area</label>
                        <select id="originArea" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                            <option value="">Select origin port first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-zinc-400 uppercase">Destination Area</label>
                        <select id="destinationArea" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                            <option value="">Select destination port first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-zinc-400 uppercase">Delivery Type</label>
                        <select id="deliveryType" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                            <option value="">Select delivery type</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-zinc-400 uppercase">Booking Date</label>
                        <input type="date" id="bookingDate" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 dark:text-zinc-900">
                    </div>
                </div>
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
                    Fill in the route and at least one cargo line to see pricing.
                </div>
                <button type="button" id="saveDraftBtn"
                    class="mt-5 w-full px-4 py-2.5 text-sm font-medium rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                    Save as Draft
                </button>
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

            const portOptions = optionsHtml(ports, 'port_id', (p) => `${p.name} (${p.code})`, 'Select port');
            document.getElementById('originPort').innerHTML = portOptions;
            document.getElementById('destinationPort').innerHTML = portOptions;

            document.getElementById('deliveryType').innerHTML = optionsHtml(
                deliveryTypes, 'delivery_type_id', (d) => d.name, 'Select delivery type'
            );
        }

        async function loadAreasFor(selectId, portId) {
            const select = document.getElementById(selectId);

            if (!portId) {
                select.innerHTML = '<option value="">Select port first</option>';
                return;
            }

            const response = await apiCall({ mode: 'GET', url: `/api/serviceableAreas?port_id=${portId}&per_page=200` });
            const areas = response?.success ? (response.data?.data ?? []) : [];
            select.innerHTML = optionsHtml(areas, 'area_id', (a) => a.area_name, 'Select area');
        }

        document.getElementById('originPort').addEventListener('change', function() {
            loadAreasFor('originArea', this.value);
            refreshManualContainersForAllLines();
            scheduleQuote();
        });

        document.getElementById('destinationPort').addEventListener('change', function() {
            loadAreasFor('destinationArea', this.value);
            scheduleQuote();
        });

        ['originArea', 'destinationArea', 'deliveryType', 'bookingDate'].forEach((id) => {
            document.getElementById(id).addEventListener('change', scheduleQuote);
        });

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
                        <div class="client-result px-3 py-2 text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 cursor-pointer" data-id="${c.id}" data-name="${c.company_name}">
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
            selectClient(item.dataset.id, item.dataset.name);
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('clientSearchWrap').contains(e.target)) {
                document.getElementById('clientSearchResults').classList.add('hidden');
            }
        });

        function selectClient(id, name) {
            document.getElementById('clientId').value = id;
            document.getElementById('clientSelectedName').textContent = name;
            document.getElementById('clientSelectedDisplay').classList.remove('hidden');
            document.getElementById('clientSearchWrap').classList.add('hidden');
            document.getElementById('clientSearchResults').classList.add('hidden');
            document.getElementById('clientSearchInput').value = '';
            scheduleQuote();
        }

        document.getElementById('clientChangeBtn').addEventListener('click', function() {
            document.getElementById('clientId').value = '';
            document.getElementById('clientSelectedDisplay').classList.add('hidden');
            document.getElementById('clientSearchWrap').classList.remove('hidden');
        });

        // -----------------------------------------------------------------
        // Cargo line cards
        // -----------------------------------------------------------------
        function variantLabel(v) {
            return `${v.container?.name ?? '-'} / ${v.container_class?.class ?? '-'} / ${v.container_size?.size ?? '-'}`;
        }

        function lineCardHtml(index) {
            const variantOptions = optionsHtml(containerVariants, 'id', variantLabel, 'Select container');

            return `
            <div class="cargo-line-card border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 space-y-3" data-line-index="${index}">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">Cargo Line</p>
                    <button type="button" class="remove-line text-red-500 text-xs font-medium">✕ Remove</button>
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

        function refreshManualContainersForAllLines() {
            document.querySelectorAll('.cargo-line-card').forEach(refreshManualContainers);
        }

        async function refreshManualContainers(card) {
            const isManual = card.querySelector('.assign-mode:checked')?.value === 'manual';
            if (!isManual) return;

            const variantId = card.querySelector('.line-variant').value;
            const quantity = Number(card.querySelector('.line-quantity').value || 1);
            const originPortId = document.getElementById('originPort').value;
            const box = card.querySelector('.manual-containers');

            if (!variantId || !originPortId) {
                box.innerHTML = '<p class="text-zinc-400">Select the container type and origin port first.</p>';
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
                    container_variant_id: Number(get('container_variant_id').value) || null,
                    quantity: Number(get('quantity').value) || 1,
                    description: get('description').value || null,
                    weight_kg: get('weight_kg').value || null,
                    volume_cbm: get('volume_cbm').value || null,
                    is_hazardous: get('is_hazardous').checked,
                    is_fragile: get('is_fragile').checked,
                    auto_assign: !isManual,
                };

                if (isManual) {
                    line.container_asset_ids = Array.from(card.querySelectorAll('.manual-asset-checkbox:checked')).map(cb => Number(cb.value));
                }

                return line;
            }).filter(l => l.container_variant_id);
        }

        function collectPayload() {
            return {
                client_id: document.getElementById('clientId').value || null,
                origin_port_id: document.getElementById('originPort').value || null,
                destination_port_id: document.getElementById('destinationPort').value || null,
                origin_area_id: document.getElementById('originArea').value || null,
                destination_area_id: document.getElementById('destinationArea').value || null,
                delivery_type_id: document.getElementById('deliveryType').value || null,
                booking_date: document.getElementById('bookingDate').value || null,
                lines: collectLines(),
            };
        }

        function payloadIsQuotable(payload) {
            return payload.client_id && payload.origin_port_id && payload.destination_port_id &&
                payload.origin_area_id && payload.destination_area_id && payload.delivery_type_id &&
                payload.lines.length > 0;
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
                quoteBody.innerHTML = '<p class="text-zinc-500">Fill in the route and at least one cargo line to see pricing.</p>';
                return;
            }

            const response = await apiCall({ mode: 'POST', isJson: true, payload, url: '/api/bookings/quote' });

            if (!response.success) {
                quoteBody.innerHTML = `<p class="text-red-500">${response.message ?? 'Unable to price this booking yet.'}</p>`;
                return;
            }

            const b = response.data;
            const lineRows = (b.lines ?? []).map(l => `
                <div class="flex justify-between text-xs py-1 border-b border-zinc-50 dark:border-zinc-800">
                    <span class="text-zinc-500">${l.quantity} &times; ${money(l.frt_after_discount)}</span>
                    <span class="font-medium">${money(l.line_total)}</span>
                </div>
            `).join('');

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
            selectClient(b.client_id, b.client?.company_name ?? 'Client');

            document.getElementById('originPort').value = b.lane?.origin_port_id ?? '';
            document.getElementById('destinationPort').value = b.lane?.destination_port_id ?? '';
            document.getElementById('deliveryType').value = b.delivery_type_id ?? '';
            document.getElementById('bookingDate').value = b.booking_date ?? '';

            await loadAreasFor('originArea', b.lane?.origin_port_id);
            await loadAreasFor('destinationArea', b.lane?.destination_port_id);
            document.getElementById('originArea').value = b.origin_area_id ?? '';
            document.getElementById('destinationArea').value = b.destination_area_id ?? '';

            (b.lines ?? []).forEach((line) => {
                const card = addLine();
                card.querySelector('[data-field="container_variant_id"]').value = line.container_variant_id;
                card.querySelector('[data-field="quantity"]').value = line.quantity;
                card.querySelector('[data-field="description"]').value = line.description ?? '';
                card.querySelector('[data-field="weight_kg"]').value = line.weight_kg ?? '';
                card.querySelector('[data-field="volume_cbm"]').value = line.volume_cbm ?? '';
                card.querySelector('[data-field="is_hazardous"]').checked = !!line.is_hazardous;
                card.querySelector('[data-field="is_fragile"]').checked = !!line.is_fragile;
                // Container assignment defaults back to Auto-assign on edit -
                // update() re-reserves from scratch regardless, so this keeps
                // the form simple rather than reconstructing prior manual picks.
            });

            scheduleQuote();
        }

        // -----------------------------------------------------------------
        // Init
        // -----------------------------------------------------------------
        async function init() {
            bookingUuid = window.bookingFormUuid || null;
            isEdit = Boolean(bookingUuid);
            window.bookingFormUuid = null;

            await loadReferenceData();

            if (isEdit) {
                await loadForEdit();
            } else {
                addLine();
            }
        }

        init();
    })();
</script>
