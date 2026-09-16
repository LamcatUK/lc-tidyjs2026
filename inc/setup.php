<?php
/**
 * Theme setup — supports, nav menus.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Core theme supports and nav menu locations.
 *
 * @return void
 */
function lc_tidyjs2026_setup() {
	load_theme_textdomain( 'lc-tidyjs2026', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' ); // Site title in <head> — no separate "site title" support needed beyond this.
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'style', 'script' ) ); // Clean markup for enqueued tags. Not search-form/comment-form/comment-list/gallery/caption — none of those are in use.
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'disable-custom-colors' );

	// Rename/extend per project.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'lc-tidyjs2026' ),
			'footer'  => __( 'Footer Menu', 'lc-tidyjs2026' ),
		)
	);
}
add_action( 'after_setup_theme', 'lc_tidyjs2026_setup' );

/**
 * Append CTA items (Contact Us / Call Now / WhatsApp / Get a Free Quote) to
 * the primary nav's markup — ported from lc-tidy2026's inc/lc-theme.php,
 * where these are injected the same way rather than stored as real nav menu
 * items (they don't appear in the actual menu content either).
 *
 * @param string   $items Existing nav menu markup.
 * @param stdClass $args  wp_nav_menu() args.
 * @return string
 */
function lc_tidyjs2026_append_primary_nav_cta_items( $items, $args ) {
	if ( 'primary' !== $args->theme_location ) {
		return $items;
	}

	$phone = lc_tidyjs2026_get_setting( 'phone' );

	$items .= '<li class="nav-item nav-item--cta"><a class="btn btn--lg" href="/contact/">Contact Us</a></li>';
	$items .= '<li class="nav-item nav-item--cta d-lg-none"><a class="btn btn--lg" href="tel:' . esc_attr( parse_phone( $phone ) ) . '"><i class="fa-solid fa-phone me-2"></i> Call Now</a></li>';
	$items .= '<li class="nav-item nav-item--cta d-lg-none">' . do_shortcode( '[whatsapp_link class="btn btn--lg has-whatsapp-background-color" icon="true" text="WhatsApp Us"]' ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whatsapp_link() escapes internally.
	$items .= '<li class="nav-item nav-item--cta d-lg-none"><a class="btn btn--lg btn--outline" href="/contact/">Get a Free Quote</a></li>';

	return $items;
}
add_filter( 'wp_nav_menu_items', 'lc_tidyjs2026_append_primary_nav_cta_items', 10, 2 );
