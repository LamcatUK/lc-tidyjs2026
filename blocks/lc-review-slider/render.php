<?php
/**
 * Block template for LC Review Slider.
 *
 * Title/intro copy is static, matching the old ACF block — the only real
 * field there was review_button_shortcode. Testimonials come from the
 * `testimonial` CPT (inc/posttypes.php); location is a plain post meta
 * field (inc/helpers.php's metabox), quote replaces what used to be the
 * post's main editor content (that CPT has no block editor at all now).
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$review_button_shortcode = $attributes['reviewButtonShortcode'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'review_slider pt-5 pb-4' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<h2 class="has-white-color">What Our Customers Say</h2>
		<div class="has-light-800-color mb-4">From single-item collections to full property clearances, our work speaks for itself. Here&#8217;s feedback from customers across the island.</div>
		<div class="row">
			<div class="col-12 col-md-9">
				<div class="review_slider__slider" data-lenis-prevent>
					<div class="swiper-wrapper">
						<?php
						$testimonials = new WP_Query(
							array(
								'post_type'      => 'testimonial',
								'posts_per_page' => -1,
							)
						);
						// review-slider-init.js runs this in loop:true mode with
						// loopAdditionalSlides:3, at up to slidesPerView:2 (its 992px
						// breakpoint) — Swiper warns (and loop breaks) below roughly
						// slidesPerView + loopAdditionalSlides slides. Padding out to a
						// comfortable minimum by repeating the real testimonials, rather
						// than disabling loop, keeps autoplay looping smoothly regardless
						// of how many testimonials happen to exist.
						//
						// Padding $testimonials->posts/post_count directly (rather than
						// looping a separately-built padded array through the standalone
						// setup_postdata() function) matters here: that function just
						// delegates to $GLOBALS['wp_query']->setup_postdata() — the
						// *main* query, not this one — so calling it with a post from
						// $testimonials produced wrong/blank data every time.
						// $testimonials->the_post() is this query's own method and
						// doesn't have that problem.
						$min_slides_for_loop = 6;
						if ( $testimonials->post_count && $testimonials->post_count < $min_slides_for_loop ) {
							$original = $testimonials->posts;
							while ( count( $testimonials->posts ) < $min_slides_for_loop ) {
								$testimonials->posts = array_merge( $testimonials->posts, $original );
							}
							$testimonials->post_count = count( $testimonials->posts );
						}

						while ( $testimonials->have_posts() ) {
							$testimonials->the_post();
							$location = get_post_meta( get_the_ID(), 'location', true );
							$quote    = get_post_meta( get_the_ID(), 'quote', true );
							?>
						<div class="review_slider__slide swiper-slide">
							<div class="review_slider__card">
								<div class="review_slider__title has-600-font-size has-white-color"><?php echo esc_html( get_the_title() ); ?>, <span class="has-primary-500-color"><?php echo esc_html( $location ); ?></span></div>
								<div class="review_slider__review has-400-font-size has-light-800-color"><?php echo wp_kses_post( $quote ); ?></div>
							</div>
						</div>
							<?php
						}
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-3">
				<div class="review_slider__shortcode">
					<div class="review_slider__preamble">
						<div class="review_slider__stars">
							<span class="fa fa-star"></span>
							<span class="fa fa-star"></span>
							<span class="fa fa-star"></span>
							<span class="fa fa-star"></span>
							<span class="fa fa-star"></span>
						</div>
						<div>Rated 5 stars on Google</div>
					</div>
					<?php echo do_shortcode( $review_button_shortcode ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
