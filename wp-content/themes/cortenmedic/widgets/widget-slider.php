<?php 
$interval       = get_field('_widget_slide_interval', $widget_id);
$effect         = get_field('_widget_slide_effect', $widget_id);
$slider_type    = get_field('_widget_slider_type', $widget_id);
$images_slides  = get_field('_widget_slides', $widget_id);
$posts_slides   = get_field('_widget_slides_posts', $widget_id);
$posts_limit    = get_field('_widget_slides_posts_limit', $widget_id);
$custom_posts   = get_field('_widget_slides_posts_custom', $widget_id);

$limit          = absint( $posts_limit ) ? absint( $posts_limit ) : 10;
$custom         = $posts_slides == 'custom' ? $custom_posts : false;

switch( $slider_type ) {
  case 'images':
    $slides = $images_slides;
    break;
  case 'posts':
    $slides = RFS_WIDGETS::get_widget_posts($widget_id, 'post', $limit, $custom);
    break;
}
?>

<?php if( $slides ) : ?>
<div class="slider" id="flexslider">
  <div class="flexslider" data-slide-interval="<?php echo absint( $interval ); ?>" data-slide-effect="<?php if( $effect ) : ?><?php echo esc_html( $effect['value'] ); ?><?php endif; ?>">
    <ul class="slides">
    
    <?php if( $slider_type == 'images') : ?>

      <?php foreach($slides as $slide) : ?>
        <?php  
        $image      = $slide['_image'];
        $header     = $slide['_header'];
        $text       = $slide['_text'];
        $link_type  = $slide['_link_type'];
        $link_inner = $slide['_link_inner'];
        $link_outer = $slide['_link_outer'];
        $link       = $link_type == 'inner' ? get_permalink( $link_inner ) : $link_outer;
        ?>
        <?php if( $image ) : ?>
        <li>
          <img src="<?php echo esc_url( $image['sizes']['slider-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>"/>
          <div class="flex-caption container">
              <div class="flex-caption-inner">
                  <?php if( !_empty_content( $header ) ) : ?>
                  <h5 class="text-uppercase"><?php echo wp_kses( _p2br( $header ), array( 'br' => array(), 'strong' => array() ) ); ?></h5>
                  <?php endif; ?>
                  <?php if( !_empty_content( $text) ) : ?>
                  <p class="flex-caption-text"><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></p>
                  <?php endif; ?>
                  <?php if( $link ) : ?>
                  <a href="<?php echo esc_url( $link ); ?>" class="btn btn-primary"><i class="fa fa-angle-right"></i><?php pll_trans('Czytaj więcej'); ?></a>
                  <?php endif; ?>
              </div>
          </div>
        </li>
        <?php endif; ?>
      <?php endforeach; ?>

    <?php endif; ?>

    <?php if( $slider_type == 'posts' ) : ?>
      
      <?php while( $slides->have_posts()) : $slides->the_post(); ?>
        <?php  
        $image  = get_field('_thumbnail');
        $header = get_the_title();
        $text   = _excerpt( get_the_content() );
        $link   = get_permalink();
        $cats_html = _get_post_terms_html(get_the_ID(), '', true, true, get_permalink( get_option('page_for_posts') ).'?filter=1&pc=' );
        ?>
        <?php if($image) : ?>
          <?php if( $image['width'] >= 1920 && $image['height'] >= 570 ) : ?>
            <li>
              <img src="<?php echo esc_url($image['sizes']['slider-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
              <div class="flex-caption container">
                  <div class="flex-caption-inner">
                      <h5 class="text-uppercase"><?php if( !_empty_content( $cats_html ) ) : ?><?php echo $cats_html; ?><br><?php endif; ?><strong><?php echo esc_html( $header ); ?></strong></h5>
                      <?php if( !_empty_content( $text) ) : ?>
                      <p class="flex-caption-text"><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></p>
                      <?php endif; ?>
                      <?php if( $link ) : ?>
                      <a href="<?php echo esc_url( $link ); ?>" class="btn btn-primary"><i class="fa fa-angle-right"></i><?php pll_trans('Czytaj więcej'); ?></a>
                      <?php endif; ?>
                  </div>
              </div>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      <?php endwhile; ?>
      
    
    <?php endif; ?>

    </ul>
  </div>
</div>
<?php endif; ?>