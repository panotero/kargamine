{{--
    resources/views/pages/proposal_requests_mine.blade.php

    "My Requests" - the assigned CSR/Relationship Manager's own workspace.
    Lists every ProposalRequest assigned to the current user (as either the
    prospect's CSR or Relationship Manager, GET /api/proposalRequests/mine,
    ProposalRequestAssignmentController::mine()) and opens the same Request
    for Proposal wizard the TEMP CSR used to open from inside the Prospect
    Info modal - that trigger now only mints the request (see
    logic_prospect_info_modal.js's Contact Summary button); filling it out
    happens here instead. An "assigned" row reopens the wizard directly; any
    other status opens a read-only Proposal Request Summary recap.

    Root element (#ProposalRequestsMinePage) is only a self-guard hook for
    logic_proposal_requests_mine.js's initProposalRequestsMinePage() - that
    file is loaded once globally (see resources/js/app.js) and re-run on
    every SPA visit to this page via the bootstrap <script> at the bottom.
--}}
<div id="ProposalRequestsMinePage" class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">My Proposal Requests</h1>
            <p class="text-zinc-500">Proposal requests assigned to you as CSR or Relationship Manager</p>
        </div>
    </div>

    {{-- Status filter strip --}}
    <section class="w-full my-5">
        <div class="flex items-center flex-wrap gap-2">
            <div class="prmStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed border-zinc-300 text-zinc-500 dark:border-zinc-600 dark:text-zinc-400"
                data-status="all">
                <span>All</span>
                <span id="prmCountAll">0</span>
            </div>
            <div class="prmStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-blue-600 border-blue-300 dark:text-blue-400 dark:border-blue-700"
                data-status="assigned">
                <span>Assigned</span>
                <span id="prmCountAssigned">0</span>
            </div>
            <div class="prmStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-amber-600 border-amber-300 dark:text-amber-400 dark:border-amber-700"
                data-status="for_approval">
                <span>For Approval</span>
                <span id="prmCountForApproval">0</span>
            </div>
            <div class="prmStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-green-600 border-green-300 dark:text-green-400 dark:border-green-700"
                data-status="approved">
                <span>Approved</span>
                <span id="prmCountApproved">0</span>
            </div>
            <div class="prmStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-green-600 border-green-300 dark:text-green-400 dark:border-green-700"
                data-status="signed">
                <span>Signed</span>
                <span id="prmCountSigned">0</span>
            </div>
            <div class="prmStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-zinc-500 border-zinc-300 dark:text-zinc-400 dark:border-zinc-600"
                data-status="cancelled">
                <span>Cancelled</span>
                <span id="prmCountCancelled">0</span>
            </div>
        </div>
    </section>

    <x-table id="tableProposalRequestsMine" />
</div>

<x-request-proposal-modal />
<x-proposal-request-summary-modal />

<script>
    (function () {
        window.initRequestProposalModal?.();
        window.initProposalRequestsMinePage?.();
    })();
</script>
