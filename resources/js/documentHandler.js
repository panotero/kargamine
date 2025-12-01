// ----------------------------
// Global function to populate Document Modal
// ----------------------------
async function populateDocumentModal(documentId) {
  try {
    const res = await fetch(`/api/documents/${documentId}`);
    const data = await res.json();
    // ------------------------
    // Check if document exists
    // ------------------------
    if (!data || Object.keys(data).length === 0) {
      hideModal("DocumentModal");
      showMessage({
        status: "error",
        message: "Document not found or does not exist.",
      });
      return;
    }

    // ------------------------
    // Populate Header
    // ------------------------
    document.getElementById("docId").value = data.document_id;
    // Set main document info
    setText("docControlNumber", data.document_control_number || "N/A");
    setText("docStatus", data.status || "N/A");

    const confirmationButton = document.getElementById("btnConfirm");
    confirmationButton.dataset.documentId = data.document_id || "";

    // ------------------------
    // Populate Metadata
    // ------------------------
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

    // ------------------------
    // Populate Files / Versions
    // ------------------------
    populateFileList(data.files || []);

    // ------------------------
    // Populate Activity Log
    // ------------------------
    populateActivityLog(data || []);
  } catch (error) {
    console.error("Failed to populate document modal:", error);
  }
}

// ------------------------
// Helper: set textContent safely
// ------------------------
function setText(id, text) {
  const el = document.getElementById(id);
  if (el) el.textContent = text || "";
}

// ------------------------
// Helper: hide modal
// ------------------------
function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.add("hidden");
}

// ------------------------
// Populate file list
// ------------------------
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

    // Open PDF modal on LI click
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

// ------------------------
// Open PDF modal and populate slides
// ------------------------
async function openPdfModal(filePath) {
  initModal({ modalId: "pdfPreviewModal" });

  const baseUrl = window.location.origin;
  const pdfUrl = `${baseUrl}/${filePath}`;

  const slides = await extractPdfImages(pdfUrl);
  loadSlidesFromArray(slides);
  initZoomFunction();
}

// ------------------------
// Populate Activity Log
// ------------------------
function populateActivityLog(data) {
  const activityLog = document.getElementById("activityLog"); // important logs only
  const fullActivityLog = document.getElementById("fullActivityLog"); // full log history

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
    console.log(act);
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

    const timeAgo = new Date(act.created_at).toLocaleString();
    const fromUser = "Unknown";
    const remarks = act.final_remarks;

    let displayText = "";

    // ----------------------------------------
    // ROUTE ACTION
    // ----------------------------------------
    if (
      ["route", "upload", "approved", "signed", "confirm"].includes(act.action)
    ) {
      let routeTarget = "";
      let actionText = "";

      // Determine target
      if (act.to_external == 1) {
        routeTargetOffice = data.destination_office
          ? data.destination_office
          : "Unknown Office";
      } else {
        routeTarget = act.routed_to
          ? `User ${act.routed_to}`
          : "Unknown Recipient";
      }

      // Determine message based on action
      switch (act.action) {
        case "route":
          actionText = `<span>routed the document to <p class="font-semibold">${routeTarget}</p</span>`;
          break;
        case "upload":
          actionText = `<span class="font-semibold">${
            act.user.name
          }</span> uploaded a document ${
            routeTarget
              ? ` for <span class="font-semibold">${act.from_user.name}</span>`
              : ""
          } <br>`;
          break;
        case "approved":
          actionText = `approved the document${
            routeTarget
              ? ` for <span class="font-semibold">${routeTarget}</span>`
              : ""
          }`;
          break;
        case "confirm":
          actionText = `<span class="font-semibold">${act.user.name}</span> confirmed receipt of document number: <p class="font-semibold">${act.document_control_number}<br></p>`;
          break;
      }

      displayText = `
        <p>
             ${actionText}
            <span class="text-gray-500 text-xs">${timeAgo}</span>

        ${
          remarks
            ? `<p><span class="font-semibold">Remarks: </span>${remarks}</p>`
            : ""
        }
    </p>`;

      // Show important activity
      importantDiv.innerHTML = displayText;
      activityLog.appendChild(importantDiv);
    }

    // ----------------------------------------
    // OTHER ACTIONS
    // ----------------------------------------
    else {
      const userName = act.user_id ? `User ${act.user_id}` : "Unknown";

      displayText = `
                <p>
                    <span class="font-semibold">${userName}</span>
                    ${act.action} the document
                </p>
                <p>
                    <span class="text-gray-500 text-xs">${timeAgo}</span>
                </p>
            `;

      // Only APPROVED / RECEIVED / REJECTED etc. should appear in important logs
      const importantActions = [
        "approve",
        "reject",
        "receive",
        "returned",
        "review",
      ];

      if (importantActions.includes(act.action)) {
        importantDiv.innerHTML = displayText;
        activityLog.appendChild(importantDiv);
      }
    }

    // ----------------------------------------
    // FULL LOG ALWAYS GETS EVERY ENTRY
    // ----------------------------------------
  });
  const activityLogcont = document.getElementById("activityLog");
  activityLogcont.scrollTop = 0;
}

