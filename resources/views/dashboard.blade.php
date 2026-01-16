<x-app-layout>
    <div class="flex h-screen dark:bg-gray-900">
        <aside id="sidebar-wrapper"
            class="bg-white dark:bg-gray-900 shadow-lg w-64
           fixed lg:static inset-y-0 left-0
           h-full flex flex-col
           transform -translate-x-full lg:translate-x-0
           transition-transform duration-300
           z-40">
            <div class="w-full p-5 dark:text-white">
                <h1 class="font-semibold text-sm">ODDG-PP</h1>
                <h1 class="text-md md:text-md font-bold">Document Monitoring Tool</h1>
            </div>

            <div class="w-full flex flex-col justify-between flex-grow">
                <nav id="sidebar-menu" class="p-4 space-y-2 text-black dark:text-white font-semibold">
                </nav>

                <div class="w-full p-5 flex justify-center dark:bg-gray-800 gap-3">
                    <img class="h-10 w-auto" src="{{ asset('/assets/images/TESDA_Logo.png') }}">
                    <img class="h-12 w-auto" src="{{ asset('/assets/images/bagong_pilipinas.png') }}">
                    <img class="h-5 w-auto my-auto" src="{{ asset('/assets/images/tesda_kayang_kaya.png') }}">
                </div>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 lg:hidden"></div>

        <div class="flex-1 flex flex-col min-w-0">
            <button id="sidebar-toggle"
                class="lg:hidden absolute top-2 left-2  px-2 py-4 bg-gray-800 text-white rounded z-10">
                ☰
            </button>
            <header class="bg-white dark:bg-gray-800 shadow px-6 py-4 flex justify-between items-center">
                <h2 id="page-title" class="text-xl font-semibold text-gray-800 dark:text-gray-200 max-lg:pl-5">
                    Dashboard
                </h2>

                <div class="flex items-center space-x-4">
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" id="notificationIcon"
                            class="relative p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                            <svg class="h-6 w-6 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <span id="notifcount"
                                class="absolute top-0 right-0 block h-5 w-5 text-white rounded-full text-sm bg-red-500"></span>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="
    fixed lg:absolute
    inset-x-4 top-20 lg:inset-auto lg:right-0 lg:top-auto
    lg:mt-2
    w-auto lg:w-96
    max-h-[32rem]
    overflow-y-auto
    rounded-xl shadow-lg
    bg-white dark:bg-gray-800
    border border-gray-200 dark:border-gray-700
    z-50
