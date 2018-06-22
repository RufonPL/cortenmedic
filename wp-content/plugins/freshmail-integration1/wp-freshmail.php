<?php
/**
 * Plugin Name: FreshMail for WordPress
 * Plugin URI: http://freshmail.com/plugin/wordpress-newsletter/
 * Description: FreshMail is an email marketing tool for creating and sending amazing newsletters. Our intuitive system leads users from campaign planning and creation to final reports. A free account lets you send up to 2000 messages to a maximum of 500 recipients each month.
 * Version: 2.1.8
 * Author: Borbis Media
 * Author URI: http://www.borbis.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

// plugin use classes with namespaces and anonymous functions
if (version_compare(PHP_VERSION, '5.4.0', '<')) {
	add_action('admin_notices', 'freshmailPhpVersionAlert');
} else {

	// Define plugin directory for inclusions
	if (!defined('WP_FRESHMAIL_DIR')) {
		define('WP_FRESHMAIL_DIR', dirname(__FILE__));
	}

	// Define plugin URL for assets
	if (!defined('WP_FRESHMAIL_URL')) {
		define('WP_FRESHMAIL_URL', plugins_url('', __FILE__));
	}

	// Define plugin version for upgrade
	if (!defined('WP_FRESHMAIL_VERSION')) {
		define('WP_FRESHMAIL_VERSION', '2.1.1');
	}

	add_action('plugins_loaded', function (){

		$currentVersion = get_option('WP_FRESHMAIL_VERSION', 0);
		if (version_compare($currentVersion, WP_FRESHMAIL_VERSION, '<')) {

			global $wpdb;

			require_once(ABSPATH.'wp-admin/includes/upgrade.php');

			$sql = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'freshmail_stats (
							form_id VARCHAR( 100 ) NOT NULL,
							referer VARCHAR( 100 ) NOT NULL,
							insert_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
							) DEFAULT CHARSET = utf8;';
			dbDelta($sql);

			$sql = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'freshmail_forms (
							form_id INT(11) NOT NULL AUTO_INCREMENT,
							freshmail_list_id VARCHAR(50) NOT NULL DEFAULT \'\',
							freshmail_list_name VARCHAR(255) NOT NULL DEFAULT \'\',
							freshmail_form_var text NULL,
							insert_date datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
							last_edited datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
							UNIQUE KEY form_id (form_id)
							) DEFAULT CHARSET=utf8;';
			dbDelta($sql);

			// check if old table fm_form exist
			$fm_forms = $wpdb->get_row('SELECT table_name FROM information_schema.tables WHERE table_name LIKE "'.$wpdb->prefix.'fm_forms"');
			if (!empty($fm_forms)) {
				$data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'fm_forms', ARRAY_A);

				if (!empty($data)) {
					//check if this column is from freshmail !
					$correct = true;
					foreach (array_keys(reset($data)) as $row) {
						if (!in_array($row, array('form_id', 'freshmail_list_id', 'freshmail_list_name', 'freshmail_form_var', 'insert_date', 'last_edited'))) {
							$correct = false;
						}
					}

					if ($correct === true) {
						foreach ($data as $row) {
							unset($row['form_id']);
							$wpdb->insert($wpdb->prefix.'freshmail_forms', $row);
						}
					}
				}
			}
			$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'fm_forms');

			// check if old table fm_mails exist
			$stats = $wpdb->get_row('SELECT table_name FROM information_schema.tables WHERE table_name LIKE "'.$wpdb->prefix.'fm_mails"');
			if (!empty($stats)) {
				$data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'fm_mails', ARRAY_A);

				if (!empty($data)) {
					//check if this collumn is from freshmail !
					$correct = true;
					foreach (array_keys(reset($data)) as $row) {
						if (!in_array($row, array('form_id', 'referer', 'insert_date', 'mail_id', 'email', 'ext_fields'))) {
							$correct = false;
						}
					}

					if ($correct === true) {
						foreach ($data as $row) {
							$stat = array(
								'form_id' => $row['form_id'],
								'referer' => $row['referer'],
								'insert_date' => $row['insert_date']
							);
							$wpdb->insert($wpdb->prefix.'freshmail_stats', $stat);
						}
					}
				}
			}
			$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'fm_mails');

			$oldCustomThemes = get_option('freshmail_custom_theme');

			if ($oldCustomThemes === false) {

				$defaultThemes = serialize(array(
					__('Black_and_white', 'wp_freshmail') => array(
						'form_container' => array(
							'width' => 300,
							'width2' => 'px',
							'background_color' => '#ffffff',
							'border_color' => '#000000',
							'border_width' => 0,
							'rounded_corners' => 5,
							'padding1' => 0,
							'padding2' => 24,
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#000000',
							'text_size' => 18,
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#000000',
							'text_size' => 11,
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => 100,
							'select_width' => 'px',
							'position' => 'clear:left;display:block;',
							'text_color' => '#000000',
							'text_size' => 9,
							'select_text_style' => 'font-style:normal;',
							'display_labels' => 'yes',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => 100,
							'select_field_width' => '%',
							'height' => 35,
							'select_field_height' => 'px',
							'text_color' => '#000000',
							'text_size' => 12,
							'border_color' => '#b7b7b7',
							'border_width' => 1,
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'no',
							'checked' => 'no',
							'text_color' => '#000000',
							'text_size' => 9,
							'text_aligment' => 'left',
						),
						'button' => array(
							'width' => 120,
							'select_width' => 'px',
							'height' => 40,
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => 11,
							'text_color' => '#ffffff',
							'background_color' => '#000000',
							'border_color' => '#8c8c8c',
							'border_width' => 0,
							'rounded_corners' => 5,
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('Yellow', 'wp_freshmail') => array(
						'name' => '',
						'form_container' => array(
							'width' => 400,
							'width2' => 'px',
							'background_color' => '#fef200',
							'border_color' => '#000000',
							'border_width' => 0,
							'rounded_corners' => 5,
							'padding1' => 5,
							'padding2' => 15,
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#000000',
							'text_size' => 20,
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#000000',
							'text_size' => 12,
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => 25,
							'select_width' => '%',
							'position' => 'display:block;float:left;',
							'text_color' => '#0a0a0a',
							'text_size' => 12,
							'select_text_style' => 0,
							'display_labels' => 'yes',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => 74,
							'select_field_width' => '%',
							'height' => 30,
							'select_field_height' => 'px',
							'text_color' => '#000000',
							'text_size' => 10,
							'border_color' => '#dddddd',
							'border_width' => 1,
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'no',
							'text_color' => '#000000',
							'text_size' => 10,
							'text_aligment' => 'left',
						),
						'button' => array(
							'width' => 100,
							'select_width' => '%',
							'height' => 40,
							'select_height' => 'px',
							'select_aligment' => 'left',
							'text_size' => 12,
							'text_color' => '#ffffff',
							'background_color' => '#000000',
							'border_color' => '#383838',
							'border_width' => 0,
							'rounded_corners' => 5,
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('Grey', 'wp_freshmail') => array(
						'form_container' => array(
							'width' => 400,
							'width2' => 'px',
							'background_color' => '#ededed',
							'border_color' => '#000000',
							'border_width' => 1,
							'rounded_corners' => 0,
							'padding1' => 0,
							'padding2' => 20,
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#000000',
							'text_size' => 14,
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#000000',
							'text_size' => 11,
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => 100,
							'select_width' => 'px',
							'position' => 'clear:left;display:block;',
							'text_color' => '#000000',
							'text_size' => 9,
							'select_text_style' => 'font-style:normal;',
							'display_labels' => 'yes',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => 100,
							'select_field_width' => '%',
							'height' => 40,
							'select_field_height' => 'px',
							'text_color' => '#000000',
							'text_size' => 12,
							'border_color' => '#ededed',
							'border_width' => 1,
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'no',
							'checked' => 'no',
							'text_color' => '#000000',
							'text_size' => 9,
							'text_aligment' => 'left',
						),
						'button' => array(
							'width' => 120,
							'select_width' => 'px',
							'height' => 40,
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => 11,
							'text_color' => '#000000',
							'background_color' => '#ededed',
							'border_color' => '#000000',
							'border_width' => 2,
							'rounded_corners' => 0,
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('Blue', 'wp_freshmail') => array(
						'form_container' => array(
							'width' => 300,
							'width2' => 'px',
							'background_color' => '#1b3558',
							'border_color' => '#f14231',
							'border_width' => 0,
							'rounded_corners' => 0,
							'padding1' => 0,
							'padding2' => 40,
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#ffffff',
							'text_size' => 20,
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#ffffff',
							'text_size' => 11,
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => 100,
							'select_width' => 'px',
							'position' => 'clear:left;display:block;',
							'text_color' => '#ffffff',
							'text_size' => 9,
							'select_text_style' => 'font-style:normal;',
							'display_labels' => 'yes',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => 100,
							'select_field_width' => '%',
							'height' => 30,
							'select_field_height' => 'px',
							'text_color' => '#5e5e5e',
							'text_size' => 11,
							'border_color' => '#b7b7b7',
							'border_width' => 1,
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'yes',
							'checked' => 'no',
							'text_color' => '#9aabb7',
							'text_size' => 9,
							'text_aligment' => 'center',
						),
						'button' => array(
							'width' => 120,
							'select_width' => 'px',
							'height' => 40,
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => 11,
							'text_color' => '#ffffff',
							'background_color' => '#f14231',
							'border_color' => '#8c8c8c',
							'border_width' => 0,
							'rounded_corners' => 5,
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('White_and_Red', 'wp_freshmail') => array(
						'form_container' => array(
							'width' => 300,
							'width2' => 'px',
							'background_color' => '#ffffff',
							'border_color' => '#000000',
							'border_width' => 1,
							'rounded_corners' => 0,
							'padding1' => 0,
							'padding2' => 14,
						),
						'text_header' => array(
							'display' => 'no',
							'text_color' => '#000000',
							'text_size' => 18,
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'yes',
							'text_color' => '#000000',
							'text_size' => 13,
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => 24,
							'select_width' => '%',
							'position' => 'display:block;float:left;',
							'text_color' => '#000000',
							'text_size' => 10,
							'select_text_style' => 'font-style:normal;',
							'display_labels' => 'yes',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => 75,
							'select_field_width' => '%',
							'height' => 30,
							'select_field_height' => 'px',
							'text_color' => '#000000',
							'text_size' => 12,
							'border_color' => '#b7b7b7',
							'border_width' => 1,
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'no',
							'checked' => 'no',
							'text_color' => '#000000',
							'text_size' => 9,
							'text_aligment' => 'left',
						),
						'button' => array(
							'width' => 110,
							'select_width' => 'px',
							'height' => 35,
							'select_height' => 'px',
							'select_aligment' => 'right',
							'text_size' => 11,
							'text_color' => '#ffffff',
							'background_color' => '#f14231',
							'border_color' => '#8c8c8c',
							'border_width' => 0,
							'rounded_corners' => 0,
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('Green', 'wp_freshmail') => array(
						'form_container' => array(
							'width' => 350,
							'width2' => 'px',
							'background_color' => '#36c113',
							'border_color' => '#417d07',
							'border_width' => 0,
							'rounded_corners' => 5,
							'padding1' => 0,
							'padding2' => 20,
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#ffffff',
							'text_size' => 18,
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#417d07',
							'text_size' => 11,
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => 24,
							'select_width' => '%',
							'position' => 'display:block;float:left;',
							'text_color' => '#ffffff',
							'text_size' => 12,
							'select_text_style' => 'font-style:normal;',
							'display_labels' => 'yes',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => 75,
							'select_field_width' => '%',
							'height' => 30,
							'select_field_height' => 'px',
							'text_color' => '#5e5e5e',
							'text_size' => 11,
							'border_color' => '#b7b7b7',
							'border_width' => 1,
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'no',
							'checked' => 'no',
							'text_color' => '#417d07',
							'text_size' => 9,
							'text_aligment' => 'center',
						),
						'button' => array(
							'width' => 120,
							'select_width' => 'px',
							'height' => 40,
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => 11,
							'text_color' => '#ffffff',
							'background_color' => '#417d07',
							'border_color' => '#8c8c8c',
							'border_width' => 0,
							'rounded_corners' => 20,
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('Black', 'wp_freshmail') => array(
						'form_container' => array(
							'width' => 300,
							'width2' => 'px',
							'background_color' => '#000000',
							'border_color' => '#f14231',
							'border_width' => 0,
							'rounded_corners' => 0,
							'padding1' => 0,
							'padding2' => 40,
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#ffffff',
							'text_size' => 20,
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#ffffff',
							'text_size' => 11,
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => 100,
							'select_width' => 'px',
							'position' => 'clear:left;display:block;',
							'text_color' => '#ffffff',
							'text_size' => 9,
							'select_text_style' => 'font-style:normal;',
							'display_labels' => 'yes',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => 100,
							'select_field_width' => '%',
							'height' => 30,
							'select_field_height' => 'px',
							'text_color' => '#5e5e5e',
							'text_size' => 12,
							'border_color' => '#b7b7b7',
							'border_width' => 1,
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'yes',
							'checked' => 'no',
							'text_color' => '#c1c1c1',
							'text_size' => 9,
							'text_aligment' => 'center',
						),
						'button' => array(
							'width' => 130,
							'select_width' => 'px',
							'height' => 44,
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => 11,
							'text_color' => '#ffffff',
							'background_color' => '#000000',
							'border_color' => '#ffffff',
							'border_width' => 1,
							'rounded_corners' => 25,
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('green_only_placeholders', 'wp_freshmail') => array
					(
						'form_container' => array(
							'width' => '350',
							'width2' => 'px',
							'background_color' => '#36c113',
							'border_color' => '#417d07',
							'border_width' => '0',
							'rounded_corners' => '5',
							'padding1' => '0',
							'padding2' => '20',
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#ffffff',
							'text_size' => '18',
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#417d07',
							'text_size' => '11',
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => '0',
							'select_width' => '%',
							'position' => 'display:block;float:left;',
							'text_color' => '#ffffff',
							'text_size' => '12',
							'select_text_style' => 'font-style:normal;',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => '100',
							'select_field_width' => '%',
							'height' => '30',
							'select_field_height' => 'px',
							'text_color' => '#5e5e5e',
							'text_size' => '11',
							'border_color' => '#b7b7b7',
							'border_width' => '1',
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'no',
							'checked' => 'no',
							'text_color' => '#417d07',
							'text_size' => '9',
							'text_aligment' => 'center',
						),
						'button' => array(
							'width' => '120',
							'select_width' => 'px',
							'height' => '40',
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => '11',
							'text_color' => '#ffffff',
							'background_color' => '#417d07',
							'border_color' => '#8c8c8c',
							'border_width' => '0',
							'rounded_corners' => '20',
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('black_only_placeholders', 'wp_freshmail') => array
					(
						'form_container' => array(
							'width' => '300',
							'width2' => 'px',
							'background_color' => '#000000',
							'border_color' => '#f14231',
							'border_width' => '0',
							'rounded_corners' => '0',
							'padding1' => '0',
							'padding2' => '40',
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#ffffff',
							'text_size' => '20',
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#ffffff',
							'text_size' => '11',
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => '0',
							'select_width' => 'px',
							'position' => 'clear:left;display:block;',
							'text_color' => '#ffffff',
							'text_size' => '0',
							'select_text_style' => 'font-style:normal;',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => '100',
							'select_field_width' => '%',
							'height' => '30',
							'select_field_height' => 'px',
							'text_color' => '#5e5e5e',
							'text_size' => '12',
							'border_color' => '#b7b7b7',
							'border_width' => '1',
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'yes',
							'checked' => 'no',
							'text_color' => '#c1c1c1',
							'text_size' => '9',
							'text_aligment' => 'center',
						),
						'button' => array(
							'width' => '130',
							'select_width' => 'px',
							'height' => '44',
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => '11',
							'text_color' => '#ffffff',
							'background_color' => '#000000',
							'border_color' => '#ffffff',
							'border_width' => '1',
							'rounded_corners' => '25',
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
					__('blue_only_placeholders', 'wp_freshmail') => array
					(
						'form_container' => array(
							'width' => '300',
							'width2' => 'px',
							'background_color' => '#1b3558',
							'border_color' => '#f14231',
							'border_width' => '0',
							'rounded_corners' => '0',
							'padding1' => '0',
							'padding2' => '40',
						),
						'text_header' => array(
							'display' => 'yes',
							'text_color' => '#ffffff',
							'text_size' => '20',
							'text_alignment' => 'center',
						),
						'sub_header' => array(
							'display' => 'no',
							'text_color' => '#ffffff',
							'text_size' => '11',
							'text_alignment' => 'center',
						),
						'label' => array(
							'width' => '0',
							'select_width' => '%',
							'position' => 'clear:left;display:block;',
							'text_color' => '#ffffff',
							'text_size' => '0',
							'select_text_style' => 'font-style:normal;',
							'display_placeholders' => 'yes',
						),
						'field' => array(
							'width' => '100',
							'select_field_width' => '%',
							'height' => '30',
							'select_field_height' => 'px',
							'text_color' => '#5e5e5e',
							'text_size' => '11',
							'border_color' => '#b7b7b7',
							'border_width' => '1',
							'vertical_margin' => 0,
							'vertical_margin_type' => '%',
							'horizontal_margin' => 0,
							'horizontal_margin_type' => '%',
						),
						'checkbox_agreement' => array(
							'display' => 'yes',
							'checked' => 'no',
							'text_color' => '#9aabb7',
							'text_size' => '9',
							'text_aligment' => 'center',
						),
						'button' => array(
							'width' => '120',
							'select_width' => 'px',
							'height' => '40',
							'select_height' => 'px',
							'select_aligment' => 'center',
							'text_size' => '11',
							'text_color' => '#ffffff',
							'background_color' => '#f14231',
							'border_color' => '#8c8c8c',
							'border_width' => '0',
							'rounded_corners' => '5',
						),
						'button_hovered' => array(
							'background_color' => '',
							'border_color' => '',
							'text_color' => '',
						),
						'error_success' => array(
							'error_text_color' => '',
							'success_text_color' => '',
						),
					),
				));

				update_option('freshmail_custom_theme', $defaultThemes);
			}

			if(version_compare($currentVersion, 2.1, '<')) {
				delete_option('freshmail_api_connected');
				delete_option('freshmail_api_secret_key');
			}

			update_option('WP_FRESHMAIL_VERSION', WP_FRESHMAIL_VERSION);
		}

		if (!class_exists('Plugin\Newsletter\Freshmail')) {

			if (!class_exists('FmRestApi')) {
				require_once(WP_FRESHMAIL_DIR.'/vendor/class.rest.php');
			}

			load_plugin_textdomain('wp_freshmail', false, dirname(plugin_basename(__FILE__)).'/languages/');

			require_once(WP_FRESHMAIL_DIR.'/src/Plugin/Newsletter/Freshmail.php');
			new Plugin\Newsletter\Freshmail();

			if (!class_exists('Plugin\Newsletter\Widget\FreshmailWidget')) {
				require_once(WP_FRESHMAIL_DIR.'/src/Plugin/Newsletter/Widget/FreshmailWidget.php');
			}

			add_action('widgets_init', function (){
				register_widget('Plugin\Newsletter\Widget\FreshmailWidget');
			});
		}
	});
}

function freshmailPhpVersionAlert(){
	echo '<div class="error"><p>Freshmail Plugin requires php 5.3 or greater!</p></div>';
}