window.initCrmLogic = function initCrmLogic() {
  // ============================================================
  // STATE
  // ============================================================

  let leadUUID = "";
  let leadsArray = [];
  let leadInfo = {};

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

  const PROPOSAL_STATUS = {
    APPROVED: 2,
  };

  const COUNT_MAP = {
    ALL: "countALL",
    LEAD: "countLead",
    QUALIFIED: "countQualified",
    OPPORTUNITY: "countOpportunity",
    NEGOTIATION: "countNegotiation",
    WIN: "countWin",
    LOST: "countLose",
  };

  // ============================================================
  // DOM REFS
  // ============================================================

  const addActivityBtn = document.getElementById("addActivityBtn");
  const activityDropdown = document.getElementById("activityDropdown");
  const activityDescInput = document.getElementById("activityDescriptionInput");
  const activityTypeInput = document.getElementById("activityTypeInput");
  const activityStatusInput = document.getElementById("activityStatusInput");
  const saveActivityBtn = document.getElementById("saveActivityBtn");
  const cancelActivityBtn = document.getElementById("cancelActivityBtn");

  const addNoteBtn = document.getElementById("addNoteBtn");
  const noteDropdown = document.getElementById("noteDropdown");
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
            <div class="w-full p-2 rounded-md text-center">
                <p class="font-semibold text-zinc-400">${message}</p>
            </div>`;
  }

  // ============================================================
  // API
  // ============================================================

  async function getLeads() {
    const leads = await apiCall({
      mode: "GET",
      url: "/api/crm/leads",
    });
    leadsArray = leads;
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

  function renderTable(leads) {
    document.getElementById("crmTableBody").innerHTML = initLoading();

    const html = leads
      .map((row) => {
        const status = row.status?.status ?? "UNKNOWN";
        const statusClass = getStatusBadgeClass(status);

        return `
                <tr class="cursor-pointer hover:bg-zinc-100" data-uuid="${row.uuid}">
                    <td>${row.contact_name}</td>
                    <td>${row.company?.company_name ?? "No Company"}</td>
                    <td>${row.email}</td>
                    <td>${row.mobile}</td>
                    <td>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                            ${status}
                        </span>
                    </td>
                    <td>${row.user.name}</td>
                    <td>${formatDateTime(row.created_at)}</td>
                </tr>`;
      })
      .join("");

    $("#crmTableBody").html(html);
    initDataTables(10);
  }

  // ============================================================
  // RENDER — COUNTS
  // ============================================================

  function renderCounts(leads) {
    const counts = {
      ALL: leads.length,
      LEAD: 0,
      QUALIFIED: 0,
      OPPORTUNITY: 0,
      NEGOTIATION: 0,
      WIN: 0,
      LOST: 0,
    };

    leads.forEach((row) => {
      const status = row.status.status;
      if (counts.hasOwnProperty(status)) counts[status]++;
    });

    Object.entries(COUNT_MAP).forEach(([key, elementId]) => {
      const el = document.getElementById(elementId);
      if (el) el.innerText = counts[key] ?? 0;
    });
  }

  // ============================================================
  // RENDER — LEAD INFO
  // ============================================================

  async function loadLeadInfo() {
    const loader = loadingLine();
    [
      "#leadCompanyName",
      "#leadStatus",
      "#leadContactName",
      "#leadEmail",
      "#leadMobile",
      "#leadSource",
      "#leadEstimatedValue",
      "#leadCreatedAt",
      "#leadExpectedCloseDate",
      "#noteContainer",
      "#activityContainer",
    ].forEach((id) => $(id).html(loader));
    document.getElementById("proposalContainer").innerHTML = loader;

    const response = await apiCall({
      mode: "GET",
      url: `/api/crm/leads/${leadUUID}`,
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
    const value = Number(lead?.estimated_value || 0);
    const statusClass = getStatusBadgeClass(lead.status.status);

    $("#leadCompanyName").html(lead.company.company_name.toUpperCase() ?? "");
    $("#leadStatus").html(`
            <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                ${lead.status.status}
            </span>`);
    $("#leadContactName").html(lead.contact_name ?? "");
    $("#leadEmail").html(lead.email ?? "");
    $("#leadMobile").html(lead.mobile ?? "");
    $("#leadSource").html(lead.source ?? "");
    $("#leadEstimatedValue").html(`₱${value.toLocaleString()}`);
    $("#leadCreatedAt").html(formatDateTime(lead.created_at) ?? "");
    $("#leadExpectedCloseDate").html(
      formatDateTime(lead.expected_close_date) ?? "",
    );

    $("#contactName").val(lead.contact_name ?? "");
    $("#contactEmail").val(lead.email ?? "");
    $("#contactMobile").val(lead.mobile ?? "");
    $("#activityStatusInput").val(lead.status.id ?? "");

    $("#btnEditLead").removeClass("hidden");
    $("#btnSaveLead").addClass("hidden");

    renderActivity(lead.activities);
    renderNotes(lead.notes);
    renderProposals(lead.proposals);
  }

  // ============================================================
  // RENDER — PROPOSALS
  // ============================================================

  function renderProposals(proposals) {
    const container = document.getElementById("proposalContainer");

    if (!proposals.length) {
      container.innerHTML = emptyState(
        "There's no proposal yet. Create one now!",
      );
      return;
    }

    container.innerHTML = proposals
      .map((proposal) => {
        const statusClass = getStatusBadgeClass(proposal.status.status);
        const isApproved = proposal.status.id === PROPOSAL_STATUS.APPROVED;
        const downloadUrl = `/createpdf/${proposal.id}`;
        const downloadClass = isApproved
          ? "bg-orange-600 hover:bg-orange-700"
          : "pointer-events-none opacity-50 cursor-not-allowed bg-gray-400";

        return `
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 w-full flex justify-between items-center gap-4">

                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full ${statusClass}"></span>
                            <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">${proposal.status.status}</p>
                        </div>
                        <h2 class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${proposal.code}</h2>
                        <p class="text-[11px] text-zinc-400">${formatDateTime(proposal.created_at)}</p>
                    </div>

                    <a href="${downloadUrl}" target="_blank"
                        class="shrink-0 ${downloadClass} text-white w-8 h-8 flex items-center justify-center rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5l4.5 4.5m0 0l4.5-4.5m-4.5 4.5V3" />
                        </svg>
                    </a>

                </div>`;
      })
      .join("");
  }

  // ============================================================
  // RENDER — ACTIVITIES
  // ============================================================

  function renderActivity(activities) {
    const container = document.getElementById("activityContainer");

    if (!activities.length) {
      container.innerHTML = emptyState("There's no activity yet.");
      return;
    }

    container.innerHTML = activities
      .map(
        (activity) => `
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 w-full flex justify-between items-start gap-4">
                <div class="flex gap-3 items-start">
                    <div class="mt-0.5 w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600 dark:text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-[11px] font-medium text-zinc-400 uppercase tracking-widest">${activity.type}</p>
                        <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100">${activity.description}</p>
                        <p class="text-[11px] text-zinc-400">${formatDateTime(activity.created_at)} &middot; ${activity.user.name}</p>
                    </div>
                </div>
            </div>`,
      )
      .join("");
  }

  // ============================================================
  // RENDER — NOTES
  // ============================================================

  function renderNotes(notes) {
    const container = document.getElementById("noteContainer");

    if (!notes.length) {
      container.innerHTML = emptyState("There's no notes yet.");
      return;
    }

    container.innerHTML = notes
      .map(
        (note) => `
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 w-full flex flex-col gap-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center shrink-0">
                            <span class="text-[10px] font-medium text-zinc-500 dark:text-zinc-300">${note.user.name.charAt(0).toUpperCase()}</span>
                        </div>
                        <p class="text-[11px] text-zinc-400">${note.user.name} &middot; ${formatDateTime(note.created_at)}</p>
                    </div>
                    <button class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition w-7 h-7 flex items-center justify-center rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="5" cy="12" r="2"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                            <circle cx="19" cy="12" r="2"></circle>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">${note.note}</p>
            </div>`,
      )
      .join("");
  }

  // ============================================================
  // LEAD FORM
  // ============================================================

  function getLeadFormData() {
    return {
      contact_name: $("#contact_name").val(),
      email: $("#email").val(),
      mobile: $("#mobile").val(),
      source: $("#source").val(),
      estimated_value: $("#estimated_value").val(),
      expected_close_date: $("#expected_close_date").val(),
    };
  }

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

    const leads = await getLeads();
    renderTable(leads);
    renderCounts(leads);
    clearInputs();
    closeSideModal("LeadDetailsSideModal");
  });

  $(document).on("click", "#btnEditLead", function () {
    $(".lead-input").prop("readonly", false);
    $("#btnEditLead").addClass("hidden");
  });

  $(document).on("input change", ".lead-input", function () {
    const changed =
      JSON.stringify(getLeadFormData()) !== JSON.stringify(originalLeadData);
    $("#btnSaveLead").toggleClass("hidden", !changed);
  });

  $(document).on("click", "#btnSaveLead", async function () {
    const response = await apiCall({
      mode: "PUT",
      isJson: true,
      payload: JSON.stringify(getLeadFormData()),
      url: "/api/crm/leads",
      button: document.getElementById("btnSaveLead"),
    });

    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Updating Lead",
        message:
          "There is an error updating your information. Please contact the system administrator.",
      });
      return;
    }

    toastr.success("Lead updated successfully");
    originalLeadData = getLeadFormData();
    $(".lead-input").prop("readonly", true);
    $("#btnSaveLead").addClass("hidden");
    $("#btnEditLead").removeClass("hidden");
  });

  // ============================================================
  // STATUS FILTER BUTTONS
  // ============================================================

  document.querySelectorAll(".statusBtn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const status = btn.dataset.status;
      const filtered =
        status === "ALL"
          ? leadsArray
          : leadsArray.filter((row) => row.status.status === status);
      renderTable(filtered);
    });
  });

  // ============================================================
  // TABLE ROW CLICK
  // ============================================================

  $(document).on("click", "#crmTable tbody tr", function () {
    leadUUID = $(this).data("uuid");
    window.uuid = leadUUID;
    initModal({ modalId: "LeadInfoModal" });
    loadLeadInfo();
  });

  // ============================================================
  // ACTIVITY EVENTS
  // ============================================================

  addActivityBtn.addEventListener("click", () =>
    openDropdown(activityDropdown),
  );

  cancelActivityBtn.addEventListener("click", () => {
    activityStatusInput.value = "";
    activityTypeInput.value = "";
    activityDescInput.value = "";
    closeDropdown(activityDropdown);
  });

  saveActivityBtn.addEventListener("click", async function () {
    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: {
        leadUUId: leadUUID,
        status: activityStatusInput.value,
        type: activityTypeInput.value,
        activity: activityDescInput.value,
      },
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
    closeDropdown(activityDropdown);
    reloadCrmData();
  });

  // ============================================================
  // NOTE EVENTS
  // ============================================================

  addNoteBtn.addEventListener("click", () => openDropdown(noteDropdown));

  cancelNoteBtn.addEventListener("click", () => {
    noteInput.value = "";
    closeDropdown(noteDropdown);
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
    closeDropdown(noteDropdown);
    loadLeadInfo();
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
    loadLeadInfo();
  });

  $(".editContactDropdown").on("change", function () {
    $("#saveContactInfoBtn").removeClass("hidden");
  });

  // ============================================================
  // CLICK OUTSIDE — CLOSE DROPDOWNS
  // ============================================================

  window.addEventListener("click", (e) => {
    if (
      !addActivityBtn.contains(e.target) &&
      !activityDropdown.contains(e.target)
    )
      closeDropdown(activityDropdown);

    if (!addNoteBtn.contains(e.target) && !noteDropdown.contains(e.target))
      closeDropdown(noteDropdown);

    if (
      !editContactBtn.contains(e.target) &&
      !editContactInfoDropdown.contains(e.target)
    )
      closeDropdown(editContactInfoDropdown);
  });

  // ============================================================
  // NEW PROPOSAL BUTTON
  // ============================================================

  document
    .getElementById("NewProposalBtn")
    .addEventListener("click", function () {
      const proposalModal = document.querySelector("#generateProposal");
      if (!proposalModal) return;

      proposalModal.querySelector('input[name="company_name"]').value =
        leadInfo.company.company_name;
      proposalModal.querySelector('input[name="company_address"]').value =
        leadInfo.company.company_address;
      proposalModal.querySelector(
        'input[name="authorized_signatory_name"]',
      ).value = leadInfo.company.authorized_signatory_name;
      proposalModal.querySelector(
        'input[name="authorized_signatory_position"]',
      ).value = leadInfo.company.authorized_signatory_position;
      proposalModal.dataset.leadUuid = leadUUID;

      initSideModal({ modalId: "generateProposal" });
    });

  // ============================================================
  // PUBLIC API
  // ============================================================

  window.reloadCrmData = function () {
    loadLeadInfo();
    updateLeadDetails();
  };

  // ============================================================
  // INIT
  // ============================================================

  async function updateLeadDetails() {
    await getLeads();
    renderTable(leadsArray);
    renderCounts(leadsArray);
  }

  async function initializePage() {
    updateLeadDetails();
    getStatuses();

    $("#btnNewLead").click(function () {
      initSideModal({ modalId: "LeadDetailsSideModal" });
    });
  }

  initializePage();
};
