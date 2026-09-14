/**
 * Smooth scroll via Lenis, ported from lc-tidy2026 (same vendored
 * js/vendor/lenis.min.js, 1.3.11 — same options, so this stays compatible
 * with that exact build rather than a newer Lenis API). No import here for
 * the `Lenis` identifier — it's a global from the vendored script, loaded
 * as a dependency of theme.min.js (see inc/enqueue.php), so it's safe to
 * bundle this file straight into theme.js like any other module.
 */
export function initLenis() {
	if ( typeof Lenis === 'undefined' ) return;

	const lenis = new Lenis( {
		smooth: true,
		lerp: 0.1,
	} );

	function raf( time ) {
		lenis.raf( time );
		requestAnimationFrame( raf );
	}

	requestAnimationFrame( raf );
}
