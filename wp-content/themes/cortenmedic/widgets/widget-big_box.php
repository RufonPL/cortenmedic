<?php
$header             = get_field('_widget_header', $widget_id);
$header_style       = get_field('_widget_header_style', $widget_id);

$invert             = get_field('_widget_bigbox_invert', $widget_id);
$bg_image           = get_field('_widget_bigbox_bg_image', $widget_id);

// LEFT SIDE
$header_left        = get_field('_widget_bigbox_left_header', $widget_id);
$content_type_left  = get_field('_widget_bigbox_content_type', $widget_id);
$btns_left          = get_field('_widget_bigbox_btns_left', $widget_id);
$form_left          = get_field('_widget_bigbox_form', $widget_id);
$text_left          = get_field('_widget_bigbox_text_left', $widget_id);
$btm_text_left      = get_field('_widget_bigbox_btm_text', $widget_id);

// RIGHT SIDE
$header_right       = get_field('_widget_bigbox_right_header', $widget_id);
$content_type_right = get_field('_widget_bigbox_content_type_right', $widget_id);
$btns_right         = get_field('_widget_bigbox_btns_right', $widget_id);
$form_right         = get_field('_widget_bigbox_form_right', $widget_id);
$text_right         = get_field('_widget_bigbox_text', $widget_id);
$btm_text_right     = get_field('_widget_bigbox_btm_text_right', $widget_id);
?>

<div class="container">

  <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

  <div class="wiget-item widget-big-box" <?php if( $bg_image ) : ?>style="background-image:url(<?php echo esc_url( $bg_image['sizes']['bigbox-image'] ); ?>);"<?php endif; ?>>
    <div class="row widget-bb-content">
      <div class="widget-bb-side widget-bb-left<?php if($invert) : ?> widget-bb-color-bg<?php endif; ?>">
        <div class="widget-bb-side-inner <?php if( $btm_text_left ) : ?>widget-bb-side-inner-padding<?php endif; ?>">
          <div class="widget-bb-side-inner-content widget-bb-content-<?php echo esc_html( $content_type_left ); ?>">
            <?php if( $header_left ) : ?><h2<?php if( $content_type_left != 'text' ) : ?> class="text-center"<?php endif; ?>><?php echo wp_kses( _p2br( $header_left ), array( 'br' => array(), 'strong' => array() ) ); ?></h2><?php endif; ?>
            <?php if( $content_type_left == 'btns' && $btns_left ) : ?>
              <?php echo RFS_WIDGETS::widget_bigbox_btns($btns_left); ?>
            <?php endif; ?>
            <?php if( $content_type_left == 'form' && $form_left ) : ?>
              <?php echo do_shortcode( $form_left ); ?>
            <?php endif; ?>
            <?php if( $content_type_left == 'text' && $text_left ) : ?><div class="widget-bb-text"><?php echo wp_kses( $text_left, array( 'br' => array(), 'ul' => array(), 'li' => array(), 'strong' => array(), 'p' => array() ) ); ?></div><?php endif; ?>
          </div>
        </div>
        <?php if( $btm_text_left ) : ?><p class="widget-bb-btm-text"><?php echo wp_kses( $btm_text_left, array( 'br' => array() ) ); ?></p><?php endif; ?>
      </div>
      <div class="widget-bb-side widget-bb-right<?php if(!$invert) : ?> widget-bb-color-bg<?php endif; ?>">
        <div class="widget-bb-side-inner <?php if( $btm_text_right ) : ?>widget-bb-side-inner-padding<?php endif; ?>">
          <div class="widget-bb-side-inner-content widget-bb-content-<?php echo esc_html( $content_type_right ); ?>">
            <?php if( $header_right ) : ?><h2<?php if( $content_type_left != 'text' ) : ?> class="text-center"<?php endif; ?>><?php echo wp_kses( _p2br( $header_right ), array( 'br' => array(), 'strong' => array() ) ); ?></h2><?php endif; ?>
            <?php if( $content_type_right == 'btns' && $btns_right ) : ?>
              <?php echo RFS_WIDGETS::widget_bigbox_btns($btns_right); ?>
            <?php endif; ?>
            <?php if( $content_type_right == 'form' && $form_right ) : ?>
              <?php echo do_shortcode( $form_right ); ?>
            <?php endif; ?>
            <?php if( $content_type_right == 'text' && $text_right ) : ?><div class="widget-bb-text"><?php echo wp_kses( $text_right, array( 'br' => array(), 'ul' => array(), 'li' => array(), 'strong' => array(), 'p' => array() ) ); ?></div><?php endif; ?>
          </div>
        </div>
        <?php if( $btm_text_right ) : ?><p class="widget-bb-btm-text"><?php echo wp_kses( $btm_text_right, array( 'br' => array() ) ); ?></p><?php endif; ?>
      </div>
    </div>
  </div>
</div>