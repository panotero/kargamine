<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">

        <div>
            <h1 class="text-2xl font-bold">CRM Leads</h1>
            <p class="text-zinc-500">
                Manage leads and sales opportunities
                <span id="crmScopeIndicator" class="hidden"></span>
            </p>
        </div>

        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400">Total</p>
                <p class="text-xl font-bold text-zinc-800 dark:text-zinc-100" id="crmTotalStat">0</p>
            </div>
            <div class="text-right">
                <p class="text-[11px] font-medium uppercase tracking-widest text-red-500 dark:text-red-400">Need
                    Attention</p>
                <p class="text-xl font-bold text-red-600 dark:text-red-400" id="crmAttentionStat">0</p>
            </div>

            <button id="btnNewLead" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                + New Prospect
            </button>
        </div>

    </div>

    <!-- Segmented Pipeline Bar -->
    <section class="w-full my-5">
        <div class="flex items-center justify-between mb-2">
            <span class="font-mono text-[11px] uppercase tracking-widest text-zinc-400">Active Pipeline</span>
            <div class="flex items-center gap-2">
                <button type="button" class="statusBtn px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 hover:opacity-80 transition"
                    data-status="WIN">
                    Won <span id="countWin">0</span>
                </button>
                <button type="button" class="statusBtn px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 hover:opacity-80 transition"
                    data-status="LOST">
                    Lost <span id="countLose">0</span>
                </button>
            </div>
        </div>

        <div id="crmPipelineBar" class="h-2 rounded-full overflow-hidden flex bg-zinc-100 dark:bg-zinc-800">
            <div class="pipeline-segment bg-gray-400" data-status="PROSPECT" style="width: 0%"></div>
            <div class="pipeline-segment bg-indigo-500" data-status="QUALIFIED" style="width: 0%"></div>
            <div class="pipeline-segment bg-purple-500" data-status="OPPORTUNITY" style="width: 0%"></div>
            <div class="pipeline-segment bg-amber-500" data-status="NEGOTIATION" style="width: 0%"></div>
        </div>

        <div class="flex flex-wrap items-center gap-2 mt-3">
            <button type="button"
                class="statusBtn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-dashed border-zinc-300 dark:border-zinc-600 text-xs font-medium text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                data-status="ALL">
                All <span id="countALL">0</span>
            </button>
            <button type="button"
                class="statusBtn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-zinc-200 dark:border-zinc-700 text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                data-status="PROSPECT">
                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                PROSPECT <span id="countProspect">0</span>
            </button>
            <button type="button"
                class="statusBtn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-zinc-200 dark:border-zinc-700 text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                data-status="QUALIFIED">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                QUALIFIED <span id="countQualified">0</span>
            </button>
            <button type="button"
                class="statusBtn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-zinc-200 dark:border-zinc-700 text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                data-status="OPPORTUNITY">
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                OPPORTUNITY <span id="countOpportunity">0</span>
            </button>
            <button type="button"
                class="statusBtn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-zinc-200 dark:border-zinc-700 text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                data-status="NEGOTIATION">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                NEGOTIATION <span id="countNegotiation">0</span>
            </button>
        </div>
    </section>

    <x-table id="tableCrm">
        <x-slot:toolbar>
            <div class="flex items-center gap-2">
                <label for="crmAssignedToFilter"
                    class="text-[11px] font-medium uppercase tracking-widest text-zinc-500">Assigned
                    Rep</label>
                <select id="crmAssignedToFilter"
                    class="rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 px-3 py-2 text-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">All Reps</option>
                </select>
            </div>
            <button type="button" id="crmNeedsAttentionToggle" data-active="false"
                class="inline-flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-widest px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                <span aria-hidden="true">⚠</span> Needs Attention
            </button>
        </x-slot:toolbar>
    </x-table>

</div>

<x-prospect-modal />
<x-pm-add-location-modal />
<x-pm-add-contact-modal />
<x-pm-add-freight-modal />
<x-pm-add-trucking-modal />
<x-pm-add-charter-modal />
<x-prospect-info-modal />

{{-- Reassign Owner side-modal - reached from ProspectInfoModal's header
     "Reassign" button (shown only when lead.can_reassign === true). Trimmed
     single-select version of proposal_requests.blade.php's
     "assignOwnersModal" - see openReassignModal()/saveReassign() in
     logic_prospect_info_modal.js. --}}
<x-side-modal id="reassignOwnerModal">
    <div
        class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
        <div>
            <p class="text-lg font-semibold dark:text-white">Reassign Owner</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500">
                <span id="reassignCompanyName">-</span>
            </p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div class="p-5 space-y-4">
        <div>
            <label for="reassignOwnerSelect"
                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                New Owner <span class="req-asterisk">*</span>
            </label>
            <select id="reassignOwnerSelect"
                class="reassignOwnerDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                <option value="">Select owner</option>
            </select>
        </div>
    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
        <button type="button"
            class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Cancel
        </button>
        <button type="button" id="reassignSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">
            Save
        </button>
    </div>
</x-side-modal>

{{--
    Minimized "New/Edit Prospect" drafts, browser-tab style - lets a CSR
    work several prospects at once without losing typed-but-unsaved input.
    Populated/managed entirely by logic_prospect_modal.js (renderDraftDock()
    etc.); lives here (not the persistent app shell) so it's scoped to the
    CRM page only - see logic_prospect_modal.js's DRAFT CACHE section.

    left offset tracks the sidebar's live width via --sidebar-w (set by
    navmenu.js's syncSidebarWidthVar()) so the dock sits beside the nav
    rail, not on top of it, and follows it in when the sidebar collapses -
    only at lg: and up, since below that the sidebar is an off-canvas
    drawer that doesn't reserve any layout space.
--}}
<div id="pmDraftDock"
    class="hidden fixed bottom-0 left-0 right-0 lg:left-[var(--sidebar-w,16rem)] z-30 flex items-end gap-3 px-4 overflow-x-auto transition-[left] duration-300 ease-in-out [-webkit-overflow-scrolling:touch]">
</div>

<script>
    (function() {
        initCrmLogic();
        initProspectModal();
        initProspectInfoModal();
        initAddModals();
        initRequestProposalModal();
    })();
</script>
