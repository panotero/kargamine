<x-modal id="proposalModal">

    <div class="p-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center">
        <div class="flex flex-col">
            <div class="flex gap-2">
                <p class="text-lg font-semibold text-zinc-900 dark:text-white" id="proposalModalCode">Proposal Code</p>
                <div class="items-center flex  flex-col">

                    <div class="px-3 my-auto text-sm rounded-full" id="proposalModalStatus">
                        status
                    </div>
                </div>
            </div>
            <div class="flex gap-2 text-md text-zinc-500 dark:text-zinc-400">
                some info
            </div>
        </div>
    </div>

    <div class="max-h-[60vh] overflow-auto">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <div
                class="col-span-1 border-r border-zinc-200 dark:border-zinc-700 flex flex-col divide-y divide-zinc-100 dark:divide-zinc-800">

                <!-- Lead Information -->
                <div class="p-4 flex flex-col gap-1">

                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest mb-2">Lead Information</p>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Company
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadCompanyName">
                            -</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Company
                            Address
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="leadCompanyAddress">
                            -</p>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Contact
                            Name
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadContactName">
                            -</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Email
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadEmail">-</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Mobile
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadMobile">-</p>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">
                            Signatory
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadSignatory">
                            -</p>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">
                            Signatory Position
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="leadSignatoryPosition">
                            -</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Source
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadSource">-</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Est.
                            Value</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="leadEstimatedValue">-</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Date
                            Added</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="leadCreatedAt">-
                        </p>
                    </div>

                    <div class="flex justify-between items-center py-2.5">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Close
                            Date</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="leadExpectedCloseDate">-</p>
                    </div>

                </div>

                <!-- Proposal Information -->
                <div class="p-4 flex flex-col gap-1">

                    <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest mb-2">Proposal Information
                    </p>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Code
                        </p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right" id="proposalCode">-
                        </p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">
                            Proposed By</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="proposalProposedBy">-</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5 border-b border-zinc-100 dark:border-zinc-800">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Date
                            Proposed</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="proposalCreatedAt">-</p>
                    </div>

                    <div class="flex justify-between items-center py-2.5">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest w-36 shrink-0">Date
                            Edited</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 text-right"
                            id="proposalUpdatedAt">-</p>
                    </div>

                </div>

            </div>
            <div class="col-span-2 p-2">
                <div class="flex justify-between items-center p-2">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-widest mb-2">Rates</p>

                    <div class="h-full items-center">
                        <button id="addRateBtn" class="px-4 bg-orange-600 rounded-lg text-white"> <b
                                class="font-black">+</b>
                            Rate</button>
                    </div>

                </div>

                <div class="flex flex-col gap-3 max-h-[50vh] overflow-y-auto pr-1" id="proposedRateContainer">


                    <!-- /Rate Card -->

                </div>
            </div>
        </div>
    </div>

    <div class="flex  justify-between">
        <div class="px-5 py-4 flex items-center gap-2 action-buttons">

            <!-- Approval Buttons -->
            <div class="flex items-center gap-2 approval-buttons  hidden">

                <!-- Approve -->
                <button id="approveBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Approve
                </button>

                <!-- On Hold -->
                <button id="onHoldBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.25 9v6m-4.5 0V9M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    On Hold
                </button>

                <!-- Reject -->
                <button id="rejectBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Reject
                </button>

            </div>


            <!-- Generate Contract -->
            <div class="generate-contract-button hidden">
                <button id="createContractBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-zinc-800 hover:bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-white rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Generate Contract
                </button>
            </div>

        </div>

        <div class="border-t border-zinc-200 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2">

            <button
                class="modal-close rounded-lg border border-zinc-300 dark:border-zinc-700 px-4 py-2 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                Close
            </button>

        </div>
    </div>
</x-modal>
