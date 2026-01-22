<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto py-5 ">
        <div class="w-full py-5 flex flex-col gap-4 md:flex-row md:justify-between md:items-end">

            <!-- Year Selector -->
            <div class="w-full md:w-96 text-black">
                <label class="text-sm text-gray-600 dark:text-white">Year</label>
                <select id="year" class="year w-full border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                </select>
                <p class="mt-1 text-sm text-red-600 hidden" data-error-for="transaction"></p>
            </div>

            <!-- New Document Button -->
            <div class="w-full md:w-auto">
                <button id="btnNewFinanceDoc"
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition modal-open">
                    + New Document
                </button>
            </div>
        </div>

        <!-- Budget Header -->
        <div class="relative w-full mb-5 statusButton cursor-pointer" data-status="all">
            <p class="flex flex-wrap items-center gap-2 text-sm sm:text-base">
                <b id="currentYear">YEAR</b> budget: <b>₱</b>
                <b id="yearBudget">1,000,000</b>

                <!-- Edit Icon -->
                <button type="button" id="editYearBudget"
                    class="ml-1 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Edit budget">
                    ✎
                </button>
            </p>

            <!-- Budget Card -->
            <div class="relative p-5 rounded-xl border border-gray-300 bg-white drop-shadow-sm overflow-hidden">

                <!-- Battery Fill -->
                <div id="budgetFill"
                    class="absolute inset-y-0 left-0 bg-gradient-to-r from-green-500 to-green-700 transition-all duration-700"
                    style="width: 0%;">
                </div>

                <!-- Budget Info -->
                <div class="w-full flex flex-col sm:flex-row sm:justify-between gap-4">
                    <div class="relative z-10 pointer-events-none text-gray-900">
                        <p class="text-sm">Available Budget</p>
                        <h2 class="mt-2 text-xl sm:text-2xl font-bold" id="totalAvailableBudgetCount">₱0</h2>
                        <p class="mt-1 text-xs">Office Total Available Budget</p>
                    </div>

                    <div class="relative z-10 pointer-events-none text-gray-900 sm:text-right">
                        <p class="text-sm">Expenses</p>
                        <h2 class="mt-2 text-xl sm:text-2xl font-bold" id="totalExpenseCount">₱0</h2>
                        <p class="mt-1 text-xs">Office Total Expense</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="processing">
                <div>
                    <p class="text-sm">Processing</p>
                    <h2 class="mt-2 text-3xl font-bold" id="processing">0</h2>
                    <p class="mt-1 text-xs">Processing documents</p>
                </div>
            </div>

            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="approved">
                <div>
                    <p class="text-sm">Approved</p>
                    <h2 class="mt-2 text-3xl font-bold text-blue-600" id="approved">0</h2>
                    <p class="mt-1 text-xs">Approved documents</p>
                </div>
            </div>

            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="completed">
                <div>
                    <p class="text-sm">Completed</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="completed">0</h2>
                    <p class="mt-1 text-xs">Completed documents</p>
                </div>
            </div>

            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="returned">
                <div>
                    <p class="text-sm">Returned</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="returned">0</h2>
                    <p class="mt-1 text-xs">Returned documents</p>
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
                                <th class="px-4 py-3">Expenditure</th>
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

