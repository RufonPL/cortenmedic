<?php  
$header = get_field('_vs_form_header');
$form   = get_field('_vs_form');
?>

<?php if( $form ) : ?>
<div class="vs-form">
  <?php if( $header ) : ?><h2><strong><?php echo esc_html( $header ); ?></strong></h2><?php endif; ?>
  <?php echo do_shortcode( $form ); ?>
</div>
<?php endif; ?>