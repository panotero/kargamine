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
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white" required>
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
                            class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
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
            <div>
                <p class="text-lg font-semibold" id="leadCompanyName">Company Name</p>
            </div>
            <div class="flex gap-2 text-md">
                some info
            </div>
        </div>
        <div class="h-full items-center">

            <div class="px-3 py-1 text-sm rounded-full bg-green-600 text-white" id="leadStatus">
                status
            </div>
        </div>
    </div>

    <div class="max-h-[60vh]   overflow-auto">
        <div class="lg:min-h-[50vh] h-full grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2 flex flex-col p-5 gap-5">
                <div class="flex flex-col gap-3">
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Name</p>
                        <p class="text-lg" id="leadContactName"> Contact Name</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Email</p>
                        <p class="text-lg" id="leadEmail">jdc@email.com</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Mobile</p>
                        <p class="text-lg" id="leadMobile">+63912 3456 789</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Source</p>
                        <p class="text-lg" id="leadSource">Facebook</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Estimated Value</p>
                        <p class="text-lg" id="leadEstimatedValue">$500,000</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Date Added</p>
                        <p class="text-lg" id="leadCreatedAt">January 1, 2026</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-500">Expected Close Date</p>
                        <p class="text-lg" id="leadExpectedCloseDate">January 16, 2026</p>
                    </div>

                </div>

            </div>
            <div class="md:col-span-4 flex flex-col md:border-l border-zinc-300 p-5 gap-5">
                <div class="w-full space-y-3">

                    <div class="w-full flex justify-between items-center">

                        <p class="text-lg font-bold text-zinc-500">activities
                        </p>
                        <button class="px-3 py-1 rounded-full font-semibold bg-zinc-100 border drop-shadow-md text-sm">
                            add
                            +</button>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="w-full p-2 border border-zinc-300 rounded-md flex  justify-between  items-center">
                            <div>

                                <p class="text-md font-semibold">activity name</p>
                                <p class="text-xs font-light">date time user</p>
                            </div>
                            <div class="px-3 py-1 text-sm rounded-full bg-blue-600 text-white">
                                status
                            </div>
                        </div>
                        <div class="w-full p-2 border border-zinc-300 rounded-md flex  justify-between  items-center">
                            <div>

                                <p class="text-md font-semibold">activity name</p>
                                <p class="text-xs font-light">date time user</p>
                            </div>
                            <div class="px-3 py-1 text-sm rounded-full bg-blue-600 text-white">
                                status
                            </div>
                        </div>

                    </div>
                </div>
                <div class="w-full space-y-3">
                    <div class="w-full flex justify-between items-center">

                        <p class="text-lg font-bold text-zinc-500">notes
                        </p>
                        <button class="px-3 py-1 rounded-full font-semibold bg-zinc-100 border drop-shadow-md text-sm">
                            add
                            +</button>
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="w-full p-2 border border-zinc-300 rounded-md">
                            <div class="flex justify-between">

                                <p class="text-md font-semibold">created by : date time</p>
                                <button><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="2"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <circle cx="19" cy="12" r="2"></circle>
                                    </svg></button>

                            </div>

                            <p class="text-md">notes</p>
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

        <button id="btnDeleteLead" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
            Delete
        </button>

        <button id="btnEditLead" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            Edit
        </button>

        <button id="btnSaveLead" class="hidden bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            Save
        </button>

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

            console.log("Open Lead", id);

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
            renderTable();

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

            const leads = await apiCall({
                mode: "GET",
                url: `/api/crm/leads/${leadID}`
            });
            console.log(leads.data);
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
            if (!response.success) {

            }

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

    })();
</script>
