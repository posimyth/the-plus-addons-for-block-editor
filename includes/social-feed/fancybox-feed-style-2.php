<?php
/**
 * Fancybox Feed Style 2.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="tpgb-block-<?php echo esc_attr( $block_id ); ?> fancybox-si fancy-<?php echo esc_attr( $fancy_style ); ?>" id="Fancy-<?php echo esc_attr( $popup_syl_num ); ?>" data-FancyFeedType="<?php echo esc_attr( $select_feed ); ?>" >

	<div class="fancy-fcb-flax d-flex flex-row">
		<div class="tpgb-fcb-img" >
			<?php require TPGB_INCLUDES_URL . 'social-feed/fancybox-feed-post.php'; ?>
		</div>
		<div class="tpgb-fcb-contant">
			<?php
				require TPGB_INCLUDES_URL . 'social-feed/fancybox-header.php';

			if ( ! empty( $massage ) ) {
				echo '<div class="tpgb-fcb-title">' . wp_kses_post( $massage ) . '</div>';
			}
			if ( ! empty( $description ) ) {
				include TPGB_INCLUDES_URL . 'social-feed/feed-Description.php';
			}
				echo '<div class="tpgb-fcb-footer">';
						require TPGB_INCLUDES_URL . 'social-feed/feed-footer.php';
						echo '<div class="tpgb-btn-viewpost">
                                <a href="' . esc_url( $user_link ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'View Post', 'the-plus-addons-for-block-editor' ) . '">' . esc_html__( 'View post', 'the-plus-addons-for-block-editor' ) . '</a>
                            </div>';
				echo '</div>';
			?>
		</div>
	</div>

</div>

<?php require TPGB_INCLUDES_URL . 'social-feed/popup-type.php'; ?>
