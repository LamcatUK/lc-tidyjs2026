import { initNavToggle } from './nav-toggle';
import { initNavDropdowns } from './nav-dropdown';
import { initDialogs } from './dialog';
import { initReveal } from './reveal';
import { initLenis } from './lenis-init';
import { initToc } from './toc';

document.addEventListener('DOMContentLoaded', () => {
	initNavToggle();
	initNavDropdowns();
	initDialogs();
	initReveal();
	initLenis();
	initToc();
});
