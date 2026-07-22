<div class="container mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-zinc-900">Contracts</h1>
            <p class="text-sm text-zinc-500 mt-1">Signed client contracts created from approved proposals. New
                contracts are created from the Clients page.</p>
        </div>
    </div>

    {{-- Contract Status Cards --}}
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">

            <div class="contractStatusBtn max-md:col-span-2 bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer ring-2 ring-blue-500"
                data-status="all">
                <div class="w-full py-1 rounded-full bg-blue-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ALL</p>
                <p class="text-2xl font-bold text-black" id="countAll">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="active">
                <div class="w-full py-1 rounded-full bg-green-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ACTIVE</p>
                <p class="text-2xl font-bold text-black" id="countActive">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="expiring">
                <div class="w-full py-1 rounded-full bg-amber-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">EXPIRING</p>
                <p class="text-2xl font-bold text-black" id="countExpiring">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="expired">
                <div class="w-full py-1 rounded-full bg-red-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">EXPIRED</p>
                <p class="text-2xl font-bold text-black" id="countExpired">0</p>
            </div>

            <div class="contractStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="terminated">
                <div class="w-full py-1 rounded-full bg-zinc-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">TERMINATED</p>
                <p class="text-2xl font-bold text-black" id="countTerminated">0</p>
            </div>

        </div>
    </section>

    <x-table id="tableClientContracts" />
</div>

{{-- View Contract modal (read-only, with download) --}}
<x-modal id="viewClientContractModal">
    <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold text-zinc-900 dark:text-white" id="vcContractCode">-</p>
                <span id="vcStatusBadge"></span>
            </div>
            <p class="text-xs text-zinc-400" id="vcClientName">-</p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div
        class="max-h-[65vh] overflow-y-auto p-5 space-y-4 text-sm text-zinc-700 dark:text-zinc-200 bg-white dark:bg-zinc-800">
        <div id="vcInfoContainer" class="grid grid-cols-2 gap-3 text-sm"></div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 dark:text-zinc-300 mb-2">Contracted Rates</p>
            <table class="min-w-full border border-zinc-200 dark:border-zinc-700 rounded-lg text-xs">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-3 py-2 text-left text-zinc-500 dark:text-zinc-400 uppercase">Route</th>
                        <th class="px-3 py-2 text-left text-zinc-500 dark:text-zinc-400 uppercase">Container</th>
                        <th class="px-3 py-2 text-right text-zinc-500 dark:text-zinc-400 uppercase">Base Rate</th>
                        <th class="px-3 py-2 text-right text-zinc-500 dark:text-zinc-400 uppercase">Discount</th>
                        <th class="px-3 py-2 text-right text-zinc-500 dark:text-zinc-400 uppercase">Final Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-zinc-700 dark:text-zinc-200"
                    id="vcRatesBody"></tbody>
            </table>
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 bg-white dark:bg-zinc-900">
        <a href="#" id="vcDownloadBtn" target="_blank"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
            Download PDF
        </a>
        <button
            class="modal-close rounded-lg border border-zinc-300 dark:border-zinc-700 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">Close</button>
    </div>
</x-modal>


