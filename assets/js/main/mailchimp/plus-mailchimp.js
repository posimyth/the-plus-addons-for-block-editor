( function( $ ) {
	"use strict";
		var mailchimp = $(".tpgb-mailchimp-form");
		if(mailchimp.length > 0){
			mailchimp.each(function(){
				var thisform = $(this),
					notverify='<span class="loading-spinner"><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>Error : API Key or List ID invalid. Please check that again in Plugin Settings.';
				thisform.on('submit',function(event){
					event.preventDefault();
					var mailchimpform = $(this),
					uid = '.tpgb-mail-'+mailchimpform.data('id'),
					MailOpt = mailchimpform.data('mail-option');
					
					$(uid+" .tpgb-notification").removeClass("not-verify danger-msg success-msg");
					$.ajax({
						type:"POST",						
						data:mailchimpform.serialize(),
						url:tpgb_load.ajaxUrl,
						beforeSend: function() {
							$(uid+" .tpgb-notification").fadeIn().animate({						
								opacity: 1
							  }, 200 );
							$(uid+" .tpgb-notification .subscribe-response").html(MailOpt.loading);
						},
						success:function(data){
							
							if(data=='not-verify'){
								$(uid+" .tpgb-notification").addClass("not-verify");
								$(uid+" .tpgb-notification .subscribe-response").html(notverify);
							}
							if(data=='incorrect'){
								$(uid+" .tpgb-notification").addClass("danger-msg");
								$(uid+" .tpgb-notification .subscribe-response").html(MailOpt.incorrect);
							}
							if(data=='pending'){
								$(uid+" .tpgb-notification").addClass("success-msg");
								$(uid+" .tpgb-notification .subscribe-response").html(MailOpt.pending);
							}
							if(data=='correct'){
								$(uid+" .tpgb-notification").addClass("success-msg");
								$(uid+" .tpgb-notification .subscribe-response").html(MailOpt.success);
							}
							$(uid+" .tpgb-notification").delay(2500).fadeOut().animate({						
								opacity: 0
							}, 2500 );
							
						}
					});
					return false;
				});
			});
		}
})(jQuery);