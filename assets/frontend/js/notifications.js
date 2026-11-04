import { escapeHtml, request, route } from './http.js';

// Notifications of the header, refreshed every 10 seconds
// The header renders the block for the desktop and the mobile navigations
const lists = document.querySelectorAll('.notificationBlock .app-notifications');
const title = document.title;

const renderNotification = (list, notification) => {
  const avatar = notification.authors?.[0]?.avatar?.thumbnail ?? list.dataset.placeholder;

  list.insertAdjacentHTML('beforeend', list.dataset.prototype
    .replace(/__id__/g, escapeHtml(notification.id))
    .replace(/__target_url__/g, escapeHtml(notification.target))
    .replace(/__message__/g, escapeHtml(notification.message))
    .replace(/__image_path__/g, escapeHtml(avatar)));
};

const refresh = async () => {
  try {
    const notifications = await request(lists[0].dataset.url) ?? [];

    document.title = notifications.length > 0 ? `(${notifications.length}) ${title}` : title;

    document.querySelectorAll('.badge-count').forEach((badge) => {
      badge.textContent = notifications.length;
      badge.style.display = notifications.length > 0 ? '' : 'none';
    });

    document.querySelectorAll('.editorial-notifications').forEach((notificationList) => {
      notificationList.querySelector('.editorial-notifications__status').textContent = notifications.length > 0 ? `${notifications.length} à lire` : 'À jour';
      notificationList.querySelector('.editorial-notifications__empty').style.display = notifications.length === 0 ? '' : 'none';
    });

    lists.forEach((list) => {
      list.querySelectorAll('.notificationItem').forEach((item) => item.remove());
      notifications.forEach((notification) => renderNotification(list, notification));
    });
  } finally {
    setTimeout(refresh, 10000);
  }
};

if (lists.length > 0) {
  // Marks the notification as read before opening its target
  lists.forEach((list) => list.addEventListener('click', async (event) => {
    const link = event.target.closest('.notificationItem a');

    if (!link) {
      return;
    }

    event.preventDefault();
    await request(route('app_api_notification_read', { id: link.dataset.id }), { method: 'PATCH' });
    window.location.replace(link.dataset.target);
  }));

  refresh();
}
