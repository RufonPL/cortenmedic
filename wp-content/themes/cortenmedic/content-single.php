<?php
/**
 * @author Rafał Puczel
 */
?>

<article>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-single-content">
			
          
		<div class="visible-xs search-xs"><?php get_search_form(); ?></div>
			<?php the_content(); ?>
		</div>
	</div>
</article>
