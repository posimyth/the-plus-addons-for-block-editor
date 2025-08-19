<?php
/**
 * Agni Builder & Cartify Compatibility
 * 
 * @since 4.1.8
 */
class Tpgb_Cartify_Compat {

	/**
	 * Instance
	 */
	private static $instance;

    public static $templates_ids = [];

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Tpgb_Cartify_Compat();

			add_action( 'wp_enqueue_scripts', [ self::$instance, 'load_enqueue_css' ] );
		}

		return self::$instance;
	}

	public function get_template_id() {

        $footer_block_id = $header_block_id = '';
        if(defined('AGNI_PLUGIN_URL')){
            $post_id = get_the_id();

            if( function_exists('is_shop') && is_shop() ){
                $post_id = wc_get_page_id('shop');
            }

            if( get_query_var( 'term' ) ){
                $term = get_queried_object();

                if( isset( $term->term_id ) ){
                    $post_id = $term->term_id;
                }
            }
            if( get_query_var( 'term' ) ){
                $footer_block_id = esc_attr( get_term_meta($post_id, 'agni_term_footer_block_id', true) );
                $header_block_id = get_term_meta($post_id, 'agni_term_header_id', true);
            }else{
                $footer_block_id = esc_attr( get_post_meta($post_id, 'agni_footer_block_id', true) );
                $header_block_id = get_post_meta($post_id, 'agni_page_header_choice', true);
            }
        }
        if( $footer_block_id == '' && function_exists( 'cartify_get_theme_option' ) ){
            $footer_block_id = cartify_get_theme_option( 'footer_settings_content_block_choice', '' );
        }
        if($header_block_id=='' && function_exists( 'cartify_get_theme_option' )){
            $headers = get_option('agni_header_builder_headers_list');
            if(!empty($headers)){
                foreach ($headers as $key => $header) {
                    if(isset($header['default'])){
                        $header_id = $header['id'];
                        self::$templates_ids[] = $header_id;
                    }
                }
            }
        }else if(!empty($header_block_id)){
            self::$templates_ids[] = $header_block_id;
        }

        if(!empty($footer_block_id)){
            self::$templates_ids[] = $footer_block_id;
        }

        if( class_exists( 'AgniBuilder' ) && class_exists('WooCommerce') && is_product() ){
            $block_choices = [];
            $layout_list = get_option('agni_product_builder_layouts_list');
            if(!empty($layout_list)){
                foreach ($layout_list as $key => $layout) {
                    if( isset( $layout['default'] ) && $layout['default'] ){
                        $layout_id = $layout['id'];
                    }else if( $layout['id'] == '0' ){
                        $layout_id = $layout['id'];
                    }
                }
            }

            if( !empty( $layout_list ) ){
                foreach ($layout_list as $key => $layout) {
                    if( $layout['id'] == $layout_id ){
                        foreach ( $layout['content'] as $placement ) {
                            if( !empty( $placement['content'] ) ){
                                foreach ($placement['content'] as $key => $block) {
                                    $block_choices = $this->processParsedProductLayoutBlockLoop( $block, $block_choices );
                                }
                            }
                        }
                    }
                }
            }

            if(!empty($block_choices)){
                foreach ($block_choices as $key => $temp_id) {
                    self::$templates_ids[] = $temp_id;
                }
            }
        }
	}

    public function processParsedProductLayoutBlockLoop( $block, $block_choices ){
		if( $block['slug'] == 'content_block' && isset($block['settings']['id'])){
			array_push($block_choices, $block['settings']['id']);
		}else if( isset($block['slug']) && $block['slug'] == 'columns' ){
			foreach ($block['content'] as $key => $column) {
				foreach ($column['content'] as $key => $inner_block) {
					$block_choices = $this->processParsedProductLayoutBlockLoop( $inner_block, $block_choices );
				}
			}
		}

		return $block_choices;
	}

    public function load_enqueue_css(){

        $this->get_template_id();
        
        if ( !empty(self::$templates_ids) && class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
            $load_init = Tpgb_Core_Init_Blocks::get_instance();
            if ( !empty($load_init) && is_callable( array( $load_init, 'enqueue_post_css' ) ) ) {
                foreach(self::$templates_ids as $post_id){
                    $load_init->enqueue_post_css( $post_id );
                }
            }
        }
    }
}

Tpgb_Cartify_Compat::instance();