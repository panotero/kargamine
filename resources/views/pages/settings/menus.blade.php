<div class="lg:m-5 p-5 rounded-lg bg-white">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Navigation Menus</h2>
        <button id="addMenuBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add New Menu
        </button>
    </div>

    <table class="w-full border  p-5" id="navMenuTable">
        <thead class="bg-gray-200 text-black">
            <tr>
                <th class="p-2 border">Title</th>
                <th class="p-2 border">Page</th>
                <th class="p-2 border">Allowed Roles</th>
                <th class="p-2 border">Parent</th>
                <th class="p-2 border text-center">Order</th>
                <th class="p-2 border text-center">Action</th>
            </tr>
        </thead>
        <tbody id="navMenuTbody">
        </tbody>
    </table>
</div>

<div id="menuModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center modal">
    <div class="bg-white w-full max-w-md rounded shadow p-6 relative">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">Add New Menu</h3>

        <form id="menuForm" class="space-y-3">
            <input type="hidden" id="menuId">

            <div>
                <label class="block font-medium">Title</label>
                <input id="menuTitle" type="text" class="w-full border p-2 rounded text-black">
            </div>

            <div>
                <label class="block font-medium">Icon</label>
                <input id="menuIcon" type="text" class="w-full border p-2 rounded text-black">
            </div>

            <div>
                <label class="block font-medium">Link</label>
                <div class="w-full h-full flex my-auto">
                    <h1 class="p-2 text-center text-xl">
                        /</h1><input id="menuLink" type="text" class="w-full border p-2 rounded text-black">
                </div>

            </div>

            <div>
                <label class="block font-medium">Allowed Roles</label>
                <div id="menuRolesContainer" class="grid grid-cols-2 gap-2 text-black">
                </div>
            </div>
            <div>
                <label class="block font-medium">Allowed Office</label>
                <div id="menuOfficeContainer" class="grid grid-cols-2 gap-2 text-black">
                </div>
            </div>

            <div>
                <label class="block font-medium">Parent Menu</label>
                <select id="menuParent" class="w-full border p-2 rounded text-black">
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" id="cancelModalBtn" class="px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" id="saveMenuBtn" class="px-4 py-2 rounded bg-green-600 text-white">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    (function() {
        const modal = document.getElementById("menuModal");
        const cancelBtn = document.getElementById("cancelModalBtn");
        const form = document.getElementById("menuForm");
        const saveBtn = document.getElementById("saveMenuBtn");
        const modalTitle = document.getElementById("modalTitle");

        const fields = {
            id: document.getElementById("menuId"),
            title: document.getElementById("menuTitle"),
            icon: document.getElementById("menuIcon"),
            link: document.getElementById("menuLink"),
            rolesContainer: document.getElementById("menuRolesContainer"),
            officeCheckBoxFiller: document.getElementById("menuOfficeContainer"),
            parent: document.getElementById("menuParent"),
        };

        const tableBody = document.getElementById("navMenuTbody");
        let menusData = [];

        cancelBtn.addEventListener("click", () => {
            closeModal();
        });

        function openModal(mode = "Add", menu = null) {
            modalTitle.textContent = mode === "Add" ? "Add New Menu" : "Modify Menu";
            saveBtn.textContent = mode === "Add" ? "Save" : "Modify";
            console.log(mode);
            if (menu) {
                fields.id.value = menu.id;
                fields.title.value = menu.title || "";
                fields.icon.value = menu.icon || "";
                fields.link.value = menu.link || "";
                fields.parent.value = menu.parent_menu || 0;

                const allowedRoles = JSON.parse(menu.allowed_roles || "[]");
                document.querySelectorAll(".roleCheckbox").forEach(cb => {
                    cb.checked = allowedRoles.includes(cb.value);
                    cb.disabled = menu.parent_menu !== 0;
                });
                const allowedOffices = JSON.parse(menu.allowed_office || "[]");
                document.querySelectorAll(".officeCheckbox").forEach(office => {
                    office.checked = allowedOffices.includes(office.value);
                    office.disabled = menu.parent_menu !== 0;
                });
            } else {
                fields.id.value = "";
                fields.title.value = "";
                fields.icon.value = "";
                fields.link.value = "";
                fields.parent.value = 0;
                document.querySelectorAll(".roleCheckbox").forEach(cb => {
                    cb.checked = false;
                    cb.disabled = false;
                });
            }

            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        function closeModal() {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        async function loadRoles() {
            const res = await fetch(`api/userconfigs`, {
                headers: {
                    Accept: "application/json"
                }
            });
            const roles = await res.json();
            const container = fields.rolesContainer;
            container.innerHTML = "";

            const checkAllWrapper = document.createElement("label");
            checkAllWrapper.classList.add("flex", "items-center", "gap-2", "mb-2");
            checkAllWrapper.innerHTML = `
            <input type="checkbox" id="checkAllRoles" class="cursor-pointer">
            <span class="font-medium text-gray-700">Check All</span>
        `;
            container.appendChild(checkAllWrapper);

            roles.forEach(role => {
                const wrapper = document.createElement("label");
                wrapper.classList.add("flex", "items-center", "gap-2");
                wrapper.innerHTML = `
                <input type="checkbox" value="${role.designation}" class="roleCheckbox cursor-pointer">
                <span>${role.designation}</span>
            `;
                container.appendChild(wrapper);
            });

            const checkAllBox = container.querySelector("#checkAllRoles");
            checkAllBox.addEventListener("change", () => {
                container.querySelectorAll(".roleCheckbox").forEach(cb => cb.checked = checkAllBox
                    .checked);
            });
        }

        async function loadOffices() {
            const res = await fetch(`api/offices`, {
                headers: {
                    Accept: "application/json"
                }
            });

            const offices = await res.json();
            const container = fields.officeCheckBoxFiller;
            container.innerHTML = "";

            const checkAllWrapper = document.createElement("label");
            checkAllWrapper.classList.add("flex", "items-center", "gap-2", "mb-2");
            checkAllWrapper.innerHTML = `
        <input type="checkbox" id="checkAllOffices" class="cursor-pointer">
        <span class="font-medium text-gray-700">Check All</span>
    `;
            container.appendChild(checkAllWrapper);

            offices.forEach(office => {
                const wrapper = document.createElement("label");
                wrapper.classList.add("flex", "items-center", "gap-2");

                wrapper.innerHTML = `
            <input type="checkbox"
                   value="${office.office_name}"
                   data-office-name="${office.office_name}"
                   class="officeCheckbox cursor-pointer">
            <span>${office.office_name}</span>
        `;

                container.appendChild(wrapper);
            });

            const checkAllBox = container.querySelector("#checkAllOffices");
            checkAllBox.addEventListener("change", () => {
                container.querySelectorAll(".officeCheckbox").forEach(cb => {
                    cb.checked = checkAllBox.checked;
                });
            });
        }
        async function loadMenus() {
            const res = await fetch(`api/nav_menus/list`, {
                credentials: 'include',
                headers: {
                    Accept: "application/json"
                }
            });
            menusData = await res.json();
            tableBody.innerHTML = "";

            const menuMap = {};
            menusData.forEach(menu => menuMap[menu.id] = menu.title);

            function renderMenuRows(parentId = 0, level = 0) {
                const children = menusData
                    .filter(m => m.parent_menu === parentId)
                    .sort((a, b) => a.menu_order - b.menu_order);

                children.forEach(menu => {
                    const tr = document.createElement("tr");
                    tr.classList.add("cursor-pointer", "hover:bg-gray-100");

                    const parentName = menu.parent_menu === 0 ?
                        "Main Menu" :
                        (menuMap[menu.parent_menu] || "Unknown");

                    const siblings = menusData
                        .filter(m => m.parent_menu === menu.parent_menu)
                        .sort((a, b) => a.menu_order - b.menu_order);
                    const index = siblings.findIndex(m => m.id === menu.id);

                    const moveUpHidden = index === 0 ? "hidden" : "";
                    const moveDownHidden = index === siblings.length - 1 ? "hidden" : "";
                    const roles = JSON.parse(menu.allowed_roles).join(', ');
                    const targetpage = menu.link.replace(/^\//, '');

                    const indent = "&nbsp;".repeat(level * 6) + (level > 0 ? "↳ " : "");

                    tr.innerHTML = `
                <td class="border p-2">${indent}${menu.title}</td>
                <td class="border p-2">${targetpage || ""}</td>
                <td class="border p-2">${roles || ""}</td>
                <td class="border p-2">${parentName}</td>
                <td class="border menubuttons p-2 text-center">
                    <button class="move-up bg-blue-600 hover:bg-blue-800 text-white px-2 py-1 rounded-md ${moveUpHidden}" data-id="${menu.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    <button class="move-down bg-blue-600 hover:bg-blue-800 text-white px-2 py-1 rounded-md ${moveDownHidden}" data-id="${menu.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </td>
                <td class="border menubuttons p-2 text-center">
                    <button class="delete-button bg-red-600 hover:bg-red-800 py-1 px-2 rounded text-white" data-id="${menu.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 6h18v2H3V6zm2 3h14l-1.5 12.5a1 1 0 0 1-1 .5H8a1 1 0 0 1-1-.5L5 9zm5 2v8h2v-8H10zm4 0v8h2v-8h-2zM9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1h5v2H4V4h5z"/>
                        </svg>
                    </button>
                </td>
            `;

                    tr.addEventListener("click", (e) => {
                        if (e.target.closest("td.menubuttons")) {
                            return;
                        }

                        modalTitle.textContent = "Modify Menu";
                        saveBtn.textContent = "Modify";

                        fields.id.value = menu.id;
                        fields.title.value = menu.title || "";
                        fields.icon.value = menu.icon || "";
                        fields.link.value = menu.link || "";
                        fields.parent.value = menu.parent_menu || 0;

                        const allowedRoles = JSON.parse(menu.allowed_roles || "[]");
                        document.querySelectorAll('.roleCheckbox').forEach(cb => {
                            cb.checked = allowedRoles.includes(cb.value);
                        });

                        const allowedOffice = JSON.parse(menu.allowed_office || "[]");
                        document.querySelectorAll('.officeCheckbox').forEach(cb => {
                            cb.checked = allowedOffice.includes(cb.value);
                        });

                        modal.classList.remove("hidden");
                        modal.classList.add("flex");
                    });

                    tableBody.appendChild(tr);

                    renderMenuRows(menu.id, level + 1);
                });
            }

            renderMenuRows(0);

            const parentSelect = fields.parent;
            parentSelect.innerHTML = `<option value="0">Main Menu</option>`;
            menusData
                .filter(m => m.parent_menu === 0)
                .forEach(menu => {
                    const opt = document.createElement("option");
                    opt.value = menu.id;
                    opt.textContent = menu.title;
                    parentSelect.appendChild(opt);
                });
        }


        fields.parent.addEventListener("change", () => {
            const parentId = parseInt(fields.parent.value);
            if (parentId === 0) {
                document.querySelectorAll(".roleCheckbox").forEach(cb => {
                    cb.disabled = false;
                    cb.checked = false;
                });
            } else {
                const parentMenu = menusData.find(m => m.id === parentId);
                const parentRoles = parentMenu ? JSON.parse(parentMenu.allowed_roles || "[]") : [];
                document.querySelectorAll(".roleCheckbox").forEach(cb => {
                    cb.checked = parentRoles.includes(cb.value);
                    cb.disabled = true;
                });
            }
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            let checkedRoles = Array.from(document.querySelectorAll(".roleCheckbox:checked"))
                .map(cb => cb.value);

            let checkedOffices = Array.from(document.querySelectorAll(".officeCheckbox:checked"))
                .map(cb => cb.value);

            const parentId = parseInt(fields.parent.value);

            if (parentId !== 0) {
                const parentMenu = menusData.find(m => m.id === parentId);

                checkedRoles = parentMenu ? JSON.parse(parentMenu.allowed_roles || "[]") : [];
                checkedOffices = parentMenu ? JSON.parse(parentMenu.allowed_office || "[]") : [];
            }

            const payload = {
                title: fields.title.value,
                icon: fields.icon.value,
                link: fields.link.value,
                allowed_roles: JSON.stringify(checkedRoles),
                allowed_office: JSON.stringify(checkedOffices),
                parent_menu: fields.parent.value,
            };

            let url = `api/nav_menus`;
            let method = "POST";

            if (fields.id.value) {
                url = `api/nav_menus/${fields.id.value}`;
                method = "PUT";
            }

            const res = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json"
                },
                body: JSON.stringify(payload)
            });

            closeModal();
            await loadMenus();
            await loadRoles();
            await loadOffices();
        });

        tableBody.addEventListener("click", async (e) => {
            const btn = e.target.closest("button");
            if (!btn) return;

            const id = parseInt(btn.dataset.id);
            if (btn.classList.contains("move-up")) await swapMenu(id, "up");
            else if (btn.classList.contains("move-down")) await swapMenu(id, "down");
            else if (btn.classList.contains("delete-button")) await deleteMenu(id);
        });

        async function swapMenu(id, direction) {
            const current = menusData.find(m => m.id === id);
            const siblings = menusData.filter(m => m.parent_menu === current.parent_menu).sort((a, b) => a
                .menu_order - b.menu_order);
            const index = siblings.findIndex(m => m.id === id);

            let swapWith = null;
            if (direction === "up" && index > 0) swapWith = siblings[index - 1];
            if (direction === "down" && index < siblings.length - 1) swapWith = siblings[index + 1];

            if (!swapWith) return;

            await fetch(`api/nav_menus/swap`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                        .content,
                },
                body: JSON.stringify({
                    id1: current.id,
                    id2: swapWith.id
                }),
                credentials: "include"
            });

            loadMenus();
        }

        async function deleteMenu(id) {

            const confirmed = await customConfirm("Delete this menu?");
            if (!confirmed) return;
            await fetch(`api/nav_menus/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                        .content,
                },
                credentials: "include"
            });
            loadMenus();
        }

        const addBtn = document.getElementById("addMenuBtn");
        addBtn.addEventListener("click", () => openModal("Add"));

        loadRoles();
        loadMenus();
        loadOffices();
    })();
</script>
