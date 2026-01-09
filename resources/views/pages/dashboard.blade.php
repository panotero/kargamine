<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto py-5 ">

        <div class="w-full p-5 rounded-md drop-shadow-md mb-5 bg-white text-black statusButton cursor-pointer"
            data-status="all">
            <div>
                <p class="text-sm">Total Documents</p>
                <h2 class="mt-2 text-2xl font-bold" id="totalDocuments">0</h2>
                <p class="mt-1 text-xs">All files in the system</p>
            </div>
        </div>
        <div class="grid md:grid-cols-5 grid-cols-2 gap-3">
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="approved">

                <div>
                    <p class="text-sm">For Signature</p>
                    <h2 class="mt-2 text-3xl font-bold" id="forSignature">0</h2>
                    <p class="mt-1 text-xs">Approved and for signature</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="for discussion">
                <div>
                    <div>
                        <p class="text-sm">For Discussion</p>
                        <h2 class="mt-2 text-3xl font-bold text-blue-600" id="forDiscussion">0</h2>
                        <p class="mt-1 text-xs">Needs attention</p>
                    </div>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="pending">

                <div>
                    <p class="text-sm">Pending</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="pending">0</h2>
                    <p class="mt-1 text-xs">Waiting for processing</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="remanded">
                <div>
                    <p class="text-sm">Remanded</p>
                    <h2 class="mt-2 text-3xl font-bold" id="remanded">0</h2>
                    <p class="mt-1 text-xs">Returned for revision</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="for approval">
                <div>
                    <p class="text-sm">For Approval</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-300" id="forApproval">0</h2>
                    <p class="mt-1 text-xs">Documents for approval</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="completed">
                <div>
                    <p class="text-sm">Completed</p>
                    <h2 class="mt-2 text-3xl font-bold text-green-600" id="completed">0</h2>
                    <p class="mt-1 text-xs">Documents for approval</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="overdue">

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

            <div
                class="lg:col-span-2 col-span-1 rounded-2xl backdrop-blur-lg p-4 shadow-lg bg-white dark:bg-gray-600 ">
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

    <div id="countModal" class="hidden fixed inset-0 flex items-center justify-center z-40 bg-black/50 modal">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-80 w-[60vw]  relative">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Document Created</h2>
            <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl shadow">
                <table id="countmodaltable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Control Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Document Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Subject</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Origin Office</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Destination Office</span>
                            </th>
                            <th class="px-4 py-auto">
                                <span class="inline-flex items-center">Status</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
                        <!-- Rows will be inserted dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="w-full flex justify-end">
                <button
                    class="modal-close px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">
                    Close
                </button>

            </div>
        </div>
    </div>
</div>

<script>
    (function() {

        initGraph()
        fillOfficeDropdown();
        initDashboard();
        let allDocuments = []; // store all fetched documents

        const statuses = ["signed", "approved", "completed"];

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
                getActivityData();


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
                allDocuments = documents;
                // console.log(documents);
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
                            forSignature++;
                            break;
                    }
                });

                overdue = checkOverDue(documents);



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



        const statusbtn = document.querySelectorAll('.statusButton');
        const modalcounttable = document.getElementById('countmodaltable');
        statusbtn.forEach(e => {
            e.addEventListener("click", function(func) {
                initDataTables();
                // console.log(e.dataset.status);
                initModal({
                    modalId: "countModal"
                });
                let filteredDocuments = [];
                const status = e.dataset.status;
                switch (status) {

                    case "pending":
                        filteredDocuments = allDocuments.filter(doc =>
                            doc.status && doc.status.toLowerCase() === status
                        );
                        break;
                    case "for approval":
                        break;
                    case "completed":
                        break;
                    case "remanded":
                        break;
                    case "overdue":
                        filteredDocuments = allDocuments.filter(doc =>
                            doc.due_date < today && statuses.includes(doc.status.toLowerCase())
                        );
                        break;
                    case "approved":
                        filteredDocuments = allDocuments.filter(doc =>
                            doc.status && doc.status.toLowerCase() === status
                        );
                        break;
                    case "completed":
                        break;
                    case "all":
                        filteredDocuments = allDocuments;
                        break;

                }


                if ($.fn.DataTable.isDataTable(modalcounttable)) {
                    const dt = $(modalcounttable).DataTable();
                    dt.clear().draw();
                }
                // console.log(filteredDocuments);
                //update alldocuments to mutated array based on the status
                filteredDocuments.forEach(doc => {
                    updaterow(doc);

                });
                //populate countmodaltable based on the status
            })

        });



        function updaterow(doc) {

            if (!modalcounttable) return;
            const tableBody = modalcounttable.querySelector("tbody");
            let dt = null;
            if ($.fn.DataTable.isDataTable(modalcounttable)) {
                dt = $(modalcounttable).DataTable();
            }



            let statuscolor = "bg-gray-100";
            switch (doc.status.toLowerCase()) {

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
            // Build one table row matching the column headers
            const rowHtml = `
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.document_control_number ?? '-'} <!-- Name -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.document_code ?? '-'} <!-- Email -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.particular ?? '-'} <!-- Designation -->
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${doc.office_origin} <!-- Office -->
                    </td>
                    <td class="px-4 py-2 text-center text-sm font-medium">
                        ${doc.destination_office} <!-- Pending -->
                    </td>
                    <td class="px-4 py-2">
                        <div class="px-3 py-1 bg-white rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
                            ${doc.status  || "-"}
                        </div>
                    </td>
                `;


            if (dt) {
                const newRow = dt.row.add([
                    doc.document_control_number ?? "-", // Name
                    doc.document_code ?? "-", // Email
                    doc.particular ?? "-", // Designation
                    doc.office_origin ?? "-", // Office
                    doc.destination_office,
                    `<div class="px-3 py-1 bg-white rounded-full text-gray-800 font-semibold text-center ${statuscolor}">
                            ${doc.status  || "-"}
                        </div>`,
                ]).draw(false);


                const rowNode = newRow.node();
                let overdueclass = "";
                rowNode.classList.add(
                    "transition-colors", "duration-300",
                    "hover:dark:bg-white", "hover:dark:text-black");
                rowNode.classList.add("cursor-pointer");
                rowNode.addEventListener("click", function() {


                    checkActionButtons(
                        doc.status,
                        doc.recipient_id,
                        doc.destination_office,
                        doc.receipt_confirmation,
                        doc.revision_status
                    );
                    clearModalFields();
                    showSkeletonLoaders();
                    initModal({
                        modalId: "DocumentModal"
                    });
                    populateDocumentModal(doc.document_id);
                    logActivity("view", doc.document_id, doc.document_control_number);
                });
            } else {
                const tr = document.createElement("tr");
                tr.innerHTML = rowHtml;
                modalcounttable.appendChild(tr);
            }
        }

        initDataTables();
    })();
</script>
