<div class="flex justify-between items-center mb-5">

    <div>
        <h1 class="text-2xl font-bold">CRM Leads</h1>
        <p class="text-zinc-500">Manage leads and sales opportunities</p>
    </div>

    <button id="btnNewLead" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
        + New Lead
    </button>

</div>
<!-- CRM Status Count Cards -->
<section class="w-full my-10">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">

        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">LEAD</p>
            <p class="text-2xl font-bold text-black" id="countLead">0</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">QUALIFIED</p>
            <p class="text-2xl font-bold text-black" id="countQualified">0</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">OPPORTUNITY</p>
            <p class="text-2xl font-bold text-black" id="countOpportunity">0</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">NEGOTIATION</p>
            <p class="text-2xl font-bold text-black" id="countNegotiation">0</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">WIN</p>
            <p class="text-2xl font-bold text-black" id="countWin">0</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">LOST</p>
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




<x-modal id="NewLeadModal">

    <div class="p-5 border-b flex justify-between">

        <p class="text-xl font-semibold dark:text-white">
            New CRM Lead
        </p>

    </div>


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
                    <input type="text" name="mobile" class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
                </div>

                <div class="flex flex-col col-span-2">
                    <label>Email</label>
                    <input type="email" name="email" class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
                </div>

                <div class="flex flex-col">
                    <label>Company Name</label>
                    <input type="text" name="company_name"
                        class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
                </div>

                <div class="flex flex-col">
                    <label>Position / Role</label>
                    <input type="text" name="position"
                        class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
                </div>

                <div class="flex flex-col">
                    <label>Status</label>

                    <select name="status"
                        class="statusDropDown border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">

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
                    <input type="text" name="source" class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
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

</x-modal>


<x-modal id="LeadInfoModal">

    <div class="p-5 border-b flex justify-between">

        <p class="text-xl font-semibold dark:text-white">
            Lead Information
        </p>

    </div>

    <div id="leadInfoContainer" class="p-5">
    </div>

    <div class="border-t px-5 py-4 flex justify-end gap-2">

        <button id="btnDeleteLead" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
            Delete
        </button>

        <button id="btnEditLead" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            Edit
        </button>

        <button class="modal-close border px-4 py-2 rounded-lg">
            Close
        </button>

    </div>
</x-modal>

<script>
    (function() {
        let statusarray = [];
        let crmArray = [];
        async function getLeads() {

            const leads = await apiCall({
                mode: "GET",
                url: "/api/crm/leads"
            });
            // console.log(leads);
            return leads;
        }

        async function renderTable() {

            document.getElementById("crmTableBody").innerHTML = "";
            const leads = await getLeads();
            let html = '';

            const counts = {
                LEAD: 0,
                QUALIFIED: 0,
                OPPORTUNITY: 0,
                NEGOTIATION: 0,
                WIN: 0,
                LOST: 0
            };
            leads.forEach(row => {

                crmArray.push(row);
                const status = row.status.status;

                if (counts.hasOwnProperty(status)) {
                    counts[status]++;
                }
                html += `
            <tr class="cursor-pointer hover:bg-zinc-100"
                data-id="${row.id}">

                <td>${row.contact_name}</td>
                <td>${row.company?.company_name ?? "No Company"}</td>
                <td>${row.email}</td>
                <td>${row.mobile}</td>
                <td>${row.status?.status ?? "Unknown Status"}</td>
                <td>${row.assigned_to}</td>
                <td>${row.created_at}</td>

            </tr>
        `;
            });

            $("#crmTableBody").html(html);

            document.getElementById("countLead").innerText = counts.LEAD;
            document.getElementById("countQualified").innerText = counts.QUALIFIED;
            document.getElementById("countOpportunity").innerText = counts.OPPORTUNITY;
            document.getElementById("countNegotiation").innerText = counts.NEGOTIATION;
            document.getElementById("countWin").innerText = counts.WIN;
            document.getElementById("countLose").innerText = counts.LOST;

            console.log(crmArray);

            initDataTables(10);
        }
        $(document).on("click", "#crmTable tbody tr", function() {

            const id = $(this).data("id");

            console.log("Open Lead", id);

            initModal({
                modalId: "LeadInfoModal",
            });

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
            closemodals();

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
        if (pageLoaded) {

            initializepage();
        }
        async function initializepage() {

            await renderTable();

            $("#btnNewLead").click(function() {
                initModal({
                    modalId: "NewLeadModal",
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



    })();
</script>
