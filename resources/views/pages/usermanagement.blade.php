<div class="max-h-[90vh] max-w-[90vw] overflow-auto bg-gray-50 dark:bg-gray-800 text-gray-800 rounded-lg">

    <div class="h-full container mx-auto py-5 ">
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

        async function loadUsers() {
            try {
                const res = await fetch(apiUsers);
                const users = await res.json();

                userTableBody.innerHTML = "";

                users.forEach((u) => {
                    const actionLabel = u.status === "deactivated" ? "Reactivate" : "Deactivate";
                    const actionClass =
                        u.status === "deactivated" ?
                        "reactivateBtn bg-green-400" :
                        "deactivateBtn bg-red-500";

                    userTableBody.insertAdjacentHTML(
                        "beforeend",
                        `
                    <tr class="border-t hover:bg-gray-50 transition ">
                        <td class="px-6 py-3">${u.id}</td>
                        <td class="px-6 py-3">${u.name}</td>
                        <td class="px-6 py-3">${u.email}</td>
                        <td class="px-6 py-3">${u.role ?? "-"}</td>
                        <td class="px-6 py-3">${u.office.office_name ?? "-"}</td>
                        <td class="px-6 py-3 text-center">
                            <button class="text-white px-5 py-2 rounded bg-blue-500 editBtn" data-id="${u.id}">Edit</button> |
                            <button class="text-white px-5 py-2 rounded ${actionClass}" data-id="${u.id}">${actionLabel}</button>
                        </td>
                    </tr>
                `
                    );
                });

                logTableContent();
                initDataTables();
            } catch (error) {
                console.error("Error loading users:", error);
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
                    offices.map((o) => `<option value="${o.office_id}">${o.office_name}</option>`).join("");

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

            const data = {
                name: userName.value,
                email: userEmail.value,
                password: userPassword.value,
                office_id: officeSelect.value,
                role_id: configSelect.value,
                role: configSelect.selectedOptions[0]?.text ?? "",
            };

            const method = userId.value ? "PATCH" : "POST";
            const url = userId.value ? `${patchsaveinfo}/${userId.value}` : apiUsers;

            try {
                await fetch(url, {
                    method,
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data),
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
    })();
</script>
