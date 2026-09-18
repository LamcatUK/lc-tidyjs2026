<?php
/**
 * Project-specific helpers — coupled to this project's own field schema or
 * content structure, unlike inc/utilities.php's project-agnostic functions.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Testimonial CPT metabox — quote (textarea) + location (text), replacing
 * the block editor entirely for this post type (see inc/posttypes.php,
 * 'testimonial' registered with no 'editor' support and show_in_rest
 * false). location was an ACF field in lc-tidy2026, plain text stored under
 * the same meta key ('location') so nothing needs migrating on the data
 * side if content is ever copied across; 'quote' replaces what used to be
 * the post's main editor content.
 *
 * @return void
 */
function lc_tidyjs2026_add_testimonial_metabox() {
	add_meta_box(
		'lc_tidyjs2026_testimonial_details',
		'Testimonial Details',
		'lc_tidyjs2026_render_testimonial_metabox',
		'testimonial',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'lc_tidyjs2026_add_testimonial_metabox' );

/**
 * Render the testimonial metabox fields.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function lc_tidyjs2026_render_testimonial_metabox( $post ) {
	wp_nonce_field( 'lc_tidyjs2026_save_testimonial', 'lc_tidyjs2026_testimonial_nonce' );

	$quote    = get_post_meta( $post->ID, 'quote', true );
	$location = get_post_meta( $post->ID, 'location', true );
	?>
	<p>
		<label for="lc_tidyjs2026_testimonial_quote"><strong>Quote</strong></label><br>
		<textarea
			id="lc_tidyjs2026_testimonial_quote"
			name="lc_tidyjs2026_testimonial_quote"
			rows="5"
			style="width: 100%;"
		><?php echo esc_textarea( $quote ); ?></textarea>
	</p>
	<p>
		<label for="lc_tidyjs2026_testimonial_location"><strong>Location</strong></label><br>
		<input
			type="text"
			id="lc_tidyjs2026_testimonial_location"
			name="lc_tidyjs2026_testimonial_location"
			value="<?php echo esc_attr( $location ); ?>"
			class="regular-text"
		>
	</p>
	<?php
}

/**
 * Save the testimonial metabox fields.
 *
 * @param int $post_id Post being saved.
 * @return void
 */
function lc_tidyjs2026_save_testimonial_metabox( $post_id ) {
	if ( ! isset( $_POST['lc_tidyjs2026_testimonial_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['lc_tidyjs2026_testimonial_nonce'] ), 'lc_tidyjs2026_save_testimonial' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['lc_tidyjs2026_testimonial_quote'] ) ) {
		update_post_meta( $post_id, 'quote', sanitize_textarea_field( wp_unslash( $_POST['lc_tidyjs2026_testimonial_quote'] ) ) );
	}
	if ( isset( $_POST['lc_tidyjs2026_testimonial_location'] ) ) {
		update_post_meta( $post_id, 'location', sanitize_text_field( wp_unslash( $_POST['lc_tidyjs2026_testimonial_location'] ) ) );
	}
}
add_action( 'save_post_testimonial', 'lc_tidyjs2026_save_testimonial_metabox' );

/**
 * Find the /areas/{slug} landing page for an 'area' taxonomy term, if one exists.
 *
 * Ported from lc-tidy2026 (inc/lc-theme.php's lc_get_area_page_by_slug()).
 *
 * @param string $slug Area term slug.
 * @return WP_Post|null
 */
function lc_tidyjs2026_get_area_page_by_slug( string $slug ) {
	$parent = get_page_by_path( 'areas' );
	if ( ! $parent ) {
		return null;
	}
	// get_page_by_path() doesn't do nested path matching against a known
	// parent object, so fetch by the full path "areas/{$slug}" instead.
	$page = get_page_by_path( 'areas/' . $slug );
	return $page instanceof WP_Post ? $page : null;
}

/**
 * Render the list of areas covered, using the 'area' taxonomy — each term
 * links to its /areas/{slug} landing page where one exists, plain text
 * otherwise.
 *
 * Ported from lc-tidy2026 (inc/lc-theme.php's
 * lc_render_areas_we_cover_from_taxonomy()). Used by the lc-areas block.
 *
 * @return void
 */
function lc_tidyjs2026_render_areas_we_cover() {
	$areas = get_terms(
		array(
			'taxonomy'   => 'area',
			'parent'     => 0,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $areas ) || empty( $areas ) ) {
		return;
	}

	echo '<ul class="areas__list">';

	foreach ( $areas as $term ) {
		$slug = $term->slug;
		$page = lc_tidyjs2026_get_area_page_by_slug( $slug );

		echo '<li class="areas__item">';
		if ( $page ) {
			echo '<a class="areas__link" href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( $term->name ) . '</a>';
		} else {
			echo '<span class="areas__text">' . esc_html( $term->name ) . '</span>';
		}
		echo '</li>';
	}

	echo '</ul>';
}
