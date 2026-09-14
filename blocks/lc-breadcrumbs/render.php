<?php
/**
 * Block template for LC Breadcrumbs.
 *
 * No ACF fields to port — the old block had only a `message` field and
 * derived its trail from either Yoast's breadcrumb or a post_parent
 * ancestry fallback. Yoast is being replaced, so this always uses the
 * ancestry fallback (lc_tidyjs2026_get_breadcrumbs(), already ported to
 * inc/utilities.php).
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$classes = 'lc-breadcrumbs';
if ( ! empty( $attributes['className'] ) ) {
	$classes .= ' ' . $attributes['className'];
}

$extra_attrs = ! empty( $attributes['anchor'] ) ? 'id="' . esc_attr( $attributes['anchor'] ) . '"' : '';

lc_tidyjs2026_render_breadcrumbs( lc_tidyjs2026_get_breadcrumbs(), $classes, $extra_attrs );
