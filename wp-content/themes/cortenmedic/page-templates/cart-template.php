<?php
/**
 * Template name: Sklep - Koszyk
 * The template for displaying shop cart page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php  
$cart = CORTEN_SHOP::cart_content();
$cart_count = $cart['count'];
?>

<main>
  <div class="container-fluid page-container shop-cart-page">
    <div class="container">

      <?php echo _section_header( pll_trans('Koszyk', true) ); ?>

      <?php if( $cart_count == 0 ) : ?>
        <?php _get_template_part( 'empty', 'shop/cart' ); ?>
      <?php else : ?>
        <?php _get_template_part( 'table', 'shop/cart', array('cart' => $cart) ); ?>
      <?php endif; ?>

    </div>
  </div>
</main>

<?php get_footer(); ?>