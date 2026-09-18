<?php
/**
 * Enqueue theme CSS/JS. filemtime versioning, no dependencies (no jQuery,
 * no Bootstrap JS) — plain vanilla output, loads immediately.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue theme.min.css.
 *
 * @return void
 */
function lc_tidyjs2026_enqueue_styles() {
	$rel = '/css/theme.min.css';
	$abs = get_stylesheet_directory() . $rel;
	if ( file_exists( $abs ) ) {
		wp_enqueue_style( 'lc-skeleton-theme', get_stylesheet_directory_uri() . $rel, array(), filemtime( $abs ) );
	}
}
add_action( 'wp_enqueue_scripts', 'lc_tidyjs2026_enqueue_styles' );

/**
 * Enqueue a file from js/vendor/, filemtime-versioned like everything else.
 *
 * Third-party libraries a project needs should be vendored into js/vendor/
 * and committed, the same convention the compiled css/ and js/ output
 * already follows, rather than loaded from a CDN — that avoids extra DNS/TLS
 * handshakes and keeps a vendored stylesheet off a third-party origin. Note
 * the file the upstream version came from in a comment near the call site
 * (e.g. gsap.min.js 3.12.7 cdn.jsdelivr.net/npm/gsap) since it's vendored by
 * hand rather than tracked in package.json.
 *
 * @param string $handle Handle to register under.
 * @param string $file   Filename within js/vendor/.
 * @param bool   $is_css True to enqueue as a stylesheet rather than a script.
 * @return void
 */
function lc_tidyjs2026_enqueue_vendor( $handle, $file, $is_css = false ) {
	$rel = '/js/vendor/' . $file;
	$abs = get_stylesheet_directory() . $rel;
	if ( ! file_exists( $abs ) ) {
		return;
	}
	$url = get_stylesheet_directory_uri() . $rel;
	if ( $is_css ) {
		wp_enqueue_style( $handle, $url, array(), filemtime( $abs ) );
	} else {
		wp_enqueue_script( $handle, $url, array(), filemtime( $abs ), true );
	}
}

/**
 * Enqueue theme.min.js.
 *
 * @return void
 */
function lc_tidyjs2026_enqueue_scripts() {

	// lc_tidyjs2026_enqueue_vendor( 'gsap', 'gsap.min.js' );

	// Lenis 1.3.11, vendored (js/vendor/lenis.min.js — same file lc-tidy2026
	// used). Recommended Lenis CSS is inlined in src/css/base.css rather
	// than a separate stylesheet. initLenis() (src/js/lenis-init.js) is
	// bundled into theme.min.js, so that script must load after this one —
	// hence the 'lenis' dependency below.
	lc_tidyjs2026_enqueue_vendor( 'lenis', 'lenis.min.js' );

	// Font Awesome 6.7.2 Free, vendored (js/vendor/fontawesome.min.css + js/webfonts/).
	// Icons are used widely across blocks and editor content, so this theme
	// carries an explicit exception to the "no icon font" rule in CLAUDE.md.
	lc_tidyjs2026_enqueue_vendor( 'fontawesome', 'fontawesome.min.css', true );

	// Swiper 11.2.10, vendored (js/vendor/swiper-bundle.min.{js,css}, from
	// cdn.jsdelivr.net/npm/swiper@11) — only on pages actually using the
	// lc-review-slider block, rather than sitewide.
	if ( is_singular() && has_block( 'lc-tidyjs2026/lc-review-slider' ) ) {
		lc_tidyjs2026_enqueue_vendor( 'swiper-style', 'swiper-bundle.min.css', true );
		lc_tidyjs2026_enqueue_vendor( 'swiper', 'swiper-bundle.min.js' );

		$init_rel = '/js/vendor/review-slider-init.js';
		$init_abs = get_stylesheet_directory() . $init_rel;
		if ( file_exists( $init_abs ) ) {
			// Only depend on 'swiper' if it's actually registered — a script
			// enqueued with a dependency on an unregistered handle is silently
			// dropped by WP entirely (no error), not just missing that one
			// dependency. Learned that the hard way: a missing vendor file
			// once took out this whole script's own enqueue, not just Swiper's.
			$deps = wp_script_is( 'swiper', 'registered' ) ? array( 'swiper' ) : array();
			wp_enqueue_script( 'lc-tidyjs2026-review-slider', get_stylesheet_directory_uri() . $init_rel, $deps, filemtime( $init_abs ), true );
		}
	}

	// lc-latest-guides can sit on an ordinary singular page/post, but is
	// just as likely to be placed on the "Posts page" itself (Settings →
	// Reading) — is_singular() is false there (it's rendering as the post
	// archive), so has_block() has to be pointed at that page's own post
	// explicitly rather than relying on its default "current post" lookup.
	$has_latest_guides = false;
	if ( is_singular() && has_block( 'lc-tidyjs2026/lc-latest-guides' ) ) {
		$has_latest_guides = true;
	} elseif ( is_home() && ! is_front_page() ) {
		$posts_page_id = (int) get_option( 'page_for_posts' );
		if ( $posts_page_id && has_block( 'lc-tidyjs2026/lc-latest-guides', $posts_page_id ) ) {
			$has_latest_guides = true;
		}
	}

	if ( $has_latest_guides ) {
		lc_tidyjs2026_enqueue_vendor( 'swiper-style', 'swiper-bundle.min.css', true );
		lc_tidyjs2026_enqueue_vendor( 'swiper', 'swiper-bundle.min.js' );

		$init_rel = '/js/vendor/latest-guides-init.js';
		$init_abs = get_stylesheet_directory() . $init_rel;
		if ( file_exists( $init_abs ) ) {
			$deps = wp_script_is( 'swiper', 'registered' ) ? array( 'swiper' ) : array();
			wp_enqueue_script( 'lc-tidyjs2026-latest-guides', get_stylesheet_directory_uri() . $init_rel, $deps, filemtime( $init_abs ), true );
		}
	}

	$rel = '/js/theme.min.js';
	$abs = get_stylesheet_directory() . $rel;
	if ( file_exists( $abs ) ) {
		// Same reasoning as above: don't let a missing/un-deployed lenis.min.js
		// silently take the entire theme bundle (nav toggle, dropdowns,
		// dialogs, reveal animations, everything) down with it.
		$deps = wp_script_is( 'lenis', 'registered' ) ? array( 'lenis' ) : array();
		wp_enqueue_script( 'lc-skeleton-theme', get_stylesheet_directory_uri() . $rel, $deps, filemtime( $abs ), true );

		// Site-Wide Settings > CF7 Redirect URL, read by src/js/cf7-redirect.js.
		wp_localize_script(
			'lc-skeleton-theme',
			'lcTidyjs2026Cf7',
			array( 'redirectUrl' => lc_tidyjs2026_get_setting( 'cf7_redirect_url', '/contact/thank-you/' ) )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'lc_tidyjs2026_enqueue_scripts' );
