<?php
/**
 * Ability: Inspect a public URL and return real typography / palette / asset
 * data extracted from the actual HTML — used by MCP clients before they build
 * a Nexter Blocks page from a reference URL. Far more accurate than guessing
 * fonts from a screenshot.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/inspect-page',
	array(
		'label'               => __( 'Inspect Page (URL → typography & palette)', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Fetches a public URL and returns the typography (Google Fonts families, weights), inline-style colors, page title, OG metadata, and every asset URL found in the HTML — images (including srcset variants), CSS background-images, <video>/<source> URLs, and YouTube/Vimeo iframe embeds. CALL THIS FIRST whenever the user provides a URL and asks to recreate the page or match its style — the returned data is concrete CSS, not a guess from pixels, and dramatically improves font/color/asset fidelity. Pair with nexter-blocks/get-image-to-page-skill (or nexter-blocks-pro/get-image-to-page-skill).', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'url'       => array(
					'type'        => 'string',
					'format'      => 'uri',
					'description' => 'Public URL to inspect. Must be http(s).',
				),
				'maxImages' => array(
					'type'        => 'integer',
					'description' => 'Cap on the number of <img> URLs returned (default 20, max 100). Also caps videos[], iframes[], and backgrounds[].',
					'default'     => 20,
				),
			),
			'required'             => array( 'url' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'url'         => array( 'type' => 'string' ),
				'title'       => array( 'type' => 'string' ),
				'description' => array( 'type' => 'string' ),
				'ogImage'     => array( 'type' => 'string' ),
				'ogVideo'     => array(
					'type'        => 'string',
					'description' => 'Open Graph video URL (og:video / og:video:url). Empty when not declared.',
				),
				'fonts'       => array(
					'type'        => 'array',
					'description' => 'Google Fonts families referenced by the page. Use these directly as fontFamily values when building blocks.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'family'  => array( 'type' => 'string' ),
							'weights' => array(
								'type'  => 'array',
								'items' => array( 'type' => 'string' ),
							),
							'source'  => array(
								'type'        => 'string',
								'description' => 'Always "google" for v1.',
							),
						),
					),
				),
				'colors'      => array(
					'type'        => 'array',
					'description' => 'Hex / rgb colors found in inline style attributes and <style> tags. Most-frequent first.',
					'items'       => array( 'type' => 'string' ),
				),
				'images'      => array(
					'type'        => 'array',
					'description' => 'Absolute URLs of <img> tags on the page (deduped, src + srcset variants merged). Useful when the user wants to reuse the actual hero / illustration assets. When building a Nexter Blocks page from this URL, every add-tpgb-image / add-tpgb-creative-image / add-tpgb-cta-banner / add-tpgb-hotspot / add-tpgb-before-after / add-tpgb-media-listing block must pull its imageUrl from this list — never invent or substitute.',
					'items'       => array( 'type' => 'string' ),
				),
				'videos'      => array(
					'type'        => 'array',
					'description' => 'Absolute URLs of <video> src and <source> src tags on the page (deduped). Self-hosted MP4/WebM media. Map to add-tpgb-video with videoType="self_hosted" and mp4Url=<this URL>.',
					'items'       => array( 'type' => 'string' ),
				),
				'iframes'     => array(
					'type'        => 'array',
					'description' => 'Absolute URLs of <iframe> src tags whose host is youtube.com / youtube-nocookie.com / youtu.be / player.vimeo.com / vimeo.com. Map to add-tpgb-video by extracting the 11-char YouTube ID (or numeric Vimeo ID) and setting videoType + youtubeId / vimeoId accordingly. Non-video iframes are dropped to keep this list focused on hero-video assets.',
					'items'       => array( 'type' => 'string' ),
				),
				'backgrounds' => array(
					'type'        => 'array',
					'description' => 'Absolute URLs found inside CSS `background-image: url(...)` declarations (inline style attributes and <style> blocks). Hero sections often use these instead of <img> tags — map to add-tpgb-container.globalBgImage, add-tpgb-cta-banner.backgroundImage, or add-tpgb-hotspot.backgroundImage so the rebuilt page matches the original.',
					'items'       => array( 'type' => 'string' ),
				),
				'headings'    => array(
					'type'        => 'object',
					'description' => 'Plain-text content of h1/h2/h3 tags so Claude can use the actual page wording.',
					'properties'  => array(
						'h1' => array(
							'type'  => 'array',
							'items' => array( 'type' => 'string' ),
						),
						'h2' => array(
							'type'  => 'array',
							'items' => array( 'type' => 'string' ),
						),
						'h3' => array(
							'type'  => 'array',
							'items' => array( 'type' => 'string' ),
						),
					),
				),
			),
		),

		'execute_callback'    => 'tpgb_mcp_inspect_page',
		'permission_callback' => 'tpgb_mcp_inspect_page_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Permission callback for the inspect-page ability.
 *
 * @param array|null $input Ability input arguments (unused; kept for callback signature).
 * @return bool True when the current user may run the inspector.
 */
