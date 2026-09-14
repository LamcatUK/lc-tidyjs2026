<?php
/**
 * Block template for LC Service Cards.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title    = $attributes['title'] ?? '';
$intro    = $attributes['intro'] ?? '';
$services = $attributes['services'] ?? array();

// Old block always rendered id="services" (a hardcoded anchor tag, not tied
// to the editor's anchor field, which existed but the template never read)
// — nav/CTA links elsewhere point at #services, so default to that when the
// editor hasn't set a custom anchor, rather than dropping the id entirely.
$anchor_id           = ! empty( $attributes['anchor'] ) ? $attributes['anchor'] : 'services';
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'service-cards py-5 has-dark-800-background-color',
		'id'    => $anchor_id,
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<?php if ( $title ) { ?>
		<h2 class="has-white-color"><?php echo esc_html( $title ); ?></h2>
		<?php } ?>
		<?php if ( $intro ) { ?>
		<div class="service-cards__intro mb-4">
			<p class="has-400-font-size has-light-800-color mb-0"><?= wp_kses_post( $intro ); ?></p>
		</div>
		<?php } ?>
		<div class="service-cards__grid">
			<?php foreach ( $services as $service ) { ?>
				<?php
				$icon_url  = $service['iconUrl'] ?? '';
				$svc_title = $service['title'] ?? '';
				$svc_text  = $service['text'] ?? '';
				$link_url  = $service['link'] ?? '';
				$has_link  = ! empty( $link_url );
				?>
			<div class="service-card__wrapper" data-reveal="fade">
				<?php if ( $has_link ) { ?>
				<a href="<?php echo esc_url( $link_url ); ?>" class="service-card">
				<?php } else { ?>
				<div class="service-card">
				<?php } ?>
					<?php if ( $icon_url ) { ?>
					<div class="service-card__icon-wrapper">
						<img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $svc_title ); ?>" class="service-card__icon" loading="lazy">
					</div>
					<?php } ?>
					<h3 class="service-card__title has-600-font-size has-white-color"><?php echo esc_html( $svc_title ); ?></h3>
					<p class="service-card__text has-400-font-size has-light-800-color mb-0"><?php echo esc_html( $svc_text ); ?></p>
				<?php if ( $has_link ) { ?>
				</a>
			</div>
				<?php } else { ?>
				</div>
			</div>
				<?php } ?>
			<?php } ?>
		</div>
		<div class="text-center mt-4">
			<a href="/how-it-works/" class="btn">See how it works</a>
		</div>
	</div>
</section>
