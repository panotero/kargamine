window.initCrmLogic = function initCrmLogic() {
  // initCrmLogic() re-runs every time the SPA shell swaps in the CRM page
  // fragment (routes/page.php -> loadPage()), but the document-level
  // delegated listeners below bind to `document`, which persists across
  // page swaps. Without this guard each revisit to the CRM page stacks
  // another copy of every delegated listener, so a single click (e.g. the
  // empty-state "+ New Lead" button) fires its handler once per prior visit.
  const bindDelegatedListeners = !window.__crmDelegatedListenersBound;
  window.__crmDelegatedListenersBound = true;

  // ============================================================
  // CONSTANTS
  // ============================================================

  const STATUS_BADGE = {
    LEAD: "bg-gray-100 text-gray-700",
    QUALIFIED: "bg-indigo-100 text-indigo-700",
    OPPORTUNITY: "bg-purple-100 text-purple-700",
    NEGOTIATION: "bg-amber-100 text-amber-700",
    WIN: "bg-green-100 text-green-700",
    LOST: "bg-red-100 text-red-700",
    DEFAULT: "bg-zinc-100 text-zinc-700",
  };

  const ROW_CLICK_ICON = {
    edit: `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>`,
    view: `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle></svg>`,
  };

  // ============================================================
  // HELPERS
  // ============================================================

  function getStatusBadgeClass(status) {
    return STATUS_BADGE[status] ?? STATUS_BADGE.DEFAULT;
  }

  // ============================================================
  // API
  // ============================================================

  async function getleadcount() {
    const leads = await apiCall({
      mode: "GET",
      url: "/api/crm/prospects",
    });
    return leads;
  }

  // ============================================================
  // RENDER — TABLE
  // ============================================================

  // Staleness tiers for "Last Activity" - shared by the table cell's dot and
  // the row-level left-border accent so the two thresholds never drift apart.
  // null (no activity yet) is treated the same as the 14+ day tier.
  function getStalenessTier(lastActivityAt) {
    if (!lastActivityAt) return "red";

    const daysAgo =
      (Date.now() - new Date(lastActivityAt).getTime()) /
      (1000 * 60 * 60 * 24);

    if (daysAgo >= 14) return "red";
    if (daysAgo >= 7) return "amber";
    return null;
  }

  // Whether any leads-list filter is currently active - drives which empty
  // state (quiet "no leads yet" vs. prompting "no leads match these filters")
  // renderTable()'s emptyMessage shows.
  function crmFiltersActive() {
    const search = document
      .querySelector("#tableCrm .table-search-input")
      ?.value.trim();
    const status = document.querySelector(".statusBtn.ring-2")?.dataset
      .status;
    const assignedTo = document.getElementById("crmAssignedToFilter")?.value;
    const needsAttention =
      document.getElementById("crmNeedsAttentionToggle")?.dataset.active ===
      "true";

    return Boolean(
      search || (status && status !== "ALL") || assignedTo || needsAttention,
    );
  }

  function crmEmptyState() {
    if (!crmFiltersActive()) {
      return `
        <div class="border-2 border-dashed border-orange-300 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-6 flex flex-col items-center text-center gap-2">
            <span class="text-[11px] font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">No leads yet</span>
            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Once you add your first lead, it'll show up here.</p>
            <button type="button" id="crmEmptyNewLeadBtn"
                class="mt-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                + New Lead
            </button>
        </div>`;
    }

    return `
        <div class="border-2 border-dashed border-orange-300 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-6 flex flex-col items-center text-center gap-2">
            <span class="text-[11px] font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">No leads match these filters</span>
            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Try widening your search or clearing the active filters.</p>
            <button type="button" id="crmEmptyClearFiltersBtn"
                class="mt-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                Clear Filters
            </button>
        </div>`;
  }

  // Delegated - both empty-state buttons are (re)rendered inside the table
  // body's innerHTML, so they can't be bound once via getElementById.
  if (bindDelegatedListeners) {
    document.addEventListener("click", function (e) {
      if (e.target.closest("#crmEmptyNewLeadBtn")) {
        document.getElementById("btnNewLead")?.click();
      }
      if (e.target.closest("#crmEmptyClearFiltersBtn")) {
        clearCrmFilters();
      }
    });
  }

  function clearCrmFilters() {
    const searchInput = document.querySelector("#tableCrm .table-search-input");
    if (searchInput) searchInput.value = "";

    document
      .querySelectorAll(".statusBtn")
      .forEach((btn) => btn.classList.remove("ring-2", "ring-orange-500"));
    document
      .querySelector('.statusBtn[data-status="ALL"]')
      ?.classList.add("ring-2", "ring-orange-500");

    const assignedToFilter = document.getElementById("crmAssignedToFilter");
    if (assignedToFilter) assignedToFilter.value = "";

    setNeedsAttentionActive(false);

    const table = renderTable();
    table.setFilters({
      status: "",
      assigned_to: "",
      needs_attention: "",
    });
    table.search("");
  }

  function setNeedsAttentionActive(active) {
    const toggle = document.getElementById("crmNeedsAttentionToggle");
    if (!toggle) return;

    toggle.dataset.active = active ? "true" : "false";
    toggle.classList.toggle("bg-red-50", active);
    toggle.classList.toggle("dark:bg-red-950/20", active);
    toggle.classList.toggle("border-red-300", active);
    toggle.classList.toggle("dark:border-red-800", active);
    toggle.classList.toggle("text-red-600", active);
    toggle.classList.toggle("dark:text-red-400", active);
    // The base zinc border/text classes must come out while active, not just
    // have the red ones added alongside them - two utility classes for the
    // same property have equal specificity, so whichever is later in the
    // compiled stylesheet silently wins regardless of which was added last.
    toggle.classList.toggle("border-zinc-300", !active);
    toggle.classList.toggle("dark:border-zinc-700", !active);
    toggle.classList.toggle("text-zinc-500", !active);
    toggle.classList.toggle("dark:text-zinc-400", !active);
  }

  function renderTable() {
    const thead = [
      {
        title: "Contact",
        key: "contact_name",
        render: (row) => renderContactCell(row),
      },
      {
        title: "Status",
        key: "crm_status.status",
        render: (row) => renderStatusCell(row),
      },
      {
        title: "Assigned To",
        key: "user.name",
      },
      {
        title: "Last Activity",
        key: "last_activity_at",
        render: (row) => renderLastActivityCell(row.last_activity_at),
      },
      {
        title: "Created",
        key: "created_at",
        render: (row) => formatDateTime(row.created_at),
      },
    ];
    const table = renderRemoteTable({
      url: "/api/crm/prospects",
      tableId: "tableCrm",
      afterRenderFunction: handleClick,
      thead: thead,
      emptyMessage: crmEmptyState,
    });

    function renderContactCell(row) {
      const company = row.company?.company_name ?? "-";

      return `
        <div class="flex flex-col">
            <span class="font-medium text-zinc-800 dark:text-zinc-100">${row.contact_name ?? "-"}</span>
            <span class="text-xs text-zinc-400">${company} · ${row.email ?? "-"} · ${row.mobile ?? "-"}</span>
        </div>`;
    }

    function renderStatusCell(row) {
      const status = row.crm_status?.status;
      // Every row now opens the same Prospect modal (edit mode) - see
      // handleClick() below - so the affordance icon is always "Edit".
      const icon = ROW_CLICK_ICON.edit;
      const tooltip = "Edit";
      const badgeClass = getStatusBadgeClass(status);

      return `
        <span class="inline-flex items-center gap-1.5">
            <span title="${tooltip}" class="text-zinc-400" aria-label="${tooltip}">${icon}</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium ${badgeClass}">${status ?? "-"}</span>
        </span>`;
    }

    function renderLastActivityCell(lastActivityAt) {
      if (!lastActivityAt) {
        return `<span class="text-zinc-400">No activity</span>`;
      }

      const tier = getStalenessTier(lastActivityAt);
      const dotClass =
        tier === "red"
          ? "bg-red-500"
          : tier === "amber"
            ? "bg-amber-500"
            : "bg-zinc-300";

      return `
        <span class="inline-flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full inline-block ${dotClass}"></span>
            <span>${formatDateTime(lastActivityAt)}</span>
        </span>`;
    }

    function handleClick(row) {
      const data = JSON.parse(row.dataset.row);
      const tier = getStalenessTier(data.last_activity_at);

      row.classList.remove(
        "border-l-[3px]",
        "border-l-red-500",
        "border-l-amber-500",
      );
      if (tier === "red") {
        row.classList.add("border-l-[3px]", "border-l-red-500");
      } else if (tier === "amber") {
        row.classList.add("border-l-[3px]", "border-l-amber-500");
      }

      // openProspectRecord() checks stage_completion and routes to the
      // read-only ProspectInfoModal once Identity/Contact/Requirements are
      // all complete, otherwise falls back to the Prospect form modal.
      row.addEventListener("click", function () {
        window.openProspectRecord?.(data.uuid);
      });

      return table;
    }

    return table;
  }

  document.querySelectorAll(".statusBtn").forEach((btn) => {
    btn.addEventListener("click", function () {
      document
        .querySelectorAll(".statusBtn")
        .forEach((card) => card.classList.remove("ring-2", "ring-orange-500"));

      this.classList.add("ring-2", "ring-orange-500");

      const status = this.dataset.status;

      renderTable().setFilter("status", status);
    });
  });

  document
    .getElementById("crmNeedsAttentionToggle")
    ?.addEventListener("click", function () {
      const nextActive = this.dataset.active !== "true";
      setNeedsAttentionActive(nextActive);
      renderTable().setFilter("needs_attention", nextActive ? "1" : "");
    });

  // ============================================================
  // RENDER — COUNTS
  // ============================================================

  // Stages shown as segments in the pipeline bar / chip row, in display order.
  const PIPELINE_STAGES = [
    "PROSPECT",
    "QUALIFIED",
    "OPPORTUNITY",
    "NEGOTIATION",
  ];

  async function renderCounts() {
    const lead = await getleadcount();
    const counts = lead.status_counts ?? {};

    const COUNT_MAP = {
      ALL: "countALL",
      PROSPECT: "countProspect",
      QUALIFIED: "countQualified",
      OPPORTUNITY: "countOpportunity",
      NEGOTIATION: "countNegotiation",
      WIN: "countWin",
      LOST: "countLose",
    };

    Object.entries(COUNT_MAP).forEach(([key, elementId]) => {
      const el = document.getElementById(elementId);
      if (el) el.innerText = counts[key] ?? 0;
    });

    const totalEl = document.getElementById("crmTotalStat");
    if (totalEl) totalEl.innerText = counts.ALL ?? 0;

    const attentionEl = document.getElementById("crmAttentionStat");
    if (attentionEl) attentionEl.innerText = lead.needs_attention_count ?? 0;

    // Segment widths are real percentages of the 4 active-pipeline stages -
    // a flat empty track (all 0%) when there's nothing to show yet, never a
    // divide-by-zero.
    const activeTotal = PIPELINE_STAGES.reduce(
      (sum, stage) => sum + (counts[stage] ?? 0),
      0,
    );

    PIPELINE_STAGES.forEach((stage) => {
      const segment = document.querySelector(
        `#crmPipelineBar [data-status="${stage}"]`,
      );
      if (!segment) return;

      const pct = activeTotal > 0 ? ((counts[stage] ?? 0) / activeTotal) * 100 : 0;
      segment.style.width = `${pct}%`;
    });
  }

  // ============================================================
  // RENDER — ASSIGNABLE USERS (scope indicator + assigned-rep filter)
  // ============================================================

  async function loadAssignableUsersFilter() {
    const response = await apiCall({
      mode: "GET",
      url: "/api/crm/prospects/assignable-users",
    });

    if (!response.success) return;

    const users = response.data ?? [];

    const scopeIndicator = document.getElementById("crmScopeIndicator");
    if (scopeIndicator) {
      scopeIndicator.classList.toggle("hidden", users.length <= 1);
      if (users.length > 1) {
        scopeIndicator.textContent = `· Showing leads for ${users.length} team members`;
      }
    }

    const assignedToFilter = document.getElementById("crmAssignedToFilter");
    if (assignedToFilter) {
      assignedToFilter.innerHTML = [
        `<option value="">All Reps</option>`,
        ...users.map((u) => `<option value="${u.id}">${u.name}</option>`),
      ].join("");

      assignedToFilter.addEventListener("change", function () {
        renderTable().setFilter("assigned_to", this.value);
      });
    }
  }

  // ============================================================
  // PUBLIC API
  // ============================================================

  window.reloadCrmData = function () {
    updateLeadDetails();
  };

  // ============================================================
  // INIT
  // ============================================================

  async function updateLeadDetails() {
    renderTable().load(1);
    renderCounts();
  }

  async function initializePage() {
    updateLeadDetails();
    loadAssignableUsersFilter();

    document
      .getElementById("btnNewLead")
      .addEventListener("click", function () {
        window.openProspectModal?.();
      });
  }

  initializePage();
};
