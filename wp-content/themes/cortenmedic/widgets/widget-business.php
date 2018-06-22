<?php
$header         = get_field('_widget_header', $widget_id);
$header_style   = get_field('_widget_header_style', $widget_id);

$bg_image       = get_field('_widget_bigbox_bg_image', $widget_id);
$bg_image_sm    = get_field('_business_box_sm_bg', $widget_id);

// LEFT SIDE
$header_left    = get_field('_widget_bigbox_left_header', $widget_id);
$text_left      = get_field('_business_box_text_left', $widget_id);

// RIGHT SIDE
$header_right   = get_field('_widget_bigbox_right_header', $widget_id);
$text_right     = get_field('_business_box_text_right', $widget_id);

$box_sm         = get_field('_business_show_box_sm', $widget_id);
$box_sm_text    = get_field('_business_box_sm_text', $widget_id);
$box_sm_btn     = get_field('_business_show_box_sm_btn', $widget_id);
?>

<div class="container">

  <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

  <div class="wiget-item widget-business-box">
    <div class="row">
      <div class="<?php if( absint( $box_sm ) == 1 ) : ?>col-sm-9<?php else : ?>col-sm-12<?php endif; ?> widget-business-left-box">
        <div class="widget-business-box-inner wblb-inner" <?php if( $bg_image ) : ?>style="background-image:url(<?php echo esc_url( $bg_image['sizes']['gallery-image'] ); ?>);"<?php endif; ?>>
          <div class="widget-business-overlay widget-business-overlay-dark"></div>
          <div class="row wblb-content">
            <div class="col-sm-6 wblb-content-col">
              <div class="wblb-content-col-inner">
                <?php if( $header_left ) : ?><h2 class="text-center"><?php echo wp_kses( _p2br( $header_left ), array( 'br' => array(), 'strong' => array() ) ); ?></h2><?php endif; ?>
                <?php echo $text_left; ?>
              </div>
            </div>
            <div class="col-sm-6 wblb-content-col">
              <div class="wblb-content-col-inner">
                <?php if( $header_right ) : ?><div class="text-center"><h2 class="text-left"><?php echo wp_kses( _p2br( $header_right ), array( 'br' => array(), 'strong' => array() ) ); ?></h2></div><?php endif; ?>
                <?php echo $text_right; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php if( absint( $box_sm ) == 1 ) : ?>
      <div class="col-sm-3 widget-business-right-box">
        <div class="widget-business-box-inner wbrb-inner" <?php if( $bg_image_sm ) : ?>style="background-image:url(<?php echo esc_url( $bg_image_sm['sizes']['about-image'] ); ?>);"<?php endif; ?>>
          <div class="widget-business-overlay widget-business-overlay-light"></div>
          <div class="wbrb-content-col-inner">
            <div class="wbrb-content-col-inner-text">
              <?php echo $box_sm_text; ?>
            </div>
            <?php if( $box_sm_btn ) : ?>
              <?php 
              $btn_text = $box_sm_btn['title'] ? $box_sm_btn['title'] : pll_trans('Zobacz więcej', true); 
              $btn_target = $box_sm_btn['target'] == '_blank' ? 'target="_blank"' : '';
              ?>
              <a <?php echo $btn_target; ?> href="<?php echo esc_url( $box_sm_btn['link'] ); ?>" class="btn btn-info"><?php echo esc_html( $btn_text ); ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>