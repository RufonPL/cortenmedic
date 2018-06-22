<?php  
if( !class_exists('CustomTMce') ) {

  class CustomTMce {

    public function __construct() {
      if( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
        return;
      }

      if( get_user_option( 'rich_editing' ) !== 'true' ) {
        return;
      }

      add_filter( 'mce_external_plugins', array($this, 'add_tinymce_plugin') );
      add_filter( 'mce_buttons', array($this, 'add_tinymce_toolbar_button') );
      add_filter( 'mce_buttons_2', array($this, 'mce_buttons') );
      add_filter( 'tiny_mce_before_init', array($this, 'mce_text_sizes') );

      //add_action( 'admin_head', array($this, 'tinymce_styles') );
    }

    public function add_tinymce_plugin($plugin_array) {
      $plugin_array['section_header'] = get_template_directory_uri().'/tinymce/tinymce.js';

      return $plugin_array;
    }
    
    public function add_tinymce_toolbar_button($buttons) {
      array_push( $buttons, 'section_header' );

      return $buttons;
    }

    public function mce_buttons( $buttons ) {
      array_unshift( $buttons, 'fontsizeselect' ); 

      return $buttons;
    }

    public function mce_text_sizes( $initArray ){
      $initArray['fontsize_formats'] = "12px 13px 14px 15px 16px 18px 24px 30px 36px";

      return $initArray;
    }

    public function tinymce_styles() {
      echo '
      <style>
        .page-section-header {
          position: relative;
          margin: 30px 0;
          padding-left: 30px;
          font-weight: 900;
        }
        
        .page-section-header:before {
          position: absolute;
          top: 0;
          left: 0;
          bottom: 0;
          display: inline-block;
          width: 18px;
          content: "";
          background-color: #a70068;
        }
      </style>
      ';
    }

  }

  new CustomTMce();

}
?>