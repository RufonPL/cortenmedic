<?php
global $wpdb;
$products_count   = $params['products_count'];
$current_category = get_queried_object();

$meta_query = CORTEN_SHOP::products_available_init_query();
$tax_query  = array();
$clear      = true;
$transient  = 'list';
$exclude    = array();
$search     = '';

$page = get_query_var('paged') > 1 ? get_query_var('paged') : 1;

$limit = CORTEN_SHOP::shop_ppp( 'products' );

$sql = "SELECT {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts ";
$join = " 
LEFT JOIN {$wpdb->prefix}postmeta as pm1 ON {$wpdb->prefix}posts.ID = pm1.post_id AND pm1.meta_key = '_product_available' 
LEFT JOIN {$wpdb->prefix}postmeta as pm2 ON {$wpdb->prefix}posts.ID = pm2.post_id AND pm2.meta_key = '_product_stock' 
";
$where = " 
WHERE {$wpdb->prefix}posts.post_type = 'corten-product' 
AND {$wpdb->prefix}posts.post_status = 'publish' 
AND ( pm1.meta_key = '_product_available' AND pm1.meta_value = 1 ) 
AND ( pm2.meta_key = '_product_stock' AND pm2.meta_value > 0 ) 
";


if( is_tax('corten-product-category') ) {
  $tax_query[] = array(
    'taxonomy'  => $current_category->taxonomy,
    'field'     => 'id',
    'terms'     => array( $current_category->term_id ),
    'operator'  => 'IN'
  );
}

if( absint( get_search_form_param('pprice_min') ) >= 0 ) {
  $price_min = absint( get_search_form_param('pprice_min') );

  $join .= " 
  LEFT JOIN {$wpdb->prefix}postmeta as pm3 ON {$wpdb->prefix}posts.ID = pm3.post_id AND pm3.meta_key = '_product_price' 
  LEFT JOIN {$wpdb->prefix}postmeta as pm4 ON {$wpdb->prefix}posts.ID = pm4.post_id AND pm4.meta_key = '_product_sale_price' 
  LEFT JOIN {$wpdb->prefix}postmeta as pm5 ON {$wpdb->prefix}posts.ID = pm5.post_id AND pm5.meta_key = '_product_sale_price' 
  ";
  $where .= " 
  AND ( 
    pm3.meta_key = '_product_price' AND pm3.meta_value >= {$price_min} 
    OR 
    ( 
      pm4.meta_key = '_product_sale_price' AND pm4.meta_value > 0 
      AND 
      pm5.meta_key = '_product_sale_price' AND pm5.meta_value >= {$price_min} 
    ) 
  ) 
  ";
}

if( absint( get_search_form_param('pprice_max') ) > 0 ) {
  $price_max = absint( get_search_form_param('pprice_max') );

  $join .= " 
  LEFT JOIN {$wpdb->prefix}postmeta as pm6 ON {$wpdb->prefix}posts.ID = pm6.post_id AND pm6.meta_key = '_product_price' 
  LEFT JOIN {$wpdb->prefix}postmeta as pm7 ON {$wpdb->prefix}posts.ID = pm7.post_id AND pm7.meta_key = '_product_sale_price' 
  LEFT JOIN {$wpdb->prefix}postmeta as pm8 ON {$wpdb->prefix}posts.ID = pm8.post_id AND pm8.meta_key = '_product_sale_price' 
  ";
  $where .= " 
  AND ( 
    pm6.meta_key = '_product_price' AND pm6.meta_value <= {$price_max} 
    OR 
    ( 
      pm7.meta_key = '_product_sale_price' AND pm7.meta_value > 0 
      AND 
      pm8.meta_key = '_product_sale_price' AND pm8.meta_value <= {$price_max} 
    ) 
  ) 
  ";

}

if( get_search_form_param('psearch') ) {
  $search = get_search_form_param('psearch');
}

$sql .= $join;
$sql .= $where;
$sql .= " GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date ";

$post__in = array();

if( absint( get_search_form_param('pprice_min') ) >= 0 || absint( get_search_form_param('pprice_max') ) > 0 ) {
  $price_filter_products = $wpdb->get_results($sql);

  if( $price_filter_products ) {
    $post__in = wp_list_pluck( $price_filter_products, 'ID' );
  }
}

$products = _run_wp_query('corten-product', $transient, $limit, $meta_query, $tax_query, $clear, $page, $exclude, 'date', 'DESC', $search, $post__in);

$search_clear = esc_url(CORTEN_SHOP::clear_products_filter( 'search' ));
$price_clear  = esc_url(CORTEN_SHOP::clear_products_filter( 'price' ));
?>
<?php if( $products->have_posts() ) : ?>
<?php $count = $products->post_count; ?>
<div class="products-container">
  <div class="row">
    <div class="col-sm-8">
      <div class="row">
        <div class="col-sm-6">
          <?php if( !is_tax('corten-product-category') ) : ?>
            <?php echo _section_header( pll_trans('Wszystkie', true).' ('.$count.')', true ); ?>
          <?php else : ?>
            <?php echo _section_header( $current_category->name.' ('.absint( $count ).')', true ); ?>
          <?php endif; ?>
        </div>  
        <div class="col-sm-6 product-filters">
          <?php if( get_search_form_param('pprice_min') || get_search_form_param('pprice_max') ) : ?>
            <div class="badge"><?php pll_trans('Cena'); ?>
            <?php if( get_search_form_param('pprice_min') ) : ?>
              <?php pll_trans('od'); ?> <?php echo $price_min; ?> <?php pll_trans('zł'); ?>
            <?php endif; ?>
            <?php if( get_search_form_param('pprice_max') ) : ?>
              <?php pll_trans('do'); ?> <?php echo $price_max; ?> <?php pll_trans('zł'); ?>
            <?php endif; ?>
            <a href="<?php echo $price_clear; ?>" class="fa fa-close"></a></div>
          <?php endif; ?>
          <?php if( get_search_form_param('psearch') ) : ?>
            <div class="badge"><?php echo esc_html( get_search_form_param('psearch') ); ?>
              <a href="<?php echo $search_clear; ?>" class="fa fa-close"></a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-sm-4 text-right products-display">
      <i class="fa fa-list"></i>
      <i class="fa fa-th display-active"></i>
    </div>
  </div>
  <div class="row products-row">
    <?php $i=1; while($products->have_posts()) : $products->the_post(); ?>
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

    <?php if( $i%3 == 0 && $i != $count ) : ?>
    </div>
    <div class="row products-row">
    <?php endif; ?>
    
    <?php $i++; endwhile; ?>
    <?php echo _pagination( '', 2, $products, $page); ?>
  </div>
</div>
<?php endif; wp_reset_postdata(); ?>

<?php _get_template_part( 'modal', 'shop' ); ?>