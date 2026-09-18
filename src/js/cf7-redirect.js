/**
 * Vanilla replacement for the "Redirect for CF7" plugin's only job: send the
 * visitor to /thank-you/ once a form has sent successfully. That plugin's own
 * script is a jQuery wrapper around exactly this DOM event, so listening for
 * it natively lets the theme drop the jQuery/jQuery-migrate dependency it was
 * the sole reason for loading (see inc/helpers.php, which dequeues the
 * plugin's script and jQuery itself).
 */
export function initCf7Redirect() {
	document.addEventListener('wpcf7mailsent', () => {
		window.location.href = '/thank-you/';
	});
}
