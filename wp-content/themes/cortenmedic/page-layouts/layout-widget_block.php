<?php 
$widget_id 		= get_sub_field('_widget');
$widget_type 	= get_field('_widget_type', $widget_id);

if( $widget_type ) {
  RFS_WIDGETS::show_widget($widget_type['value'], $widget_id, $index);
}
?>