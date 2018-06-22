<?php
/**
 * The Template for displaying single job offers posts.
 *
 * @author Rafał Puczel
 */
get_header(); ?>

<?php  
$widgets = get_field('_job_widgets');
?>

<main>
  <div class="container-fluid page-container job-offer-single">

    <?php _get_template_part( 'content', 'career' ); ?>
    
    <div class="container">

    <?php RFS_WIDGETS::loop_widgets($widgets); ?>

    </div>
  </div>
</main>

<?php get_footer(); ?>
