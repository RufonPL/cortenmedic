<?php
global $wpdb;

$ip_table = array(
	'195.149.229.109',
	'148.251.96.163', 
	'178.32.201.77', 
	'46.248.167.59', 
	'46.29.19.106',
	'176.119.38.175'
);

if( in_array( $_SERVER['REMOTE_ADDR'], $ip_table ) && !empty( $_POST ) ) {

	$seller_id            = sanitize_text_field( $_POST['id'] );
	$transaction_status   = sanitize_text_field( $_POST['tr_status'] );
	$transaction_id       = sanitize_text_field( $_POST['tr_id'] );
	$tr_amount            = sanitize_text_field( $_POST['tr_amount'] );
	$tr_paid	            = sanitize_text_field( $_POST['tr_paid'] );
	$error                = sanitize_text_field( $_POST['tr_error'] );
	$crc                  = sanitize_text_field( $_POST['tr_crc'] );
	$email                = sanitize_text_field( $_POST['tr_email'] );
  $md5sum               = sanitize_text_field( $_POST['md5sum'] );

	if( $transaction_status == 'TRUE' && $error == 'none' ) {
    $order_id = CORTEN_SHOP::order_exists( $crc );

    if( $order_id ) {
      $code = CORTEN_SHOP::order_data_item($order_id, 'code');

      if( $code == $crc ) {
        if( $tr_amount == $tr_paid ) {
          $paid = 'full';
        }else if( $tr_paid < $tr_amount && $tr_paid > 0 ) {
          $paid = 'less';	
        }else if( $tr_paid > $tr_amount ) {
          $paid = 'more';	
        }else {
          $paid = 'none';
        }

        if( $paid == 'none' ) {
          $status = 2;
        }else {
          $status = 3;
        }

        update_post_meta( $order_id, '_order_transaction_id', $transaction_id );
        update_post_meta( $order_id, '_order_paid_amount', $tr_paid );
        update_post_meta( $order_id, '_order_paid_status', $paid );

        CORTEN_SHOP::update_order_status($status, $order_id);

        if( $paid == 'full' ) {
          $customer_email = CORTEN_SHOP::order_data_item($order_id, 'customer_email');

          $message_admin = CORTEN_EMAIL::email_template(
            'order-completed', 
            array(
              'send_to'   => 'admin',
              'order_id'  => $order_id
            ) 
          );
          $message_customer = CORTEN_EMAIL::email_template(
            'order-completed', 
            array(
              'send_to'   => 'customer',
              'order_id'  => $order_id
            ) 
          );
    
          $subject = get_bloginfo('name').' - '.__('Order Completed', 'rfswp');
    
          $send_to_customer = wp_mail( $customer_email, $subject, $message_customer );
          $send_to_admin 		= wp_mail( get_option('admin_email'), $subject, $message_admin );
        }
      }
    }
	}

}else {

}

echo 'TRUE';