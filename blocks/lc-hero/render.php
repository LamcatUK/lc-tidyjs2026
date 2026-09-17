<?php
/**
 * Block template for LC Hero.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title     = $attributes['title'] ?? '';
$intro     = $attributes['intro'] ?? '';
$image_id  = $attributes['imageId'] ?? 0;
$image_url = $attributes['imageUrl'] ?? '';
$image_alt = $attributes['imageAlt'] ?: $title;
$usps      = $attributes['usps'] ?? '';
$phone     = lc_tidyjs2026_get_setting( 'phone' );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'hero' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-6 my-auto">
				<h1 class="has-900-font-size fw-semibold mb-4"><span class="headline-underline"><?php echo esc_html( $title ); ?></span></h1>
				<p class="has-700-font-size mb-5"><?php echo esc_html( $intro ); ?></p>
			</div>
			<div class="col-12 col-md-6 my-auto mb-4">
				<?php if ( $image_id ) { ?>
					<?php
					echo wp_get_attachment_image(
						$image_id,
						'full',
						false,
						array(
							'class'         => 'hero__image',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
							'alt'           => $image_alt,
						)
					);
					?>
				<?php } elseif ( $image_url ) { ?>
				<img src="<?php echo esc_url( $image_url ); ?>" class="hero__image" loading="eager" fetchpriority="high" alt="<?php echo esc_attr( $image_alt ); ?>">
				<?php } ?>
			</div>
			<?php if ( $usps ) { ?>
			<div class="col-12 my-auto d-flex flex-wrap justify-content-around gap-4 has-600-font-size hero__bar">
				<?php
				$usp_lines = preg_split( '/\r\n|\r|\n/', $usps, -1, PREG_SPLIT_NO_EMPTY );
				foreach ( $usp_lines as $usp ) {
					$usp = trim( $usp );
					if ( empty( $usp ) ) {
						continue;
					}
					$parts = explode( ':', $usp, 2 );
					if ( 2 === count( $parts ) ) {
						$icon = trim( $parts[0] );
						$desc = trim( $parts[1] );
						?>
				<div>
					<i class="fa-solid <?php echo esc_attr( $icon ); ?>"></i>
					<?php echo esc_html( $desc ); ?>
				</div>
						<?php
					}
				}
				?>
			</div>
			<?php } ?>
			<?php
			$hide_on_contact   = is_page( 'contact' );
			$hide_on_thank_you = is_page( array( 'thank-you', 'contact/thank-you' ) );

			if ( ! $hide_on_contact && ! $hide_on_thank_you ) {
				?>
			<div class="col-12 pt-4 d-flex flex-wrap justify-content-center gap-4">
				<a class="btn btn--lg w-100 w-sm-auto text-center d-none d-sm-inline-block" href="tel:<?php echo esc_attr( parse_phone( $phone ) ); ?>"><i class="fa-solid fa-phone me-2"></i> Call <?php echo esc_html( $phone ); ?></a>
				<a class="btn btn--lg w-100 w-sm-auto text-center d-sm-none" href="tel:<?php echo esc_attr( parse_phone( $phone ) ); ?>"><i class="fa-solid fa-phone me-2"></i> Call Now</a>
				<?php echo do_shortcode( '[whatsapp_link class="w-100 w-sm-auto text-center d-sm-none btn btn--lg has-whatsapp-background-color" icon=true text="WhatsApp Us"]' ); ?>
				<a class="btn btn--lg btn--outline w-100 w-sm-auto text-center" href="/contact/">Get a Free Quote</a>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
