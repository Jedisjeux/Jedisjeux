(function ($) {
    'use strict';

    $(document).ready(function () {
        var $thumbsContainer = $('.img-carousel-thumbs');

        $('.owl-carousel').each(function () {
            var $slider = $(this);

            $slider.owlCarousel({
                loop: true,
                nav: false,
                items: 1,
                dots: true,
                autoplay:true,
                autoplayTimeout:5000,
                autoplayHoverPause:true,
                smartSpeed:800
            });

            $('a', $thumbsContainer).click(function (event) {
                event.preventDefault();

                var position = $('a', $thumbsContainer).index(this);
                $slider.trigger('to.owl.carousel', [position]);
            });
        });
    });

})(jQuery);
