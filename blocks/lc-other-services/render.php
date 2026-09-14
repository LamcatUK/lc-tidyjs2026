<?php
/**
 * Block template for LC Other Services.
 *
 * Lists sibling service pages (children of the same parent) excluding the
 * current page.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$post = get_post();
if ( ! $post || ! $post->post_parent ) {
	return;
}

$siblings = get_pages(
	array(
		'parent'      => $post->post_parent,
		'exclude'     => array( $post->ID ),
		'sort_column' => 'menu_order',
		'sort_order'  => 'ASC',
	)
);

if ( empty( $siblings ) ) {
	return;
}

$heading = $attributes['heading'] ?? 'Other Services';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'lc-other-services' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<h2 class="lc-other-services__heading"><?php echo esc_html( $heading ); ?></h2>
		<ul class="lc-other-services__list">
			<?php foreach ( $siblings as $sibling ) { ?>
			<li class="lc-other-services__item">
				<a class="lc-other-services__link" href="<?php echo esc_url( get_permalink( $sibling ) ); ?>">
					<?php echo esc_html( get_the_title( $sibling ) ); ?>
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>
</section>
