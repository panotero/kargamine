<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto py-5 ">

        <div class="grid md:grid-cols-5 grid-cols-2 gap-3">
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white dark:bg-gray-600">
                <div>
                    <p class="text-sm">Total Documents</p>
                    <h2 class="mt-2 text-2xl font-bold" id="totalDocuments">0</h2>
                    <p class="mt-1 text-xs">All files in the system</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white dark:bg-gray-600">

                <div>
                    <p class="text-sm">For Signature</p>
                    <h2 class="mt-2 text-3xl font-bold" id="pending">0</h2>
                    <p class="mt-1 text-xs">Approved and for signature</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white dark:bg-gray-600">
                <div>
                    <div>
                        <p class="text-sm">For Discussion</p>
                        <h2 class="mt-2 text-3xl font-bold " id="forDiscussion">0</h2>
                        <p class="mt-1 text-xs">Needs attention</p>
                    </div>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white dark:bg-gray-600">

                <div>
                    <p class="text-sm">Pending</p>
                    <h2 class="mt-2 text-3xl font-bold" id="pending">0</h2>
                    <p class="mt-1 text-xs">Waiting for processing</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white dark:bg-gray-600">

                <div>
                    <p class="text-sm">Overdue</p>
                    <h2 class="mt-2 text-3xl font-bold" id="overdue">0</h2>
                    <p class="mt-1 text-xs">Past due date</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white dark:bg-gray-600">
                <div>
                    <p class="text-sm">Remanded</p>
                    <h2 class="mt-2 text-3xl font-bold" id="remanded">0</h2>
                    <p class="mt-1 text-xs">Returned for revision</p>
                </div>
            </div>
        </div>


        <div class="mt-8 flex justify-center ">
            <div class="w-full ">
                <div class="rounded-2xl  -white/6 backdrop-blur-lg p-4 shadow-lg bg-white  dark:bg-gray-600">
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

                    <div class="w-full h-[30vh] ">
                        <canvas id="fileGraph" class="w-full h-full"></canvas>
                    </div>

                    <p class="mt-2 text-xs text-gray-400">Sample data shown — replace with your API when ready.</p>
                </div>
            </div>
        </div>

        {{-- Two-column area: Recent updates + Top 5 priority (table) --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6 w-full ">
            {{-- Recent updates (span 2 columns on large screens) --}}
            <div class="col-span-1 rounded-2xl dark:bg-gray-600 bg-white backdrop-blur-lg p-4 shadow-lg w-full mx-auto">
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
            <div class="lg:col-span-2 col-span-1 rounded-2xl backdrop-blur-lg p-4 shadow-lg bg-white dark:bg-gray-600 ">
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
        // Global function to render File Activity Graph
        window.renderFileActivityGraph = function(range = 'week') {
            // Sample data
            const sampleData = {
                week: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    routing: [12, 15, 9, 14, 20, 18, 10],
                    approve: [8, 10, 6, 12, 15, 12, 7],
                    disapprove: [2, 3, 1, 2, 3, 2, 1]
                },
                month: {
                    labels: Array.from({
                        length: 30
                    }, (_, i) => `Day ${i+1}`),
                    routing: Array.from({
                        length: 30
                    }, () => Math.floor(Math.random() * 30)),
                    approve: Array.from({
                        length: 30
                    }, () => Math.floor(Math.random() * 20)),
                    disapprove: Array.from({
                        length: 30
                    }, () => Math.floor(Math.random() * 10))
                },
                year: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                    routing: Array.from({
                        length: 12
                    }, () => Math.floor(Math.random() * 300)),
                    approve: Array.from({
                        length: 12
                    }, () => Math.floor(Math.random() * 200)),
                    disapprove: Array.from({
                        length: 12
                    }, () => Math.floor(Math.random() * 100))
                }
            };

            const ctx = document.getElementById('fileGraph').getContext('2d');

            // Create gradients for each line
            const routingGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
            routingGradient.addColorStop(0, 'rgba(255, 165, 0, 0.5)');
            routingGradient.addColorStop(1, 'rgba(255, 165, 0, 0)');

            const approveGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
            approveGradient.addColorStop(0, 'rgba(0, 128, 0, 0.5)');
            approveGradient.addColorStop(1, 'rgba(0, 128, 0, 0)');

            const disapproveGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
            disapproveGradient.addColorStop(0, 'rgba(255, 0, 0, 0.5)');
            disapproveGradient.addColorStop(1, 'rgba(255, 0, 0, 0)');

            // Destroy previous chart if exists
            if (window.fileActivityChart) {
                window.fileActivityChart.destroy();
            }

            window.fileActivityChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: sampleData[range].labels,
                    datasets: [{
                            label: 'Routing',
                            data: sampleData[range].routing,
                            borderColor: 'orange',
                            backgroundColor: routingGradient,
                            fill: true,
                            tension: 0.2
                        },
                        {
                            label: 'Approve',
                            data: sampleData[range].approve,
                            borderColor: 'green',
                            backgroundColor: approveGradient,
                            fill: true,
                            tension: 0.2
                        },
                        {
                            label: 'Disapprove',
                            data: sampleData[range].disapprove,
                            borderColor: 'red',
                            backgroundColor: disapproveGradient,
                            fill: true,
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Time'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Count'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        };

        window.renderFileActivityGraph('week'); // default week

        // Change graph on range select
        document.getElementById('graph-range').addEventListener('change', function() {
            window.renderFileActivityGraph(this.value);
        });


    })();
</script>

<script>
    (function() {


        getActivityData();

        let allDocuments = []; // store all fetched documents

        async function initDashboard() {
            const authUser = window.authUser;
            if (!authUser) return;

            const userOffice = authUser.office?.office_code || null;


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

        async function getActivityData() {
            try {

                const data = await fetchWithRetry(`/api/activities/byOffice/${authUser.office.code}`, {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                    },
                });
                // console.log(data);
            } catch (error) {
                console.log(error);
            }
        }

        // -----------------------------
        // Update Counts
        // -----------------------------
        function updateDocumentCounts(filteredDocs = null) {
            const authUser = window.authUser;
            if (!authUser) return;

            const userOffice = authUser.office?.office_code || null;
            const docs = filteredDocs || allDocuments;

            const filtered = docs.filter(doc => {
                if (userOffice === "ODDG-PP") return true;
                return doc.destination_office === userOffice;
            });

            let total = filtered.length;
            let forDiscussion = 0;
            let pending = 0;
            let overdue = 0;
            let remanded = 0;

            filtered.forEach(doc => {
                switch ((doc.status || "").toLowerCase()) {
                    case "pending":
                        pending++;
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
            document.getElementById("overdue").textContent = overdue.toLocaleString();
            document.getElementById("remanded").textContent = remanded.toLocaleString();
        }

        // -----------------------------
        // Populate Table
        // -----------------------------
        function updateReportDocuments(filteredDocs = null) {
            const authUser = window.authUser;
            if (!authUser) return;

            const userOffice = authUser.office?.office_code || null;
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

        fillOfficeDropdown();
        initDataTables();
    })();
</script>
