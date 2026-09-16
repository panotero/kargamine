{{-- resources/views/pages/proposals.blade.php --}}
<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Proposals</h1>
            <p class="text-zinc-500">Review, approve, and track client proposals</p>
        </div>
        <div class="text-right">
            <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400">Awaiting Your Decision</p>
            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100" id="countAwaitingDecision">0</p>
        </div>
    </div>

    {{-- Status workflow strip --}}
    <section class="w-full my-5">
        <div class="flex items-center flex-wrap gap-2">
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed border-zinc-300 text-zinc-500 dark:border-zinc-600 dark:text-zinc-400"
                data-status="all">
                <span>All</span>
                <span id="countAll">0</span>
            </div>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-amber-600 border-amber-300 dark:text-amber-400 dark:border-amber-700"
                data-status="1">
                <span>Pending</span>
                <span id="countPending">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-green-600 border-green-300 dark:text-green-400 dark:border-green-700"
                data-status="2">
                <span>Approved</span>
                <span id="countApproved">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-blue-600 border-blue-300 dark:text-blue-400 dark:border-blue-700"
                data-status="4">
                <span>Accepted</span>
                <span id="countAccepted">0</span>
            </div>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-red-600 border-red-300 dark:text-red-400 dark:border-red-700"
                data-status="3">
                <span>Disapproved</span>
                <span id="countDisapproved">0</span>
            </div>
            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700"></div>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-zinc-500 border-zinc-300 dark:text-zinc-400 dark:border-zinc-600"
                data-status="5">
                <span>Rejected</span>
                <span id="countRejected">0</span>
            </div>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-zinc-500 border-zinc-300 dark:text-zinc-400 dark:border-zinc-600"
                data-status="6">
                <span>Cancelled</span>
                <span id="countCancelled">0</span>
            </div>
        </div>
    </section>

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

        {{-- Basic lead information --}}
        <div id="cpmLeadInfo" class="border rounded-lg p-3 hidden">
            <div class="flex items-center gap-2 mb-2">
                <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-widest">Lead Information</p>
                <span id="cpmLeadInfoTag"
                    class="hidden text-[10px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400">
                    Lead-Scoped &middot; No Client Master Yet
                </span>
            </div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs">
                <div><span class="text-zinc-400">Contact:</span> <span id="cpmLeadContact" class="font-medium">-</span></div>
                <div><span class="text-zinc-400">Company:</span> <span id="cpmLeadCompany" class="font-medium">-</span></div>
                <div><span class="text-zinc-400">Mobile:</span> <span id="cpmLeadMobile" class="font-medium">-</span></div>
                <div><span class="text-zinc-400">Email:</span> <span id="cpmLeadEmail" class="font-medium">-</span></div>
                <div><span class="text-zinc-400">Source:</span> <span id="cpmLeadSource" class="font-medium">-</span></div>
                <div><span class="text-zinc-400">Assigned To:</span> <span id="cpmLeadAssignedTo" class="font-medium">-</span></div>
            </div>
        </div>

        <table class="w-full text-xs">
            <thead class="text-zinc-400 uppercase">
                <tr>
                    <th class="text-left py-1">Route</th>
                    <th class="text-left py-1">Container</th>
                    <th class="text-right py-1">Min Qty</th>
                    <th class="text-right py-1">Base Rate</th>
                    <th class="text-right py-1">Adjustment</th>
                    <th class="text-right py-1">Final Rate</th>
                </tr>
            </thead>
            <tbody id="cpmRatesBody"></tbody>
        </table>

        {{-- Additional Charges - proposal-wide opt-ins, hidden entirely when none apply --}}
        <div id="cpmAdditionalCharges" class="hidden">
            <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-widest mb-1.5">Additional Charges</p>
            <div class="flex flex-wrap gap-1.5"></div>
        </div>

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
                class="hidden px-4 py-2 text-sm rounded-lg border hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800 dark:text-zinc-300">Download</a>
            <button id="cpmCreateContractBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                Create Contract
            </button>
            <button id="cpmViewContractBtn"
                class="hidden px-4 py-2 text-sm rounded-lg border hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800 dark:text-zinc-300">
                View Contract
            </button>
            <button id="cpmCreateClientMasterBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
                Create Client Master
            </button>
        </div>
        <div class="flex items-center gap-2">
            <button id="cpmCancelBtn"
                class="hidden px-4 py-2 text-sm rounded-lg border border-zinc-300 text-zinc-600 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800">Cancel Proposal</button>
            <button id="cpmDisapproveBtn"
                class="hidden px-4 py-2 text-sm rounded-lg border border-amber-400 text-amber-600 hover:bg-amber-50 dark:border-amber-700 dark:text-amber-400 dark:hover:bg-amber-950/30">Disapprove</button>
            <button id="cpmApproveBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-green-600 hover:bg-green-700 text-white">Approve</button>
            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700"></div>
            <button id="cpmRejectBtn"
                class="hidden px-4 py-2 text-sm rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-950/30 dark:text-red-400 dark:hover:bg-red-950/50">Reject</button>
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
                        <th class="text-right py-1 px-2">Min Qty</th>
                        <th class="text-right py-1 px-2">Base Rate</th>
                        <th class="text-right py-1 px-2">Adjustment</th>
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
            5: 'Rejected',
            6: 'Cancelled'
        };
        const STATUS_BADGE = {
            1: 'bg-amber-100 text-amber-600',
            2: 'bg-green-100 text-green-700',
            3: 'bg-red-100 text-red-600',
            4: 'bg-blue-100 text-blue-700',
            5: 'bg-zinc-200 text-zinc-600',
            6: 'bg-zinc-200 text-zinc-600',
        };

        let currentProposalId = null;
        let currentProposalLead = null;
        let currentProposalDetail = null;
        let activeStatusFilter = 'all';

        // Create Contract modal state
        let rateOverrides = {};
        let editingRateId = null;

        loadProposals();

        async function loadProposals() {
            const response = await apiCall({
                mode: 'GET',
                url: '/api/clientProposals',
            });

            if (!response.success) return;

            updateProposalCounts(response.status_counts);
            renderTable().load(1);
        }

        function updateProposalCounts(counts) {
            document.getElementById('countAll').textContent = counts.all;
            document.getElementById('countPending').textContent = counts.pending;
            document.getElementById('countApproved').textContent = counts.approved;
            document.getElementById('countDisapproved').textContent = counts.disapproved;
            document.getElementById('countAccepted').textContent = counts.accepted;
            document.getElementById('countRejected').textContent = counts.rejected;
            document.getElementById('countCancelled').textContent = counts.cancelled ?? 0;
            document.getElementById('countAwaitingDecision').textContent = counts.awaiting_decision ?? 0;
        }

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
                emptyMessage: () => {
                    if (activeStatusFilter === 'all') return 'No proposals yet.';
                    const label = (STATUS_LABEL[activeStatusFilter] ?? '').toLowerCase();
                    return `No ${label} proposals.`;
                },
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

        // Exposed globally so notificationController.js's data.modal_fn hook
        // (fired after navigating here from a proposal notification) can
        // call it directly by name once this page's script has run.
        window.openProposalModal = openProposalModal;

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

            // Client-scoped proposals don't carry lead_id directly - fall back
            // to the client's originating lead, same as ClientProposal::ownerUser().
            const leadInfo = p.lead ?? p.client?.lead ?? null;
            const leadInfoEl = document.getElementById('cpmLeadInfo');
            if (leadInfo) {
                document.getElementById('cpmLeadContact').textContent =
                    leadInfo.position ? `${leadInfo.contact_name} (${leadInfo.position})` : (leadInfo.contact_name ?? '-');
                document.getElementById('cpmLeadCompany').textContent = leadInfo.company?.company_name ?? '-';
                document.getElementById('cpmLeadMobile').textContent = leadInfo.mobile ?? '-';
                document.getElementById('cpmLeadEmail').textContent = leadInfo.email ?? '-';
                document.getElementById('cpmLeadSource').textContent = leadInfo.source ?? '-';
                document.getElementById('cpmLeadAssignedTo').textContent = leadInfo.user?.name ?? '-';
                document.getElementById('cpmLeadInfoTag').classList.toggle('hidden', Boolean(p.client_id));
                leadInfoEl.classList.remove('hidden');
            } else {
                leadInfoEl.classList.add('hidden');
            }

            const decisionInfo = document.getElementById('cpmDecisionInfo');
            if (p.decided_by) {
                decisionInfo.textContent =
                    `${STATUS_LABEL[p.status]} by ${p.decided_by.name} on ${formatDateTime(p.decided_at)}${p.decision_remarks ? ' — ' + p.decision_remarks : ''}`;
                decisionInfo.classList.remove('hidden');
            } else {
                decisionInfo.classList.add('hidden');
            }

            document.getElementById('cpmRatesBody').innerHTML = p.rates.map((r) => {
                const ancillary = r.ancillary_services ?? [];
                const ancillaryRow = ancillary.length ? `
                    <tr class="border-t">
                        <td colspan="6" class="py-1 text-[11px] text-zinc-500">
                            <span class="font-semibold">Ancillary Services:</span>
                            ${ancillary.map((s) => `${s.required_service ?? '-'}${s.quantity ? ' x' + s.quantity : ''}${s.unit ? ' ' + s.unit : ''}${s.location ? ' (' + s.location + ')' : ''}`).join('; ')}
                        </td>
                    </tr>` : '';

                return `
                <tr class="border-t">
                    <td class="py-1.5">
                        ${r.origin_port ? (r.origin_port.location?.name ?? '-') + ' - ' + r.origin_port.name : '-'}${r.origin_pickup_area ? ` <span class="text-zinc-400">(Pickup: ${r.origin_pickup_area.area_name})</span>` : ''}
                        → ${r.destination_port ? (r.destination_port.location?.name ?? '-') + ' - ' + r.destination_port.name : '-'}${r.destination_pickup_area ? ` <span class="text-zinc-400">(Drop-off: ${r.destination_pickup_area.area_name})</span>` : ''}
                    </td>
                    <td class="py-1.5">${r.container?.name ?? '-'} / ${r.container_class?.class ?? '-'} / ${r.container_size?.size ?? '-'}</td>
                    <td class="py-1.5 text-right">${r.min_van_qty ?? '-'}</td>
                    <td class="py-1.5 text-right">${Number(r.base_rate).toLocaleString()}</td>
                    <td class="py-1.5 text-right">${adjustmentDisplay(r.discount_type, r.discount_value)}</td>
                    <td class="py-1.5 text-right font-semibold">${Number(r.final_rate).toLocaleString()}</td>
                </tr>${ancillaryRow}`;
            }).join('');

            const additionalCharges = [
                ['include_special_charges', 'Special Charges'],
                ['include_port_charges', 'Port Charges'],
                ['include_handling_fee', 'Handling Fee'],
                ['include_general_charges', 'General Charges'],
            ].filter(([field]) => p[field]);
            const chargesEl = document.getElementById('cpmAdditionalCharges');
            chargesEl.classList.toggle('hidden', additionalCharges.length === 0);
            chargesEl.querySelector('div').innerHTML = additionalCharges.map(([, label]) => `
                <span class="text-[11px] font-semibold px-2 py-1 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400">${label}</span>
            `).join('');

            // Buttons are permission-gated server-side (p.can_approve / p.can_reject)
            // AND status-gated here - both checks matter, one is authorization,
            // the other is workflow state.
            toggle('cpmApproveBtn', p.status === 1 && p.can_approve);
            toggle('cpmDisapproveBtn', p.status === 1 && p.can_approve);
            toggle('cpmRejectBtn', [1, 2].includes(p.status) && p.can_reject);
            toggle('cpmCancelBtn', p.status === 1 && p.can_cancel);
            toggle('cpmSignedSection', p.status === 2 && p.can_upload_signed);
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

        async function decisionAction(action, successMessage, button) {
            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {},
                url: `/api/clientProposals/${currentProposalId}/${action}`,
                button,
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

        document.getElementById('cpmApproveBtn').addEventListener('click', function() {
            decisionAction('approve', 'Proposal approved', this);
        });
        document.getElementById('cpmDisapproveBtn').addEventListener('click', function() {
            decisionAction('disapprove', 'Proposal disapproved', this);
        });
        document.getElementById('cpmRejectBtn').addEventListener('click', async function() {
            const confirmed = await customConfirm('Reject this proposal? This cannot be undone.');
            if (confirmed) decisionAction('reject', 'Proposal rejected', this);
        });
        document.getElementById('cpmCancelBtn').addEventListener('click', async function() {
            const confirmed = await customConfirm('Cancel this pending proposal? This cannot be undone.');
            if (confirmed) decisionAction('cancel', 'Proposal cancelled', this);
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
                min_van_qty: rate.min_van_qty ?? null,
                base_rate: Number(rate.base_rate),
                discount_type: rate.discount_type ?? null,
                discount_value: Number(rate.discount_value ?? 0),
                final_rate: Number(rate.final_rate),
            };
        }

        function ccCurrentValues(rate) {
            return rateOverrides[rate.id] ? { ...rateOverrides[rate.id] } : ccOriginalValues(rate);
        }

        function adjustmentDisplay(type, value) {
            if (!type) return '-';
            const labels = {
                percentage: 'Discount',
                fixed: 'Discount',
                increase_percentage: 'Increase',
                increase_fixed: 'Increase',
            };
            const isPercent = type === 'percentage' || type === 'increase_percentage';
            const amount = isPercent ? `${value}%` : Number(value).toLocaleString();
            return `${labels[type] ?? type} ${amount}`;
        }

        function ccDiscountDisplay(values) {
            return adjustmentDisplay(values.discount_type, values.discount_value);
        }

        function describeRateChange(rate) {
            const original = ccOriginalValues(rate);
            const current = rateOverrides[rate.id];
            if (!current) return '';
            const fieldLabels = { min_van_qty: 'Min Qty', base_rate: 'Base Rate', discount_type: 'Adjustment Type', discount_value: 'Adjustment Value', final_rate: 'Final Rate' };
            const diffs = [];
            for (const key of Object.keys(fieldLabels)) {
                if (original[key] !== current[key]) {
                    diffs.push(`${fieldLabels[key]}: ${original[key] ?? '-'} → ${current[key] ?? '-'}`);
                }
            }
            return diffs.join(', ');
        }

        function renderRateRow(rate, editing) {
            const lane = `${rate.origin_port ? (rate.origin_port.location?.name ?? '-') + ' - ' + rate.origin_port.name : '-'} → ${rate.destination_port ? (rate.destination_port.location?.name ?? '-') + ' - ' + rate.destination_port.name : '-'}`;
            const variant = `${rate.container?.name ?? '-'} / ${rate.container_class?.class ?? '-'} / ${rate.container_size?.size ?? '-'}`;
            const values = ccCurrentValues(rate);
            const edited = Boolean(rateOverrides[rate.id]);

            if (!editing) {
                return `
                    <tr data-rate-id="${rate.id}" class="border-l-2 border-transparent hover:border-orange-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                        <td class="py-1.5 px-2">${lane}</td>
                        <td class="py-1.5 px-2">${variant}</td>
                        <td class="py-1.5 px-2 text-right">${values.min_van_qty ?? '-'}</td>
                        <td class="py-1.5 px-2 text-right">${Number(values.base_rate).toLocaleString()}</td>
                        <td class="py-1.5 px-2 text-right">${ccDiscountDisplay(values)}</td>
                        <td class="py-1.5 px-2 text-right font-semibold">
                            ${Number(values.final_rate).toLocaleString()}
                            ${edited ? `<span class="ml-1 text-[10px] font-normal text-amber-600 cursor-help" title="${describeRateChange(rate).replace(/"/g, '&quot;')}">(edited)</span>` : ''}
                        </td>
                        <td class="py-1.5 px-2 text-right">
                            <button type="button" class="cc-edit-btn text-base leading-none px-1.5 py-1 rounded text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Edit this rate">✎</button>
                        </td>
                    </tr>`;
            }

            return `
                <tr data-rate-id="${rate.id}">
                    <td class="py-1.5 px-2">${lane}</td>
                    <td class="py-1.5 px-2">${variant}</td>
                    <td class="py-1.5 px-2">
                        <input type="number" min="1" step="1" placeholder="None" class="cc-input-minqty w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${values.min_van_qty ?? ''}">
                    </td>
                    <td class="py-1.5 px-2">
                        <input type="text" inputmode="decimal" class="cc-input-base currency-input w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.base_rate)}">
                    </td>
                    <td class="py-1.5 px-2">
                        <div class="flex items-center gap-1 justify-end">
                            <select class="cc-input-disctype border rounded px-1 py-1 text-xs dark:text-zinc-900">
                                <option value="" ${!values.discount_type ? 'selected' : ''}>None</option>
                                <option value="percentage" ${values.discount_type === 'percentage' ? 'selected' : ''}>Discount (%)</option>
                                <option value="fixed" ${values.discount_type === 'fixed' ? 'selected' : ''}>Discount (Fixed)</option>
                                <option value="increase_percentage" ${values.discount_type === 'increase_percentage' ? 'selected' : ''}>Increase (%)</option>
                                <option value="increase_fixed" ${values.discount_type === 'increase_fixed' ? 'selected' : ''}>Increase (Fixed)</option>
                            </select>
                            <input type="text" inputmode="decimal" class="cc-input-discval currency-input w-16 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.discount_value)}">
                        </div>
                    </td>
                    <td class="py-1.5 px-2">
                        <input type="text" inputmode="decimal" class="cc-input-final currency-input w-24 border rounded px-1.5 py-1 text-xs text-right dark:text-zinc-900" value="${formatCurrencyDisplay(values.final_rate)}">
                    </td>
                    <td class="py-1.5 px-2 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button" class="cc-apply-btn text-base leading-none px-1.5 py-1 rounded text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-950/40" title="Apply">✓</button>
                            <button type="button" class="cc-cancel-btn text-base leading-none px-1.5 py-1 rounded text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800" title="Cancel">✕</button>
                        </div>
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
            const base = parseFloat(parseCurrencyValue(row.querySelector('.cc-input-base').value)) || 0;
            const type = row.querySelector('.cc-input-disctype').value;
            const value = parseFloat(parseCurrencyValue(row.querySelector('.cc-input-discval').value)) || 0;
            const finalInput = row.querySelector('.cc-input-final');

            let final = base;
            if (type === 'percentage') final = Math.max(0, base - (base * value / 100));
            if (type === 'fixed') final = Math.max(0, base - value);
            if (type === 'increase_percentage') final = base + (base * value / 100);
            if (type === 'increase_fixed') final = base + value;

            finalInput.value = formatCurrencyDisplay(final.toFixed(2));
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
                const minQtyRaw = row.querySelector('.cc-input-minqty').value;
                const newValues = {
                    min_van_qty: minQtyRaw === '' ? null : Number(minQtyRaw),
                    base_rate: Number(parseCurrencyValue(row.querySelector('.cc-input-base').value)),
                    discount_type: row.querySelector('.cc-input-disctype').value || null,
                    discount_value: Number(parseCurrencyValue(row.querySelector('.cc-input-discval').value) || 0),
                    final_rate: Number(parseCurrencyValue(row.querySelector('.cc-input-final').value)),
                };
                const original = ccOriginalValues(rate);
                const changed = newValues.min_van_qty !== original.min_van_qty ||
                    newValues.base_rate !== original.base_rate ||
                    newValues.discount_type !== original.discount_type ||
                    newValues.discount_value !== original.discount_value ||
                    newValues.final_rate !== original.final_rate;

                if (changed) {
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
