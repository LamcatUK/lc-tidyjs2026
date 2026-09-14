<?php
/**
 * Block template for LC Areas.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$area  = $attributes['title'] ?? '';
$intro = $attributes['intro'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'areas' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes; also carries the block's anchor id and any chosen background/text colour classes (color support in block.json). ?>>
	<div class="container py-5">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/map.png' ); ?>" class="areas__map" alt="" loading="lazy">
		<div class="row">
			<div class="col-12 col-md-7">
				<h2><?php echo esc_html( $area ); ?></h2>
				<div class="has-600-font-size mb-4"><?php echo nl2br( esc_html( $intro ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nl2br() output of an already-escaped string. ?></div>
				<?php lc_tidyjs2026_render_areas_we_cover(); ?>
				<div class="mt-4">
					<a href="/areas/" class="btn btn--outline">See the areas we cover</a>
				</div>
			</div>
		</div>
	</div>
</section>
