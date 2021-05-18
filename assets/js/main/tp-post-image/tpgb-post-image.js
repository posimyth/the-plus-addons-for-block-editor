( function( $ ) {
	"use strict";
	var postImage = $(".tpgb-post-image.post-img-bg");
	$.fn.tpOuterHTML = function() {
	  return $('<div />').append(this.eq(0).clone()).html();
	};
	if(postImage.length > 0){
		postImage.each(function(){
			var $this = $(this),
				setting = $this.data('setting');
			if( setting.imgType == 'background' && setting.imgLocation == 'section' ){
				$this.closest('.tpgb-section').prepend($this.tpOuterHTML());
				$this.remove();
			}else if( setting.imgType == 'background' && setting.imgLocation == 'column' ){
				$this.closest('.tpgb-column').prepend($this.tpOuterHTML());
				$this.remove();
			}
		});
	}
})(jQuery);