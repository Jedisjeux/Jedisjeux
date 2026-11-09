// Remote autocomplete for the resource autocomplete form types: the hidden input keeps the comma separated identifiers
const escapeHtml = (text) => String(text).replace(/[&<>"']/g, (char) => `&#${char.charCodeAt(0)};`);

const initAutocomplete = (container) => {
  if (container.dataset.autocompleteReady) {
    return;
  }

  container.dataset.autocompleteReady = '1';
  const { searchUrl, initUrl, choiceName, choiceValue, createUrl } = container.dataset;
  const multiple = container.dataset.multiple === '1';
  const hiddenInput = container.querySelector('input[type="hidden"]');
  const searchInput = container.querySelector('[data-autocomplete="search"]');
  const selection = container.querySelector('[data-autocomplete="selection"]');
  const results = container.querySelector('[data-autocomplete="results"]');
  let selected = [];

  const render = () => {
    hiddenInput.value = selected.map((item) => item.value).join(',');
    selection.innerHTML = selected.map((item) => `
      <span class="badge bg-primary-lt d-inline-flex align-items-center gap-1">
        ${escapeHtml(item.text)}
        <a href="#" class="btn-close btn-close-sm" data-autocomplete-remove="${escapeHtml(item.value)}" aria-label="Remove"></a>
      </span>`).join('');
  };

  const select = (item) => {
    selected = multiple ? selected.filter((current) => current.value !== item.value).concat(item) : [item];
    searchInput.value = '';
    results.classList.remove('show');
    render();
  };

  const toItem = (resource) => ({ value: String(resource[choiceValue]), text: resource[choiceName] });

  const fetchResources = async (url, params) => {
    const requestUrl = new URL(url, window.location.origin);
    Object.entries(params).forEach(([name, values]) => [].concat(values).forEach((value) => requestUrl.searchParams.append(name, value)));
    const response = await fetch(requestUrl, { headers: { Accept: 'application/json' } });

    return response.ok ? response.json() : [];
  };

  const showResults = (resources, phrase) => {
    const items = resources.map(toItem);
    const creatable = createUrl && phrase !== '' && !items.some((item) => item.text.toLowerCase() === phrase.toLowerCase());

    results.innerHTML = items.map((item) => `<a href="#" class="dropdown-item" data-value="${escapeHtml(item.value)}">${escapeHtml(item.text)}</a>`).join('')
      + (creatable ? `<a href="#" class="dropdown-item text-primary" data-autocomplete-create>+ ${escapeHtml(phrase)}</a>` : '');
    results.classList.toggle('show', items.length > 0 || creatable);
    results.items = items;
  };

  searchInput.addEventListener('input', () => {
    clearTimeout(searchInput.searchTimeout);
    const phrase = searchInput.value.trim();

    if (phrase.length < 2) {
      results.classList.remove('show');
      return;
    }

    searchInput.searchTimeout = setTimeout(async () => showResults(await fetchResources(searchUrl, { phrase }), phrase), 300);
  });

  results.addEventListener('click', async (event) => {
    event.preventDefault();
    const option = event.target.closest('.dropdown-item');

    if (!option) {
      return;
    }

    if (option.hasAttribute('data-autocomplete-create')) {
      const names = searchInput.value.trim().split(' ');
      const lastName = names.length > 1 ? names.slice(1).join(' ') : names[0];
      const firstName = names.length > 1 ? names[0] : '';
      const response = await fetch(createUrl, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: new URLSearchParams({ lastName, firstName }),
      });
      const person = await response.json();
      select({ value: String(person.id), text: searchInput.value.trim() });

      return;
    }

    select(results.items.find((item) => item.value === option.dataset.value));
  });

  selection.addEventListener('click', (event) => {
    const remove = event.target.closest('[data-autocomplete-remove]');

    if (remove) {
      event.preventDefault();
      selected = selected.filter((item) => item.value !== remove.dataset.autocompleteRemove);
      render();
    }
  });

  document.addEventListener('click', (event) => {
    if (!container.contains(event.target)) {
      results.classList.remove('show');
    }
  });

  const initialValues = hiddenInput.value.split(',').filter((value) => value !== '');

  if (initialValues.length > 0 && initUrl) {
    fetchResources(initUrl, { [`${choiceValue}[]`]: initialValues }).then((resources) => {
      selected = resources.map(toItem);
      render();
    });
  }
};

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-app-autocomplete]').forEach(initAutocomplete);
});

// Items added from a collection prototype, such as the blocks of an article
document.addEventListener('app:form-collection:item-added', (event) => {
  event.target.querySelectorAll('[data-app-autocomplete]').forEach(initAutocomplete);
});
