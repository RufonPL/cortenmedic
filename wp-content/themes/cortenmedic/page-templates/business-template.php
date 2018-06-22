<?php
/**
 * Template name: Biznes - Strona Główna
 * The template for displaying business page.
 *
 * @author Rafał Puczel
 */

get_header(); ?>

<main>
  <div class="container-fluid page-container">
    
    <div class="page-layout">
			<?php if( have_rows('_page_layout') ) : ?>
				<?php $i=1; while( have_rows('_page_layout') ) : the_row(); ?>

					<?php if( get_row_layout() == '_page_text_block' ) : ?>
						<?php _get_page_layout('text'); ?>
					<?php endif; ?>

					<?php if( get_row_layout() == '_page_widget_block' ) : ?>
						<?php _get_page_layout('widget', $i); ?>
					<?php endif; ?>

          <?php if( get_row_layout() == '_page_multi-icons_block' ) : ?>
            <?php _get_page_layout('multi-icons'); ?>
          <?php endif; ?>

          <?php if( get_row_layout() == '_page_files_block' ) : ?>
            <?php _get_page_layout('files'); ?>
          <?php endif; ?>

					<?php if( get_row_layout() == '_page_institutions_map_block' ) : ?>
						<?php _get_page_layout('institutions-map'); ?>
					<?php endif; ?>

					<?php if( get_row_layout() == '_page_prize_block' ) : ?>
						<?php _get_page_layout('prize'); ?>
					<?php endif; ?>

				<?php $i++; endwhile; ?>
			<?php endif; ?>
		</div>

  </div>
</main>

<?php get_footer(); ?>