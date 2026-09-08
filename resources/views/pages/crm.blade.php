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
                + New Lead
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
            <div class="pipeline-segment bg-gray-400" data-status="LEAD" style="width: 0%"></div>
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
                data-status="LEAD">
                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                LEAD <span id="countLead">0</span>
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




<x-modal id="LeadInfoModal" max-width="lg:max-w-[1100px]">

    {{-- Header --}}
    <div class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 rounded-t-2xl">
        <div class="p-5 flex justify-between items-center">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100" id="leadCompanyName">Company Name
                    </p>
                    <div id="leadStatus"></div>
                    <div id="leadCustomerCode"></div>

                    {{-- Change Stage --}}
                    <div class="relative">
                        <button id="changeStageBtn" type="button" aria-haspopup="true" aria-expanded="false"
                            aria-controls="changeStageDropdown"
                            class="text-[11px] font-medium uppercase tracking-widest px-2 py-1 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">
                            Change Stage
                        </button>

                        <div id="changeStageDropdown"
                            class="modaldropdown hidden absolute left-0 top-9 w-64 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 shadow-xl shadow-black/10 dark:shadow-black/40">
                            <p
                                class="text-xs font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-3">
                                Change Stage</p>
                            <select id="leadStageSelect"
                                class="statusDropDown w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <option value="">Select status</option>
                            </select>
                            <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                                <button id="cancelStageBtn" type="button"
                                    class="px-3 py-1.5 text-xs font-medium text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-lg transition">
                                    Cancel
                                </button>
                                <button id="saveStageBtn" type="button"
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500">
                    Lead created <span id="leadCreatedAt">-</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button id="createClientMasterBtn" class="p-2 bg-orange-600 rounded-lg text-white text-sm hidden">
                    <b class="font-black">+</b> Record
                </button>
                <button
                    class="modal-close text-zinc-400 hover:text-zinc-600 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-zinc-100">
                    ✕
                </button>
            </div>
        </div>

        {{-- Stat strip --}}
        <div
            class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-zinc-200 dark:divide-zinc-700 border-t border-zinc-200 dark:border-zinc-700">
            <div class="px-5 py-3">
                <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Deal
                    Value</p>
                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100" id="leadEstimatedValue">-</p>
            </div>
            <div class="px-5 py-3">
                <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Assigned
                    Rep</p>
                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100" id="leadAssignedTo">-</p>
            </div>
            <div class="px-5 py-3">
                <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Primary
                    Contact</p>
                <div class="flex items-baseline gap-1.5">
                    <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100" id="leadContactName">-</p>
                    <p class="text-[11px] text-zinc-400 dark:text-zinc-500" id="leadMobile">-</p>
                </div>
            </div>
            <div class="px-5 py-3">
                <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Expected
                    Close</p>
                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100" id="leadExpectedCloseDate">-</p>
            </div>
        </div>
    </div>

    <div class="flex gap-5 p-5 bg-zinc-50 dark:bg-zinc-900 max-h-[75vh]">

        {{-- ============== RAIL ============== --}}
        <div class="w-[19rem] shrink-0 overflow-y-auto flex flex-col gap-4 pr-1">

            {{-- Contact (always visible) --}}
            <div class="relative bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Contact</p>
                    <button id="editContactBtn" aria-haspopup="true" aria-expanded="false"
                        aria-controls="editContactInfoDropdown"
                        class="text-zinc-400 hover:text-zinc-600 p-1 rounded-md hover:bg-zinc-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                        </svg>
                    </button>
                </div>

                {{-- Edit contact info dropdown --}}
                <div id="editContactInfoDropdown"
                    class="modaldropdown hidden absolute right-0 top-11 w-60 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 shadow-xl shadow-black/10 dark:shadow-black/40">

                    <p class="text-xs font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-4">Edit
                        Contact Information
                    </p>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col gap-1">
                            <label for="contactName"
                                class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Contact
                                Name</label>
                            <input type="text" name="contactName" id="contactName"
                                class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="contactEmail"
                                class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Contact
                                Email</label>
                            <input type="email" name="contactEmail" id="contactEmail"
                                class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="contactMobile"
                                class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Contact
                                Mobile</label>
                            <input type="text" name="contactMobile" id="contactMobile"
                                class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700">
                        <button id="cancelContactInfoBtn"
                            class="px-4 py-1.5 text-sm font-medium text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-lg transition">
                            Cancel
                        </button>
                        <button id="saveContactInfoBtn"
                            class="px-4 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                            Save
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-3 text-sm">
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Client Type</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadClientType">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Position</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadPosition">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Email</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadEmail">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Landline</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadLandline">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Source</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadSource">-</p>
                    </div>
                </div>
            </div>

            {{-- Company (expanded by default) --}}
            <details open class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <summary
                    class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest cursor-pointer select-none">
                    Company</summary>
                <div class="flex flex-col gap-3 text-sm mt-3">
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Company Name</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadCompanyNameFull">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Type of Business</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadTypeOfBusiness">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Industry Description</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadIndustryDescription">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Authorized Signatory</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadAuthorizedSignatoryName">-</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Signatory Position</p>
                        <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadAuthorizedSignatoryPosition">-
                        </p>
                    </div>
                </div>
            </details>

            {{-- Addresses (collapsed by default) --}}
            <details class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                <summary
                    class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest cursor-pointer select-none">
                    Addresses</summary>
                <div id="leadAddressListContainer" class="flex flex-col gap-2 max-h-72 overflow-auto p-0.5 mt-3">
                </div>
            </details>
        </div>

        {{-- ============== TABBED PANE ============== --}}
        <div class="flex-1 min-w-0 flex flex-col overflow-hidden">

            {{-- Tab bar --}}
            <div class="flex border-b border-zinc-200 dark:border-zinc-700 mb-4 shrink-0">
                <button type="button" id="tabBtnProposals"
                    class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition flex items-center gap-1.5 border-orange-500 text-orange-600">
                    Proposals
                    <span id="tabBadgeProposals" class="hidden w-1.5 h-1.5 rounded-full bg-red-500"></span>
                </button>
                <button type="button" id="tabBtnRequirements"
                    class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition flex items-center gap-1.5 border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                    Requirements
                    <span id="tabBadgeRequirements"
                        class="inline-flex items-center justify-center min-w-[1.1rem] h-[1.1rem] px-1 text-[10px] font-semibold rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300">0</span>
                </button>
                <button type="button" id="tabBtnActivity"
                    class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition flex items-center gap-1.5 border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                    Activity
                    <span id="tabBadgeActivity"
                        class="inline-flex items-center justify-center min-w-[1.1rem] h-[1.1rem] px-1 text-[10px] font-semibold rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300">0</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto pr-0.5">

                {{-- ============== TAB: PROPOSALS ============== --}}
                <div id="tabPaneProposals" class="tabcontent">
                    <div class="flex justify-end mb-3">
                        <button id="leadAddProposalBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                            + New Proposal
                        </button>
                    </div>
                    <div id="leadProposalContainer"
                        class="border border-zinc-300 dark:border-zinc-600 rounded-lg flex flex-col h-full overflow-auto p-1 gap-1">
                    </div>
                    <div id="leadProposalsPagination"></div>
                </div>

                {{-- ============== TAB: REQUIREMENTS ============== --}}
                <div id="tabPaneRequirements" class="tabcontent hidden">
                    <div class="flex justify-end mb-3">
                        <button id="leadAddContainerBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                            + Add Booking Requirement
                        </button>
                    </div>
                    <div id="leadContainerListContainer" class="flex flex-col gap-2 max-h-[60vh] overflow-auto p-0.5">
                    </div>
                </div>

                {{-- ============== TAB: ACTIVITY (merged timeline) ============== --}}
                <div id="tabPaneActivity" class="tabcontent hidden">
                    <div
                        class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-3 flex items-center gap-2 mb-3">
                        <div class="flex items-center gap-1 bg-zinc-100 dark:bg-zinc-900 rounded-lg p-1 shrink-0">
                            <button type="button" data-type="Note"
                                class="timeline-type-btn active px-3 py-1.5 text-xs font-semibold rounded-md bg-white dark:bg-zinc-700 text-orange-600 dark:text-orange-400 shadow-sm">
                                Note</button>
                            <button type="button" data-type="Call"
                                class="timeline-type-btn px-3 py-1.5 text-xs font-semibold rounded-md text-zinc-500 dark:text-zinc-400">
                                Call</button>
                            <button type="button" data-type="Email"
                                class="timeline-type-btn px-3 py-1.5 text-xs font-semibold rounded-md text-zinc-500 dark:text-zinc-400">
                                Email</button>
                        </div>
                        <input type="text" id="timelineEntryInput" placeholder="Log an update on this lead…"
                            class="flex-1 min-w-0 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                        <button type="button" id="timelineAttachmentToggleBtn" title="Attach file"
                            class="w-9 h-9 flex items-center justify-center rounded-lg text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-700 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                                </path>
                            </svg>
                        </button>
                        <button type="button" id="timelineAddBtn"
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm shrink-0">
                            Add
                        </button>
                    </div>

                    <div id="timelineAttachmentRow" class="hidden mb-3">
                        <input type="file" id="timelineAttachmentInput" name="attachment"
                            class="block w-full text-xs text-zinc-700 dark:text-zinc-300
                                file:mr-2 file:py-1.5 file:px-3
                                file:rounded-lg file:border-0
                                file:text-xs file:font-medium
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                cursor-pointer
                                bg-zinc-50 dark:bg-zinc-900
                                border border-zinc-200 dark:border-zinc-700
                                rounded-lg p-1.5
                                focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div id="leadTimelineContainer" class="flex flex-col max-h-[26rem] overflow-y-auto pr-1"></div>
                </div>

            </div>
        </div>

    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-700 px-5 py-4 flex justify-end gap-2 bg-zinc-50 dark:bg-zinc-800 rounded-b-2xl">
        <button
            class="modal-close rounded-lg border border-zinc-300 px-4 py-2 text-zinc-700 bg-white hover:bg-zinc-100">
            Close
        </button>
    </div>

