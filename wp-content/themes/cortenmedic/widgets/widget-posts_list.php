<?php
$header       = get_field('_widget_header', $widget_id);
$header_style = get_field('_widget_header_style', $widget_id);
$post_type    = get_field('_widget_posts_list_pt', $widget_id);
$limit        = get_field('_widget_posts_list_limit', $widget_id);

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$posts = new WP_Query( array(
   'post_type'      => esc_html( $post_type ),
   'posts_per_page' => $limit ? absint($limit) : 3,
   'post_status'    => 'publish',
   'paged'          => $paged
) );
?>

<div class="container">
  <div class="wiget-item widget-posts_list">
    <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

    <?php if( $posts->have_posts() ) : ?>
    <div class="widget-pl-posts row">
      <?php while($posts->have_posts()) : $posts->the_post(); ?>
        <?php  
        $text         = get_the_content();
        $thumbnail    = get_field('_thumbnail');
        $thumbnailsm  = get_field('_thumbnail_sm');
        $image        = $thumbnailsm ? $thumbnailsm : $thumbnail;
        ?>
        <div class="widget-pl-item post-item row">
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
          <p><?php pll_trans('Data:'); ?> <span><?php the_time('d.m.Y'); ?></span></p>
          <?php if( $text && !_empty_content( $text ) ) : ?>
          <div class="post-item-excerpt">
            <?php echo _excerpt( _p2br( $text ), 30, '', '<br>') ?>
          </div>
          <?php endif; ?>
        </div>
        </div>
      <?php endwhile; ?>
      <?php echo _pagination( '', 2, $posts, $paged); ?>
    </div>
    <?php endif; wp_reset_postdata(); ?>

  </div>
</div>