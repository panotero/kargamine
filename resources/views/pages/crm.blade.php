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
    <x-table-container>
        <table id="crmTable" class="w-full">
            <thead>
                <tr>
                    <th>Contact</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody id="crmTableBody">

            </tbody>

        </table>
    </x-table-container>

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

                    <div class="flex flex-col">
                        <label>Contact Name</label>
                        <input type="text" name="contact_name"
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
                    </div>

                    <div class="flex flex-col">
                        <label>Mobile</label>
                        <input type="text" name="mobile"
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white format-mobile" required>
                    </div>

                    <div class="flex flex-col col-span-2">
                        <label>Email</label>
                        <input type="email" name="email"
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white" required>
                    </div>

                    <div class="flex flex-col">
                        <label>Company Name</label>
                        <input type="text" name="company_name"
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white"required>
                    </div>

                    <div class="flex flex-col">
                        <label>Position / Role</label>
                        <input type="text" name="position"
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white" required>
                    </div>

                    <div class="flex flex-col">
                        <label>Status</label>

                        <select name="status"
                            class="statusDropDown border p-2 rounded-lg dark:bg-zinc-600 dark:text-white" required>

                        </select>

                    </div>

                    <div class="flex flex-col">
                        <label>Source</label>
                        <input type="text" name="source"
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white" required>
                    </div>
                    <div class="flex flex-col col-span-2">
                        <label>Estemated Value</label>
                        <input type="text" name="est_value"
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white format-currency">
                    </div>
                    <div class="flex flex-col col-span-2">
                        <label>Notes</label>
                        <textarea name="notes" id="notes"rows="6" class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white"></textarea>
                    </div>


                </div>

            </form>
        </div>

        <div class="border-t px-5 py-4 flex justify-end gap-2">


            <button type="submit" id="saveLeadBtn"
                class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
                Save Lead
            </button>

            <button
                class="modal-close border border-gray-300  text-gray-700  hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-white px-5 py-2 rounded-lg text-sm font-medium">
                Cancel
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
            <button id="deleteLeadBtn" class="p-2  bg-orange-600 rounded-lg text-white"> <b class="font-black">+</b>
                Proposal</button>
        </div>
    </div>

    <div class="max-h-[60vh] overflow-auto">
        <div class="lg:min-h-[50vh] h-full grid grid-cols-1 md:grid-cols-6 relative">

            <div id="editContactInfoDropdown"
                class="modaldropdown hidden absolute left-1/2 top-10 w-72 bg-white dark:bg-zinc-700 border rounded-md p-3 z-50 -translate-x-1/2 shadow-xl shadow-black/10 dark:shadow-gray-200/10">
                <div>
                    <p class="text-md">Edit Contact Information</p>
                </div>
                <div class="bg-white dark:bg-zinc-700">
                    <label for="contactName" class="editContactDropdown text-sm font-semibold text-zinc-300">Contace
                        Name</label>
                    <input type="text" class="w-full bg-white dark:bg-zinc-600" name="contactName"
                        id="contactName">
                    <label for="contactEmail" class="editContactDropdown text-sm font-semibold text-zinc-300">Contact
                        Email</label>
                    <input type="email" class="w-full bg-white dark:bg-zinc-600" name="contactEmail"
                        id="contactEmail">
                    <label for="contactMobile" class="editContactDropdown text-sm font-semibold text-zinc-300">Contact
                        Mobile</label>
                    <input type="text" class="w-full bg-white dark:bg-zinc-600" name="contactMobile"
                        id="contactMobile">
                </div>

                <div class="flex justify-end gap-2 mt-2">
                    <button id="cancelContactInfoBtn" class="px-3 py-1 text-sm bg-white dark:bg-zinc-600 rounded">
                        Cancel
                    </button>
                    <button id="saveContactInfoBtn" class="px-3 py-1 text-sm bg-blue-500 text-white rounded">
                        Save
                    </button>
                </div>
            </div>
            <div class="md:col-span-2 flex flex-col gap-5 md:border-r border-zinc-300  relative">

                <div class="absolute top-0 right-0 p-2">

                    <button id="editContactBtn"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                        </svg></button>
                </div>
                <div class="flex flex-col gap-3  p-5 ">

                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Name</p>
                        <p class="text-lg" id="leadContactName">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Email</p>
                        <p class="text-lg" id="leadEmail">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Mobile</p>
                        <p class="text-lg" id="leadMobile">p-3</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Source</p>
                        <p class="text-lg" id="leadSource">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Estimated Value</p>
                        <p class="text-lg" id="leadEstimatedValue">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Date Added</p>
                        <p class="text-lg" id="leadCreatedAt">-</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Expected Close Date</p>
                        <p class="text-lg" id="leadExpectedCloseDate">-</p>
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
                            class="modaldropdown hidden absolute right-0 top-10 w-full bg-white dark:bg-zinc-700 border rounded-md  p-3 z-50 flex flex-col gap-2 shadow-xl shadow-black/10 dark:shadow-gray-200/10">

                            <div>
                                <p class="text-md">Add New Activity</p>
                            </div>
                            <div class="flex gap-2">
                                <div>

                                    <label for="type" class="text-sm font-semibold text-zinc-300">Status</label>
                                    <select name="status" id="activityStatusInput"
                                        class="statusDropDown border p-2 rounded-lg dark:bg-zinc-600 dark:text-white w-full" "
                                        required>
                                        <option value="">Select Status</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="type" class="text-sm font-semibold text-zinc-300">Activity</label>
                                    <input type="text" name="type" id="activityTypeInput"
                                        class="w-full bg-white dark:bg-zinc-600 rounded-md">

                                </div>

                            </div>
                            <div>

                                <label for="activityDescriptionInput"
                                    class="text-sm font-semibold text-zinc-300">Description</label>
                                <textarea name="activityDescriptionInput" id="activityDescriptionInput"
                                    class="w-full bg-white dark:bg-zinc-600 rounded-md p-2" rows="3" placeholder="Add activity..."></textarea>

                            </div>

                            <div class="flex justify-end gap-2 mt-2">
                                <button id="cancelActivityBtn"
                                    class="px-3 py-1 text-sm bg-white dark:bg-zinc-600 rounded">
                                    Cancel
                                </button>
                                <button id="saveActivityBtn" class="px-3 py-1 text-sm bg-blue-500 text-white rounded">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse gap-2" id="activityContainer">

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
                            class=" modaldropdown hidden absolute right-0 top-10 w-72 bg-white dark:bg-zinc-700 border rounded-md p-3 z-50 flex flex-col gap-2 shadow-xl shadow-black/10 dark:shadow-gray-200/10">

                            <div>
                                <p class="text-md">Add New Note</p>
                            </div>
                            <div>
                                <label for="noteInput" class="text-sm font-semibold text-zinc-300">Note</label>
                                <textarea name="noteInput" id="noteInput" class="w-full bg-white dark:bg-zinc-600 rounded-md p-2" rows="3"
                                    placeholder="Add note..."></textarea>
                            </div>

                            <div class="flex justify-end gap-2 mt-2">
                                <button id="cancelNoteBtn"
                                    class="px-3 py-1 text-sm bg-white dark:bg-zinc-600 rounded">
                                    Cancel
                                </button>
                                <button id="saveNoteBtn" class="px-3 py-1 text-sm bg-blue-500 text-white rounded">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col-reverse gap-2" id="noteContainer">

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

<script>
    (function() {
        initCrmLogic();
    })();
</script>
