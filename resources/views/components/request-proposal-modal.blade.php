{{--
    "Request for Proposal" wizard - opened from the assigned CSR/Relationship
    Manager's "My Requests" page under Proposals
    (window.openRequestProposalModal(lead, id), see
    resources/js/logic_proposal_requests_mine.js /
    resources/js/logic_prospect_request_proposal.js). Builds out the same
    (one-per-prospect) ProposalRequest row the Requirements tab already
    lazily creates, adding three more child concepts: Company Details
    (assigned CSR/RM display + an editable company-name/location-address
    snapshot + multiple Authorized Signatories picked from this prospect's
    own contacts), Origin & Destination (a curated list of this prospect's
    own addresses - live FK references, not copies - that will feed the
    Products tab's origin/destination selects once that's built), Products
    (placeholder - approach still being finalized), and Proposal Request
    Confirmation (read-only recap + the final Submit action).

    All four tabs read/write the SAME ProposalRequest row that also owns
    Freight/Trucking/Charter, and the Origin & Destination list saved here
    is exactly what makes the "Origin & Destination" tab appear on
    ProspectInfoModal (see that file) - both read/write
    proposal_request_locations via the same endpoints.
--}}
<x-modal id="RequestProposalModal" overlay-padding="p-0 md:p-4"
    class="rounded-none md:rounded-2xl md:min-w-[85vw] md:max-w-[95vw] h-full flex flex-col md:h-auto md:min-h-[78vh] md:max-h-[92vh]">

    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div>
            <p class="text-lg font-semibold text-zinc-900 dark:text-white">Request for Proposal</p>
            <p class="text-xs text-zinc-400 dark:text-zinc-500" id="rpmRequirementsCode"></p>
        </div>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ✕
        </button>
    </div>

    <div class="px-5 pt-3 flex items-center gap-1 border-b border-zinc-200 dark:border-zinc-700" id="rpmTabBar">
        <button type="button" data-rpm-tab="CompanyDetails"
            class="rpm-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-orange-500 text-orange-600 dark:text-orange-400">
            Company Details
        </button>
        <button type="button" data-rpm-tab="OriginDestination"
            class="rpm-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
            Origin &amp; Destination
        </button>
        <button type="button" data-rpm-tab="Products"
            class="rpm-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
            Products
        </button>
        <button type="button" data-rpm-tab="BuildMatrix"
            class="rpm-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
            Build Rate Matrix
        </button>
        <button type="button" data-rpm-tab="TermsConditions"
            class="rpm-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
            Terms &amp; Conditions
        </button>
        <button type="button" data-rpm-tab="Confirmation"
            class="rpm-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-transparent text-zinc-500 dark:text-zinc-400">
            Proposal Request Confirmation
        </button>
    </div>

    <div class="p-5 flex-1 overflow-y-auto">

        {{-- Awaiting-assignment banner - shown/hidden by applyAssignmentGating()
             in logic_prospect_request_proposal.js based on lead.is_fully_assigned.
             Read-only browsing/closing always still work; this is just a UX
             hint ahead of the real server-side 409 guard. --}}
        <div id="rfpAwaitingAssignmentBanner"
            class="hidden mb-4 rounded-xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-4 py-3">
            <p class="text-sm font-medium text-amber-700 dark:text-amber-300">
                This request is awaiting CSR / Relationship Manager assignment by Management. You'll be able to fill
                it out once assigned.
            </p>
        </div>

        {{-- ============ TAB: COMPANY DETAILS ============ --}}
        <div class="rpm-tab-pane space-y-6" data-rpm-pane="CompanyDetails">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Assigned CSR</p>
                    <p class="text-sm font-semibold text-zinc-900 dark:text-white mt-0.5" id="rpmAssignedCsr">—</p>
                </div>
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Assigned Relationship Manager</p>
                    <p class="text-sm font-semibold text-zinc-900 dark:text-white mt-0.5" id="rpmAssignedRm">—</p>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="rpmCompanyName" id="rpmCompanyNameLabel" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Company Name</label>
                        <input type="text" id="rpmCompanyName"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    </div>
                    <div>
                        <label for="rpmCompanyLocation" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Location Address</label>
                        <select id="rpmCompanyLocation"
                            class="rpmLocationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                            <option value="">Select Location</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="rpmSaveCompanyDetailsBtn"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                        Save Company Details
                    </button>
                </div>

                {{-- ---- Authorized Signatory sub-section ----
                     Picking a Contact prefills Name/Position/Email/Contact
                     Number below (see signatoryContactChange() in
                     logic_prospect_request_proposal.js) but every field
                     stays fully editable, and the Contact picker itself is
                     optional - leaving it blank and typing all four fields
                     is a valid fully-manual signatory. Whatever's in these
                     four fields at Add time is what gets saved on this
                     signatory (its own snapshot, not re-read from the
                     contact later) - and on final submit, that snapshot
                     syncs back into the picked contact's
                     position/email/mobile, or becomes a brand new contact
                     if none was picked. See
                     ProspectController::submitProposalRequest(). --}}
                <div class="border-t dark:border-zinc-700 pt-4 space-y-3">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Authorized Signatory</p>
                    <div>
                        <label for="rpmSignatoryContact" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Contact</label>
                        <select id="rpmSignatoryContact"
                            class="rpmSignatoryDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                            <option value="">Select Contact (optional - or type below manually)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="rpmSignatoryName" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Name <span class="req-asterisk">*</span></label>
                            <input type="text" id="rpmSignatoryName"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label for="rpmSignatoryPosition" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Position</label>
                            <input type="text" id="rpmSignatoryPosition"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label for="rpmSignatoryEmail" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Email</label>
                            <input type="email" id="rpmSignatoryEmail"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div>
                            <label for="rpmSignatoryContactNumber" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Contact Number</label>
                            <input type="text" id="rpmSignatoryContactNumber"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="rpmAddSignatoryBtn"
                            class="text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 shrink-0 disabled:opacity-40 disabled:cursor-not-allowed">
                            + Add Authorized
                        </button>
                    </div>
                    <p id="rpmSignatoriesEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No authorized signatories added yet.</p>
                    <div id="rpmSignatoriesList" class="space-y-2"></div>
                </div>
            </div>
        </div>

        {{-- ============ TAB: ORIGIN & DESTINATION ============ --}}
        <div class="rpm-tab-pane hidden space-y-3" data-rpm-pane="OriginDestination">
            {{-- The address form (chips + editable card) now lives in its
                 own popup - see <x-rpm-add-od-modal /> and
                 openRpmAddOdModal()/saveRpmAddOd() in
                 logic_prospect_request_proposal.js - same "+Add via popup,
                 appears in the list" pattern used for Locations on the
                 Prospect Identity tab. --}}
            <div class="flex justify-end">
                <button type="button" id="rpmAddOdBtn"
                    class="text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 shrink-0 disabled:opacity-40 disabled:cursor-not-allowed">
                    + Add
                </button>
            </div>

            <p id="rpmOdEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No origin/destination locations added yet.</p>

            <div id="rpmOdTableWrap" class="hidden overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
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
                    <tbody id="rpmOdTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                </table>
            </div>
        </div>

        {{-- ============ TAB: PRODUCTS ============ --}}
        {{--
            5 independent products (Container/Rolling Cargo/Loose
            Cargo/Trucking/Charter) - deliberately a richer, different
            field set per product than the Requirements tab, not a reuse
            of it. Each table stays hidden until it has >=1 row (same
            mechanic as the Requirements tab's toggleTableWrap), and all
            can be populated simultaneously - independent of which
            product's form is currently selected below. Ancillary
            Services / Top Load Cargo / Charter Cargo Info / Charter Ports
            are never built inline here - they're only added afterward via
            a saved row's own count+View button (see
            logic_prospect_request_proposal.js's renderProductXTable()
            functions and the 4 sub-item modals included at the bottom of
            this file).
        --}}
        <div class="rpm-tab-pane hidden space-y-6" data-rpm-pane="Products">

            {{-- CONTAINER (CV/FR/RF) table --}}
            <div id="rpmProductContainersTableWrap" class="space-y-2">
                <div class="flex justify-between items-center">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Containers</p>
                    <button type="button" id="rpmAddContainerBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 disabled:opacity-40 disabled:cursor-not-allowed">
                        + Add Container
                    </button>
                </div>
                <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Type</th>
                                <th class="px-3 py-2 text-left">Size</th>
                                <th class="px-3 py-2 text-left">Min Temp</th>
                                <th class="px-3 py-2 text-left">Service Type</th>
                                <th class="px-3 py-2 text-left">Origin</th>
                                <th class="px-3 py-2 text-left">Origin Port</th>
                                <th class="px-3 py-2 text-left">Destination</th>
                                <th class="px-3 py-2 text-left">Destination Port</th>
                                <th class="px-3 py-2 text-left">Cargo Type</th>
                                <th class="px-3 py-2 text-left">Cargo Description</th>
                                <th class="px-3 py-2 text-left">Dispatch (Origin)</th>
                                <th class="px-3 py-2 text-left">Dispatch (Destination)</th>
                                <th class="px-3 py-2 text-left">Terms of Payment</th>
                                <th class="px-3 py-2 text-left">Ancillary</th>
                                <th class="px-3 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rpmProductContainersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>

            {{-- ROLLING CARGO table --}}
            <div id="rpmProductRollingCargoTableWrap" class="space-y-2">
                <div class="flex justify-between items-center">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Rolling Cargo</p>
                    <button type="button" id="rpmAddRollingCargoBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 disabled:opacity-40 disabled:cursor-not-allowed">
                        + Add Rolling Cargo
                    </button>
                </div>
                <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Cargo Type</th>
                                <th class="px-3 py-2 text-left">Details</th>
                                <th class="px-3 py-2 text-left">Qty</th>
                                <th class="px-3 py-2 text-left">Units</th>
                                <th class="px-3 py-2 text-left">Revenue Ton (RT)</th>
                                <th class="px-3 py-2 text-left">RT Unit</th>
                                <th class="px-3 py-2 text-left">Cargo Measurement</th>
                                <th class="px-3 py-2 text-left">Service Type</th>
                                <th class="px-3 py-2 text-left">Origin</th>
                                <th class="px-3 py-2 text-left">Origin Port</th>
                                <th class="px-3 py-2 text-left">Destination</th>
                                <th class="px-3 py-2 text-left">Destination Port</th>
                                <th class="px-3 py-2 text-left">Terms of Payment</th>
                                <th class="px-3 py-2 text-left">Ancillary</th>
                                <th class="px-3 py-2 text-left">Top Load Cargo</th>
                                <th class="px-3 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rpmProductRollingCargoTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>

            {{-- BREAK BULK CARGO (LC) table (same shape minus Top Load) --}}
            <div id="rpmProductLooseCargoTableWrap" class="space-y-2">
                <div class="flex justify-between items-center">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Break Bulk Cargo (BB)</p>
                    <button type="button" id="rpmAddLooseCargoBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 disabled:opacity-40 disabled:cursor-not-allowed">
                        + Add Break Bulk Cargo
                    </button>
                </div>
                <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Cargo Type</th>
                                <th class="px-3 py-2 text-left">Details</th>
                                <th class="px-3 py-2 text-left">Qty</th>
                                <th class="px-3 py-2 text-left">Units</th>
                                <th class="px-3 py-2 text-left">Revenue Ton (RT)</th>
                                <th class="px-3 py-2 text-left">RT Unit</th>
                                <th class="px-3 py-2 text-left">Cargo Measurement</th>
                                <th class="px-3 py-2 text-left">Service Type</th>
                                <th class="px-3 py-2 text-left">Origin</th>
                                <th class="px-3 py-2 text-left">Origin Port</th>
                                <th class="px-3 py-2 text-left">Destination</th>
                                <th class="px-3 py-2 text-left">Destination Port</th>
                                <th class="px-3 py-2 text-left">Terms of Payment</th>
                                <th class="px-3 py-2 text-left">Ancillary</th>
                                <th class="px-3 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rpmProductLooseCargoTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>

            {{-- TRUCKING table --}}
            <div id="rpmProductTruckingsTableWrap" class="space-y-2">
                <div class="flex justify-between items-center">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Truckings</p>
                    <button type="button" id="rpmAddTruckingBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 disabled:opacity-40 disabled:cursor-not-allowed">
                        + Add Trucking
                    </button>
                </div>
                <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Trucking Cargo Type</th>
                                <th class="px-3 py-2 text-left">Dispatch Mode</th>
                                <th class="px-3 py-2 text-left">Origin</th>
                                <th class="px-3 py-2 text-left">Destination</th>
                                <th class="px-3 py-2 text-left">Cargo Type</th>
                                <th class="px-3 py-2 text-left">Cargo Description</th>
                                <th class="px-3 py-2 text-left">Terms of Payment</th>
                                <th class="px-3 py-2 text-left">Ancillary</th>
                                <th class="px-3 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rpmProductTruckingsTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>

            {{-- CHARTER table --}}
            <div id="rpmProductChartersTableWrap" class="space-y-2">
                <div class="flex justify-between items-center">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Charters</p>
                    <button type="button" id="rpmAddCharterBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200 disabled:opacity-40 disabled:cursor-not-allowed">
                        + Add Charter
                    </button>
                </div>
                <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <table class="min-w-full text-xs">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Vessel</th>
                                <th class="px-3 py-2 text-left">Dead Weight</th>
                                <th class="px-3 py-2 text-left">Start Date</th>
                                <th class="px-3 py-2 text-left">End Date</th>
                                <th class="px-3 py-2 text-left">Loading Date</th>
                                <th class="px-3 py-2 text-left">Laytime (Load/Unload)</th>
                                <th class="px-3 py-2 text-left">Demurrage</th>
                                <th class="px-3 py-2 text-left">Lashing</th>
                                <th class="px-3 py-2 text-left">Insurance</th>
                                <th class="px-3 py-2 text-left">Terms of Payment</th>
                                <th class="px-3 py-2 text-left">Declared Value</th>
                                <th class="px-3 py-2 text-left">Weight</th>
                                <th class="px-3 py-2 text-left">Cargo Info</th>
                                <th class="px-3 py-2 text-left">Ports</th>
                                <th class="px-3 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rpmProductChartersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                    </table>
                </div>
            </div>

            {{--
                "Copy from Prospect Products" shortcuts - one button per
                intake-time requirement row (requirement_containers/
                _truckings/_charters, already present on the wizard's
                lead payload - no fetch). Clicking is a pure shortcut: it
                opens the matching product's own "+ Add" popup below,
                pre-filled with the overlapping fields, leaving it fully
                editable and unsubmitted. Whole block hidden when the
                prospect has no requirement rows at all - see
                logic_prospect_request_proposal.js's
                renderProductsPrefillShortcuts()/applyProductPrefill().
            --}}
            <div id="rpmProductsPrefillWrap" class="hidden space-y-2">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Copy from Prospect Products</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Pick one of this prospect's own requirement rows to prefill a product's "+ Add" popup with its overlapping fields. The form stays fully editable - review and complete it, then Add manually.</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Location/route and ports aren't carried over - pick those in the form. For charter, cargo & ports are added after saving.</p>
                <div id="rpmProductsPrefillChips" class="flex flex-wrap gap-2"></div>
            </div>
        </div>

        {{-- ============ TAB: BUILD RATE MATRIX ============ --}}
        {{-- Lists every product added on the Products tab (all 5 types,
             unmerged - one row per actual saved product, not the
             merged/summarized line items used by the Confirmation recap).
             Rate column is a session-only "reviewed" flag (not persisted -
             real rate-matrix pricing is backend work planned separately,
             see ProductSpecificationModal). Row click opens that product's
             full spec + a sample fee breakdown. See
             logic_prospect_request_proposal.js's renderBuildMatrix(). --}}
        <div class="rpm-tab-pane hidden space-y-4" data-rpm-pane="BuildMatrix">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Every product added on the Products tab, one row each. Click a row to review its specification and rate breakdown.</p>

            <p id="rpmMatrixEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No products added yet — add some on the Products tab first.</p>

            <div id="rpmMatrixTableWrap" class="hidden overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                <table class="min-w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        <tr>
                            <th class="px-3 py-2 text-left">Item No.</th>
                            <th class="px-3 py-2 text-left">Rate</th>
                            <th class="px-3 py-2 text-left">Product</th>
                            <th class="px-3 py-2 text-left">Detail</th>
                            <th class="px-3 py-2 text-left">Route</th>
                            <th class="px-3 py-2 text-right">Qty</th>
                        </tr>
                    </thead>
                    <tbody id="rpmMatrixTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700"></tbody>
                </table>
            </div>
        </div>

        {{-- ============ TAB: TERMS & CONDITIONS ============ --}}
        {{-- Static reference text only, sourced from "CSR Canva v16.pdf"
             (pages 26-32) - the PDF frames each product type's clauses as a
             "dropdown list" (i.e. selectable per-proposal), but building
             that selection UI + persisting which ones were chosen is
             deferred; this is read-only reference content for now, same
             "placeholder now, full build later" treatment as Build Rate
             Matrix. Shown unconditionally (all six product types), not
             filtered to whatever was actually added on the Products tab. --}}
        <div class="rpm-tab-pane hidden space-y-6" data-rpm-pane="TermsConditions">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Reference terms &amp; conditions per product type, for the assigned RM/CSR to apply when preparing the proposal. Sourced from the standard CSR reference document. Only sections matching products actually added on the Products tab are shown.</p>

            <div id="rpmTcSectionConvan" class="space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">For ConVans &amp; Flatracks</p>
                <div>
                    <label for="rpmTcTermConvan" class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Terms of Payment</label>
                    <select id="rpmTcTermConvan" class="rpmTermsOfPaymentDropdown rpm-tc-payment-term w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm" data-section="For ConVans & Flatracks">
                        <option value="">Select Terms of Payment</option>
                    </select>
                </div>
                <ul class="list-disc list-inside text-xs text-zinc-600 dark:text-zinc-300 space-y-1 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                    <li>Above rates do not apply to "Dangerous Cargoes".</li>
                    <li>Above rates represent base rates and are subject to fuel surcharge adjustments.</li>
                    <li>For TEU bookings, above rates apply for Tandem TEU pick up/delivery.</li>
                    <li>For Flatrack bookings, above rates apply for Tandem Flatrack pick up/delivery.</li>
                    <li>Maximum declared value is ₱<input type="text" inputmode="decimal" class="currency-input w-28 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="500,000">/TEU, ₱<input type="text" inputmode="decimal" class="currency-input w-28 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="500,000">/Flatrack, or ₱<input type="text" inputmode="decimal" class="currency-input w-28 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="1,000,000">/FEU HC.</li>
                    <li>Additional valuation charge of <input type="number" step="0.01" class="w-16 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="0.50">% in excess of the above declared value limits.</li>
                    <li>Two (<input type="number" step="1" class="w-14 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="2">) Days Free Storage upon vessel arrival at Destination; excess incurs ₱<input type="text" inputmode="decimal" class="currency-input w-20 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="500">/day (TEU/Flatrack) or ₱<input type="text" inputmode="decimal" class="currency-input w-20 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="1,000">/day (FEU HC).</li>
                    <li>Allowable load limit is <input type="number" step="1" class="w-14 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="18"> Tons per TEU/Flatrack/FEU HC to avoid off loading and penalties.</li>
                    <li>Shipper will provide standard stacking configuration &amp; height for each SKU, and is fully liable for consequences of overloading or non-standard stacking.</li>
                </ul>
            </div>

            <div id="rpmTcSectionReefer" class="space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">For Reefer Vans</p>
                <div>
                    <label for="rpmTcTermReefer" class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Terms of Payment</label>
                    <select id="rpmTcTermReefer" class="rpmTermsOfPaymentDropdown rpm-tc-payment-term w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm" data-section="For Reefer Vans">
                        <option value="">Select Terms of Payment</option>
                    </select>
                </div>
                <ul class="list-disc list-inside text-xs text-zinc-600 dark:text-zinc-300 space-y-1 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                    <li>Above rates do not apply to "Dangerous Cargoes".</li>
                    <li>Above rates represent base rates and are subject to fuel surcharge adjustments.</li>
                    <li>Maximum declared value is ₱<input type="text" inputmode="decimal" class="currency-input w-28 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="500,000">/Reefer Van; additional valuation charge of <input type="number" step="0.01" class="w-16 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="0.50">% in excess.</li>
                    <li>Minimum Temperature: <input type="number" step="1" class="w-16 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="100"> degrees Celsius / Fahrenheit.</li>
                    <li>Direct-to-truck delivery required for Reefer Vans upon vessel arrival.</li>
                    <li>Allowable load limit per Reefer Van is <input type="number" step="1" class="w-14 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="18"> Tons to avoid off loading and penalties.</li>
                    <li>Shipper will provide standard stacking configuration &amp; height for each SKU, and is fully liable for consequences of overloading or non-standard stacking.</li>
                </ul>
            </div>

            <div id="rpmTcSectionRolling" class="space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">For Rolling Cargoes</p>
                <div>
                    <label for="rpmTcTermRolling" class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Terms of Payment</label>
                    <select id="rpmTcTermRolling" class="rpmTermsOfPaymentDropdown rpm-tc-payment-term w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm" data-section="For Rolling Cargoes">
                        <option value="">Select Terms of Payment</option>
                    </select>
                </div>
                <ul class="list-disc list-inside text-xs text-zinc-600 dark:text-zinc-300 space-y-1 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                    <li>Above rates do not apply to "Dangerous Cargoes".</li>
                    <li>Above rates represent base rates and are subject to fuel surcharge adjustments.</li>
                    <li>Above rates are estimates. Final charges will be based on actual measurements.</li>
                    <li>Port charges for Cebu, CDO, Masbate &amp; Palawan are excluded from above rates and must be settled directly by the client.</li>
                    <li>No passengers or unauthorized personnel are permitted inside or on the vehicle.</li>
                    <li>Maximum declared value is ₱<input type="text" inputmode="decimal" class="currency-input w-28 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="500,000">/unit; additional valuation charge of <input type="number" step="0.01" class="w-16 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="0.50">% in excess.</li>
                    <li>Two (<input type="number" step="1" class="w-14 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="2">) Days Free Storage upon vessel arrival at Destination; excess incurs ₱<input type="text" inputmode="decimal" class="currency-input w-20 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="500">/day/unit.</li>
                </ul>
            </div>

            <div id="rpmTcSectionBreakbulk" class="space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">For Break Bulk Cargo</p>
                <div>
                    <label for="rpmTcTermBreakbulk" class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Terms of Payment</label>
                    <select id="rpmTcTermBreakbulk" class="rpmTermsOfPaymentDropdown rpm-tc-payment-term w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm" data-section="For Break Bulk Cargo">
                        <option value="">Select Terms of Payment</option>
                    </select>
                </div>
                <ul class="list-disc list-inside text-xs text-zinc-600 dark:text-zinc-300 space-y-1 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                    <li>Above rates do not apply to "Dangerous Cargoes".</li>
                    <li>Above rates represent base rates and are subject to fuel surcharge adjustments.</li>
                    <li>Above rates are estimates. Final charges will be based on actual measurements.</li>
                    <li>Insurance coverage will be for the account of shipper/cargo owner.</li>
                    <li>Two (<input type="number" step="1" class="w-14 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="2">) Days Free Storage upon vessel arrival at Destination; excess incurs ₱<input type="text" inputmode="decimal" class="currency-input w-20 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="500">/day/item unit.</li>
                    <li>Shipper is fully liable for consequences of overloading or non-standard stacking.</li>
                </ul>
            </div>

            <div id="rpmTcSectionTrucking" class="space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">For Trucking</p>
                <div>
                    <label for="rpmTcTermTrucking" class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Terms of Payment</label>
                    <select id="rpmTcTermTrucking" class="rpmTermsOfPaymentDropdown rpm-tc-payment-term w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm" data-section="For Trucking">
                        <option value="">Select Terms of Payment</option>
                    </select>
                </div>
                <ul class="list-disc list-inside text-xs text-zinc-600 dark:text-zinc-300 space-y-1 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                    <li>Above rates do not apply to "Dangerous Cargoes".</li>
                    <li>Above rates represent base rates and are subject to fuel surcharge adjustments.</li>
                    <li>Foul trip charges apply to all unsuccessful transport &amp; delivery attempts.</li>
                    <li>Standard detention and demurrage fees will incur if waiting time at origin or destination exceeds the agreed threshold.</li>
                    <li>Shipper is fully liable for consequences of overloading or non-standard stacking.</li>
                </ul>
            </div>

            <div id="rpmTcSectionCharter" class="space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">For Charter</p>
                <div>
                    <label for="rpmTcTermsOfPaymentCharter" class="block text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">Terms of Payment</label>
                    <select id="rpmTcTermsOfPaymentCharter" class="rpmCharterTermsOfPaymentDropdown rpm-tc-payment-term w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm" data-section="For Charter">
                        <option value="">Select Terms of Payment</option>
                    </select>
                </div>
                <div class="text-xs text-zinc-600 dark:text-zinc-300 space-y-2 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
                    <p>SHIPPING COMPANY shall provide fuel, oil, lubricant and other supply provisions necessary to transport the CHARTERER's equipment per the agreed terms. Other out-of-pocket expenses not incurred in the normal course of the charter shall be for the CHARTERER's account.</p>
                    <p>Loading and unloading expenses shall be for the account of the CHARTERER. All CARGO loaded is accepted on the basis of the CHARTERER's load and count; CARGO shall be loaded, stowed/trimmed, and discharged by the CHARTERER at their risk and expense, under the Master's supervision.</p>
                    <p>All arrastre, stevedoring, wharfage fees and other port charges related to the shipment of the CARGO shall be for the CHARTERER's account, unless otherwise specified. Damage to the VESSEL and its accessories duly certified by the Master as incurred during loading and/or unloading shall be for the CHARTERER's account.</p>
                    <p>The CHARTERER shall undertake a suitability survey of the VESSEL; the start of loading shall be construed as the CHARTERER's final acceptance of the VESSEL, and SHIPPING COMPANY shall not be held responsible for any claims related to the CARGO thereafter.</p>
                    <p>The VESSEL shall not be used for cargo restricted by local government authorities, nor loaded with cargo not government-approved and certified for transit, nor towed outside Philippine sheltered waters.</p>
                    <p>Claims for damages or demurrage shall be computed and settled separately from the freight balance, unless otherwise agreed. Freight in full is deemed earned whether the VESSEL and/or CARGO is lost or not.</p>
                    <p>LAYTIME allowed for loading and/or unloading is on a twenty-four (<input type="number" step="1" class="w-14 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="24">) hour basis, including Sundays and Holidays. Time spent waiting for berth, documents, or freight clearances counts as LAYTIME, for the CHARTERER's expense.</p>
                    <p>Cargo Lien — the SHIPPING COMPANY has first lien over the CARGO for non-payment of freight, extra charges, damages, demurrage, or any amount due, including collection costs, and may place the CARGO in a warehouse to sell/dispose of it to recover its claim. The CHARTERER shall pay all balance due within twenty-four (<input type="number" step="1" class="w-14 inline-block mx-1 text-center border border-zinc-300 dark:border-zinc-700 rounded px-1 py-0.5 text-xs bg-white dark:bg-zinc-800 dark:text-zinc-100" value="24">) hours after the VESSEL secures berth at the unloading port and is ready to unload.</p>
                    <p>Any deviation to save life, property, or the environment, or any reasonable deviation, shall not be deemed a violation of this Charter Party, and SHIPPING COMPANY shall not be liable for any resulting loss or damage.</p>
                </div>
            </div>

            <div class="border-t dark:border-zinc-700 pt-4 flex justify-end">
                <button type="button" id="rpmSaveTermsConditionsBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                    Save Terms of Payment
                </button>
            </div>
        </div>

        {{-- ============ TAB: PROPOSAL REQUEST CONFIRMATION ============ --}}
        <div class="rpm-tab-pane hidden space-y-6" data-rpm-pane="Confirmation">
            <div id="rpmConfirmationRecap" class="space-y-4"></div>

            <div class="border-t dark:border-zinc-700 pt-4 flex items-center justify-between gap-3">
                <p id="rpmSubmittedStatus" class="text-sm text-zinc-400 dark:text-zinc-500"></p>
                <button type="button" id="rpmSubmitBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                    Confirm &amp; Submit Request
                </button>
            </div>
        </div>
    </div>
</x-modal>

<x-rpm-add-od-modal />
<x-rpm-add-container-modal />
<x-rpm-add-rolling-cargo-modal />
<x-rpm-add-loose-cargo-modal />
<x-rpm-add-trucking-modal />
<x-rpm-add-charter-modal />
<x-ancillary-services-modal />
<x-top-load-cargo-modal />
<x-charter-cargo-info-modal />
<x-charter-ports-modal />
<x-product-specification-modal />
