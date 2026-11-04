// Adds and removes the items of the collection form types rendered by frontend/form/horizontal.html.twig
const nextIndex = new WeakMap();

document.addEventListener('click', (event) => {
  const addButton = event.target.closest('[data-form-collection="add"]');

  if (addButton) {
    const collection = addButton.closest('[data-form-type="collection"]');
    const list = collection?.querySelector('[data-form-collection="list"]');

    if (!list || !collection.dataset.prototype) {
      return;
    }

    event.preventDefault();

    const index = nextIndex.get(collection) ?? list.children.length;
    list.insertAdjacentHTML('beforeend', collection.dataset.prototype.replace(new RegExp(collection.dataset.prototypeName || '__name__', 'g'), index));
    nextIndex.set(collection, index + 1);

    return;
  }

  const deleteButton = event.target.closest('[data-form-collection="delete"]');

  if (deleteButton) {
    event.preventDefault();
    deleteButton.closest('[data-form-collection="item"]')?.remove();
  }
});
