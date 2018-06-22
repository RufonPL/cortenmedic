<?php
/**
 * The template for displaying all pages.
 *
 * @author Rafal Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container">
    <?php while( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'content', 'page' ); ?>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
