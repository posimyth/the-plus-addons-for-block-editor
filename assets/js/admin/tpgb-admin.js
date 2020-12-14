(function ($) {
	"use strict";
	// Clear purge cache files styles and scripts
		var performace_cont = $('#cmb2-metabox-tpgb_performance');
		if(performace_cont.length > 0){
			var ids="tpgb-remove-smart-cache";
			var smart_action ='';
			
			if(performace_cont.length > 0){
				
				performace_cont.append('<div class="cmb-th"><label for="plus_smart_performance">Cache Manager</label></div>');
				performace_cont.append('<div class="cmb-td"><p class="cmb2-metabox-description">This is auto enabled functionality of The Plus Addons. In this functionality We use Unity method to combine all JS and CSS of page in two separate files for the best possible performance with less requests. All Cache stored at "SiteURL/wp-content/uploads/theplus_gutenberg/".</p><a href="#" id="'+ids+'" class="tpgb-smart-cache-btn">Purge All Cache</a><div class="smart-performace-desc-btn">* Use above button to delete all cache our plugin have generated. It will start creating cache once some one start visiting your website.</div></div>');
				
				smart_action = "tpgb_all_perf_clear_cache";
			}
			
			$("#"+ids).on("click", function(e) {
				e.preventDefault();
				if(performace_cont.length > 0){
					var confirmation = confirm("Are you sure want to remove all cache files? It will remove all cached JS and CSS files from your server. It will generate automatically on your next visit of page.?");
				}
				if (confirmation) {
					var $this = $(this);
					$.ajax({
						url: tpgb_admin.ajax_url,
						type: "post",
						data: {
							action: smart_action,
							security: tpgb_admin.tpgb_nonce
						},
						beforeSend: function() {
							$this.html(
								'<svg id="plus-spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><circle cx="24" cy="4" r="4" fill="#fff"/><circle cx="12.19" cy="7.86" r="3.7" fill="#fffbf2"/><circle cx="5.02" cy="17.68" r="3.4" fill="#fef7e4"/><circle cx="5.02" cy="30.32" r="3.1" fill="#fef3d7"/><circle cx="12.19" cy="40.14" r="2.8" fill="#feefc9"/><circle cx="24" cy="44" r="2.5" fill="#feebbc"/><circle cx="35.81" cy="40.14" r="2.2" fill="#fde7af"/><circle cx="42.98" cy="30.32" r="1.9" fill="#fde3a1"/><circle cx="42.98" cy="17.68" r="1.6" fill="#fddf94"/><circle cx="35.81" cy="7.86" r="1.3" fill="#fcdb86"/></svg><span style="margin-left: 5px;">Removing Purge...</span>'
							);
						},
						success: function(response) {
							if(performace_cont.length > 0){
								setTimeout(function() {
									$this.html("Purge All Cache");
								}, 100);
							}
						},
						error: function() {
						}
					});
				}
			});
		}
		
		/*Welcome page FAQ*/
		$('.tpgb-welcome-faq .tpgb-faq-section .faq-title').on('click',function() {
			var $parent = $(this).closest('.tpgb-faq-section');
			var $btn = $parent.find('.faq-icon-toggle')
			$parent.find('.faq-content').slideToggle();
			$parent.toggleClass('faq-active');
		});
		/*Welcome page FAQ*/
		/*Plus block Listing*/
		$('#block_check_all').on('click', function() {
				$('.plus-block-list input:checkbox:enabled').prop('checked', $(this).prop('checked'));
			if(this.checked){
				$(this).closest(".panel-block-check-all").addClass("active-all");
			}else{
				$(this).closest(".panel-block-check-all").removeClass("active-all");
			}
		});
		$( ".panel-block-filters .blocks-filter" ).on('change',function () {
			var selected = $( this ).val();
			var block_filter = $(".plus-block-list .tpgb-panel-col");
			if(selected!='all'){
				block_filter.removeClass('is-animated')
					.fadeOut(5).promise().done(function() {
					  block_filter.filter(".block-"+selected)
						.addClass('is-animated').fadeIn();
					});
			}else if(selected=='all'){
				block_filter.removeClass('is-animated')
					.fadeOut(5).promise().done(function() {
						block_filter.addClass('is-animated').fadeIn();
					});
			}
		});
		
		var timeoutID = null;
		
		function tpgb_block_filter(search) {
			$.ajax({
				url: tpgb_admin.ajax_url,
				type: "post",
				data: {
					action: 'tpgb_block_search',
					filter: search,
					security: tpgb_admin.tpgb_nonce
				},
				beforeSend: function() {
					
				},
				success: function(response) {
					if(response!=''){
						$(".plus-block-list").empty();
						$(".plus-block-list").append(response);
					}
					$( ".panel-block-filters .blocks-filter" ).change();
				}
			});
		}
		$( ".tpgb-block-filters-search .block-search" ).on('keyup',function( e ) {
			clearTimeout(timeoutID);
			timeoutID = setTimeout(tpgb_block_filter.bind(undefined, e.target.value), 350);
			//var search = $(this).val();
		});
		/*Plus block Listing*/
		
})(window.jQuery);