</x-modal>


{{-- Add Proposal side-modal (lead-scoped: creates/appends to ClientProposal records tied to this lead) --}}
<x-side-modal id="LeadAddProposalModal">
    <div
        class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
        <p class="text-lg font-semibold dark:text-white">New Proposal</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div class="p-5">
        <div class="flex justify-between items-center mb-3">
            <p class="font-semibold text-zinc-700 dark:text-zinc-300 text-sm">Container Lines</p>
            <button type="button" id="leadProposalAddRowBtn"
                class="text-xs px-3 py-1.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">+
                Add Container</button>
        </div>
        <div id="leadProposalRatesContainer" class="space-y-3"></div>
    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
        <button type="button"
            class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">Cancel</button>
        <button type="button" id="leadProposalSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save Proposal</button>
    </div>
</x-side-modal>

{{-- Add Container side-modal (appends one CrmLeadContainer requirement row to this lead, without touching the existing ones) --}}
<x-side-modal id="LeadAddContainerModal">
    <div
        class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center sticky top-0 bg-white dark:bg-zinc-900 z-10">
        <p class="text-lg font-semibold dark:text-white">Add Booking Requirement</p>
        <button class="modal-close text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">✕</button>
    </div>

    <div class="p-5">
        <div id="leadContainerFormWrap"></div>
    </div>

    <div
        class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-zinc-900">
        <button type="button"
            class="modal-close px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">Cancel</button>
        <button type="button" id="leadContainerSaveBtn"
            class="px-4 py-2 text-sm rounded-lg bg-orange-500 hover:bg-orange-600 text-white">Save Container</button>
    </div>
