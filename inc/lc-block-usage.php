<?php
/**
 * Block usage shortcode for debugging/QA — [block_usage_table].
 *
 * Ported from cb-hts2026's cb-block-usage.php and adapted for the ACF-block-
 * to-native-block migration: this site's page/post content still carries
 * old `<!-- wp:acf/lc-{slug}` block instances left over from before the
 * theme switch (ACF isn't active here any more, so those render as "block
 * not found" on the front end) alongside `<!-- wp:lc-tidyjs2026/{slug}`
 * instances for blocks already migrated. The table below lists every block
 * slug seen in either form and which published pages/posts still have the
 * ACF version — that's the punch list for "find it, open the page, replace
 * it with the native block."
 *
 * Unlike the original (which enumerated block slugs from a blocks/cb-*.php
 * glob — a file-per-block convention this theme doesn't use for ACF blocks
 * at all, since the ACF originals live only in the old theme), this scans
 * post content directly for both comment forms, so it needs no list of ACF
 * block slugs maintained anywhere. Native block slugs are still cross-
 * checked against blocks/*\/block.json so an unmigrated JSX block with zero
 * usage anywhere still shows up as a row.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders a table of every block slug seen (ACF and/or native), and which
 * published pages/posts use which form of it.
 *
 * @return string HTML table of block usage.
 */
function lc_tidyjs2026_block_usage_table_shortcode() {
	$theme_slug = wp_get_theme()->get( 'TextDomain' );

	// Native blocks this theme currently registers — included even if a
	// migrated block hasn't been placed on any page yet.
	$block_names = array();
	foreach ( glob( get_stylesheet_directory() . '/blocks/*/block.json' ) as $file ) {
		$block_names[] = basename( dirname( $file ) );
	}

	$posts = get_posts(
		array(
			'post_type'      => array( 'page', 'post' ),
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);

	// usage_map[ slug ] = [ 'acf' => [ post, ... ], 'jsx' => [ post, ... ] ].
	$usage_map = array_fill_keys( $block_names, array( 'acf' => array(), 'jsx' => array() ) );

	foreach ( $posts as $post ) {
		preg_match_all( '/<!-- wp:acf\/([a-z0-9-]+)\s/', $post->post_content, $acf_matches );
		preg_match_all( '/<!-- wp:' . preg_quote( $theme_slug, '/' ) . '\/([a-z0-9-]+)\s/', $post->post_content, $jsx_matches );

		foreach ( array_unique( $acf_matches[1] ) as $slug ) {
			if ( ! isset( $usage_map[ $slug ] ) ) {
				$usage_map[ $slug ] = array(
					'acf' => array(),
					'jsx' => array(),
				);
			}
			$usage_map[ $slug ]['acf'][] = $post;
		}

		foreach ( array_unique( $jsx_matches[1] ) as $slug ) {
			if ( ! isset( $usage_map[ $slug ] ) ) {
				$usage_map[ $slug ] = array(
					'acf' => array(),
					'jsx' => array(),
				);
			}
			$usage_map[ $slug ]['jsx'][] = $post;
		}
	}

	ksort( $usage_map );

	/**
	 * Renders one "Used In" cell's post list.
	 *
	 * @param WP_Post[] $posts_using_block Posts to list.
	 * @return string
	 */
	$render_post_list = function ( $posts_using_block ) {
		if ( empty( $posts_using_block ) ) {
			return '<em style="color: #999;">Not used</em>';
		}
		$items = '';
		foreach ( $posts_using_block as $post ) {
			$items .= '<li><a href="' . esc_url( get_edit_post_link( $post->ID ) ) . '" target="_blank">' . esc_html( $post->post_title ) . '</a> <span style="color: #999; font-size: 0.9em;">(' . esc_html( ucfirst( $post->post_type ) ) . ')</span></li>';
		}
		return '<ul style="margin: 0; padding-left: 20px;">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_url()/esc_html() above.
	};

	// Inline-styled on purpose — this is a standalone QA utility that should
	// look reasonable on any project regardless of whether it has opted
	// into src/css/tables.css.
	ob_start();
	?>
	<div style="padding: 2rem;">
	<table style="width: 100%; border-collapse: collapse;">
		<thead>
			<tr style="border-bottom: 2px solid #ccc;">
				<th style="text-align: left; padding: 8px; font-weight: bold;">Block Name</th>
				<th style="text-align: left; padding: 8px; font-weight: bold; color: #b32d2e;">ACF — needs migrating</th>
				<th style="text-align: left; padding: 8px; font-weight: bold; color: #2a7a2a;">Native (JSX)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $usage_map as $block_name => $usage ) : ?>
				<tr style="border-bottom: 1px solid #eee;">
					<td style="padding: 8px; vertical-align: top;"><?php echo esc_html( $block_name ); ?></td>
					<td style="padding: 8px; vertical-align: top;"><?php echo $render_post_list( $usage['acf'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $render_post_list() output is already escaped. ?></td>
					<td style="padding: 8px; vertical-align: top;"><?php echo $render_post_list( $usage['jsx'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $render_post_list() output is already escaped. ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'block_usage_table', 'lc_tidyjs2026_block_usage_table_shortcode' );
