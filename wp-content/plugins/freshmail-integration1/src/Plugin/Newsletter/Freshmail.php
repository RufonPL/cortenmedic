<?php 
namespace Plugin\Newsletter;

use Exception;

class Freshmail
{
	private $fmApiKey, $fmApiSecret, $fmVerifySSL, $api, $connect, $fmSettings, $reportType, $reportArray;

	public function __construct()
	{

		if (!session_id()) {
			session_start();
		}
		/* login */
		if (isset($_POST['freshmail_api_key'], $_POST['freshmail_api_secret'], $_POST['freshmail_api_key_submit']) && is_super_admin()) {
			if (preg_match('/^[A-Za-z0-9_]+$/', $_POST['freshmail_api_key']) && preg_match('/^[A-Za-z0-9_]+$/', $_POST['freshmail_api_secret'])) {
				update_option('freshmail_api_key', $_POST['freshmail_api_key']);
				update_option('freshmail_api_secret', $_POST['freshmail_api_secret']);
			} else {
				add_action('admin_notices', function (){
					echo '<div class="error"><p>'.__('Keys can be only alphanumeric type!', 'wp_freshmail').'</p></div>';
				});
			}
		}

		if (isset($_GET['freshmail']) && $_GET['freshmail'] == 'logout' && is_super_admin()) {
			update_option('freshmail_api_key', null);
			update_option('freshmail_api_secret', null);
		}

		/* connect */
		$this->connect = false;
		$this->api = false;
		$this->fmApiKey = get_option('freshmail_api_key');
		$this->fmApiSecret = get_option('freshmail_api_secret');
		$this->fmVerifySSL = (get_option('freshmail_dont_verify_ssl', 'false') == 'false');
		$this->connect = $this->FreshMailconnect();

		if (is_super_admin()) {

			/* report arrays */
			$this->reportArray = array();
			$this->reportType = array();

			/* duplicate form */
			if (isset($_GET['freshmail'], $_GET['form_id']) && $_GET['freshmail'] == 'duplicate' && (int)$_GET['form_id'] != 0) {
				global $wpdb;

				$original = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'freshmail_forms WHERE form_id = '.(int)$_GET['form_id']);
				if ($original != null) {
					$wpdb->insert(
						$wpdb->prefix.'freshmail_forms',
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
				wp_redirect(admin_url('admin.php?page=freshmail&form_id='.$wpdb->insert_id));
			}

			/* delete form */
			if (isset($_GET['freshmail'], $_GET['form_id']) && $_GET['freshmail'] == 'remove' && (int)$_GET['form_id'] != 0) {
				global $wpdb;
				$wpdb->delete($wpdb->prefix.'freshmail_forms', array('form_id' => (int)$_GET['form_id']));
				$wpdb->delete($wpdb->prefix.'freshmail_stats', array('form_id' => (int)$_GET['form_id']));
				wp_redirect(admin_url('admin.php?page=freshmail'));
			}

			/* admin menu */
			add_action('admin_menu', array($this, 'adminMenu'), 9);

			/* assets */
			add_action('admin_enqueue_scripts', function (){
				wp_register_script('freshmail-script', WP_FRESHMAIL_URL.'/assets/js/scripts.js', array('jquery', 'wp-color-picker'));
				wp_enqueue_script('freshmail-script');

				wp_localize_script('freshmail-script', 'static_var', array(
					'plugin_url' => WP_FRESHMAIL_URL,
					'ajax' => admin_url('admin-ajax.php')
				));

				wp_enqueue_style('freshmail-style', WP_FRESHMAIL_URL.'/assets/css/style_admin.css');
				wp_enqueue_script('jquery-ui-accordion');
				wp_enqueue_style('wp-color-picker');
			});

			/* ajax functions */
			add_action('wp_ajax_get_freshmail_fields', array($this, 'getFreshmailFieldsAjax'));

			add_action('wp_ajax_freshmail_preview_form', array($this, 'FreshMailpreviewFormAjax'));
			add_action('wp_ajax_nopriv_preview_form', array($this, 'FreshMailpreviewFormAjax'));

			add_action('wp_ajax_freshmail_change_theme', array($this, 'FreshMailchangeTheme'));
			add_action('wp_ajax_freshmail_save_as_custom_theme', array($this, 'FreshMailsaveCustomTheme'));
			add_action('wp_ajax_freshmail_get_reports', array($this, 'FreshMailgetReports'));
		}

		if ($this->connect === true) {

			/* integrations (checkboxes)*/
			add_action('init', array($this, 'freshmailSignUpForms'), 6);
			add_action('init', array($this, 'freshmailSingUpCheckboxes'), 6);
			add_action('init', array($this, 'addFmCheckboxesToWpForms'), 9);

			/* pop-up */
			add_action('wp_head', array($this, 'FreshMailpopupWpHead'), 100);
			add_action('wp_footer', array($this, 'FreshMailpopupWpFooter'), 100);

			/* shortcode */
			add_shortcode('FM_form', array($this, 'shortcode'));

			/* ajax */
			add_action('wp_ajax_fm_form', array($this, 'freshmailAddEmail'));
			add_action('wp_ajax_nopriv_fm_form', array($this, 'freshmailAddEmail'));
			add_action('wp_ajax_freshmail_popup_show', array($this, 'FreshMailajaxPopup'));

			/* assets */
			add_action('wp_enqueue_scripts', function (){
				wp_enqueue_style('freshmail-style', WP_FRESHMAIL_URL.'/assets/css/style.css');
				wp_enqueue_style("wp-jquery-ui-dialog");

				$handle = 'freshmail-script';
				wp_register_script($handle, WP_FRESHMAIL_URL.'/assets/js/user.js', array('jquery', 'jquery-ui-dialog', 'jquery-ui-tooltip'), '1.0', true);
				wp_enqueue_script($handle);

				$scriptParams = array(
					'plugin_url' => WP_FRESHMAIL_URL,
					'ajax' => admin_url('admin-ajax.php')
				);

				wp_localize_script($handle, 'static_var', $scriptParams);
			});
		}
	}

	/*
	 * Connect to freshmail by API
	 */
	private function FreshMailconnect()
	{
		if ($this->api === false) {
			$this->api = new \FmRestAPI();
			$this->api->setApiKey($this->fmApiKey);
			$this->api->setApiSecret($this->fmApiSecret);
			$this->api->setVerifySSL($this->fmVerifySSL);

			$date = new \DateTime('+1 day');
			$apiHash = md5($this->fmApiKey.$this->fmApiSecret.$date->format('Y-m-d'));
			$oldApiHash = get_option('freshmail_api_hash');

			try {
				if($apiHash != $oldApiHash) {
					$this->api->doRequest('ping');
					update_option('freshmail_api_hash', $apiHash);
				}

				return true;
			} catch (Exception $e) {
				return $e->getMessage();
			}
		}

		return false;
	}

	/*
	 * Admin Pages
	 */
	public function adminMenu()
	{
		//menu
		add_menu_page(__('FreshMail', 'wp_freshmail'), __('FreshMail', 'wp_freshmail'), 'manage_options', 'freshmail', array($this,'FreshMailrenderAllFormsPage'), WP_FRESHMAIL_URL.'/assets/images/icon.png');

		//submenu
		add_submenu_page('freshmail', __('New form', 'wp_freshmail'), __('New form', 'wp_freshmail'), 'manage_options', 'freshmail_new_form', array($this, 'FreshMailrenderNewFormPage'));
		add_submenu_page('freshmail', __('Checkboxes', 'wp_freshmail'), __('Checkboxes', 'wp_freshmail'), 'manage_options', 'freshmail_checkboxes', array(
			$this,
			'renderCheckboxesPage'
		));
		add_submenu_page('freshmail', __('Reports', 'wp_freshmail'), __('Reports', 'wp_freshmail'), 'manage_options', 'freshmail_reports', array($this, 'FreshMailrenderReportsPage'));
		add_submenu_page('freshmail', __('Connect', 'wp_freshmail'), __('Settings', 'wp_freshmail'), 'manage_options', 'freshmail_connect', array($this, 'FreshMailrenderConnectPage'));
	}

	public function FreshMailrenderAllFormsPage()
	{
		if ($this->connect !== true) {
			$this->FreshMailrenderConnectPage();
			die;
		}
		global $wpdb;
		$listForms = true;
		if (isset($_GET['form_id']) && (int)$_GET['form_id'] != 0) {
			$freshmailForm = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'freshmail_forms WHERE form_id = "'.esc_sql((int)$_GET['form_id']).'";');
			if ($freshmailForm != null) {
				$freshmailForm = unserialize($freshmailForm->freshmail_form_var);
				require_once(WP_FRESHMAIL_DIR.'/templates/admin_edit_form_page.php');
				$listForms = false;
			}
		}
		if ($listForms === true) {
			$forms = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'freshmail_forms', OBJECT);
			require_once(WP_FRESHMAIL_DIR.'/templates/admin_all_forms_page.php');
		}
	}

	public function FreshMailrenderNewFormPage()
	{
		if ($this->connect !== true) {
			$this->FreshMailrenderConnectPage();
			die;
		}

		require_once(WP_FRESHMAIL_DIR.'/templates/admin_new_form_page.php');
	}

	public function FreshMailrenderConnectPage()
	{
		if (isset($_POST['freshmail_save_settings'])) {
			if (isset($_POST['freshmail_uninstall_all'])) {
				if ($_POST['freshmail_uninstall_all'] == 'true') {
					update_option('freshmail_uninstall_all', 'true');
				}
			} else {
				update_option('freshmail_uninstall_all', 'false');
			}

			if (isset($_POST['freshmail_dont_verify_ssl'])) {
				if ($_POST['freshmail_dont_verify_ssl'] == 'true') {
					update_option('freshmail_dont_verify_ssl', 'true');
				}
			} else {
				update_option('freshmail_dont_verify_ssl', 'false');
			}
		}

		$uninstallAll = get_option('freshmail_uninstall_all', 'false');
		$doNotVerifySSL = get_option('freshmail_dont_verify_ssl', 'false');
		require_once(WP_FRESHMAIL_DIR.'/templates/admin_connect_page.php');
	}

	private function FreshMailaddNewEmail($form = array(), $referer = null, $formId = null, $freshmailListId = null)
	{
		global $wpdb;

		$email = $form['email'];
		unset($form['email']);
		unset($form['name']);
		try {
			$this->api->doRequest('subscriber/add', array('email' => $email, 'list' => $freshmailListId, 'custom_fields' => $form));

			$wpdb->insert($wpdb->prefix.'freshmail_stats',
				array(
					'form_id' => $formId,
					'referer' => $referer
				), array('%s', '%s'));

			return true;
		} catch (Exception $e) {
			//for developing
			//echo $e->getMessage();
			return false;
		}
	}

	/*
	 * Pages
	 */
	public function FreshMailpopupWpHead()
	{
		global $wpdb;
		$results = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'freshmail_forms', OBJECT);

		echo '<!-- FRESHMAIL POPUPS JAVASCRIPT DO NOT MODIFY -->';
		foreach ($results as $val) {
			$freshmailForm = unserialize($val->freshmail_form_var);
			$postType = get_post_type(get_the_ID());
			if (($postType == 'page')) {
				if (!isset($freshmailForm['allowed_pages']) || (!in_array(get_the_ID(), $freshmailForm['allowed_pages']) && !in_array('all', $freshmailForm['allowed_pages']))) {
					break;
				}
			} elseif ($postType == 'post') {
				if (!isset($freshmailForm['allowed_main_posts']) || (!in_array(get_the_ID(), $freshmailForm['allowed_main_posts']) && !in_array('all', $freshmailForm['allowed_main_posts']))) {
					break;
				}
			} elseif ($postType == 'product') {
				if (!isset($freshmailForm['allowed_products']) || (!in_array(get_the_ID(), $freshmailForm['allowed_products']) && !in_array('all', $freshmailForm['allowed_products']))) {
					break;
				}
			} else {
				break;
			}

			$allowedPages = array_merge((isset($freshmailForm['allowed_pages']) ? $freshmailForm['allowed_pages'] : array()), (isset($freshmailForm['allowed_main_posts']) ? $freshmailForm['allowed_main_posts'] : array()));
			$allowedPages = array_merge($allowedPages, (isset($freshmailForm['allowed_products']) ? $freshmailForm['allowed_products'] : array()));
			/* check if page/post is allowed to display pop-up */
			if (!in_array(get_the_ID(), $allowedPages) && !in_array('all', $allowedPages)) {
				break;
			}

			if (isset($_SESSION['fm_form_popup'][$val->form_id]['count'])) {
				$_SESSION['fm_form_popup'][$val->form_id]['count'] += 1;;
			} else {
				$_SESSION['fm_form_popup'][$val->form_id]['count'] = 0;
			}

			$showPopUp = true;
			$javascriptIncluded = false;
			$shortcode_atts = ' in_popup="true"';
			if ($freshmailForm['show_in_pop_up'] == 'yes') {
				// Don't show pop-ups on mobile phones
				if (isset($freshmailForm['pop_ups_mobile']) && wp_is_mobile()) {
					if ($freshmailForm['pop_ups_mobile'] == 'true') {
						$showPopUp = false;
					}
				}

				// Don't show on specified UTM ta
				if (isset($freshmailForm['dont_show_utm2'])) {
					if (isset($_GET[$freshmailForm['dont_show_utm2']])) {
						if ($_GET[$freshmailForm['dont_show_utm2']] == $freshmailForm['dont_show_utm_value']) {
							$showPopUp = false;
						}
					}
				}

				// The maximum number of pop-up displays per user
				if (isset($_SESSION['fm_form_popup'][$val->form_id]['count']) and $freshmailForm['max_popups_number']) {
					if ($_SESSION['fm_form_popup'][$val->form_id]['count'] >= $freshmailForm['max_popups_number']) {
						$showPopUp = false;
					}
				}
				if (isset($_SESSION['fm_form_popup'][$val->form_id]['lasttime'])) {
					if ((time() - $_SESSION['fm_form_popup'][$val->form_id]['lasttime']) <= $freshmailForm['lag_time']) {
						$showPopUp = false;
					}
				}

				require(WP_FRESHMAIL_DIR.'/templates/popup.php');
				require(WP_FRESHMAIL_DIR.'/templates/google_url.php');
			}
		}
		echo '<!-- END FRESHMAIL POPUPS JAVASCRIPT CODE -->';
	}

	public function FreshMailpopupWpFooter()
	{
		global $wpdb;
		$results = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'freshmail_forms', OBJECT);

		foreach ($results as $val) {
			$freshmailForm = unserialize($val->freshmail_form_var);
			$showPopUp = true;
			$javascriptIncluded = false;
			$shortcode_atts = ' in_popup="true"';
			if ($freshmailForm['show_in_pop_up'] == 'yes') {
				// Don't show pop-ups on mobile phones
				if (isset($freshmailForm['pop_ups_mobile']) && wp_is_mobile()) {
					if ($freshmailForm['pop_ups_mobile'] == 'true') {
						$showPopUp = false;
					}
				}
				// Don't show on specified UTM ta
				if (isset($freshmailForm['dont_show_utm2'])) {
					if (isset($_GET[$freshmailForm['dont_show_utm2']])) {
						if ($_GET[$freshmailForm['dont_show_utm2']] == $freshmailForm['dont_show_utm_value']) {
							$showPopUp = false;
						}
					}
				}

				// The maximum number of pop-up displays per user
				if (isset($_SESSION['fm_form_popup'][$val->form_id]['count']) && $freshmailForm['max_popups_number']) {
					if ($_SESSION['fm_form_popup'][$val->form_id]['count'] >= $freshmailForm['max_popups_number']) {
						$showPopUp = false;
					}
				}
				if (isset($_SESSION['fm_form_popup'][$val->form_id]['lasttime'])) {
					if ((time() - $_SESSION['fm_form_popup'][$val->form_id]['lasttime']) <= $freshmailForm['lag_time']) {
						$showPopUp = false;
					}
				}

				if ($freshmailForm['redirection'] != 'no') {
					if ($freshmailForm['redirection'] == 'wordpress') {
						$shortcode_atts .= ' redirect="'.$freshmailForm['redirection_wp'].'"';
					}
					if ($freshmailForm['redirection'] == 'custom') {
						$shortcode_atts .= ' redirect="'.$freshmailForm['redirection_custom'].'"';
					}
				}

				if ($showPopUp): ?>
					<!-- FRESHMAIL POPUPS -->
					<div id="fm_popup_<?php echo $val->form_id; ?>" class="freshmail_popup">
						<?php echo do_shortcode('[FM_form'.$shortcode_atts.' id="'.$val->form_id.'"]'); ?>
					</div>
					<div class="fm_popup_pos">&nbsp;</div>
					<!-- END FRESHMAIL POPUPS -->
				<?php  endif;
			}
		}
	}

	public function shortcode($fmFormId)
	{
		ob_start();
		global $wpdb;

		if (isset($_POST['form_serialize'])) {
			parse_str($_POST['form_serialize'], $formSerialize);
			$freshmailForm = $formSerialize['fm_form_var'];
		} else {
			$freshmailForm = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'freshmail_forms WHERE form_id = '.$fmFormId['id'].';');
			if (!is_object($freshmailForm)) {
				return false;
			}
			$freshmailForm = unserialize($freshmailForm->freshmail_form_var);
		}

		// Form Container style
		$formContainer = $freshmailForm['appearance']['form_container'];
		$textHeader = $freshmailForm['appearance']['text_header'];
		$subHeader = $freshmailForm['appearance']['sub_header'];
		$field = $freshmailForm['appearance']['field'];
		$label = $freshmailForm['appearance']['label'];
		$checkboxAgreement = $freshmailForm['appearance']['checkbox_agreement'];
		$button = $freshmailForm['appearance']['button'];
		$buttonHovered = $freshmailForm['appearance']['button_hovered'];
		$errorSuccess = $freshmailForm['appearance']['error_success'];

		if ($freshmailForm['redirection'] != 'no' && empty($fmFormId['redirect'])) {
			if ($freshmailForm['redirection'] == 'wordpress') {
				$fmFormId['redirect'] = $freshmailForm['redirection_wp'];
			}
			if ($freshmailForm['redirection'] == 'custom') {
				$fmFormId['redirect'] = $freshmailForm['redirection_custom'];
			}
		}

		require(WP_FRESHMAIL_DIR.'/templates/shortcode.php');
		require(WP_FRESHMAIL_DIR.'/templates/google_url.php');

		return ob_get_clean();
	}

	/**
	 * Ajax functions
	 */

	public function FreshMailajaxPopup()
	{
		if(isset($_POST['action']) AND $_POST['action']=='popup_show' AND isset($_POST['form_id'])){
			$_SESSION['fm_form_popup'][$_POST['form_id']]['lasttime'] = time();
			if (!isset($_SESSION['fm_form_popup'][$_POST['form_id']]['count'])) {
				$_SESSION['fm_form_popup'][$_POST['form_id']]['count'] = 1;
			} else {
				$_SESSION['fm_form_popup'][$_POST['form_id']]['count']++;
			}
			die(json_encode($_SESSION['fm_form_popup']));
		}else{
			die(0);
		}
	}

	public function getFreshmailFieldsAjax()
	{
		require_once(WP_FRESHMAIL_DIR.'/templates/ajax_get_freshmail_fields.php');
		die;
	}

	public function FreshMailpreviewFormAjax()
	{
		echo do_shortcode('[FM_form id="'.$_POST['form_id'].'"]');
		die;
	}

	public function FreshMailchangeTheme()
	{
		$freshmailForm = array('appearance' => array());

		$customTheme = get_option('freshmail_custom_theme');
		$customTheme = unserialize($customTheme);

		$freshmailForm['appearance'] = $customTheme[$_POST['theme_id']];
		$freshmailForm['appearance']['freshmail_select_theme'] = $_POST['theme_id'];

		require_once(WP_FRESHMAIL_DIR.'/templates/form_appearance.php');
		die;
	}

	public function FreshMailsaveCustomTheme()
	{
		$params = array();
		parse_str($_POST['fm_form'], $params);

		$customTheme = $this->FreshMailcountThemes();

		// save changes
		$params['fm_form_var']['appearance']['freshmail_select_theme'] = ($customTheme != null) ? $customTheme - 7 : 1; //7 - default quantity of themes in version 1.2.7
		$_POST['fm_form_var'] = $params['fm_form_var'];
		$this->freshmailSignUpForms();

		echo $customTheme;
		die;
	}

	public function freshmailSignUpForms()
	{
		if (isset($_POST['fm_form_var'])) {
			global $wpdb;
			$freshmailListName = null;
			$formId = (isset($_GET['form_id']) && is_numeric($_GET['form_id']) ? $_GET['form_id'] : 'NULL');
			$freshmail_list_id = $_POST['fm_form_var']['select_freshmail_list_id'];
			try {
				$response = $this->api->doRequest('subscribers_list/lists');
				foreach ($response['lists'] as $val) {
					if ($val['subscriberListHash'] == $_POST['fm_form_var']['select_freshmail_list_id']) {
						$freshmailListName = $val['name'];
						break;
					}
				}
			} catch (Exception $e) {
				/*
					// for developing
					echo $e->getMessage();
				*/
			}

			if (isset($_POST['fm_form_var']['messages'])) {
				$messages = $_POST['fm_form_var']['messages'];
			} else {
				//defaults
				$default = array(
					'hide_sign_up' => 'no',
					'show_in_pop_up' => 'yes',
					'when_to_show' => 'immediately',
					'sec_min' => array(30, 'seconds'),
					'per_px' => array(70, 'percent'),
					'pop_ups_mobile' => true,
					'dont_show_utm2' => 'utm_campaign',
					'dont_show_utm_value' => null,
					'max_popups_number' => 0,
					'lag_time' => 0,
					'redirection' => 'no',
					'redirection_wp' => null,
					'redirection_custom' => null
				);

				$_POST['fm_form_var'] = array_merge($_POST['fm_form_var'], $default);

				$messages = array(
					'form_header' => __('Subscribe to our newsletter', 'wp_freshmail'),
					'form_sub_header' => __('Get updates direct to your inbox.', 'wp_freshmail'),
					'form_agreement_label' => __('Send me your newsletter (you can unsubscribe at any time).', 'wp_freshmail'),
					'form_subscribe_button' => __('Sign me up!', 'wp_freshmail'),
					'success' => __('Your sign up request was successful! Please check your email inbox.', 'wp_freshmail'),
					'failure' => __('Oops. Something went wrong. Please try again later.', 'wp_freshmail'),
					'already' => __('Given email address is already subscribed, thank you!', 'wp_freshmail'),
					'invalid' => __('Please provide a valid email address', 'wp_freshmail'),
					'required' => __('Please fill all the required fields', 'wp_freshmail'),
				);
			}

			foreach ($messages as $key => $val) {
				$_POST['fm_form_var']['messages'][$key] = str_replace('\"', '"', $messages[$key]);
			}


			
			$freshmail_form_var = serialize($_POST['fm_form_var']);

			$insert_date = date('Y-m-d H:i:s');
			$last_edited = date('Y-m-d H:i:s');

			if (isset($_GET['form_id']) && (int)$_GET['form_id'] != 0) {
				$wpdb->update(
					$wpdb->prefix.'freshmail_forms',
					array(
						'freshmail_list_id' => $freshmail_list_id,
						'freshmail_list_name' => $freshmailListName,
						'freshmail_form_var' => $freshmail_form_var,
						'last_edited' => $last_edited
					),
					array('form_id' => $formId),
					array('%s', '%s', '%s', '%s')
				);
				add_action('admin_notices', function (){
					echo '<div class="fm_updated"><p>'.__('The Sign Up Form has been updated!', 'wp_freshmail').'</p></div>';
				});
				$this->FreshMailupdateThemes($_POST['fm_form_var']['appearance'], $_POST['fm_form_var']['appearance']['freshmail_select_theme']);
			} else {
				$wpdb->insert(
					$wpdb->prefix.'freshmail_forms',
					array(
						'freshmail_list_id' => $freshmail_list_id,
						'freshmail_list_name' => $freshmailListName,
						'freshmail_form_var' => $freshmail_form_var,
						'insert_date' => $insert_date,
						'last_edited' => $last_edited
					),
					array('%s', '%s', '%s', '%s', '%s', '%s')
				);
				global $newFormId;
				$newFormId = $wpdb->insert_id;
				add_action('admin_notices', function (){
					global $newFormId;
					echo '<div class="fm_updated"><p>'.__('The Sign Up Form has been added!', 'wp_freshmail').' <a href="admin.php?page=freshmail&form_id='.$newFormId.'">'.__('Show', 'wp_freshmail').'</a></p></div>';
				});
			}
		}
	}

	public function freshmailAddEmail()
	{
		global $wpdb;

		if (!isset($_POST['redirect'])) {
			$_POST['redirect'] = 0;
		}

		$form = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'freshmail_forms WHERE form_id = %s', esc_sql($_POST['fm_form_id'])));
		$freshmailForm = unserialize($form->freshmail_form_var);
		$_POST['status'] = 'success';
		$_POST['message'] = $freshmailForm['messages']['success'];

		foreach ($freshmailForm['fields'] as $key => $val) {
			if (is_array($val)) {
				if (isset($val['include'], $val['required']) && $val['include'] == 'on' && $val['required'] == 'on') {
					if (empty($_POST['form'][$key])) {
						$_POST['status'] = 'error';
						$_POST['message'] = $freshmailForm['messages']['required'];
					}
				}
			}
		}

		if (!filter_var($_POST['form']['email'], FILTER_VALIDATE_EMAIL)) {
			$_POST['status'] = 'error';
			$_POST['message'] = $freshmailForm['messages']['invalid'];
		}

		if ((!isset($_POST['fm_form_agree']) || $_POST['fm_form_agree'] != 'on') && $freshmailForm['appearance']['checkbox_agreement']['display'] == 'yes') {
			$_POST['status'] = 'error';
			$_POST['message'] = $freshmailForm['messages']['required'];
		}

		if ($_POST['status'] == 'success') {
			$custom_fields = array();
			if (count($_POST['form']) > 1) {
				foreach ($_POST['form'] as $key => $val) {
					if ($key != 'email') {
						$custom_fields[$key] = $val;
					}
				}
			}

			try {
				$this->api->doRequest('subscriber/add', array('email' => $_POST['form']['email'], 'list' => $form->freshmail_list_id, 'custom_fields' => $custom_fields));
				$wpdb->insert(
					$wpdb->prefix.'freshmail_stats',
					array(
						'form_id' => $_POST['fm_form_id'],
						'referer' => $_POST['fm_form_referer'],
						'insert_date' => date('Y-m-d H:i:s')
					)
				);
			} catch (Exception $e) {
				if ($e->getCode() == 1304) {
					$_POST['status'] = 'success';
					$_POST['message'] = $freshmailForm['messages']['already'];
				} else {
					$_POST['status'] = 'error';
					$_POST['message'] = $freshmailForm['messages']['failure'];
				}
			}
		}

		die(json_encode($_POST));
	}


	private function FreshMailupdateThemes($appearance, $key = null)
	{
		$customTheme = get_option('freshmail_custom_theme');

		if (empty($customTheme)) {
			$customTheme = array();
		} else {
			$customTheme = unserialize($customTheme);
		}

		if ($key != null) {
			$customTheme[$key] = $appearance;
		} else {
			$customTheme[] = $appearance;
		}

		update_option('freshmail_custom_theme', serialize($customTheme));

		return $customTheme;
	}

	private function FreshMailcountThemes()
	{
		$themes = get_option('freshmail_custom_theme');

		return (empty($themes)) ? null : count(unserialize($themes));
	}

	/*
	 * Reports page
	 */
	public function FreshMailrenderReportsPage()
	{
		if ($this->connect !== true) {
			$this->FreshMailrenderConnectPage();
			die;
		}

		global $wpdb;

		if (isset($_POST['sign_up_checkboxes'])) {
			$this->fmSettings['fm_sign_up_checkboxes'] = $_POST;
		}

		$this->FreshMailgetDataFromDb();

		add_action('admin_notices', function (){
			echo '<div class="fm_updated"><p>'.__('There are no added an email via the sign up form!', 'wp_freshmail').'</p></div>';
		});

		require_once(WP_FRESHMAIL_DIR.'/templates/admin_reports.php');
	}

	public function FreshMailgetReports()
	{
		$params = array();
		parse_str($_POST['params'], $params);

		$columns = array();
		if (isset($params['total_subscriptions'])) {
			$columns['total_subscriptions'] = $params['total_subscriptions'];
		}

		if (isset($params['using_form']) and is_array($params['using_form'])) {
			$columns['using_form'] = $params['using_form'];
		}

		if (isset($params['using_checkbox']) and is_array($params['using_checkbox'])) {
			$columns['using_checkbox'] = $params['using_checkbox'];
		}

		if (isset($params['sources']) and is_array($params['sources'])) {
			$columns['sources'] = $params['sources'];
		}

		$this->showVisualizationLineChart2($columns, $this->FreshMailgetDataFromDb(date('Y-m-d', strtotime($params['from_y']."-".$params['from_m']."-".$params['from_d'])).' 23:59', date('Y-m-d', strtotime($params['to_y']."-".$params['to_m']."-".$params['to_d'])).' 23:59'));
		die;
	}

	private function FreshMailgetDataFromDb($fromDate = null, $toDate = null)
	{
		global $wpdb;
		$where = '';

		if ($fromDate != null) {
			$where .= ' AND insert_date >= "'.$fromDate.'" ';
		}

		if ($toDate != null) {
			$where .= ' AND insert_date <= "'.$toDate.'" ';
		}

		$results = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'freshmail_stats WHERE 1 = 1 '.$where.' ORDER BY form_id ASC', OBJECT);

		$this->reportType = array(
			__('Total subscriptions', 'wp_freshmail'),
			__('Using Form', 'wp_freshmail'),
			__('Using Checkbox', 'wp_freshmail')
		);

		foreach ($results as $val) {
			if (!isset($this->reportArray[date('Y-m-d', strtotime($val->insert_date))][$val->referer])) {
				$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][$val->referer] = 1;
			} else {
				$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][$val->referer]++;
			}

			if (!isset($this->reportArray[date('Y-m-d', strtotime($val->insert_date))][$val->form_id])) {
				$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][$val->form_id] = 1;
			} else {
				$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][$val->form_id]++;
			}

			// Using Form
			if (is_numeric($val->form_id)) {
				if (!isset($this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Using Form', 'wp_freshmail')])) {
					$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Using Form', 'wp_freshmail')] = 1;
				} else {
					$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Using Form', 'wp_freshmail')]++;
				}
			} else {
				if (!isset($this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Using Checkbox', 'wp_freshmail')])) {
					$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Using Checkbox', 'wp_freshmail')] = 1;
				} else {
					$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Using Checkbox', 'wp_freshmail')]++;
				}
			}

			if (!isset($this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Total subscriptions', 'wp_freshmail')])) {
				$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Total subscriptions', 'wp_freshmail')] = 1;
			} else {
				$this->reportArray[date('Y-m-d', strtotime($val->insert_date))][__('Total subscriptions', 'wp_freshmail')]++;
			}

			if (!in_array($val->form_id, $this->reportType)) {
				$this->reportType[] = $val->form_id;
			}
		}

		return $this->reportArray;
	}

	private function showVisualizationLineChart($columns = array())
	{
		?>
		<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				google.setOnLoadCallback(drawChart);
				function drawChart(){
					var data = google.visualization.arrayToDataTable([
						<?php	if ( !defined( 'DOING_AJAX' ) ) {
							$columns = array(
								'total_subscriptions' => __('Total subscriptions', 'wp_freshmail'),
								'using_form' => array(__('Using Form', 'wp_freshmail')),
								'using_checkbox' => array(__('Using Checkbox', 'wp_freshmail'))
							);
						}
						$this->reportType = array();
						echo "['date' ";
							foreach ($columns as $k => $cols) {
								if (!is_array($cols)) {
									echo ", '".$cols."'";
									if (!in_array($cols, $this->reportType)) {
										$this->reportType[] = $cols;
									}
								} else {
									foreach ($cols as $col) {
										if (!is_numeric($col)) {
											echo ", '".$col."'";
										} else {
											echo ", '".__('Sign-Up Form', 'wp_freshmail')." #".$col."'";
										}
										if (!in_array($cols, $this->reportType)) {
											$this->reportType[] = $col;
									}
								}
							}
						}
						echo "],";
						foreach ($this->reportArray as $key => $val) {
							foreach ($this->reportType as $val2) {
								if (!isset($this->reportArray[$key][$val2])) {
									$this->reportArray[$key][$val2] = 0;
								}
							}
						}
						foreach ($this->reportArray as $key => $val) {
							echo "['".$key."'";
							foreach ($this->reportType as $val3) {
								echo ", ".$val[$val3];
							}
							echo '],';
						}	?>]);
					console.log(data);
					var options = {
						title: 'Raport',
						curveType: 'function',
						lineWidth: 2,
						pointSize: 3,
						legend: { position: 'right' }
					};
					var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
					chart.draw(data, options);
				}
			});
		</script>
		<div id="chart_div"></div>
	<?php
	}

	private function showVisualizationLineChart2($columns = array(), $dataArray = null)
	{
		if ($dataArray == null) {
			$this->reportArray = array();
		} else {
			$this->reportArray = $dataArray;
		}

		$this->reportType = array();
		$arr = array();
		$temp = array();
		$temp[] = 'date';

		foreach ($columns as $cols) {
			if (!is_array($cols)) {
				$temp[] = $cols;
				if (!in_array($cols, $this->reportType)) {
					$this->reportType[] = $cols;
				}
			} else {
				foreach ($cols as $col) {
					if (!is_numeric($col)) {
						$temp[] = $col;
					} else {
						$temp[] = __('Sign-Up Form', 'wp_freshmail').' #'.$col;
					}
					if (!in_array($cols, $this->reportType)) {
						$this->reportType[] = $col;
					}
				}
			}
		}

		$arr[] = $temp;
		foreach ($this->reportArray as $key => $val) {
			foreach ($this->reportType as $val2) {
				if (!isset($this->reportArray[$key][$val2])) {
					$this->reportArray[$key][$val2] = 0;
				}
			}
		}

		foreach ($this->reportArray as $key => $val) {
			$temp = array();
			$temp[] = $key;
			foreach ($this->reportType as $val3) {
				$temp[] = $val[$val3];
			}
			$arr[] = $temp;
		}

		echo json_encode($arr);
	}

	/*
	 * Checkboxes
	 */
	public function renderCheckboxesPage()
	{
		if ($this->connect !== true) {
			$this->FreshMailrenderConnectPage();
			die;
		}

		if (isset($_GET['delete_form_id'])) {
			if (is_numeric($_GET['delete_form_id'])) {
				global $wpdb;
				if ($wpdb->delete($wpdb->prefix.'freshmail_forms', array('form_id' => esc_sql((int)$_GET['delete_form_id'])))) {
					$deleteNotice = '<div class="updated"><p>'.__('Deleted', 'wp_freshmail').'</p></div>';
				}
			}
		}

		$freshmailSettings['fm_sign_up_checkboxes'] = get_option('fm_sign_up_checkboxes', null);
		require_once(WP_FRESHMAIL_DIR.'/templates/admin_checkboxes.php');
	}

	public function freshmailSingUpCheckboxes()
	{
		if (isset($_POST['sign_up_checkboxes'])) {
			update_option('fm_sign_up_checkboxes', $_POST);
			add_action('admin_notices', function (){
				echo '<div class="fm_updated"><p>'.__('The Sign Up Checkboxes has been updated!', 'wp_freshmail').'</p></div>';
			});
		}
	}

	public function addFmCheckboxesToWpForms()
	{
		$this->fmSettings['fm_sign_up_checkboxes'] = get_option('fm_sign_up_checkboxes', true);

		/* comments */
		if (isset($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form'])) {
			if ($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form'] == 'on') {

				add_action('thesis_hook_after_comment_box', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<p class="comment-form-fm-sign"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked="checked"' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></p>';
					}
				}, 11);

				add_action('comment_form', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<p class="comment-form-fm-sign"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked="checked"' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></p>';
					}
				}, 11);

				add_action('comment_post', function ($commentId, $commentApproved = ''){
					if ($_POST['fm-sign'] == 'on') {
						// is this a spam comment?
						if ($commentApproved === 'spam') {
							return false;
						}
						$comment = get_comment($commentId);
						$email = $comment->comment_author_email;
						$name = $comment->comment_author;

						$this->FreshMailaddNewEmail(array(
							'email' => $email,
							'name' => $name
						), $_POST['fm-sign-referer'], __('Comment Form', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
					}
				}, 40, 2);
			}
		}

		/* registration */
		if (isset($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['reg_form'])) {
			if ($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['reg_form'] == 'on') {
				add_action('register_form', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<p class="comment-form-fm-sign"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['reg_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></p>';
					}
				}, 20);

				add_action('user_register', function ($userId){
					if ($_POST['fm-sign'] == 'on') {
						$user = get_userdata($userId);
						// was a user found with the given ID?
						if (!$user) {
							return false;
						}
						$this->FreshMailaddNewEmail(array('email' => $user->user_email), $_POST['fm-sign-referer'], __('Registration Form', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
					}
				}, 90, 1);
			}
		}

		/* WooCommerce */
		if (isset($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['woo_form'])) {
			if ($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['woo_form'] == 'on') {
				add_action('woocommerce_checkout_billing', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<p class="comment-form-fm-sign"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['woo_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></p>';
					}
				}, 11);

				add_action('woocommerce_checkout_process', function (){
					if ($_POST['fm-sign'] == 'on') {
						$this->FreshMailaddNewEmail(array('email' => $_POST['billing_email']), $_POST['fm-sign-referer'], __('WooCommerce Checkout', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
					}
				});
			}
		}

		/* easy dig */
		if (isset($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['easy_dig_form'])) {
			if ($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['easy_dig_form'] == 'on') {
				add_action('edd_checkout_form_bottom', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<p class="comment-form-fm-sign"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['easy_dig_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></p>';
					}
				}, 11);

				add_action('edd_purchase', function (){
					if ($_POST['fm-sign'] == 'on') {
						$this->FreshMailaddNewEmail(array('email' => $_POST['edd_email']), $_POST['fm-sign-referer'], __('Easy Digital Download Checkout ', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
					}
				}, 9);
			}
		}

		/* bbPress */
		if (isset($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form'])) {
			if ($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form'] == 'on') {
				add_action('bbp_theme_after_topic_form_subscriptions', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<div class="freshmail-bbpress-checkbox"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked="checked"' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></div>';
					}
				}, 10);

				add_action('bbp_theme_after_reply_form_subscription', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<div class="freshmail-bbpress-checkbox"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked="checked"' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></div>';
					}
				}, 10);

				add_action('bbp_theme_anonymous_form_extras_bottom', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<div class="freshmail-bbpress-checkbox"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked="checked"' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></div>';
					}
				}, 10);

				add_action('bbp_new_topic', function ($topicId, $forumId, $form, $userId){
					if ($_POST['fm-sign'] == 'on') {
						if (isset($form['bbp_anonymous_email'], $form['bbp_anonymous_name']) && $form['bbp_anonymous_email'] != '' && $form['bbp_anonymous_name'] != '') {
							$email = $form['bbp_anonymous_email'];
							$name = $form['bbp_anonymous_name'];
						} elseif ($userId != 0) {
							$userData = get_userdata($userId);
							$email = $userData->user_email;
							$name = $userData->first_name.' '.$userData->last_name;
						} else {
							return false;
						}
						$this->FreshMailaddNewEmail(array(
							'email' => $email,
							'name' => $name
						), $_POST['fm-sign-referer'], __('bbPress new topic', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
					}
				}, 10, 4);

				add_action('bbp_new_reply', function ($replyId, $topicId, $forumId, $form, $userId){
					if ($_POST['fm-sign'] == 'on') {
						if (isset($form['bbp_anonymous_email'], $form['bbp_anonymous_name']) && $form['bbp_anonymous_email'] != '' && $form['bbp_anonymous_name'] != '') {
							$email = $form['bbp_anonymous_email'];
							$name = $form['bbp_anonymous_name'];
						} elseif ($userId != 0) {
							$userData = get_userdata($userId);
							$email = $userData->user_email;
							$name = $userData->first_name.' '.$userData->last_name;
						} else {
							return false;
						}
						$this->FreshMailaddNewEmail(array(
							'email' => $email,
							'name' => $name
						), $_POST['fm-sign-referer'], __('bbPress new reply', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
					}
				}, 10, 5);
			}
		}

		/* multisite form */
		if (isset($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['multisite_form'])) {
			if ($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['multisite_form'] == 'on') {
				add_action('signup_extra_fields', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<p class="comment-form-fm-sign"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked="checked"' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['multisite_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></p>';
					}
				}, 20);

				add_action('signup_blogform', function (){
					if ($_POST['fm-sign'] == 'on') {
						$value = 1;
					} else {
						$value = 0;
					}
					echo '<input type="hidden" name="_freshmail_subscribe" value="'.$value.'" />';
				}, 20);

				add_action('wpmu_activate_blog', function ($blogId, $userId, $a, $b, $meta = null){
					if (!isset($meta['_freshmail_subscribe']) || $meta['_freshmail_subscribe'] !== 1) {
						return false;
					}

					$user = get_userdata($userId);
					if (!is_object($user)) {
						return false;
					}

					$this->FreshMailaddNewEmail(array(
						'email' => $user->user_email,
						'name' => $user->first_name.' '.$user->last_name
					), $_POST['fm-sign-referer'], __('Multisite Form', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
				}, 20, 5);

				add_action('wpmu_activate_user', function ($userId, $password = null, $meta = array()){
					if (!isset($meta['_freshmail_subscribe']) || $meta['_freshmail_subscribe'] !== 1) {
						return false;
					}

					$user = get_userdata($userId);
					if (!is_object($user)) {
						return false;
					}

					$this->FreshMailaddNewEmail(array(
						'email' => $user->user_email,
						'name' => $user->first_name.' '.$user->last_name
					), $_POST['fm-sign-referer'], __('Multisite Form', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
				}, 20, 3);

				add_filter('add_signup_meta', function ($meta = array()){
					if ($_POST['fm-sign'] == 'on') {
						$meta['_freshmail_subscribe'] = 1;
					} else {
						$meta['_freshmail_subscribe'] = 0;
					}

					return $meta;
				});
			}
		}

		/* buddyPress */
		if (isset($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['buddypress_form'])) {
			if ($this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['buddypress_form'] == 'on') {
				add_action('bp_before_registration_submit_buttons', function (){
					if (isset($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']) && !empty($this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) {
						echo '<div class="freshmail-bbpress-checkbox"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked' : null).' /> '.$this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['buddypress_form_txt'].'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></div>';
					}
				}, 20);

				add_action('bp_core_signup_user', function ($userId, $name, $user_password, $email){
					if ($_POST['fm-sign'] == 'on') {
						$this->FreshMailaddNewEmail(array(
							'email' => $email,
							'name' => $name
						), $_POST['fm-sign-referer'], __('BuddyPress registration', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
					}
				}, 10, 4);
			}
		}

		/* contact form 7 */
		add_action('wpcf7_init', function (){
			add_action('wpcf7_mail_sent', function ($args = null){
				if (isset($_POST['fm-sign']) && $_POST['fm-sign'] != 'on') {
					return false;
				}

				return true;
			});

			add_action('wpcf7_posted_data', function ($data = array()){
				$email = null;
				foreach ($data as $val) {
					if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
						$email = $val;
						break;
					}
				}
				$data['_freshmail_subscribe'] = (isset($data['fm-sign']) && $data['fm-sign'] == 'on') ? __('Yes', 'wp_freshmail') : __('No', 'wp_freshmail');
				if (isset($data['fm-sign']) && $data['fm-sign'] == 'on' && $email != null) {
					$this->FreshMailaddNewEmail(array('email' => $email), $data['fm-sign-referer'], __('Contact form 7', 'wp_freshmail'), $this->fmSettings['fm_sign_up_checkboxes']['freshmail_list_id']);
				}

				return $data;
			});

			wpcf7_add_shortcode('fm_checkbox', function ($args = array()){
				$label = $args['labels'][0];
				if (empty($label)) {
					$label = $this->fmSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form_txt'];
				}

				return '<p class="comment-form-fm-sign"><label for="fm-sign"><input id="fm-sign" name="fm-sign" type="checkbox" '.($this->fmSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked' : null).' /> '.$label.'</label><input type="hidden" name="fm-sign-referer" value="'.$_SERVER['REQUEST_URI'].'" /></p>';
			});
		});
	}
}