<div class="wrap freshmail" id="fm-admin-checkboxes">
	<h1><?php _e('Sign Up Checkboxes', 'wp_freshmail'); ?></h1>
	<?php echo (isset($deleteNotice) ? $deleteNotice : null); ?>
	<p>
		<?php _e('Thanks to this function you can easily add a checkbox to different forms so users can sign up for a newsletter while completing other actions on the website, for example while commenting.', 'wp_freshmail'); ?>
	</p>
	<form method="post" action="">
		<table>
			<tr>
				<th valign="top"><?php _e('Select a FreshMail List', 'wp_freshmail'); ?></th>
				<td>
					<select id="freshmail_list_id" name="freshmail_list_id" onchange="if(jQuery(this).val() != null) { jQuery('.fm_hidden').show(); } else { jQuery('.fm_hidden').hide(); } ">
						<option value=""><?php _e('Select...', 'wp_freshmail'); ?></option>
						<?php try {
							$response = $this->api->doRequest('subscribers_list/lists');
							foreach ($response['lists'] as $key => $val) {
								echo '<option value="'.$val['subscriberListHash'].'" '.(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['freshmail_list_id'] == $val['subscriberListHash'] ? 'selected' : null) : null).'>'.$val['name'].'</option>';
							}
						} catch (Exception $e) { } ?>
					</select>
					<br />
					<br />
					<?php _e('Select the recipients list to which you would like to add new email addresses', 'wp_freshmail'); ?>
				</td>
			</tr>
		</table>
		<div class="fm_hidden" <?php echo (isset($freshmailSettings['fm_sign_up_checkboxes']['freshmail_list_id'])) ? 'style="display:block;"' : null ?>>
			<h2 class="nav-tab-wrapper"><?php _e('Checkbox Settings', 'wp_freshmail'); ?></h2>
			<p>
				<?php _e('Select where you want to add checkboxes (find out which plug-ins could be integrated with ours). <br />Customise checkbox label text. HTML tags like <span class="fm_code">&lt;strong&gt; &lt;em&gt; &lt;a&gt;</span> are allowed.', 'wp_freshmail'); ?>
			</p>
			<table class="wp-list-table widefat fm-checkboxes-table">
				<tr>
					<th class="manage-column column-name" scope="col" style="border-bottom:1px solid #eee;width:250px;"><?php _e('Form', 'wp_freshmail'); ?></th>
					<th class="manage-column column-name" scope="col" style="border-bottom:1px solid #eee;width:450px;"><?php _e('Checkbox label text', 'wp_freshmail'); ?></th>
				</tr>
				<tr>
					<td class="manage-column column-name" scope="col">
						<input type="checkbox" name="sign_up_checkboxes[comment_form]" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form'] == 'on' ? 'checked="checked"' : null) : null); ?> />
						<?php _e('Comment Form', 'wp_freshmail'); ?>
					</td>
					<td class="manage-column column-name" scope="col">
						<input type="text" name="sign_up_checkboxes[comment_form_txt]" value="<?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form_txt'])) ? $freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['comment_form_txt'] : __('Yes, sign me up for the free newsletter!', 'wp_freshmail')); ?>" />
					</td>
				</tr>
				<tr>
					<td class="manage-column column-name" scope="col">
						<input type="checkbox" name="sign_up_checkboxes[reg_form]" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['reg_form'] == 'on' ? 'checked="checked"' : null) : null); ?> />
						<?php _e('Registration Form', 'wp_freshmail'); ?>
					</td>
					<td class="manage-column column-name" scope="col">
						<input type="text" name="sign_up_checkboxes[reg_form_txt]" value="<?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['reg_form_txt'])) ? $freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['reg_form_txt'] : __('Yes, sign me up for the free newsletter!', 'wp_freshmail')); ?>" />
					</td>
				</tr>
				<?php if (class_exists('woocommerce')) { ?>
					<tr>
						<td class="manage-column column-name" scope="col">
							<input <?php echo(!class_exists('woocommerce') ? 'disabled' : null); ?> type="checkbox" name="sign_up_checkboxes[woo_form]" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['woo_form'] == 'on' ? 'checked="checked"' : null) : null); ?> />
							<?php _e('WooCommerce Checkout', 'wp_freshmail'); ?>
						</td>
						<td class="manage-column column-name" scope="col">
							<input  <?php echo(!class_exists('woocommerce') ? 'disabled' : null); ?> type="text" name="sign_up_checkboxes[woo_form_txt]" value="<?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['woo_form_txt'])) ? $freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['woo_form_txt'] : __('Yes, sign me up for the free newsletter!', 'wp_freshmail')); ?>" />
						</td>
					</tr>
				<?php	}	if (class_exists('EDD_API')) { ?>
					<tr>
						<td class="manage-column column-name" scope="col">
							<input <?php echo(!class_exists('EDD_API') ? 'disabled' : null); ?> type="checkbox" name="sign_up_checkboxes[easy_dig_form]" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['easy_dig_form'] == 'on' ? 'checked="checked"' : null) : null); ?> />
							<?php _e('Easy Digital Download Checkout', 'wp_freshmail'); ?>
						</td>
						<td class="manage-column column-name" scope="col">
							<input  <?php echo(!class_exists('EDD_API') ? 'disabled' : null); ?> type="text" name="sign_up_checkboxes[easy_dig_form_txt]" value="<?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['easy_dig_form_txt'])) ? $freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['easy_dig_form_txt'] : __('Yes, sign me up for the free newsletter!', 'wp_freshmail')); ?>" />
						</td>
					</tr>
				<?php	}	if (class_exists('bbpress')) { ?>
					<tr>
						<td class="manage-column column-name" scope="col">
							<input <?php echo(!class_exists('bbpress') ? 'disabled' : null); ?> type="checkbox" name="sign_up_checkboxes[bbpress_form]" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form'] == 'on' ? 'checked="checked"' : null) : null); ?> />
							<?php _e('bbPress', 'wp_freshmail'); ?>
						</td>
						<td class="manage-column column-name" scope="col">
							<input  <?php echo(!class_exists('bbpress') ? 'disabled' : null); ?> type="text" name="sign_up_checkboxes[bbpress_form_txt]" value="<?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form_txt'])) ? $freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['bbpress_form_txt'] : __('Yes, sign me up for the newsletter!', 'wp_freshmail')); ?>" />
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td class="manage-column column-name" scope="col">
						<input type="checkbox" name="sign_up_checkboxes[multisite_form]" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['multisite_form'] == 'on' ? 'checked="checked"' : null) : null); ?> />
						<?php _e('MultiSite Forms', 'wp_freshmail'); ?>
					</td>
					<td class="manage-column column-name" scope="col">
						<input type="text" name="sign_up_checkboxes[multisite_form_txt]" value="<?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['multisite_form_txt'])) ? $freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['multisite_form_txt'] : __('Yes, sign me up for the forum newsletter!', 'wp_freshmail')); ?>" />
					</td>
				</tr>
				<?php if (class_exists('BuddyPress')) { ?>
					<tr>
						<td class="manage-column column-name" scope="col">
							<input type="checkbox" name="sign_up_checkboxes[buddypress_form]" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['buddypress_form'] == 'on' ? 'checked="checked"' : null) : null); ?> />
							<?php _e('BuddyPress registration', 'wp_freshmail'); ?>
						</td>
						<td class="manage-column column-name" scope="col">
							<input  <?php echo(!class_exists('BuddyPress') ? 'disabled' : null); ?> type="text" name="sign_up_checkboxes[buddypress_form_txt]" value="<?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['buddypress_form_txt'])) ? $freshmailSettings['fm_sign_up_checkboxes']['sign_up_checkboxes']['buddypress_form_txt'] : __('Yes, sign me up for the forum newsletter!', 'wp_freshmail')); ?>" />
						</td>
					</tr>
				<?php }
				if (function_exists('wpcf7_add_shortcode')) { ?>
					<tr>
						<td class="manage-column column-name" scope="col">
							<input type="checkbox" checked="checked" />
							<?php _e('Contact form 7', 'wp_freshmail'); ?>
						</td>
						<td class="manage-column column-name" scope="col">
							<?php _e('Use shortcode in your Contact Form 7 mark-up to add a sign-up checkbox to your CF7 forms', 'wp_freshmail'); ?><br />
							<input type="text" value="[fm_checkbox label=&quot;Label text&quot;]" />
						</td>
					</tr>
				<?php } ?>
			</table>
			<table>
				<tr>
					<th valign="top"><?php _e('Default checkbox selected?', 'wp_freshmail'); ?></th>
					<td>
						<input type="radio" name="fm_default_selected" value="yes" <?php echo((isset($freshmailSettings['fm_sign_up_checkboxes']) && !empty($freshmailSettings['fm_sign_up_checkboxes']['fm_default_selected'])) ? ($freshmailSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'yes' ? 'checked="checked"' : null) : 'checked="checked"'); ?> /> <?php _e('Yes', 'wp_freshmail'); ?>
						<br>
						<br>
						<input type="radio" name="fm_default_selected" value="no" <?php echo(isset($freshmailSettings['fm_sign_up_checkboxes']) ? ($freshmailSettings['fm_sign_up_checkboxes']['fm_default_selected'] == 'no' ? 'checked="checked"' : null) : null); ?> /> <?php _e('No', 'wp_freshmail'); ?>
					</td>
				</tr>
			</table>
		</div>
		<p>
			<input type="submit" value="<?php _e('Save changes', 'wp_freshmail'); ?>" title="" class="button button_fm" id="freshmail_submit" name="freshmail_submit" />
		</p>
	</form>
	<?php include_once(WP_FRESHMAIL_DIR.'/templates/footer.php'); ?>
</div>