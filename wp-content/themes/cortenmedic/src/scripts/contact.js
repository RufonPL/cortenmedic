import * as Utils from './utils';

(function($, window, document, undefined) {
  'use strict';

  class ContactBranches {
    constructor() {
      const contact = $('.contact-page');
      
      this.attachmentFileName();

      if( !contact.length ) return;

      this.group    = $('#contact_group');
      this.branch   = $('#contact_branch');
      this.loader   = $('.contact-loader');
      this.content  = $('.contact-branch-content');

      this.selectGroup();
      this.selectBranch();
    }

    selectGroup() {
      this.group.on('change', () => {
        const that = $(this.group),
          value = $.trim( that.val() );
        
        if( value != '' ) { 
          this.branch.selectpicker('val', '');
          this.content.html('');
          this.getBranches(value);
        }
      });
    }

    getBranches(value) {
      this.branch.prop('disabled', true);
      this.branch.selectpicker('refresh');
      this.group.prop('disabled', true);
      this.group.selectpicker('refresh');

      const data = {
        action: 'get_contact_branches',
        value: value,
        nonce: Utils.localized('formnonce')
      }

      $.post(Utils.localized('ajaxurl'), data)
        .done( (response) => {
          switch(response[0]) {
            case 'ok':
              this.branch.prop('disabled', false);
              this.branch.html(response[1]);
              this.branch.selectpicker('refresh');
              break;
          }
          this.group.prop('disabled', false);
          this.group.selectpicker('refresh');
        })
        .fail( (error) => {

        });
    }

    selectBranch() {
      this.branch.on('change', () => {
        const that = $(this.branch),
          value = $.trim( that.val() );
        
        if( value != '' ) {
          this.getBranchContent(value);
          this.content.html('');
        }
      });
    }

    getBranchContent(value) {
      this.group.prop('disabled', true);
      this.group.selectpicker('refresh');
      this.branch.prop('disabled', true);
      this.branch.selectpicker('refresh');
      this.loader.show();

      const data = {
        action: 'get_branch_content',
        value: value,
        nonce: Utils.localized('formnonce')
      }

      $.post(Utils.localized('ajaxurl'), data)
        .done( (response) => {
          switch(response[0]) {
            case 'ok':
              this.content.html(response[1]);
              if( $('form.wpcf7-form').length ) {
                jQuery.get(`${Utils.localized('siteurl')}/wp-content/plugins/contact-form-7/includes/js/scripts.js`);
                this.attachmentFileName();
                this.starsRating();
                this.datePicker();
              }
              break;
          }
          this.group.prop('disabled', false);
          this.group.selectpicker('refresh');
          this.branch.prop('disabled', false);
          this.branch.selectpicker('refresh');
          this.loader.hide();
        })
        .fail( (error) => {

        });
    }

    attachmentFileName() {
      const file = $('.form-control-file');

      file.on('change', function() {
        const that = $(this);

        if( that[0].files.length ) {
          const filename = that[0].files[0].name;

          that.closest('.form-file-upload').find('.btn').text( filename );
        }
      });
    }

    starsRating() {
      const stars = $('.form-group-rating'),
        radios = stars.find(':radio');

      if( !stars.length ) return;

      const star = stars.find('.wpcf7-list-item');

      star.on('mouseenter', function() {
        const that = $(this);
        that.prevAll().andSelf().addClass('hovered');
      }).on('mouseleave', function() {
        const that = $(this);
        that.prevAll().andSelf().removeClass('hovered');
      });

      star.on('click', function() {
        const that = $(this),
          index = that.index();

        that.siblings().removeClass('selected');
        that.prevAll().andSelf().addClass('selected');
        radios.eq(index).prop('checked', true)
      });
    }

    datePicker() {
      const config = {
        dateFormat: "d.m.Y",
      };
      Utils.ShowdatePicker('.your-date :text[name=your-date]', config);
    }

  }

  $(window).on('load', function() {
    new ContactBranches();
  });

})(jQuery, window, document);