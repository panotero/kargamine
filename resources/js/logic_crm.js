window.initCrmLogic = function initCrmLogic() {
  // ============================================================
  // STATE
  // ============================================================

  let leadUUID = "";
  let leadInfo = {};
  let currentLeadProposalsPage = 1;

  // ============================================================
  // CONSTANTS
  // ============================================================

  const STATUS_BADGE = {
    LEAD: "bg-gray-100 dark:bg-gray-700/40 text-gray-700 dark:text-gray-300",
    QUALIFIED:
      "bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400",
    OPPORTUNITY:
      "bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400",
    NEGOTIATION:
      "bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400",
    WIN: "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400",
    LOST: "bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400",
    DEFAULT: "bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300",
  };

  const CONTAINER_TYPE_LABELS = {
    CV: "Container Van",
    FR: "Flatrack",
    RF: "Reefer Van",
    LC: "Loose Cargo",
    RC: "Rolling Cargo",
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
    [PROPOSAL_STATUS.PENDING]:
      "bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400",
    [PROPOSAL_STATUS.APPROVED]:
      "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400",
    [PROPOSAL_STATUS.DISAPPROVED]:
      "bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400",
    [PROPOSAL_STATUS.ACCEPTED]:
      "bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400",
    [PROPOSAL_STATUS.REJECTED]:
      "bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-300",
  };

  // ============================================================
  // DOM REFS
  // ============================================================

  const leadAddActivityBtn = document.getElementById("leadAddActivityBtn");
  const leadActivityDropdown = document.getElementById("leadActivityDropdown");
  const activityDescInput = document.getElementById("activityDescriptionInput");
  const activityAttachmentInput = document.getElementById(
    "activityAttachmentInput",
  );
  const activityTypeInput = document.getElementById("activityTypeInput");
  const activityStatusInput = document.getElementById("activityStatusInput");
  const saveActivityBtn = document.getElementById("saveActivityBtn");
  const cancelActivityBtn = document.getElementById("cancelActivityBtn");

  const leadAddNoteBtn = document.getElementById("leadAddNoteBtn");
  const leadNoteDropdown = document.getElementById("leadNoteDropdown");
  const noteInput = document.getElementById("noteInput");
  const saveNoteBtn = document.getElementById("saveNoteBtn");
  const cancelNoteBtn = document.getElementById("cancelNoteBtn");

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
  }

  function closeDropdown(dropdown) {
    dropdown.classList.add("hidden");
  }

  function emptyState(message) {
    return `
        <div class="w-full py-3 rounded-md text-center">
            <p class="text-xs font-medium text-zinc-400">${message}</p>
        </div>`;
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

  function renderTable() {
    const thead = [
      {
        title: "Contact",
        key: "contact_name",
      },
      {
        title: "Company",
        key: "company.company_name",
      },
      {
        title: "Email",
        key: "email",
      },
      {
        title: "Mobile",
        key: "mobile",
      },
      {
        title: "Status",
        key: "crm_status.status",
      },
      {
        title: "Assigned To",
        key: "user.name",
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
    });

    const OPEN_MODAL_STATUSES = ["OPPORTUNITY", "NEGOTIATION", "WIN", "LOST"];

    function handleClick(row) {
      row.addEventListener("click", function () {
        const data = JSON.parse(row.dataset.row);
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

  // ============================================================
  // RENDER — COUNTS
  // ============================================================

  async function renderCounts() {
    const lead = await getleadcount();
    const counts = lead.status_counts;

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
      "#leadNoteContainer",
      "#leadActivityContainer",
      "#leadContainerListContainer",
      "#leadAddressListContainer",
    ].forEach((id) => $(id).html(loader));
    document.getElementById("leadProposalContainer").innerHTML = loader;
    leadUUID = uuid;
    window.currentLeadUuid = uuid;

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
        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400">
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
    $("#activityStatusInput").val(lead.status ?? "");

    renderActivity(lead.activities);
    renderNotes(lead.notes);
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

    if (!proposals.length) {
      container.innerHTML = emptyState(
        "There's no proposal yet. Create one now!",
      );
      renderLeadProposalsPagination(null);
      return;
    }

    container.innerHTML = proposals.map((p) => renderProposalCard(p)).join("");
    renderLeadProposalsPagination(meta);
  }
  window.loadLeadProposals = loadLeadProposals;

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
                <td class="py-1.5">${r.origin_port?.code ?? "-"} &rarr; ${r.destination_port?.code ?? "-"}</td>
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

    if (!containers || !containers.length) {
      container.innerHTML = emptyState("No container requirements added yet.");
      return;
    }

    container.innerHTML = containers
      .map((c) => {
        const origin = c.origin_port?.code ?? "-";
        const destination = c.destination_port?.code ?? "-";
        const typeLabel =
          CONTAINER_TYPE_LABELS[c.container_type] ?? c.container_type;

        const summary = [
          c.container_class?.class,
          c.container_size?.size,
          c.quantity ? `Qty: ${c.quantity}` : null,
          c.booking_unit_type,
          c.dangerous_cargo ? "DG" : null,
        ]
          .filter(Boolean)
          .join(" · ");

        const details = [
          c.frequency ? ["Frequency", c.frequency] : null,
          c.service_mode ? ["Service Mode", c.service_mode] : null,
          c.service_mode_origin
            ? ["Origin Handling", c.service_mode_origin]
            : null,
          c.service_mode_destination
            ? ["Destination Handling", c.service_mode_destination]
            : null,
          c.estimated_cbm ? ["Est. CBM", c.estimated_cbm] : null,
          c.estimated_ton ? ["Est. Tonnage", c.estimated_ton] : null,
          c.declared_value_per_unit
            ? [
                "Declared Value/Unit",
                `₱${Number(c.declared_value_per_unit).toLocaleString()}`,
              ]
            : null,
          c.general_cargo_description
            ? ["Cargo Description", c.general_cargo_description]
            : null,
          c.special_requirements
            ? ["Special Requirements", c.special_requirements]
            : null,
          c.special_notes ? ["Special Notes", c.special_notes] : null,
        ].filter(Boolean);

        const dgDownload =
          c.dangerous_cargo && c.dg_documentary_requirement
            ? `<a href="${c.dg_documentary_requirement}" target="_blank"
                class="inline-flex items-center gap-1 text-[11px] font-medium text-orange-600 hover:text-orange-700 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
                </svg>
                Download DG Doc
            </a>`
            : "";

        return `
                <div class="p-3 border border-zinc-200 dark:border-zinc-700 rounded-md  w-full flex flex-col gap-1">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-100">${typeLabel}</span>
                        <span class="text-[11px] text-zinc-400">${origin} &rarr; ${destination}</span>
                    </div>
                    ${summary ? `<p class="text-[11px] text-zinc-500">${summary}</p>` : ""}
                    ${
                      details.length
                        ? `<div class="grid grid-cols-1 gap-y-0.5 mt-0.5 pt-1.5 border-t border-zinc-100 dark:border-zinc-800">
                            ${details
                              .map(
                                ([label, value]) =>
                                  `<p class="text-[11px] text-zinc-500"><span class="text-zinc-400">${label}:</span> ${value}</p>`,
                              )
                              .join("")}
                        </div>`
                        : ""
                    }
                    ${dgDownload}
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
  // RENDER — ACTIVITIES
  // ============================================================

  function renderActivity(activities) {
    const container = document.getElementById("leadActivityContainer");

    if (!activities || !activities.length) {
      container.innerHTML = emptyState("No activities found");
      return;
    }

    container.innerHTML = activities
      .map(
        (activity) => `
            <div class="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-lg px-2.5 py-2 w-full">
                <div class="flex justify-between items-baseline gap-2">
                    <p class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wide truncate">${activity.type}</p>
                    <p class="text-[10px] text-zinc-400 shrink-0">${formatDateTime(activity.created_at)}</p>
                </div>
                <p class="text-xs text-zinc-800 dark:text-zinc-100 mt-0.5 leading-snug">${activity.description}</p>
                <div class="flex justify-between items-center mt-0.5">
                    <p class="text-[10px] text-zinc-400">${activity.user.name}</p>
                    ${activity.attachment ? `<a href="${activity.attachment}" target="_blank" class="text-[10px] font-medium text-blue-600 hover:underline">Attachment</a>` : ""}
                </div>
            </div>`,
      )
      .join("");
  }

  // ============================================================
  // RENDER — NOTES
  // ============================================================

  function renderNotes(notes) {
    const container = document.getElementById("leadNoteContainer");

    if (!notes || !notes.length) {
      container.innerHTML = emptyState("No notes found");
      return;
    }

    container.innerHTML = notes
      .map(
        (note) => `
            <div class="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-lg px-2.5 py-2 w-full">
                <div class="flex justify-between items-baseline gap-2">
                    <p class="text-[10px] font-semibold text-zinc-500 truncate">${note.user.name}</p>
                    <p class="text-[10px] text-zinc-400 shrink-0">${formatDateTime(note.created_at)}</p>
                </div>
                <p class="text-xs text-zinc-700 dark:text-zinc-300 mt-0.5 leading-snug">${note.note}</p>
            </div>`,
      )
      .join("");
  }

  // ============================================================
  // NEW LEAD FORM (side modal)
  // ============================================================

  $("#saveLeadBtn").on("click", async function (e) {
    e.preventDefault();

    const form = $("#leadForm")[0];
    const estValue = form.elements["est_value"].value.replace(/,/g, "");
    const formData = new FormData();

    formData.append("contact_name", form.contact_name.value);
    formData.append("mobile", form.mobile.value);
    formData.append("email", form.email.value);
    formData.append("company_name", form.company_name.value);
    formData.append("position", form.position.value);
    formData.append("status", form.status.value);
    formData.append("est_value", estValue);
    formData.append("source", form.source.value);
    formData.append("notes", form.notes.value);

    const response = await apiCall({
      mode: "POST",
      isJson: false,
      payload: formData,
      url: "/api/crm/leads",
      button: document.getElementById("saveLeadBtn"),
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Saving Lead",
        message:
          "There is an error saving your information. Please contact the system administrator.",
      });
      return;
    }

    showMessage({ status: "success", title: "Lead saved successfully!" });

    renderTable().load(1);
    renderCounts();
    clearInputs();
    closeSideModal("LeadDetailsSideModal");
  });

  // ============================================================
  // ACTIVITY EVENTS
  // ============================================================

  leadAddActivityBtn.addEventListener("click", () =>
    openDropdown(leadActivityDropdown),
  );

  cancelActivityBtn.addEventListener("click", () => {
    activityStatusInput.value = "";
    activityTypeInput.value = "";
    activityDescInput.value = "";
    activityAttachmentInput.value = "";
    closeDropdown(leadActivityDropdown);
  });

  saveActivityBtn.addEventListener("click", async function () {
    const formData = new FormData();

    formData.append("leadUUId", leadUUID);
    formData.append("status", activityStatusInput.value);
    formData.append("type", activityTypeInput.value);
    formData.append("activity", activityDescInput.value);

    if (activityAttachmentInput.files.length > 0) {
      formData.append("attachment", activityAttachmentInput.files[0]);
    }
    const response = await apiCall({
      mode: "POST",
      isJson: false,
      payload: formData,
      url: "/api/crm/activity",
      button: saveActivityBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving Activity" });
      return;
    }

    showMessage({ status: "success", title: "Activity Saved!" });
    activityStatusInput.value = "";
    activityTypeInput.value = "";
    activityDescInput.value = "";
    activityAttachmentInput.value = "";
    closeDropdown(leadActivityDropdown);
    reloadCrmData();
  });

  // ============================================================
  // NOTE EVENTS
  // ============================================================

  leadAddNoteBtn.addEventListener("click", () =>
    openDropdown(leadNoteDropdown),
  );

  cancelNoteBtn.addEventListener("click", () => {
    noteInput.value = "";
    closeDropdown(leadNoteDropdown);
  });

  saveNoteBtn.addEventListener("click", async function () {
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: { leadUUId: leadUUID, note: noteInput.value },
      url: "/api/crm/note",
      button: saveNoteBtn,
    });

    if (!response.success) {
      showMessage({ status: "error", title: "Error Saving Note" });
      return;
    }

    showMessage({ status: "success", title: "Note saved!" });
    noteInput.value = "";
    closeDropdown(leadNoteDropdown);
    loadLeadInfo(leadUUID);
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
      !leadAddActivityBtn.contains(e.target) &&
      !leadActivityDropdown.contains(e.target)
    )
      closeDropdown(leadActivityDropdown);

    if (
      !leadAddNoteBtn.contains(e.target) &&
      !leadNoteDropdown.contains(e.target)
    )
      closeDropdown(leadNoteDropdown);

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

    document
      .getElementById("btnNewLead")
      .addEventListener("click", function () {
        window.crmLeadFormUuid = null;
        loadPage({ title: "New Lead", link: "/page_crmLeadForm" });
      });
  }

  initializePage();
};
