<?php
/**
 * Block template for LC FAQ.
 *
 * The old block defined its own local FAQPage-schema collector (a
 * function_exists()-guarded static accumulator hooked to wp_footer) so
 * multiple FAQ block instances on one page combine into a single schema
 * block instead of one per instance. This theme already has exactly that
 * as a generic utility — queue_faq_schema()/output_faq_schema() in
 * inc/utilities.php, already wp_footer-hooked — so this block just calls
 * it instead of carrying its own copy.
 *
 * Accordion is native <details>/<summary> (src/css/accordion.css), not
 * Bootstrap's collapse JS the old block used — no JS needed. Each block
 * instance gets its own wp_unique_id()-based `name` so multiple FAQ blocks
 * on the same page don't cross-collapse each other's items, matching the
 * accordion.css docblock's documented pattern.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title     = $attributes['title'] ?? '';
$intro     = $attributes['intro'] ?? '';
$faq_items = $attributes['faqItems'] ?? array();

$schema_items = array();
foreach ( $faq_items as $item ) {
	$schema_items[] = array(
		'question' => $item['question'] ?? '',
		'answer'   => $item['answer'] ?? '',
	);
}
queue_faq_schema( $schema_items );

$accordion_name = wp_unique_id( 'faq-' );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'faq py-5' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<h2><?php echo esc_html( $title ); ?></h2>
		<div class="faq__intro mb-5"><?php echo esc_html( $intro ); ?></div>
		<div class="faq__inner">
			<div class="accordion">
				<?php foreach ( $faq_items as $item ) { ?>
					<?php
					$question = $item['question'] ?? '';
					$answer   = $item['answer'] ?? '';
					if ( '' === $question || '' === $answer ) {
						continue;
					}
					?>
				<details class="accordion-item" name="<?php echo esc_attr( $accordion_name ); ?>">
					<summary class="accordion-header px-4">
						<?php echo wp_kses_post( $question ); ?>
						<svg class="accordion-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M8 2v12M2 8h12" /></svg>
					</summary>
					<div class="accordion-body">
						<div class="accordion-body__inner p-4">
							<?php echo wp_kses_post( $answer ); ?>
						</div>
					</div>
				</details>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
