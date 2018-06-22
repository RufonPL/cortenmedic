<?php
/**
 * Template name: Oferta
 * The template for displaying offer page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container offer-page">

      <?php _get_template_part( 'list', 'offer' ); ?>
      
  </div>
</main>

<?php get_footer(); ?>