<div class="min-h-screen">
    <div class="w-full  container mx-auto py-5 dark:text-gray-200 text-gray-900">


        {{-- Top row: 6 stat cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Total Documents --}}
            <div class="relative rounded-2xl backdrop-blur-lg p-4 shadow-lg dark:bg-gray-600 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm">Total Documents</p>
                        <h2 class="mt-2 text-3xl font-bold" id="totalDocuments">0</h2>
                        <p class="mt-1 text-xs">All files in the system</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <button class="px-3 py-1 rounded-full text-xs font-medium hover:bg-white/12 transition">
                            View
                        </button>
                    </div>
                </div>
            </div>

            {{-- For Discussion --}}
            <div class="relative rounded-2xl backdrop-blur-lg p-4 shadow-lg dark:bg-gray-600 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm">For Discussion</p>
                        <h2 class="mt-2 text-3xl font-bold " id="forDiscussion">0</h2>
                        <p class="mt-1 text-xs">Needs attention</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <button class="px-3 py-1 rounded-full text-xs font-medium hover:bg-white/12 transition">
                            Manage
                        </button>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="relative rounded-2xl backdrop-blur-lg p-4 shadow-lg dark:bg-gray-600 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm">Pending</p>
                        <h2 class="mt-2 text-3xl font-bold" id="pending">0</h2>
                        <p class="mt-1 text-xs">Waiting for processing</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <button class="px-3 py-1 rounded-full text-xs font-medium hover:bg-white/12 transition">
                            Action
                        </button>
                    </div>
                </div>
            </div>

            {{-- Processed --}}
            <div class="relative rounded-2xl backdrop-blur-lg p-4 shadow-lg dark:bg-gray-600 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm">Processed</p>
                        <h2 class="mt-2 text-3xl font-bold" id="processed">0</h2>
                        <p class="mt-1 text-xs">Completed files</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <button class="px-3 py-1 rounded-full text-xs font-medium hover:bg-white/12 transition">
                            View
                        </button>
                    </div>
                </div>
            </div>

            {{-- Overdue --}}
            <div class="relative rounded-2xl backdrop-blur-lg p-4 shadow-lg dark:bg-gray-600 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm">Overdue</p>
                        <h2 class="mt-2 text-3xl font-bold" id="overdue">0</h2>
                        <p class="mt-1 text-xs">Past due date</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <button class="px-3 py-1 rounded-full text-xs font-medium hover:bg-white/12 transition">
                            Action
                        </button>
                    </div>
                </div>
            </div>

            {{-- Remanded --}}
            <div class="relative rounded-2xl backdrop-blur-lg p-4 shadow-lg dark:bg-gray-600 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm">Remanded</p>
                        <h2 class="mt-2 text-3xl font-bold" id="remanded">0</h2>
                        <p class="mt-1 text-xs">Returned for revision</p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <button class="px-3 py-1 rounded-full text-xs font-medium hover:bg-white/12 transition">
                            Manage
                        </button>
                    </div>
                </div>
            </div>

        </div>


        <div class="mt-8 flex justify-center ">
            <div class="w-full ">
                <div class="rounded-2xl  -white/6 backdrop-blur-lg p-4 shadow-lg  dark:bg-gray-600">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium ">File Activity</h3>
                        <div class="flex items-center gap-2  >
                            <label for="graph-range"
                            class="text-xs ">Range</label>
                            <select id="graph-range"
                                class="text-xs rounded-full px-3 py-1 focus:outline-none bg-white text-black">
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year">Year</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <canvas id="fileGraph" class="w-full h-64"></canvas>
                    </div>
                    <p class="mt-2 text-xs text-gray-400">Sample data shown — replace with your API when ready.</p>
                </div>
            </div>
        </div>

        {{-- Two-column area: Recent updates + Top 5 priority (table) --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent updates (span 2 columns on large screens) --}}
            <div class="lg:col-span-2 rounded-2xl dark:bg-gray-600 bg-white backdrop-blur-lg p-4 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-medium ">Recent Updates</h3>
                    <span class="text-xs ">Latest 10</span>
                </div>

                <div class="max-h-64 overflow-y-auto pr-2 scroll-smooth ">
                    <ul class="divide-y divide-white/6 text-sm">
                        <li class="py-3 flex justify-between items-start">
                            <div>
                                <div class="text-sm  font-medium">File ABC123</div>
                                <div class="text-xs  mt-1">Marked as Priority by <span class="">Anna</span></div>
                            </div>
                            <div class="text-xs \">2h ago</div>
                        </li>
                        <li class="py-3
                                flex justify-between items-start">
                                <div>
                                    <div class="text-sm  font-medium">File XYZ789</div>
                                    <div class="text-xs  mt-1">Deadline missed — Overdue</div>
                                </div>
                                <div class="text-xs ">1d ago</div>
                        </li>
                        <li class="py-3 flex justify-between items-start">
                            <div>
                                <div class="text-sm e font-medium">File LMN456</div>
                                <div class="text-xs  mt-1">Status changed to Closed</div>
                            </div>
                            <div class="text-xs ">3d ago</div>
                        </li>
                        <li class="py-3 flex justify-between items-start">
                            <div>
                                <div class="text-sm  font-medium">File LMN456</div>
                                <div class="text-xs  mt-1">Status changed to Closed</div>
                            </div>
                            <div class="text-xs ">3d ago</div>
                        </li>
                        <li class="py-3 flex justify-between items-start">
                            <div>
                                <div class="text-sm  font-medium">File LMN456</div>
                                <div class="text-xs  mt-1">Status changed to Closed</div>
                            </div>
                            <div class="text-xs ">3d ago</div>
                        </li>
                        <li class="py-3 flex justify-between items-start">
                            <div>
                                <div class="text-sm  font-medium">File LMN456</div>
                                <div class="text-xs  mt-1">Status changed to Closed</div>
                            </div>
                            <div class="text-xs ">3d ago</div>
                        </li>
                        <li class="py-3 flex justify-between items-start">
                            <div>
                                <div class="text-sm  font-medium">File LMN456</div>
                                <div class="text-xs  mt-1">Status changed to Closed</div>
                            </div>
                            <div class="text-xs ">3d ago</div>
                        </li>
                        <li class="py-3 flex justify-between items-start">
                            <div>
                                <div class="text-sm  font-medium">File LMN456</div>
                                <div class="text-xs mt-1">Status changed to Closed</div>
                            </div>
                            <div class="text-xs ">3d ago</div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Top 5 priority (compact table) --}}
            <div class="rounded-2xl backdrop-blur-lg p-4 shadow-lg bg-white dark:bg-gray-600 ">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-medium ">Top 5 Priority</h3>
                    <span class="text-xs ">By priority</span>
                </div>
                <div class="w-full max-h-[30vh] overflow-y-auto" id="prioritylist">
                    <div class="p-1 rounded-md border border-gray-300">
                        <h1 class="text-lg font-semibold">Document number</h1>
                        <div class="flex justify-between">
                            <h1 class="text-sm">date forwarded by: user123</h1>
                            <h1 class="text-sm">date forwarded: 123</h1>
                        </div>

                    </div>

                </div>

                <div class="mt-3 text-xs ">Minimal columns for compact view.</div>
            </div>
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

            document.getElementById("totalDocuments").textContent = total.toLocaleString();
            document.getElementById("forDiscussion").textContent = forDiscussion.toLocaleString();
            document.getElementById("pending").textContent = pending.toLocaleString();
            document.getElementById("processed").textContent = processed.toLocaleString();
            document.getElementById("overdue").textContent = overdue.toLocaleString();
            document.getElementById("remanded").textContent = remanded.toLocaleString();
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
        // Initialize dashboard
        // -----------------------------
        initDashboard();

        initDatePickers();
        fillOfficeDropdown();
        initDataTables();
    })();
</script>
