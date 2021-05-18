<?php 
/**
 * TPGB Gutenberg Settings Options
 * @since 1.0.0
 *
 */
if (!defined('ABSPATH')) {
    exit;
}
class Tpgb_Gutenberg_Settings_Options {
	
	/**
     * Option key, and option page slug
     * @var string V1.0.0
     */
    private $key = 'tpgb_gutenberg_options';
	
	/**
     * Array of meta boxes/fields
     * @var array
     */
    protected $option_metabox = array();
    
	/**
     * Setting Name/Title
     * @var string
     */
    protected $setting_name = '';
	
	/**
     * Options Page hook
     * @var string
     */
    protected $options_page = '';
    protected $options_pages = array();
    protected $block_lists = [];
	
	/**
     * Constructor
     * @since 1.0.0
     */
    public function __construct()
    {
		add_action('tpgb_free_notice_white_label', array($this,'tpgb_free_white_label_content'));
        add_action( 'admin_enqueue_scripts', [ $this,'tpgb_options_scripts'] );
		
		if(defined('TPGBP_VERSION')){
			$options = get_option( 'tpgb_white_label' );
			$this->setting_name = (!empty($options['tpgb_plugin_name'])) ? $options['tpgb_plugin_name'] : __('ThePlus Gutenberg','tpgb');
		}else{
			$this->setting_name = esc_html__('The Plus Gutenberg', 'tpgb');
		}
		
        require_once TPGB_PATH.'includes/metabox/cmb2-conditionals.php';
        // Set our CMB fields
        $this->fields = array();
		$this->block_listout();
		add_action( 'admin_post_tpgb_blocks_opts_save', array( $this,'tpgb_blocks_opts_save_action') );
		add_action('wp_ajax_tpgb_block_search', array($this, 'tpgb_block_search'));
		
		
    }
	
	/**
     * load scripts
     * @since 1.0.0
     */
	public function tpgb_options_scripts() {
		wp_enqueue_script( 'cmb2-conditionals', TPGB_URL .'includes/metabox/cmb2-conditionals.js', array() );
	}
	
	/**
     * Initiate our hooks
     * @since 1.0.0
     */
	public function hooks()
    {
        add_action('admin_init', array( $this,'init' ) );
        add_action('admin_menu', array( $this, 'add_options_page' ));
    }
	
	/**
     * Register our setting to WP
     * @since  1.0.0
     */
    public function init()
    {
        $option_tabs = self::option_fields();
        foreach ($option_tabs as $index => $option_tab) {
            register_setting($option_tab['id'], $option_tab['id']);
        }
    }
	
	public function tpgb_free_white_label_content(){
		echo '<div class="tp-pro-note-title"><p style="margin-bottom:50px;">'.esc_html__('White Label our plugin and setup client\'s branding all around. You can update name, description, Icon and even hide the menu from dashboard. Get our pro version to have access of this feature.','tpgb').'</p></div>
			<div style="text-align:center;">
				<img style="width:55%;" src="'.esc_url(TPGB_URL .'assets/images/white-lable.png').'" alt="'.esc_attr__('White Lable','tpgb').'" class="panel-plus-white-lable" />
			</div>';
		/*echo '<div class="tp-pro-note-link"><a href="#" target="_blank">'.esc_html__('Compare Free vs Pro.','tpgb').'</a></div>';*/
	}
	/**
     * Add menu options page
     * @since 1.0.0
     */
    public function add_options_page()
    {
		 $option_tabs = self::option_fields();
		
		foreach ($option_tabs as $index => $option_tab) {
			if($index == 0){
				$this->options_pages[] = add_menu_page($this->setting_name, $this->setting_name, 'manage_options', $option_tab['id'], array(
					$this,
					'admin_page_display'
				),'dashicons-tpgb-plus-settings');
			
				add_submenu_page($option_tabs[0]['id'], $this->setting_name, $option_tab['title'], 'manage_options', $option_tab['id'], array(
					$this,
					'admin_page_display'
				));
			}else{
				$this->options_pages[] = add_submenu_page($option_tabs[0]['id'], $this->setting_name, $option_tab['title'], 'manage_options', $option_tab['id'], array(
					$this,
					'admin_page_display'
				));
			}
		}
    }
	
	public function tpgb_blocks_opts_save_action() {
		$action_page = 'tpgb_normal_blocks_opts';
		if(isset($_POST["submit-key"]) && !empty($_POST["submit-key"]) && $_POST["submit-key"]=='Save'){
			
			if ( ! isset( $_POST['nonce_tpgb_normal_blocks_opts'] ) || ! wp_verify_nonce( sanitize_key($_POST['nonce_tpgb_normal_blocks_opts']), 'nonce_tpgb_normal_blocks_action' ) ) {
			   wp_redirect( esc_url(admin_url('admin.php?page='.$action_page)) );
			} else {
			Tpgb_Library()->remove_backend_dir_files();
				if ( FALSE === get_option($action_page) ){
					$default_value = array('enable_normal_blocks' => '');
					add_option($action_page,$default_value);
					wp_redirect( esc_url(admin_url('admin.php?page=tpgb_normal_blocks_opts')) );
				}
				else{
					if(isset($_POST['enable_normal_blocks']) && !empty($_POST['enable_normal_blocks'])){
						if(is_array($_POST['enable_normal_blocks'])){
							$update_value = array('enable_normal_blocks' => map_deep( wp_unslash( $_POST['enable_normal_blocks'] ), 'sanitize_text_field' ));
						}else{
							$update_value = array('enable_normal_blocks' => sanitize_text_field($_POST['enable_normal_blocks']) );
						}
						update_option( $action_page, $update_value );
						wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
					}else if(empty($_POST['enable_normal_blocks'])){
						$update_value = array('enable_normal_blocks' => '');
						update_option( $action_page, $update_value );
						wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
					}else{
						wp_redirect(esc_url( admin_url('admin.php?page='.$action_page) ) );
					}
				}
			}
			
		}else{
			wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
		}
	}
	
