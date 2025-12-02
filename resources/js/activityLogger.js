async function logActivity(
  action,
  documentId = null,
  documentControlNumber = null
) {
  try {
    const userId = window.authUser ? window.authUser.id : null;

    const payload = {
      action: action,
      document_id: documentId,
      document_control_number: documentControlNumber,
      user_id: userId,
    };

    const response = await fetch("/api/activities", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(payload),
    });

    const data = await response.json();

    if (!response.ok) {
      console.error("Failed to log activity:", data);
    } else {
      console.log("Activity logged successfully:", data);
    }
  } catch (error) {
    console.error("Error logging activity:", error);
  }
}
window.logActivity = logActivity;
