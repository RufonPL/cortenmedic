<?php
$interval = get_field('_shop_slide_interval');
$slider   = get_field('_shop_slider');
?>

<?php if( $slider ) : ?>
<div class="shop-slider" id="flexslider">
  <div class="flexslider" data-slide-interval="<?php echo absint( $interval ); ?>" data-slide-effect="fade" data-arrows-type="angle">
    <ul class="slides">

      <?php foreach($slider as $slide) : ?>
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
          <img src="<?php echo esc_url( $image['sizes']['about-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>"/>
          <div class="flex-caption container">
              <div class="flex-caption-inner">
                  <?php if( !_empty_content( $header ) ) : ?>
                  <h4><?php echo wp_kses( _p2br( $header ), array( 'br' => array(), 'strong' => array() ) ); ?></h4>
                  <?php endif; ?>
                  <?php if( !_empty_content( $text) ) : ?>
                  <p class="flex-caption-text"><?php echo wp_kses( $text, array( 'br' => array() ) ); ?></p>
                  <?php endif; ?>
                  <?php if( $link ) : ?>
                  <a href="<?php echo esc_url( $link ); ?>" class="btn btn-info btn-medium"><?php pll_trans('Czytaj więcej'); ?></a>
                  <?php endif; ?>
              </div>
          </div>
        </li>
        <?php endif; ?>
      <?php endforeach; ?>

    </ul>
  </div>
</div>
<?php endif; ?>