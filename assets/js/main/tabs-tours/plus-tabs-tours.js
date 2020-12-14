( function( $ ) {
	"use strict";
		$('.tpgb-tabs-wrapper').each(function(){
			var $currentTab = $(this),
			$TabHover = $currentTab.data('tab-hover'),
			$tabheader = $currentTab.find('.tpgb-tab-header');

			if('no' == $TabHover){
				$tabheader.on('click',function(){
					var currentTabIndex = $(this).data("tab");
					var tabsContainer = $(this).closest('.tpgb-tabs-wrapper');
					var tabsNav = $(tabsContainer).children('ul.tpgb-tabs-nav').children('li').children('.tpgb-tab-header');
					var tabsContent = $(tabsContainer).children('.tpgb-tabs-content-wrapper').children('.tpgb-tab-content');
				
					$(tabsContainer).find(">.tpgb-tabs-nav-wrapper .tpgb-tab-header").removeClass('active default-active').addClass('inactive');
					$(this).addClass('active').removeClass('inactive');
					
					$(tabsContainer).find(">.tpgb-tabs-content-wrapper>.tpgb-tab-content").removeClass('active').addClass('inactive');
					$(">.tpgb-tabs-content-wrapper>.tpgb-tab-content[data-tab='"+currentTabIndex+"']",tabsContainer).addClass('active').removeClass('inactive');
				
					$(tabsContent).each( function(index) {
						$(this).removeClass('default-active');
					});
					if($(">.tpgb-tabs-content-wrapper>.tpgb-tab-content[data-tab='"+currentTabIndex+"'] .pt_tpgb_before_after",tabsContainer).length){
						size_Elements()
					}
				});
			}
			
		});
		
})(jQuery);