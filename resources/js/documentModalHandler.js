let approvalmode = null;
window.checkActionButtons = function checkActionButtons(
  status = false,
  documentRecepientId = false,
  documentDestinationOffice = false,
  receiptConfirmation = false,
  revision_status = false,
  source = false,
) {
  // Force calendar open when clicking anywhere on input
  document.querySelectorAll('input[type="date"]').forEach((inpt) => {
    inpt.addEventListener("click", function () {
      this.showPicker();
    });
    inpt.addEventListener("keydown", (e) => e.preventDefault());
  });
  //   console.log(window.authUser.id + "->" + documentRecepientId);
  const actionButtonArray = [
    {
      name: "approvalActions",
      el: document.getElementById("approvalButtons"),
    },
    {
      name: "confirmationActions",
      el: document.getElementById("btnConfirm"),
    },
    {
      name: "routeActions",
      el: document.getElementById("routeDocumentBtn"),
    },
    {
      name: "revisionActions",
      el: document.getElementById("revisionButtons"),
    },
    {
      name: "eSignAction",
      el: document.getElementById("eSignAction"),
    },
  ];

  actionButtonArray.forEach((item) => {
    if (!item.el.classList.contains("hidden")) {
      item.el.classList.add("hidden");
    }
  });
  if (source === "all") {
    return;
  }
  if (status) {
    status = status.toLowerCase();
  }
  const userOfficeCode = window.authUser.office.office_code;
  const userAuth = window.authUser;
  const canAct = userOfficeCode === documentDestinationOffice;
  //   console.log(userAuth);
  if (!canAct) return;
  const showAction = (name) => {
    const action = actionButtonArray.find((item) => item.name === name);
    if (action?.el) action.el.classList.remove("hidden");
  };

  // 1. Remanded with revision
  if (status === "remanded" && revision_status === 0 && canAct) {
    if (canAct) {
      showAction("routeActions");
    }
    showAction("revisionActions");
    return;
  }
  // 1. Remanded with revision
  if (status === "approved" && userAuth.authorize_signatory === 1) {
    showAction("eSignAction");
    return;
  }

  // 2. For approval (MUST come before receiptConfirmation)
  if (
    status === "for approval" &&
    Number(window.authUser.id) === Number(documentRecepientId)
  ) {
    showAction("approvalActions");
    return;
  }

  // 3. Waiting for receipt confirmation
  if (receiptConfirmation === 0) {
    showAction("confirmationActions");
    return;
  }

  // 4. Default routing case
  if (
    receiptConfirmation !== 0 &&
    status !== "remanded" &&
    userAuth.user_config.approval_type !== "final-approval"
  ) {
    showAction("routeActions");
  }
  if (
    status === "pending" &&
    userAuth.user_config.approval_type === "routing"
  ) {
    showAction("routeActions");
  }
};

window.clearModalFields = function clearModalFields() {
  const spanIds = [
    "docControlNumber",
    "docStatus",
    "docTitle",
    "docDept",
    "docDest",
    "docUploadBy",
    "docAuthor",
    "docDate",
    "docCode",
    "document_id",
    "docForm",
    "docType",
    "docDueDate",
    "docDestination",
    "docConfidentiality",
    "docRemarks",
  ];

  spanIds.forEach((id) => {
    const el = document.getElementById(id);
    if (el) el.textContent = "";
  });
};

