<?php  
global $cortenCart;

if( !class_exists('cortenCart') ) {

	class cortenCart {

		private static $cart_instance = null;

		private $cart_content;
		private $cart_id;
		private $cart_table;
		private $db;

		public function __construct() {
			$this->db 			= $GLOBALS['wpdb'];
			$this->cart_table 	= 'cart_contents';

			add_action( 'init', array($this, 'create_cart_id'), 10 );
			add_action( 'init', array($this, 'create_table'), 8 );

			// add_action( 'template_redirect', array($this, 'add_to_cart') );
			// add_action( 'template_redirect', array($this, 'remove_from_cart') );

			add_action( 'wp_ajax_add_to_cart', array($this, 'add_to_cart') );
			add_action( 'wp_ajax_nopriv_add_to_cart', array($this, 'add_to_cart') );
			
			add_action( 'wp_ajax_remove_from_cart', array($this, 'remove_from_cart') );
			add_action( 'wp_ajax_nopriv_remove_from_cart', array($this, 'remove_from_cart') );

			add_action( 'wp_ajax_update_cart', array($this, 'update_cart') );
			add_action( 'wp_ajax_nopriv_update_cart', array($this, 'update_cart') );
			
			add_action( 'wp_ajax_change_shipping_method', array($this, 'change_shipping_method') );
			add_action( 'wp_ajax_nopriv_change_shipping_method', array($this, 'change_shipping_method') );
			
			add_action( 'wp_ajax_checkout_proccess', array($this, 'checkout_proccess') );
			add_action( 'wp_ajax_nopriv_checkout_proccess', array($this, 'checkout_proccess') );

			// add_action( 'wp_ajax_make_payment', array($this, 'make_payment') );
			// add_action( 'wp_ajax_nopriv_make_payment', array($this, 'make_payment') );
		}

		/**
		 * This function will create database table for storing cart items
		 * @param n/a
		 * @return n/a
		*/
		public function create_table() {
			$charset_collate 	= $this->db->get_charset_collate();

			$sql = "CREATE TABLE {$this->db->prefix}$this->cart_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			cart_id varchar(500) NOT NULL,
			content longtext NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( array($sql) );
		}

		/**
		 * This function will create a user hashed id depending on user login status and store it in a cookie for not logged in users
		 * @param n/a
		 * @return n/a
		*/
		public function create_cart_id() {
			if( is_user_logged_in() ) {
				$this->create_logged_in_cart_id();
			}else {
				$this->create_non_logged_in_cart_id();
			}

			return $this->cart_id;
		}

		/**
		 * This function will create cart id for logged in user and move cart contents if there were any before logging in and clear old cookie
		 * @param n/a
		 * @return n/a
		*/
		public function create_logged_in_cart_id() {
			$this->cart_id = wp_hash( get_current_user_id() );
			if( isset($_COOKIE['_shop_cart_session'] ) ) {
				if( $this->cart_exists( $_COOKIE['_shop_cart_session'] ) ) {
					$this->move_cart( $_COOKIE['_shop_cart_session'], $this->cart_id );
				}
			}
			setcookie('_shop_cart_session', '', time()-3600, "/", "", 0);
		}

		/**
		 * This function will create cart id for not logged in user
		 * @param n/a
		 * @return n/a
		*/
		public function create_non_logged_in_cart_id() {
			if( isset($_COOKIE['_shop_cart_session']) ) {
				$this->cart_id = $_COOKIE['_shop_cart_session'];
			}else {
				$this->cart_id = wp_hash( session_id() );
				setcookie('_shop_cart_session', $this->cart_id, time()+ 86400 * 30 * 12, "/", "", 0);
			}
		}

		/**
		 * This function will move cart contents after logging in (if there were any before logging in)
		 * @param $old_cart_id, $new_cart_id
		 * @return n/a
		*/
		private function move_cart($old_cart_id, $new_cart_id) {
			$old_cart = $this->get_cart_content($old_cart_id);
			
			if( $old_cart === false ) return;

			if( $this->cart_exists( $new_cart_id ) ) {
				$this->db->update(
					$this->db->prefix.$this->cart_table,
					array('content' => serialize($old_cart)),
					array('cart_id' => $new_cart_id),
					array('%s'),
					array('%s')
				);
			}else {
				$this->db->insert(
					$this->db->prefix.$this->cart_table,
					array(
						'cart_id' => $new_cart_id,
						'content' => serialize($old_cart)
					)
				);
			}
			$this->db->delete(
				$this->db->prefix.$this->cart_table,
				array('cart_id' => $old_cart_id),
				array('%s')
			);
		}

		/**
		 * This function will check if user has a cart
		 * @param $cart_id
		 * @return number
		*/
		private function cart_exists($cart_id = false) {
			if( !$cart_id ) $cart_id = $this->cart_id;
			return $this->db->get_var( $this->db->prepare("SELECT COUNT(*) FROM {$this->db->prefix}$this->cart_table WHERE cart_id = '%s'", $cart_id) );
		} 

		/**
		 * This function will add product to cart
		 * @param 
		 * @return 
		*/
		public function add_to_cart() {
			if( !wp_verify_nonce(  $_POST['nonce'], 'check_cart_nonce' ) ) {
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$product_id 	= absint( $_POST['product_id'] );
			$product_qty 	= absint( $_POST['product_qty'] );
			
			$is_available = CORTEN_SHOP::is_product_available( $product_id );

			if( !$is_available || $product_qty < 1 ) {
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$cart_id 		= $this->create_cart_id();
			$cart_items = $this->get_cart_content($cart_id);

			if( is_array( $cart_items ) ) {
				$update_qty = array_search($product_id, array_column($cart_items['products'], 'id'));

				if( $update_qty === false ) {
					$cart_items['products'][] = array(
						'id' 	=> $product_id,
						'qty' => $product_qty,
					);
				}else {
					$cart_items['products'][$update_qty]['qty'] += $product_qty;
				}
				
				$cart_items['shipment']['id'] = CORTEN_SHOP::shipment_methods( true );
				
				$update_cart = $this->db->update(
					$this->db->prefix.$this->cart_table,
					array('content' => serialize($cart_items)),
					array('cart_id' => $this->cart_id),
					array('%s'),
					array('%s')
				);

				if( $update_cart === false ) {
					wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
				}
			}else {
				
				$cart_items['products'][] = array(
					'id' 	=> $product_id,
					'qty' => $product_qty,
				);

				if( !isset( $cart_items['shipment'] ) ) {
					$cart_items['shipment']['id'] = CORTEN_SHOP::shipment_methods( true );
				}
				
				$add_to_cart = $this->db->insert(
					$this->db->prefix.$this->cart_table,
					array(
						'cart_id' => $this->cart_id,
						'content' => serialize($cart_items)
					)
				);
				
				if( $add_to_cart == false ) {
					wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
				}
			}

			$message = pll_trans('Produkt', true);
			$message .= ' <strong><a href="'.esc_url( get_permalink( $product_id ) ).'">'.esc_html( get_the_title( $product_id ) ).'</a></strong> ';
			$message .= pll_trans('został dodany do koszyka', true);

			$header_cart = CORTEN_SHOP::shop_header_cart();

			wp_send_json( array('ok', $message, $header_cart ) );
		}

		/**
		 * This function will remove product from cart
		 * @param n/a
		 * @return n/a
		*/
		public function remove_from_cart() {
			if( !wp_verify_nonce(  $_POST['nonce'], 'check_cart_nonce' ) ) {
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$product_id 	= absint( $_POST['product_id'] );

			$is_available = CORTEN_SHOP::is_product_available( $product_id );
			
			if( !$is_available ) {
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$cart_id 			= $this->create_cart_id();
			$cart_content = $this->get_cart_content( $cart_id );
			$cart_items 	= $cart_content['products'];
			$shipment 		= absint( $_POST['shipment'] );
			$product_types_in_cart = array();
			$remove_shipping = false;

			if( $cart_items ) {
				$item_to_remove = array_search($product_id, array_column($cart_items, 'id'));
				
				array_splice($cart_items, $item_to_remove, 1);

				$cart_content['shipment']['id'] = $shipment;
				$cart_content['products'] = $cart_items;
				$remove = $this->db->update(
					$this->db->prefix.$this->cart_table,
					array('content' => serialize($cart_content)),
					array('cart_id' => $cart_id),
					array('%s'),
					array('%s')
				);

				if( $remove === false ) {
					wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
				}

				foreach($cart_items as $product) {
					$product_type = get_post_type( $product['id'] );
					$product_types_in_cart[] = $product_type;
				}

				if( !in_array('corten-product', $product_types_in_cart) ) {
					$remove_shipping = true;
				}

				$products_left_in_cart 	= count( $cart_items );
				$summary 								= CORTEN_SHOP::cart_summary( $cart_items, true, $shipment);
				$header_cart 						= CORTEN_SHOP::shop_header_cart( $cart_items);

				if( $products_left_in_cart == 0 ) {
					$cart_content['shipment']['id'] = 0;
					$this->db->update(
						$this->db->prefix.$this->cart_table,
						array('content' => serialize($cart_content)),
						array('cart_id' => $cart_id),
						array('%s'),
						array('%s')
					);
				}

				wp_send_json( array('ok', $product_id, $products_left_in_cart, $summary, $header_cart, $remove_shipping) );
			}

			wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
		}

		/**
		 * This function will updates products qty in cart
		 * @param n/a
		 * @return n/a
		*/
		public function update_cart() {
			if( !wp_verify_nonce(  $_POST['nonce'], 'check_cart_nonce' ) ) {
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$products 		= $_POST['products'];
			$shipment 		= absint( $_POST['shipment'] );
			$cart_id 			= $this->create_cart_id();
			$cart_content = $this->get_cart_content( $cart_id );
			$cart_items 	= $cart_content['products'];
			
			if( is_array($products) && $cart_items ) {
				foreach($products as $id => $qty) {

					$is_available = CORTEN_SHOP::is_product_available( $id );
					
					if( !$is_available ) {
						wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
					}

					$in_cart = array_search($id, array_column($cart_items, 'id'));
					
					if( $cart_items[$in_cart]['qty'] != $qty ) {
						$cart_items[$in_cart]['qty'] = $qty;
					}
				}

				$cart_content['shipment']['id'] = $shipment;
				$cart_content['products'] = $cart_items;
				$update = $this->db->update(
					$this->db->prefix.$this->cart_table,
					array('content' => serialize($cart_content)),
					array('cart_id' => $cart_id),
					array('%s'),
					array('%s')
				);

				if( $update === false ) {
					wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
				}

				$summary 					= CORTEN_SHOP::cart_summary( $cart_items, true, $shipment);
				$products_totals 	= CORTEN_SHOP::cart_products_prices( $products, true );

				wp_send_json( array('ok', $summary, $products_totals) );
			}
			wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
		}

		private function empty_cart() {
			$cart_id 			= $this->create_cart_id();
			$cart_content = $this->get_cart_content( $cart_id );

			if( isset($cart_content['shipment']) ) {
				unset( $cart_content['shipment'] );
			}

			if( isset($cart_content['products']) ) {
				$cart_content['products'] = array();
			}

			$update = $this->db->update(
				$this->db->prefix.$this->cart_table,
				array('content' => serialize($cart_content)),
				array('cart_id' => $cart_id),
				array('%s'),
				array('%s')
			);

			return $cart_content;
		}

		public function change_shipping_method() {
			if( !wp_verify_nonce(  $_POST['nonce'], 'check_cart_nonce' ) ) {
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$shipment 		= absint( $_POST['shipment'] );
			$shipment_item= esc_html( $_POST['shipment_item'] );
			$cart_id 			= $this->create_cart_id();
			$cart_content = $this->get_cart_content( $cart_id );
			$cart_items 	= $cart_content['products'];

			if( is_array($cart_content) && $cart_items ) {
				$cart_content['shipment']['id'] 	= $shipment;
				$cart_content['shipment']['item'] = $shipment_item;
				$update = $this->db->update(
					$this->db->prefix.$this->cart_table,
					array('content' => serialize($cart_content)),
					array('cart_id' => $cart_id),
					array('%s'),
					array('%s')
				);

				if( $update === false ) {
					wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
				}

				$summary = CORTEN_SHOP::cart_summary( $cart_items, true, $shipment);
				wp_send_json( array('ok', $summary) );
			}
			wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
		}

		/**
		 * This function will update shipment method in cart from outside ie. in shop cart_shipment_method function 
		 * @param $shipment (int)
		 * @return n/a
		*/
		// public function update_shipment_method($shipment_id) {
		// 	$cart_content = $this->get_cart_content( $this->cart_id );
		// 	$cart_content['shipment'] = $shipment_id;

		// 	$this->db->update(
		// 		$this->db->prefix.$this->cart_table,
		// 		array('content' => serialize($cart_content)),
		// 		array('cart_id' => $this->cart_id),
		// 		array('%s'),
		// 		array('%s')
		// 	);
		// }

		public function checkout_proccess() {
			if( !wp_verify_nonce(  $_POST['nonce'], 'check_cart_nonce' ) ) {
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$form_data 					= $_POST;
			$customer_type 			= sanitize_text_field( $form_data['customer-type'] );
			$shipment_different = $form_data['customer-shipment-different'] ? 'yes' : '';
			$payment_type 			= sanitize_text_field(  $form_data['customer-payment-type'] );

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
							$errors['empty'] = $this->get_message('error', 'empty');
							$invalid_fields[$name] = $errors;
						}else {
							if( $name == 'customer-email' ) {
								if( !is_email( $value ) ) {
									$errors['email'] = $this->get_message('error', 'email');
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
				wp_send_json( array('error', 'validation', $invalid_fields, $this->get_message('error', 'invalid'), $orderno) );
			}

			$order_time 	= current_time( 'timestamp');
			$order_no 		= $this->order_number('order');
			$cart_id 			= $this->create_cart_id();
			$cart_content = $this->get_cart_content( $cart_id );
			$cart_items 	= $cart_content['products'];

			$products = CORTEN_SHOP::cart_products_prices( $cart_content['products'] );
			$summary  = CORTEN_SHOP::cart_summary( $cart_content['products'], false, $cart_content['shipment']['id'] );

			$code = 'CORTEN_'.strtoupper( bin2hex( openssl_random_pseudo_bytes(5) ) );

			$meta_data = array(
				'_order_status'						=> 1,
				'_order_number' 					=> $order_no,
				'_order_date'							=> $order_time,
				'_order_customer_type' 		=> $customer_type,
				'_order_products'					=> count( $products ),
				'_order_totals'						=> $summary,
				'_order_shipment_method' 	=> get_the_title( $cart_content['shipment']['id'] ),
				'_order_shipment_item' 		=> $cart_content['shipment']['item'],
				'_order_user_id'					=> is_user_logged_in() ? get_current_user_id() : 0,
				'_order_code'							=> $code
			);

			foreach($form_fields as $name => $value) {
				$meta_key = '_order_'.str_replace('-', '_', $name);
				$meta_data[$meta_key] = $value;
			}

			$i=1; foreach( $products as $product ) {
				$product_id 	= absint( $product['id'] );
				$product_type = get_post_type( $product['id'] );

				$meta_data['_order_product_'.$i] = array(
					'id' 				=> $product_id,
					'name' 			=> get_the_title( $product_id ),
					'qty'				=> absint( $product['qty'] ),
					'sum_price'	=> $product['sum']
				);
			$i++; }

			$new_order 	= array(
				'post_title'		=> 'Product Order '.$order_no.' at '.$order_time,
				'post_status' 	=> 'publish',
				'post_type'			=> 'corten-product-order',
				'meta_input'		=> $meta_data
			);

			$this->db->query('START TRANSACTION');
			$create_order = wp_insert_post($new_order);

			if( is_wp_error( $create_order ) ) {
				$this->remove_order_number( $order_no );
				$this->db->query('ROLLBACK');
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}

			$message_admin = CORTEN_EMAIL::email_template(
				'new-order', 
				array(
					'send_to'   => 'admin',
					'order_id'  => $create_order
				) 
			);
			$message_customer = CORTEN_EMAIL::email_template(
				'new-order', 
				array(
					'send_to'   => 'customer',
					'order_id'  => $create_order
				) 
			);

			$subject = get_bloginfo('name').' - '.__('New order', 'rfswp');

			$send_to_admin 		= wp_mail( get_option('admin_email'), $subject, $message_admin );
			$send_to_customer = wp_mail( $form_data['customer-email'], $subject, $message_customer );

			if( !$send_to_admin || !$send_to_customer ) {
				$this->db->query('ROLLBACK');
				wp_send_json( array('error', 'error', $this->get_message('error', 'error')) );
			}
			
			CORTEN_SHOP::update_stock( $products );
			$this->empty_cart();

			if( $payment_type == 'transfer' ) {
				$redirect_url = CORTEN_SHOP::order_summary_page_url('bank-transfer/completed').'/'.$code;
			}

			if( $payment_type == 'online' ) {
				$redirect_url = CORTEN_TPAY::payment_link( 
					CORTEN_SHOP::format_price( $summary['to_pay'], true ), 
					current_lang(), 
					'Corten Medic - Zamówienie '.$order_no, 
					$meta_data['_order_customer_email'], 
					$meta_data['_order_customer_first_name'].' '.$meta_data['_order_customer_last_name'], 
					$meta_data['_order_customer_address_street'].' '.$meta_data['_order_customer_address_number'], 
					$meta_data['_order_customer_address_city'], 
					$meta_data['_order_customer_address_postcode'], 
					$meta_data['_order_customer_phone'], 
					$code
				);
			}

			$this->db->query('COMMIT');
			wp_send_json( array('ok', $redirect_url) );
		}

		public function get_message($type, $message) {
			$messages = array(
        'error' => array(
          'error'   => pll_trans('Wystąpił błąd. Odśwież stronę i spróbuj ponownie.', true),
          'invalid'	=> pll_trans('Wystąpiły błędy formularza. Sprawdź wszystkie pola i spróbuj ponownie', true),
          'empty'		=> pll_trans('To pole jest wymagane', true),
          'email'		=> pll_trans('Nieprawidłowy adres email', true),
        ),
        'success' => array(
          
        )
			);
			
			return $messages[$type][$message];
		}

		public function order_number($type) {
			$this->order_numbers_table();

			switch($type) {
				case 'order':
					$month 	= absint( current_time('m') );
					$year 	= absint( current_time('Y') );

					$order_nos = $this->db->get_row( $this->db->prepare( "SELECT MAX(number) as last_number FROM {$this->db->prefix}corten_order_numbers WHERE month = '%d' AND year = '%d'", array( $month, $year ) ) );
					
					$new_number = $order_nos === NULL ? 1 : $order_nos->last_number+1;

					$order_no = $new_number.'-'.$month.'-'.$year;	
					
					$insert_no = $this->db->insert(
						$this->db->prefix.'corten_order_numbers',
						array(
							'number'=> $order_no,
							'month'	=> $month,
							'year'	=> $year
						),
						array('%d', '%d', '%d')
					);
					
					return $order_no;	

					break;
			}
		}

		private function remove_order_number($order_number) {
			$data = explode('-', $order_number);

			$this->db->delete( 
				$this->db->prefix.'corten_order_numbers', 
				array(
					'number'=> $data[0],
					'month'	=> $data[1],
					'year'	=> $data[2]
				), 
				array('%d', '%d', '%d')
			);
		}

		protected function order_numbers_table() {
			$charset_collate 	= $this->db->get_charset_collate();
	
			$sql = "CREATE TABLE IF NOT EXISTS {$this->db->prefix}corten_order_numbers (
			id int(11) AUTO_INCREMENT NOT NULL,
			number int(11) NOT NULL,
			month int(11) NOT NULL,
			year int(11) NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";
	
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( array($sql) );
		}

		/**
		 * This function will get cart contents
		 * @param n/a
		 * @return array
		*/
		public function get_cart_content($cart_id = false, $products_only = false, $shipment = false) {
			if( !$cart_id ) $cart_id = $this->cart_id;

			$content = $this->db->get_row( $this->db->prepare("SELECT content FROM {$this->db->prefix}$this->cart_table WHERE cart_id = '%s'", $cart_id) );
			$this->cart_content = $content ? unserialize($content->content) : $content;

			if( $shipment ) {
				return $this->cart_content['shipment']['id'];
			}
			
			return $products_only ? $this->cart_content['products'] : $this->cart_content;
		}

	}

	$cortenCart = new cortenCart();
}

/**
 * This function will return global cart object
 * @param n/a
 * @return object
*/
// function shop_cart() {
// 	global $diagCart;
// 	return $diagCart;
// }
?>