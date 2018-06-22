<div class="wrap freshmail">
	<h1><?php _e('Reports', 'wp_freshmail'); ?></h1>

	<form method="post" id="reports_form">
		<div class="fm-report-set-time">
			<select id="report_type">
				<option value="today"><?php _e('Today', 'wp_freshmail'); ?></option>
				<option value="yesterday"><?php _e('Yesterday', 'wp_freshmail'); ?></option>
				<option value="week"><?php _e('Last Week', 'wp_freshmail'); ?></option>
				<option value="month"><?php _e('Last Month', 'wp_freshmail'); ?></option>
				<option value="quarter"><?php _e('Last Quarter', 'wp_freshmail'); ?></option>
				<option value="year"><?php _e('Last Year', 'wp_freshmail'); ?></option>
				<option value="custom"><?php _e('Custom', 'wp_freshmail'); ?></option>
			</select>

			<span><?php _e('From:', 'wp_freshmail'); ?></span>

			<select id="from_d" name="from_d" class="form_to">
				<?php for ($i = 1; $i < 32; $i++):
					echo '<option value="'.$i.'">'.$i.'</option>';
				endfor; ?>
			</select>

			<select id="from_m" name="from_m" class="form_to">
				<option value="1"><?php _e('Jan', 'wp_freshmail'); ?></option>
				<option value="2"><?php _e('Feb', 'wp_freshmail'); ?></option>
				<option value="3"><?php _e('Mar', 'wp_freshmail'); ?></option>
				<option value="4"><?php _e('Apr', 'wp_freshmail'); ?></option>
				<option value="5"><?php _e('May', 'wp_freshmail'); ?></option>
				<option value="6"><?php _e('Jun', 'wp_freshmail'); ?></option>
				<option value="7"><?php _e('Jul', 'wp_freshmail'); ?></option>
				<option value="8"><?php _e('Aug', 'wp_freshmail'); ?></option>
				<option value="9"><?php _e('Sep', 'wp_freshmail'); ?></option>
				<option value="10"><?php _e('Oct', 'wp_freshmail'); ?></option>
				<option value="11"><?php _e('Nov', 'wp_freshmail'); ?></option>
				<option value="12"><?php _e('Dec', 'wp_freshmail'); ?></option>
			</select>

			<select id="from_y" name="from_y" class="form_to">
				<?php for ($i = 2013; $i <= date('Y'); $i++):
					echo '<option value="'.$i.'">'.$i.'</option>';
				endfor; ?>
			</select>

			<span><?php _e('To:', 'wp_freshmail'); ?></span>
			<select id="to_d" name="to_d" class="form_to">
				<?php for ($i = 1; $i < 32; $i++):
					echo '<option value="'.$i.'">'.$i.'</option>';
				endfor; ?>
			</select>

			<select id="to_m" name="to_m" class="form_to">
				<option value="1"><?php _e('Jan', 'wp_freshmail'); ?></option>
				<option value="2"><?php _e('Feb', 'wp_freshmail'); ?></option>
				<option value="3"><?php _e('Mar', 'wp_freshmail'); ?></option>
				<option value="4"><?php _e('Apr', 'wp_freshmail'); ?></option>
				<option value="5"><?php _e('May', 'wp_freshmail'); ?></option>
				<option value="6"><?php _e('Jun', 'wp_freshmail'); ?></option>
				<option value="7"><?php _e('Jul', 'wp_freshmail'); ?></option>
				<option value="8"><?php _e('Aug', 'wp_freshmail'); ?></option>
				<option value="9"><?php _e('Sep', 'wp_freshmail'); ?></option>
				<option value="10"><?php _e('Oct', 'wp_freshmail'); ?></option>
				<option value="11"><?php _e('Nov', 'wp_freshmail'); ?></option>
				<option value="12"><?php _e('Dec', 'wp_freshmail'); ?></option>
			</select>

			<select id="to_y" name="to_y" class="form_to">
				<?php for ($i = 2014; $i <= date('Y'); $i++):
					echo '<option value="'.$i.'">'.$i.'</option>';
				endfor; ?>
			</select>
			<input type="button" value="set" onclick="refreshReports();" />
		</div>
		<div id="chart_div_c"><?php $this->showVisualizationLineChart(); ?></div>
		<table class="wp-list-table widefat">
			<tr class="table-bar">
				<th colspan="2" class="manage-column column-name" scope="col">
					<?php _e('Most Popular Forms & Checkboxes', 'wp_freshmail'); ?>
				</th>
			</tr>
			<?php $sum = array('form' => 0, 'checkbox' => 0, 'all' => 0);
			$resultForm = null;
			$resultCheckbox = null;
			$results = $wpdb->get_results('SELECT form_id,(SELECT COUNT(*) FROM '.$wpdb->prefix.'freshmail_stats WHERE form_id = forms.form_id) AS mail_count FROM '.$wpdb->prefix.'freshmail_forms forms', OBJECT);
			foreach ($results as $key => $val) {
				$sum['form'] += $val->mail_count;
				$sum['all'] += $val->mail_count;
				$resultForm .= '<tr><td class="manage-column column-name" scope="col">&nbsp;&nbsp;<input type="checkbox" class="report_checkbox" name="using_form[]" value="'.$val->form_id.'" />'.__('Sign-Up Form', 'wp_freshmail').' #'.$val->form_id.'</td><td >'.number_format($val->mail_count).'</td></tr>';
			}
			$results = $wpdb->get_results('SELECT COUNT(*) AS mail_count, form_id FROM '.$wpdb->prefix.'freshmail_stats GROUP BY form_id ORDER BY mail_count DESC', OBJECT);
			foreach ($results as $key => $val) {
				if (!is_numeric($val->form_id)) {
					$sum['checkbox'] += $val->mail_count;
					$resultCheckbox .= '<tr><td class="manage-column column-name" scope="col">&nbsp;&nbsp;<input type="checkbox" class="report_checkbox" name="using_checkbox[]" value="'.$val->form_id.'" /> '.$val->form_id.'</td><td >'.number_format($val->mail_count).'</td></tr>';
				}
			}
			$sum['all'] += $sum['checkbox']; ?>
			<tr>
				<td class="manage-column column-name" scope="col">
					<input type="checkbox" name="total_subscriptions" id="total_subscriptions" checked="checked" value="<?php _e('Total subscriptions', 'wp_freshmail'); ?>"class="report_checkbox" />
					<strong><?php _e('Total subscriptions', 'wp_freshmail'); ?></strong>
				</td>
				<td>
					<strong><?php echo number_format($sum['all']); ?></strong>
				</td>
			</tr>
			<tr>
				<td class="manage-column column-name" scope="col">
					<input type="checkbox" name="using_form[]" value="<?php _e('Using Form', 'wp_freshmail'); ?>" id="using_form" checked="checked" class="report_checkbox" />
					<strong><?php _e('Using Form', 'wp_freshmail'); ?></strong>
				</td>
				<td >
					<?php echo number_format($sum['form']); ?>
				</td>
			</tr>
			<?php echo $resultForm; ?>
			<tr>
				<td class="manage-column column-name" scope="col">
					<input type="checkbox" name="using_checkbox[]" value="<?php _e('Using Checkbox', 'wp_freshmail'); ?>" id="using_checkbox" checked="checked" class="report_checkbox" />
					<?php _e('Using Checkbox', 'wp_freshmail'); ?>
				</td>
				<td >
					<?php echo number_format($sum['checkbox']); ?>
				</td>
			</tr>
			<?php echo $resultCheckbox; ?>
		</table>

		<table class="wp-list-table widefat">
			<tr class="table-bar">
				<th colspan="2" class="manage-column column-name" scope="col">
					<?php _e('Most Popular Sources', 'wp_freshmail'); ?>
				</th>
			</tr>
			<?php global $wpdb;
			$sum = 0;
			$results = $wpdb->get_results('SELECT COUNT(*) AS mail_count, referer FROM '.$wpdb->prefix.'freshmail_stats GROUP BY referer ORDER BY mail_count DESC', OBJECT);
			foreach ($results as $key => $val): ?>
				<tr>
					<td class="manage-column column-name" scope="col">&nbsp;&nbsp;<input type="checkbox" name="sources[]" class="report_checkbox" value="<?php echo $val->referer; ?>" />
						<a href="<?php echo $val->referer; ?>" target="_blank"><?php echo $val->referer; ?></a>
					</td>
					<td >
						<?php echo number_format($val->mail_count); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</form>
	<?php require_once(WP_FRESHMAIL_DIR.'/templates/footer.php'); ?>
</div>
