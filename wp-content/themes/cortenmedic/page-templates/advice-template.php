<?php
/**
 * Template name: E-Poradnik
 * The template for displaying advice page template
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container default-page advice-main-page">

    <div class="page-layout">
      <?php if( have_rows('_page_layout') ) : ?>
        <?php while( have_rows('_page_layout') ) : the_row(); ?>

          <?php if( get_row_layout() == '_page_text_block' ) : ?>
            <?php _get_page_layout('text'); ?>
          <?php endif; ?>

          <?php if( get_row_layout() == '_page_widget_block' ) : ?>
            <?php _get_page_layout('widget'); ?>
          <?php endif; ?>

          <?php if( get_row_layout() == '_page_multi-icons_block' ) : ?>
            <?php _get_page_layout('multi-icons'); ?>
          <?php endif; ?>

          <?php if( get_row_layout() == '_page_files_block' ) : ?>
            <?php _get_page_layout('files'); ?>
          <?php endif; ?>

        <?php endwhile; ?>
      <?php endif; ?>
    </div>
      
  </div>
</main>

<?php get_footer(); ?>