import * as Utils from './utils';
import * as Sanitizer from './html-sanitizer';

(function($, window, document, undefined) {
  'use strict';

  const addToCart = function() {
    const btn = $('.add-to-cart');

    let loading = false;

    if( !btn.length ) {
      return;
    }

    btn.on('click', function(e) {
      e.preventDefault();

      if( loading == true ) {
        return;
      }
      
      const that  = $(this),
        loader      = that.find('.cart-loader'),
        productId   = that.attr('href').slice(1),
        qtyBox      = that.parent().find('input[name="product-qty"]'),
        productQty  = qtyBox.val() || 1;

      if( productId > 0 ) {
        that.attr('disabled', true);
        loader.css('display', 'inline-block');

        const data = {
          action: 'add_to_cart',
          nonce: Utils.localized('cartnonce'),
          product_id: productId,
          product_qty: productQty
        }

        $.post(Utils.localized('ajaxurl'), data)
        .done( (response) => {
          switch( response[0] ) {
            case 'ok':
              that.attr('disabled', false);
              loader.css('display', '');
              let headerCartData = response[2];

              cartModal( response[1] );
              headerCartQty( headerCartData['count'], headerCartData['text'] );
              break;
            case 'error':
              cartModal( response[2], 'error' );
              break;
          }
          loading = false;
        })
        .fail( (error) => {

        });

        loading = true;
      }
    });
  };

  const removeFromCart = function() {
    const cart = $('.cart-table')

    if( !cart.length ) {
      return;
    }

    let loading = false;

    const btn = cart.find('.remove-from-cart'),
      loader = cart.find('.cart-loader');

    btn.on('click', function() {
      if( loading == true ) {
        return;
      }

      const that = $(this),
        productID = that.attr('data-product');

      if( productID > 0 ) {
        cart.addClass('loading');
        loader.show();
        
        const data = {
          action: 'remove_from_cart',
          nonce: Utils.localized('cartnonce'),
          product_id: productID,
          shipment: getShipmentMethod()
        }

        $.post(Utils.localized('ajaxurl'), data)
        .done( (response) => {
          switch( response[0] ) {
            case 'ok':
              const productToRemove = response[1],
                productsLeftInCart  = response[2],
                cartSummary         = response[3],
                headerCartData      = response[4],
                removeShipping      = response[5];

                removeProduct( cart, productToRemove, productsLeftInCart, removeShipping );
                
                if( productsLeftInCart > 0 ) {
                  setTimeout( () => {
                    updateCartSummary( cartSummary );
                    headerCartQty( headerCartData['count'], headerCartData['text'] );
                    cart.removeClass('loading');
                    loader.hide();
                  }, 500);
                }
              break;
            case 'error':
              cartModal( response[2], 'error' );
              break;
          }
          loading = false;
        })
        .fail( (error) => {

        });

        loading = true;
      }
    });
  }

  const updateCartProducts = function(cart, productsToUpdate) {
    let loading = false;

    const updateCartBtn = cart.find('.cart-update-btn'),
      productsData = {},
      loader = cart.find('.cart-loader');

    updateCartBtn.off('click');
    updateCartBtn.on('click', () => {
      if( loading == true ) {
        return;
      }

      $.each(productsToUpdate, (i, el) => {
        const product = $(el),
          productId   = product.find('.remove-from-cart').attr('data-product'),
          productQty  = product.find('.cart-product-qty').val();

        productsData[productId] = productQty;
      });

      cart.addClass('loading');
      loader.show();
      
      const data = {
        action: 'update_cart',
        nonce: Utils.localized('cartnonce'),
        products: productsData,
        shipment: getShipmentMethod()
      }

      $.post(Utils.localized('ajaxurl'), data)
      .done( (response) => {
        switch( response[0] ) {
          case 'ok':
            const cartSummary = response[1],
              productsTotals  = response[2];

            updateCartSummary( cartSummary );
            updateCartProductsTotals( cart, productsTotals );

            cart.removeClass('loading');
            loader.hide();
            updateCartBtn.parent().hide();
            updateCart();
            break;
          case 'error':
            cartModal( response[2], 'error' );
            break;
        }
        loading = false;
      })
      .fail( (error) => {

      });

      loading = true;
    });
  }

  const updateCart = function() {
    const cart = $('.cart-table');
    
    if( !cart.length ) {
      return;
    }

    const products = cart.find('tbody tr'),
      updateCart = cart.find('.cart-update');

    if( !products.length ) {
      return;
    }

    let qts = [];

    let currentQts = products.map( (i, row) => {
      const qty = $(row).find('.cart-product-qty').val();
      
      return qty;
    }).get();

    $.each(products, (i, el) => {
      const product = $(el),
        qty = product.find('.cart-product-qty');
        
      qty.on('change keyup', () => {
        qts = products.get().filter( (row, index) => {
          const newQty = $(row).find('.cart-product-qty').val();
          
          return newQty != currentQts[index]
        });
        
        if( qts.length > 0 ) {
          updateCart.show();
          updateCartProducts(cart, qts);
        }else {
          updateCart.hide();
        }
      });
    });
  }

  const removeProduct = function(cart, productID, productsLeftInCart, removeShipping) {
    if( productsLeftInCart == 0 ) {
      window.location.reload();
    }else {
      const row = cart.find(`[data-product="${productID}"]`),
        shippingRow = $('.cart-shipping-row');

      if( row.length ) {
        row.closest('tr').animate({
          opacity: 0
        }, 500, function() {
          $(this).remove();

          if( removeShipping && shippingRow.length ) {
            shippingRow.remove();
          }
        });
      }
    }
  }

  const updateCartSummary = function(summary) {
    const cartSummary = $('.cart-summary');

    if( !cartSummary.length ) {
      return;
    }

    const cartTotal = cartSummary.find('.cart-total'),
      cartTax = cartSummary.find('.cart-tax'),
      cartToPay = cartSummary.find('.cart-to-pay');

    cartTotal.text( summary['total'] );
    cartTax.text( summary['tax'] );
    cartToPay.text( summary['to_pay'] );
  }

  const updateCartProductsTotals = function(cart, products) {
    if( !products.length ) {
      return;
    }

    $.each(products, (i, product) => {
      $.each(product, (id, price) => {
        const row = cart.find(`[data-product-id=${id}]`),
          total = row.find('.cart-product-sum');

        total.text( price );
      });
    })
  }

  const cartModal = function(message, type = 'ok') {
    const modal = $('#shop-modal');

    if( !modal.length ) {
      return;
    }

    const html = Sanitizer.sanitize( message ),
      text = modal.find('#shop-modal-label');

    text.html( html );

    if( type == 'error' ) {
      modal.find('.modal-body').hide();
      text.addClass('error');
    }

    modal.modal('show');
  }

  const headerCartQty = function(qty, text) {
    const headerCart = $('.shop-header-cart'),
      qtyNumber = headerCart.find('p strong'),
      qtyText   = headerCart.find('p span');

    qtyNumber.text( qty );
    qtyText.text( text );
  }

  const getShipmentMethod = function(element = false) {
    const shipmentMethods = $('.cart-shipment');

    if( !shipmentMethods.length ) {
      return;
    }

    const selectedMethod = shipmentMethods.find('.shipment-value:checked');

    if( !selectedMethod.length ) {
      return;
    }

    return element ? selectedMethod : selectedMethod.val();
  }

  const updateShipmentMethod = function(data, content, loader, loading) {
    $.post(Utils.localized('ajaxurl'), data)
    .done( (response) => {
      switch( response[0] ) {
        case 'ok':
          const cartSummary = response[1];
          updateCartSummary( cartSummary );
          content.removeClass('loading');
          loader.hide();
          break;
        case 'error':
          cartModal( response[2], 'error' );
          break;
      }
      loading = false;

      return loading;
    })
    .fail( (error) => {

    });
  }

  const changeShipmentMethod = function() {
    const shipmentMethods = $('.cart-shipment');
    
    if( !shipmentMethods.length ) {
      return;
    }

    const method = shipmentMethods.find('.shipment-value'),
      lists = shipmentMethods.find('.shipment-list');

    if( !method.length ) {
      return;
    }

    let loading = false;

    const content = $('.cart-summary-content'),
      loader = content.find('.cart-loader');
    
    method.on('change', function() {
      if( loading == true ) {
        return;
      }

      const that      = $(this),
        value         = that.val(),
        list          = that.siblings('.shipment-list');
      
      let shipmentItem  = list.length ? list.val() : '';

      if( that.hasClass('shipment-has-list') ) {
        list.show().css('border-color', '');

        list.on('change', function() {
          if( loading == true ) {
            return;
          }
          
          const listItem = $(this);
  
          list.show().css('border-color', '');
          shipmentItem = listItem.val();
          
          content.addClass('loading');
          loader.show();
    
          const data = {
            action: 'change_shipping_method',
            nonce: Utils.localized('cartnonce'),
            shipment: value,
            shipment_item: shipmentItem
          }
          loading = true;
    
          loading = updateShipmentMethod(data, content, loader, loading);
        });
      }else {
        lists.hide();
      }

      content.addClass('loading');
      loader.show();

      const data = {
        action: 'change_shipping_method',
        nonce: Utils.localized('cartnonce'),
        shipment: value,
        shipment_item: shipmentItem
      }
      loading = true;

      loading = updateShipmentMethod(data, content, loader, loading);
    });

    shipmentMethods.find('.shipment-default').trigger('change');
  }

  const goToCheckout = function() {
    const btn = $('.go-to-checkout');

    btn.on('click', (e) => {
      const that = $(e.target),
        shipment = getShipmentMethod(true);

      if( shipment.hasClass('shipment-has-list') ) {
        const list = shipment.siblings('.shipment-list');
        
        if( $.trim( list.val() ) == '' ) {
          list.css('border-color', 'red');
          return false;
        }else {
          list.css('border-color', '');
          return true;
        }
      }
    });
  }

  const checkoutFieldsToggle = function() {
    const form = $('#checkout-form');

    if( !form.length ) {
      return;
    }

    const customerType  = form.find('.checkout-customer-type input[type="radio"]'),
      companyFields     = form.find('.checkout-company-field'),
      personalFields     = form.find('.checkout-personal-field'),
      shipment          = form.find('#customer-shipment-different'),
      shipmentFields    = form.find('.checkout-shipment-fields');

    customerType.on('change', function() {
      const type = $(this).val();

      if( type == 'company' ) {
        companyFields.addClass('active');
        personalFields.removeClass('active');
      }else {
        companyFields.removeClass('active');
        personalFields.addClass('active');
      }
    });

    shipment.on('change', function() {
      const isChecked = $(this).is(':checked');

      if( isChecked ) {
        shipmentFields.addClass('active');
      }else {
        shipmentFields.removeClass('active');
      }
    });
    
    form.find('.checkout-customer-type input[type="radio"]:checked').trigger('change');
    form.find('#customer-shipment-different:checked').trigger('change');
    
  }

  const checkoutProccess = function() {
    const form = $('#checkout-form');
    
    if( form == null ) {
      return;
    }

    let loading = false;

    const paymentBtns = form.find('.payment-btn');
    
    form.on('submit', (e, submitter) => {
      e.preventDefault();

      if( loading ) {
        return;
      }
      
      const formData  = new FormData(form[0]),
        submitBtn     = $(document.activeElement),
        paymentType   = submitBtn.val(),
        loader        = submitBtn.find('.cart-loader'),
        required      = form.find('.required');

      const requiredFields = required.map( (index, field) => {
        return $(field).attr('name');
      }).get();

      required.removeClass('field-invalid');
      form.find('.field-error').remove();

      formData.append('customer-payment-type', paymentType);
      formData.append('required-fields', requiredFields );
      formData.append('action', 'checkout_proccess');
      formData.append('nonce', Utils.localized('cartnonce'));

      paymentBtns.attr('disabled', true);
      loader.css('display', 'inline-block');

      $.ajax({
        url: Utils.localized('ajaxurl'),
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
      })
      .done( (response) => {
        switch( response[0] ) {
          case 'ok':
            const redirectUrl = response[1];
            paymentBtns.attr('disabled', false);
            loader.css('display', '');
            
            history.pushState( {}, 'checkout', window.location.href );
            window.location.replace( redirectUrl );
            break;
          case 'error':
            switch( response[1] ) {
              case 'error':
                cartModal( response[2], 'error' );
                break;
              case 'validation':
                const errors = response[2];

                $.each(errors, (fieldName, error) => {
                  const field = form.find(`[name="${fieldName}"]`).addClass('field-invalid');
                  
                  if( error.hasOwnProperty('email') ) {
                    field.parent().append('<span class="field-error">' + error.email + '</span>');
                  }
                });

                cartModal( response[3], 'error' );

                paymentBtns.attr('disabled', false);
                loader.css('display', '');
                break;
            }
            break;
        }
        loading = false;
      })
      .fail( (error) => {
        console.log('server error');
      });

      loading = true;
    });
  }

  $(document).ready(function() {
    addToCart();
    removeFromCart();
    updateCart();
    changeShipmentMethod();

    checkoutFieldsToggle();
    checkoutProccess();
    goToCheckout();
  });

  $(window).on('load', function() {
  });

  $(window).on('load resize', function() {
  });

})(jQuery, window, document);
