<?php
/**
 * The template for displaying Search Results pages.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<div class="container">
  <div class="row">
    <?php if ( have_posts() ) : ?>
      <header>
        <h1><?php printf( __( 'Search results for: %s', 'rfswp' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
      </header>
      <?php while( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'content', get_post_format() ); ?>
      <?php endwhile; ?>
      <?php //rfs_pagination(); ?>
    <?php else : ?>
      <?php get_template_part( 'no-results', 'search' ); ?>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>