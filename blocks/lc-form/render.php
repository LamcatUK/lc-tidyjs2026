<?php
/**
 * Block template for LC Form.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$contact_form_id = $attributes['contactFormId'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'form-block' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<div class="has-600-font-size mb-4">
			<p class="mb-3">Call us on <?php echo do_shortcode( '[contact_phone]' ); ?>, or message on <?php echo do_shortcode( '[whatsapp_link]' ); ?>.</p>
			<p>Prefer not to call? Fill in the form below and we'll get back to you fast.</p>
			<p><a href="https://maps.app.goo.gl/NQfZEeBUGsMdfy8E6">Read our reviews on Google</a></p>
		</div>
		<div class="form-card">
			<?php echo do_shortcode( '[contact-form-7 id="' . esc_attr( $contact_form_id ) . '"]' ); ?>
		</div>
	</div>
</section>
