<?php  
if( !class_exists('CORTEN_PROFILE') ) {

  class CORTEN_PROFILE {

    public function __construct() {
      add_action( 'init', array($this, 'profile_tabs_rule') );
      add_action( 'init', array($this, 'profile_tabs_tags') );

      add_action( 'template_redirect', array($this, 'default_account_tab') );

      add_action( 'wp_ajax_save_personal_data', array($this, 'save_personal_data') );
      add_action( 'wp_ajax_nopriv_save_personal_data', array($this, 'save_personal_data') );
      
      add_action( 'show_user_profile', array($this, 'customer_fields_in_admin'), 9 );
      add_action( 'edit_user_profile', array($this, 'customer_fields_in_admin'), 9 );

      add_action( 'personal_options_update', array($this, 'save_customer_fields_in_admin') );
      add_action( 'edit_user_profile_update', array($this, 'save_customer_fields_in_admin') );

      add_action( 'admin_footer', array($this, 'user_js_scripts') );
      
    }

    public function profile_tabs_rule() {
      global $rfs_profile_page_id;
      $account_page_slug = get_post_field( 'post_name', $rfs_profile_page_id );

      add_rewrite_rule( 
        $account_page_slug.'/(szczegoly-konta|dane-osobowe|moje-zamowienia)/?$', 
        'index.php?pagename='.$account_page_slug.'&account_tab=$matches[1]', 
        'top' 
      );

      add_rewrite_rule( 
        $account_page_slug.'/moje-zamowienia/([0-9]+)?$', 
        'index.php?pagename='.$account_page_slug.'&account_tab=moje-zamowienia&customer_order=$matches[1]', 
        'top' 
      );
    }
    
    public function profile_tabs_tags() {
      add_rewrite_tag( '%account_tab%', '([^&]+)' );
      add_rewrite_tag( '%customer_order%', '([0-9]+)' );
    }

    public function default_account_tab() {
      global $rfs_profile_page_id;

      if( is_page( $rfs_profile_page_id ) ) {
        if( sanitize_text_field( get_query_var('account_tab') ) == '' ) {
          wp_safe_redirect( CORTEN_SHOP::shop_links('profile-main') ); exit;
        }
      }
    }

    public function save_personal_data() {
      global $cortenCart;
      global $wpdb;

      if( !wp_verify_nonce(  $_POST['nonce'], 'check_cart_nonce' ) ) {
				wp_send_json( array('error', 'error', $cortenCart->get_message('error', 'error')) );
      }
      
      $form_data 					= $_POST;
			$customer_type 			= sanitize_text_field( $form_data['customer-type'] );
			$shipment_different = $form_data['customer-shipment-different'] ? 'yes' : 'no';

			$required_fields	 	= $form_data['required-fields'] ? explode(',', $form_data['required-fields']) : array();

			$form_fields = array();
			$invalid_fields = array();

			foreach($form_data as $name => $value) {
				$shipment_fields 	= $shipment_different == 'yes'? true : strpos( $name, 'shipment' ) === false;
				$company_fields 	= $customer_type == 'company' ? true : strpos( $name, 'company' ) === false;

				if( strpos( $name, 'customer' ) !== false && $shipment_fields && $company_fields ) {
					$form_fields[$name] = sanitize_text_field( $value );
					$errors = array();

					if( in_array( $name, $required_fields ) ) {
						if( sanitize_text_field( $value ) == '' ) {
							$errors['empty'] = $cortenCart->get_message('error', 'empty');
							$invalid_fields[$name] = $errors;
						}else {
							if( $name == 'customer-email' ) {
								if( !is_email( $value ) ) {
									$errors['email'] = $cortenCart->get_message('error', 'email');
									$invalid_fields[$name] = $errors;
								}
							}

							// if( $name == 'customer-phone' ) {
							// 	if( !preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{3}$/i", $value) ) {
							// 		$errors['phone'] = true;
							// 		$invalid_fields[$name] = $errors;
							// 	}
							// }
						}
					}
				}
			}

			if( count( $invalid_fields ) > 0 ) {
				wp_send_json( array('error', 'validation', $invalid_fields, $cortenCart->get_message('error', 'invalid'), $orderno) );
      }
      
      $user_id    = get_current_user_id();
      $userdata   = get_userdata( $user_id );
      $form_fields['customer-shipment-different'] = $shipment_different;

      if( $shipment_different == 'no' ) {
        $form_fields['customer-shipment-street']    = '';
        $form_fields['customer-shipment-number']    = '';
        $form_fields['customer-shipment-city']      = '';
        $form_fields['customer-shipment-postcode']  = '';
        $form_fields['customer-shipment-country']   = '';
      }

      if( $customer_type == 'individual' ) {
        $form_fields['customer-company-name'] = '';
        $form_fields['customer-company-nip']  = '';
      }

      $wpdb->query('START TRANSACTION');

			foreach($form_fields as $name => $value) {
				$meta_key = '_'.str_replace('-', '_', $name);
        $value    = $meta_key == '_customer_email' ? $userdata->user_email : $value;

        $save = update_user_meta( $user_id, $meta_key, $value );

        if( $save == false && get_user_meta($user_id, $meta_key, true ) != $value ) {
          $wpdb->query('ROLLBACK');
          wp_send_json( array('error', 'error', $cortenCart->get_message('error', 'error')) );
        }
      }

      $update_user = wp_update_user( array(
        'ID'          => $user_id,
        'first_name'  => $form_fields['customer-first-name'],
        'last_name'   => $form_fields['customer-last-name']
      ) );

      if( is_wp_error( $update_user ) ) {
        $wpdb->query('ROLLBACK');
        wp_send_json( array('error', 'error', $cortenCart->get_message('error', 'error'), '4') );
      }
      
      $wpdb->query('COMMIT');
      wp_send_json( array('ok', $form_fields) );
    }

    public function customer_fields_in_admin($user) {
      _get_template_part( 'fields', 'shop/admin/user-fields', array('user_id' => absint( $user->ID )) );
    }

    public function save_customer_fields_in_admin($user_id) {
      if( isset( $_POST['_customer_fields_nonce'] ) && wp_verify_nonce( $_POST['_customer_fields_nonce'], '_customer_fields' )  ) {
        $customer_type      = sanitize_text_field( $_POST['_customer_type'] );
        $shipment_different = sanitize_text_field( $_POST['_customer_shipment_different'] );
  
        $customer_fields = array(
          'type'                => $customer_type,
          'company_name'        => $customer_type == 'company' ? sanitize_text_field( $_POST['_customer_company_name'] ) : '',
          'company_nip'         => $customer_type == 'company' ? sanitize_text_field( $_POST['_customer_company_nip'] ) : '',
          'phone'               => sanitize_text_field( $_POST['_customer_phone'] ),
          'email'               => sanitize_text_field( $_POST['email'] ),
          'first_name'          => sanitize_text_field( $_POST['first_name'] ),
          'last_name'           => sanitize_text_field( $_POST['last_name'] ),
          'address_street'      => sanitize_text_field( $_POST['_customer_address_street'] ),
          'address_number'      => sanitize_text_field( $_POST['_customer_address_number'] ),
          'address_city'        => sanitize_text_field( $_POST['_customer_address_city'] ),
          'address_postcode'    => sanitize_text_field( $_POST['_customer_address_postcode'] ),
          'address_country'     => sanitize_text_field( $_POST['_customer_address_country'] ),
          'shipment_different'  => $shipment_different,
          'shipment_street'     => $shipment_different == 'yes' ? sanitize_text_field( $_POST['_customer_shipment_street'] ) : '',
          'shipment_number'     => $shipment_different == 'yes' ? sanitize_text_field( $_POST['_customer_shipment_number'] ) : '',
          'shipment_city'       => $shipment_different == 'yes' ? sanitize_text_field( $_POST['_customer_shipment_city'] ) : '',
          'shipment_postcode'   => $shipment_different == 'yes' ? sanitize_text_field( $_POST['_customer_shipment_postcode'] ) : '',
          'shipment_country'    => $shipment_different == 'yes' ? sanitize_text_field( $_POST['_customer_shipment_country'] ) : '',
        );

        foreach($customer_fields as $name => $value) {
          $meta_key = '_customer_'.$name;
  
          $save = update_user_meta( $user_id, $meta_key, $value );
        }
      }else {
        add_filter('user_profile_update_errors', array($this, 'customer_fields_in_admin_error'), 10, 3);
      }
    }

    public function customer_fields_in_admin_error($errors, $update, $user) {
      $errors->add( 'customer_fields_error', __('An error occurred while saving data. Try again later.', 'rfswp') );
    }

    public function user_js_scripts() {
      global $pagenow;

      if( !is_admin() ) {
        return;
      }
      
      if( $pagenow == 'user-edit.php' || $pagenow == 'profile.php' ) {
        echo "
        <script type='text/javascript'>
          jQuery(document).ready( function($) {
            
            var customerType  = $('#_customer_type'),
              companyFields   = $('.customer-company-field'),
              personalFields  = $('.customer-personal-field'),
              shipment        = $('#_customer_shipment_different'),
              shipmentFields  = $('.customer-shipment-field');
      
            customerType.on('change', function() {
              var cType = $(this).val();
       
              if( cType == 'company' ) {
                companyFields.show();
                personalFields.hide();
              }else {
                companyFields.hide();
                personalFields.show();
              }
            });
        
            shipment.on('change', function() {
              var showShipment = $(this).val();
        
              if( showShipment == 'yes' ) {
                shipmentFields.show();
              }else {
                shipmentFields.hide();
              }
            });

            customerType.trigger('change');
            shipment.trigger('change');

          });
        </script>
        ";
  
        echo $scripts;
      }
    }

    // Helpers

    public static function is_tab($tab_name) {
      return sanitize_text_field( get_query_var('account_tab') ) == $tab_name;
    }

    public static function order_single_view() {
      return absint( get_query_var('customer_order') );
    }

    public static function customer_orders($user_id = false) {
      $user_id = $user_id ? $user_id : get_current_user_id();
      
      if( $user_id == 0 ) {
        return false;
      }

      $orders_list = array();

      $orders = new WP_Query( array(
         'post_type'      => 'corten-product-order',
         'posts_per_page' => -1,
         'post_status'    => 'publish',
         'meta_query'     => array(
           array(
            'key'      => '_order_user_id',
            'value'    => $user_id,
            'type'     => 'NUMERIC',
            'compare'  => '='
           )
         )
      ) );

      if( $orders->have_posts()) {
        while($orders->have_posts()) { $orders->the_post();
          $post_id = get_the_ID();
          $orders_list[] = array(
            'order_id'      => $post_id,
            'order_number'  => CORTEN_SHOP::order_data_item($post_id, 'number'),
            'order_date'    => CORTEN_SHOP::order_data_item($post_id, 'date'),
            'order_status'  => CORTEN_SHOP::order_data_item($post_id, 'status'),
            'totals'        => CORTEN_SHOP::order_data_item($post_id, 'totals')
          );
        }
      }; wp_reset_postdata();

      return $orders_list;
    }
    
    public static function order_details_link($order_id) {
      return CORTEN_SHOP::shop_links('profile-orders').'/'.$order_id;
    }

    public static function is_order_mine($order_id, $user_id = false) {
      $user_id = $user_id ? $user_id : get_current_user_id();
      
      if( $user_id == 0 ) {
        return false;
      }

      return CORTEN_SHOP::order_data_item($order_id, 'user_id') == $user_id;
    }

    public static function order_single_data($order_id) {
      $post_id            = $order_id;
      $products_no        = CORTEN_SHOP::order_data_item($post_id, 'products');
      $shipment_different = CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_different');

      $order_data = array(
        'order_number'        => CORTEN_SHOP::order_data_item($post_id, 'number'),
        'order_status'        => CORTEN_SHOP::order_data_item($post_id, 'status'),
        'order_date'          => CORTEN_SHOP::order_data_item($post_id, 'date'),
        'order_code'          => CORTEN_SHOP::order_data_item($post_id, 'code'),
        'payment_type'        => CORTEN_SHOP::order_data_item($post_id, 'customer_payment_type'),
        'first_name'          => CORTEN_SHOP::order_data_item($post_id, 'customer_first_name'),
        'last_name'           => CORTEN_SHOP::order_data_item($post_id, 'customer_last_name'),
        'customer_type'       => CORTEN_SHOP::order_data_item($post_id, 'customer_type'),
        'company_name'        => CORTEN_SHOP::order_data_item($post_id, 'customer_company_name'),
        'company_nip'         => CORTEN_SHOP::order_data_item($post_id, 'customer_company_nip'),
        'phone'               => CORTEN_SHOP::order_data_item($post_id, 'customer_phone'),
        'email'               => CORTEN_SHOP::order_data_item($post_id, 'customer_email'),
        'address_street'      => CORTEN_SHOP::order_data_item($post_id, 'customer_address_street'),
        'address_number'      => CORTEN_SHOP::order_data_item($post_id, 'customer_address_number'),
        'address_city'        => CORTEN_SHOP::order_data_item($post_id, 'customer_address_city'),
        'address_postcode'    => CORTEN_SHOP::order_data_item($post_id, 'customer_address_postcode'),
        'address_country'     => CORTEN_SHOP::order_data_item($post_id, 'customer_address_country'),
        'shipment_different'  => $shipment_different ? 'yes' : 'no',
        'shipment_street'     => CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_street'),
        'shipment_number'     => CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_number'),
        'shipment_city'       => CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_city'),
        'shipment_postcode'   => CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_postcode'),
        'shipment_country'    => CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_country'),
        'shipment_method'     => CORTEN_SHOP::order_data_item($post_id, 'shipment_method'),
        'shipment_item'       => CORTEN_SHOP::order_data_item($post_id, 'shipment_item'),
        'totals'              => CORTEN_SHOP::order_data_item($post_id, 'totals'),
        'products'            => array()
      );

      if( $products_no > 0 ) {
        for($i=1; $i<=$products_no; $i++) {
          $order_data['products'][] = CORTEN_SHOP::order_data_item($post_id, 'product_'.$i);
        }
      }

      return $order_data;
    }

  }

  new CORTEN_PROFILE;

}
?>