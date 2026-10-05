document.addEventListener("DOMContentLoaded", function () {
  if (typeof window.initNotifications === "function") window.initNotifications();

  const sidebarWrapper = document.getElementById("sidebar-wrapper");
  const sidebarToggle = document.getElementById("sidebar-toggle");
  const sidebarOverlay = document.getElementById("sidebar-overlay");
  const sidebarMenu = document.getElementById("sidebar-menu");
  const sidebarBrand = document.getElementById("sidebar-brand");
  const collapseToggle = document.getElementById("sidebar-collapse-toggle");
  const collapseIcon = document.getElementById("sidebar-collapse-icon");
  const topnavMenu = document.getElementById("topnav-menu");
  const navLayout = document.getElementById("appShell")?.dataset.navLayout || "side";
  if (!sidebarMenu) return;

  // A plain vertical mouse wheel does nothing on an overflow-x-auto row -
  // let hovering the top-nav menu scroll it horizontally instead, only
  // when it actually has overflow to scroll.
  if (topnavMenu) {
    topnavMenu.addEventListener(
      "wheel",
      (e) => {
        if (topnavMenu.scrollWidth <= topnavMenu.clientWidth) return;
        e.preventDefault();
        topnavMenu.scrollLeft += e.deltaY;
      },
      { passive: false },
    );
  }

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

  // Exposes the sidebar's current width as a CSS custom property so fixed
  // overlays that need to sit beside it (not on top of it) - e.g. the
  // minimized-prospect-draft dock in crm.blade.php - can react to
  // collapse/expand without duplicating this width logic themselves. 0px
  // when nav_layout is "top": there's no side rail reserving space then.
  function syncSidebarWidthVar(collapsed) {
    document.documentElement.style.setProperty(
      "--sidebar-w",
      navLayout === "top" ? "0px" : collapsed ? "4rem" : "16rem"
    );
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

    // Category group labels (see createCategoryLabel()/groupMenusByCategory()
    // below) can't just reuse the .nav-label max-width/opacity treatment
    // above - they're block-level section headers, not inline text next to
    // an icon, so there's nothing for a collapsed max-width to visually
    // hide. Swap the label for a thin divider instead, so a group boundary
    // is still legible on the icon-only rail.
    document.querySelectorAll(".cat-label").forEach((el) => {
      el.classList.toggle("hidden", collapsed);
    });
    document.querySelectorAll(".cat-divider").forEach((el) => {
      el.classList.toggle("hidden", !collapsed);
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

    syncSidebarWidthVar(collapsed);
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
  syncSidebarWidthVar(isCollapsed());

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

  // Groups top-level menus by their (optional) category for the vertical
  // sidebar - see MenusController::index()/menuSettings.js for where
  // menu.category comes from. Items with no category render first, flat,
  // exactly like before this feature existed; categorized items follow as
  // one section per category, in first-appearance order (no separate
  // "category order" concept - it just follows whatever order the items
  // already have via menu_order). The horizontal top-nav layout
  // (topnavMenu, createTopNavItem()) deliberately keeps rendering the flat
  // `menus` list untouched - grouping only applies to the vertical rail.
  function groupMenusByCategory(menus) {
    const uncategorized = [];
    const groups = [];
    const groupIndex = new Map();

    menus.forEach((menu) => {
      const category = (menu.category || "").trim();
      if (!category) {
        uncategorized.push(menu);
        return;
      }
      if (!groupIndex.has(category)) {
        groupIndex.set(category, groups.length);
        groups.push({ category, items: [] });
      }
      groups[groupIndex.get(category)].items.push(menu);
    });

    return { uncategorized, groups };
  }

  // Thin section-boundary rule, shown only on the collapsed icon rail (see
  // applyCollapsed()) where there's no room for the text label below.
  function createCategoryDivider() {
    const divider = document.createElement("div");
    divider.className = "cat-divider hidden mx-2 my-1.5 border-t border-zinc-100 dark:border-zinc-800";
    return divider;
  }

  // Small-caps section label - same style already used for the collapsed
  // rail's flyout heading and this app's form eyebrows (VISUALS.md).
  function createCategoryLabel(category) {
    const label = document.createElement("div");
    label.className =
      "cat-label px-3 pt-3 pb-1 text-[11px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 truncate";
    label.textContent = category;
    return label;
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

  // -----------------------------------------------------------------
  // Top-nav dropdown - parallel to the collapsed-rail flyout above, but
  // anchored below the button instead of beside it, used for a top-level
  // top-nav item's children when the horizontal layout is active.
  // -----------------------------------------------------------------
  const topnavFlyoutEl = document.createElement("div");
  topnavFlyoutEl.className =
    "hidden fixed w-48 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-lg dark:shadow-black/40 py-1.5 z-50";
  document.body.appendChild(topnavFlyoutEl);

  let topnavFlyoutHideTimer = null;

  function showTopnavFlyout(anchorEl, menu) {
    clearTimeout(topnavFlyoutHideTimer);
    const rect = anchorEl.getBoundingClientRect();
    const margin = 8;
    // w-48 = 12rem; measuring via offsetWidth would need the panel visible
    // first, so use its declared width to keep this a single-pass calc.
    const panelWidth = topnavFlyoutEl.offsetWidth || 192;
    const left = Math.min(rect.left, window.innerWidth - panelWidth - margin);

    topnavFlyoutEl.style.top = `${rect.bottom + 4}px`;
    topnavFlyoutEl.style.left = `${Math.max(margin, left)}px`;

    topnavFlyoutEl.innerHTML = "";

    (menu.children || []).forEach((child) => {
      const childBtn = document.createElement("button");
      childBtn.type = "button";
      childBtn.className =
        "w-full text-left px-3 py-2 rounded text-zinc-700 dark:text-zinc-200 hover:bg-orange-50 dark:hover:bg-orange-950/40 flex items-center gap-2 menu child-menu";
      childBtn.innerHTML = `${iconSvg(child.icon)}<span class="truncate">${child.title || ""}</span>`;
      childBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        loadPage(child);
        hideTopnavFlyout();
      });
      topnavFlyoutEl.appendChild(childBtn);
    });

    topnavFlyoutEl.classList.remove("hidden");
  }

  function hideTopnavFlyout() {
    clearTimeout(topnavFlyoutHideTimer);
    topnavFlyoutEl.classList.add("hidden");
  }

  function hideTopnavFlyoutDelayed() {
    clearTimeout(topnavFlyoutHideTimer);
    topnavFlyoutHideTimer = setTimeout(() => topnavFlyoutEl.classList.add("hidden"), 150);
  }

  // Keeps the dropdown open while the pointer travels from the button
  // down into the dropdown itself (there's a gap between them).
  topnavFlyoutEl.addEventListener("mouseenter", () => clearTimeout(topnavFlyoutHideTimer));
  topnavFlyoutEl.addEventListener("mouseleave", hideTopnavFlyoutDelayed);

  // -----------------------------------------------------------------
  // Dismissal for non-hover/keyboard users - both flyouts above only
  // closed via a hover-out delay or by clicking a child link, leaving no
  // way to dismiss one that was opened by click (touch, or a hover-less
  // input). One shared click-outside + Escape handler covers both.
  // -----------------------------------------------------------------
  function hideAllFlyouts() {
    hideFlyout();
    hideTopnavFlyout();
  }

  document.addEventListener("click", (e) => {
    if (
      !flyoutEl.classList.contains("hidden") &&
      !flyoutEl.contains(e.target) &&
      !sidebarMenu.contains(e.target)
    ) {
      hideFlyout();
    }

    if (
      !topnavFlyoutEl.classList.contains("hidden") &&
      !topnavFlyoutEl.contains(e.target) &&
      !(topnavMenu && topnavMenu.contains(e.target))
    ) {
      hideTopnavFlyout();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") hideAllFlyouts();
  });

  //creates a horizontal top-nav item (parent and leaf), for #topnav-menu
  function createTopNavItem(menu) {
    const wrapper = document.createElement("div");
    wrapper.className = "relative shrink-0";

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className =
      "px-3 py-2 rounded text-sm text-zinc-700 dark:text-zinc-200 hover:bg-orange-50 dark:hover:bg-orange-950/40 flex items-center gap-2 menu";
    btn.innerHTML = `${iconSvg(menu.icon)}<span class="truncate">${menu.title || ""}</span>`;

    if (menu.children && menu.children.length) {
      const arrow = document.createElement("span");
      arrow.innerHTML = "▼";
      arrow.className = "text-xs shrink-0";
      btn.appendChild(arrow);

      wrapper.addEventListener("mouseenter", () => showTopnavFlyout(wrapper, menu));
      wrapper.addEventListener("mouseleave", hideTopnavFlyoutDelayed);

      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        showTopnavFlyout(wrapper, menu);
      });
    } else {
      btn.addEventListener("click", () => loadPage(menu));
    }

    wrapper.appendChild(btn);
    return wrapper;
  }

  //loads page to the content area
  // historyMode:
  //   "push"    (default) - a real user click on a nav item. Adds a new
  //             browser-history entry so Back returns to the previous page.
  //   "replace" - an automatic restore (initial boot, loadlastpage()) that
  //             shouldn't itself become a Back-able step.
  //   "none"    - triggered by popstate itself (the user already pressed
  //             Back/Forward, the browser already moved the history
  //             pointer - pushing/replacing again here would fight it).
  window.loadPage = async function loadPage(menu, { historyMode = "push" } = {}) {
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

      // Real URL + Back/Forward support: only for menu objects with a real
      // link (guards against the pre-existing loadlastpage() fallback paths
      // that call this with a bare string instead of a menu object).
      if (historyMode !== "none" && menu && typeof menu === "object" && menu.link) {
        const state = { loadPageMenu: menu };
        if (historyMode === "replace") {
          history.replaceState(state, "", menu.link);
        } else {
          history.pushState(state, "", menu.link);
        }
      }

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
      loadPage("/dashboard", { historyMode: "replace" });
      return;
    }

    try {
      lastMenu = JSON.parse(lastMenu);

      if (typeof lastMenu === "string") {
        loadPage(lastMenu, { historyMode: "replace" });
      } else if (lastMenu?.url) {
        loadPage(lastMenu.url, { historyMode: "replace" });
      } else {
        loadPage("/dashboard", { historyMode: "replace" });
      }
    } catch (e) {
      console.warn("Failed to parse lastMenu", e);
      loadPage("/dashboard", { historyMode: "replace" });
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

    if (topnavMenu && navLayout === "top") topnavMenu.innerHTML = "";

    let firstMenu = null;
    function appendSidebarItem(menu) {
      sidebarMenu.appendChild(createMenuItem(menu));
      if (!firstMenu && menu.title?.toLowerCase() === "dashboard")
        firstMenu = menu;
    }

    // Sidebar: uncategorized items flat first (today's behavior,
    // unchanged), then one labeled section per category. Top-nav: stays
    // flat/ungrouped, reading the same `menus` list in its original order -
    // see groupMenusByCategory()'s own comment for why.
    const { uncategorized, groups } = groupMenusByCategory(menus);
    uncategorized.forEach(appendSidebarItem);
    groups.forEach(({ category, items }) => {
      sidebarMenu.appendChild(createCategoryDivider());
      sidebarMenu.appendChild(createCategoryLabel(category));
      items.forEach(appendSidebarItem);
    });

    if (topnavMenu && navLayout === "top") {
      menus.forEach((menu) => topnavMenu.appendChild(createTopNavItem(menu)));
    }

    if (collapseToggle && localStorage.getItem("sidebarCollapsed") === "1") {
      applyCollapsed(true);
    }

    // A direct/shared link to a /page_* URL (see EnsureAppShellForDirectPageVisit
    // + dashboard.blade.php) - render the shell first, then load the actual
    // requested page into #content, taking priority over "resume where I
    // left off" below since the user (or whoever shared the link) asked for
    // this specific page.
    const directPageLink = document.getElementById("appShell")?.dataset.directPageLink || "";
    if (directPageLink) {
      const findByLink = (list) => {
        for (const m of list) {
          if (m.link === directPageLink) return m;
          if (m.children?.length) {
            const found = findByLink(m.children);
            if (found) return found;
          }
        }
        return null;
      };
      loadPage(findByLink(menus) || { title: "", link: directPageLink }, { historyMode: "replace" });
      return;
    }

    let lastMenu = localStorage.getItem("lastMenu");
    if (lastMenu) {
      try {
        lastMenu = JSON.parse(lastMenu);
        loadPage(lastMenu, { historyMode: "replace" });
        return;
      } catch (e) {
        console.warn("Failed to parse lastMenu", e);
      }
    }

    if (firstMenu) loadPage(firstMenu, { historyMode: "replace" });
    else if (menus.length) loadPage(menus[0], { historyMode: "replace" });
  })();

  // Back/Forward: only react to history entries this same loadPage() wrote
  // (see the { loadPageMenu } state shape above) - going back past the very
  // first SPA navigation lands on the plain /app entry with no such state,
  // which is left alone rather than guessed at.
  window.addEventListener("popstate", (e) => {
    if (e.state && e.state.loadPageMenu) {
      loadPage(e.state.loadPageMenu, { historyMode: "none" });
    }
  });
});
