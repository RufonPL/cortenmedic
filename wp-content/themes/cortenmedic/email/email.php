<?php  
if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('CORTEN_EMAIL') ) {

	class CORTEN_EMAIL {

		public function __construct() {

			add_filter( 'wp_mail_from', array($this, 'mail_from') );
			add_filter( 'wp_mail_from_name', array($this, 'mail_from_name') );
			add_filter( 'wp_mail_content_type',array($this, 'mail_contenttype') );
		}

		/**
		 * This function will set the "from" email address 
		 * @param n/a
		 * @return n/a
		*/
		public function mail_from($email) {
			return get_option('admin_email');
		}

		/**
		 * This function will set the "from" name
		 * @param n/a
		 * @return n/a
		*/
		public function mail_from_name($name) {
			return get_option('blogname');
		}

		/**
		 * This function will email content type to html
		 * @param n/a
		 * @return n/a
		*/
		public function mail_contenttype($content_type) {
			return 'text/html';
		}

		/**
		 * This function will render styles for email templates
		 * @param n/a
		 * @return css
		*/
		public function email_styles() {
			$file = __DIR__.'/includes/styles.php';

			if( file_exists( $file ) ) {
				ob_start();

				include( $file );

				return ob_get_clean();
			}
		}

		/**
		 * This function will get email template
		 * @param $template_name (string)
		 * @return html
		*/
		public function email_template($template_name, $params = array()) {
			$file = __DIR__.'/templates/'.$template_name.'.php';

			if( file_exists( $file ) ) {
				ob_start();

				include( $file );
				$params;

				return ob_get_clean();
			}
		}

	}
	new CORTEN_EMAIL();

}
?>