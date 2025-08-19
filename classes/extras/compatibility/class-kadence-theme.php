<?php
/**
 * Kadence_Theme & Pro & Elements Compatibility
 * 
 * @since 4.1.8
 */
class Kadence_Theme_Pro_Compat {

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
			self::$instance = new Kadence_Theme_Pro_Compat();

			add_action( 'wp_enqueue_scripts', [ self::$instance, 'load_enqueue_css' ] , 11 );
		}

		return self::$instance;
	}

    public function get_template_id() {
        
		//Kadence Theme Pro
		if ( is_admin() || is_singular( 'kadence_element' ) || is_singular( 'kadence_wootemplate' ) ) {
			return;
		}

		if(!class_exists('Kadence_Pro') && !class_exists('Kadence_Pro\Elements_Controller') && !class_exists('Kadence_Pro\Elements_Post_Type_Controller')){
			return;
		}

		$kadence_element = [];
		if(class_exists('Kadence_Pro\Elements_Post_Type_Controller')){
			$kadence_element = Kadence_Pro\Elements_Post_Type_Controller::get_instance();
		}else if(class_exists('Kadence_Pro\Elements_Controller')){
			$kadence_element = Kadence_Pro\Elements_Controller::get_instance();
		}
		
		$kadence_args = array(
			'post_type'              => 'kadence_element',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'post_status'            => 'publish',
			'numberposts'            => 333,
			'order'                  => 'ASC',
			'orderby'                => 'menu_order',
			'suppress_filters'       => false,
		);

		$kadence_posts = get_posts( $kadence_args );

		if( !empty($kadence_posts) && !empty($kadence_element) ){
			foreach ( $kadence_posts as $post ) {
				$meta = $kadence_element->get_post_meta_array( $post );
				if ( apply_filters( 'kadence_element_display', $kadence_element->check_element_conditionals( $post, $meta ), $post, $meta ) ) {
                    self::$templates_ids[] = $post->ID;
				}
			}
		}

		if(class_exists('Kadence_Woo_Block_Editor_Templates')){
			$kadence_woo_element = Kadence_Woo_Block_Editor_Templates::get_instance();
			$woo_args = array(
				'post_type'              => 'kadence_wootemplate',
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'post_status'            => 'publish',
				'numberposts'            => 333,
				'order'                  => 'ASC',
				'orderby'                => 'menu_order',
				'suppress_filters'       => false,
			);
		
			$kadence_woo_posts = get_posts( $woo_args );
			if( !empty($kadence_woo_posts) && !empty($kadence_woo_element) ){
				foreach ( $kadence_woo_posts as $post ) {
					
					$meta = $kadence_woo_element->get_post_meta_array( $post );
					
					if ( 'single' === $meta['type'] ) {
                        self::$templates_ids[] = $post->ID;
					} else if ( 'archive' === $meta['type'] ) {
						self::$templates_ids[] = $post->ID;
					} else if ( 'loop' === $meta['type'] ) {
						self::$templates_ids[] = $post->ID;
					}
				}
			}
		}
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

Kadence_Theme_Pro_Compat::instance();
