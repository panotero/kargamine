<div class="w-full min-h-screen p-5 bg-gray-50  text-black dark:bg-gray-800 dark:text-white ">
    <div class="container mx-auto space-y-6">

        <!-- SUMMARY CARDS -->
        <div class="w-full border rounded-lg bg-white shadow flex flex-col lg:flex-row gap-4 p-4 text-black">
            <div class="flex flex-col w-full lg:w-1/3 gap-4">
                <div class="flex gap-4">
                    <div class="flex-1 border rounded-lg p-4 bg-blue-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Total Users</div>
                        <div id="totalUsers" class="text-2xl font-bold mt-2">0</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-4 bg-green-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Total Current Documents</div>
                        <div id="totalDocuments" class="text-2xl font-bold mt-2">0</div>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1 border rounded-lg p-4 bg-blue-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Active Users</div>
                        <div id="totalActive" class="text-2xl font-bold mt-2">0</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-4 bg-green-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Deactivated Users</div>
                        <div id="totalDeactive" class="text-2xl font-bold mt-2">0</div>
                    </div>
                </div>
            </div>


            <div class="flex flex-1 w-full gap-4 flex-wrap items-end">
                <div class="flex flex-col flex-1 gap-2 hidden">
                    <label class="text-gray-700 font-medium">From</label>

                    <input type="date" class="border rounded-lg p-2 datetimepicker dateFrom" id="filter_datefrom" />
                    <label class="text-gray-700 font-medium">To</label>
                    <input type="date" class="border rounded-lg p-2 datetimepicker dateTo" id="filter_dateto" />

                </div>
                <div class="flex flex-col flex-1 gap-2">
                    <div class="hidden">

                        <label class="text-gray-700 font-medium ">Status</label>
                        <select class="border rounded-lg p-2 w-full " id="filter_status">
                            <option>All</option>
                            <option>Pending</option>
                            <option>Processed</option>
                        </select>
                    </div>
                    <div id="office_filter">
                        <label class="text-gray-700 font-medium office">Office</label>
                        <select class="border rounded-lg p-2 w-full  officeSelect" id="filter_office">
                        </select>
                    </div>
                </div>
                <div class="flex flex-col flex-1 gap-2 hidden">
                    <label class="text-gray-700 font-medium">Label</label>
                    <select class="border rounded-lg p-2 w-full" id="filter_label">
                        <option>All</option>
                    </select>
                </div>

                <div class="flex flex-col flex-1 gap-2 justify-end">
                    <button
                        class="w-full border border-gray-300 rounded-lg p-2 bg-red-500 text-white font-medium hover:bg-red-600 transition">
                        Export PDF
                    </button>
                    <button
                        class="w-full border border-gray-300 rounded-lg p-2 bg-green-500 text-white font-medium hover:bg-green-600 transition">
                        Export Excel
                    </button>
                </div>

            </div>
        </div>

        <!-- USER REPORT TABLE -->
        <div class="w-full border rounded-lg shadow overflow-auto p-5">
            <table id="userReportTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Email</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Designation</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Office</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">Pending</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">For Discussion</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">For Approval</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">Overdue</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
            </table>
        </div>
    </div>
</div>

