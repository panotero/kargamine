{{-- Contracts - loaded into the SPA content area, not a full page --}}
<div class="container mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-zinc-900">Contracts</h1>
            <p class="text-sm text-zinc-500 mt-1">Signed client contracts and the discounted rates they carry into
                booking.</p>
        </div>
        <button type="button" id="newContractBtn"
            class="inline-flex items-center gap-1.5 rounded-lg bg-orange-600 px-3.5 py-2 text-sm font-medium text-white hover:bg-orange-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Contract
        </button>
    </div>
    <!-- Contract Status Cards -->
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">

            <div class="contractStatusBtn max-md:col-span-2 bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer ring-2 ring-blue-500"
                data-status="">
                <div class="w-full py-1 rounded-full bg-blue-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ALL</p>
                <p class="text-2xl font-bold text-black" id="countAll">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="1">
                <div class="w-full py-1 rounded-full bg-green-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ACTIVE</p>
                <p class="text-2xl font-bold text-black" id="countActive">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="2">
                <div class="w-full py-1 rounded-full bg-amber-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">EXPIRING</p>
                <p class="text-2xl font-bold text-black" id="countExpiring">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="3">
                <div class="w-full py-1 rounded-full bg-red-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">EXPIRED</p>
                <p class="text-2xl font-bold text-black" id="countExpired">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="4">
                <div class="w-full py-1 rounded-full bg-zinc-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">CANCELLED</p>
                <p class="text-2xl font-bold text-black" id="countCancelled">0</p>
            </div>

        </div>
    </section>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <select id="contractStatusFilter"
            class="rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
            <option value="">All Statuses</option>
            <option value="1">Draft</option>
            <option value="2">Active</option>
            <option value="3">Expired</option>
            <option value="4">Terminated</option>
        </select>
    </div>

    <div class="bg-white border border-zinc-200 rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 text-sm">
            <thead class="bg-zinc-50">
                <tr>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-500 uppercase tracking-wide">Code
                    </th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-500 uppercase tracking-wide">Client
                    </th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-500 uppercase tracking-wide">Proposal
                    </th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-500 uppercase tracking-wide">Valid
                        From</th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-500 uppercase tracking-wide">Valid To
                    </th>
                    <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-500 uppercase tracking-wide">Status
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100" id="contractsTableBody"></tbody>
        </table>
    </div>
</div>

{{-- View Contract slide-over (read-only) --}}
<x-side-modal id="viewContractModal">
    <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-200">
        <h3 class="text-base font-semibold text-zinc-900">Contract Details</h3>
        <button type="button" id="viewContractCloseBtn" class="text-zinc-400 hover:text-zinc-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div id="viewContractBody" class="flex-1 overflow-y-auto px-5 py-4 space-y-4 text-sm text-zinc-700"></div>
</x-side-modal>

<x-contract-modal />


