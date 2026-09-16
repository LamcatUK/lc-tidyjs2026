<?php
/**
 * Block template for LC Blog FAQ — a plain-content alternative to LC FAQ
 * (blocks/lc-faq) for use inside a blog post's own body, matching the
 * "Quick answers" pattern posts already use by hand (a heading, then one
 * bold-question paragraph per answer) rather than that block's styled
 * section/accordion, which reads as an out-of-place component dropped
 * into an article rather than part of it.
 *
 * Reuses queue_faq_schema()/output_faq_schema() (inc/utilities.php) for
 * the actual FAQPage JSON-LD — same schema output as LC FAQ, just without
 * that block's visual chrome.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title     = $attributes['title'] ?? '';
$faq_items = $attributes['faqItems'] ?? array();

$schema_items = array();
foreach ( $faq_items as $item ) {
	if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
		continue;
	}
	$schema_items[] = array(
		'question' => $item['question'],
		'answer'   => $item['answer'],
	);
}
queue_faq_schema( $schema_items );

$wrapper_attributes = get_block_wrapper_attributes();
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<?php if ( $title ) { ?>
	<h2 class="wp-block-heading"><?php echo esc_html( $title ); ?></h2>
	<?php } ?>
	<?php foreach ( $schema_items as $item ) { ?>
	<p><strong><?php echo esc_html( $item['question'] ); ?></strong> <?php echo wp_kses_post( $item['answer'] ); ?></p>
	<?php } ?>
</div>
