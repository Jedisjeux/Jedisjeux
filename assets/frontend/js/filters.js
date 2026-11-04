import { route } from './http.js';

// Taxon and award filters of the lists: the selected value is part of the route, not of the query string
const filters = [
  { container: '#article-filters', select: '#criteria_mainTaxon_mainTaxon', value: 'taxon', route: 'app_frontend_article_index_by_taxon', skipOnGamePage: true },
  { container: '#topic-filters', select: '#criteria_mainTaxon_mainTaxon', value: 'taxon', route: 'app_frontend_topic_index_by_taxon', skipOnGamePage: true },
  { container: '#year-award-filters', select: '#criteria_award', value: 'award', route: 'app_frontend_year_award_index_by_award' },
  {
    container: '#person-filters',
    select: '#criteria_zone_mainTaxon',
    value: 'taxon',
    skipOnGamePage: true,
    url: (slug) => {
      const parameters = { 'criteria[role]': document.getElementById('criteria_role')?.value ?? '' };

      return slug ? route('app_frontend_person_index_by_taxon', { ...parameters, slug }) : route('app_frontend_person_index', parameters);
    },
  },
];

filters.forEach((filter) => {
  const container = document.querySelector(filter.container);
  const select = document.querySelector(filter.select);

  if (!container || !select) {
    return;
  }

  select.value = container.dataset[filter.value] ?? select.value;

  // The game page lists its articles and topics with the regular query string filters
  if (filter.skipOnGamePage && window.location.href.includes('/jeu-de-societe/')) {
    return;
  }

  container.querySelector('form')?.addEventListener('submit', (event) => {
    event.preventDefault();
    window.location = filter.url ? filter.url(select.value) : route(filter.route, { slug: select.value });
  });
});
