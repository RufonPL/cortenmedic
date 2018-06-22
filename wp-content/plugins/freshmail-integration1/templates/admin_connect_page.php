<div class="wrap freshmail">
	<h1><?php _e('Connect to FreshMail', 'wp_freshmail'); ?></h1>
	<?php	if (gettype($this->connect) == 'boolean' && $this->connect == true): ?>
		<h2>
			<?php _e('Logged to FreshMail account.', 'wp_freshmail'); ?>
			<p>
				<a class="button button_fm" href="<?php echo admin_url('admin.php?page=freshmail&freshmail=logout'); ?>"><?php _e('Log Out', 'wp_freshmail'); ?></a>
			</p>
		</h2>
	<?php else: ?>
		<h2><?php _e('Hello! Start using this plug-in by connecting to your FreshMail account. Find out <a href="http://freshmail.com/help-and-knowledge/help/account-settings/what-is-an-api-key-and-where-can-you-find-it/" target="_blank">where to find API Keys</a>.', 'wp_freshmail'); ?></h2>
		<form method="post" action="<?php echo admin_url('admin.php?page=freshmail_connect'); ?>">
			<h2>
				<label for="freshmail_api_key" class="freshmail_api">
					<span><?php _e('API Key', 'wp_freshmail'); ?></span>
					<input type="text" value="<?php echo $this->fmApiKey; ?>" title="" class="" id="freshmail_api_key" name="freshmail_api_key" required />
				</label>
			</h2>

			<h2>
				<label for="freshmail_api_secret_key" class="freshmail_api">
					<span><?php _e('API Secret Key', 'wp_freshmail'); ?></span>
					<input type="text" value="<?php echo $this->fmApiSecret; ?>" title="" class="" id="freshmail_api_secret_key" name="freshmail_api_secret" required />
				</label>
			</h2>

			<h2>
				<label for="freshmail_api_key_submit" class="freshmail_api">
					<span>&nbsp;</span>
					<input type="submit" value="<?php _e('Save changes', 'wp_freshmail'); ?>" title="" class="button button_fm" id="freshmail_api_key_submit" name="freshmail_api_key_submit" />
				</label>
			</h2>

			<?php	if(isset($_POST['freshmail_api_key_submit']) && gettype($this->connect) == 'string'): ?>
				<h2 id="fm-connect-error">
					<label class="freshmail_api">
						<span><?php echo $this->connect ?>!</span>
						<?php echo (strpos(($this->connect), 'autoryzacji')) ? _e('Check your API key and API Secret Key <a href="https://app.freshmail.com/en/settings/integration/">here</a>', 'wp_freshmail') : null ?>
					</label>
				</h2>
			<?php endif; ?>

		</form>
		<div class="donthaveaccount">
			<span><?php _e('You don\'t have a FreshMail account yet?', 'wp_freshmail'); ?></span>
			<p><?php _e('<a href="http://freshmail.com/" target="_blank">FreshMail</a> is an email marketing tool for creating and sending amazing newsletters. Our intuitive system leads users from campaign planning and creation to final reports.', 'wp_freshmail'); ?></p>
			<p><?php _e('A free account lets you send up to 2000 messages to a maximum of 500 recipients each month.', 'wp_freshmail'); ?></p>
			<p><?php _e('<a href="https://app.freshmail.com/#register-tab" target="_blank" class="button button_fm">Sign Up For Free!</a> or <a href="http://freshmail.com">Learn more</a>', 'wp_freshmail'); ?></p>
			<p><?php _e('No credit card required', 'wp_freshmail'); ?></p>
		</div>
	<?php endif; ?>

	<h2>Settings</h2>
	<form action="" method="POST">
		<p>
			<label><input type="checkbox" name="freshmail_uninstall_all" value="true" <?php echo ($uninstallAll == 'true') ? 'checked="checked"' : null; ?>><?php echo __('Delete all options and tables from database during uninstall.', 'wp_freshmail'); ?></label>
		</p>
		<p>
			<label><input type="checkbox" name="freshmail_dont_verify_ssl" value="true" <?php echo ($doNotVerifySSL == 'true') ? 'checked="checked"' : null; ?>><?php echo __('Do not verify the SSL certificates (at your own risk).', 'wp_freshmail'); ?></label>
		</p>
		<p>
			<input type="submit" value="<?php echo __('Save settings', 'wp_freshmail'); ?>" class="button button_fm" name="freshmail_save_settings">
		</p>
	</form>
	<?php require_once(WP_FRESHMAIL_DIR.'/templates/footer.php'); ?>
</div>