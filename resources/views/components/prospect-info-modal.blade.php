{{--
    Centered "Prospect Info" modal (sized like the previous Lead Info
    modal, grown to a ~85vw/82vh floor starting at the md breakpoint so
    both tablet and desktop get the fixed larger size, true edge-to-edge
    full-screen below md) - shown instead of the ProspectModal FORM once
    Identity, Contact Information, and Requirements are all complete (see
    openProspectRecord() in resources/js/logic_prospect_modal.js).

    Layout: a persistent right-column "Contact Summary" card (source,
    prospect name, business type, CSR, relationship manager) - its own Edit
    button switches it into an inline editable form (Save disabled until a
    field actually changes, plus Cancel), plus a "Request for Proposal"
    trigger at the bottom (mints a ProposalRequest and shows a toast - it
    no longer opens any wizard here; the assigned CSR/Relationship Manager
    fill it out from the separate "My Requests" page under Proposals
    instead, see resources/js/logic_proposal_requests_mine.js) - alongside
    a tabbed main area: Locations / Contacts / Requirements. Each of those
    list tabs has its own "+ Add" button that opens a genuinely separate,
    dedicated modal (AddLocationModal/AddContactModal/AddRequirementModal -
    NOT a reopened ProspectModal form) - see
    resources/js/logic_prospect_add_modals.js. Saving in any of them
    refreshes this modal's data and the relevant tab immediately.

    Opened via window.openProspectInfoModal(lead) - takes the
    already-fetched GET /api/crm/prospects/{uuid} payload directly, no
    second network round-trip.
--}}
<x-modal id="ProspectInfoModal" overlay-padding="p-0 md:p-4"
    class="rounded-none md:rounded-2xl md:min-w-[85vw] md:max-w-[95vw] h-full flex flex-col md:h-auto md:min-h-[82vh] md:max-h-[92vh]">

    {{-- Header --}}
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold text-zinc-900 dark:text-white" id="pimTitle">Prospect</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500" id="pimSubtitle"></p>
        </div>
        <button
            class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>

    <div class="flex flex-col md:flex-row gap-5 p-5 flex-1 overflow-y-auto">

        {{-- ============== MAIN: TAB BAR + CONTENT ============== --}}
        <div class="flex-1 min-w-0">

            <div class="flex items-center gap-1 border-b border-zinc-200 dark:border-zinc-700 mb-4" id="pimTabBar">
                <button type="button" id="pimTabBtnLocations" data-pim-tab="Locations"
                    class="pim-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-orange-500 text-orange-600">
                    Locations
                </button>
                <button type="button" id="pimTabBtnContacts" data-pim-tab="Contacts"
                    class="pim-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
                    Contacts
                </button>
                <button type="button" id="pimTabBtnRequirements" data-pim-tab="Requirements"
                    class="pim-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
                    Requirements
                </button>
                <button type="button" id="pimTabBtnOriginDestination" data-pim-tab="OriginDestination"
                    class="pim-tab-btn hidden px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
                    Origin &amp; Destination
                </button>
            </div>

            {{-- ============ TAB: LOCATIONS ============ --}}
            <div class="pim-tab-pane space-y-3" data-pim-pane="Locations">
                <div class="flex justify-end">
                    <button type="button" id="pimAddLocationBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                        + Add Location
                    </button>
                </div>
                <div id="pimLocationsSummary" class="space-y-2"></div>
            </div>

            {{-- ============ TAB: CONTACTS ============ --}}
            <div class="pim-tab-pane hidden space-y-3" data-pim-pane="Contacts">
                <div class="flex justify-end">
                    <button type="button" id="pimAddContactBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                        + Add Contact
                    </button>
                </div>
                <div id="pimContactsSummary" class="space-y-4"></div>
            </div>

            {{-- ============ TAB: REQUIREMENTS ============ --}}
            <div class="pim-tab-pane hidden space-y-6" data-pim-pane="Requirements">

                <div class="flex justify-between  gap-4">
                    <div
                        class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4 flex-1 items-center">
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            Initial Information
                        </p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-0.5">
                            Freight, trucking, and charter requirements for this prospect.
                        </p>
                    </div>
                    <div class="items-start">
                        <button type="button" id="pimAddRequirementBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 shrink-0">
                            Edit Requirement
                        </button>

                    </div>
                </div>

                <p id="pimRequirementsEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">
                    No requirements added yet.
                </p>

                {{-- Freights (Container) - read-only, same columns as the form's table minus Actions --}}
                <div id="pimContainersTableWrap" class="hidden space-y-2">
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
                            <tbody id="pimContainersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Truckings - read-only --}}
                <div id="pimTruckingsTableWrap" class="hidden space-y-2">
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
                            <tbody id="pimTruckingsTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Charters - read-only --}}
                <div id="pimChartersTableWrap" class="hidden space-y-2">
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
                            <tbody id="pimChartersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ============ TAB: ORIGIN & DESTINATION (hidden until it
                 has at least one row - see renderOriginDestinationTab() in
                 logic_prospect_info_modal.js. Rows are saved via the
                 Request for Proposal wizard's own Origin & Destination
                 tab, or via this tab's own Add button which opens the
                 separate AddOriginDestinationModal.) ============ --}}
            <div class="pim-tab-pane hidden space-y-3" data-pim-pane="OriginDestination">
                <div class="flex justify-end">
                    <button type="button" id="pimAddOdBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                        + Add
                    </button>
                </div>
                <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead
                            class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Location Type</th>
                                <th class="px-3 py-2 text-left">No./Unit No.</th>
                                <th class="px-3 py-2 text-left">Building</th>
                                <th class="px-3 py-2 text-left">Street</th>
                                <th class="px-3 py-2 text-left">Country</th>
                                <th class="px-3 py-2 text-left">Province</th>
                                <th class="px-3 py-2 text-left">Town/City</th>
                                <th class="px-3 py-2 text-left">Barangay</th>
                                <th class="px-3 py-2 text-left">Postal Code</th>
                                <th class="px-3 py-2 text-left">Location Mnemonic</th>
                                <th class="px-3 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pimOdTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ============== RIGHT COLUMN: CONTACT SUMMARY ============== --}}
        <div class="w-full md:w-[19rem] shrink-0 space-y-3" id="pimContactSummary">
            <div class="flex justify-between items-center">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Contact
                    Summary</p>
                <button type="button" id="pimSummaryEditBtn"
                    class="text-xs font-medium text-orange-600 hover:text-orange-700">Edit</button>
            </div>

            {{-- Read-only view --}}
            <div id="pimIdentitySummary" class="space-y-3"></div>

            {{-- Edit-mode form (hidden until Edit is clicked) --}}
            <div id="pimIdentityEditForm" class="hidden space-y-3">
                <div>
                    <label for="pimEditSource"
                        class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Prospect
                        Source</label>
                    <select id="pimEditSource"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm mt-1">
                        <option value="Cold Call">Cold Call</option>
                        <option value="Referral">Referral</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Walk-In">Walk-In</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div>
                    <label for="pimEditCompanyName"
                        class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Prospect
                        Name</label>
                    <input type="text" id="pimEditCompanyName"
                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm mt-1">
                </div>
                <div>
                    <label for="pimEditTypeOfBusiness"
                        class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Business
                        Type</label>
                    <select id="pimEditTypeOfBusiness"
                        class="pimEditTypeOfBusinessDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm mt-1">
                        <option value="">Select Business Type</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="pimSummaryCancelBtn"
                        class="text-sm px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 font-medium">
                        Cancel
                    </button>
                    <button type="button" id="pimSummarySaveBtn" disabled
                        class="text-sm px-3 py-1.5 rounded-lg bg-orange-500 text-white font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                        Save
                    </button>
                </div>
            </div>

            {{-- Mints a ProposalRequest and confirms via toast - that's the
                 TEMP CSR's whole job here. Filling it out happens on the
                 assigned CSR/Relationship Manager's "My Requests" page
                 instead (see logic_proposal_requests_mine.js). --}}
            <div class="border-t dark:border-zinc-700 pt-3">
                <button type="button" id="pimRequestProposalBtn"
                    class="w-full justify-center bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Request for Proposal
                </button>
            </div>

            {{-- REQUESTED PROPOSALS & ACTIVITY/HISTORY - rendered into the
                 sidebar (not a tab) per product owner request; render logic
                 lives in logic_prospect_info_modal.js and targets these IDs
                 directly, independent of tabs. --}}
            <div class="border-t dark:border-zinc-700 pt-3 space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                    Requested Proposals</p>
                <div id="pimRequestedProposals" tabindex="0" role="region" aria-label="Requested proposals"
                    class="space-y-2 max-h-64 overflow-y-auto focus:outline-none focus:ring-2 focus:ring-orange-500 rounded-lg">
                </div>
            </div>

            <div class="border-t dark:border-zinc-700 pt-3 space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                    Activity &amp; History</p>
                <div id="pimActivityTimeline" tabindex="0" role="region" aria-label="Activity history"
                    class="space-y-0 max-h-64 overflow-y-auto focus:outline-none focus:ring-2 focus:ring-orange-500 rounded-lg">
                </div>
            </div>
        </div>
    </div>
</x-modal>

<x-add-location-modal />
<x-add-contact-modal />
<x-add-requirement-modal />
<x-add-origin-destination-modal />
