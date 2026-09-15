/**
 * lc-latest-guides block: Swiper init. Same loading mechanism as
 * review-slider-init.js alongside it — a plain <script> depending on
 * Swiper's global via wp_enqueue_script's dependency array (see
 * inc/enqueue.php), not an ES import into theme.js.
 *
 * 1 slide on mobile, 2 on tablet (md), 3 on desktop (lg) — matches this
 * theme's own breakpoints (src/build/tokens.config.js).
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var sliderEl = document.querySelector( '.latest-guides__slider' );
	if ( ! sliderEl || 'undefined' === typeof Swiper ) {
		return;
	}

	new Swiper( '.latest-guides__slider', {
		slidesPerView: 1,
		spaceBetween: 24,
		breakpoints: {
			768: {
				slidesPerView: 2,
			},
			992: {
				slidesPerView: 3,
			},
		},
	} );
} );
