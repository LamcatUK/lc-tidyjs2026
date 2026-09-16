<?php
/**
 * Block template for LC Text Checklist.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$btitle    = $attributes['title'] ?? '';
$intro     = $attributes['intro'] ?? '';
$checklist = $attributes['checklist'] ?? '';
$outro     = $attributes['outro'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'text-checklist' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes; also carries the block's anchor id and any chosen background/text colour classes (color support in block.json). ?>>
	<div class="container">
		<div class="row gap-5">
			<div class="col-12 col-lg-4 col-xl-6">
				<h2><?php echo esc_html( $btitle ); ?></h2>
				<p class="has-600-font-size"><?php echo esc_html( $intro ); ?></p>
			</div>
			<div class="col-12 col-lg-8 col-xl-6 my-auto">
				<div class="text-checklist__list-wrapper w-100">
					<ul class="text-checklist__list mb-0 has-500-font-size cols-md-2">
						<?php
						if ( $checklist ) {
							$items = preg_split( '/\r\n|\r|\n/', $checklist, -1, PREG_SPLIT_NO_EMPTY );
							foreach ( $items as $item ) {
								$item = trim( $item );
								if ( empty( $item ) ) {
									continue;
								}
								?>
						<li>
							<span class="text-checklist__icon">
								<i class="fa-solid fa-circle"></i>
								<i class="fa-solid fa-check"></i>
							</span>
							<span><?php echo esc_html( $item ); ?></span>
						</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<?php if ( $outro ) { ?>
		<div class="text-center pt-4">
			<?php echo wp_kses_post( $outro ); ?>
		</div>
		<?php } ?>
	</div>
</section>
