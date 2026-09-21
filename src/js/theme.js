import { initNavToggle } from './nav-toggle';
import { initNavDropdowns } from './nav-dropdown';
import { initDialogs } from './dialog';
import { initReveal } from './reveal';
import { initLenis } from './lenis-init';
import { initToc } from './toc';
import { initCf7Redirect } from './cf7-redirect';
import { initClickTracking } from './click-tracking';

document.addEventListener('DOMContentLoaded', () => {
	initNavToggle();
	initNavDropdowns();
	initDialogs();
	initReveal();
	initLenis();
	initToc();
	initCf7Redirect();
	initClickTracking();
});