window.showSkeletonLoaders = function showSkeletonLoaders() {
  const skeleton = (lines = 3) =>
    Array.from({ length: lines })
      .map(() => `<div class="h-4 bg-gray-200 rounded shimmer mb-2"></div>`)
      .join("");

  const fileList = document.getElementById("fileVersionsList");
  if (fileList) {
    fileList.innerHTML = `<div class="p-3">${skeleton(4)}</div>`;
  }

  const log = document.getElementById("activityLog");
  if (log) {
    log.innerHTML = `<div class="p-3">${skeleton(5)}</div>`;
  }
};
function formatDateTime(value) {
  const date = new Date(value.replace(" ", "T")); // handles SQL datetime too

  return date.toLocaleString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  });
}
window.populateDocumentModal = async function populateDocumentModal(
  documentId,
) {
  try {
    const data = await fetchWithRetry(`/api/documents/${documentId}`, {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    });
    if (!data || Object.keys(data).length === 0) {
      hideModal("DocumentModal");
      showMessage({
        status: "error",
        message: "Document not found or does not exist.",
      });
      return;
    }
    // console.log(data);
    document.getElementById("docId").value = data.document_id;
    setText("docControlNumber", data.document_control_number || "N/A");
    document.getElementById("eSignBtn").dataset.docControlNumber =
      data.document_control_number;
    setText("revisedocControlNumber", data.document_control_number || "N/A");
    setText("docStatus", data.status || "N/A");

    const confirmationButton = document.getElementById("btnConfirm");
    confirmationButton.dataset.documentId = data.document_id || "";

    setText("document_id", data.document_id || "N/A");
    setText("docCode", data.document_code || "N/A");
    setText("docTitle", data.particular || "N/A");
    setText("docDept", data.office_origin || "N/A");
    setText("docDest", data.destination_office || "N/A");
    setText("docUploadBy", data.user_id || "N/A");
    setText("docSignatory", data.signatory || "N/A");
    setText("docType", data.document_type || "N/A");
    setText("docDate", data.date_of_document || "N/A");
    setText("docDueDate", data.due_date || "N/A");
    setText("docStatus", data.status || "N/A");
    setText("docRemarks", data.remarks || "-");
    setText("docConfidentiality", data.confidentiality || "None");

    setText("created_at", formatDateTime(data.created_at) || "N/A");

    populateFileList(data.files || []);

    populateActivityLog(data || []);

    // Approval type toggle
    // console.log(data);
    if (data.approvals) {
      document
        .getElementById("finalApproval")
        .classList.toggle(
          "hidden",
          data.approvals.approval_type !== "final-approval",
        );
      document
        .getElementById("preApproval")
        .classList.toggle(
          "hidden",
          data.approvals.approval_type === "final-approval",
        );
    }
  } catch (error) {
    console.error("Failed to populate document modal:", error);
  }
};

function setText(id, text) {
  const el = document.getElementById(id);
  if (el) el.textContent = text || "";
}

function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.add("hidden");
}

function populateFileList(files) {
  const fileList = document.getElementById("fileVersionsList");
  fileList.innerHTML = "";

  if (!files.length) {
    fileList.innerHTML = `
      <li class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
        No files available
      </li>`;
    return;
  }
  files.forEach((file, index) => {
    const li = document.createElement("li");
    li.classList.add(
      "flex",
      "items-center",
      "justify-between",
      "px-4",
      "py-3",
      "hover:bg-gray-50",
      "dark:hover:bg-gray-800",
      "cursor-pointer",
      "fileInfoButton",
      "modal-open",
    );

    li.dataset.version = `v${index + 1}.0`;
    li.dataset.fileId = file.file_id;

    li.innerHTML = `
      <div>
        <p class="text-gray-900 dark:text-gray-100 font-medium">
          ${shortenFileName(file.file_name)}.0
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400">
          Uploaded: ${file.uploaded_at.split(" ")[0]} by ${
            file.uploading_office
          }
        </p>
      </div>
      <a href="${file.file_path}" data-file-id="${file.file_id}"
         download
         class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors duration-200"
         onclick="event.stopPropagation();">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0-8l-4 4m4-4l4 4"/>
        </svg>
      </a>
    `;

    li.addEventListener("click", () => openPdfModal(file.file_path));
    const downloadlatestbutton = document.getElementById("downloadLatestBtn");
    downloadlatestbutton.href = file.file_path;
    downloadlatestbutton.dataset.fileId = file.file_id;
    fileList.appendChild(li);
  });
}
function shortenFileName(name, maxLength = 25) {
  if (name.length <= maxLength) return name;

  const extIndex = name.lastIndexOf(".");
  if (extIndex === -1) return name.substring(0, maxLength) + "...";

  const ext = name.substring(extIndex);
  const base = name.substring(0, maxLength - ext.length - 3);

  return base + "..." + ext;
}

async function openPdfModal(filePath) {
  initModal({ modalId: "pdfPreviewModal" });

  const baseUrl = window.location.origin;
  const pdfUrl = `${baseUrl}/${filePath}`;

  const slides = await extractPdfImages(pdfUrl);
  loadSlidesFromArray(slides);
}

