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

        {{-- Attach signed document - only shown for an approved request this
             user can still accept (pr.status === "approved" && pr.can_upload_signed).
             Ports the "Upload Signed Document" affordance from the Proposals
             page's ClientProposalModal (see logic_client_proposals_shared.js's
             #cpmSignedSection) onto this page's own summary modal. --}}
        <div id="prsmSignedSection" class="hidden border-t border-zinc-200 dark:border-zinc-700 pt-4 mt-4 space-y-2">
            <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-500 dark:text-zinc-400">Upload Signed Document</p>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Upload the signed proposal document to accept this proposal.</p>
            <div class="flex items-center gap-2">
                <input type="file" id="prsmSignedFile" accept=".pdf,.jpg,.jpeg,.png"
                    class="flex-1 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                <button type="button" id="prsmUploadSignedBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium shrink-0 disabled:opacity-40 disabled:cursor-not-allowed">
                    Upload &amp; Accept
                </button>
            </div>
        </div>
    </div>

    <div id="prsmActions" class="px-5 py-4 border-t border-zinc-200 dark:border-zinc-700 flex justify-end gap-2"></div>
</x-modal>
