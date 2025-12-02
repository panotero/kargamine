document.addEventListener("click", async function (event) {
  if (event.target && event.target.id === "triggerApiBtn") {
    const status = document.getElementById("apiStatus");

    status.style.display = "block";
    status.style.background = "#eee";
    status.style.color = "#000";
    status.textContent = "Sending...";

    try {
      const response = await fetch(`api/test-api`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        body: JSON.stringify({ extraData: "test" }),
      });

      const result = await response.json();

      if (response.ok && result.success) {
        status.style.background = "#d4edda";
        status.style.color = "#155724";
        status.textContent = result.message;
      } else {
        status.style.background = "#f8d7da";
        status.style.color = "#721c24";
        status.textContent = result.message || "Failed";
      }
    } catch (err) {
      status.style.background = "#f8d7da";
      status.style.color = "#721c24";
      status.textContent = "rror: " + err.message;
    }
  }
});

// Submit listener OUTSIDE the click handler
document.addEventListener("submit", async function (e) {
  const form = e.target;

  if (form.id !== "testMailForm") return;

  e.preventDefault();

  const statusBox = document.getElementById("mailStatus");

  const formData = {
    to: form.querySelector('input[name="to"]').value,
    subject: form.querySelector('input[name="subject"]').value,
    title: form.querySelector('input[name="title"]').value,
    body: form.querySelector('textarea[name="body"]').value,
  };

  statusBox.className = "p-2 rounded mb-3 bg-gray-100 text-gray-700";
  statusBox.textContent = "Sending...";
  statusBox.classList.remove("hidden");

  try {
    const response = await fetch(`api/send-mail`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
          .content,
      },
      body: JSON.stringify(formData),
    });

    const result = await response.json();

    if (response.ok && result.success) {
      statusBox.className = "p-2 rounded mb-3 bg-green-100 text-green-700";
      statusBox.textContent = result.message;
      form.reset();
    } else {
      statusBox.className = "p-2 rounded mb-3 bg-red-100 text-red-700";
      statusBox.textContent = result.message || "Failed to send mail.";
    }
  } catch (err) {
    statusBox.className = "p-2 rounded mb-3 bg-red-100 text-red-700";
    statusBox.textContent = "Error: " + err.message;
  }

  setTimeout(() => statusBox.classList.add("hidden"), 5000);
});
