<?php
/**
 * Template name: Centra Medyczne
 * The template for displaying institutions page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container institutions-page">
    <div class="container">

      <?php _get_template_part( 'search', 'institutions', array('header' => get_field('_institutions_search_header')) ); ?>

      <?php _get_template_part( 'list', 'institutions' ); ?>
      
    </div>
  </div>
</main>

<?php get_footer(); ?>