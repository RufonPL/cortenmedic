<?php
$header       = get_field('_widget_header', $widget_id);
$header_style = get_field('_widget_header_style', $widget_id);

$post_type    = get_field('_widget_products_type', $widget_id);

switch( $post_type ) {
  case 'corten-product':
    $type = 'products';
    break;
  case 'corten-package':
    $type = 'packages';
    break;
  default:
    $type = '';
}

$show             = get_field('_widget_products_show', $widget_id);
$custom           = get_field('_widget_products_custom', $widget_id);
$products_ids     = get_field('_widget_'.$type.'_'.$custom, $widget_id);

switch($show) {
  case 'latest':
    $orderby = 'date';
    break;
  case 'random':
    $orderby = 'rand';
    break;
  default:
    $orderby = '';
}

$custom_posts = $show == 'custom' ? $products_ids : false;

$posts = RFS_WIDGETS::get_widget_posts( $widget_id, $post_type, 3, $custom_posts, $orderby );
?>

<div class="container">
  <div class="wiget-item widget-products">
    <?php echo RFS_WIDGETS::widget_header_by_style($header, $header_style, false, true); ?>

    <?php if( $posts->have_posts()) : ?>
    <div class="row products-row">
      <?php while( $posts->have_posts()) : $posts->the_post(); ?>
      <?php  
      $image = get_field('_product_image');
      $price = CORTEN_SHOP::product_price_html( get_the_ID() );
      ?>
      <div class="col-sm-4 product text-center grid-type">
        <div class="row product-inner">
          <a href="<?php the_permalink(); ?>">
            <div class="product-image">
              <?php if($image) : ?>
              <img src="<?php echo esc_url($image['sizes']['product-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
              <?php endif; ?>
              <div class="product-image-tiny">
                <?php if($image) : ?>
                <img src="<?php echo esc_url($image['sizes']['product-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php endif; ?>
              </div>
            </div>
          </a>
            <div class="product-info">
              <h6><?php the_title(); ?></h6>
              <?php echo $price; ?>
              <div class="add-to-cart-container">
                <a href="#<?php the_ID(); ?>" class="btn btn-primary add-to-cart"><?php pll_trans('Do koszyka'); ?><?php echo CORTEN_SHOP::cart_loader(); ?></a>
              </div>
            </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <?php endif; wp_reset_postdata(); ?>

  </div>
</div>