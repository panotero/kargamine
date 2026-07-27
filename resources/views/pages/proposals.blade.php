{{-- resources/views/pages/proposals.blade.php --}}
<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Proposals</h1>
            <p class="text-zinc-500">Review, approve, and track client proposals</p>
        </div>
    </div>

    {{-- Status filter tabs --}}
    <div class="flex gap-2 flex-wrap px-2 mb-3">
        <button class="proposalStatusBtn px-3 py-1.5 text-sm rounded-lg border ring-2 ring-orange-500"
            data-status="all">All</button>
        <button class="proposalStatusBtn px-3 py-1.5 text-sm rounded-lg border" data-status="1">Pending</button>
        <button class="proposalStatusBtn px-3 py-1.5 text-sm rounded-lg border" data-status="2">Approved</button>
        <button class="proposalStatusBtn px-3 py-1.5 text-sm rounded-lg border" data-status="3">Disapproved</button>
        <button class="proposalStatusBtn px-3 py-1.5 text-sm rounded-lg border" data-status="4">Accepted</button>
        <button class="proposalStatusBtn px-3 py-1.5 text-sm rounded-lg border" data-status="5">Rejected</button>
    </div>

    <x-table id="tableClientProposals" />
</div>

{{-- Detail / action modal --}}
<x-modal id="ClientProposalModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold" id="cpmCode">-</p>
                <span id="cpmStatusBadge"></span>
            </div>
            <p class="text-xs text-zinc-400" id="cpmClientName">-</p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="max-h-[65vh] overflow-y-auto p-5 space-y-5">

        <div id="cpmDecisionInfo" class="text-xs text-zinc-500 hidden"></div>

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
            <tbody id="cpmRatesBody"></tbody>
        </table>

        {{-- Attach signed document - only shown when APPROVED --}}
        <div id="cpmSignedSection" class="hidden border-t pt-4">
            <p class="font-semibold text-sm text-zinc-700 mb-2">Attach Signed Proposal</p>
            <div class="flex items-center gap-2">
                <input type="file" id="cpmSignedFile" accept=".pdf,.jpg,.jpeg,.png"
                    class="flex-1 border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
                <button id="cpmUploadSignedBtn"
                    class="px-4 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white shrink-0">
                    Upload & Accept
                </button>
            </div>
        </div>
    </div>

    <div class="border-t px-5 py-4 flex justify-between items-center gap-2">
        <div class="flex gap-2">
            <a href="#" id="cpmDownloadLink" target="_blank"
                class="hidden px-4 py-2 text-sm rounded-lg border hover:bg-zinc-50">Download</a>
            <button id="cpmCreateContractBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                Create Contract
            </button>
            <button id="cpmViewContractBtn"
                class="hidden px-4 py-2 text-sm rounded-lg border hover:bg-zinc-50">
                View Contract
            </button>
            <button id="cpmCreateClientMasterBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                Create Client Master
            </button>
        </div>
        <div class="flex gap-2">
            <button id="cpmRejectBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-red-600 hover:bg-red-700 text-white">Reject</button>
            <button id="cpmDisapproveBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-amber-500 hover:bg-amber-600 text-white">Disapprove</button>
            <button id="cpmApproveBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white">Approve</button>
        </div>
    </div>
</x-modal>

{{-- Create Contract modal (opened from an Accepted proposal) --}}
<x-modal id="createContractModal">
    <div class="p-5 border-b flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold">Create Contract</p>
            <p class="text-xs text-zinc-400">From proposal <span id="ccProposalCode">-</span> &middot; <span id="ccClientName">-</span></p>
        </div>
        <button class="modal-close">✕</button>
    </div>

    <div class="max-h-[65vh] overflow-y-auto p-5 space-y-5">
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Valid From</label>
                <input type="date" id="ccValidFrom" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Valid To</label>
                <input type="date" id="ccValidTo" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-zinc-500 uppercase mb-1">Signed Date</label>
                <input type="date" id="ccSignedDate" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:text-zinc-900">
            </div>
        </div>

        <div>
            <p class="font-semibold text-sm text-zinc-700 mb-2">Rate Lines</p>
            <p class="text-xs text-zinc-400 mb-2">Copied from the accepted proposal. Click <span class="font-medium">✎</span> on a line to correct it before saving.</p>
            <table class="w-full text-xs">
                <thead class="text-zinc-400 uppercase">
                    <tr>
                        <th class="text-left py-1 px-2">Route</th>
                        <th class="text-left py-1 px-2">Container</th>
                        <th class="text-right py-1 px-2">Base Rate</th>
                        <th class="text-right py-1 px-2">Discount</th>
                        <th class="text-right py-1 px-2">Final Rate</th>
                        <th class="py-1 px-2"></th>
                    </tr>
                </thead>
                <tbody id="ccRatesBody" class="divide-y divide-zinc-100"></tbody>
            </table>
        </div>
    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">
        <button class="modal-close border px-4 py-2 rounded-lg text-sm">Cancel</button>
        <button id="ccSaveBtn" class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
            Create Contract
        </button>
    </div>
