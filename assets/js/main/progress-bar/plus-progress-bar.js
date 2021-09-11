( function( $ ) {
	"use strict";
	$(document).ready(function(){
		var progressbar = $('.tpgb-progress-bar');
		if( progressbar.length > 0 ){
			progressbar.each(function(){
				var b=$(this);
				if(!b.hasClass('tpgb-piechart')){
					var e= $(this).find(".progress-bar-skill-bar-filled"),
						width = e.data("width");
						
					b.waypoint(function(direction) {
						if (direction === 'down') {
							if(!b.hasClass("done-progress")){
								e.css("width", width);
								if(b.find(".progress-bar-media.large")){
									b.find(".progress-bar-media.large").css("width", width);
									
								}
								b.addClass("done-progress");
							}
						}
					}, { offset: '90%' });
				}
			});
		}
		
		if($(".tpgb-progress-bar.tpgb-piechart").length){
			var elements = document.querySelectorAll(".tpgb-progress-bar.tpgb-piechart");
			Array.prototype.slice.apply(elements).forEach(function(el) {
				var $el = $(el);
				new Waypoint({
					element: el,
					handler: function() {
						if(!$el.hasClass("done-progress")){
							setTimeout(function(){
								$el.circleProgress({
									value: $el.data("value"),
									emptyFill: $el.data("emptyfill"),
									startAngle: -Math.PI/4*2,
								});
							}, 800);
							$el.addClass("done-progress");
						}
					},
					offset: "80%"
				});
			});
		}
		
	});
	
	$(window).on("load resize scroll", function(){
		if($(".tpgb-progress-bar.tpgb-piechart").length && !$('.edit-post-visual-editor').length){
			$(".tpgb-progress-bar.tpgb-piechart").each( function(){
				var height=$("canvas",this).outerHeight(),
					width=$("canvas",this).outerWidth();
				$(".tp-pie-circle",this).css("height",height+"px");
				$(".tp-pie-circle",this).css("width",width+"px");
			});
		}
	});
})(jQuery);