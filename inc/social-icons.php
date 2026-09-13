<?php
/**
 * Social icon detection for the "Footer Social" nav menu.
 *
 * Icons are picked automatically from the domain in each menu item's URL,
 * so the menu (Appearance > Menus) only needs a label and a link — no
 * separate icon picker to maintain.
 *
 * @package Dlc_Designs
 */

/**
 * Maps a URL's domain to one of the icon keys in dlc_designs_social_icons().
 *
 * @param string $url The menu item's URL.
 * @return string
 */
function dlc_designs_social_icon_key_from_url( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );

	if ( ! $host ) {
		return 'link';
	}

	$host = strtolower( preg_replace( '/^www\./', '', $host ) );

	$domain_map = array(
		'instagram.com' => 'instagram',
		'twitter.com'   => 'x',
		'x.com'         => 'x',
		'facebook.com'  => 'facebook',
		'fb.com'        => 'facebook',
		'youtube.com'   => 'youtube',
		'youtu.be'      => 'youtube',
		'tiktok.com'    => 'tiktok',
		'discord.gg'    => 'discord',
		'discord.com'   => 'discord',
		'github.com'    => 'github',
		'linkedin.com'  => 'linkedin',
		'pinterest.com' => 'pinterest',
		'threads.net'   => 'threads',
	);

	foreach ( $domain_map as $domain => $icon_key ) {
		$suffix = '.' . $domain;

		if ( $host === $domain || substr( $host, -strlen( $suffix ) ) === $suffix ) {
			return $icon_key;
		}
	}

	return 'link';
}

/**
 * A small set of inline SVG icons, keyed by platform. Deliberately simple
 * geometric shapes (not exact brand marks) so they stay consistent with
 * each other and don't depend on any external icon library.
 *
 * @return array<string, string>
 */
function dlc_designs_social_icons() {
	$attrs = 'class="social-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"';

	return array(
		'instagram' => "<svg $attrs><rect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"5\"></rect><circle cx=\"12\" cy=\"12\" r=\"4\"></circle><circle cx=\"17.5\" cy=\"6.5\" r=\"0.8\" fill=\"currentColor\" stroke=\"none\"></circle></svg>",
		'x'         => "<svg $attrs><line x1=\"4\" y1=\"4\" x2=\"20\" y2=\"20\"></line><line x1=\"20\" y1=\"4\" x2=\"4\" y2=\"20\"></line></svg>",
		'facebook'  => "<svg $attrs><path d=\"M15 4h-2a4 4 0 0 0-4 4v3H7v4h2v7h4v-7h3l1-4h-4V8a1 1 0 0 1 1-1h3z\"></path></svg>",
		'youtube'   => "<svg $attrs><rect x=\"2\" y=\"6\" width=\"20\" height=\"12\" rx=\"4\"></rect><polygon points=\"10 9 16 12 10 15\" fill=\"currentColor\" stroke=\"none\"></polygon></svg>",
		'tiktok'    => "<svg $attrs><path d=\"M14 3v10.8a3.7 3.7 0 1 1-3-3.63\"></path><path d=\"M14 7a5 5 0 0 0 5 5\"></path></svg>",
		'discord'   => "<svg $attrs><rect x=\"3\" y=\"6\" width=\"18\" height=\"12\" rx=\"6\"></rect><circle cx=\"9\" cy=\"12\" r=\"1\" fill=\"currentColor\" stroke=\"none\"></circle><circle cx=\"15\" cy=\"12\" r=\"1\" fill=\"currentColor\" stroke=\"none\"></circle></svg>",
		'github'    => "<svg $attrs><circle cx=\"12\" cy=\"11\" r=\"7\"></circle><path d=\"M8.5 18.5c-1.5.6-1.5-1-2.5-1.2\"></path><path d=\"M15.5 18.5v-1.6c0-.5.4-1 .9-1.3\"></path></svg>",
		'linkedin'  => "<svg $attrs><rect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"2\"></rect><line x1=\"7\" y1=\"10\" x2=\"7\" y2=\"17\"></line><circle cx=\"7\" cy=\"6.8\" r=\"0.8\" fill=\"currentColor\" stroke=\"none\"></circle><path d=\"M11 17v-4.5a2 2 0 0 1 4 0V17\"></path><line x1=\"11\" y1=\"10\" x2=\"11\" y2=\"17\"></line></svg>",
		'pinterest' => "<svg $attrs><circle cx=\"12\" cy=\"12\" r=\"9\"></circle><path d=\"M9 18l3-11\"></path><path d=\"M10 10a3 3 0 1 0 3 3\"></path></svg>",
		'threads'   => "<svg $attrs><path d=\"M8 8a4 4 0 0 1 8 0v4a4 4 0 0 1-8 0\"></path><path d=\"M8 14c0 3 1.5 5 4 5s4-1.5 4-4-2-3-4-3\"></path></svg>",
		'link'      => "<svg $attrs><path d=\"M10 14a4 4 0 0 0 6 0l3-3a4 4 0 0 0-6-6l-1 1\"></path><path d=\"M14 10a4 4 0 0 0-6 0l-3 3a4 4 0 0 0 6 6l1-1\"></path></svg>",
	);
}

/**
 * Returns the SVG markup for a given URL's matching platform icon.
 *
 * @param string $url The menu item's URL.
 * @return string
 */
function dlc_designs_get_social_icon_svg( $url ) {
	$icons = dlc_designs_social_icons();
	$key   = dlc_designs_social_icon_key_from_url( $url );

	return isset( $icons[ $key ] ) ? $icons[ $key ] : $icons['link'];
}
