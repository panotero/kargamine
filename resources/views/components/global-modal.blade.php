{{-- side nav for generating proposal --}}
<x-side-modal id="generateProposal">

    <div class="p-5 border-b flex justify-between sticky top-0 bg-white dark:bg-zinc-800 z-10">




        <p class="text-xl font-semibold dark:text-white">
            New Proposal
        </p>

        <button class="modal-close">
            ✕
        </button>

    </div>

    <div class="p-5">

        <form id="proposalForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                <div class="col-span-2 flex flex-col  dark:text-white">
                    <label>Rate</label>
                    <input type="text" name="proposed_rate"
                        class="border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white format-currency">
                </div>
                <div class="col-span-2 flex flex-col  dark:text-white">
                    <label>Service Mode</label>
                    <div class="p-2 rounded-lg border border-zinc-200 grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="flex flex-col  dark:text-white">
                            <label>Origin</label>

                            <select name="service_origin"
                                class="serviceDropDown origin border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white"
                                required>

                            </select>
                        </div>
                        <div class="flex flex-col  dark:text-white">
                            <label>Destination</label>

                            <select name="service_destination"
                                class="serviceDropDown destination border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white"
                                required>

                            </select>
                        </div>

                    </div>
                </div>
                <div class="col-span-2 flex flex-col  dark:text-white">
                    <label>Route</label>
                    <div class="p-2 rounded-lg border border-zinc-200 grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="flex flex-col  dark:text-white">
                            <label>From</label>

                            <select name="route_from"
                                class="routeDropDown border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white"
                                required>

                            </select>
                        </div>
                        <div class="flex flex-col  dark:text-white">
                            <label>To</label>

                            <select name="route_to"
                                class="routeDropDown border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white"
                                required>

                            </select>
                        </div>

                    </div>
                </div>
                <div class="col-span-2 flex flex-col  dark:text-white">
                    <label>Minimum Van Quantity</label>
                    <input type="number" name="min_van_qty"
                        class="border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white">
                </div>
                <div class="flex flex-col  dark:text-white">
                    <label>Van Type</label>
                    <select name="van_type"
                        class="vanTypeDropdown border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white"
                        required>
                    </select>
                </div>
                <div class="flex flex-col  dark:text-white">
                    <label>Van Size</label>
                    <select name="van_size"
                        class="vanSizeDropdown border border-zinc-200 p-2 rounded-lg dark:bg-zinc-600 dark:text-white"
                        required>
                    </select>
                </div>

            </div>
        </form>
    </div>


    <div class="border-t px-5 py-4 flex justify-end gap-2">


        <button type="submit" id="saveProposalBtn"
            class="bg-orange-400 hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
            Save Lead
        </button>

        <button
            class="modal-close border border-gray-300  text-gray-700  hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-white px-5 py-2 rounded-lg text-sm font-medium">
            Cancel
        </button>

    </div>

</x-side-modal>

<script>
    (function() {
        //proposal functions
        //onclick function
        document.getElementById("saveProposalBtn").addEventListener("click", async function() {
            if (!this) return;
            const proposalForm = document.getElementById("proposalForm");
            const formData = new FormData(proposalForm);

            const data = Object.fromEntries(formData.entries());
            data.uuid = window.uuid;
            const response = await apiCall({
                mode: "POST",
                isJson: true,
                payload: data,
                url: "/api/proposal",
                button: this,
            });
            console.log(response);
            if (!response.success) {
                showMessage({
                    status: "error",
                    title: "Error Saving Activity",
                });
                return;
            }

            showMessage({
                status: "success",
                title: "Activity Saved!",
            });


            clearInputs();
            reloadCrmData();
            closeSideModal("generateProposal");

        });
        document.addEventListener("DOMContentLoaded", function() {

            fillRouteDropdown();
            FillServiceMode();
            fillVanTypeDropdown();
            fillSizeDropdown();
        });

        async function fillRouteDropdown() {
            // const statusdropdown = document.querySelectorAll(".statusDropDown");
            const routedropdown = document.querySelectorAll(".routeDropDown");

            const routes = await apiCall({
                mode: "GET",
                url: "/api/listofval/route",
            });
            console.log(routes);
            routedropdown.forEach(dropdownroute => {
                let html = `<option value="">Select Port</option>`;
                routes.forEach(route => {
                    html += `
                        <option value="${route.id}">${route.route}</option>`;
                });
                dropdownroute.innerHTML = html;
            });


        }

        async function FillServiceMode() {
            // const statusdropdown = document.querySelectorAll(".statusDropDown");
            const serviceOrigin = document.querySelectorAll(".serviceDropDown.origin");
            const serviceDestination = document.querySelectorAll(".serviceDropDown.destination");

            const services = await apiCall({
                mode: "GET",
                url: "/api/listofval/service",
            });
            serviceOrigin.forEach(Origin => {
                console.log(Origin);

                let html = `<option value="">Select Sevice Mode</option>`;
                services.forEach(service => {

                    if (service.type === "ORIGIN") {

                        html += `
                        <option value="${service.id}">${service.mode}</option>`;

                    }

                });
                Origin.innerHTML = html;
            });
            serviceDestination.forEach(Destination => {
                console.log(Origin);

                let html = `<option value="">Select Sevice Mode</option>`;
                services.forEach(service => {

                    if (service.type === "DESTINATION") {

                        html += `
                        <option value="${service.id}">${service.mode}</option>`;

                    }

                });
                Destination.innerHTML = html;
            });
        }

        async function fillVanTypeDropdown() {
            // const statusdropdown = document.querySelectorAll(".statusDropDown");
            const vanTypeDropdown = document.querySelectorAll(".vanTypeDropdown");

            const types = await apiCall({
                mode: "GET",
                url: "/api/listofval/vantype",
            });
            console.log(types);
            vanTypeDropdown.forEach(vantype => {
                let html = `<option value="">Select Port</option>`;
                types.forEach(type => {
                    html += `
                        <option value="${type.id}">${type.type}</option>`;
                });
                vantype.innerHTML = html;
            });


        }
        async function fillSizeDropdown() {
            // const statusdropdown = document.querySelectorAll(".statusDropDown");
            const vanSizeDropdown = document.querySelectorAll(".vanSizeDropdown");

            const sizes = await apiCall({
                mode: "GET",
                url: "/api/listofval/vansize",
            });
            console.log(sizes);
            vanSizeDropdown.forEach(vansize => {
                let html = `<option value="">Select Port</option>`;
                sizes.forEach(size => {
                    html += `
                        <option value="${size.id}">${size.size}</option>`;
                });
                vansize.innerHTML = html;
            });


        }

    })();
</script>
