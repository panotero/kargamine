// Mailer settings page. All three actions (save config, send test mail, trigger
// test API) go through apiCall + showMessage - matching every other settings form.
// Uses document-level delegation so it survives SPA page swaps without re-binding.

// --- Save SMTP configuration -------------------------------------------------
document.addEventListener("submit", async function (e) {
  const form = e.target;
  if (form.id !== "mailerConfigForm") return;

  e.preventDefault();

  const payload = {
    mail_mailer: form.querySelector('[name="mail_mailer"]').value,
    mail_host: form.querySelector('[name="mail_host"]').value,
    mail_port: form.querySelector('[name="mail_port"]').value,
    mail_username: form.querySelector('[name="mail_username"]').value,
    // Blank means "keep the current password" - the controller preserves it.
    mail_password: form.querySelector('[name="mail_password"]').value,
    mail_encryption: form.querySelector('[name="mail_encryption"]').value,
    mail_from_address: form.querySelector('[name="mail_from_address"]').value,
    mail_from_name: form.querySelector('[name="mail_from_name"]').value,
  };

  const response = await window.apiCall({
    mode: "POST",
    isJson: true,
    payload,
    url: "/mailer_save",
    button: form.querySelector('button[type="submit"], button:not([type])'),
  });

  showMessage({
    status: response.success ? "success" : "error",
    message: response.message || (response.success ? "Saved." : "Failed to save."),
  });

  if (response.success) {
    // Clear the password field again so it stays blank after a save.
    form.querySelector('[name="mail_password"]').value = "";
  }
});

// --- Send test mail ----------------------------------------------------------
document.addEventListener("submit", async function (e) {
  const form = e.target;
  if (form.id !== "testMailForm") return;

  e.preventDefault();

  const payload = {
    to: form.querySelector('input[name="to"]').value,
    subject: form.querySelector('input[name="subject"]').value,
    title: form.querySelector('input[name="title"]').value,
    body: form.querySelector('textarea[name="body"]').value,
  };

  const response = await window.apiCall({
    mode: "POST",
    isJson: true,
    payload,
    url: "/api/send-mail",
    button: form.querySelector('button[type="submit"]'),
  });

  showMessage({
    status: response.success ? "success" : "error",
    message: response.message || (response.success ? "Mail sent." : "Failed to send mail."),
  });

  if (response.success) form.reset();
});

// --- Trigger test API --------------------------------------------------------
document.addEventListener("click", async function (event) {
  if (!event.target || event.target.id !== "triggerApiBtn") return;

  const response = await window.apiCall({
    mode: "POST",
    isJson: true,
    payload: { extraData: "test" },
    url: "/api/test-api",
    button: event.target,
  });

  showMessage({
    status: response.success ? "success" : "error",
    message: response.message || (response.success ? "API triggered." : "Failed."),
  });
});