</x-side-modal>

<script>
    (function() {
        initCrmLogic();

        // Set while the LeadAddProposalModal is open: 'create' posts a brand new
        // lead-scoped ClientProposal, 'append' adds container lines to an
        // existing one (proposalId set).
        let proposalModalContext = {
            mode: 'create',
            proposalId: null
        };

        document.getElementById('leadAddProposalBtn').addEventListener('click', async function() {
            proposalModalContext = {
                mode: 'create',
                proposalId: null
            };
            document.getElementById('leadProposalRatesContainer').innerHTML = '';
            await loadContainerLookups();
            await prefillFromLeadContainers();
            initSideModal({
                modalId: 'LeadAddProposalModal'
            });
        });

        // Best-effort pre-fill: one row per container requirement captured on
        // the lead (Stage 2), via ClientProposalController::leadContainerDefaults.
        // Falls back to a single empty row when the lead has none, or the
        // lookup can't resolve a rate.
        async function prefillFromLeadContainers() {
            const response = await apiCall({
                mode: 'GET',
                url: `/api/crm/leads/${window.currentLeadUuid}/proposalContainerDefaults`,
            });

            const defaults = response.success ? (response.data ?? []) : [];

            if (!defaults.length) {
                addProposalRow();
                return;
            }

            defaults.forEach((def) => {
                addProposalRow();
                const rows = document.querySelectorAll('#leadProposalRatesContainer [data-row]');
                applyRowDefaults(rows[rows.length - 1], def);
            });
        }

        function applyRowDefaults(row, def) {
            const originSel = row.querySelector('[data-field="origin_port_id"]');
            const destSel = row.querySelector('[data-field="destination_port_id"]');
            const containerSel = row.querySelector('.container-select');
            const classSel = row.querySelector('.class-select');
            const sizeSel = row.querySelector('.size-select');
            const variantInput = row.querySelector('[data-field="container_variant_id"]');
            const baseRateInput = row.querySelector('.base-rate');

            if (def.origin_port_id) {
                originSel.value = def.origin_port_id;
                originSel.disabled = false;
                const originPort = portsData.find((p) => String(p.port_id) === String(def.origin_port_id));
                if (originPort) row.querySelector('.origin-location-select').value = originPort.location_id ?? '';
            }
            if (def.destination_port_id) {
                destSel.value = def.destination_port_id;
                destSel.disabled = false;
                const destPort = portsData.find((p) => String(p.port_id) === String(def.destination_port_id));
                if (destPort) row.querySelector('.destination-location-select').value = destPort.location_id ?? '';
            }

            if (def.container_id) {
                containerSel.value = def.container_id;
                containerSel.dispatchEvent(new Event('change'));
            }
            // classSel always has a real value here - base (no-class) variants
            // use the synthetic "__base__" option, not null/empty, so a
            // class-only variant (no size) is still reachable via classSel.
            classSel.value = def.container_class_id ?? '__base__';
            classSel.dispatchEvent(new Event('change'));
            // sizeSel is keyed by variant id (not container_size_id) so it
            // stays correct for class-only variants with no size at all.
            if (def.container_variant_id) sizeSel.value = def.container_variant_id;
            if (def.container_variant_id) variantInput.value = def.container_variant_id;
            if (def.base_rate) baseRateInput.value = Number(def.base_rate).toFixed(2);

            recomputeFinalRate(row);

            [
                row.querySelector('.origin-location-select'),
                row.querySelector('.destination-location-select'),
                originSel,
                destSel,
            ].forEach(refreshSearchable);
        }

        window.openLeadAddContainerModal = function(proposalId) {
            proposalModalContext = {
                mode: 'append',
                proposalId
            };
            document.getElementById('leadProposalRatesContainer').innerHTML = '';
            loadContainerLookups().then(() => addProposalRow());
            initSideModal({
                modalId: 'LeadAddProposalModal'
            });
        };

        // ================= PROPOSAL ROW BUILDER =================
        let portsOptionsHtml = '';
        let locationsOptionsHtml = '';
        let portsData = [];
        let containerVariantsData = [];

        async function loadContainerLookups() {
            const [portsRes, locationsRes, variantsRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/locations?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containers/variants'
                }),
            ]);

            if (portsRes.success) {
                portsData = portsRes.data.data;
                portsOptionsHtml = portsData
                    .map((p) => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`)
                    .join('');
            }
            if (locationsRes.success) {
                locationsOptionsHtml = locationsRes.data.data
                    .map((l) => `<option value="${l.location_id}">${l.name}</option>`)
                    .join('');
            }
            if (variantsRes.success) {
                containerVariantsData = variantsRes.data;
            }
        }

        function portOptionsForLocation(locationId) {
            const ports = locationId ?
                portsData.filter((p) => String(p.location_id) === String(locationId)) :
                portsData;
            return ports.map((p) => `<option value="${p.port_id}">${p.name}</option>`).join('');
        }

        function refreshSearchable(el) {
            el?._searchableSelect?.refresh();
        }

        function classIdForPayload(sel) {
            const v = sel.value;
            return (v === '' || v === '__base__') ? null : v;
        }

        function sizeIdForPayload(sel) {
            const id = sel.options[sel.selectedIndex]?.dataset.sizeId;
            return id ? id : null;
        }

        function uniqueContainerOptions() {
            const seen = new Set();
            return containerVariantsData
                .filter((v) => {
                    if (seen.has(v.container.id)) return false;
                    seen.add(v.container.id);
                    return true;
                })
                .map((v) => `<option value="${v.container.id}">${v.container.name}</option>`)
                .join('');
        }

        function addProposalRow() {
            const wrap = document.getElementById('leadProposalRatesContainer');
            const div = document.createElement('div');
            div.className = 'border rounded-lg p-3 space-y-2';
            div.dataset.row = '';
            div.innerHTML = `
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Origin Location</label>
                        <select class="origin-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">All Locations</option>${locationsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Origin Port</label>
                        <select data-field="origin_port_id" disabled class="origin-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                            <option value="">Select</option>${portsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination Location</label>
                        <select class="destination-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">All Locations</option>${locationsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination Port</label>
                        <select data-field="destination_port_id" disabled class="destination-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                            <option value="">Select</option>${portsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Container</label>
                        <select data-field="container_id" class="container-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${uniqueContainerOptions()}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Class</label>
                        <select data-field="container_class_id" class="class-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select container first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Size</label>
                        <select data-field="container_size_id" class="size-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select class first</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Min Qty (for discount)</label>
                        <input type="number" min="1" step="1" data-field="min_van_qty" placeholder="No minimum" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Rate (FRT)</label>
                        <input type="text" inputmode="decimal" data-field="base_rate" readonly class="base-rate currency-input w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" value="0.00">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Discount Type</label>
                        <select data-field="discount_type" class="discount-type w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">None</option>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Discount Value</label>
                        <input type="text" inputmode="decimal" data-field="discount_value" class="discount-value currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" value="0">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Final Rate</label>
                        <input type="text" inputmode="decimal" data-field="final_rate" readonly class="final-rate currency-input w-full border border-blue-200 dark:border-blue-800 rounded-lg px-2 py-1.5 text-sm bg-blue-50 dark:bg-blue-900/40 text-zinc-900 dark:text-blue-100 font-semibold" value="0.00">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="remove-row text-red-500 text-xs">✕ Remove container</button>
                </div>
                <input type="hidden" data-field="container_variant_id">
            `;
            wrap.appendChild(div);
            wireRow(div);
        }

        function wireRow(row) {
            const originSel = row.querySelector('[data-field="origin_port_id"]');
            const destSel = row.querySelector('[data-field="destination_port_id"]');
            const originLocationSel = row.querySelector('.origin-location-select');
            const destLocationSel = row.querySelector('.destination-location-select');
            [originLocationSel, originSel, destLocationSel, destSel].forEach((el) => makeSearchableSelect(el));
            const containerSel = row.querySelector('.container-select');
            const classSel = row.querySelector('.class-select');
            const sizeSel = row.querySelector('.size-select');
            const variantInput = row.querySelector('[data-field="container_variant_id"]');
            const baseRateInput = row.querySelector('.base-rate');
            const discountTypeSel = row.querySelector('.discount-type');
            const discountValueInput = row.querySelector('.discount-value');
            const finalRateInput = row.querySelector('.final-rate');

            containerSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const variantsForContainer = containerVariantsData.filter((v) => String(v.container
                    .id) === containerId);
                const classes = [...new Map(
                    variantsForContainer
                    .filter((v) => v.container_class)
                    .map((v) => [v.container_class.id, v.container_class])
                ).values()];
                const hasBase = variantsForContainer.some((v) => !v.container_class);

                classSel.innerHTML = `<option value="">Select</option>` +
                    (hasBase ? `<option value="__base__">Base (No Class)</option>` : '') +
                    classes.map((c) => `<option value="${c.id}">${c.class}</option>`).join('');
                sizeSel.innerHTML = `<option value="">Select class first</option>`;
                variantInput.value = '';
                resetRate(baseRateInput, finalRateInput);
            });

            classSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const classId = classSel.value;
                const sizes = containerVariantsData.filter(
                    (v) => String(v.container.id) === containerId && (classId === '__base__' ? !v
                        .container_class : String(v.container_class?.id) === classId)
                );

                // Value/key on the variant id (not container_size.id) so this
                // stays safe for class-only variants with no size at all
                // (Loose Cargo / Rolling Cargo, priced by class alone).
                sizeSel.innerHTML = `<option value="">Select</option>` +
                    sizes.map((v) =>
                        `<option value="${v.id}" data-variant-id="${v.id}" data-size-id="${v.container_size?.id ?? ''}">${v.container_size?.size ?? 'N/A (no fixed size)'}</option>`
                    ).join('');
                variantInput.value = '';
                resetRate(baseRateInput, finalRateInput);
            });

            sizeSel.addEventListener('change', () => {
                const selected = sizeSel.options[sizeSel.selectedIndex];
                variantInput.value = selected?.dataset.variantId ?? '';
                lookupRate(row);
            });

            [originSel, destSel].forEach((sel) => sel.addEventListener('change', () => lookupRate(row)));

            originLocationSel.addEventListener('change', () => {
                originSel.innerHTML =
                    `<option value="">Select</option>${portOptionsForLocation(originLocationSel.value)}`;
                originSel.disabled = !originLocationSel.value;
                refreshSearchable(originSel);
                lookupRate(row);
            });
            destLocationSel.addEventListener('change', () => {
                destSel.innerHTML =
                    `<option value="">Select</option>${portOptionsForLocation(destLocationSel.value)}`;
                destSel.disabled = !destLocationSel.value;
                refreshSearchable(destSel);
                lookupRate(row);
            });

            discountTypeSel.addEventListener('change', () => recomputeFinalRate(row));
            discountValueInput.addEventListener('input', () => recomputeFinalRate(row));

            row.querySelector('.remove-row').addEventListener('click', () => row.remove());

            function resetRate(baseEl, finalEl) {
                baseEl.value = '0.00';
                finalEl.value = '0.00';
            }
        }

        async function lookupRate(row) {
            const originId = row.querySelector('[data-field="origin_port_id"]').value;
            const destId = row.querySelector('[data-field="destination_port_id"]').value;
            const variantId = row.querySelector('[data-field="container_variant_id"]').value;
            const baseRateInput = row.querySelector('.base-rate');

            if (!originId || !destId || !variantId) return;

            const response = await apiCall({
                mode: 'GET',
                url: `/api/clientProposals/rateLookup?origin_port_id=${originId}&destination_port_id=${destId}&container_variant_id=${variantId}`,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Rate Not Found',
                    message: response.message ?? 'No rate configured for this combination.'
                });
                baseRateInput.value = '0.00';
                recomputeFinalRate(row);
                return;
            }

            baseRateInput.value = formatCurrencyDisplay(Number(response.data.frt).toFixed(2));
            recomputeFinalRate(row);
        }

        function recomputeFinalRate(row) {
            const base = parseFloat(parseCurrencyValue(row.querySelector('.base-rate').value)) || 0;
            const type = row.querySelector('.discount-type').value;
            const value = parseFloat(parseCurrencyValue(row.querySelector('.discount-value').value)) || 0;
            const finalRateInput = row.querySelector('.final-rate');

            let final = base;
            if (type === 'percentage') final = base - (base * value / 100);
            if (type === 'fixed') final = Math.max(0, base - value);

            finalRateInput.value = formatCurrencyDisplay(final.toFixed(2));
        }

        document.getElementById('leadProposalAddRowBtn').addEventListener('click', addProposalRow);

        document.getElementById('leadProposalSaveBtn').addEventListener('click', async function() {
            const rows = Array.from(document.querySelectorAll(
                '#leadProposalRatesContainer [data-row]'));

            if (!rows.length) {
                showMessage({
                    status: 'error',
                    title: 'Add at least one container line.'
                });
                return;
            }

            const rates = rows.map((row) => ({
                origin_port_id: row.querySelector('[data-field="origin_port_id"]').value,
                destination_port_id: row.querySelector('[data-field="destination_port_id"]')
                    .value,
                container_id: row.querySelector('[data-field="container_id"]').value,
                container_class_id: classIdForPayload(row.querySelector(
                    '[data-field="container_class_id"]')),
                container_size_id: sizeIdForPayload(row.querySelector(
                    '[data-field="container_size_id"]')),
                container_variant_id: row.querySelector(
                    '[data-field="container_variant_id"]').value,
                min_van_qty: row.querySelector('[data-field="min_van_qty"]').value || null,
                base_rate: parseFloat(parseCurrencyValue(row.querySelector('.base-rate')
                    .value)) || 0,
                discount_type: row.querySelector('.discount-type').value || null,
                discount_value: parseFloat(parseCurrencyValue(row.querySelector(
                    '.discount-value').value)) || 0,
                final_rate: parseFloat(parseCurrencyValue(row.querySelector('.final-rate')
                    .value)) || 0,
            }));

            if (rates.some((r) => !r.origin_port_id || !r.destination_port_id || !r
                    .container_variant_id)) {
                showMessage({
                    status: 'error',
                    title: 'Incomplete',
                    message: 'Complete origin, destination, and container for every line.'
                });
                return;
            }

            const url = proposalModalContext.mode === 'append' ?
                `/api/clientProposals/${proposalModalContext.proposalId}/rates` :
                `/api/crm/leads/${window.currentLeadUuid}/proposals`;

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload: {
                    rates
                },
                url,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: response.message ? 'Error' : 'Error Saving Proposal',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: proposalModalContext.mode === 'append' ? 'Container(s) added!' :
                    'Proposal saved!'
            });
            closeSideModal('LeadAddProposalModal');
            window.loadLeadProposals(window.currentLeadUuid, proposalModalContext.mode === 'append' ?
                (window.currentLeadProposalsPage ?? 1) : 1);
        });
    })();
</script>

<script>
    (function() {
        // Mirrors the Stage 2 container-requirements form in crmLeadForm.blade.php
        // (same field set / per-type visibility rules), adapted to add ONE row
        // at a time via POST /api/crm/leads/{uuid}/containers instead of that
        // page's wholesale replace-all-containers submit.

        const CONTAINER_TYPES = [{
                value: 'CV',
                label: 'Container Van (CV)'
            },
            {
                value: 'FR',
                label: 'Flatrack (FR)'
            },
            {
                value: 'RF',
                label: 'Reefer Van (RF)'
            },
            {
                value: 'LC',
                label: 'Loose Cargo (LC)'
            },
            {
                value: 'RC',
                label: 'Rolling Cargo (RC)'
            },
        ];
        const SERVICE_MODE_OPTIONS = [{
                value: 'PIER',
                label: 'Pier'
            },
            {
                value: 'DOOR',
                label: 'Door'
            },
        ];
        const TYPE_FIELD_VISIBILITY = {
            CV: {
                convanClass: true,
                convanSize: true,
                temperature: false,
                cbmTon: false,
                splitServiceMode: true
            },
            FR: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: false,
                splitServiceMode: true
            },
            RF: {
                convanClass: true,
                convanSize: false,
                temperature: true,
                cbmTon: false,
                splitServiceMode: true
            },
            LC: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: true,
                splitServiceMode: true
            },
            RC: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: true,
                splitServiceMode: true
            },
        };

        let leadPortsOptionsHtml = '';
        let leadLocationsOptionsHtml = '';
        let leadPortsData = [];
        // Class/size lists are owned per-Container (see Container::syncCatalog())
        // and keyed here by Container.code, which lines up with CONTAINER_TYPES'
        // values (CV/RF/FR/LC/RC) - not global lookups anymore.
        let leadContainerCatalogByCode = {};
        let leadContainerLookupsLoaded = false;
        // Cargo Type is LOV-backed (Option "Cargo Type"), same fetch-once /
        // cache-as-html-string pattern as leadPortsOptionsHtml/leadLocationsOptionsHtml
        // above, since this card is (re)rendered fresh each time the modal opens.
        let leadCargoTypeOptionsHtml = '<option value="">Select Cargo Type</option>';

        function leadPortOptionsForLocation(locationId) {
            const ports = locationId ?
                leadPortsData.filter((p) => String(p.location_id) === String(locationId)) :
                leadPortsData;

            return ports.map((p) => `<option value="${p.port_id}">${p.name}</option>`).join('');
        }

        function refreshSearchable(el) {
            el?._searchableSelect?.refresh();
        }

        async function loadLeadContainerLookups() {
            if (leadContainerLookupsLoaded) return;

            const [portsRes, locationsRes, containersRes, cargoTypeRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/locations?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containers?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/listofval/cargotype'
                }),
            ]);

            if (portsRes.success) {
                leadPortsData = portsRes.data.data;
                leadPortsOptionsHtml = leadPortsData
                    .map((p) => `<option value="${p.port_id}">${p.location?.name ?? '-'} - ${p.name}</option>`)
                    .join('');
            }
            if (locationsRes.success) {
                leadLocationsOptionsHtml = locationsRes.data.data
                    .map((l) => `<option value="${l.location_id}">${l.name}</option>`)
                    .join('');
            }
            if (containersRes.success) {
                leadContainerCatalogByCode = {};
                containersRes.data.data.forEach((container) => {
                    leadContainerCatalogByCode[container.code] = {
                        sizes: container.sizes ?? [],
                        classes: container.classes ?? [],
                    };
                });
            }
            if (Array.isArray(cargoTypeRes)) {
                leadCargoTypeOptionsHtml = '<option value="">Select Cargo Type</option>' +
                    cargoTypeRes.map((lov) => `<option value="${lov.lov_name}">${lov.lov_name}</option>`).join(
                        '');
            }

            leadContainerLookupsLoaded = true;
        }

        function populateLeadSizeClassOptions(card) {
            const type = card.querySelector('.type-select').value;
            const catalog = leadContainerCatalogByCode[type] ?? {
                sizes: [],
                classes: []
            };

            card.querySelector('[data-field="container_size_id"]').innerHTML =
                '<option value="">Select Size</option>' +
                catalog.sizes.map((s) => `<option value="${s.id}">${s.size}</option>`).join('');
        }

        const serviceModeOptionsHtml = (placeholder) =>
            `<option value="">${placeholder}</option>` +
            SERVICE_MODE_OPTIONS.map((o) => `<option value="${o.value}">${o.label}</option>`).join('');

        function leadContainerCardHtml() {
            return `
    <div class="lead-container-card space-y-3">
        <button type="button" class="card-toggle flex items-center gap-2 w-full text-left">
            <span class="card-toggle-chevron text-xs text-zinc-400 dark:text-zinc-500 transition-transform duration-200 rotate-180">▼</span>
            <span class="card-summary text-sm font-medium text-zinc-600 dark:text-zinc-300 truncate">New Booking Requirement</span>
        </button>

        <div>
            <label class="text-[11px] text-zinc-400 uppercase">Container Type</label>
            <select data-field="container_type" class="type-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm font-semibold">
                ${CONTAINER_TYPES.map(t => `<option value="${t.value}">${t.label}</option>`).join('')}
            </select>
        </div>

        <input type="hidden" data-field="booking_unit_type">

        <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="md:col-span-2">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Container & Quantity</p>
            </div>
            <div class="field-convan-size hidden">
                <label class="text-[11px] text-zinc-400 uppercase">ConVan Size <span class="req-asterisk">*</span></label>
                <select data-field="container_size_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Size</option>
                </select>
            </div>
            <div class="field-temperature hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Minimum Temperature (&deg;C) <span class="req-asterisk">*</span></label>
                <input type="number" step="0.1" data-field="minimum_temperature" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="field-cbm-ton hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Estimated CBM/s</label>
                <input type="number" step="0.01" data-field="estimated_cbm" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="field-cbm-ton hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Estimated Ton/s</label>
                <input type="number" step="0.01" data-field="estimated_ton" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Quantity <span class="req-asterisk">*</span></label>
                <input type="number" data-field="quantity" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Frequency <span class="req-asterisk">*</span></label>
                <select data-field="frequency" required class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">-</option>
                    <option value="daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                </select>
            </div>

            <div class="md:col-span-2 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Route</p>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Origin Location <span class="req-asterisk">*</span></label>
                <select class="origin-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Location</option>${leadLocationsOptionsHtml}
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Origin Port <span class="req-asterisk">*</span></label>
                <select data-field="origin_port_id" required disabled class="origin-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                    <option value="">Select Port</option>${leadPortsOptionsHtml}
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Destination Location <span class="req-asterisk">*</span></label>
                <select class="destination-location-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Location</option>${leadLocationsOptionsHtml}
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Destination Port <span class="req-asterisk">*</span></label>
                <select data-field="destination_port_id" required disabled class="destination-port-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm disabled:opacity-50 disabled:bg-zinc-100 dark:disabled:bg-zinc-900">
                    <option value="">Select Port</option>${leadPortsOptionsHtml}
                </select>
            </div>
            <div class="field-split-service hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Service Mode - Origin <span class="req-asterisk">*</span></label>
                <select data-field="service_mode_origin" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>
            <div class="field-split-service hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Service Mode - Destination <span class="req-asterisk">*</span></label>
                <select data-field="service_mode_destination" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>
            <div class="field-single-service hidden md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Service Mode <span class="req-asterisk">*</span></label>
                <select data-field="service_mode" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>

            <div class="md:col-span-2 pt-2 border-t border-zinc-100 dark:border-zinc-700">
                <p class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-1.5">Cargo Details</p>
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Cargo Type</label>
                <select data-field="cargo_type" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${leadCargoTypeOptionsHtml}
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">General Cargo Description <span class="req-asterisk">*</span></label>
                <textarea data-field="general_cargo_description" required rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                <input type="text" inputmode="decimal" data-field="declared_value_per_unit" class="currency-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                <textarea data-field="special_requirements" rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Special Notes</label>
                <textarea data-field="special_notes" rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" data-field="dangerous_cargo">
                    <span class="text-sm dark:text-zinc-200">Dangerous Cargo (DG)</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">DG Documentary Requirement</label>
                <input type="file" class="dg-file-input w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                <p class="text-xs text-zinc-400 mt-1">
                    Upload the supporting DG document (e.g. MSDS, DG declaration). PDF, JPG, PNG, DOC/DOCX up to 10MB.
                </p>
                <p class="dg-file-status text-xs text-zinc-500 mt-1"></p>
                <input type="hidden" data-field="dg_documentary_requirement">
            </div>
        </div>
        </div>
    </div>`;
        }

        function setLeadContainerCardExpanded(card, expanded) {
            const body = card.querySelector('.card-body');
            const chevron = card.querySelector('.card-toggle-chevron');
            body?.classList.toggle('hidden', !expanded);
            chevron?.classList.toggle('rotate-180', expanded);
        }

        function updateLeadContainerCardSummary(card) {
            const typeSelect = card.querySelector('.type-select');
            const typeLabel = typeSelect?.options[typeSelect.selectedIndex]?.textContent ?? '';

            const selectedLabel = (select) => (select && select.value) ?
                (select.options[select.selectedIndex]?.textContent ?? '—') : '—';

            const originPortLabel = selectedLabel(card.querySelector('.origin-port-select'));
            const originLabel = originPortLabel !== '—' ? originPortLabel :
                selectedLabel(card.querySelector('.origin-location-select'));

            const destinationPortLabel = selectedLabel(card.querySelector('.destination-port-select'));
            const destinationLabel = destinationPortLabel !== '—' ? destinationPortLabel :
                selectedLabel(card.querySelector('.destination-location-select'));

            const quantity = card.querySelector('[data-field="quantity"]')?.value || '—';

            const summaryEl = card.querySelector('.card-summary');
            if (summaryEl) summaryEl.textContent =
                `${typeLabel} · ${originLabel} → ${destinationLabel} · Qty: ${quantity}`;
        }

        function applyLeadContainerTypeVisibility(card) {
            const type = card.querySelector('.type-select').value;
            const flags = TYPE_FIELD_VISIBILITY[type];

            card.querySelector('.field-convan-size').classList.toggle('hidden', !flags.convanSize);
            card.querySelector('.field-temperature').classList.toggle('hidden', !flags.temperature);
            card.querySelectorAll('.field-cbm-ton').forEach(el => el.classList.toggle('hidden', !flags.cbmTon));
            card.querySelectorAll('.field-split-service').forEach(el => el.classList.toggle('hidden', !flags
                .splitServiceMode));
            card.querySelector('.field-single-service').classList.toggle('hidden', flags.splitServiceMode);
        }

        function syncLeadContainerBookingUnitType(card) {
            const typeSelect = card.querySelector('.type-select');
            const label = typeSelect.options[typeSelect.selectedIndex]?.textContent ?? '';
            card.querySelector('[data-field="booking_unit_type"]').value = label;
        }

        async function uploadLeadContainerDgFile(file) {
            const formData = new FormData();
            formData.append('dg_document', file);

            const response = await apiCall({
                mode: 'POST',
                isJson: false,
                payload: formData,
                url: '/api/crm/leads/uploadDgDocument',
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Upload failed',
                    message: response.message ?? 'Unable to upload the DG document.',
                });
                return null;
            }

            return response.data.path;
        }

        function renderLeadContainerForm() {
            const wrap = document.getElementById('leadContainerFormWrap');
            wrap.innerHTML = leadContainerCardHtml();
            const card = wrap.firstElementChild;

            ['.origin-location-select', '.origin-port-select', '.destination-location-select',
                '.destination-port-select'
            ]
            .forEach((sel) => makeSearchableSelect(card.querySelector(sel)));

            card.querySelector('.card-toggle').addEventListener('click', () => {
                const expand = card.querySelector('.card-body').classList.contains('hidden');
                setLeadContainerCardExpanded(card, expand);
            });

            card.querySelector('.type-select').addEventListener('change', () => {
                applyLeadContainerTypeVisibility(card);
                syncLeadContainerBookingUnitType(card);
                populateLeadSizeClassOptions(card);
                updateLeadContainerCardSummary(card);
            });

            card.querySelector('.origin-location-select').addEventListener('change', function() {
                const portSelect = card.querySelector('.origin-port-select');
                portSelect.innerHTML =
                    `<option value="">Select Port</option>${leadPortOptionsForLocation(this.value)}`;
                portSelect.disabled = !this.value;
                refreshSearchable(portSelect);
                updateLeadContainerCardSummary(card);
            });
            card.querySelector('.destination-location-select').addEventListener('change', function() {
                const portSelect = card.querySelector('.destination-port-select');
                portSelect.innerHTML =
                    `<option value="">Select Port</option>${leadPortOptionsForLocation(this.value)}`;
                portSelect.disabled = !this.value;
                refreshSearchable(portSelect);
                updateLeadContainerCardSummary(card);
            });
            card.querySelector('.origin-port-select').addEventListener('change', () =>
                updateLeadContainerCardSummary(card));
            card.querySelector('.destination-port-select').addEventListener('change', () =>
                updateLeadContainerCardSummary(card));
            card.querySelector('[data-field="quantity"]').addEventListener('input', () =>
                updateLeadContainerCardSummary(card));

            card.querySelector('.dg-file-input').addEventListener('change', async function() {
                const file = this.files[0];
                const statusEl = card.querySelector('.dg-file-status');
                const hiddenField = card.querySelector('[data-field="dg_documentary_requirement"]');

                if (!file) return;

                statusEl.textContent = 'Uploading...';
                const path = await uploadLeadContainerDgFile(file);

                if (!path) {
                    statusEl.textContent = '';
                    this.value = '';
                    return;
                }

                hiddenField.value = path;
                statusEl.textContent = `Uploaded: ${file.name}`;
            });

            applyLeadContainerTypeVisibility(card);
            syncLeadContainerBookingUnitType(card);
            populateLeadSizeClassOptions(card);
            updateLeadContainerCardSummary(card);
        }

        document.getElementById('leadAddContainerBtn').addEventListener('click', async function() {
            await loadLeadContainerLookups();
            renderLeadContainerForm();
            initSideModal({
                modalId: 'LeadAddContainerModal'
            });
        });

        document.getElementById('leadContainerSaveBtn').addEventListener('click', async function() {
            const card = document.querySelector('#leadContainerFormWrap .lead-container-card');
            if (!card) return;

            const payload = {};
            card.querySelectorAll('[data-field]').forEach((el) => {
                if (el.type === 'checkbox') {
                    payload[el.dataset.field] = el.checked;
                } else if (el.classList.contains('currency-input')) {
                    payload[el.dataset.field] = parseCurrencyValue(el.value);
                } else {
                    payload[el.dataset.field] = el.value;
                }
            });

            if (!payload.container_type) {
                showMessage({
                    status: 'error',
                    title: 'Select a container type.'
                });
                return;
            }

            const response = await apiCall({
                mode: 'POST',
                isJson: true,
                payload,
                url: `/api/crm/leads/${window.currentLeadUuid}/containers`,
                button: this,
            });

            if (!response.success) {
                showMessage({
                    status: 'error',
                    title: 'Error Saving Container',
                    message: response.message ?? ''
                });
                return;
            }

            showMessage({
                status: 'success',
                title: 'Booking requirement added!'
            });
            closeSideModal('LeadAddContainerModal');
            window.reloadCrmData();
        });
    })();
</script>
