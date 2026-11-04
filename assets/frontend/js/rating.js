import { appendFlash, escapeHtml, request, route } from './http.js';

// Star ratings: <div class="rating" data-max-value="10" data-rate-value="7" data-update-input-field-name="input_id">,
// the "rate" ones save the rating of the current user through the API
const starIcon = document.getElementById('app-rating-star')?.innerHTML ?? '★';

const saveRating = async (element, rating) => {
  const { productId } = element.dataset;
  const update = Number(element.dataset.rateValue) > 0;

  try {
    const review = await request(route(update ? 'sylius_api_product_review_rating_update' : 'sylius_api_product_review_rating_create', { productId }), {
      method: update ? 'PUT' : 'POST',
      data: { rating },
      loginOnUnauthorized: true,
    });

    element.dataset.rateValue = rating;
    appendFlash('Votre note a bien été enregistrée.');

    // The review button now edits the review created with this rating
    const reviewButton = document.getElementById('productReviewButton');
    if (!update && review?.id && reviewButton) {
      reviewButton.href = route('sylius_frontend_product_review_update', { slug: reviewButton.dataset.productSlug, id: review.id });
    }
  } catch (response) {
    const errors = await response.json?.().catch(() => null);
    Object.values(errors?.errors?.errors ?? {}).forEach((error) => {
      document.querySelector('div.panel-body')?.insertAdjacentHTML('afterbegin', `<span class="help-block form-error">${escapeHtml(error)}</span>`);
    });
  }
};

const initRating = (element) => {
  const max = Number(element.dataset.maxValue) || 5;
  const readonly = element.dataset.readonly === 'true';
  const input = document.getElementById(element.dataset.updateInputFieldName || '');
  let value = Number(element.dataset.rateValue) || 0;

  element.innerHTML = Array.from({ length: max }, (_, index) => (readonly
    ? `<span class="rating__star">${starIcon}</span>`
    : `<button type="button" class="rating__star" aria-label="${index + 1} / ${max}">${starIcon}</button>`)).join('');
  const stars = Array.from(element.children);

  // Same colors as the former rater plugin: the selected stars get the theme color
  const paint = (count) => stars.forEach((star, index) => {
    star.classList.toggle('is-selected', index < count);
    star.classList.toggle('text-default', index < count);
  });
  paint(value);

  if (readonly) {
    return;
  }

  stars.forEach((star, index) => {
    star.addEventListener('mouseenter', () => paint(index + 1));
    star.addEventListener('focus', () => paint(index + 1));
    star.addEventListener('click', () => {
      value = index + 1;
      paint(value);

      if (input) {
        input.value = value;
      }

      element.setAttribute('aria-valuenow', value);

      if (element.classList.contains('rate')) {
        saveRating(element, value);
      }
    });
  });

  element.addEventListener('mouseleave', () => paint(value));
  element.addEventListener('focusout', () => paint(value));
  element.setAttribute('role', 'group');
};

document.querySelectorAll('.rating[data-max-value]').forEach(initRating);
