{{--
    Standalone, fully READ-ONLY "Proposal Request" info modal - opened by
    clicking a row on resources/views/pages/proposal_requests.blade.php
    (Management's assignment queue). Deliberately NOT the CRM's own
    <x-prospect-info-modal /> - this one has no Edit button, no Add/Remove
    on any table, and no "+Add" popups; its only interactive control is the
    "Assign" button in the rail, shown only while the request's own status
    is "pending", which opens the separate <x-side-modal id="assignOwnersModal">
    for the actual CSR/Relationship Manager assignment.

    Opened via window.openProposalRequestInfoModal(pr, lead) - `pr` is the
    ProposalRequest table row (code/status/created_at/creator), `lead` is
    the full GET /api/crm/prospects/{uuid} payload (company/addresses/
    contacts/requirementContainers&Truckings&Charters/activities) - see
    resources/js/logic_proposal_requests.js.
--}}
<x-modal id="ProposalRequestInfoModal" overlay-padding="p-0 md:p-4"
    class="relative rounded-none md:rounded-2xl md:min-w-[85vw] md:max-w-[95vw] h-full flex flex-col md:h-auto md:min-h-[78vh] md:max-h-[92vh]">

    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold text-zinc-900 dark:text-white">Proposal Request</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500">
                <span id="priRequestCode">-</span> &middot; <span id="priCompanyName">-</span>
            </p>
        </div>
        <button
            class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>

    <div class="w-full flex flex-col md:flex-row gap-5 p-5 flex-1 overflow-y-auto">

        {{-- ============== MAIN: TAB BAR + CONTENT ============== --}}
        <div class="flex-1 min-w-0">

            <div class="flex items-center gap-1 border-b border-zinc-200 dark:border-zinc-700 mb-4" id="priTabBar">
                <button type="button" id="priTabBtnLocations" data-pri-tab="Locations"
                    class="pri-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-orange-500 text-orange-600 dark:text-orange-400">
                    Locations
                </button>
                <button type="button" id="priTabBtnContacts" data-pri-tab="Contacts"
                    class="pri-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
                    Contacts
                </button>
                <button type="button" id="priTabBtnRequirements" data-pri-tab="Requirements"
                    class="pri-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
                    Requirements
                </button>
                <button type="button" id="priTabBtnActivity" data-pri-tab="Activity"
                    class="pri-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
                    Activity
                </button>
            </div>

            {{-- ============ TAB: LOCATIONS (read-only) ============ --}}
            <div class="pri-tab-pane space-y-2" data-pri-pane="Locations">
                <div id="priLocationsSummary" class="space-y-2"></div>
            </div>

            {{-- ============ TAB: CONTACTS (read-only) ============ --}}
            <div class="pri-tab-pane hidden space-y-4" data-pri-pane="Contacts">
                <div id="priContactsSummary" class="space-y-4"></div>
            </div>

            {{-- ============ TAB: REQUIREMENTS (read-only, no Actions column) ============ --}}
            <div class="pri-tab-pane hidden space-y-6" data-pri-pane="Requirements">

                <div class="space-y-2">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Freights</p>
                    <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                        <table class="min-w-full text-xs">
                            <thead
                                class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                <tr>
                                    <th class="px-3 py-2 text-left">Type</th>
                                    <th class="px-3 py-2 text-left">Size</th>
                                    <th class="px-3 py-2 text-left">Min Temp</th>
                                    <th class="px-3 py-2 text-left">Revenue Ton (RT)</th>
                                    <th class="px-3 py-2 text-left">Cargo Measurement</th>
                                    <th class="px-3 py-2 text-left">Qty</th>
                                    <th class="px-3 py-2 text-left">Frequency</th>
                                    <th class="px-3 py-2 text-left">Service Type</th>
                                    <th class="px-3 py-2 text-left">Origin</th>
                                    <th class="px-3 py-2 text-left">Destination</th>
                                    <th class="px-3 py-2 text-left">Declared Value</th>
                                    <th class="px-3 py-2 text-left">Weight</th>
                                    <th class="px-3 py-2 text-left">Cargo Type</th>
                                    <th class="px-3 py-2 text-left">Cargo Description</th>
                                    <th class="px-3 py-2 text-left">Special Requirements</th>
                                    <th class="px-3 py-2 text-left">Booking Unit Type</th>
                                </tr>
                            </thead>
                            <tbody id="priContainersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Truckings</p>
                    <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                        <table class="min-w-full text-xs">
                            <thead
                                class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                <tr>
                                    <th class="px-3 py-2 text-left">Trucking Cargo Type</th>
                                    <th class="px-3 py-2 text-left">Qty</th>
                                    <th class="px-3 py-2 text-left">Frequency</th>
                                    <th class="px-3 py-2 text-left">Dispatch Mode</th>
                                    <th class="px-3 py-2 text-left">Origin</th>
                                    <th class="px-3 py-2 text-left">Destination</th>
                                    <th class="px-3 py-2 text-left">Declared Value</th>
                                    <th class="px-3 py-2 text-left">Weight</th>
                                    <th class="px-3 py-2 text-left">Cargo Type</th>
                                    <th class="px-3 py-2 text-left">Cargo Description</th>
                                    <th class="px-3 py-2 text-left">Special Requirements</th>
                                </tr>
                            </thead>
                            <tbody id="priTruckingsTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Charters</p>
                    <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                        <table class="min-w-full text-xs">
                            <thead
                                class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                <tr>
                                    <th class="px-3 py-2 text-left">Cargo</th>
                                    <th class="px-3 py-2 text-left">Ports</th>
                                    <th class="px-3 py-2 text-left">Start Date</th>
                                    <th class="px-3 py-2 text-left">End Date</th>
                                    <th class="px-3 py-2 text-left">Declared Value</th>
                                    <th class="px-3 py-2 text-left">Weight</th>
                                </tr>
                            </thead>
                            <tbody id="priChartersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ============ TAB: ACTIVITY (read-only) ============ --}}
            <div class="pri-tab-pane hidden" data-pri-pane="Activity">
                <div id="priActivityTimeline" class="space-y-0 max-h-[60vh] overflow-y-auto"></div>
            </div>
        </div>

        {{-- ============== RIGHT RAIL: REQUEST DETAILS ============== --}}
        <div class="w-full md:w-[19rem] shrink-0 space-y-3">
            <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                Request Details</p>

            <div class="space-y-3">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">RQ Code</p>
                    <p id="priCode" class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Status</p>
                    <p id="priStatus" class="mt-1">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Created</p>
                    <p id="priCreatedAt" class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Requested By</p>
                    <p id="priCreatedBy" class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Company</p>
                    <p id="priCompanyNameFull" class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Assigned CSR</p>
                    <p id="priCsrName" class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Relationship Manager</p>
                    <p id="priRmName" class="text-sm text-zinc-800 dark:text-zinc-100 mt-0.5">-</p>
                </div>
            </div>

            {{-- Only shown while pr.can_assign is true. --}}
            <div id="priAssignBtnWrap" class="hidden border-t dark:border-zinc-700 pt-3">
                <button type="button" id="priAssignBtn"
                    class="w-full justify-center bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Assign
                </button>
            </div>

            {{-- Only shown while pr.can_open_my_request is true (viewer is
                 this request's assigned CSR/RM) - deep-links to the
                 requester's own My Requests page via
                 window.openMyProposalRequestByCode(). --}}
            <div id="priOpenRequestBtnWrap" class="hidden border-t dark:border-zinc-700 pt-3">
                <button type="button" id="priOpenRequestBtn"
                    class="w-full justify-center border border-orange-500 text-orange-600 hover:bg-orange-50 dark:border-orange-700 dark:text-orange-400 dark:hover:bg-orange-950/30 px-4 py-2 rounded-lg text-sm font-medium">
                    Open Request
                </button>
            </div>
        </div>
    </div>
</x-modal>
