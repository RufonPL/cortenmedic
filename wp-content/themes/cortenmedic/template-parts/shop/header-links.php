<?php  
$cart_data = CORTEN_SHOP::shop_header_cart();
?>
<div class="shop-header">
  <?php echo $cart_data['html']; ?>
  <?php if( is_user_logged_in() ) : ?>
    <a href="<?php echo esc_url( CORTEN_SHOP::shop_links('profile') ); ?>" class="btn btn-info btn-wide"><span><?php pll_trans('Moje konto'); ?></span><i class="fa fa-user"></i></a>
  <?php else : ?>
    <a href="<?php echo esc_url( CORTEN_SHOP::shop_links('login') ); ?>" class="btn btn-info btn-wide"><span><?php pll_trans('Zaloguj'); ?></span><i class="fa fa-lock"></i></a>
    <a href="<?php echo esc_url( CORTEN_SHOP::shop_links('register') ); ?>" class="btn btn-info btn-wide"><span><?php pll_trans('Załóż konto'); ?></span><i class="fa fa-user"></i></a>
  <?php endif; ?>
</div>