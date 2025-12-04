document.addEventListener("DOMContentLoaded", function () {
  const sidebarWrapper = document.getElementById("sidebar-wrapper");
  const sidebarToggle = document.getElementById("sidebar-toggle");
  const sidebarOverlay = document.getElementById("sidebar-overlay");
  const sidebarMenu = document.getElementById("sidebar-menu");
  if (!sidebarMenu) return;

  //sidebar toogle for tablet and mobile view
  function toggleSidebar() {
    const isHidden = sidebarWrapper.classList.contains("-translate-x-full");

    if (isHidden) {
      sidebarWrapper.classList.remove("-translate-x-full");
      sidebarOverlay.classList.remove("hidden");
    } else {
      sidebarWrapper.classList.add("-translate-x-full");
      sidebarOverlay.classList.add("hidden");
    }
  }

  sidebarToggle.addEventListener("click", toggleSidebar);
  sidebarOverlay.addEventListener("click", toggleSidebar);

  window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
      sidebarWrapper.classList.remove("-translate-x-full");
      sidebarOverlay.classList.add("hidden");
    } else {
      sidebarWrapper.classList.add("-translate-x-full");
    }
  });

  sidebarMenu.addEventListener("click", (e) => {
    const link = e.target.closest("a");
    if (!link) return;
    if (window.innerWidth < 1024) toggleSidebar();
  });

  // fetch function with max retries implemented
  async function fetchWithRetry(url, options = {}, retries = 3, delay = 500) {
    for (let i = 0; i < retries; i++) {
      try {
        const res = await fetch(url, options);
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return await res.json();
      } catch (err) {
        console.warn(`Fetch attempt ${i + 1} failed for ${url}:`, err);
        if (i < retries - 1) await new Promise((r) => setTimeout(r, delay));
        else {
          console.error(`All fetch attempts failed for ${url}`);
          return null;
        }
      }
    }
  }

  //build tree of parent and child menu
  function buildTree(flat) {
    const map = {};
    flat.forEach((m) => (map[m.id] = { ...m, children: [] }));
    const roots = [];
    flat.forEach((m) => {
      if (m.parent_menu && m.parent_menu !== 0 && map[m.parent_menu]) {
        map[m.parent_menu].children.push(map[m.id]);
      } else {
        roots.push(map[m.id]);
      }
    });
    return roots;
  }

  //creates menu item (parent and child)
  function createMenuItem(menu) {
    const wrapper = document.createElement("div");
    wrapper.className = "w-full";

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className =
      "w-full text-left px-3 py-2 rounded hover:bg-gray-200 dark:text-white dark:hover:bg-gray-700 flex items-center justify-between gap-2 menu parentmenu";

    const left = document.createElement("span");
    left.className = "flex items-center gap-2";
    left.innerHTML = `<i class="${menu.icon || ""}"></i> ${menu.title || ""}`;
    btn.appendChild(left);

    wrapper.appendChild(btn);

    if (menu.children && menu.children.length) {
      const arrow = document.createElement("span");
      arrow.innerHTML = "▼";
      arrow.className = "text-xs transition-transform duration-200";
      btn.appendChild(arrow);

      const sub = document.createElement("div");
      sub.className =
        "space-y-1 bg-gray-100 dark:bg-gray-800 dark:text-gray-300 sub";
      sub.style.maxHeight = "0px";
      sub.style.overflow = "hidden";
      sub.style.transition = "max-height 250ms ease";

      menu.children.forEach((child) => {
        const childBtn = document.createElement("button");
        childBtn.type = "button";
        childBtn.className =
          "w-full text-left px-3 py-2 rounded hover:bg-gray-200 dark:text-white dark:hover:bg-gray-700 flex items-center gap-2 menu child-menu";
        childBtn.innerHTML = `<i class="${child.icon || ""}"></i> ${
          child.title || ""
        }`;
        childBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          loadPage(child);
          if (window.innerWidth < 1024) toggleSidebar();
        });
        sub.appendChild(childBtn);
      });

      wrapper.appendChild(sub);

      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const isOpen = sub.style.maxHeight !== "0px";
        sub.style.maxHeight = isOpen ? "0px" : sub.scrollHeight + "px";
        if (arrow) arrow.style.transform = isOpen ? "" : "rotate(180deg)";
      });
    } else {
      btn.addEventListener("click", () => {
        loadPage(menu);
        if (window.innerWidth < 1024) toggleSidebar();
      });
    }

    return wrapper;
  }

  //loads page to the content area
  async function loadPage(menu) {
    if (!menu) return;
    localStorage.setItem("lastMenu", JSON.stringify(menu));

    const menulist = document.querySelectorAll(".menu");
    const activemenu = document.querySelectorAll(".menu.active");
    const activepage = Array.from(menulist).find(
      (btn) => btn.textContent.trim() === menu.title
    );

    activemenu.forEach((activebttn) => {
      activebttn.classList.remove(
        "bg-blue-400",
        "hover:dark:bg-blue-600",
        "text-white",
        "active",
        "hover:bg-blue-600"
      );
      activebttn.classList.add("text-black", "hover:bg-gray-200");
    });

    if (activepage) {
      activepage.classList.add(
        "bg-blue-400",
        "hover:dark:bg-blue-600",
        "text-white",
        "active",
        "hover:bg-blue-600"
      );
      activepage.classList.remove("hover:bg-gray-200", "text-black");
    }

    const titleEl = document.getElementById("page-title");
    if (titleEl) titleEl.textContent = menu.title;

    const contentEl = document.getElementById("content");
    contentEl.innerHTML = `
            <div class="mx-auto w-full rounded-md p-4 animate-pulse mt-4">
                <div class="w-full flex space-x-4">
                    <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                    <div class="flex-1 space-y-6 py-1">
                        <div class="h-2 rounded bg-gray-200"></div>
                        <div class="space-y-3">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2 h-2 rounded bg-gray-200"></div>
                                <div class="col-span-1 h-2 rounded bg-gray-200"></div>
                            </div>
                            <div class="h-2 rounded bg-gray-200"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

    try {
      const res = await fetch(menu.link, {
        headers: { Accept: "application/json" },
      });
      if (!res.ok)
        throw new Error(`Failed to load page: ${res.status} ${res.statusText}`);
      const data = await res.text();
      contentEl.innerHTML = `<div class="dark p-4 dark:bg-gray-800 rounded shadow">${data}</div>`;

      // Execute inline scripts
      contentEl.querySelectorAll("script").forEach((oldScript) => {
        const newScript = document.createElement("script");
        if (oldScript.src) newScript.src = oldScript.src;
        else newScript.textContent = oldScript.textContent;
        document.body.appendChild(newScript);
        document.body.removeChild(newScript);
      });
    } catch (err) {
      contentEl.innerHTML = `<div class="bg-red-600 text-white rounded p-4">
                <strong>Error:</strong> ${err.message}
            </div>`;
      console.error(err);
    }
  }

  window.loadPage = loadPage;

  //initialize menu
  (async function initApp() {
    // const authData = await fetchWithRetry("api/debug_auth", {
    //   credentials: "include",
    //   headers: { Accept: "application/json" },
    // });

    const menuData = await fetchWithRetry("api/load_menu", {
      credentials: "include",
      headers: { Accept: "application/json" },
    });

    if (!menuData || !menuData.length)
      return console.warn("No menu data available");

    let menus = "children" in menuData[0] ? menuData : buildTree(menuData);
    sidebarMenu.innerHTML = "";

    let firstMenu = null;
    menus.forEach((menu) => {
      const node = createMenuItem(menu);
      sidebarMenu.appendChild(node);
      if (!firstMenu && menu.title?.toLowerCase() === "dashboard")
        firstMenu = menu;
    });

    let lastMenu = localStorage.getItem("lastMenu");
    if (lastMenu) {
      try {
        lastMenu = JSON.parse(lastMenu);
        loadPage(lastMenu);
        return;
      } catch (e) {
        console.warn("Failed to parse lastMenu", e);
      }
    }

    if (firstMenu) loadPage(firstMenu);
    else if (menus.length) loadPage(menus[0]);
  })();
});
