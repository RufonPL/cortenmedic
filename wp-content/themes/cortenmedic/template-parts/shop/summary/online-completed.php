<?php
$text         = get_field('_tpay_thank_you_text', 'option');
$order_exists = CORTEN_SHOP::order_exists();
?>

<div class="thank-you-content">
  <?php if( $order_exists ) : ?>
    <?php if( !_empty_content( $text) ) : ?>
      <?php echo $text; ?>
    <?php endif; ?>
  <?php else : ?>
    <h4><?php pll_trans('Nieprawidłowy token'); ?></h4>
  <?php endif; ?>
</div>