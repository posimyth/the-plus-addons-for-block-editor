/*Draw Svg*/( function ( $ ) {	
	'use strict';
	$.fn.tpgb_plus_animated_svg = function() {
		return this.each(function() {
			var $self = $(this),
				data_id=$self.data("id"),
				data_duration=$self.data("duration"),
				data_type=$self.data("type"),
				data_stroke=$self.data("stroke"),
				fillenable=$self.data("fillenable"),
				fillcolor=$self.data("fillcolor");
				var drawSvg = new Vivus(data_id, {type: data_type, duration: data_duration,forceRender:false,start: 'inViewport',onReady: function (myVivus) {
					var c=myVivus.el.childNodes;
					var show_id=document.getElementById(data_id);
					if(fillenable!='' && fillenable=='yes'){
						myVivus.el.style.fillOpacity='0';
						myVivus.el.style.transition='fill-opacity 0s';
					}
					show_id.style.opacity = "1";
					if(data_stroke!=''){
						for (var i = 0; i < c.length; i++) {
							$(c[i]).attr("fill", fillcolor);
							$(c[i]).attr("stroke",data_stroke);
							var child=c[i];
							var pchildern=child.children;
							if(pchildern != undefined){
								for(var j=0; j < pchildern.length; j++){
									$(pchildern[j]).attr("fill", fillcolor);
									$(pchildern[j]).attr("stroke",data_stroke);
								}
							}
						}
					}
				}
				}, function (myVivus) {
					if(myVivus.getStatus() === 'end' && fillenable!='' && fillenable=='yes'){
						myVivus.el.style.fillOpacity='1';
						myVivus.el.style.transition='fill-opacity 1s';
					}
				} );
		});
	};
	
	$(document).ready(function() {
		$('.tpgb-draw-svg:not(.draw-svg-editor)').tpgb_plus_animated_svg();
		$('.tpgb-hover-draw-svg:not(.draw-svg-editor) .svg-inner-block').on("mouseenter",function() {
			var $self;
			$self = $(this).parent();
			var data_id=$self.data("id");
			var data_duration=$self.data("duration");
			var data_type=$self.data("type");
			new Vivus(data_id, {type: data_type, duration: data_duration,start: 'inViewport'}).reset().play();
		});
	});
} ( jQuery ) );