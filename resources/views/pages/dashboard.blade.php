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
                    <ul id="activityList" class="divide-y divide-white/6 text-sm">
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-2 col-span-1 rounded-2xl backdrop-blur-lg p-4 shadow-lg bg-white dark:bg-gray-600 ">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-medium ">Top 5 Priority</h3>
                    <span class="text-xs ">By priority</span>
                </div>
                <div class="w-full max-h-[30vh] overflow-y-auto space-y-2" id="prioritylist"></div>

                <div class="mt-3 text-xs ">Minimal columns for compact view.</div>
            </div>
        </div>

    </div>

    <div id="countModal" class="hidden fixed inset-0 flex items-center justify-center z-40 bg-black/50 modal">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-80 w-[60vw]  relative">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Document Created</h2>
            <button id="refreshbtn"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200">refresh
                table</button>
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
        initdashboard();
        document.getElementById("refreshbtn").addEventListener("click", () => {
            updatetable();
        })



    })();
</script>
