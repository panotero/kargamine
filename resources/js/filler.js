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
