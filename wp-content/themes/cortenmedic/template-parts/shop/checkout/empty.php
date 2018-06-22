<?php
$cart = CORTEN_SHOP::cart_content();
$cart_count = $cart['count'];
$is_order_summary = CORTEN_SHOP::is_order_summary_any_page();

if( $cart_count == 0 && !$is_order_summary ) {
  $url = CORTEN_SHOP::shop_links('products');
  wp_safe_redirect( $url ); exit();
}
?>