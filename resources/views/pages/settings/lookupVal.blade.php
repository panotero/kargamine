<div class="container mx-auto min-h-screen">
    <div class="p-5 space-y-3">

        <div class="w-full flex gap-5">
            <button class="px-5 py-3 bg-orange-400 hover:bg-orange-800  text-white font-semibold rounded-lg"
                id="newOptionButton">
                Add New Option
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5" id="optionsContainer">
        </div>
    </div>
</div>

<div id="NewOptionModal" class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-4 modal">

    <div
        class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full lg:max-w-[50vw] max-h-[90vh] overflow-hidden flex flex-col">

        {{-- Header --}}
        <div
            class="w-full p-5 flex justify-between items-center border-b border-gray-200 dark:border-zinc-700 text-black dark:text-white">

            <p class="text-xl font-semibold">
                Add New Option
            </p>

        </div>

        {{-- Contents --}}
        <div class="max-h-[70vh] overflow-y-auto p-5 space-y-6">

            {{-- Option Information --}}
            <div class="border border-gray-200 dark:border-zinc-700 rounded-xl p-5 space-y-4">

                <p class="font-semibold text-lg dark:text-white">
                    Option Information
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">
                            Option Name
                        </label>

                        <input type="text" name="OptionName" id="OptionName"
                            class="border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm dark:text-white">
                            Option Description
                        </label>

                        <input type="text" name="OptionDescription" id="OptionDescription"
                            class="border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
                    </div>

                </div>

            </div>

            {{-- LOV Section --}}
            <div class="border border-gray-200 dark:border-zinc-700 rounded-xl p-5 space-y-4">

                <div class="flex justify-between items-center">

                    <p class="font-semibold text-lg dark:text-white">
                        List of Values
                    </p>

                    <button id="addLovButton"
                        class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium">
                        + Add LOV
                    </button>

                </div>

                <div id="lovContainer" class="space-y-3">
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 dark:border-zinc-700 px-6 py-4 flex justify-end gap-3">

            <button id="SaveOption"
                class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                Submit
            </button>

            <button
                class="modal-close border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-600 px-5 py-2 rounded-lg text-sm font-medium">
                Cancel
            </button>

        </div>

    </div>
</div>
<div id="addLOVModal" class="fixed inset-0 hidden z-40 flex items-center justify-center bg-black/50 px-4 modal">

    <div
        class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl w-full lg:max-w-[40vw] max-h-[90vh] overflow-hidden flex flex-col">

        {{-- Header --}}
        <div
            class="w-full p-5 flex justify-between items-center border-b border-gray-200 dark:border-zinc-700 text-black dark:text-white">

            <p class="text-xl font-semibold">
                Add List of Value
            </p>

        </div>

        {{-- Contents --}}
        <div class="max-h-[70vh] overflow-y-auto p-5 space-y-6">

            <input type="hidden" name="OptionID" id="OptionID">



            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="flex flex-col">
                    <label class="text-sm dark:text-white">
                        LOV Code
                    </label>

                    <input type="text"
                        class="add-lov-code border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
                </div>

                <div class="flex flex-col">
                    <label class="text-sm dark:text-white">
                        LOV Name
                    </label>

                    <input type="text"
                        class="add-lov-name border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
                </div>

            </div>

            <div class="flex flex-col">
                <label class="text-sm dark:text-white">
                    LOV Description
                </label>

                <input type="text"
                    class="add-lov-description border p-2 rounded-lg dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">
            </div>


        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 dark:border-zinc-700 px-6 py-4 flex justify-end gap-3">

            <button id="SaveLOV"
                class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium">
                Submit
            </button>

            <button
                class="modal-close border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-600 px-5 py-2 rounded-lg text-sm font-medium">
                Cancel
            </button>

        </div>

    </div>
</div>

