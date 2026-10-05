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
            class="rpm-tab-btn px-3 py-2 text-sm font-medium border-b-2 -mb-px border-orange-500 text-orange-600">
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

                {{-- ---- Authorized Signatory sub-section ---- --}}
                <div class="border-t dark:border-zinc-700 pt-4 space-y-3">
                    <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Authorized Signatory</p>
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label for="rpmSignatoryContact" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Contact</label>
                            <select id="rpmSignatoryContact"
                                class="rpmSignatoryDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                <option value="">Select Contact</option>
                            </select>
                        </div>
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
            <div>
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-2">Select a Location to Add</p>
                <div id="rpmOdChips" class="flex flex-wrap gap-2"></div>
                <p id="rpmOdChipsEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">This prospect has no saved addresses yet - fill out the form below manually.</p>
            </div>

            {{--
                Live, editable address form - built from
                window.prospectShared.locationCardHtml()/initLocationPsgcCascade()
                (same helpers the Identity tab's own address cards and
                AddLocationModal use), minus the Primary radio and Remove
                button which don't apply to a single O&D entry. Clicking a
                suggestion chip above autofills it (including the real
                province->city->barangay cascade), but it's always visible
                and editable/submittable on its own - manual entry with no
                chip clicked is allowed. See
                logic_prospect_request_proposal.js's renderOdChips()/
                hydrateOdCard()/addOdLocation().
            --}}
            <div id="rpmOdCardWrap"></div>

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

            <p id="rpmProductsEmpty" class="hidden text-sm text-zinc-400 dark:text-zinc-500">No products added yet.</p>

            {{-- CONTAINER (CV/FR/RF) table --}}
            <div id="rpmProductContainersTableWrap" class="hidden space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Containers</p>
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
            <div id="rpmProductRollingCargoTableWrap" class="hidden space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Rolling Cargo</p>
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
            <div id="rpmProductLooseCargoTableWrap" class="hidden space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Break Bulk Cargo (BB)</p>
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
            <div id="rpmProductTruckingsTableWrap" class="hidden space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Truckings</p>
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
            <div id="rpmProductChartersTableWrap" class="hidden space-y-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Charters</p>
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
                switches to the matching product form below and prefills
                only the overlapping fields, leaving it fully editable and
                unsubmitted. Whole block hidden when the prospect has no
                requirement rows at all - see
                logic_prospect_request_proposal.js's
                renderProductsPrefillShortcuts()/applyProductPrefill().
            --}}
            <div id="rpmProductsPrefillWrap" class="hidden space-y-2">
                <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Copy from Prospect Products</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Pick one of this prospect's own requirement rows to prefill a product form below with its overlapping fields. The form stays fully editable - review and complete it, then Add manually.</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Location/route and ports aren't carried over - pick those in the form. For charter, cargo & ports are added after saving.</p>
                <div id="rpmProductsPrefillChips" class="flex flex-wrap gap-2"></div>
            </div>

            {{-- Product picker - one shared "Add" area, per brief mirroring the Requirements tab --}}
            <div class="border-t dark:border-zinc-700 pt-4">
                <label for="rpmProductSelect" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Product</label>
                <select id="rpmProductSelect"
                    class="w-full md:w-64 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                    <option value="">Select Product</option>
                    <option value="container">Container</option>
                    <option value="rolling_cargo">Rolling Cargo</option>
                    <option value="loose_cargo">Break Bulk Cargo (BB)</option>
                    <option value="trucking">Trucking</option>
                    <option value="charter">Charter</option>
                </select>
            </div>

            {{-- CONTAINER form (CV/FR/RF unified - Minimum Temperature only shown for RF) --}}
            <div id="rpmProductPaneContainer" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
                <div id="rpmContainerForm" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label for="rpmPcContainerType" class="text-[11px] text-zinc-400 uppercase">Container Type</label>
                        <select id="rpmPcContainerType" data-field="container_type"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="CV">Container Van (CV)</option>
                            <option value="FR">Flatrack (FR)</option>
                            <option value="RF">Reefer Van (RF)</option>
                        </select>
                    </div>
                    <div>
                        <label for="rpmPcContainerSize" class="text-[11px] text-zinc-400 uppercase">Size</label>
                        <select id="rpmPcContainerSize" data-field="container_size_id"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Size</option>
                        </select>
                    </div>
                    <div class="rpm-field-min-temp hidden">
                        <label for="rpmPcMinTemp" class="text-[11px] text-zinc-400 uppercase">Minimum Temperature (&deg;C)</label>
                        <input type="number" step="0.1" id="rpmPcMinTemp" data-field="minimum_temperature"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmPcDeliveryType" class="text-[11px] text-zinc-400 uppercase">Service Type</label>
                        <select id="rpmPcDeliveryType" data-field="service_type"
                            class="rpmDeliveryTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Service Type</option>
                        </select>
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="rpmPcQty" class="text-[11px] text-zinc-400 uppercase">Quantity (No. of Containers) <span class="req-asterisk">*</span></label>
                        <input type="number" min="1" step="1" id="rpmPcQty" data-field="quantity"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>

                    {{-- ROUTE - two-column, arrow-connected (see VISUALS.md) --}}
                    <div class="md:col-span-3 space-y-1.5">
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Route</p>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                            <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4">
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <label for="rpmPcOrigin" class="text-[11px] text-zinc-400 uppercase">Origin</label>
                                        <select id="rpmPcOrigin" data-field="origin_prospect_location_id"
                                            class="rpmOdOriginDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Origin</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="rpmPcOriginPort" class="text-[11px] text-zinc-400 uppercase">Port of Origin</label>
                                        <select id="rpmPcOriginPort" data-field="origin_port_id"
                                            class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Port</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center justify-center py-1 md:py-0 md:pt-7">
                                    <span class="inline-block rotate-90 md:rotate-0 text-orange-500 font-bold text-xl leading-none">&rarr;</span>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <label for="rpmPcDestination" class="text-[11px] text-zinc-400 uppercase">Destination</label>
                                        <select id="rpmPcDestination" data-field="destination_prospect_location_id"
                                            class="rpmOdDestinationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Destination</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="rpmPcDestinationPort" class="text-[11px] text-zinc-400 uppercase">Port of Destination</label>
                                        <select id="rpmPcDestinationPort" data-field="destination_port_id"
                                            class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Port</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="rpmPcCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                        <select id="rpmPcCargoType" data-field="cargo_type"
                            class="rpmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Cargo Type</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label for="rpmPcCargoDescription" class="text-[11px] text-zinc-400 uppercase">Cargo Description</label>
                        <input type="text" id="rpmPcCargoDescription" data-field="cargo_description"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmPcDispatchOrigin" class="text-[11px] text-zinc-400 uppercase">Dispatch Mode (Origin)</label>
                        <select id="rpmPcDispatchOrigin" data-field="dispatch_mode_origin"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm disabled:opacity-40">
                            <option value="single">Single</option>
                            <option value="tandem">Tandem</option>
                        </select>
                    </div>
                    <div>
                        <label for="rpmPcDispatchDestination" class="text-[11px] text-zinc-400 uppercase">Dispatch Mode (Destination)</label>
                        <select id="rpmPcDispatchDestination" data-field="dispatch_mode_destination"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm disabled:opacity-40">
                            <option value="single">Single</option>
                            <option value="tandem">Tandem</option>
                        </select>
                    </div>
                    <div>
                        <label for="rpmPcTermsOfPayment" class="text-[11px] text-zinc-400 uppercase">Terms of Payment</label>
                        <select id="rpmPcTermsOfPayment" data-field="terms_of_payment"
                            class="rpmTermsOfPaymentDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
                    </div>
                </div>
            </div>

            {{-- ROLLING CARGO form --}}
            <div id="rpmProductPaneRollingCargo" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
                <div id="rpmRollingCargoForm" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label for="rpmRcCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                        <select id="rpmRcCargoType" data-field="cargo_type"
                            class="rpmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Cargo Type</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="rpmRcCargoDetails" class="text-[11px] text-zinc-400 uppercase">Cargo Details</label>
                        <input type="text" id="rpmRcCargoDetails" data-field="cargo_details"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmRcQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                        <input type="number" id="rpmRcQuantity" data-field="cargo_quantity"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmRcUnits" class="text-[11px] text-zinc-400 uppercase">Units</label>
                        <select id="rpmRcUnits" data-field="cargo_units"
                            class="rpmUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Unit</option>
                        </select>
                    </div>
                    <div>
                        <label for="rpmRcRevenueTonUnit" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT) Unit</label>
                        <select id="rpmRcRevenueTonUnit" data-field="revenue_ton_unit"
                            class="rpmRtUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
                    </div>
                    <div>
                        <label for="rpmRcRevenueTon" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT)</label>
                        <input type="number" step="0.01" id="rpmRcRevenueTon" data-field="revenue_ton"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmRcMeasurement" class="text-[11px] text-zinc-400 uppercase">Cargo Measurement</label>
                        <input type="text" id="rpmRcMeasurement" data-field="cargo_measurement"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmRcDeliveryType" class="text-[11px] text-zinc-400 uppercase">Service Type</label>
                        <select id="rpmRcDeliveryType" data-field="service_type"
                            class="rpmDeliveryTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Service Type</option>
                        </select>
                    </div>
                    {{-- ROUTE - two-column, arrow-connected (see VISUALS.md) --}}
                    <div class="md:col-span-3 space-y-1.5">
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Route</p>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                            <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4">
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <label for="rpmRcOrigin" class="text-[11px] text-zinc-400 uppercase">Origin</label>
                                        <select id="rpmRcOrigin" data-field="origin_prospect_location_id"
                                            class="rpmOdOriginDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Origin</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="rpmRcOriginPort" class="text-[11px] text-zinc-400 uppercase">Port of Origin</label>
                                        <select id="rpmRcOriginPort" data-field="origin_port_id"
                                            class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Port</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center justify-center py-1 md:py-0 md:pt-7">
                                    <span class="inline-block rotate-90 md:rotate-0 text-orange-500 font-bold text-xl leading-none">&rarr;</span>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <label for="rpmRcDestination" class="text-[11px] text-zinc-400 uppercase">Destination</label>
                                        <select id="rpmRcDestination" data-field="destination_prospect_location_id"
                                            class="rpmOdDestinationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Destination</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="rpmRcDestinationPort" class="text-[11px] text-zinc-400 uppercase">Port of Destination</label>
                                        <select id="rpmRcDestinationPort" data-field="destination_port_id"
                                            class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Port</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="rpmRcTermsOfPayment" class="text-[11px] text-zinc-400 uppercase">Terms of Payment</label>
                        <select id="rpmRcTermsOfPayment" data-field="terms_of_payment"
                            class="rpmTermsOfPaymentDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
                    </div>
                </div>
            </div>

            {{-- BREAK BULK CARGO (LC) form (same fields as Rolling Cargo) --}}
            <div id="rpmProductPaneLooseCargo" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
                <div id="rpmLooseCargoForm" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label for="rpmLcCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                        <select id="rpmLcCargoType" data-field="cargo_type"
                            class="rpmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Cargo Type</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="rpmLcCargoDetails" class="text-[11px] text-zinc-400 uppercase">Cargo Details</label>
                        <input type="text" id="rpmLcCargoDetails" data-field="cargo_details"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmLcQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                        <input type="number" id="rpmLcQuantity" data-field="cargo_quantity"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmLcUnits" class="text-[11px] text-zinc-400 uppercase">Units</label>
                        <select id="rpmLcUnits" data-field="cargo_units"
                            class="rpmUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Unit</option>
                        </select>
                    </div>
                    <div>
                        <label for="rpmLcRevenueTonUnit" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT) Unit</label>
                        <select id="rpmLcRevenueTonUnit" data-field="revenue_ton_unit"
                            class="rpmRtUnitDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
                    </div>
                    <div>
                        <label for="rpmLcRevenueTon" class="text-[11px] text-zinc-400 uppercase">Revenue Ton (RT)</label>
                        <input type="number" step="0.01" id="rpmLcRevenueTon" data-field="revenue_ton"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmLcMeasurement" class="text-[11px] text-zinc-400 uppercase">Cargo Measurement</label>
                        <input type="text" id="rpmLcMeasurement" data-field="cargo_measurement"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmLcDeliveryType" class="text-[11px] text-zinc-400 uppercase">Service Type</label>
                        <select id="rpmLcDeliveryType" data-field="service_type"
                            class="rpmDeliveryTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Service Type</option>
                        </select>
                    </div>
                    {{-- ROUTE - two-column, arrow-connected (see VISUALS.md) --}}
                    <div class="md:col-span-3 space-y-1.5">
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Route</p>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                            <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4">
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <label for="rpmLcOrigin" class="text-[11px] text-zinc-400 uppercase">Origin</label>
                                        <select id="rpmLcOrigin" data-field="origin_prospect_location_id"
                                            class="rpmOdOriginDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Origin</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="rpmLcOriginPort" class="text-[11px] text-zinc-400 uppercase">Port of Origin</label>
                                        <select id="rpmLcOriginPort" data-field="origin_port_id"
                                            class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Port</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center justify-center py-1 md:py-0 md:pt-7">
                                    <span class="inline-block rotate-90 md:rotate-0 text-orange-500 font-bold text-xl leading-none">&rarr;</span>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div>
                                        <label for="rpmLcDestination" class="text-[11px] text-zinc-400 uppercase">Destination</label>
                                        <select id="rpmLcDestination" data-field="destination_prospect_location_id"
                                            class="rpmOdDestinationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Destination</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="rpmLcDestinationPort" class="text-[11px] text-zinc-400 uppercase">Port of Destination</label>
                                        <select id="rpmLcDestinationPort" data-field="destination_port_id"
                                            class="rpmPortDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                            <option value="">Select Port</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="rpmLcTermsOfPayment" class="text-[11px] text-zinc-400 uppercase">Terms of Payment</label>
                        <select id="rpmLcTermsOfPayment" data-field="terms_of_payment"
                            class="rpmTermsOfPaymentDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
                    </div>
                </div>
            </div>

            {{-- TRUCKING form --}}
            <div id="rpmProductPaneTrucking" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
                <div id="rpmTruckingForm" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label for="rpmTrTruckingCargoType" class="text-[11px] text-zinc-400 uppercase">Trucking Cargo Type</label>
                        <select id="rpmTrTruckingCargoType" data-field="trucking_cargo_type"
                            class="rpmTruckingCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Cargo Type</option>
                        </select>
                    </div>
                    <div>
                        <label for="rpmTrDispatchMode" class="text-[11px] text-zinc-400 uppercase">Dispatch Mode</label>
                        <select id="rpmTrDispatchMode" data-field="dispatch_mode"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="single">Single</option>
                            <option value="tandem">Tandem</option>
                        </select>
                    </div>
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                        <label for="rpmTrQty" class="text-[11px] text-zinc-400 uppercase">Quantity (No. of Units) <span class="req-asterisk">*</span></label>
                        <input type="number" min="1" step="1" id="rpmTrQty" data-field="quantity"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>

                    {{-- ROUTE - two-column, arrow-connected (no ports for Trucking) --}}
                    <div class="md:col-span-3 space-y-1.5">
                        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Route</p>
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                            <div class="flex flex-col md:flex-row items-stretch md:items-start gap-4">
                                <div class="flex-1">
                                    <label for="rpmTrOrigin" class="text-[11px] text-zinc-400 uppercase">Origin</label>
                                    <select id="rpmTrOrigin" data-field="origin_prospect_location_id"
                                        class="rpmOdOriginDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                        <option value="">Select Origin</option>
                                    </select>
                                </div>
                                <div class="shrink-0 flex items-center justify-center py-1 md:py-0 md:pt-6">
                                    <span class="inline-block rotate-90 md:rotate-0 text-orange-500 font-bold text-xl leading-none">&rarr;</span>
                                </div>
                                <div class="flex-1">
                                    <label for="rpmTrDestination" class="text-[11px] text-zinc-400 uppercase">Destination</label>
                                    <select id="rpmTrDestination" data-field="destination_prospect_location_id"
                                        class="rpmOdDestinationDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                                        <option value="">Select Destination</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="rpmTrCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                        <select id="rpmTrCargoType" data-field="cargo_type"
                            class="rpmCargoTypeDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select Cargo Type</option>
                        </select>
                    </div>
                    <div>
                        <label for="rpmTrTermsOfPayment" class="text-[11px] text-zinc-400 uppercase">Terms of Payment</label>
                        <select id="rpmTrTermsOfPayment" data-field="terms_of_payment"
                            class="rpmTermsOfPaymentDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
                    </div>
                    <div class="md:col-span-3">
                        <label for="rpmTrCargoDescription" class="text-[11px] text-zinc-400 uppercase">Cargo Description</label>
                        <input type="text" id="rpmTrCargoDescription" data-field="cargo_description"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            {{-- CHARTER form (main fields only - Cargo Info/Ports added afterward via View) --}}
            <div id="rpmProductPaneCharter" class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
                <div id="rpmCharterForm" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label for="rpmChVesselName" class="text-[11px] text-zinc-400 uppercase">Vessel Name</label>
                        <input type="text" id="rpmChVesselName" data-field="vessel_name"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChDeadWeight" class="text-[11px] text-zinc-400 uppercase">Vessel Dead Weight</label>
                        <input type="number" step="0.01" id="rpmChDeadWeight" data-field="vessel_dead_weight"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChLoadingDate" class="text-[11px] text-zinc-400 uppercase">Loading Date</label>
                        <input type="date" id="rpmChLoadingDate" data-field="loading_date"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChStartDate" class="text-[11px] text-zinc-400 uppercase">Charter Start Date</label>
                        <input type="date" id="rpmChStartDate" data-field="charter_start_date"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChEndDate" class="text-[11px] text-zinc-400 uppercase">Charter End Date</label>
                        <input type="date" id="rpmChEndDate" data-field="charter_end_date"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChLaytimeLoading" class="text-[11px] text-zinc-400 uppercase">Laytime - Loading (days)</label>
                        <input type="number" id="rpmChLaytimeLoading" data-field="laytime_loading_days"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChLaytimeUnloading" class="text-[11px] text-zinc-400 uppercase">Laytime - Unloading (days)</label>
                        <input type="number" id="rpmChLaytimeUnloading" data-field="laytime_unloading_days"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-center gap-2 mt-5">
                        <input type="checkbox" id="rpmChDemurrage" data-field="demurrage_charges" class="rounded border-zinc-300 dark:border-zinc-700">
                        <label for="rpmChDemurrage" class="text-sm text-zinc-700 dark:text-zinc-200">Demurrage Charges</label>
                    </div>
                    <div class="flex items-center gap-2 mt-5">
                        <input type="checkbox" id="rpmChLashing" data-field="lashing_service" class="rounded border-zinc-300 dark:border-zinc-700">
                        <label for="rpmChLashing" class="text-sm text-zinc-700 dark:text-zinc-200">Lashing Service</label>
                    </div>
                    <div class="flex items-center gap-2 mt-5">
                        <input type="checkbox" id="rpmChInsurance" data-field="insurance_services" class="rounded border-zinc-300 dark:border-zinc-700">
                        <label for="rpmChInsurance" class="text-sm text-zinc-700 dark:text-zinc-200">Insurance Services</label>
                    </div>
                    <div class="md:col-span-2">
                        <label for="rpmChOtherCharges" class="text-[11px] text-zinc-400 uppercase">Other Charges</label>
                        <input type="text" id="rpmChOtherCharges" data-field="other_charges"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChTermsOfPayment" class="text-[11px] text-zinc-400 uppercase">Terms of Payment</label>
                        <select id="rpmChTermsOfPayment" data-field="terms_of_payment"
                            class="rpmCharterTermsOfPaymentDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm"></select>
                    </div>
                    <div>
                        <label for="rpmChDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared Value</label>
                        <input type="number" step="0.01" id="rpmChDeclaredValue" data-field="declared_value"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label for="rpmChWeight" class="text-[11px] text-zinc-400 uppercase">Weight</label>
                        <div class="flex gap-2">
                            <input type="number" step="0.01" id="rpmChWeight" data-field="weight"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                            <select id="rpmChWeightUnit" data-field="weight_unit"
                                class="w-24 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-2 text-sm">
                                <option value="kg">kg</option>
                                <option value="mt">MT</option>
                            </select>
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label for="rpmChCargoManifest" class="text-[11px] text-zinc-400 uppercase">Cargo Manifest</label>
                        {{-- Prepared for a future upload endpoint - deliberately not wired to anything yet. --}}
                        <input type="file" id="rpmChCargoManifest"
                            class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" id="rpmAddProductBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium disabled:opacity-40 disabled:cursor-not-allowed">
                    + Add
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

<x-ancillary-services-modal />
<x-top-load-cargo-modal />
<x-charter-cargo-info-modal />
<x-charter-ports-modal />
