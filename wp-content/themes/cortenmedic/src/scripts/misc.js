import * as Utils from './utils';

(function($, window, document, undefined) {
  'use strict';

  let productHeights = [];

  const pageLoader = function() {
    const loader = $('.page-loader');

    setTimeout( () => {
      loader.hide();
    }, 500);
  }

  const navbarDropdownParentLink = function() {
    const link = $('.navbar .dropdown > a');

    link.on('click', function() {
      location.href = this.href;
    });
  }

  const postsWidgetTitlesHeight = function() {
    const row = $('.widget-pb-posts');

    $.each(row, (index, element) => {
      const title = $(element).find('.widget-pb-post-title.widget-pb-pt-style2');

      title.css('min-height', '1px');
      const maxHeight = Utils.getMaxHeight(title);
      title.css('min-height', maxHeight);
    });
  }

  const widgetLists = function() {
    const list = $('.widget-lists .widget-list ul');

    $.each(list, (index, element) => {
      const that  = $(element),
        items     = that.find('li'),
        more      = that.parent().find('a.show-more'),
        height    = that.outerHeight();

      if( items.length > 5 ) {
        more.show();
        const heights = [];

        $.each(items, (index, item) => {
          if( index < 5 ) {
            heights.push( $(item).outerHeight() )
          }
        });

        const maxHeight = heights.reduce( (a,b) => {
          return a + b;
        });

        that.css({
          'height': maxHeight
        });

        more.on('click', function(e) {
          e.preventDefault();
          const btn = $(this);
          let slideHeight = height,
            btnText = Utils.localized('showmore');

          if( btn.hasClass('show-less') ) {
            slideHeight = maxHeight;
            btn.removeClass('show-less');
            btnText = Utils.localized('showmore');
          }else {
            slideHeight = height;
            btn.addClass('show-less');
            btnText = Utils.localized('showless');
          }
            
          that.stop().animate({
            'height': slideHeight
          }, 500, function() {
            btn.find('strong').text(btnText)
          });
        });
      }
    })
  }

  const fontResier = function() {    
    const htmlElements = $('body, h1, h2, h3, h4, h5, h6, .btn, p:not(.no-resize), ul, a, blockquote, span, button, input, select'),
      resizer = $('.font-size-item'),
      toResize = [];
    
    let newFontSize;
			
    $.each(htmlElements, (i, el) => {
      let element = $(el),
        fontSizeInit = parseFloat( element.css('font-size') );

      if( isNaN( fontSizeInit) ) return;

      toResize.push({
        element: element,
        size: fontSizeInit
      });
    });

    resizer.on('click', function() {
      const that = $(this),
        size = that.attr('id').slice(10);

      $.each(toResize, (i, el) => {
        let element = $(el.element),
          currentFontSize = parseFloat( element.css('font-size') );
          
        switch( size ) {
          case 'md':
            newFontSize = currentFontSize + 1;
            break;
          case 'lg':
            newFontSize = currentFontSize + 2;
            break;
          default:
            newFontSize = '';
            break;
        }
        
        element.css('font-size', newFontSize);
      });
    });
  }

  const languageSwitcher = function() {
    const switcher = $('.language-switcher'),
      select = switcher.find('select');

    select.addClass('selectpicker')
  }

  const postsSidebarHeight = function() {
    const left = $('.posts-index-left'),
      right = $('.posts-index-right'),
      leftHeight = left.outerHeight(),
      rightHeight = right.outerHeight();

    if( rightHeight < leftHeight ) {
      right.css('height', leftHeight);
    }else {
      right.css('height', '');
    }
  }

  const postsFilterAnchorScroll = function() {
    const filteron = Utils.localized('filteron');

    if( filteron != 1 ) return;

    const anchor = $('#posts-anchor');

    if( !anchor.length ) return;

    setTimeout( () => {
      let offset = anchor.offset().top;
      
      $('html, body').stop().animate({
        'scrollTop': offset
      }, 500);
    }, 200);
  }

  const postsPlacesDropdownSubmit = function() {
    const dropdown = $('#pplace');

    if( !dropdown.length ) return;

    dropdown.on('change', function() {
      const that = $(this),
        value = isNaN( parseInt( that.val() ) ) ? 0 : parseInt( that.val() ),
        url = Utils.localized('blogurl');

      if( value > 0 ) {
        window.location.replace( `${url}?filter=1&pp=${value}` );
      }
    })
  }

  const formAttachmentFileName = function() {
    const file = $('.form-control-file');

    file.on('change', function() {
      const that = $(this),
        filename = that[0].files[0].name;

      that.closest('.form-file-upload').find('.btn').text( filename );
    });
  }

  const careerBoxHoverBg = function() {
    const classes = ['colored', 'light'],
      box = $('.career-box');

    if( !box.length ) return;

    box.on('mouseenter', function() {
      let random = Math.floor( Math.random() * classes.length );
      const that = $(this),
        text = that.find('.career-box-text');

      text.addClass( classes[random] );
    }).on('mouseleave', function() {
      const that = $(this),
        text = that.find('.career-box-text');
      
      for( let i=0; i<classes.length; i++) {
        text.removeClass( classes[i] );
      }
    });
  }

  const careerStepsHeightEven = function() {
    const stepsBox = $('.pgs-box');

    if( !stepsBox.length ) return;

    const stepsBoxLeft = stepsBox.find('.pgs-box-left'),
      stepsBoxRight = stepsBox.find('.pgs-box-right'),
      stepsBoxColumns = stepsBox.find('.pgs-box-left, .pgs-box-right');

    stepsBoxColumns.css('min-height', '1px');

    if( Utils.isRwdSize('480') ) {
      stepsBoxColumns.css('min-height', '1px');
    }else {
      stepsBoxColumns.css('min-height', stepsBox.outerHeight());
    }
    
  }

  const servicesGroupsContentSlide = function() {
    const content = $('.sod-content-slide'),
      btn = $('.sod-slide-toggle');

    if( !content.length || !btn.length ) return;

    btn.on('click', () => {
      content.slideToggle();
    });
  }

  const accordionActive = function() {
    const pricelist = $('.pricelist-table-container, .widget-accordion');

    if( !pricelist.length ) return;

    let accordion = pricelist.find('.panel'),
      header      = accordion.find('.panel-heading');

    accordion.on('show.bs.collapse', function (event) {
      event.stopPropagation();

      const that = $(this),
        heading = that.find('.panel-heading').first();
        
      header.removeClass('active');
      heading.addClass('active');

      that.siblings().find('.collapse').collapse('hide');
      
      if( that.hasClass('inner-panel') ) {
        const parent = that.parent().closest('.panel');
        parent.find('.panel-heading').first().addClass('active');
      }else {
        accordion.find('.inner-panel .collapse').collapse('hide');
        heading.addClass('active');
      }
    });

    accordion.on('hide.bs.collapse', function (event) {
      event.stopPropagation();

      const that = $(this),
        headings = that.find('.panel-heading');
      headings.removeClass('active');
    });
  }

  const pricelistFilter = function() {
    const get = Utils.localized('pfilter');

    if( get === 'null' || !get ) return;
    
    const params = JSON.parse(get);

    if( params[0] > 0 ) {
      const group = params[0],
        groupAccordion = $(`#group-${group}`);
      
      if( groupAccordion.length ) {
        groupAccordion.collapse('show');
        
        if( params[1] > 0 ) {
          const service = params[1],
            serviceAccordion = $(`#group-${group}-service-${service}`);

          if( serviceAccordion.length ) {
            $(`#group-${group}-service-${service}`).collapse('show');

            $('html, body').stop().delay(500).animate({
              'scrollTop': serviceAccordion.offset().top - 100
            }, 500);
          }
        }
      }
    }
  }

  const doctorsPagingScroll = function() {
    const doctorspaging = Utils.localized('doctorspaging');

    if( doctorspaging != 1 ) return;

    const anchor = $('.doctors-list').find('.doctors-row').last();

    if( !anchor.length ) return;

    setTimeout( () => {
      let offset = anchor.offset().top;
      
      $('html, body').stop().animate({
        'scrollTop': offset - 50
      }, 500);
    }, 200);
  }

  const aboutSidesAdjust = function() {
    const group = $('.about-us-group');

    if( !group.length ) return;
      
    if( Utils.isRwdSize('768') ) {
      group.find('.col-sm-6').css('min-height', '1px');
    }else {
      for(let i=0; i<group.length; i++) {
        const side = $(group[i]).find('.col-sm-6');
        side.css('min-height', '1px');
        
        const  maxHeight = Utils.getMaxHeight(side);
        side.css('min-height', maxHeight);
      }
    }
  }

  const aboutEntryAdjust = function() {
    const group = $('.amp-entry');

    if( !group.length ) return;
      
    if( Utils.isRwdSize('768') ) {
      group.find('.amp-entry-side').css('min-height', '1px');
    }else {
      for(let i=0; i<group.length; i++) {
        const side = $(group[i]).find('.amp-entry-side');
        side.css('min-height', '1px');
        
        const  maxHeight = Utils.getMaxHeight(side);
        side.css('min-height', maxHeight);
      }
    }
  }

  const WidgetBoxContentAdjust = function() {
    //let group = Utils.isRwdSize('992') ? $('.widget-bc-wrap') : $('.wiget-bc-boxes');
    const group = $('.wiget-bc-boxes');

    if( !group.length ) return;
      
    if( Utils.isRwdSize('640') ) {
      group.find('.widget-bc-inner').css('min-height', '1px');
    }else {
      for(let i=0; i<group.length; i++) {
        const box = $(group[i]).find('.widget-bc-inner'),
          text = box.find('.widget-bc-text-inner'),
          textPadding = box.find('.widget-bc-text').css('padding-top');

        box.css('min-height', '1px');
        
        const  maxHeight = Utils.getMaxHeight(text);
        
        box.css('min-height', maxHeight + (parseFloat(textPadding) * 2) );
      }
    }
  }

  const accordionsScroll = function() {
    const list = $('.accordions-page').find('.accordions-sidebar-list'),
      accordions = $('.accordions-page-accordions');

    if( !list.length ) return;

    list.find('li a').on('click', function(e) {
      e.preventDefault();

      const item = $(this),
        href = item.attr('href'),
        target = accordions.find(href);

      if( !target.length ) return;

      const header = target.parent().find('.page-section-header, .widget-item-header');

      if( !header.length ) return;

      const headerHeight = header.outerHeight(true);
      
      $('html, body').animate({
        'scrollTop': target.offset().top - headerHeight
      }, 500);
    });
  }

  const productItem = function(enable = true) {
    const row = $('.products-row');

    if( !row.length ) {
      return;
    }

    $.each(row, function(index, el) {
      const that = $(el),
        elements = [
          '.product',
          '.product-inner',
          '.product-inner h6'
        ];
        
      for(let i=0; i<elements.length; i++) {
        const element = that.find(elements[i]);
        element.css('min-height', '1px');
        
        if( enable ) {
          setTimeout( () => {
            let maxHeight = Utils.getMaxHeight(element);

            maxHeight = Utils.isRwdSize(768) ? '1px' : maxHeight;

            element.css('min-height', maxHeight);
          }, 100);

          // if( productHeights.length < (elements.length * row.length) ) {
          //   productHeights.push(maxHeight);
          // }else {
          //   maxHeight = productHeights[elements.length * index + i];
          // }
          
        }
      }
    });
  }

  const priceFilterSubmitOnEnterKey = function() {
    const form = $('#products-price-filter-form');

    if( !form.length ) {
      return;
    }

    form.find('input').on('keydown', (e) => {
      if( e.keyCode == 13 || e.which == 13 || e.key == 'Enter' ) {
        form.submit();
      }
    });
  }

  const productHoverZindex = function() {
    const product = $('.product');

    let zindex = 1;
    
    if( !product.length) {
      return;
    }

    product.on('mouseenter', function() {
      const that = $(this);
      zindex += 1;
      
      that.css('z-index', zindex);

      setTimeout( () => {
        product.css('z-index', 1);
        that.css('z-index', zindex);
      }, 300);
    });
  }

  const productsDisplay = function() {
    const displayType = $('.products-display').find('i.fa'),
      product = $('.products-container .product')

    if( !displayType.length || !product.length ) {
      return;
    }
    
    displayType.on('click', function() {
      const that = $(this);

      displayType.removeClass('display-active');
      that.addClass('display-active');

      product.css('opacity', 0);
      setTimeout( () => {
        product.animate({
          opacity: 1
        }, 500);
      }, 300);

      if( that.hasClass('fa-list') ) {
        product.removeClass('grid-type');
        product.addClass('list-type');
        productItem(false);
      }else {
        product.removeClass('list-type');
        product.addClass('grid-type');
        productItem(true);
      }
    });
  }

  const shopOffers = function() {
    const row = $('.shop-offers');

    if( !row.length ) {
      return;
    }

    $.each(row, function(index, el) {
      const that = $(el),
        elements = [
          '.shop-offer-image',
          '.shop-offer-info h4',
          '.shop-offer-text'
        ];
        
      for(let i=0; i<elements.length; i++) {
        const element = that.find(elements[i]);
        element.css('min-height', '1px');
        
        const maxHeight = Utils.getMaxHeight(element);
        element.css('min-height', maxHeight);
      }
    });
  }

  const shopBox2 = function() {
    const elements = $('.shop-offer, .shop-box-howto'),
      box = $('.shop-box-howto');

    box.css('min-height', '1px');

    const maxHeight = Utils.getMaxHeight(elements);
    
    if( !Utils.isRwdSize(992) ) {
      box.css('min-height', maxHeight);
    }
  }
  
  const galleryThumbnails = function() {
    const container = $('.product-gallery-thumbnails'),
      inner = container.find('.product-gallery-thumbnail-inner');

    if( !container.length || !inner.length ) {
      return;
    }

    const thumbnails = container.find('img'),
      containerWidth = container.outerWidth();
    
    let imagesWidth = 0;
      
    $.each(thumbnails, (i, thumb) => {
      imagesWidth += $(thumb).outerWidth(true) + (thumbnails.length * 0.5);
    });

    if( containerWidth > imagesWidth ) {
      return;
    }

    container.on('mousemove touchmove', function(e) {
      let rect = e.currentTarget.getBoundingClientRect(),
        offsetX = e.clientX - rect.left,
        middle = containerWidth / 2,
        deviation = Math.max(offsetX - middle) - Math.min(middle - offsetX),
        multiplier = container[0].scrollWidth / 2;
        
      container.stop().animate({
        scrollLeft: container.scrollLeft() + Math.min(deviation, 1000)
      })
      
    });
  }

  const packageDescriptionExcerpt = function() {
    const packagesListingPage = $('.packages-page');

    if( !packagesListingPage.length ) {
      return;
    }

    const text = $('.package-description'),
      limit = 200;

    if( !text.length ) {
      return;
    }
    
    $.each(text, (i, el) => {
      const content = $(el),
        btn         = content.parent().find('.package-more'),
        inner       = content.find('.package-description-inner'),
        html        = inner.html();

      let totalHeight = inner.outerHeight();

      if( html.length > limit ) {
        const words = html.split(' ');

        words.splice(50, 0, '<span class="hide-text"></span>');
          
        inner.html( words.join(' ') );

        let showHeight = inner.find('.hide-text').position().top;

        if( totalHeight > showHeight ) {
          content.css('height', showHeight).addClass('folded');
          // btn.css('display', 'inline-block');

          // btn.off('click');
          // btn.on('click', () => {
          //   if( content.hasClass('folded') ) {
          //     content.css('height', totalHeight).removeClass('folded');
          //   }else {
          //     content.css('height', showHeight).addClass('folded');
          //   }
          // });
        }
      }
    });

  }

  $(document).ready(function() {
    //navbarDropdownParentLink();
    postsWidgetTitlesHeight();
    widgetLists();
    fontResier();
    languageSwitcher();
    postsPlacesDropdownSubmit();
    //formAttachmentFileName();
    careerBoxHoverBg();
    servicesGroupsContentSlide();
    accordionActive();
    pricelistFilter();
    priceFilterSubmitOnEnterKey();
  });

  $(window).on('load', function() {
    pageLoader();
    postsFilterAnchorScroll();
    doctorsPagingScroll();
    accordionsScroll();
    productsDisplay();
    productHoverZindex();
  });

  $(window).on('load resize', function() {
    postsSidebarHeight();
    careerStepsHeightEven();
    aboutSidesAdjust();
    aboutEntryAdjust();
    WidgetBoxContentAdjust();
    productItem();
    shopOffers();
    shopBox2();
    packageDescriptionExcerpt();
    //galleryThumbnails();
  });

})(jQuery, window, document);