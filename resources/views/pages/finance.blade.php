<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto py-5 ">

        <div class="relative w-full mb-5 statusButton cursor-pointer" data-status="all">

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
                        <p class="mt-1 text-xs">All files in the system</p>
                    </div>
                    <div class="text-right relative z-10 pointer-events-none text-gray-900">
                        <p class="text-sm">Available Budget</p>
                        <h2 class="mt-2 text-2xl font-bold" id="totalExpenseCount">₱0</h2>
                        <p class="mt-1 text-xs">All files in the system</p>
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
                        <p class="text-sm">Procured</p>
                        <h2 class="mt-2 text-3xl font-bold text-blue-600" id="forDiscussion">₱0</h2>
                        <p class="mt-1 text-xs">Needs attention</p>
                    </div>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="pending">

                <div>
                    <p class="text-sm">Reimbursed</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="pending">₱0</h2>
                    <p class="mt-1 text-xs">Waiting for processing</p>
                </div>
            </div>
            <div class="col-span-1 p-5 rounded-md drop-shadow-md bg-white text-black statusButton cursor-pointer"
                data-status="pending">

                <div>
                    <p class="text-sm">Travel Expense</p>
                    <h2 class="mt-2 text-3xl font-bold text-orange-400" id="pending">₱0</h2>
                    <p class="mt-1 text-xs">Waiting for processing</p>
                </div>
            </div>
        </div>


        <div class="mt-8 flex justify-center ">
            <div class="w-full ">

                <div class="w-full flex justify-between mb-5">

                    <button id="btnNewFinanceDoc"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition modal-open">
                        + New Document
                    </button>
                </div>
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
    <div class="bg-white rounded-xl p-6 w-80 w-[60vw]  relative">

        <div class="max-h-[60vh] overflow-y-auto p-3 ">

            <h2 class="text-lg font-semibold text-gray-700 mb-4">Upload New Document</h2>
            <div id="modalErrorMessage"
                class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul id="modalErrorList" class="list-disc list-inside"></ul>
            </div>
            <div id="dropzone"
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
                <input type="file" accept="application/pdf" class="hidden" id="fileInput" required />
            </div>
            <p id="fileInfo" class="text-sm text-gray-600 mt-3 text-center"></p>
            <button id="clearSelectionBtn"
                class="mt-3 bg-gray-200 px-3 py-1 rounded hidden hover:bg-gray-300 transition">Clear</button>
            <div class="mt-6 text-black">
                <div>
                    <label class="text-sm text-gray-600">Document Number</label>
                    <input id="document_code" type="text" maxlength="25" pattern="^[a-zA-Z0-9\-_'\]+$"
                        title="Only letters, numbers, hyphen (-), underscore (_), single quote ('), and double quote (\") are allowed."
                        class="w-full border-gray-300 rounded-lg px-3 py-2" required />

                </div>
                <div>
                    <label class="text-sm text-gray-600">Subject</label>
                    <input id="subject" type="text" class="w-full border-gray-300 rounded-lg px-3 py-2"
                        required />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Signatory</label>
                    <input id="signatory" type="text" class="w-full border-gray-300 rounded-lg px-3 py-2"
                        required />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Remarks</label>
                    <textarea id="remarks" class="w-full border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>
            </div>

        </div>
        <div class="flex justify-end mt-8 space-x-3">
            <button id="btnCancelModal"
                class="px-4 py-2 rounded-lg border text-black border-gray-300 hover:bg-gray-100 modal-close">
                Cancel
            </button>
            <button class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 submitbtn">
                Submit
            </button>
        </div>
    </div>
</div>
</div>

<script>
    (function() {

        initDataTables();
        // console.log(e.dataset.status);
        const newfinancedoc = document.getElementById("btnNewFinanceDoc");
        newfinancedoc.addEventListener("click", () => {

            initModal({
                modalId: "newfinanceModal"
            });
        })



        //get total budget per year.
        const totalBudget = 1000000;


        //get total expenses per year
        const totalExpense = 0;

        const availableBudget = totalBudget - totalExpense;


        //update dashboardcount
        UpdateCounts();

        function UpdateCounts() {
            const totalavailableBudget = document.getElementById("totalAvailableBudgetCount");
            const totalExpenseCount = document.getElementById("totalExpenseCount");

            totalavailableBudget.textContent = "₱" + availableBudget.toLocaleString('en-US');
            totalExpenseCount.textContent = "₱" + totalExpense.toLocaleString('en-US');
        }


        //calculate the utilization percentage
        const percentage = (totalExpense / totalBudget) * 100;
        console.log(percentage);
        setBudgetPercent(100 - percentage);



        function setBudgetPercent(percent) {
            percent = Math.max(0, Math.min(100, percent));
            document.getElementById('budgetFill').style.width = percent + '%';
        }
    })();
</script>
