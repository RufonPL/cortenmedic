<?php
/**
 * The Template for displaying all single products.
 *
 * @author Rafał Puczel
 */
get_header(); ?>

<?php
$products_count = absint( CORTEN_SHOP::products_count() );
$product_id     = get_the_ID();
$gallery        = get_field('_product_gallery');
$manufacturers  = wp_get_post_terms( $product_id, 'corten-product-manufacturer' );
$price          = CORTEN_SHOP::product_price_html( $product_id, 'Cena:' );
$description    = get_field('_product_description');
?>

<main>
  <div class="container-fluid page-container product-single">

    <div class="container">

      <?php _get_template_part( 'search', 'shop/products' ); ?>

      <div class="row">
        <div class="col-sm-3 products-sidebar">
          <?php _get_template_part( 'sidebar', 'shop/products', array('products_count' => $products_count) ); ?>
        </div>
        <div class="col-sm-9">
          
          <div class="row single-product">
            <?php if( $gallery ) : ?>
            <div class="col-sm-4 product-gallery">

              <div class="product-gallery-main">
                <div class="owl-carousel" id="product-gallery-main">
                  <?php foreach($gallery as $image) : ?>
                    <a data-imagelightbox="lightbox" href="<?php echo esc_url( $image['sizes']['slider-image'] ); ?>">
                      <img src="<?php echo esc_url( $image['sizes']['product-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" data-caption="<?php echo esc_html( $image['description'] ); ?>">
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
              
              <div class="product-gallery-thumbnails owl-carousel" id="product-gallery-thumbnails">
                <?php $i=0; foreach($gallery as $image) : ?>
                  <div class="product-gallery-thumbnail" data-gallery-nav-item="<?php echo $i; ?>">
                    <img src="<?php echo esc_url( $image['sizes']['product-image'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
                  </div>
                <?php $i++; endforeach; ?>
              </div>
              
            </div>
            <?php endif; ?>
            <div class="col-sm-8">
              <div class="single-product-content">
                <h2><strong><?php the_title(); ?></strong></h2>
                <?php if( $manufacturers ) : ?>
                <p class="product-manufacturer"><?php pll_trans('Producent:'); ?>
                  <?php $i=1; foreach($manufacturers as $manufacturer) : ?>
                    <span><?php echo esc_html( $manufacturer->name ); ?></span><?php if( $i!= count($manufacturers) ) : ?>,<?php endif; ?>
                  <?php $i++; endforeach; ?>
                </p>
                <?php endif; ?>

                <?php if( $price ) : ?><?php echo $price; ?><?php endif; ?>

                <div class="single-product-add-to-cart">
                  <div class="form-group">
                    <label for="product-qty"><?php pll_trans('Ilość:'); ?></label>
                    <input type="number" class="form-control" name="product-qty" value="1" min="1" step="1">
                  </div>
                  <a href="#<?php the_ID(); ?>" class="btn btn-primary btn-wide add-to-cart"><?php pll_trans('Do koszyka'); ?><?php echo CORTEN_SHOP::cart_loader(); ?></a>
                </div>

                <?php if( !_empty_content( $description ) ) : ?>
                <?php echo _section_header( pll_trans('Opis', true), true ); ?>
                <div class="product-description"><?php echo $description ?></div>
                <?php endif; ?>
                <?php if( function_exists('DISPLAY_ULTIMATE_PLUS') ) : ?>
                <div style="padding-top:25px"><?php echo DISPLAY_ULTIMATE_PLUS(); ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      
    </div>

  </div>
</main>

<?php _get_template_part( 'modal', 'shop' ); ?>

<?php get_footer(); ?>
