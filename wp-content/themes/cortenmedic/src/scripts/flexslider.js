import * as Utils from './utils';

(function($, window, document, undefined) {
  'use strict';

  let slider = $('.flexslider'),
    interval = slider.attr('data-slide-interval'),
    effect = slider.attr('data-slide-effect'),
    caption = slider.find('.flex-caption'),
    image = slider.find('img'),
    arrowsType = slider.attr('data-arrows-type') || 'chevron';

  const animateCaption = (caption, opacity, speed = 500) => {
    caption.stop().animate({
      opacity: opacity
    }, speed);
  }

  slider.flexslider({
    animation: effect,
    slideshowSpeed: interval,
    direction: 'horizontal',
    animationSpeed: 1000,
    reverse: false,
    controlNav: true,
    pauseOnHover: true,
    directionNav: true,
    prevText: '<i class="fa fa-' + arrowsType + '-left"></i>',
    nextText: '<i class="fa fa-' + arrowsType + '-right"></i>',
    start: function() {
      animateCaption(caption, 1);
    },
    before: function(slider) {
      animateCaption(caption, 0, 200);
    },
    after: function(slider) {
      animateCaption(caption, 1);
    }
  });

})(jQuery, window, document);