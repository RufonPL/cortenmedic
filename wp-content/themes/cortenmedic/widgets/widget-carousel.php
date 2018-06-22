<?php 
$header   = get_field('_widget_carousel_header', $widget_id);
$carousel = get_field('_widget_carousel', $widget_id); 
?>
<?php if( $carousel ) : ?>
<div class="container">
  <div class="wiget-item widget-carousel">

    <div class="widget-carousel-content row">
      <?php if( $header ) : ?>
      <div class="widget-carousel-header text-center">
        <h4><?php echo esc_html( $header ); ?></h4>
      </div>
      <?php endif; ?>
      <div class="widget-carousel-simple owl-carousel">
        <?php foreach($carousel as $items) : ?>
          <?php  
          $image      = $items['_image'];
          $link_type  = $items['_link_type'];
          $link_inner = $items['_link_inner'];
          $link_outer = $items['_link_outer'];
          $link       = $link_type == 'inner' ? get_permalink( $link_inner ) : $link_outer;
          ?>
          <?php if($image) : ?>
          <div class="widget-cs-item">
            <a href="<?php echo esc_url( $link ); ?>"><img src="<?php echo esc_url( $image['sizes']['medium'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>"></a>
          </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</div>
<?php endif; ?>