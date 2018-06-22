<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="tabs" id="list_fields">
	<p>
		<?php _e('Select the recipient list to which you would like to add the new subscribers gained from the sign-up form.', 'wp_freshmail'); ?>
	</p>

	<p>
		<label for="select_freshmail_list">
			<strong><?php _e('Select a FreshMail List', 'wp_freshmail'); ?></strong>
			<select id="select_freshmail_list" name="fm_form_var[select_freshmail_list_id]" onchange="get_freshmail_fields(jQuery(this).val());">
				<option value=""><?php _e('Select list', 'wp_freshmail'); ?></option>
				<?php try {
					$response = $this->api->doRequest('subscribers_list/lists');
					foreach ($response['lists'] as $key => $val) {
						echo '<option value="'.$val['subscriberListHash'].'" '.(isset($freshmailForm['select_freshmail_list_id']) ? ($freshmailForm['select_freshmail_list_id'] == $val['subscriberListHash'] ? 'selected' : null) : null).'>'.$val['name'].'</option>';
					}
				} catch (Exception $e) {
				/* //for developing
				echo $e->getMessage()*/	} ?>
			</select>
		</label>
	</p>

	<p>
		<?php _e('Mark the fields that a new subscriber must fill in when signing up for a newsletter using the form.', 'wp_freshmail'); ?>
	</p>

	<table id="freshmail_fields" class="widefat">
		<?php if (isset($freshmailForm['select_freshmail_list_id'])) { ?>
			<tr>
				<th><?php _e('Field name', 'wp_freshmail'); ?></th>
				<th><?php _e('Text on the Form', 'wp_freshmail'); ?></th>
				<th><?php _e('Placeholder on the Form', 'wp_freshmail'); ?></th>
				<th><?php _e('Required', 'wp_freshmail'); ?></th>
				<th><?php _e('Include', 'wp_freshmail'); ?></th>
			</tr>

			<?php try {
				$response = $this->api->doRequest('subscribers_list/getFields', array('hash' => $freshmailForm['select_freshmail_list_id'])); ?>
				<tr>
					<td><?php _e('Email', 'wp_freshmail'); ?></td>
					<td>
						<input type="text" placeholder="<?php _e('Email', 'wp_freshmail'); ?>" name="fm_form_var[fields][email][value]" value="<?php echo(isset($freshmailForm['fields']['email']['value']) ? $freshmailForm['fields']['email']['value'] : __('Email', 'wp_freshmail')); ?>" />
					</td>
					<td>
						<input type="text" placeholder="<?php _e('Placeholder', 'wp_freshmail'); ?>" name="fm_form_var[fields][email][placeholder]" value="<?php echo (isset($freshmailForm['fields']['email']['placeholder'])) ? $freshmailForm['fields']['email']['placeholder'] : __('Placeholder', 'wp_freshmail'); ?>" />
					</td>
					<td>
						<input type="checkbox" checked="yes" name="fm_form_var[fields][email][required]" disabled="disabled" checked="checked" />
					</td>
					<td>
						<input type="checkbox" checked="yes" name="fm_form_var[fields][email][include]" disabled="disabled" checked="checked" />
					</td>
				</tr>
				<?php foreach ($response['fields'] as $key => $val) { ?>
					<tr>
						<td><?php echo $val['name']; ?></td>
						<td>
							<input type="text" placeholder="<?php echo $val['name']; ?>" name="fm_form_var[fields][<?php echo $val['tag']; ?>][value]" value="<?php echo(isset($freshmailForm['fields'][$val['tag']]['value']) ? $freshmailForm['fields'][$val['tag']]['value'] : $val['name']); ?>" />
							<input type="hidden" name="fm_form_var[fields][<?php echo $val['tag']; ?>][hash]" value="<?php echo $val['hash']; ?>" />
						</td>
						<td>
							<input type="text" placeholder="<?php echo $val['name']; ?>" name="fm_form_var[fields][<?php echo $val['tag']; ?>][placeholder]" value="<?php echo(isset($freshmailForm['fields'][$val['tag']]['placeholder']) ? $freshmailForm['fields'][$val['tag']]['placeholder'] : __('Placeholder', 'wp_freshmail')); ?>" />
						</td>
						<td>
							<input type="checkbox" name="fm_form_var[fields][<?php echo $val['tag']; ?>][required]" <?php echo(isset($freshmailForm['fields'][$val['tag']]['required']) ? ($freshmailForm['fields'][$val['tag']]['required'] == 'on' ? 'checked="checked"' : null) : ''); ?> />
						</td>
						<td>
							<input type="checkbox" name="fm_form_var[fields][<?php echo $val['tag']; ?>][include]" <?php echo(isset($freshmailForm['fields'][$val['tag']]['include']) ? ($freshmailForm['fields'][$val['tag']]['include'] == 'on' ? 'checked="checked"' : null) : ''); ?> />
						</td>
					</tr>
				<?php	}
			} catch (Exception $e) {
				echo 'Error message: '.$e->getMessage().', Error code: '.$e->getCode().', HTTP code: '.$this->api->getHttpCode().PHP_EOL;
			}
		} ?>
	</table>

	<p>
		<input type="submit" value="<?php _e('Save', 'wp_freshmail'); ?>" class="button button_fm" name="freshmail_new_form" />
	</p>

	<?php if (isset($_GET['form_id'])) { ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				freshmail_changeTheme();
			});
		</script>
	<?php } ?>
</div>