<?php
/**
 * Navigation Menu.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp navbuilder render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_navbuilder_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output        = '';
	$block_id      = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$menu_name     = ( ! empty( $attributes['menuName'] ) ) ? $attributes['menuName'] : '';
	$menu_layout   = ( ! empty( $attributes['menuLayout'] ) ) ? $attributes['menuLayout'] : 'horizontal';
	$hvr_click     = ( ! empty( $attributes['HvrClick'] ) ) ? $attributes['HvrClick'] : 'hover';
	$menu_effect   = ( ! empty( $attributes['menuEffect'] ) ) ? $attributes['menuEffect'] : 'style-1';
	$vtitle_bar    = ( ! empty( $attributes['VtitleBar'] ) ) ? $attributes['VtitleBar'] : false;
	$title_link    = ( ! empty( $attributes['titleLink'] ) ) ? $attributes['titleLink'] : '';
	$nav_title     = ( ! empty( $attributes['navTitle'] ) ) ? $attributes['navTitle'] : 'Navigation Menu';
	$prefix_icon   = ( ! empty( $attributes['prefixIcon'] ) ) ? $attributes['prefixIcon'] : '';
	$postfix_icon  = ( ! empty( $attributes['postfixIcon'] ) ) ? $attributes['postfixIcon'] : '';
	$v_sideevent   = ( ! empty( $attributes['vSideevent'] ) ) ? $attributes['vSideevent'] : 'normal';
	$menu_align    = ( ! empty( $attributes['menuAlign'] ) ) ? $attributes['menuAlign'] : 'text-left';
	$respo_menu    = ( ! empty( $attributes['respoMenu'] ) ) ? $attributes['respoMenu'] : false;
	$resmenu_type  = ( ! empty( $attributes['resmenuType'] ) ) ? $attributes['resmenuType'] : 'toggle';
	$momenu_type   = ( ! empty( $attributes['momenuType'] ) ) ? $attributes['momenuType'] : 'normal-menu';
	$toggle_style  = ( ! empty( $attributes['toggleStyle'] ) ) ? $attributes['toggleStyle'] : 'style-1';
	$toggle_align  = ( ! empty( $attributes['toggleAlign'] ) ) ? $attributes['toggleAlign'] : 'text-left';
	$ctmtoggletype = ( ! empty( $attributes['ctmtoggletype'] ) ) ? $attributes['ctmtoggletype'] : 'custom_icon';
	$open_icon     = ( ! empty( $attributes['openIcon'] ) ) ? $attributes['openIcon'] : '';
	$close_icon    = ( ! empty( $attributes['closeIcon'] ) ) ? $attributes['closeIcon'] : '';
	$open_img      = ( ! empty( $attributes['openImg'] ) ) ? $attributes['openImg'] : '';
	$close_img     = ( ! empty( $attributes['closeImg'] ) ) ? $attributes['closeImg'] : '';
	$nav_align     = ( ! empty( $attributes['navAlign'] ) ) ? $attributes['navAlign'] : 'text-left';
	$icon_style    = ( ! empty( $attributes['iconStyle'] ) ) ? $attributes['iconStyle'] : 'none';
	$navwidth      = ( ! empty( $attributes['navwidth'] ) ) ? $attributes['navwidth'] : 'full';
	$hvreffect     = ( ! empty( $attributes['Hvreffect'] ) ) ? $attributes['Hvreffect'] : 'none';
	$menu_inver    = ( ! empty( $attributes['menuInver'] ) ) ? $attributes['menuInver'] : false;
	$submenu_inver = ( ! empty( $attributes['submenuInver'] ) ) ? $attributes['submenuInver'] : false;
	$sub_menuindi  = ( ! empty( $attributes['subMenuindi'] ) ) ? $attributes['subMenuindi'] : 'none';
	$type_menu     = ( ! empty( $attributes['TypeMenu'] ) ) ? $attributes['TypeMenu'] : '';
	$menulast_open = ( ! empty( $attributes['menulastOpen'] ) ) ? $attributes['menulastOpen'] : false;
	$access_web    = ( ! empty( $attributes['accessWeb'] ) ) ? $attributes['accessWeb'] : false;
	$close_menu    = ( ! empty( $attributes['closeMenu'] ) ) ? 'yes' : 'no';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$uid = 'Tpgbmobilemenu2285' . esc_attr( $block_id ) . '';
	// set Main Menu Hover class.
	$menu_hover_class = '';
	if ( 'style-1' === $hvreffect ) {
		$menu_hover_class = 'menu-hover-style-1';
	} elseif ( 'style-2' === $hvreffect ) {
		$menu_hover_class = 'menu-hover-style-2';
	}

	// set Menu Inverse Class.
	$menu_hover_inverse = '';
	if ( ! empty( $menu_inver ) ) {
		$menu_hover_inverse = 'hover-inverse-effect';
	}
	if ( ! empty( $submenu_inver ) ) {
		$menu_hover_inverse .= ' submenu-hover-inverse-effect';
	}

	$menuopen_class = '';
	if ( ! empty( $menulast_open ) ) {
		$menuopen_class = 'tpgb-open-sub-menu-left';
	}

	// Get Navigation Title Bar For VerticalSide Menu.
	$getnav_title          = '';
	$link_attr             = Tp_Blocks_Helper::add_link_attributes( $title_link );
	$getnav_title         .= '<a class="vertical-side-toggle tpgb-trans-easeinout" href="' . ( ! empty( $title_link['url'] ) ? esc_url( $title_link['url'] ) : '#' ) . '" ' . $link_attr . '>';
		$getnav_title     .= '<span>';
			$getnav_title .= '<i aria-hidden="true" class="pre-icon ' . esc_attr( $prefix_icon ) . '"></i>';
			$getnav_title .= wp_kses_post( $nav_title );
		$getnav_title     .= '</span>';
		$getnav_title     .= '<i aria-hidden="true" class="post-icon ' . esc_attr( $postfix_icon ) . '"></i>';
	$getnav_title         .= '</a>';

	// get Toggle icon & Image.
	$get_toogleicon  = '';
	$get_toogleicon .= '<div class="close-toggle-icon  toggle-icon">';
	if ( 'custom_icon' === $ctmtoggletype ) {
		$get_toogleicon .= '<i class="' . esc_attr( $open_icon ) . '"></i>';
	} else {
		$opimg_src = '';
		$alt_text  = ( isset( $open_img['alt'] ) && ! empty( $open_img['alt'] ) ) ? esc_attr( $open_img['alt'] ) : ( ( ! empty( $open_img['title'] ) ) ? esc_attr( $open_img['title'] ) : esc_attr__( 'Close Image', 'the-plus-addons-for-block-editor' ) );

		if ( ! empty( $open_img ) && ! empty( $open_img['id'] ) ) {
			$opimg_src = wp_get_attachment_image( $open_img['id'], 'full', false, array( 'alt' => $alt_text ) );
		} elseif ( ! empty( $open_img['url'] ) ) {
			$opimg_src = '<img src="' . esc_url( $open_img['url'] ) . '" alt="' . $alt_text . '"/>';
		}
		$get_toogleicon .= $opimg_src;
	}
	$get_toogleicon .= '</div>';
	$get_toogleicon .= '<div class="open-toggle-icon toggle-icon">';
	if ( 'custom_icon' === $ctmtoggletype ) {
		$get_toogleicon .= '<i class="' . esc_attr( $close_icon ) . '"></i>';
	} else {
		$cloimg_src = '';
		$alt_text1  = ( isset( $close_img['alt'] ) && ! empty( $close_img['alt'] ) ) ? esc_attr( $close_img['alt'] ) : ( ( ! empty( $close_img['title'] ) ) ? esc_attr( $close_img['title'] ) : esc_attr__( 'Open Image', 'the-plus-addons-for-block-editor' ) );

		if ( ! empty( $close_img ) && ! empty( $close_img['id'] ) ) {
			$cloimg_src = wp_get_attachment_image( $close_img['id'], 'full', false, array( 'alt' => $alt_text1 ) );
		} elseif ( ! empty( $close_img['url'] ) ) {
			$cloimg_src = '<img src="' . esc_url( $close_img['url'] ) . '" alt="' . $alt_text1 . '"/>';
		}
		$get_toogleicon .= $cloimg_src;
	}
	$get_toogleicon .= '</div>';

	// Set Attr For close Sub Menu on click on Body.
	$data_attr = '';
	if ( ! empty( $close_menu ) && 'yes' === $close_menu ) {
		$data_attr = 'data-mobile-menu-click="' . esc_attr( $close_menu ) . '"';
	}

	// Get Navmanu output.
	$output                 .= '<div class="tpgb-navbuilder tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '' . ( ! empty( $access_web ) ? ' tpgb-web-access' : '' ) . '" data-id="Nav1231" data-menu_id="' . esc_attr( $block_id ) . '" >';
		$output             .= '<div class="tpgb-nav-wrap ' . esc_attr( $menu_align ) . '">';
			$output         .= '<div class="tpgb-nav-inner menu-' . esc_attr( $hvr_click ) . ' menu-' . esc_attr( $menu_effect ) . ' indicator-' . esc_attr( $icon_style ) . ' sub-menu-indiacator-' . esc_attr( $sub_menuindi ) . '" data-menu_transition="' . esc_attr( $menu_effect ) . '" ' . $data_attr . '>';
				$output     .= '<div class="tpgb-normal-menu">';
					$output .= '<div class="tpgb-nav-item ' . esc_attr( $menu_layout ) . ' toggle-' . esc_attr( $v_sideevent ) . '">';
	if ( 'vertical-side' === $menu_layout && ! empty( $vtitle_bar ) ) {
		$output .= $getnav_title;
	}
	if ( 'standard' !== $type_menu ) {
		$output .= tpgb_mega_menu( $attributes );
	}
					$output .= '</div>';
				$output     .= '</div>';
	if ( ! empty( $respo_menu ) ) {
		if ( 'toggle' === $resmenu_type ) {
			$output         .= '<div class="tpgb-mobile-nav-toggle navbar-header mobile-toggle ' . esc_attr( $toggle_align ) . '">';
				$output     .= '<div class="tpgb-toggle-menu hamburger-' . esc_attr( $resmenu_type ) . ' toggle-' . esc_attr( $toggle_style ) . '" data-target="#' . esc_attr( $uid ) . '">';
					$output .= '<div class="toggle-line">';
			if ( 'style-5' !== $toggle_style ) {
				if ( 'style-1' === $toggle_style ) {
					$output .= '<span></span>';
					$output .= '<span></span>';
				} else {
					$output .= '<span></span>';
					$output .= '<span></span>';
					$output .= '<span></span>';
				}
			} else {
				$output .= $get_toogleicon;
			}
					$output     .= '</div>';
				$output         .= '</div>';
						$output .= '</div>';
		}
		$output .= '<div class="tpgb-mobile-menu tpgb-menu-' . esc_attr( $resmenu_type ) . ' collapse navbar-collapse navigation-' . esc_attr( $navwidth ) . ' ' . esc_attr( $nav_align ) . '" id="' . esc_attr( $uid ) . '">';
		if ( 'custom' === $momenu_type ) {
					$output .= tpgb_mega_menu( $attributes, 1 );
		}
					$output .= '</div>';
	}
			$output .= '</div>';
		$output     .= '</div>';
	$output         .= '</div>';

	$css_rule = '';
	if ( ! empty( $menulast_open ) ) {
		$menu_no = ( ! empty( $attributes['menuNo'] ) ) ? $attributes['menuNo'] : '';
		if ( is_rtl() ) {
			$css_rule .= '[dir="rtl"] .tpgb-block-' . esc_attr( $block_id ) . ' .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu ul.dropdown-menu{right: auto;left: 100% !important;}';
			$css_rule .= '[dir="rtl"] .tpgb-block-' . esc_attr( $block_id ) . ' .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu > li { text-align: left; }';
		} else {
			$css_rule .= '.tpgb-block-' . esc_attr( $block_id ) . ' .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu ul.dropdown-menu{left: auto !important;right: 100%;}.tpgb-block-' . esc_attr( $block_id ) . ' .tpgb-nav-item:not(.menu-vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu {left: 0;}.tpgb-block-' . esc_attr( $block_id ) . ' .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu > li { text-align: right; } .tpgb-block-' . esc_attr( $block_id ) . ' .sub-menu-indiacator-style-1 .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu > li .indi-icon .fa{ transform: rotate(180deg);}.tpgb-block-' . esc_attr( $block_id ) . ' .sub-menu-indiacator-style-1 .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu > li .indi-icon{left : 0; right : 100%;}.tpgb-block-' . esc_attr( $block_id ) . ' .sub-menu-indiacator-style-2 .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu > li .indi-icon:before{left: 10px;right:100%;}.tpgb-block-' . esc_attr( $block_id ) . ' .sub-menu-indiacator-style-2 .tpgb-nav-item:not(.vertical) .navbar-nav.tpgb-open-sub-menu-left > li:nth-last-child(-n+' . esc_attr( $menu_no ) . ') > ul.dropdown-menu > li .indi-icon:after{left: 4px;right:100%;}';

		}
		$output .= '<style>' . $css_rule . '</style>';
	}

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Tpgb mega menu.
 *
 * @param mixed  $attributes The attributes.
 * @param string $att The att.
 * @return mixed The result.
 */
