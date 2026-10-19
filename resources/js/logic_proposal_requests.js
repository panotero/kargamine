// Management's Proposal Request assignment queue - resources/views/pages/proposal_requests.blade.php.
// Lists every ProposalRequest (GET /api/proposalRequests) and lets
// Management assign the prospect's CSR (assigned_to) / Relationship
// Manager (relationship_manager_id) via <x-side-modal id="assignOwnersModal">
// (POST /api/crm/prospects/{uuid}/assignment) - see
// ProposalRequestAssignmentController::index() / ProspectController::assignOwners().
// Loaded once globally (resources/js/app.js) like every other logic_*.js
// file; initProposalRequestsPage() is re-run on every SPA visit by this
// page's own bootstrap <script>, and self-guards by checking for the page
// root element.
(function () {
  const EMPTY = "—";

  // pending/assigned/for_approval/approved/signed/cancelled - mirrors
  // logic_prospect_info_modal.js's own STATUS_PILL copy (small intentional
  // duplication, same "own local cache per file" precedent used throughout
  // this codebase).
  const STATUS_PILL = {
    pending: { label: "Pending", classes: "bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-200" },
    assigned: { label: "Assigned", classes: "bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300" },
    for_approval: { label: "For Approval", classes: "bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300" },
    approved: { label: "Approved", classes: "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300" },
    signed: { label: "Signed", classes: "bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300" },
    cancelled: { label: "Cancelled", classes: "bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-500" },
  };

  let activeAssignmentStatus = "all";
  let assignableUsersOptionsHtml = "";
  let assignableUsersLoaded = false;
  let currentAssignProspectUuid = null;

  // The ProposalRequest row + full prospect currently shown in
  // ProposalRequestInfoModal - kept around so the rail's "Assign" button
  // (bound once) and saveAssignment()'s post-save refresh both know what's
  // open without re-reading the DOM.
  let currentInfoPr = null;
  let currentInfoLead = null;

  // ------------------------------------------------------------------
  // Small display-only constants for the Requirements/Contacts/Activity
  // tabs below - same "own local copy per file" duplication already used
  // throughout this codebase (see logic_prospect_info_modal.js's own
  // near-identical set, and its header comment explaining why).
  // ------------------------------------------------------------------
  const CONTAINER_TYPE_LABEL = {
    CV: "Container Van (CV)",
    FR: "Flatrack (FR)",
    RF: "Reefer Van (RF)",
    LC: "Loose Cargo (LC)",
    RC: "Rolling Cargo (RC)",
  };
  const CONTAINER_TYPE_DOT_COLOR = {
    CV: "bg-orange-500",
    FR: "bg-amber-500",
    RF: "bg-cyan-500",
    LC: "bg-purple-500",
    RC: "bg-blue-500",
  };
  const TYPE_FIELD_VISIBILITY = {
    CV: { convanSize: true, temperature: false, revenueTon: false },
    FR: { convanSize: true, temperature: false, revenueTon: false },
    RF: { convanSize: true, temperature: true, revenueTon: false },
    LC: { convanSize: false, temperature: false, revenueTon: true },
    RC: { convanSize: false, temperature: false, revenueTon: true },
  };
  const WEIGHT_UNIT_LABEL = { kg: "kg", mt: "MT" };
  const DISPATCH_MODE_LABEL = { single: "Single", tandem: "Tandem" };
  const CHANNEL_TYPE_LABEL = { mobile: "Mobile", landline: "Landline", email: "Email" };
  const CONTACT_TYPE_LABEL = { personal: "Personal", business: "Business" };
  const ACTIVITY_DOT_COLOR = {
    proposal_approved: "bg-green-500",
    proposal_signed: "bg-green-500",
    proposal_disapproved: "bg-red-500",
    proposal_rejected: "bg-red-500",
    proposal_cancelled: "bg-red-500",
    rfp_cancelled: "bg-red-500",
  };
  const ACTIVITY_DOT_DEFAULT = "bg-blue-500";

  function esc(v) {
    return String(v ?? "").replace(
      /[&<>"']/g,
      (c) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;",
        })[c],
    );
  }

  function refreshSearchable(el) {
    el?._searchableSelect?.refresh();
  }

  function statusPill(status) {
    const pill = STATUS_PILL[status] ?? STATUS_PILL.pending;
    return `<span class="text-xs font-semibold px-2.5 py-1 rounded-full ${pill.classes}">${pill.label}</span>`;
  }

  function formatLocationLabel(loc) {
    const line =
      [loc.address_no, loc.address_building, loc.address_street, loc.address_barangay, loc.address_town_city, loc.address_province]
        .filter(Boolean)
        .join(", ") || "Unnamed location";
    return loc.address_type ? `${esc(loc.address_type)} — ${esc(line)}` : esc(line);
  }

  // ------------------------------------------------------------------
  // PROPOSAL REQUEST INFO MODAL - read-only tabs (Locations/Contacts/
  // Requirements/Activity), ported from logic_prospect_info_modal.js's
  // equivalent renderers minus any Add/Remove wiring - see this file's
  // header comment on why this is a separate modal, not a reused one.
  // ------------------------------------------------------------------
  function renderPriLocations(lead) {
    const locations = lead.addresses ?? [];
    const wrap = document.getElementById("priLocationsSummary");
    wrap.innerHTML = locations.length
      ? locations
          .map(
            (loc) => `
      <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-3">
          <p class="text-sm text-zinc-800 dark:text-zinc-100">${formatLocationLabel(loc)}</p>
          <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Postal Code: ${esc(loc.address_postal_code || EMPTY)}${loc.is_primary ? " · Primary" : ""}</p>
      </div>`,
          )
          .join("")
      : `<p class="text-sm text-zinc-400 dark:text-zinc-500">No locations on file.</p>`;
  }

  function renderPriContacts(lead) {
    const locationsById = {};
    (lead.addresses ?? []).forEach((loc) => (locationsById[loc.id] = formatLocationLabel(loc)));

    const contacts = lead.contacts ?? [];
    const wrap = document.getElementById("priContactsSummary");
    wrap.innerHTML = contacts.length
      ? contacts
          .map((contact) => {
            const name = [contact.title, contact.first_name, contact.middle_name, contact.last_name]
              .filter(Boolean)
              .join(" ");
            const channelsHtml = (contact.channels ?? []).length
              ? contact.channels
                  .map(
                    (ch) =>
                      `<li>${esc(CHANNEL_TYPE_LABEL[ch.channel_type] ?? ch.channel_type)}: ${esc(ch.value)} (${esc(CONTACT_TYPE_LABEL[ch.contact_type] ?? ch.contact_type)})</li>`,
                  )
                  .join("")
              : `<li>${EMPTY}</li>`;
            const locationId = (contact.location_ids ?? [])[0];
            const addressLine = locationId != null ? (locationsById[locationId] ?? EMPTY) : EMPTY;

            return `
      <div class="border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-xl p-4 space-y-2">
          <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">${esc(name || EMPTY)}</p>
          <p class="text-xs text-zinc-400 dark:text-zinc-500">${esc(contact.position || EMPTY)}</p>
          <ul class="text-sm text-zinc-700 dark:text-zinc-200 list-disc list-inside">${channelsHtml}</ul>
          <p class="text-xs text-zinc-400 dark:text-zinc-500">Address: ${esc(addressLine)}</p>
      </div>`;
          })
          .join("")
      : `<p class="text-sm text-zinc-400 dark:text-zinc-500">No contact persons on file.</p>`;
  }

  function priContainerRowHtml(c) {
    const NA = "-";
    const flags = TYPE_FIELD_VISIBILITY[c.container_type] || {};
    const typeLabel = CONTAINER_TYPE_LABEL[c.container_type] ?? c.container_type ?? EMPTY;
    const dot = CONTAINER_TYPE_DOT_COLOR[c.container_type] ?? "bg-zinc-400";
    const weight =
      c.weight != null
        ? `${Number(c.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[c.weight_unit] ?? c.weight_unit ?? ""}`.trim()
        : EMPTY;

    const cells = [
      `<span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full ${dot} shrink-0"></span>${esc(typeLabel)}</span>`,
      flags.convanSize ? esc(c.container_size?.size ?? EMPTY) : NA,
      flags.temperature ? esc(c.minimum_temperature ?? EMPTY) : NA,
      flags.revenueTon ? esc(c.revenue_ton_unit ?? EMPTY) : NA,
      flags.revenueTon ? esc(c.cargo_measurement ?? EMPTY) : NA,
      esc(c.quantity ?? EMPTY),
      esc(c.frequency ?? EMPTY),
      esc(c.delivery_type?.name ?? EMPTY),
      esc(c.origin_location?.name ?? EMPTY),
      esc(c.destination_location?.name ?? EMPTY),
      c.declared_value_per_unit != null ? `₱${Number(c.declared_value_per_unit).toLocaleString()}` : EMPTY,
      weight,
      esc(c.cargo_type ?? EMPTY),
      esc(c.general_cargo_description ?? EMPTY),
      esc(c.special_requirements ?? EMPTY),
      esc(c.booking_unit_type ?? EMPTY),
    ];
    return `<tr>${cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("")}</tr>`;
  }

  function priTruckingRowHtml(t) {
    const weight =
      t.weight != null
        ? `${Number(t.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[t.weight_unit] ?? t.weight_unit ?? ""}`.trim()
        : EMPTY;
    const cells = [
      esc(t.trucking_cargo_type ?? EMPTY),
      esc(t.quantity ?? EMPTY),
      esc(t.frequency ?? EMPTY),
      esc(DISPATCH_MODE_LABEL[t.dispatch_mode] ?? t.dispatch_mode ?? EMPTY),
      esc(t.origin_location?.name ?? EMPTY),
      esc(t.destination_location?.name ?? EMPTY),
      t.declared_value_per_unit != null ? `₱${Number(t.declared_value_per_unit).toLocaleString()}` : EMPTY,
      weight,
      esc(t.cargo_type ?? EMPTY),
      esc(t.general_cargo_description ?? EMPTY),
      esc(t.special_requirements ?? EMPTY),
    ];
    return `<tr>${cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("")}</tr>`;
  }

  function priCharterRowHtml(c) {
    const cargoSummary =
      (c.cargo_items ?? [])
        .map((item) => item.cargo_type)
        .filter(Boolean)
        .join(", ") || EMPTY;
    const portSummary = (c.ports ?? []).map((p, i) => `PORT ${i + 1}: ${p.port?.name ?? "—"}`).join(", ") || EMPTY;
    const weight =
      c.weight != null
        ? `${Number(c.weight).toLocaleString()} ${WEIGHT_UNIT_LABEL[c.weight_unit] ?? c.weight_unit ?? ""}`.trim()
        : EMPTY;
    const cells = [
      esc(cargoSummary),
      esc(portSummary),
      esc(c.charter_start_date ?? EMPTY),
      esc(c.charter_end_date ?? EMPTY),
      c.declared_value != null ? `₱${Number(c.declared_value).toLocaleString()}` : EMPTY,
      weight,
    ];
    return `<tr>${cells.map((v) => `<td class="px-3 py-2 align-top text-zinc-700 dark:text-zinc-200">${v}</td>`).join("")}</tr>`;
  }

  function renderPriRequirementTables(lead) {
    document.getElementById("priContainersTableBody").innerHTML = (lead.requirement_containers ?? [])
      .map(priContainerRowHtml)
      .join("");
    document.getElementById("priTruckingsTableBody").innerHTML = (lead.requirement_truckings ?? [])
      .map(priTruckingRowHtml)
      .join("");
    document.getElementById("priChartersTableBody").innerHTML = (lead.requirement_charters ?? [])
      .map(priCharterRowHtml)
      .join("");
  }

  function priActivityRowHtml(item) {
    const dot = ACTIVITY_DOT_COLOR[item.type] ?? ACTIVITY_DOT_DEFAULT;
    return `
    <div class="flex gap-3 py-1.5">
        <span class="w-2 h-2 rounded-full ${dot} shrink-0 mt-1.5"></span>
        <div>
            <p class="text-xs text-zinc-800 dark:text-zinc-100">${esc(item.description)}</p>
            <p class="text-[11px] text-zinc-400 dark:text-zinc-500">${esc(item.user?.name ?? "System")} · ${esc(formatDateTime(item.created_at))}</p>
        </div>
    </div>`;
  }

  function renderPriActivity(lead) {
    const items = lead.activities ?? [];
    document.getElementById("priActivityTimeline").innerHTML = items.length
      ? items.map(priActivityRowHtml).join("")
      : `<p class="text-xs text-zinc-400 dark:text-zinc-500">No activity recorded yet.</p>`;
  }

  function switchPriTab(tab) {
    document.querySelectorAll(".pri-tab-pane").forEach((pane) => {
      pane.classList.toggle("hidden", pane.dataset.priPane !== tab);
    });
    document.querySelectorAll(".pri-tab-btn").forEach((btn) => {
      const active = btn.dataset.priTab === tab;
      btn.classList.toggle("border-orange-500", active);
      btn.classList.toggle("text-orange-600", active);
      btn.classList.toggle("dark:text-orange-400", active);
      btn.classList.toggle("border-transparent", !active);
      btn.classList.toggle("text-zinc-500", !active);
      btn.classList.toggle("dark:text-zinc-400", !active);
    });
  }

  function openProposalRequestInfoModal(pr, lead) {
    currentInfoPr = pr;
    currentInfoLead = lead;

    document.getElementById("priRequestCode").textContent = pr.code ?? EMPTY;
    document.getElementById("priCompanyName").textContent = lead.company?.company_name || EMPTY;
    document.getElementById("priCode").textContent = pr.code ?? EMPTY;
    document.getElementById("priStatus").innerHTML = statusPill(pr.status);
    document.getElementById("priCreatedAt").textContent = formatDateTime(pr.created_at);
    document.getElementById("priCreatedBy").textContent = pr.creator?.name || EMPTY;
    document.getElementById("priCompanyNameFull").textContent = lead.company?.company_name || EMPTY;
    document.getElementById("priCsrName").textContent = lead.user?.name || EMPTY;
    document.getElementById("priRmName").textContent = lead.relationship_manager?.name || EMPTY;

    document.getElementById("priAssignBtnWrap").classList.toggle("hidden", pr.status !== "pending");

    renderPriLocations(lead);
    renderPriContacts(lead);
    renderPriRequirementTables(lead);
    renderPriActivity(lead);
    switchPriTab("Locations");

    window.initModal({ modalId: "ProposalRequestInfoModal" });
  }
  window.openProposalRequestInfoModal = openProposalRequestInfoModal;

  // ------------------------------------------------------------------
  // ASSIGNABLE USERS (shared options list for both CSR and RM selects) -
  // fetched once ever, same "fetch once, paint per open" pattern as
  // logic_prospect_modal.js's lookup caches.
  // ------------------------------------------------------------------
  async function ensureAssignableUsersLoaded() {
    if (assignableUsersLoaded) return;
    const response = await apiCall({
      mode: "GET",
      url: "/api/crm/prospects/assignable-users",
    });
    if (!response.success) return;
    assignableUsersOptionsHtml =
      '<option value="">Select User</option>' +
      response.data.map((u) => `<option value="${u.id}">${esc(u.name)}</option>`).join("");
    assignableUsersLoaded = true;
  }

  // ------------------------------------------------------------------
  // COUNTS + STATUS STRIP
  // ------------------------------------------------------------------
  function updateAssignmentCounts(counts) {
    document.getElementById("prCountAll").textContent = counts?.all ?? 0;
    document.getElementById("prCountAwaiting").textContent = counts?.awaiting ?? 0;
    document.getElementById("prCountAssigned").textContent = counts?.assigned ?? 0;
  }

  // ------------------------------------------------------------------
  // TABLE
  // ------------------------------------------------------------------
  function renderTable() {
    const thead = [
      { title: "RQ Code", key: "code" },
      {
        title: "Company",
        key: "prospect.company.company_name",
        render: (r) => esc(r.prospect?.company?.company_name || EMPTY),
      },
      { title: "Status", key: "status", render: (r) => statusPill(r.status) },
      {
        title: "Assigned CSR",
        key: "prospect.user.name",
        render: (r) => esc(r.prospect?.user?.name || EMPTY),
      },
      {
        title: "RM",
        key: "prospect.relationship_manager.name",
        render: (r) => esc(r.prospect?.relationship_manager?.name || EMPTY),
      },
      { title: "Created", key: "created_at", render: (r) => formatDateTime(r.created_at) },
    ];

    return window.renderRemoteTable({
      url: "/api/proposalRequests",
      tableId: "tableProposalRequests",
      thead,
      afterRenderFunction: (row) => {
        // Dedicated read-only ProposalRequestInfoModal (not ProspectInfoModal)
        // - needs its own fetch since the table row payload only has the
        // handful of fields its own columns render.
        row.addEventListener("click", async function () {
          const pr = JSON.parse(row.dataset.row);
          const uuid = pr.prospect?.uuid;
          if (!uuid) {
            showMessage({ status: "error", title: "This request has no prospect on file." });
            return;
          }
          const response = await apiCall({ mode: "GET", url: `/api/crm/prospects/${uuid}` });
          if (!response.success) return;
          openProposalRequestInfoModal(pr, response.data);
        });
      },
      emptyMessage: () => {
        if (activeAssignmentStatus === "awaiting") return "No requests awaiting assignment.";
        if (activeAssignmentStatus === "assigned") return "No assigned requests yet.";
        return "No proposal requests yet.";
      },
    });
  }

  async function loadProposalRequests() {
    const response = await apiCall({
      mode: "GET",
      url: "/api/proposalRequests",
    });
    if (!response.success) return;
    updateAssignmentCounts(response.status_counts);
    renderTable().load(1);
  }

  // ------------------------------------------------------------------
  // ASSIGN OWNERS MODAL - takes a Prospect-shaped object directly
  // (uuid/company.company_name/user.id/relationship_manager.id) rather
  // than a ProposalRequest row, reached from ProposalRequestInfoModal's
  // rail "Assign" button (shown only while status === "pending", see
  // openProposalRequestInfoModal()/priAssignBtn's binding below) - a
  // ProposalRequest's own `code` is a separate, optional param purely for
  // this modal's subtitle.
  // ------------------------------------------------------------------
  async function openAssignModal(lead, requestCode) {
    const modal = document.getElementById("assignOwnersModal");
    if (!modal) return;

    currentAssignProspectUuid = lead?.uuid ?? null;
    if (!currentAssignProspectUuid) {
      showMessage({ status: "error", title: "This request has no prospect on file." });
      return;
    }

    await ensureAssignableUsersLoaded();

    modal.querySelector("#assignRequestCode").textContent = requestCode ?? "";
    modal.querySelector("#assignCompanyName").textContent = lead?.company?.company_name || EMPTY;

    const csrSelect = modal.querySelector("#assignCsrSelect");
    const rmSelect = modal.querySelector("#assignRmSelect");
    csrSelect.innerHTML = assignableUsersOptionsHtml;
    rmSelect.innerHTML = assignableUsersOptionsHtml;
    csrSelect.value = String(lead?.user?.id ?? "");
    rmSelect.value = String(lead?.relationship_manager?.id ?? "");
    refreshSearchable(csrSelect);
    refreshSearchable(rmSelect);

    window.initSideModal({ modalId: "assignOwnersModal" });
  }

  async function saveAssignment(button) {
    const modal = document.getElementById("assignOwnersModal");
    const assignedTo = modal.querySelector("#assignCsrSelect").value;
    const relationshipManagerId = modal.querySelector("#assignRmSelect").value;

    if (!assignedTo || !relationshipManagerId) {
      showMessage({ status: "error", title: "Select both a CSR and a Relationship Manager." });
      return;
    }

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: {
        assigned_to: assignedTo,
        relationship_manager_id: relationshipManagerId,
      },
      url: `/api/crm/prospects/${currentAssignProspectUuid}/assignment`,
      button,
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Assigning",
        message: response.message ?? "",
      });
      return;
    }

    showMessage({ status: "success", title: "Owners Assigned" });
    window.closeSideModal("assignOwnersModal");
    renderTable().reload();

    // ProposalRequestInfoModal stays open underneath - refresh its rail's
    // CSR/RM text in place rather than making the user close/reopen it.
    if (currentInfoLead?.uuid === currentAssignProspectUuid) {
      const refreshed = await apiCall({ mode: "GET", url: `/api/crm/prospects/${currentAssignProspectUuid}` });
      if (refreshed.success) {
        currentInfoLead = refreshed.data;
        document.getElementById("priCsrName").textContent = currentInfoLead.user?.name || EMPTY;
        document.getElementById("priRmName").textContent = currentInfoLead.relationship_manager?.name || EMPTY;
      }
    }
  }

  // ------------------------------------------------------------------
  // PUBLIC API
  // ------------------------------------------------------------------
  function initProposalRequestsPage() {
    const root = document.getElementById("ProposalRequestsPage");
    if (!root) return; // guarded: only runs on this page

    activeAssignmentStatus = "all";
    currentAssignProspectUuid = null;
    currentInfoPr = null;
    currentInfoLead = null;

    window.makeSearchableSelect(document.getElementById("assignCsrSelect"));
    window.makeSearchableSelect(document.getElementById("assignRmSelect"));

    ensureAssignableUsersLoaded();
    loadProposalRequests();

    document.querySelectorAll(".prAssignmentStatusBtn").forEach((btn) => {
      btn.addEventListener("click", function () {
        activeAssignmentStatus = this.dataset.assignmentStatus;
        document
          .querySelectorAll(".prAssignmentStatusBtn")
          .forEach((b) => b.classList.remove("ring-2", "ring-orange-500"));
        this.classList.add("ring-2", "ring-orange-500");
        renderTable().setFilter("assignment_status", activeAssignmentStatus);
      });
    });

    document.getElementById("assignSaveBtn").addEventListener("click", function () {
      saveAssignment(this);
    });

    document.querySelectorAll(".pri-tab-btn").forEach((btn) => {
      btn.addEventListener("click", function () {
        switchPriTab(this.dataset.priTab);
      });
    });

    document.getElementById("priAssignBtn").addEventListener("click", function () {
      if (!currentInfoLead) return;
      openAssignModal(currentInfoLead, currentInfoPr?.code);
    });
  }
  window.initProposalRequestsPage = initProposalRequestsPage;
})();
