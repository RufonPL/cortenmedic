<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}
$uninstallAll = get_option('freshmail_uninstall_all', 'false');

if ($uninstallAll == 'true') {

	delete_option('WP_FRESHMAIL_VERSION');
	delete_option('freshmail_custom_theme');
	delete_option('freshmail_api_key');
	delete_option('freshmail_api_secret');
	delete_option('fm_sign_up_checkboxes');
	delete_option('freshmail_uninstall_all');

	global $wpdb;
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'freshmail_stats');
	$wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'freshmail_forms');

}