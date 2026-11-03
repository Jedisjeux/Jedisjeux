// Moves the article blocks up and down, and keeps their position fields in sync
const recalculatePositions = (list) => {
  list.querySelectorAll(':scope > [data-form-collection="item"]').forEach((item, position) => {
    item.querySelectorAll('.position').forEach((input) => { input.value = position; });
  });
};

document.addEventListener('click', (event) => {
  const button = event.target.closest('.app-move-up, .app-move-down');

  if (!button) {
    return;
  }

  event.preventDefault();

  const block = button.closest('[data-form-collection="item"]');
  const sibling = button.classList.contains('app-move-up') ? block.previousElementSibling : block.nextElementSibling;

  if (!sibling) {
    return;
  }

  if (button.classList.contains('app-move-up')) {
    sibling.before(block);
  } else {
    sibling.after(block);
  }

  block.scrollIntoView({ behavior: 'smooth', block: 'center' });
  recalculatePositions(block.parentElement);
});
