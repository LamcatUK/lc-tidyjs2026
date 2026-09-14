<?php
/**
 * Block template for LC Service List.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title    = $attributes['title'] ?? '';
$intro    = $attributes['intro'] ?? '';
$services = $attributes['services'] ?? array();
$outro    = $attributes['outro'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'service-list' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes; also carries any chosen background/text colour classes (color support in block.json). ?>>
	<div class="container">
		<h2><?php echo esc_html( $title ); ?></h2>
		<p class="has-600-font-size mb-4"><?php echo esc_html( $intro ); ?></p>
		<div class="service-list__grid mb-4">
			<?php foreach ( $services as $service ) { ?>
				<?php
				$icon_url    = $service['iconUrl'] ?? '';
				$link_url    = $service['service'] ?? '';
				$link_title  = $service['serviceText'] ?? '';
				$description = $service['text'] ?? '';
				?>
			<a class="service-list__item" href="<?php echo esc_url( $link_url ); ?>">
				<?php if ( $icon_url ) { ?>
				<div class="service-list__icon-wrapper">
					<img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $link_title ); ?>" class="service-list__icon" loading="lazy">
				</div>
				<?php } ?>
				<div class="service-list__text">
					<h3 class="service-list__item-title has-600-font-size mb-0"><?php echo esc_html( $link_title ); ?></h3>
					<div><?php echo esc_html( $description ); ?></div>
				</div>
			</a>
			<?php } ?>
		</div>
		<div class="has-600-font-size"><?php echo esc_html( $outro ); ?></div>
	</div>
</section>
