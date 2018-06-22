import * as Utils from './utils';

(function($, window, document, undefined) {
  'use strict';

  class FormValidate {

    constructor(formclass, hideOnChange = false) {
      this.form           = $(formclass);

      if( !this.form.length ) return;
      //this.select   = this.form.find('select');
      this.hideOnChange   = hideOnChange;
      this.formType       = formclass.replace('.', '').replace('-search-form', ''),
      this.btn            = this.form.find('.form-serach-submit button');
      this.inputInit      = $.trim( this.form.attr('data-input-init') );
      this.dependencyInit = $.trim( this.form.attr('data-dependency-init') );

      //this.selectpickerInit();
      this.inputsDependencies();
    }

    selectpickerInit() {
      this.form.find('.selectpicker').selectpicker('val', '');

      $('.selectpicker').on('shown.bs.select', function (e) {
				const that = $(this),
					closeBtn = that.parent().find('.close');
					
				closeBtn.on('click', () => {
					that.selectpicker('val', '');
				});
			});
    }

    inputsDependencies() {
      let input, dependency, 
        $this = this;

      switch(this.formType) {
        case 'institutions':
            input = $('#igroup'),
            dependency = $('#iclinic');
          break;
        case 'doctors':
            input = $('#igroup, #icity'),
            dependency = $('#ispec, #iaddress');
          break;
        case 'offer':
            input = $('#ogroup'),
            dependency = $('#oservice');
          break;
        case 'institutions-map':
            input = $('#mcity'),
            dependency = $('#minstitution');
          break;
        default:
          break;
      }

      $.each(input, (index, el) => {
        let i = $(el),
          inputs = input.get(),
          dependencies = dependency.get();

        if( this.inputInit && this.dependencyInit ) {
          let inputInit = JSON.parse( this.inputInit ),
            dependencyInit = JSON.parse( this.dependencyInit );
            
          if( inputInit[index] != '' && inputInit[index] != false ) {
            $(inputs[index]).selectpicker('val', inputInit[index]);
            $(inputs[index]).selectpicker('refresh');
            this.loadDependency( $(inputs[index]), $(dependencies[index]), dependencyInit[index] );
          }
        }
        
        if( i === undefined ) return;

        i.on('change', () => {
          let id = i.attr('id');

          if( this.hideOnChange ) {
            const contentToHide = $(this.hideOnChange)
            contentToHide.hide();
          }
          
          let inputIndexes = inputs.map( (element, index) => {
            return $(element).attr('id') == id ? index : -1;
          });

          let inputIndex = inputIndexes.filter( (element) => {
            return element > -1;
          });

          if( inputIndex.length > 0 ) {
            let inputToLoad = $(`#${id}`),
              dependencyIndex = inputIndex[0],
              dependencyToLoad = $(dependencies[dependencyIndex]);
              
            this.loadDependency(inputToLoad, dependencyToLoad);
          }
        });

      });

    }

    submitForm(input, dependency) {
      this.form.on('submit', (e) => {
        
        const inputVal = $(input).val(),
          dependencyVal = $(dependency).val(),
          error         = dependency.closest('.form-group').find('.sff-error'),
          dependencyOptional = dependency.hasClass('optional');

        if( $.trim( inputVal ) != '' && $.trim( dependencyVal ) == '' && !dependencyOptional ) {
          e.preventDefault();
          error.show();
        }else {
          error.hide();
        }

      });
    }

    loadDependency(input, dependency, setValue = false) {
      const field = $(input),
        val = field.val();

      if( val != '' ) {
        this.btn.attr('disabled', true);
        dependency.prop('disabled', true);
        dependency.selectpicker('refresh');

        const data = {
          action: 'get_form_data',
          value: val,
          type: this.formType,
          fieldId: input.attr('id'),
          nonce: Utils.localized('formnonce')
        }

        $.post(Utils.localized('ajaxurl'), data)
          .done( (response) => {
            this.btn.attr('disabled', false);
            dependency.prop('disabled', false);
            dependency.html(response[1]);
            dependency.selectpicker('refresh');

            if( setValue ) {
              dependency.selectpicker('val', setValue);
              dependency.selectpicker('refresh');
            }
          })
          .fail( (error) => {

          });

        this.submitForm(input, dependency);
      }else {
        this.btn.attr('disabled', true);
        dependency.prop('disabled', true);
        dependency.selectpicker('refresh');
      }
    }

  }

  $(window).on('load', function() {
    new FormValidate('.institutions-search-form');
    new FormValidate('.doctors-search-form');
    new FormValidate('.offer-search-form', '.single-offer-details');
    new FormValidate('.institutions-map-search-form');
  });

})(jQuery, window, document);