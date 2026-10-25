<?php
/**
 * Block template for LC Related Services.
 *
 * Lists the service pages (children of /services/) picked in the editor. Renders
 * nothing when none are picked.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

$selected = array_map( 'intval', (array) ( $attributes['pages'] ?? array() ) );

if ( empty( $selected ) ) {
	return;
}

// Only published children of the top-level /services/ page, in menu order,
// and never the page the block is on.
$services_page = get_page_by_path( 'services' );
$current_id    = (int) get_the_ID();

if ( ! $services_page ) {
	return;
}

$services = get_pages(
	array(
		'include'     => $selected,
		'sort_column' => 'menu_order',
		'sort_order'  => 'ASC',
	)
);

// get_pages() ignores 'parent' and 'exclude' when 'include' is set, so check both here.
$services = array_filter(
	$services,
	function ( $service ) use ( $services_page, $current_id ) {
		return (int) $service->post_parent === $services_page->ID && $service->ID !== $current_id;
	}
);

if ( empty( $services ) ) {
	return;
}

$heading = $attributes['heading'] ?: 'Related Services';

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'lc-related-services' ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes. ?>>
	<div class="container">
		<h2 class="lc-related-services__heading"><?php echo esc_html( $heading ); ?></h2>
		<ul class="lc-related-services__list">
			<?php foreach ( $services as $service ) { ?>
			<li class="lc-related-services__item">
				<a class="lc-related-services__link" href="<?php echo esc_url( get_permalink( $service ) ); ?>">
					<?php echo esc_html( get_the_title( $service ) ); ?>
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>
</section>
