<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @author Rafał Puczel
 */

get_header(); ?>
<div class="container">
  <div class="row">
    <?php if( have_posts() ) : ?>
    <header>
      <h1>
      <?php  
      if( is_category() ) {
        single_cat_title();
      }elseif( is_tag() ) {
        single_tag_title();
      }elseif( is_tax() ) {
        single_term_title();
      }
      ?>
      </h1>
    </header>
    <?php endif; ?>
    <?php if( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'content', get_post_format() ); ?>
      <?php endwhile; ?>
      <?php //rfs_pagination(); ?>
    <?php else : ?>
      <?php get_template_part( 'no-results', 'archive' ); ?>
    <?php endif; ?>
  
  </div>
</div>
<?php get_footer(); ?>
