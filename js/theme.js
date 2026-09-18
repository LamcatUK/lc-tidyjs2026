/*!
 * lc-tidyjs2026 v1.0.0 (https://github.com/LamcatUK/lc-tidyjs2026)
 * Copyright 2026 LamcatUK
 * Licensed under GPL-3.0
 */
(function () {
	'use strict';

	/**
	 * Mobile nav toggle. Wires any button with aria-controls pointing at a
	 * .navbar-collapse to show/hide it and keep aria-expanded in sync — this is
	 * the entire replacement for Bootstrap's Collapse component for this use case.
	 */
	function initNavToggle() {
	  document.querySelectorAll('.navbar-toggler[aria-controls]').forEach(toggler => {
	    const target = document.getElementById(toggler.getAttribute('aria-controls'));
	    if (!target) return;
	    toggler.addEventListener('click', () => {
	      const isOpen = target.classList.toggle('is-open');
	      toggler.setAttribute('aria-expanded', String(isOpen));
	    });

	    // Close after choosing a link — expected mobile nav behaviour.
	    target.querySelectorAll('a').forEach(link => {
	      link.addEventListener('click', () => {
	        target.classList.remove('is-open');
	        toggler.setAttribute('aria-expanded', 'false');
	      });
	    });
	  });
	}

	/**
	 * Click-to-open nav dropdowns. Each dropdown-toggle button shows/hides its
	 * linked .dropdown-menu and keeps aria-expanded in sync. Clicking elsewhere,
	 * or pressing Escape, closes whatever is open — this is the entire
	 * replacement for hover-based submenus.
	 */
	function initNavDropdowns() {
	  const toggles = document.querySelectorAll('.dropdown-toggle[aria-controls]');
	  function close(toggle) {
	    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
	    if (!menu) return;
	    menu.classList.remove('is-open');
	    toggle.setAttribute('aria-expanded', 'false');
	  }
	  function closeAllExcept(except) {
	    toggles.forEach(toggle => {
	      if (toggle !== except) close(toggle);
	    });
	  }
	  toggles.forEach(toggle => {
	    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
	    if (!menu) return;
	    toggle.addEventListener('click', event => {
	      event.stopPropagation();
	      const isOpen = menu.classList.toggle('is-open');
	      toggle.setAttribute('aria-expanded', String(isOpen));
	      closeAllExcept(toggle);
	    });
	  });
	  document.addEventListener('click', event => {
	    if (event.target.closest('.dropdown-menu')) return;
	    closeAllExcept();
	  });
	  document.addEventListener('keydown', event => {
	    if (event.key !== 'Escape') return;
	    const openToggle = Array.from(toggles).find(toggle => toggle.getAttribute('aria-expanded') === 'true');
	    closeAllExcept();
	    if (openToggle) openToggle.focus();
	  });
	}

	/**
	 * Native <dialog> wiring — replaces Bootstrap's Modal component entirely.
	 * showModal()/close() do the heavy lifting (focus trap, Escape-to-close,
	 * ::backdrop); this just connects trigger/close buttons to a target dialog.
	 *
	 * Markup:
	 *   <button data-dialog-target="my-dialog">Open</button>
	 *   <dialog id="my-dialog">
	 *     <button data-dialog-close>Close</button>
	 *     ...
	 *   </dialog>
	 */
	function initDialogs() {
	  document.querySelectorAll('[data-dialog-target]').forEach(trigger => {
	    const dialog = document.getElementById(trigger.getAttribute('data-dialog-target'));
	    if (!(dialog instanceof HTMLDialogElement)) return;
	    trigger.addEventListener('click', () => dialog.showModal());
	    dialog.querySelectorAll('[data-dialog-close]').forEach(closeBtn => {
	      closeBtn.addEventListener('click', () => dialog.close());
	    });

	    // Click on the backdrop (the dialog element itself, outside its content) closes it.
	    dialog.addEventListener('click', event => {
	      if (event.target === dialog) dialog.close();
	    });
	  });
	}

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
	function initReveal() {
	  const targets = document.querySelectorAll('[data-reveal]');
	  if (!targets.length) return;

	  // Respects prefers-reduced-motion by skipping the observer entirely —
	  // elements start already visible (see the reduced-motion override in
	  // src/css/reveal.css), so there's nothing left to reveal.
	  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
	    return;
	  }
	  const observer = new IntersectionObserver((entries, obs) => {
	    entries.forEach(entry => {
	      if (!entry.isIntersecting) return;
	      entry.target.classList.add('is-revealed');
	      obs.unobserve(entry.target);
	    });
	  }, {
	    threshold: 0.1,
	    rootMargin: '0px 0px -10% 0px'
	  });
	  targets.forEach(el => {
	    const delay = el.getAttribute('data-reveal-delay');
	    if (delay) {
	      el.style.transitionDelay = `${delay}ms`;
	    }
	    observer.observe(el);
	  });
	}

	/**
	 * Smooth scroll via Lenis, ported from lc-tidy2026 (same vendored
	 * js/vendor/lenis.min.js, 1.3.11 — same options, so this stays compatible
	 * with that exact build rather than a newer Lenis API). No import here for
	 * the `Lenis` identifier — it's a global from the vendored script, loaded
	 * as a dependency of theme.min.js (see inc/enqueue.php), so it's safe to
	 * bundle this file straight into theme.js like any other module.
	 */
	function initLenis() {
	  if (typeof Lenis === 'undefined') return;
	  const lenis = new Lenis({
	    smooth: true,
	    lerp: 0.1
	  });
	  function raf(time) {
	    lenis.raf(time);
	    requestAnimationFrame(raf);
	  }
	  requestAnimationFrame(raf);
	}

	/**
	 * single.php's in-page table of contents: marks the sidebar link for
	 * whichever H2 section is currently in view. Same IntersectionObserver
	 * approach as reveal.js, not a scroll listener — cheaper, and immune to
	 * the usual "which element is 'current' while scrolling fast" edge cases
	 * a naive scroll-position comparison runs into.
	 */
	function initToc() {
	  const links = document.querySelectorAll('.toc__link');
	  if (!links.length) return;
	  const linksById = new Map();
	  const targets = [];
	  links.forEach(link => {
	    const id = link.getAttribute('href').slice(1);
	    const target = document.getElementById(id);
	    if (!target) return;
	    linksById.set(id, link);
	    targets.push(target);
	  });
	  if (!targets.length) return;
	  function setActive(id) {
	    linksById.forEach((link, linkId) => {
	      link.classList.toggle('is-active', linkId === id);
	    });
	  }

	  // Headings crossing a line just below the sticky header, rather than
	  // "anywhere in the viewport" — that band is what actually reads as
	  // "the section you're currently at" while scrolling.
	  const observer = new IntersectionObserver(entries => {
	    entries.forEach(entry => {
	      if (entry.isIntersecting) {
	        setActive(entry.target.id);
	      }
	    });
	  }, {
	    rootMargin: '-15% 0px -70% 0px'
	  });
	  targets.forEach(target => observer.observe(target));

	  // Nothing has crossed the line yet on initial load (e.g. page opened
	  // already scrolled to a #hash) — fall back to the first heading so a
	  // link is always marked active rather than none at all.
	  setActive(targets[0].id);
	}

	/**
	 * Vanilla replacement for the "Redirect for CF7" plugin's only job: send the
	 * visitor to /thank-you/ once a form has sent successfully. That plugin's own
	 * script is a jQuery wrapper around exactly this DOM event, so listening for
	 * it natively lets the theme drop the jQuery/jQuery-migrate dependency it was
	 * the sole reason for loading (see inc/helpers.php, which dequeues the
	 * plugin's script and jQuery itself).
	 */
	function initCf7Redirect() {
	  document.addEventListener('wpcf7mailsent', () => {
	    window.location.href = '/thank-you/';
	  });
	}

	document.addEventListener('DOMContentLoaded', () => {
	  initNavToggle();
	  initNavDropdowns();
	  initDialogs();
	  initReveal();
	  initLenis();
	  initToc();
	  initCf7Redirect();
	});

})();
//# sourceMappingURL=theme.js.map