function tpgb_mcp_inspect_page_permission( ?array $input = null ): bool {
	unset( $input );
	return current_user_can( 'edit_posts' );
}

/**
 * Execute callback: fetch a public URL and return extracted typography,
 * palette, and asset metadata.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_inspect_page( array $input ) {
	$url = isset( $input['url'] ) ? trim( (string) $input['url'] ) : '';
	if ( '' === $url || ! preg_match( '#^https?://#i', $url ) ) {
		return new WP_Error( 'invalid_url', __( 'A valid http(s) URL is required.', 'the-plus-addons-for-block-editor' ) );
	}

	$max_images = isset( $input['maxImages'] ) ? max( 1, min( 100, (int) $input['maxImages'] ) ) : 20;

	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! $host || tpgb_mcp_inspect_is_blocked_host( $host ) ) {
		return new WP_Error( 'blocked_host', __( 'Internal / loopback URLs are not allowed.', 'the-plus-addons-for-block-editor' ) );
	}

	// Follow redirects manually so each hop's host can be re-validated against
	// tpgb_mcp_inspect_is_blocked_host(). WP's built-in `redirection` option
	// follows hops internally and does NOT re-check the host on each, so we
	// keep `redirection => 0` and loop ourselves. Cap at 5 hops to match the
	// HTTP API default and avoid loops.
	$current_url = $url;
	$response    = null;
	for ( $hop = 0; $hop < 5; $hop++ ) {
		$response = wp_remote_get(
			$current_url,
			array(
				'timeout'     => 12,
				'redirection' => 0,
				'user-agent'  => 'NexterBlocks-MCP-Inspector/1.0',
				'headers'     => array( 'Accept' => 'text/html,application/xhtml+xml' ),
			)
		);
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( $code < 300 || $code >= 400 ) {
			break;
		}
		$location = wp_remote_retrieve_header( $response, 'location' );
		if ( ! $location ) {
			break;
		}
		// Resolve relative Location against the current request URL.
		if ( ! preg_match( '#^https?://#i', $location ) ) {
			$parsed   = wp_parse_url( $current_url );
			$location = tpgb_mcp_inspect_resolve_url( $location, $parsed );
		}
		if ( '' === $location ) {
			break;
		}
		$next_host = wp_parse_url( $location, PHP_URL_HOST );
		if ( ! $next_host || tpgb_mcp_inspect_is_blocked_host( (string) $next_host ) ) {
			return new WP_Error( 'blocked_redirect', __( 'Refused to follow redirect to an internal / loopback host.', 'the-plus-addons-for-block-editor' ) );
		}
		$current_url = $location;
	}
	if ( ! $response ) {
		return new WP_Error( 'fetch_failed', __( 'Remote returned no response.', 'the-plus-addons-for-block-editor' ) );
	}
	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		return new WP_Error(
			'fetch_failed',
			sprintf(
				/* translators: %d: HTTP status code returned by the remote server. */
				__( 'Remote returned HTTP %d.', 'the-plus-addons-for-block-editor' ),
				$code
			)
		);
	}

	// Use the final resolved URL as the base for relative-image resolution
	// below — so /wp-content/uploads/foo.jpg resolves against the canonical
	// host, not the user-supplied one.
	$url = $current_url;

	$body = (string) wp_remote_retrieve_body( $response );
	if ( strlen( $body ) > 2 * 1024 * 1024 ) {
		return new WP_Error( 'response_too_large', __( 'Remote response exceeds the 2 MB inspection limit.', 'the-plus-addons-for-block-editor' ) );
	}
	if ( '' === $body ) {
		return new WP_Error( 'empty_body', __( 'Remote returned an empty response.', 'the-plus-addons-for-block-editor' ) );
	}

	$title       = tpgb_mcp_inspect_extract_title( $body );
	$description = tpgb_mcp_inspect_extract_meta( $body, 'description' );
	$og_image    = tpgb_mcp_inspect_extract_meta( $body, 'og:image', true );
	if ( '' !== $og_image && ! tpgb_mcp_inspect_is_safe_url( $og_image ) ) {
		$og_image = '';
	}
	$og_video = tpgb_mcp_inspect_extract_meta( $body, 'og:video', true );
	if ( '' === $og_video ) {
		$og_video = tpgb_mcp_inspect_extract_meta( $body, 'og:video:url', true );
	}
	if ( '' === $og_video ) {
		$og_video = tpgb_mcp_inspect_extract_meta( $body, 'og:video:secure_url', true );
	}
	if ( '' !== $og_video && ! tpgb_mcp_inspect_is_safe_url( $og_video ) ) {
		$og_video = '';
	}
	$fonts       = tpgb_mcp_inspect_extract_fonts( $body );
	$colors      = tpgb_mcp_inspect_extract_colors( $body );
	$images      = tpgb_mcp_inspect_extract_images( $body, $url, $max_images );
	$videos      = tpgb_mcp_inspect_extract_videos( $body, $url, $max_images );
	$iframes     = tpgb_mcp_inspect_extract_iframes( $body, $url, $max_images );
	$backgrounds = tpgb_mcp_inspect_extract_backgrounds( $body, $url, $max_images );
	$headings    = tpgb_mcp_inspect_extract_headings( $body );

	return array(
		'url'         => $url,
		'title'       => $title,
		'description' => $description,
		'ogImage'     => $og_image,
		'ogVideo'     => $og_video,
		'fonts'       => $fonts,
		'colors'      => $colors,
		'images'      => $images,
		'videos'      => $videos,
		'iframes'     => $iframes,
		'backgrounds' => $backgrounds,
		'headings'    => $headings,
	);
}