function tpgb_mega_menu( $attributes, $att = '' ) {
	$custom_menu = ''; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	$stylecss    = '';
	if ( ! empty( $attributes['ItemMenu'] ) ) {
		$custom_menu .= '<ul class="nav navbar-nav ' . ( 'swiper' === $attributes['resmenuType'] ? 'swiper-slide' : '' ) . ' ' . ( 'style-1' === $attributes['Hvreffect'] ? 'menu-hover-style-1' : ( 'style-2' === $attributes['Hvreffect'] ? 'menu-hover-style-2' : '' ) ) . ' ' . ( ! empty( $attributes['menuInver'] ) ? 'hover-inverse-effect' : '' ) . ' ' . ( ! empty( $attributes['submenuInver'] ) ? 'submenu-hover-inverse-effect' : '' ) . ' ' . ( ! empty( $attributes['menulastOpen'] ) ? ' tpgb-open-sub-menu-left' : '' ) . '" aria-label="' . esc_attr__( 'Main menu', 'the-plus-addons-for-block-editor' ) . '">';
		$menu_array   = $attributes['ItemMenu'];

		foreach ( $attributes['ItemMenu'] as $index => $item ) {

			$depth     = (int) $item['depth'];
			$nextdepth = ( isset( $menu_array[ $index + 1 ] ) ) ? (int) $menu_array[ $index + 1 ]['depth'] : -1;
			$prevdepth = ( isset( $menu_array[ $index - 1 ] ) ) ? (int) $menu_array[ $index - 1 ]['depth'] : -1;

			// Open a new <ul class="dropdown-menu"> when descending into a child level.
			$st_child_li = '';
			if ( $depth > 0 && $depth > $prevdepth ) {
				$st_child_li = '<ul role="menu" class="dropdown-menu">';
			}

			$name         = '';
			$item_url     = '';
			$menu_name    = '';
			$indi_icon    = '';
			$subindi_icon = '';

			// Get Prefix Icon.
			$preicon = '';
			if ( ! empty( $item['menuiconTy'] ) && 'icon' === $item['menuiconTy'] ) {
				$preicon .= '<span class="tpgb-navicon-wrap"><i class="' . esc_attr( $item['preicon'] ) . ' nav-menu-icon"></i></span>';
			} elseif ( ! empty( $item['menuiconTy'] ) && 'img' === $item['menuiconTy'] ) {
				$alt_text2 = ( isset( $item['menuImg']['alt'] ) && ! empty( $item['menuImg']['alt'] ) ) ? esc_attr( $item['menuImg']['alt'] ) : ( ( ! empty( $item['menuImg']['title'] ) ) ? esc_attr( $item['menuImg']['title'] ) : esc_attr__( 'Navigation Image', 'the-plus-addons-for-block-editor' ) );

				if ( ! empty( $item['menuImg'] ) && ! empty( $item['menuImg']['id'] ) ) {
					$preicon .= '<span class="tpgb-navicon-wrap">' . wp_get_attachment_image(
						$item['menuImg']['id'],
						'full',
						true,
						array(
							'class' => 'nav-menu-img',
							'alt'   => $alt_text2,
						)
					) . '</span>';
				} elseif ( ! empty( $item['menuImg']['url'] ) ) {
					$preicon .= '<span class="tpgb-navicon-wrap"><img src="' . esc_url( $item['menuImg']['url'] ) . '" class="nav-menu-img" alt="' . $alt_text2 . '" /></span>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $alt_text2 is already escaped above with esc_attr().
				} else {
					$preicon .= '<span class="tpgb-navicon-wrap"><img src="' . esc_url( TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg' ) . '" class="nav-menu-img" alt="' . esc_attr__( 'Navigation Image', 'the-plus-addons-for-block-editor' ) . '" /></span>';
				}
			}

			// Get Label.
			$txt_label = '';
			if ( ! empty( $item['showlabel'] ) && ! empty( $item['labeltxt'] ) ) {
				$txt_label .= '<span class="nav-label-text">' . wp_kses_post( $item['labeltxt'] ) . '</span>';
			}

			// Get Description.
			$navdesc = '';
			if ( ! empty( $item['navDesc'] ) ) {
				$navdesc .= '<span class="tpgb-nav-desc">' . wp_kses_post( $item['navDesc'] ) . '</span>';
			}

			$link_filter = (array) $item['LinkFilter'];

			$menu_name = ( ! empty( $link_filter ) && ! empty( $link_filter['filter'] ) && ! empty( $link_filter['filter']['label'] ) ) ? $link_filter['filter']['label'] : '';

			// Get Page Url from id.
			$current_active = '';
			if ( ! empty( $link_filter['filter']['url'] ) ) {
				$item_url = $link_filter['filter']['url'];
				if ( isset( $link_filter['filter']['id'] ) && (int) $link_filter['filter']['id'] === (int) get_the_ID() ) { // phpcs:ignore WordPress.PHP.YodaConditions.NotYoda -- Non-Yoda condition intentional for readability.
					$current_active = ' active';
				}
			} else {
				$item_url = '#';
			}

			$link_attr = '';
			if ( ! empty( $link_filter['filter'] ) && isset( $link_filter['filter']['opensInNewTab'] ) && ! empty( $link_filter['filter']['opensInNewTab'] ) ) {
				$link_attr .= ' target="_blank"';
			}

			// Top-level dropdown indicator (only when this item has children at depth+1).
			if ( 'style-1' === $attributes['iconStyle'] && 0 === $depth && $nextdepth > $depth ) {
				$indi_icon .= '<span class="indi-icon"><i class="' . ( 'vertical-side' === $attributes['menuLayout'] ? 'fa fa-angle-right' : 'fa fa-angle-down' ) . '"></i></span>';
			}

			// Sub-menu indicator (for items at depth >= 1 that have children).
			if ( $depth >= 1 && $nextdepth > $depth ) {
				$subindi_icon .= '<span class="indi-icon">';
				if ( 'style-1' === $attributes['subMenuindi'] ) {
					$subindi_icon .= '<i class="fa fa-angle-right"></i>';
				}
				$subindi_icon .= '</span>';
			}

			// Ajax load Class.
			$triclass  = '';
			$cnt_class = '';
			$next_menu = ( ! empty( $menu_array[ $index + 1 ] ) ) ? $menu_array[ $index + 1 ] : '';

			if ( '' !== $next_menu && 'mega-menu' === $next_menu['SmenuType'] && isset( $next_menu['blockTemp'] ) && ! empty( $next_menu['blockTemp'] ) && 'none' !== $next_menu['blockTemp'] && isset( $next_menu['ajaxbase'] ) && ! empty( $next_menu['ajaxbase'] ) && 'ajax-base' === $next_menu['ajaxbase'] ) {
				$triclass = 'tpgb-load-template-' . esc_attr( $attributes['HvrClick'] ) . ' tpgb-load-' . esc_attr( $next_menu['blockTemp'] );
			}

			if ( ! empty( $item['SmenuType'] ) && 'mega-menu' !== $item['SmenuType'] && 'link' === $item['SmenuType'] ) {
				$name = '<a class="' . esc_attr( $triclass ) . '" href="' . esc_url( $item_url ) . '" title="' . esc_attr( $menu_name ) . '" aria-label="' . esc_attr( $menu_name ) . '" data-text="' . esc_attr( $menu_name ) . '" ' . $link_attr . '>' . $preicon . '<span class="tpgb-title-wrap">' . esc_html( $menu_name ) . $txt_label . $indi_icon . $subindi_icon . $navdesc . '</span></a>';
			}

			// Decide dropdown class based on whether this item has children.
			$has_children   = ( $nextdepth > $depth );
			$dropdown_class = '';
			if ( $has_children ) {
				$dropdown_class = ( $depth >= 1 ) ? 'dropdown-submenu menu-item-has-children' : 'dropdown menu-item-has-children';
			}

			$mega_menu_class = '';
			if ( 0 === $depth && 1 === $nextdepth ) {
				if ( '' !== $next_menu && 'mega-menu' === $next_menu['SmenuType'] ) {
					$mega_menu_class .= ' tpgb-fw';
					if ( isset( $next_menu['megaMType'] ) && ! empty( $next_menu['megaMType'] ) ) {
						$mega_menu_class .= ' tpgb-dropdown-' . esc_attr( $next_menu['megaMType'] );
					}
					if ( isset( $next_menu['megaMType'] ) && 'default' === $next_menu['megaMType'] ) {
						// Desktop.
						if ( isset( $next_menu['megaMwid']['md'] ) && ! empty( $next_menu['megaMwid']['md'] ) ) {

							$unit = isset( $next_menu['megaMwid']['unit'] ) && ! empty( $next_menu['megaMwid']['unit'] ) ? $next_menu['megaMwid']['unit'] : 'px';

							$stylecss .= '@media (min-width: 1024px) { .tpgb-block-' . esc_attr( $attributes['block_id'] ) . ' .tpgb-nav-wrap .tpgb-nav-inner .navbar-nav>li.tp-repeater-item-' . $item['_key'] . '.tpgb-dropdown-default>ul.dropdown-menu{ max-width: ' . $next_menu['megaMwid']['md'] . $unit . ' !important; min-width: ' . $next_menu['megaMwid']['md'] . $unit . '!important; ' . ( isset( $next_menu['megaMAlign'] ) && 'default' === $next_menu['megaMAlign'] ? 'right: auto;' : '' ) . '} } ';
						}

						// Tablet.
						if ( isset( $next_menu['megaMwid']['sm'] ) && ! empty( $next_menu['megaMwid']['sm'] ) ) {

							$unittab = isset( $next_menu['megaMwid']['unitsm'] ) && ! empty( $next_menu['megaMwid']['unitsm'] ) ? $next_menu['megaMwid']['unitsm'] : $next_menu['megaMwid']['unit'];

							$stylecss .= '@media (max-width: 1024px) and (min-width:768px){ .tpgb-block-' . esc_attr( $attributes['block_id'] ) . ' .tpgb-nav-wrap .tpgb-nav-inner .navbar-nav>li.tp-repeater-item-' . $item['_key'] . '.tpgb-dropdown-default>ul.dropdown-menu{ max-width: ' . $next_menu['megaMwid']['sm'] . $unittab . ' !important; min-width: ' . $next_menu['megaMwid']['sm'] . $unittab . ' !important; ' . ( isset( $next_menu['megaMAlign'] ) && 'default' === $next_menu['megaMAlign'] ? 'right: auto;' : '' ) . '} } ';
						} elseif ( isset( $next_menu['megaMwid']['md'] ) && ! empty( $next_menu['megaMwid']['md'] ) ) {

							$unit = isset( $next_menu['megaMwid']['unit'] ) && ! empty( $next_menu['megaMwid']['unit'] ) ? $next_menu['megaMwid']['unit'] : 'px';

							$stylecss .= '@media (max-width: 1024px) and (min-width:768px){ .tpgb-block-' . esc_attr( $attributes['block_id'] ) . ' .tpgb-nav-wrap .tpgb-nav-inner .navbar-nav>li.tp-repeater-item-' . $item['_key'] . '.tpgb-dropdown-default>ul.dropdown-menu{ max-width: ' . $next_menu['megaMwid']['md'] . $unit . ' !important; min-width: ' . $next_menu['megaMwid']['md'] . $unit . ' !important; ' . ( isset( $next_menu['megaMAlign'] ) && 'default' === $next_menu['megaMAlign'] ? 'right: auto;' : '' ) . '} } ';
						}

						// Mobile.
						if ( isset( $next_menu['megaMwid']['xs'] ) && ! empty( $next_menu['megaMwid']['xs'] ) ) {

							if ( isset( $next_menu['megaMwid']['unitxs'] ) && ! empty( $next_menu['megaMwid']['unitxs'] ) ) {
								$unitmob = $next_menu['megaMwid']['unitxs'];
							} elseif ( isset( $next_menu['megaMwid']['unitsm'] ) && ! empty( $next_menu['megaMwid']['unitsm'] ) ) {
								$unitmob = $next_menu['megaMwid']['unitsm'];
							} else {
								$unitmob = isset( $next_menu['megaMwid']['unit'] ) && ! empty( $next_menu['megaMwid']['unit'] ) ? $next_menu['megaMwid']['unit'] : 'px';
							}

							$stylecss .= '@media (max-width: 767px) { .tpgb-block-' . esc_attr( $attributes['block_id'] ) . ' .tpgb-nav-wrap .tpgb-nav-inner .navbar-nav>li.tp-repeater-item-' . $item['_key'] . '.tpgb-dropdown-default>ul.dropdown-menu{ max-width: ' . $next_menu['megaMwid']['xs'] . $unitmob . ' !important; min-width: ' . $next_menu['megaMwid']['xs'] . $unitmob . ' !important; ' . ( isset( $next_menu['megaMAlign'] ) && 'default' === $next_menu['megaMAlign'] ? 'right: auto;' : '' ) . '} } ';
						} elseif ( isset( $next_menu['megaMwid']['sm'] ) && ! empty( $next_menu['megaMwid']['sm'] ) ) {

							$unittab = isset( $next_menu['megaMwid']['unitsm'] ) && ! empty( $next_menu['megaMwid']['unitsm'] ) ? $next_menu['megaMwid']['unitsm'] : $next_menu['megaMwid']['unit'];

							$stylecss .= '@media (max-width: 767px) { .tpgb-block-' . esc_attr( $attributes['block_id'] ) . ' .tpgb-nav-wrap .tpgb-nav-inner .navbar-nav>li.tp-repeater-item-' . $item['_key'] . '.tpgb-dropdown-default>ul.dropdown-menu{ max-width: ' . $next_menu['megaMwid']['sm'] . $unittab . ' !important; min-width: ' . $next_menu['megaMwid']['sm'] . $unittab . ' !important; ' . ( isset( $next_menu['megaMAlign'] ) && 'default' === $next_menu['megaMAlign'] ? 'right: auto;' : '' ) . '} } ';
						} elseif ( isset( $next_menu['megaMwid']['md'] ) && ! empty( $next_menu['megaMwid']['md'] ) ) {

							$unit = isset( $next_menu['megaMwid']['unit'] ) && ! empty( $next_menu['megaMwid']['unit'] ) ? $next_menu['megaMwid']['unit'] : 'px';

							$stylecss .= '@media (max-width: 767px) { .tpgb-block-' . esc_attr( $attributes['block_id'] ) . ' .tpgb-nav-wrap .tpgb-nav-inner .navbar-nav>li.tp-repeater-item-' . $item['_key'] . '.tpgb-dropdown-default>ul.dropdown-menu{ max-width: ' . $next_menu['megaMwid']['md'] . $unit . ' !important; min-width: ' . $next_menu['megaMwid']['md'] . $unit . ' !important; ' . ( isset( $next_menu['megaMAlign'] ) && 'default' === $next_menu['megaMAlign'] ? 'right: auto;' : '' ) . '} } ';
						}
					}
				}
				if ( '' !== $next_menu && isset( $next_menu['megaMType'] ) && 'default' === $next_menu['megaMType'] && isset( $next_menu['megaMAlign'] ) && 'center' === $next_menu['megaMAlign'] ) {
					$mega_menu_class .= ' tpgb-dropdown-' . esc_attr( $next_menu['megaMAlign'] );
				}
			}

			$start_li = "<li class='menu-item depth-" . esc_attr( $depth ) . ' ' . esc_attr( $dropdown_class ) . ' ' . esc_attr( $mega_menu_class ) . ' ' . ( ! empty( $item['classTxt'] ) ? esc_attr( $item['classTxt'] ) : '' ) . ' tp-repeater-item-' . esc_attr( $item['_key'] ) . $current_active . "'>";

			if ( 1 === $depth && 'mega-menu' === $item['SmenuType'] ) {
				if ( empty( $att ) || empty( $item['moblieMmenu'] ) ) {
					if ( isset( $item['blockTemp'] ) && ! empty( $item['blockTemp'] ) && 'none' !== $item['blockTemp'] && isset( $item['ajaxbase'] ) && ! empty( $item['ajaxbase'] ) && 'ajax-base' === $item['ajaxbase'] ) {
						$cnt_class = 'tpgb-load-' . esc_attr( $item['blockTemp'] ) . '-content';
					}

					$start_li .= '<div class="tpgb-megamenu-content ' . esc_attr( $cnt_class ) . '">';
					if ( ! empty( $item['blockTemp'] ) && 'none' !== $item['blockTemp'] ) {
						ob_start();
						if ( ! empty( $item['blockTemp'] ) ) {
							echo Tpgb_Library()->plus_do_block( $item['blockTemp'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output from plus_do_block() is already escaped within the library method.
						}
						if ( isset( $item['ajaxbase'] ) && ! empty( $item['ajaxbase'] ) && 'ajax-base' === $item['ajaxbase'] ) {
							$start_li .= '';
						} else {
							$start_li .= ob_get_contents();
						}

						ob_end_clean();
					}
					$start_li .= '</div>';
				}
				if ( ! empty( $item['moblieMmenu'] ) && ! empty( $att ) ) {
					$m_link_filter = (array) $item['MLinkFilter'];
					$mmenu_name    = ( ! empty( $m_link_filter ) && ! empty( $m_link_filter['filter'] ) && ! empty( $m_link_filter['filter']['label'] ) ) ? $m_link_filter['filter']['label'] : '';
					$mitem_url     = ( ! empty( $m_link_filter['filter'] ) && ! empty( $m_link_filter['filter']['url'] ) ) ? $m_link_filter['filter']['url'] : '#';
					$mitem_attr    = '';
					if ( ! empty( $m_link_filter['filter'] ) && isset( $m_link_filter['filter']['opensInNewTab'] ) && ! empty( $m_link_filter['filter']['opensInNewTab'] ) ) {
						$mitem_attr .= ' target="_blank"';
					}
					$start_li .= '<a href="' . esc_attr( $mitem_url ) . '" title="' . esc_attr( $mmenu_name ) . '" data-text="' . esc_attr( $mmenu_name ) . '" aria-label="' . esc_attr( $mmenu_name ) . '"  ' . $mitem_attr . '>' . $preicon . '' . $mmenu_name . '' . $txt_label . '</a>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $mmenu_name is sanitized via filter label, $preicon and $txt_label are escaped above.
				}
			}

			/**
			 * Closing logic — this is the heart of the fix.
			 *
			 * Three cases when the current <li> ends:
			 * 1. Next item is at the same depth  → just close </li>
			 * 2. Next item is shallower          → close </li>, then for each level
			 *                                      we ascend, close </ul></li>
			 * 3. No next item (end of list)      → close </li>, then close </ul></li>
			 *                                      for every level above 0
			 *
			 * If the next item is DEEPER, we DO NOT close the <li> here — it will
			 * wrap the upcoming <ul class="dropdown-menu"> as its child.
			 */
			$close_html = '';
			if ( -1 === $nextdepth ) {
				// End of the entire list — close current <li> plus all open ancestor <ul><li>.
				$close_html .= '</li>';
				for ( $i = 0; $i < $depth; $i++ ) {
					$close_html .= '</ul></li>';
				}
			} elseif ( $nextdepth === $depth ) {
				// Sibling follows — just close this <li>.
				$close_html .= '</li>';
			} elseif ( $nextdepth < $depth ) {
				// Going up the tree — close this <li>, then close one </ul></li> per level ascended.
				$close_html .= '</li>';
				$diff        = $depth - $nextdepth;
				for ( $i = 0; $i < $diff; $i++ ) {
					$close_html .= '</ul></li>';
				}
			}
			// If $nextdepth > $depth, we leave <li> open — the child <ul> nests inside it.

			$custom_menu .= $st_child_li . $start_li . $name . $close_html;
		}
		$custom_menu .= '</ul>';
		if ( ! empty( $stylecss ) ) {
			$custom_menu .= '<style>' . $stylecss . '</style>';
		}
	}
	return $custom_menu;
}
/**
 * Render for the server-side
 */
function tpgb_tp_navbuilder() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_navbuilder_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_navbuilder' );
