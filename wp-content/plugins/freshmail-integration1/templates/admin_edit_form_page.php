<script>
	jQuery(document).ready(function($){
		$('.freshmail-content').hide();
		$('#freshmail-properties').show();
		$('.nav-tab').first().addClass('nav-tab-active');
		$('.nav-tab').on('click', function(){
			var tab = $(this).attr('for');
			$('.freshmail-content').hide();
			$('#' + tab).show();
			$('.nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
		});
	});
</script>

<style>
	a.nav-tab {
		cursor: pointer;
	}
</style>

<div class="wrap freshmail">
	<h1><?php _e('Freshmail', 'wp_freshmail'); ?></h1>

	<div id="icon-themes" class="icon32"><br></div>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab" for="freshmail-properties"><?php _e('Properties', 'wp_freshmail'); ?></a>
		<a class="nav-tab" for="freshmail-appearance"><?php _e('Appearance', 'wp_freshmail'); ?></a>
		<a class="nav-tab" for="freshmail-messages"><?php _e('Messages', 'wp_freshmail'); ?></a>
		<a class="nav-tab" for="freshmail-fields"><?php _e('List &amp; Fields', 'wp_freshmail'); ?></a>
	</h2>

	<form method="post" id="fm_form" form_id="<?php echo(isset($_GET['form_id']) ? $_GET['form_id'] : 0); ?>">
		<div id="freshmail-properties" class="freshmail-content">
			<?php require_once(WP_FRESHMAIL_DIR.'/templates/form_properties.php'); ?>
		</div>
		<div id="freshmail-appearance" class="freshmail-content">
			<?php require_once(WP_FRESHMAIL_DIR.'/templates/form_appearance.php'); ?>
		</div>
		<div id="freshmail-messages" class="freshmail-content">
			<?php require_once(WP_FRESHMAIL_DIR.'/templates/form_messages.php'); ?>
		</div>
		<div id="freshmail-fields" class="freshmail-content">
			<?php require_once(WP_FRESHMAIL_DIR.'/templates/form_fields.php'); ?>
		</div>
	</form>
	<?php require_once(WP_FRESHMAIL_DIR.'/templates/footer.php'); ?>
</div>