<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto py-5 ">

        <div class="w-full py-5 flex justify-between">

            <div class="w-96 text-black ">
                <label class="text-sm text-gray-600 dark:text-white">Year</label>
                <select id="year" class="year w-full border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                </select>
                <p class="mt-1 text-sm text-red-600 hidden" data-error-for="transaction"></p>
            </div>
            <div class="my-auto">

                <button id="btnNewFinanceDoc"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition modal-open">
                    + New Document
                </button>
            </div>
        </div>
        <div class="relative w-full mb-5 statusButton cursor-pointer" data-status="all">
            <p class="flex items-center gap-2">
                <b id="currentYear">YEAR</b> budget: <b>₱</b>
                <b id="yearBudget">1,000,000</b>

                <!-- Edit Icon -->
                <button type="button" id="editYearBudget"
                    class="ml-1 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Edit budget">
                    ✎
                </button>
            </p>
            <div class="relative p-5 rounded-xl border border-gray-300 bg-white drop-shadow-sm overflow-hidden">

                <!-- Battery Charge Fill -->
                <div id="budgetFill"
                    class="absolute inset-y-0 left-0 bg-gradient-to-r from-green-500 to-green-700 transition-all duration-700"
                    style="width: 0%;">
                </div>

                <!-- DARK TEXT (base layer) -->
                <div class="w-full flex justify-between">
                    <div class="relative z-10 pointer-events-none text-gray-900">
                        <p class="text-sm">Available Budget</p>
                        <h2 class="mt-2 text-2xl font-bold" id="totalAvailableBudgetCount">₱0</h2>
                        <p class="mt-1 text-xs">Office Total Available Budget</p>
                    </div>
                    <div class="text-right relative z-10 pointer-events-none text-gray-900">
                        <p class="text-sm">Expenses</p>
                        <h2 class="mt-2 text-2xl font-bold" id="totalExpenseCount">₱0</h2>
                        <p class="mt-1 text-xs">Office Total Expense</p>
                    </div>

                </div>


            </div>
        </div>
        <div class="grid md:grid-cols-4 grid-cols-2 gap-3">
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="approved">

                <div>
                    <p class="text-sm">Processing</p>
                    <h2 class="mt-2 text-3xl font-bold" id="forSignature">₱0</h2>
                    <p class="mt-1 text-xs">Approved and for signature</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="for discussion">
                <div>
                    <div>
                        <p class="text-sm">Procurement Expense</p>
                        <h2 class="mt-2 text-3xl font-bold text-blue-600" id="forDiscussion">₱0</h2>
                        <p class="mt-1 text-xs">Procured items and services</p>
                    </div>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="pending">

                <div>
                    <p class="text-sm">Reimbursed</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="pending">₱0</h2>
                    <p class="mt-1 text-xs">Reimbursements</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="pending">

                <div>
                    <p class="text-sm">Other Expense</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="pending">₱0</h2>
                    <p class="mt-1 text-xs">Purchase, Service and other expenses</p>
                </div>
            </div>
        </div>


        <div class="mt-8 flex justify-center ">
            <div class="w-full ">

                <div class="w-full flex justify-between mb-5">

                </div>
                <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl shadow">
                    <table id="financeTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Transaction</th>
                                <th class="px-4 py-3">Date Processed</th>
                                <th class="px-4 py-3">Uploading Office</th>
                                <th class="px-4 py-3">Uploaded By</th>
                                <th class="px-4 py-3">Payee</th>
                                <th class="px-4 py-3">Particular</th>
                                <th class="px-4 py-3">Responsibility Center</th>
                                <th class="px-4 py-3">MFO/PAP</th>
                                <th class="px-4 py-3">UACS Object Code</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Fund Cluster</th>
                                <th class="px-4 py-3">Date Signed</th>
                                <th class="px-4 py-3">File Name</th>
                                <th class="px-4 py-3">File Path</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
                            <!-- Rows will be inserted dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>

    <div id="financeModal" class="hidden fixed inset-0 flex items-center justify-center z-40 bg-black/50 modal">
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
<div id="newfinanceModal" class="hidden fixed inset-0 flex items-center justify-center z-40 bg-black/50 modal">
    <div class="bg-white rounded-xl p-6 w-80 w-[60vw] relative">

        <div class="max-h-[60vh] overflow-y-auto p-3">

            <h2 class="text-lg font-semibold text-gray-700 mb-4">Upload New Document</h2>
            <div id="modalErrorMessage"
                class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul id="modalErrorList" class="list-disc list-inside"></ul>
            </div>

            <!-- Dropzone -->
            <div id="financedropzone"
                class="border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center p-6 text-gray-500 cursor-pointer hover:border-blue-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16V4m0 0L3 8m4-4l4 4m-4 8h10a2 2 0 002-2V8a2 2 0 00-2-2h-3" />
                </svg>
                <p class="text-sm">
                    Drag & drop a PDF file here or
                    <span class="text-blue-600 underline">click to browse</span>
                </p>
                <input type="file" accept="application/pdf" class="hidden" id="financefileInput" required />
                <p class="mt-1 text-sm text-red-600 hidden" data-error-for="financefileInput"></p>
            </div>
            <p id="financefileInfo" class="text-sm text-gray-600 mt-3 text-center"></p>
            <button id="clearfinanceSelectionBtn"
                class="mt-3 bg-gray-200 px-3 py-1 rounded hidden hover:bg-gray-300 transition">Clear</button>

            <!-- Form Fields -->
            <div class="mt-6 text-black grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm text-gray-600">Transaction</label>
                    <select id="transaction" class="w-full border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select</option>
                        <option value="ORS">Obligation Request and Status (ORS)</option>
                        <option value="DV">Disbursement Voucher (DV)</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="transaction"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Date Processed</label>
                    <input id="date_processed" type="date" class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="date_processed"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Payee</label>
                    <input id="payee" type="text" class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="payee"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Particular</label>
                    <textarea id="particular" class="w-full border-gray-300 rounded-lg px-3 py-2" required></textarea>
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="particular"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Responsibility Center</label>
                    <input id="responsibility_center" type="text"
                        class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="responsibility_center"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Expenditure Type</label>
                    <select id="expenditure" class="w-full border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select</option>
                        <option value="ORS">Obligation Request and Status (ORS)</option>
                        <option value="DV">Disbursement Voucher (DV)</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="expenditure"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">UACS Object Code</label>
                    <input id="uacs_object_code" type="text"
                        class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="uacs_object_code"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Amount</label>
                    <input id="amount" type="number" step="0.01"
                        class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="amount"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Fund Cluster</label>
                    <input id="fund_cluster" type="text" class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="fund_cluster"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Date Signed</label>
                    <input id="date_signed" type="date" class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="date_signed"></p>
                </div>

            </div>

        </div>

        <!-- Modal Buttons -->
        <div class="flex justify-end mt-8 space-x-3">
            <button id="btnCancelModal"
                class="px-4 py-2 rounded-lg border text-black border-gray-300 hover:bg-gray-100 modal-close">
                Cancel
            </button>
            <button id="submitFinanceBtn"
                class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 submitbtn">
                Submit
            </button>
        </div>

    </div>

