<?php  
if( !class_exists('CORTEN_TPAY') ) {

	class CORTEN_TPAY {

		protected static function md5sum($id, $amount, $crc, $code) {
			return md5($id.$amount.$crc.$code);
		}

		public static function payment_link($amount, $lang, $desc, $email, $name, $address, $city, $postcode, $phone, $crc) {

			$id 	  = get_field('_tpay_seller_id', 'option');
			$code 	= get_field('_tpay_security_code', 'option');

			$notify_url 		= CORTEN_SHOP::order_summary_page_url('payment-online/notification').'/'.$crc;
			$return_url 		= CORTEN_SHOP::order_summary_page_url('payment-online/completed').'/'.$crc;
			$return_url_err = CORTEN_SHOP::order_summary_page_url('payment-online/canceled').'/'.$crc;

			$md5sum = self::md5sum($id, $amount, $crc, $code);

			return 'https://secure.tpay.com/?id='.$id.'&kwota='.$amount.'&jezyk='.$lang.'&opis='.rawurlencode($desc).'&email='.rawurlencode($email).'&nazwisko='.rawurlencode($name).'&adres='.rawurlencode($address).'&miasto='.rawurlencode($city).'&kod='.rawurlencode($postcode).'&telefon='.rawurlencode($phone).'&online=1&crc='.rawurlencode($crc).'&pow_url='.rawurlencode($return_url).'&pow_url_blad='.rawurlencode($return_url_err).'&wyn_url='.rawurlencode($notify_url).'&kanal=31&md5sum='.$md5sum.'';
		}

	}

}
?>