function populateActivityLog(data) {
  const activityLog = document.getElementById("activityLog");
  const fullActivityLog = document.getElementById("fullActivityLog");

  activityLog.innerHTML = "";
  fullActivityLog.innerHTML = "";

  if (!data.activities.length) {
    activityLog.innerHTML = `
            <div class="text-sm text-gray-500 dark:text-gray-400">
                No activity yet.
            </div>
        `;

    fullActivityLog.innerHTML = `
            <div class="text-sm text-gray-500 dark:text-gray-400">
                No logs available.
            </div>
        `;
    return;
  }
  const activities = [...data.activities].reverse();
  console.log(activities);
  activities.forEach((act) => {
    const importantDiv = document.createElement("li");
    const fullDiv = document.createElement("li");

    importantDiv.classList.add(
      "text-sm",
      "text-gray-700",
      "dark:text-gray-300",
      "border",
      "border-gray-300",
      "rounded-md",
      "p-2",
    );
    fullDiv.classList.add("text-sm", "text-gray-600", "dark:text-gray-300");

    const date = new Date(act.created_at);

    const time = date.toLocaleString("en-US", {
      month: "long", // full month name
      day: "2-digit", // 01, 02, ...
      year: "numeric", // 2020
      hour: "numeric", // 1–12
      minute: "2-digit", // 00–59
      hour12: true, // AM/PM
    });

    // Example output: "January 01, 2020, 9:00 AM"

    // Remove the comma before AM/PM if you want exactly "January 01, 2020 9AM"
    const timeAgo = time.replace(/, /, " ").replace(":00", "");
    const remarks = act.final_remarks;

    const mainActions = [
      "route",
      "upload",
      "approved",
      "signed",
      "confirm",
      "for approval",
    ];
    const importantActions = [
      "approved",
      "reject",
      "signed",
      "confirm",
      "for approval",
      "route",
    ];

    // --------------------------
    // FULL LOG: all activities
    // --------------------------
    let fullText = "";
    const fullUserName = act.user?.name || `User ${act.user_id || "Unknown"}`;
    let fullActionText = "";
    console.log(act);
    switch (act.action) {
      case "route": {
        const senderName = act.user?.name ?? "Unknown User";

        const routeTargetFull = act.routed_user
          ? `${act.routed_user.name}`
          : (act.office_id ?? "Unknown Office");

        fullActionText =
          `${senderName} routed the document to ` +
          `<span class="font-semibold">${routeTargetFull}</span>`;
        break;
      }
      case "upload": {
        const senderName = act.user?.name ?? "Unknown User";

        const uploadTargetFull = act.routed_user
          ? `${act.routed_user.name}`
          : (act.office_id ?? null);

        fullActionText =
          `${senderName} uploaded a document` +
          (uploadTargetFull
            ? ` for <span class="font-semibold">${uploadTargetFull}</span>`
            : "");
        break;
      }

      case "confirm": {
        const senderName = act.user?.name ?? "Unknown User";

        fullActionText =
          `<span class="font-semibold">${senderName}</span> confirmed receipt of document number: ` +
          `<span class="font-semibold">${act.document_control_number}</span>`;
        break;
      }

      case "signed": {
        const senderName = act.user?.name ?? "Unknown User";

        fullActionText = `${senderName} signed the document`;
        break;
      }
      default:
        fullActionText = `<span class="font-semibold">${fullUserName}</span> ${act.action} the document`;
        break;
    }

    fullText = `
      <p>
        ${fullActionText}
        <span class="text-gray-500 text-xs">${timeAgo}</span>
        ${
          remarks
            ? `<p><span class="font-semibold">Remarks: </span>${remarks}</p>`
            : ""
        }
      </p>
  `;
    fullDiv.innerHTML = fullText;
    fullActivityLog.appendChild(fullDiv);

    // --------------------------
    // IMPORTANT LOG: only main/important actions
    // --------------------------
    if (
      mainActions.includes(act.action) ||
      importantActions.includes(act.action)
    ) {
      let importantText = fullText; // reuse fullText as base
      importantDiv.innerHTML = importantText;
      activityLog.appendChild(importantDiv);
    }
  });

  const activityLogcont = document.getElementById("activityLog");
  activityLogcont.scrollTop = 0;
}

//-----------------------
//approval methods
//-----------------------

