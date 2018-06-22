<?php
$header       = get_field('_widget_header', $widget_id);
$header_style = get_field('_widget_header_style', $widget_id);
$gallery      = get_field('_widget_gallery', $widget_id);
?>
<?php if( $gallery ) : ?>
<div class="container">
  <div class="wiget-item widget-gallery">
    <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

    <div class="row widget-gallery-row">
      <div class="widget-gallery-main-carousel owl-carousel">
        <?php $i=0; foreach($gallery as $image) : ?>
        <div class="widget-gallery-main-item" data-gallery-image-item="<?php echo $i; ?>">
          <img src="<?php echo esc_url( $image['sizes']['gallery-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
        </div>
        <?php $i++; endforeach; ?>
      </div>

      <div class="widget-gallery-nav-carousel owl-carousel">
        <?php $i=0; foreach($gallery as $image) : ?>
        <div class="widget-gallery-nav-item" data-gallery-nav-item="<?php echo $i; ?>">
          <img src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
        </div>
        <?php $i++; endforeach; ?>
      </div>
    </div>

  </div>
</div>
<?php endif; ?>