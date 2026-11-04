// The Bootstrap 4 components used by the templates (data-toggle and data-dismiss attributes), without jQuery

// Dropdowns
const closeDropdowns = (except = null) => {
  document.querySelectorAll('.dropdown-menu.show').forEach((menu) => {
    const parent = menu.parentElement;

    if (except && parent.contains(except)) {
      return;
    }

    menu.classList.remove('show');
    parent.classList.remove('show');
    parent.querySelector('[data-toggle="dropdown"]')?.setAttribute('aria-expanded', 'false');
  });
};

document.addEventListener('click', (event) => {
  const toggle = event.target.closest('[data-toggle="dropdown"]');

  if (toggle) {
    event.preventDefault();

    const parent = toggle.parentElement;
    const menu = parent.querySelector(':scope > .dropdown-menu') || toggle.nextElementSibling;
    const open = !parent.classList.contains('show');

    closeDropdowns(parent.parentElement?.closest('.dropdown-menu.show') ? parent : null);
    parent.classList.toggle('show', open);
    menu?.classList.toggle('show', open);
    toggle.setAttribute('aria-expanded', String(open));

    return;
  }

  // Clicks inside an open menu (forms, checkboxes) keep it open
  if (!event.target.closest('.dropdown-menu.show')) {
    closeDropdowns();
  }
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    closeDropdowns();
  }
});

// Tabs
document.addEventListener('click', (event) => {
  const tab = event.target.closest('[data-toggle="tab"]');

  if (!tab) {
    return;
  }

  event.preventDefault();

  const pane = document.querySelector(tab.getAttribute('href') || tab.dataset.target);

  if (!pane) {
    return;
  }

  tab.closest('.nav')?.querySelectorAll('[data-toggle="tab"]').forEach((link) => {
    link.classList.toggle('active', link === tab);
    link.setAttribute('aria-selected', String(link === tab));
  });

  pane.parentElement.querySelectorAll(':scope > .tab-pane').forEach((sibling) => {
    sibling.classList.toggle('active', sibling === pane);
    sibling.classList.toggle('show', sibling === pane);
  });
});

// Collapse
document.addEventListener('click', (event) => {
  const toggle = event.target.closest('[data-toggle="collapse"]');

  if (!toggle) {
    return;
  }

  event.preventDefault();

  const target = document.querySelector(toggle.dataset.target || toggle.getAttribute('href'));
  const open = !target?.classList.contains('show');

  target?.classList.toggle('show', open);
  toggle.classList.toggle('collapsed', !open);
  toggle.setAttribute('aria-expanded', String(open));
});

// Alerts
document.addEventListener('click', (event) => {
  const dismiss = event.target.closest('[data-dismiss="alert"]');

  if (dismiss) {
    event.preventDefault();
    dismiss.closest('.alert')?.remove();
  }
});

// Carousels: shows the next item every 5 seconds, the indicators select an item
const initCarousel = (carousel) => {
  const items = Array.from(carousel.querySelectorAll('.carousel-item'));
  const indicators = Array.from(carousel.querySelectorAll('[data-slide-to]'));
  let current = Math.max(0, items.findIndex((item) => item.classList.contains('active')));

  const show = (index) => {
    current = (index + items.length) % items.length;
    items.forEach((item, position) => item.classList.toggle('active', position === current));
    indicators.forEach((indicator, position) => indicator.classList.toggle('active', position === current));
  };

  indicators.forEach((indicator) => indicator.addEventListener('click', () => show(Number(indicator.dataset.slideTo))));

  if (items.length > 1) {
    setInterval(() => show(current + 1), 5000);
  }
};

document.querySelectorAll('.carousel[data-ride="carousel"]').forEach(initCarousel);
