<div class="w-full h-screen p-5 bg-gray-50">
    <div class="container mx-auto space-y-6">
        <div class="w-full border rounded-lg bg-white shadow flex flex-col lg:flex-row gap-4 p-4">
            <div class="flex flex-col w-full lg:w-1/3 gap-4">
                <div class="flex gap-4">
                    <div class="flex-1 border rounded-lg p-4 bg-blue-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Total Documents</div>
                        <div class="text-2xl font-bold mt-2">16,520</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-4 bg-yellow-50 text-center">
                        <div class="text-sm font-medium text-gray-700">For Discussion</div>
                        <div class="text-2xl font-bold mt-2">6,520</div>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Pending</div>
                        <div class="text-xl font-bold mt-1">6,520</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Processed</div>
                        <div class="text-xl font-bold mt-1">6,520</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Overdue</div>
                        <div class="text-xl font-bold mt-1">6,520</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Remanded</div>
                        <div class="text-xl font-bold mt-1">6,520</div>
                    </div>
                </div>

            </div>
            <div class="flex flex-1 w-full gap-4 flex-wrap items-end">
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-gray-700 font-medium">From</label>
                    <input type="date" class="border rounded-lg p-2 datetimepicker" />
                    <label class="text-gray-700 font-medium">To</label>
                    <input type="date" class="border rounded-lg p-2 datetimepicker" />
                </div>
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-gray-700 font-medium">Status</label>
                    <select class="border rounded-lg p-2 w-full">
                        <option>All</option>
                        <option>Pending</option>
                        <option>Processed</option>
                    </select>
                    <label class="text-gray-700 font-medium">Office</label>
                    <select class="border rounded-lg p-2 w-full">
                        <option>All</option>
                        <option>DEV</option>
                        <option>EOD-PO</option>
                    </select>
                </div>
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-gray-700 font-medium">Label</label>
                    <select class="border rounded-lg p-2 w-full">
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

        <div class="w-full bg-white border rounded-lg shadow overflow-auto">
            <table id="reportsocumentsTable" class="min-w-full divide-y divide-gray-200 p-5">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Control No.</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">File Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Label</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Subject</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Due Date</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Origin</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Destination</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date Uploaded</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date Updated</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">102225-0001</td>
                        <td class="px-4 py-2 text-sm">ABE FILE 123</td>
                        <td class="px-4 py-2 text-sm">PP</td>
                        <td class="px-4 py-2 text-sm">MEMO FOR SOMETHING</td>
                        <td class="px-4 py-2 text-sm">October 15, 2025</td>
                        <td class="px-4 py-2 text-sm">PP</td>
                        <td class="px-4 py-2 text-sm">PP</td>
                        <td class="px-4 py-2 text-sm">October 5, 2025</td>
                        <td class="px-4 py-2 text-sm">October 5, 2025</td>
                        <td class="px-4 py-2 text-sm text-red-500 font-medium">Remanded</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    (function() {
        initDataTables();
        initDatePickers();
    })();
</script>
