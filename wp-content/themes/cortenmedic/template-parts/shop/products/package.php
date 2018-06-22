<?php
$post_id      = $params['package_id'];
$name         = get_field('_package_name', $post_id);
$custom_name  = get_field('_package_name_custom', $post_id);
$image        = get_field('_product_image', $post_id);
$price        = CORTEN_SHOP::product_price_html( $post_id );
$price_text   = get_field('_product_price_text', $post_id);
$description  = get_field('_product_description', $post_id);

$title = $name == 'custom' ? $custom_name : '<strong>'.esc_html( get_the_title( $post_id ) ).'</strong>';

$get_categories = wp_get_post_terms( $post_id, 'corten-package-category' );
$categories     = '';

if( $get_categories ) {
  foreach($get_categories as $cat) {
    $sep = $cat == end($get_categories) ? '' : ', ';
    $categories .= $cat->name.$sep;
  }
}
?>
<div class="row package">
  <div class="col-sm-3 package-image">
    <?php if($image) : ?>
    <img src="<?php echo esc_url($image['sizes']['post-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
    <?php endif; ?>
  </div>
  <div class="col-sm-9 package-content">
    <?php if( $categories ) : ?>
    <h4 class="package-categories"><strong><?php echo $categories; ?></strong></h4>
    <?php endif; ?>
    <h3 class="package-name"><?php echo wp_kses( _p2br( $title ), array( 'br' => array(), 'strong' => array() ) ); ?></h3>

    <?php if( !_empty_content( $description ) ) : ?>
    <div class="package-description">
      <div class="package-description-inner"><?php echo $description; ?></div>
    </div>
    <?php endif; ?>

    <div class="row package-actions">
      <div class="col-sm-5 package-actions-left">
        <?php if( !is_singular('corten-package') ) : ?>
          <a href="<?php the_permalink(); ?>" class="package-more btn btn-info btn-wide"><?php pll_trans('Zobacz więcej'); ?></a>
        <?php endif; ?>
      </div>
      <div class="col-sm-7 package-actions-right">
        <?php if( $price_text ) : ?><p class="package-price-text"><strong><?php echo esc_html( $price_text ); ?></strong></p><?php endif; ?>
        <?php echo $price; ?>
        <?php if( is_singular('corten-package') ) : ?>
          <a href="#<?php echo $post_id; ?>" class="btn btn-primary btn-wide add-to-cart"><?php pll_trans('Do koszyka'); ?><?php echo CORTEN_SHOP::cart_loader(); ?></a>
        <?php else : ?>
          <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-wide"><?php pll_trans('Kup teraz'); ?></a>
        <?php endif; ?>
      </div>
    </div>
    <?php if( function_exists('DISPLAY_ULTIMATE_PLUS') ) : ?>
    <div style="padding-top:25px"><?php echo DISPLAY_ULTIMATE_PLUS(); ?></div>
    <?php endif; ?>
  </div>
</div>