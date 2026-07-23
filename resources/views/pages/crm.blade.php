<div class="container mx-auto p-3">

    <div class="flex justify-between items-center mb-5 p-2">

        <div>
            <h1 class="text-2xl font-bold">CRM Leads</h1>
            <p class="text-zinc-500">Manage leads and sales opportunities</p>
        </div>

        <button id="btnNewLead" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            + New Lead
        </button>

    </div>
    <!-- CRM Status Count Cards -->
    <section class="w-full my-5">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3">
            <div class="statusBtn max-md:col-span-2 bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="ALL">

                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-blue-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">ALL</p>
                <p class="text-2xl font-bold text-black" id="countALL">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="LEAD">

                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-gray-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">LEAD</p>
                <p class="text-2xl font-bold text-black" id="countLead">0</p>
            </div>

            <div class="statusBtn  bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="QUALIFIED">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-indigo-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">QUALIFIED</p>
                <p class="text-2xl font-bold text-black" id="countQualified">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="OPPORTUNITY">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-purple-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">OPPORTUNITY</p>
                <p class="text-2xl font-bold text-black" id="countOpportunity">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="NEGOTIATION">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-amber-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">NEGOTIATION</p>
                <p class="text-2xl font-bold text-black" id="countNegotiation">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="WIN">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-green-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">WIN</p>
                <p class="text-2xl font-bold text-black" id="countWin">0</p>
            </div>

            <div class="statusBtn bg-white border border-gray-200 rounded-xl p-4 shadow-sm  cursor-pointer"
                data-status="LOST">
                <div class="w-full flex-1 items-center">
                    <div class="w-full py-1  rounded-full bg-red-500">
                    </div>
                </div>
                <p class="text-xs text-zinc-400 font-semibold">LOST</p>
                <p class="text-2xl font-bold text-black" id="countLose">0</p>
            </div>

        </div>
    </section>

    <x-table id="tableCrm" />

</div>




<x-side-modal id="LeadDetailsSideModal">

    <div class="p-5 border-b flex justify-between sticky top-0 bg-white dark:bg-zinc-800 z-10">




        <p class="text-xl font-semibold dark:text-white">
            New CRM Lead
        </p>

        <button class="modal-close">
            ✕
        </button>

    </div>

    <div class="p-5">


        <div class="p-5">
            <form id="leadForm">
                <div class="grid grid-cols-2 gap-3">

                    <!-- Contact Name -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Name</label>
                        <input type="text" name="contact_name"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Mobile -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Mobile</label>
                        <input type="text" name="mobile" required
                            class="format-mobile w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Email</label>
                        <input type="email" name="email" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Company Name -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Company
                            Name</label>
                        <input type="text" name="company_name" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Position -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Position /
                            Role</label>
                        <input type="text" name="position" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Status</label>
                        <select name="status" required
                            class="statusDropDown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                        </select>
                    </div>

                    <!-- Source -->
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Source</label>
                        <input type="text" name="source" required
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Estimated Value -->
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Estimated
                            Value</label>
                        <input type="text" name="est_value"
                            class="format-currency w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <!-- Notes -->
                    <div class="flex flex-col gap-1 col-span-2">
                        <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Notes</label>
                        <textarea name="notes" id="notes" rows="5"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition resize-none"></textarea>
                    </div>

                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2">
            <button type="button"
                class="modal-close px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                Cancel
            </button>
            <button type="submit" id="saveLeadBtn"
                class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
                Save Lead
            </button>
        </div>
    </div>

