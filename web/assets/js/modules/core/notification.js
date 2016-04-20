$(function () {

    var $notificationBlock = $('#notificationBlock');

    if ($notificationBlock.length > 0) {

        var $notificationItemClone = $('.notificationItem', $notificationBlock).clone();

        function readNotification($a) {
            $.ajax({
                url: Routing.generate('app_api_notification_read', {'id': $a.attr('data-id')}),
                type: 'PATCH',
                success: function() {
                    refresh();
                },
                error: function (xhr) {
                    if(xhr.status==403) {
                        //handle error
                        window.location.replace(Routing.generate('fos_user_security_login'));
                    }
                }
            });
        }

        function refresh() {
            $.get(Routing.generate('app_api_notification_index'), function (response) {
                $('.notificationItem', $notificationBlock).remove();

                $.each(response, function (index, entity) {
                    var $item = $notificationItemClone.clone();

                    $('.notificationMessage', $item).html(entity.message);
                    $('.notificationA', $item).attr('data-id', entity.id);
                    $('.notificationContainer', $notificationBlock).append($item);

                    $('.notificationA', $item).click(function(event) {
                        event.preventDefault();
                        readNotification($(this));
                    });
                });

                if (response.length > 0) {
                    $('.badge').show().html(response.length);
                } else {
                    $('.badge').hide();
                }
            });

            setTimeout(refresh, 10000);
        }

        refresh();
    }
});