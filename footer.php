</main>

<footer id="footer">
	<div class="container py-5">
		<div class="row gap-4 mb-4">
			<div class="col-12 col-lg-3 text-lg-start">
				<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/tidy-solutions-logo--wh.svg' ); ?>" width="270" height="74" class="footer__logo" alt="<?php bloginfo( 'name' ); ?> Logo" loading="lazy">
			</div>
			<div class="col-12 col-sm-6 col-lg-6">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
			<div class="col-12 col-lg-3 text-lg-start">
				<div class="mb-2"><i class="fa-solid fa-phone"></i> <?php echo do_shortcode( '[contact_phone]' ); ?></div>
				<div class="mb-2"><i class="fa-solid fa-paper-plane"></i> <?php echo do_shortcode( '[contact_email]' ); ?></div>
				<?php $social_icons = do_shortcode( '[social_icons class="has-700-font-size"]' ); ?>
				<?php if ( $social_icons ) { ?>
				<div class="mt-3">
					Connect: <?php echo $social_icons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built by the [social_icons] shortcode, already escaped there. ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div id="colophon">
		<div class="container py-2">
			<div class="d-flex flex-wrap justify-content-between">
				<div class="text-md-start">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
				</div>
				<div class="d-flex align-items-center justify-content-end flex-wrap gap-1">
					<span><a href="/privacy-policy/">Privacy</a> &amp; <a href="/cookie-policy/">Cookies</a></span> |
					<span>Site by <a href="https://www.lamcat.co.uk/" rel="nofollow noopener" target="_blank">Lamcat</a></span>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
