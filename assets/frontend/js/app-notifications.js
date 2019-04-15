(function ($) {
    'use strict';

    var title = document.title;
    var $element;

    $.fn.extend({
        notifications: function () {
            $element = $(this);

            refresh();
        }
    });

    function refresh() {
        $('.notificationItem', $element).remove();

        $.get($element.data('url'), function(response) {
            updateTitleWithNotificationCount(title, response.length);

            if (response.length > 0) {
                $('.badge-count').show().html(response.length);
            } else {
                $('.badge-count').hide();
            }

            $.each(response, function(index, notification) {
                renderNotification(notification);
            });
        });

        $element.on(
            'click',
            '.notificationItem a',
            readNotification
        );

        setTimeout(refresh, 10000);
    }

    function renderNotification(notification) {
        var prototype = $element.data('prototype');

        prototype = prototype.replace(
            /__id__/g,
            notification.id
        );

        prototype = prototype.replace(
            /__target_url__/g,
            notification.target
        );

        prototype = prototype.replace(
            /__message__/g,
            notification.message
        );

        var imagePath;

        if (notification.authors.length > 0 && typeof notification.authors[0].avatar !== 'undefined') {
            imagePath = notification.authors[0].avatar.thumbnail;
        } else {
            imagePath = $element.data('placeholder');
        }

        prototype = prototype.replace(
            /__image_path__/g,
            imagePath
        );

        $element.append(prototype);
    }

    function readNotification(event) {
        event.preventDefault();

        var id = $(event.currentTarget).data('id');
        var target = $(event.currentTarget).data('target');

        $.ajax({
            url: Routing.generate('app_api_notification_read', {'id': id}),
            type: 'PATCH',
            success: function () {
                window.location.replace(target);
            }
        });
    }

    function updateTitleWithNotificationCount(title, notificationCount) {
        if (notificationCount === 0) {
            document.title = title;
            return;
        }

        // this regex will test if the document title already has a notification count in it, e.g. (1) My Document
        if (/\([\d]+\)/.test(title)) {
            // we will split the title after the first bracket
            title = title.split(') ');
            // get the first part of the splitted string and save it - this will be the count of the unseen notifications in our document string
            var notifications = title[0].substring(1);

            // only proceed when the notification count is difference to our ajax request
            if (notifications === 0) {
                return;
            } else {
                // else update the title with the new notification count
                document.title = '(' + notificationCount + ') ' + title[1];
            }
        }
        // when the current document title does not contain any notification count, just update it
        else {
            document.title = '(' + notificationCount + ') ' + title;
        }
    }

})(jQuery);
