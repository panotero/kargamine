<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto py-5 ">

        <div class="w-full p-5 rounded-md drop-shadow-md mb-5 bg-white text-black">
            <div>
                <p class="text-sm">Total Documents</p>
                <h2 class="mt-2 text-2xl font-bold" id="totalDocuments">0</h2>
                <p class="mt-1 text-xs">All files in the system</p>
            </div>
        </div>
        <div class="grid md:grid-cols-5 grid-cols-2 gap-3">
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black">

                <div>
                    <p class="text-sm">For Signature</p>
                    <h2 class="mt-2 text-3xl font-bold" id="forSignature">0</h2>
                    <p class="mt-1 text-xs">Approved and for signature</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black">
                <div>
                    <div>
                        <p class="text-sm">For Discussion</p>
                        <h2 class="mt-2 text-3xl font-bold text-blue-600" id="forDiscussion">0</h2>
                        <p class="mt-1 text-xs">Needs attention</p>
                    </div>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black">

                <div>
                    <p class="text-sm">Pending</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="pending">0</h2>
                    <p class="mt-1 text-xs">Waiting for processing</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black">
                <div>
                    <p class="text-sm">Remanded</p>
                    <h2 class="mt-2 text-3xl font-bold" id="remanded">0</h2>
                    <p class="mt-1 text-xs">Returned for revision</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black">
                <div>
                    <p class="text-sm">For Approval</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-300" id="forApproval">0</h2>
                    <p class="mt-1 text-xs">documents for approval</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black">
                <div>
                    <p class="text-sm">Completed</p>
                    <h2 class="mt-2 text-3xl font-bold text-green-600" id="completed">0</h2>
                    <p class="mt-1 text-xs">documents for approval</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black">

                <div>
                    <p class="text-sm">Overdue</p>
                    <h2 class="mt-2 text-3xl font-bold text-red-500" id="overdue">0</h2>
                    <p class="mt-1 text-xs">Past due date</p>
                </div>
            </div>
        </div>


        <div class="mt-8 flex justify-center ">
            <div class="w-full ">
                <div class="rounded-2xl  -white/6 backdrop-blur-lg p-4 shadow-lg bg-white text-black">
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
        const ACTION_MAP = {
            routing: 'route',
            approve: 'approved',
            signed: 'signed',
            confirmed: 'confirm',
            disapprove: 'disapproved',
        };
        const isSameDay = (a, b) =>
            a.getFullYear() === b.getFullYear() &&
            a.getMonth() === b.getMonth() &&
            a.getDate() === b.getDate();

        const startOfDay = (d) =>
            new Date(d.getFullYear(), d.getMonth(), d.getDate());
        const week = () => ({
            routing: Array(7).fill(0),
            approved: Array(7).fill(0),
            signed: Array(7).fill(0),
            confirmed: Array(7).fill(0),
            disapprove: Array(7).fill(0),
        });

        const month = (daysInMonth) => ({
            routing: Array(daysInMonth).fill(0),
            approved: Array(daysInMonth).fill(0),
            signed: Array(daysInMonth).fill(0),
            confirmed: Array(daysInMonth).fill(0),
            disapprove: Array(daysInMonth).fill(0),
        });

        const year = () => ({
            routing: Array(12).fill(0),
            approved: Array(12).fill(0),
            signed: Array(12).fill(0),
            confirmed: Array(12).fill(0),
            disapprove: Array(12).fill(0),
        });


        function buildWeeklyData(logs) {
            const today = startOfDay(new Date());

            const days = [...Array(7)].map((_, i) => {
                const d = new Date(today);
                d.setDate(today.getDate() - (6 - i));
                return d;
            });

            const labels = days.map(d =>
                d.toLocaleDateString('en-US', {
                    weekday: 'short'
                })
            );

            const counts = week();

            logs.forEach(log => {
                const logDate = startOfDay(new Date(log.created_at));

                days.forEach((day, index) => {
                    if (isSameDay(logDate, day)) {
                        if (log.action === ACTION_MAP.routing) counts.routing[index]++;
                        if (log.action === ACTION_MAP.approve) counts.approved[index]++;
                        if (log.action === ACTION_MAP.signed) counts.signed[index]++;
                        if (log.action === ACTION_MAP.confirmed) counts.confirmed[index]++;
                        if (log.action === ACTION_MAP.disapprove) counts.disapprove[index]++;
                    }
                });
            });

            return {
                labels,
                ...counts
            };
        }

        function buildMonthlyData(logs) {
            const now = new Date();
            const yearNow = now.getFullYear();
            const monthNow = now.getMonth();

            const daysInMonth = new Date(yearNow, monthNow + 1, 0).getDate();
            const labels = Array.from({
                length: daysInMonth
            }, (_, i) => `Day ${i + 1}`);

            const counts = month(daysInMonth);

            logs.forEach(log => {
                const d = new Date(log.created_at);

                if (d.getFullYear() === yearNow && d.getMonth() === monthNow) {
                    const index = d.getDate() - 1;

                    if (log.action === ACTION_MAP.routing) counts.routing[index]++;
                    if (log.action === ACTION_MAP.approve) counts.approved[index]++;
                    if (log.action === ACTION_MAP.signed) counts.signed[index]++;
                    if (log.action === ACTION_MAP.confirmed) counts.confirmed[index]++;
                    if (log.action === ACTION_MAP.disapprove) counts.disapprove[index]++;
                }
            });

            return {
                labels,
                ...counts
            };
        }

        function buildYearlyData(logs) {
            const now = new Date();
            const yearNow = now.getFullYear();
            const currentMonth = now.getMonth();

            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            // fresh yearly counts (no reuse, no mutation risk)
            const counts = {
                routing: Array(12).fill(0),
                approved: Array(12).fill(0),
                signed: Array(12).fill(0),
                confirmed: Array(12).fill(0),
                disapprove: Array(12).fill(0),
            };

            // daily buckets per month
            const daily = {};

            logs.forEach(log => {
                const d = new Date(log.created_at);
                if (d.getFullYear() !== yearNow || d.getMonth() > currentMonth) return;

                const m = d.getMonth();
                const day = d.getDate();
                const key = `${m}-${day}`;

                if (!daily[key]) {
                    daily[key] = {
                        routing: 0,
                        approved: 0,
                        signed: 0,
                        confirmed: 0,
                        disapprove: 0,
                    };
                }

                if (log.action === ACTION_MAP.routing) daily[key].routing++;
                if (log.action === ACTION_MAP.approve) daily[key].approved++;
                if (log.action === ACTION_MAP.signed) daily[key].signed++;
                if (log.action === ACTION_MAP.confirmed) daily[key].confirmed++;
                if (log.action === ACTION_MAP.disapprove) daily[key].disapprove++;
            });

            // reduce daily → yearly (MAX per month)
            Object.keys(daily).forEach(key => {
                const [monthIndex] = key.split('-').map(Number);

                counts.routing[monthIndex] = Math.max(counts.routing[monthIndex], daily[key].routing);
                counts.approved[monthIndex] = Math.max(counts.approved[monthIndex], daily[key].approved);
                counts.signed[monthIndex] = Math.max(counts.signed[monthIndex], daily[key].signed);
                counts.confirmed[monthIndex] = Math.max(counts.confirmed[monthIndex], daily[key].confirmed);
                counts.disapprove[monthIndex] = Math.max(counts.disapprove[monthIndex], daily[key]
                    .disapprove);
            });

            return {
                labels,
                ...counts
            };
        }





        window.renderFileActivityGraph = async function(range = 'week') {


            try {

                const activities = await fetchWithRetry(
                    `/api/activities/byOffice/${authUser.office.office_code}`, {
                        method: "GET",
                        headers: {
                            Accept: "application/json",
                        },
                    });

                const sampleData = {
                    week: buildWeeklyData(activities),
                    month: buildMonthlyData(activities),
                    year: buildYearlyData(activities)
                };
                // console.log(sampleData);


                const ctx = document.getElementById('fileGraph').getContext('2d');

                // Create gradients for each line
                const routingGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
                routingGradient.addColorStop(0, 'rgba(255, 165, 0, 0.5)');
                routingGradient.addColorStop(1, 'rgba(255, 165, 0, 0)');

                const approveGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
                approveGradient.addColorStop(0, 'rgba(0, 128, 0, 0.5)');
                approveGradient.addColorStop(1, 'rgba(0, 128, 0, 0)');

                const signedGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
                signedGradient.addColorStop(0, 'rgba(0, 0, 255, 0.5)');
                signedGradient.addColorStop(1, 'rgba(0, 0, 255, 0)');

                const confirmedGradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
                confirmedGradient.addColorStop(0, 'rgba(128, 0, 128, 0.5)');
                confirmedGradient.addColorStop(1, 'rgba(128, 0, 128, 0)');

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
                                label: 'Approved',
                                data: sampleData[range].approved,
                                borderColor: 'green',
                                backgroundColor: approveGradient,
                                fill: true,
                                tension: 0.2
                            },
                            {
                                label: 'Signed',
                                data: sampleData[range].signed,
                                borderColor: 'green',
                                backgroundColor: signedGradient,
                                fill: true,
                                tension: 0.2
                            },
                            {
                                label: 'Confirmed',
                                data: sampleData[range].confirmed,
                                borderColor: 'blue',
                                backgroundColor: confirmedGradient,
                                fill: true,
                                tension: 0.2
                            },
                            {
                                label: 'Disapproved',
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
                // console.log(data);
            } catch (error) {
                console.log(error);
            }
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
            const userApprovalType = authUser?.user_config.approval_type
            // console.log(userApprovalType);


            // Fetch all documents once
            try {
                // getActivitiesCounts();
                getDocsCounts();

            } catch (err) {
                console.error(err);
                return;
            }
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

        async function getDocsCounts() {
            try {

                const documents = await fetchWithRetry(
                    `/api/documents/getdocs/${window.authUser.office.office_code}`, {
                        method: "GET",
                        headers: {
                            Accept: "application/json"
                        },
                    }
                );
                const userOffice = authUser.office.office_code;
                let total = documents.length;
                let forDiscussion = 0;
                let forSignature = 0;
                let forApproval = 0;
                let pending = 0;
                let overdue = 0;
                let remanded = 0;
                let completed = 0;
                documents.forEach(doc => {
                    // console.log(doc.status);

                    switch ((doc.status || "").toLowerCase()) {
                        case "pending":
                            pending++;
                            break;
                        case "overdue":
                            overdue++;
                            break;
                        case "for approval":
                            forApproval++;
                            break;
                        case "remanded":
                            remanded++;
                            break;
                        case "for discussion":
                            forDiscussion++;
                            break;
                        case "complete":
                            completed++;
                            break;
                        case "approved":
                            if (doc.destination_office === userOffice)
                                forSignature++;
                            break;
                    }
                });

                //counnt all overdue
                documents.forEach(doc => {
                    // console.log(doc.status);
                });



                document.getElementById("totalDocuments").textContent = total.toLocaleString();
                document.getElementById("forDiscussion").textContent = forDiscussion.toLocaleString();
                document.getElementById("forSignature").textContent = forSignature.toLocaleString();
                document.getElementById("pending").textContent = pending.toLocaleString();
                document.getElementById("overdue").textContent = overdue.toLocaleString();
                document.getElementById("completed").textContent = completed.toLocaleString();
                document.getElementById("remanded").textContent = remanded.toLocaleString();

                document.getElementById("forApproval").textContent = forApproval.toLocaleString();
            } catch (error) {
                console.error(error)
            }
        }
        // -----------------------------
        // Update Counts
        // -----------------------------
        async function getActivitiesCounts(filteredDocs = null) {
            try {

                const response = await fetchWithRetry(
                    `/api/activities/byOffice/${authUser.office.office_code}`, {
                        method: "GET",
                        headers: {
                            Accept: "application/json",
                        },
                    });
                if (!response) throw new Error("Failed to fetch documents");
                allDocuments = response;
                // console.log(response);
                const authUser = window.authUser;
                if (!authUser) return;

                const userOffice = authUser.office?.office_code || null;
                const docs = filteredDocs || allDocuments;

                const filtered = docs.filter(doc => {
                    if (userOffice === "ODDG-PP") return true;
                    return doc.destination_office === userOffice;
                });

                let total = filtered.length;
                let routed = 0;
                let approved = 0;
                let disapproved = 0;
                let fordiscussion = 0;
                let completed = 0;

                filtered.forEach(doc => {});
            } catch (error) {

                console.error(error);
            }
        }


        initDashboard();
        initDataTables();
    })();
</script>
