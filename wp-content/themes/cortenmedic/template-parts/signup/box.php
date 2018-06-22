<?php  
$header = get_field('_vs_box_header');
$text   = get_field('_vs_box_text');
$btns   = get_field('_vs_box_btns');
?>

<div class="vs-box">
  <?php if( $header ) : ?><h2><strong><?php echo esc_html( $header ); ?></strong></h2><?php endif; ?>

  <?php if( !_empty_content( $text ) ) : ?>
    <div class="vs-box-text">
      <?php echo ( $text ); ?>
    </div>
  <?php endif; ?>

  <?php if( $btns ) : ?>
    <div class="vs-box-btns">
      <?php echo RFS_WIDGETS::widget_bigbox_btns($btns); ?>
    </div>
  <?php endif; ?>
</div>