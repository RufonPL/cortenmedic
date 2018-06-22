<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

if(isset($_GET['view'], $_GET['form_id']) && $_GET['view'] == 'Duplicate' && (int)$_GET['form_id'] != 0) {
	global $wpdb;

	$original = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'fm_forms WHERE form_id = '.(int)$_GET['form_id']);
	if($original != null) {
		$wpdb->insert(
			$wpdb->prefix.'fm_forms',
			array(
				'freshmail_list_id' => $original->freshmail_list_id,
				'freshmail_list_name' => $original->freshmail_list_name,
				'freshmail_form_var' => $original->freshmail_form_var,
				'insert_date' => date('Y-m-d H:i:s'),
				'last_edited' => date('Y-m-d H:i:s')
			),
			array('%s', '%s', '%s', '%s', '%s')
		);
	}

}