<?php
/**
 * The Template for displaying all single posts.
 *
 * @author Rafał Puczel
 */
get_header(); ?>

<?php  
$widgets = get_field('_institutions_widgets');
?>

<main>
  <div class="container-fluid page-container institution-single">
    <div class="container">

      <?php _get_template_part( 'general', 'institutions' ); ?>

      <?php RFS_WIDGETS::loop_widgets($widgets); ?>

    </div>
  </div>
</main>

<?php get_footer(); ?>
