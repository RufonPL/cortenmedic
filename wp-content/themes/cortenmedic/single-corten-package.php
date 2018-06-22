<?php
/**
 * The Template for displaying all single products.
 *
 * @author Rafał Puczel
 */
get_header(); ?>


<main>
  <div class="container-fluid page-container package-single">

    <div class="container">
      <div class="row">
        <?php _get_template_part( 'package', 'shop/products', array('package_id' => get_the_ID()) ); ?>

        <?php _get_template_part( 'package-bottom', 'shop/products' ); ?>
      </div>
    </div>

  </div>
</main>

<?php _get_template_part( 'modal', 'shop' ); ?>

<?php get_footer(); ?>
