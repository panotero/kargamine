window.checkActionButtons = function checkActionButtons(
  status = false,
  documentRecepientId = false,
  documentDestinationOffice = false,
  receiptConfirmation = false,
  source = false
) {
  //   console.log(documentData);
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

  //   console.log(documentRecepientId);
  //   console.log(documentDestinationOffice);
  //   console.log(window.authUser.office.office_name);
  const canAct =
    documentRecepientId !== false ||
    documentDestinationOffice === window.authUser.office.office_name;

  if (!canAct) return;

  const showAction = (name) => {
    const action = actionButtonArray.find((item) => item.name === name);
    if (action?.el) action.el.classList.remove("hidden");
  };

  if (status === "remanded") {
    showAction("revisionActions");
    return;
  } else if (receiptConfirmation === 0) {
    showAction("confirmationActions");
    return;
  } else if (status === "for approval") {
    showAction("approvalActions");
    return;
  }

  showAction("routeActions");
};

window.clearModalFields = function clearModalFields() {
  const spanIds = [
    "docControlNumber",
    "docStatus",
    "docTitle",
    "docDept",
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

window.populateDocumentModal = async function populateDocumentModal(
  documentId
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
    setText("revisedocControlNumber", data.document_control_number || "N/A");
    setText("docStatus", data.status || "N/A");

    const confirmationButton = document.getElementById("btnConfirm");
    confirmationButton.dataset.documentId = data.document_id || "";

    setText("document_id", data.document_id || "N/A");
    setText("docCode", data.document_code || "N/A");
    setText("docTitle", data.particular || "N/A");
    setText("docDept", data.office_origin || "N/A");
    setText("docSignatory", data.signatory || "N/A");
    setText("docType", data.document_type || "N/A");
    setText("docDate", data.date_received || "N/A");
    setText("docDueDate", data.due_date || "N/A");
    setText("docStatus", data.status || "N/A");
    setText("docRemarks", data.remarks || "-");
    setText("docConfidentiality", data.confidentiality || "None");
    setText("created_at", data.created_at || "N/A");
    setText("date_received", data.date_received || "N/A");

    populateFileList(data.files || []);

    populateActivityLog(data || []);

    // Approval type toggle
    // console.log(data);
    if (data.approvals) {
      document
        .getElementById("finalApproval")
        .classList.toggle(
          "hidden",
          data.approvals.approval_type !== "final-approval"
        );
      document
        .getElementById("preApproval")
        .classList.toggle(
          "hidden",
          data.approvals.approval_type === "final-approval"
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
      "modal-open"
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
      "p-2"
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

    const mainActions = ["route", "upload", "approved", "signed", "confirm"];
    const importantActions = [
      "approve",
      "reject",
      "receive",
      "returned",
      "review",
    ];

    // --------------------------
    // FULL LOG: all activities
    // --------------------------
    let fullText = "";
    const fullUserName = act.user?.name || `User ${act.user_id || "Unknown"}`;
    let fullActionText = "";

    switch (act.action) {
      case "route":
        const routeTargetFull =
          act.to_external === 1
            ? data.destination_office || "Unknown Office"
            : act.routed_to
            ? `User ${act.routed_to}`
            : "Unknown Recipient";
        fullActionText = `routed the document to <span class="font-semibold">${routeTargetFull}</span>`;
        break;
      case "upload":
        const uploadTargetFull =
          act.to_external === 1
            ? data.destination_office || "Unknown Office"
            : act.routed_to
            ? `User ${act.routed_to}`
            : "";
        fullActionText = `<span class="font-semibold">${fullUserName}</span> uploaded a document${
          uploadTargetFull
            ? ` for <span class="font-semibold">${
                act.from_user?.name || "Unknown"
              }</span>`
            : ""
        }`;
        break;
      case "approved":
        const approvedTargetFull =
          act.to_external === 1
            ? data.destination_office || "Unknown Office"
            : act.routed_to
            ? `User ${act.routed_to}`
            : "";
        fullActionText = `approved the document${
          approvedTargetFull
            ? ` for <span class="font-semibold">${approvedTargetFull}</span>`
            : ""
        }`;
        break;
      case "confirm":
        fullActionText = `<span class="font-semibold">${fullUserName}</span> confirmed receipt of document number: <span class="font-semibold">${act.document_control_number}</span>`;
        break;
      case "signed":
        fullActionText = `<span class="font-semibold">${fullUserName}</span> signed the document`;
        break;
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
  const currentOffice = data.office?.office_name || null;
  const userSelect = document.getElementById("userSelect");
  const users = await fetchWithRetry("/api/users", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
  });
  if (!users) return;

  const filtered = users.filter(
    (u) =>
      u.office?.office_name === currentOffice &&
      u.user_config?.approval_type !== approvalType
  );

  userSelect.innerHTML =
    `<option value="">Select User</option>` +
    filtered
      .map(
        (u) =>
          `<option value="${u.id}" data-approvalType="${u.user_config.approval_type}">${u.name}</option>`
      )
      .join("");
};

const userSelect = document.getElementById("userSelect");
const modalApproveBtn = document.getElementById("modalApproveBtn");
const modalDisapproveBtn = document.getElementById("modalDisapproveBtn");
const modalForDiscussionBtn = document.getElementById("modalForDiscussionBtn");
const modalrevisionBtn = document.getElementById("modalrevisionBtn");
const confirmBtn = document.getElementById("confirmApprovalBtn");
const confirmDisapprovalBtn = document.getElementById("confirmDisapprovalBtn");
const submitrevisionBtn = document.getElementById("submitrevisionBtn");
const confirmForDiscussionBtn = document.getElementById(
  "confirmForDiscussionBtn"
);
const remarksTextarea = document.getElementById("remarksTextarea");
const document_id = document.getElementById("document_id");

confirmBtn.addEventListener("click", () => {
  const selectedOption = userSelect.options[userSelect.selectedIndex];
  sendApprovalAction({
    approvalId: document_id.textContent,
    action: "approved",
    next_action: selectedOption.dataset.approvaltype,
    remarks: remarksTextarea.value,
    nextUserId: userSelect.value,
  });
});
confirmDisapprovalBtn.addEventListener("click", () => {
  const selectedOption = userSelect.options[userSelect.selectedIndex];
  sendApprovalAction({
    approvalId: document_id.textContent,
    action: "disapproved",
    next_action: selectedOption.dataset.approvaltype,
    remarks: remarksTextarea.value,
    nextUserId: userSelect.value,
  });
});
submitrevisionBtn.addEventListener("click", async function () {
  const reviseformData = new FormData();
  const revisefileInput = document.getElementById("revisefileInput");

  reviseformData.append(
    "revisedocControlNumber",
    document.getElementById("revisedocControlNumber").textContent.trim()
  );
  reviseformData.append("document_form", "PDF");

  if (revisefileInput.files.length > 0) {
    reviseformData.append("file", revisefileInput.files[0]);
  }

  this.disabled = true;
  this.textContent = "Submitting...";

  try {
    const result = await fetchWithRetry("/api/documents/revise", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: reviseformData,
    });

    console.log(result);
  } catch (error) {
    console.error("Revision failed:", error);
  } finally {
    this.disabled = false;
    this.textContent = "Submit Revision";
  }
});
confirmForDiscussionBtn.addEventListener("click", () => {
  const selectedOption = userSelect.options[userSelect.selectedIndex];
  sendApprovalAction({
    approvalId: document_id.textContent,
    action: "for-discussion",
    next_action: selectedOption.dataset.approvaltype,
    remarks: remarksTextarea.value,
    nextUserId: userSelect.value,
  });
});
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
modalApproveBtn.addEventListener("click", () =>
  initModal({
    modalId: "approvalModal",
  })
);

modalDisapproveBtn.addEventListener("click", () =>
  initModal({
    modalId: "disapprovalModal",
  })
);

modalForDiscussionBtn.addEventListener("click", () =>
  initModal({
    modalId: "forDiscussionModal",
  })
);
