/*
 *
 *  _____ ____  _____ ____  _   _ __  __    _    ___ _       ____ ___  __  __
 * |  ___|  _ \| ____/ ___|| | | |  \/  |  / \  |_ _| |     / ___/ _ \|  \/  |
 * | |_  | |_) |  _| \___ \| |_| | |\/| | / _ \  | || |    | |  | | | | |\/| |
 * |  _| |  _ <| |___ ___) |  _  | |  | |/ ___ \ | || |___ | |__| |_| | |  | |
 * |_|   |_| \_\_____|____/|_| |_|_|  |_/_/   \_\___|_____(_)____\___/|_|  |_|
 *
 *
 * FreshMail is a browser-based software for sending and tracking
 * email marketing campaigns. FreshMail is absolutely free if you send
 * less than 2 000 e-mails up to 500 subscribers per month.
 *
 * Visit: http://freshmail.com/
 *
 */

// user
jQuery(document).ready(function($){
	$('.form_subscribe').submit(function(){
		var fm_form_id = '.freshmail_form_' + $(this).find('input[name=fm_form_id]').val();


		var btn_val = $(this).find('button.form_subscribe_button').html();
		$(fm_form_id).find('button.form_subscribe_button').html("Sending...");
		$(fm_form_id).find('button.form_subscribe_button').css("opacity", "0.6");
		$(fm_form_id).find('button.form_subscribe_button').attr("disabled", "disabled");
		$.post(static_var.ajax, $(this).serialize(), function(data){
			var obj = $.parseJSON(data);
			$(fm_form_id).find('button.form_subscribe_button').html(btn_val);
			$(fm_form_id).find('button.form_subscribe_button').css("opacity", "1");
			$(fm_form_id).find('button.form_subscribe_button').removeAttr("disabled");
			if(obj.status == "success"){

				$(fm_form_id).html('<span class="message_' + obj.status + '">' + obj.message + '</span>');
				if(obj.redirect != 0){
					setTimeout(function(){
						document.location = obj.redirect;
					}, 2000);
				}
			}
			if(obj.status == "error"){
				$('.message_error').remove();
				$(fm_form_id).prepend('<span class="message_' + obj.status + '">' + obj.message + '</span>');
			}
		});
		return false;
	})
});