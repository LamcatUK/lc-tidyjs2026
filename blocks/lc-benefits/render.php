<?php
/**
 * Block template for LC Benefits.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title    = $attributes['title'] ?? '';
$benefits = $attributes['benefits'] ?? array();

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'benefits py-5 has-dark-800-background-color' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<h2 class="has-white-color mb-4"><?php echo esc_html( $title ); ?></h2>
		<div class="benefits__grid">
			<?php foreach ( $benefits as $index => $benefit ) { ?>
				<?php
				$icon_url = $benefit['iconUrl'] ?? '';
				$b_title  = $benefit['title'] ?? '';
				$text     = $benefit['text'] ?? '';
				?>
			<div class="benefit" data-reveal="fade" data-reveal-delay="<?php echo esc_attr( ( $index + 1 ) * 200 ); ?>">
				<?php if ( $icon_url ) { ?>
				<div class="benefit__icon-wrapper">
					<img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $b_title ); ?>" class="benefit__icon" loading="lazy">
				</div>
				<?php } ?>
				<h3 class="benefit__title has-600-font-size has-white-color"><?php echo esc_html( $b_title ); ?></h3>
				<p class="benefit__text has-400-font-size has-light-800-color mb-0"><?php echo nl2br( esc_html( $text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></p>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