</div>
<div id="BudgetModal" class="hidden fixed inset-0 flex items-center justify-center z-40 bg-black/50 modal">
    <div class="bg-white rounded-xl p-6 w-80 w-[60vw] relative">

        <div class="max-h-[60vh] overflow-y-auto p-3">

            <h2 class="text-lg font-semibold text-gray-700 mb-4">Add or Edit this year's budget</h2>
            <div id="modalErrorMessage"
                class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul id="modalErrorList" class="list-disc list-inside"></ul>
            </div>
            <div class="mt-6 text-black grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="w-96 text-black ">
                    <label class="text-sm text-gray-600 dark:text-white">Year</label>
                    <select id="budgetYear" class="year w-full border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="transaction"></p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Budget Amount</label>
                    <input id="budgetAmount" type="text" class="w-full border-gray-300 rounded-lg px-3 py-2" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="fund_cluster"></p>
                </div>


            </div>

        </div>

        <!-- Modal Buttons -->
        <div class="flex justify-end mt-8 space-x-3">
            <button id="btnCancelModal"
                class="px-4 py-2 rounded-lg border text-black border-gray-300 hover:bg-gray-100 modal-close">
                Cancel
            </button>
            <button id="submitBudgetBtn"
                class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 submitbtn">
                Submit
            </button>
        </div>

    </div>