</x-side-modal><x-modal id="LeadInfoModal">

    {{-- Header --}}
    <div
        class="p-5 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center bg-zinc-50 dark:bg-zinc-800 rounded-t-2xl">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-100" id="leadCompanyName">Company Name
                </p>
                <div id="leadStatus"></div>
                <div id="leadCustomerCode"></div>
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

    <div class="max-h-[75vh] overflow-auto p-5 space-y-5 bg-zinc-50 dark:bg-zinc-900">

        {{-- ============== LEAD & COMPANY INFORMATION ============== --}}
        <div class="relative bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">

            <div class="flex justify-between items-center mb-3">
                <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Lead &
                    Company Information</p>
                <button id="editContactBtn"
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
                class="modaldropdown hidden absolute right-4 top-12 w-80 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 shadow-xl shadow-black/10 dark:shadow-black/40">

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

            {{-- Contact & Company field grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-3 text-sm">
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Client Type</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadClientType">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Contact Name</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadContactName">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Position</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadPosition">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Email
                    </p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadEmail">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Mobile</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadMobile">-</p>
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
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Assigned To</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadAssignedTo">-</p>
                </div>

                <div class="col-span-2 md:col-span-3 border-t border-zinc-100 dark:border-zinc-700 pt-3">
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Company Name</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadCompanyNameFull">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Type
                        of Business</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadTypeOfBusiness">-</p>
                </div>
                <div class="col-span-2 md:col-span-2">
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Industry Description
                    </p>
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

                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Estimated Value</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadEstimatedValue">-</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Expected Close Date</p>
                    <p class="font-medium text-zinc-800 dark:text-zinc-200" id="leadExpectedCloseDate">-</p>
                </div>
            </div>
        </div>

        {{-- ============== ADDRESSES ============== --}}
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest mb-3">Addresses
            </p>
            <div id="leadAddressListContainer"
                class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-72 overflow-auto p-0.5">
            </div>
        </div>

        {{-- ============== CONTAINER REQUIREMENTS ============== --}}
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <div class="flex justify-between items-center mb-3">
                <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Container
                    Requirements</p>
                <button id="leadAddContainerBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                    + Add Container
                </button>
            </div>
            <div id="leadContainerListContainer"
                class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-72 overflow-auto p-0.5">
            </div>
        </div>

        {{-- ============== PROPOSALS ============== --}}
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
            <div class="flex justify-between items-center mb-3">
                <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Proposals
                </p>
                <button id="leadAddProposalBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2
                        rounded-lg text-sm">
                    + New Proposal
                </button>
            </div>
            <div id="leadProposalContainer"
                class="border border-zinc-300 dark:border-zinc-600 rounded-lg flex flex-col max-h-72 overflow-auto p-1 gap-1">
            </div>
            <div id="leadProposalsPagination"></div>
        </div>

        {{-- ============== ACTIVITIES + NOTES ============== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Activities --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 relative">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                        Activity</p>
                    <button id="leadAddActivityBtn"
                        class="w-6 h-6 flex items-center justify-center rounded-md bg-zinc-100 hover:bg-zinc-200 text-sm font-semibold text-zinc-600">
                        +
                    </button>
                </div>

                <div id="leadActivityDropdown"
                    class="modaldropdown hidden absolute right-2 bottom-9 w-72 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">

                    <p class="text-xs font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Add New
                        Activity</p>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1">
                            <label for="activityStatusInput"
                                class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Status</label>
                            <select name="status" id="activityStatusInput" required
                                class="statusDropDown w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <option value="">Select status</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="activityTypeInput"
                                class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Activity</label>
                            <input type="text" name="type" id="activityTypeInput"
                                class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="activityDescriptionInput"
                            class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Description</label>
                        <textarea name="activityDescriptionInput" id="activityDescriptionInput" rows="3" placeholder="Add activity..."
                            class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-200 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"></textarea>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="activityAttachmentInput"
                            class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                            Attachment
                        </label>
                        <input type="file" name="attachment" id="activityAttachmentInput"
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

                    <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                        <button id="cancelActivityBtn"
                            class="px-3 py-1.5 text-xs font-medium text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-lg transition">
                            Cancel
                        </button>
                        <button id="saveActivityBtn"
                            class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                            Save
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 max-h-72 overflow-auto pr-0.5" id="leadActivityContainer">
                    <div class="w-full p-2 rounded-md text-center">
                        <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500">No activities found</p>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 relative">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Notes
                    </p>
                    <button id="leadAddNoteBtn"
                        class="w-6 h-6 flex items-center justify-center rounded-md bg-zinc-100 hover:bg-zinc-200 text-sm font-semibold text-zinc-600">
                        +
                    </button>
                </div>

                <div id="leadNoteDropdown"
                    class="modaldropdown hidden absolute right-2 bottom-9 w-72 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">

                    <p class="text-xs font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Add New
                        Note</p>

                    <div class="flex flex-col gap-1">
                        <label for="noteInput"
                            class="text-[11px] font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">Note</label>
                        <textarea name="noteInput" id="noteInput" rows="3" placeholder="Add note..."
                            class="w-full bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm text-zinc-800 dark:text-zinc-200 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-700">
                        <button id="cancelNoteBtn"
                            class="px-3 py-1.5 text-xs font-medium text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-lg transition">
                            Cancel
                        </button>
                        <button id="saveNoteBtn"
                            class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                            Save
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5 max-h-72 overflow-auto pr-0.5" id="leadNoteContainer">
                    <div class="w-full p-2 rounded-md text-center">
                        <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500">No notes found</p>
                    </div>
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
        <p class="text-lg font-semibold dark:text-white">Add Container Requirement</p>
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

            if (def.origin_port_id) originSel.value = def.origin_port_id;
            if (def.destination_port_id) destSel.value = def.destination_port_id;

            if (def.container_id) {
                containerSel.value = def.container_id;
                containerSel.dispatchEvent(new Event('change'));
            }
            if (def.container_class_id) {
                classSel.value = def.container_class_id;
                classSel.dispatchEvent(new Event('change'));
            }
            if (def.container_size_id) sizeSel.value = def.container_size_id;
            if (def.container_variant_id) variantInput.value = def.container_variant_id;
            if (def.base_rate) baseRateInput.value = Number(def.base_rate).toFixed(2);

            recomputeFinalRate(row);
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
        let containerVariantsData = [];

        async function loadContainerLookups() {
            const [portsRes, variantsRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containers/variants'
                }),
            ]);

            if (portsRes.success) {
                portsOptionsHtml = portsRes.data.data
                    .map((p) => `<option value="${p.port_id}">${p.code} - ${p.name}</option>`)
                    .join('');
            }
            if (variantsRes.success) {
                containerVariantsData = variantsRes.data;
            }
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
                        <label class="text-[11px] text-zinc-400 uppercase">Origin</label>
                        <select data-field="origin_port_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                            <option value="">Select</option>${portsOptionsHtml}
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Destination</label>
                        <select data-field="destination_port_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
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
                        <label class="text-[11px] text-zinc-400 uppercase">Rate (FRT)</label>
                        <input type="text" data-field="base_rate" readonly class="base-rate w-full border border-zinc-300 dark:border-zinc-700 rounded-lg px-2 py-1.5 text-sm bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100" value="0.00">
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
                        <input type="number" step="0.01" min="0" data-field="discount_value" class="discount-value w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm" value="0">
                    </div>
                    <div>
                        <label class="text-[11px] text-zinc-400 uppercase">Final Rate</label>
                        <input type="text" data-field="final_rate" readonly class="final-rate w-full border border-blue-200 dark:border-blue-800 rounded-lg px-2 py-1.5 text-sm bg-blue-50 dark:bg-blue-900/40 text-zinc-900 dark:text-blue-100 font-semibold" value="0.00">
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
                const classes = [...new Map(
                    containerVariantsData
                    .filter((v) => String(v.container.id) === containerId)
                    .map((v) => [v.container_class.id, v.container_class])
                ).values()];

                classSel.innerHTML = `<option value="">Select</option>` +
                    classes.map((c) => `<option value="${c.id}">${c.class}</option>`).join('');
                sizeSel.innerHTML = `<option value="">Select class first</option>`;
                variantInput.value = '';
                resetRate(baseRateInput, finalRateInput);
            });

            classSel.addEventListener('change', () => {
                const containerId = containerSel.value;
                const classId = classSel.value;
                const sizes = containerVariantsData.filter(
                    (v) => String(v.container.id) === containerId && String(v.container_class.id) ===
                    classId
                );

                sizeSel.innerHTML = `<option value="">Select</option>` +
                    sizes.map((v) =>
                        `<option value="${v.container_size.id}" data-variant-id="${v.id}">${v.container_size.size}</option>`
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

            baseRateInput.value = Number(response.data.frt).toFixed(2);
            recomputeFinalRate(row);
        }

        function recomputeFinalRate(row) {
            const base = parseFloat(row.querySelector('.base-rate').value) || 0;
            const type = row.querySelector('.discount-type').value;
            const value = parseFloat(row.querySelector('.discount-value').value) || 0;
            const finalRateInput = row.querySelector('.final-rate');

            let final = base;
            if (type === 'percentage') final = base - (base * value / 100);
            if (type === 'fixed') final = Math.max(0, base - value);

            finalRateInput.value = final.toFixed(2);
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
                container_class_id: row.querySelector('[data-field="container_class_id"]')
                    .value,
                container_size_id: row.querySelector('[data-field="container_size_id"]')
                    .value,
                container_variant_id: row.querySelector(
                    '[data-field="container_variant_id"]').value,
                base_rate: parseFloat(row.querySelector('.base-rate').value) || 0,
                discount_type: row.querySelector('.discount-type').value || null,
                discount_value: parseFloat(row.querySelector('.discount-value').value) || 0,
                final_rate: parseFloat(row.querySelector('.final-rate').value) || 0,
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
                splitServiceMode: false
            },
            RC: {
                convanClass: false,
                convanSize: false,
                temperature: false,
                cbmTon: true,
                splitServiceMode: false
            },
        };

        let leadPortsOptionsHtml = '';
        let leadClassOptionsHtml = '';
        let leadSizeOptionsHtml = '';
        let leadContainerLookupsLoaded = false;

        async function loadLeadContainerLookups() {
            if (leadContainerLookupsLoaded) return;

            const [portsRes, classesRes, sizesRes] = await Promise.all([
                apiCall({
                    mode: 'GET',
                    url: '/api/ports?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containerClasses?per_page=200'
                }),
                apiCall({
                    mode: 'GET',
                    url: '/api/containerSizes?per_page=200'
                }),
            ]);

            if (portsRes.success) {
                leadPortsOptionsHtml = portsRes.data.data
                    .map((p) => `<option value="${p.port_id}">${p.code} - ${p.name}</option>`)
                    .join('');
            }
            if (classesRes.success) {
                leadClassOptionsHtml = classesRes.data.data
                    .map((c) => `<option value="${c.id}">${c.class}</option>`)
                    .join('');
            }
            if (sizesRes.success) {
                leadSizeOptionsHtml = sizesRes.data.data
                    .map((s) => `<option value="${s.id}">${s.size}</option>`)
                    .join('');
            }
            leadContainerLookupsLoaded = true;
        }

        const serviceModeOptionsHtml = (placeholder) =>
            `<option value="">${placeholder}</option>` +
            SERVICE_MODE_OPTIONS.map((o) => `<option value="${o.value}">${o.label}</option>`).join('');

        function leadContainerCardHtml() {
            return `
    <div class="lead-container-card space-y-3">
        <div>
            <label class="text-[11px] text-zinc-400 uppercase">Container Type</label>
            <select data-field="container_type" class="type-select w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-3 py-2 text-sm font-semibold">
                ${CONTAINER_TYPES.map(t => `<option value="${t.value}">${t.label}</option>`).join('')}
            </select>
        </div>

        <input type="hidden" data-field="booking_unit_type">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Origin</label>
                <select data-field="origin_port_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Port</option>${leadPortsOptionsHtml}
                </select>
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Destination</label>
                <select data-field="destination_port_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Port</option>${leadPortsOptionsHtml}
                </select>
            </div>

            <div class="field-convan-class hidden">
                <label class="text-[11px] text-zinc-400 uppercase">ConVan Class</label>
                <select data-field="container_class_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Class</option>${leadClassOptionsHtml}
                </select>
            </div>
            <div class="field-convan-size hidden">
                <label class="text-[11px] text-zinc-400 uppercase">ConVan Size</label>
                <select data-field="container_size_id" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Size</option>${leadSizeOptionsHtml}
                </select>
            </div>
            <div class="field-temperature hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Required Temperature (&deg;C)</label>
                <input type="number" step="0.1" data-field="required_temperature" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>

            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Quantity</label>
                <input type="number" data-field="quantity" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
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
                <label class="text-[11px] text-zinc-400 uppercase">Declared Value per Unit</label>
                <input type="number" step="0.01" data-field="declared_value_per_unit" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="text-[11px] text-zinc-400 uppercase">Frequency</label>
                <select data-field="frequency" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    <option value="">Select Frequency</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">General Cargo Description</label>
                <textarea data-field="general_cargo_description" rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>

            <div class="field-split-service hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Service Mode - Origin</label>
                <select data-field="service_mode_origin" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>
            <div class="field-split-service hidden">
                <label class="text-[11px] text-zinc-400 uppercase">Service Mode - Destination</label>
                <select data-field="service_mode_destination" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
            </div>
            <div class="field-single-service hidden md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Service Mode</label>
                <select data-field="service_mode" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm">
                    ${serviceModeOptionsHtml('Select Mode')}
                </select>
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
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Special Requirements</label>
                <textarea data-field="special_requirements" rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="text-[11px] text-zinc-400 uppercase">Special Notes</label>
                <textarea data-field="special_notes" rows="2" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 rounded-lg px-2 py-1.5 text-sm"></textarea>
            </div>
        </div>
    </div>`;
        }

        function applyLeadContainerTypeVisibility(card) {
            const type = card.querySelector('.type-select').value;
            const flags = TYPE_FIELD_VISIBILITY[type];

            card.querySelector('.field-convan-class').classList.toggle('hidden', !flags.convanClass);
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

            card.querySelector('.type-select').addEventListener('change', () => {
                applyLeadContainerTypeVisibility(card);
                syncLeadContainerBookingUnitType(card);
            });

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
                payload[el.dataset.field] = el.type === 'checkbox' ? el.checked : el.value;
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
                title: 'Container requirement added!'
            });
            closeSideModal('LeadAddContainerModal');
            window.reloadCrmData();
        });
    })();
</script>
