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

</x-side-modal>

<x-modal id="LeadInfoModal">

    <div class="p-5 border-b flex justify-between items-center">
        <div class="flex flex-col">
            <div class="flex gap-2">
                <p class="text-lg font-semibold" id="leadCompanyName">Company Name</p>
                <div class="items-center flex  flex-col">

                    <div class="px-3 my-auto text-sm rounded-full text-white" id="leadStatus">
                        status
                    </div>
                </div>
            </div>
            <div class="flex gap-2 text-md">
                some info
            </div>
        </div>
        <div class="h-full items-center">
            <button id="createClientMasterBtn" class="p-2  bg-orange-600 rounded-lg text-white"> <b
                    class="font-black">+</b>
                Record</button>
        </div>
    </div>

    <div class="max-h-[60vh] overflow-auto">
        <div class="lg:min-h-[50vh] h-full grid grid-cols-1 md:grid-cols-6 relative">

            <div id="editContactInfoDropdown"
                class="modaldropdown hidden absolute left-1/2 top-10 w-80 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 -translate-x-1/2 shadow-xl shadow-black/10 dark:shadow-black/40">

                <!-- Header -->
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest mb-4">Edit Contact Information
                </p>

                <!-- Fields -->
                <div class="flex flex-col gap-3">

                    <div class="flex flex-col gap-1">
                        <label for="contactName"
                            class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Name</label>
                        <input type="text" name="contactName" id="contactName"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="contactEmail"
                            class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Email</label>
                        <input type="email" name="contactEmail" id="contactEmail"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="contactMobile"
                            class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Contact
                            Mobile</label>
                        <input type="text" name="contactMobile" id="contactMobile"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </div>

                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <button id="cancelContactInfoBtn"
                        class="px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                        Cancel
                    </button>
                    <button id="saveContactInfoBtn"
                        class="px-4 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                        Save
                    </button>
                </div>

            </div>
            <div class="md:col-span-2 flex flex-col gap-5 md:border-r border-zinc-300  relative ">

                <div class="absolute top-0 right-0 p-2">

                    <button id="editContactBtn"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                        </svg></button>
                </div>
                <div class="flex flex-col gap-0 p-4">

                    <!-- Name -->
                    <div class="flex justify-between items-center py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Name
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="leadContactName">-</p>
                    </div>

                    <!-- Email -->
                    <div class="flex justify-between items-center py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Email
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadEmail">-
                        </p>
                    </div>

                    <!-- Mobile -->
                    <div class="flex justify-between items-center py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Mobile
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadMobile">-
                        </p>
                    </div>

                    <!-- Source -->
                    <div class="flex justify-between items-center py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Source
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadSource">-
                        </p>
                    </div>

                    <!-- Estimated Value -->
                    <div class="flex justify-between items-center py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Est.
                            Value</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="leadEstimatedValue">-</p>
                    </div>

                    <!-- Date Added -->
                    <div class="flex justify-between items-center py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Date
                            Added</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadCreatedAt">
                            -</p>
                    </div>

                    <!-- Expected Close Date -->
                    <div class="flex justify-between items-center py-3">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Close
                            Date</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="leadExpectedCloseDate">-</p>
                    </div>

                </div>
                <div class="p-5">
                    <p class="text-xs font-semibold text-zinc-500">Proposals</p>
                    <div id="proposalContainer"
                        class="border border-zinc-300 rounded-lg flex flex-col max-h-[20vh] overflow-auto p-1 gap-1">
                        <div
                            class="dark:bg-zinc-600 border border-zinc-300 rounded-md  p-1 w-full flex justify-between items-center">
                            <div class="flex flex-col">

                                <h1>proposal 1 </h1>
                                <p class="text-xs dark:text-zinc-300">date time</p>
                            </div>
                            <button class="bg-orange-600 text-white rounded-md p-1"><svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
                                </svg></button>
                        </div>
                        <div
                            class="dark:bg-zinc-600 border border-zinc-300 rounded-md  p-1 w-full flex justify-between items-center">
                            <div class="flex flex-col">

                                <h1>proposal 2 </h1>
                                <p class="text-xs dark:text-zinc-300">date time</p>
                            </div>
                            <button class="bg-orange-600 text-white rounded-md p-1"><svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
                                </svg></button>
                        </div>


                    </div>

                </div>

            </div>
            <div class="md:col-span-4 flex flex-col p-5 gap-5">
                <div class="w-full space-y-3">

                    <div class="w-full flex justify-between items-center relative">

                        <p class="text-lg font-bold text-zinc-500">activities
                        </p>
                        <button id="addActivityBtn"
                            class="px-3 py-1 rounded-full font-semibold bg-white dark:bg-zinc-700 border drop-shadow-md text-sm">
                            add
                            +</button>
                        <div id="activityDropdown"
                            class="modaldropdown hidden absolute right-0 top-10 w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">

                            <!-- Header -->
                            <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest">Add New Activity</p>

                            <!-- Status + Activity row -->
                            <div class="grid grid-cols-2 gap-3">

                                <div class="flex flex-col gap-1">
                                    <label for="activityStatusInput"
                                        class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Status</label>
                                    <select name="status" id="activityStatusInput" required
                                        class="statusDropDown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                                        <option value="">Select status</option>
                                    </select>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label for="activityTypeInput"
                                        class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Activity</label>
                                    <input type="text" name="type" id="activityTypeInput"
                                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                                </div>

                            </div>

                            <!-- Description -->
                            <div class="flex flex-col gap-1">
                                <label for="activityDescriptionInput"
                                    class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Description</label>
                                <textarea name="activityDescriptionInput" id="activityDescriptionInput" rows="3" placeholder="Add activity..."
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition resize-none"></textarea>
                            </div>
                            <!-- Attachment -->
                            <div class="flex flex-col gap-1">
                                <label for="activityAttachmentInput"
                                    class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">
                                    Attachment
                                </label>

                                <input type="file" name="attachment" id="activityAttachmentInput"
                                    class="block w-full text-sm text-zinc-700 dark:text-zinc-200
               file:mr-4 file:py-2 file:px-4
               file:rounded-lg file:border-0
               file:text-sm file:font-medium
               file:bg-blue-50 file:text-blue-700
               hover:file:bg-blue-100
               dark:file:bg-blue-900/40 dark:file:text-blue-300
               cursor-pointer
               bg-zinc-50 dark:bg-zinc-800
               border border-zinc-200 dark:border-zinc-700
               rounded-lg p-2
               focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">

                                <p class="text-xs text-zinc-400 mt-1">
                                    Accepted: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max 10MB)
                                </p>
                            </div>
                            <!-- Footer -->
                            <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                                <button id="cancelActivityBtn"
                                    class="px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                    Cancel
                                </button>
                                <button id="saveActivityBtn"
                                    class="px-4 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                    Save
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="flex flex-col gap-2  p-1 max-h-[30vh] overflow-auto" id="activityContainer">

                        <div class="w-full p-2 rounded-md text-center">
                            <p class="font-semibold text-zinc-400">Theres no activities found</p>
                        </div>

                    </div>
                </div>
                <div class="w-full space-y-3">
                    <div class="w-full flex justify-between items-center relative">

                        <p class="text-lg font-bold text-zinc-500">notes
                        </p>
                        <button id="addNoteBtn"
                            class="px-3 py-1 rounded-full font-semibold bg-white dark:bg-zinc-700 border drop-shadow-md text-sm">
                            add
                            +</button>
                        <div id="noteDropdown"
                            class="modaldropdown hidden absolute right-0 top-10 w-80 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 z-50 flex flex-col gap-3 shadow-xl shadow-black/10 dark:shadow-black/40">

                            <!-- Header -->
                            <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest">Add New Note</p>

                            <!-- Note field -->
                            <div class="flex flex-col gap-1">
                                <label for="noteInput"
                                    class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Note</label>
                                <textarea name="noteInput" id="noteInput" rows="4" placeholder="Add note..."
                                    class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition resize-none"></textarea>
                            </div>

                            <!-- Footer -->
                            <div class="flex justify-end gap-2 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                                <button id="cancelNoteBtn"
                                    class="px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
                                    Cancel
                                </button>
                                <button id="saveNoteBtn"
                                    class="px-4 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                    Save
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="flex flex-col gap-2  p-1 max-h-[30vh] overflow-auto" id="noteContainer">

                        <div class="w-full p-2 rounded-md text-center">
                            <p class="font-semibold text-zinc-400">Theres no notes found</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
            <div>
                <label class="block text-sm font-medium mb-1">
                    Contact Name
                </label>
                <input type="text" id="contact_name" class="lead-input w-full border rounded-lg px-3 py-2"
                    readonly>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Email
                </label>
                <input type="email" id="email" class="lead-input w-full border rounded-lg px-3 py-2" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Mobile
                </label>
                <input type="text" id="mobile" class="lead-input w-full border rounded-lg px-3 py-2" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Source
                </label>
                <input type="text" id="source" class="lead-input w-full border rounded-lg px-3 py-2" readonly>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Estimated Value
                </label>
                <input type="number" id="estimated_value" class="lead-input w-full border rounded-lg px-3 py-2"
                    readonly>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Expected Close Date
                </label>
                <input type="date" id="expected_close_date" class="lead-input w-full border rounded-lg px-3 py-2"
                    readonly>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Status
                </label>
                <input type="text" id="status" class="w-full border rounded-lg px-3 py-2 bg-gray-100" readonly>
            </div>

        </div>

    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">

        <button class="modal-close border px-4 py-2 rounded-lg">
            Close
        </button>

    </div>

</x-modal>


<x-new-proposal-modal />

<script>
    (function() {
        initCrmLogic();
    })();
</script>
