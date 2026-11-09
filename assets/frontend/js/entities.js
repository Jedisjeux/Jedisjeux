import { escapeHtml, request, route } from './http.js';

// Game cards embedded in the contents: <div class="entity" data-entity="game">product code</div>
const icon = (name) => document.getElementById(`app-entity-icon-${name}`)?.innerHTML ?? '';

// Plain text of the HTML short description, cut on a word
const summary = (html, length = 220) => {
  const text = (new DOMParser().parseFromString(html ?? '', 'text/html').body.textContent ?? '').replace(/\s+/g, ' ').trim();

  return text.length > length ? `${text.slice(0, text.lastIndexOf(' ', length))}…` : text;
};

const playerCount = ({ min_player_count: min, max_player_count: max }) => {
  if (!max) {
    return `à partir de ${min} joueur${min > 1 ? 's' : ''}`;
  }

  return min === max ? `${min} joueur${min > 1 ? 's' : ''}` : `${min} à ${max} joueurs`;
};

const duration = ({ min_duration: min, max_duration: max }) => `${min}${max && max !== min ? ` - ${max}` : ''} min`;

const fact = (iconName, content) => `<li>${icon(iconName)}${content}</li>`;

const taxonLinks = (taxons) => taxons
  .map((taxon) => `<a href="${escapeHtml(route('sylius_frontend_product_index_by_taxon', { permalink: taxon.permalink }))}">${escapeHtml(taxon.name)}</a>`)
  .join(', ');

const renderGame = (product) => {
  const path = escapeHtml(route('sylius_frontend_product_show', { slug: product.slug }));
  const description = summary(product.short_description);
  const taxons = [...(product.mechanisms ?? []), ...(product.themes ?? [])];

  return `
    <article class="editorial-game-embed">
      <a class="editorial-game-embed__image" href="${path}" aria-label="${escapeHtml(product.name)}">
        ${product.image?.default ? `<img src="${escapeHtml(product.image.default)}" alt="${escapeHtml(product.name)}" loading="lazy">` : ''}
      </a>
      <div class="editorial-game-embed__body">
        <span class="editorial-game-embed__eyebrow">Le jeu</span>
        <a class="editorial-game-embed__title" href="${path}">${escapeHtml(product.name)}</a>
        ${description ? `<div class="editorial-game-embed__description">${escapeHtml(description)}</div>` : ''}
        <ul class="editorial-game-embed__facts">
          ${product.min_player_count ? fact('user', escapeHtml(playerCount(product))) : ''}
          ${product.min_age ? fact('age', `${escapeHtml(product.min_age)}+`) : ''}
          ${product.min_duration ? fact('clock', escapeHtml(duration(product))) : ''}
        </ul>
        ${taxons.length ? `<div class="editorial-game-embed__taxons">${icon('tag')}<span>${taxonLinks(taxons)}</span></div>` : ''}
      </div>
    </article>`;
};

document.querySelectorAll('.entity[data-entity="game"]').forEach(async (entity) => {
  try {
    const product = await request(route('sylius_api_product_show', { code: entity.textContent.trim() }));
    entity.innerHTML = renderGame(product);
  } catch {
    // An unknown game code leaves no stray code in the content
    entity.remove();
  }
});
