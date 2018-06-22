<?php
$text = get_field('_transfer_thank_you_text', 'option');
?>

<?php if( !_empty_content( $text) ) : ?>
<div class="thank-you-content">
  <?php if( CORTEN_SHOP::order_exists() ) : ?>
    <?php echo $text; ?>
  <?php else : ?>
    <h4><?php pll_trans('Nieprawidłowy token'); ?></h4>
  <?php endif; ?>
</div>
<?php endif; ?>