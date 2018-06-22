<div class="wrap freshmail">
	<h1><?php _e('Sign Up Forms', 'wp_freshmail'); ?></h1>
	<form method="post" action="">
		<p><?php _e('Select the recipient list to which you would like to add the new subscribers gained from the sign-up form.', 'wp_freshmail'); ?></p>
		<p>
			<label for="select_freshmail_list">
				<strong><?php _e('Select a FreshMail List', 'wp_freshmail'); ?></strong>
				<select id="select_freshmail_list" name="fm_form_var[select_freshmail_list_id]" onchange="get_freshmail_fields(jQuery(this).val());">
					<option value=""><?php _e('Select list', 'wp_freshmail'); ?></option>
					<?php try {
						$response = $this->api->doRequest('subscribers_list/lists');
						foreach ($response['lists'] as $key => $val) {
							echo '<option value="'.$val['subscriberListHash'].'" '.(isset($freshmailForm['select_freshmail_list_id']) ? ($freshmailForm['select_freshmail_list_id'] == $val['subscriberListHash'] ? 'selected="selected"' : null) : null).'>'.$val['name'].'</option>';
						}
					} catch (Exception $e) {
					/* //for developing
					echo $e->getMessage();*/} ?>
				</select>
			</label>
		</p>
		<p><?php _e('Mark the fields that a new subscriber must fill in when signing up for a newsletter using the form.', 'wp_freshmail'); ?></p>
		<table id="freshmail_fields" class="widefat"></table>
		<p><input type="submit" value="<?php _e('Add form', 'wp_freshmail'); ?>" class="button button_fm" name="freshmail_new_form" /></p>
	</form>
	<?php require_once(WP_FRESHMAIL_DIR.'/templates/footer.php'); ?>
</div>