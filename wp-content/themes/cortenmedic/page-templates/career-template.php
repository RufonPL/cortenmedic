<?php
/**
 * Template name: Kariera - Oferty pracy
 * The template for displaying job offers page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php $widgets = get_field('_job_offers_widgets'); ?>

<main>
  <div class="container-fluid page-container career-page">
    <div class="container">

      <?php _get_template_part( 'search', 'career', array('header' => get_field('_jobs_search_header')) ); ?>

      <?php _get_template_part( 'list', 'career' ); ?>

      <?php RFS_WIDGETS::loop_widgets($widgets); ?>
      
    </div>
  </div>
</main>

<?php get_footer(); ?>