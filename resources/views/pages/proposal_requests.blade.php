{{--
    resources/views/pages/proposal_requests.blade.php

    Management's Proposal Request assignment queue - lists every
    ProposalRequest alongside its prospect's current CSR (assigned_to) /
    Relationship Manager (relationship_manager_id) assignment, and lets
    Management assign both via <x-side-modal id="assignOwnersModal">. A
    request stays locked out of the RFP wizard (see
    logic_prospect_request_proposal.js's applyAssignmentGating()) until
    both are set - see ProspectController::assignOwners() /
    ProposalRequestAssignmentController::index().

    Root element (#ProposalRequestsPage) is only a self-guard hook for
    logic_proposal_requests.js's initProposalRequestsPage() - that file is
    loaded once globally (see resources/js/app.js) and re-run on every SPA
    visit to this page via the bootstrap <script> at the bottom.
--}}
<div id="ProposalRequestsPage" class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Proposal Requests</h1>
            <p class="text-zinc-500">Assign a CSR and Relationship Manager before a Request for Proposal can be
                filled out</p>
        </div>
    </div>

    {{-- Assignment-status segment strip --}}
    <section class="w-full my-5">
        <div class="flex items-center flex-wrap gap-2">
            <div class="prAssignmentStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed border-zinc-300 text-zinc-500 dark:border-zinc-600 dark:text-zinc-400"
                data-assignment-status="all">
                <span>All</span>
                <span id="prCountAll">0</span>
            </div>
            <div class="prAssignmentStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-amber-600 border-amber-300 dark:text-amber-400 dark:border-amber-700"
                data-assignment-status="awaiting">
                <span>Awaiting Assignment</span>
                <span id="prCountAwaiting">0</span>
            </div>
            <div class="prAssignmentStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-green-600 border-green-300 dark:text-green-400 dark:border-green-700"
                data-assignment-status="assigned">
                <span>Assigned</span>
                <span id="prCountAssigned">0</span>
            </div>
        </div>
    </section>

    <x-table id="tableProposalRequests" />
</div>

{{-- Assign CSR / Relationship Manager side-modal --}}
<x-side-modal id="assignOwnersModal">
    <div
        class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
        <div>
            <p class="text-lg font-semibold dark:text-white">Assign Owners</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500">
                <span id="assignRequestCode">-</span> &middot; <span id="assignCompanyName">-</span>
            </p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div class="p-5 space-y-4">
        <div>
            <label for="assignCsrSelect"
                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                Attending CSR <span class="req-asterisk">*</span>
            </label>
            <select id="assignCsrSelect"
                class="assignCsrDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                <option value="">Select CSR</option>
            </select>
        </div>
        <div>
            <label for="assignRmSelect"
                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                Relationship Manager <span class="req-asterisk">*</span>
            </label>
            <select id="assignRmSelect"
                class="assignRmDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                <option value="">Select Relationship Manager</option>
            </select>
        </div>
    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
        <button type="button"
            class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Cancel
        </button>
        <button type="button" id="assignSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
            Save Assignment
        </button>
    </div>
</x-side-modal>

<script>
    (function () {
        window.initProposalRequestsPage?.();
    })();
</script>
