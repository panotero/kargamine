async function fillOfficeDropdown(otherofficetb = null) {
  const officedropdown = document.querySelectorAll(".officeSelect");

  const res = await fetch("/api/offices");
  const offices = await res.json();

  officedropdown.forEach((officeoption) => {
    officeoption.innerHTML =
      `<option value="">Select Office</option>` +
      offices
        .map(
          (o) => `<option value="${o.office_code}">${o.office_code}</option>`,
        )
        .join("") +
      `<option value="Other">Other</option>`;
  });

  return offices;
}

async function getOfficeList() {
  const officedropdown = document.querySelectorAll(".officeSelect");

  const res = await fetch("/api/getOfficeList");
  const offices = await res.json();
  console.log(offices);
}

window.office = async function office() {
  try {
    const officeList = await fetchWithRetry(`/api/offices`, {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    });
    return officeList;
    // console.log(data);
  } catch (error) {
    console.log(error);
  }
};

function parseDateSafe(dateString) {
  return new Date(dateString.replace(" ", "T"));
}

function safeDate(d) {
  return new Date(d.replace(" ", "T"));
}
window.calculateDuration = function calculateDuration(dateForwarded) {
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
};

window.fillOfficeDropdownCustom = async function fillOfficeDropdownCustom() {
  const dropdownContainers = document.querySelectorAll(".custom-dropdown");

  if (!dropdownContainers.length) return false;

  // Fetch offices once for all dropdowns
  const res = await fetch("/api/offices");
  const offices = await res.json();
  const officeOptionsHTML =
    `<li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" data-value="">
      Select Office
    </li>` +
    offices
      .map(
        (o) => `
      <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" data-value="${o.office_code}">
        ${o.office_code}
      </li>
    `,
      )
      .join("") +
    `<li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" data-value="Other">
      Other
    </li>`;

  dropdownContainers.forEach((container) => {
    const btn = container.querySelector(".dropdownBtn");
    const menu = container.querySelector(".dropdownMenu");
    const select = container.querySelector(".officeSelect"); // hidden select for form submission

    // Populate custom dropdown
    menu.innerHTML = officeOptionsHTML;

    // Populate hidden select (keeps submission logic intact)
    select.innerHTML =
      `<option value="">Select Office</option>` +
      offices
        .map(
          (o) => `<option value="${o.office_code}">${o.office_code}</option>`,
        )
        .join("") +
      `<option value="Other">Other</option>`;

    // Handle dropdown toggle
    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      menu.classList.toggle("hidden");
    });

    // Handle selection
    menu.addEventListener("click", (e) => {
      const item = e.target.closest("li");
      if (!item) return;

      btn.textContent = item.textContent;
      select.value = item.dataset.value;
      menu.classList.add("hidden");

      // Trigger change event for form submission or other logic
      select.dispatchEvent(new Event("change"));
    });
  });

  // Close dropdowns on outside click
  document.addEventListener("click", () => {
    document
      .querySelectorAll(".dropdownMenu")
      .forEach((menu) => menu.classList.add("hidden"));
  });
};

function fillDocType(otherdocumenttb = null) {
  const documentdropdown = document.querySelectorAll(".docTypeSelect");

  documentdropdown.forEach((documentoption) => {
    fetch("/api/documenttypes")
      .then((res) => res.json())
      .then((doctype) => {
        documentoption.innerHTML =
          `<option value="">Select Document Type</option>` +
          doctype
            .map(
              (dt) =>
                `<option value="${dt.document_type}">${dt.document_type}</option>`,
            )
            .join("") +
          `<option value="Other">Other</option>`;
      });
  });
}

async function fetchAuthUser() {
  try {
    const data = await fetchWithRetry("/api/user_info", {
      headers: {
        Accept: "application/json",
      },
    });

    if (data.isLoggedIn) {
      window.authUser = data.user;
    } else {
      window.authUser = null;
      console.error("User is not logged in");
    }
    return data;
  } catch (error) {
    console.error("Failed to fetch user info:", error);
    window.authUser = null;
  }
}

