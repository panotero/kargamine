{{--
    resources/views/pages/proposal_requests.blade.php

    Consolidated Proposal Requests page - merges the old assignment queue
    (this page), the Approvals page (resources/views/pages/proposals.blade.php),
    and the Signed/Contracts page (resources/views/pages/proposal_signed_contracts.blade.php)
    into one list driven by GET /api/proposalRequests. Each row carries a
    server-computed `effective_status` across the whole lifecycle (assignment
    -> RM/Manager approval -> accepted/signed -> terminal states) plus
    `can_assign` / `can_open_my_request` / `latest_client_proposal` - see
    resources/js/logic_proposal_requests.js's header comment and STATUS_PILL.

    Clicking a row with a `latest_client_proposal` opens the full
    #ClientProposalModal below (decision + signed/contract actions, bound via
    the shared resources/js/logic_client_proposals_shared.js -
    window.bindClientProposalModalEvents()); clicking a row without one opens
    the dedicated, fully read-only <x-proposal-request-info-modal /> (whose
    own "Assign" action opens <x-side-modal id="assignOwnersModal">, and whose
    new "Open Request" action deep-links to the requester's own My Requests
    page - see openProposalRequestInfoModal() for both).

    Root element (#ProposalRequestsPage) is only a self-guard hook for
    logic_proposal_requests.js's initProposalRequestsPage() - that file is
    loaded once globally (see resources/js/app.js) and re-run on every SPA
    visit to this page via the bootstrap <script> at the bottom. Do NOT set
    window.PROPOSALS_PAGE_MODE here - that global only matters to the two
    original standalone pages.
--}}
<div id="ProposalRequestsPage" class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Proposal Requests</h1>
            <p class="text-zinc-500">Track every request through assignment, approval, and signing</p>
        </div>
    </div>

    {{-- Effective-status segment strip - mirrors proposals.blade.php's
         .proposalStatusBtn strip pattern/classes, expanded to the full
         effective_status vocabulary. --}}
    <section class="w-full my-5">
        <div class="flex items-center flex-wrap gap-2">
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed border-zinc-300 text-zinc-500 dark:border-zinc-600 dark:text-zinc-400"
                data-eff-status="all">
                <span>All</span>
                <span id="prCount_all">0</span>
            </div>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-zinc-500 border-zinc-300 dark:text-zinc-400 dark:border-zinc-600"
                data-eff-status="for_assignment">
                <span>For Assignment</span>
                <span id="prCount_for_assignment">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-indigo-600 border-indigo-300 dark:text-indigo-400 dark:border-indigo-700"
                data-eff-status="assigned">
                <span>Assigned</span>
                <span id="prCount_assigned">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-amber-600 border-amber-300 dark:text-amber-400 dark:border-amber-700"
                data-eff-status="pending_rm">
                <span>Pending RM</span>
                <span id="prCount_pending_rm">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-amber-600 border-amber-300 dark:text-amber-400 dark:border-amber-700"
                data-eff-status="pending_manager">
                <span>Pending Manager</span>
                <span id="prCount_pending_manager">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-green-600 border-green-300 dark:text-green-400 dark:border-green-700"
                data-eff-status="approved">
                <span>Approved</span>
                <span id="prCount_approved">0</span>
            </div>
            <span class="text-zinc-300 dark:text-zinc-600">→</span>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 text-blue-600 border-blue-300 dark:text-blue-400 dark:border-blue-700"
                data-eff-status="accepted">
                <span>Accepted</span>
                <span id="prCount_accepted">0</span>
            </div>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-red-600 border-red-300 dark:text-red-400 dark:border-red-700"
                data-eff-status="disapproved">
                <span>Disapproved</span>
                <span id="prCount_disapproved">0</span>
            </div>
            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700"></div>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-zinc-500 border-zinc-300 dark:text-zinc-400 dark:border-zinc-600"
                data-eff-status="rejected">
                <span>Rejected</span>
                <span id="prCount_rejected">0</span>
            </div>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-zinc-500 border-zinc-300 dark:text-zinc-400 dark:border-zinc-600"
                data-eff-status="proposal_cancelled">
                <span>Proposal Cancelled</span>
                <span id="prCount_proposal_cancelled">0</span>
            </div>
            <div class="prEffStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed text-zinc-500 border-zinc-300 dark:text-zinc-400 dark:border-zinc-600"
                data-eff-status="cancelled">
                <span>Cancelled</span>
                <span id="prCount_cancelled">0</span>
            </div>
        </div>
    </section>

    <x-table id="tableProposalRequests" />
</div>

{{-- Dedicated, fully read-only "Proposal Request Info" modal - see
     resources/views/components/proposal-request-info-modal.blade.php. Used
     when a row has no latest_client_proposal yet. --}}
<x-proposal-request-info-modal />

