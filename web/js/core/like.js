$(function() {

    $('.likeButton').click(function() {
        var $likeButton = $(this);
        var $form = $likeButton.closest('form');
        var userReview = $('.userReview', $form).val();

        var like = 0;
        if ($likeButton.hasClass('likeIt')) {
            like = 1;
        }

        $('.like', $form).val(like);

        $.post($form.attr('action'), $form.serialize())
            .done(function (response) {

                $likeButton
                    .siblings()
                    .removeClass('btn-yellow')
                    .addClass('btn-default');

                $likeButton
                    .removeClass('btn-default')
                    .addClass('btn-yellow');

                $('.nbLikes[data-user-review=' + userReview + ']').html(response.nbLikes);
                $('.nbDislikes[data-user-review=' + userReview + ']').html(response.nbDislikes);
            });
    });
});