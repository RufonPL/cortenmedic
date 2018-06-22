<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php $appearance = isset($freshmailForm['appearance']) ? $freshmailForm['appearance'] : null; ?>
<div class="tabs" id="appearance">
		<div id="freshmail_theme">
		<h4><?php _e('Theme Editor', 'wp_freshmail'); ?></h4>

		<div id="accordion">
			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title"><h4><?php _e('Form Container', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4></div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Form width', 'wp_freshmail'); ?></label></td>
						<td><input type="text" value="<?php echo (isset($appearance['form_container']['width']) ? $appearance['form_container']['width'] : '100'); ?>" name="fm_form_var[appearance][form_container][width]"  />
							<select name="fm_form_var[appearance][form_container][width2]">
								<option value="%" <?php echo (isset($appearance['form_container']['width2']) ? ($appearance['form_container']['width2'] == '%' ? 'selected="selected"' : null) : null); ?>>%</option>
								<option value="px" <?php echo (isset($appearance['form_container']['width2']) ? ($appearance['form_container']['width2'] == 'px' ? 'selected="selected"' : null) : null); ?>>px</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Background color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['form_container']['background_color']) ? $appearance['form_container']['background_color'] : '#CCC'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['form_container']['background_color']) ? $appearance['form_container']['background_color'] : '#CCC'); ?>" name="fm_form_var[appearance][form_container][background_color]"  /></td>
					</tr>
					<tr>
						<td><label><?php _e('Border color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['form_container']['border_color']) ? $appearance['form_container']['border_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['form_container']['border_color']) ? $appearance['form_container']['border_color'] : '#000'); ?>" name="fm_form_var[appearance][form_container][border_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Border width', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['form_container']['border_width']) ? $appearance['form_container']['border_width'] : '1'); ?>" name="fm_form_var[appearance][form_container][border_width]"  /> px
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Rounded corners', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['form_container']['rounded_corners']) ? $appearance['form_container']['rounded_corners'] : '0'); ?>" name="fm_form_var[appearance][form_container][rounded_corners]"  />0-90
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Vertical padding', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['form_container']['padding1']) ? $appearance['form_container']['padding1'] : '25'); ?>" name="fm_form_var[appearance][form_container][padding1]"  /> px
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Horizontal padding', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['form_container']['padding2']) ? $appearance['form_container']['padding2'] : '25'); ?>" name="fm_form_var[appearance][form_container][padding2]"  /> px
						</td>
					</tr>
				</table>
			</div>
			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title"><h4><?php _e('Text Header', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4></div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Display header text?', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="radio" <?php echo (isset($appearance['text_header']['display']) ? ($appearance['text_header']['display'] == 'yes' ? 'checked="checked"' : null) : 'checked="checked"'); ?> value="yes" name="fm_form_var[appearance][text_header][display]" /><?php _e('Yes', 'wp_freshmail'); ?>
							<input type="radio" <?php echo (isset($appearance['text_header']['display']) ? ($appearance['text_header']['display'] == 'no' ? 'checked="checked"' : null) : null); ?> value="no" name="fm_form_var[appearance][text_header][display]" /><?php _e('No', 'wp_freshmail'); ?>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['text_header']['text_color']) ? $appearance['text_header']['text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['text_header']['text_color']) ? $appearance['text_header']['text_color'] : '#000'); ?>" name="fm_form_var[appearance][text_header][text_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text size', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['text_header']['text_size']) ? $appearance['text_header']['text_size'] : '0'); ?>" name="fm_form_var[appearance][text_header][text_size]"  /> pt
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text alignment', 'wp_freshmail'); ?></label></td>
						<td>
							<select name="fm_form_var[appearance][text_header][text_alignment]">
								<option value="0" <?php echo (isset($appearance['text_header']['text_alignment']) ? ($appearance['text_header']['text_alignment'] == '0' ? 'selected="selected"' : null) : null); ?>><?php _e('select alignment...', 'wp_freshmail'); ?></option>
								<option value="left" <?php echo (isset($appearance['text_header']['text_alignment']) ? ($appearance['text_header']['text_alignment'] == 'left' ? 'selected="selected"' : null) : null); ?>><?php _e('Left', 'wp_freshmail'); ?></option>
								<option value="center" <?php echo (isset($appearance['text_header']['text_alignment']) ? ($appearance['text_header']['text_alignment'] == 'center' ? 'selected="selected"' : null) : null); ?>><?php _e('Center', 'wp_freshmail'); ?></option>
								<option value="right" <?php echo (isset($appearance['text_header']['text_alignment']) ? ($appearance['text_header']['text_alignment'] == 'right' ? 'selected="selected"' : null) : null); ?>><?php _e('Right', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title">
					<h4><?php _e('Sub-Header', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4>
				</div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Display sub header?', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="radio" <?php echo (isset($appearance['sub_header']['display']) ? ($appearance['sub_header']['display'] == 'yes' ? 'checked="checked"' : null) : 'checked="checked"'); ?> value="yes" name="fm_form_var[appearance][sub_header][display]" /><?php _e('Yes', 'wp_freshmail'); ?>
							<input type="radio" <?php echo (isset($appearance['sub_header']['display']) ? ($appearance['sub_header']['display'] == 'no' ? 'checked="checked"' : null) : null); ?> value="no" name="fm_form_var[appearance][sub_header][display]" /><?php _e('No', 'wp_freshmail'); ?></td>
					</tr>
					<tr>
						<td><label><?php _e('Text color', 'wp_freshmail'); ?></label></td>
						<td><input type="text" value="<?php echo (isset($appearance['sub_header']['text_color']) ? $appearance['sub_header']['text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['sub_header']['text_color']) ? $appearance['sub_header']['text_color'] : '#000'); ?>" name="fm_form_var[appearance][sub_header][text_color]"  /></td>
					</tr>
					<tr>
						<td><label><?php _e('Text size', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['sub_header']['text_size']) ? $appearance['sub_header']['text_size'] : '0'); ?>" name="fm_form_var[appearance][sub_header][text_size]"  /> pt
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text alignment', 'wp_freshmail'); ?></label></td>
						<td>
							<select name="fm_form_var[appearance][sub_header][text_alignment]">
								<option value="0" <?php echo (isset($appearance['sub_header']['text_alignment']) ? ($appearance['sub_header']['text_alignment'] == '0' ? 'selected="selected"' : null) : null); ?>><?php _e('select alignment...', 'wp_freshmail'); ?></option>
								<option value="left" <?php echo (isset($appearance['sub_header']['text_alignment']) ? ($appearance['sub_header']['text_alignment'] == 'left' ? 'selected="selected"' : null) : null); ?>><?php _e('Left', 'wp_freshmail'); ?></option>
								<option value="center" <?php echo (isset($appearance['sub_header']['text_alignment']) ? ($appearance['sub_header']['text_alignment'] == 'center' ? 'selected="selected"' : null) : null); ?>><?php _e('Center', 'wp_freshmail'); ?></option>
								<option value="right" <?php echo (isset($appearance['sub_header']['text_alignment']) ? ($appearance['sub_header']['text_alignment'] == 'right' ? 'selected="selected"' : null) : null); ?>><?php _e('Right', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title"><h4><?php _e('Label', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4></div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Label width', 'wp_freshmail'); ?></label></td>
						<td><input type="text" value="<?php echo (isset($appearance['label']['width']) ? $appearance['label']['width'] : '100'); ?>" name="fm_form_var[appearance][label][width]"  />
							<select name="fm_form_var[appearance][label][select_width]">
								<option value="%" <?php echo (isset($appearance['label']['select_width']) ? ($appearance['label']['select_width'] == '%' ? 'selected="selected"' : null) : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
								<option value="px" <?php echo (isset($appearance['label']['select_width']) ? ($appearance['label']['select_width'] == 'px' ? 'selected="selected"' : null) : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Label position', 'wp_freshmail'); ?></label></td>
						<td>
							<select name="fm_form_var[appearance][label][position]">
								<option value="clear:left;display:block;" <?php echo (isset($appearance['label']['position']) ? ($appearance['label']['position'] == 'clear:left;display:block;' ? 'selected="selected"' : null) : null); ?>><?php _e('New line', 'wp_freshmail'); ?></option>
								<option value="display:block;float:left;" <?php echo (isset($appearance['label']['position']) ? ($appearance['label']['position'] == 'display:block;float:left;' ? 'selected="selected"' : null) : null); ?>><?php _e('Inline', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['label']['text_color']) ? $appearance['label']['text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['label']['text_color']) ? $appearance['label']['text_color'] : '#000'); ?>" name="fm_form_var[appearance][label][text_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text size', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['label']['text_size']) ? $appearance['label']['text_size'] : '0'); ?>" name="fm_form_var[appearance][label][text_size]"  /> pt
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text style', 'wp_freshmail'); ?></label></td>
						<td>
							<select name="fm_form_var[appearance][label][select_text_style]">
								<option value="0" <?php echo (isset($appearance['label']['select_text_style']) ? ($appearance['label']['select_text_style'] == '0' ? 'selected="selected"' : null) : null); ?>><?php _e('select...', 'wp_freshmail'); ?></option>
								<option value="font-style:normal;" <?php echo (isset($appearance['label']['select_text_style']) ? ($appearance['label']['select_text_style'] == 'font-style:normal;' ? 'selected="selected"' : null) : null); ?>><?php _e('Normal', 'wp_freshmail'); ?></option>
								<option value="font-weight:bold;" <?php echo (isset($appearance['label']['select_text_style']) ? ($appearance['label']['select_text_style'] == 'font-style:bold;' ? 'selected="selected"' : null) : null); ?>><?php _e('Bold', 'wp_freshmail'); ?></option>
								<option value="font-style:italic;" <?php echo (isset($appearance['label']['select_text_style']) ? ($appearance['label']['select_text_style'] == 'font-style:italic;' ? 'selected="selected"' : null) : null); ?>><?php _e('Italic', 'wp_freshmail'); ?></option>
								<option value="font-style:italic;font-weight:bold;" <?php echo (isset($appearance['label']['select_text_style']) ? ($appearance['label']['select_text_style'] == 'font-style:bold italic;' ? 'selected="selected"' : null) : null); ?>><?php _e('Bold&Italic', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Display labels', 'wp_freshmail'); ?></label></td>
						<td><input type="checkbox" name="fm_form_var[appearance][label][display_labels]" value="yes" <?php echo (isset($appearance['label']['display_labels'])) ? 'checked="checked"' : null; ?>  /></td>
					</tr>
					<tr>
						<td><label><?php _e('Display placeholders', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="checkbox" name="fm_form_var[appearance][label][display_placeholders]" value="yes" <?php echo (isset($appearance['label']['display_placeholders'])) ? 'checked="checked"' : null; ?> />
						</td>
					</tr>
				</table>
			</div>

			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title"><h4><?php _e('Field', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4></div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Field width', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['width']) ? $appearance['field']['width'] : '0'); ?>" name="fm_form_var[appearance][field][width]"  />
							<select name="fm_form_var[appearance][field][select_field_width]">
								<option value="%" <?php echo (isset($appearance['field']['select_field_width']) ? ($appearance['field']['select_field_width'] == '%' ? 'selected="selected"' : null) : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
								<option value="px" <?php echo (isset($appearance['field']['select_field_width']) ? ($appearance['field']['select_field_width'] == 'px' ? 'selected="selected"' : null) : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Field height', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['height']) ? $appearance['field']['height'] : '0'); ?>" name="fm_form_var[appearance][field][height]"  />
							<select name="fm_form_var[appearance][field][select_field_height]">
								<option value="%" <?php echo (isset($appearance['field']['select_field_height']) ? ($appearance['field']['select_field_height'] == '%' ? 'selected="selected"' : null) : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
								<option value="px" <?php echo (isset($appearance['field']['select_field_height']) ? ($appearance['field']['select_field_height'] == 'px' ? 'selected="selected"' : null) : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Text color', 'wp_freshmail'); ?></label>
						</td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['text_color']) ? $appearance['field']['text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['field']['text_color']) ? $appearance['field']['text_color'] : '#000'); ?>" name="fm_form_var[appearance][field][text_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text size', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['text_size']) ? $appearance['field']['text_size'] : '0'); ?>" name="fm_form_var[appearance][field][text_size]"  /> pt
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Border color', 'wp_freshmail'); ?></label>
						</td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['border_color']) ? $appearance['field']['border_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['field']['border_color']) ? $appearance['field']['border_color'] : '#000'); ?>" name="fm_form_var[appearance][field][border_color]"  />
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Border width', 'wp_freshmail'); ?></label>
						</td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['border_width']) ? $appearance['field']['border_width'] : '1'); ?>" name="fm_form_var[appearance][field][border_width]"  /> px
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Vertical margin', 'wp_freshmail'); ?></label>
						</td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['vertical_margin']) ? $appearance['field']['vertical_margin'] : '0'); ?>" name="fm_form_var[appearance][field][vertical_margin]"  />
							<select name="fm_form_var[appearance][field][vertical_margin_type]">
								<option value="%" <?php echo (isset($appearance['field']['vertical_margin_type']) ? ($appearance['field']['vertical_margin_type'] == '%' ? 'selected="selected"' : null) : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
								<option value="px" <?php echo (isset($appearance['field']['vertical_margin_type']) ? ($appearance['field']['vertical_margin_type'] == 'px' ? 'selected="selected"' : null) : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label><?php _e('Horizontal margin', 'wp_freshmail'); ?></label>
						</td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['field']['horizontal_margin']) ? $appearance['field']['horizontal_margin'] : '0'); ?>" name="fm_form_var[appearance][field][horizontal_margin]"  />
							<select name="fm_form_var[appearance][field][horizontal_margin_type]">
								<option value="%" <?php echo (isset($appearance['field']['horizontal_margin_type']) ? ($appearance['field']['horizontal_margin_type'] == '%' ? 'selected="selected"' : null) : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
								<option value="px" <?php echo (isset($appearance['field']['horizontal_margin_type']) ? ($appearance['field']['horizontal_margin_type'] == 'px' ? 'selected="selected"' : null) : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>

			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title"><h4><?php _e('Checkbox agreement', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4></div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Display checkbox?', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="radio" value="yes" <?php echo (isset($appearance['checkbox_agreement']['display']) ? ($appearance['checkbox_agreement']['display'] == 'yes' ? 'checked="checked"' : null) : 'checked="checked"'); ?> name="fm_form_var[appearance][checkbox_agreement][display]" /><?php _e('Yes', 'wp_freshmail'); ?>
							<input type="radio" value="no" <?php echo (isset($appearance['checkbox_agreement']['display']) ? ($appearance['checkbox_agreement']['display'] == 'no' ? 'checked="checked"' : null) : null); ?> name="fm_form_var[appearance][checkbox_agreement][display]" /><?php _e('No', 'wp_freshmail'); ?>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Default checked?', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="radio" value="yes" <?php echo (isset($appearance['checkbox_agreement']['checked']) ? ($appearance['checkbox_agreement']['checked'] == 'yes' ? 'checked="checked"' : null) : 'checked="checked"'); ?> name="fm_form_var[appearance][checkbox_agreement][checked]" /><?php _e('Yes', 'wp_freshmail'); ?>
							<input type="radio" value="no" <?php echo (isset($appearance['checkbox_agreement']['checked']) ? ($appearance['checkbox_agreement']['checked'] == 'no' ? 'checked="checked"' : null) : null); ?> name="fm_form_var[appearance][checkbox_agreement][checked]" /><?php _e('No', 'wp_freshmail'); ?>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text color', 'wp_freshmail'); ?></label></td>
						<td><input type="text" value="<?php echo (isset($appearance['checkbox_agreement']['text_color']) ? $appearance['checkbox_agreement']['text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['checkbox_agreement']['text_color']) ? $appearance['checkbox_agreement']['text_color'] : '#000'); ?>" name="fm_form_var[appearance][checkbox_agreement][text_color]"  /></td>
					</tr>
					<tr>
						<td><label><?php _e('Text size', 'wp_freshmail'); ?></label></td>
						<td><input type="text" value="<?php echo (isset($appearance['checkbox_agreement']['text_size']) ? $appearance['checkbox_agreement']['text_size'] : '0'); ?>" name="fm_form_var[appearance][checkbox_agreement][text_size]"  /> pt
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text alignment', 'wp_freshmail'); ?></label></td>
						<td>
							<select name="fm_form_var[appearance][checkbox_agreement][text_aligment]">
								<option value="0" <?php echo (isset($appearance['checkbox_agreement']['text_aligment']) ? ($appearance['checkbox_agreement']['text_aligment'] == '0' ? 'selected="selected"' : null) : null); ?>><?php _e('select alignment...', 'wp_freshmail'); ?></option>
								<option value="left" <?php echo (isset($appearance['checkbox_agreement']['text_aligment']) ? ($appearance['checkbox_agreement']['text_aligment'] == 'left' ? 'selected="selected"' : null) : null); ?>><?php _e('Left', 'wp_freshmail'); ?></option>
								<option value="center" <?php echo (isset($appearance['checkbox_agreement']['text_aligment']) ? ($appearance['checkbox_agreement']['text_aligment'] == 'center' ? 'selected="selected"' : null) : null); ?>><?php _e('Center', 'wp_freshmail'); ?></option>
								<option value="right" <?php echo (isset($appearance['checkbox_agreement']['text_aligment']) ? ($appearance['checkbox_agreement']['text_aligment'] == 'right' ? 'selected="selected"' : null) : null); ?>><?php _e('Right', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title"><h4><?php _e('Button', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4></div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Button width', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button']['width']) ? $appearance['button']['width'] : '100'); ?>" name="fm_form_var[appearance][button][width]"  />
							<select name="fm_form_var[appearance][button][select_width]">
								<option value="%" <?php echo (isset($appearance['button']['select_width']) ? ($appearance['button']['select_width'] == '%' ? 'selected="selected"' : null) : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
								<option value="px" <?php echo (isset($appearance['button']['select_width']) ? ($appearance['button']['select_width'] == 'px' ? 'selected="selected"' : null) : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Button height', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button']['height']) ? $appearance['button']['height'] : '100'); ?>" name="fm_form_var[appearance][button][height]"  />
							<select name="fm_form_var[appearance][button][select_height]">
								<option	value="%" <?php echo (isset($appearance['button']['select_height']) ? ($appearance['button']['select_height'] == '%' ? 'selected="selected"' : null) : null); ?>><?php _e('%', 'wp_freshmail'); ?></option>
								<option value="px" <?php echo (isset($appearance['button']['select_height']) ? ($appearance['button']['select_height'] == 'px' ? 'selected="selected"' : null) : null); ?>><?php _e('px', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Button alignment', 'wp_freshmail'); ?></label></td>
						<td>
							<select name="fm_form_var[appearance][button][select_aligment]">
								<option value="0" <?php echo (isset($appearance['button']['select_aligment']) ? ($appearance['button']['select_aligment'] == '0' ? 'selected="selected"' : null) : null); ?>><?php _e('select alignment...', 'wp_freshmail'); ?></option>
								<option value="left" <?php echo (isset($appearance['button']['select_aligment']) ? ($appearance['button']['select_aligment'] == 'left' ? 'selected="selected"' : null) : null); ?>><?php _e('Left', 'wp_freshmail'); ?></option>
								<option value="center" <?php echo (isset($appearance['button']['select_aligment']) ? ($appearance['button']['select_aligment'] == 'center' ? 'selected="selected"' : null) : null); ?>><?php _e('Center', 'wp_freshmail'); ?></option>
								<option value="right" <?php echo (isset($appearance['button']['select_aligment']) ? ($appearance['button']['select_aligment'] == 'right' ? 'selected="selected"' : null) : null); ?>><?php _e('Right', 'wp_freshmail'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text size', 'wp_freshmail'); ?></label></td>
						<td><input type="text" value="<?php echo (isset($appearance['button']['text_size']) ? $appearance['button']['text_size'] : '0'); ?>" name="fm_form_var[appearance][button][text_size]"  /> pt
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text color', 'wp_freshmail'); ?></label></td>
						<td><input type="text" value="<?php echo (isset($appearance['button']['text_color']) ? $appearance['button']['text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($freshmailForm) ? $appearance['button']['text_color'] : '#000'); ?>" name="fm_form_var[appearance][button][text_color]"  /></td>
					</tr>
					<tr>
						<td><label><?php _e('Background color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button']['background_color']) ? $appearance['button']['background_color'] : '#CCC'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['button']['background_color']) ? $appearance['button']['background_color'] : '#CCC'); ?>" name="fm_form_var[appearance][button][background_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Border color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button']['border_color']) ? $appearance['button']['border_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['button']['border_color']) ? $appearance['button']['border_color'] : '#000'); ?>" name="fm_form_var[appearance][button][border_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Border width', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button']['border_width']) ? $appearance['button']['border_width'] : '1'); ?>" name="fm_form_var[appearance][button][border_width]"  /> px
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Rounded corners', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button']['rounded_corners']) ? $appearance['button']['rounded_corners'] : '0'); ?>" name="fm_form_var[appearance][button][rounded_corners]"  /> 0-90
						</td>
					</tr>
					<tr>
						<td colspan="2"><h2 class="nav-tab-wrapper"><?php _e('Hovered', 'wp_freshmail'); ?></h2></td>
					</tr>
					<tr>
						<td><label><?php _e('Background color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button_hovered']['background_color']) ? $appearance['button_hovered']['background_color'] : '#CCC'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['button_hovered']['background_color']) ? $appearance['button_hovered']['background_color'] : '#CCC'); ?>" name="fm_form_var[appearance][button_hovered][background_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Border color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button_hovered']['border_color']) ? $appearance['button_hovered']['border_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['button_hovered']['border_color']) ? $appearance['button_hovered']['border_color'] : '#000'); ?>" name="fm_form_var[appearance][button_hovered][border_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Text color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['button_hovered']['text_color']) ? $appearance['button_hovered']['text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['button_hovered']['text_color']) ? $appearance['button_hovered']['text_color'] : '#000'); ?>" name="fm_form_var[appearance][button_hovered][text_color]"  />
						</td>
					</tr>
				</table>
			</div>
			<div class="widget-top">
				<div class="widget-title-action"><a href="#" class="widget-action hide-if-no-js"></a></div>
				<div class="widget-title"><h4><?php _e('Error & Success Messages', 'wp_freshmail'); ?><span class="in-widget-title"></span></h4></div>
			</div>
			<div class="widget-inside">
				<table>
					<tr>
						<td><label><?php _e('Error text color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['error_success']['error_text_color']) ? $appearance['error_success']['error_text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['error_success']['error_text_color']) ? $appearance['error_success']['error_text_color'] : '#000'); ?>" name="fm_form_var[appearance][error_success][error_text_color]"  />
						</td>
					</tr>
					<tr>
						<td><label><?php _e('Success text color', 'wp_freshmail'); ?></label></td>
						<td>
							<input type="text" value="<?php echo (isset($appearance['error_success']['success_text_color']) ? $appearance['error_success']['success_text_color'] : '#000'); ?>" class="popup-colorpicker" data-default-color="<?php echo (isset($appearance['error_success']['success_text_color']) ? $appearance['error_success']['success_text_color'] : '#000'); ?>" name="fm_form_var[appearance][error_success][success_text_color]"  />
						</td>
					</tr>
				</table>
			</div>
		</div>
		<br />
		<br />
		<p>
			<input type="submit" value="<?php _e('Save changes', 'wp_freshmail'); ?>" class="freshmail_save_submit freshmail_api_key_submit button button_fm" id="freshmail_api_key_submit" name="freshmail_api_key_submit" />
			<input type="button" value="<?php _e('Save as custom theme', 'wp_freshmail'); ?>" class="freshmail_custom_submit freshmail_api_key_submit button button_fm" onclick="freshmail_saveAsCustomTheme();" />
		</p>
		</div>
		<div class="fm-preview-theme">
			<h4><?php _e('Select Theme', 'wp_freshmail'); ?></h4>
			<p><?php if (isset($_POST['theme_id'])):
					$appearance['freshmail_select_theme'] = $_POST['theme_id'];
				endif; ?>
				<select id="freshmail_select_theme" name="fm_form_var[appearance][freshmail_select_theme]" onchange="freshmail_changeTheme();">
					<?php	$customTheme = get_option('freshmail_custom_theme');
					if (!empty($customTheme)) {
						$customTheme = unserialize($customTheme);
						$firstCustom = false;
						foreach ($customTheme as $key => $val) {
							if (!is_numeric($key)) {
								echo '<option value="'.$key.'" '.(isset($appearance['freshmail_select_theme']) ? (($appearance['freshmail_select_theme'] == $key) ? 'selected="selected"' : null) : null).'>'.ucfirst(str_replace('_', ' ', $key)).'</option>';
							} else {
								if ($firstCustom == false) {
									$firstCustom = true;
									echo '<option disabled>---</option>';
								}
								echo '<option value="'.$key.'" '.(isset($appearance['freshmail_select_theme']) ? (($appearance['freshmail_select_theme'] == $key && is_numeric($appearance['freshmail_select_theme'])) ? 'selected="selected"' : null) : null).'>'.__('Custom theme', 'wp_freshmail').' #'.($key + 1).'</option>';
							}
						}
					} ?>
				</select>
			</p>
			<h4><?php _e('Form Preview', 'wp_freshmail'); ?> <img id="form_preview_loader" src="<?php echo WP_FRESHMAIL_URL; ?>/assets/images/loader.gif" alt="..." /></h4>
			<p id="preview_form">
				<?php if (!defined('DOING_AJAX')) { ?>
					<script type="text/javascript">
						jQuery(document).ready(function($){
							freshmail_disable_submit_buttons();
							freshmail_previewForm();
							jQuery('img#form_preview_loader').toggle();
						});
					</script>
				<?php } ?>
			</p>
		</div>
</div>