<script>
    (function() {

        async function loadOptions() {

            const options = await apiCall({
                mode: "GET",
                url: "/api/options"
            });
            // const options = await apiJSONGET('api/options');
            const optionsContainer = document.getElementById("optionsContainer");
            optionsContainer.innerHTML = "";

            //build table row
            options.forEach((option) => {
                let lovCount = 0;
                const tableRows = [];
                const lovData = option.values;
                lovData.forEach((lov) => {
                    lovCount++;
                    const rowdata = `
                            <tr>
                                <td class="px-4 py-2 text-blue-600 font-semibold uppercase">${lov.lov_code}</td>
                                <td class="px-4 py-2">${lov.lov_name}</td>
                                <td class="px-4 py-2">${lov.lov_description}</td>
                                <td class="px-4 py-2 flex justify-end">
                                    <button data-option-id="${lov.lov_optionId}" data-lov-id="${lov.lov_id}" type="button"
                                        class="delete-btn remove-lov  aspect-square p-2 bg-red-500 text-white rounded font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 7h12m-1 0-1 14a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2L7 7m3 0V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>`;
                    tableRows.push(rowdata);

                });

                const optionsHTML = `

            <div class=" overflow-auto bg-white rounded-lg text-black border-2 border-slate-100 drop-shadow-md">
                <div class="w-full max-h-[50vh] overflow-auto">
                {{-- content header --}}
                <div class="w-full px-5 py-2 bg-gradient-to-r from-orange-50 to-white flex justify-between gap-3">
                    <div class="flex w-full">
                        <div class="p-2 hidden">
                            <div class="p-4 border border-gray-700 drop-shadow-md rounded-md">
                            </div>
                        </div>
                        <div>

                        <p class="text-xl font-bold">${option.option_name}</p>

                        <p class="text-xs text-blue-400">${option.option_description}</p>
                        </div>
                        <div class="p-2 flex flex-col justify-center">
                            <div class="w-7 h-auto aspect-square flex items-center justify-center rounded-full bg-orange-300 text-orange-800 shadow-md text-sm font-bold">
                                ${lovCount}
                            </div>
                        </div>
                        <div class="p-2 flex flex-col justify-center">
                            <button data-option-id="${option.option_id}"  type="button"
                                        class="delete-option remove-lov  aspect-square p-2 bg-red-500 text-white rounded font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 7h12m-1 0-1 14a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2L7 7m3 0V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                        </div>

                    </div>
                    <div class="w-full flex items-center justify-end">
                        <button data-option-id="${option.option_id}" class="add-btn flex px-4 py-2 bg-orange-400 hover:bg-orange-800 text-sm text-white font-semibold rounded-lg">Add New
                        </button>
                    </div>
                </div>
                {{-- conntent like table --}}
                <div class="w-full rounded-lg overflow-hidden p-3 ">
                    <table class="w-full text-sm text-left text-black border-collapse responsive-table rounded-lg">
                        <thead >
                            <tr >
                                <th class="px-4 py-3">Code</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3 flex justify-end">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-100">
                        ${tableRows.join('')}

                        </tbody>
                    </table>
                </div>

                    </div>
            </div>`;


                // optionsContainer.appendChild(optionsHTML);
                optionsContainer.insertAdjacentHTML('beforeend', optionsHTML);



            });
            initDataTables(5);

        }
        loadOptions();



        const newOptionButton = document.getElementById('newOptionButton');
        const SaveOption = document.getElementById('SaveOption');
        const lovContainer = document.getElementById('lovContainer');
        newOptionButton.addEventListener("click", function() {
            console.log("new button triggered");

            initModal({
                modalId: "NewOptionModal",
            });
        });
        SaveOption.addEventListener("click", async function() {
            console.log("save option button triggered");

            const OptionName = document.getElementById("OptionName").value;
            const OptionDescription = document.getElementById("OptionDescription").value;
            const lovItems = document.querySelectorAll(".lov-item");

            const lovData = [];
            lovItems.forEach(item => {
                const lov_code = item.querySelector(".lov-code")?.value || "";
                const lov_name = item.querySelector(".lov-name")?.value || "";
                const lov_description = item.querySelector(".lov-description")?.value || "";

                lovData.push({
                    lov_code,
                    lov_name,
                    lov_description
                });
            });

            const payload = {
                option_name: OptionName,
                option_description: OptionDescription,
                lovData: lovData,
            };

            console.log("ALL LOV DATA:", payload);


            const response = await apiCall({
                mode: "POST",
                isJson: true,
                payload: payload,
                url: "/api/options",
                button: SaveOption
            });
            // const response = await apiJSONPOST(payload, 'api/options', SaveOption);
            console.log("response: " + response);
            if (!response.success) {

                showMessage({
                    status: "error",
                    title: "Error Saving Options",
                    message: "There is some error saving your information. Please contact system administrator",
                });
                return;
            };

            function clearinputs() {

                // Reset main inputs
                document.getElementById("OptionName").value = "";
                document.getElementById("OptionDescription").value = "";

                // Remove all LOV items
                const lovContainer = document.getElementById("lovContainer");
                lovContainer.innerHTML = "";
            }

            showMessage({
                status: "success",
                title: "Option Saved!",
            });
            clearinputs();

            loadOptions();

            closemodals();

        });


        // 1. ADD NEW LOV BLOCK
        const addLovButton = document.getElementById('addLovButton');
        addLovButton.addEventListener("click", function() {


            const lovTemplate = document.createElement("div");

            lovTemplate.classList.add("lov-item", "flex", "p-2", "rounded", "border", "gap-4", "mb-2",
                "bg-white", "text-black");

            lovTemplate.innerHTML = `
        <div class="flex-1">
            <p>List of Value Code</p>
            <input type="text" class="lov-code w-full border rounded px-2 py-1  text-black">
        </div>
        <div class="flex-1">
            <p>List of Value Name</p>
            <input type="text" class="lov-name w-full border rounded px-2 py-1  text-black">
        </div>

        <div class="flex-1">
            <p>List of Value Description</p>
            <input type="text" class="lov-description w-full border rounded px-2 py-1  text-black">
        </div>

        <div class="flex items-end">
            <button type="button" class="remove-lov  aspect-square p-2 bg-red-500 text-white rounded font-black">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-1 0-1 14a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2L7 7m3 0V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2" />
</svg>
            </button>
        </div>
    `;

            lovContainer.appendChild(lovTemplate);
        });


        // 2. REMOVE LOV BLOCK (event delegation)
        lovContainer.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-lov")) {
                e.target.closest(".lov-item").remove();
            }
        });

        $(document).on('click', '.delete-btn', function() {
            let optionID = $(this).data('optionId');
            let lovID = $(this).data('lovId');
            let deleteButton = this;
            // console.log("option ID: ", optionID, " LOV ID: ", lovID);
            // return;

            deleteLOV(optionID, lovID, deleteButton);
        });


        $(document).on('click', '.delete-option', function() {
            let optionID = $(this).data('optionId');
            let deleteButton = this;

            deleteOption(optionID, deleteButton);
        });

        $(document).on('click', '.add-btn', function() {
            let optionID = $(this).data('optionId');

            addLOV(optionID);
        });

        function addLOV(optionID) {

            initModal({
                modalId: "addLOVModal",
            });
            const OptionID = document.getElementById("OptionID");
            OptionID.value = optionID;
        }
        const submitaddLOV = document.getElementById("SaveLOV");

        submitaddLOV.addEventListener("click", async function() {

            const addLOVCode = document.querySelector('.add-lov-code').value;
            const addLOVName = document.querySelector(".add-lov-name").value;
            const addLOVDescription = document.querySelector(".add-lov-description").value;
            const OptionID = document.getElementById("OptionID").value;
            const payload = {
                addLOVCode: addLOVCode,
                addLOVName: addLOVName,
                addLOVDescription: addLOVDescription,
                optionID: OptionID,
            };


            const response = await apiCall({
                mode: "POST",
                isJson: true,
                payload: payload,
                url: "/api/lov",
                button: SaveOption
            });
            // const response = await apiJSONPOST(payload, 'api/options', SaveOption);
            console.log("response: " + response);
            if (!response.success) {

                showMessage({
                    status: "error",
                    title: "Error Saving Headline",
                    message: "There is some error saving your information. Please contact system administrator",
                });
                return;
            };

            function clearinputs() {

                // Reset main inputs
                document.getElementById("OptionName").value = "";
                document.getElementById("OptionDescription").value = "";

                // Remove all LOV items
                const lovContainer = document.getElementById("lovContainer");
                lovContainer.innerHTML = "";
            }

            showMessage({
                status: "success",
                title: "Headline Saved!",
            });
            clearinputs();

            loadOptions();

            closemodals();
        });

        async function deleteLOV(optionID, lovID, deleteButton) {
            console.log("delete request for :" + lovID + "from option: " + optionID);

            const confirmed = await customConfirm("Do you really want to delete this item?");
            if (!confirmed) return;
            const payload = {
                optionID: optionID,
                lovID: lovID,
            }
            const response = await apiCall({
                mode: "DELETE",
                isJson: true,
                payload: payload,
                url: "/api/lov",
                button: deleteButton
            });
            console.log(response);
            if (!response.success) {

                showMessage({
                    status: "error",
                    title: "Error Deleting LOV item",
                    message: "There is some error deleting LOV item. Please contact system administrator",
                });
                return;
            };

            showMessage({
                status: "success",
                title: "LOV Deleted!",
            });

            loadOptions();
            closemodals();
        }

        async function deleteOption(optionID, deleteoptionButton) {

            const confirmed = await customConfirm("Do you really want to delete this item?");
            if (!confirmed) return;
            const payload = {
                optionID: optionID,
            }
            const response = await apiCall({
                mode: "DELETE",
                isJson: true,
                payload: payload,
                url: "/api/options",
                button: deleteoptionButton
            });
            console.log(response);
            if (!response.success) {

                showMessage({
                    status: "error",
                    title: "Error Deleting Option",
                    message: "There is some error deleting Option. Please contact system administrator",
                });
                return;
            };

            showMessage({
                status: "success",
                title: "Option Deleted!",
            });

            loadOptions();
            closemodals();
        }

    })();
</script>
