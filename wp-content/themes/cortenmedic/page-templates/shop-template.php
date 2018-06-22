<?php
/**
 * Template name: Sklep - Główna
 * The template for displaying shop main page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container shop-main-page">
    <div class="container">

      <div class="row shop-page-top">
        <div class="col-md-9">

          <?php _get_template_part( 'slider', 'shop' ); ?>

          <?php _get_template_part( 'offer', 'shop' ); ?>

        </div>
        <div class="col-md-3 shop-sidebar">
          <div class="shop-sidebar-inner row">
            <?php _get_template_part( 'box1', 'shop' ); ?>

            <?php _get_template_part( 'box2', 'shop' ); ?>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  <div class="row shop-page-middle">
    <?php _get_template_part( 'widgets', 'shop' ); ?>
  </div>
</main>

<?php get_footer(); ?>