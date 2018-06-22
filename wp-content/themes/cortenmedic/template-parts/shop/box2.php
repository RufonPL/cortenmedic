<?php  
$text   = get_field('_shop_box_2_text');
$btn    = get_field('_shop_box_2_btn');

$button = _link_data( $btn, 'Czytaj więcej' );
?>

<?php if( !_empty_content( $text ) ) : ?>
<div class="shop-box-howto shop-side-box">
  <div class="ssd-text"><?php echo $text; ?></div>
  <?php if( $button['url']  ) : ?>
    <a<?php if( $button['target'] ) : ?> target="<?php echo $button['target']; ?>"<?php endif; ?> href="<?php echo $button['url']; ?>" class="btn btn-primary btn-medium"><?php echo $button['title']; ?></a>
  <?php endif; ?>
</div>
<?php endif; ?>