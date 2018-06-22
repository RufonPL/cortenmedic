<?php
/**
 * The Template for displaying all single posts.
 *
 * @author Rafał Puczel
 */
get_header(); ?>

<?php 
$thumbnail  = get_field('_thumbnail');
$subtitle   = get_field('_post_subtitle');
$width      = $thumbnail ? $thumbnail['width'] : 0;
$height     = $thumbnail ? $thumbnail['height'] : 0;
?>

<main>
  <div class="container-fluid page-container post-single">

    <div class="post-single-image<?php if( !$thumbnail || $width < 1110 || $height < 250 ) : ?> no-image<?php endif; ?>">
      <?php if($thumbnail && $width >= 1110 && $height >= 250) : ?>
        <img src="<?php echo esc_url($thumbnail['sizes']['slider-image']); ?>" alt="<?php echo esc_attr($thumbnail['alt']); ?>">
      <?php endif; ?>
      <div class="post-single-header container">
        <header>
          <h1 class="h2"><strong><?php the_title(); ?></strong></h1>
          <?php if($subtitle) : ?><h3><strong><?php echo esc_html( $subtitle ); ?></strong></h3><?php endif; ?>
        </header>
      </div>
      <div class="container post-single-date">
        <p><?php the_time('d.m.Y'); ?></p>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-sm-9 posts-single-left">
          <?php while( have_posts() ) : the_post(); ?>
              <?php get_template_part( 'content', 'single' ); ?>
          <?php endwhile; ?>
        </div>
        <div class="col-sm-3 posts-single-right">
          <?php _get_template_part( 'post-sidebar', 'posts' ); ?>
        </div>
      </div>
    </div>

    <?php _get_template_part( 'posts-similar', 'posts' ); ?>

  </div>
</main>

<?php get_footer(); ?>
