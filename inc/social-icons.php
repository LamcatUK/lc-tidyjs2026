<?php
/**
 * [social_icons] shortcode — one <a> + inline brand-logo SVG per platform
 * that has a URL filled in on Site-Wide Settings' Social tab (inc/options.php's
 * options page). Add another platform by adding a {slug}_url field there, an
 * entry in $platforms below, and a matching SVG in img/icons/brands/ (see
 * lc_tidyjs2026_get_brand_icon() in inc/utilities.php — these used to be
 * Font Awesome fa-brands glyphs, swapped for vendored SVGs so the ~116KB
 * fa-brands-400.woff2 font file doesn't need loading for a handful of icons).
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

add_shortcode(
	'social_icons',
	function ( $atts ) {
		$atts = shortcode_atts( array( 'class' => '' ), $atts, 'social_icons' );

		$platforms = array(
			'facebook'  => array(
				'label' => 'Facebook',
				'icon'  => 'facebook-f',
			),
			'instagram' => array(
				'label' => 'Instagram',
				'icon'  => 'instagram',
			),
			'twitter'   => array(
				'label' => 'X (Twitter)',
				'icon'  => 'x-twitter',
			),
			'pinterest' => array(
				'label' => 'Pinterest',
				'icon'  => 'pinterest-p',
			),
			'youtube'   => array(
				'label' => 'YouTube',
				'icon'  => 'youtube',
			),
			'linkedin'  => array(
				'label' => 'LinkedIn',
				'icon'  => 'linkedin-in',
			),
			// Font Awesome Free has no dedicated Google Business Profile
			// glyph — google is the closest brand icon available.
			'gbp'       => array(
				'label' => 'Google Business Profile',
				'icon'  => 'google',
			),
		);

		$links = '';
		foreach ( $platforms as $slug => $platform ) {
			$url = lc_tidyjs2026_get_setting( $slug . '_url' );
			if ( ! $url ) {
				continue;
			}
			$links .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="nofollow noopener noreferrer" aria-label="' . esc_attr( $platform['label'] ) . '">' . lc_tidyjs2026_get_brand_icon( $platform['icon'] ) . '</a>';
		}

		if ( ! $links ) {
			return '';
		}

		$classes = trim( 'social-icons ' . sanitize_html_class( $atts['class'] ) );

		return '<div class="' . esc_attr( $classes ) . '">' . $links . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $links built from esc_url()/esc_attr() above plus lc_tidyjs2026_get_brand_icon()'s already-sanitised theme SVGs.
	}
);
