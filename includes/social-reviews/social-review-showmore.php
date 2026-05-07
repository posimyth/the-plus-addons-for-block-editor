<?php
/**
 * Social Review Showmore.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	$url = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
if ( ! empty( $txt_limt ) ) {
	$ltn = '';
	if ( 'char' === $text_type ) {
		$ltn       = strlen( $massage );
		$firstdesc = mb_substr( $massage, 0, $text_count );
		$totaldesc = mb_substr( $massage, $text_count, $ltn );
	} elseif ( 'word' === $text_type ) {
		$words     = explode( ' ', $massage );
		$ltn       = count( $words );
		$firstdesc = implode( ' ', array_splice( $words, 0, $text_count ) );
		$totaldesc = implode( ' ', array_splice( $words, 0 ) );
	}
	echo '<div class="tpgb-message tpgb-trans-linear">
                    <div class="showtext">' . wp_kses_post( $firstdesc );
	if ( $ltn > strlen( $firstdesc ) && 'char' === $text_type || ( 'word' === $text_type && ( $ltn > count( explode( ' ', $firstdesc ) ) ) ) ) { // phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
		echo '<span class="sf-dots">' . esc_html( $text_dots ) . '</span>
                                <div class="moreText" >' . wp_kses_post( $totaldesc ) . '</div>
                                <a class="readbtn" aria-label="' . esc_attr( $text_more ) . '">' . esc_html( $text_more ) . '</a>';
	}
				echo '</div>
                </div>';
} else {
	echo "<div class='tpgb-message tpgb-trans-linear'><div class='showtext'>" . wp_kses_post( $massage ) . '</div></div>';
}
