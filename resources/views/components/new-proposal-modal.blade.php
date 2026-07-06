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

                <!-- Company Info -->
                <div class="flex flex-col gap-1 col-span-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Company Name</label>
                    <input type="text" name="company_name"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>
                <div class="flex flex-col gap-1 col-span-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Company
                        Address</label>
                    <input type="text" name="company_address"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>
                <div class="flex flex-col gap-1 col-span-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Authorized
                        Signatory</label>
                    <input type="text" name="authorized_signatory_name"
                        class=" w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>
                <div class="flex flex-col gap-1 col-span-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Signatory
                        Position</label>
                    <input type="text" name="authorized_signatory_position"
                        class=" w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>

                <!-- Rate -->
                <div class="flex flex-col gap-1 col-span-2">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Rate</label>
                    <input type="text" name="proposed_rate"
                        class="format-currency w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>

                <!-- Service Mode -->
                <div class="flex flex-col gap-1 col-span-2">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Service Mode</label>
                    <div
                        class="bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1">
                            <label
                                class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Origin</label>
                            <select name="service_origin" required
                                class="serviceDropDown origin w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label
                                class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Destination</label>
                            <select name="service_destination" required
                                class="serviceDropDown destination w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Route -->
                <div class="flex flex-col gap-1 col-span-2">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Route</label>
                    <div
                        class="bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">From</label>
                            <select name="route_from" required
                                class="routeDropDown w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">To</label>
                            <select name="route_to" required
                                class="routeDropDown w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Minimum Van Quantity -->
                <div class="flex flex-col gap-1 col-span-2">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Minimum Van
                        Quantity</label>
                    <input type="number" name="min_van_qty"
                        class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                </div>

                <!-- Van Class -->
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Van Class</label>
                    <select name="container_class" required
                        class="vanClassDropdown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </select>
                </div>

                <!-- Van Type -->
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Van Type</label>
                    <select name="container_type" required
                        class="vanTypeDropdown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </select>
                </div>

                <!-- Van Size -->
                <div class="col-span-2 flex flex-col gap-1">
                    <label class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">Van Size</label>
                    <select name="container_size" required
                        class="vanSizeDropdown w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition">
                    </select>
                </div>

            </div>
        </form>
    </div>

    <!-- Footer -->
    <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2">
        <button type="button"
            class="modal-close px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
            Cancel
        </button>
        <button type="submit" id="saveProposalBtn"
            class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
            Save Proposal
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
            if (!response.success) {
                showMessage({
                    status: "error",
                    message: response,
                    title: "Error Saving Proposal",
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
        loadOptions();

        function loadOptions() {
            const modal = document.getElementById("generateProposal");

            fillRouteDropdown();
            FillServiceMode();
            fillVanTypeDropdown();
            fillVanClassDropdown();
            fillSizeDropdown();
        }


        async function fillRouteDropdown() {
            // const statusdropdown = document.querySelectorAll(".statusDropDown");
            const routedropdown = document.querySelectorAll(".routeDropDown");

            const routes = await apiCall({
                mode: "GET",
                url: "/api/listofval/route",
            });
            routedropdown.forEach(dropdownroute => {
                let html = `<option value="">Select Port</option>`;
                routes.forEach(route => {
                    html +=
                        `
                        <option value="${route.port_id}" class="flex justify-between"> <p>${route.code} </p> <p>${route.port}</option></p>`;
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
            vanTypeDropdown.forEach(vantype => {
                let html = `<option value="">Select Size</option>`;
                types.forEach(type => {
                    html += `
                        <option value="${type.id}">${type.type}</option>`;
                });
                vantype.innerHTML = html;
            });


        }

        async function fillVanClassDropdown() {
            // const statusdropdown = document.querySelectorAll(".statusDropDown");
            const vanClassDropdown = document.querySelectorAll(".vanClassDropdown");

            const classes = await apiCall({
                mode: "GET",
                url: "/api/listofval/vanclass",
            });

            vanClassDropdown.forEach(vanclass => {

                let html = `<option value="">Select Class</option>`;
                classes.forEach(Class => {
                    html += `
                        <option value="${Class.id}">${Class.class}</option>`;
                });
                vanclass.innerHTML = html;
            });


        }
        async function fillSizeDropdown() {
            // const statusdropdown = document.querySelectorAll(".statusDropDown");
            const vanSizeDropdown = document.querySelectorAll(".vanSizeDropdown");

            const sizes = await apiCall({
                mode: "GET",
                url: "/api/listofval/vansize",
            });
            vanSizeDropdown.forEach(vansize => {
                let html = `<option value="">Select Size</option>`;
                sizes.forEach(size => {
                    html += `
                        <option value="${size.id}">${size.size}</option>`;
                });
                vansize.innerHTML = html;
            });


        }

    })();
</script>
