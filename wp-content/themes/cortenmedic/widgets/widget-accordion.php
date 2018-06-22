<?php
$header       = get_field('_widget_header', $widget_id);
$header_style = get_field('_widget_header_style', $widget_id);
$accordion    = get_field('_widget_accordion', $widget_id);
?>
<?php if( $accordion ) : ?>
<div class="container">
  <div class="wiget-item widget-accordion">
    <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

    <div class="panel-group" id="accordion<?php echo $index; ?>">
      <?php $i=1; foreach($accordion as $block) : ?>
        <?php  
        $header = $block['_header'];
        $text   = $block['_text'];
        ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion<?php echo $index; ?>" href="#<?php echo $index.'-'.$i.'-'.sanitize_title( $header ); ?>">
                <?php echo esc_html( $header ); ?>
              </a>
            </h4>
          </div>
          <div id="<?php echo $index.'-'.$i.'-'.sanitize_title( $header ); ?>" class="panel-collapse collapse">
            <div class="panel-body">
              <?php echo ( $text ); ?>
            </div>
          </div>
        </div>
      <?php $i++; endforeach; ?>

    </div>

  </div>
</div>
<?php endif; ?>