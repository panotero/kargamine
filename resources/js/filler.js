async function fillOfficeDropdown(otherofficetb = null) {
  const officedropdown = document.querySelectorAll(".officeSelect");

  // Fetch offices ONCE
  const res = await fetch("/api/offices");
  const offices = await res.json();

  // Loop through dropdown elements and fill them
  officedropdown.forEach((officeoption) => {
    officeoption.innerHTML =
      `<option value="">Select Office</option>` +
      offices
        .map(
          (o) => `<option value="${o.office_name}">${o.office_name}</option>`
        )
        .join("") +
      `<option value="Other">Other</option>`;
  });

  // Tell caller that this async function is finished
  return true;
}
function fillDocType(otherdocumenttb = null) {
  const documentdropdown = document.querySelectorAll(".docTypeSelect");

  documentdropdown.forEach((documentoption) => {
    // Fetch offices
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

// window.routing
// Call this function on page load
async function fetchAuthUser() {
  try {
    const response = await fetch("/api/user_info", {
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json();

    if (data.isLoggedIn) {
      // Store globally
      window.authUser = data.user;
      //   console.log("Authenticated user:", window.authUser);
    } else {
      window.authUser = null;
      console.log("User is not logged in");
    }
  } catch (error) {
    console.error("Failed to fetch user info:", error);
    window.authUser = null;
  }
}
fetchAuthUser();
window.fetchAuthUser = fetchAuthUser;
window.fillOfficeDropdown = fillOfficeDropdown;
window.fillDocType = fillDocType;
