<?php
$header       = get_field('_widget_header', $widget_id);
$header_style = get_field('_widget_header_style', $widget_id);
$lists        = get_field('_widget_lists', $widget_id);
?>
<?php if( $lists ) : ?>
<?php 
$count = count($lists); 

switch( $count ) {
  case 2:
    $class = 'col-sm-6';
    break;
  case 3:
    $class = 'col-sm-4';
    break;
  case 4:
    $class = 'col-sm-3';
    break;
  default:
    $class = 'col-sm-12';
    break;
}
?>
<div class="container">
  <div class="wiget-item widget-lists">
    <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

    <div class="row widget-lists-row">
      <?php foreach($lists as $item) : ?>
        <?php
        $header = $item['_header'];
        $list   = $item['_list'];
        ?>
        <div class="widget-list <?php echo $class; ?>">
          <?php if( $header ) : ?><h4><span></span><?php echo esc_html( $header ); ?></h4><?php endif; ?>

          <?php if( $list ) : ?>
          <ul class="list-unstyled">
            <?php foreach($list as $item) : ?>
            <li><?php echo esc_html( $item['_text'] ); ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <a class="text-uppercase show-more" href="#more"><strong><?php pll_trans('Więcej'); ?></strong><i class="fa fa-angle-double-right"></i></a>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>
<?php endif; ?>