<?php
$cart             = $params['cart'];
$shipment_methods = CORTEN_SHOP::shipment_methods();
$shipment_default = CORTEN_SHOP::shipment_methods(true);
$summary          = CORTEN_SHOP::cart_summary( $cart['content'], true, $shipment_default );

$total  = $summary['total'];
$tax    = $summary['tax'];
$topay  = $summary['to_pay'];

$product_types_in_cart = CORTEN_SHOP::cart_product_types();
?>

<div class="cart-table">
  <?php echo CORTEN_SHOP::cart_loader(); ?>
  <table class="table table-striped responsive-table">
    <thead>
      <tr>
        <th></th>
        <th></th>
        <th><?php pll_trans('Produkt'); ?></th>
        <th><?php pll_trans('Cena'); ?></th>
        <th><?php pll_trans('Ilość'); ?></th>
        <th><?php pll_trans('Suma'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($cart['content'] as $product) : ?>
        <?php  
        $product_id       = $product['id'];
        $product_qty      = $product['qty'];
        $thumbnail        = get_field('_product_image', $product_id);
        $prices           = CORTEN_SHOP::product_prices( $product_id );
        $price_raw        = $prices['to_pay']['raw'];
        $price_formatted  = $prices['to_pay']['formatted'];
        $product_sum      = CORTEN_SHOP::format_price( $product_qty * $price_raw );
        $prefix           = CORTEN_SHOP::package_prefix( $product['id'] );
        ?>
      <tr data-product-id="<?php echo $product_id; ?>">
        <td class="row" data-col-name="<?php pll_trans('Usuń'); ?>">
          <div class="td-inner">
            <i class="fa fa-close remove-from-cart" title="<?php pll_trans('Usuń'); ?>" data-product="<?php echo $product_id; ?>"></i>
          </div>
        </td>
        <td class="row hide-on-mobile">
          <?php if($thumbnail) : ?>
            <div class="td-inner">
              <img class="cart-thumbnail" src="<?php echo esc_url($thumbnail['sizes']['product-image']); ?>" alt="<?php echo esc_attr($thumbnail['alt']); ?>">
            </div>
          <?php endif; ?>
        </td>
        <td class="row" data-col-name="<?php pll_trans('Produkt'); ?>">
          <div class="td-inner">
            <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><strong><?php echo $prefix.esc_html( get_the_title( $product_id ) ); ?></strong></a>
          </div>
        </td>
        <td class="row" data-col-name="<?php pll_trans('Cena'); ?>">
          <div class="td-inner">
            <?php echo $price_formatted ?> <?php pll_trans('zł'); ?>
          </div>
        </td>
        <td class="row" data-col-name="<?php pll_trans('Ilość'); ?>">
          <div class="td-inner">
            <input class="form-control cart-product-qty" type="number" name="product-qty--<?php echo $product_id; ?>" value="<?php echo $product_qty ?>" min="1" step="1">
          </div>
        </td>
        <td class="row" data-col-name="<?php pll_trans('Suma'); ?>">
          <div class="td-inner">
            <span class="cart-product-sum"><?php echo $product_sum ?></span> <?php pll_trans('zł'); ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="row text-right cart-update">
    <span class="btn btn-primary btn-medium cart-update-btn"><?php pll_trans('Aktualizuj koszyk'); ?></span>
  </div>
</div>

<div class="cart-summary row">
  <div class="col-sm-6 col-sm-offset-6 cart-summary-content">
    <?php echo CORTEN_SHOP::cart_loader(); ?>
    <?php echo _section_header( pll_trans('Podsumowanie', true), true ); ?>
    <table class="table table-bordered">
      <tbody>
        <tr>
          <td><?php pll_trans('Razem'); ?></td>
          <td><span class="cart-total"><?php echo $total; ?></span> <?php pll_trans('zł'); ?></td>
        </tr>
        <tr>
          <td><?php pll_trans('Podatek VAT'); ?></td>
          <td><span class="cart-tax"><?php echo $tax; ?></span> <?php pll_trans('zł'); ?></td>
        </tr>
        <?php if( $shipment_methods && in_array( 'corten-product', $product_types_in_cart ) ) : ?>
        <tr class="cart-shipping-row">
          <td><?php pll_trans('Wysyłka'); ?></td>
          <td>
            <div class="cart-shipment">
              <?php $i=1; foreach($shipment_methods as $shipment) : ?>
                <?php  
                $id               = $shipment['id'];
                $name             = $shipment['name'];
                $cost             = $shipment['cost'];
                $default          = $shipment['default'];
                $list_active      = $shipment['list_active'];
                $list_placeholder = $shipment['list_placeholder'];
                $list             = $shipment['list'];
                ?>
                <label class="shipment-method">
                  <input class="shipment-value<?php if( $list_active ) : ?> shipment-has-list<?php endif; ?><?php if( $default ) : ?> shipment-default<?php endif; ?>" type="radio" name="shipment_method" value="<?php echo $id; ?>"<?php if( $default ) : ?> checked<?php endif; ?>><span><?php echo esc_html( $name ); ?>: <strong><?php echo CORTEN_SHOP::format_price( $cost ); ?> <?php pll_trans('zł'); ?></strong></span>
                  <?php if( $list_active ) : ?>
                    <select name="shipment_method_item" id="shipment_method_item" class="form-control shipment-list">
                      <option value=""><?php echo esc_html( $list_placeholder ); ?></option>
                      <?php foreach($list as $item) : ?>
                      <option value="<?php echo esc_html( $item['_item'] ); ?>"><?php echo esc_html( $item['_item'] ); ?></option>
                      <?php endforeach; ?>
                    </select>
                  <?php endif; ?>
                </label>
              <?php $i++; endforeach; ?>
            </div>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td><?php pll_trans('Do zapłaty'); ?></td>
          <td><span class="cart-to-pay"><?php echo $topay; ?></span> <?php pll_trans('zł'); ?></td>
        </tr>
      </tbody>
    </table>
    <div class="text-right go-to-checkout">
      <a href="<?php echo esc_url( CORTEN_SHOP::shop_links('checkout') ); ?>" class="btn btn-primary btn-large"><?php pll_trans('Przejdź do kasy');  ?></a>
    </div>
  </div>
</div>

<?php _get_template_part( 'modal', 'shop' ); ?>