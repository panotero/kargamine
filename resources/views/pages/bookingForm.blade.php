@php $navLayout = Auth::user()->nav_layout ?? 'side'; @endphp
<div class="container mx-auto p-5 max-w-[1800px]">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" id="formPageTitle">New Booking</h1>
            <p class="text-zinc-500 text-sm">A booking is saved as Draft until you confirm it. Work through the four steps — each cargo line has its own route and delivery mode, so a single booking can ship to more than one destination.</p>
        </div>
        <button id="btnBackToList" type="button"
            class="border border-zinc-300 dark:border-zinc-700 px-4 py-2 rounded-lg text-sm font-medium text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ← Back to List
        </button>
    </div>

    {{-- When the user's nav layout preference is "top", the horizontal top
         menu bar already fills the top of the screen - mirror that choice
         here by running the stepper as a left side-rail instead of another
         horizontal bar (same treatment as crmLeadForm.blade.php). --}}
    <div class="{{ $navLayout === 'top' ? 'flex gap-6 items-start' : '' }}">

    @php
        $stepBtnClass = $navLayout === 'top'
            ? 'w-full px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center justify-start gap-2'
            : 'flex-1 px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center justify-center gap-2';
        $stepLabelClass = $navLayout === 'top' ? '' : 'hidden sm:inline';
    @endphp
    <div class="{{ $navLayout === 'top' ? 'flex flex-col gap-2 w-56 shrink-0' : 'flex items-center gap-2 mb-2' }}" id="bookingStepper">
        <button type="button" class="step-btn {{ $stepBtnClass }}" data-step="1">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">1</span>
            <span class="{{ $stepLabelClass }}">Client &amp; Date</span>
        </button>
        <button type="button" class="step-btn {{ $stepBtnClass }}" data-step="2">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">2</span>
            <span class="{{ $stepLabelClass }}">Cargo Lines</span>
        </button>
        <button type="button" class="step-btn {{ $stepBtnClass }}" data-step="3">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">3</span>
            <span class="{{ $stepLabelClass }}">Delivery Details</span>
        </button>
        <button type="button" class="step-btn {{ $stepBtnClass }}" data-step="4">
            <span class="step-indicator flex items-center justify-center w-5 h-5 rounded-full border border-current text-[11px] font-bold shrink-0">4</span>
            <span class="{{ $stepLabelClass }}">Review &amp; Confirm</span>
        </button>
    </div>

    <div class="{{ $navLayout === 'top' ? 'flex-1 min-w-0' : '' }}">
    <div id="stepHint" class="text-xs text-zinc-500 dark:text-zinc-400 mb-4 mt-2"></div>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">

        {{-- ===================== STEP 1: CLIENT & DATE ===================== --}}
        <div class="step-panel" data-panel="1">
            <div class="space-y-5">
                {{-- Client --}}
                <div>
                    <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Client <span class="text-red-500">*</span></p>
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
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest mb-1.5">Contracted Lanes — click to add a line</p>
                        <div id="contractSuggestionsChips" class="flex flex-wrap gap-1.5"></div>
                    </div>
                </div>

                {{-- Booking date --}}
                <div class="border-t border-zinc-100 dark:border-zinc-800 pt-5">
                    <label class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Booking Date</label>
                    <input type="date" id="bookingDate" class="block w-full md:w-64 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1.5">
                </div>
            </div>

            <div class="flex justify-between gap-2 pt-5 mt-5 border-t border-zinc-100 dark:border-zinc-800">
                <button type="button"
                    class="save-draft-btn border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    Save as Draft
                </button>
                <button type="button" class="step-next bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium" data-target="2">
                    Continue to Cargo Lines →
                </button>
            </div>
        </div>

        {{-- ===================== STEP 2: CARGO LINES ===================== --}}
        <div class="step-panel hidden" data-panel="2">
            <div class="flex justify-between items-center mb-2">
                <p class="font-semibold text-zinc-700 dark:text-zinc-200">Cargo Lines</p>
                <button type="button" id="addLineBtn" class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">+ Add Cargo Line</button>
            </div>
            <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-4">
                <span class="font-semibold">Tentative</span> means cargo type &amp; value aren't set yet — it won't show on the Cargo Build-Up board until it does.
                <span class="font-semibold">Live</span> means it's ready. Neither blocks saving a Draft.
            </p>
            <div id="cargoLinesContainer" class="space-y-4"></div>

            <div class="flex justify-between gap-2 pt-5 mt-5 border-t border-zinc-100 dark:border-zinc-800">
                <button type="button" class="step-prev border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800" data-target="1">← Back</button>
                <div class="flex gap-2">
                    <button type="button"
                        class="save-draft-btn border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        Save as Draft
                    </button>
                    <button type="button" class="step-next bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium" data-target="3">
                        Continue to Delivery →
                    </button>
                </div>
            </div>
        </div>

        {{-- ===================== STEP 3: DELIVERY DETAILS ===================== --}}
        <div class="step-panel hidden" data-panel="3">
            <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-1">Delivery Details</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-4">
                One block per cargo line. You can leave these blank and still Save as Draft — they're only required to Confirm the booking.
            </p>
            <div id="deliveryEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500 border border-dashed border-zinc-200 dark:border-zinc-700 rounded-xl p-6 text-center">
                Add cargo lines in Step 2 first — a delivery block appears here for each one.
            </div>
            <div id="deliveryBlocksContainer" class="space-y-4"></div>

            <div class="flex justify-between gap-2 pt-5 mt-5 border-t border-zinc-100 dark:border-zinc-800">
                <button type="button" class="step-prev border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800" data-target="2">← Back</button>
                <div class="flex gap-2">
                    <button type="button"
                        class="save-draft-btn border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        Save as Draft
                    </button>
                    <button type="button" class="step-next bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium" data-target="4">
                        Continue to Review →
                    </button>
                </div>
            </div>
        </div>

        {{-- ===================== STEP 4: REVIEW & CONFIRM ===================== --}}
        <div class="step-panel hidden" data-panel="4">
            <p class="font-semibold text-zinc-700 dark:text-zinc-200 mb-3">Price Preview</p>
            <div id="quoteBody" class="text-sm text-zinc-500 mb-4">
                Fill in a client and at least one cargo line to see pricing.
            </div>

            {{-- Client-side completeness check for Confirm (⚠ callout). Save as
                 Draft is never blocked by this. --}}
            <div id="confirmBlockers" class="hidden border-2 border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-950/20 rounded-xl p-4 mb-4">
                <p class="text-[11px] font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400 mb-2">⚠ Can't confirm yet</p>
                <div id="confirmBlockersBody" class="text-sm text-amber-800 dark:text-amber-200 space-y-1"></div>
            </div>

            <div class="flex justify-between gap-2 pt-5 mt-5 border-t border-zinc-100 dark:border-zinc-800">
                <button type="button" class="step-prev border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800" data-target="3">← Back</button>
                <div class="flex gap-2">
                    <button type="button"
                        class="save-draft-btn border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 px-5 py-2 rounded-lg text-sm font-medium hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        Save as Draft
                    </button>
                    <button type="button" id="confirmBookingBtn"
                        class="px-5 py-2 text-sm font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        Confirm Booking
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>

