<?php $order_exists = CORTEN_SHOP::order_exists(); ?>

<div class="thank-you-content">
  <?php if( $order_exists ) : ?>
  	<?php
    $products_no  = CORTEN_SHOP::order_data_item($order_exists, 'products');
    $products     = array();
    for($i=1; $i<=$products_no; $i++) {
      $products[] = CORTEN_SHOP::order_data_item($order_exists, 'product_'.$i);
    }

    CORTEN_SHOP::update_stock( $products, 'add', $order_exists );
		CORTEN_SHOP::update_order_status(4, $order_exists);
    ?>
    <h4><?php pll_trans('Twoje zamówienie zostało anulowane'); ?></h4>
  <?php else : ?>
    <h4><?php pll_trans('Nieprawidłowy token'); ?></h4>
  <?php endif; ?>
</div>