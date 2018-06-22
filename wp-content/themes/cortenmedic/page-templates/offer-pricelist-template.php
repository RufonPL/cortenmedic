<?php
/**
 * Template name: Cennik usług
 * The template for displaying pricelist page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php  
$header = get_field('_pricelist_header');
$data   = _get_pricelist_data();
?>

<main>
  <div class="container-fluid page-container pricelist-page">
    <div class="container">
      <?php if($header) : ?><?php echo _section_header( $header ); ?><?php endif; ?>

      <?php _get_template_part( 'pricelist-city', 'offer' ); ?>

      <?php //_get_template_part( 'pricelist-filter', 'offer', array( 'data' => $data ) ); ?>
      
      <?php _get_template_part( 'pricelist-table', 'offer', array( 'data' => $data ) ); ?>

    </div>
  </div>
</main>

<?php get_footer(); ?>