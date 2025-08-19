<?php
/**
 * TPGB Reusable Shortcode
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Tpgb_Resuable_Shortcode' ) ) {

	class Tpgb_Resuable_Shortcode {
		
		const TPGB_SHORTCODE = 'tpgb-reusable'; //Patterns Blocks
		
		/**
		 * Member Variable
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {
			$this->add_actions_shortcode();

			add_action('current_screen', [ $this, 'handle_current_screen' ]);
		}
		
		public function handle_current_screen() {
			if ( get_current_screen()->id != 'edit-wp_block' && get_current_screen()->post_type != 'wp_block'  ) {
				return;
			}
			
			$tpgbAjax = Tp_Blocks_Helper::get_extra_option('tpgb_template_load');
			if( (isset($tpgbAjax) && !empty($tpgbAjax) && $tpgbAjax=='enable') || empty($tpgbAjax) ){
				add_action( 'admin_footer', [ $this, 'tpgb_shortcode_popup'] );
			}
		}
		
		private function add_actions_shortcode(){
			if ( is_admin() ) {
				add_filter( 'manage_wp_block_posts_columns', [ $this, 'admin_columns_shortcode' ],15 );
				add_action( 'manage_wp_block_posts_custom_column', [ $this, 'admin_columns_shortcode_content' ], 15, 2 );
			}

			add_shortcode( self::TPGB_SHORTCODE, [ $this, 'create_shortcode' ] );
		}
		
		public function admin_columns_shortcode( $columns ) {
			$columns['tpgb_shortcode'] = __( 'Shortcode', 'the-plus-addons-for-block-editor');

			return $columns;
		}
	
		public function admin_columns_shortcode_content( $column, $post_id ) {
			if ( 'tpgb_shortcode' === $column ) {
				//translator %s = shortcode, %d = post_id
				$shortcode = sprintf( '[%s id="%d"]', self::TPGB_SHORTCODE, $post_id );
				printf( '<input type="text" class="nxt-shortcode-input" onfocus="this.select()" value="%s" readonly style="font-size: 12px;"/>', esc_attr( $shortcode ) );

				$tpgbAjax = Tp_Blocks_Helper::get_extra_option('tpgb_template_load');
				if( (isset($tpgbAjax) && !empty($tpgbAjax) && $tpgbAjax=='enable') || empty($tpgbAjax) ){
					printf( '<button class="tpgb-shcode-btn" data-resid="%d">'.esc_html__('AJAX Shortcodes', 'the-plus-addons-for-block-editor').'</button>' , esc_attr(  $post_id ));
				}
			}
		}
		
		public function create_shortcode( $option = [] ) {
			if ( empty( $option['id'] ) ) {
				return '';
			}

			$output = '';
			$loadClass = '';

			if(isset($option['tpgbajax']) && !empty($option['tpgbajax'])){
				$loadClass = 'tpgb-load-'.$option['id'].'-content';
				$output .= '<div class="'.esc_attr($loadClass).'" data-resuid="'.esc_attr($option['id']).'" style="min-height: 100px;flex-basis: 100%;">';
				$output .= '</div>';
				Tpgb_Library()->plus_do_block($option['id']);
				return $output;
			}

			if( isset($option['id']) && !empty($option['id']) ){
				if( class_exists('Tpgb_Library') ){
					ob_start();
					return Tpgb_Library()->plus_do_block($option['id']);
					ob_get_clean();
				}
			}
		}

		public function tpgb_shortcode_popup() {
			global $pagenow;
            if ( !empty($pagenow) && $pagenow == 'edit.php'  ) {
				self::tpgb_shcode_popup_html();

				self::tpgb_shcode_popup_css();
                self::tpgb_shcode_popup_js();
            }
		}

		public function tpgb_shcode_popup_html() {
			?>
				<div class="tpgb-modal" id="tpgb-deactive-modal">
					<div class="tpgb-modal-wrap">
						<?php 
							$copyicon = '<span class="copy-icon"><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_751_17557)"><path d="M12.5 5.625H6.875C6.18464 5.625 5.625 6.18464 5.625 6.875V12.5C5.625 13.1904 6.18464 13.75 6.875 13.75H12.5C13.1904 13.75 13.75 13.1904 13.75 12.5V6.875C13.75 6.18464 13.1904 5.625 12.5 5.625Z" stroke="#808489" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.125 9.375H2.5C2.16848 9.375 1.85054 9.2433 1.61612 9.00888C1.3817 8.77446 1.25 8.45652 1.25 8.125V2.5C1.25 2.16848 1.3817 1.85054 1.61612 1.61612C1.85054 1.3817 2.16848 1.25 2.5 1.25H8.125C8.45652 1.25 8.77446 1.3817 9.00888 1.61612C9.2433 1.85054 9.375 2.16848 9.375 2.5V3.125" stroke="#808489" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_751_17557"><rect width="15" height="15" fill="white"/></clipPath></defs></svg></span>';

						?>
						<div class="tpgb-modal-header">
							<svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="none"><rect width="44" height="44" fill="#fff" rx="4"/><g clip-path="url(#a)"><path fill="url(#b)" d="M15.6 28.4h-3.2V15.6h3.2v-3.2H9.2v19.2h6.4m1.8 1.6h3.3l6-22.4h-3.4m5.1 1.6v3.2h3.2v12.8h-3.2v3.2h6.4V12.4"/></g><defs><linearGradient id="b" x1="9.2" x2="42" y1="33.2" y2="33.2" gradientUnits="userSpaceOnUse"><stop stop-color="#1717cc"/><stop offset="1" stop-color="#8072FC"/></linearGradient><clipPath id="a"><path fill="#fff" d="M6 6h32v32H6z"/></clipPath></defs></svg>
							<div>
								<h3 class="tpgb-feed-head-title"><?php echo esc_html__( 'Regular Shortcodes', 'the-plus-addons-for-block-editor'); ?> </h3>
								<p class="tpgb-sc-desc"> <?php echo esc_html__( 'You can use below shortcodes to load this tempate load AJAX Way.', 'the-plus-addons-for-block-editor'); ?> </p>
							</div>
						</div>

						<div class="tpgb-modal-body">
							
							<h3 class="tpgb-feed-head-title"><?php echo esc_html__( 'Regular Shortcodes', 'the-plus-addons-for-block-editor'); ?> </h3>
							<p class="tpgb-sc-desc"> <?php echo esc_html__( 'You need to use this class in your block to load this as AJAX.', 'the-plus-addons-for-block-editor'); ?> </p>

							
							<label for="copy-input"> <?php echo esc_html__('Shortcode for Ajax render:', 'the-plus-addons-for-block-editor') ?></label>
							<div class="tpgb-shcode-wrap nrow-gap">
								<input type="text" id="tpgb-ajax-code" class="nxt-ajax-shcode" readonly/>
								<?php echo $copyicon; ?>
							</div>
							<div class="tpgb-shcode-class">
								<h3 class="tpgb-feed-head-title"><?php echo esc_html__( 'AJAX Class', 'the-plus-addons-for-block-editor'); ?> </h3>
								<p class="tpgb-sc-desc"> <?php echo esc_html__( 'You need to use this class in your block to load this as AJAX.', 'the-plus-addons-for-block-editor'); ?> </p>

								<div class="tpgb-shcode-wrap"> 
									<div class="tpgb-shcode-inner">  
										<span> <?php echo esc_html__('Hover trigger: ' ,'the-plus-addons-for-block-editor') ?> </span>
										<input id="tpgb-ajax-hover" type="text" id="" class="nxt-ajax-shcode" readonly/>
										<?php echo $copyicon; ?>
									</div>
									<div class="tpgb-shcode-inner">  
										<span> <?php echo esc_html__('Click trigger: ','the-plus-addons-for-block-editor') ?> </span>
										<input id="tpgb-ajax-click" type="text" id="" class="nxt-ajax-shcode" readonly/>
										<?php echo $copyicon; ?>
									</div>
									<div class="tpgb-shcode-inner">  
										<span> <?php echo esc_html__('On view trigger: ','the-plus-addons-for-block-editor') ?> </span>
										<input id="tpgb-ajax-view" type="text" id="" class="nxt-ajax-shcode" readonly/>
										<?php echo $copyicon; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
		}

		public function tpgb_shcode_popup_css() {
			?>
				<style type="text/css">
					.tpgb_shortcode,.tpgb-shcode-wrap {
						display: flex;
						flex-direction: column;
						row-gap: 15px;
						position: relative
					}

					.tpgb-shcode-wrap.nrow-gap {
						row-gap: 5px
					}

					.tpgb-shcode-btn {
						max-width: max-content;
						width: 100%;
						padding: 6px 18px;
						border: 0;
						background: #1717cc;
						color: #fff;
						border-radius: 2px;
						cursor: pointer
					}

					.tpgb-modal-body {
						padding: 30px
					}

					.tpgb-modal {
						position: fixed;
						z-index: 99999;
						top: 0;
						right: 0;
						bottom: 0;
						left: 0;
						backdrop-filter: blur(4px);
						display: none;
						box-sizing: border-box;
						overflow: scroll;
						opacity: 0;
						visibility: hidden;
						background: rgba(0,0,0,.25);
						transition: opacity .3s,visibility .3s,backdrop-filter .3s
					}

					.tpgb-modal.modal-active {
						display: block;
						opacity: 1;
						visibility: visible
					}

					.tpgb-modal-wrap {
						width: 100%;
						position: relative;
						top: 50%;
						left: 50%;
						transform: translate(-50%,-50%);
						background: #fff;
						max-width: 554px;
						border-radius: 5px;
						overflow: hidden;
						transition: transform .3s ease-in-out;
						transform-origin: center
					}

					.tpgb-modal-header {
						background: linear-gradient(93.13deg, #1717cc .37%, #0303a0 116.12%);
						color: #fff;
						display: flex;
						align-items: center;
						width: 100%;
						justify-content: flex-start;
						column-gap: 15px;
						padding: 12px
					}

					.tpgb-feed-head-title,p.tpgb-sc-desc {
						margin: 0;
						color: #fff;
						font-size: 15px;
						line-height: 20px
					}

					p.tpgb-sc-desc {
						font-size: 12px
					}

					.tpgb-modal-body .tpgb-feed-head-title {
						color: #000
					}

					.tpgb-modal-body p.tpgb-sc-desc {
						color: #626262;
						margin-bottom: 8px
					}

					.tpgb-shcode-inner {
						display: flex;
						align-items: center;
						justify-content: flex-start;
						column-gap: 35px;
						position: relative
					}

					.tpgb-shcode-inner span {
						flex-basis: 30%
					}

					.tpgb-shcode-inner input[type=text] {
						flex-basis: 74%%
					}

					.tpgb-shcode-class {
						padding: 20px;
						background: #f8fafc;
						margin-top: 20px
					}

					.nxt-ajax-shcode {
						display: block;
						width: 100%
					}
					.tpgb-modal-body label{
						line-height : 24px
					}
					.tpgb-modal-body label,.tpgb-shcode-inner span {
						color: #626262;
						font-size: 13px;
						font-weight: 400
					}

					.tpgb-modal-body input[type=text] {
						padding-right: 30px;
						position: relative;
						background: #f8fafc;
						border-radius : 4px;
						border: .5px solid #808489
					}

					.copy-icon {
						position: absolute;
						top: 50%;
						right: 10px;
						transform: translateY(-50%);
						cursor: pointer;
						transition: all .3s ease-in-out
					}

					.copy-icon {
						position: absolute;
						top: 50%;
						right: 2px;
						cursor: pointer;
						transform: translateY(-50%);
						width: 32px;
						height: 28px;
						display: flex;
						align-items: center;
						justify-content: center;
						border-radius: 0px 4px 4px 0px;
						background: #fff
					}

					.copy-icon:hover {
						background: #1717cc
					}

					.copy-icon:hover svg,.copy-icon:hover svg g,.copy-icon:hover svg path {
						stroke: #fff
					}
					.nrow-gap .copy-icon{
						right: 0.5px;
					}
				</style>
			<?php
		}

		public function tpgb_shcode_popup_js() { ?>
			<script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    'use strict';

                    // Modal Cancel Click Action
                    document.addEventListener('click', function(e) {
                        var modal = document.getElementById('tpgb-deactive-modal');
                        if (e.target === modal) {
                            modal.classList.remove('modal-active');
                        }
                    });

                    document.addEventListener('keydown', function(e) {
                        var modal = document.getElementById('tpgb-deactive-modal');
                        if (e.keyCode === 27) {
                            modal.classList.remove('modal-active');
                        }
                    });

					var shbtn = document.querySelectorAll('.tpgb-shcode-btn');
					if(shbtn){
						shbtn.forEach(function(ele) {


							ele.addEventListener('click', function(e) {
								e.preventDefault();
								
								var tempId = e.target.getAttribute('data-resid'),
								 	modal = document.getElementById('tpgb-deactive-modal'),
									ajaxcode = document.getElementById('tpgb-ajax-code'),
									hajcode = document.getElementById('tpgb-ajax-hover'),
									cliajcode = document.getElementById('tpgb-ajax-click'),
									viewajcode = document.getElementById('tpgb-ajax-view');

									if(ajaxcode){ ajaxcode.value = '[tpgb-reusable id="'+tempId+'" tpgbajax="1"]' }
									if(hajcode) { hajcode.value = 'tpgb-load-template-hover tpgb-load-'+tempId }
									if(cliajcode) { cliajcode.value = 'tpgb-load-template-click tpgb-load-'+tempId }
									if(viewajcode) { viewajcode.value = 'tpgb-load-template-view tpgb-load-'+tempId }
									
								modal.classList.add('modal-active');
							});
						})
					}

					const copyButtons = document.querySelectorAll('.copy-icon');
					copyButtons.forEach(function(button) {
						button.addEventListener('click', function() {
							const input = this.parentNode.querySelector('.nxt-ajax-shcode');
							copyToClipboard( this , input.value);
						});
					});

					function copyToClipboard(current , text) {
						navigator.clipboard.writeText(text)
							.then(function() {
								current.innerHTML = '<svg width="15" height="11" viewBox="0 0 15 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.7559 0.247833C15.0814 0.578276 15.0814 1.11403 14.7559 1.44448L5.58926 10.7522C5.26382 11.0826 4.73618 11.0826 4.41074 10.7522L0.244078 6.5214C-0.0813592 6.19095 -0.0813592 5.6552 0.244078 5.32476C0.569515 4.99431 1.09715 4.99431 1.42259 5.32476L5 8.9572L13.5774 0.247833C13.9028 -0.0826109 14.4305 -0.0826109 14.7559 0.247833Z" fill="white"/></svg>';

								setTimeout(function() {
									current.innerHTML = '<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_751_17557)"><path d="M12.5 5.625H6.875C6.18464 5.625 5.625 6.18464 5.625 6.875V12.5C5.625 13.1904 6.18464 13.75 6.875 13.75H12.5C13.1904 13.75 13.75 13.1904 13.75 12.5V6.875C13.75 6.18464 13.1904 5.625 12.5 5.625Z" stroke="#808489" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.125 9.375H2.5C2.16848 9.375 1.85054 9.2433 1.61612 9.00888C1.3817 8.77446 1.25 8.45652 1.25 8.125V2.5C1.25 2.16848 1.3817 1.85054 1.61612 1.61612C1.85054 1.3817 2.16848 1.25 2.5 1.25H8.125C8.45652 1.25 8.77446 1.3817 9.00888 1.61612C9.2433 1.85054 9.375 2.16848 9.375 2.5V3.125" stroke="#808489" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_751_17557"><rect width="15" height="15" fill="white"/></clipPath></defs></svg>'
								}, 500);
							})
							.catch(function(err) {
								console.error('Failed to copy text: ', err);
							});
					}

                });
		    </script>
		<?php
		}
	}
}

Tpgb_Resuable_Shortcode::get_instance();