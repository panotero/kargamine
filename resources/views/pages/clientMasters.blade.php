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

{{-- resources/views/pages/clientMasters.blade.php --}}
{{-- ... existing header/status cards/x-table unchanged ... --}}

{{-- Complete-client detail modal --}}
<x-modal id="ClientDetailModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold" id="cdClientName">-</p>
            <p class="text-xs text-zinc-400" id="cdClientCode">-</p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="border-b flex gap-1 px-5">
        <button class="clientDetailTab px-3 py-2 text-sm font-semibold border-b-2 border-orange-500 text-orange-600"
            data-tab="info">Client Information</button>
        <button class="clientDetailTab px-3 py-2 text-sm font-semibold border-b-2 border-transparent text-zinc-500"
            data-tab="proposals">Proposals</button>
        <button class="clientDetailTab px-3 py-2 text-sm font-semibold border-b-2 border-transparent text-zinc-500"
            data-tab="contracts">Contracts</button>
    </div>

    <div class="max-h-[65vh] overflow-y-auto p-5">

        <div class="clientDetailPanel" data-panel="info">
            <div id="cdInfoContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm"></div>
        </div>

        <div class="clientDetailPanel hidden" data-panel="proposals">
            <div class="flex justify-end mb-3">
                <button id="cdAddProposalBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ Add
                    Proposal</button>
            </div>
            <div id="cdProposalsContainer" class="space-y-3"></div>
        </div>

        <div class="clientDetailPanel hidden" data-panel="contracts">
            <div id="cdContractsContainer" class="space-y-3"></div>
        </div>

    </div>
</x-modal>

{{-- Add Proposal side-modal --}}
<x-side-modal id="AddClientProposalModal">
    <div class="p-5 border-b flex justify-between items-center sticky top-0 bg-white z-10">
        <p class="text-lg font-semibold">New Proposal</p>
        <button class="modal-close">✕</button>
    </div>

    <div class="p-5">
        <div class="flex justify-between items-center mb-3">
            <p class="font-semibold text-zinc-700 text-sm">Container Lines</p>
            <button type="button" id="cpAddRowBtn"
                class="text-xs px-3 py-1.5 rounded-lg border bg-zinc-50 hover:bg-zinc-100">+ Add Container</button>
        </div>
        <div id="cpRatesContainer" class="space-y-3"></div>
    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white">
        <button type="button" class="modal-close px-4 py-2 text-sm rounded-lg border">Cancel</button>
        <button type="button" id="cpSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save Proposal</button>
    </div>
</x-side-modal>