"
                            style="display: none;" id="notificationWrapper">
                            <div
                                class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Notifications</h3>
                                <button class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Mark all as
                                    read</button>
                            </div>
                            <div id="notificationsContainer" class="divide-y divide-gray-200 dark:divide-gray-700">


                            </div>
                            <div class="text-center py-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                    See all notifications
                                </a>
                            </div>
                        </div>

                    </div>
                    <x-dropdown align="right" width="w-auto">
                        <x-slot name="trigger">
                            <button class="text-gray-800 dark:text-gray-200">
                                <div class="flex flex-col items-center justify-center">
                                    @if (Auth::user()->profile_photo_url)
                                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-gray-400 text-white flex items-center justify-center font-semibold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>
            <main id="content" class="flex-1 w-full overflow-y-auto  text-gray-800 dark:text-gray-200">
            </main>

            <div id="DocumentModal"
                class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-4 modal">

                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh overflow-y-auto">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            Document Control Number: <span id="docControlNumber">DCN-0001</span>
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Status: <span id="docStatus"
                                class="font-medium text-blue-600 dark:text-blue-400">Active</span>
                        </p>
                    </div>
                    <div
                        class="max-h-[60vh] overflow-y-auto flex flex-col lg:flex-row divide-y lg:divide-y-0 lg:divide-x divide-gray-200 dark:divide-gray-700">
                        <div class="w-full lg:w-1/2 p-6 space-y-4">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100">Document Metadata</h3>
                            <div class="space-y-2 text-md">

                                <div class="flex justify-between hidden">
                                    <span class="text-gray-600 dark:text-gray-400">Document ID:</span>
                                    <span id="document_id" class="text-gray-900 dark:text-gray-100"></span>
                                </div>
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
                                    <span class="text-gray-600 dark:text-gray-400">Destination Office:</span>
                                    <span id="docDest" class="text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Uploaded by:</span>
                                    <span id="docUploadBy" class="text-gray-900 dark:text-gray-100"></span>
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
                                    <span class="text-gray-600 dark:text-gray-400">Remarks:</span>
                                    <span id="docRemarks" class="text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Uploaded At:</span>
                                    <span id="created_at" class="text-gray-900 dark:text-gray-100"></span>
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

                                <!-- Make THIS the positioning context -->
                                <div class="flex justify-between w-full relative">

                                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-3">
                                        Activity History
                                    </h3>

                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">

                                        <div class="flex justify-between w-full relative">
                                            <button id="toggleFullLogBtn"
                                                class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M1.5 12s4-7.5 10.5-7.5S22.5 12 22.5 12s-4 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                                    <circle cx="12" cy="12" r="3" stroke="currentColor"
                                                        stroke-width="1.5" />
                                                </svg>
                                            </button>
                                        </div>
                                        {{-- BUG ID:  --}}
                                        <!-- DROPDOWN -->
                                        <div id="fullActivityLogContainer"
                                            class="hidden absolute z-50
           bottom-full mb-2
           left-1/2 -translate-x-1/2
           sm:left-auto sm:right-0 sm:translate-x-0
           w-[90vw] sm:w-96
           border border-gray-300 dark:border-gray-700
           rounded-lg p-3 bg-white dark:bg-gray-800 shadow-xl">

                                            <h4 class="text-md font-medium mb-2 text-gray-700 dark:text-gray-200">
                                                Full Activity Log
                                            </h4>

                                            <ul id="fullActivityLog" class="space-y-2 max-h-60 overflow-y-auto"></ul>
                                        </div>

                                    </div>
                                </div>

                                <ul id="activityLog" class="space-y-2 max-h-48 lg:h-48 overflow-y-auto">
                                    <div id="spinner" class="flex items-center justify-center">
                                        <div class="w-10 h-10 border-2 border-gray-200 border-t-2 border-t-gray-800 rounded-full animate-spin"
                                            role="status" aria-label="Loading"></div>
                                    </div>
                                </ul>

                            </div>


                        </div>
                    </div>
                    <div
                        class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 mt-auto flex justify-end gap-3">

                        <button id="btnConfirm"
                            class="hidden bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                            Confirm Receipt
                        </button>
                        <button id="routeDocumentBtn"
                            class="hidden bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium routeBtn">
                            Route Document
                        </button>
                        <div class="eSignAction hidden" id="eSignAction">
                            <button id="eSignBtn"
                                class=" bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium eSignBtn">
                                eSign
                            </button>

                        </div>
                        <div class="approvalButtons hidden" id="approvalButtons">
                            <button id="modalApproveBtn"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto modal-open">
                                Approve
                            </button>

                            <button id="modalDisapproveBtn"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">
                                Disapprove
                            </button>

                            <button id="modalForDiscussionBtn"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto">
                                Request for Discussion
                            </button>
                        </div>
                        <div class="hidden" id="revisionButtons">

                            <button id="modalrevisionBtn"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium w-full sm:w-auto modal-open">
                                Revise
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

                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-5xl h-auto lg:flex lg:flex-col">
                    <div
                        class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 px-6 py-3">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">PDF Preview</h3>

                        <button
                            class="modal-close border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 px-5 py-2 rounded-lg text-sm font-medium">
                            Close
                        </button>
                    </div>
                    <div class="lg:flex w-full max-h-[70vh] overflow-y-auto">
                        <div
                            class="w-full flex items-center justify-center relative border-r border-gray-200 dark:border-gray-700">
                            <div id="galleryLoading"
                                class="absolute inset-0 flex items-center justify-center bg-white/70 hidden z-50">
                                {{-- <div
                                    class="animate-spin text-black dark:text-gray-200 h-10 w-10 border-4 border-gray-400 border-t-transparent rounded-full">
                                </div> --}}
                            </div>
                            {{-- swiper carousel --}}

                            <button
                                class="swiper-button-prev slide-previous absolute top-1/2 left-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3">

                            </button>

                            <button
                                class="swiper-button-next slide-next absolute top-1/2 right-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3">

                            </button>
                            <div id="gallerySwiper" class="swiper w-full max-w-xl mx-auto relative">
                                <div class="swiper-wrapper h-[80vh]" id="swiperSlides"></div>


                                <div class="swiper-pagination"></div>
                            </div>


                            {{-- glide carousel --}}
                            {{-- <div id="galleryGlide" class="glide w-full max-w-xl mx-auto relative">
                                <div id="galleryLoading"
                                    class="absolute inset-0 flex items-center justify-center bg-white/70 hidden z-50">
                                    <div
                                        class="animate-spin text-black dark:text-gray-200 h-10 w-10 border-4 border-gray-400 border-t-transparent rounded-full">
                                    </div>
                                </div>

                                <div class="glide__track h-[80vh] bg-gray-100" data-glide-el="track">
                                    <ul class="glide__slides h-full" id="glideSlides">
                                    </ul>
                                    <div class="pointer-events-none">
                                        <button data-glide-dir="<"
                                            class="slide-previous pointer-events-auto absolute top-1/2 left-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3 hover:bg-white transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button data-glide-dir=">"
                                            class="slide-next pointer-events-auto absolute top-1/2 right-3 -translate-y-1/2 bg-white/80 border border-gray-300 shadow rounded-full p-3 hover:bg-white transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>

                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="w-1/3 p-6 overflow-y-auto hidden">

                            <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">File Information
                            </h4>

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
                                    <p id="infoDateUploaded" class="font-medium text-gray-900 dark:text-gray-100">—
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Uploader</p>
                                    <p id="infoUploader" class="font-medium text-gray-900 dark:text-gray-100">—</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Uploaded Office</p>
                                    <p id="infoUploadedOffice" class="font-medium text-gray-900 dark:text-gray-100">—
                                    </p>
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
            <div id="reviseModal"
                class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden modal p-5">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl p-6 overflow-y-auto max-h-[90vh]">
                    <div class="max-h-[60vh] overflow-y-auto p-3 ">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="space-y-4">

                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    Document Control Number: <span id="revisedocControlNumber">DCN-0001</span>
                                </h2>
                            </div>
                        </div>

                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Upload Revised Document</h2>
                        <div id="modalErrorMessage"
                            class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                            <ul id="modalErrorList" class="list-disc list-inside"></ul>
                        </div>
                        <div id="revisedropzone"
                            class="border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center p-6 text-gray-500 cursor-pointer hover:border-blue-400 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m-4 8h10a2 2 0 002-2V8a2 2 0 00-2-2h-3" />
                            </svg>
                            <p class="text-sm">
                                Drag & drop a PDF file here or
                                <span class="text-blue-600 underline">click to browse</span>
                            </p>
                            <input type="file" accept="application/pdf" class="hidden" id="revisefileInput"
                                required />
                        </div>
                        <p id="revisefileInfo" class="text-sm text-gray-600 mt-3 text-center"></p>
                        <button id="reviseclearSelectionBtn"
                            class="mt-3 bg-gray-200 px-3 py-1 rounded hidden hover:bg-gray-300 transition">Clear</button>

                    </div>
                    <div class="flex justify-end mt-8 space-x-3">
                        <button id="btnCancelModal"
                            class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 modal-close">
                            Cancel
                        </button>

                        <button id="submitrevisionBtn"
                            class="px-5 py-2 rounded-lg text-sm bg-blue-600 text-white hover:bg-blue-700 transition">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
            <div id="controlNumberModal"
                class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50 modal">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-80 max-w-full relative">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Document Created</h2>
                    <p id="controlNumberText" class="text-gray-700 dark:text-gray-300 mb-4 text-center text-sm"></p>
                    <button
                        class="modal-close px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200 w-full">
                        Close
                    </button>
                </div>
            </div>
            <div id="routingModal"
                class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 px-4 modal z-50">
                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 space-y-6">
                    <input type="hidden" id="docId" value="">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Route Document</h2>

                        <div id="modalErrorMessage"
                            class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm errorbox">
                            <ul id="routingmodalErrorList" class="list-disc list-inside errorlist"></ul>
                        </div>
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

                        <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Select Approval
                            Type</label>
                        <select required id="routeApprovalSelect"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Approval Type</option>
                            <option value="pre-approval">Pre-approval</option>
                            <option value="final-approval">Final-approval</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Remarks</label>
                        <textarea id="routeRemarks"
                            class="no-special-chars w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
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


            <div id="esignModal"
                class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/50 px-4 modal z-50">
                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 space-y-6">
                    <input type="hidden" id="docId" value="">
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">eSign Document</h2>

                        <div id="modalErrorMessage"
                            class="hidden mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm errorbox">
                            <ul id="esignmodalErrorList" class="list-disc list-inside errorlist"></ul>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Select office and user for routing</p>
                    </div>
                    <div id="" class=" space-y-4">
                        <div id="pdfUploadSection" class="">

                            <div id="esigndropzone"
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
                                <input type="file" accept="application/pdf" class="" id="esignfileInput" />
                            </div>
                            <div id="esignfileInfo" class="mt-2 text-sm text-gray-600 dark:text-gray-300"></div>
                            <button id="clearesignSelectionBtn"
                                class="mt-2 text-xs text-gray-500 hover:text-red-500 transition">Clear
                                Selection</button>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-gray-700 dark:text-gray-300 font-medium text-sm">Remarks</label>
                        <textarea id="esignRemarks"
                            class="no-special-chars w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                            rows="3" placeholder="Enter remarks..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button
                            class="modal-close px-5 py-2 rounded-lg text-sm bg-gray-100 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button id="submitesignBtn"
                            class="px-5 py-2 rounded-lg text-sm bg-blue-600 text-white hover:bg-blue-700 transition">
                            Submit
                        </button>
                    </div>
                </div>
            </div>



            <div id="approvalModal"
                class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/30 px-4 sm:px-6 modal">

                <div
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 relative">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Approval Details</h2>

                    <input type="hidden" id="approval_id">
                    <div id="finalApproval" class=" m-5 hidden">
                        <h1>Confirm your approval please</h1>
                    </div>
                    <div id="preApproval">
                        <div id="userSelectWrapper" class="mb-5">
                            <label for="userSelect" class="block text-gray-700 font-medium mb-2">Select User</label>
                            <select id="userSelect"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            </select>
                        </div>

                    </div>
                    <div id="remarksWrapper" class="mb-5">
                        <label for="remarksTextarea" class="block text-gray-700 font-medium mb-2">Remarks</label>
                        <textarea id="remarksTextarea"
                            class="no-special-chars w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            rows="4" placeholder="Enter remarks..."></textarea>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <button id="confirmApprovalBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg w-full sm:w-auto">
                            Confirm
                        </button>
                        <button
                            class="modal-close w-full sm:w-auto border border-gray-200 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
            <div id="disapprovalModal"
                class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/30 px-4 sm:px-6 modal">

                <div
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 relative">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Disapproval Details</h2>

                    <input type="hidden" id="approval_id">
                    <div id="finalApproval" class=" m-5 hidden">
                        <h1>Confirm your disapproval please</h1>
                    </div>
                    <div id="remarksWrapper" class="mb-5">
                        <label for="remarksTextarea"
                            class="block text-gray-700 font-medium mb-2">Remarks/Instructions</label>
                        <textarea id="remarksTextarea"
                            class="no-special-chars w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            rows="4" placeholder="Enter remarks..."></textarea>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <button id="confirmDisapprovalBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg w-full sm:w-auto">
                            Confirm
                        </button>
                        <button
                            class="modal-close w-full sm:w-auto border border-gray-200 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
            <div id="forDiscussionModal"
                class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/30 px-4 sm:px-6 modal">

                <div
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 relative">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">Requestion For Discussion Details</h2>

                    <input type="hidden" id="approval_id">
                    <div id="finalApproval" class=" m-5 hidden">
                        <h1>Confirm your request for discussion</h1>
                    </div>
                    <div id="remarksWrapper" class="mb-5">
                        <label for="remarksTextarea"
                            class="block text-gray-700 font-medium mb-2">Remarks/Instructions</label>
                        <textarea id="remarksTextarea"
                            class="no-special-chars w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-300"
                            rows="4" placeholder="Enter remarks..."></textarea>
                    </div>


                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <button id="confirmForDiscussionBtn"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg w-full sm:w-auto">
                            Submit
                        </button>
                        <button
                            class="modal-close w-full sm:w-auto border border-gray-200 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
