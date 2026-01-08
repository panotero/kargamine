<div class="w-full h-screen p-5 bg-gray-50 text-black dark:bg-gray-800 dark:text-white ">
    <div class="container mx-auto space-y-6">
        <div class="w-full border rounded-lg bg-white shadow flex flex-col lg:flex-row gap-4 p-4 text-black">
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

                <div class="flex lg:gap-4 gap-1">
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
                    <div>

                        <label class="text-gray-700 font-medium ">Status</label>
                        <select class="border rounded-lg p-2 w-full " id="filter_status">
                            <option>All</option>
                            <option>Pending</option>
                            <option>For Discussion</option>
                            <option>Routed</option>
                            <option>Confirmed</option>
                            <option>Completed</option>
                            <option>For Approval</option>
                            <option>Approved</option>
                            <option>Signed</option>
                            <option>Disapproved</option>
                            <option>Remanded</option>
                        </select>
                    </div>
                    <div id="office_filter" class="hidden">
                        <label class="text-gray-700 font-medium office">Office</label>
                        <select class="border rounded-lg p-2 w-full  officeSelect" id="filter_office">
                        </select>
                    </div>
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
        <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl shadow p-3">
            <table id="reportsdocumentsTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                    <tr>
                        <th>Control #</th>
                        <th>Document Code</th>
                        <th>Label</th>
                        <th>Subject</th>
                        <th>Origin Office</th>
                        <th>Destination Office</th>
                        <th>Due Date</th>
                        <th>Duration</th>
                        <th>Date Uploaded</th>
                        <th>Confidentiality</th>
                        <th>Status</th>

                        <th>User</th>
                        <th>Recipient</th>
                        <th>Document Form</th>
                        <th>Document Type</th>
                        <th>Date of Document</th>
                        <th>Signatory</th>
                        <th>Confirmed By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    (function() {



        let allDocuments = []; // store all fetched documents
        const fromDateEl = document.getElementById("filter_datefrom");
        const toDateEl = document.getElementById("filter_dateto");
        const statusFilter = document.getElementById("filter_status");
        const officeFilterEl = document.getElementById("filter_office");
        const labelFilter = document.getElementById("filter_label");
        async function initDashboard() {

            // Set default dates to today
            const today = new Date().toISOString().split("T")[0];

            if (fromDateEl) fromDateEl.value = today;
            if (toDateEl) toDateEl.value = today;
            const authUser = window.authUser;
            if (!authUser) return;
            if (authUser.office.office_code === "ODDG-PP") {
                document.getElementById("office_filter").classList.remove("hidden");
            } else {
                document.getElementById("office_filter").classList.add("hidden");

            }

            const userOffice = authUser.office?.office_code || null;

            // Hide office filter if not ODDG-PP
            const officeFilterWrapper = document.querySelector('select:nth-of-type(2)').closest('div');
            if (userOffice !== "ODDG-PP" && officeFilterWrapper) {
                officeFilterWrapper.style.display = "none";
            }

            try {
                initDataTables();
                const documents = await fetchWithRetry(
                    `/api/documents/getdocs/${window.authUser.office.office_code}`, {
                        method: "GET",
                        headers: {
                            Accept: "application/json"
                        },
                    });
                const filteredDocuments =
                    userOffice === "ODDG-PP" ?
                    documents :
                    documents.filter((row) =>
                        Array.isArray(row.involved_office) &&
                        row.involved_office.includes(userOffice)
                    );
                allDocuments = filteredDocuments;
                updateDocumentCounts(filteredDocuments);
                filteredDocuments.forEach((doc) => {
                    updaterow(doc);
                });



            } catch (err) {
                console.error(err);
                return;
            }

        }

        const filterElements = [fromDateEl, toDateEl, statusFilter, officeFilterEl, labelFilter];

        filterElements.forEach(el => {
            if (el) {
                el.addEventListener("change", (event) => {
                    const table = document.getElementById("reportsdocumentsTable");
                    let filteredDocuments = allDocuments; // start with all documents

                    switch (event.target) {
                        case fromDateEl:
                        case toDateEl:
                            const fromVal = fromDateEl.value;
                            const toVal = toDateEl.value;
                            const fromDate = fromVal ? new Date(fromVal + "T00:00:00") : null;
                            const toDate = toVal ? new Date(toVal + "T23:59:59") : null;

                            // Validate date range
                            if (fromDate && toDate && fromDate > toDate) {
                                alert("Invalid From and To date");
                                return;
                            }

                            // Filter by date range only
                            filteredDocuments = allDocuments.filter(doc => {
                                if (!doc.date_forwarded) return false;
                                const docDate = new Date(doc.date_forwarded.replace(" ",
                                    "T"));
                                if (fromDate && docDate < fromDate) return false;
                                if (toDate && docDate > toDate) return false;
                                return true;
                            });
                            break;

                        case statusFilter:
                            const statusVal = statusFilter.value.toLowerCase();
                            if (statusVal && statusVal !== "all") {
                                filteredDocuments = allDocuments.filter(doc =>
                                    (doc.status || "").toLowerCase() === statusVal
                                );
                            }
                            break;

                        case officeFilterEl:
                            const officeVal = officeFilterEl.value.toLowerCase();
                            if (officeVal && officeVal !== "all") {
                                filteredDocuments = allDocuments.filter(doc => {
                                    const involved = Array.isArray(doc.involved_office) ?
                                        doc.involved_office : [];
                                    return doc.destination_office.toLowerCase() ===
                                        officeVal ||
                                        involved.map(o => o.toLowerCase()).includes(
                                            officeVal);
                                });
                            }
                            break;

                        case labelFilter:
                            const labelVal = labelFilter.value.toLowerCase();
                            if (labelVal && labelVal !== "all") {
                                filteredDocuments = allDocuments.filter(doc =>
                                    (doc.label || "").toLowerCase() === labelVal
                                );
                            }
                            break;
                    }

                    console.log("Filtered Documents:", filteredDocuments);

                    /* ---- UPDATE COUNTS + TABLE ---- */
                    updateDocumentCounts(filteredDocuments);

                    if ($.fn.DataTable.isDataTable(table)) {
                        $(table).DataTable().clear().draw();
                    }

                    filteredDocuments.forEach(doc => {
                        updaterow(doc);
                    });
                });
            }
        });


        function updaterow(doc) {

            const table = document.getElementById("reportsdocumentsTable");
            if (!table) return;
            const tableBody = table.querySelector("tbody");
            let dt = null;
            if ($.fn.DataTable.isDataTable(table)) {
                dt = $(table).DataTable();
            }
            const dueDate = doc.due_date ?
                new Date(doc.due_date).toLocaleDateString("en-US", {
                    month: "short",
                    day: "2-digit",
                    year: "numeric",
                }) :
                "-";

            const dateUploaded = doc.date_forwarded ?
                new Date(doc.date_forwarded).toLocaleString("en-US", {
                    month: "short",
                    day: "2-digit",
                    year: "numeric",
                    hour: "numeric",
                    minute: "2-digit",
                    hour12: true,
                }) :
                "-";

            const duration =
                doc.date_received && doc.date_forwarded ?
                Math.ceil(
                    (new Date(doc.date_forwarded) - new Date(doc.date_received)) /
                    (1000 * 60 * 60 * 24)
                ) + " days" :
                "-";

            let statuscolor = "bg-gray-100";
            switch (doc.status?.toLowerCase()) {
                case "pending":
                    statuscolor = "bg-yellow-200";
                    break;
                case "for approval":
                    statuscolor = "bg-yellow-100";
                    break;
                case "completed":
                    statuscolor = "bg-green-200";
                    break;
                case "remanded":
                    statuscolor = "bg-red-200";
                    break;
                case "overdue":
                    statuscolor = "bg-red-300";
                    break;
                case "approved":
                    statuscolor = "bg-blue-200";
                    break;
            }

            const rowHtml = `
        <td class="px-4 py-2">${doc.document_control_number}</td>
        <td class="px-4 py-2">${doc.document_code}</td>
        <td class="px-4 py-2">${doc.label || "-"}</td>
        <td class="px-4 py-2">${doc.particular || "-"}</td>
        <td class="px-4 py-2">${doc.office_origin || "-"}</td>
        <td class="px-4 py-2">${doc.destination_office || "-"}</td>
        <td class="px-4 py-2">${dueDate}</td>
        <td class="px-4 py-2">${calculateDuration(doc.date_forwarded)}</td>
        <td class="px-4 py-2">${dateUploaded}</td>
        <td class="px-4 py-2">${doc.confidentiality || "-"}</td>
        <td class="px-4 py-2">
            <div class="px-3 py-1 rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
                ${doc.status || "-"}
            </div>
        </td>
        <td class="px-3 py-3">${doc.user_name || ""}</td>
        <td class="px-3 py-3">${doc.recipient_name || ""}</td>
        <td class="px-3 py-3">${doc.document_form || ""}</td>
        <td class="px-3 py-3">${doc.document_type || ""}</td>
        <td class="px-3 py-3">${doc.date_of_document || ""}</td>
        <td class="px-3 py-3">${doc.signatory || ""}</td>
        <td class="px-3 py-3">${doc.confirmed_by_name || ""}</td>
        <td class="px-3 py-3">${doc.remarks || ""}</td>
    `;

            if (dt) {
                dt.row.add([
                    doc.document_control_number,
                    doc.document_code,
                    doc.label || "-",
                    doc.particular || "-",
                    doc.office_origin || "-",
                    doc.destination_office || "-",
                    dueDate,
                    calculateDuration(doc.date_forwarded),
                    dateUploaded,
                    doc.confidentiality || "-",
                    `<div class="px-3 py-1 rounded-full text-gray-800 font-semibold text-center ${statuscolor}">${doc.status || "-"}</div>`,
                    doc.user_name || "",
                    doc.recipient_name || "",
                    doc.document_form || "",
                    doc.document_type || "",
                    doc.date_of_document || "",
                    doc.signatory || "",
                    doc.confirmed_by_name || "",
                    doc.remarks || "",
                ]).draw(false);
            } else {
                const tr = document.createElement("tr");
                tr.innerHTML = rowHtml;
                tableBody.appendChild(tr);
            }
        }

        // -----------------------------
        // Update Counts
        // -----------------------------
        function updateDocumentCounts(filteredDocs = null) {

            let total = filteredDocs.length;
            let forDiscussion = 0;
            let pending = 0;
            let processed = 0;
            let overdue = 0;
            let remanded = 0;

            filteredDocs.forEach(doc => {
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

            overdue = checkOverDue(filteredDocs);
            document.querySelector("#totalDocuments .text-2xl").textContent = total.toLocaleString();
            document.querySelector("#forDiscussion .text-2xl").textContent = forDiscussion.toLocaleString();
            document.querySelector("#pending .text-xl").textContent = pending.toLocaleString();
            document.querySelector("#processed .text-xl").textContent = processed.toLocaleString();
            document.querySelector("#overdue .text-xl").textContent = overdue.toLocaleString();
            document.querySelector("#remanded .text-xl").textContent = remanded.toLocaleString();
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
            if (!Array.isArray(allDocuments) || allDocuments.length === 0) {
                alert("No documents to export");
                return;
            }
            const cleanedData = allDocuments.map(
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
            XLSX.writeFile(workbook, "all_documents.xlsx");
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
                html: "#reportsdocumentsTable"
            });
            const today = new Date();

            // Format as DDMMYYYY
            const formattedDate =
                String(today.getDate()).padStart(2, '0') + // Day
                String(today.getMonth() + 1).padStart(2, '0') + // Month (0-indexed)
                today.getFullYear(); // Year

            doc.save("users_reports" + formattedDate + ".pdf");
        }

        // -----------------------------
        // Attach export buttons
        // -----------------------------
        document.querySelector('button.bg-red-500').addEventListener('click', exportTableToPDF);
        document.querySelector(
            'button.bg-green-500').addEventListener('click', exportTableToExcel);

        initDashboard();

        fillOfficeDropdown();
    })();
</script>
