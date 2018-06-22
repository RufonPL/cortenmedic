<?php  
$post_id        = $params['post_id'];
$products       = array();
$products_no    = CORTEN_SHOP::order_data_item($post_id, 'products');
$totals         = CORTEN_SHOP::order_data_item($post_id, 'totals');

$shipment_type  = CORTEN_SHOP::order_data_item($post_id, 'shipment_method');

for($i=1; $i<=$products_no; $i++) {
  $products[] = CORTEN_SHOP::order_data_item($post_id, 'product_'.$i);
}

$product_types_in_order = CORTEN_SHOP::cart_product_types($products);
?>

<?php if( $products && $totals ) : ?>
<div class="shop-meta-box">
  <div class="row order-products-table">
    <table class="wp-list-table widefat fixed striped posts">
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
          <td><a target="_blank" href="<?php echo esc_url( get_edit_post_link( $product['id'] ) ); ?>"><?php echo $prefix.$product['name']; ?></a></td>
          <td><?php echo $product['qty']; ?></td>
          <td><?php echo CORTEN_SHOP::format_price( $product['sum_price'] ).' '.__('PLN', 'rfswp'); ?> </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="row order-totals-table">
    <h4><?php _e('Summary', 'rfswp'); ?></h4>
    <table class="wp-list-table widefat fixed striped posts">
      <tbody>
        <tr>
          <td>
            <div class="td-inner">
              <strong><?php _e('Total', 'rfswp'); ?></strong>
            </div>
          </td>
          <td>
            <div class="td-inner">
              <?php echo CORTEN_SHOP::format_price( $totals['total'] ); ?> <?php _e('PLN', 'rfswp'); ?>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="td-inner">
              <strong><?php _e('VAT tax', 'rfswp'); ?></strong>
            </div>
          </td>
          <td>
            <div class="td-inner">
              <?php echo CORTEN_SHOP::format_price( $totals['tax'] ); ?> <?php _e('PLN', 'rfswp'); ?>
            </div>
          </td>
        </tr>
        <?php if( in_array( 'corten-product', $product_types_in_order ) ) : ?>
        <tr>
          <td>
            <div class="td-inner">
              <strong><?php _e('Shipment', 'rfswp'); ?></strong>
            </div>
          </td>
          <td>
            <div class="td-inner">
              <?php echo esc_html( $shipment_type ); ?>: <?php echo CORTEN_SHOP::format_price( $totals['shipment'] ); ?> <?php _e('PLN', 'rfswp'); ?>
            </div>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td>
            <div class="td-inner">
              <strong><?php _e('To pay', 'rfswp'); ?></strong>
            </div>
          </td>
          <td>
            <div class="td-inner">
              <?php echo CORTEN_SHOP::format_price( $totals['to_pay'] ); ?> <?php _e('PLN', 'rfswp'); ?>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  
</div>
<?php endif; ?>