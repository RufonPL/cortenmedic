<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @author Rafał Puczel
 */
?>


<article>
	<div id="post-<?php the_ID(); ?>" <?php post_class('no-results'); ?>>
		<header>
      <h1 class="h3"><?php pll_trans( 'Nic nie znaleziono' ); ?></h1>
    </header>
	</div>
</article>
