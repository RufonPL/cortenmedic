<?php

if( !class_exists( 'RFS_ACF' ) ) {
	
	class RFS_ACF {
		
		private $acf_dir = '/inc/advanced-custom-fields-pro/';
		
		public function __construct() {
			
			$this->acf_plugin();
			
			add_action('widgets_init', array($this, 'acf_options_page'));
			
		}
		
		public function acf_plugin() {
			
			include_once( get_stylesheet_directory() . $this->acf_dir . 'acf.php' );
			
			add_filter( 'acf/settings/path', array($this, 'my_acf_settings_path') );
			
			add_filter( 'acf/settings/dir', array($this, 'my_acf_settings_dir') );

			add_filter( 'acf/fields/wysiwyg/toolbars', array($this, 'acf_wyswig_toolbars') );

			add_action( 'admin_head', array($this, 'acf_admin_styles') );

			add_action( 'acf/init', array($this, 'acf_google_maps_api_key') );
			
			add_filter( 'acf/fields/post_object/query/name=_news_slider', array($this, 'acf_get_widget_sliders'), 10, 3);

			add_filter( 'acf/fields/relationship/query/name=_accordion_page_accordions', array($this, 'acf_get_widget_accordions'), 10, 3);

			add_filter( 'acf/load_field/name=_job_location_city', array($this, 'acf_load_job_offer_cities') );

			add_filter( 'acf/fields/relationship/query/name=_widget_products_on_sale', array($this, 'acf_get_products_on_sale'), 10, 3);
			add_filter( 'acf/fields/relationship/query/name=_widget_packages_on_sale', array($this, 'acf_get_products_on_sale'), 10, 3);

			add_filter( 'acf/fields/post_object/query/name=_shipment_default', array($this, 'acf_get_shipment_active'), 10, 3);
			
			add_filter('acf/settings/show_admin', '__return_false');

			add_action( 'init', array($this, 'php_registered_fields_groups'), 11 );

			add_filter( 'posts_where', array($this, 'acf_pricelists_where') );
		
		}
		
		public function my_acf_settings_path( $path ) {
			
			$path = get_stylesheet_directory() . $this->acf_dir;
			
			return $path;
			
		}
		
		public function my_acf_settings_dir( $dir ) {
			
			$dir = get_stylesheet_directory_uri() . $this->acf_dir;
			
			return $dir;
			
		}
		
		/**
		 * This function will create acf options pages
		 * @param n/a
		 * @return n/a
		*/
		public function acf_options_page() {
			
			if( function_exists('acf_add_options_page') ) {
				
				acf_add_options_page(array(
					'page_title' 	=> __( 'Miscellaneous', 'rfswp'),
					'menu_title'	=> __( 'Miscellaneous', 'rfswp'),
					'menu_slug' 	=> 'theme-general-settings',
					'capability'	=> 'edit_posts',
					'redirect'		=> true,
					'icon_url'		=> 'dashicons-screenoptions',
					'position'		=> 23
				));
				
				$acf_subpages = array(
					__('Header', 'rfswp'),
					__('Social Media', 'rfswp'),
					__('Google Maps', 'rfswp'),
				);
				
				foreach($acf_subpages as $subpage) {
					acf_add_options_sub_page(array(
					'page_title' 	=> $subpage,
					'menu_title'	=> $subpage,
					'parent_slug'	=> 'theme-general-settings',
					));
				}

				acf_add_options_page(array(
					'page_title' 	=> __( 'Woo - Ustawienia', 'rfswp'),
					'menu_title'	=> __( 'Woo - Ustawienia', 'rfswp'),
					'menu_slug' 	=> 'theme-shop-settings',
					'capability'	=> 'edit_posts',
					'redirect'		=> true,
					'icon_url'		=> 'dashicons-cart',
					'position'		=> 22
				));

				$shop_subpages = array(
					__('Products', 'rfswp') => 'products',
					__('Shipping', 'rfswp') => 'shipping',
					__('Payment', 'rfswp') => 'payment',
				);
				
				foreach($shop_subpages as $subpage => $slug) {
					acf_add_options_sub_page(array(
					'page_title' 	=> $subpage,
					'menu_title'	=> $subpage,
					'parent_slug'	=> 'theme-shop-settings',
					'menu_slug' => 'acf-options-'.$slug
					));
				}
			}
		}

		/**
		 * This function will create custom tollbars for acf wyswigh field
		 * @param n/a
		 * @return n/a
		*/
		public function acf_wyswig_toolbars($toolbars) {
			// echo '< pre >';
			// 	print_r($toolbars);
			// echo '< /pre >';
			// die;
			
			$toolbars['Simple'] = array();
			$toolbars['Simple'][1] = array('bold', 'bullist');
			
			$toolbars['Text field'] = array();
			$toolbars['Text field'][1] = array('bold');
			
			if(($key = array_search('code', $toolbars['Full' ][2])) !== false) {
					unset($toolbars['Full'][2][$key]);
			}
			return $toolbars;
		}

		/**
		 * This function will add css styles to acf fileds in admin
		 * @param n/a
		 * @return styles
		*/
		public function acf_admin_styles() {
			echo '<style type="text/css">
					.wyswig-text_field {
						max-height:250px;
					}
					[data-toolbar="text_field"] iframe  {
						height:120px !important;
						min-height:120px !important;
					}
					.wyswig-simple {
						max-height:350px;
					}
					[data-toolbar="simple"] iframe  {
						height:220px !important;
						min-height:220px !important;
					}
					</style>';
		}

		public function acf_google_maps_api_key() {
			$api_key = get_field('_google_maps_api_key', 'option');
			acf_update_setting('google_api_key', $api_key);
		}

		/**
		 * This function will filter widgets to return only sliders
		 * @param 
		 * @return n/a
		*/
		public function acf_get_widget_sliders($args, $field, $post_id) {
			$args['meta_query'] = array(
				array(
					'key' 		=> '_widget_type',
					'value' 	=> 'slider',
					'type' 		=> 'CHAR',
					'compare' => '='
				)
			);

			return $args;
		}

		/**
		 * This function will filter widgets to return only accordions
		 * @param 
		 * @return n/a
		*/
		public function acf_get_widget_accordions($args, $field, $post_id) {
			$args['meta_query'] = array(
				array(
					'key' 		=> '_widget_type',
					'value' 	=> 'accordion',
					'type' 		=> 'CHAR',
					'compare' => '='
				)
			);

			return $args;
		}

		/**
		 * This function will filter prodcust to return only the ones on sale
		 * @param 
		 * @return n/a
		*/
		public function acf_get_products_on_sale($args, $field, $post_id) {
			$args['meta_query'] = array(
				array(
					'key' 		=> '_product_sale_price',
					'value' 	=> 0,
					'type' 		=> 'NUMERIC',
					'compare' => '>'
				)
			);

			return $args;
		}

		public function acf_get_shipment_active($args, $field, $post_id) {
			$args['meta_query'] = array(
				array(
					'key'      => '_sm_active',
					'value'    => 1,
					'type'     => 'NUMERIC',
					'compare'  => '='
				)
			);

			return $args;
		}

		public function acf_load_job_offer_cities($field) {
			$field['choices'] = array();
			$cities = get_terms(array(
				'taxonomy' => 'institution-city',
				'hide_empty' => true
			));

			if( $cities ) {
				foreach($cities as $city) {
					$field['choices'][esc_html( $city->name )] = esc_html( $city->name );
				}
			}

			return $field;
		}

		public function php_registered_fields_groups() {
			
			if( function_exists('acf_add_local_field_group') ) {

				$cities = get_terms(array(
					'taxonomy' => 'institution-city',
					'hide_empty' => true
				));

				$pricelist_subfields = array(
					array (
						'key' => 'field_59707e4748254',
						'label' => 'Nazwa',
						'name' => '_name',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => count($cities) > 3 ? '40' : '50',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array (
						'key' => 'field_59707e4e48255',
						'label' => 'Cena (do usunięcia)',
						'name' => '_price',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => 'zł',
						'maxlength' => '',
					)
				);

				if( $cities ) {
					foreach($cities as $city) {
						$pricelist_subfields[] = array (
							'key' => 'field_59707e4e482'.$city->term_id,
							'label' => 'Cena '.esc_html( $city->name ),
							'name' => '_price_'.$city->term_id,
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => 'zł',
							'maxlength' => '',
						);
					}
				}
				
				acf_add_local_field_group(array (
					'key' => 'group_596f617a89485',
					'title' => 'Usługa',
					'fields' => array (
						array (
							'key' => 'field_59707e0a48251',
							'label' => 'Oferta',
							'name' => '',
							'type' => 'tab',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'placement' => 'top',
							'endpoint' => 0,
						),
						array (
							'key' => 'field_596f619a5159a',
							'label' => 'Oferta',
							'name' => '_service_detalis',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => 'field_596f61bc5159b',
							'min' => 0,
							'max' => 0,
							'layout' => 'block',
							'button_label' => 'Dodaj element',
							'sub_fields' => array (
								array (
									'key' => 'field_596f61bc5159b',
									'label' => 'Nagłówek',
									'name' => '_name',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'maxlength' => '',
								),
								array (
									'key' => 'field_596f61ca5159c',
									'label' => 'Tekst',
									'name' => '_text',
									'type' => 'wysiwyg',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'tabs' => 'all',
									'toolbar' => 'full',
									'media_upload' => 0,
									'delay' => 0,
								),
							),
						),
						array (
							'key' => 'field_59707e1448252',
							'label' => 'Cennik',
							'name' => '',
							'type' => 'tab',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'placement' => 'top',
							'endpoint' => 0,
						),
						array (
							'key' => 'field_59707e1b48253',
							'label' => 'Cennik',
							'name' => '_service_pricelist',
							'type' => 'repeater',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'collapsed' => '',
							'min' => 0,
							'max' => 0,
							'layout' => 'table',
							'button_label' => 'Dodaj element',
							'sub_fields' => $pricelist_subfields,
						),
					),
					'location' => array (
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => 'service',
							),
						),
					),
					'menu_order' => 0,
					'position' => 'acf_after_title',
					'style' => 'default',
					'label_placement' => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen' => '',
					'active' => 1,
					'description' => 'Registered via php',
				));
				
			}
				
		}

		public function acf_pricelists_where( $where ) {
			
			$where = str_replace( "meta_key = '_service_pricelist_%", "meta_key LIKE '_service_pricelist_%", $where );
		
			return $where;
		
		}

	}
	
	new RFS_ACF;
}
?>