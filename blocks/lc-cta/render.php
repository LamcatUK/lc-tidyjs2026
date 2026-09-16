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

$is_post_cta = is_singular( 'post' );

$wrapper_classes = 'cta has-dark-700-background-color py-5';
if ( $is_post_cta ) {
	$wrapper_classes .= ' cta--post';
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $wrapper_classes ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<?php
	if ( $is_post_cta ) {
		?>
	<div class="cta__content">
		<h2 class="h1 has-white-color"><?php echo esc_html( $cta_title ); ?></h2>
		<div class="has-600-font-size has-light-500-color text-balance">
			<?php echo nl2br( esc_html( $content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?>
		</div>
	</div>
	<div class="d-flex flex-column align-items-center gap-3">
		<a class="btn btn--lg w-100 text-center d-none d-sm-inline-block" href="tel:<?php echo esc_attr( parse_phone( $phone ) ); ?>"><i class="fa-solid fa-phone me-2"></i> Call <?php echo esc_html( $phone ); ?></a>
		<a class="btn btn--lg w-100 text-center d-sm-none" href="tel:<?php echo esc_attr( parse_phone( $phone ) ); ?>"><i class="fa-solid fa-phone me-2"></i> Call Now</a>
		<?php echo do_shortcode( '[whatsapp_link class="w-100 d-sm-none btn btn--lg has-whatsapp-background-color" icon=true text="WhatsApp Us"]' ); ?>
		<a class="btn btn--lg btn--outline btn--outline-white w-100 text-center" href="/contact/">Get a Free Quote</a>
	</div>
		<?php
	} else {
		?>
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
				<?php echo do_shortcode( '[whatsapp_link class="w-100 d-sm-none btn btn--lg has-whatsapp-background-color" icon=true text="WhatsApp Us"]' ); ?>
				<a class="btn btn--lg btn--outline btn--outline-white w-100 text-center" href="/contact/">Get a Free Quote</a>
			</div>
		</div>
	</div>
		<?php
	}
	?>
</section>