window.sendApprovalAction = async function sendApprovalAction({
  approvalId,
  action,
  next_action,
  remarks = "",
  nextUserId = null,
}) {
  const response = await fetchWithRetry(`/api/approvals/${approvalId}/action`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({
      action,
      next_action,
      remarks,
      next_user_id: nextUserId,
    }),
  });

  if (response) {
    // Close modals
    document.getElementById("DocumentModal")?.classList.add("hidden");
    document.getElementById("approvalModal")?.classList.add("hidden");
    document.getElementById("disapprovalModal")?.classList.add("hidden");
    document.getElementById("forDiscussionModal")?.classList.add("hidden");
    showMessage({
      status: "success",
      message: "Document successfully approved!",
    });
    loadlastpage();
  }
};

window.populateUsers = async function populateUsers(approvalType) {
  const data = await fetchAuthUser();
  //   console.log(data);
  const currentOffice = data.user.office.office_code || null;

  const userSelect = document.getElementById("userSelect");

  const users = await fetchWithRetry("/api/users", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
  });

  //   if (!users || !currentOffice) return;

  // Clear existing options
  userSelect.innerHTML = `<option value="">Select User</option>`;

  users.forEach((u) => {
    // console.log("User office:", u.office?.office_code);
    // console.log("Current office:", currentOffice);
    // console.log("User approval:", u.user_config?.approval_type);
    // console.log("Passed approvalType:", approvalType);

    const officeMatch =
      u.office?.office_code?.trim().toLowerCase() ===
      currentOffice?.trim().toLowerCase();

    const approvalMatch = u.user_config?.approval_type !== approvalType;

    if (officeMatch && approvalMatch) {
      const option = document.createElement("option");
      option.value = u.id;
      option.textContent = u.name;
      option.dataset.approvalType = u.user_config.approval_type;

      userSelect.appendChild(option);
    }
  });
};

//modal controls
const userSelect = document.getElementById("userSelect");

//approvals
const modalApproveBtn = document.getElementById("modalApproveBtn");
const confirmBtn = document.getElementById("confirmApprovalBtn");

modalApproveBtn.addEventListener("click", function () {
  populateUsers("routing");
  initModal({
    modalId: "approvalModal",
  });
});

confirmBtn.addEventListener("click", async function () {
  try {
    confirmBtn.disabled = true;
    confirmBtn.textContent = "Confirming...";
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    const finalapproval = document
      .getElementById("preApproval")
      .classList.contains("hidden");
    // console.log(finalapproval);
    let approvaltype = "final-approval";
    if (
      (!finalapproval && !selectedOption) ||
      (!finalapproval && !selectedOption.value)
    ) {
      console.warn("No user selected");
      return;
    } else {
      approvaltype = selectedOption.dataset.approvalType;
    }

    // console.log(approvaltype);
    // return;
    sendApprovalAction({
      approvalId: document_id.textContent,
      action: "approved",
      next_action: approvaltype || null, // note the correct camelCase
      remarks: remarksTextarea.value,
      nextUserId: userSelect.value,
    });

    remarksTextarea.value = "";
    userSelect.selectedIndex = 0;
  } catch (error) {
    console.error("Confirmation failed:", error);
  } finally {
    confirmBtn.disabled = false;
    confirmBtn.textContent = "Confirm";
  }
});

//disapprovals
const modalDisapproveBtn = document.getElementById("modalDisapproveBtn");
const confirmDisapprovalBtn = document.getElementById("confirmDisapprovalBtn");

modalDisapproveBtn.addEventListener("click", () =>
  initModal({
    modalId: "disapprovalModal",
  }),
);

confirmDisapprovalBtn.addEventListener("click", async function () {
  try {
    confirmDisapprovalBtn.disabled = true;
    confirmDisapprovalBtn.textContent = "Confirming...";
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    sendApprovalAction({
      approvalId: document_id.textContent,
      action: "disapproved",
      next_action: selectedOption.dataset.approvaltype,
      remarks: remarksTextarea.value,
      nextUserId: userSelect.value,
    });

    remarksTextarea.value = "";
    userSelect.selectedIndex = 0;
  } catch (error) {
    console.error("Disapproval failed:", error);
  } finally {
    confirmDisapprovalBtn.disabled = false;
    confirmDisapprovalBtn.textContent = "Confirm";
  }
});

//for discussions
const modalForDiscussionBtn = document.getElementById("modalForDiscussionBtn");
const confirmForDiscussionBtn = document.getElementById(
  "confirmForDiscussionBtn",
);

