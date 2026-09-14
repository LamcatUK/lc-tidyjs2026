<?php
/**
 * Block template for LC How Stack.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title = $attributes['title'] ?? '';
$intro = $attributes['intro'] ?? '';

$steps = array(
	array(
		'title'    => $attributes['step1Title'] ?? '',
		'subtitle' => $attributes['step1Subtitle'] ?? '',
		'content'  => $attributes['step1Content'] ?? '',
	),
	array(
		'title'    => $attributes['step2Title'] ?? '',
		'subtitle' => $attributes['step2Subtitle'] ?? '',
		'content'  => $attributes['step2Content'] ?? '',
	),
	array(
		'title'    => $attributes['step3Title'] ?? '',
		'subtitle' => $attributes['step3Subtitle'] ?? '',
		'content'  => $attributes['step3Content'] ?? '',
	),
);

$highlight = $attributes['highlight'] ?? '';

// The step title's heading level drops to h3 once there's a real h2 above
// it (the block's own title) — kept exactly as the old block computed it.
$has_title    = ! empty( $title );
$step_heading = $has_title ? 'h3' : 'h2';

$anchor_id           = ! empty( $attributes['anchor'] ) ? $attributes['anchor'] : '';
$wrapper_attributes = get_block_wrapper_attributes(
	array_filter(
		array(
			'class' => 'how-stack has-dark-700-background-color',
			'id'    => $anchor_id,
		)
	)
);

$reveal_delays = array( '', '100', '200' );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container py-5">
		<?php if ( $has_title ) { ?>
		<h2 class="has-white-color"><?php echo esc_html( $title ); ?></h2>
		<?php } ?>
		<?php if ( $intro ) { ?>
		<div class="has-light-800-color"><?php echo nl2br( esc_html( $intro ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
		<?php } ?>
		<?php foreach ( $steps as $index => $step ) { ?>
		<div class="row mb-5" data-reveal="up" <?php echo $reveal_delays[ $index ] ? 'data-reveal-delay="' . esc_attr( $reveal_delays[ $index ] ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_attr() above. ?>>
			<div class="col-12 col-md-1">
				<div class="how-stack__number has-1000-font-size fw-semibold ff-heading has-primary-500-color"><?php echo (int) ( $index + 1 ); ?></div>
			</div>
			<div class="col-12 col-md-11">
				<?php echo '<' . esc_attr( $step_heading ) . ' class="how-stack__title has-700-font-size has-white-color">' . esc_html( $step['title'] ) . '</' . esc_attr( $step_heading ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_attr()/esc_html() above. ?>
				<p class="has-600-font-size has-white-color"><strong><?php echo esc_html( $step['subtitle'] ); ?></strong></p>
				<div class="how-stack__text has-400-font-size has-light-800-color mb-0"><?php echo wp_kses_post( wpautop( $step['content'] ) ); ?></div>
			</div>
		</div>
		<?php } ?>
		<?php if ( $highlight ) { ?>
		<div class="has-primary-500-background-color py-2 has-600-font-size text-center mt-4 text-uppercase fw-semibold text-balance" data-reveal="fade" data-reveal-delay="300">
			<?php echo esc_html( $highlight ); ?>
		</div>
		<?php } ?>
	</div>
</section>
