<?php
$post_id = $params['post_id'];
wp_nonce_field( 'check_product_order_status_nonce', 'product_order_status_nonce' );
$order_status = CORTEN_SHOP::order_data_item( $post_id, 'status' );
?>

<div class="metabox-inner">
  <p>
    <label for="product-order-statuses"><?php _e( 'Order status', 'rfswp' )?></label>
    <select name="product-order-statuses" id="product-order-statuses">
      <?php for($i=1; $i<8; $i++) : ?>
        <option value="<?php echo $i ?>"<?php if( $order_status == $i) : ?> selected<?php endif; ?>><?php echo CORTEN_SHOP::order_status($i); ?></option>
      <?php endfor; ?>
    </select>
  </p>
</div>

<div id="major-publishing-actions">
  <div id="delete-action">
  <a class="submitdelete deletion" href="<?php echo get_delete_post_link( $post_id ); ?>"><?php _e('Move to trash', 'rfswp'); ?></a></div>

  <div id="publishing-action">
    <span class="spinner"></span>
    <input name="original_publish" id="original_publish" value="<?php _e('Update'); ?>" type="hidden">
    <?php submit_button( __('Update'), 'primary', 'submit', false ); ?>
  </div>
  <div class="clear"></div>
</div>
