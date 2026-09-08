<div class="container mx-auto p-3">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-5 p-2 gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-bold dark:text-white">Roles &amp; Permissions</h1>
            <p class="text-zinc-500">Manage system roles and the permissions each one grants.</p>
        </div>
        <button id="addRoleBtn" type="button"
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + Add Role
        </button>
    </div>

    {{-- ROLES LIST --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800">
            <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Roles</p>
        </div>
        <div id="rolesContainer" class="divide-y divide-zinc-100 dark:divide-zinc-800"></div>
    </div>
</div>

{{-- ROLE FORM MODAL (create + edit, with permissions checklist) --}}
<x-modal id="roleFormModal" maxWidth="lg:max-w-lg">
    <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
        <p class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Roles &amp; Permissions</p>
        <p class="text-lg font-semibold mt-0.5 dark:text-white" id="roleFormTitle">New Role</p>
    </div>

    <div class="p-5 space-y-4 text-sm max-h-[65vh] overflow-y-auto">
        <input type="hidden" id="roleFormId">

        <div class="border-l-2 border-orange-400 dark:border-orange-600 pl-3 flex flex-col gap-1">
            <label for="roleFormName" class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Role Name</label>
            <input type="text" id="roleFormName" placeholder="e.g. Sales Rep"
                class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 transition">
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-[11px] font-medium uppercase tracking-widest text-zinc-400 dark:text-zinc-500">Permissions</label>
                <label class="flex items-center gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 cursor-pointer">
                    <input type="checkbox" id="roleFormCheckAll"
                        class="rounded border-zinc-300 text-orange-500 focus:ring-orange-400">
                    Check All
                </label>
            </div>
            <div id="roleFormPermissions" class="space-y-4"></div>
        </div>
    </div>

    <div class="border-t border-zinc-100 dark:border-zinc-800 px-5 py-4 flex justify-end gap-2">
        <button type="button"
            class="modal-close px-4 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition">
            Cancel
        </button>
        <button type="button" id="roleFormSaveBtn"
            class="px-4 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition">
            Save Role
        </button>
    </div>
</x-modal>

<script>
    (function () {
        const rolesContainer = document.getElementById("rolesContainer");
        if (!rolesContainer) return;

        let rolesList = [];
        let permissionsGrouped = {};

        function esc(value) {
            return String(value ?? "").replace(/[&<>"']/g, (c) => ({
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
            })[c]);
        }

        async function init() {
            await loadPermissions();
            await loadRoles();
        }

        async function loadPermissions() {
            const response = await apiCall({ mode: "GET", url: "/api/permissions" });
            permissionsGrouped = response?.success ? (response.data ?? {}) : {};
        }

        function renderPermissionChecklist(selectedIds = []) {
            const container = document.getElementById("roleFormPermissions");
            const modules = Object.keys(permissionsGrouped);

            if (!modules.length) {
                container.innerHTML = `<p class="text-sm text-zinc-400 dark:text-zinc-500">No permissions defined yet.</p>`;
                return;
            }

            container.innerHTML = modules
                .map(
                    (module) => `
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1.5">${esc(module)}</p>
                        <div class="space-y-1.5">
                            ${permissionsGrouped[module]
                                .map(
                                    (p) => `
                                <label class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-200 cursor-pointer">
                                    <input type="checkbox" class="rolePermissionCheckbox rounded border-zinc-300 text-orange-500 focus:ring-orange-400"
                                        value="${p.id}" ${selectedIds.includes(p.id) ? "checked" : ""}>
                                    ${esc(p.label)}
                                </label>`,
                                )
                                .join("")}
                        </div>
                    </div>`,
                )
                .join("");

            wirePermissionCheckAll();
        }

        function wirePermissionCheckAll() {
            const checkAllBox = document.getElementById("roleFormCheckAll");
            const boxes = () => Array.from(document.querySelectorAll(".rolePermissionCheckbox"));

            const syncCheckAllState = () => {
                const all = boxes();
                const checkedCount = all.filter((cb) => cb.checked).length;
                checkAllBox.checked = all.length > 0 && checkedCount === all.length;
                checkAllBox.indeterminate = checkedCount > 0 && checkedCount < all.length;
            };

            checkAllBox.onchange = () => {
                boxes().forEach((cb) => (cb.checked = checkAllBox.checked));
                checkAllBox.indeterminate = false;
            };

            boxes().forEach((cb) => (cb.onchange = syncCheckAllState));
            syncCheckAllState();
        }

        function openRoleForm(role = null) {
            document.getElementById("roleFormId").value = role?.id ?? "";
            document.getElementById("roleFormName").value = role?.role_name ?? "";
            document.getElementById("roleFormTitle").textContent = role ? "Edit Role" : "New Role";

            const selectedIds = (role?.permissions ?? []).map((p) => p.id);
            renderPermissionChecklist(selectedIds);

            initModal({ modalId: "roleFormModal" });
        }

        async function loadRoles() {
            const response = await apiCall({ mode: "GET", url: "/api/roles" });
            rolesList = Array.isArray(response) ? response : (response?.data ?? []);
            renderRoles();
        }

        function renderRoles() {
            if (!Array.isArray(rolesList)) rolesList = [];

            if (!rolesList.length) {
                rolesContainer.innerHTML = `<p class="px-4 py-10 text-center text-sm text-zinc-400 dark:text-zinc-500">No roles yet.</p>`;
                return;
            }

            rolesContainer.innerHTML = rolesList
                .map((role) => {
                    const canDelete = !role.is_system && (role.users_count ?? 0) === 0;
                    const deleteTitle = role.is_system
                        ? "System roles cannot be deleted"
                        : canDelete
                          ? "Delete"
                          : "Reassign users before deleting";

                    return `
                    <div class="flex items-center gap-2 px-4 py-2.5" data-role-id="${role.id}">
                        <div class="flex-1 min-w-0 flex items-center gap-1.5">
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 truncate">${esc(role.role_name)}</p>
                            ${role.is_system ? '<span class="text-[9px] font-semibold text-zinc-400 border border-zinc-200 dark:border-zinc-700 rounded px-1 py-0.5 shrink-0">SYSTEM</span>' : ""}
                        </div>
                        <span class="text-[10px] font-semibold text-zinc-400 shrink-0">${role.users_count ?? 0} user${(role.users_count ?? 0) !== 1 ? "s" : ""}</span>
                        <button type="button" class="btn-edit-role p-1.5 text-zinc-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/40 rounded-lg transition shrink-0" data-id="${role.id}" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                            </svg>
                        </button>
                        <button type="button" class="btn-delete-role p-1.5 rounded-lg transition shrink-0 ${canDelete ? "text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40" : "text-zinc-200 dark:text-zinc-700 cursor-not-allowed"}" data-id="${role.id}" title="${deleteTitle}" ${canDelete ? "" : "disabled"}>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>`;
                })
                .join("");
        }

        rolesContainer.addEventListener("click", async (e) => {
            const editBtn = e.target.closest(".btn-edit-role");
            const deleteBtn = e.target.closest(".btn-delete-role");

            if (editBtn) {
                const role = rolesList.find((r) => String(r.id) === String(editBtn.dataset.id));
                if (role) openRoleForm(role);
                return;
            }

            if (deleteBtn) {
                if (deleteBtn.disabled) return;

                const confirmed = await customConfirm(
                    "Delete this role? Roles that still have users assigned cannot be deleted.",
                );
                if (!confirmed) return;

                const response = await apiCall({
                    mode: "DELETE",
                    url: `/api/roles/${deleteBtn.dataset.id}`,
                    button: deleteBtn,
                });

                if (!response || !response.success) {
                    showMessage({
                        status: "error",
                        title: "Cannot delete role",
                        message: response?.message ?? "Failed to delete role.",
                    });
                    return;
                }

                showMessage({ status: "success", title: "Role deleted." });
                await loadRoles();
            }
        });

        document.getElementById("addRoleBtn").addEventListener("click", () => openRoleForm(null));

        document.getElementById("roleFormSaveBtn").addEventListener("click", async function () {
            const roleId = document.getElementById("roleFormId").value;
            const roleNameInput = document.getElementById("roleFormName");
            const roleName = roleNameInput.value.trim();

            if (!roleName) {
                roleNameInput.focus();
                return;
            }

            const permissionIds = Array.from(document.querySelectorAll(".rolePermissionCheckbox:checked")).map((cb) =>
                Number(cb.value),
            );

            const isUpdate = Boolean(roleId);

            const response = await apiCall({
                mode: isUpdate ? "PUT" : "POST",
                isJson: true,
                payload: { role_name: roleName, permission_ids: permissionIds },
                url: isUpdate ? `/api/roles/${roleId}` : "/api/roles",
                button: this,
            });

            if (!response || !response.success) {
                showMessage({
                    status: "error",
                    title: "Error",
                    message: response?.invalid_fields
                        ? Object.values(response.invalid_fields).flat().join(" ")
                        : (response?.message ?? "Failed to save role."),
                });
                return;
            }

            showMessage({ status: "success", title: isUpdate ? "Role updated." : "Role created." });
            closeModal("roleFormModal");
            await loadRoles();
        });

        init();
    })();
</script>
