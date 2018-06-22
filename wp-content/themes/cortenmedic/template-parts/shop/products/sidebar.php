<?php
$products_count = $params['products_count'];

$categories = get_terms( array(
  'taxonomy'  => 'corten-product-category',
  'parent'    => 0,
  'number'    => 0
) );
 
$current_category = get_queried_object();

$meta_query = CORTEN_SHOP::products_available_init_query();
$meta_query[] = array(
  'key'     => '_product_recommended',
  'value'   => 1,
  'type'    => 'NUMERIC',
  'compare' => '='
);

$recommendedd = _run_wp_query(array('corten-product', 'corten-package'), 'recommended', CORTEN_SHOP::shop_ppp( 'products_recommended' ), $meta_query, array(), true);
$packages_count = wp_count_posts('corten-package')->publish;
?>

<?php if( $categories ) : ?>
<div class="product-categories-container">
  <?php echo _section_header( pll_trans('Kategorie', true), true ); ?>

  <ul class="product-categories list-unstyled">
    <li><a<?php if( !is_tax('corten-product-category') && !is_singular( 'corten-product' ) ) : ?> class="current"<?php endif; ?> href="<?php echo esc_url( get_permalink( _page_template_id( 'products' ) ) ); ?>"><?php pll_trans('Wszystkie'); ?> (<?php echo $products_count + $packages_count; ?>)</a></li>
    <?php foreach($categories as $category) : ?>
      <?php  
      $parent_active = is_tax('corten-product-category') && ($current_category->term_id == $category->term_id || $current_category->parent == $category->term_id) ? $category->term_id : false;
      $subcategories = get_terms( array(
        'taxonomy'  => $category->taxonomy,
        'parent'    => $category->term_id,
        'number'    => 0
      ) );
      ?>
      <li<?php if( $parent_active == $category->term_id ) : ?> class="active"<?php endif; ?>>
        <a<?php if( $parent_active == $category->term_id ) : ?> class="current"<?php endif; ?> href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?> (<?php echo absint( $category->count ); ?>)</a>
        <?php if( $subcategories ) : ?>
        <ul class="product-subcategories list-unstyled">
          <?php foreach($subcategories as $subcategory) : ?>
            <?php  
            $active_subcategory = is_tax('corten-product-category') && $current_category->term_id == $subcategory->term_id ? $subcategory->term_id : false;
            ?>
            <li>
              <a<?php if( $active_subcategory == $subcategory->term_id ) : ?> class="current"<?php endif; ?> href="<?php echo esc_url( get_term_link( $subcategory->term_id ) ); ?>"><?php echo esc_html( $subcategory->name ); ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
    
      <li>
        <a href="<?php echo CORTEN_SHOP::shop_links('packages'); ?>"><?php pll_trans('Pakiety'); ?> (<?php echo $packages_count; ?>)</a>
      </li>
  </ul>
</div>

<div class="products-price-filter">
  <?php echo _section_header( pll_trans('Cena', true), true ); ?>
  <form id="products-price-filter-form" action="" method="get" class="form-horizontal">
    <div class="form-group row">
      <label for="pprice_min" class="control-label col-sm-1 col-xs-2">Od</label>
      <div class="col-sm-5 col-xs-4">
        <input name="pprice_min" type="number" min="0" class="form-control" value="<?php echo get_search_form_param('pprice_min'); ?>">
      </div>
      <label for="pprice_max" class="control-label col-sm-1 col-xs-2">Do</label>
      <div class="col-sm-5 col-xs-4">
        <input name="pprice_max" type="number" min="0" class="form-control" value="<?php echo get_search_form_param('pprice_max'); ?>">
      </div>
    </div>
    <input type="hidden" name="psearch" value="<?php echo get_search_form_param('psearch'); ?>">
  </form>
</div>
<?php endif; ?>

<?php if( $recommendedd->have_posts() ) : ?>
<div class="products-recommended">
  <?php echo _section_header( pll_trans('Rekomendowane produkty', true), true ); ?>
  <ul class="list-unstyled">
    <?php while($recommendedd->have_posts()) : $recommendedd->the_post(); ?>
      <?php
      $image = get_field('_product_image');
      $price = CORTEN_SHOP::product_price_html( get_the_ID() );
      ?>
      <li class="row product-recommended">
        <a href="<?php the_permalink(); ?>">
          <?php if($image) : ?>
          <img class="pull-left" src="<?php echo esc_url($image['sizes']['product-image']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
          <?php endif; ?>
          <div class="product-recommended-info">
            <h6><?php the_title(); ?></h6>
            <?php echo $price; ?>
          </div>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
<?php endif; wp_reset_postdata(); ?>