/**
 * Reject hosts that point at private, loopback, link-local, or otherwise
 * non-public IPs. Resolves DNS and checks every A/AAAA record so a name that
 * resolves to an internal IP (DNS-rebinding-style) is also blocked.
 *
 * Fails closed: if name resolution returns nothing, the host is treated as
 * blocked.
 *
 * @param string $host Host portion of the URL (may include IPv6 brackets).
 * @return bool True when the host must not be fetched.
 */
function tpgb_mcp_inspect_is_blocked_host( string $host ): bool {
	$host = trim( $host );
	if ( '' === $host ) {
		return true;
	}
	$host = trim( $host, '[]' );

	$lower = strtolower( $host );
	if ( in_array( $lower, array( 'localhost', 'localhost.localdomain', 'ip6-localhost', 'ip6-loopback', 'broadcasthost' ), true ) ) {
		return true;
	}
	if ( substr( $lower, -6 ) === '.local' || substr( $lower, -10 ) === '.localhost' || substr( $lower, -8 ) === '.lan' ) {
		return true;
	}

	$ips = array();
	if ( filter_var( $host, FILTER_VALIDATE_IP ) ) {
		$ips[] = $host;
	} else {
		$records = function_exists( 'dns_get_record' ) ? @dns_get_record( $host, DNS_A | DNS_AAAA ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- DNS lookup may emit warnings on hosts that have no records; null result is handled below.
		if ( is_array( $records ) ) {
			foreach ( $records as $rec ) {
				if ( ! empty( $rec['ip'] ) ) {
					$ips[] = $rec['ip'];
				}
				if ( ! empty( $rec['ipv6'] ) ) {
					$ips[] = $rec['ipv6'];
				}
			}
		}
		if ( empty( $ips ) && function_exists( 'gethostbyname' ) ) {
			$resolved = @gethostbyname( $host ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Failure is returned as the original hostname; handled below.
			if ( $resolved && $resolved !== $host && filter_var( $resolved, FILTER_VALIDATE_IP ) ) {
				$ips[] = $resolved;
			}
		}
		// Fail closed: unresolved hostnames are treated as suspect.
		if ( empty( $ips ) ) {
			return true;
		}
	}

	foreach ( $ips as $ip ) {
		if ( tpgb_mcp_inspect_is_private_ip( (string) $ip ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Return true when the IP belongs to a private / loopback / reserved range
 * (covers RFC1918 10/8, 172.16-31/12, 192.168/16, loopback 127/8 & ::1,
 * link-local 169.254/16 & fe80::/10, ULA fc00::/7, plus IANA-reserved blocks).
 *
 * @param string $ip Literal IPv4 or IPv6 address.
 * @return bool True when the IP must not be fetched.
 */
function tpgb_mcp_inspect_is_private_ip( string $ip ): bool {
	$ip = trim( $ip );
	if ( '' === $ip ) {
		return true;
	}
	// IPv4-mapped IPv6 (::ffff:10.0.0.1) — extract the v4 portion and re-check.
	if ( preg_match( '/^::ffff:(\d+\.\d+\.\d+\.\d+)$/i', $ip, $m ) ) {
		$ip = $m[1];
	}
	$valid_public = filter_var(
		$ip,
		FILTER_VALIDATE_IP,
		FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
	);
	if ( false === $valid_public ) {
		return true;
	}
	// Explicit catches for ranges PHP's reserved-range flag does not always cover.
	if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		$long = ip2long( $ip );
		if ( false === $long ) {
			return true;
		}
		// 100.64.0.0/10 (CGNAT).
		if ( ( $long & 0xFFC00000 ) === ( ip2long( '100.64.0.0' ) & 0xFFC00000 ) ) {
			return true;
		}
	}
	return false;
}

/**
 * True when a URL is safe to return to the caller — http(s) scheme and a
 * public host.
 *
 * @param string $url Candidate URL.
 * @return bool
 */
function tpgb_mcp_inspect_is_safe_url( string $url ): bool {
	if ( ! preg_match( '#^https?://#i', $url ) ) {
		return false;
	}
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! $host ) {
		return false;
	}
	return ! tpgb_mcp_inspect_is_blocked_host( (string) $host );
}

// -------------------------------------------------------------------------
// EXTRACTORS — kept simple/regex-based to avoid pulling in a full HTML parser.
// -------------------------------------------------------------------------

/**
 * Extract the contents of the first <title> tag.
 *
 * @param string $html Raw HTML markup.
 * @return string Decoded title text, or empty string when not found.
 */
function tpgb_mcp_inspect_extract_title( string $html ): string {
	if ( preg_match( '#<title[^>]*>(.*?)</title>#si', $html, $m ) ) {
		return trim( html_entity_decode( wp_strip_all_tags( $m[1] ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	}
	return '';
}

/**
 * Extract a single <meta> tag value by name or property attribute.
 *
 * @param string $html        Raw HTML markup.
 * @param string $name        Meta name or property to look for.
 * @param bool   $is_property When true, match the `property` attribute (e.g. og:image).
 * @return string Decoded content value, or empty string when not found.
 */
function tpgb_mcp_inspect_extract_meta( string $html, string $name, bool $is_property = false ): string {
	$attr    = $is_property ? 'property' : 'name';
	$pattern = '#<meta[^>]*' . $attr . '\s*=\s*["\']' . preg_quote( $name, '#' ) . '["\'][^>]*content\s*=\s*["\']([^"\']*)["\'][^>]*>#i';
	if ( preg_match( $pattern, $html, $m ) ) {
		return trim( html_entity_decode( $m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	}
	// Content-before-name attribute order.
	$pattern2 = '#<meta[^>]*content\s*=\s*["\']([^"\']*)["\'][^>]*' . $attr . '\s*=\s*["\']' . preg_quote( $name, '#' ) . '["\'][^>]*>#i';
	if ( preg_match( $pattern2, $html, $m ) ) {
		return trim( html_entity_decode( $m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	}
	return '';
}

/**
 * Pull Google Fonts families out of <link href="https://fonts.googleapis.com/...">
 * and @import url("https://fonts.googleapis.com/...") inside <style> blocks.
 *
 * @param string $html Raw HTML markup.
 * @return array List of {family, weights[], source} entries (deduped).
 */
function tpgb_mcp_inspect_extract_fonts( string $html ): array {
	$fonts   = array();
	$sources = array();

	if ( preg_match_all( '#https?://fonts\.googleapis\.com/[^"\'<>\s]+#i', $html, $m ) ) {
		$sources = array_unique( $m[0] );
	}

	foreach ( $sources as $src ) {
		$query = wp_parse_url( html_entity_decode( $src, ENT_QUOTES | ENT_HTML5, 'UTF-8' ), PHP_URL_QUERY );
		if ( ! $query ) {
			continue; }
		parse_str( $query, $params );
		// css2 supports multiple "family" keys (?family=A&family=B). PHP collapses
		// duplicates in parse_str — fall back to manual scan when needed.
		$family_specs = array();
		if ( isset( $params['family'] ) ) {
			$family_specs = is_array( $params['family'] ) ? $params['family'] : array( $params['family'] );
		}
		if ( preg_match_all( '/family=([^&]+)/i', $query, $fm ) ) {
			$family_specs = array_unique( array_merge( $family_specs, array_map( 'urldecode', $fm[1] ) ) );
		}

		foreach ( $family_specs as $spec ) {
			// Spec looks like "Inter:wght@400;600;700" or "Playfair+Display".
			$parts  = explode( ':', (string) $spec );
			$family = str_replace( '+', ' ', trim( $parts[0] ) );
			if ( '' === $family ) {
				continue; }
			$weights = array();
			if ( isset( $parts[1] ) ) {
				if ( preg_match_all( '/(\d{3})/', $parts[1], $wm ) ) {
					$weights = array_values( array_unique( $wm[1] ) );
				}
			}
			// Dedupe by family — first occurrence wins.
			$key = strtolower( $family );
			if ( ! isset( $fonts[ $key ] ) ) {
				$fonts[ $key ] = array(
					'family'  => $family,
					'weights' => $weights,
					'source'  => 'google',
				);
			} elseif ( $weights ) {
				$fonts[ $key ]['weights'] = array_values( array_unique( array_merge( $fonts[ $key ]['weights'], $weights ) ) );
			}
		}
	}

	return array_values( $fonts );
}

/**
 * Top hex / rgb() colors found in inline style attributes and <style> blocks.
 * Returns the most-frequent first, deduped, capped at 20.
 *
 * @param string $html Raw HTML markup.
 * @return array Lower-cased color strings ordered by frequency.
 */
function tpgb_mcp_inspect_extract_colors( string $html ): array {
	$tally  = array();
	$bumper = function ( string $color ) use ( &$tally ) {
		$color = strtolower( trim( $color ) );
		if ( '' === $color ) {
			return; }
		$tally[ $color ] = ( $tally[ $color ] ?? 0 ) + 1;
	};

	// Hex.
	if ( preg_match_all( '/#([0-9a-fA-F]{3,8})\b/', $html, $m ) ) {
		foreach ( $m[0] as $c ) {
			$bumper( $c ); }
	}
	// RGB / RGBA.
	if ( preg_match_all( '/rgba?\(\s*[\d\s,\.\/%]+\s*\)/i', $html, $m ) ) {
		foreach ( $m[0] as $c ) {
			$bumper( preg_replace( '/\s+/', '', $c ) ); }
	}

	arsort( $tally );
	return array_slice( array_keys( $tally ), 0, 20 );
}

/**
 * Attribute names known to carry the *real* image URL on lazy-loaded
 * <img> / <source> / <picture> tags. Modern themes and lazy-load plugins
 * replace `src` with a tiny placeholder and stash the real URL in one of
 * these data-attrs — without scanning them, the inspector returns nothing
 * useful for most production WordPress sites.
 *
 * Keep ordered by specificity: real URL holders first, then srcsets.
 *
 * @var string[]
 */
const TPGB_MCP_INSPECT_LAZY_IMG_ATTRS = array(
	'src',
	'data-src',
	'data-lazy-src',
	'data-original',
	'data-orig-src',
	'data-lazy-original',
	'data-lazy-original-src',
	'data-large_image',
	'data-image',
	'data-img',
	'data-full-src',
	'data-actualsrc',
);

/** @var string[] Same idea for srcset / responsive variants. */
const TPGB_MCP_INSPECT_LAZY_SRCSET_ATTRS = array(
	'srcset',
	'data-srcset',
	'data-lazy-srcset',
	'data-orig-srcset',
);

/** @var string[] Lazy-load attrs that hold a CSS background-image URL. */
const TPGB_MCP_INSPECT_LAZY_BG_ATTRS = array(
	'data-bg',
	'data-background',
	'data-background-image',
	'data-bg-image',
	'data-bgset',
);

/**
 * Common tracking-pixel / placeholder filenames that lazy-load plugins
 * stash in `src` while the real URL lives in `data-src`. Filtering these
 * out keeps the returned list focused on actual content imagery.
 *
 * @var string[]
 */
const TPGB_MCP_INSPECT_PLACEHOLDER_NEEDLES = array(
	'/blank.gif',
	'/transparent.gif',
	'/pixel.gif',
	'/spacer.gif',
	'/lazy.gif',
	'/lazy-placeholder',
	'lazy_placeholder',
	'data:image/gif;base64,r0lgo',  // 1×1 transparent gif, base64-prefix is stable
	'data:image/svg+xml',
);

/**
 * True if a URL looks like a lazy-load placeholder rather than a real asset.
 *
 * @param string $url Absolute or relative URL to test (lower-cased for match).
 * @return bool
 */
function tpgb_mcp_inspect_is_placeholder_url( string $url ): bool {
	$lower = strtolower( $url );
	foreach ( TPGB_MCP_INSPECT_PLACEHOLDER_NEEDLES as $needle ) {
		if ( strpos( $lower, $needle ) !== false ) {
			return true;
		}
	}
	return false;
}

/**
 * Pull every value of any of `$attrs` out of `<$tag ...>` openings.
 * Returns raw strings in document order; resolution / filtering / dedupe
 * is the caller's job.
 *
 * Regex-based parser kept deliberately permissive — themes ship malformed
 * markup all the time and we'd rather over-collect candidates than miss
 * the one with the real URL.
 *
 * @param string   $html  Raw HTML markup.
 * @param string   $tag   Tag name (e.g. "img", "iframe", "source").
 * @param string[] $attrs Attribute names to collect.
 * @return string[] Captured raw attribute values, in document order.
 */
function tpgb_mcp_inspect_collect_tag_attrs( string $html, string $tag, array $attrs ): array {
	if ( '' === $html || empty( $attrs ) ) {
		return array();
	}
	$attr_alt = implode( '|', array_map(
		static function ( $a ) {
			return preg_quote( $a, '#' );
		},
		$attrs
	) );
	$pattern = '#<' . preg_quote( $tag, '#' ) . '\b[^>]*?\s(?:' . $attr_alt . ')\s*=\s*["\']([^"\']+)["\'][^>]*>#i';
	$out     = array();
	// `preg_match_all` only captures the first matching attribute per opening
	// tag, so on a tag carrying both src and data-src it picks whichever
	// regex met first. Run the pattern per-attribute to ensure both get read.
	foreach ( $attrs as $attr ) {
		$single = '#<' . preg_quote( $tag, '#' ) . '\b[^>]*?\s' . preg_quote( $attr, '#' ) . '\s*=\s*["\']([^"\']+)["\'][^>]*>#i';
		if ( preg_match_all( $single, $html, $m ) ) {
			foreach ( $m[1] as $value ) {
				$out[] = $value;
			}
		}
	}
	return $out;
}

/**
 * Absolute, deduplicated image URLs — pulls every common attribute that
 * lazy-load plugins use to hold the real URL (`src`, `data-src`,
 * `data-lazy-src`, `data-original`, etc.), plus `srcset` / `data-srcset`
 * candidates and `<source>` tags inside `<picture>`. Returns in
 * document order, placeholder URLs filtered out.
 *
 * @param string $html     Raw HTML markup.
 * @param string $base_url Page URL used to resolve relative image paths.
 * @param int    $max      Maximum number of URLs to return.
 * @return array List of absolute image URLs (deduped, capped at $max).
 */
function tpgb_mcp_inspect_extract_images( string $html, string $base_url, int $max ): array {
	$base = wp_parse_url( $base_url );
	$out  = array();

	$push = static function ( string $src ) use ( $base, $max, &$out ): bool {
		$src = trim( html_entity_decode( $src, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		if ( '' === $src || tpgb_mcp_inspect_is_placeholder_url( $src ) ) {
			return false;
		}
		$abs = tpgb_mcp_inspect_resolve_url( $src, $base );
		if ( '' === $abs ) {
			return false;
		}
		if ( tpgb_mcp_inspect_is_placeholder_url( $abs ) ) {
			return false;
		}
		// Drop images on internal / loopback hosts so a client that later
		// fetches the asset can't be used to probe the WP server's network.
		if ( ! tpgb_mcp_inspect_is_safe_url( $abs ) ) {
			return false;
		}
		$out[ $abs ] = true;
		return count( $out ) >= $max;
	};

	// Scan <img> and <source> openings for any of the known real-URL attrs.
	foreach ( array( 'img', 'source' ) as $tag ) {
		foreach ( tpgb_mcp_inspect_collect_tag_attrs( $html, $tag, TPGB_MCP_INSPECT_LAZY_IMG_ATTRS ) as $value ) {
			if ( $push( $value ) ) {
				return array_keys( $out );
			}
		}
	}

	// Scan srcset / data-srcset attributes — each value is a comma list of
	// `<url> <descriptor>` pairs; take the URL of each pair.
	foreach ( array( 'img', 'source' ) as $tag ) {
		foreach ( tpgb_mcp_inspect_collect_tag_attrs( $html, $tag, TPGB_MCP_INSPECT_LAZY_SRCSET_ATTRS ) as $srcset ) {
			$candidates = preg_split( '/\s*,\s*/', $srcset );
			if ( ! is_array( $candidates ) ) {
				continue;
			}
			foreach ( $candidates as $candidate ) {
				$parts = preg_split( '/\s+/', trim( $candidate ) );
				if ( ! is_array( $parts ) || empty( $parts[0] ) ) {
					continue;
				}
				if ( $push( $parts[0] ) ) {
					return array_keys( $out );
				}
			}
		}
	}

	return array_keys( $out );
}

/**
 * Absolute, deduplicated <video src> + <source src> (inside <video>) URLs.
 * Used to detect self-hosted hero videos. YouTube / Vimeo iframes are
 * extracted separately by tpgb_mcp_inspect_extract_iframes().
 *
 * @param string $html     Raw HTML markup.
 * @param string $base_url Page URL used to resolve relative paths.
 * @param int    $max      Maximum number of URLs to return.
 * @return array List of absolute video URLs (deduped, capped at $max).
 */
function tpgb_mcp_inspect_extract_videos( string $html, string $base_url, int $max ): array {
	$base = wp_parse_url( $base_url );
	$out  = array();

	$push = static function ( string $src ) use ( $base, $max, &$out ): bool {
		$src = trim( html_entity_decode( $src, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		if ( '' === $src ) {
			return false;
		}
		$abs = tpgb_mcp_inspect_resolve_url( $src, $base );
		if ( '' === $abs || ! tpgb_mcp_inspect_is_safe_url( $abs ) ) {
			return false;
		}
		$out[ $abs ] = true;
		return count( $out ) >= $max;
	};

	// <video src="...">.
	if ( preg_match_all( '#<video[^>]+src\s*=\s*["\']([^"\']+)["\']#i', $html, $m ) ) {
		foreach ( $m[1] as $src ) {
			if ( $push( $src ) ) {
				return array_keys( $out );
			}
		}
	}

	// <source src="..."> tags inside <video>...</video>.
	if ( preg_match_all( '#<video\b[^>]*>(.*?)</video>#is', $html, $blocks ) ) {
		foreach ( $blocks[1] as $inner ) {
			if ( preg_match_all( '#<source[^>]+src\s*=\s*["\']([^"\']+)["\']#i', $inner, $sm ) ) {
				foreach ( $sm[1] as $src ) {
					if ( $push( $src ) ) {
						return array_keys( $out );
					}
				}
			}
		}
	}

	return array_keys( $out );
}

/**
 * YouTube / Vimeo iframe URLs found on the page.
 *
 * The scan deliberately reaches beyond real `<iframe>` tags. Lazy-video
 * plugins (PerfMatters, WP Rocket, LazyYT, etc.) replace the iframe with
 * a placeholder `<div data-provider="youtube" data-src="...">` and only
 * hydrate the real iframe on click — so a `<iframe>`-only scan misses
 * the hero video on most performance-tuned WordPress sites. We pull
 * `src` + lazy-load attrs from `<iframe>` AND any `<div>` that carries
 * `data-provider="youtube"|"vimeo"`, then filter the resulting URLs by
 * known video hosts so analytics / captcha iframes never leak in.
 *
 * @param string $html     Raw HTML markup.
 * @param string $base_url Page URL used to resolve relative paths.
 * @param int    $max      Maximum number of URLs to return.
 * @return array List of absolute YouTube/Vimeo iframe URLs.
 */
function tpgb_mcp_inspect_extract_iframes( string $html, string $base_url, int $max ): array {
	$lazy_video_attrs = array( 'src', 'data-src', 'data-lazy-src', 'data-original', 'data-id', 'data-url', 'data-embed' );

	$candidates = array_merge(
		tpgb_mcp_inspect_collect_tag_attrs( $html, 'iframe', $lazy_video_attrs ),
		// PerfMatters / WP Rocket / LazyYT-style placeholder wrappers —
		// the real iframe URL lives on a <div> until the user clicks.
		tpgb_mcp_inspect_collect_tag_attrs( $html, 'div', $lazy_video_attrs ),
		// Some setups use a <span> or <a> instead of a <div>.
		tpgb_mcp_inspect_collect_tag_attrs( $html, 'span', $lazy_video_attrs ),
		tpgb_mcp_inspect_collect_tag_attrs( $html, 'a', $lazy_video_attrs )
	);
	if ( empty( $candidates ) ) {
		return array();
	}
	$base    = wp_parse_url( $base_url );
	$out     = array();
	$allowed = array(
		'youtube.com',
		'www.youtube.com',
		'm.youtube.com',
		'youtube-nocookie.com',
		'www.youtube-nocookie.com',
		'youtu.be',
		'player.vimeo.com',
		'vimeo.com',
		'www.vimeo.com',
	);
	foreach ( $candidates as $src ) {
		$src = trim( html_entity_decode( $src, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		if ( '' === $src ) {
			continue;
		}
		$abs = tpgb_mcp_inspect_resolve_url( $src, $base );
		if ( '' === $abs || ! tpgb_mcp_inspect_is_safe_url( $abs ) ) {
			continue;
		}
		$host = strtolower( (string) wp_parse_url( $abs, PHP_URL_HOST ) );
		if ( ! in_array( $host, $allowed, true ) ) {
			continue;
		}
		$out[ $abs ] = true;
		if ( count( $out ) >= $max ) {
			break;
		}
	}
	return array_keys( $out );
}

/**
 * URLs found inside CSS `background-image: url(...)` declarations or the
 * common lazy-bg data-attributes (`data-bg`, `data-background`,
 * `data-background-image`, `data-bgset`). Scans inline `style="..."`
 * attributes and the contents of <style> blocks. Hero sections frequently
 * use a CSS background-image instead of an <img> tag — without this
 * extractor, the model that rebuilds the page from the inspector output
 * would have no source for the hero asset.
 *
 * @param string $html     Raw HTML markup.
 * @param string $base_url Page URL used to resolve relative paths.
 * @param int    $max      Maximum number of URLs to return.
 * @return array List of absolute background-image URLs.
 */
function tpgb_mcp_inspect_extract_backgrounds( string $html, string $base_url, int $max ): array {
	$base = wp_parse_url( $base_url );
	$out  = array();

	$push = static function ( string $src ) use ( $base, $max, &$out ): bool {
		$src = trim( $src, " \t\n\r\0\x0B\"'" );
		if ( '' === $src || strpos( $src, 'data:' ) === 0 || tpgb_mcp_inspect_is_placeholder_url( $src ) ) {
			return false;
		}
		$src = html_entity_decode( $src, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$abs = tpgb_mcp_inspect_resolve_url( $src, $base );
		if ( '' === $abs || ! tpgb_mcp_inspect_is_safe_url( $abs ) ) {
			return false;
		}
		if ( tpgb_mcp_inspect_is_placeholder_url( $abs ) ) {
			return false;
		}
		$out[ $abs ] = true;
		return count( $out ) >= $max;
	};

	// 1. CSS `background-image: url(...)` and the shorthand
	//    `background: ... url(...) ...` (inline styles + <style> blocks).
	//    Quotes inside url() are optional.
	if ( preg_match_all( '/background(?:-image)?\s*:\s*[^;}"\']*?url\(\s*([^)]+?)\s*\)/i', $html, $m ) ) {
		foreach ( $m[1] as $candidate ) {
			if ( $push( (string) $candidate ) ) {
				return array_keys( $out );
			}
		}
	}

	// 2. data-bg / data-background / data-background-image / data-bg-image —
	//    these hold a literal URL and are applied to the element's bg by JS
	//    after page load. Scan every tag, not just <div>, since themes use
	//    them on <section>, <header>, <a>, etc.
	$attr_alt = implode( '|', array_map(
		static function ( $a ) {
			return preg_quote( $a, '#' );
		},
		TPGB_MCP_INSPECT_LAZY_BG_ATTRS
	) );
	if ( preg_match_all( '#\s(?:' . $attr_alt . ')\s*=\s*["\']([^"\']+)["\']#i', $html, $m ) ) {
		foreach ( $m[1] as $value ) {
			// data-bgset uses the srcset syntax: "url1 1x, url2 2x".
			if ( strpos( $value, ',' ) !== false ) {
				$candidates = preg_split( '/\s*,\s*/', $value );
				if ( is_array( $candidates ) ) {
					foreach ( $candidates as $candidate ) {
						$parts = preg_split( '/\s+/', trim( $candidate ) );
						if ( is_array( $parts ) && ! empty( $parts[0] ) ) {
							if ( $push( (string) $parts[0] ) ) {
								return array_keys( $out );
							}
						}
					}
					continue;
				}
			}
			if ( $push( (string) $value ) ) {
				return array_keys( $out );
			}
		}
	}

	return array_keys( $out );
}

/**
 * Extract plain-text content of h1/h2/h3 tags.
 *
 * @param string $html Raw HTML markup.
 * @return array{h1:string[],h2:string[],h3:string[]} Heading text grouped by tag.
 */
function tpgb_mcp_inspect_extract_headings( string $html ): array {
	$out = array(
		'h1' => array(),
		'h2' => array(),
		'h3' => array(),
	);
	foreach ( array( 'h1', 'h2', 'h3' ) as $tag ) {
		if ( preg_match_all( '#<' . $tag . '[^>]*>(.*?)</' . $tag . '>#si', $html, $m ) ) {
			foreach ( $m[1] as $raw ) {
				$text = trim( html_entity_decode( wp_strip_all_tags( $raw ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
				if ( '' !== $text ) {
					$out[ $tag ][] = $text; }
			}
			$out[ $tag ] = array_values( array_unique( $out[ $tag ] ) );
		}
	}
	return $out;
}

/**
 * Resolve a possibly-relative URL against a parsed base URL.
 *
 * @param string     $src  Raw URL value (may be absolute, protocol-relative, or relative).
 * @param array|null $base Result of wp_parse_url() on the page URL.
 * @return string Absolute URL, or empty string when unresolvable.
 */
function tpgb_mcp_inspect_resolve_url( string $src, ?array $base ): string {
	$src = trim( $src );
	if ( '' === $src || strpos( $src, 'data:' ) === 0 ) {
		return ''; }
	if ( preg_match( '#^https?://#i', $src ) ) {
		return $src; }
	if ( ! $base || empty( $base['scheme'] ) || empty( $base['host'] ) ) {
		return ''; }
	if ( strpos( $src, '//' ) === 0 ) {
		return $base['scheme'] . ':' . $src;
	}
	if ( strpos( $src, '/' ) === 0 ) {
		return $base['scheme'] . '://' . $base['host'] . $src;
	}
	$path = isset( $base['path'] ) ? rtrim( dirname( $base['path'] ), '/' ) : '';
	return $base['scheme'] . '://' . $base['host'] . $path . '/' . ltrim( $src, '/' );
}