<script>
    (function() {
        /**
         * Contracts page (SPA content-area script).
         *
         * Contracts are now created exclusively from the Clients page
         * (Client Master detail modal -> Proposals tab -> "Create Contract").
         * This page only lists ALL client contracts across ALL clients,
         * lets you filter by status, and view/download any one of them.
         */

        const CONTRACT_STATUS_MAPPING = {
            1: {
                label: 'Draft',
                classes: 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300'
            },
            2: {
                label: 'Active',
                classes: 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400'
            },
            3: {
                label: 'Expired',
                classes: 'bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400'
            },
            4: {
                label: 'Terminated',
                classes: 'bg-red-50 dark:bg-red-900/40 text-red-700 dark:text-red-400'
            },
        };

        function statusBadge(status) {
            const meta = CONTRACT_STATUS_MAPPING[status] ?? {
                label: 'Unknown',
                classes: 'bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400'
            };
            return `<span class="inline-flex items-center rounded-full ${meta.classes} px-2 py-0.5 text-xs font-medium">${meta.label}</span>`;
        }

        // -----------------------------------------------------------------
        // List
        // -----------------------------------------------------------------
        async function loadContracts() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/clientContracts',
            });

            if (!response.success) return;

            updateContractCounts(response.status_counts);
            renderTable().load(1);
        }

        function renderTable() {
            const thead = [{
                    title: "Code",
                    key: "code",
                },
                {
                    title: "Client",
                    key: "client.company_name",
                    render: (r) => r.client?.company_name ?? '-',
                },
                {
                    title: "Customer Code",
                    key: "client.customer_code",
                    render: (r) => r.client?.customer_code ?? '-',
                },
                {
                    title: "Source Proposal",
                    key: "proposal.code",
                    render: (r) => r.proposal?.code ?? '-',
                },
                {
                    title: "Valid From",
                    key: "valid_from",
                },
                {
                    title: "Valid To",
                    key: "valid_to",
                },
                {
                    title: "Status",
                    key: "status",
                    render: (r) => statusBadge(r.status),
                },
            ];

            const table = renderRemoteTable({
                url: '/api/clientContracts',
                tableId: "tableClientContracts",
                afterRenderFunction: handleClick,
                thead: thead,
            });

            return table;
        }

        function handleClick(row) {
            row.addEventListener("click", function() {
                const data = JSON.parse(row.dataset.row);
                openViewContract(data.id);
            });
        }

        document.querySelectorAll(".contractStatusBtn").forEach((btn) => {
            btn.addEventListener("click", function() {
                document
                    .querySelectorAll(".contractStatusBtn")
                    .forEach((card) => card.classList.remove("ring-2", "ring-orange-500",
                        "ring-blue-500"));

                this.classList.add("ring-2", "ring-orange-500");

                const status = this.dataset.status;
                renderTable().setFilter("status", status);
            });
        });

        // -----------------------------------------------------------------
        // View + download
        // -----------------------------------------------------------------
        async function openViewContract(id) {
            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientContracts/${id}`
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

            document.getElementById('vcContractCode').textContent = contract.code;
            document.getElementById('vcClientName').textContent =
                `${contract.client?.company_name ?? '-'} (${contract.client?.customer_code ?? '-'})`;
            document.getElementById('vcStatusBadge').innerHTML = statusBadge(contract.status);

            document.getElementById('vcInfoContainer').innerHTML = `
                <div><span class="text-zinc-400">Source Proposal:</span> <div class="font-medium text-zinc-900 dark:text-zinc-100">${contract.proposal?.code ?? '-'}</div></div>
                <div><span class="text-zinc-400">Prepared By:</span> <div class="font-medium text-zinc-900 dark:text-zinc-100">${contract.creator?.name ?? '-'}</div></div>
                <div><span class="text-zinc-400">Signed Date:</span> <div class="font-medium text-zinc-900 dark:text-zinc-100">${contract.signed_date ?? '-'}</div></div>
                <div><span class="text-zinc-400">Valid From:</span> <div class="font-medium text-zinc-900 dark:text-zinc-100">${contract.valid_from}</div></div>
                <div><span class="text-zinc-400">Valid To:</span> <div class="font-medium text-zinc-900 dark:text-zinc-100">${contract.valid_to}</div></div>
            `;

            const rateRows = (contract.rates ?? []).map((r) => `
                <tr class="border-t border-zinc-100 dark:border-zinc-800">
                    <td class="px-3 py-2">${r.origin_port?.code ?? '-'} &rarr; ${r.destination_port?.code ?? '-'}</td>
                    <td class="px-3 py-2">${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</td>
                    <td class="px-3 py-2 text-right">${Number(r.base_rate).toLocaleString()}</td>
                    <td class="px-3 py-2 text-right">${r.discount_type ? (r.discount_type === 'percentage' ? r.discount_value + '%' : Number(r.discount_value).toLocaleString()) : '-'}</td>
                    <td class="px-3 py-2 text-right font-semibold">${Number(r.final_rate).toLocaleString()}</td>
                </tr>
            `).join('');

            document.getElementById('vcRatesBody').innerHTML = rateRows ||
                '<tr><td colspan="5" class="px-3 py-4 text-center text-zinc-400">No rate lines.</td></tr>';

            document.getElementById('vcDownloadBtn').href = `/api/clientContracts/${contract.id}/pdf`;

            initModal({
                modalId: 'viewClientContractModal'
            });
        }

        function updateContractCounts(counts) {
            document.getElementById("countAll").textContent = counts.all;
            document.getElementById("countActive").textContent = counts.active;
            document.getElementById("countExpiring").textContent = counts?.expiring ?? 0;
            document.getElementById("countExpired").textContent = counts.expired;
            document.getElementById("countTerminated").textContent = counts.terminated;
        }

        // -----------------------------------------------------------------
        // Init
        // -----------------------------------------------------------------
        function init() {
            loadContracts();
        }

        init();
    })();
</script>