<script>
    (function() {
        let isEdit = false;
        let bookingUuid = null;
        let ports = [];
        let locations = [];
        let deliveryTypes = [];
        let containerVariants = [];
        let quoteDebounce = null;
        let lineCounter = 0;
        let containerRowCounter = 0;

        const el = (id) => document.getElementById(id);

        function optionsHtml(items, valueKey, labelFn, placeholder) {
            return `<option value="">${placeholder}</option>` +
                items.map(i => `<option value="${i[valueKey]}">${labelFn(i)}</option>`).join('');
        }

        // -----------------------------------------------------------------
        // Reference data
        // -----------------------------------------------------------------
        async function loadReferenceData() {
            const [portsRes, locationsRes, deliveryRes, variantsRes] = await Promise.all([
                apiCall({ mode: 'GET', url: '/api/ports?per_page=500' }),
                apiCall({ mode: 'GET', url: '/api/locations?per_page=200' }),
                apiCall({ mode: 'GET', url: '/api/deliveryTypes?per_page=100' }),
                apiCall({ mode: 'GET', url: '/api/containers/variants' }),
            ]);

            ports = portsRes?.success ? (portsRes.data?.data ?? []) : [];
            locations = locationsRes?.success ? (locationsRes.data?.data ?? []) : [];
            deliveryTypes = deliveryRes?.success ? (deliveryRes.data?.data ?? []) : [];
            containerVariants = variantsRes?.success ? (variantsRes.data ?? []) : [];
        }

        function portOptionsHtml() {
            return optionsHtml(ports, 'port_id', (p) => `${p.location?.name ?? '-'} - ${p.name}`, 'Select port');
        }

        function locationOptionsHtml() {
            return optionsHtml(locations, 'location_id', (l) => l.name, 'All Locations');
        }

        function portOptionsForLocation(locationId) {
            const filtered = locationId ?
                ports.filter((p) => String(p.location_id) === String(locationId)) :
                ports;
            return optionsHtml(filtered, 'port_id', (p) => p.name, 'Select port');
        }

        function refreshSearchable(elm) {
            elm?._searchableSelect?.refresh();
        }

        async function loadAreasForElement(selectEl, portId) {
            const port = ports.find((p) => String(p.port_id) === String(portId));

            if (!portId || !port) {
                selectEl.innerHTML = '<option value="">Select port first</option>';
                return;
            }

            const response = await apiCall({ mode: 'GET', url: `/api/serviceableAreas?location_id=${port.location_id}&per_page=200` });
            const areas = response?.success ? (response.data?.data ?? []) : [];
            selectEl.innerHTML = optionsHtml(areas, 'area_id', (a) => a.area_name, 'Select area');
        }

        el('bookingDate').addEventListener('change', scheduleQuote);

        // -----------------------------------------------------------------
        // Wizard stepper
        // -----------------------------------------------------------------
        let currentStep = 1;

        function updateStepperHighlight() {
            document.querySelectorAll('.step-btn').forEach((b) => {
                const active = Number(b.dataset.step) === currentStep;
                b.classList.toggle('border-orange-500', active);
                b.classList.toggle('text-orange-600', active);
                b.classList.toggle('border-zinc-200', !active);
                b.classList.toggle('dark:border-zinc-700', !active);
                b.classList.toggle('text-zinc-400', !active);
                b.classList.toggle('dark:text-zinc-500', !active);
            });
        }

        function showStep(step) {
            currentStep = step;
            document.querySelectorAll('.step-panel').forEach((p) => {
                p.classList.toggle('hidden', Number(p.dataset.panel) !== step);
            });
            updateStepperHighlight();
            if (step === 4) scheduleQuote();
        }

        document.querySelectorAll('.step-btn').forEach((btn) => {
            btn.addEventListener('click', () => showStep(Number(btn.dataset.step)));
        });
        document.querySelectorAll('.step-prev').forEach((btn) => {
            btn.addEventListener('click', () => showStep(Number(btn.dataset.target)));
        });
        // Step-forward Continue buttons. Step 1 requires a client; Step 2
        // requires at least one complete line; Step 3 never blocks.
        document.querySelectorAll('.step-next').forEach((btn) => {
            btn.addEventListener('click', () => {
                const target = Number(btn.dataset.target);
                if (target === 2 && !el('clientId').value) {
                    showMessage({ status: 'error', title: 'Select a client first' });
                    return;
                }
                if (target === 3 && !hasCompleteLine()) {
                    showMessage({ status: 'error', title: 'Add at least one cargo line with a route and container' });
                    return;
                }
                showStep(target);
            });
        });

        // -----------------------------------------------------------------
        // Client picker
        // -----------------------------------------------------------------
        let clientSearchDebounce = null;

        async function performClientSearch(term) {
            const resultsEl = el('clientSearchResults');

            const response = await apiCall({ mode: 'GET', url: `/api/clientMasters?search=${encodeURIComponent(term)}&per_page=20` });
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
        }

        el('clientSearchInput').addEventListener('focus', function() {
            clearTimeout(clientSearchDebounce);
            performClientSearch(this.value.trim());
        });

        el('clientSearchInput').addEventListener('input', function() {
            clearTimeout(clientSearchDebounce);
            const term = this.value.trim();
            clientSearchDebounce = setTimeout(() => performClientSearch(term), 350);
        });

        el('clientSearchResults').addEventListener('click', function(e) {
            const item = e.target.closest('.client-result');
            if (!item) return;
            selectClient(item.dataset.id, item.dataset.name, item.dataset.uuid);
        });

        document.addEventListener('click', function(e) {
            if (!el('clientSearchWrap').contains(e.target)) {
                el('clientSearchResults').classList.add('hidden');
            }
        });

        function selectClient(id, name, uuid) {
            el('clientId').value = id;
            el('clientSelectedName').textContent = name;
            el('clientSelectedDisplay').classList.remove('hidden');
            el('clientSearchWrap').classList.add('hidden');
            el('clientSearchResults').classList.add('hidden');
            el('clientSearchInput').value = '';
            scheduleQuote();
            loadContractSuggestions(uuid);
            refreshWizardState();
        }

        el('clientChangeBtn').addEventListener('click', function() {
            el('clientId').value = '';
            el('clientSelectedDisplay').classList.add('hidden');
            el('clientSearchWrap').classList.remove('hidden');
            hideContractSuggestions();
            refreshWizardState();
        });

        // -----------------------------------------------------------------
        // Completeness checks
        // -----------------------------------------------------------------
        const REQUIRED_ROUTE_FIELDS = [
            'origin_port_id', 'destination_port_id', 'origin_mode', 'destination_mode',
            'origin_area_id', 'destination_area_id',
        ];

        // Consignee/delivery fields that live in Step 3 and are enforced by the
        // backend confirm() endpoint (nullable at draft-save time).
        const DELIVERY_FIELD_LABELS = {
            consignee_name: 'Consignee Name',
            consignee_address: 'Consignee Address',
            consignee_contact_person: 'Consignee Contact Person',
            consignee_contact_number: 'Consignee Contact Number',
            delivery_date: 'Delivery Date',
            delivery_date_notes: 'Delivery Date Notes',
            first_delivery_date: 'First Delivery Date',
            last_delivery_date: 'Last Delivery Date',
        };

        function cargoCards() {
            return Array.from(el('cargoLinesContainer').querySelectorAll('.cargo-line-card'));
        }

        function lineNumberOf(card) {
            return cargoCards().indexOf(card) + 1;
        }

        function deliveryBlockFor(card) {
            return document.querySelector(`.delivery-block[data-line-index="${card.dataset.lineIndex}"]`);
        }

        // A line is "complete enough" for Step 2: full route + at least one
        // container row with a variant and a positive quantity.
        function routeCardComplete(card) {
            const routeOk = REQUIRED_ROUTE_FIELDS.every((field) => {
                const f = card.querySelector(`[data-field="${field}"]`);
                return f && String(f.value).trim() !== '';
            });
            if (!routeOk) return false;

            const rows = card.querySelectorAll('.container-row');
            if (!rows.length) return false;

            return Array.from(rows).every((row) => {
                const variantId = row.querySelector('[data-field="container_variant_id"]')?.value;
                const quantity = row.querySelector('[data-field="quantity"]')?.value;
                return !!variantId && Number(quantity) > 0;
            });
        }

        function hasCompleteLine() {
            return cargoCards().some(routeCardComplete);
        }

        function everyLineRouteComplete() {
            const cards = cargoCards();
            return cards.length > 0 && cards.every(routeCardComplete);
        }

        function routeText(card) {
            const lbl = (portSel, locSel) => {
                if (portSel && portSel.value) return portSel.options[portSel.selectedIndex].textContent;
                if (locSel && locSel.value) return locSel.options[locSel.selectedIndex].textContent;
                return '—';
            };
            const origin = lbl(card.querySelector('.line-origin-port'), card.querySelector('.line-origin-location'));
            const destination = lbl(card.querySelector('.line-destination-port'), card.querySelector('.line-destination-location'));
            return `${origin} → ${destination}`;
        }

        // Client-side mirror of the backend confirm() contract: which lines are
        // missing which of the 8 Step-3 fields. Only inspects lines that have a
        // complete route (those are the ones that will actually be saved).
        function getConfirmBlockers() {
            const out = [];
            cargoCards().forEach((card, i) => {
                if (!routeCardComplete(card)) return;
                const block = deliveryBlockFor(card);
                const missing = Object.entries(DELIVERY_FIELD_LABELS)
                    .filter(([field]) => !(block?.querySelector(`[data-field="${field}"]`)?.value.trim()))
                    .map(([, label]) => label);
                if (missing.length) {
                    out.push(`Line ${i + 1} (${routeText(card)}) is missing: ${missing.join(', ')}.`);
                }
            });
            return out;
        }

        // -----------------------------------------------------------------
        // Wizard state: step checkmarks, hint line, Confirm gating.
        // -----------------------------------------------------------------
        function refreshWizardState() {
            const hasClient = !!el('clientId').value;
            const cards = cargoCards();
            const routeComplete = everyLineRouteComplete();
            const blockers = getConfirmBlockers();
            const ready = hasClient && routeComplete && blockers.length === 0;

            // Step checkmarks
            setStepDone(1, hasClient);
            setStepDone(2, routeComplete);
            setStepDone(3, cards.length > 0 && blockers.length === 0 && routeComplete);

            // Reason Confirm is unavailable (reused for both stepHint and callout)
            let reason = '';
            if (!hasClient) reason = 'Select a client in Step 1 to begin.';
            else if (!routeComplete) reason = 'Give every cargo line a complete route and at least one container in Step 2.';
            else if (blockers.length) reason = 'Fill in the delivery details for every cargo line in Step 3 to unlock Confirm.';

            const hintEl = el('stepHint');
            hintEl.textContent = reason || 'All required fields are filled — you can Confirm this booking in the Review step.';
            hintEl.classList.toggle('text-amber-600', !ready);
            hintEl.classList.toggle('dark:text-amber-400', !ready);
            hintEl.classList.toggle('text-zinc-500', ready);
            hintEl.classList.toggle('dark:text-zinc-400', ready);

            // Step 4 confirm callout + button
            const calloutEl = el('confirmBlockers');
            const bodyEl = el('confirmBlockersBody');
            if (ready) {
                calloutEl.classList.add('hidden');
                bodyEl.innerHTML = '';
            } else {
                calloutEl.classList.remove('hidden');
                if (blockers.length && hasClient && routeComplete) {
                    bodyEl.innerHTML = blockers.map((b) => `<p>${b}</p>`).join('');
                } else {
                    bodyEl.innerHTML = `<p>${reason}</p>`;
                }
            }

            const confirmBtn = el('confirmBookingBtn');
            confirmBtn.disabled = !ready;
            confirmBtn.title = ready ? '' : reason;
        }

        function setStepDone(step, done) {
            const btn = document.querySelector(`.step-btn[data-step="${step}"] .step-indicator`);
            if (btn) btn.textContent = done ? '✓' : String(step);
        }

        // -----------------------------------------------------------------
        // Contract-aware quick-add suggestions
        // -----------------------------------------------------------------
        function hideContractSuggestions() {
            el('contractSuggestions').classList.add('hidden');
            el('contractSuggestionsChips').innerHTML = '';
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

            const chipsEl = el('contractSuggestionsChips');
            chipsEl.innerHTML = activeRates.map((rate, i) => `
                <button type="button" class="contract-chip text-xs px-2.5 py-1.5 rounded-full border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/40"
                    data-index="${i}"
                    data-origin-port-id="${rate.origin_port_id}"
                    data-destination-port-id="${rate.destination_port_id}"
                    data-container-variant-id="${rate.container_variant_id}"
                    data-min-van-qty="${rate.min_van_qty ?? ''}">
                    ${rate.origin_port ? (rate.origin_port.location?.name ?? '?') + ' - ' + rate.origin_port.name : '?'} &rarr; ${rate.destination_port ? (rate.destination_port.location?.name ?? '?') + ' - ' + rate.destination_port.name : '?'}
                    &middot; ${rate.container?.name ?? '-'}/${rate.container_class?.class ?? '-'}/${rate.container_size?.size ?? '-'}
                    &middot; &#8369;${money(rate.final_rate)}
                    ${rate.min_van_qty ? `&middot; min ${rate.min_van_qty} for discount` : ''}
                </button>
            `).join('');

            el('contractSuggestions').classList.remove('hidden');
        }

        // Reuses an existing cargo line when its route (origin+destination
        // port) already matches, instead of always adding a new one.
        function findMatchingCargoLine(originPortId, destinationPortId) {
            return cargoCards().find((card) => {
                const origin = card.querySelector('.line-origin-port')?.value;
                const destination = card.querySelector('.line-destination-port')?.value;
                return origin && destination && String(origin) === String(originPortId) && String(destination) ===
                    String(destinationPortId);
            });
        }

        function findMatchingContainerRow(card, variantId) {
            return Array.from(card.querySelectorAll('.container-row')).find(
                (row) => row.querySelector('[data-field="container_variant_id"]').value && String(row.querySelector(
                    '[data-field="container_variant_id"]').value) === String(variantId)
            );
        }

        function bumpRowQuantity(row, minVanQty) {
            const quantityInput = row.querySelector('[data-field="quantity"]');
            const increment = minVanQty > 1 ? minVanQty : 1;
            quantityInput.value = (parseInt(quantityInput.value, 10) || 0) + increment;
            quantityInput.dispatchEvent(new Event('change'));
        }

        // Preset quantity to the contract's minimum so the discount
        // actually applies instead of silently falling back to the
        // standard tariff - the user can still lower it, they'll just
        // see the price change accordingly.
        function presetRowQuantity(row, minVanQty) {
            if (minVanQty > 1) {
                const quantityInput = row.querySelector('[data-field="quantity"]');
                quantityInput.value = minVanQty;
                quantityInput.dispatchEvent(new Event('change'));
            }
        }

        function fillLineRoute(card, originPortId, destinationPortId) {
            const originSelect = card.querySelector('.line-origin-port');
            const originLocationSelect = card.querySelector('.line-origin-location');
            const originPort = ports.find((p) => String(p.port_id) === String(originPortId));
            if (originPort) {
                originLocationSelect.value = originPort.location_id ?? '';
                refreshSearchable(originLocationSelect);
                originSelect.innerHTML = portOptionsForLocation(originPort.location_id);
            }
            originSelect.disabled = false;
            originSelect.value = originPortId;
            refreshSearchable(originSelect);
            originSelect.dispatchEvent(new Event('change'));

            const destinationSelect = card.querySelector('.line-destination-port');
            const destinationLocationSelect = card.querySelector('.line-destination-location');
            const destinationPort = ports.find((p) => String(p.port_id) === String(destinationPortId));
            if (destinationPort) {
                destinationLocationSelect.value = destinationPort.location_id ?? '';
                refreshSearchable(destinationLocationSelect);
                destinationSelect.innerHTML = portOptionsForLocation(destinationPort.location_id);
            }
            destinationSelect.disabled = false;
            destinationSelect.value = destinationPortId;
            refreshSearchable(destinationSelect);
            destinationSelect.dispatchEvent(new Event('change'));
        }

        el('contractSuggestionsChips').addEventListener('click', function(e) {
            const chip = e.target.closest('.contract-chip');
            if (!chip) return;

            const originPortId = chip.dataset.originPortId;
            const destinationPortId = chip.dataset.destinationPortId;
            const variantId = chip.dataset.containerVariantId;
            const minVanQty = Number(chip.dataset.minVanQty || 0);

            // Same origin+destination as an existing cargo line - reuse it
            // instead of adding a whole new one.
            const existingCard = findMatchingCargoLine(originPortId, destinationPortId);

            if (existingCard) {
                const existingRow = findMatchingContainerRow(existingCard, variantId);
                if (existingRow) {
                    bumpRowQuantity(existingRow, minVanQty);
                } else {
                    const row = addContainerRow(existingCard);
                    setContainerRowVariant(row, variantId);
                    presetRowQuantity(row, minVanQty);
                }
                scheduleQuote();
                refreshWizardState();
                return;
            }

            const card = addLine();
            fillLineRoute(card, originPortId, destinationPortId);

            const row = card.querySelector('.container-row');
            setContainerRowVariant(row, variantId);
            presetRowQuantity(row, minVanQty);
            scheduleQuote();
            refreshWizardState();
        });

        // -----------------------------------------------------------------
        // Cargo line cards
        // -----------------------------------------------------------------
        function uniqueContainerTypeOptions() {
            const seen = new Set();
            return containerVariants
                .filter((v) => {
                    if (seen.has(v.container.id)) return false;
                    seen.add(v.container.id);
                    return true;
                })
                .map((v) => `<option value="${v.container.id}">${v.container.name}</option>`)
                .join('');
        }

        // pierOptions: [{ value: 'stuffing'|'van_out'|'stripping', label: '...' }, ...] -
        // origin only ever offers stuffing/van_out, destination only ever
        // offers stripping/van_out (enforced server-side too, see
        // BookingController::validatePayload()).
        function modeCardsHtml(side, doorLabel, doorHelp, pierLabel, pierHelp, pierOptions) {
            const base = 'mode-card border rounded-lg px-3 py-2 text-left flex flex-col gap-0.5 transition';
            const pierOptionsHtml = pierOptions.map((o) => `<option value="${o.value}">${o.label}</option>`).join('');
            return `
                <div class="mode-cards grid grid-cols-2 gap-2" data-mode-side="${side}">
                    <button type="button" class="${base}" data-mode="door">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Door <span class="text-[10px] font-normal text-zinc-400 dark:text-zinc-500">(${doorLabel})</span></span>
                        <span class="text-[11px] text-zinc-400 dark:text-zinc-500">${doorHelp}</span>
                    </button>
                    <button type="button" class="${base}" data-mode="pier">
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Pier <span class="text-[10px] font-normal text-zinc-400 dark:text-zinc-500">(${pierLabel})</span></span>
                        <span class="text-[11px] text-zinc-400 dark:text-zinc-500">${pierHelp}</span>
                    </button>
                </div>
                <select data-field="${side}_mode" class="hidden">
                    <option value="door">Door</option>
                    <option value="pier">Pier</option>
                </select>
                <div class="pier-handling-field hidden mt-2">
                    <label class="text-[11px] text-zinc-400 uppercase">Pier Handling</label>
                    <select data-field="${side}_pier_handling" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        <option value="">Select</option>${pierOptionsHtml}
                    </select>
                </div>`;
        }

        function lineCardHtml(index) {
            const portOptions = portOptionsHtml();

            return `
            <div class="cargo-line-card border border-zinc-200 dark:border-zinc-700 rounded-xl" data-line-index="${index}">
                <div class="flex items-center justify-between gap-2 p-4">
                    <button type="button" class="line-toggle flex items-center gap-2 min-w-0 flex-1 text-left">
                        <span class="line-chevron text-xs text-zinc-400 dark:text-zinc-500 transition-transform duration-200 rotate-180 shrink-0">▼</span>
                        <span class="line-title text-sm font-semibold text-zinc-600 dark:text-zinc-300 shrink-0">Cargo Line ${index + 1}</span>
                        <span class="line-summary hidden text-sm font-medium text-zinc-600 dark:text-zinc-300 truncate"></span>
                        <span class="line-badge shrink-0 text-[11px] font-semibold px-2 py-0.5 rounded-full"></span>
                    </button>
                    <button type="button" class="remove-line text-red-500 text-xs font-medium shrink-0">✕ Remove</button>
                </div>

                <div class="line-body px-4 pb-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- LEFT: route (flight-card) + delivery mode --}}
                        <div>
                            <label class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest block mb-2">Route &amp; Delivery Mode</label>
                            <div class="grid grid-cols-[1fr_auto_1fr] gap-3 items-start">
                                {{-- Origin column --}}
                                <div class="space-y-2">
                                    <p class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Origin</p>
                                    <select class="line-origin-location w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                        ${locationOptionsHtml()}
                                    </select>
                                    <select data-field="origin_port_id" disabled class="line-origin-port w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                                        ${portOptions}
                                    </select>
                                    <select data-field="origin_area_id" class="line-origin-area w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                        <option value="">Select origin port first</option>
                                    </select>
                                    ${modeCardsHtml('origin', 'We pick it up', 'we truck it from your address', 'You deliver it', 'you bring it to the port yourself', [
                                        { value: 'stuffing', label: 'Pier Stuffing' },
                                        { value: 'van_out', label: 'Pier Van Out' },
                                    ])}
                                </div>
                                {{-- Arrow --}}
                                <div class="shrink-0 w-6 flex justify-center pt-8 text-zinc-300 dark:text-zinc-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                </div>
                                {{-- Destination column --}}
                                <div class="space-y-2">
                                    <p class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-right">Destination</p>
                                    <select class="line-destination-location w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                        ${locationOptionsHtml()}
                                    </select>
                                    <select data-field="destination_port_id" disabled class="line-destination-port w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                                        ${portOptions}
                                    </select>
                                    <select data-field="destination_area_id" class="line-destination-area w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                        <option value="">Select destination port first</option>
                                    </select>
                                    ${modeCardsHtml('destination', 'We deliver it', 'we truck it to the receiver', 'They pick it up', 'receiver collects at the port', [
                                        { value: 'stripping', label: 'Pier Stripping' },
                                        { value: 'van_out', label: 'Pier Van Out' },
                                    ])}
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: one or more containers riding on this route --}}
                        <div class="lg:border-l lg:border-zinc-100 dark:lg:border-zinc-800 lg:pl-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Containers</label>
                                <button type="button" class="add-container-row text-xs font-medium text-blue-600 hover:underline">+ Add Container</button>
                            </div>
                            <div class="container-rows-container space-y-3 max-h-[520px] overflow-y-auto pr-1"></div>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        function containerRowHtml(index) {
            const typeOptions = uniqueContainerTypeOptions();

            return `
            <div class="container-row border border-zinc-200 dark:border-zinc-700 rounded-lg bg-zinc-50/50 dark:bg-zinc-800/30" data-row-index="${index}">
                <div class="flex items-center justify-between gap-2 p-3">
                    <button type="button" class="row-toggle flex items-center gap-2 min-w-0 flex-1 text-left">
                        <span class="row-chevron text-xs text-zinc-400 dark:text-zinc-500 transition-transform duration-200 rotate-180 shrink-0">▼</span>
                        <span class="row-title text-xs font-semibold text-zinc-500 dark:text-zinc-400 shrink-0">Container ${index + 1}</span>
                        <span class="row-summary hidden text-xs font-medium text-zinc-500 dark:text-zinc-400 truncate"></span>
                    </button>
                    <button type="button" class="remove-container-row text-red-500 text-xs font-medium shrink-0">✕ Remove</button>
                </div>
                <div class="row-body px-3 pb-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Container Type</label>
                        <select class="row-container-type w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            <option value="">Select</option>${typeOptions}
                        </select>
                    </div>
                    <div class="field-container-class hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Container Class</label>
                        <select class="row-container-class w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            <option value="">Select type first</option>
                        </select>
                    </div>
                    <div class="field-container-size hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Container Size</label>
                        <select class="row-container-size w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            <option value="">Select class first</option>
                        </select>
                    </div>
                    <div class="field-container-temperature hidden">
                        <label class="text-[11px] text-zinc-400 uppercase">Minimum Temperature (&deg;C)</label>
                        <input type="number" step="0.1" data-field="minimum_temperature" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                        <input type="number" min="1" value="1" data-field="quantity" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Declared Value</label>
                        <input type="text" inputmode="decimal" data-field="declared_value" class="currency-input w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Cargo Type / Content</label>
                        <input type="text" data-field="cargo_type" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                    </div>
                    <div class="sm:col-span-2">
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
                    <div class="sm:col-span-2">
                        <label class="text-[11px] text-zinc-400 uppercase">Other Cargo Details</label>
                        <textarea data-field="other_cargo_details" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" data-field="is_fragile"> Fragile
                        </label>
                    </div>
                    <div class="sm:col-span-2 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                        <label class="flex items-center gap-2 text-sm font-medium text-red-600 dark:text-red-400">
                            <input type="checkbox" data-field="is_hazardous" class="row-hazardous-checkbox"> Hazardous / Dangerous Goods
                        </label>
                        <div class="hazmat-upload hidden mt-2">
                            <label class="text-[11px] text-zinc-400 uppercase">Dangerous Goods Document (MSDS, declaration, etc.)</label>
                            <input type="file" class="row-hazmat-file w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900" accept=".pdf,.jpg,.jpeg,.png,.webp">
                            <p class="hazmat-upload-status text-xs text-zinc-400 mt-1">No file uploaded.</p>
                            <input type="hidden" data-field="hazardous_document_path">
                        </div>
                    </div>
                </div>
                <input type="hidden" data-field="container_variant_id">
                </div>
            </div>`;
        }

        function deliveryBlockHtml(index) {
            return `
            <div class="delivery-block border border-zinc-200 dark:border-zinc-700 rounded-xl" data-line-index="${index}">
                <div class="flex items-center justify-between gap-2 p-4">
                    <button type="button" class="delivery-toggle flex items-center gap-2 min-w-0 flex-1 text-left">
                        <span class="delivery-chevron text-xs text-zinc-400 dark:text-zinc-500 transition-transform duration-200 rotate-180 shrink-0">▼</span>
                        <span class="delivery-heading text-sm font-semibold text-zinc-600 dark:text-zinc-300 truncate">Line ${index + 1}</span>
                    </button>
                    <span class="delivery-status shrink-0 text-[11px] font-semibold px-2 py-0.5 rounded-full"></span>
                </div>
                <div class="delivery-body px-4 pb-4">
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Consignee</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Name</label>
                            <input type="text" data-field="consignee_name" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Contact Person</label>
                            <input type="text" data-field="consignee_contact_person" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Contact Number</label>
                            <input type="text" data-field="consignee_contact_number" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Consignee Address</label>
                            <input type="text" data-field="consignee_address" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                        </div>
                    </div>

                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mt-4 mb-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">Delivery Timing</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Delivery Date</label>
                            <input type="date" data-field="delivery_date" class="w-full sm:w-64 border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">The date you expect to deliver.</p>
                        </div>
                        <div class="sm:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Delivery Window</label>
                            <div class="flex flex-col sm:flex-row gap-3 mt-1">
                                <div>
                                    <label class="text-[10px] text-zinc-400 uppercase">First (earliest)</label>
                                    <input type="date" data-field="first_delivery_date" class="block border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                </div>
                                <div>
                                    <label class="text-[10px] text-zinc-400 uppercase">Last (latest)</label>
                                    <input type="date" data-field="last_delivery_date" class="block border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                                </div>
                            </div>
                            <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">If delivery could happen anytime in a window instead of one exact date, give the earliest and latest possible dates here.</p>
                        </div>
                        <div class="sm:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Delivery Notes</label>
                            <input type="text" data-field="delivery_date_notes" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                            <p class="text-[11px] text-zinc-400 dark:text-zinc-500 mt-1">Anything about the delivery timing the receiving team should know (optional context, not required).</p>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        // -----------------------------------------------------------------
        // Exclusive-expand chrome (lines, container rows, delivery blocks)
        // -----------------------------------------------------------------
        function setLineExpanded(card, expanded) {
            card.querySelector('.line-body').classList.toggle('hidden', !expanded);
            card.querySelector('.line-chevron').classList.toggle('rotate-180', expanded);
            card.querySelector('.line-title').classList.toggle('hidden', !expanded);
            card.querySelector('.line-summary').classList.toggle('hidden', expanded);
        }

        // 1 line = always expanded, no collapse chrome; 2+ = collapse chrome active.
        function applyLineChrome() {
            const cards = cargoCards();
            const exclusive = cards.length > 1;
            cards.forEach((card) => {
                card.querySelector('.line-toggle').classList.toggle('pointer-events-none', !exclusive);
                card.querySelector('.line-chevron').classList.toggle('invisible', !exclusive);
                if (!exclusive) setLineExpanded(card, true);
            });
        }

        function setRowExpanded(row, expanded) {
            row.querySelector('.row-body').classList.toggle('hidden', !expanded);
            row.querySelector('.row-chevron').classList.toggle('rotate-180', expanded);
            row.querySelector('.row-title').classList.toggle('hidden', !expanded);
            row.querySelector('.row-summary').classList.toggle('hidden', expanded);
        }

        function applyRowChrome(card) {
            const rows = Array.from(card.querySelectorAll('.container-row'));
            const exclusive = rows.length > 1;
            rows.forEach((row) => {
                row.querySelector('.row-toggle').classList.toggle('pointer-events-none', !exclusive);
                row.querySelector('.row-chevron').classList.toggle('invisible', !exclusive);
                if (!exclusive) setRowExpanded(row, true);
            });
        }

        function setDeliveryExpanded(block, expanded) {
            block.querySelector('.delivery-body').classList.toggle('hidden', !expanded);
            block.querySelector('.delivery-chevron').classList.toggle('rotate-180', expanded);
        }

        function applyDeliveryChrome() {
            const blocks = Array.from(el('deliveryBlocksContainer').querySelectorAll('.delivery-block'));
            const exclusive = blocks.length > 1;
            blocks.forEach((block) => {
                block.querySelector('.delivery-toggle').classList.toggle('pointer-events-none', !exclusive);
                block.querySelector('.delivery-chevron').classList.toggle('invisible', !exclusive);
                if (!exclusive) setDeliveryExpanded(block, true);
            });
            el('deliveryEmpty').classList.toggle('hidden', blocks.length > 0);
        }

        // -----------------------------------------------------------------
        // Summaries & badges
        // -----------------------------------------------------------------
        function updateLineSummary(card) {
            const n = lineNumberOf(card);
            const route = routeText(card);
            const count = card.querySelectorAll('.container-row').length;
            card.querySelector('.line-summary').textContent = `${route} · ${count} container${count === 1 ? '' : 's'}`;

            const block = deliveryBlockFor(card);
            if (block) block.querySelector('.delivery-heading').textContent = `Line ${n}: ${route}`;
        }

        function updateLineBadge(card) {
            const rows = Array.from(card.querySelectorAll('.container-row'));
            const live = rows.length > 0 && rows.every((r) =>
                r.querySelector('[data-field="cargo_type"]').value.trim() &&
                r.querySelector('[data-field="declared_value"]').value.trim());
            const badge = card.querySelector('.line-badge');
            badge.textContent = live ? 'Live' : 'Tentative';
            badge.classList.toggle('bg-green-100', live);
            badge.classList.toggle('text-green-700', live);
            badge.classList.toggle('dark:bg-green-900/40', live);
            badge.classList.toggle('dark:text-green-300', live);
            badge.classList.toggle('bg-amber-100', !live);
            badge.classList.toggle('text-amber-700', !live);
            badge.classList.toggle('dark:bg-amber-900/40', !live);
            badge.classList.toggle('dark:text-amber-300', !live);
        }

        function updateRowSummary(row) {
            const typeSel = row.querySelector('.row-container-type');
            const typeLabel = typeSel.value ? typeSel.options[typeSel.selectedIndex].textContent : 'No container';
            const sizeSel = row.querySelector('.row-container-size');
            const sizeLabel = sizeSel.value ? sizeSel.options[sizeSel.selectedIndex].textContent : '';
            const qty = row.querySelector('[data-field="quantity"]').value || '0';

            let text = typeLabel;
            if (sizeLabel && sizeLabel !== 'N/A (no fixed size)') text += ` · ${sizeLabel}`;
            text += ` · Qty ${qty}`;

            const tempHidden = row.querySelector('.field-container-temperature').classList.contains('hidden');
            const tempVal = row.querySelector('[data-field="minimum_temperature"]').value;
            if (!tempHidden && !tempVal) text += ' · ⚠ Min. Temp not set';

            row.querySelector('.row-summary').textContent = text;
        }

        function updateDeliverySummary(block) {
            const filled = Object.keys(DELIVERY_FIELD_LABELS).every((field) =>
                block.querySelector(`[data-field="${field}"]`)?.value.trim());
            const status = block.querySelector('.delivery-status');
            status.textContent = filled ? 'Complete' : 'Incomplete';
            status.classList.toggle('bg-green-100', filled);
            status.classList.toggle('text-green-700', filled);
            status.classList.toggle('dark:bg-green-900/40', filled);
            status.classList.toggle('dark:text-green-300', filled);
            status.classList.toggle('bg-amber-100', !filled);
            status.classList.toggle('text-amber-700', !filled);
            status.classList.toggle('dark:bg-amber-900/40', !filled);
            status.classList.toggle('dark:text-amber-300', !filled);
        }

        // -----------------------------------------------------------------
        // Mode option-cards (presentation over a hidden door/pier select)
        // -----------------------------------------------------------------
        function syncModeCards(card) {
            ['origin', 'destination'].forEach((side) => {
                const sel = card.querySelector(`[data-field="${side}_mode"]`);
                const val = sel.value || 'door';
                card.querySelectorAll(`.mode-cards[data-mode-side="${side}"] .mode-card`).forEach((btn) => {
                    const on = btn.dataset.mode === val;
                    btn.classList.toggle('border-orange-500', on);
                    btn.classList.toggle('bg-orange-50', on);
                    btn.classList.toggle('dark:bg-orange-950/30', on);
                    btn.classList.toggle('ring-1', on);
                    btn.classList.toggle('ring-orange-500', on);
                    btn.classList.toggle('border-zinc-200', !on);
                    btn.classList.toggle('dark:border-zinc-700', !on);
                });

                // Pier Handling only applies (and only shows) when that side is Pier.
                // modeCardsHtml() renders the hidden mode <select> immediately
                // before the .pier-handling-field div as siblings.
                const pierField = sel.nextElementSibling;
                const isPier = val === 'pier';
                pierField?.classList.toggle('hidden', !isPier);
                if (!isPier) {
                    const pierSelect = pierField?.querySelector(`[data-field="${side}_pier_handling"]`);
                    if (pierSelect) pierSelect.value = '';
                }
            });
        }

        function addLine() {
            const index = lineCounter++;
            el('cargoLinesContainer').insertAdjacentHTML('beforeend', lineCardHtml(index));
            const card = document.querySelector(`.cargo-line-card[data-line-index="${index}"]`);

            el('deliveryBlocksContainer').insertAdjacentHTML('beforeend', deliveryBlockHtml(index));
            const block = document.querySelector(`.delivery-block[data-line-index="${index}"]`);

            wireLineCard(card);
            wireDeliveryBlock(block);
            addContainerRow(card);

            // Exclusive expand: adding a line collapses existing ones (both the
            // cargo card and its Step-3 delivery block).
            cargoCards().forEach((other) => { if (other !== card) setLineExpanded(other, false); });
            el('deliveryBlocksContainer').querySelectorAll('.delivery-block').forEach((other) => {
                if (other !== block) setDeliveryExpanded(other, false);
            });

            renumberLines();
            applyLineChrome();
            applyDeliveryChrome();
            updateLineSummary(card);
            updateLineBadge(card);
            updateDeliverySummary(block);
            refreshWizardState();
            return card;
        }

        function renumberLines() {
            cargoCards().forEach((card, i) => {
                card.querySelector('.line-title').textContent = `Cargo Line ${i + 1}`;
                updateLineSummary(card);
            });
        }

        function refreshRowRemovability(rowsContainer) {
            const rows = rowsContainer.querySelectorAll('.container-row');
            rows.forEach((r) => r.querySelector('.remove-container-row').classList.toggle('hidden', rows.length <= 1));
        }

        function addContainerRow(card) {
            const rowsContainer = card.querySelector('.container-rows-container');
            const index = containerRowCounter++;
            rowsContainer.insertAdjacentHTML('beforeend', containerRowHtml(index));
            const row = rowsContainer.querySelector(`.container-row[data-row-index="${index}"]`);
            wireContainerRow(card, row);

            // Exclusive expand within this line's rows.
            rowsContainer.querySelectorAll('.container-row').forEach((other) => {
                if (other !== row) setRowExpanded(other, false);
            });

            refreshRowRemovability(rowsContainer);
            applyRowChrome(card);
            updateRowSummary(row);
            updateLineSummary(card);
            updateLineBadge(card);
            refreshWizardState();
            return row;
        }

        // -----------------------------------------------------------------
        // Container type / class / size cascade (unchanged logic)
        // -----------------------------------------------------------------
        function applyContainerRowVisibility(row, containerId) {
            const variantsForContainer = containerVariants.filter((v) => String(v.container.id) === String(
                containerId));
            const hasRealClasses = variantsForContainer.some((v) => v.container_class);
            const hasRealSizes = variantsForContainer.some((v) => v.container_size);
            const isReefer = variantsForContainer[0]?.container.code === 'RF';

            row.querySelector('.field-container-class').classList.toggle('hidden', !containerId || !hasRealClasses);
            row.querySelector('.field-container-size').classList.toggle('hidden', !containerId || !hasRealSizes);
            row.querySelector('.field-container-temperature').classList.toggle('hidden', !containerId || !isReefer);
        }

        function autoFillSizeIfHidden(row) {
            const sizeSel = row.querySelector('.row-container-size');
            const variantInput = row.querySelector('[data-field="container_variant_id"]');
            const sizeHidden = row.querySelector('.field-container-size').classList.contains('hidden');

            if (sizeHidden) {
                const opt = Array.from(sizeSel.options).find((o) => o.value);
                sizeSel.value = opt ? opt.value : '';
                variantInput.value = opt?.dataset.variantId ?? '';
            } else {
                sizeSel.value = '';
                variantInput.value = '';
            }
        }

        function populateContainerClassSelect(row, containerId) {
            const classSel = row.querySelector('.row-container-class');
            const variantsForContainer = containerVariants.filter((v) => String(v.container.id) === String(containerId));
            const classes = [...new Map(
                variantsForContainer
                .filter((v) => v.container_class)
                .map((v) => [v.container_class.id, v.container_class])
            ).values()];
            const hasBase = variantsForContainer.some((v) => !v.container_class);

            classSel.innerHTML = `<option value="">Select</option>` +
                (hasBase ? `<option value="__base__">Base (No Class)</option>` : '') +
                classes.map((c) => `<option value="${c.id}">${c.class}</option>`).join('');
        }

        function populateContainerSizeSelect(row, containerId, classId) {
            const sizeSel = row.querySelector('.row-container-size');
            const sizes = containerVariants.filter(
                (v) => String(v.container.id) === String(containerId) && (classId === '__base__' ? !v
                    .container_class : String(v.container_class?.id) === String(classId))
            );

            sizeSel.innerHTML = `<option value="">Select</option>` +
                sizes.map((v) =>
                    `<option value="${v.id}" data-variant-id="${v.id}">${v.container_size?.size ?? 'N/A (no fixed size)'}</option>`
                ).join('');
        }

        function setContainerRowVariant(row, variantId) {
            const variant = containerVariants.find((v) => String(v.id) === String(variantId));
            if (!variant) return;

            const typeSel = row.querySelector('.row-container-type');
            const classSel = row.querySelector('.row-container-class');
            const sizeSel = row.querySelector('.row-container-size');

            typeSel.value = variant.container.id;
            populateContainerClassSelect(row, variant.container.id);
            applyContainerRowVisibility(row, variant.container.id);
            classSel.value = variant.container_class ? variant.container_class.id : '__base__';
            populateContainerSizeSelect(row, variant.container.id, classSel.value);
            sizeSel.value = variant.id;
            row.querySelector('[data-field="container_variant_id"]').value = variant.id;
            updateRowSummary(row);
        }

        async function uploadHazmatFile(file) {
            const formData = new FormData();
            formData.append('file', file);

            const response = await apiCall({
                mode: 'POST',
                isJson: false,
                payload: formData,
                url: '/api/bookings/hazmat-upload',
            });

            return response.success ? response.data.path : null;
        }

        function wireLineCard(card) {
            card.querySelector('.remove-line').addEventListener('click', () => {
                const block = deliveryBlockFor(card);
                card.remove();
                block?.remove();
                renumberLines();
                applyLineChrome();
                applyDeliveryChrome();
                scheduleQuote();
                refreshWizardState();
            });

            card.querySelector('.line-toggle').addEventListener('click', () => {
                if (cargoCards().length <= 1) return;
                const expand = card.querySelector('.line-body').classList.contains('hidden');
                if (expand) {
                    cargoCards().forEach((other) => { if (other !== card) setLineExpanded(other, false); });
                }
                setLineExpanded(card, expand);
            });

            [card.querySelector('.line-origin-location'), card.querySelector('.line-origin-port'),
                card.querySelector('.line-destination-location'), card.querySelector('.line-destination-port')
            ].forEach((elm) => makeSearchableSelect(elm));

            card.querySelectorAll('input, select').forEach((elm) => {
                elm.addEventListener('change', scheduleQuote);
            });

            card.querySelector('.line-origin-port').addEventListener('change', function() {
                loadAreasForElement(card.querySelector('.line-origin-area'), this.value);
            });

            card.querySelector('.line-destination-port').addEventListener('change', function() {
                loadAreasForElement(card.querySelector('.line-destination-area'), this.value);
            });

            card.querySelector('.line-origin-location').addEventListener('change', function() {
                const portSelect = card.querySelector('.line-origin-port');
                portSelect.innerHTML = portOptionsForLocation(this.value);
                portSelect.disabled = !this.value;
                refreshSearchable(portSelect);
                portSelect.dispatchEvent(new Event('change'));
            });

            card.querySelector('.line-destination-location').addEventListener('change', function() {
                const portSelect = card.querySelector('.line-destination-port');
                portSelect.innerHTML = portOptionsForLocation(this.value);
                portSelect.disabled = !this.value;
                refreshSearchable(portSelect);
                portSelect.dispatchEvent(new Event('change'));
            });

            // Presentation-only mode option-cards drive the hidden door/pier
            // select, which every downstream reader still uses unchanged.
            card.querySelectorAll('.mode-card').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const side = btn.closest('.mode-cards').dataset.modeSide;
                    const sel = card.querySelector(`[data-field="${side}_mode"]`);
                    sel.value = btn.dataset.mode;
                    sel.dispatchEvent(new Event('change'));
                    syncModeCards(card);
                });
            });
            syncModeCards(card);

            card.querySelector('.add-container-row').addEventListener('click', () => {
                addContainerRow(card);
                scheduleQuote();
            });
        }

        function wireContainerRow(card, row) {
            row.querySelector('.remove-container-row').addEventListener('click', () => {
                const rowsContainer = card.querySelector('.container-rows-container');
                row.remove();
                refreshRowRemovability(rowsContainer);
                applyRowChrome(card);
                updateLineSummary(card);
                updateLineBadge(card);
                scheduleQuote();
                refreshWizardState();
            });

            row.querySelector('.row-toggle').addEventListener('click', () => {
                const rowsContainer = card.querySelector('.container-rows-container');
                if (rowsContainer.querySelectorAll('.container-row').length <= 1) return;
                const expand = row.querySelector('.row-body').classList.contains('hidden');
                if (expand) {
                    rowsContainer.querySelectorAll('.container-row').forEach((other) => {
                        if (other !== row) setRowExpanded(other, false);
                    });
                }
                setRowExpanded(row, expand);
            });

            row.querySelectorAll('input, select').forEach((elm) => {
                elm.addEventListener('change', scheduleQuote);
            });

            const typeSel = row.querySelector('.row-container-type');
            const classSel = row.querySelector('.row-container-class');
            const sizeSel = row.querySelector('.row-container-size');
            const variantInput = row.querySelector('[data-field="container_variant_id"]');

            typeSel.addEventListener('change', () => {
                const containerId = typeSel.value;
                populateContainerClassSelect(row, containerId);
                applyContainerRowVisibility(row, containerId);
                const classHidden = row.querySelector('.field-container-class').classList.contains('hidden');
                classSel.value = classHidden ? '__base__' : '';
                populateContainerSizeSelect(row, containerId, classSel.value);
                autoFillSizeIfHidden(row);
                updateRowSummary(row);
                scheduleQuote();
            });

            classSel.addEventListener('change', () => {
                populateContainerSizeSelect(row, typeSel.value, classSel.value);
                autoFillSizeIfHidden(row);
                updateRowSummary(row);
                scheduleQuote();
            });

            sizeSel.addEventListener('change', () => {
                const selected = sizeSel.options[sizeSel.selectedIndex];
                variantInput.value = selected?.dataset.variantId ?? '';
                updateRowSummary(row);
                scheduleQuote();
            });

            row.querySelector('.row-hazardous-checkbox').addEventListener('change', function() {
                row.querySelector('.hazmat-upload').classList.toggle('hidden', !this.checked);
                scheduleQuote();
            });

            row.querySelector('.row-hazmat-file').addEventListener('change', async function() {
                const file = this.files[0];
                if (!file) return;
                const statusEl = row.querySelector('.hazmat-upload-status');
                statusEl.textContent = 'Uploading...';
                const path = await uploadHazmatFile(file);
                row.querySelector('[data-field="hazardous_document_path"]').value = path ?? '';
                statusEl.textContent = path ? `Uploaded: ${file.name}` : 'Upload failed.';
            });
        }

        function wireDeliveryBlock(block) {
            block.querySelector('.delivery-toggle').addEventListener('click', () => {
                const blocks = el('deliveryBlocksContainer').querySelectorAll('.delivery-block');
                if (blocks.length <= 1) return;
                const expand = block.querySelector('.delivery-body').classList.contains('hidden');
                if (expand) {
                    blocks.forEach((other) => { if (other !== block) setDeliveryExpanded(other, false); });
                }
                setDeliveryExpanded(block, expand);
            });
        }

        // Delegated updates: cargo card / row edits refresh summaries, badges
        // and wizard gating without re-wiring each dynamically-added field.
        el('cargoLinesContainer').addEventListener('input', onCargoFieldChange);
        el('cargoLinesContainer').addEventListener('change', onCargoFieldChange);

        function onCargoFieldChange(e) {
            const card = e.target.closest('.cargo-line-card');
            if (card) {
                updateLineSummary(card);
                updateLineBadge(card);
                const row = e.target.closest('.container-row');
                if (row) updateRowSummary(row);
            }
            refreshWizardState();
        }

        el('deliveryBlocksContainer').addEventListener('input', onDeliveryFieldChange);
        el('deliveryBlocksContainer').addEventListener('change', onDeliveryFieldChange);

        function onDeliveryFieldChange(e) {
            const block = e.target.closest('.delivery-block');
            if (block) updateDeliverySummary(block);
            refreshWizardState();
        }

        el('addLineBtn').addEventListener('click', () => { addLine(); scheduleQuote(); });

        // -----------------------------------------------------------------
        // Payload + quote + save
        // -----------------------------------------------------------------
        function collectLines() {
            return cargoCards().flatMap((card) => {
                const get = (field) => card.querySelector(`[data-field="${field}"]`);
                const block = deliveryBlockFor(card);
                const dget = (field) => block?.querySelector(`[data-field="${field}"]`);

                // Route + delivery mode come from the Step-2 card; consignee +
                // delivery-date fields now live in the Step-3 delivery block.
                // The backend still stores one flat booking_line per container,
                // so this shared data is duplicated across each container row.
                const shared = {
                    origin_port_id: Number(get('origin_port_id').value) || null,
                    destination_port_id: Number(get('destination_port_id').value) || null,
                    origin_area_id: Number(get('origin_area_id').value) || null,
                    destination_area_id: Number(get('destination_area_id').value) || null,
                    origin_mode: get('origin_mode').value,
                    destination_mode: get('destination_mode').value,
                    origin_pier_handling: get('origin_pier_handling')?.value || null,
                    destination_pier_handling: get('destination_pier_handling')?.value || null,
                    consignee_name: dget('consignee_name')?.value || null,
                    consignee_address: dget('consignee_address')?.value || null,
                    consignee_contact_person: dget('consignee_contact_person')?.value || null,
                    consignee_contact_number: dget('consignee_contact_number')?.value || null,
                    delivery_date: dget('delivery_date')?.value || null,
                    delivery_date_notes: dget('delivery_date_notes')?.value || null,
                    first_delivery_date: dget('first_delivery_date')?.value || null,
                    last_delivery_date: dget('last_delivery_date')?.value || null,
                };

                return Array.from(card.querySelectorAll('.container-row')).map((row) => {
                    const rowGet = (field) => row.querySelector(`[data-field="${field}"]`);

                    return {
                        ...shared,
                        container_variant_id: Number(rowGet('container_variant_id').value) || null,
                        quantity: Number(rowGet('quantity').value) || 1,
                        description: rowGet('description').value || null,
                        weight_kg: rowGet('weight_kg').value || null,
                        volume_cbm: rowGet('volume_cbm').value || null,
                        is_hazardous: rowGet('is_hazardous').checked,
                        is_fragile: rowGet('is_fragile').checked,
                        hazardous_document_path: rowGet('hazardous_document_path').value || null,
                        minimum_temperature: rowGet('minimum_temperature').value || null,
                        cargo_type: rowGet('cargo_type').value || null,
                        other_cargo_details: rowGet('other_cargo_details').value || null,
                        declared_value: parseCurrencyValue(rowGet('declared_value').value) || null,
                    };
                });
            }).filter(l => l.container_variant_id && l.origin_port_id && l.destination_port_id);
        }

        function collectPayload() {
            return {
                client_id: el('clientId').value || null,
                booking_date: el('bookingDate').value || null,
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
            const quoteBody = el('quoteBody');

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
                const originPort = portById[String(l.origin_port_id)];
                const destinationPort = portById[String(l.destination_port_id)];
                const origin = originPort ? (originPort.location?.name ?? '?') + ' - ' + originPort.name : '?';
                const destination = destinationPort ? (destinationPort.location?.name ?? '?') + ' - ' + destinationPort.name : '?';

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

        function validateBeforeSave(payload) {
            if (!payload.client_id) {
                showMessage({ status: 'error', title: 'Select a client first' });
                return false;
            }

            if (!payload.lines.length) {
                showMessage({ status: 'error', title: 'Add at least one cargo line' });
                return false;
            }

            return true;
        }

        // Creates or updates the booking (Draft). Save as Draft submits the FULL
        // current in-memory state - the relaxed backend validation accepts
        // partial/empty Step-3 delivery data. Returns the saved uuid or null.
        async function saveBooking(button) {
            const payload = collectPayload();
            if (!validateBeforeSave(payload)) return null;

            const response = await apiCall({
                mode: isEdit ? 'PUT' : 'POST',
                isJson: true,
                payload,
                url: isEdit ? `/api/bookings/${bookingUuid}` : '/api/bookings',
                button,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to save booking',
                    message: response.invalid_fields ? Object.values(response.invalid_fields).flat().join(' ') : (response.message ?? ''),
                });
                return null;
            }

            bookingUuid = response.data.uuid;
            isEdit = true;
            el('formPageTitle').textContent = `Edit Booking — ${response.data.code}`;

            return bookingUuid;
        }

        // Save as Draft is available from every step's footer, all pointing here.
        document.querySelectorAll('.save-draft-btn').forEach((btn) => {
            btn.addEventListener('click', async function() {
                const wasEdit = isEdit;
                const uuid = await saveBooking(this);
                if (!uuid) return;

                showMessage({ status: 'success', title: wasEdit ? 'Booking updated' : 'Booking saved as Draft' });
                goBackToList();
            });
        });

        // Confirm saves the form first (creating the booking if new), then hits
        // the confirm endpoint. The client-side check keeps this button disabled
        // until every line's delivery details are filled; if the server still
        // rejects (race / missed field) its multi-line message is shown verbatim.
        el('confirmBookingBtn').addEventListener('click', async function() {
            if (this.disabled) return;

            const uuid = await saveBooking(this);
            if (!uuid) return;

            const response = await apiCall({
                mode: 'POST',
                url: `/api/bookings/${uuid}/confirm`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Booking saved but could not be confirmed',
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

        el('btnBackToList').addEventListener('click', goBackToList);

        // -----------------------------------------------------------------
        // Edit mode - prefill from an existing Draft booking
        // -----------------------------------------------------------------
        function lineGroupKey(line) {
            return [
                line.origin_port_id, line.destination_port_id, line.origin_area_id, line.destination_area_id,
                line.delivery_type ? (line.delivery_type.includes_origin_trucking ? 'door' : 'pier') : '',
                line.delivery_type ? (line.delivery_type.includes_destination_trucking ? 'door' : 'pier') : '',
                line.origin_pier_handling, line.destination_pier_handling,
                line.consignee_name, line.consignee_address, line.consignee_contact_person, line.consignee_contact_number,
                line.delivery_date, line.delivery_date_notes, line.first_delivery_date, line.last_delivery_date,
            ].join('|');
        }

        function fillContainerRow(row, line) {
            setContainerRowVariant(row, line.container_variant_id);
            row.querySelector('[data-field="quantity"]').value = line.quantity;
            row.querySelector('[data-field="minimum_temperature"]').value = line.minimum_temperature ?? '';
            row.querySelector('[data-field="description"]').value = line.description ?? '';
            row.querySelector('[data-field="weight_kg"]').value = line.weight_kg ?? '';
            row.querySelector('[data-field="volume_cbm"]').value = line.volume_cbm ?? '';
            row.querySelector('[data-field="is_fragile"]').checked = !!line.is_fragile;
            const hazardousCheckbox = row.querySelector('[data-field="is_hazardous"]');
            hazardousCheckbox.checked = !!line.is_hazardous;
            row.querySelector('.hazmat-upload').classList.toggle('hidden', !hazardousCheckbox.checked);
            row.querySelector('[data-field="hazardous_document_path"]').value = line.hazardous_document_path ?? '';
            if (line.hazardous_document_path) {
                row.querySelector('.hazmat-upload-status').textContent = 'Document already uploaded.';
            }
            row.querySelector('[data-field="cargo_type"]').value = line.cargo_type ?? '';
            row.querySelector('[data-field="other_cargo_details"]').value = line.other_cargo_details ?? '';
            row.querySelector('[data-field="declared_value"]').value = formatCurrencyDisplay(line.declared_value ?? '');
            updateRowSummary(row);
        }

        async function loadForEdit() {
            const response = await apiCall({ mode: 'GET', url: `/api/bookings/${bookingUuid}` });
            if (!response.success) return;

            const b = response.data;
            el('formPageTitle').textContent = `Edit Booking — ${b.code}`;
            selectClient(b.client_id, b.client?.company_name ?? 'Client', b.client?.uuid);
            el('bookingDate').value = b.booking_date ?? '';

            const groups = [];
            const groupIndexByKey = new Map();
            for (const line of (b.lines ?? [])) {
                const key = lineGroupKey(line);
                if (!groupIndexByKey.has(key)) {
                    groupIndexByKey.set(key, groups.length);
                    groups.push([]);
                }
                groups[groupIndexByKey.get(key)].push(line);
            }

            for (const group of groups) {
                const card = addLine();
                const block = deliveryBlockFor(card);
                const line = group[0];

                const originSelect = card.querySelector('.line-origin-port');
                const originLocationSelect = card.querySelector('.line-origin-location');
                const originPort = ports.find((p) => String(p.port_id) === String(line.origin_port_id));
                if (originPort) {
                    originLocationSelect.value = originPort.location_id ?? '';
                    refreshSearchable(originLocationSelect);
                    originSelect.innerHTML = portOptionsForLocation(originPort.location_id);
                }
                originSelect.disabled = !line.origin_port_id;
                originSelect.value = line.origin_port_id ?? '';
                refreshSearchable(originSelect);
                await loadAreasForElement(card.querySelector('.line-origin-area'), line.origin_port_id);
                card.querySelector('[data-field="origin_area_id"]').value = line.origin_area_id ?? '';

                const destinationSelect = card.querySelector('.line-destination-port');
                const destinationLocationSelect = card.querySelector('.line-destination-location');
                const destinationPort = ports.find((p) => String(p.port_id) === String(line.destination_port_id));
                if (destinationPort) {
                    destinationLocationSelect.value = destinationPort.location_id ?? '';
                    refreshSearchable(destinationLocationSelect);
                    destinationSelect.innerHTML = portOptionsForLocation(destinationPort.location_id);
                }
                destinationSelect.disabled = !line.destination_port_id;
                destinationSelect.value = line.destination_port_id ?? '';
                refreshSearchable(destinationSelect);
                await loadAreasForElement(card.querySelector('.line-destination-area'), line.destination_port_id);
                card.querySelector('[data-field="destination_area_id"]').value = line.destination_area_id ?? '';

                if (line.delivery_type) {
                    card.querySelector('[data-field="origin_mode"]').value = line.delivery_type.includes_origin_trucking ? 'door' : 'pier';
                    card.querySelector('[data-field="destination_mode"]').value = line.delivery_type.includes_destination_trucking ? 'door' : 'pier';
                }
                syncModeCards(card);
                card.querySelector('[data-field="origin_pier_handling"]').value = line.origin_pier_handling ?? '';
                card.querySelector('[data-field="destination_pier_handling"]').value = line.destination_pier_handling ?? '';

                block.querySelector('[data-field="consignee_name"]').value = line.consignee_name ?? '';
                block.querySelector('[data-field="consignee_address"]').value = line.consignee_address ?? '';
                block.querySelector('[data-field="consignee_contact_person"]').value = line.consignee_contact_person ?? '';
                block.querySelector('[data-field="consignee_contact_number"]').value = line.consignee_contact_number ?? '';
                block.querySelector('[data-field="delivery_date"]').value = line.delivery_date ?? '';
                block.querySelector('[data-field="delivery_date_notes"]').value = line.delivery_date_notes ?? '';
                block.querySelector('[data-field="first_delivery_date"]').value = line.first_delivery_date ?? '';
                block.querySelector('[data-field="last_delivery_date"]').value = line.last_delivery_date ?? '';
                updateDeliverySummary(block);

                const rowsContainer = card.querySelector('.container-rows-container');
                for (let i = 0; i < group.length; i++) {
                    const row = i === 0 ? rowsContainer.querySelector('.container-row') : addContainerRow(card);
                    fillContainerRow(row, group[i]);
                }
                applyRowChrome(card);
                updateLineSummary(card);
                updateLineBadge(card);
            }

            // Existing lines can look alike until scanned - collapse each to its
            // summary once hydrated; applyLineChrome() re-expands a lone line.
            cargoCards().forEach((card) => setLineExpanded(card, false));
            el('deliveryBlocksContainer').querySelectorAll('.delivery-block').forEach((block) => setDeliveryExpanded(block, false));
            renumberLines();
            applyLineChrome();
            applyDeliveryChrome();

            scheduleQuote();
            refreshWizardState();
        }

        // -----------------------------------------------------------------
        // Init
        // -----------------------------------------------------------------
        async function init() {
            bookingUuid = window.bookingFormUuid || null;
            isEdit = Boolean(bookingUuid);
            window.bookingFormUuid = null;

            el('bookingDate').value = new Date().toISOString().slice(0, 10);

            await loadReferenceData();

            if (isEdit) {
                await loadForEdit();
            } else {
                addLine();
            }

            showStep(1);
            refreshWizardState();
        }

        init();
    })();
</script>
