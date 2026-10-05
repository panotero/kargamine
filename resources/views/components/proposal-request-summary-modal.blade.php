{{--
    Read-only "Proposal Request Summary" modal - opened from the assigned
    CSR/Relationship Manager's "My Requests" page under Proposals by
    clicking a non-"assigned" row (for_approval/approved/signed/cancelled -
    see openProposalRequestSummary() in logic_proposal_requests_mine.js).
    An "assigned" row instead reopens the Request for Proposal wizard
    directly - this modal is a pure recap, built from the same
    window.buildProposalRequestRecapHtml() the wizard's own Confirmation
    tab uses (see logic_prospect_request_proposal.js).
--}}
<x-modal id="ProposalRequestSummaryModal" overlay-padding="p-0 md:p-4"
    class="rounded-none md:rounded-2xl md:min-w-[70vw] md:max-w-[90vw] h-full flex flex-col md:h-auto md:min-h-[60vh] md:max-h-[92vh]">

    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold text-zinc-900 dark:text-white">Proposal Request Summary</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500" id="prsmCode"></p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>

    <div class="p-5 flex-1 overflow-y-auto">
        <div id="prsmRecap" class="space-y-6"></div>
    </div>

    <div id="prsmActions" class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end gap-2"></div>
</x-modal>
