<?php _get_template_part( 'empty', 'shop/checkout' ); ?>
<?php if( CORTEN_SHOP::is_order_summary_page('payment-online', 'notification') ) : ?>
  <?php _get_template_part( 'online-notification', 'shop/summary' ); ?>
<?php endif; ?>
<?php
/**
 * Template name: Sklep - Zamówienie
 * The template for displaying shop checkout page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container shop-checkout-page">
    <div class="container">

      <?php echo _section_header( pll_trans('Zamówienie', true) ); ?>

      <?php if( CORTEN_SHOP::is_order_summary_page('bank-transfer') ) : ?>
        <?php _get_template_part( 'bank-transfer', 'shop/summary' ); ?>
      <?php elseif( CORTEN_SHOP::is_order_summary_page('payment-online', 'completed') ) : ?>
        <?php _get_template_part( 'online-completed', 'shop/summary' ); ?>
      <?php elseif( CORTEN_SHOP::is_order_summary_page('payment-online', 'canceled') ) : ?>
        <?php _get_template_part( 'online-canceled', 'shop/summary' ); ?>
      <?php elseif( CORTEN_SHOP::is_order_summary_page('payment-online', 'notification') ) : ?>
        
      <?php else : ?>
        <?php _get_template_part( 'form', 'shop/checkout' ); ?>
      <?php endif; ?>

    </div>
  </div>
</main>

<?php _get_template_part( 'modal', 'shop' ); ?>

<?php get_footer(); ?>