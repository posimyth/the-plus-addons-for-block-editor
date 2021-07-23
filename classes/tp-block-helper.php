<?php
/**
 * TPGB Core Plugin.
 *
 * @package TPGB
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tp_Blocks_Helper.
 *
 * @package TPGB
 */
class Tp_Blocks_Helper {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;
	protected static $get_load_block;
	
	protected static $get_block_deactivate;
	
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
	 * Constructor
	 */
	public function __construct() {
		add_action('plugins_loaded', array($this, 'init_blocks_load'));
		add_action('wp_head', array($this,'custom_css_js_load'));
		add_filter('upload_mimes', array($this,'tpgb_mime_types') );
		add_action( 'wp_ajax_tpgb_cross_cp_import', array( $this, 'cross_copy_paste_media_import' ) );
	}
	
	/* Load Custom Css and Js
	 * @since 1.0.0
	 */
	public function custom_css_js_load(){
		$get_custom_css_js=get_option( 'tpgb_custom_css_js' );
	
		$load_css_js='';
		//Load Custom Style
		if(!empty($get_custom_css_js['tpgb_custom_css_editor'])){
			$get_css=$get_custom_css_js['tpgb_custom_css_editor'];
			
			// Remove comments
			$get_css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $get_css);
			// Remove space after colons
			$get_css = str_replace(': ', ':', $get_css);
			// Remove whitespace
			$get_css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $get_css);
			//Remove Last Semi colons
			$get_css = preg_replace('/;}/', '}', $get_css);
			
