// Logic for the "Navigation Menus" admin page (pages/settings/menus.blade.php).
// Bootstrapped by a one-line inline <script> in that view, since pages are
// injected into #content via AJAX (see navmenu.js loadPage()) and this
// module is bundled globally rather than fetched with the page fragment.
window.initMenuSettingsPage = function initMenuSettingsPage() {
  const modal = document.getElementById("menuModal");
  const cancelBtn = document.getElementById("cancelModalBtn");
  const form = document.getElementById("menuForm");
  const saveBtn = document.getElementById("saveMenuBtn");
  const modalTitle = document.getElementById("modalTitle");
  const tableBody = document.getElementById("navMenuTbody");
  if (!tableBody) return;

  const ROW_MOVE_DURATION = 700; // ms, keep in sync with the duration-700 class below
  const ROW_HIGHLIGHT_DURATION = 1000; // ms, keep in sync with the duration-1000 class below

  const fields = {
    id: document.getElementById("menuId"),
    title: document.getElementById("menuTitle"),
    icon: document.getElementById("menuIcon"),
    link: document.getElementById("menuLink"),
    rolesContainer: document.getElementById("menuRolesContainer"),
    parent: document.getElementById("menuParent"),
  };

  let menusData = [];

  cancelBtn.addEventListener("click", () => closeModal());

  function openModal(mode = "Add", menu = null) {
    modalTitle.textContent = mode === "Add" ? "Add New Menu" : "Modify Menu";
    saveBtn.textContent = mode === "Add" ? "Save" : "Modify";

    if (menu) {
      fields.id.value = menu.id;
      fields.title.value = menu.title || "";
      fields.icon.value = menu.icon || "";
      fields.link.value = menu.link || "";
      fields.parent.value = menu.parent_menu || 0;

      const allowedRoles = JSON.parse(menu.allowed_roles || "[]");
      document.querySelectorAll(".roleCheckbox").forEach((cb) => {
        cb.checked = allowedRoles.includes(cb.value);
        cb.disabled = menu.parent_menu !== 0;
      });
    } else {
      fields.id.value = "";
      fields.title.value = "";
      fields.icon.value = "";
      fields.link.value = "";
      fields.parent.value = 0;
      document.querySelectorAll(".roleCheckbox").forEach((cb) => {
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
    const roles = await fetchWithRetry(`/api/roles`, {
      headers: { Accept: "application/json" },
    });

    const container = fields.rolesContainer;
    if (!container) return;

    container.innerHTML = "";

    const checkAllWrapper = document.createElement("label");
    checkAllWrapper.className = "flex items-center gap-2 mb-2";

    const checkAllBox = document.createElement("input");
    checkAllBox.type = "checkbox";
    checkAllBox.id = "checkAllRoles";
    checkAllBox.className = "cursor-pointer";

    const checkAllText = document.createElement("span");
    checkAllText.className = "font-medium text-gray-700";
    checkAllText.textContent = "Check All";

    checkAllWrapper.append(checkAllBox, checkAllText);
    container.appendChild(checkAllWrapper);

    roles.forEach(({ id, role_name }) => {
      const wrapper = document.createElement("label");
      wrapper.className = "flex items-center gap-2";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.value = id;
      checkbox.dataset.roleName = role_name;
      checkbox.className = "roleCheckbox cursor-pointer";

      const labelText = document.createElement("span");
      labelText.textContent = role_name;

      wrapper.append(checkbox, labelText);
      container.appendChild(wrapper);
    });

    checkAllBox.addEventListener("change", () => {
      container
        .querySelectorAll(".roleCheckbox")
        .forEach((cb) => (cb.checked = checkAllBox.checked));
    });
  }

  // Captures each row's current position (by menu id) before the table body
  // is rebuilt, so the rebuild below can FLIP-animate rows into their new spot.
  function captureRowPositions() {
    const positions = new Map();
    tableBody.querySelectorAll("tr[data-menu-id]").forEach((tr) => {
      positions.set(tr.dataset.menuId, tr.getBoundingClientRect().top);
    });
    return positions;
  }

  // Plays the FLIP animation: rows slide from their previous position into
  // their new one, and rows in `highlightIds` get a brief color flash so the
  // user can clearly see which item moved and in which direction it went.
  function animateRowPositions(previousPositions, highlightIds = []) {
    const rows = tableBody.querySelectorAll("tr[data-menu-id]");

    rows.forEach((tr) => {
      const prevTop = previousPositions.get(tr.dataset.menuId);
      if (prevTop === undefined) return;

      const delta = prevTop - tr.getBoundingClientRect().top;
      if (delta === 0) return;

      // Jump the row back to where it used to be, with no transition...
      tr.style.transition = "none";
      tr.style.transform = `translateY(${delta}px)`;

      // ...then, on the next frame, let the transition classes take over
      // and animate it to its resting position (transform: none).
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          tr.classList.add(
            "transition-transform",
            "duration-700",
            "ease-in-out",
          );
          tr.style.transition = "";
          tr.style.transform = "";

          setTimeout(() => {
            tr.classList.remove(
              "transition-transform",
              "duration-700",
              "ease-in-out",
            );
          }, ROW_MOVE_DURATION);
        });
      });
    });

    highlightIds.forEach((id) => {
      const tr = tableBody.querySelector(`tr[data-menu-id="${id}"]`);
      if (!tr) return;

      tr.classList.add(
        "transition-colors",
        "duration-1000",
        "ease-out",
        "bg-blue-100",
        "dark:bg-blue-900/40",
      );

      setTimeout(() => {
        tr.classList.remove(
          "transition-colors",
          "duration-1000",
          "ease-out",
          "bg-blue-100",
          "dark:bg-blue-900/40",
        );
      }, ROW_HIGHLIGHT_DURATION);
    });
  }

  async function loadMenus({ highlightIds = [] } = {}) {
    const previousPositions = captureRowPositions();

    menusData = await fetchWithRetry(`api/nav_menus/list`, {
      credentials: "include",
      headers: { Accept: "application/json" },
    });
    tableBody.innerHTML = "";

    const menuMap = {};
    menusData.forEach((menu) => (menuMap[menu.id] = menu.title));

    function renderMenuRows(parentId = 0, level = 0) {
      const children = menusData
        .filter((m) => m.parent_menu === parentId)
        .sort((a, b) => a.menu_order - b.menu_order);

      children.forEach((menu) => {
        const tr = document.createElement("tr");
        tr.classList.add(
          "cursor-pointer",
          "hover:bg-zinc-50",
          "dark:hover:bg-zinc-800",
          "transition-colors",
          "duration-100",
        );
        tr.dataset.menuId = menu.id;

        const parentName =
          menu.parent_menu === 0
            ? "Main Menu"
            : menuMap[menu.parent_menu] || "Unknown";

        const siblings = menusData
          .filter((m) => m.parent_menu === menu.parent_menu)
          .sort((a, b) => a.menu_order - b.menu_order);
        const index = siblings.findIndex((m) => m.id === menu.id);

        const moveUpHidden = index === 0 ? "hidden" : "";
        const moveDownHidden = index === siblings.length - 1 ? "hidden" : "";
        const roles = JSON.parse(menu.allowed_roles).join(", ");
        const targetpage = menu.link.replace(/^\//, "");

        const indent =
          "&nbsp;".repeat(level * 6) +
          (level > 0 ? '<span class="text-zinc-400">↳</span> ' : "");

        tr.innerHTML = `
                <td class="px-4 py-2.5 text-black dark:text-white">${indent}${menu.title}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${targetpage || ""}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${roles || ""}</td>
                <td class="px-4 py-2.5 text-black dark:text-white">${parentName}</td>
                <td class="menubuttons px-4 py-2.5 text-center">
                    <button class="move-up p-1.5 text-zinc-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/40 rounded-lg transition ${moveUpHidden}" data-id="${menu.id}" title="Move up">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    <button class="move-down p-1.5 text-zinc-400 hover:text-orange-600 hover:bg-orange-50 dark:hover:bg-orange-950/40 rounded-lg transition ${moveDownHidden}" data-id="${menu.id}" title="Move down">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </td>
                <td class="menubuttons px-4 py-2.5 text-center">
                    <button class="delete-button p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition" data-id="${menu.id}" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 6h18v2H3V6zm2 3h14l-1.5 12.5a1 1 0 0 1-1 .5H8a1 1 0 0 1-1-.5L5 9zm5 2v8h2v-8H10zm4 0v8h2v-8h-2zM9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1h5v2H4V4h5z"/>
                        </svg>
                    </button>
                </td>
            `;

        tr.addEventListener("click", (e) => {
          if (e.target.closest("td.menubuttons")) return;

          modalTitle.textContent = "Modify Menu";
          saveBtn.textContent = "Modify";

          fields.id.value = menu.id;
          fields.title.value = menu.title || "";
          fields.icon.value = menu.icon || "";
          fields.link.value = menu.link || "";
          fields.parent.value = menu.parent_menu || 0;

          const allowedRoles = JSON.parse(menu.allowed_roles || "[]");
          document.querySelectorAll(".roleCheckbox").forEach((cb) => {
            cb.checked = allowedRoles.includes(cb.value);
          });

          modal.classList.remove("hidden");
          modal.classList.add("flex");
        });

        tableBody.appendChild(tr);

        renderMenuRows(menu.id, level + 1);
      });
    }

    renderMenuRows(0);
    animateRowPositions(previousPositions, highlightIds);

    const parentSelect = fields.parent;
    parentSelect.innerHTML = `<option value="0">Main Menu</option>`;
    menusData
      .filter((m) => m.parent_menu === 0)
      .forEach((menu) => {
        const opt = document.createElement("option");
        opt.value = menu.id;
        opt.textContent = menu.title;
        parentSelect.appendChild(opt);
      });
  }

  fields.parent.addEventListener("change", () => {
    const parentId = parseInt(fields.parent.value);
    if (parentId === 0) {
      document.querySelectorAll(".roleCheckbox").forEach((cb) => {
        cb.disabled = false;
        cb.checked = false;
      });
    } else {
      const parentMenu = menusData.find((m) => m.id === parentId);
      const parentRoles = parentMenu
        ? JSON.parse(parentMenu.allowed_roles || "[]")
        : [];
      document.querySelectorAll(".roleCheckbox").forEach((cb) => {
        cb.checked = parentRoles.includes(cb.value);
        cb.disabled = true;
      });
    }
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    let checkedRoles = Array.from(
      document.querySelectorAll(".roleCheckbox:checked"),
    ).map((cb) => cb.value);

    const parentId = parseInt(fields.parent.value);

    if (parentId !== 0) {
      const parentMenu = menusData.find((m) => m.id === parentId);
      checkedRoles = parentMenu
        ? JSON.parse(parentMenu.allowed_roles || "[]")
        : [];
    }

    const payload = {
      title: fields.title.value,
      icon: fields.icon.value,
      link: fields.link.value,
      allowed_roles: JSON.stringify(checkedRoles),
      parent_menu: fields.parent.value,
    };

    let url = `api/nav_menus`;
    let method = "POST";

    if (fields.id.value) {
      url = `api/nav_menus/${fields.id.value}`;
      method = "PUT";
    }

    await fetchWithRetry(url, {
      method,
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(payload),
    });

    closeModal();
    await loadMenus();
    await loadRoles();
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
    const current = menusData.find((m) => m.id === id);
    const siblings = menusData
      .filter((m) => m.parent_menu === current.parent_menu)
      .sort((a, b) => a.menu_order - b.menu_order);
    const index = siblings.findIndex((m) => m.id === id);

    let swapWith = null;
    if (direction === "up" && index > 0) swapWith = siblings[index - 1];
    if (direction === "down" && index < siblings.length - 1)
      swapWith = siblings[index + 1];

    if (!swapWith) return;

    await fetchWithRetry(`api/nav_menus/swap`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify({ id1: current.id, id2: swapWith.id }),
      credentials: "include",
    });

    await loadMenus({ highlightIds: [current.id, swapWith.id] });
  }

  async function deleteMenu(id) {
    const confirmed = await customConfirm("Delete this menu?");
    if (!confirmed) return;
    await fetchWithRetry(`api/nav_menus/${id}`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      credentials: "include",
    });
    await loadMenus();
  }

  const addBtn = document.getElementById("addMenuBtn");
  addBtn.addEventListener("click", () => openModal("Add"));

  loadRoles();
  loadMenus();
};
