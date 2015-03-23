$(function() {

    $('.likeButton').click(function() {
        var that = this;
        var $form = $(this).closest('form');
        var userReview = $('.userReview', $form).val();

        var like = 0;
        if ($(this).hasClass('likeIt')) {
            like = 1;
        }

        $('.like', $form).val(like);

        $.post($form.attr('action'), $form.serialize())
            .done(function (response) {

                $(that)
                    .siblings()
                    .removeClass('btn-yellow')
                    .addClass('btn-default');

                $(that)
                    .removeClass('btn-default')
                    .addClass('btn-yellow');

                $('.nbLikes').each(function() {
                    if (userReview === $(this).attr('data-user-review')) {
                        $(this).html(response.nbLikes);
                    }
                });

                $('.nbUnlikes').each(function() {
                    if (userReview === $(this).attr('data-user-review')) {
                        $(this).html(response.nbUnlikes);
                    }
                });
            });
    });
});