</div>

<script>
    (function() {

        document.getElementById('editYearBudget').addEventListener('click', async function(e) {
            e.stopPropagation(); // prevent parent click if any
            //get that years amount
            const year = document.getElementById("budgetYear").value;
            const customYearsBudget = await getCurrentBudget(year);
            if (customYearsBudget.data.length > 0) {
                document.getElementById("budgetAmount").value = Number(customYearsBudget.data[0].amount)
                    .toLocaleString();
                document.getElementById("submitBudgetBtn").textContent = "Save";

            } else {

                document.getElementById("budgetAmount").value = 0;
                document.getElementById("submitBudgetBtn").textContent = "Submit";
            }
            initModal({
                modalId: "BudgetModal"
            });
        });
        document.getElementById("budgetYear").addEventListener("change", async function() {
            const customYearsBudget = await getCurrentBudget(this.value);
            if (customYearsBudget.data.length > 0) {
                document.getElementById("budgetAmount").value = Number(customYearsBudget.data[0].amount)
                    .toLocaleString();
                document.getElementById("submitBudgetBtn").textContent = "Save";

            } else {

                document.getElementById("budgetAmount").value = 0;
                document.getElementById("submitBudgetBtn").textContent = "Submit";
            }
        });


        document.getElementById("submitBudgetBtn").addEventListener("click", async function() {
            const submitbtn = this;
            const amount = document.getElementById("budgetAmount");
            const year = document.getElementById("budgetYear");
            if (this.textContent.toLowerCase() === "save") {
                const response = await updateBudget(year.value, amount.value);
                if (response.success) {
                    amount.value = "";
                    showMessage({
                        status: "success",
                        message: "budget updated",
                    });

                    getFinanceData();
                    hideModal('BudgetModal');
                }
            } else {
                const response = await insertBudget(year.value, amount.value);
                if (response.success) {

                    amount.value = "";
                    showMessage({
                        status: "success",
                        message: "budget inserted",
                    });

                    getFinanceData();
                    hideModal('BudgetModal');
                }
            }

        });




        populateYearDropDowm();
        initPDFDropzone({
            dropzoneId: "financedropzone",
            fileInputId: "financefileInput",
            fileInfoId: "financefileInfo",
            clearBtnId: "clearfinanceSelectionBtn",
        });
        initDataTables();
        const newfinancedoc = document.getElementById("btnNewFinanceDoc");
        newfinancedoc.addEventListener("click", () => {

            initModal({
                modalId: "newfinanceModal"
            });
        })
        const selectedYear = document.getElementById("year");
        selectedYear.addEventListener("change", function() {
            getFinanceData(this.value);
        });


        getFinanceData();

        async function getFinanceData(year = null) {
            year = document.getElementById("year").value;
            let filteredDocs = [];
            //BUG ID: 1 refactored getActivityData function
            try {
                const documents = await fetchWithRetry(
                    `/api/finance/getdata`, {
                        method: "GET",
                        headers: {
                            Accept: "application/json",
                        },
                    }
                );
                if (year) {
                    if (year) {
                        filteredDocs = documents.filter(doc => {
                            const createdYear = new Date(doc.created_at).getFullYear();
                            return createdYear === Number(year);
                        });
                    }

                } else {
                    filteredDocs = documents;
                }
                // insertBudget();
                updateRow(filteredDocs);
                updateCounts(filteredDocs, year);
            } catch (error) {
                console.error(error);
            }
            //get total budget per year.


        }

        function updateRow(documents) {
            const financeTable = document.getElementById("financeTable");
            if (!financeTable) return;

            let dt = null;
            if ($.fn.DataTable.isDataTable(financeTable)) {
                dt = $(financeTable).DataTable();
                dt.clear().draw(); // THIS is the key line
            } else {
                // fallback if DataTable was never initialized
                const tableBody = financeTable.querySelector("tbody");
                if (tableBody) tableBody.innerHTML = "";
            }

            documents.forEach((doc) => {
                // Format status if you have some logic for colors
                const statusColor = "bg-gray-200"; // Example, replace with real logic

                if (dt) {
                    const newRow = dt.row.add([
                        doc.transaction ?? "-", // Transaction
                        doc.date_processed ?? "-", // Date Processed
                        doc.uploading_office ?? "-", // Uploading Office
                        doc.uploaded_by ?? "-", // Uploaded By
                        doc.payee ?? "-", // Payee
                        doc.particular ?? "-", // Particular
                        doc.responsibility_center ?? "-", // Responsibility Center
                        doc.mfo_pap ?? "-", // MFO/PAP
                        doc.uacs_object_code ?? "-", // UACS Object Code
                        "₱" + Number(doc.amount).toLocaleString() ?? "-", // Amount
                        doc.fund_cluster ?? "-", // Fund Cluster
                        doc.date_signed ?? "-", // Date Signed
                        doc.file_name ?? "-", // File Name
                        `<a href="${doc.file_path ?? '#'}" target="_blank" class="text-blue-500 underline">
                    View
                </a>` // File Path as clickable link
                    ]).draw(false);

                    const rowNode = newRow.node();
                    if (!rowNode) return;

                    rowNode.classList.add(
                        "transition-colors",
                        "duration-300",
                        "hover:dark:bg-white",
                        "hover:dark:text-black",
                        "cursor-pointer"
                    );

                    // rowNode.addEventListener("click", function() {
                    //     checkActionButtons(
                    //         doc.status,
                    //         doc.recipient_id,
                    //         doc.destination_office,
                    //         doc.receipt_confirmation,
                    //         doc.revision_status
                    //     );
                    //     clearModalFields();
                    //     showSkeletonLoaders();
                    //     initModal({
                    //         modalId: "DocumentModal"
                    //     });
                    //     populateDocumentModal(doc.id); // Use doc.id as document_id
                    //     logActivity("view", doc.id, doc.transaction); // Example
                    // });
                } else {
                    // Fallback if DataTable not initialized
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                <td>${doc.transaction ?? "-"}</td>
                <td>${doc.date_processed ?? "-"}</td>
                <td>${doc.uploading_office ?? "-"}</td>
                <td>${doc.uploaded_by ?? "-"}</td>
                <td>${doc.payee ?? "-"}</td>
                <td>${doc.particular ?? "-"}</td>
                <td>${doc.responsibility_center ?? "-"}</td>
                <td>${doc.mfo_pap ?? "-"}</td>
                <td>${doc.uacs_object_code ?? "-"}</td>
                <td>${doc.amount ?? "-"}</td>
                <td>${doc.fund_cluster ?? "-"}</td>
                <td>${doc.date_signed ?? "-"}</td>
                <td>${doc.file_name ?? "-"}</td>
                <td><a href="${doc.file_path ?? '#'}" target="_blank" class="text-blue-500 underline">View</a></td>
            `;
                    tableBody.appendChild(tr);
                }
            });
        }


        async function insertBudget(year = 2026, amount = 1500000) {
            budgetamount = amount.replace(/,/g, '');
            const payload = {
                year: year,
                amount: budgetamount,
            };

            const data = await fetchWithRetry("/api/finance/budget", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });
            if (!data.success) return;
            return data;
        }

        async function updateBudget(year = 2026, amount = 1500000) {
            budgetamount = amount.replace(/,/g, '');
            const payload = {
                amount: Number(budgetamount),
                year: year,
            };

            const data = await fetchWithRetry(`/api/finance/budget/${year}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });
            if (!data.success) return;
            return data;

        }
        async function getCurrentBudget(customYear = 2025) {

            const year = customYear;
            const data = await fetchWithRetry(`/api/finance/budget?year=${year}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                },
            });

            return data;
        }


        async function updateCounts(docs, year = false) {
            let currentYear = 0;
            if (!year) {

                currentYear = new Date().getFullYear();
            } else {
                currentYear = year;
            }

            const budget = await getCurrentBudget(currentYear);
            let totalBudget = budget?.data?.[0]?.amount ?? 0;
            let availableBudget = 0;



            let totalExpense = 0;
            docs.forEach((doc) => {

                const amount = Number(doc.amount);
                totalExpense += amount;
            });
            availableBudget = totalBudget - totalExpense;
            document.getElementById("currentYear").textContent = currentYear;
            document.getElementById("yearBudget").textContent = Number(totalBudget).toLocaleString();

            // return;




            const totalavailableBudget = document.getElementById("totalAvailableBudgetCount");
            const totalExpenseCount = document.getElementById("totalExpenseCount");

            totalavailableBudget.textContent = "₱" + availableBudget.toLocaleString('en-US');
            totalExpenseCount.textContent = "₱" + totalExpense.toLocaleString('en-US');



            //calculate the utilization percentage
            const percentage = (totalExpense / totalBudget) * 100;
            setBudgetPercent(100 - percentage);



            function setBudgetPercent(percent) {
                percent = Math.max(0, Math.min(100, percent));
                document.getElementById('budgetFill').style.width = percent + '%';
            }
        }


        async function submitDocumentForm() {
            const fileInput = document.getElementById("financefileInput");

            if (!fileInput.files.length) {
                alert("Please select a PDF file.");
                return;
            }

            // Build FormData
            const formData = new FormData();
            formData.append('transaction', document.getElementById("transaction").value);
            formData.append('date_processed', document.getElementById("date_processed").value);
            formData.append('payee', document.getElementById("payee").value);
            formData.append('particular', document.getElementById("particular").value);
            formData.append('responsibility_center', document.getElementById("responsibility_center").value);
            formData.append('expenditure', document.getElementById("expenditure").value);
            formData.append('uacs_object_code', document.getElementById("uacs_object_code").value);
            formData.append('amount', parseFloat(document.getElementById("amount").value) || 0);
            formData.append('fund_cluster', document.getElementById("fund_cluster").value);
            formData.append('date_signed', document.getElementById("date_signed").value);
            formData.append('file', fileInput.files[0]);
            const submitBtn = document.getElementById("submitFinanceBtn");

            try {

                submitBtn.disabled = true;
                submitBtn.textContent = "Submitting...";
                const response = await fetchWithRetry("/api/finance/document", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                if (!response.success) {
                    showValidationErrors(response.response.invalid_fields);
                    showMessage({
                        status: "error",
                        message: "error uploading document",
                    });
                    return;
                }
                hideModal('newfinanceModal');

                clearDocumentForm();

                showMessage({
                    status: "success",
                    message: "Upload success",
                });
                getFinanceData();

            } catch (error) {
                console.error("Error uploading document:", error);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = "Submit";
            }
        }


        function showValidationErrors(errors) {
            if (!errors) return;

            document.querySelectorAll("[data-error-for]").forEach(el => {
                el.textContent = "";
                el.classList.add("hidden");
            });

            document.querySelectorAll("input, textarea, select").forEach(el => {
                el.classList.remove("border-red-500", "focus:ring-red-500");
            });

            Object.keys(errors).forEach((field) => {
                const input = document.querySelector(`[name="${field}"]`);
                const errorTag = document.querySelector(
                    `[data-error-for="${field}"]`
                );

                if (input) {
                    input.classList.add("border-red-500", "focus:ring-red-500");
                }

                if (errorTag) {
                    const message = errors[field][0];

                    if (message.includes("safe_text")) {
                        errorTag.textContent = "Invalid input";
                    } else {
                        errorTag.textContent = message;
                    }
                    errorTag.classList.remove("hidden");
                }
            });
        }


        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add("hidden");
        }

        function clearDocumentForm() {
            // Clear text, number, date, textarea, select inputs
            const fields = [
                "transaction",
                "date_processed",
                "payee",
                "particular",
                "responsibility_center",
                "expenditure",
                "uacs_object_code",
                "amount",
                "fund_cluster",
                "date_signed"
            ];

            fields.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;

                if (el.tagName === "SELECT") {
                    el.selectedIndex = 0; // reset select
                } else {
                    el.value = ""; // clear text, textarea, date, number
                }
            });

            // Clear file input
            const fileInput = document.getElementById("financefileInput");
            if (fileInput) {
                fileInput.value = "";
            }

            // Optional: clear file info text
            const fileInfo = document.getElementById("financefileInfo");
            if (fileInfo) fileInfo.textContent = "";
        }

        // Attach the submit handler
        document.getElementById("submitFinanceBtn").addEventListener("click", submitDocumentForm);

    })();
</script>
