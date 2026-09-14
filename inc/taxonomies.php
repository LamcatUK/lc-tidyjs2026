<?php
/**
 * Custom Taxonomies Registration
 *
 * Duplicate the register_taxonomy() call below (commented out) as a
 * starting point for a new taxonomy — nothing is registered by default.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register custom taxonomies for the theme.
 *
 * @return void
 */
function lc_tidyjs2026_register_theme_taxonomies() {

	// Ported from lc-tidy2026's inc/lc-taxonomies.php — backs the lc-areas
	// block's "areas we cover" list (lc_tidyjs2026_render_areas_we_cover(),
	// inc/utilities.php) and area landing pages under /areas/{slug}.
	register_taxonomy(
		'area',
		array( 'page' ),
		array(
			'labels'             => array(
				'name'          => 'Areas',
				'singular_name' => 'Area',
			),
			'public'             => false,
			'publicly_queryable' => false,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rewrite'            => false,
		)
	);

}
add_action( 'init', 'lc_tidyjs2026_register_theme_taxonomies' );
