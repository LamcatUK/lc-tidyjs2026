/**
 * Fade/slide-in-on-scroll reveal, replacing lc-tidy2026's AOS dependency.
 * The old theme only ever used AOS for a one-shot "fade in once, the first
 * time it scrolls into view" effect (AOS.init({ once: true }) there) — small
 * enough to not need a whole library for. Reach for GSAP instead of
 * extending this if a block ever needs more than a fade/slide reveal
 * (staggered timelines, scroll-scrubbed effects, etc.) — see
 * inc/enqueue.php's commented-out gsap vendor line.
 *
 * Markup: `data-reveal="fade"` (opacity only) or `data-reveal="up"`
 * (opacity + translateY, AOS's old "fade-up"). Optional `data-reveal-delay`
 * in ms, matching AOS's old data-aos-delay values verbatim where ported.
 */
export function initReveal() {
	const targets = document.querySelectorAll('[data-reveal]');
	if (!targets.length) return;

	// Respects prefers-reduced-motion by skipping the observer entirely —
	// elements start already visible (see the reduced-motion override in
	// src/css/reveal.css), so there's nothing left to reveal.
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	const observer = new IntersectionObserver(
		(entries, obs) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) return;
				entry.target.classList.add('is-revealed');
				obs.unobserve(entry.target);
			});
		},
		{ threshold: 0.1, rootMargin: '0px 0px -10% 0px' }
	);

	targets.forEach((el) => {
		const delay = el.getAttribute('data-reveal-delay');
		if (delay) {
			el.style.transitionDelay = `${delay}ms`;
		}
		observer.observe(el);
	});
}
