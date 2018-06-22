<div class="customer-orders">

  <?php if( CORTEN_PROFILE::order_single_view() > 0 ) : ?>
    <?php _get_template_part( 'orders-details-single', 'shop/my-account', array('order_id' => CORTEN_PROFILE::order_single_view()) ); ?>
  <?php else : ?>
    <?php _get_template_part( 'orders-details-list', 'shop/my-account' ); ?>
  <?php endif; ?>

</div>
</div>