			$load_css_js .='<style type="text/css">';
			$load_css_js .= $get_css;
			$load_css_js .='</style>';
		}
		
		//Load Custom Script
		if(!empty($get_custom_css_js['tpgb_custom_js_editor'])){
			$get_js= $get_custom_css_js['tpgb_custom_js_editor'];
			$load_css_js .= wp_print_inline_script_tag($get_js);
		}
		echo $load_css_js;
	}
	
	/*
	 * SVG Upload Mime types
	 * @since 1.0.0
	 */
	public function tpgb_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	
	public static function get_extra_option($field){
		$options=get_option( 'tpgb_connection_data' );	
			$values='';
			if(isset($options[$field]) && !empty($options[$field])){
				$values=$options[$field];
			}	
		return $values;
	}
	
	/**
	 * Init Block Load.
	 *
	 * @since 1.0.0
	 */
	public function init_blocks_load() {
		// Return early if this function does not exist.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		include_once 'global-options/tp-global-options.php';
		
		$load_blocks = array(
			'tp-accordion' => TPGB_CATEGORY.'/tp-accordion',
			'tp-blockquote' => TPGB_CATEGORY.'/tp-blockquote',
			'tp-breadcrumbs' => TPGB_CATEGORY.'/tp-breadcrumbs',
			'tp-button' => TPGB_CATEGORY.'/tp-button',
			'tp-countdown' => TPGB_CATEGORY.'/tp-countdown',
			'tp-creative-image' => TPGB_CATEGORY.'/tp-creative-image',
			'tp-data-table' => TPGB_CATEGORY.'/tp-data-table',
			'tp-draw-svg' => TPGB_CATEGORY.'/tp-draw-svg',
			'tp-empty-space' => TPGB_CATEGORY.'/tp-empty-space',
			'tp-flipbox' => TPGB_CATEGORY.'/tp-flipbox',
			'tp-google-map' => TPGB_CATEGORY.'/tp-google-map',
			'tp-heading-title' => TPGB_CATEGORY.'/tp-heading-title',
			'tp-hovercard' => TPGB_CATEGORY.'/tp-hovercard',
			'tp-infobox' => TPGB_CATEGORY.'/tp-infobox',
			'tp-messagebox' => TPGB_CATEGORY.'/tp-messagebox',
			'tp-number-counter' => TPGB_CATEGORY.'/tp-number-counter',
			'tp-post-author' => TPGB_CATEGORY.'/tp-post-author',
			'tp-post-comment' => TPGB_CATEGORY.'/tp-post-comment',
			'tp-post-content' => TPGB_CATEGORY.'/tp-post-content',
			'tp-post-image' => TPGB_CATEGORY.'/tp-post-image',
			'tp-post-listing' => TPGB_CATEGORY.'/tp-post-listing',
			'tp-post-meta' => TPGB_CATEGORY.'/tp-post-meta',
			'tp-post-title' => TPGB_CATEGORY.'/tp-post-title',
			'tp-pricing-list' => TPGB_CATEGORY.'/tp-pricing-list',
			'tp-pricing-table' => TPGB_CATEGORY.'/tp-pricing-table',
			'tp-pro-paragraph' => TPGB_CATEGORY.'/tp-pro-paragraph',
			'tp-progress-bar' => TPGB_CATEGORY.'/tp-progress-bar',
			'tp-row' => TPGB_CATEGORY.'/tp-row',
			'tp-site-logo' => TPGB_CATEGORY.'/tp-site-logo',
			'tp-stylist-list' => TPGB_CATEGORY.'/tp-stylist-list',
			'tp-social-icons' => TPGB_CATEGORY.'/tp-social-icons',
			'tpgb-settings' => TPGB_CATEGORY.'/tpgb-settings',
			'tp-smooth-scroll' => TPGB_CATEGORY.'/tp-smooth-scroll',
			'tp-tabs-tours' => TPGB_CATEGORY.'/tp-tabs-tours',
			'tp-testimonials' => TPGB_CATEGORY.'/tp-testimonials',
			'tp-video' => TPGB_CATEGORY.'/tp-video',
		);
		
		if(has_filter('tpgb_load_blocks')) {
			$load_blocks = apply_filters('tpgb_load_blocks', $load_blocks);
		}
		
		$enable_normal_blocks = $this->tpgb_get_option('tpgb_normal_blocks_opts','enable_normal_blocks');
		
			if(!empty($enable_normal_blocks)){
				self::$get_load_block = $enable_normal_blocks;
				self::$get_load_block[] = 'tpgb-settings';
				$this->include_block( 'tpgb-settings' );
				
				foreach ( $load_blocks as $block_id => $block ) {
					if(in_array($block_id,$enable_normal_blocks)){
						$this->include_block( $block_id );
						if(!empty($block_id) && $block_id=='tp-row'){
							self::$get_load_block[] = 'tp-column';
							$this->include_block( 'tp-column' );
						}
					}
				}
				
				$deactivate_block =array();
				foreach ( $load_blocks as $block_id => $block ) {
					if(!in_array($block_id,$enable_normal_blocks) && $block_id!='tpgb-settings'){
						$deactivate_block[] = $block_id;
					}
				}
				if(!in_array('tp-row',$enable_normal_blocks)){
					$deactivate_block[] = 'tp-column';
				}
				self::$get_block_deactivate = $deactivate_block;
			}else{
				foreach ( $load_blocks as $block_id => $block ) {
					self::$get_load_block[] = $block_id;
					$this->include_block( $block_id );
					if(!empty($block_id) && $block_id=='tp-row'){
						self::$get_load_block[] = 'tp-column';
						$this->include_block( 'tp-column' );
					}
				}
			}
	}
	
	/**
	 * Load Block Include Required File
	 * @since 1.0.0
	 */
	public function include_block($block_id){
		$filename = sprintf('classes/blocks/'.esc_attr($block_id).'/index.php');
		
		$block_path = TPGB_PATH;
		if (defined('TPGBP_VERSION') && defined('TPGBP_PATH')) {
			$block_path = TPGBP_PATH;
		}
		
		if ( file_exists( $block_path.$filename ) ) {
			require $block_path.$filename;
			return true;
		}else if( file_exists( TPGB_PATH.$filename ) ){
			require TPGB_PATH.$filename;
			return true;
		}else{
			return false;
		}
		
	}
	
	/*
	 * Get load activate Block for tpgb
	 *	@Array
	 */
	public static function get_block_enabled(){
		$load_enable_block = self::$get_load_block;
		
		if(!empty($load_enable_block)){
			return $load_enable_block;
		}else{
			return;
		}
	}
	
	/*
	 * Get load deactivate Block for tpgb
	 *	@Array
	 */
	public static function get_block_deactivate(){
		$load_disable_block = self::$get_block_deactivate;
		
		if(!empty($load_disable_block)){
			return $load_disable_block;
		}else{
			return;
		}
	}
	
	public static function get_post_type_list(){
		$args = array(
			'public'   => true,
			'show_ui' => true
		);	 
		$post_types = get_post_types( $args, 'objects' );
		$options = array();
		foreach ( $post_types  as $post_type ) {
			$exclude = array( 'attachment', 'elementor_library' );
			if( TRUE === in_array( $post_type->name, $exclude ) )
			  continue;
		  
			$options[] = [$post_type->name,$post_type->label]; 
		}
		
		return $options;
	}
	
	/**
	 * Get Image size information for all currently-registered image sizes
	 */
	public static function get_image_sizes() {

		global $_wp_additional_image_sizes;

		$sizes       = get_intermediate_image_sizes();
		$image_sizes = array();

		$image_sizes[] = [ 'full', esc_html__( 'Full', 'tpgb' ) ];

		foreach ( $sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$image_sizes[] = [ $size, ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ) ];
			} else {
				$image_sizes[] = [ $size, sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					) ];
			}
		}

		$image_sizes = apply_filters( 'tpgb_image_sizes', $image_sizes );

		return $image_sizes;
	}
	
	public function tpgb_get_option($options,$field){
		
		$tpgb_options=get_option( $options );
		$values='';
		if($tpgb_options){
			if(isset($tpgb_options[$field]) && !empty($tpgb_options[$field])){
				$values=$tpgb_options[$field];
			}
		}
		return $values;
	}
	
	public static function get_default_thumb(){
		return TPGB_ASSETS_URL. 'assets/images/tpgb-placeholder.jpg';
	}
	
	public static function get_contact_form_post() {
		$contact_forms = array();
		$cf7 = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
		if ($cf7) {
			$contact_forms[0] = ['',"Select Form"];
				foreach ($cf7 as $cform) {
					$contact_forms[] = [$cform->ID,$cform->post_title];
				}
		} else {
				$contact_forms[0] = ['', esc_html__("No contact forms found",'tpgb') ];
		}
		return $contact_forms;
	}
	
	
	
	/* Generate HTML of Breadcrumbs */
	public static function theplus_breadcrumbs( $icontype='', $sepIconType='', $icons='', $homeTitle='', $sepIcons='', $activeTextDefault='',$breadcrumbs_last_sec_tri_normal='', $bdToggleHome='', $bdToggleParent='', $bdToggleCurrent='', $letterLimitParent='', $letterLimitCurrent='', $markupSch =false) {
		
        if($homeTitle != '') {
            $text['home'] = $homeTitle;
        } else {
            $text['home'] = 'Home';
        }
        $text['category'] = esc_html__('Archive by "%s"', 'tpgb'); 
        $text['search']   = esc_html__('Search Results for "%s"', 'tpgb');
        $text['tag']      = esc_html__('Posts Tagged "%s"', 'tpgb');
        $text['author']   = esc_html__('Articles Posted by %s', 'tpgb');
        $text['404']      = esc_html__('Error 404', 'tpgb');
        $showCurrent = 1; 
        $showOnHome  = 1; 
        $delimiter   = ' <span class="del"></span> '; 
        
		$schemaArr = [ 
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => [],
		];
		$breadposi = 0;
        if($bdToggleCurrent == 'on-off-current'){
            if($breadcrumbs_last_sec_tri_normal != '') {
                if($activeTextDefault != '') {
                    $before = '<span class="current_active normal"><div class="current_tab_sec">';
                } else {
                    $before = '<span class="current normal"><div class="current_tab_sec">'; 
                }
            } else {
                if($activeTextDefault != '') {
                    $before = '<span class="current_active"><div class="current_tab_sec">';
                } else {
                    $before = '<span class="current"><div class="current_tab_sec">'; 
                }
            }
        } else {
            if($breadcrumbs_last_sec_tri_normal != '') {
                if($activeTextDefault != ''){
                    $before = '<span class="current_active normal on-off-current"><div class="current_tab_sec">';
                } else {
                    $before = '<span class="current normal on-off-current"><div class="current_tab_sec">'; 
                }
            } else {
                if($activeTextDefault != ''){
                    $before = '<span class="current_active on-off-current"><div class="current_tab_sec">';
                } else {
                    $before = '<span class="current on-off-current"><div class="current_tab_sec">'; 
                }
            }			
        }
       
        $after = '</div></span>';
        
        $icons_content = '';
        if($icontype=='icon' && $icons != ''){
            $icons_content = '<i class=" '.esc_attr($icons).' bread-home-icon" ></i>';
        }
        if($icontype=='image' && $icons != ''){
            $icons_content = '<img class="bread-home-img" src="'.esc_url($icons).'" />';
        }
        $icons_sep_content ='';
        if($sepIconType=='sep_icon' && $sepIcons != ''){
                $icons_sep_content = '<i class=" '.esc_attr($sepIcons).' bread-sep-icon" ></i>';
        }
        if($sepIconType=='sep_image' && $sepIcons != ''){
            $icons_sep_content = '<img class="bread-sep-icon" src="'.esc_url($sepIcons).'" />';		
        }
        
        global $post;
        $homeLink = home_url() . '/';
        $linkBefore = '<span>';
        $linkAfter = '</span>';
        if($icons_content != '' || $icons_sep_content != '' ||  $text['home'] != ''){
            if($bdToggleHome != '' && $bdToggleHome == true) {
                $home_link = '<span class="bc_home"><a class="home_bread_tab" href="%1$s">'.$icons_content.'%2$s'.$icons_sep_content.'</a>' . $linkAfter;
            } else {
                $home_link = '';
            }
            $home_delimiter = ' <span class="del"></span> ';
        } else {
            $home_link = $home_delimiter = '';
        }
        if($bdToggleParent != '' && $bdToggleParent = true) {			
                $link = '<span class="bc_parent"><a class="parent_sub_bread_tab" href="%1$s">%2$s'.$icons_sep_content.'</a>' . $linkAfter;
        } else {			
                $link = '';
        }
        
        if (is_home() || is_front_page()) {
            if ($showOnHome == 1) $crumbs_output = '<nav id="breadcrumbs"><a href="' . esc_url(home_url()) . '">'.$icons_content . esc_html($text['home']) . '</a></nav>';
			$schemaArr['itemListElement'][] = array(
				"@type" => "ListItem",
				"position" => ++$breadposi,
				"name" => $text['home'],
				"item" => esc_url(home_url())
			);
        } else {
            $crumbs_output ='<nav id="breadcrumbs">' . sprintf($home_link, $homeLink, $text['home']) . $home_delimiter;
            if ( is_category() ) {
                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) {
                    $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
					$schemaArr['itemListElement'][] = array(
						"@type" => "ListItem",
						"position"=> ++$breadposi,
						"name" => $text['category'],
						"item" => get_category_link($thisCat->term_id)
					);
                    $cats = str_replace('<a', $linkBefore . '<a', $cats);
                    $cats = str_replace('</a>', $icons_sep_content.'</a>' . $linkAfter, $cats);
                    $crumbs_output .= $cats;
                }
                $crumbs_output .= $before . sprintf($text['category'], single_cat_title('', false)) . $after;
            } elseif ( is_search() ) {
                $crumbs_output .= $before . sprintf($text['search'], get_search_query()) . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => $text['search'],
					"item" => site_url().'/'.get_search_query()
				);
            }
            elseif (is_singular('topic') ){
                $post_type = get_post_type_object(get_post_type());
                printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => $post_type->labels->singular_name,
					"item" => $homeLink . '/forums/', $post_type->labels->singular_name
				);
            }
            /* in forum, add link to support forum page template */
            elseif (is_singular('forum')){
                $post_type = get_post_type_object(get_post_type());
                printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => $post_type->labels->singular_name,
					"item" => $homeLink . '/forums/', $post_type->labels->singular_name
				);
            }
            elseif (is_tax('topic-tag')){
                $post_type = get_post_type_object(get_post_type());
                printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => $post_type->labels->singular_name,
					"item" => $homeLink . '/forums/', $post_type->labels->singular_name
				);
            }
            elseif ( is_day() ) {
                $crumbs_output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                $crumbs_output .= sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
                $crumbs_output .= $before . esc_html(get_the_time('d')) . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => get_the_time('d'),
					"item" => get_month_link(get_the_time('Y'),get_the_time('m'))
				);
            } elseif ( is_month() ) {
                $crumbs_output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                $crumbs_output .= $before . esc_html(get_the_time('F')) . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => get_the_time('d'),
					"item" => get_year_link(get_the_time('Y'))
				);
            } elseif ( is_year() ) {
                $crumbs_output .= $before . esc_html(get_the_time('Y')) . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => get_the_time('d'),
				);
            } elseif ( is_single() && !is_attachment() ) {
                if ( 'product' === get_post_type( $post ) ) {
                    
                    $terms_cate = wc_get_product_terms(
                        $post->ID,
                        'product_cat',
                        apply_filters(
                            'woocommerce_breadcrumb_product_terms_args',
                            array(
                                'orderby' => 'parent',
                                'order'   => 'DESC',
                            )
                        )
                    );
    
                    if ( $terms_cate ) {
                        $first_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms_cate[0], $terms_cate );
                        $ancestors = get_ancestors( $first_term->term_id, 'product_cat' );
                        $ancestors = array_reverse( $ancestors );
    
                        foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );
    
                            if ( ! is_wp_error( $ancestor ) && $ancestor ) {
                                $crumbs_output .= sprintf($link, get_term_link( $ancestor ), $ancestor->name);
								$schemaArr['itemListElement'][] = array(
									"@type" => "ListItem",
									"position"=> ++$breadposi,
									"name" =>  $ancestor->name,
									"item" => get_term_link( $ancestor )
								);
                            }
                        }
                        if($bdToggleCurrent == 'on-off-current'){
                            $crumbs_output .= sprintf($link, get_term_link( $first_term ), $first_term->name);
							$schemaArr['itemListElement'][] = array(
								"@type" => "ListItem",
								"position"=> ++$breadposi,
								"name" =>  $first_term->name,
								"item" => get_term_link( $first_term )
							);
                        }else{
                            $crumbs_output .= $linkBefore . '<a href="'.esc_url(get_term_link( $first_term )). '">'.esc_html($first_term->name).'</a>' . $linkAfter;
							$schemaArr['itemListElement'][] = array(
								"@type" => "ListItem",
								"position"=> ++$breadposi,
								"name" => $first_term->name ,
								"item" => get_term_link( $first_term )
							);
                        }
                    }
                    
                    if($letterLimitCurrent != '0'){
                        if ($showCurrent == 1) $crumbs_output .= $delimiter . $before .substr(get_the_title(),0,$letterLimitCurrent). $after;
                    }else{
                        if ($showCurrent == 1) $crumbs_output .= $delimiter . $before .get_the_title(). $after;
                    }
                } else if ( get_post_type() != 'post' ) {
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    $crumbs_output .= $linkBefore . '<a href="'.esc_url($homeLink). '?post_type=' . esc_attr($slug["slug"]) . '">'.esc_html($post_type->labels->singular_name).'</a>' . $linkAfter;
                    if($letterLimitCurrent != '0'){
                        if ($showCurrent == 1) $crumbs_output .= $delimiter . $before .substr(get_the_title(),0,$letterLimitCurrent). $after;
                    }else{
                        if ($showCurrent == 1) $crumbs_output .= $delimiter . $before .get_the_title(). $after;
                    }
					$schemaArr['itemListElement'][] = array(
						"@type" => "ListItem",
						"position"=> ++$breadposi,
						"name" => $post_type->labels->singular_name,
						"item" => $homeLink.'?post_type=' . esc_attr($slug["slug"])
					);
                } else {
                    $cat = get_the_category();
                    if(isset($cat[0])) {
                        $cat =  $cat[0];
                        $cats = get_category_parents($cat, TRUE, $delimiter);
                        if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                        $cats = str_replace('<a', $linkBefore . '<a', $cats);
                        $cats = str_replace('</a>', $icons_sep_content.'</a>' . $linkAfter, $cats);						
                        if($bdToggleParent != '' && $bdToggleParent == true) {
                            $crumbs_output .= $cats;
							$schemaArr['itemListElement'][] = array(
								"@type" => "ListItem",
								"position"=> ++$breadposi,
								"name" => $cat->term_id,
								"item" => get_category_link($cat->term_id)
							);
                        }else{
                            $crumbs_output .='';
                        }						
                        
                        if($letterLimitCurrent != '0'){
                            if ($showCurrent == 1) $crumbs_output .= $before . substr(get_the_title(),0,$letterLimitCurrent) . $after;
                        }else{
                            if ($showCurrent == 1) $crumbs_output .= $before . get_the_title() . $after;
                        }
                    }
                }
            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                $crumbs_output .= $before . esc_html($post_type->labels->singular_name) . $after;
            } elseif ( is_attachment() ) {
                $parent = get_post($post->post_parent);
                $cat = get_the_category($parent->ID);
                if($cat) {
                    $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    $cats = str_replace('<a', $linkBefore . '<a', $cats);
                    $cats = str_replace('</a>', $icons_sep_content.'</a>' . $linkAfter, $cats);
                    $crumbs_output .= $cats;
					
					$schemaArr['itemListElement'][] = array(
						"@type" => "ListItem",
						"position"=> ++$breadposi,
						"name" => $cat[0]['term_id'],
						"item" => get_category_link($cat[0]['term_id'])
					);
                   
					printf($link, get_permalink($parent), $parent->post_title);
                    if ($showCurrent == 1) $crumbs_output .= $delimiter . $before . esc_html(get_the_title()) . $after;
                }
            } elseif ( is_page() && !$post->post_parent ) {
                if ($showCurrent == 1) $crumbs_output .= $before . esc_html(get_the_title()) . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" => get_the_title(),
					"item" => ''
				);
            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
				$posi = ++$breadposi;
                while ($parent_id) {
					$posi++;
                    $page = get_page($parent_id);
                    $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
					$schemaArr['itemListElement'][] = array(
						"@type" => "ListItem",
						"position"=> $posi,
						"name" => get_the_title($page->ID),
						"item" => get_permalink($page->ID)
					);
                    $parent_id  = $page->post_parent;
                }
				$breadposi = $posi;
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    $crumbs_output .= $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) $crumbs_output .= $delimiter;
                }
                if ($showCurrent == 1){
					$crumbs_output .= $delimiter . $before . esc_html(get_the_title()) . $after;
					$schemaArr['itemListElement'][] = array(
						"@type" => "ListItem",
						"position"=> ++$breadposi,
						"name" => get_the_title(),
						"item" => get_permalink()
					);
				}
            } elseif ( is_tag() ) {
                $crumbs_output .= $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" =>$text['tag'],
					"item" => get_permalink()
				);
            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);
                $crumbs_output .= $before . sprintf($text['author'], $userdata->display_name) . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" =>$text['tag'],
					"item" => $userdata->user_url
				);
            } elseif ( is_404() ) {
                $crumbs_output .= $before . $text['404'] . $after;
				$schemaArr['itemListElement'][] = array(
					"@type" => "ListItem",
					"position"=> ++$breadposi,
					"name" =>$text['404'],
				);
            }
            if ( get_query_var('paged') ) {
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $crumbs_output .= ' (';
                    $crumbs_output .= '<span class="del"></span>'.esc_html__('Page', 'tpgb') . ' ' . get_query_var('paged');
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $crumbs_output .= ')';
            }
            $crumbs_output .= '</nav>';
			
        }
		if( !empty($markupSch) ){
			$encoded_data = wp_json_encode( $schemaArr );
			$crumbs_output .= '<script type="application/ld+json">'.$encoded_data.'</script>';
		}
        return $crumbs_output;
	}
	
	/* Get Taxonomie  Slug
	 * @since 1.1.0
	 */
	public static function tpgb_get_post_taxonomies() {
		$args = array(
			'public'   => true,
			'show_ui' => true
		);
		$output = 'objects'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$cat_list = array();
		$cat_list[] = ['' , 'Select Taxonomy'];
		$taxonomies = get_taxonomies( $args, $output, $operator );
		if ( $taxonomies ) {		
			foreach ( $taxonomies  as $taxonomy ) {
				$cat_list[] = [ $taxonomy->name , $taxonomy->label ];
			}
			
		}
		return $cat_list;
	}
	
	/*
	 * Get Common Classes Block Options
	 * @since 1.1.1
	 */
	public static function block_wrapper_classes( $attr ){
		$className = (!empty($attr['className'])) ? $attr['className'] :'';
		$align = (!empty($attr['align'])) ? $attr['align'] :'';
		
		$blockClass = '';
		if(!empty($className)){
			$blockClass .= $className;
		}
		if(!empty($align)){
			$blockClass .= ' align'.$align;
		}
		
		return $blockClass;
	}
	
	/*
	 * Get Carousel Settings Block Options
	 * @since 1.1.2
	 */
	public static function carousel_settings( $attr ){	
		$cenpadding = isset( $attr['centerPadding'] ) ? (array) $attr['centerPadding'] : '';
		
		$settings =[
			'updateOnMove' => true,
			'direction' => isset( $attr['sliderMode'] ) && $attr['sliderMode'] == 'vertical'  ? 'ttb' : 'ltr',
			'start' => isset( $attr['initialSlide'] ) ? $attr['initialSlide'] : 0,
			'autoplay' => isset( $attr['slideAutoplay'] ) ? $attr['slideAutoplay'] : false,
			'speed' => isset( $attr['slideSpeed'] ) ? (int)$attr['slideSpeed'] : 1500,
			'interval' => isset( $attr['slideAutoplaySpeed'] ) ? (int)$attr['slideAutoplaySpeed'] : '',
			'drag' => isset( $attr['slideDraggable']['md'] ) ? $attr['slideDraggable']['md'] : false  ,
			'type' => !empty( $attr['slideInfinite'] ) ? 'loop' : 'slide',
			'pauseOnHover' => isset( $attr['slideHoverPause'] ) ? $attr['slideHoverPause'] : false,
			'pagination' => isset( $attr['showDots']['md'] ) ? $attr['showDots']['md'] : false ,
			'arrows' => ( !empty($attr['showArrows']['md']) || !empty($attr['showArrows']['sm']) || !empty($attr['showArrows']['xs']) ) ? true : false,
			'padding' =>  isset( $cenpadding['md'] ) ? (int) $cenpadding['md'] : '',
			'perMove' => isset( $attr['slideScroll']['md'] ) ? (int)$attr['slideScroll']['md']  : 1,
			'perPage' => isset( $attr['slideColumns']['md'] ) ? (int)$attr['slideColumns']['md'] : 1,
			'padding' =>  isset( $cenpadding['md'] ) ? (int) $cenpadding['md'] : '',
			'breakpoints' => [
				'1024' => [
					'pagination' => ( !isset($attr['showDots']['sm']) ) ? $attr['showDots']['md'] : ( isset($attr['showDots']['sm'])  ? $attr['showDots']['sm'] : false ) ,

					'drag' => ( !isset($attr['slideDraggable']['sm']) ) ? $attr['slideDraggable']['md'] : ( isset($attr['slideDraggable']['sm'])  ? $attr['slideDraggable']['sm'] : false ),


					'padding' => ( !isset( $cenpadding['sm']) ) ? (isset( $cenpadding['md'] ) ? (int) $cenpadding['md'] : '') : ( isset($cenpadding['sm'])  ? $cenpadding['sm'] : '' ),

					'perMove' => ( !isset($attr['slideScroll']['sm']) ) ? (int)$attr['slideScroll']['md'] : ( isset($attr['slideScroll']['sm'])  ? (int)$attr['slideScroll']['sm'] : 1 ) ,
					
					'perPage' =>  ( !isset( $attr['slideColumns']['sm']) ) ? $attr['slideColumns']['md'] : ( isset($attr['slideColumns']['sm'])  ? $attr['slideColumns']['sm'] : 1 ),
				],
				'767' => [
					'pagination' => ( !isset($attr['showDots']['xs']) ) ? ( (!isset($attr['showDots']['sm'])) ? $attr['showDots']['md'] : $attr['showDots']['sm'] ) : (isset($attr['showDots']['xs']) ? $attr['showDots']['xs'] : false),

					'drag' => ( !isset($attr['slideDraggable']['xs']) ) ? ( (!isset($attr['slideDraggable']['sm'])) ? $attr['slideDraggable']['md'] : $attr['slideDraggable']['sm'] ) : (isset($attr['slideDraggable']['xs']) ? $attr['slideDraggable']['xs'] : false),

					'padding' =>  ( !isset($cenpadding['xs']) ) ? ( (!isset($cenpadding['sm'])) ? (isset( $cenpadding['md'] ) ? (int) $cenpadding['md'] : '') : $cenpadding['sm'] ) : (isset($cenpadding['xs']) ? $cenpadding['xs'] : ''),

					'perMove' => ( !isset($attr['slideScroll']['xs']) ) ? ( (!isset($attr['slideScroll']['sm'])) ? (int)$attr['slideScroll']['md'] : (int)$attr['slideScroll']['sm'] ) : (isset($attr['slideScroll']['xs']) ? (int)$attr['slideScroll']['xs'] : 1),

					'perPage' =>  ( !isset($attr['slideColumns']['xs']) ) ? ( (!isset($attr['slideColumns']['sm'])) ? $attr['slideColumns']['md'] : $attr['slideColumns']['sm'] ) : (isset($attr['slideColumns']['xs']) ? $attr['slideColumns']['xs'] : 1),
				]
			],
		];

		if(isset($attr['centerMode']['md']) && $attr['centerMode']['md'] == true){
			$settings['focus'] =  'center';
		}else if(isset( $attr['slideScroll']['md'] ) && $attr['slideScroll']['md'] == 1){
			$settings['focus'] =  0;
		}else{
			$settings['focus'] =  false;
		}
		
		
		if(isset($attr['centerMode']['sm']) && $attr['centerMode']['sm'] == true){
			$settings['breakpoints']['1024']['focus'] =  'center';
		}else if(!isset( $attr['centerMode']['sm']) && !isset( $attr['slideScroll']['sm']) ){
			$settings['breakpoints']['1024']['focus'] =  $settings['focus'];
		}else if(isset( $attr['slideScroll']['sm'] ) && $attr['slideScroll']['sm'] == 1){
			$settings['breakpoints']['1024']['focus'] =  0;
		}else{
			$settings['breakpoints']['1024']['focus'] =  false;
		}

		
		if(isset($attr['centerMode']['xs']) && $attr['centerMode']['xs'] == true){
			$settings['breakpoints']['767']['focus'] =  'center';
		}else if(!isset( $attr['centerMode']['xs']) && !isset( $attr['slideScroll']['xs']) ){
			$settings['breakpoints']['767']['focus'] =  $settings['breakpoints']['1024']['focus'];
		}else if(isset( $attr['slideScroll']['xs'] ) && $attr['slideScroll']['xs'] == 1){
			$settings['breakpoints']['767']['focus'] =  0;
		}else{
			$settings['breakpoints']['767']['focus'] =  false;
		}

		if( (isset($attr['centerMode']['md']) && $attr['centerMode']['md'] == true) || (isset($attr['centerMode']['sm']) && $attr['centerMode']['sm'] == true) || (isset($attr['centerMode']['xs']) && $attr['centerMode']['xs'] == true) ){
			if(isset($attr['trimSpace']) && $attr['trimSpace'] == true){
				$settings['trimSpace'] =  true;
			}else{
				$settings['trimSpace'] =  false;
			}
		}

		if(isset( $attr['sliderMode'])  &&  $attr['sliderMode'] == 'vertical' ){
			$settings['heightRatio'] = isset( $attr['slideheightRatio']) ? $attr['slideheightRatio'] : '';
		}

		return $settings;
	}
	
	/*
	 * Get Carousel Custom dots Block Options
	 * 	@since 1.1.2
	 */
	public static function tpgb_carousel_arrow($arrowsStyle , $arrowsPosition='' ){
		$arrow = '';
		$arrow .= '<div class="splide__arrows '.esc_attr($arrowsStyle).'">';
			$arrow .= '<button class="splide__arrow splide__arrow--prev '.esc_attr($arrowsStyle).' '.($arrowsStyle == 'style-3' || $arrowsStyle == 'style-4' ? esc_attr($arrowsPosition) : '').' ">';
				if($arrowsStyle == 'style-2' || $arrowsStyle == 'style-5' ){
					$arrow .= '<span class="icon-wrap"></span>';
				}else if($arrowsStyle == 'style-3' || $arrowsStyle == 'style-4'){
					$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="angle-left" class="svg-inline--fa fa-angle-left fa-w-6" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path fill="currentColor" d="M4.2 247.5L151 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L69.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L168 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 264.5c-4.7-4.7-4.7-12.3 0-17z"></path></svg></span>';
				}else if($arrowsStyle == 'style-6'){
					$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="long-arrow-left" class="svg-inline--fa fa-long-arrow-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M152.485 396.284l19.626-19.626c4.753-4.753 4.675-12.484-.173-17.14L91.22 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H91.22l80.717-77.518c4.849-4.656 4.927-12.387.173-17.14l-19.626-19.626c-4.686-4.686-12.284-4.686-16.971 0L3.716 247.515c-4.686 4.686-4.686 12.284 0 16.971l131.799 131.799c4.686 4.685 12.284 4.685 16.97-.001z"></path></svg></span>';
				}
			$arrow .= '</button>';
			$arrow .= '<button class="splide__arrow splide__arrow--next '.esc_attr($arrowsStyle).' '.($arrowsStyle == 'style-3' || $arrowsStyle == 'style-4' ? esc_attr($arrowsPosition) : '').'">';
				if($arrowsStyle == 'style-2' || $arrowsStyle == 'style-5' ){
					$arrow .= '<span class="icon-wrap"></span>';
				}else if($arrowsStyle == 'style-3' || $arrowsStyle == 'style-4'){
					$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="angle-right" class="svg-inline--fa fa-angle-right fa-w-6" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path fill="currentColor" d="M187.8 264.5L41 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 392.7c-4.7-4.7-4.7-12.3 0-17L122.7 256 4.2 136.3c-4.7-4.7-4.7-12.3 0-17L24 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17z"></path></svg></span>';
				}else if($arrowsStyle == 'style-6'){
					$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="long-arrow-alt-right" class="svg-inline--fa fa-long-arrow-alt-right fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M340.485 366l99.03-99.029c4.686-4.686 4.686-12.284 0-16.971l-99.03-99.029c-7.56-7.56-20.485-2.206-20.485 8.485v71.03H12c-6.627 0-12 5.373-12 12v32c0 6.627 5.373 12 12 12h308v71.03c0 10.689 12.926 16.043 20.485 8.484z"></path></svg></span>';
				}
			$arrow .= '</button>';
		$arrow .=  '</div>';

		return $arrow;
	}
	
	/*
	 * Get Carousel Arrows Css
	 * 	@since 1.1.2
	 */
	public static function tpgb_carousel_arrow_css($showArrows , $block_id ){
		$arrowCss = '';
		if( isset($showArrows['md']) &&  $showArrows['md'] === true){
	
			if( isset($showArrows['sm']) && $showArrows['sm'] === false){
				$arrowCss .= '@media (max-width:1024px){.tpgb-block-'.esc_attr($block_id).' .splide__arrows{display: none } }';
			}
			if( isset($showArrows['xs']) && $showArrows['xs'] === false){
				$arrowCss .= '@media (max-width:767px){.tpgb-block-'.esc_attr($block_id).' .splide__arrows{display: none } }';
			}
			$arrowCss .= '.tpgb-block-'.esc_attr($block_id).' .splide__arrows{display: block }';
		}
		if( isset($showArrows['sm']) && $showArrows['sm'] === true ){
	
			if( isset($showArrows['xs']) && $showArrows['xs'] === false){
				$arrowCss .= '@media (max-width:767px){.tpgb-block-'.esc_attr($block_id).' .splide__arrows{display: none } }';
			}
			$arrowCss .= '@media (max-width:1024px){.tpgb-block-'.esc_attr($block_id).' .splide__arrows{display: block } }';
		}
		if( isset($showArrows['xs']) && $showArrows['xs'] === true){
			$arrowCss .= '@media (max-width:767px){.tpgb-block-'.esc_attr($block_id).' .splide__arrows{display: block } }';
		}

		return "<style>".$arrowCss."</style>";
	}
	
	/**
	 * Cross copy paste media import
	 * @since  1.1.0
	 */
	public static function cross_copy_paste_media_import() {
		
		check_ajax_referer( 'tpgb-addons', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error(
				__( 'Not a Valid', 'tpgb' ),
				403
			);
		}
		require_once TPGB_PATH . 'classes/global-options/tp-import-media.php';
		$media_import = isset( $_POST['content'] ) ? wp_unslash( $_POST['content'] ) : '';
		
		if ( empty( $media_import ) ) {
			wp_send_json_error( __( 'Empty Content.', 'tpgb' ) );
		}

		$media_import = array( json_decode( $media_import, true ) );
		$media_import = self::tp_import_media_copy_content( $media_import );

		wp_send_json_success( $media_import );
	}
	
	/**
	 * Recursively data.
	 *
	 * Accept any type of data and a callback function. The callback
	 * function runs recursively for each data and his child data.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 */
	public static function tp_import_media_copy_content( $data_import ){
		return self::array_recursively_data(
			$data_import,
			function( $block_data ) {
				
				$elements = self::block_data_instance( $block_data );
				
				return $elements;
			}
		);
	}
	
	/*
	 * Block Data inner Block Instance
	 *
	 * @since 1.1.0
	 */
	public static function block_data_instance( array $block_data, array $args = [], $block_args = null ){

		if ( $block_data['name'] && $block_data['clientId'] && $block_data['attributes'] ) {
		
			foreach($block_data['attributes'] as $block_key => $block_val) {
				if( isset( $block_val['url'] ) && isset( $block_val['id'] ) && !empty( $block_val['url'] ) ){
					$new_media = Tpgb_Import_Images::media_import( $block_val );
					$block_data['attributes'][$block_key] = $new_media;
				}
			}
		}

		return $block_data;
	}
	
	/**
	 * Recursively data.
	 *
	 * Accept any type of data and a callback function. The callback
	 * function runs recursively for each data and his child data.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 */
	public static function array_recursively_data( $data, $callback, $args = [] ) {
		if ( isset( $data['name'] ) ) {
			if ( ! empty( $data['innerBlocks'] ) ) {
				$data['innerBlocks'] = self::array_recursively_data( $data['innerBlocks'], $callback, $args );
			}

			return call_user_func( $callback, $data, $args );
		}

		foreach ( $data as $block_key => $block_value ) {
			$block_data = self::array_recursively_data( $data[ $block_key ], $callback, $args );

			if ( null === $block_data ) {
				continue;
			}

			$data[ $block_key ] = $block_data;
		}

		return $data;
	}
}

Tp_Blocks_Helper::get_instance();