<?php
/**
 * The template for displaying product category page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php  
$products_count = absint( CORTEN_SHOP::products_count() );
?>

<main>
  <div class="container-fluid page-container products-page product-category-page">
    <div class="container">

      <?php _get_template_part( 'search', 'shop/products' ); ?>

      <div class="row">
        <div class="col-sm-3">
          <?php _get_template_part( 'sidebar', 'shop/products', array('products_count' => $products_count) ); ?>
        </div>
        <div class="col-sm-9">
          <?php _get_template_part( 'main', 'shop/products', array('products_count' => $products_count) ); ?>
        </div>
      </div>
      
    </div>
  </div>
</main>

<?php get_footer(); ?>