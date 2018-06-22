<?php  
$widgets = get_field('_shop_widgets');
?>

<?php if( $widgets ) : ?>
<div class="shop-page-widgets">
  <?php RFS_WIDGETS::loop_widgets($widgets); ?>
</div>
<?php endif; ?>