/*slick carousel*/
( function( $ ) {
	"use strict";
	if( $(".tpgb-carousel").length > 0 ) {
		$(".tpgb-carousel").each(function(i){
			tpgb_carousel_list(this);
		})
	}
})( jQuery );
function tpgb_carousel_list(element) {
	var $=jQuery,
	current = $(element),
	con_id = current.data('id'),
	settings = current.data('carousel-option');
	
	if(settings =='' && settings==undefined){
		return;
	}
	
	var rtl_crl=false;
	if (document.dir == "rtl" && slider_direction!=true){
		rtl_crl=true;
	}
	var prev_arrow='';
	var next_arrow='';
	if(settings.arrowsStyle!=undefined && settings.arrowsStyle=='style-1'){
		prev_arrow='<button type="button" class="slick-nav slick-prev '+settings.arrowsStyle+'"></button>';
		next_arrow='<button type="button" class="slick-nav slick-next '+settings.arrowsStyle+'"></button>';
	}
	
	var args = {
		slidesToShow: (Number(settings.slidesToShow.md)) ? Number(settings.slidesToShow.md) : 4,
		slidesToScroll: (Number(settings.slidesToScroll.md) !== '') ? Number(settings.slidesToShow.md) : 1,
		initialSlide: (Number(settings.initialSlide) !== '') ? Number(settings.initialSlide) : 1,
		speed: (Number(settings.speed)) ? Number(settings.speed) : 1500,
		draggable : (settings.draggable.md) ? true : false,
		infinite: (settings.infinite.md) ? true : false,
		pauseOnHover: false,
		adaptiveHeight: false,
		autoplay: (settings.autoplay.md) ? true : false,
		autoplaySpeed: (Number(settings.autoplaySpeed.md)) ? Number(settings.autoplaySpeed.md) : 1500,
		vertical : false,
		verticalSwiping : false,
		swipeToSlide: false,
		dots: (settings.dots.md) ? settings.dots.md : false,
		dotsClass: (settings.dotsStyle) ? 'slick-dots '+settings.dotsStyle : 'slick-dots style-1',
		centerMode: false,
		rows : 1,
		arrows: (settings.arrows.md) ? settings.arrows.md : false,
		prevArrow: prev_arrow,
		nextArrow: next_arrow,
		rtl : rtl_crl,
		responsive: [
			{
			  breakpoint: 1024,
			  settings: {
				slidesToShow: (settings.slidesToShow.sm===undefined) ? Number(settings.slidesToShow.md) : (Number(settings.slidesToShow.sm)) ? Number(settings.slidesToShow.sm) : 3,
			  	slidesToScroll: (settings.slidesToScroll.sm===undefined) ? Number(settings.slidesToScroll.md) : (Number(settings.slidesToScroll.sm)  !== 1) ? Number(settings.slidesToShow.sm) : 1,
			  	draggable : (settings.draggable.sm===undefined) ? settings.draggable.md : (settings.draggable.sm) ? true : false,
				infinite: (settings.infinite.sm===undefined) ? settings.infinite.md : (settings.infinite.sm) ? true : false,
				autoplay: (settings.autoplay.sm===undefined) ? settings.autoplay.md : (settings.autoplay.sm) ? true : false,
			  	autoplaySpeed: (settings.autoplaySpeed.sm===undefined) ? Number(settings.autoplaySpeed.md) : (Number(settings.autoplaySpeed.sm)) ? Number(settings.autoplaySpeed.sm) : 1500,
				dots: (settings.dots.sm===undefined) ? settings.dots.md : (settings.dots.sm) ? settings.dots.sm : false,
				centerMode: false,
				arrows: (settings.arrows.sm===undefined) ? settings.arrows.md : (settings.arrows.sm) ? settings.arrows.sm : false,
			  }
			},
			{
			  breakpoint: 767,
			  settings: {
				slidesToShow: (settings.slidesToShow.xs===undefined) ? ((settings.slidesToShow.sm===undefined) ? Number(settings.slidesToShow.md) : Number(settings.slidesToShow.sm)) : (Number(settings.slidesToShow.xs)) ? Number(settings.slidesToShow.xs) : 2,
			  	slidesToScroll: (settings.slidesToScroll.xs===undefined) ? ((settings.slidesToScroll.sm===undefined) ? Number(settings.slidesToScroll.md) : Number(settings.slidesToScroll.sm)) : (Number(settings.slidesToScroll.xs)  !== 1) ? Number(settings.slidesToShow.xs) : 1,
			  	draggable : (settings.draggable.xs===undefined) ? ((settings.draggable.sm===undefined) ? settings.draggable.md : settings.draggable.sm) : (settings.draggable.xs) ? true : false,
				infinite: (settings.infinite.xs===undefined) ? ((settings.infinite.sm===undefined) ? settings.infinite.md : settings.infinite.sm) : (settings.infinite.xs) ? true : false,
				autoplay: (settings.autoplay.xs===undefined) ? ((settings.autoplay.sm===undefined) ? settings.autoplay.md : settings.autoplay.sm) : (settings.autoplay.xs) ? true : false,
			  	autoplaySpeed: (settings.autoplaySpeed.xs===undefined) ? ((settings.autoplaySpeed.sm===undefined) ? Number(settings.autoplaySpeed.md) : Number(settings.autoplaySpeed.sm)) : (Number(settings.autoplaySpeed.xs)) ? Number(settings.autoplaySpeed.xs) : 1500,
				dots: (settings.dots.xs===undefined) ? ((settings.dots.sm===undefined) ? settings.dots.md : settings.dots.sm) : (settings.dots.xs) ? settings.dots.xs : false,
				centerMode: false,
				arrows: (settings.arrows.xs===undefined) ? ((settings.arrows.sm===undefined) ? settings.arrows.md : settings.arrows.sm) : (settings.arrows.xs) ? settings.arrows.xs : false,
			  }
			}
		]
	}
	$(' > .post-loop-inner',current).on('init reInit afterChange', function(e, slick, currentSlide, nextSlide) {
		$(this).attr('nosilde' , slick.slideCount );
	});

	$('> .post-loop-inner',current).slick(args);
}