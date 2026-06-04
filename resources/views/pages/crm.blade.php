<div class="flex justify-between items-center mb-5">

    <div>
        <h1 class="text-2xl font-bold">CRM Leads</h1>
        <p class="text-zinc-500">Manage leads and sales opportunities</p>
    </div>

    <button id="btnNewLead" class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
        + New Lead
    </button>

</div>

<div class=" overflow-auto bg-white rounded-lg text-black border-2 border-slate-100 drop-shadow-md p-5">
    <div class="w-full rounded-lg overflow-hidden">
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

    </div>
</div>


<x-modal id="NewLeadModal">

    <div class="p-5 border-b flex justify-between">

        <p class="text-xl font-semibold dark:text-white">
            New CRM Lead
        </p>

    </div>

    <form id="leadForm">

        <div class="p-5">

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

                    <select name="status" class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">

                        <option value="LEAD">LEAD</option>
                        <option value="QUALIFIED">QUALIFIED</option>
                        <option value="OPPORTUNITY">OPPORTUNITY</option>
                        <option value="NEGOTIATION">NEGOTIATION</option>
                        <option value="WIN">WIN</option>
                        <option value="LOST">LOST</option>

                    </select>

                </div>

                <div class="flex flex-col">
                    <label>Assigned To</label>
                    <input type="text" name="assigned_to"
                        class="border p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
                </div>

            </div>

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

    </form>
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
        async function getLeads() {
            return [{
                    id: 1,
                    contact_name: "John Smith",
                    mobile: "+1 555 123 4567",
                    email: "john@acme.com",
                    company_name: "Acme Corporation",
                    position: "Procurement Manager",
                    status: "LEAD",
                    assigned_to: "Minton",
                    created_at: "2026-06-05"
                },
                {
                    id: 2,
                    contact_name: "Sarah Johnson",
                    mobile: "+1 555 999 1234",
                    email: "sarah@globex.com",
                    company_name: "Globex Inc.",
                    position: "Operations Director",
                    status: "NEGOTIATION",
                    assigned_to: "Minton",
                    created_at: "2026-06-04"
                },
                {
                    id: 3,
                    contact_name: "Michael Reyes",
                    mobile: "+63 917 123 4567",
                    email: "michael@northstar.ph",
                    company_name: "Northstar Logistics",
                    position: "General Manager",
                    status: "QUALIFIED",
                    assigned_to: "Minton",
                    created_at: "2026-06-03"
                }
            ];

            /*
            const leads = await apiCall({
                mode: "GET",
                url: "/api/crm/leads"
            });

            return leads;
            */
        }

        async function renderTable() {
            const leads = await getLeads();

            let html = '';

            leads.forEach(row => {

                html += `
            <tr class="cursor-pointer hover:bg-zinc-100"
                data-id="${row.id}">

                <td>${row.contact_name}</td>
                <td>${row.company_name}</td>
                <td>${row.email}</td>
                <td>${row.mobile}</td>
                <td>${row.status}</td>
                <td>${row.assigned_to}</td>
                <td>${row.created_at}</td>

            </tr>
        `;
            });

            $("#crmTableBody").html(html);

            initDataTables(5);
        }
        $(document).on("click", "#crmTable tbody tr", function() {

            const id = $(this).data("id");

            console.log("Open Lead", id);

            initModal({
                modalId: "LeadInfoModal",
            });

        });
        $("#leadForm").on("submit", async function(e) {
            const submitBtn = $("#saveLeadBtn");

            e.preventDefault();

            // const formData = new FormData();

            // formData.append(
            //     "contact_name",
            //     $('[name="contact_name"]').val()
            // );

            // formData.append(
            //     "mobile",
            //     $('[name="mobile"]').val()
            // );

            // formData.append(
            //     "email",
            //     $('[name="email"]').val()
            // );

            // formData.append(
            //     "company_name",
            //     $('[name="company_name"]').val()
            // );

            // formData.append(
            //     "position",
            //     $('[name="position"]').val()
            // );

            // formData.append(
            //     "status",
            //     $('[name="status"]').val()
            // );

            // formData.append(
            //     "assigned_to",
            //     $('[name="assigned_to"]').val()
            // );

            // console.log(formData);

            /*
            const response = await apiCall({
                mode: "POST",
                url: "/api/crm/leads",
                body: formData
            });
            */

            const formData = {
                contact_name: "Juan Dela Cruz",
                email: "juan@email.com",
                mobile: "09171234567",
                status: 1, // crm_status ID
                source: "Facebook Ads",
                assigned_to: 3, // user ID
                estimated_value: 150000,
                expected_close_date: "2026-06-30",

                company: {
                    company_name: "Juan Marketing Agency",
                    position: "CEO"
                },

                notes: [
                    "Initial inquiry from Facebook ad",
                    "Client is interested in premium package",
                    "Follow up next week"
                ]
            };

            const response = await apiCall({
                mode: "POST",
                isJson: true,
                payload: formData,
                url: "/api/crm/leads",
                button: submitBtn
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

            console.log(response);
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
        console.log(pageLoaded);
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

        }



    })();
</script>
