<div class="w-full h-screen p-5 bg-gray-50">
    <div class="container mx-auto space-y-6">
        <div class="w-full border rounded-lg bg-white shadow flex flex-col lg:flex-row gap-4 p-4">
            <div class="flex flex-col w-full lg:w-1/3 gap-4">
                <div class="flex gap-4">
                    <div class="flex-1 border rounded-lg p-4 bg-blue-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Total Users</div>
                        <div class="text-2xl font-bold mt-2">1,250</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-4 bg-green-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Active Users</div>
                        <div class="text-2xl font-bold mt-2">1,020</div>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Pending</div>
                        <div class="text-xl font-bold mt-1">120</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Suspended</div>
                        <div class="text-xl font-bold mt-1">50</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Admins</div>
                        <div class="text-xl font-bold mt-1">30</div>
                    </div>
                    <div class="flex-1 border rounded-lg p-2 bg-gray-50 text-center">
                        <div class="text-sm font-medium text-gray-700">Guests</div>
                        <div class="text-xl font-bold mt-1">50</div>
                    </div>
                </div>

            </div>
            <div class="flex flex-1 w-full gap-4 flex-wrap items-end">
                <div class="flex flex-col flex-1 gap-4">
                    <div>
                        <label for="registeredFrom" class="text-gray-700 font-medium">Registered From</label>
                        <input id="registeredFrom" type="text" placeholder="Select start date"
                            class="border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none datetimepicker" />
                    </div>
                    <div>
                        <label for="registeredTo" class="text-gray-700 font-medium">Registered To</label>
                        <input id="registeredTo" type="text" placeholder="Select end date"
                            class="border rounded-lg p-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none datetimepicker" />
                    </div>
                </div>
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-gray-700 font-medium">Role</label>
                    <select class="border rounded-lg p-2 w-full">
                        <option>All</option>
                        <option>Admin</option>
                        <option>User</option>
                        <option>Guest</option>
                    </select>
                    <label class="text-gray-700 font-medium">Status</label>
                    <select class="border rounded-lg p-2 w-full">
                        <option>All</option>
                        <option>Active</option>
                        <option>Suspended</option>
                        <option>Pending</option>
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
            <table class="min-w-full divide-y divide-gray-200  p-5">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">User ID</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Email</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Role</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">1001</td>
                        <td class="px-4 py-2 text-sm">John Doe</td>
                        <td class="px-4 py-2 text-sm">johndoe@example.com</td>
                        <td class="px-4 py-2 text-sm">Admin</td>
                        <td class="px-4 py-2 text-sm text-green-500 font-medium">Active</td>
                        <td class="px-4 py-2 text-sm">Oct 1, 2025</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">1002</td>
                        <td class="px-4 py-2 text-sm">Jane Smith</td>
                        <td class="px-4 py-2 text-sm">janesmith@example.com</td>
                        <td class="px-4 py-2 text-sm">User</td>
                        <td class="px-4 py-2 text-sm text-yellow-500 font-medium">Pending</td>
                        <td class="px-4 py-2 text-sm">Oct 2, 2025</td>
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
