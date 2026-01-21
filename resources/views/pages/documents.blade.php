<div class="max-h-screen max-w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 p-5 rounded-lg">


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
                <table id="assignedToYouDocumentTable"
                    class="w-full text-sm text-left text-gray-700 dark:text-gray-300 table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Control Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Document Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Label</span>
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
                            <th class="px-4 py-6">
                                <span class="inline-flex items-center">Due Date</span>
                            </th>
                            <th class="px-4 py-6">
                                <span class="inline-flex items-center">Duration</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Date Uploaded</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Confidentiality</span>
                            </th>
                            <th class="px-4 py-3">
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
        <div class="mb-5">
            <h2 class="text-lg font-semibold text-gray-700  dark:text-white mb-5">
                All Documents
            </h2>
            <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl shadow">
                <table id="allDocumentTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Control Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Document Number</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Label</span>
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
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Due Date</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Duration</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Date Uploaded</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="inline-flex items-center">Confidentiality</span>
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


    <div id="modalNewDocument"
        class="fixed inset-0 bg-black/40 inline-flex items-center justify-center z-50 hidden modal p-5">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl p-6 overflow-y-auto max-h-[90vh]">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 text-black">
                    <div class="space-y-4">
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
                            <textarea id="remarks" class="no-special-chars w-full border-gray-300 rounded-lg px-3 py-2"></textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Origin Office</label>
                            <select id="originOffice" class="w-full border-gray-300 rounded-lg px-3 py-2 officeSelect"
                                required>
                                <option>Select...</option>
                            </select>
                            <div id="otheroriginofficetb" class="hidden">
                                <label class="text-sm text-gray-600">Specify Office</label>
                                <input id="otheroriginoffice" type="text"
                                    class="w-full border-gray-300 rounded-lg px-3 py-2" required />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Destination Office</label>
                            <select id="destinationOffice"
                                class="w-full border-gray-300 rounded-lg px-3 py-2 officeSelect" required>
                                <option>Select...</option>
                            </select>
                            <div id="otherdestinationofficetb" class="hidden">
                                <label class="text-sm text-gray-600">Specify Office</label>
                                <input id="otherdestinationoffice" type="text"
                                    class="w-full border-gray-300 rounded-lg px-3 py-2" required />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Document Type</label>

                            <select id="documentType"
                                class="docTypeSelect w-full border-gray-300 rounded-lg px-3 py-2" required>
                            </select>
                            <div id="otherdoctypetb" class="hidden">
                                <label class="text-sm text-gray-600">Specify Document</label>
                                <input id="otherdocument" type="text"
                                    class="w-full border-gray-300 rounded-lg px-3 py-2" required />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Document Date</label>
                            <input id="document_date" type="date"
                                class="w-full border-gray-300 rounded-lg px-3 py-2" />
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Due Date</label>
                            <input id="due_date" type="date"
                                class="w-full border-gray-300 rounded-lg px-3 py-2" />
                        </div>
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
        initdocumentcontroller();
        filllabeldropdown();
    })();
</script>