// ------------------------
// Zoom functionality
// ------------------------
function initZoomFunction() {
  let currentZoom = 1;
  const zoomStep = 0.2;
  const glideSlides = document.getElementById("glideSlides");

  const applyZoom = () => {
    glideSlides.querySelectorAll("img").forEach((img) => {
      img.style.transform = `scale(${currentZoom})`;
      img.style.transition = "transform 0.2s";
      img.style.transformOrigin = "center center";
    });
  };

  const hammer = new Hammer(glideSlides);
  hammer.get("pinch").set({ enable: true });

  hammer.on("pinch", (ev) => {
    currentZoom = Math.max(1, ev.scale);
    applyZoom();
  });
  hammer.on("pinchend", (ev) => {
    currentZoom = Math.max(1, ev.scale);
  });
}

function initdocumentcontroller() {
  // ----------------------------
  // Helper Functions
  // ----------------------------
  function calculateDuration(dateForwarded) {
    const start = parseDateSafe(dateForwarded);
    const end = new Date();

    if (isNaN(start.getTime())) {
      console.error("Invalid date:", dateForwarded);
      return "Invalid date";
    }

    let diffMs = end.getTime() - start.getTime();
    if (diffMs < 0) diffMs = 0;

    const totalMinutes = Math.floor(diffMs / 60000);
    const totalHours = Math.floor(totalMinutes / 60);
    const days = Math.floor(totalHours / 24);
    const hours = totalHours % 24;
    const minutes = totalMinutes % 60;

    let result = [];
    if (days > 0) result.push(`${days} day${days > 1 ? "s" : ""}`);
    if (hours > 0) result.push(`${hours} hour${hours > 1 ? "s" : ""}`);
    result.push(`${minutes} min`);

    return result.join(" ");
  }

  function parseDateSafe(dateString) {
    return new Date(dateString.replace(" ", "T"));
  }

  function safeDate(d) {
    return new Date(d.replace(" ", "T"));
  }

  function appendDocumentRow(tableBody, item, source = null, initTable = true) {
    if (!tableBody || !item) return;

    const routeBtn = document.getElementById("routeDocumentBtn");
    const approvalButtons = document.getElementById("approvalButtons");

    const {
      document_id,
      document_code,
      document_control_number,
      document_type,
      particular,
      office_origin,
      destination_office,
      date_forwarded,
      date_of_document,
      created_at,
      confidentiality,
      status,
      recipient_id,
    } = item;

    const tr = document.createElement("tr");
    tr.classList.add("border-t", "hover:bg-gray-50", "cursor-pointer");

    tr.dataset.documentId = document_id;
    tr.dataset.documentControlNumber = document_control_number;
    tr.dataset.userId = item.user_id || "";
    tr.dataset.status = status;
    tr.dataset.source = source;

    tr.innerHTML = `
        <td class="px-4 py-2">${document_control_number}</td>
        <td class="px-4 py-2">${document_code}</td>
        <td class="px-4 py-2">
            <select class="border rounded px-2 py-1 text-xs labeldropdown">
                <option ${
                  document_type === "General" ? "selected" : ""
                }>General</option>
                <option ${
                  document_type === "Confidential" ? "selected" : ""
                }>Confidential</option>
            </select>
        </td>
        <td class="px-4 py-2">${particular}</td>
        <td class="px-4 py-2">${office_origin}</td>
        <td class="px-4 py-2">${destination_office}</td>
        <td class="px-4 py-2">${date_forwarded || "-"}</td>
        <td class="px-4 py-2">${calculateDuration(date_forwarded)}</td>
        <td class="px-4 py-2">${
          created_at ? created_at.split("T")[0] : "-"
        }</td>
        <td class="px-4 py-2">${confidentiality || "-"}</td>
        <td class="px-4 py-2">${status || "-"}</td>
    `;

    tr.addEventListener("click", (e) => {
      if (e.target.classList.contains("labeldropdown")) return;
      checkActionButtons(item.status, item.receipt_confirmation, source);

      clearModalFields();
      showSkeletonLoaders();

      initModal({ modalId: "DocumentModal" });
      populateDocumentModal(document_id);

      const lowerStatus = status?.toLowerCase() || "";

      //   if (source === "all" || lowerStatus === "for approval") {
      //     routeBtn?.classList.add("hidden");

      //     if (
      //       lowerStatus === "for approval" &&
      //       recipient_id === window.authUser.id
      //     ) {
      //       approvalButtons?.classList.remove("hidden");
      //     }
      //   } else {
      //     routeBtn?.classList.remove("hidden");
      //     approvalButtons?.classList.add("hidden");
      //   }

      logActivity("view", document_id, document_control_number);
    });

    tableBody.appendChild(tr);

    if (initTable) initDataTables();
  }
  function checkActionButtons(
    status = false,
    receiptConfirmation = false,
    source = false
  ) {
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
    ];

    // First, hide all action buttons
    actionButtonArray.forEach((item) => {
      if (!item.el.classList.contains("hidden")) {
        item.el.classList.add("hidden");
      }
    });
    if (source === "all") {
      return;
    }

    // Then, show the appropriate button based on the conditions
    if (receiptConfirmation === 0) {
      if (status === "For approval") {
        const approvalAction = actionButtonArray.find(
          (item) => item.name === "approvalActions"
        );
        if (approvalAction?.el) approvalAction.el.classList.remove("hidden");
      } else {
        const confirmationAction = actionButtonArray.find(
          (item) => item.name === "confirmationActions"
        );
        if (confirmationAction?.el)
          confirmationAction.el.classList.remove("hidden");
      }
    } else {
      const routeAction = actionButtonArray.find(
        (item) => item.name === "routeActions"
      );
      if (routeAction?.el) routeAction.el.classList.remove("hidden");
    }
  }
  /* ------------ Helper Functions ------------- */

  function clearModalFields() {
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
  }

  function showSkeletonLoaders() {
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
  }
  window.getDocs = async function getDocs() {
    const authUser = window.authUser;
    if (!authUser) return;

    const userId = authUser.id;
    const userOfficeName = authUser.office?.office_name || null;
    const userApprovalType = authUser.user_config?.approval_type || null;

    try {
      const response = await fetch("/api/documents");
      const documents = await response.json();

      const allDocsTableBody = document.querySelector(
        "#allDocumentTable tbody"
      );
      const assignedTableBody = document.querySelector(
        "#assignedToYouDocumentTable tbody"
      );
      if (!allDocsTableBody || !assignedTableBody) return;

      // Clear tables
      allDocsTableBody.innerHTML = "";
      assignedTableBody.innerHTML = "";

      documents.forEach((doc) => {
        const involvedOffices = Array.isArray(doc.involved_office)
          ? doc.involved_office
          : [];

        // --------------------------
        // All Documents
        // --------------------------
        const canSeeAllDocs =
          !userOfficeName ||
          userOfficeName === "ODDG-PP" ||
          involvedOffices.includes(userOfficeName);

        if (canSeeAllDocs) {
          appendDocumentRow(allDocsTableBody, doc, "all", false);
        }

        // --------------------------
        // Assigned To You
        // --------------------------
        let showAssigned = false;
        const recipientId = doc.recipient_id;

        if (recipientId !== null) {
          showAssigned = recipientId == userId;
        } else {
          const isRoutingUser = userApprovalType === "routing";
          const sameOffice = userOfficeName === doc.destination_office;
          showAssigned = isRoutingUser && sameOffice;
        }

        if (showAssigned) {
          appendDocumentRow(assignedTableBody, doc, "assigned", false);
        }
      });

      // Initialize DataTables **once** per table after all rows are appended
      initDataTables();
    } catch (error) {
      console.error("Error fetching documents:", error);
    }
  };
  // ----------------------------
  // Event Listeners
  // ----------------------------
  function initEventListeners() {
    // Initialize PDF dropzones
    initPDFDropzone({
      dropzoneId: "dropzone",
      fileInputId: "fileInput",
      fileInfoId: "fileInfo",
      clearBtnId: "clearSelectionBtn",
    });
    initroute();
    init();
    async function init() {
      const response = await fillOfficeDropdown();

      if (response) {
        console.log(document.getElementById("originOffice"));
        document.getElementById("originOffice").value =
          authUser.office.office_name;
        fillDocType();
      }
    }
    // console.log(authUser.office.office_name);

    // --------------------------
    // Utility function: Toggle "Other" field visibility
    // --------------------------
    function toggleOtherField(dropdownId, textboxId) {
      const dropdown = document.getElementById(dropdownId);
      const textbox = document.getElementById(textboxId);
      if (!dropdown || !textbox) return;

      dropdown.addEventListener("change", () => {
        textbox.classList.toggle("hidden", dropdown.value !== "Other");
      });
    }

    toggleOtherField("destinationOffice", "otherdestinationofficetb");
    toggleOtherField("originOffice", "otheroriginofficetb");
    toggleOtherField("documentType", "otherdoctypetb");

    // --------------------------
    // New Document Modal
    // --------------------------
    const newDocBtn = document.getElementById("btnNewDocument");
    const submitBtn = document.querySelector(".submitbtn");
    const fileInput = document.getElementById("fileInput");
    const confirmationBtn = document.getElementById("btnConfirm");
    // console.log(submitBtn);
    newDocBtn?.addEventListener("click", () => {
      initModal({ modalId: "modalNewDocument" });
    });
    submitBtn.addEventListener("click", async () => {
      console.log("submitBtn clicked");
      const modal = document.getElementById("modalNewDocument");
      if (!modal) return;

      clearModalErrors(); // clear previous errors
      const errors = [];

      // Validate PDF file
      if (!fileInput?.files[0]) {
        errors.push("Please upload a PDF file.");
      }

      // Validate required text fields
      const requiredFields = ["document_code", "subject", "signatory"];
      requiredFields.forEach((id) => {
        const el = document.getElementById(id);
        if (!el || !el.value.trim()) {
          errors.push(
            `${el?.previousElementSibling?.textContent || id} is required.`
          );
        }
      });

      // Dropdowns and "Other" inputs
      const originDropdown = document.getElementById("originOffice");
      const destinationDropdown = document.getElementById("destinationOffice");
      const documentDropdown = document.getElementById("documentType");

      const otherOriginInput = document.getElementById("otheroriginoffice");
      const otherDestinationInput = document.getElementById(
        "otherdestinationoffice"
      );
      const otherDocumentInput = document.getElementById("otherdocument");

      // ORIGIN OFFICE
      if (
        !originDropdown.value ||
        originDropdown.value.trim() === "" ||
        originDropdown.value === "Select..."
      ) {
        errors.push("Please select an Origin Office.");
      } else if (
        originDropdown.value === "Other" &&
        (!otherOriginInput || !otherOriginInput.value.trim())
      ) {
        errors.push("Please specify the Origin Office.");
      }

      // DESTINATION OFFICE
      if (
        !destinationDropdown.value ||
        destinationDropdown.value.trim() === "" ||
        destinationDropdown.value === "Select..."
      ) {
        errors.push("Please select a Destination Office.");
      } else if (
        destinationDropdown.value === "Other" &&
        (!otherDestinationInput || !otherDestinationInput.value.trim())
      ) {
        errors.push("Please specify the Destination Office.");
      }

      // DOCUMENT TYPE
      if (
        !documentDropdown.value ||
        documentDropdown.value.trim() === "" ||
        documentDropdown.value === "Select..."
      ) {
        errors.push("Please select a Document Type.");
      } else if (
        documentDropdown.value === "Other" &&
        (!otherDocumentInput || !otherDocumentInput.value.trim())
      ) {
        errors.push("Please specify the Document Type.");
      }

      // If any validation errors, show them and stop
      if (errors.length > 0) {
        showModalErrors(errors);
        modal.scrollTop = 0; // scroll to top to see errors
        return;
      }

      // No errors → prepare form data
      const formData = new FormData();
      const docFields = [
        "document_code",
        "subject",
        "documentType",
        "due_date",
        "document_date",
        "signatory",
        "remarks",
      ];

      docFields.forEach((id) => {
        const el = document.getElementById(id);
        if (!el) return;
        let value = sanitizeInput(el.value.trim());

        if (id === "subject") formData.append("particular", value);
        else if (id === "documentType") formData.append("document_type", value);
        else formData.append(id, value);
      });

      // Origin Office
      if (originDropdown.value === "Other") {
        formData.append(
          "office_origin",
          "OTHER - " + sanitizeInput(otherOriginInput.value)
        );
      } else {
        formData.append("office_origin", sanitizeInput(originDropdown.value));
      }

      // Destination Office
      if (destinationDropdown.value === "Other") {
        formData.append(
          "destination_office",
          "OTHER - " + sanitizeInput(otherDestinationInput.value)
        );
      } else {
        formData.append(
          "destination_office",
          sanitizeInput(destinationDropdown.value)
        );
      }

      // Document Type
      if (documentDropdown.value === "Other") {
        formData.append(
          "document_type",
          "OTHER - " + sanitizeInput(otherDocumentInput.value)
        );
      } else {
        formData.append("document_type", sanitizeInput(documentDropdown.value));
      }

      // Additional fields
      formData.append("user_id", window.authUser.id);
      formData.append("document_form", "PDF");
      formData.append("file", fileInput.files[0]);
      formData.append("date_received", new Date().toISOString().split("T")[0]);

      try {
        submitBtn.disabled = true;
        submitBtn.textContent = "Submitting...";
        console.log(document.querySelector('meta[name="csrf-token"]'));
        const response = await fetch("/api/documents", {
          method: "POST",

          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
              .content,
          },
          body: formData,
        });

        const result = await response.json();

        if (!response.ok) {
          if (response.status === 422) {
            const invalid = result.invalid_fields;

            let messages = ["Invalid input detected:"];

            Object.keys(invalid).forEach((field) => {
              invalid[field].forEach((msg) => {
                messages.push(`${field}: ${msg}`);
              });
            });

            showModalErrors(messages);
            return;
          }

          // If any validation errors, show them and stop
          if (errors.length > 0) {
            showModalErrors(errors);
            modal.scrollTop = 0; // scroll to top to see errors
            return;
          }

          return;
        } else {
          showMessage({
            status: "success",
            message: "Document has been uploaded",
          });
        }

        resetFormModal("modalNewDocument");
        showControlNumberModal(result.docControlNumber);
        getDocs();
      } catch (err) {
        console.error(err);
        showModalErrors(["Unexpected error occurred."]);
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = "Submit";
      }
    });

    // Helper functions
    function sanitizeInput(str) {
      if (typeof str !== "string") return "";
      return str
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#x27;")
        .replace(/\//g, "&#x2F;");
    }

    function showModalErrors(errors) {
      const errorBox = document.getElementById("modalErrorMessage");
      const errorList = document.getElementById("modalErrorList");
      errorList.innerHTML = "";
      errors.forEach((err) => {
        const li = document.createElement("li");
        li.textContent = err;
        errorList.appendChild(li);
      });
      errorBox.classList.remove("hidden");
    }

    function clearModalErrors() {
      const errorBox = document.getElementById("modalErrorMessage");
      const errorList = document.getElementById("modalErrorList");
      errorList.innerHTML = "";
      errorBox.classList.add("hidden");
    }

    // --------------------------
    // PDF Preview Modal
    // --------------------------
    document
      .querySelectorAll(".fileInfoButton")
      .forEach((btn) =>
        btn.addEventListener("click", () =>
          initModal({ modalId: "pdfPreviewModal" })
        )
      );

    // --------------------------
    // Routing Modal
    // --------------------------
    document.querySelectorAll(".routeBtn").forEach((btn) =>
      btn.addEventListener("click", () => {
        initPDFDropzone({
          dropzoneId: "routedropzone",
          fileInputId: "routefileInput",
          fileInfoId: "routefileInfo",
          clearBtnId: "clearrouteSelectionBtn",
        });
        initModal({ modalId: "routingModal" });
      })
    );

    // --------------------------
    // Route Office change logic
    // --------------------------
    const officeSelect = document.getElementById("routeOfficeSelect");
    const userSelect = document.getElementById("routeUserSelect");
    const approvalSelect = document.getElementById("approvalSelect");
    const statusSelect = document.getElementById("routeStatusSelect");
    const internalSection = document.getElementById("internalSection");
    const externalSection = document.getElementById("externalSection");
    const pdfUploadSection = document.getElementById("pdfUploadSection");
    const currentOffice = window.authUser.office?.office_name || null;

    officeSelect?.addEventListener("change", async (e) => {
      const selected = e.target.value;
      internalSection?.classList.toggle("hidden", selected !== currentOffice);
      externalSection?.classList.toggle(
        "hidden",
        selected === currentOffice || !selected
      );

      if (selected === currentOffice) {
        const users = await fetch("/api/users").then((res) => res.json());
        const filtered = users.filter(
          (u) => u.office?.office_name === currentOffice
        );
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

    statusSelect?.addEventListener("change", (e) => {
      pdfUploadSection?.classList.toggle(
        "hidden",
        e.target.value !== "approved"
      );
    });

    // --------------------------
    // Helper Functions
    // --------------------------
    function resetFormModal(modalId) {
      const modal = document.getElementById(modalId);
      if (!modal) return;

      modal.querySelectorAll("input, select, textarea").forEach((el) => {
        switch (el.type) {
          case "checkbox":
          case "radio":
            el.checked = false;
            break;
          case "file":
            el.value = "";
            break;
          default:
            el.value = "";
        }
      });

      const fileInfo = modal.querySelector("#fileInfo");
      const clearBtn = modal.querySelector("#clearSelectionBtn");
      if (fileInfo) fileInfo.textContent = "";
      if (clearBtn) clearBtn.classList.add("hidden");

      modal.classList.add("hidden");
    }

    function showControlNumberModal(docControlNumber) {
      if (!docControlNumber) return;

      const controlModal = document.getElementById("controlNumberModal");
      const controlText = document.getElementById("controlNumberText");
      if (!controlModal || !controlText) return;

      controlText.textContent = Array.isArray(docControlNumber)
        ? docControlNumber.join(", ")
        : docControlNumber;

      controlModal.classList.add("modal-open");
    }
  }

  document.getElementById("toggleFullLogBtn").addEventListener("click", () => {
    const panel = document.getElementById("fullActivityLogContainer");
    panel.classList.toggle("hidden");
  });

  const confirmButton = document.getElementById("btnConfirm");
  confirmButton.addEventListener("click", async (e) => {
    const post = {
      document_id: confirmButton.dataset.documentId,
      user_id: window.authUser.id,
    };
    const response = await fetch("/api/documents/confirm", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(post),
    });
    const result = await response.json();
    if (response.ok) {
      console.log("result ok!");

      document.getElementById("routeDocumentBtn").classList.remove("hidden");
      confirmButton.classList.add("hidden");
      getDocs();
    }
  });

  // ----------------------------
  // Initialization
  // ----------------------------
  getDocs();
  initEventListeners();
}

// Expose to global
window.initdocumentcontroller = initdocumentcontroller;
window.populateDocumentModal = populateDocumentModal;
window.initZoomFunction = initZoomFunction;
