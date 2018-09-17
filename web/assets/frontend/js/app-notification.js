$(function () {

  var title = document.title;
  var $notificationBlock = $('#notificationBlock');

  if ($notificationBlock.length > 0) {

    var $notificationItemClone = $('.notificationItem', $notificationBlock).clone();

    function readNotification($a) {
      $.ajax({
        url: Routing.generate('app_api_notification_read', {'id': $a.attr('data-id')}),
        type: 'PATCH',
        success: function () {
          window.location.replace($a.attr('data-target'));
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
          $('.notificationA', $item).attr('data-target', entity.target);
          $('.notificationContainer', $notificationBlock).append($item);

          if (entity.authors.length > 0 && typeof entity.authors[0].avatar !== 'undefined') {
            $('.notificationA img', $item).attr('src', entity.authors[0].avatar.thumbnail);
          }

          $('.notificationA', $item).click(function (event) {
            event.preventDefault();
            readNotification($(this));
          });
        });

        if (response.length > 0) {
          $('.badge-count').show().html(response.length);
        } else {
          $('.badge-count').hide();
        }

        updateTitleWithNotificationCount(title, response.length);
      });

      setTimeout(refresh, 10000);
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


    refresh();
  }
});