<?php  
$post_id        = $params['post_id'];
$status         = CORTEN_SHOP::order_data_item( $post_id, 'status' );
$transaction_id = CORTEN_SHOP::order_data_item( $post_id, 'transaction_id' );
$paid_amount    = CORTEN_SHOP::order_data_item( $post_id, 'paid_amount' );
$paid_status    = CORTEN_SHOP::order_data_item( $post_id, 'paid_status' );
$totals         = CORTEN_SHOP::order_data_item( $post_id, 'totals' );
$payment_method = CORTEN_SHOP::order_data_item( $post_id, 'customer_payment_type');

$paid_amount    = $paid_amount ? $paid_amount : 0;
$paid_status    = $paid_status ? $paid_status : 'none';

$color          = $paid_status == 'less' || $paid_status == 'more' ? '#c00' : '#555';

$paid_amount    = !$transaction_id && $status == 3 ? $totals['to_pay'] : $paid_amount;
?>

<div class="metabox-inner">
<table class="wp-list-table widefat fixed striped posts">
      <tbody>
        <tr>
          <td>
            <div class="td-inner">
              <strong><?php _e('To pay', 'rfswp'); ?></strong>
            </div>
          </td>
          <td>
            <div class="td-inner">
              <?php echo CORTEN_SHOP::format_price( $totals['to_pay'] ); ?> <?php _e('PLN', 'rfswp'); ?>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="td-inner">
              <strong style="color:<?php echo $color; ?>"><?php _e('Paid', 'rfswp'); ?></strong>
            </div>
          </td>
          <td>
            <div class="td-inner" style="color:<?php echo $color; ?>">
              <?php echo CORTEN_SHOP::format_price( $paid_amount ); ?> <?php _e('PLN', 'rfswp'); ?>
            </div>
          </td>
        </tr>
        <?php if( $payment_method == 'online' ) : ?>
        <tr>
          <td>
            <div class="td-inner">
              <strong><?php _e('Transaction ID', 'rfswp'); ?></strong>
            </div>
          </td>
          <td>
            <div class="td-inner">
              <?php echo $transaction_id; ?>
            </div>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
</div>