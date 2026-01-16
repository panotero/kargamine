<div class="w-full h-screen bg-white dark:bg-gray-800">
    <div class=" container mx-auto py-5 rounded-lg">
        <div>

            <div class="flex flex-wrap gap-3 mb-4 hidden">
                <select class="rounded-full border-gray-300 text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Filter by Office</option>
                </select>
                <select class="rounded-full border-gray-300 text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Filter by Status</option>
                </select>
                <select class="rounded-full border-gray-300 text-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Filter by File Type</option>
                </select>
                <input type="text" placeholder="Search..."
                    class="no-special-chars rounded-full border-gray-300 px-4 py-2 text-sm w-64 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
                <table id="approvaltable" class="w-full text-sm text-left border-collapse responsive-table  p-5">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-white uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Control #</th>
                            <th class="px-4 py-3">Label</th>
                            <th class="px-4 py-3">Subject</th>
                            <th class="px-4 py-3">Origin Office</th>
                            <th class="px-4 py-3">Destination Office</th>
                            <th class="px-4 py-3">Due Date</th>
                            <th class="px-4 py-3">Duration</th>
                            <th class="px-4 py-3">Date Uploaded</th>
                            <th class="px-4 py-3">Confidentiality</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-white ">
                    </tbody>
                </table>
            </div>
        </div>
        <div id="approvalDocumentModal"
            class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-2 sm:px-4 modal modal-open">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[95vh] overflow-y-auto">

                <div class="border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-4">
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-gray-100 break-all">
                        Document Control Number: <span id="modalapprovalDocControlNumber">DCN-0001</span>
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Status:
                        <span id="modalapproveDocStatus"
                            class="font-medium text-blue-600 dark:text-blue-400">Active</span>
                    </p>
                </div>

                <div
                    class="max-h-[70vh] overflow-y-auto flex flex-col lg:flex-row divide-y lg:divide-y-0 lg:divide-x divide-gray-200 dark:divide-gray-700">

                    <div class="w-full lg:w-1/2 p-4 sm:p-6">
                        <div id="galleryGlide" class="glide w-full max-w-md mx-auto relative">

                            <div id="galleryLoading"
                                class="absolute inset-0 flex items-center justify-center bg-white/70 hidden z-50">
                                <div
                                    class="animate-spin text-black dark:text-gray-200 h-10 w-10 border-4 border-gray-400 border-t-transparent rounded-full">
                                </div>
                            </div>

                            <div class="glide__track h-[60vh]" data-glide-el="track">
                                <ul class="glide__slides  h-full" id="approvalglideSlides">
                                </ul>

                                <button data-glide-dir="<"
                                    class="slide-previous pointer-events-auto absolute top-1/2 left-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3 hover:bg-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                <button data-glide-dir=">"
                                    class="slide-next pointer-events-auto absolute top-1/2 right-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3 hover:bg-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 p-4 sm:p-6 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">Document Information</h3>

                            <a id="modalDownloadLatestBtn" download href="#"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg w-full sm:w-auto">
                                Download File
                            </a>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Title:</span>
                                <span id="docTitle"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Document Code:</span>
                                <span id="docCode"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Form:</span>
                                <span id="docForm"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Type:</span>
                                <span id="docType"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Due Date:</span>
                                <span id="docDueDate"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>
                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Approval Type:</span>
                                <span id="approvalType"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Destination Office:</span>
                                <span id="docDestination"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Confidentiality:</span>
                                <span id="docConfidentiality"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Department:</span>
                                <span id="docDept"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Created By:</span>
                                <span id="docAuthor"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Created At:</span>
                                <span id="docDate"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>

                            <div class="flex justify-between gap-3">
                                <span class="text-gray-600 dark:text-gray-400">Remarks:</span>
                                <span id="docRemarks"
                                    class="text-gray-900 dark:text-gray-100 text-right break-all"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="border-t border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-4 flex flex-wrap justify-end gap-2 sm:gap-3">

                    <button id="modalApproveBtn"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto modal-open">
                        Approve
                    </button>

                    <button id="modalDisapproveBtn"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto modal-open">
                        Disapprove
                    </button>

                    <button id="modalRequestDiscussionBtn"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">
                        Request for Discussion
                    </button>

                    <button
                        class="modal-close border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200
                hover:bg-gray-100 dark:hover:bg-gray-800 px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">
                        Cancel
                    </button>
                </div>

            </div>
        </div>




    </div>
</div>