<script>
    (function() {
        renderTable().load(1);
        loadCounts();

        function statusBadge(isComplete) {
            return isComplete ?
                `<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Complete</span>` :
                `<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-600"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Incomplete</span>`;
        }

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

        document.getElementById('btnNewClient').addEventListener('click', function() {
            window.clientMasterFormUuid = null;
            loadPage({
                title: 'New Client',
                link: '/page_clientMasterForm'
            });
        });

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

            return renderRemoteTable({
                url: '/api/clientMasters',
                tableId: 'tableClientMasters',
                thead: thead,
                afterRenderFunction: (row) => {
                    row.addEventListener('click', function() {
                        const data = JSON.parse(row.dataset.row);
                        if (data.is_complete) {
                            openClientDetailModal(data.uuid);
                        } else {
                            window.clientMasterFormUuid = data.uuid;
                            loadPage({
                                title: 'Edit Client',
                                link: '/page_clientMasterForm'
                            });
                        }
                    });
                },
            });
        }

        document.querySelectorAll('.clientStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.clientStatusBtn').forEach((c) => c.classList.remove(
                    'ring-2', 'ring-blue-500'));
                this.classList.add('ring-2', 'ring-blue-500');
                renderTable().setFilter('status', this.dataset.status);
            });
        });

        // ================= CLIENT DETAIL MODAL =================
        let currentClientUuid = null;

        async function openClientDetailModal(uuid) {
            currentClientUuid = uuid;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/${uuid}`
            });
            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to load this client.'
                });
                return;
            }

            const c = response.data;
            document.getElementById('cdClientName').textContent = c.company_name ?? '-';
            document.getElementById('cdClientCode').textContent = c.customer_code ?? '-';

            document.getElementById('cdInfoContainer').innerHTML = `
                <p><span class="text-zinc-400">Registered Address:</span> ${c.registered_address ?? '-'}</p>
                <p><span class="text-zinc-400">Contact No. 1:</span> ${c.contact_number_1 ?? '-'}</p>
                <p><span class="text-zinc-400">Contact No. 2:</span> ${c.contact_number_2 ?? '-'}</p>
                <p><span class="text-zinc-400">Industry:</span> ${c.industry ?? '-'}</p>
                <p><span class="text-zinc-400">Organization Type:</span> ${c.organization_type ?? '-'}</p>
                <p><span class="text-zinc-400">TIN:</span> ${c.tin ?? '-'}</p>
                <p><span class="text-zinc-400">Sales Rep:</span> ${c.sales_rep?.name ?? '-'}</p>
                <p><span class="text-zinc-400">Credit Terms:</span> ${c.finance?.credit_terms ?? '-'}</p>
                <p><span class="text-zinc-400">Payment Mode:</span> ${c.finance?.payment_mode ?? '-'}</p>
                <p><span class="text-zinc-400">Billed To:</span> ${c.billing?.billed_to ?? '-'}</p>
            `;

            loadProposals(uuid);
            loadContracts(uuid);

            initModal({
                modalId: 'ClientDetailModal'
            });
        }

        document.querySelectorAll('.clientDetailTab').forEach((btn) => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.clientDetailTab').forEach((b) => {
                    b.classList.remove('border-orange-500', 'text-orange-600');
                    b.classList.add('border-transparent', 'text-zinc-500');
                });
                this.classList.add('border-orange-500', 'text-orange-600');
                this.classList.remove('border-transparent', 'text-zinc-500');

                document.querySelectorAll('.clientDetailPanel').forEach((p) => {
                    p.classList.toggle('hidden', p.dataset.panel !== this.dataset.tab);
                });
            });
        });

        // ================= PROPOSALS LIST =================
        async function loadProposals(uuid) {
            const container = document.getElementById('cdProposalsContainer');
            container.innerHTML = `<p class="text-sm text-zinc-400">Loading...</p>`;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/${uuid}/proposals`
            });
            if (!response.success || !response.data.length) {
                container.innerHTML = `<p class="text-sm text-zinc-400 text-center py-6">No proposals yet.</p>`;
                return;
            }

            container.innerHTML = response.data.map((p) => `
                <div class="border rounded-xl p-4">
                    <div class="flex justify-between mb-2">
                        <p class="font-semibold text-sm">${p.code}</p>
                        <p class="text-xs text-zinc-400">${formatDateTime(p.created_at)}</p>
                    </div>
                    <table class="w-full text-xs">
                        <thead class="text-zinc-400 uppercase">
                            <tr>
                                <th class="text-left py-1">Route</th>
                                <th class="text-left py-1">Container</th>
                                <th class="text-right py-1">Base Rate</th>
                                <th class="text-right py-1">Discount</th>
                                <th class="text-right py-1">Final Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${p.rates.map((r) => `
                                <tr class="border-t">
                                    <td class="py-1.5">${r.origin_port?.code ?? '-'} → ${r.destination_port?.code ?? '-'}</td>
                                    <td class="py-1.5">${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</td>
                                    <td class="py-1.5 text-right">${Number(r.base_rate).toLocaleString()}</td>
                                    <td class="py-1.5 text-right">${r.discount_type ? (r.discount_type === 'percentage' ? r.discount_value + '%' : Number(r.discount_value).toLocaleString()) : '-'}</td>
                                    <td class="py-1.5 text-right font-semibold">${Number(r.final_rate).toLocaleString()}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `).join('');
        }

        document.getElementById('cdAddProposalBtn').addEventListener('click', function() {
            document.getElementById('cpRatesContainer').innerHTML = '';
            loadContainerLookups().then(() => addProposalRow());
            initSideModal({
                modalId: 'AddClientProposalModal'
            });
        });

        // ================= PROPOSAL ROW BUILDER =================
        let portsOptionsHtml = '';
        let containerVariantsData = [];

        async function loadContainerLookups() {
            const [portsRes, variantsRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containers/variants'
                }),
            ]);

            if (portsRes.success) {
                portsOptionsHtml = portsRes.data.data
                    .map((p) => `<option value="${p.port_id}">${p.code} - ${p.name}</option>`)
                    .join('');
            }
            if (variantsRes.success) {
                containerVariantsData = variantsRes.data;
            }
        }

        function uniqueContainerOptions() {
            const seen = new Set();
            return containerVariantsData
                .filter((v) => {
                    if (seen.has(v.container.id)) return false;
                    seen.add(v.container.id);
                    return true;
                })
                .map((v) => `<option value="${v.container.id}">${v.container.name}</option>`)
                .join('');
        }

        function addProposalRow() {
            const wrap = document.getElementById('cpRatesContainer');
            const div = document.createElement('div');
            div.className = 'border rounded-lg p-3 space-y-2';
            div.dataset.row = '';
            div.innerHTML = `
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Origin</label>
                        <select data-field="origin_port_id" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${portsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination</label>
                        <select data-field="destination_port_id" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${portsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Container</label>
                        <select data-field="container_id" class="container-select w-full border rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${uniqueContainerOptions()}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Class</label>
                        <select data-field="container_class_id" class="class-select w-full border rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select container first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Size</label>
                        <select data-field="container_size_id" class="size-select w-full border rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select class first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Rate (FRT)</label>
                        <input type="text" data-field="base_rate" readonly class="base-rate w-full border rounded-lg px-2 py-1.5 text-sm bg-zinc-50" value="0.00">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Discount Type</label>
                        <select data-field="discount_type" class="discount-type w-full border rounded-lg px-2 py-1.5 text-sm">
                            <option value="">None</option>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Discount Value</label>
                        <input type="number" step="0.01" min="0" data-field="discount_value" class="discount-value w-full border rounded-lg px-2 py-1.5 text-sm" value="0">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Final Rate</label>
                        <input type="text" data-field="final_rate" readonly class="final-rate w-full border rounded-lg px-2 py-1.5 text-sm bg-blue-50 font-semibold" value="0.00">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="remove-row text-red-500 text-xs">✕ Remove container</button>
                </div>
                <input type="hidden" data-field="container_variant_id">
            `;
            wrap.appendChild(div);
            wireRow(div);
        }

        function wireRow(row) {
            const originSel = row.querySelector('[data-field="origin_port_id"]');
            const destSel = row.querySelector('[data-field="destination_port_id"]');
            const containerSel = row.querySelector('.container-select');
            const classSel = row.querySelector('.class-select');
            const sizeSel = row.querySelector('.size-select');
            const variantInput = row.querySelector('[data-field="container_variant_id"]');
            const baseRateInput = row.querySelector('.base-rate');
            const discountTypeSel = row.querySelector('.discount-type');
            const discountValueInput = row.querySelector('.discount-value');
            const finalRateInput = row.querySelector('.final-rate');

            containerSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const classes = [...new Map(
                    containerVariantsData
                    .filter((v) => String(v.container.id) === containerId)
                    .map((v) => [v.container_class.id, v.container_class])
                ).values()];

                classSel.innerHTML = `<option value="">Select</option>` +
                    classes.map((c) => `<option value="${c.id}">${c.class}</option>`).join('');
                sizeSel.innerHTML = `<option value="">Select class first</option>`;
                variantInput.value = '';
                resetRate(baseRateInput, finalRateInput);
            });

            classSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const classId = classSel.value;
                const sizes = containerVariantsData.filter(
                    (v) => String(v.container.id) === containerId && String(v.container_class.id) ===
                    classId
                );

                sizeSel.innerHTML = `<option value="">Select</option>` +
                    sizes.map((v) =>
                        `<option value="${v.container_size.id}" data-variant-id="${v.id}">${v.container_size.size}</option>`
                        ).join('');
                variantInput.value = '';
                resetRate(baseRateInput, finalRateInput);
            });

            sizeSel.addEventListener('change', () => {
                const selected = sizeSel.options[sizeSel.selectedIndex];
                variantInput.value = selected?.dataset.variantId ?? '';
                lookupRate(row);
            });

            [originSel, destSel].forEach((sel) => sel.addEventListener('change', () => lookupRate(row)));
            discountTypeSel.addEventListener('change', () => recomputeFinalRate(row));
            discountValueInput.addEventListener('input', () => recomputeFinalRate(row));

            row.querySelector('.remove-row').addEventListener('click', () => row.remove());

            function resetRate(baseEl, finalEl) {
                baseEl.value = '0.00';
                finalEl.value = '0.00';
            }
        }

        async function lookupRate(row) {
            const originId = row.querySelector('[data-field="origin_port_id"]').value;
            const destId = row.querySelector('[data-field="destination_port_id"]').value;
            const variantId = row.querySelector('[data-field="container_variant_id"]').value;
            const baseRateInput = row.querySelector('.base-rate');

            if (!originId || !destId || !variantId) return;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/proposals/rateLookup?origin_port_id=${originId}&destination_port_id=${destId}&container_variant_id=${variantId}`,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Rate Not Found',
                    message: response.message ?? 'No rate configured for this combination.'
                });
                baseRateInput.value = '0.00';
                recomputeFinalRate(row);
                return;
            }

            baseRateInput.value = Number(response.data.frt).toFixed(2);
            recomputeFinalRate(row);
        }

        function recomputeFinalRate(row) {
            const base = parseFloat(row.querySelector('.base-rate').value) || 0;
            const type = row.querySelector('.discount-type').value;
            const value = parseFloat(row.querySelector('.discount-value').value) || 0;
            const finalRateInput = row.querySelector('.final-rate');

            let final = base;
            if (type === 'percentage') final = base - (base * value / 100);
            if (type === 'fixed') final = Math.max(0, base - value);

            finalRateInput.value = final.toFixed(2);
        }

        document.getElementById('cpAddRowBtn').addEventListener('click', addProposalRow);

        document.getElementById('cpSaveBtn').addEventListener('click', async function() {
            const rows = Array.from(document.querySelectorAll('#cpRatesContainer [data-row]'));

            if (!rows.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one container line.'
                });
                return;
            }

            const rates = rows.map((row) => ({
                origin_port_id: row.querySelector('[data-field="origin_port_id"]').value,
                destination_port_id: row.querySelector('[data-field="destination_port_id"]')
                    .value,
                container_id: row.querySelector('[data-field="container_id"]').value,
                container_class_id: row.querySelector('[data-field="container_class_id"]')
                    .value,
                container_size_id: row.querySelector('[data-field="container_size_id"]')
                    .value,
                container_variant_id: row.querySelector(
                    '[data-field="container_variant_id"]').value,
                base_rate: parseFloat(row.querySelector('.base-rate').value) || 0,
                discount_type: row.querySelector('.discount-type').value || null,
                discount_value: parseFloat(row.querySelector('.discount-value').value) || 0,
                final_rate: parseFloat(row.querySelector('.final-rate').value) || 0,
            }));

            if (rates.some((r) => !r.origin_port_id || !r.destination_port_id || !r
                    .container_variant_id)) {
                showMessage({
                    status: 'error',
                    title: 'Incomplete',
                    message: 'Complete origin, destination, and container for every line.'
                });
                return;
            }

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    rates
                },
                url: `/api/clientMasters/${currentClientUuid}/proposals`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving Proposal'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Proposal saved!'
            });
            closeSideModal('AddClientProposalModal');
            loadProposals(currentClientUuid);
        });

        // ================= CONTRACTS LIST (basic) =================
        async function loadContracts(uuid) {
            const container = document.getElementById('cdContractsContainer');
            container.innerHTML = `<p class="text-sm text-zinc-400">Loading...</p>`;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientMasters/${uuid}/contracts`
            });
            if (!response.success || !response.data.length) {
                container.innerHTML = `<p class="text-sm text-zinc-400 text-center py-6">No contracts yet.</p>`;
                return;
            }

            container.innerHTML = response.data.map((c) => `
                <div class="border rounded-xl p-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-sm">${c.code}</p>
                        <p class="text-xs text-zinc-400">${c.valid_from} → ${c.valid_to}</p>
                    </div>
                    <p class="text-xs text-zinc-500">${c.rates.length} rate line(s)</p>
                </div>
            `).join('');
        }
    })();
</script>
