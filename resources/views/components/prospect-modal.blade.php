{{--
    Full-screen 3-tab "Prospect" FORM modal - replaces the old full-page CRM
    lead form (resources/views/pages/crmLeadForm.blade.php). This is the
    create/edit surface; once a prospect's Identity, Contact, and
    Requirements are all complete, clicking its row instead opens the
    read-only <x-prospect-info-modal /> (with an Edit button that reopens
    this modal) - see openProspectRecord() in resources/js/logic_prospect_modal.js.

    Not a staged/sequential form: all three tabs are freely navigable at any
    time (each tab's own save action guards itself if a prospect hasn't been
    created yet). Each tab shows a green check when its own required fields
    are complete - see updateTabGating().

    Opened via window.openProspectModal(uuid = null):
        window.openProspectModal();          // blank, create mode
        window.openProspectModal('abc-123');  // prefilled, edit mode
--}}
<x-modal id="ProspectModal" overlay-padding="p-0 md:p-4"
    class="relative rounded-none md:rounded-2xl md:min-w-[85vw] md:max-w-[95vw] h-full flex flex-col md:h-auto md:min-h-[78vh] md:max-h-[92vh]">

    {{--
        Shown the instant the modal opens/reopens (see showProspectModal()/
        setProspectModalLoading() in logic_prospect_modal.js) so a click
        never has to wait on a network round-trip before anything appears -
        the modal itself is already visible and interactive underneath.
    --}}
    <div id="pmLoadingOverlay"
        class="hidden absolute inset-0 z-20 flex items-center justify-center bg-white/70 dark:bg-zinc-900/70 backdrop-blur-sm rounded-none md:rounded-2xl">
        <div class="flex flex-col items-center gap-2">
            <div class="w-8 h-8 border-2 border-zinc-300 dark:border-zinc-600 border-t-orange-500 rounded-full animate-spin"></div>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">Loading…</p>
        </div>
    </div>

    {{--
        Header spans the full modal width edge-to-edge (no inner max-w
        container) - matches every other modal in the app (e.g.
        RequestProposalModal's header); this one used to be the only
        exception, boxed in at max-w-6xl inside a modal that's actually up
        to 95vw wide, which read as a narrower bar floating inside the panel
        instead of filling it.
    --}}
    <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-800">
        <div class="w-full flex justify-between items-center">
            <div>
                <p class="text-lg font-semibold text-zinc-900 dark:text-white" id="pmTitle">New Prospect</p>
                <p class="text-xs text-zinc-400 dark:text-zinc-500" id="pmSubtitle">Fill in the Prospect Identity tab to
                    get started.</p>
            </div>
            <div class="flex items-center gap-1">
                <button type="button" id="pmMinimizeBtn" title="Minimize"
                    class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    &ndash;
                </button>
                <button
                    class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800">
                    ✕
                </button>
            </div>
        </div>
        <p id="pmDraftIndicator" class="hidden w-full text-[11px] text-zinc-400 dark:text-zinc-500 mt-1"></p>
    </div>

    {{-- Body: same full-width rule - the tab rail + content (and each
         tab's own Save button, acting as this modal's footer) now stretch
         the full panel width instead of sitting in a centered box. --}}
    <div class="w-full flex gap-5 p-5 flex-1 overflow-y-auto">

        {{-- ============== RAIL: TAB NAV ============== --}}
        <div class="w-[13rem] shrink-0 flex flex-col gap-1.5" id="pmTabRail">
            <button type="button" id="pmTabBtnIdentity" data-pm-tab="Identity"
                class="pm-tab-btn w-full px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center gap-2 text-left border-orange-500 text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/20">
                <span
                    class="pm-tab-indicator flex items-center justify-center w-5 h-5 rounded-full shrink-0 text-[11px] font-bold"></span>
                <span>Prospect Identity</span>
            </button>
            <button type="button" id="pmTabBtnContact" data-pm-tab="Contact"
                class="pm-tab-btn w-full px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center gap-2 text-left border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300">
                <span
                    class="pm-tab-indicator flex items-center justify-center w-5 h-5 rounded-full shrink-0 text-[11px] font-bold"></span>
                <span>Prospect Contact Information</span>
            </button>
            <button type="button" id="pmTabBtnRequirements" data-pm-tab="Requirements"
                class="pm-tab-btn w-full px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center gap-2 text-left border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300">
                <span
                    class="pm-tab-indicator flex items-center justify-center w-5 h-5 rounded-full shrink-0 text-[11px] font-bold"></span>
                <span>Prospect Requirements</span>
            </button>
        </div>

        {{-- ============== TAB CONTENT ============== --}}
        <div class="flex-1 min-w-0">

            {{-- ============ TAB 1: PROSPECT IDENTITY ============ --}}
            <div class="pm-tab-pane space-y-6" data-pm-pane="Identity">
                <form id="pmIdentityForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">

                    {{-- Prospect Source --}}
                    <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3 relative">
                        <label for="pmSourceSelect"
                            class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            Prospect Source <span class="req-asterisk">*</span>
                        </label>
                        <select id="pmSourceSelect"
                            class="pmSourceDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                            <option value="">Select Source</option>
                        </select>
                        <p id="pmSourceDetailSummary" class="hidden text-xs text-zinc-500 dark:text-zinc-400 mt-1"></p>

                        {{-- Referral popover --}}
                        <div id="pmReferralPopover"
                            class="modaldropdown hidden absolute left-0 top-full mt-1 w-72 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 shadow-xl shadow-black/10 dark:shadow-black/40">
                            <label for="pmSourceReferralName"
                                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                Referred By
                            </label>
                            <input type="text" id="pmSourceReferralName" placeholder="Name of referral"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                            <div class="flex justify-end mt-3">
                                <button type="button"
                                    class="pm-popover-done text-xs px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white"
                                    data-pm-popover="pmReferralPopover">Done</button>
                            </div>
                        </div>

                        {{-- Social Media popover: 4-choice picker (hardcoded, not LOV-backed) --}}
                        <div id="pmSocialMediaPopover"
                            class="modaldropdown hidden absolute left-0 top-full mt-1 w-72 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 shadow-xl shadow-black/10 dark:shadow-black/40">
                            <p
                                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-2">
                                Which platform?
                            </p>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" aria-pressed="false"
                                    class="pm-social-platform-btn text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:border-orange-400 dark:hover:border-orange-500 text-zinc-700 dark:text-zinc-200"
                                    data-platform="Facebook">Facebook</button>
                                <button type="button" aria-pressed="false"
                                    class="pm-social-platform-btn text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:border-orange-400 dark:hover:border-orange-500 text-zinc-700 dark:text-zinc-200"
                                    data-platform="Instagram">Instagram</button>
                                <button type="button" aria-pressed="false"
                                    class="pm-social-platform-btn text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:border-orange-400 dark:hover:border-orange-500 text-zinc-700 dark:text-zinc-200"
                                    data-platform="Tiktok">Tiktok</button>
                                <button type="button" aria-pressed="false"
                                    class="pm-social-platform-btn text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:border-orange-400 dark:hover:border-orange-500 text-zinc-700 dark:text-zinc-200"
                                    data-platform="Website">Website</button>
                            </div>
                            <input type="hidden" id="pmSourceSocialPlatform">
                        </div>

                        {{-- Others popover --}}
                        <div id="pmOthersPopover"
                            class="modaldropdown hidden absolute left-0 top-full mt-1 w-72 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 shadow-xl shadow-black/10 dark:shadow-black/40">
                            <label for="pmSourceOthersText"
                                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                Specify Source
                            </label>
                            <input type="text" id="pmSourceOthersText" placeholder="Specify source"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                            <div class="flex justify-end mt-3">
                                <button type="button"
                                    class="pm-popover-done text-xs px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white"
                                    data-pm-popover="pmOthersPopover">Done</button>
                            </div>
                        </div>
                    </div>

                    {{-- Corporate/Individual is no longer a manual choice here - it's derived
                         from whichever Business Type is picked below (see
                         logic_prospect_modal.js's deriveClientTypeFromBusinessType()). --}}
                    <input type="hidden" id="pmClientType" value="corporate">

                    {{-- Prospect Name / Business Type --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label for="pmCompanyName"
                                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                Prospect Name <span class="req-asterisk">*</span>
                            </label>
                            <input type="text" id="pmCompanyName" required
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                        </div>
                        <div id="pmTypeOfBusinessField">
                            <label for="pmTypeOfBusiness"
                                class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                Business Type <span class="req-asterisk">*</span>
                            </label>
                            <select id="pmTypeOfBusiness"
                                class="pmTypeOfBusinessDropdown w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                                <option value="">Select Business Type</option>
                            </select>
                        </div>
                    </div>

                    {{-- Attending CSR --}}
                    <div>
                        <label
                            class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                            Attending CSR
                        </label>
                        <p id="pmAttendingCsr" class="text-sm font-medium text-zinc-800 dark:text-zinc-100 mt-2">
                            {{ Auth::user()->name }}</p>
                    </div>
                </div>

                {{-- Locations: right column. Added via the <x-pm-add-location-modal />
                     popup (see #pmAddLocationBtn's binding in logic_prospect_modal.js)
                     and rendered as a summary list, not inline editable cards. --}}
                <div class="md:border-l md:pl-6 dark:border-zinc-700">
                    <div class="flex justify-between items-center mb-3">
                        <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Location(s) <span
                                class="req-asterisk">*</span></p>
                        <button type="button" id="pmAddLocationBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                            + Add Location
                        </button>
                    </div>
                    <div id="pmLocationsContainer" class="space-y-2"></div>
                </div>
                </div>

                    <div class="flex justify-end gap-2 pt-4 border-t dark:border-zinc-700">
                        <button type="button" id="pmSaveIdentityBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                            Save Prospect Identity
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============ TAB 2: PROSPECT CONTACT INFORMATION ============ --}}
            <div class="pm-tab-pane hidden space-y-6" data-pm-pane="Contact">
                <div class="flex justify-between items-center">
                    <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Contact Person(s) <span
                            class="req-asterisk">*</span></p>
                    <button type="button" id="pmAddContactBtn"
                        class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                        + Add Contact
                    </button>
                </div>
                <div id="pmContactsContainer" class="space-y-4"></div>

                <div class="flex justify-end gap-2 pt-4 border-t dark:border-zinc-700">
                    <button type="button" id="pmSaveContactsBtn"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                        Save Contact Information
                    </button>
                </div>
            </div>

            {{-- ============ TAB 3: PROSPECT REQUIREMENTS ============ --}}
            <div class="pm-tab-pane hidden space-y-6" data-pm-pane="Requirements">

                {{--
                    "Prospect products" - independent of any Proposal
                    Request (no code/id here on purpose - see
                    Prospect::requirementContainers()/etc.).
                --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 p-4">
                    <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        Initial Information
                    </p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-0.5">
                        Freight, trucking, and charter requirements for this prospect.
                    </p>
                </div>

                {{--
                    Product tables - Freight (Container), Trucking, Charter -
                    always visible (each with its own "+ Add" button that
                    opens that product's own popup - see
                    <x-pm-add-freight-modal />/<x-pm-add-trucking-modal />/
                    <x-pm-add-charter-modal /> and openPmAddFreightModal()/
                    openPmAddTruckingModal()/openPmAddCharterModal() in
                    logic_prospect_modal.js). Column order in each MUST stay
                    in sync with its *TableRowHtml()` `cells` array there.
                --}}
                <div id="pmContainersTableWrap" class="space-y-2">
                    <div class="flex justify-between items-center">
                        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Freights</p>
                        <div class="flex items-center gap-3">
                            <span class="text-[11px] text-zinc-400 dark:text-zinc-500">scroll for more &rarr;</span>
                            <button type="button" id="pmAddFreightBtn"
                                class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                                + Add Freight
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto border border-zinc-200 dark:border-zinc-700 rounded-xl">
                        <table class="min-w-full text-xs border-separate border-spacing-0">
                            <thead
                                class="bg-zinc-50 dark:bg-zinc-800/50 text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                <tr>
                                    <th class="px-3 py-2 text-left sticky left-0 z-10 bg-zinc-50 dark:bg-zinc-800/50 border-r border-zinc-200 dark:border-zinc-700">Type / Size</th>
                                    <th class="px-3 py-2 text-left">Qty</th>
                                    <th class="px-3 py-2 text-left">Origin</th>
                                    <th class="px-3 py-2 text-left">Destination</th>
                                    <th class="px-3 py-2 text-left">Min Temp</th>
                                    <th class="px-3 py-2 text-left">Revenue Ton (RT)</th>
                                    <th class="px-3 py-2 text-left">Cargo Measurement</th>
                                    <th class="px-3 py-2 text-left">Frequency</th>
                                    <th class="px-3 py-2 text-left">Service Type</th>
                                    <th class="px-3 py-2 text-left">Declared Value</th>
                                    <th class="px-3 py-2 text-left">Weight</th>
                                    <th class="px-3 py-2 text-left">Cargo Type</th>
                                    <th class="px-3 py-2 text-left">Cargo Description</th>
                                    <th class="px-3 py-2 text-left">Special Requirements</th>
                                    <th class="px-3 py-2 text-left">Booking Unit Type</th>
                                    <th class="px-3 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pmContainersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="pmTruckingsTableWrap" class="space-y-2">
                    <div class="flex justify-between items-center">
                        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Truckings</p>
                        <button type="button" id="pmAddTruckingBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                            + Add Trucking
                        </button>
                    </div>
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
                                    <th class="px-3 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pmTruckingsTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="pmChartersTableWrap" class="space-y-2">
                    <div class="flex justify-between items-center">
                        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Charters</p>
                        <button type="button" id="pmAddCharterBtn"
                            class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                            + Add Charter
                        </button>
                    </div>
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
                                    <th class="px-3 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pmChartersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-modal>
