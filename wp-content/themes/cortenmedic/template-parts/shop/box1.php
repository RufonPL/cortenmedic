<?php  
$image  = get_field('_shop_box_1_image');
$text   = get_field('_shop_box_1_text');
$btn    = get_field('_shop_box_1_btn');

$button = _link_data( $btn, 'Zapisz się' );
?>

<?php if( !_empty_content( $text ) ) : ?>
<div class="shop-box-signup shop-side-box" <?php if( $image ) : ?>style="background-image:url(<?php echo esc_url( $image['sizes']['doctor-image'] ); ?>)"<?php endif; ?>>
  <div class="ssd-text"><?php echo wp_kses( _p2br( $text ), array( 'br' => array(), 'strong' => array() ) ); ?></div>
  <?php if( $button['url']  ) : ?>
    <a<?php if( $button['target'] ) : ?> target="<?php echo $button['target']; ?>"<?php endif; ?> href="<?php echo $button['url']; ?>" class="btn btn-primary btn-medium"><?php echo $button['title']; ?></a>
  <?php endif; ?>
</div>
<?php endif; ?>