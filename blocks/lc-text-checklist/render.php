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

$split_checklist_items = static function ( $html ) {
	$html = preg_replace( '/\r\n|\r|\n/', '<br>', $html );

	$dom            = new DOMDocument();
	$previous_state = libxml_use_internal_errors( true );
	$loaded         = $dom->loadHTML(
		'<?xml encoding="utf-8" ?><div id="lc-text-checklist-content">' . $html . '</div>',
		LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();
	libxml_use_internal_errors( $previous_state );

	$root = $loaded ? $dom->getElementById( 'lc-text-checklist-content' ) : null;
	if ( ! $root ) {
		return preg_split( '/<br\s*\/?>/i', $html, -1, PREG_SPLIT_NO_EMPTY );
	}

	foreach ( $root->getElementsByTagName( 'a' ) as $link ) {
		$href = $link->getAttribute( 'href' );
		if ( preg_match( '~^\[(https?://[^\]\s]+)\]\(\1\)$~', $href, $matches ) ) {
			$link->setAttribute( 'href', $matches[1] );
		}
	}

	$split_node = static function ( $node, $wrap = true ) use ( &$split_node, $dom ) {
		if ( XML_TEXT_NODE === $node->nodeType ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.
			return array( $dom->saveHTML( $node ) );
		}

		if ( XML_ELEMENT_NODE !== $node->nodeType ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.
			return array( '' );
		}

		if ( 'br' === strtolower( $node->nodeName ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.
			return array( '', '' );
		}

		$lines = array( '' );
		foreach ( $node->childNodes as $child ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.
			$parts                              = $split_node( $child );
			$lines[ array_key_last( $lines ) ] .= array_shift( $parts );
			foreach ( $parts as $part ) {
				$lines[] = $part;
			}
		}

		if ( ! $wrap ) {
			return $lines;
		}

		$opening_tag = '<' . $node->nodeName; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.
		foreach ( $node->attributes as $attribute ) {
			$opening_tag .= sprintf(
				' %s="%s"',
				$attribute->nodeName, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.
				esc_attr( $attribute->nodeValue ) // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.
			);
		}
		$opening_tag .= '>';
		$closing_tag  = '</' . $node->nodeName . '>'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMNode exposes this property as nodeType.

		return array_map(
			static function ( $line ) use ( $opening_tag, $closing_tag ) {
				return '' === $line ? '' : $opening_tag . $line . $closing_tag;
			},
			$lines
		);
	};

	return array_values(
		array_filter(
			array_map( 'trim', $split_node( $root, false ) ),
			static function ( $item ) {
				return '' !== trim( wp_strip_all_tags( $item ) );
			}
		)
	);
};
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() already escapes; also carries the block's anchor id and any chosen background/text colour classes (color support in block.json). ?>>
	<div class="container">
		<div class="row gap-5">
			<div class="col-12 col-lg-4 col-xl-6">
				<h2><?php echo esc_html( $btitle ); ?></h2>
				<div class="has-600-font-size"><?php echo wpautop( esc_html( $intro ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- The plain-text attribute is escaped before paragraph markup is added. ?></div>
			</div>
			<div class="col-12 col-lg-8 col-xl-6 my-auto">
				<div class="text-checklist__list-wrapper w-100">
					<ul class="text-checklist__list mb-0 has-500-font-size cols-md-2">
						<?php
						if ( $checklist ) {
							$items = $split_checklist_items( $checklist );
							foreach ( $items as $item ) {
								?>
						<li>
							<span class="text-checklist__icon">
								<i class="fa-solid fa-circle"></i>
								<i class="fa-solid fa-check"></i>
							</span>
							<span><?php echo wp_kses_post( $item ); ?></span>
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
