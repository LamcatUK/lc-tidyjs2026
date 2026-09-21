/**
 * Vanilla replacement for the "Redirect for CF7" plugin's only job: send the
 * visitor to a thank-you page once a form has sent successfully. That
 * plugin's own script was a jQuery wrapper around exactly this DOM event, so
 * listening for it natively lets the theme drop the jQuery/jQuery-migrate
 * dependency it was the sole reason for loading. The target URL is the
 * Site-Wide Settings "CF7 Redirect URL" field (inc/options.php), passed in
 * via wp_localize_script (inc/enqueue.php).
 */
export function initCf7Redirect() {
	document.addEventListener('wpcf7mailsent', () => {
		window.location.href = window.lcTidyjs2026Cf7?.redirectUrl || '/contact/thank-you/';
	});
}
