<?php
$header       = get_field('_widget_header', $widget_id);
$header_style = get_field('_widget_header_style', $widget_id);
$boxes        = get_field('_widget_bc_boxes', $widget_id);
?>
<?php if( $boxes ) : ?>
<?php $count = count($boxes); ?>
<div class="container">
  <div class="wiget-item widget-box-content">
    <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

    <div class="wiget-bc-boxes row">
      <div class="widget-bc-wrap col-md-6">
      <?php $i=1; foreach($boxes as $box) : ?>
        <?php  
        $image      = $box['_image'];
        $text       = $box['_text'];
        $link_type  = $box['_link_type'];
        $link_inner = $box['_link_inner'];
        $link_outer = $box['_link_outer'];
        $link       = $link_type == 'inner' && $link_inner ? get_permalink( $link_inner ) : $link_outer;
        ?>
        <?php if($image) : ?>
        <div class="widget-bc-item col-sm-6">
          <div class="widget-bc-inner">
            <?php if( $link ) : ?><a href="<?php echo esc_url( $link ); ?>"><?php endif; ?>
            <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
            <?php if( !_empty_content( $text ) ) : ?>
            <div class="widget-bc-text">
              <div class="widget-bc-text-inner">
                <?php echo wp_kses( $text, array( 'br' => array() ) ); ?>
              </div>
            </div>
            <?php endif; ?>
            <?php if( $link ) : ?></a><?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if( $i%2==0 && $i!=$count ) : ?>
        </div>
        <?php if( $i%4==0 && $i!=$count ) : ?>
        </div>
        <div class="wiget-bc-boxes row">
        <?php endif; ?>
        <div class="widget-bc-wrap col-md-6">
        <?php endif; ?>
      <?php $i++; endforeach; ?>
      </div>
    </div>

  </div>
</div>
<?php endif; ?>