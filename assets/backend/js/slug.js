// Generates the product and taxon slugs from their names, unless the slug field is locked
const updateSlug = async (nameInput, slugInput) => {
  if (slugInput.readOnly) {
    return;
  }

  const url = new URL(slugInput.dataset.url, window.location.origin);
  url.searchParams.set('name', nameInput.value);

  const locale = nameInput.closest('[data-locale]');
  if (locale) {
    url.searchParams.set('locale', locale.dataset.locale);
  }

  const parent = slugInput.dataset.parent || document.querySelector('#sylius_taxon_parent')?.value;
  if (parent) {
    url.searchParams.set('parentId', parent);
  }

  const response = await fetch(url, { headers: { Accept: 'application/json' } });
  slugInput.value = (await response.json()).slug;
};

document.addEventListener('input', (event) => {
  const nameInput = event.target;

  if (!nameInput.matches('[name*="sylius_product[translations]"][name*="[name]"], [name*="sylius_taxon[translations]"][name*="[name]"]')) {
    return;
  }

  const slugInput = nameInput.closest('[data-locale], form').querySelector('[name*="[slug]"][data-url]');

  if (!slugInput) {
    return;
  }

  clearTimeout(nameInput.slugTimeout);
  nameInput.slugTimeout = setTimeout(() => updateSlug(nameInput, slugInput), 1000);
});

document.addEventListener('click', (event) => {
  const button = event.target.closest('.toggle-product-slug-modification, .toggle-taxon-slug-modification');

  if (!button) {
    return;
  }

  event.preventDefault();

  const slugInput = button.parentElement.querySelector('input');
  slugInput.readOnly = !slugInput.readOnly;
  button.classList.toggle('active', !slugInput.readOnly);
});
