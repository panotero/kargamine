<!-- User Config Table -->
<div class="container mx-auto">

    <div class="w-full h-auto bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5">
        <div class="w-full flex justify-between p-5">

            <h2 class="text-xl font-semibold mb-4">User List</h2>
            <button id="addUserBtn" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow">
                + New User
            </button>
        </div>
        <div class="overflow-y-auto max-h-[75vh]">
            <table class="min-w-full text-sm border-collapse dark:text-white p-5" id="userTable">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">ID</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Name</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Email</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Role</th>
                        <th class="text-left px-6 py-3 text-sm font-semibold text-gray-600">Office</th>
                        <th class="text-center px-6 py-3 text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="bg-gray-100 dark:bg-gray-700"></tbody>
            </table>

            <!-- Mobile Card Container -->
            <div id="userCardList" class="hidden md:hidden overflow-y-auto max-h-[400px] p-3 space-y-3"></div>
        </div>

    </div>

    <!-- Modal -->
    <div id="userModal" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50 modal">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl relative transition-all text-black">
            <h2 id="modalTitle" class="text-xl font-semibold mb-4 ">Add New User</h2>

            <!-- Form -->
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

<!-- Script -->
<script>
    (function() {
        // ==========================
        // API Endpoints
        // ==========================
        const apiUsers = "/api/users";
        const apiOffices = "/api/offices";
        const apiConfigs = "/api/userconfigs";
        const patchsaveinfo = "/api/users/save";

        // ==========================
        // DOM Elements
        // ==========================
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

        // ==========================
        // Load Users Table
        // ==========================
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

        // ==========================
        // Log Table Content
        // ==========================
        function logTableContent() {
            const rows = userTableBody.querySelectorAll("tr");
            const tableData = Array.from(rows).map((row) =>
                Array.from(row.querySelectorAll("td")).map((cell) => cell.textContent.trim())
            );

            // console.log(" Table data:", tableData);
        }

        // ==========================
        // Load Dropdowns
        // ==========================
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

        // ==========================
        // Modal Handling
        // ==========================
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

        // ==========================
        // Event Listeners
        // ==========================

        // Detect form input changes
        userForm.addEventListener("input", () => {
            saveBtn.classList.remove("hidden");
        });

        // Submit (Add or Edit)
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

        // Global click handler
        document.addEventListener("click", async (e) => {
            const id = e.target.dataset.id;

            // Edit
            if (e.target.matches(".editBtn")) {
                const res = await fetch(`${apiUsers}/${id}`);
                const data = await res.json();
                openModal(true, data);
            }

            // Deactivate
            if (e.target.matches(".deactivateBtn")) {

                // Use customConfirm instead of default confirm
                const confirmed = await customConfirm("Deactivate this user?");
                if (!confirmed) return; // if user clicks Cancel, exit

                // User clicked OK → proceed with API call
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

                // Reload users table
                loadUsers();
            }

            // Reactivate
            if (e.target.matches(".reactivateBtn")) {

                // Use customConfirm instead of default confirm
                const confirmed = await customConfirm("Reactivate this user?");
                if (!confirmed) return; // if user clicks Cancel, exit
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

        // Modal buttons
        addUserBtn.addEventListener("click", () => openModal());
        cancelBtn.addEventListener("click", closeModal);

        // ==========================
        // Initialize
        // ==========================
        loadUsers();
        loadDropdowns();
    })();
</script>
