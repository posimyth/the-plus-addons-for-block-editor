( function( $ ) {
	"use strict";
		$('.tpgb-stylist-list').each(function(){
			if($(this).hasClass('hover-inverse-effect')){
				$('.tpgb-icon-list-items > .tpgb-icon-list-item',this).on({
					mouseenter: function () {
						$(this).closest(".tpgb-icon-list-items").addClass("on-hover");
					},
					mouseleave: function () {
						$(this).closest(".tpgb-icon-list-items").removeClass("on-hover");
					}
				});
			}
		});
})(jQuery);