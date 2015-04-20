$(document).ready(function () {
    $( document ).on( 'click','.likeButton',function() {
        console.log('local el loco');
        var $likeButton = $(this);
        var $form = $likeButton.closest('form');
        var userReview = $('.userReview', $form).val();
        var comment = $('.comment', $form).val();

        var like = 0;
        if ($likeButton.hasClass('likeIt')) {
            like = 1;
        }

        $('.like', $form).val(like);

        $.post($form.attr('action'), $form.serialize())
            .done(function (response) {

                /**
                 * disable active state on other buttons
                 */
                $likeButton
                    .siblings()
                    .removeClass('btn-yellow')
                    .addClass('btn-default');

                /**
                 * enable active state on clicked button
                 */
                $likeButton
                    .removeClass('btn-default')
                    .addClass('btn-yellow');

                /**
                 * update nbLikes and nbDislikes on userReview
                 */
                console.log(userReview);
                if(userReview)
                {
                    $('.nbLikes[data-user-review=' + userReview + ']').html(response.nbLikes);
                    $('.nbDislikes[data-user-review=' + userReview + ']').html(response.nbDislikes);
                }

                /**
                 * update nbLikes and nbDislikes on comment
                 */
                if(comment)
                {
                    $('.nbLikes[data-comment=' + comment + ']').html(response.nbLikes);
                    $('.nbDislikes[data-comment=' + comment + ']').html(response.nbDislikes);
                }

            });
    });
});