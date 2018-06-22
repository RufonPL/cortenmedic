<?php
/**
 * Template name: Pakiety
 * The template for displaying shop packages page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php
$page   = get_query_var('paged') > 1 ? get_query_var('paged') : 1;
$limit  = CORTEN_SHOP::shop_ppp( 'packages' );

$packages   = _run_wp_query('corten-package', 'list', $limit, array(), array(), true, $page);
$pagination = _pagination( '', 2, $packages, $page);
?>

<main>
  <div class="container-fluid page-container packages-page">
    <div class="container">

      <?php echo _section_header( get_the_title() ); ?>

      <?php if( $packages->have_posts() ) : ?>
      <div class="row packages-list">
        <?php while( $packages->have_posts() ) : $packages->the_post(); ?>
          <?php _get_template_part( 'package', 'shop/products', array('package_id' => get_the_ID()) ); ?>
        <?php endwhile; ?>
        <?php echo $pagination; ?>
      </div>
      <?php endif; wp_reset_postdata(); ?>
      
    </div>
  </div>
</main>

<?php get_footer(); ?>