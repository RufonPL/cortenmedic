<?php  
global $cortenCart;
$cart_content = $cortenCart->get_cart_content();
$product_types_in_cart = CORTEN_SHOP::cart_product_types();
?>
<div class="row checkout-order">
  <?php echo _section_header( pll_trans('Twoje zamówienie', true), true ); ?>

  <?php if( $cart_content ) : ?>
    <?php  
    $products = CORTEN_SHOP::cart_products_prices( $cart_content['products'], true );
    $summary  = CORTEN_SHOP::cart_summary( $cart_content['products'], true, $cart_content['shipment']['id'] );
    ?>
    <?php if( $products && $summary ) : ?>
    <div class="checkout-order-details row">

      <div class="row">
        <div class="col-sm-12">
          <table class="table table-striped checkout-products-table responsive-table">
            <thead>
              <tr>
                <th><?php pll_trans('Produkt'); ?></th>
                <th><?php pll_trans('Suma'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($products as $product) : ?>
              <?php $prefix = CORTEN_SHOP::package_prefix( $product['id'] ); ?>
              <tr>
                <td class="row" data-col-name="<?php pll_trans('Produkt'); ?>">
                  <div class="td-inner">
                    <a href="<?php echo esc_url( get_permalink( $product['id'] ) ); ?>">
                      <?php echo $prefix.esc_html( get_the_title( $product['id'] ) ); ?>
                    </a> 
                    x <?php echo absint( $product['qty'] ); ?>
                  </div>
                </td>
                <td class="row" data-col-name="<?php pll_trans('Suma'); ?>">
                  <div class="td-inner">
                    <?php echo esc_html( $product['sum'] ); ?> <?php pll_trans('zł'); ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6 col-sm-offset-6">
          <?php echo _section_header( pll_trans('Podsumowanie', true), true ); ?>

          <table class="table table-bordered checkout-summary-table">
            <tbody>
              <tr>
                <td>
                  <div class="td-inner">
                    <strong><?php pll_trans('Razem'); ?></strong>
                  </div>
                </td>
                <td>
                  <div class="td-inner">
                    <?php echo esc_html( $summary['total'] ); ?> <?php pll_trans('zł'); ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="td-inner">
                    <strong><?php pll_trans('Podatek VAT'); ?></strong>
                  </div>
                </td>
                <td>
                  <div class="td-inner">
                    <?php echo esc_html( $summary['tax'] ); ?> <?php pll_trans('zł'); ?>
                  </div>
                </td>
              </tr>
              <?php if( in_array( 'corten-product', $product_types_in_cart ) ) : ?>
              <tr>
                <td>
                  <div class="td-inner">
                    <strong><?php pll_trans('Wysyłka'); ?></strong>
                  </div>
                </td>
                <td>
                  <div class="td-inner">
                    <?php echo esc_html( get_the_title( $cart_content['shipment']['id'] ) ); ?>: <?php echo esc_html( $summary['shipment'] ); ?> <?php pll_trans('zł'); ?>
                    <?php if( $cart_content['shipment']['item'] ) : ?>
                      <br>
                      <?php echo esc_html( $cart_content['shipment']['item'] ); ?>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td>
                  <div class="td-inner">
                    <strong><?php pll_trans('Do zapłaty'); ?></strong>
                  </div>
                </td>
                <td>
                  <div class="td-inner">
                    <?php echo esc_html( $summary['to_pay'] ); ?> <?php pll_trans('zł'); ?>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
    <?php endif; ?>
  <?php endif; ?>
</div>