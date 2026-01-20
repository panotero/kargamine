<div class="max-h-screen max-md:w-screen w-full overflow-auto bg-gray-50 dark:bg-gray-800 text-gray-800 p-5 rounded-lg">


    <div class="h-full container mx-auto ">
        <div class=" mb-5">
            <div class="w-full flex justify-between mb-5">

                <h2 class="text-lg font-semibold text-gray-700 dark:text-white">
                    User List
                </h2>
                <button id="addUserBtn"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition modal-open">
                    + New User
                </button>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-xl shadow">
                <table id="userTable" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">
                                <span class="flex items-center">ID</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Name</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Email</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Role</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Office</span>
                            </th>
                            <th class="px-4 py-3">
                                <span class="flex items-center">Actions</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody id="userTableBody"
                        class="divide-y divide-gray-200 bg-white dark:bg-gray-800 dark:divide-gray-700">
                        <!-- Rows will be inserted dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="userModal" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50 modal">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl relative transition-all text-black">
            <h2 id="modalTitle" class="text-xl font-semibold mb-4 ">Add New User</h2>

            <form id="userForm" class="space-y-4">
                <input type="hidden" id="userId" />

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Name</label>
                    <input id="userName" type="text"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                        placeholder="Full name" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input id="userEmail" type="email"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                        placeholder="Email address" />
                    <p class="mt-1 text-sm text-red-600 hidden" data-error-for="email"></p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Password</label>
                    <input id="userPassword" type="password"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" placeholder="Password"
                        minlength="6" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Office</label>
                    <select id="officeSelect" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="">Select Office</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">User Config</label>
                    <select id="configSelect" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="">Select Config</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="authorizedSginatory"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="agreeTerms" class="text-gray-700 dark:text-gray-300 text-sm">
                        Authorized signatory
                    </label>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelBtn"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg modal-close text-black">Cancel</button>
                    <button type="submit" id="saveBtn"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg hidden">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    (function() {
        const apiUsers = "/api/users";
        const apiOffices = "/api/offices";
        const apiConfigs = "/api/userconfigs";
        const patchsaveinfo = "/api/users/save";

        const userTableBody = document.getElementById("userTableBody");
        const userModal = document.getElementById("userModal");
        const modalTitle = document.getElementById("modalTitle");
        const addUserBtn = document.getElementById("addUserBtn");
        const cancelBtn = document.getElementById("cancelBtn");
        const saveBtn = document.getElementById("saveBtn");
        const userForm = document.getElementById("userForm");

        const userName = document.getElementById("userName");
        const userEmail = document.getElementById("userEmail");
        const userPassword = document.getElementById("userPassword");
        const userId = document.getElementById("userId");
        const officeSelect = document.getElementById("officeSelect");
        const configSelect = document.getElementById("configSelect");
        const authorizedSginatory = document.getElementById("authorizedSginatory");
        const table = document.getElementById("userTable");
        initDataTables();
        async function loadUsers() {
            try {

                if ($.fn.DataTable.isDataTable(table)) {
                    $(table).DataTable().clear();
                }
                const users = await fetchWithRetry(`/api/users`, {
                    method: "GET",
                    headers: {
                        Accept: "application/json"
                    },
                });


                users.forEach((user) => {
                    updateRow(user)
                });

                // logTableContent();
            } catch (error) {
                console.error("Error loading users:", error);
            }
        }

        function updateRow(users) {


            if (!table) return;
            const tableBody = table.querySelector("tbody");
            let dt = null;
            if ($.fn.DataTable.isDataTable(table)) {
                dt = $(table).DataTable();
            }
            const actionLabel = users.status === "deactivated" ? "Reactivate" : "Deactivate";
            const actionClass =
                users.status === "deactivated" ?
                "reactivateBtn bg-green-400" :
                "deactivateBtn bg-red-500";



            // Build one table row matching the column headers
            const rowHtml = `
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.id ?? '-'}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.name ?? '-'}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.email ?? '-'}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.role ?? '-'}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                        ${users.office.office_code?? '-'}
                    </td>

                        <td class="px-6 py-3 text-center">
                            <button class="text-white px-5 py-2 rounded bg-blue-500 editBtn" data-id="${users.id}">Edit</button> |
                            <button class="text-white px-5 py-2 rounded ${actionClass}" data-id="${users.id}">${actionLabel}</button>
                        </td>`;



            if (dt) {
                const newRow = dt.row.add([
                    users.id ?? "-", // Name
                    users.name ?? "-", // Name
                    users.email ?? "-", // Email
                    users.role ?? "-", // Designation
                    users.office?.office_name ?? "-", // Office
                    `<td class="px-6 py-3 text-center">
                            <button class="text-white px-5 py-2 rounded bg-blue-500 editBtn" data-id="${users.id}">Edit</button> |
                            <button class="text-white px-5 py-2 rounded ${actionClass}" data-id="${users.id}">${actionLabel}</button>
                        </td>`,
                ]).draw(false);

                const rowNode = newRow.node();
                // console.log(rowNode);
                if (rowNode === null) return;
                rowNode.classList.add(
                    "transition-colors", "duration-300",
                    "hover:dark:bg-white", "hover:dark:text-black");
            } else {
                const tr = document.createElement("tr");
                tr.innerHTML = rowHtml;
                table.appendChild(tr);
            }
        }

        function logTableContent() {
            const rows = userTableBody.querySelectorAll("tr");
            const tableData = Array.from(rows).map((row) =>
                Array.from(row.querySelectorAll("td")).map((cell) => cell.textContent.trim())
            );

        }

        async function loadDropdowns() {
            try {
                const [officesRes, configsRes] = await Promise.all([
                    fetch(apiOffices),
                    fetch(apiConfigs),
                ]);
                const [offices, configs] = await Promise.all([
                    officesRes.json(),
                    configsRes.json(),
                ]);

                officeSelect.innerHTML =
                    '<option value="">Select Office</option>' +
                    offices.map((o) => `<option value="${o.office_id}">${o.office_code}</option>`).join("");

                configSelect.innerHTML =
                    '<option value="">Select Config</option>' +
                    configs.map((c) => `<option value="${c.id}">${c.designation}</option>`).join("");
            } catch (error) {
                console.error("Error loading dropdowns:", error);
            }
        }

        function openModal(edit = false, user = null) {
            userModal.classList.remove("hidden");
            saveBtn.classList.add("hidden");

            if (edit && user) {
                modalTitle.textContent = "Edit User";
                userId.value = user.id;
                userName.value = user.name;
                userEmail.value = user.email;
                officeSelect.value = user.office_id ?? "";
                configSelect.value = user.role_id ?? "";
                if (user.authorize_signatory === 1) {

                    authorizedSginatory.checked = true;
                } else {
                    authorizedSginatory.checked = false;
                }
            } else {
                modalTitle.textContent = "Add New User";
                userForm.reset();
            }
        }

        function closeModal() {
            userModal.classList.add("hidden");
            userForm.reset();
            userId.value = "";
        }


        userForm.addEventListener("input", () => {
            saveBtn.classList.remove("hidden");
        });

        userForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            let authSignatory = 0;
            if (authorizedSginatory.checked) {
                authSignatory = 1;
            }
            const data = {
                name: userName.value,
                email: userEmail.value,
                password: userPassword.value,
                office_id: officeSelect.value,
                role_id: configSelect.value,
                role: configSelect.selectedOptions[0]?.text ?? "",
                authSignatory: authSignatory,
            };

            const method = userId.value ? "PATCH" : "POST";
            const url = userId.value ? `${patchsaveinfo}/${userId.value}` : apiUsers;

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .content,
                    },
                    body: JSON.stringify(data),
                });

                // Clear old errors
                clearValidationErrors();

                if (!response.ok) {
                    const result = await response.json();

                    if (result.success === false && result.invalid_fields) {
                        clearValidationErrors();
                        showValidationErrors(result.invalid_fields);
                        return;
                    }

                    throw new Error(result.message || "Request failed");
                }

                resetUserForm();
                showMessage({
                    status: "success",
                    message: "User created successfully",
                });

                closeModal();
                loadUsers();

            } catch (error) {
                console.error("Error saving user:", error);
            }
        });

        document.addEventListener("click", async (e) => {
            const id = e.target.dataset.id;

            if (e.target.matches(".editBtn")) {
                const res = await fetch(`${apiUsers}/${id}`);
                const data = await res.json();
                openModal(true, data);
            }

            if (e.target.matches(".deactivateBtn")) {

                const confirmed = await customConfirm("Deactivate this user?");
                if (!confirmed) return;

                const response = await fetch(`/api/users/deactivate/${id}`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .content
                    }
                });
                if (response.ok) {

                    showMessage({
                        status: "success",
                        message: "User deactivated successfully",
                    });
                } else {

                    showMessage({
                        status: "error",
                        message: "Failed to deactivate user",
                    });

                }

                loadUsers();
            }

            if (e.target.matches(".reactivateBtn")) {

                const confirmed = await customConfirm("Reactivate this user?");
                if (!confirmed) return;
                const response = await fetch(`/api/users/reactivate/${id}`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .content
                    }
                });
                if (response.ok) {

                    showMessage({
                        status: "success",
                        message: "User reactivated successfully",
                    });
                } else {

                    showMessage({
                        status: "error",
                        message: "Failed to reactivate user",
                    });
                }
                loadUsers();
            }
        });

        addUserBtn.addEventListener("click", () => openModal());
        cancelBtn.addEventListener("click", closeModal);

        loadUsers();
        loadDropdowns();

        function resetUserForm() {
            // Clear hidden ID (important for edit/create switching)
            document.getElementById('userId').value = '';

            // Clear text inputs
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userPassword').value = '';

            // Reset selects to default option
            document.getElementById('officeSelect').selectedIndex = 0;
            document.getElementById('configSelect').selectedIndex = 0;

            // Hide save button if your form switches between edit/create
            document.getElementById('saveBtn').classList.add('hidden');

            // Optional: remove validation states if you add any later
            document.querySelectorAll('#userForm input, #userForm select').forEach(el => {
                el.classList.remove('border-red-500', 'border-green-500');
            });
        }

        function showValidationErrors(errors) {
            if (!errors) return;

            Object.keys(errors).forEach((field) => {
                const input = document.querySelector(`[name="${field}"]`);
                const errorTag = document.querySelector(
                    `[data-error-for="${field}"]`
                );

                if (input) {
                    input.classList.add(
                        "border-red-500",
                        "focus:ring-red-500"
                    );
                }

                if (errorTag) {
                    errorTag.textContent = errors[field][0];
                    errorTag.classList.remove("hidden");
                }
            });
        }



        function clearValidationErrors() {
            document.querySelectorAll(".is-invalid").forEach((el) => {
                el.classList.remove("is-invalid");
            });

            document.querySelectorAll(".error-message").forEach((el) => {
                el.textContent = "";
            });
        }

    })();
</script>
