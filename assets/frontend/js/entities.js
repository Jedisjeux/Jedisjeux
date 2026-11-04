import { escapeHtml, request, route } from './http.js';

// Game cards embedded in the BBCode contents: <div class="entity" data-entity="game">product code</div>
const icon = (name) => document.getElementById(`app-entity-icon-${name}`)?.innerHTML ?? '';

const attribute = (iconName, content) => `
  <li>
    <p class="pull-left bg-color square-2 rounded-2 white">${icon(iconName)}</p>
    <p class="game-attribute">${content}</p>
  </li>`;

const taxonLinks = (taxons) => taxons
  .map((taxon) => `<a href="${escapeHtml(route('sylius_frontend_product_index_by_taxon', { permalink: taxon.permalink }))}">${escapeHtml(taxon.name)}</a>`)
  .join(', ');

const renderGame = (product) => `
  <div class="img-box-5">
    <div class="container">
      <div class="img-box-5-item">
        <div class="row">
          <div class="col-md-4">
            <div class="img-box-5-img">
              <a href="${escapeHtml(route('sylius_frontend_product_show', { slug: product.slug }))}">
                <img class="img-responsive img-thumbnail" src="${escapeHtml(product.image?.default)}" alt="${escapeHtml(product.name)}">
              </a>
            </div>
          </div>
          <div class="col-md-8">
            <div class="img-box-5-content">
              <div class="container">
                <div class="row"><h4>${escapeHtml(product.name)}</h4></div>
                ${product.short_description === undefined ? '' : `
                <div class="row">
                  <div class="quote-one"><div class="row"><div class="quote-one-item">
                    <span class="color">“</span>
                    <div class="quote-one-right"><div class="content">${product.short_description}</div></div>
                  </div></div></div>
                </div>`}
              </div>
              <div class="container">
                <div class="row">
                  <ul class="list-unstyled">
                    ${attribute('user', `${escapeHtml(product.min_player_count)} à ${escapeHtml(product.max_player_count)} joueurs`)}
                    ${attribute('child', `à partir de ${escapeHtml(product.min_age)} ans`)}
                    ${product.min_duration === undefined ? '' : attribute('clock-o', `${escapeHtml(product.min_duration)} minutes`)}
                    ${product.mechanisms?.length ? attribute('cog', taxonLinks(product.mechanisms)) : ''}
                    ${product.themes?.length ? attribute('picture-o', taxonLinks(product.themes)) : ''}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>`;

document.querySelectorAll('.entity[data-entity="game"]').forEach(async (entity) => {
  const product = await request(route('sylius_api_product_show', { code: entity.textContent.trim() }));
  entity.innerHTML = renderGame(product);
});