{{-- <script>
    let approvalList = [];
    getApprovals();
    async function getApprovals() {
        try {
            initDataTables();
            const response = await fetchWithRetry(
                `/api/approvals/`, {
                    method: "GET",
                    headers: {
                        Accept: "application/json"
                    },
                });


            // usersList = response;
            console.log(response);

            // updateDocumentCounts(filteredDocuments);
            // response.forEach((users) => {
            //     updaterow(users);
            // });
            // updateUserCounts(response);



        } catch (err) {
            console.error(err);
            return;
        }
    }
</script> --}}
<script>
    (function() {
        const tableBody = document.querySelector("#approvaltable tbody");
        const baseApiUrl = "/api/approvals";
        const baseUrl = window.location.origin;
        initDataTables();

        // =========================
        // Helper Functions
        // =========================
        async function fetchJson(url, options = {}) {
            try {
                const res = await fetch(url, options);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.headers.get("Content-Type")?.includes("application/json") ?
                    await res.json() :
                    null;
            } catch (err) {
                console.error(`Error fetching ${url}:`, err);
                return null;
            }
        }

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.innerText = value ?? "";
        }

        function createSelect(options = [], selectedValue = "") {
            return `<select class="border rounded px-2 py-1 text-xs labeldropdown">
            ${options
                .map(
                    (opt) =>
                        `<option ${opt === selectedValue ? "selected" : ""}>${opt}</option>`
                )
                .join("")}
        </select>`;
        }

        function clearContainer(container) {
            if (container) container.innerHTML = "";
        }

        // =========================
        // Table Rendering
        // =========================
        function renderTable(approvals) {
            clearContainer(tableBody);

            approvals.forEach((app) => {
                const doc = app.document;
                const tr = document.createElement("tr");
                tr.classList.add("border-t", "hover:bg-gray-50", "cursor-pointer");

                tr.innerHTML = `
                <td class="px-4 py-2">${doc.document_control_number}</td>
                <td class="px-4 py-2">${createSelect(["General", "Confidential"], doc.confidentiality)}</td>
                <td class="px-4 py-2">${doc.particular}</td>
                <td class="px-4 py-2">${doc.office_origin}</td>
                <td class="px-4 py-2">${doc.destination_office}</td>
                <td class="px-4 py-2">${doc.due_date ?? "—"}</td>
                <td class="px-4 py-2">—</td>
                <td class="px-4 py-2">${doc.date_forwarded ?? "—"}</td>
                <td class="px-4 py-2">${doc.confidentiality}</td>
                <td class="px-4 py-2">${doc.status}</td>
            `;

                tr.addEventListener("click", (e) => {
                    if (e.target.classList.contains("labeldropdown")) return;
                    console.log(doc);

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
                    logActivity(
                        "view",
                        doc.document_id,
                        doc.document_control_number
                    );

                });

                tableBody.appendChild(tr);
            });
        }
        async function loadApprovals() {
            const data = await fetchJson(`${baseApiUrl}`);
            if (!data?.approvals) return;
            renderTable(data.approvals);
        }

        async function loadModal(app) {
            const doc = app.document;

            setText("modalapprovalDocControlNumber", doc.document_control_number);
            setText("modalapproveDocStatus", doc.status);
            setText("docTitle", doc.particular ?? "");
            setText("document_id", doc.document_id ?? "");
            setText("docCode", doc.document_code ?? "");
            setText("docForm", doc.document_form ?? "");
            setText("docType", doc.document_type ?? "");
            setText("docDueDate", doc.due_date ?? "None");
            setText("approvalType", app.approval_type ?? "");
            setText("docDestination", doc.destination_office ?? "");
            setText("docConfidentiality", doc.confidentiality ?? "");
            setText("docDept", doc.office_origin ?? "");
            setText("docAuthor", doc.user_id ?? "");
            setText("docDate", doc.date_received ?? "");
            setText("docRemarks", doc.remarks ?? "");

            let pdfUrl = "";
            if (doc.files?.length > 0) {
                const lastFile = doc.files[doc.files.length - 1];
                pdfUrl = `${baseUrl}/${lastFile.file_path}`;
            }
            document.getElementById("modalDownloadLatestBtn").href = pdfUrl;

            const slides = pdfUrl ? await extractPdfImages(pdfUrl) : [];
            const slideContainer = document.getElementById("approvalglideSlides");
            clearContainer(slideContainer);
            slides.forEach((slideHTML) => slideContainer.insertAdjacentHTML("beforeend", slideHTML));
            if (typeof window.initGlide === "function") window.initGlide();


            // Additional fields
            setText("modalDocCode", doc.document_code);
            setText("modalDocType", doc.document_type);
            setText("modalDocOrigin", doc.office_origin);
            setText("modalDocDestination", doc.destination_office);
            setText("modalDocRemarks", doc.remarks ?? "None");
            setText("modalDocSignatory", doc.signatory ?? "—");
            setText("modalDocConfidentiality", doc.confidentiality);
            setText("modalDocDateReceived", doc.date_received);
            setText("modalDocDueDate", doc.due_date ?? "—");
        }
        loadApprovals();
    })();
</script>
