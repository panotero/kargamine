async function fillOfficeDropdown(otherofficetb = null) {
  const officedropdown = document.querySelectorAll(".officeSelect");

  const res = await fetch("/api/offices");
  const offices = await res.json();

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

  return true;
}
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
