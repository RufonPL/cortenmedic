<?php 
$post_id    = get_the_ID();
$categories = get_the_category( $post_id ); ?>

<?php if( $categories ) : ?>
<?php
$style        = 'style1';
$badge_shows  = 'date';
$header       = '<p><strong>'.pll_trans('Zobacz także', true).'</strong></p><p>Podobne wpisy</p>';
$tax_query = array(
  array(
    'taxonomy'  => 'category',
    'field'     => 'id',
    'terms'     => array( $categories[0]->term_id),
    'operator'  => 'IN'
  )
);
$posts = _run_wp_query('post', 'similar', 4, array(), $tax_query, true, 1, array( $post_id ), 'rand');
?>
<div class="container-fluid posts-similar">
  <?php if( $posts->have_posts() ) : ?>
  <div class="container">
    <div class="wiget-item widget-posts-box">
      <?php echo RFS_WIDGETS::widget_header( $header ); ?>
      <div class="widget-pb-posts row">
        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
          <?php 
          $image    = get_field('_thumbnail'); 
          $imagesm  = get_field('_thumbnail_sm'); 
          $thumbnail        = $imagesm ? $imagesm : $image;
          ?>
          <div class="col-sm-3 widget-pb-post">
            <div class="widget-pb-post-inner">
              <?php if($thumbnail && is_array($thumbnail) ) : ?>
              <div class="widget-pb-post-image">
                <a href="<?php the_permalink(); ?>">
                  <img src="<?php echo esc_url( $thumbnail['sizes']['post-image'] ); ?>" alt="<?php echo esc_attr( $thumbnail['alt'] ); ?>">
                </a>
                <?php if( $style == 'style1' ) : ?>
                  <?php echo RFS_WIDGETS::widget_badge( get_the_ID(), $badge_shows ); ?>
                <?php endif; ?>
              </div>
              <?php endif; ?>
              <a class="widget-pb-post-title <?php if( $style == 'style1' ) : ?>widget-pb-pt-style1<?php else : ?>widget-pb-pt-style2<?php endif; ?>" href="<?php the_permalink(); ?>">
                <h4><?php the_title(); ?></h4>
                <?php if( $style == 'style2' ) : ?><i class="fa fa-angle-double-right"></i><?php endif; ?>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
  <?php endif; wp_reset_postdata(); ?>
</div>
<?php endif; ?>