{{-- Assign CSR / Relationship Manager side-modal - reached from the info
     modal's own "Assign" button (can_assign only). --}}
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

{{-- Client Proposal detail/action modal - copied from
     resources/views/pages/proposal_signed_contracts.blade.php (the 4th copy
     of this modal - see that page's and proposals.blade.php's header
     comments on the "one modal copy per consuming page" convention), with
     the Approvals page's decision-button group merged into this footer so
     the single modal here covers the whole lifecycle. All ids are kept
     identical to the source pages so resources/js/logic_client_proposals_shared.js
     binds/toggles them unmodified - every toggle() call there is null-safe,
     so having every button present (gated at runtime by status + can_*
     flags) is correct. --}}
<x-modal id="ClientProposalModal">
    <div class="p-5 border-b dark:border-zinc-800 flex justify-between items-center">
        <div>
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold dark:text-white" id="cpmCode">-</p>
                <span id="cpmStatusBadge"></span>
            </div>
            <p class="text-xs text-zinc-400" id="cpmClientName">-</p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
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

        {{-- Legacy rate lines (Client Masters -> Add Proposal origin) - hidden
             when this proposal came from the RFP wizard instead; see
             #cpmRfpRatesTable below and logic_client_proposals_shared.js's
             openProposalModal() for the branch. --}}
        <table id="cpmLegacyRatesTable" class="w-full text-xs">
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

        {{-- RFP-sourced rate lines (Request for Proposal wizard's Build Rate
             Matrix) - shown instead of the table above when the proposal has
             a proposal_request with saved product_rates. --}}
        <table id="cpmRfpRatesTable" class="w-full text-xs hidden">
            <thead class="text-zinc-400 uppercase">
                <tr>
                    <th class="text-left py-1">Item No.</th>
                    <th class="text-left py-1">Product</th>
                    <th class="text-right py-1">Base Rate</th>
                    <th class="text-right py-1">Adjustment</th>
                    <th class="text-right py-1">Total</th>
                    <th class="text-right py-1">Grand Total</th>
                </tr>
            </thead>
            <tbody id="cpmRfpRatesBody"></tbody>
        </table>

        {{-- Additional Charges - proposal-wide opt-ins, hidden entirely when none apply --}}
        <div id="cpmAdditionalCharges" class="hidden">
            <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-widest mb-1.5">Additional Charges</p>
            <div class="flex flex-wrap gap-1.5"></div>
        </div>

        {{-- RFP Terms & Conditions recap (Terms of Payment + grouped
             Ancillary Service fees saved on the request's T&C tab) - hidden
             entirely unless the proposal's proposal_request carries this
             data. See logic_client_proposals_shared.js's openProposalModal(). --}}
        <div id="cpmRfpTerms" class="hidden space-y-4">
            <div id="cpmRfpTermsOfPayment" class="hidden space-y-2">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Terms of Payment</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div id="cpmRfpTopStandardWrap" class="hidden border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 mb-1">All Except Charter</p>
                        <p id="cpmRfpTopStandard" class="text-sm font-medium text-zinc-800 dark:text-zinc-100">-</p>
                    </div>
                    <div id="cpmRfpTopCharterWrap" class="hidden border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 mb-1">Charter Only</p>
                        <p id="cpmRfpTopCharter" class="text-sm font-medium text-zinc-800 dark:text-zinc-100">-</p>
                    </div>
                </div>
            </div>

            <div id="cpmRfpAncillaryWrap" class="hidden space-y-2">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Ancillary Service Fees</p>
                <div id="cpmRfpAncillaryBody" class="space-y-3"></div>
            </div>
        </div>

        {{-- RFP "Additional Note" (proposal_request.additional_notes) - the
             requester's free-text note to the approver, entered on the RFP
             wizard's Confirmation tab. Hidden entirely when empty. --}}
        <div id="cpmRfpAdditionalNote" class="hidden space-y-1">
            <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Additional Note</p>
            <p id="cpmRfpAdditionalNoteText" class="text-sm text-zinc-700 dark:text-zinc-200 whitespace-pre-line"></p>
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

    {{-- Footer merges BOTH button groups: decision actions (from
         proposals.blade.php) on the left, download/contract/client-master
         actions (from proposal_signed_contracts.blade.php) on the right.
         Visibility of every button is decided at runtime by
         logic_client_proposals_shared.js's openProposalModal() toggle()
         calls - status + server-computed can_* flags. --}}
    <div class="border-t px-5 py-4 flex justify-between items-center gap-2">
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
    </div>
</x-modal>

{{-- Create Contract modal (opened from an Accepted proposal) - copied from
     resources/views/pages/proposal_signed_contracts.blade.php. --}}
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
    (function () {
        window.initProposalRequestsPage?.();
    })();
</script>
