{{--
    resources/views/pages/proposal_signed_contracts.blade.php

    "Signed / Contracts" view of the Client Proposals workflow - same list/
    table/#ClientProposalModal as proposals.blade.php ("Approvals"), but
    scoped to the downstream lifecycle: attach the signed document,
    download the PDF, create/view the resulting contract, and create a
    Client Master from a lead-scoped Accepted proposal. No decision buttons
    here (Approve/Disapprove/Reject/Cancel) - those live on the Approvals
    page, whose own pill strip already includes Approved-status rows too.
    Owns the "Create Contract" modal, moved here from proposals.blade.php.

    Both pages call the SAME /api/clientProposals* endpoints via the
    mode-agnostic resources/js/logic_client_proposals_shared.js (see its
    header comment) - this page just sets window.PROPOSALS_PAGE_MODE =
    'signed_contracts' before calling the shared init hook.
--}}
<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">
        <div>
            <h1 class="text-2xl font-bold">Signed Proposals &amp; Contracts</h1>
            <p class="text-zinc-500">Attach signed documents and generate contracts from approved proposals</p>
        </div>
    </div>

    {{-- Status pill strip - limited to Approved / Accepted / All, default filter Approved --}}
    <section class="w-full my-5">
        <div class="flex items-center flex-wrap gap-2">
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
            <div class="w-px h-6 bg-zinc-200 dark:bg-zinc-700"></div>
            <div class="proposalStatusBtn cursor-pointer border rounded-full px-3 py-1.5 text-xs font-semibold flex items-center gap-1.5 border-dashed border-zinc-300 text-zinc-500 dark:border-zinc-600 dark:text-zinc-400"
                data-status="all">
                <span>All</span>
                <span id="countAll">0</span>
            </div>
        </div>
    </section>

    <x-table id="tableClientProposals" />
</div>

{{-- Detail modal - same id/body as the Approvals page, footer scoped to
     the signed/contract action group only. --}}
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
    </div>
</x-modal>

{{-- Create Contract modal (opened from an Accepted proposal) - moved here
     from proposals.blade.php, only reachable from this page. --}}
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
        window.PROPOSALS_PAGE_MODE = 'signed_contracts';
        window.initClientProposalsPage?.();
    })();
</script>