modalForDiscussionBtn.addEventListener("click", () =>
  initModal({
    modalId: "forDiscussionModal",
  }),
);

confirmForDiscussionBtn.addEventListener("click", async function () {
  try {
    confirmForDiscussionBtn.disabled = true;
    confirmForDiscussionBtn.textContent = "Confirming...";
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    sendApprovalAction({
      approvalId: document_id.textContent,
      action: "for-discussion",
      next_action: selectedOption.dataset.approvaltype,
      remarks: remarksTextarea.value,
      nextUserId: userSelect.value,
    });
  } catch (error) {
    console.error("Discussion request failed:", error);
  } finally {
    confirmForDiscussionBtn.disabled = false;
    confirmForDiscussionBtn.textContent = "Submit";
  }
});

//revisions
const submitrevisionBtn = document.getElementById("submitrevisionBtn");
const modalrevisionBtn = document.getElementById("modalrevisionBtn");

modalrevisionBtn.addEventListener("click", () => {
  initPDFDropzone({
    dropzoneId: "revisedropzone",
    fileInputId: "revisefileInput",
    fileInfoId: "revisefileInfo",
    clearBtnId: "reviseclearSelectionBtn",
  });

  initModal({
    modalId: "reviseModal",
  });
});

submitrevisionBtn.addEventListener("click", async function () {
  try {
    this.disabled = true;
    this.textContent = "Submitting...";
    const reviseformData = new FormData();
    const revisefileInput = document.getElementById("revisefileInput");

    reviseformData.append(
      "revisedocControlNumber",
      document.getElementById("revisedocControlNumber").textContent.trim(),
    );
    reviseformData.append("user_id", window.authUser.id);
    reviseformData.append("document_form", "PDF");

    if (revisefileInput.files.length > 0) {
      reviseformData.append("file", revisefileInput.files[0]);
    }

    try {
      const result = await fetchWithRetry("/api/documents/revise", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        body: reviseformData,
      });

      // console.log(result);
      if (result) {
        // Close modals
        document.getElementById("DocumentModal")?.classList.add("hidden");
        document.getElementById("approvalModal")?.classList.add("hidden");
        document.getElementById("disapprovalModal")?.classList.add("hidden");
        document.getElementById("forDiscussionModal")?.classList.add("hidden");
        document.getElementById("reviseModal")?.classList.add("hidden");
        document.getElementById("esignModal")?.classList.add("hidden");
        showMessage({
          status: "success",
          message: "Revised Document has been uploaded",
        });
        loadlastpage();
      }
    } catch (error) {
      console.error("Revision failed:", error);
    } finally {
      this.disabled = false;
      this.textContent = "Submit";
    }
  } catch (error) {
    console.error(error);
  } finally {
    this.disabled = false;
    this.textContent = "Submit";
  }
});

//routing
const routeDocumentBtn = document.getElementById("routeDocumentBtn");
const routeSubmitBtnBtn = document.getElementById("routeSubmitBtn");

