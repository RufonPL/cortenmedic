<?php
/**
 * @author Rafał Puczel
 */
?>

<?php  
$blog_id      = get_option('page_for_posts');
$thumbnail    = get_field('_thumbnail');
$thumbnailsm  = get_field('_thumbnail_sm');
$image        = $thumbnailsm ? $thumbnailsm : $thumbnail;
$places       = _get_post_terms_html(get_the_ID(), 'institution-city', true, false, get_permalink( $blog_id ).'?filter=1&pp=' );
?>

<article>
	<div id="post-<?php the_ID(); ?>" <?php post_class('post-item row'); ?>>
    <div class="post-item-left pull-left">
      <?php if($image) : ?>
      <a href="<?php the_permalink(); ?>">
        <img src="<?php echo esc_url($image['sizes']['post-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
        <div class="post-item-date-big">
          <span><?php the_time('d'); ?></span>
          <span><?php the_time('F'); ?></span>
        </div>
      </a>
      <?php endif; ?>
    </div>
    <div class="post-item-right">
      <header>
        <h1 class="h4"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
      </header>
      <p><?php pll_trans('Data:'); ?> <a href="<?php echo esc_url( get_permalink( $blog_id ).'?filter=1&pd='.get_the_time('Ymd') ); ?>"><?php the_time('d.m.Y'); ?></a></p>
      <?php if( $places ) : ?><p><?php pll_trans('Miejsce:'); ?> <span><?php echo $places; ?></span></p><?php endif; ?>
    </div>
  </div>
</article>
