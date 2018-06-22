<?php
/**
 * The main functions file.
 *
 * @author Rafał Puczel
 */

namespace RFSWP\Functions;

if( !class_exists('ThemeFunctions') ) {

  class ThemeFunctions {

    private static $instance;

    public function __construct() {

			$this->theme_setup();

      $this->action_hooks();

      $this->register_acf();

      $this->functions();

		}

    public function theme_setup() {

			add_action( 'after_setup_theme', array($this, 'theme_setup_core'));

		}

		/**
		 * This function will run basic theme setup
		 * @param n/a
		 * @return n/a
		*/
		public function theme_setup_core() {

			load_theme_textdomain('rfswp', get_template_directory().'/languages');

			require get_template_directory().'/functions/wp-bootstrap-navwalker.php';

			register_nav_menus( array(
				'primary' => __('Primary Menu', 'rfswp'),
				'business' => __('Business Menu', 'rfswp'),
				'e-advice'=> __('E-Advice Menu', 'rfswp'),
				'footer1' => __('Footer Menu 1', 'rfswp'),
				'footer2' => __('Footer Menu 2', 'rfswp'),
				'footer3' => __('Footer Menu 3', 'rfswp'),
				'footer4' => __('Footer Menu 4', 'rfswp'),
				'footer5' => __('Footer Menu 5', 'rfswp'),
                'shop' => __('Shop Menu', 'rfswp'),
			) );

			add_image_size( 'theme-logo', 300, 100, false );

			add_theme_support( 'custom-logo', array(
				'size' 				=> 'theme-logo',
				'flex-height' => true,
				'flex-width'  => true,
			) );


			add_image_size( 'slider-image', 1920, 570, true );
			add_image_size( 'post-image', 255, 160, true );
			add_image_size( 'doctor-image-lg', 445, 445, true );
			//add_image_size( 'doctor-image-sm', 160, 250, true );
			add_image_size( 'doctor-image', 250, 390, true );
			add_image_size( 'bigbox-image', 1110, 900 );
			add_image_size( 'gallery-image', 1110, 480, true );
			add_image_size( 'about-image', 540, 440, true );

			add_image_size( 'product-image', 250, 250, true );
		}

		/**
		 * This function is used to run action hooks and filter
		 * @param n/a
		 * @return n/a
		*/
    public function action_hooks() {
			// change default custom logo classes
			add_filter( 'get_custom_logo', array($this, 'custom_logo_classes') );

			//enqueue custom scripts and styles
			add_action( 'wp_enqueue_scripts', array($this, 'load_scripts') );
			add_action( 'wp_enqueue_scripts', array($this, 'localize_scripts'), 9 );

			if( class_exists('WPCF7') ) {
				//add_filter('script_loader_tag', array($this, 'cf7_defer'), 10, 2);
			}
			
			//loads scripts in footer
			//add_action( 'wp_enqueue_scripts', array($this, 'js_scripts_in_footer') );
			add_action( 'admin_footer', array($this, 'custom_admin_css') );

			//show site title
			add_filter( 'wp_title', array( $this, 'page_title' ) );

      add_action( 'init', array($this, 'disable_emojis') );

			add_filter( 'the_generator', array($this, 'hide_wp_version') );

			add_action( 'admin_menu', array($this, 'admin_menus_changes') );

			add_action( 'init', array($this, 'remove_post_support_elements') );

			add_filter( 'mce_buttons', array($this, 'tiny_mce_buttons_restore'), 5 );	

			add_filter( 'display_post_states', array($this, 'custom_pages_states') );
			add_action( 'admin_footer', array($this, 'page_template_one_use') );
			//add_action( 'admin_footer', array($this, 'plugins_hide') );

			add_filter( 'site_transient_update_plugins', array($this, 'disable_plugin_updates') );
		}

		/**
		 * This function is a filter to add custom classes to logo html
		 * @param $html
		 * @return html
		*/
    public function custom_logo_classes( $html ) {
			
			$html = str_replace( 'custom-logo-link', 'navbar-brand', $html );
			$html = str_replace( 'custom-logo', 'site-logo', $html );

			return $html;
		}

		/**
		 * This function will force js scripts to footer
		 * @param n/a
		 * @return n/a
		*/
    public function js_scripts_in_footer() {
			remove_action('wp_head', 'wp_print_scripts');
			remove_action('wp_head', 'wp_print_head_scripts', 9);
			remove_action('wp_head', 'wp_enqueue_scripts', 1);

			add_action('wp_footer', 'wp_print_scripts', 5);
			add_action('wp_footer', 'wp_enqueue_scripts', 5);
			add_action('wp_footer', 'wp_print_head_scripts', 5);
		}
		
		public function custom_admin_css() {
			echo '
			<style>
			.wp-submenu li a[href="update-core.php"] {display:none !important;}
			</style>
			';
		}

		/**
		 * This function will register css and js scripts
		 * @param n/a
		 * @return n/a
		*/
		public function load_scripts() {

			wp_enqueue_style( 'main', get_template_directory_uri().'/dist/styles/main.css', array(), '1.0' );
            wp_enqueue_style( 'custom', get_template_directory_uri().'/custom.css', array(), '1.0' );

			// wp_enqueue_style( 'fontawesome-css', get_template_directory_uri().'/css/font-awesome.min.css', array(), '4.7.0' );

			// wp_enqueue_style( 'styles-css', get_template_directory_uri().'/css/styles.min.css', array(), '1.0' );

			wp_enqueue_script( 'libs', get_template_directory_uri().'/dist/scripts/libs.js', array('jquery'), '1.0', true );

			wp_enqueue_script( 'main', get_template_directory_uri().'/dist/scripts/bundle.js', array('jquery'), '1.0', true );

		}

		public function cf7_defer($tag, $handle) {
				if( 'contact-form-7' !== $handle ) {
					return $tag;
				}
				return str_replace( ' src', ' async="async" src', $tag );
		}

		public function localize_scripts() {
			wp_enqueue_script('localizedscripts',  get_stylesheet_directory_uri() . '/dist/scripts/localize.js', array('jquery'),'', true );
			wp_localize_script(
				'localizedscripts', 
				'cortenscripts',
				array(
					'ajaxurl'				=> admin_url( 'admin-ajax.php'),
					'gmapsapikey' 	=> get_field('_google_maps_api_key', 'option'),
					'mapmarkerurl' 	=> get_template_directory_uri().'/dist/assets/images/mapmarker.png',
					'showmore' 			=> pll_trans('więcej', true),
					'showless' 			=> pll_trans('mniej', true),
					'mapnonce'			=> wp_create_nonce( 'check_map_nonce' ),
					'formnonce'			=> wp_create_nonce( 'check_form_nonce' ),
					'filteron'			=> is_home() && isset( $_GET['filter'] ) && absint( $_GET['filter'] ) == 1 ? 1 : 0,
					'blogurl'				=> esc_url( get_permalink( get_option('page_for_posts') ) ),
					'siteurl'				=> esc_url( get_bloginfo('url') ),
					'currentlang'		=> current_lang(),
					'pfilter'				=> _get_pricelist_filter_params(),
					'doctorspaging'	=> is_page( _page_template_id('doctors') ) && get_query_var('page') > 1 ? 1 : 0,
					'cartnonce'			=> wp_create_nonce( 'check_cart_nonce' ),
				)
			);
		}

		/**
		 * This function is a wp title filter callback
		 * @param 
		 * @return string
		*/
		public function page_title( $title, $sep = ' | ' ) {
			global $page, $paged;

			if( is_feed() ) {
			  return $title;
      }

			$site_description = get_bloginfo( 'description' );

			$filtered_title = get_the_title() . $sep . get_bloginfo( 'name' );

			$filtered_title .= ( ! empty( $site_description ) && ( is_home() || is_front_page() ) ) ? $sep . $site_description : '';

			$filtered_title .= ( 2 <= $paged || 2 <= $page ) ? $sep . sprintf( __( 'Page %s', 'rfswp' ), max( $paged, $page ) ) : '';

			return $filtered_title;
		}

		/**
		 * This function will remove wp default emoji scripts
		 * @param n/a
		 * @return n/a
		*/
    public function disable_emojis() {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			add_filter( 'tiny_mce_plugins', array($this, 'disable_emojis_tinymce') );
		}

		public function disable_emojis_tinymce( $plugins ) {
			if( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			} else {
				return array();
			}
		}

		/**
		 * This function will hide wp version
		 * @param n/a
		 * @return n/a
		*/
		public function hide_wp_version() {
			return '';
		}

		/**
		 * This function will add changes to admin menus
		 * @param n/a
		 * @return n/a
		*/
		public function admin_menus_changes() {
			global $menu;
			global $submenu;
			
    remove_menu_page('plugins.php');
			$menu[5][0] = __( 'News', 'rfswp' );
			$submenu['edit.php'][5][0] = __( 'News', 'rfswp' );
			$submenu['edit.php'][10][0] = __( 'Add new post', 'rfswp' );
		}

		/**
		 * This function will remove posts type supports
		 * @param n/a
		 * @return n/a
		*/
		function remove_post_support_elements() {
			remove_post_type_support('post', 'post-formats');
			remove_post_type_support('post', 'excerpt');
			remove_post_type_support('post', 'comments');
			remove_post_type_support('post', 'revisions');
			remove_post_type_support('post', 'trackbacks');
			remove_post_type_support('post', 'author');
			remove_post_type_support('post', 'custom-fields');
			remove_post_type_support('post', 'thumbnail');

			register_taxonomy('post_tag', array());
		}

		/**
		 * This function will restore tinymce justify button
		 * @param n/a
		 * @return n/a
		*/
		public function tiny_mce_buttons_restore( $buttons_array ){
			$mce_buttons = array( 
				'formatselect',		// Dropdown list with block formats to apply to selection.
				'bold',				// Applies the bold format to the current selection.
				'italic',			// Applies the italic format to the current selection.
				'underline',		// Applies the underline format to the current selection.
				'bullist',			// Formats the current selection as a bullet list.
				'numlist',			// Formats the current selection as a numbered list.
				'blockquote',		// Applies block quote format to the current block level element.
				'alignleft',		// Left aligns the current block or image.
				'aligncenter',		// Left aligns the current block or image.
				'alignright',		// Right aligns the current block or image.
				'alignjustify',		// Full aligns the current block or image.
				'link',				// Creates/Edits links within the editor.
				'unlink',			// Removes links from the current selection.
				'wp_more',			// Inserts the <!-- more --> tag.
				'spellchecker',		// ???
				'wp_adv',			// Toggles the second toolbar on/off.
				'dfw' 				// Distraction-free mode on/off.
			); 
			
			return $mce_buttons;
		}

		/**
		 * This function will display pages states text in pages list table
		 * @param $states
		 * @return string
		*/
		public function custom_pages_states($states) {
			global $post;

			if( !is_admin() ) return;

			$custom_states = array(
				'institutions' 		=> __( 'Institutions page', 'rfswp' ),
				'doctors' 				=> __( 'Doctors page', 'rfswp' ),
				//'career-main' 	=> __( 'Career page - This page and its subpages will show career menu', 'rfswp' ),
				'career' 					=> __( 'Job offers page', 'rfswp' ),
				'offer' 					=> __( 'Offers page - services groups list', 'rfswp' ),
				'offer-pricelist' => __( 'Services pricelist page', 'rfswp' ),
				'advice' 					=> __( 'E-advice main page', 'rfswp' ),
				'products' 				=> __( 'Products page', 'rfswp' ),
				'packages' 				=> __( 'Packages page', 'rfswp' ),
				'shop'		 				=> __( 'Shop main page', 'rfswp' ),
				'cart'		 				=> __( 'Shop cart page', 'rfswp' ),
				'checkout'		 		=> __( 'Shop checkout page', 'rfswp' ),
			);

			foreach($custom_states as $page => $state) {
				if( get_post_type($post->ID) == 'page' && is_array(_page_template_id( $page )) && in_array($post->ID, _page_template_id( $page )) ) {
					$states[] = $state;
				}
			}

			return $states;
		}

		/**
		 * This function will restrict page templates usage to only one
		 * @param n/a
		 * @return n/a
		*/
		public function page_template_one_use() {
			if( is_admin() ) {
				global $post;
				global $pagenow;

				if( ($pagenow != 'post.php' && $pagenow != 'post-new.php') || $post->post_type != 'page' ) return;

				$templates = array(
					'home',
					'contact',
					'institutions', 
					'doctors', 
					'career', 
					'career-main', 
					'recruitment',
					'career-photos',
					'offer',
					'offer-pricelist',
					'signup-visit',
					'about-us',
					'about-mission',
					'prizes',
					'advice',
					'products',
					'packages',
					'shop',
					'cart',
					'checkout'
				);

				foreach($templates as $template) {
					$is_used = _page_template_id($template, true);
					
					if( $is_used ) {
						$page_id = $is_used;
						if( $page_id != $post->ID ) {
							echo <<< JQ
							<script type="text/javascript">
								jQuery(document).ready( function($) {
									$('#page_template').find('option[value="page-templates/$template-template.php"]').hide();
								});
							</script>
JQ;
						}
					}
				}
			}
		}

// 		public function plugins_hide() {
// 			if( is_admin() ) {
// 				global $pagenow;

// 				if( $pagenow != 'plugins.php' ) return;
				
// 				echo <<< JQ
// 				<script type="text/javascript">
// 					jQuery(document).ready( function($) {
// 						$('#wp-media-folder-update').hide();
// 					});
// 				</script>
// JQ;
// 			}
// 		}

		/**
		 * This function will disable specified plugins updates
		 * @param 
		 * @return 
		*/
		public function disable_plugin_updates( $value ) {
			//unset( $value->response['wp-media-folder/wp-media-folder.php'] );
			return $value;
		}

		/**
		 * This function will register acf pro plugin in a theme
		 * @param n/a
		 * @return n/a
		*/
    public function register_acf() {
      require get_template_directory().'/functions/acf.php';
    }

		/**
		 * This function will include custom functions scripts
		 * @param n/a
		 * @return n/a
		*/
    public function functions() {
      require get_template_directory().'/inc/db-session-storage.php';
      require get_template_directory().'/functions/cpt.php';
      require get_template_directory().'/functions/polylang.php';
      require get_template_directory().'/email/email.php';
      require get_template_directory().'/functions/widgets.php';
      require get_template_directory().'/functions/helpers.php';
      require get_template_directory().'/functions/theme-functions.php';
			require get_template_directory().'/shop/tpay.php';
			require get_template_directory().'/functions/ajax.php';
			require get_template_directory().'/shop/shop.php';
			require get_template_directory().'/shop/cart.php';
			require get_template_directory().'/shop/my-account.php';

      require get_template_directory().'/tinymce/tinymce.php';
    }

  }

  new ThemeFunctions;

}
?>