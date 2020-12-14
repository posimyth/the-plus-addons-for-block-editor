( function( $ ) {
	"use strict";
	var rowStretch = $(".tpgb-section-stretch-row");
	if(rowStretch.length > 0){
		tpgb_rowStretch();
		$(window).resize(function() {
			tpgb_rowStretch();
		});
	}
	function tpgb_rowStretch(){
		$(".tpgb-section-stretch-row").each(function(){
			var $this=$(this),
				window_width = $(window).width();
		
			if($('body').hasClass("rtl")){
				$this.css({
					right: 0,
				});
				var offset_left = 0 - $this.offset().left;
				$this.css({
					right: offset_left,
					width: window_width
				});
			}else{
				$this.css({
					left: 0,
				});
				var offset_left = 0 - $this.offset().left;
				$this.css({
					left: offset_left,
					width: window_width
				});
			}
		});
	}
})(jQuery);