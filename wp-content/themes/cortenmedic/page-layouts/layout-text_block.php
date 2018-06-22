<?php 
$text = get_sub_field('_text');
$header = get_sub_field('_header');
?>
<?php if($text) : ?>
<div class="page-block page-block-text">
  <div class="container">
    <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>
    <?php echo $text; ?>
  </div>
</div>
<?php endif; ?>