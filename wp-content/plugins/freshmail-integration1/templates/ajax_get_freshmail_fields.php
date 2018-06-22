<?php if (!isset($_POST['list_hash']) || empty($_POST['list_hash'])) {
die('<div style="padding:5px">'.__('Select a FreshMail List to view fields.', 'wp_freshmail').'</div>');
} ?>
<tr>
	<th><?php _e('Field name', 'wp_freshmail'); ?></th>
	<th><?php _e('Text on the Form', 'wp_freshmail'); ?></th>
	<th><?php _e('Placeholder on the Form', 'wp_freshmail'); ?></th>
	<th><?php _e('Required', 'wp_freshmail'); ?></th>
	<th><?php _e('Include', 'wp_freshmail'); ?></th>
</tr>
<?php global $freshmailForm;

try {
	$response = $this->api->doRequest('subscribers_list/getFields', array('hash' => $_POST['list_hash']));?>
	<tr>
		<td><?php _e('Email', 'wp_freshmail'); ?></td>
		<td>
			<input type="text" placeholder="<?php _e('Email', 'wp_freshmail'); ?>" name="fm_form_var[fields][email][value]" value="<?php _e('Email', 'wp_freshmail'); ?>" />
		</td>
		<td>
			<input type="text" placeholder="<?php _e('Email', 'wp_freshmail'); ?>" name="fm_form_var[fields][email][placeholder]" value="<?php _e('Email', 'wp_freshmail'); ?>" />
		</td>
		<td>
			<input type="checkbox" checked="yes" name="fm_form_var[fields][email][required]" disabled="disabled" checked="checked" />
		</td>
		<td>
			<input type="checkbox" checked="yes" name="fm_form_var[fields][email][include]" disabled="disabled" checked="checked" />
		</td>
	</tr>
	<?php foreach ($response['fields'] as $val): ?>
		<tr>
			<td><?php echo $val['name']; ?></td>
			<td>
				<input type="text" placeholder="<?php echo $val['name']; ?>" name="fm_form_var[fields][<?php echo $val['tag']; ?>][value]" value="<?php echo(is_array($freshmailForm) ? $freshmailForm['fields'][$val['tag']]['value'] : $val['name']); ?>" />
				<input type="hidden" name="fm_form_var[fields][<?php echo $val['tag']; ?>][hash]" value="<?php echo $val['hash']; ?>" />
			</td>
			<td>
				<input type="text" placeholder="<?php echo $val['name']; ?>" name="fm_form_var[fields][<?php echo $val['tag']; ?>][placeholder]" value="<?php echo $val['name'] ?>" />
			</td>
			<td>
				<input type="checkbox" name="fm_form_var[fields][<?php echo $val['tag']; ?>][required]" <?php echo(isset($freshmailForm['fields'][$val['tag']]['required']) ? ($freshmailForm['fields'][$val['tag']]['required'] == 'on' ? 'checked="checked"' : null) : 'checked="checked"'); ?>/>
			</td>
			<td>
				<input type="checkbox" name="fm_form_var[fields][<?php echo $val['tag']; ?>][include]" <?php echo(isset($freshmailForm['fields'][$val['tag']]['include']) ? ($freshmailForm['fields'][$val['tag']]['include'] == 'on' ? 'checked="checked"' : null) : 'checked="checked"'); ?>/>
			</td>
		</tr>
	<?php endforeach;
} catch (Exception $e) {
	echo 'Error message: '.$e->getMessage().', Error code: '.$e->getCode().', HTTP code: '.$rest->getdttpCode().PHP_EOL;
}