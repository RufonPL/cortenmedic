<?php  
$order_id       = $params['order_id'];
$is_order_mine  = CORTEN_PROFILE::is_order_mine($order_id);
?>

<?php if( $is_order_mine ) : ?>
  <?php 
  $order_data = CORTEN_PROFILE::order_single_data($order_id);

  $general = array(
    array(
      'label'   => pll_trans('Data zamówienia', true),
      'active'  => true,
      'html'    => date('d.m.Y',  $order_data['order_date'])
    ),
    array(
      'label'   => pll_trans('Sposób płatności', true),
      'active'  => true,
      'html'    => $order_data['payment_type'] == 'transfer' ? pll_trans('Przelew', true) : pll_trans('Płatność online', true)
    ),
    array(
      'label'   => pll_trans('Dostawa', true),
      'active'  => true,
      'html'    => $order_data['shipment_method'].'<br>'.$order_data['shipment_item']
    ),
    array(
      'label'   => pll_trans('Sposób wysyłki', true),
      'active'  => $order_data['shipment_different'] == 'yes',
      'html'    => esc_html( $order_data['shipment_method'] )
    ),
  );

  $customer_details = array(
    array(
      'label'   => pll_trans('Imię i nazwisko', true),
      'active'  => true,
      'html'    => esc_html( $order_data['first_name'].' '.$order_data['last_name'] )
    ),
    array(
      'label'   => pll_trans('Nazwa firmy', true),
      'active'  => $customer_type == 'company',
      'html'    => esc_html( $order_data['company_name'] )
    ),
    array(
      'label'   => pll_trans('NIP firmy', true),
      'active'  => $customer_type == 'company',
      'html'    => esc_html( $order_data['company_nip'] )
    ),
    array(
      'label'   => pll_trans('Adres', true),
      'active'  => true,
      'html'    => __('ul.', 'rfswp').' '.esc_html( $order_data['address_street'] ).' '.esc_html( $order_data['address_number'] ).'<br>'.esc_html( $order_data['address_postcode'] ).' '.esc_html( $order_data['address_city'] ).'<br>'.esc_html( $order_data['address_country'] )
    ),
    array(
      'label'   => pll_trans('Adres email', true),
      'active'  => true,
      'html'    => antispambot( $order_data['email'] )
    ),
    array(
      'label'   => pll_trans('Numer telefonu', true),
      'active'  => true,
      'html'    => esc_html( $order_data['phone'] )
    ),
  );

  $shipment_details = array(
    array(
      'label'   => pll_trans('Adres', true),
      'active'  => true,
      'html'    => __('ul.', 'rfswp').' '.esc_html( $order_data['shipment_street'] ).' '.esc_html( $order_data['shipment_number'] ).'<br>'.esc_html( $order_data['shipment_postcode'] ).' '.esc_html( $order_data['shipment_city'] ).'<br>'.esc_html( $order_data['shipment_country'] )
    ),
  );

  $product_types_in_order = CORTEN_SHOP::cart_product_types($order_data['products']);
  ?>
  <div class="row customer-order-details">

    <h4><?php echo _section_header( pll_trans('Zamówienie nr', true).' #'.esc_html( $order_data['order_number'] ).' - '.CORTEN_SHOP::order_status( $order_data['order_status'] ), true, true ); ?></h4>

    <?php if( in_array( 'corten-package', $product_types_in_order ) ) : ?>
      <hr>
      <h4><?php pll_trans('Kod aktywacyjny pakietu'); ?> - <?php echo $order_data['order_code']; ?></h4>
      <hr>
    <?php endif; ?>

    <div class="row customer-order-details-general">
      <div class="col-sm-4">
        <h5><?php pll_trans('Ogólne informacje'); ?></h5>
        <?php if( $general ) : ?>
          <?php foreach($general as $item) : ?>
            <?php if( $item['active'] ) : ?>
            <p>
              <span><?php echo $item['label']; ?></span><br>
              <strong><?php echo $item['html']; ?></strong>
            </p>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="col-sm-4">
        <h5><?php pll_trans('Szczegóły klienta'); ?></h5>
        <?php if( $customer_details ) : ?>
          <?php foreach($customer_details as $item) : ?>
            <?php if( $item['active'] ) : ?>
            <p>
              <span><?php echo $item['label']; ?></span><br>
              <strong><?php echo $item['html']; ?></strong>
            </p>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="col-sm-4">
        <?php if( in_array( 'corten-product', $product_types_in_order ) ) : ?>
          <h5><?php pll_trans('Szczegóły wysyłki'); ?></h5>
          <?php if( $shipment_details && $order_data['shipment_different'] == 'yes' ) : ?>
            <?php foreach($shipment_details as $item) : ?>
              <?php if( $item['active'] ) : ?>
              <p>
                <span><?php echo $item['label']; ?></span><br>
                <strong><?php echo $item['html']; ?></strong>
              </p>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php else : ?>
          <p><span><?php pll_trans('Wysyłka na adres klienta'); ?></span></p>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="row customer-order-details-products">
      <h4><?php echo _section_header( pll_trans('Zamówienie', true), true ); ?></h4>
      <table class="table table-striped responsive-table">
        <thead>
          <tr>
            <th><?php pll_trans('Produkt'); ?></th>
            <th><?php pll_trans('Ilość'); ?></th>
            <th><?php pll_trans('Koszt'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order_data['products'] as $product) : ?>
          <?php $prefix = CORTEN_SHOP::package_prefix( $product['id'] ); ?>
          <tr>
            <td class="row" data-col-name="<?php pll_trans('Produkt'); ?>">
              <div class="td-inner">
                <?php echo esc_html( $prefix.$product['name'] ); ?>
              </div>
            </td>
            <td class="row" data-col-name="<?php pll_trans('Ilość'); ?>">
              <div class="td-inner">
                <?php echo absint( $product['qty'] ); ?>
              </div>
            </td>
            <td class="row" data-col-name="<?php pll_trans('Koszt'); ?>">
              <div class="td-inner">
                <?php echo CORTEN_SHOP::format_price( $product['sum_price'] ); ?> <?php pll_trans('zł'); ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

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
                    <?php echo CORTEN_SHOP::format_price( $order_data['totals']['total'] ); ?> <?php pll_trans('zł'); ?>
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
                    <?php echo CORTEN_SHOP::format_price( $order_data['totals']['tax'] ); ?> <?php pll_trans('zł'); ?>
                  </div>
                </td>
              </tr>
              <?php if( in_array( 'corten-product', $product_types_in_order ) ) : ?>
              <tr>
                <td>
                  <div class="td-inner">
                    <strong><?php pll_trans('Wysyłka'); ?></strong>
                  </div>
                </td>
                <td>
                  <div class="td-inner">
                    <?php echo esc_html( $order_data['shipment_method'] ); ?>: <?php echo CORTEN_SHOP::format_price( $order_data['totals']['shipment'] ); ?> <?php pll_trans('zł'); ?>
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
                    <?php echo CORTEN_SHOP::format_price( $order_data['totals']['to_pay'] ); ?> <?php pll_trans('zł'); ?>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
    </div>
  </div>

<?php else : ?>
  <h5><?php pll_trans('Nieprawidłowe id zamówienia. Dostęp zablokowany.'); ?></h5>
<?php endif; ?>