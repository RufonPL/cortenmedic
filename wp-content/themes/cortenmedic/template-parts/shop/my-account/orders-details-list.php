<?php $orders_list = CORTEN_PROFILE::customer_orders(); ?>
<table class="table table-striped responsive-table">
  <thead>
    <tr>
      <th><?php pll_trans('Data zamówienia'); ?></th>
      <th><?php pll_trans('Numer zamówienia'); ?></th>
      <th><?php pll_trans('Wartość zamówienia'); ?></th>
      <th><?php pll_trans('Status zamówienia'); ?></th>
      <th><?php pll_trans('Akcje'); ?></th>
      <th><?php pll_trans('Faktura'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($orders_list as $order) : ?>
    <tr>
      <td class="row" data-col-name="<?php pll_trans('Data zamówienia'); ?>">
        <div class="td-inner">
          <?php echo date( 'd.m.Y', $order['order_date'] ); ?>
        </div>
      </td>
      <td class="row" data-col-name="<?php pll_trans('Numer zamówienia'); ?>">
        <div class="td-inner">
          <?php echo esc_html( $order['order_number'] ); ?>
        </div>
      </td>
      <td class="row" data-col-name="<?php pll_trans('Wartość zamówienia'); ?>">
        <div class="td-inner">
          <?php echo CORTEN_SHOP::format_price( $order['totals']['to_pay'] ); ?> <?php pll_trans('zł'); ?>
        </div>
      </td>
      <td class="row" data-col-name="<?php pll_trans('Status zamówienia'); ?>">
        <div class="td-inner">
          <?php echo CORTEN_SHOP::order_status( $order['order_status'] ); ?>
        </div>
      </td>
      <td class="row" data-col-name="<?php pll_trans('Akcje'); ?>">
        <div class="td-inner">
          <a href="<?php echo esc_url( CORTEN_PROFILE::order_details_link($order['order_id']) ); ?>" class="btn btn-primary"><?php pll_trans('Szczegóły'); ?></a>
        </div>
      </td>
      <td class="row" data-col-name="<?php pll_trans('Faktura'); ?>">
        <div class="td-inner">
          
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>