<div class="max-h-screen overflow-y-auto bg-gray-50 dark:bg-gray-800 text-gray-800 p-5" id="contentDashboard">


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
                <table id="assignedToYouDocumentTable" class="w-full text-sm text-left border-collapse">
                    <thead class=" text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Control Number</th>
                            <th class="px-4 py-3">Document Number</th>
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
                    <tbody class="bg-gray-100 dark:bg-gray-700  dark:text-white">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="h-full">
            <h2 class="text-lg font-semibold text-gray-700  dark:text-white mb-5">
                All Documents
            </h2>
            <div class="overflow-x-auto bg-white dark:bg-gray-800  rounded-xl shadow">
                <table id="allDocumentTable" class="w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Control #</th>
                            <th class="px-4 py-3">Document Number</th>
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
                    <tbody class="bg-gray-100 dark:bg-gray-700 dark:text-white">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="modalNewDocument" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden modal p-5">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
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
                            <textarea id="remarks" class="w-full border-gray-300 rounded-lg px-3 py-2"></textarea>
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
                    class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 modal-close">
                    Cancel
                </button>
                <button class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 submitbtn">
                    Submit
                </button>
            </div>
        </div>
    </div>
    <div id="DocumentModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 px-4 modal">

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh overflow-y-auto">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    Document Control Number: <span id="docControlNumber">DCN-0001</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Status: <span id="docStatus" class="font-medium text-blue-600 dark:text-blue-400">Active</span>
                </p>
            </div>
            <div
                class="max-h-[60vh] overflow-y-auto flex flex-col lg:flex-row divide-y lg:divide-y-0 lg:divide-x divide-gray-200 dark:divide-gray-700">
                <div class="w-full lg:w-1/2 p-6 space-y-4">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">Document Metadata</h3>
                    <div class="space-y-2 text-md">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Document Number:</span>
                            <span id="docCode" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Subject:</span>
                            <span id="docTitle" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Origin Office:</span>
                            <span id="docDept" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Created By / User ID:</span>
                            <span id="docSignatory" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Type:</span>
                            <span id="docType" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Confidentiality:</span>
                            <span id="docConfidentiality" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Document Date:</span>
                            <span id="docDate" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Due Date:</span>
                            <span id="docDueDate" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span id="docStatus" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Remarks:</span>
                            <span id="docRemarks" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Uploaded At:</span>
                            <span id="created_at" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Date Received:</span>
                            <span id="date_received" class="text-gray-900 dark:text-gray-100"></span>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 p-6 space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">File Versions</h3>
                        <a id="downloadLatestBtn" download
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">
                            Download Latest
                        </a>
                    </div>
                    <div class="space-y-2">
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-lg max-h-48 lg:h-48 overflow-y-auto">
                            <ul id="fileVersionsList"
                                class="divide-y divide-gray-200 dark:divide-gray-700 flex flex-col-reverse">
                                <div id="spinner" class="flex items-center justify-center">
                                    <div class="w-10 h-10 border-2 border-gray-200 border-t-2 border-t-gray-800 rounded-full animate-spin"
                                        role="status" aria-label="Loading"></div>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-3">Activity History</h3>
                        <ul id="activityLog" class="space-y-2 max-h-48 lg:h-48 overflow-y-auto ">
                            <div id="spinner" class="flex items-center justify-center">
                                <div class="w-10 h-10 border-2 border-gray-200 border-t-2 border-t-gray-800 rounded-full animate-spin"
                                    role="status" aria-label="Loading"></div>
                            </div>
                        </ul>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 relative">
                        <div class="flex items-center justify-between">
                            <button id="toggleFullLogBtn"
                                class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12s-4 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor"
                                        stroke-width="1.5" />
                                </svg>
                            </button>
                        </div>

                        <div id="fullActivityLogContainer"
                            class="hidden absolute bottom-full left-0 w-full border border-gray-300 dark:border-gray-700
               rounded-lg p-3 bg-white dark:bg-gray-800 shadow-xl z-50">

                            <h4 class="text-md font-medium mb-2 text-gray-700 dark:text-gray-200">
                                Full Activity Log
                            </h4>

                            <ul id="fullActivityLog" class="space-y-2 max-h-60 overflow-y-auto">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 mt-auto flex justify-end gap-3">

                <button id="btnConfirm"
                    class="hidden bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Confirm Receipt
                </button>
                <button id="routeDocumentBtn"
                    class="hidden bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium routeBtn">
                    Route Document
                </button>
                <div class="approvalButtons hidden" id="approvalButtons">
                    <button id="modalApproveBtn"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto modal-open">
                        Approve
                    </button>

                    <button id="modalDisapproveBtn"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">
                        Disapprove
                    </button>

                    <button id="modalRequestDiscussionBtn"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">
                        Request for Discussion
                    </button>
                </div>
                <button
                    class="modal-close border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 px-5 py-2 rounded-lg text-sm font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <div id="pdfPreviewModal"
        class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 px-4 modal">

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-5xl h-[90vh] flex flex-col">
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 px-6 py-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">PDF Preview</h3>

                <button
                    class="modal-close border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 px-5 py-2 rounded-lg text-sm font-medium">
                    Cancel
                </button>
            </div>
            <div class="flex flex-1 overflow-hidden">
                <div
                    class="w-2/3 flex items-center justify-center relative border-r border-gray-200 dark:border-gray-700">

                    <div id="galleryGlide" class="glide w-full max-w-xl mx-auto relative">
                        <div id="galleryLoading"
                            class="absolute inset-0 flex items-center justify-center bg-white/70 hidden z-50">
                            <div
                                class="animate-spin text-black dark:text-gray-200 h-10 w-10 border-4 border-gray-400 border-t-transparent rounded-full">
                            </div>
                        </div>

                        <div class="glide__track" data-glide-el="track">
                            <ul class="glide__slides" id="glideSlides">
                            </ul>
                            <div class="pointer-events-none">
                                <button data-glide-dir="<"
                                    class="slide-previous pointer-events-auto absolute top-1/2 left-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3 hover:bg-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button data-glide-dir=">"
                                    class="slide-next pointer-events-auto absolute top-1/2 right-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3 hover:bg-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/3 p-6 overflow-y-auto">

                    <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">File Information</h4>

                    <div class="space-y-3">

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">File Name</p>
                            <p id="infoFileName" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Document Subject</p>
                            <p id="infoDocSubject" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Date Uploaded</p>
                            <p id="infoDateUploaded" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Uploader</p>
                            <p id="infoUploader" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Uploaded Office</p>
                            <p id="infoUploadedOffice" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Remarks</p>
                            <p id="infoRemarks"
                                class="font-medium text-gray-900 dark:text-gray-100 whitespace-pre-line">—</p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="controlNumberModal" class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50 modal">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-80 max-w-full relative">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Document Created</h2>
            <p id="controlNumberText" class="text-gray-700 dark:text-gray-300 mb-4 text-center text-sm"></p>
            <button
                class="modal-close px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200 w-full">
                Close
            </button>
        </div>
    </div>
    <div id="routingModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 px-4 modal">
        <div
            class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 space-y-6">
            <input type="hidden" id="docId" value="">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Route Document</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Select office and user for routing</p>
            </div>
            <div class="space-y-2">
                <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Select Office</label>
                <select id="routeOfficeSelect"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 officeSelect">
                    <option value="">Loading offices...</option>
                </select>
            </div>
            <div id="internalSection" class="hidden space-y-2">
                <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Select User</label>
                <select required id="routeUserSelect"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Loading users...</option>
                </select>

                <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Select Approval Type</label>
                <select required id="routeApprovalSelect"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Approval Type</option>
                    <option value="pre-approval">Pre-approval</option>
                    <option value="final-approval">Final-approval</option>
                </select>
            </div>
            <div id="externalSection" class="hidden space-y-4">
                <div class="space-y-2">
                    <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Select Status</label>
                    <select id="routeStatusSelect"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Status</option>
                        <option value="approved">Approved</option>
                        <option value="remand">Remand</option>
                    </select>
                </div>
                <div id="pdfUploadSection" class="hidden">

                    <div id="routedropzone"
                        class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex flex-col items-center justify-center p-6 text-gray-500 dark:text-gray-400 cursor-pointer hover:border-blue-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m-4 8h10a2 2 0 002-2V8a2 2 0 00-2-2h-3" />
                        </svg>
                        <p class="text-sm">
                            Drag & drop a PDF file here or
                            <span class="text-blue-600 dark:text-blue-400 underline">click to browse</span>
                        </p>
                        <input type="file" accept="application/pdf" class="" id="routefileInput" />
                    </div>
                    <div id="routefileInfo" class="mt-2 text-sm text-gray-600 dark:text-gray-300"></div>
                    <button id="clearrouteSelectionBtn"
                        class="mt-2 text-xs text-gray-500 hover:text-red-500 transition">Clear Selection</button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Remarks</label>
                <textarea id="routeRemarks"
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                    rows="3" placeholder="Enter remarks..."></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button
                    class="modal-close px-5 py-2 rounded-lg text-sm bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button id="routeSubmitBtn"
                    class="px-5 py-2 rounded-lg text-sm bg-blue-600 text-white hover:bg-blue-700 transition">
                    Submit
                </button>
            </div>
        </div>
    </div>

</div>
<script>
    (function() {

        initdocumentcontroller();

    })();
</script>