window.filllabeldropdown = async function labeldropdown(document_id = null) {
  const label = document.querySelectorAll(".labeldropdown");

  const labels = await fetchWithRetry("/api/labeltypes/", {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });
  //   return;

  label.forEach((labeloption) => {
    const selectedLabel = labeloption.dataset.selectedLabel || "";

    // Keep the first option as is (placeholder or selected value)
    // Remove any old appended options except the first
    Array.from(labeloption.options)
      .slice(1)
      .forEach((opt) => opt.remove());

    // Append labels from API
    labels.forEach((o) => {
      const option = document.createElement("option");
      option.value = o.label_name;
      option.textContent = o.label_name;

      // Automatically select if it matches saved label
      if (o.label_name === selectedLabel) {
        option.selected = true;
      }

      labeloption.appendChild(option);
    });

    // Always append "Other" at the end
    const otherOption = document.createElement("option");
    otherOption.value = "Other";
    otherOption.textContent = "Other";
    if (selectedLabel === "Other") {
      otherOption.selected = true;
    }
    labeloption.appendChild(otherOption);

    labeloption.addEventListener("change", () => {
      const row = labeloption.closest("tr");

      if (!row) return;

      const documentId = row.dataset.documentId; // camelCase
      console.log("label has been changed to:" + labeloption.value);
      updatelabel(documentId, labeloption.value);
      //trigger label update
    });
  });

  return true;
};

async function updatelabel(document_id, label) {
  console.log("updated document id: " + document_id + " label to:" + label);
  //   return;
  const response = await fetch(`/api/documents/update_label/${document_id}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({
      document_id: document_id,
      label: label,
    }),
  });
  if (response.ok) {
    getDocs();
    showMessage({
      status: "success",
      message: "label updated",
    });
  }
}

window.fetchAuthUser = fetchAuthUser;
window.fillOfficeDropdown = fillOfficeDropdown;
window.fillDocType = fillDocType;

// ---------------------- DATA TABLE HELPERS ----------------------
window.clearTable = function clearTable(selector) {
  if ($.fn.DataTable.isDataTable(selector)) {
    $(selector).DataTable().clear();
  } else {
    const tbody = document.querySelector(`${selector} tbody`);
    if (tbody) tbody.innerHTML = "";
  }
};

function redrawTable(selector) {
  if ($.fn.DataTable.isDataTable(selector)) {
    $(selector).DataTable().draw(false);
  }
}

window.checkOverDue = function checkOverDue(documents) {
  let overdue = 0;
  let filterdoc = [];
  const statuses = ["signed", "approved", "completed"];
  const now = new Date();
  const today =
    now.getFullYear() +
    "-" +
    String(now.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(now.getDate()).padStart(2, "0");

  window.today = today;
  //counting of overdue
  const activeStatuses = ["pending", "routed", "for review", "for approval"];
  documents.forEach(async (docs) => {
    if (
      today > docs.due_date &&
      activeStatuses.includes(docs.status.toLowerCase())
    ) {
      overdue++;
    }

    filterdoc = documents.filter(
      (doc) =>
        today > doc.due_date &&
        activeStatuses.includes(doc.status.toLowerCase()),
    );
  });
  return filterdoc;
};

async function updateStatus(document_id, status) {
  try {
    const payload = {
      document_id: document_id,
      status: status,
    };
    const data = await fetchWithRetry("/api/documents/update_status", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(payload),
    });
  } catch (error) {
    console.error(error);
  }
}
// Prevent special characters on all elements with class "no-special-chars"
document.querySelectorAll(".no-special-chars").forEach((el) => {
  // Only allow letters, numbers, space, dot, comma, dash, underscore
  const regex = /[^a-zA-Z0-9 .,\-_]/g;

  // Sanitize on input (typing, paste, etc.)
  el.addEventListener("input", () => {
    el.value = el.value.replace(regex, "");
  });
});

window.populateYearDropDowm = function populateYearDropDowm() {
  const currentYear = new Date().getFullYear();
  const yearSelects = document.querySelectorAll("select.year");

  yearSelects.forEach((select) => {
    select.innerHTML = "";

    for (let year = currentYear + 3; year >= currentYear - 3; year--) {
      const option = document.createElement("option");
      option.value = year;
      option.textContent = year;

      if (year === currentYear) {
        option.selected = true;
      }

      select.appendChild(option);
    }
  });
};

window.controller = new AbortController();
