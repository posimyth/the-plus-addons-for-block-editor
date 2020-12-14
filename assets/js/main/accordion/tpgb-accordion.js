( function( $ ) {
	"use strict";
		$('.tpgb-accordion-wrapper').each(function(){
		var $this =  $(this),
			$type = $this.data('type'),
			$accrodionList = $this.find('.tpgb-accordion-item'),
			$PlusAccordionListHeader = $accrodionList.find('.tpgb-accordion-header');
		
			$accrodionList.each(function(i) {
				if( $(this).find($PlusAccordionListHeader).hasClass('active-default') ) {
					$(this).find($PlusAccordionListHeader).removeClass('active-default').addClass('active');
					$(this).find('.tpgb-accordion-content').slideToggle( 300, function() {
						$(this).toggleClass('active');
					});
				}
			});
		
		if( 'accordion' == $type ) {
			$PlusAccordionListHeader.on('click', function() {
				if( $(this).hasClass('active') ) {
					$(this).removeClass('active');
					$(this).next('.tpgb-accordion-content').removeClass('active').slideUp(300);
				}else {
					$PlusAccordionListHeader.removeClass('active');
					$PlusAccordionListHeader.next('.tpgb-accordion-content').removeClass('active').slideUp(300);
			
					$(this).toggleClass('active');
					$(this).next('.tpgb-accordion-content').slideToggle( 300, function() {
						$(this).toggleClass('active');
					});

				}
			});			
		}
		
	});
	var hash = window.location.hash;
	if(hash!='' && hash!=undefined && !$(hash).hasClass("active") && $(hash).length){
		$('html, body').animate({
			scrollTop: $(hash).offset().top
		}, 1500);
		$(hash+".tpgb-accordion-header").trigger("click");
	}
})(jQuery);