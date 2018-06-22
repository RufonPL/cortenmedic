<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div style="width:<?php echo $formContainer['width'].$formContainer['width2']; ?>;" >
	<div style="<?php echo 'background-color: '.$formContainer['background_color'].';border:'.$formContainer['border_width'].'px solid '.$formContainer['border_color'].';border-radius:'.$formContainer['rounded_corners'].'px; padding: '.$formContainer['padding1'].'px  '.$formContainer['padding2'].'px;'; ?>overflow:hidden;" id="fm_form_<?php echo $fmFormId['id']; ?>">
		<div class="form_container">
			<form method="post" class="form_subscribe freshmail_form_<?php echo $fmFormId['id']; ?>">
				<?php if ($textHeader['display'] == 'yes'): ?>
					<p style="<?php echo 'color: '.$textHeader['text_color'].'; font-size:'.$textHeader['text_size'].'pt; text-align:'.$textHeader['text_alignment'].';'; ?>" class="text_header">
						<?php echo $freshmailForm['messages']['form_header']; ?>
					</p>
				<?php endif;

				if ($subHeader['display'] == 'yes'): ?>
					<div class="sub_header"><?php echo $freshmailForm['messages']['form_sub_header']; ?></div>
					<style>
						.sub_header *, .sub_header {
						<?php echo 'color:'.$subHeader['text_color'].';
						 font-size:'.$subHeader['text_size'].'pt;
						 text-align:'.$subHeader['text_alignment'].';'; ?>
						}
					</style>
				<?php endif;

				if (isset($freshmailForm['fields']) && is_array($freshmailForm['fields'])):
					foreach ($freshmailForm['fields'] as $key => $val):


						if (is_array($val)):
							if ((isset($val['include']) && $val['include'] == 'on') || $key=='email'):
							 if (isset($label['display_labels']) && $label['display_labels'] == 'yes'): ?>
							<div>
								<label style="<?php echo $label['position'].';width:'.$label['width'].$label['select_width'].';color:'.$label['text_color'].';font-size:'.$label['text_size'].'pt; '.$label['select_text_style']; ?>" class="label">
									<?php echo $val['value'];
									echo ((isset($val['required']) && $val['required'] == 'on') || $key=='email'  ? '<span style="color:red;">*</span>' : null); ?>
								</label>
							</div>
							<?php endif; ?>
								<div style="<?php echo 'margin: '.$field['vertical_margin'].$field['vertical_margin_type'].' '.$field['horizontal_margin'].$field['horizontal_margin_type'].';'; ?>">
									<input type="text" class="field" placeholder="<?php echo (isset($label['display_placeholders']) && $label['display_placeholders'] == 'yes') ? (isset($val['placeholder']) ? $val['placeholder'] : $val['value']) : null; ?>" value="" name="form[<?php echo $key; ?>]" style="padding:1px 5px;<?php echo 'max-width:100%;width:'.$field['width'].$field['select_field_width'].'; height:'.$field['height'].$field['select_field_height'].';color:'.$field['text_color'].';font-size:'.$field['text_size'].'pt; border:'.$field['border_width'].'px solid '.$field['border_color'].';';?>" />
								</div>
							<?php	endif;
						endif;
					endforeach;
				endif;

				if ($checkboxAgreement['display'] == 'yes'): ?>
					<p style="margin-top:8px;<?php echo 'color:'.$checkboxAgreement['text_color'].';font-size:'.$checkboxAgreement['text_size'].'pt;text-align:'.$checkboxAgreement['text_aligment'].';'; ?>">
						<input type="checkbox" value="on" name="fm_form_agree" id="fm_form_agree" class="checkbox_agreement" <?php echo($checkboxAgreement['checked'] == 'yes') ? 'checked="checked"' : null; ?> />
						<span><?php echo $freshmailForm['messages']['form_agreement_label']; ?></span>
					</p>
				<?php endif; ?>

				<p style="<?php echo 'text-align:'.$button['select_aligment'].';'; ?>">
					<button type="submit" name="form_subscribe_button" class="form_subscribe_button button" style="<?php echo 'box-shadow:none;border-radius:'.$button['rounded_corners'].'px;width:'.$button['width'].$button['select_width'].'; height:'.$button['height'].$button['select_height'].';font-size:'.$button['text_size'].'pt; color:'.$button['text_color'].';background:'.$button['background_color'].';border:'.$button['border_width'].'px solid '.$button['border_color'].';'; ?>">
						<?php echo $freshmailForm['messages']['form_subscribe_button']; ?>
					</button>
				</p>

				<?php if (!empty($fmFormId['redirect'])): ?>
					<input type="hidden" value="<?php echo $fmFormId['redirect']; ?>" name="redirect" />
				<?php endif; ?>

				<input type="hidden" value="<?php echo $fmFormId['id']; ?>" name="fm_form_id" />
				<input type="hidden" value="fm_form" name="action" />
				<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="fm_form_referer" />
			</form>
		</div>
	</div>
</div>

<style>
	.freshmail_dialog .ui-dialog-titlebar-close {
		background: url(<?php echo WP_FRESHMAIL_URL.'/assets/images/close.png);'; ?>)
	}
	.message_error {
		<?php echo 'color:'.$errorSuccess['error_text_color'].';';?>
	}
	.message_success {
		<?php echo 'color:'.$errorSuccess['success_text_color'].';';?>
	}
	.form_subscribe_button:hover {
		<?php echo 'background:'.$buttonHovered['background_color'].' !important; color:'.$buttonHovered['text_color'].' !important; border-color:'.$buttonHovered['border_color'].' !important;'; ?>
	}
</style>