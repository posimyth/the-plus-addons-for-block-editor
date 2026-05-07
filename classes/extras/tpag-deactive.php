<?php
/**
 * TPGB Deactive Popup
 *
 * @since 3.2.8
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Tpgb_Deactive' ) ) {

	/**
	 * Tpgb_ Deactive.
	 *
	 * @since 1.0.0
	 */
	class Tpgb_Deactive {

		/**
		 * Member Variable
		 */ // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing
		private static $instance; // phpcs:ignore Squiz.Commenting.VariableComment.Missing,Squiz.Commenting.VariableComment.MissingVar

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			add_action(
				'current_screen',
				function () {
					if ( ! in_array( get_current_screen()->id, array( 'plugins', 'plugins-network' ), true ) ) {
						return;
					}

					add_action( 'admin_footer', array( $this, 'tpgb_deactive_popup' ) );
				}
			);

			add_action( 'wp_ajax_tpgb_deactive_plugin', array( $this, 'tpgb_deactive_plugin' ) );
			add_action( 'wp_ajax_tpgb_skip_deactivate', array( $this, 'tpgb_skip_deactivate' ) );
		}

		/**
		 * Tpgb check white label.
		 *
		 * @return mixed The result.
		 */
		public function tpgb_check_white_label() {
			if ( defined( 'TPGBP_VERSION' ) ) {
				$label_options = get_option( 'tpgb_white_label' );

				if ( ! empty( $label_options ) && is_array( $label_options ) ) {
					foreach ( $label_options as $key => $val ) {
						if ( ! empty( $val ) && 'hidden' !== $val ) {
							return false;
						}
					}
				}
			}
			return true;
		}

		/**
		 *  Popup Html Css Js
		 */
		public function tpgb_deactive_popup() {
			global $pagenow;
			if ( ! empty( $pagenow ) && 'plugins.php' === $pagenow && $this->tpgb_check_white_label() ) {
				$this->tpgb_deact_popup_html();

				$this->tpgb_deact_popup_css();
				$this->tpgb_deact_popup_js();
			}
		}

		/**
		 *  Popup Html Code
		 */
		public function tpgb_deact_popup_html() {

			$security = wp_create_nonce( 'tpgb-deactivate-feedback' );
			?>
			<div class="tpgb-modal" id="tpgb-deactive-modal">
				<div class="tpgb-modal-wrap">

					<div class="tpgb-modal-body">
						<h3 class="tpgb-feed-caption"><?php echo esc_html__( 'Deactivation Reason', 'the-plus-addons-for-block-editor' ); ?></h3>
						<form class="tpgb-feedback-dialog-form" method="post">

							<input type="hidden" name="nonce" value="<?php echo esc_attr( $security ); ?>" />
							<div class="tpgb-modal-input">
								<?php
									$reson_data = array(
										array(
											'reason' => __( 'Just Debugging.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><g stroke="#1717CC" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.667" clip-path="url(#a)"><path d="M10 18.333a8.333 8.333 0 1 0 0-16.666 8.333 8.333 0 0 0 0 16.666ZM8.333 12.5v-5M11.667 12.5v-5"/></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h20v20H0z"/></clipPath></defs></svg>',
										),
										array(
											'reason' => __( 'Plugin Issue.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="#1717CC" d="M10.179 2.771a3.601 3.601 0 0 1 3.42 3.596l.113.007a.9.9 0 0 1 .273.08l2.73-1.745.08-.046a.9.9 0 0 1 .89 1.562L14.97 7.961c.244.623.391 1.283.428 1.956l.002.05h2.7l.092.004a.9.9 0 0 1 0 1.791l-.092.005h-2.7v.9l-.006.268a5.405 5.405 0 0 1-.172 1.103l2.44 1.457.076.05a.9.9 0 0 1-.918 1.537l-.082-.042-2.264-1.353a5.402 5.402 0 0 1-8.95.001L3.261 17.04l-.461-.773-.462-.772 2.44-1.457a5.403 5.403 0 0 1-.178-1.372v-.899H1.9a.901.901 0 0 1 0-1.8h2.7v-.05l.038-.42a6.301 6.301 0 0 1 .391-1.536L2.314 6.225l-.075-.054a.9.9 0 0 1 1.045-1.463l2.73 1.747a.9.9 0 0 1 .274-.081l.111-.007A3.602 3.602 0 0 1 10 2.767l.179.004ZM3.26 17.04a.9.9 0 0 1-.923-1.545l.923 1.545Zm3.652-8.873a4.499 4.499 0 0 0-.514 1.837v2.662a3.602 3.602 0 0 0 2.7 3.486v-4.385a.9.9 0 0 1 1.8 0v4.385a3.602 3.602 0 0 0 2.697-3.307l.004-.179V9.995a4.496 4.496 0 0 0-.514-1.829H6.913ZM10 4.566a1.802 1.802 0 0 0-1.8 1.8h3.6l-.009-.178a1.8 1.8 0 0 0-1.613-1.613L10 4.566Z"/></svg>',
										),
										array(
											'reason' => __( 'Slow Performance.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="#1717CC" d="M2.8 10.931c0 1.99.806 3.79 2.109 5.091l-1.272 1.272A8.972 8.972 0 0 1 1 10.931a9 9 0 0 1 9-9 9 9 0 0 1 6.364 15.364l-1.273-1.273A7.2 7.2 0 1 0 2.8 10.932Zm4.236-4.236 4.05 4.05-1.272 1.272-4.05-4.05 1.272-1.272Z"/></svg>',
										),
										array(
											'reason' => __( 'Switched to Alternative.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="#1717CC" d="M5.532 9.195a.809.809 0 0 1 0 1.61l-.083.003H3.252a5.58 5.58 0 0 0 6.222 2.772l.352-.097a5.562 5.562 0 0 0 3.681-3.716.81.81 0 0 1 1.55.465 7.181 7.181 0 0 1-1.265 2.415l4.97 4.972.056.061a.81.81 0 0 1-1.137 1.14l-.062-.056-4.972-4.973a7.183 7.183 0 0 1-2.794 1.361v.001a7.199 7.199 0 0 1-7.236-2.406v.893a.808.808 0 1 1-1.617 0V10l.004-.083a.809.809 0 0 1 .805-.726h3.64l.083.004ZM6.506 1.2a7.199 7.199 0 0 1 7.235 2.406V2.72a.81.81 0 0 1 1.619 0v3.64a.81.81 0 0 1-.81.809h-3.64a.81.81 0 0 1 0-1.617h2.201a5.583 5.583 0 0 0-6.226-2.78h-.002a5.565 5.565 0 0 0-3.919 3.474l-.115.346a.81.81 0 0 1-1.551-.463l.071-.225a7.18 7.18 0 0 1 5.137-4.705v.001Z"/></svg>',
										),
										array(
											'reason' => __( 'No Longer Needed.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="#1717CC" d="M16.566 1.914a2.7 2.7 0 0 1 1.643 4.595c-.287.287-.633.5-1.009.633v8.259a2.701 2.701 0 0 1-2.7 2.7h-9a2.704 2.704 0 0 1-2.688-2.433L2.8 15.4V7.143a2.7 2.7 0 0 1-1.01-.634 2.701 2.701 0 0 1-.777-1.641L.999 4.6a2.702 2.702 0 0 1 2.7-2.7h12.6l.267.014ZM4.6 15.4l.004.089a.903.903 0 0 0 .896.811h9a.903.903 0 0 0 .9-.9V7.3H4.6v8.1Zm7.292-6.296a.9.9 0 0 1 0 1.791l-.092.005H8.2a.9.9 0 0 1 0-1.8h3.6l.092.004ZM3.699 3.701a.9.9 0 0 0-.9.9l.005.088a.902.902 0 0 0 .895.811h12.6l.09-.004A.901.901 0 0 0 17.2 4.6a.9.9 0 0 0-.811-.895l-.09-.004H3.7Z"/></svg>',
										),
										array(
											'reason' => __( 'Compatibility Issue.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="#1717CC" fill-rule="evenodd" d="M19 10a9 9 0 0 1-9 9 9 9 0 0 1-9-9 9 9 0 0 1 9-9 9 9 0 0 1 9 9Zm-9 7.2a7.2 7.2 0 1 0 0-14.4 7.2 7.2 0 0 0 0 14.4Z" clip-rule="evenodd"/><path fill="#1717CC" fill-rule="evenodd" d="M16.036 4.414a.9.9 0 0 1 0 1.272l-10.35 10.35a.9.9 0 0 1-1.272-1.272l10.35-10.35a.9.9 0 0 1 1.272 0Z" clip-rule="evenodd"/></svg>',
										),
										array(
											'reason' => __( 'Missing Feature.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="#1717CC" d="M17.363 10a1.154 1.154 0 0 0-.263-.734l-.075-.084-1.377-1.376a1.636 1.636 0 0 1 .774-2.749l.157-.048a1.23 1.23 0 0 0 .408-.26l.11-.12a1.23 1.23 0 0 0-.093-1.633 1.228 1.228 0 0 0-2.012.426l-.049.155a1.638 1.638 0 0 1-2.585.919l-.164-.143-1.376-1.377a1.157 1.157 0 0 0-1.551-.077l-.085.077-1.378 1.376h.001l.184.05A2.864 2.864 0 1 1 4.404 7.99l-.051-.184-1.378 1.377a1.158 1.158 0 0 0-.338.818l.006.114a1.157 1.157 0 0 0 .331.703h.001l1.377 1.377.144.163a1.636 1.636 0 0 1-.92 2.585h.001a1.228 1.228 0 0 0-.024 2.381 1.228 1.228 0 0 0 1.504-.9 1.637 1.637 0 0 1 2.748-.775l1.377 1.376.085.077a1.16 1.16 0 0 0 .733.262l.113-.005a1.16 1.16 0 0 0 .705-.334l1.377-1.376a2.864 2.864 0 1 1 3.401-3.637l.05.183v.001h.002l1.377-1.377.075-.084a1.156 1.156 0 0 0 .263-.734ZM19 10a2.793 2.793 0 0 1-.634 1.771l-.185.204-1.377 1.375.001.001a1.638 1.638 0 0 1-2.75-.775v-.001a1.227 1.227 0 1 0-1.479 1.482l.207.064a1.637 1.637 0 0 1 .712 2.52l-.143.165-1.377 1.375a2.794 2.794 0 0 1-1.7.805l-.275.013a2.793 2.793 0 0 1-1.772-.633l-.203-.184-1.377-1.377v-.001a2.864 2.864 0 1 1-3.636-3.402l.184-.05-1.377-1.376v-.001a2.793 2.793 0 0 1-.805-1.701L1 10a2.793 2.793 0 0 1 .82-1.975l1.376-1.377a1.638 1.638 0 0 1 2.337.023c.202.21.344.47.412.753l.047.155a1.228 1.228 0 0 0 2.326-.776 1.227 1.227 0 0 0-.739-.81l-.155-.05a1.636 1.636 0 0 1-.776-2.748l1.377-1.376.203-.184a2.793 2.793 0 0 1 3.747.184l1.377 1.377.051-.185a2.861 2.861 0 0 1 4.759-1.171 2.864 2.864 0 0 1 .092 3.952l-.134.137a2.863 2.863 0 0 1-1.132.67l-.184.05 1.377 1.376.185.203A2.796 2.796 0 0 1 19 10Z"/></svg>',
										),
										array(
											'reason' => __( 'Other Reasons.', 'the-plus-addons-for-block-editor' ),
											'svg'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"><path fill="#1717CC" d="M10 1a9 9 0 0 1 9 9 9 9 0 0 1-9 9 9 9 0 0 1-9-9 9 9 0 0 1 9-9Zm0 1.8a7.2 7.2 0 1 0 0 14.4 7.2 7.2 0 0 0 0-14.4Zm0 10.8a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8Zm0-8.55a3.263 3.263 0 0 1 1.213 6.291.72.72 0 0 0-.274.18c-.04.046-.046.103-.045.163l.006.116a.9.9 0 0 1-1.794.105L9.1 11.8v-.225c0-1.038.837-1.66 1.444-1.904a1.463 1.463 0 1 0-2.007-1.358.9.9 0 1 1-1.8 0A3.262 3.262 0 0 1 10 5.05Z"/></svg>',
										),
									);
									foreach ( $reson_data as $key => $value ) {
										?>
										<div class="nxt-reason-item" tabindex="0" >
											<label class="tpgb-relist">
												<span class="nxt-reason-svg">
													<?php
													if ( ! empty( $value['svg'] ) ) {
														echo $value['svg'];  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													}
													?>
												</span>
												<div class="tpgb-reason-text"><?php echo esc_html( $value['reason'] ); ?></div>
												
											</label>
										</div>
								<?php } ?>
							</div>
							<textarea name="tpgb-reason-txt" placeholder="<?php echo esc_html__( 'Please share more details', 'the-plus-addons-for-block-editor' ); ?>" class="tpgb-reason-deails" rows="3"></textarea>
							<div class="tpgb-help-link">                                 
								<span><?php echo esc_html__( 'If you require any help, please', 'the-plus-addons-for-block-editor' ); ?></span>                                 
								<span> <a href="
								<?php
								if ( defined( 'TPGBP_VERSION' ) ) {
									echo esc_url( 'https://store.posimyth.com/helpdesk/?utm_source=wpbackend&utm_medium=admin&utm_campaign=links' );
								} else {
									echo esc_url( 'https://wordpress.org/support/plugin/the-plus-addons-for-block-editor/' ); }
								?>
								" target="_blank" rel="noopener noreferrer" > <?php echo esc_html__( 'Create A Ticket.', 'the-plus-addons-for-block-editor' ); ?> </a> <?php echo esc_html__( 'We reply within 24 working hours.', 'the-plus-addons-for-block-editor' ); ?></span>                                 
								<span> <?php echo esc_html__( 'Looking for instant solutions? Read our ', 'the-plus-addons-for-block-editor' ); ?><a href="<?php echo esc_url( 'https://nexterwp.com/docs/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage' ); ?>" target="_blank" rel="noopener noreferrer" ><?php echo esc_html__( 'Documentation', 'the-plus-addons-for-block-editor' ); ?></a><?php echo esc_html__( ' or ', 'the-plus-addons-for-block-editor' ); ?><a href="<?php echo esc_url( 'https://nexterwp.com/chat/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage' ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Ask AI', 'the-plus-addons-for-block-editor' ); ?></a>. </span>                              
							</div>
							<div class="nxt-contact-item">
								<label class="tpgb-relist">
									<input type="checkbox" class="nxt-contact-checkbox" name="nxt-contact-consent" value="1"/>
									<span class="tpgb-reason-text"> <?php echo esc_html__( 'I agree to be contacted via email for support with this plugin.', 'the-plus-addons-for-block-editor' ); ?> </span>
								</label>
							</div>
						</form>
					</div>

					<div class="tpgb-modal-footer">
						<a class="tpgb-modal-deactive" href="#"><?php echo esc_html__( 'Skip & Deactivate', 'the-plus-addons-for-block-editor' ); ?></a>
						<a class="tpgb-modal-submit tpgb-btn tpgb-btn-primary" href="#"><?php echo esc_html__( 'Submit & Deactivate', 'the-plus-addons-for-block-editor' ); ?></a>
					</div>
						
					
				</div>
			</div>
			<?php
		}

		/**
		 *  Popup Css  Code
		 */
		public function tpgb_deact_popup_css() {
			?>
			<style type="text/css">
				.tpgb-relist .nxt-contact-checkbox + .tpgb-reason-text{
					font-size: 12px;
				}
				.tpgb-relist .nxt-contact-checkbox {
					margin-top: 1px;
					position: relative;
				}

				.nxt-contact-checkbox::after {
					content: "";
					position: absolute;
					top: 40%;
					left: 50%;
					border: solid #fff;
					border-width: 0 2px 2px 0;
					width: calc(20px - 100%);
					height: calc(20px - 75%);
					transform: translate(-50%,-50%) rotate(45deg) scale(0);
					opacity: 0;
					transition: transform .3s cubic-bezier(.12,.4,.29,1.46),opacity .3s ease
				}

				.nxt-contact-checkbox:checked::after {
					transform: translate(-50%,-50%) rotate(45deg) scale(1);
					opacity: 1
				}

				.nxt-contact-checkbox:focus {
					outline-width: 0;
					outline-style: none
				}

				.nxt-contact-checkbox:checked:focus,.nxt-contact-checkbox:checked:hover,.nxt-contact-checkbox:checked {
					background-color: #162d9e;
					background-image: none;
					outline-width: 0;
					outline-style: none;
					border: none;
				}

				.nxt-contact-checkbox:not(:checked)::after {
					transform: translate(-50%,-50%) rotate(45deg) scale(0);
					opacity: 0;
					transition: none
				}
				.tpgb-relist .nxt-contact-checkbox:checked::before {
					content: "";
				}
				.tpgb-reason-txt{
					border: 1px solid #72727266;
					border-radius: 5px;
				}
				.nxt-reason-svg{
					background-color: #F5F7FE;
					padding: 5px 5px 0px 5px;
					border-radius: 2.67px;
					
				}
				.nxt-reason-item,.nxt-contact-item {
					border: 1.5px solid #72727266; 
					flex: 0 0 44%;
					padding: 10px;
					border-radius: 5px;
					transition: border-color 0.3s;
				}

				.nxt-reason-item:focus,.nxt-reason-item:active,.nxt-reason-item.active {
					border-color: #1717CC;
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
					max-width: 550px;
					border-radius: 5px;
					overflow: hidden;
					transition: transform .3s ease-in-out;
					transform-origin: center
				}

				.tpgb-reason-deails {
					display: block;
					width: 100%;
					margin-top: 20px;
					padding: 9px 15px;
				}

				#tpgb-deactive-modal {
					background: rgb(0 0 0/33%);
					overflow: hidden
				}

				#tpgb-deactive-modal .tpgb-feed-caption {
					font-weight: 700;
					font-size: 14px;
					line-height: 17px;
				}
				.tpgb-help-link{
					padding: 20px 5px;
					display:block;
				}
				#tpgb-deactive-modal .tpgb-modal-body
				{
					padding: 20px 30px;
					display: flex;
					flex-direction: column
				}

				.tpgb-feedback-dialog-form {
					padding-top: 25px
				}

				#tpgb-deactive-modal .tpgb-modal-body h3 {
					padding: 0;
					margin: 0;
					line-height: 20px;
					font-size: 16px;
					text-align: center;
				}

				#tpgb-deactive-modal .tpgb-modal-body ul {
					margin: 25px 0 10px
				}

				#tpgb-deactive-modal .tpgb-modal-body ul li {
					display: flex;
					margin-bottom: 10px;
					color: #807d7d
				}

				#tpgb-deactive-modal .tpgb-modal-body ul li:last-child {
					margin-bottom: 0
				}

				#tpgb-deactive-modal .tpgb-modal-body ul li label {
					align-items: center;
					width: 100%
				}

				#tpgb-deactive-modal .tpgb-modal-body ul li label input {
					padding: 0!important;
					margin: 0;
					display: inline-block
				}

				#tpgb-deactive-modal .tpgb-modal-body ul li label textarea {
					margin-top: 8px;
					width: 350px
				}

				#tpgb-deactive-modal .tpgb-modal-body ul li label .tpgb-reason-text {
					margin-left: 8px;
					display: inline-block
				}

				#tpgb-deactive-modal .tpgb-modal-footer {
					padding: 0px 30px 30px 30px;
					display: flex;
					align-items: center;
					justify-content: space-between;
					flex-wrap: wrap
				}

				#tpgb-deactive-modal .tpgb-modal-footer .tpgb-modal-deactive,#tpgb-deactive-modal .tpgb-modal-footer .tpgb-modal-submit {
					cursor: pointer;
					font-size: 14px;
					font-weight: 500;
					padding: 10px 15px;
					outline: 0;
					border: 0;
					border-radius: 3px;
					transition: all .3s;
					text-decoration: none;
					text-align: center;
					line-height: 20px;
				}

				#tpgb-deactive-modal .tpgb-modal-footer .tpgb-modal-submit {
					background-color: #1717CC;
					color: #fff;
					width: 150px
				}

				#tpgb-deactive-modal .tpgb-modal-footer .tpgb-modal-deactive {
					color: #1717CC
				}

				.tpgb-modal-input {
					display: flex;
					flex-wrap: wrap;
					align-items: flex-start;
					justify-content: center;
					gap:10px;
				}

				.tpgb-relist {
					display: flex;
					gap:8px;
					align-items: anchor-center;
				}

				.tpgb-reason-text {
					display: inline-block;
					font-weight: 400;
					color: #1A1A1A;
					font-Size: 14px;
					line-height: 18px;
				}

				.tpgb-modal-deactive:focus,.tpgb-modal-submit:focus {
					border-color: #1717CC!important;
					box-shadow: none!important
				}

				.tpgb-help-link span {
					font-size: 12px;
					color : #666666;
					font-weight: 400
				}

				.tpgb-help-link span>a {
					color: #1717CC;
					text-decoration: none;
					line-height: 1.8
				}
				@keyframes tp-rotation{
					0%{
						transform:rotate(0deg)
					}
					100%{
						transform:rotate(359deg)
					}
				}
				#tpgb-deactive-modal .tpgb-modal-submit.tpgb-loading:before{
					display: flex;
					justify-content: center;
					align-items: center;
					content: "\f463";
					font: 18px dashicons;
					animation: tp-rotation 2s infinite linear;
				}
			</style>
			<?php
		}

		/**
		 *  Popup Js Code
		 */
		public function tpgb_deact_popup_js() {
			?>
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

					// Deactivate Button Click Action
					let element = document.getElementById('deactivate-nexter-blocks') || document.getElementById('deactivate-the-plus-addons-for-block-editor');

					if(element !== null){
						element.addEventListener('click', function(e) {
							e.preventDefault();
							var modal = document.getElementById('tpgb-deactive-modal');
							modal.classList.add('modal-active');
							var href = this.getAttribute('href');
							document.querySelector('.tpgb-modal-deactive').setAttribute('href', href);
							document.querySelector('.tpgb-modal-submit').setAttribute('href', href);
							
							// Initially disable the submit button when modal opens
							updateSubmitButtonState();
							
							// Initially hide textarea and help text
							toggleFeedbackElements(false);
						});
					}

					let selectedReasonValue = "";
					
					// Function to toggle textarea and help text visibility
					const toggleFeedbackElements = (show) => {
						const textarea = document.querySelector('.tpgb-reason-deails');
						
						if (textarea) {
							if (show) {
								textarea.style.display = 'block';
							} else {
								textarea.style.display = 'none';
							}
						}
					}
					
					// Function to update submit button state
					const updateSubmitButtonState = () => {
						const submitButton = document.querySelector('.tpgb-modal-submit');
						if (selectedReasonValue === "") {
							// No reason selected, disable button
							submitButton.classList.add('tpgb-submit-disabled');
							submitButton.style.opacity = "0.5"; 
							submitButton.style.cursor = "not-allowed";
						} else {
							// Reason selected, enable button
							submitButton.classList.remove('tpgb-submit-disabled');
							submitButton.style.opacity = "1";
							submitButton.style.cursor = "pointer";
						}
					}
					
					document.querySelectorAll('.nxt-reason-item').forEach(item => {
						item.addEventListener('click', function() {
							document.querySelectorAll('.nxt-reason-item').forEach(el => el.classList.remove('active'));
							this.classList.add('active');
							selectedReasonValue = this.querySelector('.tpgb-reason-text').textContent;
							
							// Update button state when a reason is selected
							updateSubmitButtonState();
							
							// Show textarea and help text when a reason is selected
							toggleFeedbackElements(true);
						})
					});

					// Submit to Remote Server
					document.addEventListener('click', function(e) {
						if (e.target.classList.contains('tpgb-modal-submit')) {
							e.preventDefault();
							
							// Check if button is disabled
							if (e.target.classList.contains('tpgb-submit-disabled')) {
								return; // Do nothing if disabled
							}
							
							var submitButton = e.target;
							var url = submitButton.getAttribute('href');
							submitButton.textContent = '';
							submitButton.classList.add('tpgb-loading');

							var formObj = document.getElementById('tpgb-deactive-modal').querySelector('form.tpgb-feedback-dialog-form');
							var formData = new FormData(formObj);  
							var checkbox = formObj.querySelector('.nxt-contact-checkbox');
							var checkboxValue = checkbox && checkbox.checked ? '1' : '0';                          
							var ajaxData = 'action=tpgb_deactive_plugin' +
								'&nonce=' + formData.get('nonce') +
								'&deactreson=' + selectedReasonValue+'&nxt-contact-consent=' + encodeURIComponent(checkboxValue);

							if (formData.get('tpgb-reason-txt') && formData.get('tpgb-reason-txt') !== '') {
								ajaxData += '&tprestxt=' + formData.get('tpgb-reason-txt');
							}
							

							var request = new XMLHttpRequest();
							request.open('POST', "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", true);
							request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
							request.onload = function () {
								if (request.status >= 200 && request.status < 400) {
									document.getElementById('tpgb-deactive-modal').classList.remove('modal-active');
									window.location.href = url;
								}
							};
							request.send(ajaxData);
						}
					});

				});
			</script>
			<?php
		}

		/**
		 *  Deactive Plugin API Call
		 */
		public function tpgb_deactive_plugin() {

			$nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! isset( $nonce ) || empty( $nonce ) || ! wp_verify_nonce( $nonce, 'tpgb-deactivate-feedback' ) ) {
				die( 'Security checked!' );
			}

			if ( ! current_user_can( 'activate_plugins' ) ) {
				wp_send_json_error( 'Permission denied' );
			}

			$deavtive_url = 'https://api.posimyth.com/wp-json/tpag/v2/tpgb_deactivate_user_data';

			$deactreson = ! empty( $_POST['deactreson'] ) ? sanitize_text_field( wp_unslash( $_POST['deactreson'] ) ) : '';
			$tprestxt   = isset( $_POST['tprestxt'] ) && ! empty( $_POST['tprestxt'] ) ? sanitize_text_field( wp_unslash( $_POST['tprestxt'] ) ) : '';
			$ncc        = isset( $_POST['nxt-contact-consent'] ) ? sanitize_text_field( wp_unslash( $_POST['nxt-contact-consent'] ) ) : '';

			// Get User Email.
			$admin_user       = wp_get_current_user();
			$admin_email      = $ncc ? $admin_user->user_email : '';
			$nxt_install_data = get_option( 'nexter-installed-data' );

			$api_params = array(
				// 'site_url'    => esc_url( home_url() ), // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				'reason_key'  => $deactreson,
				'reason_text' => $tprestxt,
				// 'tpgb_version' => TPGB_VERSION, // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				'admin_email' => $admin_email,
			);

			if ( ! empty( $nxt_install_data ) ) {
				$api_params = array_merge( $api_params, $nxt_install_data );
			}

			$response = wp_remote_post(
				$deavtive_url,
				array(
					'timeout'   => 30,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			if ( is_wp_error( $response ) ) {
				wp_send_json( array( 'deactivated' => false ) );
			} else {
				wp_send_json( array( 'deactivated' => true ) );
			}

			wp_die();
		}
	}

	Tpgb_Deactive::get_instance();
}
