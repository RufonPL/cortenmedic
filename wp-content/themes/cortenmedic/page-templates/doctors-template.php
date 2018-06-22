<?php
/**
 * Template name: Lekarze
 * The template for displaying doctors page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container doctors-page">
    <div class="container">

      <?php _get_template_part( 'search', 'institutions', array(
        'header' => get_field('_doctors_search_header'),
        'doctors' => true,
      ) ); ?>

      <?php _get_template_part( 'list', 'doctors' ); ?>
      
    </div>
  </div>
</main>

<?php get_footer(); ?>