<?php  
$id           = isset( $params['id'] ) ? sanitize_text_field( $params['id'] ) : 0;
$name         = isset( $params['name'] ) ? sanitize_text_field( $params['name'] ) : '';
$term_id      = isset( $params['term_id'] ) ? absint( $params['term_id'] ) : 0;
$show_link    = isset( $params['show_link'] ) ? absint( $params['show_link'] ) : false;
$image        = get_field('_sg_image', $id);
$header       = get_field('_sg_header', $id);
$text         = get_field('_sg_text', $id);
$text_side    = get_field('_sg_text_side', $id);
$link_to_self = get_field('_sg_link_to_self', $id);
$link_type    = get_field('_sg_link_type', $id);
$link_inner   = get_field('_sg_link_inner', $id);
$link_outer   = get_field('_sg_link_outer', $id);

$header       = $header ? $header : $name;
$link         = get_term_link( $term_id );

if( !$link_to_self ) {
  $link       = $link_type == 'page' ? get_permalink( $link_inner ) : $link_outer;
}
?>

<?php if($image) : ?>
<div class="offer-item">
  <img src="<?php echo esc_url($image['sizes']['slider-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
  <div class="offer-item-content container">
    <div class="row">
      <div class="offer-item-content-inner<?php if( $text_side ) : ?> pull-left<?php else : ?> pull-right<?php endif; ?>">
        <h2 class="h1 text-uppercase"><strong><?php echo esc_html( $header ); ?></strong></h2>
        <?php if( $text ) : ?><h4><?php echo esc_html( $text ); ?></h4><?php endif; ?>
        <?php if( $show_link ) : ?><a href="<?php echo esc_url( $link ); ?>" class="btn btn-info btn-medium"><?php pll_trans('Zobacz więcej'); ?></a><?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>