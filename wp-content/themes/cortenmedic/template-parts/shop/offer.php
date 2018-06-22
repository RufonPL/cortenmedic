<?php  
$type         = get_field('_shop_offer_type');
$display      = get_field('_shop_offer_display');
$custom       = get_field('_shop_offer_custom');
$price_prefix = get_field('_shop_offer_price_prefix_text');
$price_zl_pln = get_field('_shop_offer_price_zl_pln');
$price_affix  = absint( $price_zl_pln ) == 1 ? pll_trans('zł', true) : pll_trans('PLN', true);

$offer_data = array();
$meta_query = CORTEN_SHOP::products_available_init_query();
$tax_query  = array();
$exclude    = array();
$post_in    = array();
$orderby    = 'date';

if( $type == 'corten-product' || $type == 'corten-package' ) {
  $btn_text   = get_field('_shop_offer_btn_text');
  $btn_target = get_field('_shop_offer_btn_target');

  switch( $display ) {
    case 'random':
      $orderby = 'rand';
      break;
    case 'recommended':
      $meta_query[] = array(
        'key'     => '_product_recommended',
        'value'   => 1,
        'type'    => 'NUMERIC',
        'compare' => '='
      );
      break;
    case 'custom':
      $custom_products   = get_field('_shop_offer_custom_'.$type);

      $post_in = $custom_products;
      break;
  }
  $offers = _run_wp_query($type, 'shop_offer', 3, $meta_query, $tax_query, true, 1, $exclude, $orderby, 'DESC', '', $post_in );

  if( $offers->have_posts() ) {
    while( $offers->have_posts() ) { $offers->the_post();
      $image  = get_field('_product_image');
      $text   = get_field('_product_description');
      $prices = CORTEN_SHOP::product_prices( get_the_ID() );
      
      $price  = $prices['base']['raw'];

      if( $prices['sale']['raw'] > 0 ) {
        $price  = $prices['sale']['raw'];
      }
      
      $offer_data[] = array(
        'image'     => $image,
        'header'    => get_the_title(),
        'text'      => $text,
        'cut_text'  => true,
        'price'     => CORTEN_SHOP::format_price( $price ),
        'btn'       => array(
          'url'     => get_permalink(),
          'title'   => $btn_text ? esc_html( $btn_text ) : pll_trans('Kup teraz', true),
          'target'  => absint( $btn_target ) == 1 ? '_blank' : ''
        )
      );
    }
  }; wp_reset_postdata();
}

if( $type == 'custom' ) {
  if( $custom ) {
    foreach($custom as $item) {
      $image  = $item['_image'];
      $header = $item['_header'];
      $text   = $item['_text'];
      $price  = $item['_price'];
      $btn    = $item['_btn'];
      $button = _link_data( $btn, 'Kup teraz' );

      $offer_data[] = array(
        'image'     => $image,
        'header'    => $header,
        'text'      => $text,
        'cut_text'  => true,
        'price'     => $price,
        'btn'       => $button
      );
    }
  }
}

?>
<?php if( $type != 'off' && !empty( $offer_data ) ) : ?>
<div class="shop-offers">
  <div class="row">
    <?php foreach($offer_data as $offer) : ?>
      <?php  
      $text = $offer['text'];
      $text = wp_kses( $text, array( 'br' => array() ) );
      if( $offer['cut_text'] ) {
        $text = _excerpt( $text, 24, '', '<br>');
      }
      ?>
    <div class="col-sm-4 shop-offer">
      <div class="shop-offer-inner">
        <div class="shop-offer-image">
        <?php if( $offer['btn']['url'] ) : ?>
          <?php if($offer['image']) : ?>
          <a<?php if( $offer['btn']['target'] ) : ?> target="<?php echo $offer['btn']['target']; ?>"<?php endif; ?> href="<?php echo $offer['btn']['url']; ?>">
          <img src="<?php echo esc_url($offer['image']['sizes']['post-image']); ?>" alt="<?php echo esc_attr($offer['image']['alt']); ?>">
          </a>
          <?php endif; ?>
        <?php endif; ?>
        </div>
        <div class="shop-offer-info">
          <?php if( $offer['header'] ) : ?><h4><?php echo esc_html( $offer['header'] ); ?></h4><?php endif; ?>
          <?php if( !_empty_content( $text ) ) : ?>
            <div class="shop-offer-text"><?php echo $text; ?></div>
          <?php endif; ?>
          <p class="shop-offer-price">
            <?php if( $price_prefix ) : ?><?php echo esc_html( $price_prefix ); ?> <?php endif; ?>
            <strong><?php echo $offer['price']; ?> <span><?php echo $price_affix; ?></span></strong>
          </p>
          <?php if( $offer['btn']['url'] ) : ?>
            <a<?php if( $offer['btn']['target'] ) : ?> target="<?php echo $offer['btn']['target']; ?>"<?php endif; ?> href="<?php echo $offer['btn']['url']; ?>" class="btn btn-primary btn-medium"><?php echo $offer['btn']['title']; ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>