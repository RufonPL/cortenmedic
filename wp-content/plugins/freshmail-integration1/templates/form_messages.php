<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="tabs" id="messages">
	<p>
		<?php _e('Customise your automatic messages. HTML tags like <span class="fm_code">&lt;strong&gt; &lt;em&gt; &lt;a&gt;</span> are allowed.', 'wp_freshmail'); ?>
	</p>

	<table>
		<tr>
			<th><?php _e('Form header', 'wp_freshmail'); ?></th>
			<td>
				<input type="text" placeholder="<?php _e('Subscribe to our newsletter', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['form_header']) : __('Subscribe to our newsletter', 'wp_freshmail')); ?>" name="fm_form_var[messages][form_header]"/>
			</td>
		</tr>
		<tr>
			<th><?php _e('Form sub-header', 'wp_freshmail'); ?></th>
			<td>
				<?php
				$settings = array(
					'dfw' => true,
					'default_editor' => 'tmce',
					'media_buttons' => true,
					'textarea_name' => 'fm_form_var[messages][form_sub_header]',
					'quicktags' => true
				);
				wp_editor((is_array($freshmailForm) ? $freshmailForm['messages']['form_sub_header'] : __('Get updates direct to your inbox.', 'wp_freshmail')), 'fm_form_var_messages_form_sub_header', $settings); ?>
			</td>
		</tr>
		<tr>
			<th><?php _e('Checkbox agreement label text', 'wp_freshmail'); ?></th>
			<td><input type="text" placeholder="<?php _e('Send me your newsletter (you can unsubscribe at any time).', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['form_agreement_label']) : __('Send me your newsletter (you can unsubscribe at any time).', 'wp_freshmail')); ?>" name="fm_form_var[messages][form_agreement_label]" /></td>
		</tr>
		<tr>
			<th><?php _e('Form subscribe button', 'wp_freshmail'); ?></th>
			<td>
				<input type="text" placeholder="<?php _e('Sign me up!', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['form_subscribe_button']) : __('Sign me up!', 'wp_freshmail')); ?>" name="fm_form_var[messages][form_subscribe_button]" />
			</td>
		</tr>
	</table>

	<h2 class="nav-tab-wrapper">&nbsp;</h2>
	<table>
		<tr>
			<th><?php _e('Submission success message', 'wp_freshmail'); ?></th>
			<td><input type="text" placeholder="<?php _e('Your sign up request was successful! Please check your email inbox.', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['success']) : __('Your sign up request was successful! Please check your email inbox.', 'wp_freshmail')); ?>" name="fm_form_var[messages][success]"/></td>
		</tr>
		<tr>
			<th><?php _e('Submission failure message', 'wp_freshmail'); ?></th>
			<td>
				<input type="text" placeholder="<?php _e('Oops. Something went wrong. Please try again later.', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['failure']) : __('Oops. Something went wrong. Please try again later.', 'wp_freshmail')); ?>" name="fm_form_var[messages][failure]"/>
			</td>
		</tr>
		<tr>
			<th><?php _e('Already subscribed message', 'wp_freshmail'); ?></th>
			<td>
				<input type="text" placeholder="<?php _e('Given email address is already subscribed, thank you!', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['already']) : __('Given email address is already subscribed, thank you!', 'wp_freshmail')); ?>" name="fm_form_var[messages][already]"/>
			</td>
		</tr>
		<tr>
			<th><?php _e('Invalid email address message', 'wp_freshmail'); ?></th>
			<td>
				<input type="text" placeholder="<?php _e('Please provide a valid email address', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['invalid']) : __('Please provide a valid email address', 'wp_freshmail')); ?>" name="fm_form_var[messages][invalid]"/></td>
		</tr>
		<tr>
			<th><?php _e('Required field missing message', 'wp_freshmail'); ?></th>
			<td>
				<input type="text" placeholder="<?php _e('Please fill all the required fields', 'wp_freshmail'); ?>" value="<?php echo(is_array($freshmailForm) ? str_replace('"', "&quot;", $freshmailForm['messages']['required']) : __('Please fill all the required fields', 'wp_freshmail')); ?>" name="fm_form_var[messages][required]"/></td>
		</tr>
	</table>
	<p>
		<input type="submit" value="<?php _e('Save changes', 'wp_freshmail'); ?>" title="" class="button button_fm freshmail_api_key_submit" id="freshmail_api_key_submit" name="freshmail_api_key_submit" />
	</p>
</div>