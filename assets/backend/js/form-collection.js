// Adds and removes the items of the collection form types rendered by backend/form/theme.html.twig
document.addEventListener('click', (event) => {
  const addButton = event.target.closest('[data-form-collection="add"]');

  if (addButton) {
    event.preventDefault();

    const collection = addButton.closest('[data-form-type="collection"]');
    const key = addButton.dataset.prototypeKey;
    const prototype = collection.querySelector(key
      ? `template[data-form-collection="prototype"][data-prototype-key="${key}"]`
      : 'template[data-form-collection="prototype"]');
    const list = collection.querySelector('[data-form-collection="list"]');
    const index = Math.max(-1, ...Array.from(list.children).map((item) => Number(item.dataset.formCollectionIndex) || 0)) + 1;
    const name = new RegExp(prototype.dataset.prototypeName, 'g');

    list.insertAdjacentHTML('beforeend', prototype.innerHTML.replace(name, index));
    list.lastElementChild.dataset.formCollectionIndex = index;
    list.lastElementChild.dispatchEvent(new CustomEvent('app:form-collection:item-added', { bubbles: true }));

    return;
  }

  const deleteButton = event.target.closest('[data-form-collection="delete"]');

  if (deleteButton) {
    event.preventDefault();
    deleteButton.closest('[data-form-collection="item"]').remove();
  }
});
