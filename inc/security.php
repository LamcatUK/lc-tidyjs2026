<?php
/**
 * Fixed site hardening not covered by the lcp-blog-options plugin.
 *
 * The plugin already handles comments, gravatars, tags, emojis, dashboard
 * widgets and (opt-in) the security response headers, so none of that is
 * repeated here. This file adds head cleanup, XML-RPC lockdown, generic
 * login errors and author/REST user-enumeration blocks.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Strip version and discovery tags from wp_head: generator, RSD, WLW
 * manifest, shortlink and pingback link. Runs at file load — everything
 * it touches fires later, so no hook needed.
 *
 * @return void
 */
function lc_tidyjs2026_head_cleanup() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'pingback_link' );
	add_filter( 'wp_headers', 'lc_tidyjs2026_remove_pingback_header' );
}
lc_tidyjs2026_head_cleanup();

/**
 * Drop the X-Pingback response header (pairs with the pingback_link
 * removal above).
 *
 * @param array $headers Response headers.
 * @return array
 */
function lc_tidyjs2026_remove_pingback_header( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}

/**
 * Disable XML-RPC: closes the pingback/trackback abuse vector. Comments
 * and pings are forced off by the lcp-blog-options plugin, so nothing
 * legitimate needs it.
 *
 * @return void
 */
function lc_tidyjs2026_disable_xmlrpc() {
	add_filter( 'xmlrpc_enabled', '__return_false' );
}
lc_tidyjs2026_disable_xmlrpc();

/**
 * Return a generic login error so failed logins never reveal whether
 * the username or the password was wrong.
 *
 * @return string
 */
function lc_tidyjs2026_generic_login_errors() {
	return __( 'Invalid username or password.', 'lc-tidyjs2026' );
}
add_filter( 'login_errors', 'lc_tidyjs2026_generic_login_errors' );

/**
 * Block ?author=N user enumeration scans: author archives and plain
 * author query vars redirect home instead of revealing usernames.
 *
 * @return void
 */
function lc_tidyjs2026_block_author_enumeration() {
	if ( is_author() || isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- value never used, presence alone triggers the redirect.
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'lc_tidyjs2026_block_author_enumeration' );

/**
 * Hide user routes (/wp/v2/users*) from the REST index for logged-out
 * visitors so usernames cannot be harvested in bulk. Logged-in requests
 * are untouched.
 *
 * @param array $endpoints Registered REST routes.
 * @return array
 */
function lc_tidyjs2026_restrict_rest_users( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}

	foreach ( array_keys( $endpoints ) as $route ) {
		if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
			unset( $endpoints[ $route ] );
		}
	}

	return $endpoints;
}
add_filter( 'rest_endpoints', 'lc_tidyjs2026_restrict_rest_users' );
