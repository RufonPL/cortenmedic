<?php 
$post_id                = $params['post_id'];

$order_number           = CORTEN_SHOP::order_data_item($post_id, 'number');
$order_date             = CORTEN_SHOP::order_data_item($post_id, 'date');
$order_status           = CORTEN_SHOP::order_data_item($post_id, 'status' );
$payment_method         = CORTEN_SHOP::order_data_item($post_id, 'customer_payment_type');
$shipment_type          = CORTEN_SHOP::order_data_item($post_id, 'shipment_method');
$shipment_item          = CORTEN_SHOP::order_data_item($post_id, 'shipment_item');

$user_id                = CORTEN_SHOP::order_data_item($post_id, 'user_id');
$customer_type          = CORTEN_SHOP::order_data_item($post_id, 'customer_type');
$shipment_different     = CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_different');

$customer_first_name    = CORTEN_SHOP::order_data_item($post_id, 'customer_first_name');
$customer_last_name     = CORTEN_SHOP::order_data_item($post_id, 'customer_last_name');
$customer_personal_nip  = CORTEN_SHOP::order_data_item($post_id, 'customer_personal_nip');
$customer_company_name  = CORTEN_SHOP::order_data_item($post_id, 'customer_company_name');
$customer_company_nip   = CORTEN_SHOP::order_data_item($post_id, 'customer_company_nip');

$address_street         = CORTEN_SHOP::order_data_item($post_id, 'customer_address_street');
$address_number         = CORTEN_SHOP::order_data_item($post_id, 'customer_address_number');
$address_city           = CORTEN_SHOP::order_data_item($post_id, 'customer_address_city');
$address_postcode       = CORTEN_SHOP::order_data_item($post_id, 'customer_address_postcode');
$address_country        = CORTEN_SHOP::order_data_item($post_id, 'customer_address_country');

$customer_email         = CORTEN_SHOP::order_data_item($post_id, 'customer_email');
$customer_phone         = CORTEN_SHOP::order_data_item($post_id, 'customer_phone');

$shipment_street        = CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_street');
$shipment_number        = CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_number');
$shipment_city          = CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_city');
$shipment_postcode      = CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_postcode');
$shipment_country       = CORTEN_SHOP::order_data_item($post_id, 'customer_shipment_country');

$code                   = CORTEN_SHOP::order_data_item($post_id, 'code');

$user_html = __('Guest / not registered', 'rfswp');

if( $user_id > 0 ) {
  $userdata = get_userdata( $user_id );

  if( $userdata ) {
    $email  = $userdata->user_email;
    $link   = get_edit_user_link( $user_id );

    $user_html = '<a target="_blank" href="'.esc_url( $link ).'">#'.absint( $user_id ).'</a> - '.antispambot( $email );
  }
}

$general = array(
  array(
    'label'   =>  __('Order date:', 'rfswp'),
    'active'  => true,
    'html'    => date('d.m.Y', $order_date)
  ),
  array(
    'label'   =>  __('Payment method:', 'rfswp'),
    'active'  => true,
    'html'    => $payment_method == 'transfer' ? __('Bank transfer', 'rfswp') : __('Online payment', 'rfswp')
  ),
  array(
    'label'   =>  __('Delivery:', 'rfswp'),
    'active'  => true,
    'html'    => $shipment_type.'<br>'.$shipment_item
  ),
  array(
    'label'   =>  __('Customer:', 'rfswp'),
    'active'  => true,
    'html'    => $user_html
  ),
);

$customer_details = array(
  array(
    'label'   =>  __('Full Name:', 'rfswp'),
    'active'  => true,
    'html'    => esc_html( $customer_first_name.' '.$customer_last_name )
  ),
  array(
    'label'   =>  __('NIP:', 'rfswp'),
    'active'  => $customer_type == 'individual',
    'html'    => esc_html( $customer_personal_nip )
  ),
  array(
    'label'   =>  __('Company:', 'rfswp'),
    'active'  => $customer_type == 'company',
    'html'    => __('Name: ', 'rfswp').' '.esc_html( $customer_company_name ).'<br>'.__('NIP: ', 'rfswp').' '.esc_html( $customer_company_nip )
  ),
  array(
    'label'   =>  __('Address:', 'rfswp'),
    'active'  => true,
    'html'    => __('Street:', 'rfswp').' '.esc_html( $address_street ).' '.esc_html( $address_number ).'<br>'.esc_html( $address_postcode ).' '.esc_html( $address_city ).'<br>'.esc_html( $address_country )
  ),
  array(
    'label'   =>  __('Email address:', 'rfswp'),
    'active'  => true,
    'html'    => '<a href="mailto:'.antispambot( $customer_email ).'">'.antispambot( $customer_email ).'</a>'
  ),
  array(
    'label'   =>  __('Phone number:', 'rfswp'),
    'active'  => true,
    'html'    => esc_html( $customer_phone )
  ),
);

$shipment_details = array(
  array(
    'label'   =>  __('Address:', 'rfswp'),
    'active'  => $shipment_different == 'on',
    'html'    => __('Street:', 'rfswp').' '.esc_html( $shipment_street ).' '.esc_html( $shipment_number ).'<br>'.esc_html( $shipment_postcode ).' '.esc_html( $shipment_city ).'<br>'.esc_html( $shipment_country )
  ),
);

$products_no  = CORTEN_SHOP::order_data_item($post_id, 'products');
$products     = array();
for($i=1; $i<=$products_no; $i++) {
  $products[] = CORTEN_SHOP::order_data_item($post_id, 'product_'.$i);
}

$product_types_in_order = CORTEN_SHOP::cart_product_types($products);
?>
<div class="shop-meta-box">
  <h3><strong><?php _e('Order no', 'rfswp'); ?> #<?php echo esc_html( $order_number ); ?> - <?php echo CORTEN_SHOP::order_status($order_status); ?></strong></h3>

  
  <?php if( in_array( 'corten-package', $product_types_in_order ) ) : ?>
    <hr>
    <h4><?php _e('Package activation code', 'rfswp') ?> - <?php echo $code; ?></h4>
    <hr>
  <?php endif; ?>
  
  <div class="row">
    <div class="col col-33">
      <h4><strong><?php _e('General information', 'rfswp'); ?></strong></h4>
      <?php foreach($general as $item) : ?>
        <?php if( $item['active'] ) : ?>
          <p>
            <strong><?php echo $item['label']; ?></strong>
            <?php echo $item['html']; ?>
          </p>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="col col-33">
      <h4><strong><?php _e('Customer details', 'rfswp'); ?></strong></h4>
      <?php foreach($customer_details as $item) : ?>
        <?php if( $item['active'] ) : ?>
          <p>
            <strong><?php echo $item['label']; ?></strong>
            <?php echo $item['html']; ?>
          </p>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <div class="col col-33">
      <?php if( in_array( 'corten-product', $product_types_in_order ) ) : ?>
        <h4><strong><?php _e('Shipping details', 'rfswp'); ?></strong></h4>
        <?php foreach($shipment_details as $item) : ?>
          <?php if( $item['active'] ) : ?>
            <p>
              <strong><?php echo $item['label']; ?></strong>
              <?php echo $item['html']; ?>
            </p>
          <?php endif; ?>
        <?php endforeach; ?>
        <?php if( $shipment_different != 'on' ) : ?>
        <p><strong><?php _e('Shipping to customer address', 'rfswp'); ?></strong></p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>