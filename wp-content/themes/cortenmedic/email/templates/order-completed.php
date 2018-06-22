<?php  
$styles 		= CORTEN_EMAIL::email_styles();
$send_to 		= isset($params['send_to']) ? $params['send_to'] : 'admin';
$order_id 	= isset($params['order_id']) ? $params['order_id'] : 0;

$order_number           = CORTEN_SHOP::order_data_item($order_id, 'number');
$order_date             = CORTEN_SHOP::order_data_item($order_id, 'date');
$payment_method         = CORTEN_SHOP::order_data_item($order_id, 'customer_payment_type');

$customer_type          = CORTEN_SHOP::order_data_item($order_id, 'customer_type');
$shipment_different     = CORTEN_SHOP::order_data_item($order_id, 'customer_shipment_different');

$customer_first_name    = CORTEN_SHOP::order_data_item($order_id, 'customer_first_name');
$customer_last_name     = CORTEN_SHOP::order_data_item($order_id, 'customer_last_name');
$customer_personal_nip  = CORTEN_SHOP::order_data_item($order_id, 'customer_personal_nip');
$customer_company_name  = CORTEN_SHOP::order_data_item($order_id, 'customer_company_name');
$customer_company_nip   = CORTEN_SHOP::order_data_item($order_id, 'customer_company_nip');

$address_street         = CORTEN_SHOP::order_data_item($order_id, 'customer_address_street');
$address_number         = CORTEN_SHOP::order_data_item($order_id, 'customer_address_number');
$address_city           = CORTEN_SHOP::order_data_item($order_id, 'customer_address_city');
$address_postcode       = CORTEN_SHOP::order_data_item($order_id, 'customer_address_postcode');
$address_country        = CORTEN_SHOP::order_data_item($order_id, 'customer_address_country');

$customer_email         = CORTEN_SHOP::order_data_item($order_id, 'customer_email');
$customer_phone         = CORTEN_SHOP::order_data_item($order_id, 'customer_phone');

$shipment_street        = CORTEN_SHOP::order_data_item($order_id, 'customer_shipment_street');
$shipment_number        = CORTEN_SHOP::order_data_item($order_id, 'customer_shipment_number');
$shipment_city          = CORTEN_SHOP::order_data_item($order_id, 'customer_shipment_city');
$shipment_postcode      = CORTEN_SHOP::order_data_item($order_id, 'customer_shipment_postcode');
$shipment_country       = CORTEN_SHOP::order_data_item($order_id, 'customer_shipment_country');

$code                   = CORTEN_SHOP::order_data_item($order_id, 'code');

$products       = array();
$products_no    = CORTEN_SHOP::order_data_item($order_id, 'products');
$totals         = CORTEN_SHOP::order_data_item($order_id, 'totals');
$shipment_type  = CORTEN_SHOP::order_data_item($order_id, 'shipment_method');
$shipment_item  = CORTEN_SHOP::order_data_item($order_id, 'shipment_item');

for($i=1; $i<=$products_no; $i++) {
  $products[] = CORTEN_SHOP::order_data_item($order_id, 'product_'.$i);
}

