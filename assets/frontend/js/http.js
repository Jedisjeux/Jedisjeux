// Small fetch wrapper for the JSON API: sends form encoded data, and redirects to the login page when an action needs a session
export async function request(url, { method = 'GET', data, loginOnUnauthorized = false } = {}) {
  const response = await fetch(url, {
    method,
    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: data ? new URLSearchParams(data) : undefined,
  });

  if (response.status === 401 && loginOnUnauthorized) {
    window.location.replace('/login');
  }

  if (!response.ok) {
    throw response;
  }

  const content = await response.text();

  return content === '' ? null : JSON.parse(content);
}

// Generates a route with FOSJsRoutingBundle, loaded as a classic script before the modules run
export const route = (name, parameters = {}) => window.Routing.generate(name, parameters);

export const escapeHtml = (text) => String(text ?? '').replace(/[&<>"']/g, (char) => `&#${char.charCodeAt(0)};`);

export const onReady = (callback) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback);
  } else {
    callback();
  }
};

export function appendFlash(message, selector = '#flashes') {
  const holder = document.querySelector(selector);

  if (holder) {
    holder.innerHTML = `<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">×</a>${escapeHtml(message)}</div>`;
  }
}
