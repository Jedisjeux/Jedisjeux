$(function () {
    'use strict';

    $(document).ready(function () {

        var $pageViews = $("#page-views");

        if ($pageViews.length > 0) {
            var pagePath = $pageViews.data('page-path');

            $.ajax({
                type: "GET",
                url: Routing.generate('app_api_page_views_show', {'pagePath': pagePath}),
                success: function (results) {
                    updatePageViews(results.view_count);
                }
            });
        }

        function updatePageViews(viewCount) {

            var updatePath = null;
            var object = $pageViews.data('object');

            if ('product' === object) {
                updatePath = 'sylius_api_product_view_count_update';
            } else if ('article' === object) {
                updatePath = 'app_api_article_view_count_update';
            } else if ('topic' === object) {
                updatePath = 'app_api_topic_view_count_update';
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
