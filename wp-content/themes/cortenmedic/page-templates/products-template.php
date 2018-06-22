<?php
/**
 * Template name: Produkty
 * The template for displaying shop products page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php  
$products_count = absint( CORTEN_SHOP::products_count() );
$widgets = get_field('_products_page_widgets');
?>

<main>
  <div class="container-fluid page-container products-page">
    <div class="container">

      <?php _get_template_part( 'search', 'shop/products' ); ?>

      <div class="row">
        <div class="col-sm-3 products-sidebar">
          <?php _get_template_part( 'sidebar', 'shop/products', array('products_count' => $products_count) ); ?>
        </div>
        <div class="col-sm-9">
          <?php _get_template_part( 'main', 'shop/products', array('products_count' => $products_count) ); ?>

          <div class="products-page-widgets">
          <?php RFS_WIDGETS::loop_widgets($widgets); ?>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</main>

<?php get_footer(); ?>