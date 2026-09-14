<?php
/**
 * Block template for LC Who Grid.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$title = $attributes['title'] ?? '';
$intro = $attributes['intro'] ?? '';
$items = $attributes['items'] ?? array();
$outro = $attributes['outro'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'who-grid' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container py-5">
		<h2><?php echo esc_html( $title ); ?></h2>
		<p class="has-600-font-size mb-4"><?php echo esc_html( $intro ); ?></p>
		<div class="who-grid__grid mb-4">
			<?php foreach ( $items as $item ) { ?>
				<?php
				$icon_url    = $item['iconUrl'] ?? '';
				$item_title  = $item['title'] ?? '';
				$description = $item['description'] ?? '';
				$link_url    = $item['link'] ?? '';
				$tag         = $link_url ? 'a' : 'div';
				?>
			<<?php echo esc_attr( $tag ); ?> class="who-grid__item" <?php echo $link_url ? 'href="' . esc_url( $link_url ) . '"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_url() above. ?>>
				<?php if ( $icon_url ) { ?>
				<div class="who-grid__icon-wrapper">
					<img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $item_title ); ?>" class="who-grid__icon" loading="lazy">
				</div>
				<?php } ?>
				<h3 class="who-grid__item-title has-600-font-size mb-0"><?php echo esc_html( $item_title ); ?></h3>
				<div class="who-grid__item-description"><?php echo esc_html( $description ); ?></div>
			</<?php echo esc_attr( $tag ); ?>>
			<?php } ?>
		</div>
		<p class="has-600-font-size"><?php echo esc_html( $outro ); ?></p>
	</div>
</section>
