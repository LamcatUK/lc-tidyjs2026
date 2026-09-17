<?php
/**
 * [social_icons] shortcode — one <a> + Font Awesome brand icon per platform
 * that has a URL filled in on Site-Wide Settings' Social tab (inc/options.php's
 * options page). Add another platform by adding a {slug}_url field there and
 * an entry in $platforms below (icon class must be a valid fa-brands glyph —
 * see js/vendor/fontawesome.min.css, vendored in inc/enqueue.php).
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
				'icon'  => 'fa-facebook-f',
			),
			'instagram' => array(
				'label' => 'Instagram',
				'icon'  => 'fa-instagram',
			),
			'twitter'   => array(
				'label' => 'X (Twitter)',
				'icon'  => 'fa-x-twitter',
			),
			'pinterest' => array(
				'label' => 'Pinterest',
				'icon'  => 'fa-pinterest-p',
			),
			'youtube'   => array(
				'label' => 'YouTube',
				'icon'  => 'fa-youtube',
			),
			'linkedin'  => array(
				'label' => 'LinkedIn',
				'icon'  => 'fa-linkedin-in',
			),
			// Font Awesome Free has no dedicated Google Business Profile
			// glyph — fa-google is the closest brand icon available.
			'gbp'       => array(
				'label' => 'Google Business Profile',
				'icon'  => 'fa-google',
			),
		);

		$links = '';
		foreach ( $platforms as $slug => $platform ) {
			$url = lc_tidyjs2026_get_setting( $slug . '_url' );
			if ( ! $url ) {
				continue;
			}
			$links .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="nofollow noopener noreferrer" aria-label="' . esc_attr( $platform['label'] ) . '"><i class="fa-brands ' . esc_attr( $platform['icon'] ) . '" aria-hidden="true"></i></a>';
		}

		if ( ! $links ) {
			return '';
		}

		$classes = trim( 'social-icons ' . sanitize_html_class( $atts['class'] ) );

		return '<div class="' . esc_attr( $classes ) . '">' . $links . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $links built from esc_url()/esc_attr() above plus get_icon()'s already-sanitised theme SVGs.
	}
);
