<?php
/**
 * Block template for LC Social Proof.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$content = ! empty( $attributes['content'] )
	? $attributes['content']
	: '<strong>5-star rated on Google</strong> · Trusted by homeowners, landlords and businesses across the Isle of Man';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'lc-social-proof' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes; also carries the block's anchor id and any chosen background/text colour classes (color support in block.json). ?>>
	<div class="container text-center lc-social-proof__inner">
		<div class="lc-social-proof__stars">
			<span class="fa fa-star"></span>
			<span class="fa fa-star"></span>
			<span class="fa fa-star"></span>
			<span class="fa fa-star"></span>
			<span class="fa fa-star"></span>
		</div>
		<div class="lc-social-proof__content">
			<?php echo wp_kses_post( $content ); ?>
		</div>
	</div>
</section>