routeDocumentBtn.addEventListener("click", () => {
  const officeSelect = document.getElementById("routeOfficeSelect");
  const userSelect = document.getElementById("routeUserSelect");
  const approvalSelect = document.getElementById("routeApprovalSelect");
  const internalSection = document.getElementById("internalSection");
  const currentOffice = window.authUser.office?.office_code || null;
  //   console.log(currentOffice);

  officeSelect?.addEventListener("change", async (e) => {
    const selected = e.target.value;
    internalSection?.classList.toggle("hidden", selected !== currentOffice);
    const status = document
      .getElementById("docStatus")
      .textContent.toLowerCase();
    // console.log(status);

    const users = await fetchWithRetry("/api/users", {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    });
    if (selected === currentOffice) {
      let filtered = [];
      if (status !== "disapproved") {
        filtered = users.filter(
          (u) =>
            u.office?.office_code === currentOffice &&
            u.user_config.approval_type !== "routing",
        );
        if (approvalSelect.classList.contains("hidden")) {
          approvalSelect.classList.remove("hidden");
        }
      } else {
        filtered = users.filter(
          (u) =>
            u.office?.office_code === currentOffice &&
            u.user_config.approval_type !== "final-approval",
        );
        approvalSelect.classList.add("hidden");
      }
      //   if()
      userSelect.innerHTML =
        `<option value="">Select User</option>` +
        filtered
          .map((u) => `<option value="${u.id}">${u.name}</option>`)
          .join("");
      approvalSelect.innerHTML = `<option value="">Select Approval Type</option>
                 <option value="pre-approval">Pre-approval</option>
                 <option value="final-approval">Final-approval</option>`;
    }
  });

  initModal({ modalId: "routingModal" });
});
routeSubmitBtnBtn.addEventListener("click", async function () {
  clearModalErrors();
  routeSubmitBtnBtn.disabled = true;
  routeSubmitBtnBtn.textContent = "Submitting...";

  const modal = document.getElementById("routingModal");

  if (!modal) return;
  try {
    const documentId = document.getElementById("docId").value;
    const destinationOffice =
      document.getElementById("routeOfficeSelect").value;
    const recipientUserId = document.getElementById("routeUserSelect").value;
    const approvalType = document.getElementById("routeApprovalSelect").value;
    const remarks = document.getElementById("routeRemarks").value;
    const status = document
      .getElementById("docStatus")
      .textContent.toLowerCase();
    const internalSection = document.getElementById("internalSection");
    // console.log(status);
    // ---- Validation ----
    const errors = [];

    if (!documentId) errors.push("Document ID is missing.");
    if (!destinationOffice) errors.push("Destination office is required.");

    // Validate user selection if internal section is visible
    if (!internalSection.classList.contains("hidden") && !recipientUserId) {
      errors.push("Recipient user is required for internal routing.");
    }

    // Validate approval type if internal section is visible
    if (status !== "disapproved") {
      if (!internalSection.classList.contains("hidden") && !approvalType)
        errors.push("Approval type is required for internal routing.");
    }

    // ---- Build FormData ----
    const formData = new FormData();
    formData.append("document_id", documentId);
    formData.append("destination_office", destinationOffice);
    if (!internalSection.classList.contains("hidden")) {
      formData.append("recipient_user_id", recipientUserId);
      if (status !== "disapproved") {
        formData.append("approval_type", approvalType);
      }
      formData.append("status", status);
    }
    formData.append("remarks", remarks);

    console.log("FormData to submit:", Array.from(formData.entries()));
    // return;
    // ---- Submit to API ----
    const res = await fetchWithRetry("/api/documents/route", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: formData,
    });
    if (res) {
      if (typeof window.getDocs === "function") {
        getDocs();
      } else {
        console.warn("getDocs() not available yet.");
      }
      if (typeof window.updatetable === "function") {
        updatetable();
      } else {
        console.warn("updatetable() not available yet.");
      }
      document.getElementById("routingModal").classList.add("hidden");
      document.getElementById("DocumentModal").classList.add("hidden");
      showMessage({ status: "success", message: "Routing Success" });

      // Reset form
      document.getElementById("routeOfficeSelect").selectedIndex = 0;
      document.getElementById("routeUserSelect").selectedIndex = 0;
      document.getElementById("routeApprovalSelect").selectedIndex = 0;
      document.getElementById("routeRemarks").value = "";
    }
  } catch (error) {
    console.error("Routing request failed:", error);
  } finally {
    routeSubmitBtnBtn.disabled = false;
    routeSubmitBtnBtn.textContent = "Submit";
  }

  // Stop submission if errors exist
  if (errors.length > 0) {
    showModalErrors(
      errors,
      "routingmodalErrorMessage",
      "routingmodalErrorList",
    );
    modal.scrollTop = 0;
    return;
  }
});

//esigning
const submitesignBtn = document.getElementById("submitesignBtn");
const modalesignBtn = document.getElementById("eSignBtn");
const esignmodal = document.getElementById("esignModal");
const remarks = document.getElementById("esignRemarks").value;
let ControlNumber = "";
modalesignBtn.addEventListener("click", () => {
  ControlNumber = modalesignBtn.dataset.docControlNumber;
  //   console.log(ControlNumber);
  initPDFDropzone({
    dropzoneId: "esigndropzone",
    fileInputId: "esignfileInput",
    fileInfoId: "esignfileInfo",
    clearBtnId: "clearesignSelectionBtn",
  });

  initModal({
    modalId: "esignModal",
  });
});

