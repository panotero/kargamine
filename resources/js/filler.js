async function fillOfficeDropdown(otherofficetb = null) {
  const officedropdown = document.querySelectorAll(".officeSelect");

  const res = await fetch("/api/offices");
  const offices = await res.json();

  officedropdown.forEach((officeoption) => {
    officeoption.innerHTML =
      `<option value="">Select Office</option>` +
      offices
        .map(
          (o) => `<option value="${o.office_code}">${o.office_code}</option>`
        )
        .join("") +
      `<option value="Other">Other</option>`;
  });

  return true;
}

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
    `
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
          (o) => `<option value="${o.office_code}">${o.office_code}</option>`
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
                `<option value="${dt.document_type}">${dt.document_type}</option>`
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
      console.log("User is not logged in");
    }
    return data;
  } catch (error) {
    console.error("Failed to fetch user info:", error);
    window.authUser = null;
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

window.checkOverDue = async function checkOverDue(documents) {
  let overdue = 0;
  const now = new Date();
  const today =
    now.getFullYear() +
    "-" +
    String(now.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(now.getDate()).padStart(2, "0");
  //counting of overdue
  const activeStatuses = ["pending", "routed", "for review", "for approval"];
  documents.forEach(async (docs) => {
    if (
      docs.due_date > today &&
      activeStatuses.includes(docs.status.toLowerCase())
    ) {
      overdue++;
      updateStatus(docs.document_id, "overdue");
      //   return;
      //update doc status to overdue
    } else {
      updateStatus(docs.document_id, "due today");
    }
  });
  return overdue;
};

async function updateStatus(document_id, status) {
  try {
    const payload = {
      document_id: document_id,
      status: status,
    };
    const data = await fetchWithRetry("/api/documents/update_status", {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(payload),
    });
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}
