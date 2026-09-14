/**
 * lc-review-slider block: Swiper init + equal-height slides. Ported from
 * lc-tidy2026's inline wp_footer script.
 *
 * Project code, NOT a third-party file, despite living in js/vendor/
 * alongside swiper-bundle.min.js — it's here because it shares the same
 * loading mechanism those files need: a plain <script> tag depending on
 * Swiper's global via wp_enqueue_script's own dependency array (see
 * inc/enqueue.php), not an ES import into the rollup bundle (theme.js),
 * which only handles this theme's own module-based JS.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var sliderEl = document.querySelector( '.review_slider__slider' );
	if ( ! sliderEl || 'undefined' === typeof Swiper ) {
		return;
	}

	function setEqualHeight( selector ) {
		var maxHeight = 0;
		var slides = document.querySelectorAll( selector );

		slides.forEach( function ( slide ) {
			slide.style.height = 'auto';
		} );

		slides.forEach( function ( slide ) {
			if ( slide.offsetHeight > maxHeight ) {
				maxHeight = slide.offsetHeight;
			}
		} );

		slides.forEach( function ( slide ) {
			slide.style.height = maxHeight + 'px';
		} );
	}

	new Swiper( '.review_slider__slider', {
		loop: true,
		loopAdditionalSlides: 3,
		autoplay: {
			delay: 4000,
			disableOnInteraction: false,
		},
		slidesPerView: 1,
		slidesPerGroup: 1,
		spaceBetween: 0,
		on: {
			init: function () {
				setEqualHeight( '.review_slider__slide' );
			},
			resize: function () {
				setEqualHeight( '.review_slider__slide' );
			},
		},
		breakpoints: {
			768: {
				slidesPerView: 1,
				spaceBetween: 0,
			},
			992: {
				slidesPerView: 2,
				spaceBetween: 0,
			},
		},
	} );
} );
