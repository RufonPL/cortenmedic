<?php
/**
 * Template name: Akordeony
 * The template for displaying accordions page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<?php  
$entry      = get_field('_accordion_page_entry');
$accordions = get_field('_accordion_page_accordions');
$widgets    = get_field('_accordion_page_widgets');
?>

<main>
  <div class="container-fluid page-container accordions-page">

    <?php if( !_empty_content( $entry ) ) : ?>
      <div class="container accordions-entry"><?php echo ( $entry ); ?></div>
    <?php endif; ?>

    <div class="accordions-page-accordions posts-index container">
      <div class="row">
        <div class="col-sm-9 posts-index-left">
          <div class="posts-list-container">
            <?php RFS_WIDGETS::loop_widgets($accordions); ?>
          </div>
        </div>
        <div class="col-sm-3 posts-index-right">
          <?php _get_template_part( 'accordions-sidebar', 'misc', array('page_id' => get_the_ID()) ); ?>
        </div>
      </div>
    </div>

    <div class="accordions-page-widgets">
    <?php RFS_WIDGETS::loop_widgets($widgets); ?>
    </div>
      
  </div>
</main>

<?php get_footer(); ?>