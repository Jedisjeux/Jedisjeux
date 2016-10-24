$(function () {
  'use strict';

  $(document).ready(function () {

    var $pageViews = $("#page-views")


    if ($pageViews.length > 0) {

      var pagePath = $pageViews.data('page-path');

      $.ajax({
        type: "GET",
        url: Routing.generate('app_api_page_views_show', {'pagePath': pagePath}),
        success: function (results) {
          updatePageViews(results.view_count);

        },
        error: function (xhr, textStatus, errorThrown) {

        }
      });

    }

    function updatePageViews(viewCount) {

      var updatePath = null;

      if ('product' === $pageViews.data('object')) {
        updatePath = 'sylius_api_product_view_count_update';
      }

      if (updatePath) {
        $.ajax({
          type: "PATCH",
          url: Routing.generate(updatePath, {'id': $pageViews.data('id')}),
          data: {
            'viewCount': viewCount
          }
        });
      }

    }

  });
});