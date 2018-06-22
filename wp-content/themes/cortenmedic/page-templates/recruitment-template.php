<?php
/**
 * Template name: Kariera - Rekrutacja
 * The template for displaying recruitment page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container recruitment-page">
    <div class="container">

      <div class="page-layout">
        <?php if( have_rows('_page_layout') ) : ?>
          <?php while( have_rows('_page_layout') ) : the_row(); ?>

            <?php if( get_row_layout() == '_page_text_block' ) : ?>
              <?php _get_page_layout('text'); ?>
            <?php endif; ?>

            <?php if( get_row_layout() == '_page_widget_block' ) : ?>
              <?php _get_page_layout('widget'); ?>
            <?php endif; ?>

            <?php if( get_row_layout() == '_page_icons_block' ) : ?>
              <?php _get_page_layout('icons'); ?>
            <?php endif; ?>

            <?php if( get_row_layout() == '_page_steps_block' ) : ?>
              <?php _get_page_layout('steps'); ?>
            <?php endif; ?>

          <?php endwhile; ?>
        <?php endif; ?>
      </div>
      
    </div>
  </div>
</main>

<?php get_footer(); ?>