<script>
    (function() {
        /**
         * Contracts list page (SPA content-area script).
         *
         * Contract creation itself lives entirely in create-contract-modal.js
         * (loaded via the _create-contract-modal partial) so it can be reused
         * on other pages (e.g. the CRM proposal tab). This file only lists,
         * filters, and views contracts, and refreshes the list whenever a
         * 'contract:created' event fires from that other module.
         */

        function rows(response) {
            // ADJUST ME if your pagination shape differs.
            return response?.data?.data ?? [];
        }

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

        function statusBadge(status) {
            const meta = CONTRACT_STATUS_MAPPING[status] ?? {
                label: 'Unknown',
                classes: 'bg-zinc-100 text-zinc-500'
            };
            return `<span class="inline-flex items-center rounded-full ${meta.classes} px-2 py-0.5 text-xs font-medium">${meta.label}</span>`;
        }

        // -----------------------------------------------------------------
        // List
        // -----------------------------------------------------------------
        async function loadContracts() {

            const body = document.getElementById('contractsTableBody');
            if (!body) return;

            const status = document.getElementById('contractStatusFilter').value;
            const url = status ? `/api/contracts?status=${status}` : '/api/contracts';

            const response = await apiCall({
                mode: 'GET',
                url
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to load contracts. Please contact the system administrator.',
                });
                return;
            }

            const contracts = rows(response);

            updateContractCounts(contracts);
            console.log(contracts);
            let html = "";
            contracts.forEach(contract => {
                html += buildContractRow(contract);

            });
            body.innerHTML = html;
            initDataTables(10);

            body.querySelectorAll('[data-view-id]').forEach((btn) => {
                btn.addEventListener('click', () => openViewContract(btn.dataset.viewId));
            });
        }

        function buildContractRow(contract) {
            return `
            <tr data-view-id="${contract.id}">
                <td class="px-4 py-2.5 text-zinc-700 font-medium">${contract.code}</td>
                <td class="px-4 py-2.5 text-zinc-700">${contract.lead?.contact_name ?? '-'}</td>
                <td class="px-4 py-2.5 text-zinc-700">${contract.proposal?.code ?? '-'}</td>
                <td class="px-4 py-2.5 text-zinc-700">${contract.valid_from ?? '-'}</td>
                <td class="px-4 py-2.5 text-zinc-700">${contract.valid_to ?? '-'}</td>
                <td class="px-4 py-2.5">${statusBadge(contract.status)}</td>
            </tr>
        `;
        }

        // -----------------------------------------------------------------
        // View
        // -----------------------------------------------------------------
        async function openViewContract(id) {
            const response = await apiCall({
                mode: 'GET',
                url: `/api/contracts/${id}`
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to load this contract.',
                });
                return;
            }

            const contract = response.data;
            const body = document.getElementById('viewContractBody');

            const rateRows = (contract.rates ?? [])
                .map(
                    (r) => `
                    <tr>
                        <td class="px-3 py-2">${r.route_from} &rarr; ${r.route_to}</td>
                        <td class="px-3 py-2">${r.discount_type === 'PERCENTAGE' ? `${r.discount_value}%` : r.discount_value}</td>
                    </tr>
                `
                )
                .join('');

            body.innerHTML = `
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><span class="text-zinc-400">Code</span><div class="font-medium text-zinc-900">${contract.code}</div></div>
                <div><span class="text-zinc-400">Status</span><div>${statusBadge(contract.status)}</div></div>
                <div><span class="text-zinc-400">Valid From</span><div>${contract.valid_from}</div></div>
                <div><span class="text-zinc-400">Valid To</span><div>${contract.valid_to}</div></div>
            </div>

            <div class="mt-4">
                <div class="text-sm font-medium text-zinc-700 mb-2">Rate Lines</div>
                <table class="min-w-full border border-zinc-200 rounded-lg text-xs">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Route</th>
                            <th class="px-3 py-2 text-left text-zinc-500 uppercase">Discount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">${rateRows || '<tr><td colspan="2" class="px-3 py-4 text-center text-zinc-400">No rate lines.</td></tr>'}</tbody>
                </table>
            </div>
        `;

            initSideModal({
                modalId: 'viewContractModal'
            });
        }

        // -----------------------------------------------------------------
        // Init
        // -----------------------------------------------------------------
        function init() {
            loadContracts();

            document.getElementById('contractStatusFilter').addEventListener('change', function() {
                loadContracts();
            });

            // Opens the standalone modal from create-contract-modal.js -
            // this page has no idea how that modal works internally.
            document.getElementById('newContractBtn').addEventListener('click', () => window
                .openCreateContractModal());

            document.getElementById('viewContractCloseBtn').addEventListener('click', () => closeSideModal(
                'viewContractModal'));

            // Refresh the list whenever a contract is created from anywhere,
            // including this page's own modal.
            document.addEventListener('contract:created', loadContracts);
        }

        init();

        function updateContractCounts(contracts) {

            const counts = {
                all: contracts.length,
                active: 0,
                expiring: 0,
                expired: 0,
                cancelled: 0,
            };

            contracts.forEach((contract) => {
                switch (Number(contract.status)) {
                    case 1:
                        counts.active++;
                        break;
                    case 2:
                        counts.expiring++;
                        break;
                    case 3:
                        counts.expired++;
                        break;
                    case 4:
                        counts.cancelled++;
                        break;
                }
            });

            document.getElementById("countAll").textContent = counts.all;
            document.getElementById("countActive").textContent = counts.active;
            document.getElementById("countExpiring").textContent = counts.expiring;
            document.getElementById("countExpired").textContent = counts.expired;
            document.getElementById("countCancelled").textContent = counts.cancelled;
        }
    })();
</script>
