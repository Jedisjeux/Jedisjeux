import { request, route } from './http.js';

// Counts the views of the articles, games and topics
const element = document.getElementById('page-views');
const updateRoutes = {
  article: 'app_api_article_view_count_update',
  product: 'sylius_api_product_view_count_update',
  topic: 'app_api_topic_view_count_update',
};

if (element) {
  (async () => {
    const { pagePath, object, id } = element.dataset;
    const results = await request(route('app_api_page_views_show', { pagePath }));
    const updateRoute = updateRoutes[object];

    if (updateRoute) {
      await request(route(updateRoute, { id }), { method: 'PATCH', data: { viewCount: results.view_count } });
    }
  })();
}
