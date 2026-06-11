window.initCrmLogic = function initCrmLogic() {
  let statusarray = [];
  let leadUUID = "";
  let leadsArray = [];
  let leadinfo = [];
  async function getLeads() {
    const leads = await apiCall({
      mode: "GET",
      url: "/api/crm/leads",
    });

    leadsArray = leads;
    return leads;
  }

  function renderCounts(leads) {
    const counts = {
      LEAD: 0,
      QUALIFIED: 0,
      OPPORTUNITY: 0,
      NEGOTIATION: 0,
      WIN: 0,
      LOST: 0,
    };
    let total = 0;

    leads.forEach((row) => {
      total++;
      const status = row.status.status;
      if (counts.hasOwnProperty(status)) {
        counts[status]++;
      }
    });

    document.getElementById("countALL").innerText = total;
    document.getElementById("countLead").innerText = counts.LEAD;
    document.getElementById("countQualified").innerText = counts.QUALIFIED;
    document.getElementById("countOpportunity").innerText = counts.OPPORTUNITY;
    document.getElementById("countNegotiation").innerText = counts.NEGOTIATION;
    document.getElementById("countWin").innerText = counts.WIN;
    document.getElementById("countLose").innerText = counts.LOST;
  }

  function renderTable(leads) {
    document.getElementById("crmTableBody").innerHTML = initLoading();
    let html = "";
    leads.forEach((row) => {
      const status = row.status?.status ?? "UNKNOWN";

      const statusClass = getStatusBadgeClass(status);

      html += `
        <tr class="cursor-pointer hover:bg-zinc-100"
            data-uuid="${row.uuid}">

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

        </tr>
    `;
    });

    $("#crmTableBody").html(html);

    initDataTables(10);
  }

  function getStatusBadgeClass(status) {
    switch (status) {
      case "LEAD":
        return "bg-gray-100 text-gray-700";

      case "QUALIFIED":
        return "bg-indigo-100 text-indigo-700";

      case "OPPORTUNITY":
        return "bg-purple-100 text-purple-700";

      case "NEGOTIATION":
        return "bg-amber-100 text-amber-700";

      case "WIN":
        return "bg-green-100 text-green-700";

      case "LOST":
        return "bg-red-100 text-red-700";

      default:
        return "bg-zinc-100 text-zinc-700";
    }
  }
  $(document).on("click", "#crmTable tbody tr", function () {
    const uuid = $(this).data("uuid");

    initModal({
      modalId: "LeadInfoModal",
    });
    leadUUID = uuid;
    loadLeadInfo();
  });
  $("#saveLeadBtn").on("click", async function (e) {
    const submitBtn = $("#saveLeadBtn");
    const form = $("#leadForm")[0];
    const estValue = form.elements["est_value"].value.replace(/,/g, "");
    e.preventDefault();
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
        title: "Error Saving Company Information",
        message:
          "There is some error saving your company information. Please contact system administrator",
      });
      return;
    }

    showMessage({
      status: "success",
      title: "Successfully Saved Company Info. Keep it up!",
    });

    const leads = await getLeads();
    renderTable(leads);
    renderCounts(leads);

    clearInputs();
    closeSideModal("LeadDetailsSideModal");
  });
  async function updateLead(id) {
    console.log("Update Lead", id);

    /*
            const response = await apiCall({
                mode: "PUT",
                url: `/api/crm/leads/${id}`,
                body: formData
            });
            */
  }
  async function deleteLead(id) {
    console.log("Delete Lead", id);

    /*
            const response = await apiCall({
                mode: "DELETE",
                url: `/api/crm/leads/${id}`
            });
            */
  }

  initializepage();
  async function initializepage() {
    updateLeadDetails();
    $("#btnNewLead").click(function () {
      // initModal({
      //     modalId: "NewLeadModal",
      // });
      initSideModal({
        modalId: "LeadDetailsSideModal",
      });
    });

    getStatuses();
  }

  async function updateLeadDetails() {
    await getLeads();
    renderTable(leadsArray);

    renderCounts(leadsArray);
  }

  async function getStatuses() {
    const statusdropdown = document.querySelectorAll(".statusDropDown");

    const statuses = await apiCall({
      mode: "GET",
      url: "/api/crm/getCrmStatus",
    });

    statusdropdown.forEach((dropdown) => {
      let html = `<option value="">Select Status</option>`;
      statuses.data.forEach((status) => {
        html += `
                        <option value="${status.id}">${status.status}</option>`;
      });
      dropdown.innerHTML = html;
    });
  }

  async function loadLeadInfo() {
    const loader = loadingLine();
    $("#leadCompanyName").html(loader);
    $("#leadStatus").html(loader);
    $("#leadContactName").html(loader);
    $("#leadEmail").html(loader);
    $("#leadMobile").html(loader);
    $("#leadSource").html(loader);
    $("#leadEstimatedValue").html(loader);
    $("#leadCreatedAt").html(loader);
    $("#leadExpectedCloseDate").html(loader);
    $("#noteContainer").html(loader);
    $("#activityContainer").html(loader);

    const leads = await apiCall({
      mode: "GET",
      url: `/api/crm/leads/${leadUUID}`,
    });

    console.log(leads);
    if (!leads.success) {
      showMessage({
        status: "error",
        title: "Error Saving Company Information",
        message:
          "There is some error saving your company information. Please contact system administrator",
      });
      return;
      closemodals();
    }

    leadinfo = leads.data;
    const lead = leads.data;
    const value = Number(lead?.estimated_value || 0);

    const statusClass = getStatusBadgeClass(lead.status.status);
    const statusHTML = `
                <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                    ${lead.status.status}
                </span>`;
    //company info
    $("#leadCompanyName").html(lead.company.company_name.toUpperCase() ?? "");
    $("#contactName").val(lead.contact_name ?? "");
    $("#contactEmail").val(lead.email ?? "");
    $("#contactMobile").val(lead.mobile ?? "");
    $("#activityStatusInput").val(lead.status.id ?? "");
    $("#leadStatus").html(statusHTML ?? "");
    $("#leadContactName").html(lead.contact_name ?? "");
    $("#leadEmail").html(lead.email ?? "");
    $("#leadMobile").html(lead.mobile ?? "");
    $("#leadSource").html(lead.source ?? "");
    $("#leadEstimatedValue").html(`₱${value.toLocaleString()}`);
    $("#leadCreatedAt").html(formatDateTime(lead.created_at) ?? "");
    $("#leadExpectedCloseDate").html(
      formatDateTime(lead.expected_close_date) ?? "",
    );

    $("#btnEditLead").removeClass("hidden");
    $("#btnSaveLead").addClass("hidden");

    //render activities
    renderActivity(leads.data.activities);

    //render notes
    renderNotes(leads.data.notes);
  }

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

    toastr.success("Lead updated successfully");

    originalLeadData = getLeadFormData();

    $(".lead-input").prop("readonly", true);

    $("#btnSaveLead").addClass("hidden");
    $("#btnEditLead").removeClass("hidden");
  });

  document.querySelectorAll(".statusBtn").forEach((statusButton) => {
    statusButton.addEventListener("click", function () {
      const status = statusButton.dataset.status;
      let filtered = leadsArray.filter((row) => row.status.status === status);
      if (status === "ALL") {
        filtered = leadsArray;
      }

      //call rendertable here

      renderTable(filtered);
    });
  });

  function renderActivity(activities) {
    //build activity html
    let html = "";

    //select the container
    const activityContainer = document.getElementById("activityContainer");
    //foreach logic for activities
    activities.forEach((activity) => {
      html += `
            <div class="w-full p-2 border border-zinc-300 rounded-md flex  justify-between  items-center">
                            <div class="flex flex-col gap-2">

                                <div class="flex flex-col">
                                <p class="text-lg font-semibold">${activity.type}</p>
                                <p class="text-md">${activity.description}</p>
                                    </div>
                                <p class="text-xs font-light">${formatDateTime(activity.created_at)} ${activity.user.name}</p>
                            </div>
                        </div>`;
    });

    if (activities.length === 0) {
      html = `

                        <div class="w-full p-2 rounded-md text-center">
                            <p class="font-semibold text-zinc-400">Theres no notes</p>
                            </div>
                            `;
    }
    //append html to the container
    activityContainer.innerHTML = html;
  }

  function renderNotes(notes) {
    let html = "";
    //select the container
    const noteContainer = document.getElementById("noteContainer");

    //foreach logic for activities
    notes.forEach((note) => {
      //build activity html
      html += `
                        <div class="w-full p-2 border border-zinc-300 rounded-md">
                            <div class="flex justify-between">

                                <p class="text-xs font-light">${formatDateTime(note.created_at)} ${note.user.name}</p>
                                <button><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="5" cy="12" r="2"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <circle cx="19" cy="12" r="2"></circle>
                                    </svg></button>

                            </div>

                            <p class="text-md">${note.note}</p>
                        </div>
            `;
    });
    if (notes.length === 0) {
      html = `

                        <div class="w-full p-2 border border-zinc-300 rounded-md text-center">
                            <p class="font-semibold text-zinc-400">Theres no notes</p>
                            </div>
                            `;
    }

    //append html to the container
    noteContainer.innerHTML = html;
  }

  //adding of notes and activity function
  const addActivityBtn = document.getElementById("addActivityBtn");
  const activityDropdown = document.getElementById("activityDropdown");
  const activityDescriptionInput = document.getElementById(
    "activityDescriptionInput",
  );
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

  // OPEN / CLOSE HELPERS
  function openDropdown(dropdown) {
    dropdown.classList.remove("hidden");
  }

  function closeDropdown(dropdown) {
    dropdown.classList.add("hidden");
  }

  // =======================
  // ACTIVITY EVENTS
  // =======================

  addActivityBtn.addEventListener("click", () => {
    openDropdown(activityDropdown);
  });

  saveActivityBtn.addEventListener("click", async function () {
    const payload = {
      leadUUId: leadUUID,
      status: activityStatusInput.value,
      type: activityTypeInput.value,
      activity: activityDescriptionInput.value,
    };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: payload,
      url: "/api/crm/activity",
      button: saveActivityBtn,
    });
    console.log(response);
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Saving Activity",
      });
      return;
    }

    showMessage({
      status: "success",
      title: "Activity Saved!",
    });
    activityStatusInput.value = "";
    activityTypeInput.value = "";
    activityDescriptionInput.value = "";
    closeDropdown(activityDropdown);
    loadLeadInfo();
    updateLeadDetails();
  });

  cancelActivityBtn.addEventListener("click", () => {
    activityStatusInput.value = "";
    activityTypeInput.value = "";
    activityDescriptionInput.value = "";
    closeDropdown(activityDropdown);
  });

  // =======================
  // NOTE EVENTS
  // =======================

  addNoteBtn.addEventListener("click", () => {
    openDropdown(noteDropdown);
  });

  saveNoteBtn.addEventListener("click", async function () {
    const payload = {
      leadUUId: leadUUID,
      note: noteInput.value,
    };

    const response = await apiCall({
      mode: "POST",
      isJson: true,
      payload: payload,
      url: "/api/crm/note",
      button: saveNoteBtn,
    });
    console.log(response);
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Saving Note",
      });
      return;
    }

    showMessage({
      status: "success",
      title: "Note saved!",
    });

    noteInput.value = "";
    closeDropdown(noteDropdown);
    loadLeadInfo();
  });

  cancelNoteBtn.addEventListener("click", () => {
    noteInput.value = "";
    closeDropdown(noteDropdown);
  });

  editContactBtn.addEventListener("click", () => {
    openDropdown(editContactInfoDropdown);
  });
  saveContactInfoBtn.addEventListener("click", async function () {
    const payload = {
      leadUUId: leadUUID,
      contact_name: $("#contactName").val(),
      contact_mobile: $("#contactMobile").val(),
      contact_email: $("#contactEmail").val(),
    };

    const response = await apiCall({
      mode: "PUT",
      isJson: true,
      payload: payload,
      url: `/api/crm/leads/${leadUUID}`,
      button: saveContactInfoBtn,
    });
    if (!response.success) {
      showMessage({
        status: "error",
        title: "Error Saving Note",
      });
      return;
    }

    showMessage({
      status: "success",
      title: "Contact Updated!",
    });

    closeDropdown(editContactInfoDropdown);
    loadLeadInfo();
  });
  cancelContactInfoBtn.addEventListener("click", () => {
    $("#saveContactInfoBtn").removeClass("hidden");
    closeDropdown(editContactInfoDropdown);
  });

  // =======================
  // CLICK OUTSIDE CLOSE
  // (still scoped, no document query loops)
  // =======================

  window.addEventListener("click", (e) => {
    const isActivityClick =
      addActivityBtn.contains(e.target) || activityDropdown.contains(e.target);

    const isNoteClick =
      addNoteBtn.contains(e.target) || noteDropdown.contains(e.target);

    const iseditContactClick =
      editContactBtn.contains(e.target) ||
      editContactInfoDropdown.contains(e.target);

    if (!isActivityClick) closeDropdown(activityDropdown);
    if (!isNoteClick) closeDropdown(noteDropdown);
    if (!iseditContactClick) closeDropdown(editContactInfoDropdown);
  });

  $(".editContactDropdown").on("change", function () {
    $("#saveContactInfoBtn").removeClass("hidden");
  });
};
