<?php
/**
 * Template name: Nagrody / Certyfikaty
 * The template for displaying prizes page.
 *
 * @author Rafał Puczel
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