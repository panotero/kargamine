const NOTIFICATION_POLL_INTERVAL_MS = 20000;
const NOTIFICATION_SCROLL_LOAD_DELAY_MS = 2000;
const NOTIFICATION_SCROLL_NEAR_BOTTOM_PX = 60;

let notificationPollTimer = null;
let scrollLoadTimer = null;
let loadMoreController = null;
let isNearBottom = false;
let isLoadingMore = false;
let hasMoreNotifications = true;
let lastNotificationId = null;

window.initNotifications = function initNotifications() {
  const notifIcon = document.getElementById("notificationIcon");
  const notifCountEl = document.getElementById("notifcount");
  const wrapper = document.getElementById("notificationWrapper");
  if (!notifIcon || !notifCountEl || !wrapper) return;

  if (notificationPollTimer) clearInterval(notificationPollTimer);

  refreshUnreadCount();
  notificationPollTimer = setInterval(refreshUnreadCount, NOTIFICATION_POLL_INTERVAL_MS);

  notifIcon.addEventListener("click", () => {
    resetPagination();
    refreshNotifications();
  });

  wrapper.addEventListener("scroll", () => handleWrapperScroll(wrapper));

  document.addEventListener("click", (e) => {
    if (e.target.closest("[data-notification-mark-all]")) {
      markAllRead();
    }
  });
};

async function refreshUnreadCount() {
  const res = await fetchWithRetry("/api/notifications/unread-count", {
    headers: { Accept: "application/json" },
  });
  if (!res) return;
  updateBadge(res.count);
}

function updateBadge(count) {
  const notifCountEl = document.getElementById("notifcount");
  if (!notifCountEl) return;

  if (count > 0) {
    notifCountEl.textContent = count;
    notifCountEl.classList.remove("hidden");
  } else {
    notifCountEl.textContent = "";
    notifCountEl.classList.add("hidden");
  }
}

function resetPagination() {
  cancelPendingLoadMore();
  lastNotificationId = null;
  hasMoreNotifications = true;
  isNearBottom = false;
}

async function refreshNotifications() {
  const res = await fetchWithRetry("/api/notifications", {
    headers: { Accept: "application/json" },
  });
  if (!res || res.success === false) return;

  lastNotificationId = res.data.length ? res.data[res.data.length - 1].id : null;
  hasMoreNotifications = res.has_more;
  renderNotifications(res.data, { append: false });
}

// Fetches the next batch after the currently loaded list, appending below it.
async function loadMoreNotifications() {
  if (!hasMoreNotifications || isLoadingMore || lastNotificationId == null) return;

  isLoadingMore = true;
  loadMoreController = new AbortController();

  const res = await fetchWithRetry(
    `/api/notifications?before_id=${lastNotificationId}`,
    { headers: { Accept: "application/json" } },
    loadMoreController.signal,
  );

  removeLoadingIndicator();
  isLoadingMore = false;
  loadMoreController = null;

  if (!res || res.aborted || res.success === false) return;

  if (res.data.length) {
    lastNotificationId = res.data[res.data.length - 1].id;
  }
  hasMoreNotifications = res.has_more;
  renderNotifications(res.data, { append: true });

  // Still pinned to the bottom (short viewport / small batch) - keep the chain going.
  if (isNearBottom) scheduleLoadMore();
}

function handleWrapperScroll(wrapper) {
  const nearBottom =
    wrapper.scrollTop + wrapper.clientHeight >= wrapper.scrollHeight - NOTIFICATION_SCROLL_NEAR_BOTTOM_PX;

  if (nearBottom) {
    if (isNearBottom) return;
    isNearBottom = true;
    scheduleLoadMore();
  } else {
    isNearBottom = false;
    cancelPendingLoadMore();
  }
}

function scheduleLoadMore() {
  if (!hasMoreNotifications || isLoadingMore) return;

  showLoadingIndicator();

  clearTimeout(scrollLoadTimer);
  scrollLoadTimer = setTimeout(() => {
    scrollLoadTimer = null;
    loadMoreNotifications();
  }, NOTIFICATION_SCROLL_LOAD_DELAY_MS);
}

// Cancels a pending debounce timer, and aborts an in-flight load-more request if one is running.
function cancelPendingLoadMore() {
  if (scrollLoadTimer) {
    clearTimeout(scrollLoadTimer);
    scrollLoadTimer = null;
  }

  if (loadMoreController) {
    loadMoreController.abort();
    loadMoreController = null;
    isLoadingMore = false;
  }

  removeLoadingIndicator();
}

function showLoadingIndicator() {
  removeLoadingIndicator();
  const container = document.getElementById("notificationsContainer");
  if (!container) return;

  const el = document.createElement("div");
  el.id = "notificationsLoadingMore";
  el.className = "flex items-center justify-center py-3";
  el.innerHTML = `<div class="w-5 h-5 border-2 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>`;
  container.appendChild(el);
}

function removeLoadingIndicator() {
  document.getElementById("notificationsLoadingMore")?.remove();
}

function renderNotifications(notifications, { append }) {
  const container = document.getElementById("notificationsContainer");
  if (!container) return;

  if (!append) container.innerHTML = "";

  if (!append && notifications.length === 0) {
    container.innerHTML = `<div class="px-4 py-6 text-sm text-center text-gray-500 dark:text-gray-400">No notifications</div>`;
    return;
  }

  notifications.forEach((notification) => {
    container.appendChild(buildNotificationItem(notification));
  });
}

function buildNotificationItem(notification) {
  const item = document.createElement("div");
  item.className =
    "cursor-pointer px-4 py-3 border-b border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600" +
    (notification.is_read ? "" : " bg-blue-50 dark:bg-zinc-700/40");

  item.innerHTML = `
    <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">${escapeHtml(notification.title)}</p>
    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">${escapeHtml(notification.message)}</p>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${formatTimestamp(notification.created_at)}</p>
  `;

  item.addEventListener("click", () => handleNotificationClick(notification));
  return item;
}

async function handleNotificationClick(notification) {
  markRead([notification.id]);

  const wrapper = document.getElementById("notificationWrapper");
  if (wrapper) wrapper.style.display = "none";

  if (notification.link_url && typeof window.loadPage === "function") {
    await window.loadPage({
      title: notification.link_title || "",
      link: notification.link_url,
    });
  }

  const modalFn = notification.data?.modal_fn;
  if (modalFn && typeof window[modalFn] === "function") {
    window[modalFn](...(notification.data.modal_args || []));
  }
}

async function markRead(ids) {
  await fetchWithRetry("/api/notifications/mark-read", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content,
    },
    body: JSON.stringify({ ids }),
  });
  refreshUnreadCount();
}

async function markAllRead() {
  await fetchWithRetry("/api/notifications/mark-read", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content,
    },
    body: JSON.stringify({ all: true }),
  });
  refreshUnreadCount();
  resetPagination();
  refreshNotifications();
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

function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str ?? "";
  return div.innerHTML;
}
