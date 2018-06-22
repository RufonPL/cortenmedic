(function($, window, document, undefined) {
  'use strict';

  const defaults = {
    margin: 0,
    nav: true,
    dots: false,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplaySpeed: 1000,
    navSpeed: 1000,
    dotsSpeed: 1000,
    touchDrag: true,
    autoplayHoverPause: true,
    pullDrag: true,
    mouseDrag: true,
    callbacks: true,
  }

  const widget_simple_carousel = function() {
    const carousel = $('.widget-carousel-simple'),
      options = {
        loop: carousel.find('.widget-cs-item').size() > 1 ? true : false,
				controlsClass:'widget-cs-controls',
				navText: [
				"<i class='fa fa-angle-left'></i>",
				"<i class='fa fa-angle-right'></i>"
				],
				responsive:{
					0: {
						items: 1
					},
					480:{
						items:2
					},
					640: {
						items:3
					},
					768:{
						items:4
					}
				}
      },
      args = $.extend(defaults, options);

    carousel.owlCarousel(args);
  }
  
  const widget_posts_carousel = function() {
		const carousel = $('.widget-carousel-posts'),
			foundItems = carousel.find('.widget-pb-post').size(),
      options = {
				controlsClass:'widget-pb-controls',
				navText: [
				"<i class='fa fa-angle-left'></i>",
				"<i class='fa fa-angle-right'></i>"
				],
				responsive:{
					0: {
						items: 1
					},
					480: {
						items:2
					},
					640: {
						items:3
					},
					768: {
						items: 4,
        		loop: foundItems > 3 ? true : false,
					}
				}
      },
      args = $.extend(defaults, options);

    carousel.owlCarousel(args);
	}

	const product_gallery_carousel = function() {
		const carouselMain = $('#product-gallery-main'),
			optionsMain = {
				loop: false,
				nav: false,
				autoplay: false,
				responsive: {},
				items: 1,
				pullDrag: false,
				mouseDrag: true,
				touchDrag: false,
				animateOut: 'fadeOut',
				autoHeight: false
			},
			argsMain = $.extend(defaults, optionsMain);

		carouselMain.owlCarousel(argsMain);

		const carouselNav = $('#product-gallery-thumbnails'),
			itemsFound = carouselNav.find('.product-gallery-thumbnail').size(),
			optionsNav = {
				controlsClass:'product-gn-controls',
				nav: false,
				dots: true,
				navText: [
				"<i class='fa fa-angle-left'></i>",
				"<i class='fa fa-angle-right'></i>"
				],
				loop: false,
				autoplay: false,
				margin: 3,
				responsive:{
					0: {
						items: itemsFound > 2 ? 2 : itemsFound,
					},
					360: {
						items: itemsFound > 3 ? 3 : itemsFound,
					},
					540: {
						items: itemsFound > 4 ? 4 : itemsFound,
					},
					768: {
						items: itemsFound > 5 ? 5 : itemsFound,
					},
					1110: {
						items: itemsFound > 5 ? 5 : itemsFound,
					}
				}
			},
			argsNav = $.extend(defaults, optionsNav);

		carouselNav.on('initialized.owl.carousel changed.owl.carousel', function(event) {
			const that = $(this),
				item = that.find('.product-gallery-thumbnail');

			item.first().addClass('current');

			item.on('click', function() {
				var thumbnail = $(this),
					index = thumbnail.attr('data-gallery-nav-item');

				item.removeClass('current');
				thumbnail.addClass('current');
				
				carouselMain.trigger('to.owl.carousel', index, 500);
			});
		});
			
		carouselNav.owlCarousel(argsNav);
	}
	
	const widget_gallery_carousel = function() {
		const carouselMain = $('.widget-gallery-main-carousel'),
      optionsMain = {
				loop: true,
				nav: false,
				autoplay: false,
				responsive: {},
				items: 1
			},
      argsMain = $.extend(defaults, optionsMain);

		carouselMain.owlCarousel(argsMain);
		
		const carouselNav = $('.widget-gallery-nav-carousel'),
			itemsFound = carouselNav.find('.widget-gallery-nav-item').size(),
			optionsNav = {
				controlsClass:'widget-gn-controls',
				nav: true,
				navText: [
				"<i class='fa fa-angle-left'></i>",
				"<i class='fa fa-angle-right'></i>"
				],
				margin: 10,
				responsive:{
					0: {
						items: itemsFound > 2 ? 2 : itemsFound,
						loop: itemsFound > 2 ? true : false,
					},
					360: {
						items: itemsFound > 3 ? 3 : itemsFound,
						loop: itemsFound > 3 ? true : false,
					},
					540: {
						items: itemsFound > 4 ? 4 : itemsFound,
						loop: itemsFound > 4 ? true : false,
					},
					768: {
						items: itemsFound > 5 ? 5 : itemsFound,
						loop: itemsFound > 5 ? true : false,
					},
					1110: {
						items: itemsFound > 6 ? 6 : itemsFound,
						loop: itemsFound > 6 ? true : false,
					}
				}
      },
			argsNav = $.extend(defaults, optionsNav);

		carouselNav.on('initialized.owl.carousel changed.owl.carousel', function(event) {
			const that = $(this),
				item = that.find('.widget-gallery-nav-item');

			item.first().addClass('current');

			item.on('click', function() {
				var thumbnail = $(this),
					index = thumbnail.attr('data-gallery-nav-item');

				item.removeClass('current');
				thumbnail.addClass('current');
				
				carouselMain.trigger('to.owl.carousel', index, 500);
			});
		});
			
    carouselNav.owlCarousel(argsNav);
	}

  widget_simple_carousel();
	widget_posts_carousel();
	widget_gallery_carousel();
	product_gallery_carousel();

})(jQuery, window, document);