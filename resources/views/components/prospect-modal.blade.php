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
                class="pm-tab-btn w-full px-3 py-2 rounded-lg text-sm font-semibold border-2 flex items-center gap-2 text-left border-orange-500 text-orange-600 bg-orange-50 dark:bg-orange-950/20">
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
                                <button type="button"
                                    class="pm-social-platform-btn text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:border-orange-400 dark:hover:border-orange-500 text-zinc-700 dark:text-zinc-200"
                                    data-platform="Facebook">Facebook</button>
                                <button type="button"
                                    class="pm-social-platform-btn text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:border-orange-400 dark:hover:border-orange-500 text-zinc-700 dark:text-zinc-200"
                                    data-platform="Instagram">Instagram</button>
                                <button type="button"
                                    class="pm-social-platform-btn text-sm px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:border-orange-400 dark:hover:border-orange-500 text-zinc-700 dark:text-zinc-200"
                                    data-platform="Tiktok">Tiktok</button>
                                <button type="button"
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

                    {{-- Locations --}}
                    <div class="border-t dark:border-zinc-700 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Location(s) <span
                                    class="req-asterisk">*</span></p>
                            <button type="button" id="pmAddLocationBtn"
                                class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                                + Add Location
                            </button>
                        </div>
                        <div id="pmLocationsContainer" class="space-y-4"></div>
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
                    each hidden until its first row is added (per brief:
                    "initially there is no table on the tab"). Independent of
                    which product is currently selected below; all three stay
                    visible together once populated. Column order in each
                    MUST stay in sync with its *TableRowHtml()` `cells` array
                    in logic_prospect_modal.js.
                --}}
                <div id="pmContainersTableWrap" class="hidden space-y-2">
                    <div class="flex justify-between items-baseline">
                        <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Freights</p>
                        <span class="text-[11px] text-zinc-400 dark:text-zinc-500">scroll for more &rarr;</span>
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

                <div id="pmTruckingsTableWrap" class="hidden space-y-2">
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
                                    <th class="px-3 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pmTruckingsTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="pmChartersTableWrap" class="hidden space-y-2">
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
                                    <th class="px-3 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pmChartersTableBody" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                {{--
                    Product picker - one "Add Requirement" area shared by all
                    three products. Starts blank (no pane shown, per brief)
                    and swaps which pane is visible on change - see
                    bindProductSelector() in logic_prospect_modal.js.
                --}}
                <div class="border-t dark:border-zinc-700 pt-4">
                    <label for="pmRequirementProduct"
                        class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        Product
                    </label>
                    <select id="pmRequirementProduct"
                        class="w-full md:w-64 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm mt-1">
                        <option value="">Select Product</option>
                        <option value="freight">Freight</option>
                        <option value="trucking">Trucking</option>
                        <option value="charter">Charter</option>
                    </select>
                </div>

                {{-- Freight (Container) pane --}}
                <div id="pmProductPaneFreight"
                    class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
                    <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Add Booking Requirement</p>

                    <div id="pmContainerForm" class="space-y-4">
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <p
                                    class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">
                                    Container &amp; Quantity</p>
                            </div>

                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmContainerType" class="text-[11px] text-zinc-400 uppercase">Container
                                    Type <span class="req-asterisk">*</span></label>
                                <select id="pmContainerType" data-field="container_type"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Type</option>
                                </select>
                            </div>
                            <div
                                class="field-convan-size hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmContainerSize" class="text-[11px] text-zinc-400 uppercase">Size <span
                                        class="req-asterisk">*</span></label>
                                <select id="pmContainerSize" data-field="container_size_id"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Size</option>
                                </select>
                            </div>
                            <div
                                class="field-temperature hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmMinTemp" class="text-[11px] text-zinc-400 uppercase">Minimum Temperature
                                    (&deg;C) <span class="req-asterisk">*</span></label>
                                <input type="number" step="0.1" id="pmMinTemp" data-field="minimum_temperature"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div
                                class="field-revenue-ton hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmRevenueTon" class="text-[11px] text-zinc-400 uppercase"
                                    title="Billing unit for cargo with no fixed container size - whichever is greater, weight or space taken up.">Revenue Ton (RT)
                                    Unit <span class="req-asterisk">*</span> <span class="text-zinc-300 dark:text-zinc-600">ⓘ</span></label>
                                <input type="number" step="0.01" id="pmRevenueTon" data-field="revenue_ton"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div
                                class="field-revenue-ton hidden border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmCargoMeasurement" class="text-[11px] text-zinc-400 uppercase">Cargo
                                    Measurement</label>
                                <input type="text" id="pmCargoMeasurement" data-field="cargo_measurement"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmContainerQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity
                                    <span class="req-asterisk">*</span></label>
                                <input type="number" id="pmContainerQuantity" data-field="quantity"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmContainerFrequency"
                                    class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                                <select id="pmContainerFrequency" data-field="frequency"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">-</option>
                                    <option value="daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                            <div>
                                <p
                                    class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">
                                    Service &amp; Route</p>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmDeliveryType" class="text-[11px] text-zinc-400 uppercase"
                                    title="Door = picked up/delivered at the client's own address. Pier = handled only at the port.">Service Type
                                    <span class="req-asterisk">*</span> <span class="text-zinc-300 dark:text-zinc-600">ⓘ</span></label>
                                <select id="pmDeliveryType" data-field="service_type"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Service Type</option>
                                </select>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmOriginLocation" class="text-[11px] text-zinc-400 uppercase">Origin <span
                                        class="req-asterisk">*</span></label>
                                <select id="pmOriginLocation" data-field="origin_location_id"
                                    class="pmOriginLocationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Location</option>
                                </select>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmDestinationLocation"
                                    class="text-[11px] text-zinc-400 uppercase">Destination <span
                                        class="req-asterisk">*</span></label>
                                <select id="pmDestinationLocation" data-field="destination_location_id"
                                    class="pmDestinationLocationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Location</option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                            <div>
                                <p
                                    class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">
                                    Cargo Details</p>
                            </div>
                            <div>
                                <label for="pmCargoType" class="text-[11px] text-zinc-400 uppercase">Cargo
                                    Type</label>
                                <select id="pmCargoType" data-field="cargo_type"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Cargo Type</option>
                                </select>
                            </div>
                            <div>
                                <label for="pmDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared
                                    Value per Unit</label>
                                <input type="text" inputmode="decimal" id="pmDeclaredValue"
                                    data-field="declared_value_per_unit"
                                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div>
                                <label for="pmWeight" class="text-[11px] text-zinc-400 uppercase">Weight</label>
                                <div class="flex gap-2">
                                    <input type="number" step="0.01" id="pmWeight" data-field="weight"
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <select id="pmWeightUnit" data-field="weight_unit"
                                        class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                        <option value="">Unit</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="mt">Metric Ton</option>
                                    </select>
                                </div>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmGeneralCargoDescription"
                                    class="text-[11px] text-zinc-400 uppercase">General Cargo Description <span
                                        class="req-asterisk">*</span></label>
                                <textarea id="pmGeneralCargoDescription" data-field="general_cargo_description" rows="2"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                            </div>
                            <div>
                                <label for="pmSpecialRequirements" class="text-[11px] text-zinc-400 uppercase">Special
                                    Requirements</label>
                                <textarea id="pmSpecialRequirements" data-field="special_requirements" rows="2"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trucking pane --}}
                <div id="pmProductPaneTrucking"
                    class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-3">
                    <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Add Trucking Requirement</p>

                    <div id="pmTruckingForm" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmTruckingCargoType" class="text-[11px] text-zinc-400 uppercase">Trucking
                                    Cargo Type <span class="req-asterisk">*</span></label>
                                <select id="pmTruckingCargoType" data-field="trucking_cargo_type"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Type</option>
                                </select>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmTruckingQuantity" class="text-[11px] text-zinc-400 uppercase">Quantity
                                    <span class="req-asterisk">*</span></label>
                                <input type="number" id="pmTruckingQuantity" data-field="quantity"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div>
                                <label for="pmTruckingFrequency"
                                    class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                                <select id="pmTruckingFrequency" data-field="frequency"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">-</option>
                                    <option value="daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmDispatchMode" class="text-[11px] text-zinc-400 uppercase"
                                    title="Single = one truck. Tandem = two trucks working together, for heavier/oversized loads.">Dispatch Mode
                                    <span class="req-asterisk">*</span> <span class="text-zinc-300 dark:text-zinc-600">ⓘ</span></label>
                                <select id="pmDispatchMode" data-field="dispatch_mode"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Mode</option>
                                    <option value="single">Single</option>
                                    <option value="tandem">Tandem</option>
                                </select>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmTruckingOrigin" class="text-[11px] text-zinc-400 uppercase">Origin <span
                                        class="req-asterisk">*</span></label>
                                <select id="pmTruckingOrigin" data-field="origin_location_id"
                                    class="pmTruckingOriginSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Location</option>
                                </select>
                            </div>
                            <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmTruckingDestination"
                                    class="text-[11px] text-zinc-400 uppercase">Destination <span
                                        class="req-asterisk">*</span></label>
                                <select id="pmTruckingDestination" data-field="destination_location_id"
                                    class="pmTruckingDestinationSelect w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Location</option>
                                </select>
                            </div>
                            <div>
                                <label for="pmTruckingDeclaredValue"
                                    class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                                <input type="text" inputmode="decimal" id="pmTruckingDeclaredValue"
                                    data-field="declared_value_per_unit"
                                    class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div>
                                <label class="text-[11px] text-zinc-400 uppercase">Weight</label>
                                <div class="flex gap-2">
                                    <input type="number" step="0.01" id="pmTruckingWeight" data-field="weight"
                                        class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <select id="pmTruckingWeightUnit" data-field="weight_unit"
                                        class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                        <option value="">Unit</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="mt">Metric Ton</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="pmTruckingCargoTypeGeneral"
                                    class="text-[11px] text-zinc-400 uppercase">General Cargo Type</label>
                                <select id="pmTruckingCargoTypeGeneral" data-field="cargo_type"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Select Cargo Type</option>
                                </select>
                            </div>
                            <div class="md:col-span-3 border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                                <label for="pmTruckingCargoDescription"
                                    class="text-[11px] text-zinc-400 uppercase">Cargo Description <span
                                        class="req-asterisk">*</span></label>
                                <textarea id="pmTruckingCargoDescription" data-field="general_cargo_description" rows="2"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                            </div>
                            <div class="md:col-span-3">
                                <label for="pmTruckingSpecialRequirements"
                                    class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                                <textarea id="pmTruckingSpecialRequirements" data-field="special_requirements" rows="2"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charter pane - each booking owns its own repeatable
                     Cargo list and Port list, entered inline here and
                     summarized as one table row (see charterTableRowHtml()). --}}
                <div id="pmProductPaneCharter"
                    class="hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-4">
                    <p class="font-semibold text-zinc-700 dark:text-zinc-200 text-sm">Add Charter Requirement</p>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <p
                                class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                                Cargo <span class="req-asterisk">*</span></p>
                            <button type="button" id="pmAddCharterCargoBtn"
                                class="text-xs px-2 py-1 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                                + Add Cargo
                            </button>
                        </div>
                        <div id="pmCharterCargoContainer" class="space-y-2"></div>
                    </div>

                    <div class="border-t dark:border-zinc-700 pt-3">
                        <div class="flex justify-between items-center mb-2">
                            <p
                                class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                                Ports <span class="req-asterisk">*</span></p>
                            <button type="button" id="pmAddCharterPortBtn"
                                class="text-xs px-2 py-1 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-200">
                                + Add Port
                            </button>
                        </div>
                        <div id="pmCharterPortsContainer" class="space-y-2"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 border-t dark:border-zinc-700 pt-3">
                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label for="pmCharterStartDate" class="text-[11px] text-zinc-400 uppercase">Charter Start
                                Date <span class="req-asterisk">*</span></label>
                            <input type="date" id="pmCharterStartDate" data-field="charter_start_date"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        </div>
                        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3">
                            <label for="pmCharterEndDate" class="text-[11px] text-zinc-400 uppercase">Charter End Date
                                <span class="req-asterisk">*</span></label>
                            <input type="date" id="pmCharterEndDate" data-field="charter_end_date"
                                class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        </div>
                        <div>
                            <label for="pmCharterDeclaredValue" class="text-[11px] text-zinc-400 uppercase">Declared
                                Value</label>
                            <input type="text" inputmode="decimal" id="pmCharterDeclaredValue"
                                data-field="declared_value"
                                class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="text-[11px] text-zinc-400 uppercase">Weight</label>
                            <div class="flex gap-2 md:w-1/3">
                                <input type="number" step="0.01" id="pmCharterWeight" data-field="weight"
                                    class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                <select id="pmCharterWeightUnit" data-field="weight_unit"
                                    class="w-28 shrink-0 border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Unit</option>
                                    <option value="kg">Kilogram</option>
                                    <option value="mt">Metric Ton</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shared submit - routes to the currently-selected
                     product's add function, see #pmRequirementProduct's
                     change handler / pmAddRequirementBtn's click handler. --}}
                <div id="pmAddRequirementBtnWrap" class="hidden flex justify-end pt-2">
                    <button type="button" id="pmAddRequirementBtn"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        + Add Requirement
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-modal>
