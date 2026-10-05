{{--
    resources/views/pages/proposals.blade.php

    "Approvals" view of the Client Proposals workflow - list + pill strip +
    detail modal, decision buttons only (Approve/Disapprove/Reject/Cancel).
    The downstream lifecycle (attach signed document, download PDF, create
    contract, create client master) lives on the sibling
    proposal_signed_contracts.blade.php ("Signed / Contracts") page instead
    - both pages share the SAME /api/clientProposals* endpoints and the same
    #ClientProposalModal id/body, via the mode-agnostic
    resources/js/logic_client_proposals_shared.js (see its header comment).
--}}
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
                <span>Pending RM</span>
                <span id="countPending">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-amber-600 border-amber-300 dark:text-amber-400 dark:border-amber-700"
                data-status="7">
                <span>Pending Manager</span>
                <span id="countPendingManager">0</span>
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
    </div>

    {{-- Approvals page: footer shows ONLY the decision button group. The
         signed-document/download/create-contract/view-contract/create-client-master
         actions live on the Signed/Contracts page instead - see
         resources/views/pages/proposal_signed_contracts.blade.php. --}}
    <div class="border-t px-5 py-4 flex justify-end items-center gap-2">
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

<script>
    (function () {
        window.PROPOSALS_PAGE_MODE = 'approvals';
        window.initClientProposalsPage?.();
    })();
</script>
