<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 text-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto py-5 ">
        <div class=" mb-5">
            <div class="w-full flex justify-between mb-5">

                <h2 class="text-lg font-semibold text-gray-700 dark:text-white">
                    Assigned to You
                </h2>
                <button id="btnNewDocument"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition modal-open">
                    + New Document
                </button>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl shadow">
                <table id="assignedToYouDocumentTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Control Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Document Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Label</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Subject</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Origin Office</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Destination Office</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Due Date</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Duration</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Date Uploaded</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Confidentiality</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Status</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
                        <!-- Rows will be inserted dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-5">
            <h2 class="text-lg font-semibold text-gray-700  dark:text-white mb-5">
                All Documents
            </h2>
            <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl shadow">
                <table id="allDocumentTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Control Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Document Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Label</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Subject</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Origin Office</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Destination Office</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Due Date</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Duration</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Date Uploaded</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Confidentiality</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Status</span>
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
<script>
    (function() {

        initdocumentcontroller();


    })();
</script>
