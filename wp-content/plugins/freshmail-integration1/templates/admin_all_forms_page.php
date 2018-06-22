<div class="wrap freshmail">
	<h1>FreshMail <a class="add-new-h2" href="<?php echo admin_url('admin.php?page=freshmail_new_form'); ?>"><?php _e('Add New Form', 'wp_freshmail'); ?></a></h1>
	<br class="clear">
	<br class="clear">
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th><?php _e('Form', 'wp_freshmail'); ?></th>
				<th><?php _e('Contact List', 'wp_freshmail'); ?></th>
				<th><?php _e('Number of Subscribers', 'wp_freshmail'); ?></th>
				<th><?php _e('Pop-up', 'wp_freshmail'); ?></th>
				<th><?php _e('Shortcode', 'wp_freshmail'); ?></th>
				<th><?php _e('Last edited', 'wp_freshmail'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php _e('Form', 'wp_freshmail'); ?></th>
				<th><?php _e('Contact List', 'wp_freshmail'); ?></th>
				<th><?php _e('Number of Subscribers', 'wp_freshmail'); ?></th>
				<th><?php _e('Pop-up', 'wp_freshmail'); ?></th>
				<th><?php _e('Shortcode', 'wp_freshmail'); ?></th>
				<th><?php _e('Last edited', 'wp_freshmail'); ?></th>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($forms as $row):
			$vars = unserialize($row->freshmail_form_var);
			$subscribers = $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'freshmail_stats WHERE form_id = '.$row->form_id);?>
			<tr>
				<td>
					<strong><a href="<?php echo admin_url('admin.php?page=freshmail&form_id='.$row->form_id); ?>"><?php _e('Sign Up Form', 'wp_freshmail'); ?> #<?php echo $row->form_id; ?></a></strong>
					<br>
					<span class="row-actions">
						<a href="<?php echo admin_url('admin.php?page=freshmail&form_id='.$row->form_id); ?>"><?php _e('Edit Form', 'wp_freshmail'); ?></a> |
						<a href="<?php echo admin_url('admin.php?page=freshmail&freshmail=duplicate&form_id='.$row->form_id); ?>"><?php _e('Duplicate', 'wp_freshmail'); ?></a> |
						<a href="<?php echo admin_url('admin.php?page=freshmail&freshmail=remove&form_id='.$row->form_id); ?>" style="color: #a00"><?php _e('Delete', 'wp_freshmail'); ?></a>
					</span>
				</td>
				<td><?php echo $row->freshmail_list_name; ?></td>
				<td><?php echo $subscribers; ?></td>
				<td><?php echo $vars['show_in_pop_up']; ?></td>
				<td><input type="text" value="[FM_form id=&quot;<?php echo $row->form_id; ?>&quot;]" /></td>
				<td><?php echo $row->last_edited; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<p><?php _e('Drag & drop the FreshMail Form to the sidebar on the <a href="widgets.php">Appearance > Widgets</a> page.', 'wp_freshmail'); ?></p>
	<p><?php _e('Use a shortcode, for example: <input type="text" value="[FM_form id=&quot;1&quot;]" /> to display the selected form inside a post, page or text widget.', 'wp_freshmail'); ?></p>
	<?php require_once(WP_FRESHMAIL_DIR.'/templates/footer.php'); ?>
</div>