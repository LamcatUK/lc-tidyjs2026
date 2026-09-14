<?php
/**
 * Block template for LC CTA.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$cta_title = $attributes['ctaTitle'] ?? '';
$content   = $attributes['content'] ?? '';
$phone     = lc_tidyjs2026_get_setting( 'phone' );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'cta has-dark-700-background-color py-5' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container py-5">
		<div class="row gap-5 align-items-center">
			<div class="col-12 col-md-8">
				<div class="cta__content">
					<h2 class="h1 has-white-color"><?php echo esc_html( $cta_title ); ?></h2>
					<div class="has-700-font-size has-light-500-color">
						<?php echo nl2br( esc_html( $content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-4 d-flex flex-column align-items-center gap-3">
				<a class="btn btn--lg w-100 text-center d-none d-sm-inline-block" href="tel:<?php echo esc_attr( parse_phone( $phone ) ); ?>"><i class="fa-solid fa-phone me-2"></i> Call <?php echo esc_html( $phone ); ?></a>
				<a class="btn btn--lg w-100 text-center d-sm-none" href="tel:<?php echo esc_attr( parse_phone( $phone ) ); ?>"><i class="fa-solid fa-phone me-2"></i> Call Now</a>
				<?php echo do_shortcode( '[whatsapp_link class="d-sm-none btn btn--lg has-whatsapp-background-color" icon=true text="WhatsApp Us"]' ); ?>
				<a class="btn btn--lg btn--outline btn--outline-white w-100 text-center" href="/contact/">Get a Free Quote</a>
			</div>
		</div>
	</div>
</section>
