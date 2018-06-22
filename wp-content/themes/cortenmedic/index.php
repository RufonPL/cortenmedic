<?php
/**
 * The main template file.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php 
$slider = get_field('_news_slider', get_option('page_for_posts') );
?>

<main>
  <div class="container-fluid page-container">

    <?php if( absint( $slider ) > 0 ) : ?>
      <?php RFS_WIDGETS::show_widget('slider', $slider); ?>
    <?php endif; ?>

    <div class="container posts-index" id="posts-anchor">
      <div class="row">
        <div class="col-sm-9 posts-index-left">

          <div class="row posts-index-header">
            <div class="col-sm-6">
              <?php echo _section_header( get_the_title( get_option('page_for_posts') ) ); ?>
            </div>
            <div class="col-sm-6 text-right">
              <?php echo _news_filter_header(); ?>
            </div>
          </div>

          <div class="posts-list-container">

            <div class="visible-xs search-xs"><?php get_search_form(); ?></div>

            <div class="posts-prev-next row">
              <div class="col-sm-6">
                <?php previous_posts_link( '<i class="fa fa-angle-double-left"></i> '.pll_trans('Poprzednie', true) ); ?> 
              </div>
              <div class="col-sm-6 text-right">
                <?php next_posts_link( pll_trans('Następne', true).' <i class="fa fa-angle-double-right"></i>' ); ?> 
              </div>
            </div>
            
            <?php if( have_posts() ) : ?>
            <?php while( have_posts() ) : the_post(); ?>
        
              <?php get_template_part( 'content', get_post_format() ); ?>

            <?php endwhile; ?>
            
          </div>
          <?php echo _pagination(); ?>
          
          <?php else : ?>
            <div class="container">
              <?php get_template_part( 'no-results' ); ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-sm-3 posts-index-right">
          <?php _get_template_part( 'posts-sidebar', 'posts' ); ?>
        </div>
      </div>
    </div>

  </div>
</main>

<?php get_footer(); ?>
