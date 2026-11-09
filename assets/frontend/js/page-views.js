import { route } from './http.js';

// Counts the views of the articles, games and topics
const element = document.getElementById('page-views');

if (element) {
  const { resource, id } = element.dataset;
  const url = route('app_api_view_count_increment', { resource, id });

  if (!navigator.sendBeacon?.(url)) {
    fetch(url, { method: 'POST', keepalive: true }).catch(() => {});
  }
}