</div>
<div id="financeModal" class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-4 modal">

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h2 class="hidden text-2xl font-semibold text-gray-900 dark:text-gray-100">
                ID: <span id="financeId">—</span>
            </h2>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                Transaction: <span id="financeTransaction">—</span>
            </h2>
        </div>

        <!-- BODY -->
        <div
            class="max-h-[60vh] overflow-y-auto flex flex-col lg:flex-row divide-y lg:divide-y-0 lg:divide-x divide-gray-200 dark:divide-gray-700">

            <!-- LEFT: METADATA -->
            <div class="w-full lg:w-1/2 p-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">
                    Finance Document Details
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Status:
                    <span id="financeStatus" class="font-medium text-blue-600 dark:text-blue-400">—</span>
                </p>

                <div class="space-y-2 text-md">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Uploading Office:</span>
                        <span id="financeOffice" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Uploaded By:</span>
                        <span id="financeUploader" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Payee:</span>
                        <span id="financePayee" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Particular:</span>
                        <span id="financeParticular" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Responsibility Center:</span>
                        <span id="financeResponsibilityCenter" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Expenditure:</span>
                        <span id="financeMfoPap" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">UACS Object Code:</span>
                        <span id="financeUacs" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Fund Cluster:</span>
                        <span id="financeFundCluster" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Amount:</span>
                        <span id="financeAmount" class="text-gray-900 dark:text-gray-100 font-semibold"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Date Signed:</span>
                        <span id="financeDateSigned" class="text-gray-900 dark:text-gray-100"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Date Processed:</span>
                        <span id="financeDateProcessed" class="text-gray-900 dark:text-gray-100">—</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: FILE + ACTIVITY -->
            <div class="w-full lg:w-1/2 p-6 space-y-6">

                <!-- FILE -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">
                            Attached File
                        </h3>

                        <a id="financeDownloadBtn" download
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">
                            Download File
                        </a>
                    </div>

                    <div
                        class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex items-center justify-between">
                        <span id="financeFileName" class="text-gray-800 dark:text-gray-200 truncate">—</span>

                        <a id="financeViewFile" href="#" target="_blank"
                            class="text-blue-600 hover:underline text-sm">
                            View File
                        </a>
                    </div>
                </div>

                <!-- ACTIVITY -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">
                            Activity History
                        </h3>

                        <button id="addFinanceActivityBtn"
                            class="text-sm font-medium text-blue-600 hover:text-blue-700">
                            + Add Action
                        </button>
                    </div>

                    <ul id="financeActivityLog" class="space-y-2 max-h-48 overflow-y-auto">
                        <div class="flex items-center justify-center">
                            <div class="w-8 h-8 border-2 border-gray-200 border-t-2 border-t-gray-800 rounded-full animate-spin"
                                role="status" aria-label="Loading">
                            </div>
                        </div>
                    </ul>
                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
            <button
                class="modal-close border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 px-5 py-2 rounded-lg text-sm font-medium">
                Close
            </button>
        </div>
    </div>
</div>