	public function block_listout(){
		$this->block_lists = [
				'tp-accordion' => [
					'label' => esc_html__('Accordion','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/accordion/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="lightbulb-on" class="svg-inline--fa fa-lightbulb-on fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M319.45,0C217.44.31,144,83,144,176a175,175,0,0,0,43.56,115.78c16.52,18.85,42.36,58.22,52.21,91.44,0,.28.07.53.11.78H400.12c0-.25.07-.5.11-.78,9.85-33.22,35.69-72.59,52.21-91.44A175,175,0,0,0,496,176C496,78.63,416.91-.31,319.45,0ZM320,96a80.09,80.09,0,0,0-80,80,16,16,0,0,1-32,0A112.12,112.12,0,0,1,320,64a16,16,0,0,1,0,32Z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M240.06,454.34A32,32,0,0,0,245.42,472l17.1,25.69c5.23,7.91,17.17,14.28,26.64,14.28h61.7c9.47,0,21.41-6.37,26.64-14.28L394.59,472A37.47,37.47,0,0,0,400,454.34L400,416H240ZM112,192a24,24,0,0,0-24-24H24a24,24,0,0,0,0,48H88A24,24,0,0,0,112,192Zm504-24H552a24,24,0,0,0,0,48h64a24,24,0,0,0,0-48ZM131.08,55.22l-55.42-32a24,24,0,1,0-24,41.56l55.42,32a24,24,0,1,0,24-41.56Zm457.26,264-55.42-32a24,24,0,1,0-24,41.56l55.42,32a24,24,0,0,0,24-41.56Zm-481.26-32-55.42,32a24,24,0,1,0,24,41.56l55.42-32a24,24,0,0,0-24-41.56ZM520.94,100a23.8,23.8,0,0,0,12-3.22l55.42-32a24,24,0,0,0-24-41.56l-55.42,32a24,24,0,0,0,12,44.78Z"></path></g></svg>',
					'keyword' => ['accordion', 'tabs', 'toggle', 'faq', 'collapse', 'show hide content', 'Tiles'],
				],
				'tp-anything-carousel' => [
					'label' => esc_html__('Carousel Anything','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/carousal-anything/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="sliders-h" class="svg-inline--fa fa-sliders-h fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M496 64H288v64h208a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zM16 128h176V64H16A16 16 0 0 0 0 80v32a16 16 0 0 0 16 16zm0 160h304v-64H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16zm480-64h-80v64h80a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0 160H160v64h336a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM0 400v32a16 16 0 0 0 16 16h48v-64H16a16 16 0 0 0-16 16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M272 32h-32a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16h32a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zm128 160h-32a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16h32a16 16 0 0 0 16-16v-96a16 16 0 0 0-16-16zM144 352h-32a16 16 0 0 0-16 16v96a16 16 0 0 0 16 16h32a16 16 0 0 0 16-16v-96a16 16 0 0 0-16-16z"></path></g></svg>',
					'keyword' => ['carousel anything', 'slider', 'slideshow'],
				],
				'tp-audio-player' => [
					'label' => esc_html__('Audio Player','tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="headphones-alt" class="svg-inline--fa fa-headphones-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M496 416h-16a16 16 0 0 1-16-16V288c0-114.67-93.33-207.8-208-207.82S48 173.33 48 288v112a16 16 0 0 1-16 16H16a16 16 0 0 1-16-16V288C4.57 151.13 112.91 32 256 32s251.43 119.13 256 256v112a16 16 0 0 1-16 16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M160 288h-16a64.05 64.05 0 0 0-64 64.12v63.76A64.06 64.06 0 0 0 144 480h16a32 32 0 0 0 32-32.06V320.06A32 32 0 0 0 160 288zm208 0h-16a32 32 0 0 0-32 32.06v127.88A32 32 0 0 0 352 480h16a64.06 64.06 0 0 0 64-64.12v-63.76A64.06 64.06 0 0 0 368 288z"></path></g></svg>',
				],
				'tp-blockquote' => [
					'label' => esc_html__('Blockquote','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/blockquote/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="quote-left" class="svg-inline--fa fa-quote-left fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M464 256h-80v-64a64.06 64.06 0 0 1 64-64h8a23.94 23.94 0 0 0 24-23.88V56a23.94 23.94 0 0 0-23.88-24H448a160 160 0 0 0-160 160v240a48 48 0 0 0 48 48h128a48 48 0 0 0 48-48V304a48 48 0 0 0-48-48z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M176 256H96v-64a64.06 64.06 0 0 1 64-64h8a23.94 23.94 0 0 0 24-23.88V56a23.94 23.94 0 0 0-23.88-24H160A160 160 0 0 0 0 192v240a48 48 0 0 0 48 48h128a48 48 0 0 0 48-48V304a48 48 0 0 0-48-48z"></path></g></svg>',
					'keyword' => ['blockquote', 'Block Quotation', 'Citation', 'Pull Quotes'],
				],
				'tp-breadcrumbs' => [
					'label' => esc_html__('Breadcrumbs','tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="home-alt" class="svg-inline--fa fa-home-alt fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M336 463.58v-95.64a16 16 0 0 0-16-16h-64a16 16 0 0 0-16 16v95.71a16 16 0 0 1-15.92 16l-112.08.29a16 16 0 0 1-16-16V300.05L280.39 148.2a12.19 12.19 0 0 1 15.3 0L480 299.94v164a16 16 0 0 1-16 16l-112-.31a16 16 0 0 1-16-16.05z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M530.92 300.94L295.69 107.2a12.19 12.19 0 0 0-15.3 0L45.17 300.94a12 12 0 0 1-16.89-1.64l-25.5-31a12 12 0 0 1 1.61-16.89l253.1-208.47a48 48 0 0 1 61 0l253.13 208.47a12 12 0 0 1 1.66 16.89l-25.5 31a12 12 0 0 1-16.86 1.64z"></path></g></svg>',
					'keyword' => ['breadcrumbs bar', 'breadcrumb trail', 'navigation', 'site navigation', 'breadcrumb navigation']
				],
				'tp-button' => [
					'label' => esc_html__('Tp Button','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/button/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="link" class="svg-inline--fa fa-link fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M44.45 252.59l37.11-37.1c9.84-9.84 26.78-3.3 27.29 10.6a184.45 184.45 0 0 0 9.69 52.72 16.08 16.08 0 0 1-3.78 16.61l-13.09 13.09c-28 28-28.9 73.66-1.15 102a72.07 72.07 0 0 0 102.32.51L270 343.79A72 72 0 0 0 270 242a75.64 75.64 0 0 0-10.34-8.57 16 16 0 0 1-6.95-12.6A39.86 39.86 0 0 1 264.45 191l21.06-21a16.06 16.06 0 0 1 20.58-1.74A152.05 152.05 0 0 1 327 400l-.36.37-67.2 67.2c-59.27 59.27-155.7 59.26-215 0s-59.26-155.72.01-214.98z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M410.33 203.49c28-28 28.9-73.66 1.15-102a72.07 72.07 0 0 0-102.32-.49L242 168.21A72 72 0 0 0 242 270a75.64 75.64 0 0 0 10.34 8.57 16 16 0 0 1 6.94 12.6A39.81 39.81 0 0 1 247.55 321l-21.06 21.05a16.07 16.07 0 0 1-20.58 1.74A152.05 152.05 0 0 1 185 112l.36-.37 67.2-67.2c59.27-59.27 155.7-59.26 215 0s59.27 155.7 0 215l-37.11 37.1c-9.84 9.84-26.78 3.3-27.29-10.6a184.45 184.45 0 0 0-9.69-52.72 16.08 16.08 0 0 1 3.78-16.61z"></path></g></svg>',
					'keyword' => ['Button', 'CTA', 'link', 'creative button', 'Call to action', 'Marketing Button']
				],
				'tp-carousel-remote' => [
					'label' => esc_html__('Carousal Remote','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/carousal-remote/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="signal-stream" class="svg-inline--fa fa-signal-stream fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M198.27 168.37l-22.76-22.23a16.44 16.44 0 0 0-24 1.31 168.77 168.77 0 0 0 0 217.1 16.44 16.44 0 0 0 24 1.31l22.76-22.22a15.12 15.12 0 0 0 1.45-20.32 107.39 107.39 0 0 1 0-134.64 15.11 15.11 0 0 0-1.45-20.31zm226.19-20.92a16.44 16.44 0 0 0-24-1.31l-22.76 22.23a15.12 15.12 0 0 0-1.45 20.31 107.39 107.39 0 0 1 0 134.64 15.14 15.14 0 0 0 1.45 20.32l22.76 22.22a16.44 16.44 0 0 0 24-1.31 168.77 168.77 0 0 0 0-217.1z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M288 200a56 56 0 1 0 56 56 56 56 0 0 0-56-56zM64 256a214.3 214.3 0 0 1 55.42-144.06c5.59-6.22 4.91-15.74-1.08-21.59L96 68.53a16.41 16.41 0 0 0-23.56 1C25.59 121 0 186.56 0 256s25.59 135 72.44 186.52a16.41 16.41 0 0 0 23.56 1l22.34-21.82c6-5.85 6.67-15.37 1.08-21.59A214.3 214.3 0 0 1 64 256zM503.56 69.48a16.41 16.41 0 0 0-23.56-1l-22.34 21.87c-6 5.85-6.67 15.37-1.08 21.59a214.95 214.95 0 0 1 0 288.12c-5.59 6.22-4.91 15.74 1.08 21.59L480 443.47a16.41 16.41 0 0 0 23.56-1C550.41 391 576 325.44 576 256s-25.59-135-72.44-186.52z"></path></g></svg>',
					'keyword' => ['carousel remote', 'slider controller'],
				],
				'tp-countdown' => [
					'label' => esc_html__('Countdown','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/countdown/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="clock" class="svg-inline--fa fa-clock fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M348.49,321h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z"></path></g></svg>',
					'keyword' => ['Countdown', 'countdown timer', 'timer', 'Scarcity Countdown', 'Urgency Countdown', 'Event countdown', 'Sale Countdown', 'chronometer', 'stopwatch']
				],
				'tp-creative-image' => [
					'label' => esc_html__('TP Image','tpgb'),
					'demoUrl' => '#demo',
					'docUrl' => '#doc',
					'videoUrl' => '#video',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="file-image" class="svg-inline--fa fa-file-image fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M384 128H272a16 16 0 0 1-16-16V0H24A23.94 23.94 0 0 0 0 23.88V488a23.94 23.94 0 0 0 23.88 24H360a23.94 23.94 0 0 0 24-23.88V128zm-271.46 48a48 48 0 1 1-48 48 48 48 0 0 1 48-48zm208 240h-256l.46-48.48L104.51 328c4.69-4.69 11.8-4.2 16.49.48L160.54 368 264 264.48a12 12 0 0 1 17 0L320.54 304z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M377 105L279.1 7a24 24 0 0 0-17-7H256v112a16 16 0 0 0 16 16h112v-6.1a23.9 23.9 0 0 0-7-16.9zM112.54 272a48 48 0 1 0-48-48 48 48 0 0 0 48 48zM264 264.45L160.54 368 121 328.48c-4.69-4.68-11.8-5.17-16.49-.48L65 367.52 64.54 416h256V304L281 264.48a12 12 0 0 0-17-.03z"></path></g></svg>',
					'keyword' => ['Creative image', 'Image', 'Animated Image', 'ScrollReveal', 'scrolling image', 'decorative image', 'image effect', 'Photo', 'Visual']
				],
				'tp-cta-banner' => [
					'label' => esc_html__('CTA Banner','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/cta-banner/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="magnet" class="svg-inline--fa fa-magnet fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M476.1 20h-104a36 36 0 0 0-36 36v80a12 12 0 0 0 12 12h152a11.89 11.89 0 0 0 12-11.9V56a36 36 0 0 0-36-36zm-336.1.1H36a36 36 0 0 0-36 36v80a12 12 0 0 0 12 12h152.1a11.89 11.89 0 0 0 11.9-12v-80a36 36 0 0 0-36-36z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M512 192.2c-.2 20.2-.6 40.4 0 53.2 0 150.7-134.5 246.7-255.1 246.7S.1 396.1.1 245.5c.6-13 .1-31.9 0-53.3a12 12 0 0 1 12-12.1h152a12 12 0 0 1 12 12v52c0 127.9 160 128.1 160 0v-52a12 12 0 0 1 12-12H500a12 12 0 0 1 12 12.1z"></path></g></svg>',
					'keyword' => ['advertisement', 'banner', 'advertisement banner', 'ad manager', 'announcement', 'announcement banner']
				],
				'tp-data-table' => [
					'label' => esc_html__('Data Table','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/data-table/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="table" class="svg-inline--fa fa-table fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M288 160v96h160v-96zm0 256h160v-96H288zM64 256h160v-96H64zm0 160h160v-96H64z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M464 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h416a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48zM224 416H64v-96h160zm0-160H64v-96h160zm224 160H288v-96h160zm0-160H288v-96h160z"></path></g></svg>',
					'keyword' => ['Data table', 'datatable', 'grid', 'csv table', 'table', 'tabular layout', 'Table Showcase']
				],
				'tp-draw-svg' => [
					'label' => esc_html__('Draw SVG','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/draw-svg/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="car" class="svg-inline--fa fa-car fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M319.5 128a48 48 0 0 1 44.57 30.17L384 208H128l19.93-49.83A48 48 0 0 1 192.5 128zM80 384a63.82 63.82 0 0 1-47.57-21.2A31.82 31.82 0 0 0 32 368v48a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32v-32zm352 0h-48v32a32 32 0 0 0 32 32h32a32 32 0 0 0 32-32v-48a31.82 31.82 0 0 0-.43-5.2A63.82 63.82 0 0 1 432 384z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M500 176h-59.88l-16.64-41.6A111.43 111.43 0 0 0 319.5 64h-127a111.47 111.47 0 0 0-104 70.4L71.87 176H12A12 12 0 0 0 .37 190.91l6 24A12 12 0 0 0 18 224h20.08A63.55 63.55 0 0 0 16 272v48a64 64 0 0 0 64 64h352a64 64 0 0 0 64-64v-48a63.58 63.58 0 0 0-22.07-48H494a12 12 0 0 0 11.64-9.09l6-24A12 12 0 0 0 500 176zm-352.07-17.83A48 48 0 0 1 192.5 128h127a48 48 0 0 1 44.57 30.17L384 208H128zM96 256c19.2 0 48 28.71 48 47.85s-28.8 15.95-48 15.95-32-12.8-32-31.9S76.8 256 96 256zm272 47.85c0-19.14 28.8-47.85 48-47.85s32 12.76 32 31.9-12.8 31.9-32 31.9-48 3.2-48-15.95z"></path></g></svg>',
					'keyword' => ['Draw SVG', 'Draw Icon', 'illustration', 'animated svg', 'animated icons', 'Lottie animations', 'Lottie files', 'effects', 'image effect']
				],
				'tp-empty-space' => [
					'label' => esc_html__('TP Spacer','tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="container-storage" class="svg-inline--fa fa-container-storage fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M16 96v320h608V96zm96 288H80V128h32zm112 0h-32V128h32zm112 0h-32V128h32zm112 0h-32V128h32zm112 0h-32V128h32z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M624 416H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h608a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-384H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h608a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z"></path></g></svg>',
					'keyword' => ['Spacer', 'Divider', 'Spacing','empty space']
				],
				'tp-expand' => [
					'label' => esc_html__('Expand','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/expand/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="arrows-alt-v" class="svg-inline--fa fa-arrows-alt-v fa-w-8" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M88 378V134.08h80V378z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M42 134.08h171.94c21.4 0 32.09-25.87 17-41L145 7a24 24 0 0 0-33.89 0L25 93.09c-15 15.15-4.32 40.99 17 40.99zM213.94 378H42.14c-21.4 0-32.09 25.88-17 41l86 86a24 24 0 0 0 33.86 0l85.93-86c15.07-15.23 4.4-41-16.99-41z"></path></g></svg>',
					'keyword' => ['Expand', 'read more', 'show hide content', 'Expand tabs', 'show more', 'toggle', 'Excerpt']
				],
				'tp-flipbox' => [
					'label' => esc_html__('Flipbox','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/flipbox/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="repeat-alt" class="svg-inline--fa fa-repeat-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M493.54 181.5A159 159 0 0 1 512 257.25C511.34 345.4 438.56 416 350.4 416H192v47.5c0 22.5-26.18 32.3-41 17.5l-80-80a24 24 0 0 1 0-33.94l80-80c15.11-15.11 41-4.34 41 17v48h158.87c52.82 0 96.58-42.18 97.12-95a95.53 95.53 0 0 0-9.21-42.06 23.94 23.94 0 0 1 4.8-27.28c4.74-4.71 8.64-8.55 11.87-11.79a24 24 0 0 1 38.09 5.57z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M68.42 324.35c-4.74 4.71-8.64 8.56-11.87 11.79a24 24 0 0 1-38.09-5.57A159 159 0 0 1 0 254.82C.66 166.67 73.44 96 161.6 96H320V48.58c0-22.29 26-32.47 41-17.52l80 80a24 24 0 0 1 0 33.94l-80 80c-14.85 14.85-41 4.91-41-17.46V160H161.12c-52.81 0-96.57 42.18-97.12 95a95.47 95.47 0 0 0 9.22 42 23.94 23.94 0 0 1-4.8 27.35z"></path></g></svg>',
					'keyword' => ['flipbox', 'flip', 'flip image', 'flip card', 'action box', 'flipbox 3D', 'card'],
				],
				'tp-google-map' => [
					'label' => esc_html__('Google Map','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/google-maps/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="map" class="svg-inline--fa fa-map fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M192 32l192 64v384l-192-64z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M0 117.66V464a16 16 0 0 0 21.94 14.86L160 416V32L20.12 88A32 32 0 0 0 0 117.66zm554.06-84.5L416 96v384l139.88-55.95A32 32 0 0 0 576 394.34V48a16 16 0 0 0-21.94-14.84z"></path></g></svg>',
					'keyword' => ['Map', 'Maps', 'Google Maps', 'g maps', 'location map', 'map iframe', 'embed']
				],
				'tp-heading-animation' => [
					'label' => esc_html__('Heading Animation','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/heading-animation/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="underline" class="svg-inline--fa fa-underline fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M432 448H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M32 64h32v160c0 88.22 71.78 160 160 160s160-71.78 160-160V64h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16H272a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h32v160a80 80 0 0 1-160 0V64h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16H32a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16z"></path></g></svg>',
					'keyword' => ['Heading Animation', 'Animated Heading', 'Animation Text', 'Animated Text', 'Text Animation']
				],
				'tp-heading-title' => [
					'label' => esc_html__('TP Heading','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/heading-title/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="heading" class="svg-inline--fa fa-heading fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M480 32H320a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zm-288 0H32a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zm0 384H32a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm288 0H320a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h160a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M352 96h96v320h-96V288H160v128H64V96h96v128h192z"></path></g></svg>',
					'keyword' => ['Heading', 'Title', 'Text', 'Heading title', 'Headline']
				],
				'tp-hotspot' => [
					'label' => esc_html__('Hotspot','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/hotspot-pin-point/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="thumbtack" class="svg-inline--fa fa-thumbtack fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M384 328a24 24 0 0 1-24 24H224V208a16 16 0 0 0-16-16h-32a16 16 0 0 0-16 16v144H24a24 24 0 0 1-24-24c0-50.74 37.47-91.18 86-113.73L98.21 96H56a24 24 0 0 1-24-24V24A24 24 0 0 1 56 0h272a24 24 0 0 1 24 24v48a24 24 0 0 1-24 24h-42.21L298 214.27c48 22.31 86 62.55 86 113.73z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M224 208v248a8 8 0 0 1-.84 3.57l-24 48a8 8 0 0 1-14.32 0l-24-48A8 8 0 0 1 160 456V208a16 16 0 0 1 16-16h32a16 16 0 0 1 16 16z"></path></g></svg>',
					'keyword' => [ 'Image hotspot', 'maps', 'pin' ],
				],
				'tp-hovercard' => [
					'label' => esc_html__('Hover Card','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/advanced-hover-card-animations/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="code" class="svg-inline--fa fa-code fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M422.12 18.16a12 12 0 0 1 8.2 14.9l-136.5 470.2a12 12 0 0 1-14.89 8.2l-61-17.7a12 12 0 0 1-8.2-14.9l136.5-470.2a12 12 0 0 1 14.89-8.2z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M636.23 247.26l-144.11-135.2a12.11 12.11 0 0 0-17 .5L431.62 159a12 12 0 0 0 .81 17.2L523 256l-90.59 79.7a11.92 11.92 0 0 0-.81 17.2l43.5 46.4a12 12 0 0 0 17 .6l144.11-135.1a11.94 11.94 0 0 0 .02-17.54zm-427.8-88.2l-43.5-46.4a12 12 0 0 0-17-.5l-144.11 135a11.94 11.94 0 0 0 0 17.5l144.11 135.1a11.92 11.92 0 0 0 17-.5l43.5-46.4a12 12 0 0 0-.81-17.2L117 256l90.6-79.7a11.92 11.92 0 0 0 .83-17.24z"></path></g></svg>',
					'keyword' => ['Hover Card', 'Card', 'Business Card'],
				],
				'tp-infobox' => [
					'label' => esc_html__('Infobox','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/infobox/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="info-circle" class="svg-inline--fa fa-info-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M256 8C119 8 8 119.08 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 110a42 42 0 1 1-42 42 42 42 0 0 1 42-42zm56 254a12 12 0 0 1-12 12h-88a12 12 0 0 1-12-12v-24a12 12 0 0 1 12-12h12v-64h-12a12 12 0 0 1-12-12v-24a12 12 0 0 1 12-12h64a12 12 0 0 1 12 12v100h12a12 12 0 0 1 12 12z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M256 202a42 42 0 1 0-42-42 42 42 0 0 0 42 42zm44 134h-12V236a12 12 0 0 0-12-12h-64a12 12 0 0 0-12 12v24a12 12 0 0 0 12 12h12v64h-12a12 12 0 0 0-12 12v24a12 12 0 0 0 12 12h88a12 12 0 0 0 12-12v-24a12 12 0 0 0-12-12z"></path></g></svg>',
					'keyword' => ['Infobox', 'Information', 'Info box', 'card', 'info']
				],
				'tp-mailchimp' => [
					'label' => esc_html__('Mailchimp','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/mailchimp/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="envelope" class="svg-inline--fa fa-envelope fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M256.47 352h-.94c-30.1 0-60.41-23.42-82.54-40.52C169.39 308.7 24.77 202.7 0 183.33V400a48 48 0 0 0 48 48h416a48 48 0 0 0 48-48V183.36c-24.46 19.17-169.4 125.34-173 128.12-22.12 17.1-52.43 40.52-82.53 40.52zM464 64H48a48 48 0 0 0-48 48v19a24.08 24.08 0 0 0 9.2 18.9c30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4 23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7A23.93 23.93 0 0 0 512 131v-19a48 48 0 0 0-48-48z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M512 131v52.36c-24.46 19.17-169.4 125.34-173 128.12-22.12 17.1-52.43 40.52-82.53 40.52h-.94c-30.1 0-60.41-23.42-82.54-40.52C169.39 308.7 24.77 202.7 0 183.33V131a24.08 24.08 0 0 0 9.2 18.9c30.6 23.9 40.7 32.4 173.4 128.7 16.69 12.12 49.75 41.4 72.93 41.4h.94c23.18 0 56.24-29.28 72.93-41.4 132.7-96.3 142.8-104.7 173.4-128.7A23.93 23.93 0 0 0 512 131z"></path></g></svg>',
					'keyword' => ['Mailchimp', 'Mailchimp addon', 'subscribe form']
				],
				'tp-media-listing' => [
					'label' => esc_html__('Media Listing','tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="grav" class="svg-inline--fa fa-grav fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M301.1 212c4.4 4.4 4.4 11.9 0 16.3l-9.7 9.7c-4.4 4.7-11.9 4.7-16.6 0l-10.5-10.5c-4.4-4.7-4.4-11.9 0-16.6l9.7-9.7c4.4-4.4 11.9-4.4 16.6 0l10.5 10.8zm-30.2-19.7c3-3 3-7.8 0-10.5-2.8-3-7.5-3-10.5 0-2.8 2.8-2.8 7.5 0 10.5 3.1 2.8 7.8 2.8 10.5 0zm-26 5.3c-3 2.8-3 7.5 0 10.2 2.8 3 7.5 3 10.5 0 2.8-2.8 2.8-7.5 0-10.2-3-3-7.7-3-10.5 0zm72.5-13.3c-19.9-14.4-33.8-43.2-11.9-68.1 21.6-24.9 40.7-17.2 59.8.8 11.9 11.3 29.3 24.9 17.2 48.2-12.5 23.5-45.1 33.2-65.1 19.1zm47.7-44.5c-8.9-10-23.3 6.9-15.5 16.1 7.4 9 32.1 2.4 15.5-16.1zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-66.2 42.6c2.5-16.1-20.2-16.6-25.2-25.7-13.6-24.1-27.7-36.8-54.5-30.4 11.6-8 23.5-6.1 23.5-6.1.3-6.4 0-13-9.4-24.9 3.9-12.5.3-22.4.3-22.4 15.5-8.6 26.8-24.4 29.1-43.2 3.6-31-18.8-59.2-49.8-62.8-22.1-2.5-43.7 7.7-54.3 25.7-23.2 40.1 1.4 70.9 22.4 81.4-14.4-1.4-34.3-11.9-40.1-34.3-6.6-25.7 2.8-49.8 8.9-61.4 0 0-4.4-5.8-8-8.9 0 0-13.8 0-24.6 5.3 11.9-15.2 25.2-14.4 25.2-14.4 0-6.4-.6-14.9-3.6-21.6-5.4-11-23.8-12.9-31.7 2.8.1-.2.3-.4.4-.5-5 11.9-1.1 55.9 16.9 87.2-2.5 1.4-9.1 6.1-13 10-21.6 9.7-56.2 60.3-56.2 60.3-28.2 10.8-77.2 50.9-70.6 79.7.3 3 1.4 5.5 3 7.5-2.8 2.2-5.5 5-8.3 8.3-11.9 13.8-5.3 35.2 17.7 24.4 15.8-7.2 29.6-20.2 36.3-30.4 0 0-5.5-5-16.3-4.4 27.7-6.6 34.3-9.4 46.2-9.1 8 3.9 8-34.3 8-34.3 0-14.7-2.2-31-11.1-41.5 12.5 12.2 29.1 32.7 28 60.6-.8 18.3-15.2 23-15.2 23-9.1 16.6-43.2 65.9-30.4 106 0 0-9.7-14.9-10.2-22.1-17.4 19.4-46.5 52.3-24.6 64.5 26.6 14.7 108.8-88.6 126.2-142.3 34.6-20.8 55.4-47.3 63.9-65 22 43.5 95.3 94.5 101.1 59z"></path></svg>',
				],
				'tp-messagebox' => [
					'label' => esc_html__('Message box','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/message-box/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="exclamation-square" class="svg-inline--fa fa-exclamation-square fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h352a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48zM224 384a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm38.24-238.41l-12.8 128A16 16 0 0 1 233.52 288h-19a16 16 0 0 1-15.92-14.41l-12.8-128A16 16 0 0 1 201.68 128h44.64a16 16 0 0 1 15.92 17.59z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M246.32 128h-44.64a16 16 0 0 0-15.92 17.59l12.8 128A16 16 0 0 0 214.48 288h19a16 16 0 0 0 15.92-14.41l12.8-128A16 16 0 0 0 246.32 128zM224 320a32 32 0 1 0 32 32 32 32 0 0 0-32-32z"></path></g></svg>',
					'keyword' => ['Message box', 'Notification box', 'alert box']
				],
				'tp-navigation-builder' => [
					'label' => esc_html__('Navigation Menu','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/navigation-menu/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="bars" class="svg-inline--fa fa-bars fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M16 288h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M432 384H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H16A16 16 0 0 0 0 80v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16z"></path></g></svg>',
					'keyword' => ['navigation menu', 'mega menu', 'header builder', 'sticky menu', 'navigation bar', 'header menu', 'menu', 'navigation builder']
				],
				'tp-number-counter' => [
					'label' => esc_html__('Number Counter','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/number-counter/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="hashtag" class="svg-inline--fa fa-hashtag fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M202.46 32.19a11.5 11.5 0 0 0-2.11-.19h-40.63a12 12 0 0 0-11.81 9.89L132.53 128h65l14.62-81.89a12 12 0 0 0-9.69-13.92zM72.19 465.89a12 12 0 0 0 9.7 13.92A11.5 11.5 0 0 0 84 480h40.64a12 12 0 0 0 11.81-9.89L186.11 192h-65zm163.65 0a12 12 0 0 0 9.7 13.92 11.5 11.5 0 0 0 2.11.19h40.63a12 12 0 0 0 11.82-9.89L315.47 384h-65zm130.27-433.7A11.5 11.5 0 0 0 364 32h-40.63a12 12 0 0 0-11.82 9.89L261.89 320h65l48.92-273.89a12 12 0 0 0-9.7-13.92z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M44.18 191.81a11.5 11.5 0 0 0 2.11.19H285l11-64H53.43a12 12 0 0 0-11.81 9.89l-7.14 40a12 12 0 0 0 9.7 13.92zM7.33 329.89l-7.14 40a12 12 0 0 0 9.7 13.92A11.5 11.5 0 0 0 12 384h75l11-64H19.15a12 12 0 0 0-11.82 9.89zm430.78-201.7A11.5 11.5 0 0 0 436 128h-75l-11 64h78.85a12 12 0 0 0 11.82-9.89l7.14-40a12 12 0 0 0-9.7-13.92zm-34.29 192a11.5 11.5 0 0 0-2.11-.19H163l-11 64h242.57a12 12 0 0 0 11.81-9.89l7.14-40a12 12 0 0 0-9.7-13.92z"></path></g></svg>',
					'keyword' => ['number counter', 'counter', 'animated counter', 'Odometer']
				],
				'tp-post-author' => [
					'label' => esc_html__('Post Author', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="user" class="svg-inline--fa fa-user fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M352 128A128 128 0 1 1 224 0a128 128 0 0 1 128 128z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M313.6 288h-16.7a174.1 174.1 0 0 1-145.8 0h-16.7A134.43 134.43 0 0 0 0 422.4V464a48 48 0 0 0 48 48h352a48 48 0 0 0 48-48v-41.6A134.43 134.43 0 0 0 313.6 288z"></path></g></svg>',
					'keyword' => ['post author', 'author','user info']
				],
				'tp-post-comment' => [
					'label' => esc_html__('Post Comments', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="comment-dots" class="svg-inline--fa fa-comment-dots fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5a8 8 0 0 0-1.5 8.7A7.83 7.83 0 0 0 8 480c66.3 0 116-31.8 140.6-51.4A305 305 0 0 0 256 448c141.4 0 256-93.1 256-208S397.4 32 256 32zM128 272a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm128 0a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm128 0a32 32 0 1 1 32-32 32 32 0 0 1-32 32z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M128 208a32 32 0 1 0 32 32 32 32 0 0 0-32-32zm128 0a32 32 0 1 0 32 32 32 32 0 0 0-32-32zm128 0a32 32 0 1 0 32 32 32 32 0 0 0-32-32z"></path></g></svg>',
					'keyword' => ['post comments', 'comments','comments area']
				],
				'tp-post-content' => [
					'label' => esc_html__('Post Content', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="file-alt" class="svg-inline--fa fa-file-alt fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M384 128H272a16 16 0 0 1-16-16V0H24A23.94 23.94 0 0 0 0 23.88V488a23.94 23.94 0 0 0 23.88 24H360a23.94 23.94 0 0 0 24-23.88V128zm-96 244a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12zm0-64a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12zm0-64a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M377 105L279.1 7a24 24 0 0 0-17-7H256v112a16 16 0 0 0 16 16h112v-6.1a23.9 23.9 0 0 0-7-16.9zM276 352H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12zm0-64H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12zm0-64H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12z"></path></g></svg>',
					'keyword' => ['content', 'post content', 'post excerpt', 'archive description']
				],
				'tp-post-image' => [
					'label' => esc_html__('Post Image', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="file-image" class="svg-inline--fa fa-file-image fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M384 128H272a16 16 0 0 1-16-16V0H24A23.94 23.94 0 0 0 0 23.88V488a23.94 23.94 0 0 0 23.88 24H360a23.94 23.94 0 0 0 24-23.88V128zm-271.46 48a48 48 0 1 1-48 48 48 48 0 0 1 48-48zm208 240h-256l.46-48.48L104.51 328c4.69-4.69 11.8-4.2 16.49.48L160.54 368 264 264.48a12 12 0 0 1 17 0L320.54 304z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M377 105L279.1 7a24 24 0 0 0-17-7H256v112a16 16 0 0 0 16 16h112v-6.1a23.9 23.9 0 0 0-7-16.9zM112.54 272a48 48 0 1 0-48-48 48 48 0 0 0 48 48zM264 264.45L160.54 368 121 328.48c-4.69-4.68-11.8-5.17-16.49-.48L65 367.52 64.54 416h256V304L281 264.48a12 12 0 0 0-17-.03z"></path></g></svg>',
					'keyword' => ['post featured image', 'post image', 'featured image']
				],
				'tp-post-listing' => [
					'label' => esc_html__('Post Listing', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="list-alt" class="svg-inline--fa fa-list-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M464 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h416a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48zM128 392a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-96a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm0-96a40 40 0 1 1 40-40 40 40 0 0 1-40 40zm288 168a12 12 0 0 1-12 12H204a12 12 0 0 1-12-12v-32a12 12 0 0 1 12-12h200a12 12 0 0 1 12 12zm0-96a12 12 0 0 1-12 12H204a12 12 0 0 1-12-12v-32a12 12 0 0 1 12-12h200a12 12 0 0 1 12 12zm0-96a12 12 0 0 1-12 12H204a12 12 0 0 1-12-12v-32a12 12 0 0 1 12-12h200a12 12 0 0 1 12 12z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M128 200a40 40 0 1 0-40-40 40 40 0 0 0 40 40zm0 16a40 40 0 1 0 40 40 40 40 0 0 0-40-40zm0 96a40 40 0 1 0 40 40 40 40 0 0 0-40-40z"></path></g></svg>',
					'keyword' => ['post listing', 'related posts', 'archive posts', 'post list', 'post grid', 'post masonry','post carousel', 'post slider']
				],
				'tp-post-meta' => [
					'label' => esc_html__('Post Meta Info', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="info-circle" class="svg-inline--fa fa-info-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M256 8C119 8 8 119.08 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 110a42 42 0 1 1-42 42 42 42 0 0 1 42-42zm56 254a12 12 0 0 1-12 12h-88a12 12 0 0 1-12-12v-24a12 12 0 0 1 12-12h12v-64h-12a12 12 0 0 1-12-12v-24a12 12 0 0 1 12-12h64a12 12 0 0 1 12 12v100h12a12 12 0 0 1 12 12z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M256 202a42 42 0 1 0-42-42 42 42 0 0 0 42 42zm44 134h-12V236a12 12 0 0 0-12-12h-64a12 12 0 0 0-12 12v24a12 12 0 0 0 12 12h12v64h-12a12 12 0 0 0-12 12v24a12 12 0 0 0 12 12h88a12 12 0 0 0 12-12v-24a12 12 0 0 0-12-12z"></path></g></svg>',
					'keyword' => ['post category', 'post tags', 'post meta info', 'meta info', 'post date', 'post comment', 'post author']
				],
				'tp-post-navigation' => [
					'label' => esc_html__('Post Navigation', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="exchange" class="svg-inline--fa fa-exchange fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M488 384H106l30.47 27.73a24 24 0 0 1 .47 34.4L126.13 457a24 24 0 0 1-33.94 0L9.37 374.63a32 32 0 0 1 0-45.26L92.19 247a24 24 0 0 1 33.94 0L137 257.87a24 24 0 0 1-.47 34.4L106 320h382a24 24 0 0 1 24 24v16a24 24 0 0 1-24 24z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M0 168v-16a24 24 0 0 1 24-24h382l-30.5-27.73a24 24 0 0 1-.47-34.4L385.87 55a24 24 0 0 1 33.94 0l82.82 82.34a32 32 0 0 1 0 45.26L419.81 265a24 24 0 0 1-33.94 0L375 254.13a24 24 0 0 1 .47-34.4L406 192H24a24 24 0 0 1-24-24z"></path></g></svg>',
					'keyword' => ['previous next', 'post previous next', 'post navigation']
				],
				'tp-post-title' => [
					'label' => esc_html__('Post Title', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="underline" class="svg-inline--fa fa-underline fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M432 448H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M32 64h32v160c0 88.22 71.78 160 160 160s160-71.78 160-160V64h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16H272a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h32v160a80 80 0 0 1-160 0V64h32a16 16 0 0 0 16-16V16a16 16 0 0 0-16-16H32a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16z"></path></g></svg>',
					'keyword' => ['post title', 'page title', 'archive title']
				],
				'tp-popup-builder' => [
					'label' => esc_html__('Popup Builder','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/popup-builder/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="paper-plane" class="svg-inline--fa fa-paper-plane fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M245.53 410.5l-75 92.83c-14 17.1-42.5 7.8-42.5-15.8V358l280.26-252.77c5.5-4.9 13.3 2.6 8.6 8.3L191.72 387.87z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M511.59 28l-72 432a24.07 24.07 0 0 1-33 18.2l-214.87-90.33 225.17-274.34c4.7-5.7-3.1-13.2-8.6-8.3L128 358 14.69 313.83a24 24 0 0 1-2.2-43.2L476 3.23c17.29-10 39 4.6 35.59 24.77z"></path></g></svg>',
					'keyword' => ['popup', 'pop up', 'alertbox', 'offcanvas', 'modal box', 'modal popup']
				],
				'tp-pricing-list' => [
					'label' => esc_html__('Pricing List','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/pricing-list/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="file-alt" class="svg-inline--fa fa-file-alt fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M384 128H272a16 16 0 0 1-16-16V0H24A23.94 23.94 0 0 0 0 23.88V488a23.94 23.94 0 0 0 23.88 24H360a23.94 23.94 0 0 0 24-23.88V128zm-96 244a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12zm0-64a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12zm0-64a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M377 105L279.1 7a24 24 0 0 0-17-7H256v112a16 16 0 0 0 16 16h112v-6.1a23.9 23.9 0 0 0-7-16.9zM276 352H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12zm0-64H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12zm0-64H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12z"></path></g></svg>',
					'keyword' => ['Pricing list', 'Item price', 'price card', 'Price Guide', 'price box']
				],
				'tp-pricing-table' => [
					'label' => esc_html__('Pricing Table','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/pricing-table/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="money-bill-alt" class="svg-inline--fa fa-money-bill-alt fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M101.22 112A112.5 112.5 0 0 1 48 165.22v181.56A112.5 112.5 0 0 1 101.22 400h437.56A112.5 112.5 0 0 1 592 346.78V165.22A112.5 112.5 0 0 1 538.78 112zM320 368c-53 0-96-50.16-96-112s43-112 96-112 96 50.14 96 112-43 112-96 112z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M616 64H24A24 24 0 0 0 0 88v336a24 24 0 0 0 24 24h592a24 24 0 0 0 24-24V88a24 24 0 0 0-24-24zm-24 282.78A112.5 112.5 0 0 0 538.78 400H101.22A112.5 112.5 0 0 0 48 346.78V165.22A112.5 112.5 0 0 0 101.22 112h437.56A112.5 112.5 0 0 0 592 165.22zM352 288h-16v-88a8 8 0 0 0-8-8h-13.58a24 24 0 0 0-13.31 4l-15.33 10.22a8 8 0 0 0-2.22 11.08l8.88 13.31a8 8 0 0 0 11.08 2.22l.47-.31V288H288a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8z"></path></g></svg>',
					'keyword' => ['Pricing table', 'pricing list', 'price table', 'plans table', 'pricing plans', 'dynamic pricing', 'price comparison', 'Plans & Pricing Table', 'Price Chart']
				],
				'tp-pro-paragraph' => [
					'label' => esc_html__('TP Paragraph','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/advance-text-block/',
					'docUrl' => '#doc',
					'videoUrl' => '#video',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="file-alt" class="svg-inline--fa fa-file-alt fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M384 128H272a16 16 0 0 1-16-16V0H24A23.94 23.94 0 0 0 0 23.88V488a23.94 23.94 0 0 0 23.88 24H360a23.94 23.94 0 0 0 24-23.88V128zm-96 244a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12zm0-64a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12zm0-64a12 12 0 0 1-12 12H108a12 12 0 0 1-12-12v-8a12 12 0 0 1 12-12h168a12 12 0 0 1 12 12z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M377 105L279.1 7a24 24 0 0 0-17-7H256v112a16 16 0 0 0 16 16h112v-6.1a23.9 23.9 0 0 0-7-16.9zM276 352H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12zm0-64H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12zm0-64H108a12 12 0 0 0-12 12v8a12 12 0 0 0 12 12h168a12 12 0 0 0 12-12v-8a12 12 0 0 0-12-12z"></path></g></svg>',
					'keyword' => ['Paragraph', 'wysiwyg', 'editor', 'editor block', 'textarea', 'text area', 'text editor'],
				],
				'tp-process-steps' => [
					'label' => esc_html__('Process Steps','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/process-steps/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="ellipsis-h" class="svg-inline--fa fa-ellipsis-h fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M256 184a72 72 0 1 0 72 72 72 72 0 0 0-72-72z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M432 184a72 72 0 1 0 72 72 72 72 0 0 0-72-72zm-352 0a72 72 0 1 0 72 72 72 72 0 0 0-72-72z"></path></g></svg>',
					'keyword' => ['Process steps', 'post timeline', 'step process', 'steps form', 'Steppers', 'timeline', 'Progress Tracker']
				],
				'tp-progress-bar' => [
					'label' => esc_html__('Progress Bar','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/progress-bar/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="chart-pie" class="svg-inline--fa fa-chart-pie fa-w-17" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 544 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M379.86 443.87c6.85 6.85 6.33 18.48-1.57 24.08A238.14 238.14 0 0 1 243 512C114.83 513.59 4.5 408.51.14 280.37-4.1 155.6 87 51.49 206.16 34.65c9.45-1.34 17.84 6.51 17.84 16.06V288z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M512 223.2C503.72 103.74 408.26 8.28 288.8 0 279.68-.59 272 7.1 272 16.24V240h223.77c9.14 0 16.82-7.68 16.23-16.8zm15.79 64.8H290.5l158 158c6 6 16 6.53 22.19.68a239.5 239.5 0 0 0 73.13-140.86c1.37-9.43-6.48-17.82-16.03-17.82z"></path></g></svg>',
					'keyword' => ['Progress bar', 'progressbar', 'status bar', 'progress indicator', 'scroll progress', 'process progress bar', 'Progress Tracker']
				],
				'tp-row' => [
					'label' => esc_html__('TP Row','tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="grip-lines" class="svg-inline--fa fa-grip-lines fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M512 304v32a16 16 0 0 1-16 16H16a16 16 0 0 1-16-16v-32a16 16 0 0 1 16-16h480a16 16 0 0 1 16 16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M512 176v32a16 16 0 0 1-16 16H16a16 16 0 0 1-16-16v-32a16 16 0 0 1 16-16h480a16 16 0 0 1 16 16z"></path></g></svg>',
					'keyword' => ['Row', 'layout'],
				],
				'tp-site-logo' => [
					'label' => esc_html__('Site Logo','tpgb'),
					'demoUrl' => '#',
					'docUrl' => '#doc',
					'videoUrl' => '#video',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="fire" class="svg-inline--fa fa-fire fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M216 23.86C216 9.06 204.15 0 192 0a24 24 0 0 0-20.1 10.82C48 191.85 224 200 224 288v.81A64 64 0 0 1 160 352h-.87C124 351.5 96 322.18 96 287v-85.5c0-14.52-11.83-24-24.15-24a23.63 23.63 0 0 0-17.28 7.5C27.8 213.16 0 261.33 0 320c0 105.87 86.13 192 192 192s192-86.13 192-192c0-170.29-168-193-168-296.14zM192 444a123.61 123.61 0 0 1-87.78-36.5l2.63 1.21a129.9 129.9 0 0 0 51.39 11.23h1.78A132 132 0 0 0 292 289.71V288c0-27.83-8.69-54.44-25.84-79.11l-.3-.43c10.81 11 20.62 22.28 28.61 34.68C309.16 265.92 316 290.34 316 320a124.15 124.15 0 0 1-124 124z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M265.86 208.46c10.81 11 20.62 22.28 28.61 34.68C309.16 265.92 316 290.34 316 320a123.94 123.94 0 0 1-211.78 87.5l2.63 1.21a129.9 129.9 0 0 0 51.39 11.23h1.78A132 132 0 0 0 292 289.71V288c0-27.83-8.69-54.44-25.84-79.11l-.3-.43"></path></g></svg>',
					'keyword' => ['site logo', 'logo'],
				],
				'tp-stylist-list' => [
					'label' => esc_html__('Stylist Lists','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/stylish-list/',
					'docUrl' => '#doc',
					'videoUrl' => '#video',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="list" class="svg-inline--fa fa-list fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M496 384H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M80 368H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm0-320H16A16 16 0 0 0 0 64v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16V64a16 16 0 0 0-16-16zm0 160H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16z"></path></g></svg>',
					'keyword' => ['Stylish list', 'listing', 'item listing'],
				],
				'tp-scroll-navigation' => [
					'label' => esc_html__('Scroll Navigation','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/one-page-scroll-navigation/',
					'docUrl' => '#doc',
					'videoUrl' => '#video',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="sort-up" class="svg-inline--fa fa-sort-up fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M41.05 288.05h238c21.4 0 32.1 25.9 17 41l-119 119a23.9 23.9 0 0 1-33.8.1l-.1-.1-119.1-119c-15.05-15.05-4.4-41 17-41z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M24.05 183.05l119.1-119A23.9 23.9 0 0 1 177 64a.94.94 0 0 1 .1.1l119 119c15.1 15.1 4.4 41-17 41h-238c-21.45-.05-32.1-25.95-17.05-41.05z"></path></g></svg>',
					'keyword' => ['Scroll navigation', 'slide show', 'slideshow', 'vertical slider'],
				],
				'tp-social-icons' => [
					'label' => esc_html__('Social Icon','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/social-icon/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-square" class="svg-inline--fa fa-facebook-square fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"></path></svg>',
					'keyword' => ['Social Icon', 'Icon', 'link']
				],
				'tp-social-sharing' => [
					'label' => esc_html__('Social Sharing','tpgb'),
					'demoUrl' => '#',
					'docUrl' => '#',
					'videoUrl' => '#',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="share-alt-square" class="svg-inline--fa fa-share-alt-square fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h352a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48zm-96 376a56 56 0 0 1-54.26-69.9l-68-40.77a56 56 0 1 1 0-82.66l68-40.77a56 56 0 1 1 16.48 27.43l-68 40.77a56.39 56.39 0 0 1 0 27.8l68 40.77A56 56 0 1 1 304 408z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M360 352a56 56 0 1 1-110.26-13.9l-68-40.77a56 56 0 1 1 0-82.66l68-40.77a56 56 0 1 1 16.48 27.43l-68 40.77a56.39 56.39 0 0 1 0 27.8l68 40.77A56 56 0 0 1 360 352z"></path></g></svg>',
				],
				'tp-switcher' => [
					'label' => esc_html__('Switcher','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/switcher/',
					'docUrl' => '#doc',
					'videoUrl' => '#video',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="toggle-on" class="svg-inline--fa fa-toggle-on fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M384 384a128 128 0 1 1 128-128 127.93 127.93 0 0 1-128 128z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M384 64H192C86 64 0 150 0 256s86 192 192 192h192c106 0 192-86 192-192S490 64 384 64zm0 320a128 128 0 1 1 128-128 127.93 127.93 0 0 1-128 128z"></path></g></svg>',
					'keyword' => ['Switcher', 'on/off', 'switch control', 'toggle', 'true/false', 'toggle switch', 'state', 'binary']
				],
				'tp-table-content' => [
					'label' => esc_html__('Table of Contents','tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/table-of-contents/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="indent" class="svg-inline--fa fa-indent fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M432 416H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm3.17-128H204.83A12.82 12.82 0 0 0 192 300.83v38.34A12.82 12.82 0 0 0 204.83 352h230.34A12.82 12.82 0 0 0 448 339.17v-38.34A12.82 12.82 0 0 0 435.17 288zm0-128H204.83A12.82 12.82 0 0 0 192 172.83v38.34A12.82 12.82 0 0 0 204.83 224h230.34A12.82 12.82 0 0 0 448 211.17v-38.34A12.82 12.82 0 0 0 435.17 160zM432 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M27.31 363.3l96-96a16 16 0 0 0 0-22.62l-96-96C17.27 138.66 0 145.78 0 160v192c0 14.31 17.33 21.3 27.31 11.3z"></path></g></svg>',
					'keyword' => [ 'Table of Contents', 'Contents', 'toc', 'index', 'listing', 'appendix' ]
				],
				'tp-tabs-tours' => [
					'label' => esc_html__('Tabs Tours', 'tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/tabs-tours/',
					'docUrl' => '#doc',
					'videoUrl' => '#video',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="th-list" class="svg-inline--fa fa-th-list fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M488 352H205.33a24 24 0 0 0-24 24v80a24 24 0 0 0 24 24H488a24 24 0 0 0 24-24v-80a24 24 0 0 0-24-24zm0-320H205.33a24 24 0 0 0-24 24v80a24 24 0 0 0 24 24H488a24 24 0 0 0 24-24V56a24 24 0 0 0-24-24zm0 160H205.33a24 24 0 0 0-24 24v80a24 24 0 0 0 24 24H488a24 24 0 0 0 24-24v-80a24 24 0 0 0-24-24z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M125.33 192H24a24 24 0 0 0-24 24v80a24 24 0 0 0 24 24h101.33a24 24 0 0 0 24-24v-80a24 24 0 0 0-24-24zm0-160H24A24 24 0 0 0 0 56v80a24 24 0 0 0 24 24h101.33a24 24 0 0 0 24-24V56a24 24 0 0 0-24-24zm0 320H24a24 24 0 0 0-24 24v80a24 24 0 0 0 24 24h101.33a24 24 0 0 0 24-24v-80a24 24 0 0 0-24-24z"></path></g></svg>',
					'keyword' => ['Tabs', 'Tours', 'tab content', 'pills', 'toggle']
				],
				'tp-team-listing' => [
					'label' => esc_html__('Team Member', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="users" class="svg-inline--fa fa-users fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M96 224a64 64 0 1 0-64-64 64.06 64.06 0 0 0 64 64zm480 32h-64a63.81 63.81 0 0 0-45.1 18.6A146.27 146.27 0 0 1 542 384h66a32 32 0 0 0 32-32v-32a64.06 64.06 0 0 0-64-64zm-512 0a64.06 64.06 0 0 0-64 64v32a32 32 0 0 0 32 32h65.9a146.64 146.64 0 0 1 75.2-109.4A63.81 63.81 0 0 0 128 256zm480-32a64 64 0 1 0-64-64 64.06 64.06 0 0 0 64 64z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M396.8 288h-8.3a157.53 157.53 0 0 1-68.5 16c-24.6 0-47.6-6-68.5-16h-8.3A115.23 115.23 0 0 0 128 403.2V432a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48v-28.8A115.23 115.23 0 0 0 396.8 288zM320 256a112 112 0 1 0-112-112 111.94 111.94 0 0 0 112 112z"></path></g></svg>',
				],
				'tp-testimonials' => [
					'label' => esc_html__('Testimonials', 'tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-listing/testimonials/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'freemium',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="users" class="svg-inline--fa fa-users fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M96 224a64 64 0 1 0-64-64 64.06 64.06 0 0 0 64 64zm480 32h-64a63.81 63.81 0 0 0-45.1 18.6A146.27 146.27 0 0 1 542 384h66a32 32 0 0 0 32-32v-32a64.06 64.06 0 0 0-64-64zm-512 0a64.06 64.06 0 0 0-64 64v32a32 32 0 0 0 32 32h65.9a146.64 146.64 0 0 1 75.2-109.4A63.81 63.81 0 0 0 128 256zm480-32a64 64 0 1 0-64-64 64.06 64.06 0 0 0 64 64z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M396.8 288h-8.3a157.53 157.53 0 0 1-68.5 16c-24.6 0-47.6-6-68.5-16h-8.3A115.23 115.23 0 0 0 128 403.2V432a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48v-28.8A115.23 115.23 0 0 0 396.8 288zM320 256a112 112 0 1 0-112-112 111.94 111.94 0 0 0 112 112z"></path></g></svg>',
					'keyword' => ['Testimonials', 'testimonial', 'slider', 'client reviews', 'ratings']
				],
				'tp-timeline' => [
					'label' => esc_html__('Timeline', 'tpgb'),
					'demoUrl' => '',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'pro',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="ellipsis-v" class="svg-inline--fa fa-ellipsis-v fa-w-6" role="img" height="40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M96 184a72 72 0 1 0 72 72 72 72 0 0 0-72-72z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M96 152a72 72 0 1 0-72-72 72 72 0 0 0 72 72zm0 208a72 72 0 1 0 72 72 72 72 0 0 0-72-72z"></path></g></svg>',
				],
				'tp-video' => [
					'label' => esc_html__('TP Video', 'tpgb'),
					'demoUrl' => 'https://theplusblocks.com/plus-blocks/video/',
					'docUrl' => '',
					'videoUrl' => '',
					'tag' => 'free',
					'icon' => '<svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="video" class="svg-inline--fa fa-video fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><g class="fa-group"><path class="fa-secondary" fill="currentColor" d="M525.6 410.2L416 334.7V177.3l109.6-75.6c21.3-14.6 50.4.4 50.4 25.8v256.9c0 25.5-29.2 40.4-50.4 25.8z" opacity="0.4"></path><path class="fa-primary" fill="currentColor" d="M0 400.2V111.8A47.8 47.8 0 0 1 47.8 64h288.4a47.8 47.8 0 0 1 47.8 47.8v288.4a47.8 47.8 0 0 1-47.8 47.8H47.8A47.8 47.8 0 0 1 0 400.2z"></path></g></svg>',
					'keyword' => ['Video', 'youtube video', 'vimeo video', 'video player', 'mp4 player', 'web player', 'youtube content', 'Youtube embed', 'youtube iframe']
				],
			];
	}
	
	/* Get Block Filter Search Ajax
	 * @since v1.0.0 
	 */
	public function tpgb_block_search(){
		check_ajax_referer('tpgb-addons', 'security');
		if(isset($_POST['filter']) && !empty($_POST['filter'])){
			$this->block_listout();
			$filter_block =[];
			if(!empty($this->block_lists)){
				
				foreach($this->block_lists as $key => $block){
					$label = strtolower($block['label']);
					$filter_block[$key] = $block;
					$filter_block[$key]['filter'] = 'no';
					if(!empty($block['keyword'])){
						foreach($block['keyword'] as $keyword){
							$key_word= strtolower($keyword);
							if(strpos($key_word, sanitize_text_field($_POST['filter'])) !== false){
								$filter_block[$key]['filter'] = 'yes';
							}
						}
					}
					if(strpos($label, sanitize_text_field($_POST['filter'])) !== false){
						$filter_block[$key]['filter'] = 'yes';
					}
				}
			}
			$this->block_lists = $filter_block;
			
		}else{
			$this->block_listout();
		}
		
		$output = $this->tpgb_block_list_rendered();
		echo $output;
		exit();
	}
	
	private function tpgb_block_list_rendered(){
		$block_list = $this->block_lists;
		$output ='';
		$get_blocks_save = get_option( 'tpgb_normal_blocks_opts' );
		$save_block ='';
		if(!empty($get_blocks_save['enable_normal_blocks'])){
			$save_block = $get_blocks_save['enable_normal_blocks'];
		}
		
		if(!empty($block_list)){
			foreach ($block_list as $key => $block){
				$filter_class = '';
				if(!empty($block['filter'])){
					$filter_class = 'filter-block-'.esc_attr($block['filter']);
				}
				$output .='<div class="tpgb-panel-col tpgb-panel-col-25 block-'.esc_attr($block['tag']).' '.esc_attr($filter_class).'">';
					$output .='<div class="plus-block-list-wrap">';
						$output .='<div class="block-pin-free-pro">'.esc_html($block['tag']).'</div>';
						$output .='<div class="plus-block-list-inner">';
							$output .= (!empty($block['icon'])) ? '<span class="block-icon">'.$block['icon'].'</span>' : '';
							$output .='<span>'.esc_html($block['label']).'</span>';
							$output .='<span class="block-group-info">';
								$output .='<span class="block-hover-info">';
								$output .='<svg xmlns="http://www.w3.org/2000/svg" width="4.347" height="13.909" viewBox="0 0 4.347 13.909">
										  <g transform="translate(-176)">
											<path d="M178.173,192A2.176,2.176,0,0,0,176,194.173v4.347a2.173,2.173,0,0,0,4.347,0v-4.347A2.176,2.176,0,0,0,178.173,192Zm1.3,6.278a1.481,1.481,0,0,1-1.407,1.545h-.04a.136.136,0,0,1-.076-.246,1.586,1.586,0,0,0,.644-1.3v-3.864a1.586,1.586,0,0,0-.644-1.3.136.136,0,0,1,.076-.246h.04a1.481,1.481,0,0,1,1.407,1.545Z" transform="translate(0 -186.784)" fill="#888"/>
											<path d="M178.173,0a2.173,2.173,0,1,0,2.173,2.173A2.176,2.176,0,0,0,178.173,0Zm.049,3.476h-.054a.126.126,0,0,1-.071-.23,1.3,1.3,0,0,0,0-2.148.126.126,0,0,1,.07-.23h0a1.3,1.3,0,0,1,.049,2.607Z" transform="translate(0)" fill="#888"/>
										  </g>
										</svg>';
							$output .='</span>';
							$output .='<a href="'.esc_url($block['demoUrl']).'" target="_blank" class="block-hover-details block-info-demo">';
								$output .='<svg xmlns="http://www.w3.org/2000/svg" width="10" height="9.009" viewBox="0 0 10 9.009">
									  <path d="M9.755,36.8a.787.787,0,0,0-.589-.255H.833a.788.788,0,0,0-.589.255A.851.851,0,0,0,0,37.409V43.3a.851.851,0,0,0,.245.612.788.788,0,0,0,.589.254H3.667a1.171,1.171,0,0,1-.083.422,3.905,3.905,0,0,1-.167.379.614.614,0,0,0-.083.238.339.339,0,0,0,.1.244.314.314,0,0,0,.234.1H6.333a.314.314,0,0,0,.234-.1.339.339,0,0,0,.1-.244.635.635,0,0,0-.083-.235,4.052,4.052,0,0,1-.167-.384,1.18,1.18,0,0,1-.083-.42H9.167a.787.787,0,0,0,.588-.254A.851.851,0,0,0,10,43.3v-5.89A.851.851,0,0,0,9.755,36.8Zm-.422,5.116a.17.17,0,0,1-.049.122.158.158,0,0,1-.117.051H.833a.157.157,0,0,1-.117-.051.17.17,0,0,1-.049-.122v-4.5a.17.17,0,0,1,.049-.122.158.158,0,0,1,.117-.051H9.167a.157.157,0,0,1,.117.051.17.17,0,0,1,.049.122v4.5Z" transform="translate(0 -36.543)" />
									</svg>';
							$output .='</a>';
							$output .='<a href="'.esc_url($block['docUrl']).'" target="_blank" class="block-hover-details block-info-doc">';
								$output .='<svg xmlns="http://www.w3.org/2000/svg" width="8.053" height="10.166" viewBox="0 0 8.053 10.166">
									<g transform="translate(-41.796)">
										<g transform="translate(42.06 1.188)">
										  <path d="M226.884,303.02l-2.231,2.218v-1.69a.528.528,0,0,1,.528-.528Z" transform="translate(-220.296 -296.551)"/>
										</g>
										<g transform="translate(41.796)">
										  <path d="M46.39,45.813h-3.8a.792.792,0,0,1-.792-.792V37.363a.792.792,0,0,1,.792-.792h5.545a.792.792,0,0,1,.792.792v5.928a.264.264,0,0,1-.079.185h-.013L46.575,45.72A.264.264,0,0,1,46.39,45.813Zm-3.8-8.713a.264.264,0,0,0-.264.264V45.02a.264.264,0,0,0,.264.264h3.7l2.112-2.1V37.363a.264.264,0,0,0-.264-.264Z" transform="translate(-41.796 -35.647)"/>
										  <path d="M214.468,295.344a.264.264,0,0,1-.264-.264v-1.716a.792.792,0,0,1,.792-.792h1.716a.264.264,0,1,1,0,.528H215a.264.264,0,0,0-.264.264v1.716A.264.264,0,0,1,214.468,295.344Z" transform="translate(-209.847 -285.179)"/>
										  <path d="M137.2,206.9h-3.656a.269.269,0,1,1,0-.528H137.2a.269.269,0,1,1,0,.528Z" transform="translate(-131.798 -201.152)" />
										  <path d="M137.2,154.65h-3.656a.269.269,0,1,1,0-.528H137.2a.269.269,0,1,1,0,.528Z" transform="translate(-131.798 -150.227)" />
										  <path d="M137.2,102.406h-3.656a.269.269,0,1,1,0-.528H137.2a.269.269,0,1,1,0,.528Z" transform="translate(-131.798 -99.304)" />
										  <path d="M85.232,7.512a.264.264,0,0,1-.264-.264V1.056A.528.528,0,0,0,84.44.528H78.631a.264.264,0,1,1,0-.528H84.44A1.056,1.056,0,0,1,85.5,1.056V7.248A.264.264,0,0,1,85.232,7.512Z" transform="translate(-77.443)" />
										</g>
									</g>
									</svg>';
							$output .='</a>';
							$output .='<a href="'.esc_url($block['videoUrl']).'" target="_blank" class="block-hover-details block-info-video">';
								$output .='<svg xmlns="http://www.w3.org/2000/svg" width="7.801" height="10.037" viewBox="0 0 7.801 10.037">
									  <path d="M47.444,44.945a.4.4,0,0,1-.4-.4V35.308a.4.4,0,0,1,.62-.334l7,4.618a.4.4,0,0,1,.181.334.4.4,0,0,1-.181.334l-7,4.619a.4.4,0,0,1-.22.066Zm.4-8.894V43.8l5.874-3.876Z" transform="translate(-47.044 -34.909)" />
									</svg>';
							$output .='</a>';
							$output .='</span>';
						$output .='</div>';
						$pro_disable = '';
						if(!defined('TPGBP_VERSION') && $block['tag']=='pro'){
							$pro_disable = 'disabled="disabled"';
						}
						$checked = '';
						if(!empty($save_block) && in_array($key, $save_block)){
							$checked = 'checked="checked"';
						}
						
						$output .='<div class="block-check-wrap"><input type="checkbox" class="block-list-checkbox" name="enable_normal_blocks[]" id="'.esc_attr($key).'" value="'.esc_attr($key).'" '.$checked.' '.$pro_disable.'> <label for="'.esc_attr($key).'"></label></div>';
					$output .='</div>';
				$output .='</div>';
			}
		}
		return $output;
	}
	
	/**
     * Theplus Gutenberg Display Page
     * @since  1.0.0
     */
    public function admin_page_display() {
		$option_tabs = self::option_fields();
		$tab_forms   = array();
		
		$output ='';
		
		$output .='<div class="'.esc_attr($this->key).'">';
			
			$output .='<div id="tpgb-setting-header-wrapper">';
				$output .='<div class="tpgb-head-inner">';
				
					$options = get_option( 'tpgb_white_label' );
					if(defined('TPGBP_VERSION') && (!empty($options['tpgb_plus_logo']))){
						$output .='<img src="'.esc_url($options['tpgb_plus_logo']).'" style="max-width:150px;"/>';
					}else{
						$output .='<svg xmlns="http://www.w3.org/2000/svg" width="250" viewBox="0 0 976.078 341.4">
  <g transform="translate(-21 -2711)">
    <rect width="172" height="76" rx="10" transform="translate(825 2711)" fill="#fff"/>
    <text transform="translate(850 2769)" fill="#6f1df1" font-size="51" font-family="Roboto-Medium, Roboto" font-weight="500"><tspan x="0" y="0">BETA</tspan></text>
    <g transform="translate(21 2787)">
      <g>
        <g>
          <g opacity="0.4">
            <path d="M139.14,108.15H126.28v18.12H108.15v12.86h18.13v18.13h12.86V139.13h18.11V126.27H139.14V108.15" fill="#fff"/>
            <path d="M132.7,0H84.18V126.27H45v12.86H84.18v18.12H97V12.86h35.7a35.73,35.73,0,0,1,35.66,35.46V73.18h12.86V48.31A48.56,48.56,0,0,0,132.7,0" fill="#fff"/>
            <path d="M139.13,45H126.27V84.18H108.15V97H252.54v35.7a35.73,35.73,0,0,1-35.46,35.66H192.23v12.86h24.88A48.53,48.53,0,0,0,265.4,132.7V84.18H139.13V45" fill="#fff"/>
            <path d="M181.23,108.15H168.37V252.54H132.71a35.73,35.73,0,0,1-35.66-35.46V192.23H84.19V217.1a48.53,48.53,0,0,0,48.52,48.3h48.52V139.13h39.15V126.27H181.23V108.15" fill="#fff"/>
            <path d="M73.18,84.19H48.31A48.56,48.56,0,0,0,0,132.71v48.52H126.27v39.15h12.86V181.23h18.12V168.37H12.86V132.71A35.72,35.72,0,0,1,48.33,97.05H73.18V84.19" fill="#fff"/>
          </g>
          <path  d="M97,12.86h35.7a35.73,35.73,0,0,1,35.66,35.46V73.18h12.86V48.31A48.56,48.56,0,0,0,132.7,0H84.18V157.25H97Z" fill="#fff"/>
          <path d="M170.3,126.27h-44v12.86h42.09V252.54H132.71a35.73,35.73,0,0,1-35.66-35.46V192.23H84.19V217.1a48.53,48.53,0,0,0,48.52,48.3h48.52V126.27Z" fill="#fff"/>
          <path d="M393.3,44.93H313.65V61.25H343.2v81.46h20.15V61.25H393.3V44.93" fill="#fff"/>
          <path d="M490,44.93H469.86V84.55H430.57V44.93H410.42v97.78h20.15V100.8h39.29v41.91H490V44.93" fill="#fff"/>
          <path d="M578.65,44.93H513.24v97.78h65.54V126.53H533.39v-26.2h38.68V84.55H533.39V61.25h45.26V44.93" fill="#fff"/>
          <path d="M656.28,91.94V61.25h18.46q7.46.14,11.69,4.57t4.23,11.61q0,7-4.19,10.75t-12.2,3.76h-18m18-47H636.13v97.78h20.15V108.26h17.66q17.38,0,27.23-8.3T711,77.3a31.94,31.94,0,0,0-4.5-16.89A29.72,29.72,0,0,0,693.65,49a44.1,44.1,0,0,0-19.38-4" fill="#fff"/>
          <path d="M750.63,44.93H730.48v97.78h62.93V126.53H750.63V44.93" fill="#fff"/>
          <path d="M883,44.93H862.78V110q-.27,17.86-17.26,17.87-8.13,0-12.73-4.4t-4.6-14V44.93H808.05v65.14q.19,15.86,10.27,24.92t27.2,9.06q17.38,0,27.43-9.33t10-25.39V44.93" fill="#fff"/>
          <path d="M940.14,43.59a48.23,48.23,0,0,0-18.77,3.49,29.2,29.2,0,0,0-12.83,9.7,23.62,23.62,0,0,0-4.46,14.14q0,15.24,16.65,24.24a102.137,102.137,0,0,0,16.59,6.69q10.47,3.39,14.5,6.44a10.33,10.33,0,0,1,4,8.76,9.51,9.51,0,0,1-4,8.17q-4,2.91-11.21,2.92-19.33,0-19.34-16.19H901.05a29,29,0,0,0,4.87,16.66,32.51,32.51,0,0,0,14.14,11.31,50,50,0,0,0,20.55,4.13q16.25,0,25.86-7.22t9.6-19.91a25.529,25.529,0,0,0-7.86-19.07Q960.35,90.19,943.16,85q-9.33-2.8-14.16-6t-4.81-8a9.86,9.86,0,0,1,4.1-8.23q4.09-3.12,11.48-3.12,7.66,0,11.89,3.72t4.23,10.45h20.15A28,28,0,0,0,971.5,58.1a29.72,29.72,0,0,0-12.72-10.71,44,44,0,0,0-18.67-3.79" fill="#fff"/>
          <path d="M354.12,216.21a6.32,6.32,0,0,1-4.23-1.39,4.79,4.79,0,0,1-1.65-3.86q0-5.66,9.65-5.65H362v6.3a7.7,7.7,0,0,1-3.21,3.32,9.11,9.11,0,0,1-4.68,1.28m1.7-27.28a15,15,0,0,0-6.15,1.25,11.2,11.2,0,0,0-4.42,3.39,7.24,7.24,0,0,0-1.63,4.44h5.28a4.18,4.18,0,0,1,1.9-3.43,7.62,7.62,0,0,1,4.71-1.42,6.6,6.6,0,0,1,4.85,1.63,5.91,5.91,0,0,1,1.65,4.39v2.41h-5.1q-6.61,0-10.26,2.66A8.68,8.68,0,0,0,343,211.7a8.3,8.3,0,0,0,2.91,6.51,10.87,10.87,0,0,0,7.45,2.57,11.72,11.72,0,0,0,8.74-3.8,11.14,11.14,0,0,0,.74,3.23h5.51v-.45a17.291,17.291,0,0,1-1.08-6.73V198.9a9.6,9.6,0,0,0-3.12-7.31q-3-2.66-8.32-2.66" fill="#fff"/>
          <path d="M391.65,216.32a7,7,0,0,1-6-2.95q-2.16-3-2.16-8.17,0-5.85,2.16-8.83a7,7,0,0,1,6-3,7.74,7.74,0,0,1,7.24,4.34v14.11a7.67,7.67,0,0,1-7.3,4.48m12.55-39.7H399v16a11.4,11.4,0,0,0-17.32.61q-3.35,4.31-3.35,11.37v.4q0,7,3.36,11.41a10.52,10.52,0,0,0,8.71,4.37,10.71,10.71,0,0,0,8.77-3.86l.25,3.29h4.83V176.62" fill="#fff"/>
          <path d="M428.5,216.32a7,7,0,0,1-6-2.95c-1.44-2-2.15-4.69-2.15-8.17,0-3.9.71-6.84,2.15-8.83a7,7,0,0,1,6-3,7.73,7.73,0,0,1,7.24,4.34v14.11a7.67,7.67,0,0,1-7.3,4.48m12.55-39.7H435.8v16a11.4,11.4,0,0,0-17.32.61q-3.35,4.31-3.35,11.37v.4q0,7,3.37,11.41a10.49,10.49,0,0,0,8.7,4.37,10.71,10.71,0,0,0,8.77-3.86l.25,3.29h4.83V176.62" fill="#fff"/>
          <path d="M465.87,216.49a7.6,7.6,0,0,1-6.35-3.09,13.14,13.14,0,0,1-2.37-8.2c0-3.84.8-6.8,2.38-8.86a8,8,0,0,1,12.65,0,13.11,13.11,0,0,1,2.4,8.19q0,5.66-2.36,8.79a7.53,7.53,0,0,1-6.35,3.13m-.06-27.56a13.45,13.45,0,0,0-7.22,2,13.19,13.19,0,0,0-4.94,5.56,18.07,18.07,0,0,0-1.78,8.12v.37q0,7.16,3.88,11.49a12.9,12.9,0,0,0,10.12,4.35,13.54,13.54,0,0,0,7.33-2,13,13,0,0,0,4.91-5.55,18.18,18.18,0,0,0,1.72-8v-.37q0-7.22-3.87-11.55a13,13,0,0,0-10.15-4.35" fill="#fff"/>
          <path d="M504.82,188.93a11.2,11.2,0,0,0-9.2,4.43l-.17-3.86h-5v30.71h5.25V198.33a9.32,9.32,0,0,1,3-3.58,7.59,7.59,0,0,1,4.49-1.36,6.09,6.09,0,0,1,4.64,1.59,7.1,7.1,0,0,1,1.52,4.91v20.32h5.25V199.92q-.09-11-9.82-11" fill="#fff"/>
          <path d="M537.75,188.93a12.48,12.48,0,0,0-8.16,2.61,8,8,0,0,0-3.19,6.39,6.79,6.79,0,0,0,1.12,3.92,9.06,9.06,0,0,0,3.46,2.84,26.739,26.739,0,0,0,6.33,2,15.38,15.38,0,0,1,5.59,2,3.87,3.87,0,0,1,1.61,3.31,3.7,3.7,0,0,1-1.81,3.22,8.48,8.48,0,0,1-4.78,1.2,8.22,8.22,0,0,1-5.21-1.54,5.45,5.45,0,0,1-2.11-4.19h-5.25a8.73,8.73,0,0,0,1.6,5.06,10.57,10.57,0,0,0,4.44,3.65,15.47,15.47,0,0,0,6.53,1.31,13.83,13.83,0,0,0,8.54-2.48,7.87,7.87,0,0,0,3.3-6.6,7.26,7.26,0,0,0-1.18-4.19,9.33,9.33,0,0,0-3.62-2.94,28.723,28.723,0,0,0-6.37-2.1,18.569,18.569,0,0,1-5.44-1.84,3.2,3.2,0,0,1-1.5-2.87,3.94,3.94,0,0,1,1.61-3.26,7.15,7.15,0,0,1,4.49-1.25,6.85,6.85,0,0,1,4.61,1.56,4.74,4.74,0,0,1,1.83,3.72h5.28a8.56,8.56,0,0,0-3.25-6.9,12.81,12.81,0,0,0-8.47-2.7" fill="#fff"/>
          <path d="M591.81,176q-4.81,0-7.46,2.69c-1.76,1.81-2.64,4.35-2.64,7.64v3.15h-4.86v4.06h4.86v26.65H587V193.56h6.56V189.5H587v-3.25a6,6,0,0,1,1.39-4.28,5.1,5.1,0,0,1,3.95-1.5,15,15,0,0,1,2.83.26l.29-4.23a13.88,13.88,0,0,0-3.61-.48" fill="#fff"/>
          <path d="M616,216.49a7.6,7.6,0,0,1-6.35-3.09,13.14,13.14,0,0,1-2.37-8.2c0-3.84.8-6.8,2.39-8.86a8,8,0,0,1,12.64,0,13.11,13.11,0,0,1,2.4,8.19c0,3.77-.79,6.7-2.35,8.79a7.56,7.56,0,0,1-6.36,3.13m-.06-27.56a13.47,13.47,0,0,0-7.22,2,13.25,13.25,0,0,0-4.94,5.56,18.2,18.2,0,0,0-1.78,8.12v.37q0,7.16,3.88,11.49A12.91,12.91,0,0,0,616,220.78a13.54,13.54,0,0,0,7.33-2,13,13,0,0,0,4.91-5.55,18.181,18.181,0,0,0,1.72-8v-.37q0-7.22-3.87-11.55a13,13,0,0,0-10.15-4.35" fill="#fff"/>
          <path d="M653.11,188.93a8.16,8.16,0,0,0-7.32,4.12l-.09-3.55h-5.1v30.71h5.25v-21.8q1.845-4.395,7-4.4a16.08,16.08,0,0,1,2.58.2v-4.88a5.45,5.45,0,0,0-2.33-.4" fill="#fff"/>
          <path id="Path_8980" data-name="Path 8980" d="M698.89,178.32q-7.89,0-12.25,5.14t-4.36,14.47v3.6a23.32,23.32,0,0,0,2.2,10.14,15.83,15.83,0,0,0,6.06,6.74,17,17,0,0,0,9,2.37,23.781,23.781,0,0,0,8.76-1.49,12.57,12.57,0,0,0,5.86-4.5V199.63h-15v4.46h9.56v9.26a8.17,8.17,0,0,1-3.8,2.29,18.72,18.72,0,0,1-5.36.68,10.3,10.3,0,0,1-8.6-4.1q-3.18-4.11-3.18-11.07v-3.38q0-7.29,2.85-11.15a9.653,9.653,0,0,1,8.3-3.85q8.24,0,9.77,8.23h5.45a14.46,14.46,0,0,0-4.83-9.38q-3.94-3.3-10.41-3.3" fill="#fff"/>
          <path id="Path_8981" data-name="Path 8981" d="M750.52,189.5h-5.25v22.34q-2,4.49-8,4.48-5.68,0-5.68-7V189.5h-5.25v20c0,3.73.88,6.55,2.6,8.45s4.2,2.85,7.48,2.85q5.93,0,9-3.61l.12,3h5V189.5" fill="#fff"/>
          <path id="Path_8982" data-name="Path 8982" d="M769.69,182.07h-5.25v7.43h-5.59v4.06h5.59v19a9.17,9.17,0,0,0,1.79,6.05,6.53,6.53,0,0,0,5.31,2.13,15.19,15.19,0,0,0,4-.57V216a12.46,12.46,0,0,1-2.5.34,3.24,3.24,0,0,1-2.61-.92,4.28,4.28,0,0,1-.77-2.77V193.56h5.74V189.5h-5.74v-7.43" fill="#fff"/>
          <path id="Path_8983" data-name="Path 8983" d="M789.71,202.05a11.24,11.24,0,0,1,2.67-6.49,7,7,0,0,1,5.34-2.31,6.59,6.59,0,0,1,5.22,2.2,10,10,0,0,1,2.16,6.2v.4H789.71m8-13.12a12.31,12.31,0,0,0-6.8,2,13.6,13.6,0,0,0-4.88,5.62,18.35,18.35,0,0,0-1.75,8.16v1q0,6.87,3.92,11a13.43,13.43,0,0,0,10.16,4.1q7.73,0,11.58-5.93l-3.21-2.5a11.771,11.771,0,0,1-3.38,3,9.18,9.18,0,0,1-4.71,1.13,8.33,8.33,0,0,1-6.45-2.79,10.921,10.921,0,0,1-2.66-7.34h20.81v-2.19q0-7.35-3.32-11.29t-9.31-3.95" fill="#fff"/>
          <path id="Path_8984" data-name="Path 8984" d="M834.82,188.93a11.18,11.18,0,0,0-9.19,4.43l-.17-3.86h-5v30.71h5.25V198.33a9.23,9.23,0,0,1,3-3.58,7.56,7.56,0,0,1,4.48-1.36,6.08,6.08,0,0,1,4.64,1.59,7,7,0,0,1,1.52,4.91v20.32h5.25V199.92q-.07-11-9.82-11" fill="#fff"/>
          <path id="Path_8985" data-name="Path 8985" d="M869.4,216.32a7.92,7.92,0,0,1-7.52-4.82V198.21a7.71,7.71,0,0,1,7.47-4.82,6.85,6.85,0,0,1,6,2.92q2.07,2.92,2.07,8.29,0,5.88-2.1,8.8a6.82,6.82,0,0,1-5.88,2.92m-7.52-39.7h-5.25v43.59h4.83l.25-3.55a10.7,10.7,0,0,0,9,4.12,10.32,10.32,0,0,0,8.68-4.27q3.24-4.27,3.24-11.31v-.46q0-7.35-3.19-11.58a10.41,10.41,0,0,0-8.79-4.23,10.66,10.66,0,0,0-8.77,3.95V176.62" fill="#fff"/>
          <path id="Path_8986" data-name="Path 8986" d="M897.4,202.05a11.24,11.24,0,0,1,2.67-6.49,7,7,0,0,1,5.33-2.31,6.6,6.6,0,0,1,5.23,2.2,10,10,0,0,1,2.15,6.2v.4H897.4m8-13.12a12.27,12.27,0,0,0-6.79,2,13.62,13.62,0,0,0-4.89,5.62,18.49,18.49,0,0,0-1.74,8.16v1q0,6.87,3.91,11a13.45,13.45,0,0,0,10.17,4.1q7.71,0,11.58-5.93l-3.21-2.5a11.9,11.9,0,0,1-3.38,3,9.2,9.2,0,0,1-4.71,1.13,8.37,8.37,0,0,1-6.46-2.79,10.92,10.92,0,0,1-2.65-7.34H918v-2.19q0-7.35-3.32-11.29t-9.31-3.95" fill="#fff"/>
          <path id="Path_8987" data-name="Path 8987" d="M940.69,188.93a8.16,8.16,0,0,0-7.32,4.12l-.08-3.55h-5.11v30.71h5.25v-21.8q1.85-4.4,7-4.4a15.9,15.9,0,0,1,2.58.2v-4.88a5.45,5.45,0,0,0-2.33-.4" fill="#fff"/>
          <path id="Path_8988" data-name="Path 8988" d="M963.5,216.32a6.9,6.9,0,0,1-6-3q-2.13-3-2.13-8.16,0-5.85,2.15-8.83a7,7,0,0,1,6-3,7.77,7.77,0,0,1,7.24,4.4v14a7.68,7.68,0,0,1-7.29,4.51m-1.25-27.39a10.61,10.61,0,0,0-8.8,4.25q-3.32,4.23-3.32,11.42,0,7.49,3.33,11.83a10.49,10.49,0,0,0,8.73,4.35,10.78,10.78,0,0,0,8.6-3.64v2.65a8.23,8.23,0,0,1-2.14,6.07,8,8,0,0,1-6,2.16,10.269,10.269,0,0,1-8.259-4.168L951.69,227a11.119,11.119,0,0,0,4.85,3.88,15.75,15.75,0,0,0,6.5,1.42q6,0,9.5-3.43t3.53-9.4v-30h-4.8l-.25,3.41a10.57,10.57,0,0,0-8.77-4" fill="#fff"/>
        </g>
      </g>
    </g>
  </g>
</svg>
';
					}
					$output .='<div class="tpgb-panel-head-inner">';
						$output .='<h2 class="tpgb-head-setting-panel">'.esc_html__('Setting Panel','tpgb').'</h2>';
						$output .='<div class="tpgb-current-version"> '.esc_html__('Version','tpgb').' '.TPGB_VERSION.'</div>';
					$output .='</div>';
				$output .='</div>';
			$output .='</div>';
			
			if( "tpgb_gutenberg_settings" != $_GET['page'] ) {
				$output .='<div class="tpgb-nav-tab-wrapper">';
					$output .='<div class="nav-tab-wrapper">';
						ob_start();
						foreach ($option_tabs as $option_tab):
							$tab_slug  = $option_tab['id'];
							$nav_class = 'nav-tab';
							if ($tab_slug == $_GET['page']) {
								$nav_class .= ' nav-tab-active'; //add active class to current tab
								$tab_forms[] = $option_tab; //add current tab to forms to be rendered
							}
							$navicon = '';
							if($tab_slug == "tpgb_welcome_page"){
								$navicon = '<svg class="tab-nav-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120" preserveAspectRatio="none">
											<path d="M109.148 120h-36c-1.104 0-2-0.9-2-2v-34h-20v34c0 1.1-0.896 2-2 2h-36c-1.104 0-2-0.9-2-2v-62h-8.648c-0.832 0-1.576-0.512-1.868-1.288-0.296-0.776-0.080-1.652 0.54-2.204l57.324-51c0.736-0.656 1.836-0.676 2.596-0.056l14.060 11.54v-10.992c0-1.104 0.896-2 2-2h20c1.1 0 2 0.896 2 2v31.648l19.74 18.908c0.588 0.568 0.776 1.432 0.472 2.192-0.308 0.756-1.044 1.252-1.86 1.252h-6.356v62c0 1.1-0.896 2-2 2zM75.148 116h32v-62c0-1.104 0.896-2 2-2h3.376l-16.756-16.056c-0.396-0.376-0.612-0.9-0.612-1.444v-30.5h-16v13.22c0 0.772-0.44 1.48-1.144 1.808-0.7 0.328-1.528 0.232-2.124-0.26l-16-13.136-52.124 46.368h5.396c1.104 0 2 0.896 2 2v62h32v-34c0-1.1 0.896-2 2-2h24c1.104 0 2 0.9 2 2v34h-0.012z"></path>
										</svg>';
							}
							if($tab_slug == "tpgb_normal_blocks_opts"){
								$navicon = '<svg class="tab-nav-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120" preserveAspectRatio="none">
									<path d="M118 110h-116c-1.104 0-2-0.9-2-2v-96c0-1.104 0.896-2 2-2h116c1.1 0 2 0.896 2 2v96c0 1.1-0.9 2-2 2zM4 106h112v-92h-112v92z"></path>
									<path d="M116 34h-112c-1.104 0-2-0.896-2-2s0.896-2 2-2h112c1.1 0 2 0.896 2 2s-0.9 2-2 2z"></path>
									<path d="M46.904 97.048c-0.412 0-0.824-0.132-1.172-0.384-0.704-0.508-1-1.416-0.732-2.236l4.932-15.304-12.896-9.516c-0.696-0.516-0.984-1.416-0.712-2.24s1.036-1.38 1.9-1.38h15.916l4.916-15.136c0.536-1.648 3.268-1.648 3.804 0l4.916 15.136h15.916c0.864 0 1.628 0.56 1.904 1.38 0.264 0.82-0.016 1.724-0.716 2.24l-12.896 9.516 4.928 15.236c0.264 0.824-0.032 1.728-0.732 2.236s-1.648 0.508-2.348 0l-12.876-9.32-12.88 9.384c-0.348 0.256-0.76 0.388-1.172 0.388zM44.3 70l9.164 6.756c0.692 0.508 0.98 1.408 0.716 2.228l-3.488 10.828 9.088-6.616c0.7-0.516 1.648-0.516 2.348-0.008l9.088 6.584-3.484-10.776c-0.264-0.82 0.024-1.712 0.712-2.224l9.164-6.76h-11.288c-0.868 0-1.636-0.564-1.908-1.388l-3.464-10.664-3.464 10.664c-0.268 0.824-1.036 1.388-1.904 1.388h-11.284v-0.012z"></path>
									<path d="M15.956 23c0 1.381-1.119 2.5-2.5 2.5s-2.5-1.119-2.5-2.5c0-1.381 1.119-2.5 2.5-2.5s2.5 1.119 2.5 2.5z"></path>
									<path d="M25.956 23c0 1.381-1.119 2.5-2.5 2.5s-2.5-1.119-2.5-2.5c0-1.381 1.119-2.5 2.5-2.5s2.5 1.119 2.5 2.5z"></path>
									<path d="M35.956 23c0 1.381-1.119 2.5-2.5 2.5s-2.5-1.119-2.5-2.5c0-1.381 1.119-2.5 2.5-2.5s2.5 1.119 2.5 2.5z"></path>
								</svg>';
							}
							if($tab_slug == "tpgb_connection_data"){
								$navicon = '<svg class="tab-nav-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120">
									<g id="icomoon-ignore"><line stroke-width="1" stroke="#449FDB" opacity=""></line>
									</g>
									<path d="M66.968 91.64c-0.1 0-0.208-0.008-0.312-0.024-0.752-0.116-1.372-0.656-1.592-1.384l-8.488-27.32-27.32-8.488c-0.728-0.228-1.26-0.848-1.38-1.6s0.2-1.504 0.82-1.944l23.36-16.516-0.364-28.604c-0.008-0.76 0.416-1.464 1.088-1.808 0.68-0.344 1.496-0.276 2.104 0.18l22.924 17.104 27.084-9.18c0.72-0.244 1.516-0.060 2.056 0.48 0.54 0.536 0.728 1.332 0.48 2.056l-9.18 27.092 17.096 22.924c0.46 0.608 0.528 1.424 0.18 2.1-0.352 0.676-1.072 1.080-1.808 1.092l-28.608-0.368-16.516 23.352c-0.368 0.544-0.984 0.856-1.624 0.856zM34.312 51.808l24.452 7.6c0.628 0.196 1.12 0.688 1.316 1.316l7.596 24.448 14.784-20.908c0.38-0.536 1.016-0.828 1.656-0.844l25.608 0.328-15.308-20.52c-0.392-0.528-0.5-1.216-0.292-1.84l8.224-24.252-24.256 8.224c-0.624 0.212-1.312 0.1-1.836-0.292l-20.52-15.308 0.328 25.608c0.008 0.66-0.308 1.28-0.844 1.66l-20.908 14.78z"></path>
									<path d="M6.252 116.264c-0.512 0-1.024-0.196-1.416-0.584-0.78-0.776-0.78-2.048 0-2.828l41.356-41.352c0.78-0.78 2.048-0.78 2.828 0s0.78 2.048 0 2.828l-41.356 41.352c-0.392 0.392-0.904 0.584-1.412 0.584z"></path>
								</svg>';
							}
							if($tab_slug == "tpgb_performance"){
								$navicon = '<svg class="tab-nav-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120" preserveAspectRatio="none">
									<path d="M69.532 99.592h-19.064c-21.288 0-21.532-56.236-21.532-58.624 0-16.388 16.056-32.804 30.464-37.284 0.392-0.12 0.8-0.12 1.192 0 12.768 3.972 30.464 18.988 30.464 36.848 0.004 2.408-0.24 59.060-21.524 59.060zM60 7.692c-11.5 3.892-27.064 18.132-27.064 33.272 0 21.12 4.756 54.624 17.532 54.624h19.064c13.252 0 17.532-37.096 17.532-55.064-0.004-15.448-15.792-29.024-27.064-32.832z"></path>
									<path d="M35.156 116.408c-0.152 0-0.3-0.016-0.448-0.056-0.632-0.148-1.16-0.592-1.404-1.196l-10.312-25.316c-0.316-0.768-0.116-1.656 0.496-2.224l11.016-10.188c0.808-0.752 2.072-0.704 2.828 0.108 0.752 0.812 0.7 2.080-0.112 2.828l-10 9.252 8.72 21.404 16.612-14.916c0.82-0.736 2.084-0.672 2.824 0.156 0.744 0.82 0.668 2.084-0.152 2.828l-18.732 16.812c-0.372 0.328-0.848 0.508-1.336 0.508z"></path>
									<path d="M84.844 116.408c-0.488 0-0.964-0.18-1.336-0.516l-18.728-16.812c-0.824-0.74-0.896-2-0.152-2.828 0.736-0.82 2-0.888 2.824-0.148l16.608 14.916 8.72-21.4-10-9.252c-0.812-0.752-0.864-2.016-0.112-2.828s2.016-0.86 2.828-0.112l11.016 10.196c0.612 0.568 0.808 1.448 0.496 2.22l-10.312 25.316c-0.244 0.604-0.764 1.052-1.404 1.196-0.148 0.036-0.3 0.052-0.448 0.052z"></path>
								</svg>';
							}
							
							if($tab_slug == "tpgb_custom_css_js"){
								$navicon = '<svg class="tab-nav-icon" xmlns="http://www.w3.org/2000/svg" width="36" height="30" viewBox="0 0 36 30">
									  <g transform="translate(0 -2.5)">
										<path d="M35.4,32.5H.6a.6.6,0,0,1-.6-.6V3.1a.6.6,0,0,1,.6-.6H35.4a.6.6,0,0,1,.6.6V31.9A.6.6,0,0,1,35.4,32.5ZM1.2,31.3H34.8V3.7H1.2Z"/>
										<path d="M34.7,8.7H1.1a.6.6,0,0,1,0-1.2H34.7a.6.6,0,0,1,0,1.2Z" transform="translate(0.1 1)"/>
										<path d="M11.153,26.763a.6.6,0,0,1-.509-.281L6.08,19.233a.6.6,0,0,1,.025-.676l4.564-6.187a.6.6,0,0,1,.965.713l-4.32,5.858,4.345,6.9a.6.6,0,0,1-.506.917Z" transform="translate(1.198 1.925)"/>
										<path d="M20.338,26.764a.6.6,0,0,1-.509-.918l4.345-6.9-4.32-5.858a.6.6,0,1,1,.965-.713l4.564,6.187a.606.606,0,0,1,.025.676l-4.564,7.25A.6.6,0,0,1,20.338,26.764Z" transform="translate(3.947 1.925)"/>
										<path d="M12.838,25.151a.615.615,0,0,1-.293-.076.6.6,0,0,1-.23-.817l5.938-10.625a.6.6,0,1,1,1.048.584L13.362,24.844A.6.6,0,0,1,12.838,25.151Z" transform="translate(2.448 2.165)"/>
										<g transform="translate(3.287 5.65)">
										  <circle id="Ellipse_29" data-name="Ellipse 29" cx="0.75" cy="0.75" r="0.75"/>
										  <circle id="Ellipse_30" data-name="Ellipse 30" cx="0.75" cy="0.75" r="0.75" transform="translate(3)"/>
										  <circle id="Ellipse_31" data-name="Ellipse 31" cx="0.75" cy="0.75" r="0.75" transform="translate(6)"/>
										</g>
									  </g>
									</svg>';
							}
							
							if($tab_slug == "tpgb_white_label"){
								$navicon = '<svg class="tab-nav-icon" xmlns="http://www.w3.org/2000/svg" width="30.152" height="27.537" viewBox="0 0 30.152 27.537">
								  <g transform="matrix(-0.788, 0.616, -0.616, -0.788, 34.922, 16.639)">
									<path d="M27.478,20.962H4.894a.466.466,0,0,1-.335-.144L.947,17.023a3.593,3.593,0,0,1,.008-5.266L4.567,8.136A.456.456,0,0,1,4.9,8H27.462a.462.462,0,0,1,.367.744L23.416,14.48l4.348,5.651a.466.466,0,0,1-.286.83ZM5.092,20.036H26.52l-4.056-5.273a.46.46,0,0,1,0-.564L26.52,8.927H5.086L1.609,12.412a2.671,2.671,0,0,0,0,3.966Z" />
									<path d="M6.33,17.43a2.778,2.778,0,1,1,2.778-2.778A2.782,2.782,0,0,1,6.33,17.43Zm0-4.629a1.852,1.852,0,1,0,1.852,1.852A1.853,1.853,0,0,0,6.33,12.8Z" transform="translate(-0.278 -0.287)" fill="#8072fc"/>
									<path d="M18.581,13.926H13.026a.463.463,0,1,1,0-.926h5.555a.463.463,0,0,1,0,.926Z" transform="translate(-0.946 -0.371)"/>
									<path d="M18.581,16.926H13.026a.463.463,0,0,1,0-.926h5.555a.463.463,0,0,1,0,.926Z" transform="translate(-0.946 -0.593)"/>
								  </g>
								</svg>';
							}
							?>
							<a class="<?php echo esc_attr($nav_class); ?>" href="<?php menu_page_url($tab_slug); ?>">
								<span><?php echo $navicon; ?></span>
								<span><?php echo esc_html($option_tab['title']); ?></span>
							</a>
							<?php 
						endforeach;
						$out = ob_get_clean();
						$output .= $out;
					$output .='</div>';
				$output .='</div>';
			
				/*Content Options*/
				$output .='<div class="tpgb-settings-form-wrapper form-'.esc_attr($tab_forms[0]['id']).'">';
				
					if(!empty($tab_forms)){
						ob_start();
						foreach ($tab_forms as $tab_form):
							if($tab_form['id']=='tpgb_normal_blocks_opts'){
								echo '<div class="tpgb-panel-plus-block-page">';
									
									/*block filter*/
									echo '<div class="tpgb-panel-row tpgb-mt-50">';
										echo '<div class="tpgb-panel-col tpgb-panel-col-100">';
											echo '<div class="panel-plus-block-filter">';
												echo '<div class="tpgb-block-filters-check">';
													echo '<label class="panel-block-head panel-block-check-all"><span><svg xmlns="http://www.w3.org/2000/svg" width="23.532" height="20.533" viewBox="0 0 23.532 20.533">
														  <path d="M6.9,15.626,0,8.73,2.228,6.5,6.9,11.064,17.729,0,20,2.388Z" transform="translate(4.307) rotate(16)"/>
														</svg></span><input type="checkbox" id="block_check_all" /> '.esc_html__('Enable All','tpgb').'</label>';
													echo '<div class="panel-block-head panel-block-filters">';
														echo '<select class="blocks-filter">';
															echo '<option value="all">'.esc_html__('All','tpgb').'</option>';
															echo '<option value="free">'.esc_html__('Free','tpgb').'</option>';
															echo '<option value="freemium">'.esc_html__('Freemium','tpgb').'</option>';
															echo '<option value="pro">'.esc_html__('Pro','tpgb').'</option>';
														echo '</select>';
													echo '</div>';
												echo '</div>';
												echo '<div class="tpgb-block-filters-search">';
													echo '<label class="tpgb-filter-block-search"><input type="text" class="block-search" placeholder="'.esc_attr__("Blocks Search..","tpgb").'" /></label>';
												echo '</div>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
									/*block filter*/
									
									/*block listing*/
									echo '<form class="cmb-form" action="'.esc_url( admin_url('admin-post.php') ).'" method="post" id="tpgb_normal_blocks_opts" enctype="multipart/form-data" encoding="multipart/form-data">';
										wp_nonce_field( 'nonce_tpgb_normal_blocks_action', 'nonce_tpgb_normal_blocks_opts' );
										
										$is_pro = '';
										if (!defined('TPGBP_VERSION')) {
											$is_pro = 'plus-block-pro';
										}
										
										echo '<div class="tpgb-panel-row tpgb-mt-50 plus-block-list '.esc_attr($is_pro).'">';
											echo $this->tpgb_block_list_rendered();
										echo '</div>';
										echo '<input type="hidden" name="action" value="tpgb_blocks_opts_save">';
										echo '<input type="submit" name="submit-key" value="'.esc_attr__('Save','tpgb').'" class="button-primary tpgb-submit-block">';
									echo '</form>';
									/*block listing*/
								echo '</div>';
							}
							
							if($tab_form['id']=='tpgb_white_label'){
								do_action('tpgb_free_notice_white_label');
							}
							
							if(defined('TPGBP_VERSION') && $tab_form['id']=='tpgb_white_label'){								
								cmb2_metabox_form($tab_form, $tab_form['id']);
							}else if($tab_form['id']!='tpgb_welcome_page' && $tab_form['id']!='tpgb_normal_blocks_opts' && $tab_form['id']!='tpgb_white_label'){
								cmb2_metabox_form($tab_form, $tab_form['id']);
							}else if($tab_form['id']=='tpgb_welcome_page'){
								include_once TPGB_INCLUDES_URL.'welcome-page.php';
							}
						endforeach;
						$out = ob_get_clean();
						$output .= $out;
					}
				$output .='</div>';
			}
			
		$output .='</div>';
		
		echo $output;
	}
	
	
	
	/**
     * Gutenberg options metabox and field configuration
     * @since  1.0.0
     * @return array
     */
    public function option_fields($verify_api='') {
		// Only need to initiate the array once per page-load
        if (!empty($this->option_metabox)) {
            return $this->option_metabox;
        }
		
		$this->option_metabox[] = array(
            'id' => 'tpgb_welcome_page',
            'title' => esc_html__('Welcome', 'tpgb'),
            'show_on' => array(
                'key' => 'options-page',
                'value' => array(
                    'tpgb_welcome_page'
                )
            ),
            'show_names' => true,
            'fields' => ''
        );
		
		$this->option_metabox[] = array(
            'id' => 'tpgb_normal_blocks_opts',
            'title' => esc_html__('Plus Blocks', 'tpgb'),
            'show_on' => array(
                'key' => 'options-page',
                'value' => array(
                    'tpgb_normal_blocks_opts'
                )
            ),
			'show_names' => true,
            'fields' => '',
        );
		
		$extra_options= array(
			/*array(
				'name' => esc_html__('Mailchimp API Key', 'tpgb'),
				'desc' => esc_html__('Go to your Mailchimp > Account > Extras > API Keys then create a key and paste here', 'tpgb'),
				'default' => '',
				'id' => 'mailchimp_api',
				'type' => 'text',
				'attributes'  => array(
					'autocomplete' => 'off',
				),
			),
			array(
				'name' => esc_html__('Mailchimp List ID', 'tpgb'),
				'desc' => esc_html__('Go to your Mailchimp > Audience > Settings > Audience name and defaults > Copy the Audience ID and paste here.', 'tpgb'),
				'default' => '',
				'id' => 'mailchimp_id',
				'type' => 'text',
				'attributes'  => array(
					'autocomplete' => 'off',
				),
			),*/
			array(
				'name' => esc_html__('Font Awesome Loading'),
				'desc' => esc_html__('Note : If you disable this, It will stop loading at frontend throughout your website.','tpgb'),
				'id'   => 'fontawesome_load',
				'type' => 'select',
				'show_option_none' => false,
				'default' => 'enable',
				'options' => array(
					'enable' => esc_html__('Enable', 'tpgb'),
					'disable' => esc_html__('Disable', 'tpgb'),
				),
			),
			array(
				'name' => esc_html__('Google Map API Key', 'tpgb'),
				'desc' => esc_html__('NOTE : Turn Off this key If you theme already have google key option. So, It will not generate error in console for multiple google map keys.', 'tpgb'),
				'default' => '',
				'id' => 'gmap_api_switch',
				'type' => 'select',
				'show_option_none' => false,
				'default' => 'enable',
				'options' => array(
					'none' => esc_html__('None', 'tpgb'),
					'enable' => esc_html__('Show', 'tpgb'),
					'disable' => esc_html__('Hide', 'tpgb'),
				),
			),
			array(
				'name' => esc_html__('Google Map API Key', 'tpgb'),
				'desc' => sprintf(__('This field is required if you want to use Advance Google Map element. You can obtain your own Google Maps Key here: (<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Click Here</a>)', 'tpgb')),
				'default' => '',
				'id' => 'googlemap_api',
				'type' => 'text',
				'attributes' => array(
					'data-conditional-id'    => 'gmap_api_switch',
					'data-conditional-value' => 'enable',
				),
			),
		);
		
		if(has_filter('tpgb_extra_options')) {
			$extra_options = apply_filters('tpgb_extra_options', $extra_options);
		}
		
		$this->option_metabox[] = array(
            'id' => 'tpgb_connection_data',
            'title' => esc_html__('Extra Options', 'tpgb'),
            'show_on' => array(
                'key' => 'options-page',
                'value' => array(
                    'tpgb_connection_data'
                )
            ),
            'show_names' => true,
            'fields' => $extra_options,
        );
		
		$this->option_metabox[] = array(
            'id' => 'tpgb_performance',
            'title' => esc_html__('Performance', 'tpgb'),
            'show_on' => array(
                'key' => 'options-page',
                'value' => array(
                    'tpgb_performance'
                )
            ),
            'show_names' => true,
            'fields' => ''
        );
		
		$this->option_metabox[] = array(
            'id' => 'tpgb_custom_css_js',
            'title' => esc_html__('Custom', 'tpgb'),
            'show_on' => array(
                'key' => 'options-page',
                'value' => array(
                    'tpgb_custom_css_js'
                )
            ),
            'show_names' => true,
            'fields' => array(				
				array( 
					'name' => esc_html__( 'Custom CSS', 'tpgb' ),
					'desc' => esc_html__( 'Add Your Custom CSS Styles', 'tpgb' ),
					'id' => 'tpgb_custom_css_editor',
					'type' => 'textarea_code',
					'default' => '',
				),
				array( 
					'name' => esc_html__( 'Custom JS', 'tpgb' ),
					'desc' => esc_html__( 'Add Your Custom JS Scripts', 'tpgb' ),
					'id' => 'tpgb_custom_js_editor',
					'type' => 'textarea_code',
					'default' => '',
				),
				array(
					'id'   => 'tpgb_custom_hidden',
					'type' => 'hidden',
					'default' => 'hidden',
				),
			),
        );
		
		$white_label_options=[];
		if(has_filter('tpgb_white_label_options')) {
			$white_label_options = apply_filters('tpgb_white_label_options', $white_label_options);
		}
		
		$this->option_metabox[] = array(
			'id' => 'tpgb_white_label',
			'title' => esc_html__('White Label', 'tpgb'),
			'show_on' => array(
				'key' => 'options-page',
				'value' => array(
					'tpgb_white_label'
				)
			),
			'show_names' => true,
			'fields' => $white_label_options,
		);
		
		return $this->option_metabox;
	}
	
	/**
     * get options Key/Tab
     * @since  1.0.0
     * @return array
     */
	public function get_option_key($field_id)
    {
        $option_tabs = $this->option_fields();
        foreach ($option_tabs as $option_tab) {
            foreach ($option_tab['fields'] as $field) {
                if ($field['id'] == $field_id) {
                    return $option_tab['id'];
                }
            }
        }
        return $this->key;
    }
	
	/**
     * Public getter method for retrieving protected/private variables
     * @since  1.0.0
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get($field)
    {
        
        // Allowed fields to retrieve
        if (in_array($field, array('key','fields','title','options_page'), true)) {
            return $this->{$field};
        }
        if ('option_metabox' === $field) {
            return $this->option_fields();
        }
        
        throw new Exception( sprintf( esc_html__( 'Invalid property: %1$s', 'tpgb' ), $field ) );
    }
}

// Get it started
$Tpgb_Gutenberg_Settings_Options = new Tpgb_Gutenberg_Settings_Options();
$Tpgb_Gutenberg_Settings_Options->hooks();
?>