{{-- Modal --}}


<div id="NewBookingModal" class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-4 modal">

    <div class="bg-white  rounded-2xl shadow-2xl w-full lg:max-w-[80vw] max-h-[90vh overflow-y-auto">
        {{-- Header --}}
        <div class="w-full p-5 flex flex-justify-between text-black">

            <p class="text-xl font-semibold">Title Header</p>

        </div>
        {{-- Contents --}}
        <div class="max-h-[70vh] overflow-y-auto space-y-5 p-5">
            Contents

        </div>
        {{-- Footer  --}}
        <div class="border-t border-gray-200 px-6 py-4 mt-auto flex justify-end gap-3">
            <button id="proceedBtn"
                class=" bg-blue-500 border border-gray-300  text-gray-200 hover:bg-blue-800 dark:hover:bg-blue-800 px-5 py-2 rounded-lg text-sm font-medium">
                Proceed
            </button>
            <button
                class="modal-close border border-gray-300  text-gray-700  hover:bg-gray-100 dark:hover:bg-gray-800 px-5 py-2 rounded-lg text-sm font-medium">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    //to open the modal you must put this code inside the trigger button's click function.
    initModal({
        modalId: "DocumentModal",
    });
</script>



{{-- toast --}}


{{-- button --}}
<button id="buttonID"
    class="modal-close border border-gray-300 dark:border-gray-700 text-gray-700 bg-blue-500 dark:text-gray-200 hover:bg-blue-800 dark:hover:bg-blue-800 px-5 py-2 rounded-lg text-sm font-medium">
    submit
</button>
<button
    class="modal-close border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 px-5 py-2 rounded-lg text-sm font-medium">
    Cancel
</button>
<script>
    // submit button function
    const submitBtn = document.getElementById("buttonID");


    if (!submitBtn.dataset.listenerAttached) {
        submitBtn.addEventListener("click", function() {
            console.log("submit button clicked");

            showMessage({
                status: "success",
                title: "success",
                message: "submit button clicked",
            });
        });

        submitBtn.dataset.listenerAttached = "true";
    }
</script>


{{-- table --}}
<table id="reportsdocumentsTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
        <tr>
            <th>Control #</th>
            <th>Document Code</th>
            <th>Label</th>
            <th>Subject</th>
            <th>Origin Office</th>
            <th>Destination Office</th>
            <th>Due Date</th>
            <th>Duration</th>
            <th>Date Uploaded</th>
            <th>Confidentiality</th>
            <th>Status</th>

            <th>User</th>
            <th>Recipient</th>
            <th>Document Form</th>
            <th>Document Type</th>
            <th>Date of Document</th>
            <th>Signatory</th>
            <th>Confirmed By</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
    </tbody>
</table>

<script>
    //you must call this to initialize the datatable inside a loaded content
    initDataTables();
</script>



{{-- custom toast --}}
{{-- you must add this to the main layout so the script will work --}}
<div id="globalMessageContainer"
    class="fixed top-16 left-1/2 transform -translate-x-1/2 z-50 flex flex-col items-center pointer-events-none">
</div>
<script>
    //to trigger toast use this
    showMessage({
        status: "success",
        message: "User created successfully",
    });
</script>
