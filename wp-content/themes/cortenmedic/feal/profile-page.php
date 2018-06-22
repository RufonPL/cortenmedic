<?php 
$tabs = array(
	array(
		'label' => __('Account details', 'rfswp'),
		'link'	=> 'profile-main',
		'slug'	=> 'szczegoly-konta'
	),
	array(
		'label' => __('Personal details', 'rfswp'),
		'link'	=> 'profile-personal',
		'slug'	=> 'dane-osobowe'
	),
	array(
		'label' => __('Orders', 'rfswp'),
		'link'	=> 'profile-orders',
		'slug'	=> 'moje-zamowienia'
	),
);
?>
<div id="rfs-feal" class="rfs-feal-container">

	<div class="rfs-feal-pages rfs-feal-pages-full-width container">

		<div class="rfs-feal-page rfs-feal-profile active" id="profile">
	
			<h2 class="text-center"><?php _e('My account', 'rfswp'); ?></h2>

			<ul class="my-acount-nav">
				<?php foreach($tabs as $tab) : ?>
				<li<?php if( CORTEN_PROFILE::is_tab( $tab['slug'] ) ) : ?> class="active"<?php endif; ?>><a href="<?php echo CORTEN_SHOP::shop_links($tab['link']); ?>"><?php echo $tab['label']; ?></a></li>
				<?php endforeach; ?>
			</ul>

			<?php if( CORTEN_PROFILE::is_tab( 'moje-zamowienia' ) ) : ?>
				<?php _get_template_part( 'orders-details', 'shop/my-account' ); ?>
			<?php elseif( CORTEN_PROFILE::is_tab( 'dane-osobowe' ) ) : ?>
				<?php _get_template_part( 'personal-details', 'shop/my-account' ); ?>
			<?php else : ?>
				<?php _get_template_part( 'account-details', 'shop/my-account' ); ?>
			<?php endif; ?>
		
		</div>
		
	</div>

</div>

<?php _get_template_part( 'modal', 'shop' ); ?>