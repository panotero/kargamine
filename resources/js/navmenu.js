document.addEventListener("DOMContentLoaded", function () {
  if (typeof window.initNotifications === "function") window.initNotifications();

  const sidebarWrapper = document.getElementById("sidebar-wrapper");
  const sidebarToggle = document.getElementById("sidebar-toggle");
  const sidebarOverlay = document.getElementById("sidebar-overlay");
  const sidebarMenu = document.getElementById("sidebar-menu");
  const sidebarBrand = document.getElementById("sidebar-brand");
  const collapseToggle = document.getElementById("sidebar-collapse-toggle");
  const collapseIcon = document.getElementById("sidebar-collapse-icon");
  if (!sidebarMenu) return;

  const FALLBACK_ICON_SVG =
    '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5v-6.5zM13.75 3.75h6.5v6.5h-6.5v-6.5zM3.75 13.75h6.5v6.5h-6.5v-6.5zM13.75 13.75h6.5v6.5h-6.5v-6.5z" />';

  let navIcons = {};

  async function loadNavIcons() {
    const response = await fetchWithRetry("api/nav-icons", {
      credentials: "include",
      headers: { Accept: "application/json" },
    });
    (response?.data ?? []).forEach((icon) => (navIcons[icon.key] = icon.svg));
  }

  function iconSvg(key) {
    const inner = (key && navIcons[key]) || FALLBACK_ICON_SVG;
    return `<svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">${inner}</svg>`;
  }

  // -----------------------------------------------------------------
  // Collapsed-rail flyout - a single shared element portalled onto <body>
  // and positioned with fixed coordinates, reused for whichever parent
  // item is currently hovered. It must live outside the sidebar's own DOM
  // subtree: #sidebar-wrapper (and its inner nav container) use
  // overflow-hidden for the width-collapse animation, which would clip an
  // absolutely-positioned child flyout before it could ever be seen.
  // -----------------------------------------------------------------
  const flyoutEl = document.createElement("div");
  flyoutEl.className =
    "hidden fixed w-48 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-lg dark:shadow-black/40 py-1.5 z-50";
  document.body.appendChild(flyoutEl);

  let flyoutHideTimer = null;

  function showFlyout(anchorEl, menu) {
    clearTimeout(flyoutHideTimer);
    const rect = anchorEl.getBoundingClientRect();
    flyoutEl.style.top = `${rect.top}px`;
    flyoutEl.style.left = `${rect.right + 8}px`;

    flyoutEl.innerHTML = `<div class="px-3 py-1 text-xs font-semibold text-zinc-400 uppercase tracking-widest">${menu.title || ""}</div>`;

    (menu.children || []).forEach((child) => {
      const childBtn = document.createElement("button");
      childBtn.type = "button";
      childBtn.className =
        "w-full text-left px-3 py-2 rounded text-zinc-700 dark:text-zinc-200 hover:bg-orange-50 dark:hover:bg-orange-950/40 flex items-center gap-2 menu child-menu";
      childBtn.innerHTML = `${iconSvg(child.icon)}<span class="truncate">${child.title || ""}</span>`;
      childBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        loadPage(child);
        hideFlyout();
      });
      flyoutEl.appendChild(childBtn);
    });

    flyoutEl.classList.remove("hidden");
  }

  function hideFlyout() {
    clearTimeout(flyoutHideTimer);
    flyoutEl.classList.add("hidden");
  }

  function hideFlyoutDelayed() {
    clearTimeout(flyoutHideTimer);
    flyoutHideTimer = setTimeout(() => flyoutEl.classList.add("hidden"), 150);
  }

  // Keeps the flyout open while the pointer travels from the icon rail
  // onto the flyout itself (there's a gap between them).
  flyoutEl.addEventListener("mouseenter", () => clearTimeout(flyoutHideTimer));
  flyoutEl.addEventListener("mouseleave", hideFlyoutDelayed);

  // -----------------------------------------------------------------
  // Collapsed-rail tooltip - shows a menu item's name on hover, but only
  // for items with no children (a parent already reveals its name via the
  // flyout above it, so it doesn't need this too). Same portal-to-<body>
  // approach as the flyout, for the same overflow-clipping reason.
  // -----------------------------------------------------------------
  const tooltipEl = document.createElement("div");
  tooltipEl.className =
    "hidden fixed whitespace-nowrap bg-zinc-900 dark:bg-zinc-700 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg shadow-lg z-50";
  document.body.appendChild(tooltipEl);

  function showTooltip(anchorEl, text) {
    const rect = anchorEl.getBoundingClientRect();
    tooltipEl.textContent = text || "";
    tooltipEl.classList.remove("hidden");
    // Measure after making it visible so offsetHeight is accurate.
    tooltipEl.style.top = `${rect.top + rect.height / 2 - tooltipEl.offsetHeight / 2}px`;
    tooltipEl.style.left = `${rect.right + 8}px`;
  }

  function hideTooltip() {
    tooltipEl.classList.add("hidden");
  }

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

  // -----------------------------------------------------------------
  // Desktop collapse - shrinks the sidebar to an icon-only rail instead
  // of hiding it entirely. Persisted so it stays collapsed across page
  // loads/reloads until the user expands it again.
  // -----------------------------------------------------------------
  function isCollapsed() {
    return sidebarWrapper.classList.contains("w-16");
  }

  function applyCollapsed(collapsed) {
    sidebarWrapper.classList.toggle("w-16", collapsed);
    sidebarWrapper.classList.toggle("w-64", !collapsed);
    sidebarBrand.classList.toggle("opacity-0", collapsed);
    sidebarBrand.classList.toggle("invisible", collapsed);
    collapseIcon.style.transform = collapsed ? "rotate(180deg)" : "";

    // max-w-0 (not just opacity/invisible) so the label actually stops
    // occupying flex space once collapsed - otherwise its full-width text
    // node keeps pushing the icon off-center inside the narrow icon rail,
    // even though it's invisible.
    document.querySelectorAll(".nav-label").forEach((el) => {
      el.classList.toggle("max-w-0", collapsed);
      el.classList.toggle("max-w-[10rem]", !collapsed);
      el.classList.toggle("opacity-0", collapsed);
      el.classList.toggle("opacity-100", !collapsed);
    });

    // The rail is much narrower than the sidebar's own p-4 + the button's
    // own px-3 combined leave room for, which is what made the icon look
    // crooked/off-center - drop the button's own horizontal padding and
    // center its (now label-less) content while collapsed. gap-2 is also
    // dropped since it still reserves space between the icon and a
    // zero-width label/arrow, which is enough to visibly nudge the icon
    // off true-center even after the width collapse above.
    document.querySelectorAll(".parentmenu").forEach((el) => {
      el.classList.toggle("px-3", !collapsed);
      el.classList.toggle("px-0", collapsed);
      el.classList.toggle("justify-between", !collapsed);
      el.classList.toggle("justify-center", collapsed);
      el.classList.toggle("gap-2", !collapsed);
      el.classList.toggle("gap-0", collapsed);
    });

    document.querySelectorAll(".nav-icon-row").forEach((el) => {
      el.classList.toggle("gap-2", !collapsed);
      el.classList.toggle("gap-0", collapsed);
    });

    // Collapsing while a submenu is open would leave it visually floating
    // with no parent label - close every open accordion instead.
    if (collapsed) {
      document.querySelectorAll(".sub").forEach((sub) => {
        sub.style.maxHeight = "0px";
      });
      document.querySelectorAll(".menu-arrow").forEach((arrow) => {
        arrow.style.transform = "";
      });
    } else {
      hideFlyout();
      hideTooltip();
    }
  }

  if (collapseToggle) {
    collapseToggle.addEventListener("click", () => {
      const next = !isCollapsed();
      applyCollapsed(next);
      localStorage.setItem("sidebarCollapsed", next ? "1" : "0");
    });

    if (localStorage.getItem("sidebarCollapsed") === "1") {
      applyCollapsed(true);
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
    wrapper.className = "w-full relative";

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className =
      "w-full text-left px-3 py-2 rounded text-zinc-700 dark:text-zinc-200 hover:bg-orange-50 dark:hover:bg-orange-950/40 flex items-center justify-between gap-2 menu parentmenu";

    const left = document.createElement("span");
    left.className = "flex items-center gap-2 min-w-0 nav-icon-row";
    const labelSpan = `<span class="nav-label truncate overflow-hidden max-w-[10rem] opacity-100 transition-all duration-150">${menu.title || ""}</span>`;
    left.innerHTML = `${iconSvg(menu.icon)}${labelSpan}`;
    btn.appendChild(left);

    wrapper.appendChild(btn);

    if (menu.children && menu.children.length) {
      const arrow = document.createElement("span");
      arrow.innerHTML = "▼";
      arrow.className =
        "text-xs overflow-hidden max-w-[10rem] opacity-100 transition-all duration-200 nav-label menu-arrow shrink-0";
      btn.appendChild(arrow);

      const sub = document.createElement("div");
      sub.className =
        "space-y-1 bg-zinc-50 dark:bg-zinc-800 dark:text-zinc-300 sub";
      sub.style.maxHeight = "0px";
      sub.style.overflow = "hidden";
      sub.style.transition = "max-height 250ms ease";

      menu.children.forEach((child) => {
        const childBtn = document.createElement("button");
        childBtn.type = "button";
        childBtn.className =
          "w-full text-left px-3 py-2 rounded text-zinc-700 dark:text-zinc-200 hover:bg-orange-50 dark:hover:bg-orange-950/40 flex items-center gap-2 menu child-menu";
        childBtn.innerHTML = `${iconSvg(child.icon)}<span class="nav-label truncate overflow-hidden max-w-[10rem] opacity-100 transition-all duration-150">${child.title || ""}</span>`;
        childBtn.addEventListener("click", (e) => {
          e.stopPropagation();
          loadPage(child);
          if (window.innerWidth < 1024) toggleSidebar();
        });
        sub.appendChild(childBtn);
      });

      wrapper.appendChild(sub);

      // Collapsed-rail flyout - a parent icon with children still needs a
      // way to reach them without expanding the whole sidebar back out.
      wrapper.addEventListener("mouseenter", () => {
        if (isCollapsed()) showFlyout(wrapper, menu);
      });
      wrapper.addEventListener("mouseleave", () => {
        if (isCollapsed()) hideFlyoutDelayed();
      });

      btn.addEventListener("click", (e) => {
        e.stopPropagation();

        if (isCollapsed()) {
          showFlyout(wrapper, menu);
          return;
        }

        const isOpen = sub.style.maxHeight !== "0px";
        sub.style.maxHeight = isOpen ? "0px" : sub.scrollHeight + "px";
        arrow.style.transform = isOpen ? "" : "rotate(180deg)";
      });
    } else {
      btn.addEventListener("click", () => {
        loadPage(menu);
        if (window.innerWidth < 1024) toggleSidebar();
      });

      // No children - the flyout above only applies to parents, so this is
      // the item's only way to reveal its name while the rail is collapsed.
      wrapper.addEventListener("mouseenter", () => {
        if (isCollapsed()) showTooltip(wrapper, menu.title);
      });
      wrapper.addEventListener("mouseleave", hideTooltip);
    }

    return wrapper;
  }

  //loads page to the content area
  window.loadPage = async function loadPage(menu) {
    window.pageLoaded = false;
    if (!menu) return;
    localStorage.setItem("lastMenu", JSON.stringify(menu));

    const menulist = document.querySelectorAll(".menu");
    const activemenu = document.querySelectorAll(".menu.active");
    const activepage = Array.from(menulist).find(
      (btn) => btn.textContent.trim() === menu.title,
    );

    const ACTIVE_CLASSES = [
      "bg-orange-500",
      "dark:bg-orange-600",
      "text-white",
      "active",
      "hover:bg-orange-600",
      "dark:hover:bg-orange-700",
    ];
    const INACTIVE_CLASSES = [
      "text-zinc-700",
      "dark:text-zinc-200",
      "hover:bg-orange-50",
      "dark:hover:bg-orange-950/40",
    ];

    activemenu.forEach((activebttn) => {
      activebttn.classList.remove(...ACTIVE_CLASSES);
      activebttn.classList.add(...INACTIVE_CLASSES);
    });

    if (activepage) {
      activepage.classList.add(...ACTIVE_CLASSES);
      activepage.classList.remove(...INACTIVE_CLASSES);
    }

    const titleEl = document.getElementById("page-title");
    if (titleEl) titleEl.textContent = menu.title;

    const contentEl = document.getElementById("content");
    contentEl.innerHTML = initLoading();

    try {
      // Abort previous fetch if any
      if (window.controller) {
        window.controller.abort(); // abort ongoing fetch
      }

      // Reinitialize controller for the new fetch
      window.controller = new AbortController();

      const res = await fetch(menu.link, {
        headers: { Accept: "application/json" },
        signal: window.controller.signal,
      });

      if (res.status === 401) {
        window.location.reload();
        return;
      }
      if (!res.ok)
        throw new Error(`Failed to load page: ${res.status} ${res.statusText}`);
      const data = await res.text();
      contentEl.innerHTML = `<div class="dark lg:p-4 dark:bg-zinc-800 rounded shadow">${data}</div>`;

      window.pageLoaded = true;

      // Execute inline scripts
      contentEl.querySelectorAll("script").forEach((oldScript) => {
        const newScript = document.createElement("script");
        if (oldScript.src) newScript.src = oldScript.src;
        else newScript.textContent = oldScript.textContent;
        document.body.appendChild(newScript);
        document.body.removeChild(newScript);
      });
    } catch (err) {
      if (err.name === "AbortError") {
        return; // simply exit
      }
      contentEl.innerHTML = `<div class="bg-red-600 text-white rounded p-4">
                <strong>Error:</strong> ${err.message}
            </div>`;
      console.error(err);
    }
  };

  window.loadPage = loadPage;

  window.loadlastpage = async function loadlastpage() {
    const navType = performance.getEntriesByType("navigation")[0]?.type;

    // Only run on real reload
    if (navType !== "reload") return;

    let lastMenu = localStorage.getItem("lastMenu");

    if (!lastMenu) {
      loadPage("/dashboard");
      return;
    }

    try {
      lastMenu = JSON.parse(lastMenu);

      if (typeof lastMenu === "string") {
        loadPage(lastMenu);
      } else if (lastMenu?.url) {
        loadPage(lastMenu.url);
      } else {
        loadPage("/dashboard");
      }
    } catch (e) {
      console.warn("Failed to parse lastMenu", e);
      loadPage("/dashboard");
    }
  };
  //initialize menu
  (async function initApp() {
    // const authData = await fetchWithRetry("api/debug_auth", {
    //   credentials: "include",
    //   headers: { Accept: "application/json" },
    // });

    await loadNavIcons();

    const menuData = await fetchWithRetry("api/load_menu", {
      method: "GET",
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

    if (collapseToggle && localStorage.getItem("sidebarCollapsed") === "1") {
      applyCollapsed(true);
    }

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
