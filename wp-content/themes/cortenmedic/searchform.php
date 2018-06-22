<?php
/**
 * The template for displaying search forms in upBootWP
 *
 * @author Rafał Puczel
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>">
	<div class="search-inner form-group">
		<input type="hidden" name="filter" value="1">
		<input type="search" class="search-field form-control" placeholder="<?php pll_trans( 'Szukaj...' ); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="ps"/>
		<button type="submit" id="search-submit"><i class="fa fa-search"></i></button>
	</div>
</form>
