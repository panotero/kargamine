{{--
    Reusable "New Contract" slide-over.

    @include this partial on ANY page that needs to trigger contract
    creation (the Contracts page, the CRM proposal tab, etc). It only
    needs public/js/create-contract-modal.js loaded alongside it.

    Trigger it from anywhere with:
        window.openCreateContractModal();                 // blank, manual search
        window.openCreateContractModal('PR-202607-0004');  // prefilled + auto-searched
--}}
<x-side-modal id="createContractModal">
    <div
        class="flex items-center justify-between px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
        <h3 class="text-base font-semibold text-zinc-900 dark:text-white">New Contract</h3>
        <button type="button" id="createContractCloseBtn"
            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <form id="createContractForm" class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

        {{-- Search --}}
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Proposal Code</label>
            <div class="flex gap-2">
                <input type="text" id="contractProposalCodeInput" placeholder="e.g. PR-202607-0004"
                    class="flex-1 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                <button type="button" id="contractProposalSearchBtn"
                    class="rounded-lg bg-zinc-800 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-900">
                    Search
                </button>
            </div>
            <p id="contractSearchStatus" class="text-xs text-zinc-400 mt-1">Search an agreed proposal to load its client
                and rates.</p>
        </div>

        {{-- Autofilled, read-only once a proposal is found --}}
        <div class="grid grid-cols-2 gap-3 hidden" id="contractSummarySection">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Client</label>
                <input type="text" id="contractClientDisplay" readonly
                    class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Proposal</label>
                <input type="text" id="contractProposalDisplay" readonly
                    class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-300">
            </div>
        </div>

        <input type="hidden" name="lead_id" id="contractLeadIdInput">
        <input type="hidden" name="proposal_id" id="contractProposalIdInput">

        {{-- Contract details - shown once a proposal is loaded --}}
        <div class="grid grid-cols-2 gap-3 hidden" id="contractDetailsSection">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Signed Date</label>
                <input type="date" name="signed_date"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>

            <div></div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Valid From *</label>
                <input type="date" name="valid_from" required
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Valid To *</label>
                <input type="date" name="valid_to" required
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Signed Document
                    Path</label>
                <input type="text" name="signed_document_path" placeholder="e.g. contracts/2026/ctr-0001.pdf"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>

            <p class="col-span-2 text-xs text-zinc-400">Contract code is generated automatically on save.</p>
        </div>

        {{-- Rate lines pulled from the proposal --}}
        <div class="hidden" id="contractRatesSection">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Contract Rates</label>
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-zinc-500 dark:text-zinc-400 uppercase">Route</th>
                            <th class="px-3 py-2 text-left font-medium text-zinc-500 dark:text-zinc-400 uppercase">Container</th>
                            <th class="px-3 py-2 text-left font-medium text-zinc-500 dark:text-zinc-400 uppercase">Min Qty</th>
                            <th class="px-3 py-2 text-left font-medium text-zinc-500 dark:text-zinc-400 uppercase">Discount Type</th>
                            <th class="px-3 py-2 text-left font-medium text-zinc-500 dark:text-zinc-400 uppercase">Discount Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800" id="contractRatesBody"></tbody>
                </table>
            </div>
        </div>
    </form>

    <div class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex justify-end gap-2">
        <button type="button" id="createContractCancelBtn"
            class="rounded-lg border border-zinc-300 dark:border-zinc-700 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Cancel
        </button>
        <button type="submit" form="createContractForm" id="createContractSubmitBtn" disabled
            class="rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700 disabled:opacity-40 disabled:cursor-not-allowed">
            Save Contract
        </button>
    </div>
</x-side-modal>

