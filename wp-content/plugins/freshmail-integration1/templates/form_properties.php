<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="tabs" id="properties">
	<div id="fm-form-properties">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="hide_sign_up"><?php _e('Do you want to hide the sign-up form and pop-up when the process is completed?', 'wp_freshmail'); ?></label>
				</th>
				<td>
					<input type="radio" name="fm_form_var[hide_sign_up]" value="yes" <?php echo(isset($freshmailForm['hide_sign_up']) && ($freshmailForm['hide_sign_up'] == 'yes') ? 'checked="checked"' : null); ?>/> <?php _e('Yes', 'wp_freshmail'); ?>
					<br />
					<br />
					<input type="radio" name="fm_form_var[hide_sign_up]" value="no" <?php echo(isset($freshmailForm['hide_sign_up']) && ($freshmailForm['hide_sign_up'] == 'no') ? 'checked="checked"' : null); ?>/> <?php _e('No', 'wp_freshmail'); ?>
				</td>
			</tr>
			</tbody>
		</table>
		<h2 class="nav-tab-wrapper"><?php _e('Pop-up Properties', 'wp_freshmail'); ?></h2>

		<p>
			<?php _e('Pop-up windows are one of the most effective ways of obtaining new email addresses. At the same time, however, they can be a pushy form of advertising. That\'s why we suggest that you use it no more than twice per user.', 'wp_freshmail'); ?>
		</p>

		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<?php _e('Show the sign-up form in a pop-up?', 'wp_freshmail'); ?>
				</th>
				<td>
					<input type="radio" name="fm_form_var[show_in_pop_up]" value="yes" <?php echo(isset($freshmailForm['show_in_pop_up']) && ($freshmailForm['show_in_pop_up'] == 'yes') ? 'checked="checked"' : null); ?>/> <?php _e('Yes', 'wp_freshmail'); ?>
					<br /><br />
					<input type="radio" name="fm_form_var[show_in_pop_up]" value="no" <?php echo(isset($freshmailForm['show_in_pop_up']) && ($freshmailForm['show_in_pop_up'] == 'no') ? 'checked="checked"' : null); ?>/> <?php _e('No', 'wp_freshmail'); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('When to show a pop-up?', 'wp_freshmail'); ?>
				</th>
				<td>
					<input type="radio" name="fm_form_var[when_to_show]" value="immediately" <?php echo(isset($freshmailForm['when_to_show']) && ($freshmailForm['when_to_show'] == 'immediately') ? 'checked="checked"' : 'checked="checked"'); ?>/> <?php _e('Immediately', 'wp_freshmail'); ?>
					<br />
					<br />

					<input type="radio" name="fm_form_var[when_to_show]" value="sec_min" <?php echo(isset($freshmailForm['when_to_show']) && ($freshmailForm['when_to_show'] == 'sec_min') ? 'checked="checked"' : null); ?>/> <?php _e('After', 'wp_freshmail'); ?>
					<input type="text" name="fm_form_var[sec_min][0]" value="<?php echo(isset($freshmailForm['sec_min'][0]) ? $freshmailForm['sec_min'][0] : '30'); ?>" />
					<select name="fm_form_var[sec_min][1]">
						<option value="seconds" <?php echo(isset($freshmailForm['sec_min'][1]) && ($freshmailForm['sec_min'][1] == 'seconds') ? 'selected="selected"' : null); ?>><?php _e('seconds', 'wp_freshmail'); ?></option>
						<option	value="minutes" <?php echo(isset($freshmailForm['sec_min'][1]) && ($freshmailForm['sec_min'][1] == 'minutes') ? 'selected="selected"' : null); ?>><?php _e('minutes', 'wp_freshmail'); ?></option>
					</select>
					<br />
					<br />

					<input type="radio" name="fm_form_var[when_to_show]" value="per_px" <?php echo (isset($freshmailForm['when_to_show']) && $freshmailForm['when_to_show'] == 'per_px') ? 'checked="checked"' : null; ?>/> <?php _e('After', 'wp_freshmail'); ?>
					<input type="text" name="fm_form_var[per_px][0]" value="<?php echo (isset($freshmailForm['per_px'][0]) ? $freshmailForm['per_px'][0] : '70'); ?>" />
					<select name="fm_form_var[per_px][1]">
						<option value="percent" <?php echo(isset($freshmailForm['per_px'][1]) && ($freshmailForm['per_px'][1] == 'percent') ? 'selected="selected"' : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
						<option value="px" <?php echo(isset($freshmailForm['per_px'][1]) && ($freshmailForm['per_px'][1] == 'px') ? 'selected="selected"' : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
					</select> <?php _e('of the page has been scrolled', 'wp_freshmail'); ?><br /><br />

					<input type="checkbox" name="fm_form_var[show_leaves]" value="true" <?php echo (isset($freshmailForm['show_leaves']) && $freshmailForm['show_leaves'] == 'true') ? 'checked="checked"'  : null; ?>/>
						<span><?php _e('Show a pop-up when a user leaves the website', 'wp_freshmail'); ?>
							<br />
							<small><?php _e('Directing a cursor into the upper corner suggests that the user would like to leave the website. This is the last moment to take action and display the popup.', 'wp_freshmail'); ?></small>
						</span>
					<br />
					<br />

					<input type="checkbox" name="fm_form_var[pop_ups_mobile]" value="true" <?php echo isset($freshmailForm['pop_ups_mobile']) && ($freshmailForm['pop_ups_mobile'] == 'true') ? 'checked="checked"' : null; ?>/>
						<span><?php _e('Don\'t show pop-ups on mobile phones', 'wp_freshmail'); ?>
							<br />
							<small><?php _e('It\'s hard to close a pop-up on mobile phones, that\'s why it\'s better not to annoy your users.', 'wp_freshmail'); ?></small>
						</span>
					<br />
					<br />

					<input type="checkbox" name="fm_form_var[dont_show_utm]" value="true" <?php echo isset($freshmailForm['dont_show_utm']) && ($freshmailForm['dont_show_utm'] == 'true') ? 'checked="checked"' : null; ?>/> <?php _e('Don\'t show on specified UTM tag', 'wp_freshmail'); ?>
					<select name="fm_form_var[dont_show_utm2]">
						<option value="utm_source" <?php echo(isset($freshmailForm['dont_show_utm2']) && ($freshmailForm['dont_show_utm2'] == 'utm_source') ? 'selected="selected"' : null); ?>>utm_source=</option>
						<option value="utm_medium" <?php echo(isset($freshmailForm['dont_show_utm2']) && ($freshmailForm['dont_show_utm2'] == 'utm_medium') ? 'selected="selected"'  : null); ?>>utm_medium=</option>
						<option value="utm_campaign" <?php echo(isset($freshmailForm['dont_show_utm2']) && ($freshmailForm['dont_show_utm2'] == 'utm_campaign') ? 'selected="selected"' : null); ?>>utm_campaign=</option>
					</select>
					<input type="text" name="fm_form_var[dont_show_utm_value]" value="<?php echo(isset($freshmailForm['dont_show_utm_value']) ? $freshmailForm['dont_show_utm_value'] : null); ?>" /><br /><br />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e('The maximum number of pop-up displays per user', 'wp_freshmail'); ?>
				</th>
				<td>
					<select name="fm_form_var[max_popups_number]">
						<option	value="0" <?php echo(isset($freshmailForm['max_popups_number']) && ($freshmailForm['max_popups_number'] == '0') ? 'selected="selected"' : null); ?>><?php _e('No limit', 'wp_freshmail'); ?></option>
						<?php for ($i = 1; $i < 15; $i++): ?>
							<option value="<?php echo $i; ?>" <?php echo(isset($freshmailForm['max_popups_number']) && ($freshmailForm['max_popups_number'] == $i) ? 'selected="selected"' : null); ?>><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Lag time between displays', 'wp_freshmail'); ?></th>
				<td>
					<input type="text" name="fm_form_var[lag_time]" value="<?php echo(isset($freshmailForm['lag_time']) ? $freshmailForm['lag_time'] : '0'); ?>" /> <?php _e('seconds', 'wp_freshmail'); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Select pages where popups will be displayed', 'wp_freshmail'); ?></th>
				<td>
					<div class="fm-select-pages">
						<?php $pages = get_pages();
						$allowedPages = (isset($freshmailForm['allowed_pages']) && is_array($freshmailForm['allowed_pages'])) ? $freshmailForm['allowed_pages'] : array(); ?>
						<strong><?php _e('Pages', 'wp_freshmail'); ?></strong>
						<label class="allowAll">
							<small>(<?php _e('Set all', 'wp_freshmail'); ?></small>
						</label> /
						<label class="disallowAll">
							<small><?php _e('Unset all', 'wp_freshmail'); ?>)</small>
						</label><br>
						<select multiple="multiple" name="fm_form_var[allowed_pages][]" >
							<option value="all" <?php echo in_array('all', isset($freshmailForm['allowed_pages']) ? $freshmailForm['allowed_pages'] : array()) ? 'selected="selected"' : null; ?>><?php _e('All Pages', 'wp_freshmail'); ?></option>
							<?php foreach ($pages as $key => $value): ?>
								<option value="<?php echo $value->ID; ?>" <?php echo in_array($value->ID, isset($freshmailForm['allowed_pages']) ? $freshmailForm['allowed_pages'] : array()) ? 'selected="selected"' : null; ?>><?php echo $value->post_title; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="fm-select-pages">
						<?php $mainPosts = get_posts();
						$allowedMainPosts = (isset($freshmailForm['allowed_main_posts']) && is_array($freshmailForm['allowed_main_posts'])) ? $freshmailForm['allowed_main_posts'] : array(); ?>
						<strong><?php _e('Posts', 'wp_freshmail'); ?></strong>
						<label class="allowAll">
							<small>(<?php _e('Set all', 'wp_freshmail'); ?></small>
						</label> /
						<label class="disallowAll">
							<small><?php _e('Unset all', 'wp_freshmail'); ?>)</small>
						</label><br>
						<select multiple="multiple" name="fm_form_var[allowed_main_posts][]" >
							<option value="all" <?php echo in_array('all', (isset($freshmailForm['allowed_main_posts']) ? $freshmailForm['allowed_main_posts'] : array())) ? 'selected="selected"' : null; ?>><?php _e('All Pages', 'wp_freshmail'); ?></option>
							<?php foreach ($mainPosts as $key => $value): ?>
								<option value="<?php echo $value->ID; ?>" <?php echo in_array($value->ID, (isset($freshmailForm['allowed_main_posts']) ? $freshmailForm['allowed_main_posts'] : array())) ? 'selected="selected"' : null; ?>><?php echo $value->post_title; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php $args = array(
						'orderby' => 'post_date',
						'order' => 'DESC',
						'post_type' => 'product',
						'post_status' => 'publish'
					);
					$products = get_posts($args);

					if (!empty($products)): ?>
						<div class="fm-select-pages">
						<?php if (post_type_exists('product')):
							$allowedProducts = (isset($freshmailForm['allowed_products']) && is_array($freshmailForm['allowed_products'])) ? $freshmailForm['allowed_products'] : array(); ?>
							<strong><?php _e('Products', 'wp_freshmail'); ?></strong>
							<label class="allowAll">
								<small>(<?php _e('Set all', 'wp_freshmail'); ?></small>
							</label> /
							<label class="disallowAll">
								<small><?php _e('Unset all', 'wp_freshmail'); ?>)</small>
							</label>
							<br />
							<select multiple="multiple" name="fm_form_var[allowed_products][]" >
								<option value="all" <?php echo in_array('all', (isset($freshmailForm['allowed_products']) ? $freshmailForm['allowed_products'] : array())) ? 'selected="selected"' : null; ?>><?php _e('All Products', 'wp_freshmail'); ?></option>
								<?php foreach ($products as $key => $value): ?>
									<option value="<?php echo $value->ID; ?>" <?php echo in_array($value->ID, (isset($freshmailForm['allowed_products']) ? $freshmailForm['allowed_products'] : array())) ? 'selected="selected"' : null; ?>><?php echo $value->post_title; ?></option>
								<?php endforeach; ?>
							</select>
							</div>
						<?php endif;
					endif;?>
				</td>
			</tr>
			</tbody>
		</table>

		<h2 class="nav-tab-wrapper"><?php _e('Redirection After Sign Up', 'wp_freshmail'); ?></h2>

		<p><?php _e('Where do you want to redirect the user after a successful sign up?', 'wp_freshmail'); ?></p>

		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<input type="radio" name="fm_form_var[redirection]" value="no" <?php echo(isset($freshmailForm['redirection']) ? ($freshmailForm['redirection'] == 'no' ? 'checked="checked"' : null) : 'checked="checked"'); ?> /> <?php _e('No redirection', 'wp_freshmail'); ?>
				</th>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th scope="row">
					<input type="radio" name="fm_form_var[redirection]" id="redirection_wordpress" value="wordpress" <?php echo(isset($freshmailForm['redirection']) ? ($freshmailForm['redirection'] == 'wordpress' ? 'checked="checked"' : null) : null); ?> /> <?php _e('WordPress page', 'wp_freshmail'); ?>
				</th>
				<td>
					<select name="fm_form_var[redirection_wp]" onchange="jQuery('#redirection_wordpress').attr('checked','checked');">
						<option value=""><?php _e('select...', 'wp_freshmail'); ?></option>
						<?php foreach ($pages as $page): ?>
							<option	value="<?php echo get_page_link($page->ID); ?>" <?php echo(isset($freshmailForm['redirection_wp']) ? ($freshmailForm['redirection_wp'] == get_page_link($page->ID) ? 'selected="selected"' : '') : ''); ?>><?php echo $page->post_title; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<input type="radio" name="fm_form_var[redirection]" value="custom" <?php echo(isset($freshmailForm['redirection']) ? ($freshmailForm['redirection'] == 'custom' ? 'checked="checked"' : null) : null); ?> /> <?php _e('Custom URL', 'wp_freshmail'); ?>
				</th>
				<td>
					<input type="text" name="fm_form_var[redirection_custom]" id="customurl_addr" value="<?php echo(isset($freshmailForm['redirection_custom']) ? $freshmailForm['redirection_custom'] : null); ?>" />
					<a target="_blank" href="#" id="customurl" onclick="jQuery(this).attr('href',jQuery('#customurl_addr').val());"><?php _e('Check page', 'wp_freshmail'); ?></a>
				</td>
			</tr>
			</tbody>
		</table>

		<p>
			<input type="submit" value="<?php _e('Save changes', 'wp_freshmail'); ?>" title="" class="button button_fm freshmail_api_key_submit" id="freshmail_api_key_submit" name="freshmail_api_key_submit" />
		</p>
	</div>
</div>