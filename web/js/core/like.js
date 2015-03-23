$(function() {

    $('.likeUserReviewButton').click(function() {
        var that = this;
        var userReview = $(that).attr('data-user-review');

        var like = 0;
        if ($(this).hasClass('likeIt')) {
            like = 1;
        }

        $.post(Routing.generate('user_review_like', {'id': userReview}), {
            'like': like
        })
            .done(function (response) {

                $(that)
                    .removeClass('btn-default')
                    .addClass('btn-yellow');

                $('.nbLikes').each(function() {
                    if (userReview === $(this).attr('data-user-review')) {
                        $(this).html(response.nbLikes);
                    }
                });

                $('.nbUnLikes').each(function() {
                    if (userReview === $(this).attr('data-user-review')) {
                        $(this).html(response.nbUnlikes);
                    }
                });
            });
    });
});