<div id="newfinanceModal" class="hidden fixed inset-0 flex items-center justify-center z-40 bg-black/50 modal p-4">

    <!-- Modal Container -->
    <div class="bg-white rounded-xl relative w-full max-w-3xl md:w-[60vw] p-6">

        <div class="max-h-[70vh] overflow-y-auto">

            <h2 class="text-lg md:text-xl font-semibold text-gray-700 mb-4">Upload New Document</h2>

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
                <p class="text-sm text-center">
                    Drag & drop a PDF file here or
                    <span class="text-blue-600 underline">click to browse</span>
                </p>
                <input type="file" accept="application/pdf" class="hidden" id="financefileInput" required />
                <p class="mt-1 text-sm text-red-600 hidden" data-error-for="financefileInput"></p>
            </div>
            <p id="financefileInfo" class="text-sm text-gray-600 mt-3 text-center"></p>
            <button id="clearfinanceSelectionBtn"
                class="mt-3 bg-gray-200 px-3 py-1 rounded hidden hover:bg-gray-300 transition w-full md:w-auto">
                Clear
            </button>

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
                        <option value="EME">Extraordinary and Miscellaneous Expenses (EME)</option>
                        <option value="Mobile Reimbursement">Mobile Reimbursement</option>
                        <option value="Subscription Reimbursement">Subscription Reimbursement</option>
                        <option value="Subscription Payment">Subscription Payment</option>
                        <option value="Payment">Payment</option>
                        <option value="Reimbursement">Reimbursement</option>
                        <option value="Other">Other</option>
                    </select>
                    <div id="otherExpenditureContainer" class="hidden my-3">
                        <label class="text-sm text-gray-600">Other Expenditure</label>
                        <input id="otherExpenditure" type="text"
                            class="w-full border-gray-300 rounded-lg px-3 py-2" />
                        <p class="mt-1 text-sm text-red-600 hidden" data-error-for="otherExpenditure"></p>

                    </div>
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
        <div class="flex flex-col md:flex-row justify-end mt-6 md:space-x-3 space-y-2 md:space-y-0">
            <button id="btnCancelModal"
                class="px-4 py-2 rounded-lg border text-black border-gray-300 hover:bg-gray-100 modal-close w-full md:w-auto">
                Cancel
            </button>
            <button id="submitFinanceBtn"
                class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 submitbtn w-full md:w-auto">
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
<div id="addFinanceActivityModal"
    class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 px-4 modal">

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        <!-- HEADER -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                Add Finance Activity
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Record a new action or status update
            </p>
        </div>

        <!-- BODY -->
        <div class="px-6 py-5 space-y-4">

            <!-- ACTIVITY -->
            <div>
                <label for="activityInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Activity <span class="text-red-500">*</span>
                </label>
                <input id="activityInput" type="text" maxlength="255"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white
                           focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g. Document reviewed">
            </div>

            <!-- STATUS -->
            <div>
                <label for="statusSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Status
                </label>
                <select id="statusSelect"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white
                           focus:ring-blue-500 focus:border-blue-500">
                    <option value="approved">Approved</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="returned">Returned</option>
                </select>
            </div>

            <!-- REMARKS -->
            <div>
                <label for="remarksInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Remarks
                </label>
                <textarea id="remarksInput" rows="3"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white
                           focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Optional notes or remarks"></textarea>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">

            <button
                class="modal-close border border-gray-300 dark:border-gray-700
                       text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800
                       px-4 py-2 rounded-lg text-sm font-medium">
                Cancel
            </button>

            <button id="saveFinanceActivityBtn"
                class="bg-blue-600 hover:bg-blue-700 text-white
                       px-4 py-2 rounded-lg text-sm font-medium">
                Save Activity
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
        document.getElementById("addFinanceActivityBtn")?.addEventListener("click", async () => {
            initModal({
                modalId: "addFinanceActivityModal"
            });
        });

        document.getElementById("saveFinanceActivityBtn")?.addEventListener("click", async () => {
            const activity = document.getElementById("activityInput").value.trim();
            const status = document.getElementById("statusSelect").value;
            const remarks = document.getElementById("remarksInput").value.trim();
            const finance_id = document.getElementById("financeId").textContent;

            const payload = {
                activity,
                status,
                finance_id,
                remarks: remarks || null,
            };

            try {
                this.disabled = true;
                this.textContent = "Saving...";
                const response = await postFinanceActivity(payload);

                if (response.success) {
                    // Optional: refresh activity list
                    // await loadFinanceActivities();

                    document.querySelectorAll(
                            "#addFinanceActivityModal input, #addFinanceActivityModal textarea")
                        .forEach(el => el.value = "");

                    document.getElementById("addFinanceActivityModal").classList.add("hidden");
                    // document.getElementById("financeModal").classList.add("hidden");
                    showMessage({
                        status: "success",
                        message: "Activity Saved",
                    });
                    const activities = await getFinanceActivities(finance_id);
                    // console.log(activities);
                    getFinanceData();
                    populateFinanceActivityLog(activities.data);
                }
            } catch (error) {
                console.error("Failed to save finance activity:", error);
                showMessage({
                    status: "error",
                    message: "error",
                });
            } finally {

                this.disabled = false;
                this.textContent = "Save Activity";
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

        function populateFinanceModal(doc) {
            document.getElementById("financeTransaction").textContent =
                doc.transaction ?? "-";
            document.getElementById("financeStatus").textContent =
                doc.status ?? "-";
            document.getElementById("financeId").textContent =
                doc.id ?? "-";

            document.getElementById("financeDateProcessed").textContent =
                doc.date_processed ?? "-";

            document.getElementById("financeOffice").textContent =
                doc.uploading_office_info?.office_name ?? "-";

            document.getElementById("financeUploader").textContent =
                doc.uploader_info?.name ?? "-";

            document.getElementById("financePayee").textContent =
                doc.payee ?? "-";

            document.getElementById("financeParticular").textContent =
                doc.particular ?? "-";

            document.getElementById("financeResponsibilityCenter").textContent =
                doc.responsibility_center ?? "-";

            document.getElementById("financeMfoPap").textContent =
                doc.expenditure ?? "-";

            document.getElementById("financeUacs").textContent =
                doc.uacs_object_code ?? "-";

            document.getElementById("financeFundCluster").textContent =
                doc.fund_cluster ?? "-";

            document.getElementById("financeAmount").textContent =
                doc.amount ?
                "₱" + Number(doc.amount).toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }) :
                "-";

            document.getElementById("financeDateSigned").textContent =
                doc.date_signed ?? "-";

            document.getElementById("financeFileName").textContent =
                doc.file_name ?? "-";

            const fileLink = document.getElementById("financeViewFile");
            if (doc.file_path) {
                fileLink.href = doc.file_path;
                fileLink.classList.remove("pointer-events-none", "text-gray-400");
            } else {
                fileLink.href = "#";
                fileLink.classList.add("pointer-events-none", "text-gray-400");
            }
            const downloadBtn = document.getElementById("financeDownloadBtn");

            if (doc.file_path) {
                downloadBtn.href = doc.file_path;
                downloadBtn.classList.remove("pointer-events-none", "opacity-50");
            } else {
                downloadBtn.href = "#";
                downloadBtn.classList.add("pointer-events-none", "opacity-50");
            }
        }

        document.getElementById("addFinanceActivityBtn")?.addEventListener("click", () => {
            // openAddFinanceActivityModal();
            console.log("Add finance action clicked");
        });

        async function postFinanceActivity(payload) {
            return await fetchWithRetry("/api/finance/finance-activity", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            });
        }
        async function getFinanceActivities(finance_id) {
            return await fetchWithRetry(`/api/finance/finance-activity/${finance_id}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                },
            });
        }

        getFinanceData();

        async function getFinanceData(year = null) {
            year = document.getElementById("year").value;
            let filteredDocs = [];
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
                        doc.uploading_office_info.office_name ?? "-", // Uploading Office
                        doc.uploader_info.name ?? "-", // Uploaded By
                        doc.payee ?? "-", // Payee
                        doc.particular ?? "-", // Particular
                        doc.responsibility_center ?? "-", // Responsibility Center
                        doc.expenditure ?? "-", // MFO/PAP
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

                    rowNode.addEventListener("click", async function() {
                        populateFinanceModal(doc);
                        const activities = await getFinanceActivities(doc.id);
                        // console.log(activities);
                        populateFinanceActivityLog(activities.data);
                        initModal({
                            modalId: "financeModal"
                        });
                        //populate finance modal
                    });
                } else {
                    // Fallback if DataTable not initialized
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                <td>${doc.transaction ?? "-"}</td>
                <td>${doc.date_processed ?? "-"}</td>
                <td>${doc.uploading_office_info.office_name ?? "-"}</td>
                <td>${doc.uploader_info.name ?? "-"}</td>
                <td>${doc.payee ?? "-"}</td>
                <td>${doc.particular ?? "-"}</td>
                <td>${doc.responsibility_center ?? "-"}</td>
                <td>${doc.expenditure ?? "-"}</td>
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

        function populateFinanceActivityLog(activities) {
            const container = document.getElementById("financeActivityLog");
            if (!container) return;

            // Clear existing
            container.innerHTML = "";

            if (!activities || !activities.length) {
                container.innerHTML = `<li class="text-gray-500 dark:text-gray-400 text-sm">No activity yet.</li>`;
                return;
            }

            // Reverse so latest appears first
            const sorted = [...activities].sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

            sorted.forEach(act => {
                const timestamp = new Date(act.timestamp);
                const formattedTime = timestamp.toLocaleString("en-PH", {
                    year: "numeric",
                    month: "short",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                });

                const li = document.createElement("li");
                li.className = "bg-gray-50 dark:bg-gray-800 p-3 rounded-lg shadow-sm";

                li.innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium text-gray-800 dark:text-gray-100">${act.activity}</p>
                    ${act.remarks ? `<p class="text-gray-600 dark:text-gray-400 text-sm mt-1">${act.remarks}</p>` : ''}
                </div>
                <span class="text-gray-500 dark:text-gray-400 text-xs ml-2">${formattedTime}</span>
            </div>
        `;

                container.appendChild(li);
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
            let processing = 0;
            let approved = 0;
            let completed = 0;
            let returned = 0;
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
                const status = doc.status;
                switch (status.toLowerCase()) {
                    case "processing":
                        processing++
                        break;
                    case "approved":
                        approved++
                        break;
                    case "completed":
                        completed++
                        break;
                    case "returned":
                        returned++
                        break;
                }

                const amount = Number(doc.amount);
                totalExpense += amount;
            });
            availableBudget = totalBudget - totalExpense;
            document.getElementById("currentYear").textContent = currentYear;
            document.getElementById("approved").textContent = approved;
            document.getElementById("completed").textContent = completed;
            document.getElementById("returned").textContent = returned;
            document.getElementById("processing").textContent = processing;
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

        document.getElementById("expenditure").addEventListener("change", function() {
            if (this.value.toLowerCase() === "other") {
                document.getElementById("otherExpenditureContainer").classList.remove("hidden");
            } else {
                document.getElementById("otherExpenditureContainer").classList.add("hidden");
            }
        })
        async function submitDocumentForm() {
            const fileInput = document.getElementById("financefileInput");

            if (!fileInput.files.length) {
                alert("Please select a PDF file.");
                return;
            }
            let expenditureValue = document.getElementById("expenditure").value.toLowerCase();

            if (expenditureValue === "other") {
                // If "Other" is selected, get the value from the "otherExpenditure" textbox
                expenditureValue = document.getElementById("otherExpenditure").value;
            }

            // Build FormData
            const formData = new FormData();
            formData.append('transaction', document.getElementById("transaction").value);
            formData.append('date_processed', document.getElementById("date_processed").value);
            formData.append('payee', document.getElementById("payee").value);
            formData.append('particular', document.getElementById("particular").value);
            formData.append('responsibility_center', document.getElementById("responsibility_center").value);
            formData.append('expenditure', expenditureValue);
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
