<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Clients Master Data</h1>
            <p class="text-zinc-500">Manage company master file records</p>
        </div>
        <button id="btnNewClient" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            + New Client
        </button>
    </div>

    {{-- Status count cards --}}
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div class="clientStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer ring-2 ring-blue-500"
                data-status="all">
                <div class="w-full py-1 rounded-full bg-blue-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">ALL</p>
                <p class="text-2xl font-bold text-black" id="countAll">0</p>
            </div>
            <div class="clientStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="complete">
                <div class="w-full py-1 rounded-full bg-green-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">COMPLETE</p>
                <p class="text-2xl font-bold text-black" id="countComplete">0</p>
            </div>
            <div class="clientStatusBtn bg-white border border-zinc-200 rounded-xl p-4 shadow-sm cursor-pointer"
                data-status="incomplete">
                <div class="w-full py-1 rounded-full bg-amber-500"></div>
                <p class="text-xs text-zinc-400 font-semibold mt-2">INCOMPLETE</p>
                <p class="text-2xl font-bold text-black" id="countIncomplete">0</p>
            </div>
        </div>
    </section>

    <x-table id="tableClientMasters" />
</div>

<script>
    (function() {
        renderTable().load(1);

        document.getElementById('btnNewClient').addEventListener('click', function() {
            window.clientMasterFormUuid = null; // explicitly clear — this is "new"
            loadPage({
                title: 'New Client',
                link: '/page_clientMasterForm'
            });
        });

        async function loadCounts() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/clientMasters'
            });
            if (!response.success) return;
            document.getElementById('countAll').textContent = response.counts.all;
            document.getElementById('countComplete').textContent = response.counts.complete;
            document.getElementById('countIncomplete').textContent = response.counts.incomplete;
        }
        loadCounts();

        function statusBadge(isComplete) {
            return isComplete ?
                `<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Complete</span>` :
                `<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-600"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Incomplete</span>`;
        }

        function renderTable() {
            const thead = [{
                    title: 'Customer Code',
                    key: 'customer_code',
                    render: (r) => r.customer_code ?? '-'
                },
                {
                    title: 'Company Name',
                    key: 'company_name',
                    render: (r) => r.company_name ?? '-'
                },
                {
                    title: 'Industry',
                    key: 'industry',
                    render: (r) => r.industry ?? '-'
                },
                {
                    title: 'Sales Rep',
                    key: 'sales_rep.name',
                    render: (r) => r.sales_rep?.name ?? '-'
                },
                {
                    title: 'Stage',
                    key: 'current_stage',
                    render: (r) => `${r.current_stage} / 3`
                },
                {
                    title: 'Status',
                    key: 'is_complete',
                    render: (r) => statusBadge(r.is_complete)
                },
                {
                    title: 'Last Updated',
                    key: 'created_at',
                    render: (r) => formatDateTime(r.created_at)
                },
            ];

            const table = renderRemoteTable({
                url: '/api/clientMasters',
                tableId: 'tableClientMasters',
                afterRenderFunction: handleClick,
                thead: thead,
            });

            function handleClick(row) {
                row.addEventListener('click', function() {
                    const data = JSON.parse(row.dataset.row);
                    window.clientMasterFormUuid = data.uuid; // pass explicitly, not via URL
                    loadPage({
                        title: 'Edit Client',
                        link: '/page_clientMasterForm'
                    });
                });
            }

            return table;
        }

        document.querySelectorAll('.clientStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.clientStatusBtn').forEach((c) => c.classList.remove(
                    'ring-2', 'ring-blue-500'));
                this.classList.add('ring-2', 'ring-blue-500');
                renderTable().setFilter('status', this.dataset.status);
            });
        });
    })();
</script>
