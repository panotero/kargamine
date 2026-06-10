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

                            <option value="LEAD">LEAD</option>
                            <option value="QUALIFIED">QUALIFIED</option>
                            <option value="OPPORTUNITY">OPPORTUNITY</option>
                            <option value="NEGOTIATION">NEGOTIATION</option>
                            <option value="WIN">WIN</option>
                            <option value="LOST">LOST</option>

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

                    <div class="px-3 my-auto text-sm rounded-full bg-green-600 text-white" id="leadStatus">
                        status
                    </div>
                </div>
            </div>
            <div class="flex gap-2 text-md">
                some info
            </div>
        </div>
        <div class="h-full items-center">
            <button id="deleteLeadBtn" class="p-2 aspect-square bg-red-400 rounded-full text-white"><svg
                    xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">

                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                    <path d="M10 11v6"></path>
                    <path d="M14 11v6"></path>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                </svg></button>
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
                    <label for="contaceName" class="text-sm font-semibold text-zinc-300">Contace Name</label>
                    <input type="text" class="w-full bg-white dark:bg-zinc-600" name="contaceName"
                        id="contaceName">
                    <label for="contactEmail" class="text-sm font-semibold text-zinc-300">Contact Email</label>
                    <input type="text" class="w-full bg-white dark:bg-zinc-600" name="contactEmail"
                        id="contactEmail">
                    <label for="contactMobile" class="text-sm font-semibold text-zinc-300">Contact Mobile</label>
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
                            class="modaldropdown hidden absolute right-0 top-10 w-72 bg-white dark:bg-zinc-700 border rounded-md  p-3 z-50 flex flex-col gap-2 shadow-xl shadow-black/10 dark:shadow-gray-200/10">

                            <div>
                                <p class="text-md">Add New Activity</p>
                            </div>
                            <div>

                                <label for="type" class="text-sm font-semibold text-zinc-300">Note</label>
                                <input type="text" name="type" id="activityTypeInput"
                                    class="w-full bg-white dark:bg-zinc-600 rounded-md">

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
        let statusarray = [];
        let leadID = "";
        let leadsArray = [];
        async function getLeads() {

            const leads = await apiCall({
                mode: "GET",
                url: "/api/crm/leads"
            });

            leadsArray = leads;
            return leads;
        }

        function renderCounts(leads) {

            const counts = {
                LEAD: 0,
                QUALIFIED: 0,
                OPPORTUNITY: 0,
                NEGOTIATION: 0,
                WIN: 0,
                LOST: 0
            };
            let total = 0;

            leads.forEach(row => {
                total++;
                const status = row.status.status;
                if (counts.hasOwnProperty(status)) {
                    counts[status]++;
                }
            });

            document.getElementById("countALL").innerText = total;
            document.getElementById("countLead").innerText = counts.LEAD;
            document.getElementById("countQualified").innerText = counts.QUALIFIED;
            document.getElementById("countOpportunity").innerText = counts.OPPORTUNITY;
            document.getElementById("countNegotiation").innerText = counts.NEGOTIATION;
            document.getElementById("countWin").innerText = counts.WIN;
            document.getElementById("countLose").innerText = counts.LOST;
        }

        function renderTable(leads) {
            document.getElementById("crmTableBody").innerHTML = initLoading();
            let html = '';
            leads.forEach(row => {

                const status = row.status?.status ?? "UNKNOWN";

                const statusClass = getStatusBadgeClass(status);

                html += `
        <tr class="cursor-pointer hover:bg-zinc-100"
            data-id="${row.id}">

            <td>${row.contact_name}</td>
            <td>${row.company?.company_name ?? "No Company"}</td>
            <td>${row.email}</td>
            <td>${row.mobile}</td>

            <td>
                <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                    ${status}
                </span>
            </td>

            <td>${row.user.name}</td>
            <td>${formatDateTime(row.created_at)}</td>

        </tr>
    `;
            });

            $("#crmTableBody").html(html);



            initDataTables(10);
        }

        function getStatusBadgeClass(status) {
            switch (status) {

                case "LEAD":
                    return "bg-gray-100 text-gray-700";

                case "QUALIFIED":
                    return "bg-indigo-100 text-indigo-700";

                case "OPPORTUNITY":
                    return "bg-purple-100 text-purple-700";

                case "NEGOTIATION":
                    return "bg-amber-100 text-amber-700";

                case "WIN":
                    return "bg-green-100 text-green-700";

                case "LOST":
                    return "bg-red-100 text-red-700";

                default:
                    return "bg-zinc-100 text-zinc-700";
            }
        }
        $(document).on("click", "#crmTable tbody tr", function() {

            const id = $(this).data("id");

            initModal({
                modalId: "LeadInfoModal",
            });
            leadID = id;
            loadLeadInfo();


        });
        $("#saveLeadBtn").on("click", async function(e) {
            const submitBtn = $("#saveLeadBtn");
            const form = $("#leadForm")[0];

            e.preventDefault();
            const formData = new FormData();

            formData.append("contact_name", form.contact_name.value);
            formData.append("mobile", form.mobile.value);
            formData.append("email", form.email.value);
            formData.append("company_name", form.company_name.value);
            formData.append("position", form.position.value);
            formData.append("status", form.status.value);
            formData.append("est_value", form.status.value);
            formData.append("source", form.source.value);
            formData.append("notes", form.notes.value);


            const response = await apiCall({
                mode: "POST",
                isJson: false,
                payload: formData,
                url: "/api/crm/leads",
                button: document.getElementById("saveLeadBtn")
            });

            if (!response.success) {

                showMessage({
                    status: "error",
                    title: "Error Saving Company Information",
                    message: "There is some error saving your company information. Please contact system administrator",
                });
                return;
            };

            showMessage({
                status: "success",
                title: "Successfully Saved Company Info. Keep it up!",
            });

            const leads = await getLeads();
            renderTable(leads);
            renderCounts(leads);

            clearInputs();
            closeSideModal("LeadDetailsSideModal");

        });
        async function updateLead(id) {
            console.log("Update Lead", id);

            /*
            const response = await apiCall({
                mode: "PUT",
                url: `/api/crm/leads/${id}`,
                body: formData
            });
            */
        }
        async function deleteLead(id) {
            console.log("Delete Lead", id);

            /*
            const response = await apiCall({
                mode: "DELETE",
                url: `/api/crm/leads/${id}`
            });
            */
        }

        initializepage();
        async function initializepage() {
            await getLeads();
            renderTable(leadsArray);

            renderCounts(leadsArray);

            $("#btnNewLead").click(function() {
                // initModal({
                //     modalId: "NewLeadModal",
                // });
                initSideModal({
                    modalId: "LeadDetailsSideModal"
                });
            });


            getStatuses();

        }

        async function getStatuses() {
            const statusdropdown = document.querySelectorAll(".statusDropDown");

            const statuses = await apiCall({
                mode: "GET",
                url: "/api/crm/getCrmStatus"
            });

            let html = "";
            statusdropdown.forEach(dropdown => {
                statuses.data.forEach(status => {
                    statusarray.push({
                        id: status.id,
                        status: status.status
                    });
                    html += `
                        <option value="${status.id}">${status.status}</option>`;

                });
                dropdown.innerHTML = html;
            });
        }


        async function loadLeadInfo() {
            const loader = loadingLine();
            $('#leadCompanyName').html(loader);
            $('#leadStatus').html(loader);
            $('#leadContactName').html(loader);
            $('#leadEmail').html(loader);
            $('#leadMobile').html(loader);
            $('#leadSource').html(loader);
            $('#leadEstimatedValue').html(loader);
            $('#leadCreatedAt').html(loader);
            $('#leadExpectedCloseDate').html(loader);
            $('#noteContainer').html(loader);
            $('#activityContainer').html(loader);


            const leads = await apiCall({
                mode: "GET",
                url: `/api/crm/leads/${leadID}`
            });
            console.log(leads);
            if (!leads.success) {
                showMessage({
                    status: "error",
                    title: "Error Saving Company Information",
                    message: "There is some error saving your company information. Please contact system administrator",
                });
                return;
                closemodals();
            }
            const lead = leads.data;
            //company info
            $('#leadCompanyName').html(lead.company.company_name.toUpperCase() ?? '');
            $('#leadStatus').html(lead.status.status ?? '');
            $('#leadContactName').html(lead.contact_name ?? '');
            $('#leadEmail').html(lead.email ?? '');
            $('#leadMobile').html(lead.mobile ?? '');
            $('#leadSource').html(lead.source ?? '');
            $('#leadEstimatedValue').html(lead.estimated_value ?? '');
            $('#leadCreatedAt').html(formatDateTime(lead.created_at) ?? '');
            $('#leadExpectedCloseDate').html(formatDateTime(lead.expected_close_date) ?? '');



            $('#btnEditLead').removeClass('hidden');
            $('#btnSaveLead').addClass('hidden');

            //render activities
            renderActivity(leads.data.activities);

            //render notes
            renderNotes(leads.data.notes);
        }

        function getLeadFormData() {
            return {
                contact_name: $('#contact_name').val(),
                email: $('#email').val(),
                mobile: $('#mobile').val(),
                source: $('#source').val(),
                estimated_value: $('#estimated_value').val(),
                expected_close_date: $('#expected_close_date').val()
            };
        }
        $(document).on('click', '#btnEditLead', function() {
            $('.lead-input').prop('readonly', false);

            $('#btnEditLead').addClass('hidden');
        });

        $(document).on('input change', '.lead-input', function() {
            const changed =
                JSON.stringify(getLeadFormData()) !==
                JSON.stringify(originalLeadData);

            $('#btnSaveLead').toggleClass('hidden', !changed);
        });

        $(document).on('click', '#btnSaveLead', async function() {


            const response = await apiCall({
                mode: "PUT",
                isJson: true,
                payload: JSON.stringify(getLeadFormData()),
                url: "/api/crm/leads",
                button: document.getElementById("btnSaveLead")
            });

            toastr.success('Lead updated successfully');

            originalLeadData = getLeadFormData();

            $('.lead-input').prop('readonly', true);

            $('#btnSaveLead').addClass('hidden');
            $('#btnEditLead').removeClass('hidden');
        });

        document.querySelectorAll(".statusBtn").forEach(statusButton => {
            statusButton.addEventListener("click", function() {
                const status = statusButton.dataset.status;
                let filtered = leadsArray.filter(row => row.status.status === status);
                if (status === "ALL") {
                    filtered = leadsArray
                }

                //call rendertable here

                renderTable(filtered);
            });
        });


        function renderActivity(activities) {
            //build activity html
            let html = "";

            //select the container
            const activityContainer = document.getElementById("activityContainer");
            //foreach logic for activities
            activities.forEach(activity => {
                html += `
            <div class="w-full p-2 border border-zinc-300 rounded-md flex  justify-between  items-center">
                            <div class="flex flex-col gap-2">

                                <div class="flex flex-col">
                                <p class="text-lg font-semibold">${activity.type}</p>
                                <p class="text-md">${activity.type}</p>
                                    </div>
                                <p class="text-xs font-light">${formatDateTime(activity.created_at)} ${activity.user.name}</p>
                            </div>
                        </div>`;

            });

            if (activities.length === 0) {
                html = `

                        <div class="w-full p-2 rounded-md text-center">
                            <p class="font-semibold text-zinc-400">Theres no notes</p>
                            </div>
                            `
            }
            //append html to the container
            activityContainer.innerHTML = html;

        }

        function renderNotes(notes) {
            let html = "";
            //select the container
            const noteContainer = document.getElementById("noteContainer");

            //foreach logic for activities
            notes.forEach(note => {
                //build activity html
                html += `
                        <div class="w-full p-2 border border-zinc-300 rounded-md">
                            <div class="flex justify-between">

                                <p class="text-xs font-light">${formatDateTime(note.created_at)} ${note.user.name}</p>
                                <button><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="2"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <circle cx="19" cy="12" r="2"></circle>
                                    </svg></button>

                            </div>

                            <p class="text-md">${note.note}</p>
                        </div>
            `;
            });
            if (notes.length === 0) {
                html = `

                        <div class="w-full p-2 border border-zinc-300 rounded-md text-center">
                            <p class="font-semibold text-zinc-400">Theres no notes</p>
                            </div>
                            `
            }

            //append html to the container
            noteContainer.innerHTML = html;



        }

        //adding of notes and activity function
        const addActivityBtn = document.getElementById('addActivityBtn');
        const activityDropdown = document.getElementById('activityDropdown');
        const activityDescriptionInput = document.getElementById('activityDescriptionInput');
        const activityTypeInput = document.getElementById('activityTypeInput');
        const saveActivityBtn = document.getElementById('saveActivityBtn');
        const cancelActivityBtn = document.getElementById('cancelActivityBtn');

        const addNoteBtn = document.getElementById('addNoteBtn');
        const noteDropdown = document.getElementById('noteDropdown');
        const noteInput = document.getElementById('noteInput');
        const saveNoteBtn = document.getElementById('saveNoteBtn');
        const cancelNoteBtn = document.getElementById('cancelNoteBtn');


        const editContactBtn = document.getElementById('editContactBtn');
        const editContactInfoDropdown = document.getElementById('editContactInfoDropdown');
        const saveContactInfoBtn = document.getElementById('saveContactInfoBtn');
        const cancelContactInfoBtn = document.getElementById('cancelContactInfoBtn');



        // OPEN / CLOSE HELPERS
        function openDropdown(dropdown) {
            dropdown.classList.remove('hidden');
        }

        function closeDropdown(dropdown) {
            dropdown.classList.add('hidden');
        }


        // =======================
        // ACTIVITY EVENTS
        // =======================

        addActivityBtn.addEventListener('click', () => {
            openDropdown(activityDropdown);
        });

        saveActivityBtn.addEventListener('click', async function() {


            const payload = {
                leadId: leadID,
                type: activityTypeInput.value,
                activity: activityDescriptionInput.value
            };

            const response = await apiCall({
                mode: "POST",
                isJson: true,
                payload: payload,
                url: "/api/crm/activity",
                button: saveActivityBtn
            });
            console.log(response);
            if (!response.success) {

                showMessage({
                    status: "error",
                    title: "Error Saving Activity",
                });
                return;
            };

            showMessage({
                status: "success",
                title: "Activity Saved!",
            });
            activityTypeInput.value = '';
            activityDescriptionInput.value = '';
            closeDropdown(activityDropdown);
            loadLeadInfo();

        });

        cancelActivityBtn.addEventListener('click', () => {
            closeDropdown(activityDropdown);
            activityTypeInput.value = '';
            activityDescriptionInput.value = '';
        });


        // =======================
        // NOTE EVENTS
        // =======================

        addNoteBtn.addEventListener('click', () => {
            openDropdown(noteDropdown);
        });

        saveNoteBtn.addEventListener('click', async function() {

            const payload = {
                leadId: leadID,
                note: noteInput.value
            };

            const response = await apiCall({
                mode: "POST",
                isJson: true,
                payload: payload,
                url: "/api/crm/note",
                button: saveNoteBtn
            });
            console.log(response);
            if (!response.success) {

                showMessage({
                    status: "error",
                    title: "Error Saving Note",
                });
                return;
            };

            showMessage({
                status: "success",
                title: "Note saved!",
            });

            noteInput.value = '';
            closeDropdown(noteDropdown);
            loadLeadInfo();
        });

        cancelNoteBtn.addEventListener('click', () => {
            noteInput.value = '';
            closeDropdown(noteDropdown);
        });

        editContactBtn.addEventListener("click", () => {
            openDropdown(editContactInfoDropdown);

        });
        saveContactInfoBtn.addEventListener('click', () => {

        });
        cancelContactInfoBtn.addEventListener('click', () => {
            closeDropdown(editContactInfoDropdown);
        });


        // =======================
        // CLICK OUTSIDE CLOSE
        // (still scoped, no document query loops)
        // =======================

        window.addEventListener('click', (e) => {

            const isActivityClick =
                addActivityBtn.contains(e.target) ||
                activityDropdown.contains(e.target);

            const isNoteClick =
                addNoteBtn.contains(e.target) ||
                noteDropdown.contains(e.target);

            const iseditContactClick =
                editContactBtn.contains(e.target) ||
                editContactInfoDropdown.contains(e.target);

            if (!isActivityClick) closeDropdown(activityDropdown);
            if (!isNoteClick) closeDropdown(noteDropdown);
            if (!iseditContactClick) closeDropdown(editContactInfoDropdown);
        });

    })();
</script>