submitesignBtn.addEventListener("click", async function () {
  clearModalErrors();
  const esignformData = new FormData();
  const esignfileInput = document.getElementById("esignfileInput");

  let errors = [];
  //   console.log(ControlNumber);

  esignformData.append("docControlNumber", ControlNumber);
  esignformData.append("user_id", window.authUser.id);
  esignformData.append("document_form", "PDF");

  if (esignfileInput.files.length > 0) {
    esignformData.append("file", esignfileInput.files[0]);
  }
  esignformData.append("remarks", remarks);

  this.disabled = true;
  this.textContent = "Submitting...";

  //   console.table([...esignformData.entries()]);
  //   return;
  try {
    // Validate PDF if status is approved and upload section is visible
    if (esignfileInput.files.length === 0) {
      errors.push("PDF file is required.");
    }

    // Stop submission if errors exist
    if (errors.length > 0) {
      showModalErrors(errors, "esignmodalErrorMessage", "esignmodalErrorList");
      esignmodal.scrollTop = 0;
      return;
    }
    // return;
    const result = await fetchWithRetry("/api/documents/esign", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: esignformData,
    });

    if (result) {
      // Close modals
      document.getElementById("DocumentModal")?.classList.add("hidden");
      document.getElementById("approvalModal")?.classList.add("hidden");
      document.getElementById("disapprovalModal")?.classList.add("hidden");
      document.getElementById("forDiscussionModal")?.classList.add("hidden");
      document.getElementById("reviseModal")?.classList.add("hidden");
      document.getElementById("esignModal")?.classList.add("hidden");
      showMessage({
        status: "success",
        message: "eSigned Document has been uploaded",
      });
      loadlastpage();
    }
  } catch (error) {
    console.error("Revision failed:", error);
  } finally {
    this.disabled = false;
    this.textContent = "Submit";
  }
});

const remarksTextarea = document.getElementById("remarksTextarea");
const document_id = document.getElementById("document_id");

//error handling functions
function clearModalErrors() {
  const errorBox = document.querySelectorAll(".errorbox");
  const errorList = document.querySelectorAll(".errorlist");
  //   console.log(errorBox);
  errorBox.forEach((errbox) => {
    if (errbox.classList.contains("hidden")) return;
    errbox.classList.add("hidden");
  });
  errorList.forEach((errorList) => {
    errorList.innerHTML = "";
  });
}

function showModalErrors(errors, modal, errorlist) {
  const errorBox = document.getElementById(modal);
  const errorList = document.getElementById(errorlist);
  errorList.innerHTML = "";
  errors.forEach((err) => {
    const li = document.createElement("li");
    li.textContent = err;
    errorList.appendChild(li);
  });
  errorBox.classList.remove("hidden");
}

//BUG ID: 4
const confirmButton = document.getElementById("btnConfirm");
confirmButton.addEventListener("click", async (e) => {
  try {
    confirmButton.disabled = true;
    confirmButton.textContent = "Confirming...";
    const post = {
      document_id: confirmButton.dataset.documentId,
      user_id: window.authUser.id,
    };
    const result = await fetchWithRetry("/api/documents/confirm", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(post),
    });
    if (result) {
      document.getElementById("routeDocumentBtn").classList.remove("hidden");
      confirmButton.classList.add("hidden");
      ["getDocs", "updatetable"].forEach((fn) => {
        if (typeof window[fn] === "function") {
          window[fn]();
        } else {
          console.warn(`Function "${fn}" is not available on this page.`);
        }
      });
    }
  } catch (error) {
    console.error(error);
  } finally {
    confirmButton.disabled = false;
    confirmButton.textContent = "Confirm";
  }
});

document.addEventListener("click", function (e) {
  const btn = document.getElementById("toggleFullLogBtn");
  const dropdown = document.getElementById("fullActivityLogContainer");

  if (!btn || !dropdown) return;

  const isClickInside = btn.contains(e.target) || dropdown.contains(e.target);

  if (!isClickInside) {
    dropdown.classList.add("hidden");
  }
});
window.addEventListener("scroll", () => {
  const dropdown = document.getElementById("fullActivityLogContainer");
  dropdown?.classList.add("hidden");
});
document
  .getElementById("toggleFullLogBtn")
  .addEventListener("click", function (e) {
    e.stopPropagation(); // prevent immediate close
    document
      .getElementById("fullActivityLogContainer")
      .classList.toggle("hidden");
  });