<script>
    (function() {
        /**
         * Create Contract modal - fully self-contained so it can be dropped
         * onto any page (Contracts list, CRM proposal tab, etc) alongside its
         * partial: resources/views/contracts/_create-contract-modal.blade.php
         *
         * Public API (attached to window so other pages/buttons can call it):
         *   window.openCreateContractModal();                  -> opens blank, manual search
         *   window.openCreateContractModal('PR-202607-0004');  -> prefills the code and
         *                                                          auto-runs the search
         *
         * On successful save, dispatches a 'contract:created' CustomEvent on
         * document with the created contract as event.detail - any page
         * (e.g. the Contracts list) can listen for this to refresh itself
         * without this file needing to know that page exists.
         *
         * ASSUMPTION - adjust PROPOSAL_SEARCH_URL if your actual route for
         * ProposalController@getByCode differs.
         */

        const PROPOSAL_SEARCH_URL = (code) => `/api/proposal/${encodeURIComponent(code)}`; // ADJUST ME

        // -----------------------------------------------------------------
        // Helpers
        // -----------------------------------------------------------------

        // proposals_rates FK columns (route_from, container_class, etc.) come
        // back either as the raw id or as the eager-loaded relation object
        // (Laravel's JSON serialization can surface either depending on your
        // model's $with/casts) - this normalizes to just the id either way.
        function rawId(value) {
            if (value && typeof value === 'object') {
                return value.id ?? value.value ?? '';
            }
            return value ?? '';
        }

        function displayLabel(value, fallback = '-') {
            if (value && typeof value === 'object') {
                return value.name ?? value.code ?? value.label ?? fallback;
            }
            return value ?? fallback;
        }

        function setSectionVisible(id, visible) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('hidden', !visible);
        }

        // -----------------------------------------------------------------
        // Search a proposal by code and autofill the form
        // -----------------------------------------------------------------
        async function searchProposal(code) {
            const statusEl = document.getElementById('contractSearchStatus');
            const submitBtn = document.getElementById('createContractSubmitBtn');

            if (!code) {
                statusEl.textContent = 'Enter a proposal code to search.';
                return;
            }

            statusEl.textContent = 'Searching...';
            submitBtn.disabled = true;

            const response = await apiCall({
                mode: 'GET',
                url: PROPOSAL_SEARCH_URL(code)
            });

            if (!response.success) {
                statusEl.textContent = response.message ?? 'Proposal not found.';
                clearAutofill();
                showMessage({
                    status: 'error',
                    title: 'Not Found',
                    message: response.message ?? `No proposal found for code "${code}".`,
                });
                return;
            }

            const proposal = response.data;
            applyProposalToForm(proposal);
            statusEl.textContent = `Loaded ${proposal.code} - review the rates below and set validity dates.`;
        }

        function applyProposalToForm(proposal) {
            document.getElementById('contractLeadIdInput').value = proposal.lead_id ?? proposal.lead?.id ?? '';
            document.getElementById('contractProposalIdInput').value = proposal.id;

            document.getElementById('contractClientDisplay').value =
                proposal.lead?.contact_name ?? proposal.lead?.company?.company_name ?? `Lead #${proposal.lead_id}`;
            document.getElementById('contractProposalDisplay').value = proposal.code;

            setSectionVisible('contractSummarySection', true);
            setSectionVisible('contractDetailsSection', true);
            setSectionVisible('contractRatesSection', true);

            renderRateLines(proposal.rates ?? []);

            document.getElementById('createContractSubmitBtn').disabled = false;
        }

        function clearAutofill() {
            document.getElementById('contractLeadIdInput').value = '';
            document.getElementById('contractProposalIdInput').value = '';
            document.getElementById('contractClientDisplay').value = '';
            document.getElementById('contractProposalDisplay').value = '';
            document.getElementById('contractRatesBody').innerHTML = '';

            setSectionVisible('contractSummarySection', false);
            setSectionVisible('contractDetailsSection', false);
            setSectionVisible('contractRatesSection', false);

            document.getElementById('createContractSubmitBtn').disabled = true;
        }

        // -----------------------------------------------------------------
        // Rate lines - route_from/route_to/container_* travel as hidden
        // inputs untouched; only discount_type and discount_value are
        // editable here.
        // -----------------------------------------------------------------
        function renderRateLines(rates) {
            const body = document.getElementById('contractRatesBody');

            if (!rates.length) {
                body.innerHTML =
                    `<tr><td colspan="5" class="px-3 py-4 text-center text-zinc-400">This proposal has no rate lines.</td></tr>`;
                return;
            }

            body.innerHTML = rates.map(buildRateLineRow).join('');

            initDataTables(10);
        }

        function buildRateLineRow(rate) {
            const routeFromId = rate.route_from.port_id;
            const routeToId = rate.route_to.port_id;
            const containerClassId = rawId(rate.van_class);
            const containerTypeId = rawId(rate.van_type);
            const containerSizeId = rawId(rate.van_size);
            const originServiceId = rawId(rate.origin_service_type);
            const destinationServiceId = rawId(rate.destination_service_type);

            const routeLabel = `${displayLabel(rate.route_from)} → ${displayLabel(rate.route_to)}`;
            const containerLabel =
                `${displayLabel(rate.van_class.class)} / ${displayLabel(rate.van_type.type)} / ${displayLabel(rate.van_size.size)}`;


            return `
            <tr data-rate-line>
                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-200 whitespace-nowrap">
                    ${routeLabel}
                    <input type="hidden" data-field="route_from" value="${routeFromId}">
                    <input type="hidden" data-field="route_to" value="${routeToId}">
                </td>
                <td class="px-3 py-2 text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                    ${containerLabel}
                    <input type="hidden" data-field="container_class" value="${containerClassId}">
                    <input type="hidden" data-field="container_type" value="${containerTypeId}">
                    <input type="hidden" data-field="container_size" value="${containerSizeId}">
                    <input type="hidden" data-field="origin_service_type" value="${originServiceId}">
                    <input type="hidden" data-field="destination_service_type" value="${destinationServiceId}">
                </td>
                <td class="px-3 py-2 text-zinc-700 dark:text-zinc-200">
                    ${rate.min_van_qty}
                    <input type="hidden" data-field="min_van_qty" value="${rate.min_van_qty}">
                </td>
                <td class="px-3 py-2">
                    <select data-field="discount_type" required class="w-full rounded border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-2 py-1 text-xs focus:border-orange-500 focus:ring-orange-500">
                        <option value="PERCENTAGE">Percentage</option>
                        <option value="FIXED">Fixed Amount</option>
                    </select>
                </td>
                <td class="px-3 py-2">
                    <input type="number" step="0.01" min="0" data-field="discount_value" required
                           class="w-full rounded border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-2 py-1 text-xs focus:border-orange-500 focus:ring-orange-500">
                </td>
            </tr>
        `;

        }

        function collectRateLines() {
            return Array.from(document.querySelectorAll('[data-rate-line]')).map((row) => {
                const line = {};
                row.querySelectorAll('[data-field]').forEach((el) => {
                    line[el.dataset.field] = el.type === 'number' ? Number(el.value) : el.value;
                });
                return line;
            });
        }

        // -----------------------------------------------------------------
        // Submit
        // -----------------------------------------------------------------
        async function handleSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('createContractForm');
            const button = document.getElementById('createContractSubmitBtn');
            const rateLines = collectRateLines();

            if (!rateLines.length) {
                showMessage({
                    status: 'error',
                    title: 'No rate lines',
                    message: 'Search a proposal with at least one rate line before saving.',
                });
                return;
            }

            const payload = {
                lead_id: form.lead_id.value,
                proposal_id: form.proposal_id.value,
                signed_date: form.signed_date.value || null,
                valid_from: form.valid_from.value,
                valid_to: form.valid_to.value,
                signed_document_path: form.signed_document_path.value || null,
                rates: rateLines,
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: '/api/contracts',
                button,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: response.message ??
                        'An unexpected error occurred. Please contact the system administrator.',
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Saved',
                message: `Contract ${response.data.code} created successfully.`,
            });

            closeSideModal('createContractModal');
            resetForm();

            document.dispatchEvent(new CustomEvent('contract:created', {
                detail: response.data
            }));
        }

        function resetForm() {
            document.getElementById('createContractForm').reset();
            document.getElementById('contractProposalCodeInput').value = '';
            document.getElementById('contractSearchStatus').textContent =
                'Search an agreed proposal to load its client and rates.';
            clearAutofill();
        }

        // -----------------------------------------------------------------
        // Public API
        // -----------------------------------------------------------------
        function open(proposalCode) {
            initSideModal({
                modalId: 'createContractModal'
            });

            if (proposalCode) {
                document.getElementById('contractProposalCodeInput').value = proposalCode;
                searchProposal(proposalCode);
            }
        }

        window.openCreateContractModal = open;

        // -----------------------------------------------------------------
        // Init - runs immediately since this script is injected after the
        // markup already exists in the DOM.
        // -----------------------------------------------------------------
        function init() {
            const searchBtn = document.getElementById('contractProposalSearchBtn');
            const searchInput = document.getElementById('contractProposalCodeInput');

            if (!searchBtn) return; // partial not on this page

            searchBtn.addEventListener('click', () => searchProposal(searchInput.value.trim()));

            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchProposal(searchInput.value.trim());
                }
            });

            document.getElementById('createContractForm').addEventListener('submit', handleSubmit);
            document.getElementById('createContractCloseBtn').addEventListener('click', () => closeSideModal(
                'createContractModal'));
            document.getElementById('createContractCancelBtn').addEventListener('click', () => closeSideModal(
                'createContractModal'));
        }

        init();
    })();
</script>
