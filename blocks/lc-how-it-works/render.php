<?php
/**
 * Block template for LC How it works.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$btitle         = $attributes['title'] ?? '';
$intro          = $attributes['intro'] ?? '';
$step_1_title   = $attributes['step1Title'] ?? '';
$step_1_content = $attributes['step1Content'] ?? '';
$step_2_title   = $attributes['step2Title'] ?? '';
$step_2_content = $attributes['step2Content'] ?? '';
$step_3_title   = $attributes['step3Title'] ?? '';
$step_3_content = $attributes['step3Content'] ?? '';
$highlight      = $attributes['highlight'] ?? '';

$anchor_id          = ! empty( $attributes['anchor'] ) ? $attributes['anchor'] : 'how-it-works';
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'how-it-works has-dark-700-background-color',
		'id'    => $anchor_id,
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container py-5">
		<h2 class="has-white-color"><?php echo esc_html( $btitle ); ?></h2>
		<?php if ( $intro ) { ?>
		<div class="has-light-800-color"><?php echo nl2br( esc_html( $intro ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
		<?php } ?>
		<div class="row justify-content-md-center gap-0 gap-md-5">
			<div class="col-12 col-md-4 mx-md-auto my-4" data-reveal="up">
				<div class="how-it-works__number has-1000-font-size fw-semibold ff-heading has-primary-500-color">1</div>
				<h3 class="how-it-works__title has-600-font-size has-white-color"><?php echo esc_html( $step_1_title ); ?></h3>
				<p class="how-it-works__text has-400-font-size has-light-800-color mb-0"><?php echo esc_html( $step_1_content ); ?></p>
			</div>
			<div class="col-12 col-md-4 mx-md-auto my-4" data-reveal="up" data-reveal-delay="200">
				<div class="how-it-works__number has-1000-font-size fw-semibold ff-heading has-primary-500-color">2</div>
				<h3 class="how-it-works__title has-600-font-size has-white-color"><?php echo esc_html( $step_2_title ); ?></h3>
				<p class="how-it-works__text has-400-font-size has-light-800-color mb-0"><?php echo esc_html( $step_2_content ); ?></p>
			</div>
			<div class="col-12 col-md-4 mx-md-auto my-4" data-reveal="up" data-reveal-delay="400">
				<div class="how-it-works__number has-1000-font-size fw-semibold ff-heading has-primary-500-color">3</div>
				<h3 class="how-it-works__title has-600-font-size has-white-color"><?php echo esc_html( $step_3_title ); ?></h3>
				<p class="how-it-works__text has-400-font-size has-light-800-color mb-0"><?php echo esc_html( $step_3_content ); ?></p>
			</div>
		</div>
		<?php if ( $highlight ) { ?>
		<div class="has-primary-500-background-color py-2 px-4 has-600-font-size text-center mt-4 text-uppercase fw-semibold text-balance" data-reveal="fade" data-reveal-delay="300">
			<?php echo esc_html( $highlight ); ?>
		</div>
		<?php } ?>
	</div>
</section>
