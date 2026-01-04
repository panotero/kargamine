let allNotificationIds = [];
let lastUnreadCount = 0;
const NotifContainer = document.getElementById("notifcount");
const notifIcon = document.getElementById("notificationIcon");
const notificationWrapper = document.getElementById("notificationWrapper");

window.initNotificationStream = function initNotificationStream() {
  const evtSource = new EventSource("/api/notifications/stream");
  evtSource.onmessage = function (event) {
    const notifications = JSON.parse(event.data);

    const unreadCount = notifications.filter((n) => n.is_read === false).length;
    if (unreadCount <= 0) {
      NotifContainer.classList.add("hidden");
    } else {
      NotifContainer.classList.remove("hidden");
    }

    NotifContainer.textContent = unreadCount > 0 ? unreadCount : "";
    allNotificationIds = notifications.map((n) => n.id);

    // const notificationsArray = notifications.map((item) => ({
    //   id: item.id,
    //   message: item.message,
    //   documentControlNumber: item.document?.document_control_number || null,
    //   document_id: item.document?.document_id || null,
    //   status: item.approvals?.status || null,
    //   created_at: item.created_at,
    //   is_read: item.is_read,
    // }));
    if (unreadCount !== lastUnreadCount) {
      if (typeof window.getDocs === "function") {
        window.getDocs();
      } else {
        console.warn("getDocs() not available yet.");
      }

      lastUnreadCount = unreadCount;
    }

    populateNotifications(notifications);
  };

  evtSource.onerror = (err) => {};
  notifIcon.addEventListener("click", async () => {
    if (allNotificationIds.length === 0) return;
    try {
      const res = await fetch("/api/notifications/mark-read", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        body: JSON.stringify({ ids: allNotificationIds }),
      });

      if (res.ok) {
        NotifContainer.textContent = "";

        NotifContainer.classList.add("hidden");
      } else {
        console.error("Failed to mark notifications as read.");
      }
    } catch (err) {
      console.error(err);
    }
  });
};

function populateNotifications(notificationsArray) {
  const container = document.getElementById("notificationsContainer");
  if (!container) return;

  container.innerHTML = "";
  const additionalMessage = "";

  notificationsArray.forEach((notification) => {
    const li = document.createElement("div");

    li.className =
      "cursor-pointer px-4 py-3 border-b border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600";

    li.dataset.document_id = notification.document_id;

    li.innerHTML = `
  <div class="flex items-start space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
      <!-- Icon/avatar -->

      <!-- Notification content -->
      <div class="flex-1 min-w-0">
          <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">
              ${formatNotificationMessage(
                notification.message,
                notification.document.document_control_number
              )}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
              ${formatTimestamp(notification.created_at)}
          </p>
      </div>
  </div>
        `;

    li.addEventListener("click", () => {
      //   console.log("Notification clicked:");
      //   console.log("Message:", notification.document.status);
      //   console.log("document ID", notification.document);

      checkActionButtons(
        notification.document.status,
        notification.document.recipient_id,
        notification.document.destination_office,
        notification.document.receipt_confirmation,
        notification.document.revision_status
      );

      clearModalFields();
      showSkeletonLoaders();

      initModal({
        modalId: "DocumentModal",
      });
      populateDocumentModal(notification.document_id);
      notificationWrapper.style.display = "none";
      logActivity(
        "view",
        notification.document_id,
        notification.document.document_control_number
      );
    });

    container.appendChild(li);
  });
}

function formatNotificationMessage(msg, docctrlnumber) {
  msg = msg.toLowerCase();

  if (msg.includes("uploaded")) {
    return (
      "Uploaded a document with control number <b>" +
      docctrlnumber +
      "</b> to your office"
    );
  }

  if (msg.includes("approval")) {
    return (
      "Routed a document with control number <b>" +
      docctrlnumber +
      "</b> for your approval"
    );
  } else if (msg.includes("routed")) {
    return (
      "Routed a document with control number <b>" +
      docctrlnumber +
      "</b> to you"
    );
  }

  if (msg.includes("remanded")) {
    return (
      "Remanded the document with control number <b>" + docctrlnumber + "</b>"
    );
  }

  if (msg.includes("signed")) {
    return (
      "Signed the document with control number <b>" + docctrlnumber + "</b>"
    );
  }

  /* IMPORTANT: order matters here */
  if (msg.includes("disapproved")) {
    return (
      "Disapproved the document with control number <b>" +
      docctrlnumber +
      "</b>"
    );
  }

  if (msg.includes("approved")) {
    return (
      "Approved the document with control number <b>" + docctrlnumber + "</b>"
    );
  }

  if (msg.includes("3 days")) {
    return (
      "The document with control number <b>" +
      docctrlnumber +
      "</b> is due in 3 days"
    );
  }

  if (msg.includes("due today")) {
    return (
      "The document with control number <b>" +
      docctrlnumber +
      "</b> is due today"
    );
  }

  if (msg.includes("over due") || msg.includes("overdue")) {
    return (
      "The document with control number <b>" + docctrlnumber + "</b> is overdue"
    );
  }

  return msg;
}
function formatTimestamp(isoString) {
  const date = new Date(isoString);
  return date.toLocaleString("en-US", {
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}
