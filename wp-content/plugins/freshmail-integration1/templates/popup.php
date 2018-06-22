<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
// Show a pop-up when a user leaves the website
if (isset($freshmailForm['show_leaves'])) {
	if ($showPopUp == true) {
		$javascriptIncluded = true; ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(document).bind("mousemove", function(event){
					if(event.pageY < 15){
						jQuery('#fm_popup_<?php echo $val->form_id; ?>').dialog({ modal: true, resizable: false, draggable: false, autoOpen: false, width: 'auto', dialogClass: 'freshmail_dialog' });
						jQuery('#fm_popup_<?php echo $val->form_id; ?>').parent().css({position: 'fixed'}).end().dialog('open');
						jQuery.post(static_var.ajax, {'action': 'freshmail_popup_show', 'form_id':<?php echo $val->form_id; ?>});
						jQuery(document).unbind("mousemove");
					}
				});
			});
		</script>
	<?php
	}
}

if ($freshmailForm['when_to_show'] != 'immediately') {
	if ($freshmailForm['when_to_show'] == 'sec_min') {
		if ($freshmailForm['sec_min'][1] == 'minutes') {
			$freshmailForm['sec_min'][0] = $freshmailForm['sec_min'][0] / 60;
		}
		$javascriptIncluded = true; ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				console.log('FreshMail - show pop-ups after <?php echo $freshmailForm['sec_min'][0]; ?>seconds.');
				setTimeout(function(){
					console.log('FreshMail - form <?php echo $val->form_id; ?> opened.');
					jQuery('#fm_popup_<?php echo $val->form_id; ?>').dialog({ modal: true, resizable: false, draggable: false, autoOpen: false, width: 'auto', dialogClass: 'freshmail_dialog' });
					jQuery('#fm_popup_<?php echo $val->form_id; ?>').parent().css({position: 'fixed'}).end().dialog('open');
					jQuery.post(static_var.ajax, {'action': 'freshmail_popup_show', 'form_id':<?php echo $val->form_id; ?>});
				}, <?php echo $freshmailForm['sec_min'][0] * 1000; ?>);
			});
		</script>
	<?php
	}
	if ($freshmailForm['when_to_show'] == 'per_px') {
		$javascriptIncluded = true; ?>
		<script type="text/javascript">
			window.showFreshmailPopup = true;
			jQuery(document).ready(function(){
				console.log('FreshMail - show pop-ups after <?php echo $freshmailForm['per_px'][0].' '.$freshmailForm['per_px'][1]; ?> scrolled.');
				jQuery(document).bind("scroll", function(){
					var max = <?php
						if ($freshmailForm['per_px'][1] == 'percent') {
							echo 'jQuery(document).height() * '.($freshmailForm['per_px'][0]/100);
						} else {
							echo $freshmailForm['per_px'][0];
						}	?>;
					if((jQuery(document).scrollTop() + jQuery(window).height()) > max && window.showFreshmailPopup == true){
						console.log('FreshMail - form <?php echo $val->form_id; ?> opened after ' + jQuery(window).scrollTop() + 'px scrolled. <?php echo $freshmailForm['per_px'][0].' '.$freshmailForm['per_px'][1]; ?> - ' + max);
						jQuery('#fm_popup_<?php echo $val->form_id; ?>').dialog({ modal: true, resizable: false, draggable: false, autoOpen: false, width: 'auto', dialogClass: 'freshmail_dialog' });
						jQuery('#fm_popup_<?php echo $val->form_id; ?>').parent().css({position: "fixed"}).end().dialog('open');
						jQuery.post(static_var.ajax, {'action': 'freshmail_popup_show', 'form_id':<?php echo $val->form_id; ?>});
						window.showFreshmailPopup = false;
					}
				});
			});
		</script>
	<?php
	}
}

if ($showPopUp == true) {
	if ($javascriptIncluded == false) {
		$javascriptIncluded = true;	?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#fm_popup_<?php echo $val->form_id; ?>").dialog({ modal: true, resizable: false, draggable: false, autoOpen: false, width: 'auto', dialogClass: 'freshmail_dialog' });
				jQuery("#fm_popup_<?php echo $val->form_id; ?>").parent().css({position: "fixed"}).end().dialog('open');
				jQuery.post(static_var.ajax, {'action': 'popup_show', 'form_id':<?php echo $val->form_id; ?>});
			});
		</script>
	<?php
	}
}