<script>
    (function() {
        let usersList = []; // store all fetched documents
        fillOfficeDropdown();
        getusers();
        async function getusers() {

            try {
                initDataTables();
                const response = await fetchWithRetry(
                    `/api/users/reports/${window.authUser.office.office_code}`, {
                        method: "GET",
                        headers: {
                            Accept: "application/json"
                        },
                    });


                usersList = response;
                // console.log(response);

                // updateDocumentCounts(filteredDocuments);
                updateUserCounts(response);
                response.forEach((users) => {
                    updaterow(users);
                });



            } catch (err) {
                console.error(err);
                return;
            }
        }



        function updaterow(users) {

            const table = document.getElementById("userReportTable");
            if (!table) return;
            const tableBody = table.querySelector("tbody");
            let dt = null;
            if ($.fn.DataTable.isDataTable(table)) {
                dt = $(table).DataTable();
            }
            // console.log(users);

            //count all documents
            let pendingCount = 0;
            let forDiscussionCount = 0;
            let forApprovalCount = 0;
            let overdueCount = 0;
            const documents = users.documents;

            documents.forEach(docs => {
                const status = docs.status.toLowerCase();
                // console.log(status);
                switch (status) {
                    case "pending":
                        pendingCount++;
                        break;
                    case "for discussion":
                        forDiscussionCount++;
                        break;
                    case "for approval":
                        forApprovalCount++;
                        break;
                }
                // Create Date objects
                if (docs.dueDate === null || docs.due_date === "") return;
                const dueDate = new Date(docs.due_date);
                const now = new Date();

                // Normalize both dates to start of day (00:00:00)
                const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                const startOfDueDate = new Date(dueDate.getFullYear(), dueDate.getMonth(), dueDate
                    .getDate());

                // Compare dates
                if (startOfDueDate < startOfToday) {
                    overdueCount++
                }
            })


            let statuscolor = "bg-gray-100";
            switch (users.status.toLowerCase()) {
                case "active":
                    statuscolor = "bg-green-200";
                    break;
                case "deactivated":
                    statuscolor = "bg-red-200";
                    break;
            }
            // Build one table row matching the column headers
            const rowHtml = `
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.name ?? '-'} <!-- Name -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.email ?? '-'} <!-- Email -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.role ?? '-'} <!-- Designation -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.office.office_name?? '-'} <!-- Office -->
                    </td>
                    <td class="px-4 py-2 text-center text-sm font-medium">
                        ${pendingCount?? 0} <!-- Pending -->
                    </td>
                    <td class="px-4 py-2 text-center text-sm font-medium">
                        ${forDiscussionCount ?? 0} <!-- For Discussion -->
                    </td>
                    <td class="px-4 py-2 text-center text-sm font-medium">
                        ${forApprovalCount ?? 0} <!-- For Discussion -->
                    </td>
                    <td class="px-4 py-2 text-center text-sm font-medium">
                        ${overdueCount ?? 0} <!-- Overdue -->
                    </td>
        <td class="px-4 py-2">
            <div class="px-3 py-1 bg-white rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
                ${users.status  || "-"}
            </div>
        </td>
                `;


            if (dt) {
                const newRow = dt.row.add([
                    users.name ?? "-", // Name
                    users.email ?? "-", // Email
                    users.role ?? "-", // Designation
                    users.office?.office_name ?? "-", // Office
                    pendingCount ?? 0, // Pending
                    forDiscussionCount ?? 0, // For Discussion
                    forApprovalCount ?? 0, // For Approval
                    overdueCount ?? 0, // Overdue
                    `<div class="px-3 py-1 rounded-full text-gray-800 font-semibold text-center ${statuscolor}">${users.status || "-"}</div>`,
                ]).draw(false);

                const rowNode = newRow.node();
                rowNode.classList.add(
                    "transition-colors", "duration-300",
                    "hover:dark:bg-white", "hover:dark:text-black");
            } else {
                const tr = document.createElement("tr");
                tr.innerHTML = rowHtml;
                table.appendChild(tr);
            }
        }

        const officeFilterEl = document.getElementById("filter_office");

        const filterElements = [officeFilterEl];

        filterElements.forEach(el => {
            if (el) {
                el.addEventListener("change", (event) => {
                    const table = document.getElementById("userReportTable");
                    let filteredDocuments = usersList; // start with all documents

                    switch (event.target) {


                        case officeFilterEl:
                            const officeVal = officeFilterEl.value.toLowerCase();
                            if (officeVal && officeVal !== "all") {
                                filteredDocuments = usersList.filter(doc => {
                                    const involved = Array.isArray(doc.involved_office) ?
                                        doc.involved_office : [];
                                    return doc.office.office_code.toLowerCase() ===
                                        officeVal ||
                                        involved.map(o => o.toLowerCase()).includes(
                                            officeVal);
                                });
                            }
                            break;
                    }

                    // console.log("Filtered Documents:", filteredDocuments);

                    /* ---- UPDATE COUNTS + TABLE ---- */
                    updateUserCounts(filteredDocuments);

                    if ($.fn.DataTable.isDataTable(table)) {
                        $(table).DataTable().clear().draw();
                    }

                    filteredDocuments.forEach(doc => {
                        updaterow(doc);
                    });
                });
            }
        });

        function updateUserCounts(userslist) {
            let totalUsers = userslist.length;
            let deactivated = 0;
            let active = 0;
            let totalDocs = 0;



            userslist.forEach(user => {
                user.documents.forEach(docs => {
                    totalDocs++
                });
                switch ((user.status || "").toLowerCase()) {
                    case "active":
                        active++;
                        break;
                    case "deactivated":
                        deactivated++;
                        break;
                }
            });
            document.getElementById("totalUsers").textContent = totalUsers.toLocaleString();
            document.getElementById("totalActive").textContent = active.toLocaleString();
            document.getElementById("totalDeactive").textContent = deactivated.toLocaleString();
            document.getElementById("totalDocuments").textContent = totalDocs.toLocaleString();
        }

        //flatten array function

        function flattenObject(obj, parentKey = "", result = {}) {
            for (const key in obj) {
                if (!obj.hasOwnProperty(key)) continue;

                const newKey = parentKey ? `${parentKey}.${key}` : key;
                const value = obj[key];

                if (Array.isArray(value)) {
                    // Convert array to string
                    result[newKey] = value
                        .map(v => typeof v === "object" ? JSON.stringify(v) : v)
                        .join(" | ");
                } else if (value !== null && typeof value === "object") {
                    flattenObject(value, newKey, result);
                } else {
                    result[newKey] = value;
                }
            }
            return result;
        }
        // -----------------------------
        // Export Excel
        // -----------------------------
        function exportTableToExcel() {
            if (!Array.isArray(usersList) || usersList.length === 0) {
                alert("No documents to export");
                return;
            }
            const cleanedData = usersList.map(
                ({
                    files,
                    activities,
                    involved_office,
                    ...rest
                }) => rest
            );

            const flattenedData = cleanedData.map(doc => flattenObject(doc));

            const worksheet = XLSX.utils.json_to_sheet(flattenedData);
            const workbook = XLSX.utils.book_new();

            XLSX.utils.book_append_sheet(workbook, worksheet, "All Documents");
            XLSX.writeFile(workbook, "user_reports" + datetoday() + ".xlsx ");
        }

        // -----------------------------
        // Export PDF
        // -----------------------------
        function exportTableToPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            doc.autoTable({
                html: "#userReportTable"
            });

            doc.save("users_reports" + datetoday() + ".pdf");
        }

        function datetoday() {

            const today = new Date();

            // Format as DDMMYYYY
            const formattedDate =
                String(today.getDate()).padStart(2, '0') + // Day
                String(today.getMonth() + 1).padStart(2, '0') + // Month (0-indexed)
                today.getFullYear(); // Year
            return formattedDate;
        }

        // -----------------------------
        // Attach export buttons
        // -----------------------------
        document.querySelector('button.bg-red-500').addEventListener('click', exportTableToPDF);
        document.querySelector(
            'button.bg-green-500').addEventListener('click', exportTableToExcel);
    })();
</script>
