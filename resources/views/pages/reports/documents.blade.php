<div class="w-full h-screen p-5 bg-gray-50">
    <div class="container mx-auto space-y-6">
        <div class="w-full border rounded-lg bg-white shadow flex flex-col lg:flex-row gap-4 p-4">
            <div class="flex flex-col w-full lg:w-1/3 gap-4">
                <!-- Dashboard HTML IDs for easier targeting -->
                <div class="flex gap-4">
                    <div id="totalDocuments" class="flex-1 border rounded-lg p-4 bg-blue-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Total Documents</div>
                        <div class="text-2xl font-bold mt-2">0</div>
                    </div>
                    <div id="forDiscussion" class="flex-1 border rounded-lg p-4 bg-yellow-50 text-center">
                        <div class="text-sm font-medium text-gray-700">For Discussion</div>
                        <div class="text-2xl font-bold mt-2">0</div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div id="pending" class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Pending</div>
                        <div class="text-xl font-bold mt-1">0</div>
                    </div>
                    <div id="processed" class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Processed</div>
                        <div class="text-xl font-bold mt-1">0</div>
                    </div>
                    <div id="overdue" class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Overdue</div>
                        <div class="text-xl font-bold mt-1">0</div>
                    </div>
                    <div id="remanded" class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Remanded</div>
                        <div class="text-xl font-bold mt-1">0</div>
                    </div>
                </div>

            </div>
            <div class="flex flex-1 w-full gap-4 flex-wrap items-end">
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-gray-700 font-medium">From</label>

                    <input type="date" class="border rounded-lg p-2 datetimepicker dateFrom" id="filter_datefrom" />
                    <label class="text-gray-700 font-medium">To</label>
                    <input type="date" class="border rounded-lg p-2 datetimepicker dateTo" id="filter_dateto" />

                </div>
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-gray-700 font-medium ">Status</label>
                    <select class="border rounded-lg p-2 w-full " id="filter_status">
                        <option>All</option>
                        <option>Pending</option>
                        <option>Processed</option>
                    </select>
                    <label class="text-gray-700 font-medium office">Office</label>
                    <select class="border rounded-lg p-2 w-full  officeSelect" id="filter_office">
                    </select>
                </div>
                <div class="flex flex-col flex-1 gap-2">
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
        <div class="w-full bg-white dark:bg-gray-800  rounded-xl shadow">
            <table id="reportsocumentsTable" class=" text-sm text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Control #</th>
                        <th class="px-4 py-3">Document Number</th>
                        <th class="px-3 py-3">Label</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-3 py-3">Origin Office</th>
                        <th class="px-3 py-3">Destination Office</th>
                        <th class="px-4 py-3">Due Date</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">Date Uploaded</th>
                        <th class="px-4 py-3">Confidentiality</th>
                        <th class="px-3 py-3">Status</th>

                        <!-- Hidden columns for export -->
                        <th class="hidden">User</th>
                        <th class="hidden">Recipient</th>
                        <th class="hidden">Document Form</th>
                        <th class="hidden">Document Type</th>
                        <th class="hidden">Date of Document</th>
                        <th class="hidden">Signatory</th>
                        <th class="hidden">Receipt Confirmed By</th>
                        <th class="hidden">Involved Office</th>
                        <th class="hidden">Action Taken</th>
                        <th class="hidden">Remarks</th>
                        <th class="hidden">Created At</th>
                        <th class="hidden">Updated At</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100 dark:bg-gray-700 dark:text-white">
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    (function() {



        let allDocuments = []; // store all fetched documents

        async function initDashboard() {
            const authUser = window.authUser;
            if (!authUser) return;

            const userOffice = authUser.office?.office_name || null;

            // Hide office filter if not ODDG-PP
            const officeFilterWrapper = document.querySelector('select:nth-of-type(2)').closest('div');
            if (userOffice !== "ODDG-PP" && officeFilterWrapper) {
                officeFilterWrapper.style.display = "none";
            }

            // Fetch all documents once
            try {
                const response = await fetch("/api/documents");
                if (!response.ok) throw new Error("Failed to fetch documents");
                allDocuments = await response.json();
            } catch (err) {
                console.error(err);
                return;
            }

            // Initially populate table
            updateDocumentCounts();
            updateReportDocuments();

            // Attach filter events
            document.querySelectorAll('.datetimepicker, select').forEach(el => {
                el.addEventListener('input', applyFilters);
            });
        }

        // -----------------------------
        // Update Counts
        // -----------------------------
        function updateDocumentCounts(filteredDocs = null) {
            const authUser = window.authUser;
            if (!authUser) return;

            const userOffice = authUser.office?.office_name || null;
            const docs = filteredDocs || allDocuments;

            const filtered = docs.filter(doc => {
                if (userOffice === "ODDG-PP") return true;
                return doc.destination_office === userOffice;
            });

            let total = filtered.length;
            let forDiscussion = 0;
            let pending = 0;
            let processed = 0;
            let overdue = 0;
            let remanded = 0;

            filtered.forEach(doc => {
                switch ((doc.status || "").toLowerCase()) {
                    case "pending":
                        pending++;
                        break;
                    case "processed":
                        processed++;
                        break;
                    case "overdue":
                        overdue++;
                        break;
                    case "remanded":
                        remanded++;
                        break;
                    case "for discussion":
                        forDiscussion++;
                        break;
                }
            });

            document.querySelector("#totalDocuments .text-2xl").textContent = total.toLocaleString();
            document.querySelector("#forDiscussion .text-2xl").textContent = forDiscussion.toLocaleString();
            document.querySelector("#pending .text-xl").textContent = pending.toLocaleString();
            document.querySelector("#processed .text-xl").textContent = processed.toLocaleString();
            document.querySelector("#overdue .text-xl").textContent = overdue.toLocaleString();
            document.querySelector("#remanded .text-xl").textContent = remanded.toLocaleString();
        }

        // -----------------------------
        // Populate Table
        // -----------------------------
        function updateReportDocuments(filteredDocs = null) {
            const authUser = window.authUser;
            if (!authUser) return;

            const userOffice = authUser.office?.office_name || null;
            const docs = filteredDocs || allDocuments;
            const tableBody = document.querySelector("#reportsocumentsTable tbody");
            if (!tableBody) return;

            tableBody.innerHTML = "";

            const filtered = docs.filter(doc => {
                if (userOffice === "ODDG-PP") return true;
                return doc.destination_office === userOffice;
            });

            filtered.forEach(doc => {
                const dueDate = doc.due_date ?
                    new Date(doc.due_date).toLocaleDateString("en-US", {
                        month: "short",
                        day: "2-digit",
                        year: "numeric"
                    }) : "-";

                const dateUploaded = doc.date_forwarded ?
                    new Date(doc.date_forwarded).toLocaleString("en-US", {
                        month: "short",
                        day: "2-digit",
                        year: "numeric",
                        hour: "numeric",
                        minute: "2-digit",
                        hour12: true
                    }) : "-";

                const duration = doc.date_received && doc.date_forwarded ?
                    Math.ceil((new Date(doc.date_forwarded) - new Date(doc.date_received)) / (1000 * 60 *
                        60 * 24)) + " days" : "-";

                const row = document.createElement("tr");
                row.innerHTML = `
            <td class="px-4 py-2">${doc.document_control_number}</td>
            <td class="px-4 py-2">${doc.document_code}</td>
            <td class="px-4 py-2">${doc.label || "-"}</td>
            <td class="px-4 py-2">${doc.particular || "-"}</td>
            <td class="px-4 py-2">${doc.office_origin || "-"}</td>
            <td class="px-4 py-2">${doc.destination_office || "-"}</td>
            <td class="px-4 py-2">${dueDate}</td>
            <td class="px-4 py-2">${duration}</td>
            <td class="px-4 py-2">${dateUploaded}</td>
            <td class="px-4 py-2">${doc.confidentiality || "-"}</td>
            <td class="px-4 py-2">${doc.status || "-"}</td>

            <!-- Hidden columns for export -->
            <td class="hidden">${doc.user_name || ""}</td>
            <td class="hidden">${doc.recipient_name || ""}</td>
            <td class="hidden">${doc.document_form || ""}</td>
            <td class="hidden">${doc.document_type || ""}</td>
            <td class="hidden">${doc.date_of_document || ""}</td>
            <td class="hidden">${doc.signatory || ""}</td>
            <td class="hidden">${doc.confirmed_by_name || ""}</td>
            <td class="hidden">${JSON.stringify(doc.involved_office) || ""}</td>
            <td class="hidden">${doc.action_taken || ""}</td>
            <td class="hidden">${doc.remarks || ""}</td>
            <td class="hidden">${doc.created_at || ""}</td>
            <td class="hidden">${doc.updated_at || ""}</td>
        `;
                tableBody.appendChild(row);

                initDataTables();
            });
        }
        let prevFromDate = "";
        let prevToDate = "";
        // apply filters
        function applyFilters() {
            const fromDateEl = document.getElementById("filter_datefrom");
            const toDateEl = document.getElementById("filter_dateto");

            const statusFilter = document.getElementById("filter_status").value.toLowerCase();
            const officeFilterEl = document.getElementById("filter_office");
            const officeFilter = officeFilterEl ? officeFilterEl.value.toLowerCase() : null;
            const labelFilter = document.getElementById("filter_label").value.toLowerCase();


            // Convert dd-mm-yyyy → Date object
            function parseDMY(dateStr) {
                if (!dateStr) return null;
                const [day, month, year] = dateStr.split("-").map(Number);
                return new Date(year, month - 1, day);
            }

            const fromDate = parseDMY(fromDateEl.value);
            const toDate = parseDMY(toDateEl.value);

            // Create full-day end for "to date"
            let toDateEnd = null;
            if (toDate) {
                toDateEnd = new Date(toDate);
                toDateEnd.setHours(23, 59, 59, 999);
            }

            let filtered = [...allDocuments];

            // ---------------------
            // Filter by created_at
            // ---------------------
            filtered = filtered.filter(d => {
                if (!d.created_at) return false;

                // created_at timestamp → Date object
                const createdDate = new Date(d.created_at);

                // Compare
                if (fromDate && createdDate < fromDate) return false;
                if (toDateEnd && createdDate > toDateEnd) return false;

                return true;
            });

            // ---------------------
            // Status filter
            // ---------------------
            if (statusFilter && statusFilter !== "all") {
                filtered = filtered.filter(d =>
                    (d.status || "").toLowerCase() === statusFilter
                );
            }

            // ---------------------
            // Office filter
            // ---------------------
            if (officeFilter && officeFilter !== "all") {
                filtered = filtered.filter(d =>
                    (d.destination_office || "").toLowerCase() === officeFilter
                );
            }

            // ---------------------
            // Label filter
            // ---------------------
            if (labelFilter && labelFilter !== "all") {
                filtered = filtered.filter(d =>
                    (d.label || "").toLowerCase().includes(labelFilter)
                );
            }

            updateDocumentCounts(filtered);
            updateReportDocuments(filtered);

            initDataTables();
        }


        // -----------------------------
        // Export Excel
        // -----------------------------
        function exportTableToExcel() {
            const table = document.querySelector("#reportsocumentsTable");
            const wb = XLSX.utils.table_to_book(table, {
                sheet: "Documents"
            });
            XLSX.writeFile(wb, "documents.xlsx");
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
                html: "#reportsocumentsTable"
            });
            doc.save("documents.pdf");
        }

        // -----------------------------
        // Attach export buttons
        // -----------------------------
        document.querySelector('button.bg-red-500').addEventListener('click', exportTableToPDF);
        document.querySelector('button.bg-green-500').addEventListener('click', exportTableToExcel);

        // -----------------------------
        // Initialize dashboard
        // -----------------------------
        initDashboard();

        initDatePickers();
        fillOfficeDropdown();
        initDataTables();
    })();
</script>
