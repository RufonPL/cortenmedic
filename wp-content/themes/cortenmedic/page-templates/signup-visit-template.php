<?php
/**
 * Template name: Zapis na wizytę
 * The template for displaying visit signup page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container visit-signup-page">

    <div class="container">
      <div class="row">
        <div class="col-sm-6 vs-page-left">
          <?php _get_template_part( 'form', 'signup' ); ?>
        </div>
        <div class="col-sm-6 vs-page-right">
          <?php _get_template_part( 'box', 'signup' ); ?>
        </div>
      </div>
    </div>
      
  </div>
</main>

<?php get_footer(); ?>