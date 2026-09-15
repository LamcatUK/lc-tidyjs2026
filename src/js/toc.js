/**
 * single.php's in-page table of contents: marks the sidebar link for
 * whichever H2 section is currently in view. Same IntersectionObserver
 * approach as reveal.js, not a scroll listener — cheaper, and immune to
 * the usual "which element is 'current' while scrolling fast" edge cases
 * a naive scroll-position comparison runs into.
 */
export function initToc() {
	const links = document.querySelectorAll( '.toc__link' );
	if ( ! links.length ) return;

	const linksById = new Map();
	const targets = [];

	links.forEach( ( link ) => {
		const id = link.getAttribute( 'href' ).slice( 1 );
		const target = document.getElementById( id );
		if ( ! target ) return;
		linksById.set( id, link );
		targets.push( target );
	} );

	if ( ! targets.length ) return;

	function setActive( id ) {
		linksById.forEach( ( link, linkId ) => {
			link.classList.toggle( 'is-active', linkId === id );
		} );
	}

	// Headings crossing a line just below the sticky header, rather than
	// "anywhere in the viewport" — that band is what actually reads as
	// "the section you're currently at" while scrolling.
	const observer = new IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					setActive( entry.target.id );
				}
			} );
		},
		{ rootMargin: '-15% 0px -70% 0px' }
	);

	targets.forEach( ( target ) => observer.observe( target ) );

	// Nothing has crossed the line yet on initial load (e.g. page opened
	// already scrolled to a #hash) — fall back to the first heading so a
	// link is always marked active rather than none at all.
	setActive( targets[ 0 ].id );
}
