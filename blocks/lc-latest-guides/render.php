<?php
/**
 * Block template for LC Latest Guides — the 3 most recent posts in a
 * Swiper slider (3 slides at desktop width, 2 tablet, 1 mobile; see
 * js/vendor/latest-guides-init.js for the breakpoints). Reuses
 * lc_tidyjs2026_render_post_card() (inc/utilities.php) so a card here
 * looks identical to the ones on the Guides index and at the bottom of
 * single.php, rather than a third near-duplicate markup.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$pinned_ids     = array_map( 'intval', (array) ( $attributes['pinnedIds'] ?? array() ) );
$legacy_pin     = (int) ( $attributes['pinnedId'] ?? 0 ); // Earlier single-pin attribute.
$posts_page_id  = (int) get_option( 'page_for_posts' );
$posts_page_url = $posts_page_id ? get_permalink( $posts_page_id ) : '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'latest-guides py-5' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="latest-guides__header">
			<h2 class="latest-guides__heading"><?php esc_html_e( 'Tidy Guides', 'lc-tidyjs2026' ); ?></h2>
			<?php if ( $posts_page_url ) { ?>
			<a class="btn btn--outline" href="<?php echo esc_url( $posts_page_url ); ?>"><?php esc_html_e( 'All Guides', 'lc-tidyjs2026' ); ?></a>
			<?php } ?>
		</div>
		<div class="latest-guides__slider">
			<div class="swiper-wrapper">
				<?php
				// Exclude the post this block itself renders inside (if any) —
				// otherwise that post is its own most-recent result, and
				// lc_tidyjs2026_render_post_card()'s apply_filters( 'the_content' )
				// call (for the reading-time figure) re-renders this same block,
				// recursing until PHP's memory limit kills the request.
				$current_post_id = get_the_ID();

				// Pinned guides go first, in the order picked (published posts
				// only, no duplicates, never the post this block is on); the
				// latest guides then fill the remaining slots so the slider
				// still holds 3.
				if ( empty( $pinned_ids ) && $legacy_pin ) {
					$pinned_ids = array( $legacy_pin );
				}

				$guide_ids = array();
				foreach ( $pinned_ids as $pinned_id ) {
					if ( $pinned_id && $pinned_id !== $current_post_id && ! in_array( $pinned_id, $guide_ids, true ) && 'post' === get_post_type( $pinned_id ) && 'publish' === get_post_status( $pinned_id ) ) {
						$guide_ids[] = $pinned_id;
					}
				}
				$guide_ids = array_slice( $guide_ids, 0, 3 );

				if ( count( $guide_ids ) < 3 ) {
					$latest_guides = new WP_Query(
						array(
							'post_type'           => 'post',
							'posts_per_page'      => 3 - count( $guide_ids ),
							'post__not_in'        => array_filter( array_merge( array( $current_post_id ), $guide_ids ) ),
							'ignore_sticky_posts' => true,
							'no_found_rows'       => true,
							'fields'              => 'ids',
						)
					);
					$guide_ids     = array_merge( $guide_ids, $latest_guides->posts );
				}

				foreach ( $guide_ids as $guide_id ) {
					$GLOBALS['post'] = get_post( $guide_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- template-tag setup, restored by wp_reset_postdata() below.
					setup_postdata( $GLOBALS['post'] );
					?>
					<div class="swiper-slide">
						<?php lc_tidyjs2026_render_post_card(); ?>
					</div>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</section>
