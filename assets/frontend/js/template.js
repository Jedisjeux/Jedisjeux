import './../plugins/magnific-popup/jquery.magnific-popup.min';
import './../plugins/waypoints/jquery.waypoints.min';
import './../plugins/waypoints/sticky.min';
import './../plugins/slick/slick.min';

(function($) {

	// USE STRICT
	"use strict";

	$(document).ready(function() {

		//Show dropdown on click
		//-----------------------------------------------
		$('.header [data-toggle=dropdown]').on('click', function(event) {
			// Avoid following the href location when clicking
			event.preventDefault();

			// Avoid having the menu to close when clicking
			event.stopPropagation();

			// close all the siblings
			$(this).parent().siblings().removeClass('show');
			$(this).parent().siblings().find('.dropdown-menu').removeClass('show');

			// close all the submenus of siblings
			$(this).parent().siblings().find('[data-toggle=dropdown]').parent().removeClass('show');

			// opening the one you clicked on
			$(this).parent().toggleClass('show');
			$(this).siblings('.dropdown-menu').toggleClass('show');
		});

		//Slick carousel with thumbs
		//-----------------------------------------------
		if ($('.slick-carousel').length>0) {
			$('.slick-carousel.content-slider-with-thumbs').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				fade: true,
				asNavFor: '.slick-carousel.content-slider-thumbs'
			});
			$('.slick-carousel.content-slider-thumbs').slick({
				slidesToShow: 4,
				slidesToScroll: 1,
				asNavFor: '.slick-carousel.content-slider-with-thumbs',
				arrows: false,
				focusOnSelect: true
			});
		};

		// Fixed header
		//-----------------------------------------------
		if (($(".header.fixed.fixed-desktop").length > 0) && ($(window).width() > 1000)) {
			(function(){
				var sticky = new Waypoint.Sticky({
					element: $('.header-container .header.fixed'),
					stuckClass: 'object-visible',
					handler: function(direction) {
						$('body').toggleClass('fixed-header-on');
					},
					offset: -1
				});
			})();
		};

		// Magnific popup
		//-----------------------------------------------
		if (($(".popup-img").length > 0) || ($(".popup-iframe").length > 0) || $(".slick-carousel--popup-img").length > 0) {
			$(".popup-img").magnificPopup({
				type:"image",
				gallery: {
					enabled: true,
				}
			});
			if ($(".slick-carousel--popup-img").length > 0) {
				$(".slick-carousel").each(function() {
					$(this).find(".slick-slide:not(.slick-cloned) .slick-carousel--popup-img").magnificPopup({
						type:"image",
						gallery: {
							enabled: true,
						}
					});
				});
			}
			$('.popup-iframe').magnificPopup({
				disableOn: 700,
				type: 'iframe',
				preloader: false,
				fixedContentPos: false
			});
		};

		// Animations
		//-----------------------------------------------
		if ($("[data-animation-effect]").length>0) {
			$("[data-animation-effect]").each(function() {
				var waypoints = $(this).waypoint(function(direction) {
					var animatedObject;
					animatedObject = $(this.element);
					animatedObject.addClass('animated object-visible ' + animatedObject.attr("data-animation-effect"));
					this.destroy();
				},{
					offset: '90%'
				});
			});
		};

		//Scroll totop
		//-----------------------------------------------
		$(window).scroll(function() {
			if($(this).scrollTop() != 0) {
				$(".scrollToTop").addClass("fadeToTop");
				$(".scrollToTop").removeClass("fadeToBottom");
			} else {
				$(".scrollToTop").removeClass("fadeToTop");
				$(".scrollToTop").addClass("fadeToBottom");
			}
		});

		$(".scrollToTop").click(function() {
			$("body,html").animate({scrollTop:0},800);
		});

		//Modal
		//-----------------------------------------------
		if($(".modal").length>0) {
			$(".modal").each(function() {
				$(".modal").prependTo( "body" );
			});
		}

	});

})(jQuery);
