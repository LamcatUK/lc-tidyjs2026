/**
 * GA4 event tracking for CTAs and form submissions, ported from
 * lc-tidy2026's src/js/custom-javascript.js (that theme's compiled
 * child-theme.min.js) — same event names/params, so existing GA4 reports
 * built against them keep working. `.button` there is `.btn` here (this
 * theme's own button class), and the AOS/Bootstrap Collapse code it was
 * bundled alongside isn't relevant here (this theme uses data-reveal and
 * native <details> instead) so isn't ported.
 *
 * Listeners bind immediately on load and never wait on gtag — a click can
 * happen before gtag.js has loaded (it's deferred, and/or gated behind
 * cookie-consent), so an event fired too early is queued here and flushed
 * once gtag actually becomes callable, rather than silently dropped.
 */
export function initClickTracking() {
	const queue = [];

	function flushQueue() {
		if ( 'function' !== typeof window.gtag ) {
			return;
		}
		while ( queue.length ) {
			const [ eventName, params ] = queue.shift();
			window.gtag( 'event', eventName, params );
		}
	}

	function trackEvent( eventName, params ) {
		flushQueue();
		if ( 'function' === typeof window.gtag ) {
			window.gtag( 'event', eventName, params );
		} else {
			queue.push( [ eventName, params ] );
			startPolling();
		}
	}

	function labelFor( el ) {
		return el.getAttribute( 'aria-label' ) || el.textContent.trim().replace( /\s+/g, ' ' );
	}

	document.addEventListener( 'click', ( event ) => {
		const el = event.target.closest( 'a, button' );
		if ( ! el ) {
			return;
		}

		const href = el.getAttribute( 'href' ) || '';

		if ( href.startsWith( 'tel:' ) ) {
			trackEvent( 'phone_click', { button_name: labelFor( el ), link_url: href } );
			return;
		}

		if ( href.startsWith( 'mailto:' ) ) {
			trackEvent( 'email_click', { button_name: labelFor( el ), link_url: href } );
			return;
		}

		if ( href.includes( 'wa.me' ) || href.includes( 'api.whatsapp.com' ) ) {
			trackEvent( 'whatsapp_click', { button_name: labelFor( el ), link_url: href } );
			return;
		}

		if ( el.classList.contains( 'btn' ) ) {
			trackEvent( 'button_click', { button_name: labelFor( el ), link_url: href } );
		}
	} );

	document.addEventListener( 'wpcf7mailsent', ( event ) => {
		const form = event.target;
		trackEvent( 'form_submission', {
			form_name: form.querySelector( ".wpcf7-form-control[name='form_name']" )?.value || form.getAttribute( 'id' ) || 'contact_form',
			form_id: event.detail?.contactFormId || '',
		} );
	} );

	// Catches gtag becoming available with no further clicks happening
	// after the one that got queued — starts only once something is
	// actually waiting, keeps trying for ~15s, then gives up; a queued
	// event past that point still flushes on the visitor's next click,
	// same as normal. A single shared interval regardless of how many
	// events end up queued while it's running.
	let pollId = null;
	function startPolling() {
		if ( null !== pollId ) {
			return;
		}
		let attempts = 0;
		const maxAttempts = 30;
		pollId = window.setInterval( () => {
			attempts += 1;
			flushQueue();
			if ( 0 === queue.length || attempts >= maxAttempts ) {
				window.clearInterval( pollId );
				pollId = null;
			}
		}, 500 );
	}
}
