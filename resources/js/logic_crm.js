window.initCrmLogic = function initCrmLogic() {
  // ============================================================
  // STATE
  // ============================================================

  let leadUUID = "";
  let leadInfo = {};
  let currentLeadProposalsPage = 1;
  // Cached so the Activity-tab "Call"/"Email" timeline submit can resend the
  // lead's unchanged status - CrmActivityController::store always does
  // $lead->update(['status' => $request->status]), so omitting it would
  // silently blank out the lead's real status.
  let currentLeadStatus = "";
  let timelineActiveType = "Note";

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

  const CONTAINER_TYPE_LABELS = {
    CV: "Container Van",
    FR: "Flatrack",
    RF: "Reefer Van",
    LC: "Loose Cargo",
    RC: "Rolling Cargo",
  };

  // Accent color per container type for the booking-requirement info card
  // (renderContainers) - a quick visual cue distinguishing types in a
  // scrolled list, independent of the app's orange primary-action color.
  const CONTAINER_TYPE_ACCENT = {
    CV: { text: "text-orange-600 dark:text-orange-400", dot: "bg-orange-500" },
    FR: { text: "text-amber-600 dark:text-amber-400", dot: "bg-amber-500" },
    RF: { text: "text-cyan-600 dark:text-cyan-400", dot: "bg-cyan-500" },
    LC: { text: "text-purple-600 dark:text-purple-400", dot: "bg-purple-500" },
    RC: { text: "text-blue-600 dark:text-blue-400", dot: "bg-blue-500" },
  };

  // Rows whose status opens LeadInfoModal on click vs. navigates to the
  // full-page lead form - also drives the row-click affordance icon
  // rendered in the Status column (see ROW_CLICK_ICON below).
  const OPEN_MODAL_STATUSES = ["OPPORTUNITY", "NEGOTIATION", "WIN", "LOST"];

  // Dot color for the merged Activity timeline (renderTimeline) - keyed by
  // display type ("Note" for notes, activity.type for activities).
  const TIMELINE_DOT_COLOR = {
    Note: "bg-zinc-400",
    "Status Change": "bg-amber-500",
    DEFAULT: "bg-blue-500",
  };

  // LeadInfoModal right-pane tab ids - drives setActiveTab().
  const LEAD_INFO_TABS = ["Proposals", "Requirements", "Activity"];

  const ROW_CLICK_ICON = {
    edit: `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>`,
    view: `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle></svg>`,
  };

  // Mirrors ClientProposal::STATUS_* / STATUS_LABELS (app/Models/ClientProposal.php)
  const PROPOSAL_STATUS = {
    PENDING: 1,
    APPROVED: 2,
    DISAPPROVED: 3,
    ACCEPTED: 4,
    REJECTED: 5,
  };

  const PROPOSAL_STATUS_LABEL = {
    [PROPOSAL_STATUS.PENDING]: "Pending",
    [PROPOSAL_STATUS.APPROVED]: "Approved",
    [PROPOSAL_STATUS.DISAPPROVED]: "Disapproved",
    [PROPOSAL_STATUS.ACCEPTED]: "Accepted",
    [PROPOSAL_STATUS.REJECTED]: "Rejected",
  };

  const PROPOSAL_STATUS_BADGE = {
    [PROPOSAL_STATUS.PENDING]: "bg-amber-100 text-amber-600",
    [PROPOSAL_STATUS.APPROVED]: "bg-green-100 text-green-700",
    [PROPOSAL_STATUS.DISAPPROVED]: "bg-red-100 text-red-600",
    [PROPOSAL_STATUS.ACCEPTED]: "bg-blue-100 text-blue-700",
    [PROPOSAL_STATUS.REJECTED]: "bg-zinc-200 text-zinc-600",
  };

  // ============================================================
  // DOM REFS
  // ============================================================

  const changeStageBtn = document.getElementById("changeStageBtn");
  const changeStageDropdown = document.getElementById("changeStageDropdown");
  const leadStageSelect = document.getElementById("leadStageSelect");
  const saveStageBtn = document.getElementById("saveStageBtn");
  const cancelStageBtn = document.getElementById("cancelStageBtn");

  const timelineTypeBtns = document.querySelectorAll(".timeline-type-btn");
  const timelineEntryInput = document.getElementById("timelineEntryInput");
  const timelineAttachmentToggleBtn = document.getElementById(
    "timelineAttachmentToggleBtn",
  );
  const timelineAttachmentRow = document.getElementById(
    "timelineAttachmentRow",
  );
  const timelineAttachmentInput = document.getElementById(
    "timelineAttachmentInput",
  );
  const timelineAddBtn = document.getElementById("timelineAddBtn");

  const editContactBtn = document.getElementById("editContactBtn");
  const editContactInfoDropdown = document.getElementById(
    "editContactInfoDropdown",
  );
  const saveContactInfoBtn = document.getElementById("saveContactInfoBtn");
  const cancelContactInfoBtn = document.getElementById("cancelContactInfoBtn");

  // ============================================================
  // HELPERS
  // ============================================================

  function getStatusBadgeClass(status) {
    return STATUS_BADGE[status] ?? STATUS_BADGE.DEFAULT;
  }

  function openDropdown(dropdown) {
    dropdown.classList.remove("hidden");
    document
      .querySelector(`[aria-controls="${dropdown.id}"]`)
      ?.setAttribute("aria-expanded", "true");
  }

  function closeDropdown(dropdown) {
    dropdown.classList.add("hidden");
    document
      .querySelector(`[aria-controls="${dropdown.id}"]`)
      ?.setAttribute("aria-expanded", "false");
  }

  function emptyState(message) {
    return `
        <div class="w-full py-3 rounded-md text-center">
            <p class="text-xs font-medium text-zinc-400">${message}</p>
        </div>`;
  }

  // Distinct "prompting" empty state for the Proposals tab (dashed orange
  // block) - reuses the same #leadAddProposalBtn click handler via a
  // delegated click listener instead of duplicating its logic.
  function proposalEmptyState() {
    return `
        <div class="border-2 border-dashed border-orange-300 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/20 rounded-xl p-6 flex flex-col items-center text-center gap-2">
            <span class="text-[11px] font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">⚠ Action Needed</span>
            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">No proposal yet for this stage</p>
            <button type="button" id="leadProposalEmptyAddBtn"
                class="mt-1 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">
                + New Proposal
            </button>
        </div>`;
  }

  function setActiveTab(tab) {
    LEAD_INFO_TABS.forEach((t) => {
      const btn = document.getElementById(`tabBtn${t}`);
      const pane = document.getElementById(`tabPane${t}`);
      if (!btn || !pane) return;
      const active = t === tab;
      btn.classList.toggle("border-orange-500", active);
      btn.classList.toggle("text-orange-600", active);
      btn.classList.toggle("border-transparent", !active);
      btn.classList.toggle("text-zinc-500", !active);
      pane.classList.toggle("hidden", !active);
    });
  }

  function setTimelineActiveType(type) {
    timelineActiveType = type;
    timelineTypeBtns.forEach((btn) => {
      const active = btn.dataset.type === type;
      btn.classList.toggle("bg-white", active);
      btn.classList.toggle("dark:bg-zinc-700", active);
      btn.classList.toggle("text-orange-600", active);
      btn.classList.toggle("dark:text-orange-400", active);
      btn.classList.toggle("shadow-sm", active);
      btn.classList.toggle("text-zinc-500", !active);
      btn.classList.toggle("dark:text-zinc-400", !active);
    });
  }

  function formatAddress(address) {
    if (!address) return "-";

    const parts = [
      address.address_no,
      address.address_building,
      address.address_street,
      address.address_barangay,
      address.address_town_city,
      address.address_province,
      address.address_country,
      address.address_postal_code,
    ].filter(Boolean);

    return parts.length ? parts.join(", ") : "-";
  }

  // ============================================================
  // API
  // ============================================================

  async function getleadcount() {
    const leads = await apiCall({
      mode: "GET",
      url: "/api/crm/leads",
    });
    return leads;
  }

  async function getStatuses() {
    const statuses = await apiCall({
      mode: "GET",
      url: "/api/crm/getCrmStatus",
    });

    document.querySelectorAll(".statusDropDown").forEach((dropdown) => {
      dropdown.innerHTML = [
        `<option value="">Select Status</option>`,
        ...statuses.data.map(
          (s) => `<option value="${s.id}">${s.status}</option>`,
        ),
      ].join("");
    });
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
  document.addEventListener("click", function (e) {
    if (e.target.closest("#crmEmptyNewLeadBtn")) {
      document.getElementById("btnNewLead")?.click();
    }
    if (e.target.closest("#crmEmptyClearFiltersBtn")) {
      clearCrmFilters();
    }
  });

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
      url: "/api/crm/leads",
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
      const willOpenModal = OPEN_MODAL_STATUSES.includes(status);
      const icon = willOpenModal ? ROW_CLICK_ICON.view : ROW_CLICK_ICON.edit;
      const tooltip = willOpenModal ? "View" : "Edit";
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

      row.addEventListener("click", function () {
        const status = data.crm_status?.status;

        if (OPEN_MODAL_STATUSES.includes(status)) {
          loadLeadInfo(data.uuid);
          initModal({ modalId: "LeadInfoModal" });
        } else {
          window.crmLeadFormUuid = data.uuid;
          loadPage({ title: "Edit Lead", link: "/page_crmLeadForm" });
        }
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
    "LEAD",
    "QUALIFIED",
    "OPPORTUNITY",
    "NEGOTIATION",
  ];

  async function renderCounts() {
    const lead = await getleadcount();
    const counts = lead.status_counts ?? {};

    const COUNT_MAP = {
      ALL: "countALL",
      LEAD: "countLead",
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
      url: "/api/crm/leads/assignable-users",
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
  // RENDER — LEAD INFO
  // ============================================================

  async function loadLeadInfo(uuid) {
    const loader = loadingLine();
    [
      "#leadCompanyName",
      "#leadStatus",
      "#leadCustomerCode",
      "#leadContactName",
      "#leadPosition",
      "#leadClientType",
      "#leadEmail",
      "#leadMobile",
      "#leadLandline",
      "#leadSource",
      "#leadAssignedTo",
      "#leadCompanyNameFull",
      "#leadTypeOfBusiness",
      "#leadIndustryDescription",
      "#leadAuthorizedSignatoryName",
      "#leadAuthorizedSignatoryPosition",
      "#leadEstimatedValue",
      "#leadCreatedAt",
      "#leadExpectedCloseDate",
      "#leadTimelineContainer",
      "#leadContainerListContainer",
      "#leadAddressListContainer",
    ].forEach((id) => $(id).html(loader));
    document.getElementById("leadProposalContainer").innerHTML = loader;
    leadUUID = uuid;
    window.currentLeadUuid = uuid;
    setActiveTab("Proposals");

    const response = await apiCall({
      mode: "GET",
      url: `/api/crm/leads/${uuid}`,
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Fetching Lead",
        message:
          "There is an error fetching your information. Please contact the system administrator.",
      });
      return;
    }

    leadInfo = response.data;
    const lead = response.data;
    const company = lead.company ?? {};
    const value = Number(lead?.estimated_value || 0);
    const statusClass = getStatusBadgeClass(lead.crm_status.status);

    const opportunityBtn = document.getElementById("createClientMasterBtn");
    const canCreateRecord = lead.has_accepted_proposal === true;
    opportunityBtn.classList.toggle("hidden", !canCreateRecord);
    opportunityBtn.onclick = async function () {
      // Reserve (or fetch the already-reserved) customer code so it stays
      // locked to this lead across the whole Client Master creation flow.
      const codeResponse = await apiCall({
        mode: "GET",
        url: `/api/crm/leads/${uuid}/customerCode`,
        button: opportunityBtn,
      });

      if (!codeResponse.success) {
        showMessage({
          status: "error",
          title: "Error",
          message: "Unable to generate a customer code for this lead.",
        });
        return;
      }

      window.clientMasterFormUuid = null;
      window.clientMasterFormLeadId = lead.id;
      window.clientMasterFormPrefill = {
        customer_code: codeResponse.data.customer_code,
        company_name: company.company_name ?? "",
        industry: company.type_of_business ?? "",
        contact_number_1: lead.mobile ?? "",
        addresses: lead.addresses ?? [],
      };
      loadPage({
        title: "New Client Master Data",
        link: "/page_clientMasterForm",
      });
    };

    $("#leadCompanyName").html(
      (company.company_name ?? lead.contact_name ?? "").toUpperCase(),
    );
    $("#leadStatus").html(`
        <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
            ${lead.crm_status.status}
        </span>`);
    if (lead.client_master?.customer_code) {
      $("#leadCustomerCode").html(`
        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
            ${lead.client_master.customer_code}
        </span>`);
    } else {
      $("#leadCustomerCode").html("");
    }
    $("#leadContactName").html(lead.contact_name ?? "-");
    $("#leadPosition").html(lead.position ?? "-");
    $("#leadClientType").html(
      lead.client_type
        ? lead.client_type.charAt(0).toUpperCase() + lead.client_type.slice(1)
        : "-",
    );
    $("#leadEmail").html(
      `${lead.email ?? "-"}${lead.email_type ? ` (${lead.email_type})` : ""}`,
    );
    $("#leadMobile").html(
      `${lead.mobile ?? "-"}${lead.mobile_type ? ` (${lead.mobile_type})` : ""}`,
    );
    $("#leadLandline").html(
      lead.landline_number
        ? `${lead.landline_number}${lead.landline_type ? ` (${lead.landline_type})` : ""}`
        : "-",
    );
    $("#leadSource").html(lead.source ?? "-");
    $("#leadAssignedTo").html(lead.user?.name ?? "-");
    $("#leadCompanyNameFull").html(company.company_name ?? "-");
    $("#leadTypeOfBusiness").html(company.type_of_business ?? "-");
    $("#leadIndustryDescription").html(company.industry_description ?? "-");
    $("#leadAuthorizedSignatoryName").html(
      company.authorized_signatory_name ?? "-",
    );
    $("#leadAuthorizedSignatoryPosition").html(
      company.authorized_signatory_position ?? "-",
    );
    $("#leadEstimatedValue").html(`₱${value.toLocaleString()}`);
    $("#leadCreatedAt").html(formatDateTime(lead.created_at) ?? "-");
    $("#leadExpectedCloseDate").html(
      lead.expected_close_date ? formatDateTime(lead.expected_close_date) : "-",
    );

    $("#contactName").val(lead.contact_name ?? "");
    $("#contactEmail").val(lead.email ?? "");
    $("#contactMobile").val(lead.mobile ?? "");

    currentLeadStatus = lead.status ?? "";
    $("#leadStageSelect").val(currentLeadStatus);

    renderTimeline(lead.activities, lead.notes);
    renderContainers(lead.containers);
    renderAddresses(lead.addresses);
    loadLeadProposals(uuid, 1);
  }

  // ============================================================
  // RENDER — PROPOSALS
  // ============================================================

  async function loadLeadProposals(uuid, page = 1) {
    currentLeadProposalsPage = page;
    window.currentLeadProposalsPage = page;
    const container = document.getElementById("leadProposalContainer");
    container.innerHTML = loadingLine();

    const response = await apiCall({
      mode: "GET",
      url: `/api/crm/leads/${uuid}/proposals?page=${page}&per_page=5`,
    });

    if (!response.success) {
      container.innerHTML = emptyState("Unable to load proposals.");
      renderLeadProposalsPagination(null);
      return;
    }

    const meta = response.data;
    const proposals = meta.data ?? [];

    document
      .getElementById("tabBadgeProposals")
      ?.classList.toggle("hidden", proposals.length > 0);

    if (!proposals.length) {
      container.innerHTML = proposalEmptyState();
      renderLeadProposalsPagination(null);
      return;
    }

    container.innerHTML = proposals.map((p) => renderProposalCard(p)).join("");
    renderLeadProposalsPagination(meta);
  }
  window.loadLeadProposals = loadLeadProposals;

  // The empty-state "+ New Proposal" button is (re)rendered inside
  // #leadProposalContainer's innerHTML, so it's wired via delegation rather
  // than a one-time getElementById binding - it just forwards the click to
  // the existing #leadAddProposalBtn handler (defined in crm.blade.php's
  // proposal-modal script) instead of duplicating that logic.
  document.addEventListener("click", function (e) {
    if (e.target.closest("#leadProposalEmptyAddBtn")) {
      document.getElementById("leadAddProposalBtn")?.click();
    }
  });

  function renderProposalCard(proposal) {
    const statusClass =
      PROPOSAL_STATUS_BADGE[proposal.status] ?? STATUS_BADGE.DEFAULT;
    const statusLabel = PROPOSAL_STATUS_LABEL[proposal.status] ?? "Unknown";
    const isPending = proposal.status === PROPOSAL_STATUS.PENDING;
    const isApproved = proposal.status === PROPOSAL_STATUS.APPROVED;
    const downloadUrl = `/api/clientProposals/${proposal.id}/pdf`;
    const downloadClass = isApproved
      ? "bg-orange-600 hover:bg-orange-700"
      : "pointer-events-none opacity-50 cursor-not-allowed bg-gray-400";

    const rateRows = (proposal.rates ?? [])
      .map(
        (r) => `
            <tr class="border-t border-zinc-100">
                <td class="py-1.5">${r.origin_port ? (r.origin_port.location?.name ?? "-") + " - " + r.origin_port.name : "-"} &rarr; ${r.destination_port ? (r.destination_port.location?.name ?? "-") + " - " + r.destination_port.name : "-"}</td>
                <td class="py-1.5">${r.container?.name ?? "-"} / ${r.container_class?.class ?? "-"} / ${r.container_size?.size ?? "-"}</td>
                <td class="py-1.5 text-right">${Number(r.base_rate).toLocaleString()}</td>
                <td class="py-1.5 text-right font-semibold">${Number(r.final_rate).toLocaleString()}</td>
            </tr>`,
      )
      .join("");

    return `
        <div class="bg-white  dark:bg-zinc-700 border border-zinc-200 rounded-xl p-4 w-full flex flex-col gap-3"
            data-proposal-id="${proposal.id}">

            <div class="flex justify-between items-center gap-4">
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full ${statusClass}"></span>
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">${statusLabel}</p>
                    </div>
                    <h2 class="text-sm font-medium text-zinc-800">${proposal.code}</h2>
                    <p class="text-[11px] text-zinc-400">${formatDateTime(proposal.created_at)} &middot; ${proposal.creator?.name ?? "-"}</p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    ${
                      isPending
                        ? `
                        <button type="button" class="lead-add-container-btn text-xs px-3 py-1.5 rounded-lg border border-zinc-300 bg-zinc-50 hover:bg-zinc-100 text-zinc-700"
                            data-proposal-id="${proposal.id}">
                            + Add Container
                        </button>`
                        : ""
                    }
                    <a href="${downloadUrl}" target="_blank"
                        class="${downloadClass} text-white w-8 h-8 flex items-center justify-center rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
                        </svg>
                    </a>
                </div>
            </div>

            ${
              rateRows
                ? `
                <table class="w-full text-xs">
                    <thead class="text-zinc-400 uppercase">
                        <tr>
                            <th class="text-left py-1">Route</th>
                            <th class="text-left py-1">Container</th>
                            <th class="text-right py-1">Base Rate</th>
                            <th class="text-right py-1">Final Rate</th>
                        </tr>
                    </thead>
                    <tbody>${rateRows}</tbody>
                </table>`
                : ""
            }
        </div>`;
  }

  function renderLeadProposalsPagination(meta) {
    const el = document.getElementById("leadProposalsPagination");
    if (!el) return;

    if (!meta || meta.last_page <= 1) {
      el.innerHTML = "";
      return;
    }

    el.innerHTML = `
        <div class="flex items-center justify-between px-1 py-2">
            <p class="text-xs text-zinc-400">Showing ${meta.from ?? 0}-${meta.to ?? 0} of ${meta.total ?? 0}</p>
            <div class="flex items-center gap-1">
                <button type="button" id="leadProposalsPrevBtn" ${meta.prev_page_url ? "" : "disabled"}
                    class="px-2 py-1 text-xs rounded-md text-zinc-600 hover:bg-zinc-100 disabled:opacity-30">Prev</button>
                <span class="text-xs text-zinc-500 px-1">${meta.current_page} / ${meta.last_page}</span>
                <button type="button" id="leadProposalsNextBtn" ${meta.next_page_url ? "" : "disabled"}
                    class="px-2 py-1 text-xs rounded-md text-zinc-600 hover:bg-zinc-100 disabled:opacity-30">Next</button>
            </div>
        </div>`;

    document
      .getElementById("leadProposalsPrevBtn")
      ?.addEventListener("click", () => {
        if (meta.prev_page_url)
          loadLeadProposals(leadUUID, meta.current_page - 1);
      });
    document
      .getElementById("leadProposalsNextBtn")
      ?.addEventListener("click", () => {
        if (meta.next_page_url)
          loadLeadProposals(leadUUID, meta.current_page + 1);
      });
  }

  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".lead-add-container-btn");
    if (btn) window.openLeadAddContainerModal?.(btn.dataset.proposalId);
  });

  // ============================================================
  // RENDER — CONTAINER REQUIREMENTS
  // ============================================================

  function renderContainers(containers) {
    const container = document.getElementById("leadContainerListContainer");

    const badge = document.getElementById("tabBadgeRequirements");
    if (badge) badge.textContent = containers?.length ?? 0;

    if (!containers || !containers.length) {
      container.innerHTML = emptyState("No container requirements added yet.");
      return;
    }

    container.innerHTML = containers
      .map((c) => {
        const typeLabel =
          CONTAINER_TYPE_LABELS[c.container_type] ?? c.container_type;
        const accent =
          CONTAINER_TYPE_ACCENT[c.container_type] ?? CONTAINER_TYPE_ACCENT.CV;

        const originCity = c.origin_port?.location?.name ?? "-";
        const originPort = c.origin_port?.name ?? null;
        const destCity = c.destination_port?.location?.name ?? "-";
        const destPort = c.destination_port?.name ?? null;

        const containerInfo =
          [c.container_class?.class, c.container_size?.size]
            .filter(Boolean)
            .join(" · ") ||
          [
            c.estimated_cbm ? `${c.estimated_cbm} CBM` : null,
            c.estimated_ton ? `${c.estimated_ton} MT` : null,
          ]
            .filter(Boolean)
            .join(" · ") ||
          (c.minimum_temperature != null
            ? `${c.minimum_temperature}°C`
            : null) ||
          "-";

        const footNotes = [
          c.declared_value_per_unit
            ? `<span><b>₱${Number(c.declared_value_per_unit).toLocaleString()}</b> declared value/unit</span>`
            : null,
          c.special_requirements
            ? `<span><b>Special Requirements:</b> ${c.special_requirements}</span>`
            : null,
          c.special_notes
            ? `<span><b>Special Notes:</b> ${c.special_notes}</span>`
            : null,
        ].filter(Boolean);

        const dgDownload =
          c.dangerous_cargo && c.dg_documentary_requirement
            ? `<a href="${c.dg_documentary_requirement}" target="_blank"
                class="inline-flex items-center gap-1 text-[11px] font-medium text-amber-700 dark:text-amber-400 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
                </svg>
                Download DG Doc
            </a>`
            : "";

        return `
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden w-full shrink-0">
                    <div class="flex items-center justify-between px-3 pt-3 pb-2">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold ${accent.text}">
                            <span class="w-2 h-2 rounded-full ${accent.dot}"></span>
                            ${typeLabel}
                        </span>
                        ${c.quantity ? `<span class="font-mono text-[11px] font-bold bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-full px-2 py-0.5 text-zinc-600 dark:text-zinc-300">&times;${c.quantity}</span>` : ""}
                    </div>

                    <div class="flex items-start gap-2 px-3 pt-2 pb-2.5">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-zinc-800 dark:text-zinc-100 truncate">${originCity}</p>
                            ${originPort ? `<p class="text-[11px] text-zinc-400 truncate">${originPort}</p>` : ""}
                            ${c.service_mode_origin ? `<p class="text-[11px] font-semibold text-zinc-600 dark:text-zinc-300 mt-1">${c.service_mode_origin}</p>` : ""}
                        </div>
                        <div class="shrink-0 w-8 flex justify-center pt-1 text-zinc-300 dark:text-zinc-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </div>
                        <div class="flex-1 min-w-0 text-right">
                            <p class="text-sm font-bold text-zinc-800 dark:text-zinc-100 truncate">${destCity}</p>
                            ${destPort ? `<p class="text-[11px] text-zinc-400 truncate">${destPort}</p>` : ""}
                            ${c.service_mode_destination ? `<p class="text-[11px] font-semibold text-zinc-600 dark:text-zinc-300 mt-1">${c.service_mode_destination}</p>` : ""}
                        </div>
                    </div>

                    <div class="flex border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/60">
                        <div class="flex-1 px-3 py-2 border-r border-zinc-100 dark:border-zinc-800">
                            <p class="text-[10px] font-medium text-zinc-400 uppercase tracking-widest">Container Info</p>
                            <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-200">${containerInfo}</p>
                        </div>
                        <div class="flex-1 px-3 py-2">
                            <p class="text-[10px] font-medium text-zinc-400 uppercase tracking-widest">Frequency</p>
                            <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-200">${c.frequency ?? "-"}</p>
                        </div>
                    </div>

                    ${
                      c.cargo_type
                        ? `<div class="px-3 py-2 border-t border-zinc-100 dark:border-zinc-800">
                            <p class="text-[10px] font-medium text-zinc-400 uppercase tracking-widest">Cargo Type</p>
                            <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-200">${c.cargo_type}</p>
                        </div>`
                        : ""
                    }

                    ${
                      c.general_cargo_description
                        ? `<div class="px-3 py-2 border-t border-zinc-100 dark:border-zinc-800">
                            <p class="text-[10px] font-medium text-zinc-400 uppercase tracking-widest">Cargo Description</p>
                            <p class="text-xs text-zinc-600 dark:text-zinc-300">${c.general_cargo_description}</p>
                        </div>`
                        : ""
                    }

                    ${
                      footNotes.length
                        ? `<div class="flex flex-wrap gap-x-4 gap-y-0.5 px-3 pb-2 text-[11px] text-zinc-500 dark:text-zinc-400">
                            ${footNotes.join("")}
                        </div>`
                        : ""
                    }

                    ${
                      c.dangerous_cargo
                        ? `<div class="flex items-center justify-between gap-2 px-3 py-1.5 border-t border-zinc-100 dark:border-zinc-800 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400">
                            <span class="text-[11px] font-semibold">&#9888; Dangerous Cargo declared</span>
                            ${dgDownload}
                        </div>`
                        : ""
                    }
                </div>`;
      })
      .join("");
  }

  // ============================================================
  // RENDER — ADDRESSES
  // ============================================================

  function renderAddresses(addresses) {
    const container = document.getElementById("leadAddressListContainer");

    if (!addresses || !addresses.length) {
      container.innerHTML = emptyState("No addresses added yet.");
      return;
    }

    container.innerHTML = addresses
      .map((a) => {
        const typeLabel = a.address_type || "Address";

        return `
                <div class=" p-3 border border-zinc-200 dark:border-zinc-700 rounded-md p-2.5 w-full flex flex-col gap-1">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">${typeLabel}</span>
                        ${a.is_primary ? `<span class="text-[11px] font-medium text-orange-600">Primary</span>` : ""}
                    </div>
                    <p class="text-[11px] text-zinc-500">${formatAddress(a)}</p>
                </div>`;
      })
      .join("");
  }

  // ============================================================
  // RENDER — ACTIVITY TIMELINE (merged activities + notes)
  // ============================================================

  function renderTimeline(activities, notes) {
    const container = document.getElementById("leadTimelineContainer");

    const merged = [
      ...(activities ?? []).map((activity) => ({
        typeTag: activity.type,
        text: activity.description,
        created_at: activity.created_at,
        user: activity.user,
        attachment: activity.attachment,
      })),
      ...(notes ?? []).map((note) => ({
        typeTag: "Note",
        text: note.note,
        created_at: note.created_at,
        user: note.user,
        attachment: null,
      })),
    ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

    const badge = document.getElementById("tabBadgeActivity");
    if (badge) badge.textContent = merged.length;

    if (!merged.length) {
      container.innerHTML = emptyState("No activity yet.");
      return;
    }

    container.innerHTML = merged
      .map((entry, index) => {
        const dotClass =
          TIMELINE_DOT_COLOR[entry.typeTag] ?? TIMELINE_DOT_COLOR.DEFAULT;
        const isLast = index === merged.length - 1;

        return `
            <div class="relative pl-6 ${isLast ? "" : "pb-4"}">
                ${isLast ? "" : `<span class="absolute left-[5px] top-3 bottom-0 w-px bg-zinc-200 dark:bg-zinc-700"></span>`}
                <span class="absolute left-[2px] top-1 w-2.5 h-2.5 rounded-full ring-2 ring-zinc-50 dark:ring-zinc-900 ${dotClass}"></span>
                <div class="flex justify-between items-baseline gap-2">
                    <p class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wide truncate">${entry.typeTag}</p>
                    <p class="text-[10px] text-zinc-400 shrink-0">${formatDateTime(entry.created_at)}</p>
                </div>
                <p class="text-xs text-zinc-800 dark:text-zinc-100 mt-0.5 leading-snug">${entry.text ?? "-"}</p>
                <div class="flex justify-between items-center mt-0.5">
                    <p class="text-[10px] text-zinc-400">${entry.user?.name ?? "-"}</p>
                    ${entry.attachment ? `<a href="${entry.attachment}" target="_blank" class="text-[10px] font-medium text-blue-600 hover:underline">Attachment</a>` : ""}
                </div>
            </div>`;
      })
      .join("");
  }

  // ============================================================
  // TAB EVENTS
  // ============================================================

  LEAD_INFO_TABS.forEach((t) => {
    document
      .getElementById(`tabBtn${t}`)
      ?.addEventListener("click", () => setActiveTab(t));
  });

  // ============================================================
  // CHANGE STAGE EVENTS
  // ============================================================

  changeStageBtn.addEventListener("click", () =>
    openDropdown(changeStageDropdown),
  );

  cancelStageBtn.addEventListener("click", () => {
    leadStageSelect.value = currentLeadStatus;
    closeDropdown(changeStageDropdown);
  });

  saveStageBtn.addEventListener("click", async function () {
    const selectedOption =
      leadStageSelect.options[leadStageSelect.selectedIndex];
    const statusLabel = selectedOption ? selectedOption.textContent : "";

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: {
        leadUUId: leadUUID,
        status: leadStageSelect.value,
        type: "Status Change",
        activity: "Status changed to " + statusLabel,
      },
      url: "/api/crm/activity",
      button: saveStageBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Error Changing Stage" });
      return;
    }

    showMessage({ status: "success", title: "Stage Updated!" });
    closeDropdown(changeStageDropdown);
    reloadCrmData();
  });

  // ============================================================
  // TIMELINE (ACTIVITY TAB) EVENTS
  // ============================================================

  timelineTypeBtns.forEach((btn) => {
    btn.addEventListener("click", () =>
      setTimelineActiveType(btn.dataset.type),
    );
  });

  timelineAttachmentToggleBtn.addEventListener("click", () => {
    timelineAttachmentRow.classList.toggle("hidden");
  });

  timelineAddBtn.addEventListener("click", async function () {
    const text = timelineEntryInput.value.trim();
    if (!text) return;

    let response;

    if (timelineActiveType === "Note") {
      response = await apiCall({
        mode: "POST",
        isJson: true,
        payload: { leadUUId: leadUUID, note: text },
        url: "/api/crm/note",
        button: timelineAddBtn,
      });
    } else {
      const formData = new FormData();
      formData.append("leadUUId", leadUUID);
      // Resend the lead's current status unchanged - see currentLeadStatus
      // declaration for why this must never be blank.
      formData.append("status", currentLeadStatus);
      formData.append("type", timelineActiveType);
      formData.append("activity", text);

      if (timelineAttachmentInput.files.length > 0) {
        formData.append("attachment", timelineAttachmentInput.files[0]);
      }

      response = await apiCall({
        mode: "POST",
        isJson: false,
        payload: formData,
        url: "/api/crm/activity",
        button: timelineAddBtn,
      });
    }

    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving Entry" });
      return;
    }

    showMessage({ status: "success", title: "Entry Added!" });
    timelineEntryInput.value = "";
    timelineAttachmentInput.value = "";
    timelineAttachmentRow.classList.add("hidden");
    setTimelineActiveType("Note");
    reloadCrmData();
  });

  // ============================================================
  // CONTACT INFO EVENTS
  // ============================================================

  editContactBtn.addEventListener("click", () =>
    openDropdown(editContactInfoDropdown),
  );

  cancelContactInfoBtn.addEventListener("click", () => {
    $("#saveContactInfoBtn").removeClass("hidden");
    closeDropdown(editContactInfoDropdown);
  });

  saveContactInfoBtn.addEventListener("click", async function () {
    const response = await apiCall({
      mode: "PUT",
      isJson: true,
      payload: {
        leadUUId: leadUUID,
        contact_name: $("#contactName").val(),
        contact_mobile: $("#contactMobile").val(),
        contact_email: $("#contactEmail").val(),
      },
      url: `/api/crm/leads/${leadUUID}`,
      button: saveContactInfoBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Error Updating Contact" });
      return;
    }

    showMessage({ status: "success", title: "Contact Updated!" });
    closeDropdown(editContactInfoDropdown);
    loadLeadInfo(leadUUID);
  });

  $(".editContactDropdown").on("change", function () {
    $("#saveContactInfoBtn").removeClass("hidden");
  });

  // ============================================================
  // CLICK OUTSIDE — CLOSE DROPDOWNS
  // ============================================================

  window.addEventListener("click", (e) => {
    if (
      !changeStageBtn.contains(e.target) &&
      !changeStageDropdown.contains(e.target)
    )
      closeDropdown(changeStageDropdown);

    if (
      !editContactBtn.contains(e.target) &&
      !editContactInfoDropdown.contains(e.target)
    )
      closeDropdown(editContactInfoDropdown);
  });

  // ============================================================
  // PUBLIC API
  // ============================================================

  window.reloadCrmData = function () {
    loadLeadInfo(leadUUID);
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
    getStatuses();
    loadAssignableUsersFilter();

    document
      .getElementById("btnNewLead")
      .addEventListener("click", function () {
        window.crmLeadFormUuid = null;
        loadPage({ title: "New Lead", link: "/page_crmLeadForm" });
      });
  }

  initializePage();
};
