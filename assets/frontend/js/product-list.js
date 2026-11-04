import { appendFlash, escapeHtml, request, route } from './http.js';

// "Add to" menu of the game page: adds the game to the lists of the user, or to a new list
const form = document.getElementById('productListForm');
const newListForm = document.getElementById('newProductListForm');

const productId = form?.querySelector('input[name=productId]')?.value;
const findCheckbox = (code) => Array.from(form.querySelectorAll('input[data-code]')).find((input) => input.dataset.code === code);

const addListOnDom = (code, name) => {
  if (!findCheckbox(code)) {
    form.insertAdjacentHTML('beforeend', `<div class="list"><input id="${escapeHtml(code)}" type="checkbox" data-code="${escapeHtml(code)}"> <label for="${escapeHtml(code)}">${escapeHtml(name)}</label></div>`);
  }
};

const selectList = (code) => {
  const checkbox = findCheckbox(code);

  if (checkbox) {
    checkbox.checked = true;
  }
};

const addOrRemoveProduct = async (add, code) => {
  await request(route(add ? 'app_api_product_list_add_product' : 'app_api_product_list_remove_product', { code, productId }), {
    method: add ? 'POST' : 'DELETE',
    loginOnUnauthorized: true,
  });

  appendFlash(add ? 'Le jeu a bien été ajouté à votre liste.' : 'Le jeu a bien été supprimé de votre liste.');
};

if (form) {
  (async () => {
    (await request(route('app_api_product_list_index'), { loginOnUnauthorized: true }) ?? []).forEach((list) => addListOnDom(list.code, list.name));
    (await request(route('app_api_product_list_by_product', { productId }), { loginOnUnauthorized: true }) ?? []).forEach((list) => selectList(list.code));
  })();

  form.addEventListener('change', (event) => {
    if (event.target.matches('input[data-code]')) {
      addOrRemoveProduct(event.target.checked, event.target.dataset.code);
    }
  });

  newListForm?.addEventListener('submit', async (event) => {
    event.preventDefault();

    const name = newListForm.querySelector('input[name=name]').value;
    const list = await request(route('app_api_product_list_create'), { method: 'POST', data: { name }, loginOnUnauthorized: true });

    await addOrRemoveProduct(true, list.code);
    addListOnDom(list.code, name);
    selectList(list.code);
  });
}
