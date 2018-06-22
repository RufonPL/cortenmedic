import * as Utils from './utils';
import * as Sanitizer from './html-sanitizer';

(function($, window, document, undefined) {
  'use strict';



  const shopModal = function(message, type = 'ok') {
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

  const personalFieldsToggle = function() {
    const form = $('#my-details-form');

    if( !form.length ) {
      return;
    }

    const customerType  = form.find('.checkout-customer-type input[type="radio"]'),
      companyFields     = form.find('.checkout-company-field'),
      personalFields    = form.find('.checkout-personal-field'),
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

  const savePersonalData = function() {
    const form = $('#my-details-form');
    
    if( form == null ) {
      return;
    }

    let loading = false;
    
    form.on('submit', (e) => {
      e.preventDefault();

      if( loading ) {
        return;
      }
      
      const formData  = new FormData(form[0]),
        submitBtn     = form.find('.save-my-details'),
        successMsg    = form.find('.alert-success'),
        loader        = submitBtn.find('.cart-loader'),
        required      = form.find('.required');

      const requiredFields = required.map( (index, field) => {
        return $(field).attr('name');
      }).get();

      required.removeClass('field-invalid');
      form.find('.field-error').remove();
      successMsg.hide();

      formData.append('required-fields', requiredFields );
      formData.append('action', 'save_personal_data');
      formData.append('nonce', Utils.localized('cartnonce'));

      submitBtn.attr('disabled', true);
      loader.css('display', 'inline-block');

      $.ajax({
        url: Utils.localized('ajaxurl'),
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
      })
      .done( (response) => {
        console.log(response)
        switch( response[0] ) {
          case 'ok':
            const updatedFields = response[1];
            
            $.each(updatedFields, (fieldName, value) => {
              const field = form.find(`[name="${fieldName}"]`);
              
              if( $.trim( value ) == '' ) {
                field.val('');
              }
            });

            successMsg.show();
            submitBtn.attr('disabled', false);
            loader.css('display', '');
            break;
          case 'error':
            switch( response[1] ) {
              case 'error':
                shopModal( response[2], 'error' );
                break;
              case 'validation':
                const errors = response[2];

                $.each(errors, (fieldName, error) => {
                  const field = form.find(`[name="${fieldName}"]`).addClass('field-invalid');
                  
                  if( error.hasOwnProperty('email') ) {
                    field.parent().append('<span class="field-error">' + error.email + '</span>');
                  }
                });

                shopModal( response[3], 'error' );

                submitBtn.attr('disabled', false);
                loader.css('display', '');
                break;
            }
            break;
        }
        loading = false;
      })
      .fail( (error) => {
        
      });

      loading = true;
    });
  }

  $(document).ready(function() {
    personalFieldsToggle();
    savePersonalData();
  });

  $(window).on('load', function() {
  });

  $(window).on('load resize', function() {
  });

})(jQuery, window, document);
