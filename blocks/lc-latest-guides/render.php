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

$posts_page_id  = (int) get_option( 'page_for_posts' );
$posts_page_url = $posts_page_id ? get_permalink( $posts_page_id ) : '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'latest-guides py-5' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="latest-guides__header">
			<h2 class="latest-guides__heading"><?php esc_html_e( 'Tidy Guides', 'lc-tidyjs2026' ); ?></h2>
			<?php if ( $posts_page_url ) { ?>
			<a class="btn btn--outline" href="<?php echo esc_url( $posts_page_url ); ?>"><?php esc_html_e( 'Guides', 'lc-tidyjs2026' ); ?></a>
			<?php } ?>
		</div>
		<div class="latest-guides__slider" data-lenis-prevent>
			<div class="swiper-wrapper">
				<?php
				// Exclude the post this block itself renders inside (if any) —
				// otherwise that post is its own most-recent result, and
				// lc_tidyjs2026_render_post_card()'s apply_filters( 'the_content' )
				// call (for the reading-time figure) re-renders this same block,
				// recursing until PHP's memory limit kills the request.
				$current_post_id = get_the_ID();
				$latest_guides    = new WP_Query(
					array(
						'post_type'           => 'post',
						'posts_per_page'      => 3,
						'post__not_in'        => $current_post_id ? array( $current_post_id ) : array(),
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);
				while ( $latest_guides->have_posts() ) {
					$latest_guides->the_post();
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
