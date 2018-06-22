<?php  
$image      = get_field('_widget_image_image', $widget_id);
$header     = get_field('_widget_image_header', $widget_id);
$text_align = get_field('_widget_image_text_position', $widget_id);
$text       = get_field('_widget_image_text', $widget_id);
$link_type  = get_field('_widget_image_link_type', $widget_id);
$link_inner = get_field('_widget_image_link_inner', $widget_id);
$link_outer = get_field('_widget_image_link_outer', $widget_id);
$link       = $link_type == 'inner' && absint( $link_inner ) > 0 ? get_permalink( $link_inner ) : $link_outer;

$width      = $image ? $image['width'] : 0;
$height     = $image ? $image['height'] : 0;
?>
<?php if($image && $width >= 1110 && $height >= 250) : ?>
<div class="container-fluid page-container post-single wiget-item widget-image">

  <div class="post-single-image">
    <img src="<?php echo esc_url($image['sizes']['slider-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
    <div class="post-single-header widget-image-content container <?php echo esc_html( $text_align ); ?>">
      <?php if( !_empty_content( $header ) ) : ?>
      <h5 class="text-uppercase"><?php echo wp_kses( _p2br( $header ), array( 'br' => array(), 'strong' => array() ) ); ?></h5>
      <?php endif; ?>
      <?php if( !_empty_content( $text) ) : ?>
      <p class="widget-image-text"><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></p>
      <?php endif; ?>
      <?php if( $link ) : ?>
      <a href="<?php echo esc_url( $link ); ?>" class="btn btn-primary"><i class="fa fa-angle-right"></i><?php pll_trans('Więcej'); ?></a>
      <?php endif; ?>
    </div>
  </div>

</div>
<?php endif; ?>