$product_types_in_order = CORTEN_SHOP::cart_product_types($products);
?>
<!doctype html>
<html>
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Order Completed', 'rfswp'); ?></title>
	<?php echo $styles; ?>
	</head>
	<body class="">
		<table border="0" cellpadding="0" cellspacing="0" class="body">
			<tr>
			<td>&nbsp;</td>
			<td class="container">
				<div class="content">

				<!-- START CENTERED WHITE CONTAINER -->
				<span class="preheader">
					<?php if( $send_to == 'admin' ) : ?>
						<?php _e('Order has been completed', 'rfswp'); ?>
					<?php else : ?>
						<?php _e('Your order has been completed', 'rfswp'); ?>
					<?php endif; ?>
				</span>
				<table class="main">

					<!-- START MAIN CONTENT AREA -->
					<tr>
					<td class="wrapper">
						<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td><img src="<?php echo get_template_directory_uri().'/dist/assets/images/cortenmedic.png'; ?>" alt="" width="" height="" border="0" style="border:0; outline:none; text-decoration:none; display:block;"><br></td>
						</tr>
						<tr>
							<td>
								<p>
								<?php if( $send_to == 'admin' ) : ?>
									<?php _e('Order has been completed', 'rfswp'); ?>
								<?php else : ?>
									<?php _e('Hello', 'rfswp'); ?> <?php echo esc_html( $customer_first_name.' '.$customer_last_name ); ?><br>
									<?php _e('Your order has been completed', 'rfswp'); ?>
								<?php endif; ?>
								<br>
								</p>

								<h3><?php _e('Order details', 'rfswp') ?></h3>
								<table border="0" cellpadding="0" cellspacing="0" class="striped">
									<tbody>
										<tr>
											<td width="50%"><?php _e('Order number:', 'rfswp'); ?></td>
											<td width="50%"><?php echo esc_html( $order_number ); ?></td>
										</tr>
										<tr>
											<td width="50%"><?php _e('Order date:', 'rfswp') ?></td>
											<td width="50%"><?php echo date('Y.m.d', $order_date); ?></td>
										</tr>
										<tr>
											<td width="50%"><?php _e('Payment method:', 'rfswp'); ?></td>
											<td width="50%"><?php $payment_method == 'transfer' ? _e('Bank transfer', 'rfswp') : _e('Online payment', 'rfswp'); ?></td>
										</tr>
										<tr>
											<td width="50%"><?php _e('Delivery:', 'rfswp'); ?></td>
											<td width="50%"><?php echo $shipment_type.'<br>'.$shipment_item; ?></td>
										</tr>
									</tbody>
								</table>

								<br>
								<?php if( $send_to == 'admin' ) : ?>
								<?php endif; ?>
								<h3><?php _e('Customer details', 'rfswp'); ?></h3>
								<table border="0" cellpadding="0" cellspacing="0" class="striped">
									<tbody>
										<tr>
											<td width="50%"><?php _e('Full Name:', 'rfswp'); ?></td>
											<td width="50%"><?php echo esc_html( $customer_first_name.' '.$customer_last_name ); ?></td>
										</tr>
										<?php if( $customer_type == 'individual' && $customer_personal_nip ) : ?>
										<tr>
											<td width="50%"><?php _e('NIP:', 'rfswp'); ?></td>
											<td width="50%"><?php echo esc_html( $customer_personal_nip ); ?></td>
										</tr>
										<?php endif; ?>
										<?php if( $customer_type == 'company' ) : ?>
										<tr>
											<td width="50%"><?php _e('Company:', 'rfswp'); ?></td>
											<td width="50%"><?php echo esc_html( $customer_company_name ); ?></td>
										</tr>
										<tr>
											<td width="50%"><?php _e('NIP:', 'rfswp'); ?></td>
											<td width="50%"><?php echo esc_html( $customer_company_nip ); ?></td>
										</tr>
										<?php endif; ?>
										<tr>
											<td width="50%"><?php _e('Address:', 'rfswp') ?></td>
											<td width="50%"><?php echo __('Street:', 'rfswp').' '.esc_html( $address_street ).' '.esc_html( $address_number ).'<br>'.esc_html( $address_postcode ).' '.esc_html( $address_city ).'<br>'.esc_html( $address_country ); ?></td>
										</tr>
										<tr>
											<td width="50%"><?php _e('Email address:', 'rfswp'); ?></td>
											<td width="50%"><?php echo antispambot( $customer_email ); ?></td>
										</tr>
										<tr>
											<td width="50%"><?php _e('Phone number:', 'rfswp'); ?></td>
											<td width="50%"><?php echo esc_html( $customer_phone ); ?></td>
										</tr>
									</tbody>
								</table>

								<?php if( in_array( 'corten-product', $product_types_in_order ) ) : ?>
								<br>
				
								<h3><?php _e('Shipping details', 'rfswp'); ?></h3>
								<table border="0" cellpadding="0" cellspacing="0" class="striped">
									<tbody>
										<?php if( $shipment_different == 'on' ) : ?>
										<tr>
											<td width="50%"><?php _e('Address:', 'rfswp') ?></td>
											<td width="50%"><?php echo __('Street:', 'rfswp').' '.esc_html( $shipment_street ).' '.esc_html( $shipment_number ).'<br>'.esc_html( $shipment_postcode ).' '.esc_html( $shipment_city ).'<br>'.esc_html( $shipment_country ); ?></td>
										</tr>
										<?php else : ?>
										<tr>
											<td colspan="2"><?php _e('Shipping to customer address', 'rfswp'); ?></td>
										</tr>
										<?php endif; ?>
									</tbody>
								</table>
								<?php endif; ?>

							</td>
						</tr>
						<tr>
							<td>
								<?php if( $products && $totals ) : ?>
									<br>
									<h3><?php _e('Order', 'rfswp') ?></h3>
									<table border="0" cellpadding="0" cellspacing="0">
										<thead>
											<tr>
												<th><?php _e('Product', 'rfswp'); ?></th>
												<th><?php _e('Quantity', 'rfswp'); ?></th>
												<th><?php _e('Cost', 'rfswp'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($products as $product) : ?>
											<?php $prefix = CORTEN_SHOP::package_prefix( $product['id'] ); ?>
											<tr>
												<td><?php echo $prefix.$product['name']; ?></td>
												<td><?php echo $product['qty']; ?></td>
												<td><?php echo CORTEN_SHOP::format_price( $product['sum_price'] ).' '.__('PLN', 'rfswp'); ?> </td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
									<br>
									<div class="table-summary">
										<table border="0" cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td width="50%"><?php _e('Total', 'rfswp'); ?></td>
													<td width="50%"><?php echo CORTEN_SHOP::format_price( $totals['total'] ); ?> <?php _e('PLN', 'rfswp'); ?></td>
												</tr>
												<tr>
													<td width="50%"><?php _e('VAT tax', 'rfswp'); ?></td>
													<td width="50%"><?php echo CORTEN_SHOP::format_price( $totals['tax'] ); ?> <?php _e('PLN', 'rfswp'); ?></td>
												</tr>
												<?php if( in_array( 'corten-product', $product_types_in_order ) ) : ?>
												<tr>
													<td width="50%"><?php _e('Shipment', 'rfswp'); ?></td>
													<td width="50%"><?php echo esc_html( $shipment_type ); ?>: <?php echo CORTEN_SHOP::format_price( $totals['shipment'] ); ?> <?php _e('PLN', 'rfswp'); ?></td>
												</tr>
												<?php endif; ?>
												<tr>
													<td width="50%"><?php _e('To pay', 'rfswp'); ?></td>
													<td width="50%"><?php echo CORTEN_SHOP::format_price( $totals['to_pay'] ); ?> <?php _e('PLN', 'rfswp'); ?></td>
												</tr>
											</tbody>
										</table>
									</div>
								<?php endif; ?>
							</td>
						</tr>
						<?php if( in_array( 'corten-package', $product_types_in_order ) ) : ?>
						<tr>
							<td>
								<br>
								<h4 style="text-align:center;"><?php _e('Package activation code', 'rfswp') ?><br> <?php echo $code; ?></h4>
							</td>
						</tr>
						<?php endif; ?>
						</table>
					</td>
					</tr>

				<!-- END MAIN CONTENT AREA -->
				</table>

				<!-- START FOOTER -->
				<div class="footer">
					<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="content-block">
							<span><?php _e('Best regards', 'rfswp') ?></span>
							<br>
							<span><?php bloginfo( 'name' ); ?></span>
						</td>
					</tr>
					</table>
				</div>
				<!-- END FOOTER -->
				
				<!-- END CENTERED WHITE CONTAINER -->
				</div>
			</td>
			<td>&nbsp;</td>
			</tr>
		</table>
	</body>
</html>