</x-modal>

<script>
    (function() {
        const STATUS_LABEL = {
            1: 'Pending',
            2: 'Approved',
            3: 'Disapproved',
            4: 'Accepted',
            5: 'Rejected'
        };
        const STATUS_BADGE = {
            1: 'bg-amber-100 text-amber-600',
            2: 'bg-green-100 text-green-700',
            3: 'bg-red-100 text-red-600',
            4: 'bg-blue-100 text-blue-700',
            5: 'bg-zinc-200 text-zinc-600',
        };

        let currentProposalId = null;
        let currentProposalLead = null;
        let currentProposalDetail = null;
        let activeStatusFilter = 'all';

        // Create Contract modal state
        let rateOverrides = {};
        let editingRateId = null;

        renderTable().load(1);

        function statusPill(status) {
            return `<span class="text-xs font-semibold px-2.5 py-1 rounded-full ${STATUS_BADGE[status] ?? 'bg-zinc-100 text-zinc-500'}">${STATUS_LABEL[status] ?? 'Unknown'}</span>`;
        }

        function renderTable() {
            const thead = [{
                    title: 'Code',
                    key: 'code'
                },
                {
                    title: 'Client',
                    key: 'client.company_name',
                    render: (r) => r.client?.company_name ?? '-'
                },
                {
                    title: 'Customer Code',
                    key: 'client.customer_code',
                    render: (r) => r.client?.customer_code ?? '-'
                },
                {
                    title: 'Status',
                    key: 'status',
                    render: (r) => statusPill(r.status)
                },
                {
                    title: 'Decided By',
                    key: 'decided_by.name',
                    render: (r) => r.decided_by?.name ?? '-'
                },
                {
                    title: 'Created',
                    key: 'created_at',
                    render: (r) => formatDateTime(r.created_at)
                },
            ];

            const table = renderRemoteTable({
                url: '/api/clientProposals',
                tableId: 'tableClientProposals',
                afterRenderFunction: (row) => row.addEventListener('click', function() {
                    openProposalModal(JSON.parse(row.dataset.row).id);
                }),
                thead: thead,
            });

            return table;
        }

        document.querySelectorAll('.proposalStatusBtn').forEach((btn) => {
            btn.addEventListener('click', function() {
                activeStatusFilter = this.dataset.status;
                document.querySelectorAll('.proposalStatusBtn').forEach((b) => b.classList.remove(
                    'ring-2', 'ring-orange-500'));
                this.classList.add('ring-2', 'ring-orange-500');
                renderTable().setFilter('status', activeStatusFilter);
            });
        });

        async function openProposalModal(id) {
            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientProposals/${id}`
            });
            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to load this proposal.'
                });
                return;
            }

            const p = response.data;
            currentProposalId = p.id;
            currentProposalLead = p.lead ?? null;
            currentProposalDetail = p;

            document.getElementById('cpmCode').textContent = p.code;
            document.getElementById('cpmClientName').textContent =
                `${p.client?.company_name ?? '-'} (${p.client?.customer_code ?? '-'})`;
            document.getElementById('cpmStatusBadge').innerHTML = statusPill(p.status);

            const decisionInfo = document.getElementById('cpmDecisionInfo');
            if (p.decided_by) {
                decisionInfo.textContent =
                    `${STATUS_LABEL[p.status]} by ${p.decided_by.name} on ${formatDateTime(p.decided_at)}${p.decision_remarks ? ' — ' + p.decision_remarks : ''}`;
                decisionInfo.classList.remove('hidden');
            } else {
                decisionInfo.classList.add('hidden');
            }

            document.getElementById('cpmRatesBody').innerHTML = p.rates.map((r) => `
                <tr class="border-t">
                    <td class="py-1.5">${r.origin_port?.code ?? '-'} → ${r.destination_port?.code ?? '-'}</td>
                    <td class="py-1.5">${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</td>
                    <td class="py-1.5 text-right">${Number(r.base_rate).toLocaleString()}</td>
                    <td class="py-1.5 text-right">${r.discount_type ? (r.discount_type === 'percentage' ? r.discount_value + '%' : Number(r.discount_value).toLocaleString()) : '-'}</td>
                    <td class="py-1.5 text-right font-semibold">${Number(r.final_rate).toLocaleString()}</td>
                </tr>
            `).join('');

            // Buttons are permission-gated server-side (p.can_approve / p.can_reject)
            // AND status-gated here - both checks matter, one is authorization,
            // the other is workflow state.
            toggle('cpmApproveBtn', p.status === 1 && p.can_approve);
            toggle('cpmDisapproveBtn', p.status === 1 && p.can_approve);
            toggle('cpmRejectBtn', [1, 2].includes(p.status) && p.can_reject);
            toggle('cpmSignedSection', p.status === 2);
            toggle('cpmDownloadLink', [2, 4].includes(p.status));
            // A lead-scoped accepted proposal (no client yet) has nowhere to
            // attach a contract - it needs to become a client first.
            const hasActiveContract = Boolean(p.active_contract);
            toggle('cpmCreateContractBtn', p.status === 4 && Boolean(p.client_id) && !hasActiveContract);
            toggle('cpmViewContractBtn', hasActiveContract);
            toggle('cpmCreateClientMasterBtn', p.status === 4 && !p.client_id && Boolean(p.lead_id));

            if ([2, 4].includes(p.status)) {
                document.getElementById('cpmDownloadLink').href = `/api/clientProposals/${p.id}/pdf`;
            }

            initModal({
                modalId: 'ClientProposalModal'
            });
        }

        function toggle(id, visible) {
            document.getElementById(id).classList.toggle('hidden', !visible);
        }

        async function decisionAction(action, successMessage) {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/clientProposals/${currentProposalId}/${action}`,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: response.message ?? 'Action failed.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: successMessage
            });
            closemodals();
            renderTable().reload();
        }

        document.getElementById('cpmApproveBtn').addEventListener('click', () => decisionAction('approve',
            'Proposal approved'));
        document.getElementById('cpmDisapproveBtn').addEventListener('click', () => decisionAction('disapprove',
            'Proposal disapproved'));
        document.getElementById('cpmRejectBtn').addEventListener('click', async () => {
            const confirmed = await customConfirm('Reject this proposal? This cannot be undone.');
            if (confirmed) decisionAction('reject', 'Proposal rejected');
        });

        document.getElementById('cpmUploadSignedBtn').addEventListener('click', async function() {
            const fileInput = document.getElementById('cpmSignedFile');
            if (!fileInput.files.length) {
                showMessage({
                    status: 'error',
                    title: 'Select a file first'
                });
                return;
            }

            const formData = new FormData();
            formData.append('signed_document', fileInput.files[0]);

            const response = await apiCall({
                mode: 'POST',
                isJson: false,
                payload: formData,
                url: `/api/clientProposals/${currentProposalId}/attachSigned`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: response.message ?? 'Upload failed.'
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Signed document uploaded — proposal accepted!'
            });
            closemodals();
            renderTable().reload();
        });

        document.getElementById('cpmCreateContractBtn').addEventListener('click', function() {
            rateOverrides = {};
            editingRateId = null;

            document.getElementById('ccProposalCode').textContent = currentProposalDetail.code;
            document.getElementById('ccClientName').textContent = currentProposalDetail.client?.company_name ?? '-';
            document.getElementById('ccValidFrom').value = '';
            document.getElementById('ccValidTo').value = '';
            document.getElementById('ccSignedDate').value = '';
            renderContractRatesTable();

            initModal({ modalId: 'createContractModal' });
        });

        document.getElementById('cpmViewContractBtn').addEventListener('click', function() {
            window.contractsOpenId = currentProposalDetail?.active_contract?.id ?? null;
            closemodals();
            loadPage({ title: 'Contracts', link: '/page_contracts' });
        });

        // -----------------------------------------------------------------
        // Create Contract modal - rate lines are copied from the proposal
        // read-only by default; a pencil unlocks a row for a confirmed edit.
        // -----------------------------------------------------------------
        function ccOriginalValues(rate) {
            return {
                base_rate: Number(rate.base_rate),
                discount_type: rate.discount_type ?? null,
                discount_value: Number(rate.discount_value ?? 0),
                final_rate: Number(rate.final_rate),
            };
        }

        function ccCurrentValues(rate) {
            return rateOverrides[rate.id] ? { ...rateOverrides[rate.id] } : ccOriginalValues(rate);
        }

        function ccDiscountDisplay(values) {
            if (!values.discount_type) return '-';
            return values.discount_type === 'percentage' ?
                `${values.discount_value}%` :
                Number(values.discount_value).toLocaleString();
        }

        function renderRateRow(rate, editing) {
            const lane = `${rate.origin_port?.code ?? '-'} → ${rate.destination_port?.code ?? '-'}`;
            const variant = `${rate.container?.name ?? '-'} / ${rate.container_class?.class ?? '-'} / ${rate.container_size?.size ?? '-'}`;
            const values = ccCurrentValues(rate);
            const edited = Boolean(rateOverrides[rate.id]);

            if (!editing) {
                return `
                    <tr data-rate-id="${rate.id}">
                        <td class="py-1.5 px-2">${lane}</td>
                        <td class="py-1.5 px-2">${variant}</td>
                        <td class="py-1.5 px-2 text-right">${Number(values.base_rate).toLocaleString()}</td>
                        <td class="py-1.5 px-2 text-right">${ccDiscountDisplay(values)}</td>
                        <td class="py-1.5 px-2 text-right font-semibold">
                            ${Number(values.final_rate).toLocaleString()}
                            ${edited ? '<span class="ml-1 text-[10px] font-normal text-amber-600">(edited)</span>' : ''}
                        </td>
                        <td class="py-1.5 px-2 text-right">
                            <button type="button" class="cc-edit-btn text-zinc-400 hover:text-zinc-700" title="Edit this rate">✎</button>
                        </td>
                    </tr>`;
            }

            return `
                <tr data-rate-id="${rate.id}">
                    <td class="py-1.5 px-2">${lane}</td>
                    <td class="py-1.5 px-2">${variant}</td>
                    <td class="py-1.5 px-2">
                        <input type="number" step="0.01" min="0" class="cc-input-base w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.base_rate}">
                    </td>
                    <td class="py-1.5 px-2">
                        <div class="flex items-center gap-1 justify-end">
                            <select class="cc-input-disctype border rounded px-1 py-1 text-xs dark:text-zinc-900">
                                <option value="" ${!values.discount_type ? 'selected' : ''}>None</option>
                                <option value="percentage" ${values.discount_type === 'percentage' ? 'selected' : ''}>%</option>
                                <option value="fixed" ${values.discount_type === 'fixed' ? 'selected' : ''}>Fixed</option>
                            </select>
                            <input type="number" step="0.01" min="0" class="cc-input-discval w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.discount_value}">
                        </div>
                    </td>
                    <td class="py-1.5 px-2">
                        <input type="number" step="0.01" min="0" class="cc-input-final w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.final_rate}">
                    </td>
                    <td class="py-1.5 px-2 text-right whitespace-nowrap">
                        <button type="button" class="cc-apply-btn text-green-600 hover:text-green-700" title="Apply">✓</button>
                        <button type="button" class="cc-cancel-btn text-zinc-400 hover:text-zinc-700" title="Cancel">✕</button>
                    </td>
                </tr>`;
        }

        function renderContractRatesTable() {
            document.getElementById('ccRatesBody').innerHTML = (currentProposalDetail?.rates ?? [])
                .map((r) => renderRateRow(r, editingRateId === r.id))
                .join('');
        }

        // Base rate / discount type / discount value all feed Final Rate, same
        // as the Add Proposal / Add Contract row builders elsewhere - without
        // this, editing the discount changed nothing the user could see or
        // save, since Final Rate is what actually gets sent as the override.
        function recomputeCcFinalRate(row) {
            const base = parseFloat(row.querySelector('.cc-input-base').value) || 0;
            const type = row.querySelector('.cc-input-disctype').value;
            const value = parseFloat(row.querySelector('.cc-input-discval').value) || 0;
            const finalInput = row.querySelector('.cc-input-final');

            let final = base;
            if (type === 'percentage') final = base - (base * value / 100);
            if (type === 'fixed') final = Math.max(0, base - value);

            finalInput.value = final.toFixed(2);
        }

        document.getElementById('ccRatesBody').addEventListener('input', function(e) {
            if (e.target.matches('.cc-input-base, .cc-input-discval')) {
                recomputeCcFinalRate(e.target.closest('tr'));
            }
        });

        document.getElementById('ccRatesBody').addEventListener('change', function(e) {
            if (e.target.matches('.cc-input-disctype')) {
                recomputeCcFinalRate(e.target.closest('tr'));
            }
        });

        document.getElementById('ccRatesBody').addEventListener('click', async function(e) {
            const row = e.target.closest('tr');
            if (!row) return;

            const rateId = Number(row.dataset.rateId);
            const rate = (currentProposalDetail?.rates ?? []).find((r) => r.id === rateId);
            if (!rate) return;

            if (e.target.closest('.cc-edit-btn')) {
                editingRateId = rateId;
                renderContractRatesTable();
                return;
            }

            if (e.target.closest('.cc-cancel-btn')) {
                editingRateId = null;
                renderContractRatesTable();
                return;
            }

            if (e.target.closest('.cc-apply-btn')) {
                const newValues = {
                    base_rate: Number(row.querySelector('.cc-input-base').value),
                    discount_type: row.querySelector('.cc-input-disctype').value || null,
                    discount_value: Number(row.querySelector('.cc-input-discval').value || 0),
                    final_rate: Number(row.querySelector('.cc-input-final').value),
                };
                const original = ccOriginalValues(rate);
                const changed = newValues.base_rate !== original.base_rate ||
                    newValues.discount_type !== original.discount_type ||
                    newValues.discount_value !== original.discount_value ||
                    newValues.final_rate !== original.final_rate;

                if (changed) {
                    const confirmed = await customConfirm('Apply this rate change?');
                    if (!confirmed) return;
                    rateOverrides[rateId] = newValues;
                } else {
                    delete rateOverrides[rateId];
                }

                editingRateId = null;
                renderContractRatesTable();
            }
        });

        document.getElementById('ccSaveBtn').addEventListener('click', async function() {
            const validFrom = document.getElementById('ccValidFrom').value;
            const validTo = document.getElementById('ccValidTo').value;

            if (!validFrom || !validTo) {
                showMessage({ status: 'error', title: 'Valid From and Valid To are required' });
                return;
            }

            const payload = {
                signed_date: document.getElementById('ccSignedDate').value || null,
                valid_from: validFrom,
                valid_to: validTo,
                rate_overrides: rateOverrides,
            };

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/clientProposals/${currentProposalDetail.id}/contract`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Unable to create contract',
                    message: response.message ?? '',
                });
                return;
            }

            showMessage({ status: 'success', title: 'Contract created' });
            closemodals();
            await openProposalModal(currentProposalDetail.id);
            renderTable().reload();
        });

        document.getElementById('cpmCreateClientMasterBtn').addEventListener('click', async function() {
            const lead = currentProposalLead;
            if (!lead) return;

            const codeResponse = await apiCall({
                mode: 'GET',
                url: `/api/crm/leads/${lead.uuid}/customerCode`,
                button: this,
            });

            if (!codeResponse.success) {
                showMessage({
                    status: 'error',
                    title: 'Error',
                    message: 'Unable to generate a customer code for this lead.'
                });
                return;
            }

            window.clientMasterFormUuid = null;
            window.clientMasterFormLeadId = lead.id;
            window.clientMasterFormPrefill = {
                customer_code: codeResponse.data.customer_code,
                company_name: lead.company?.company_name ?? '',
                industry: lead.company?.type_of_business ?? '',
                contact_number_1: lead.mobile ?? '',
                addresses: lead.addresses ?? [],
            };

            closemodals();
            loadPage({
                title: 'New Client Master Data',
                link: '/page_clientMasterForm'
            });
        });
    })();
</script>
