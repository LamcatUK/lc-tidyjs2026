<?php
/**
 * Reusable, project-agnostic utility functions — safe to lift verbatim into
 * other projects built on this skeleton. Project-specific helpers (coupled
 * to a project's own field schema or content structure) belong in
 * inc/helpers.php instead — don't create that file until something actually
 * needs it.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Strip formatting from a UK phone number for use in tel: links.
 *
 * @param string $phone Phone number as entered (spaces, brackets, dashes allowed).
 * @return string
 */
function parse_phone( $phone ) {
	$phone = preg_replace( '/\s+/', '', $phone );
	$phone = preg_replace( '/\(0\)/', '', $phone );
	$phone = preg_replace( '/[\(\)\.]/', '', $phone );
	$phone = preg_replace( '/-/', '', $phone );
	$phone = preg_replace( '/^0/', '+44', $phone );
	return $phone;
}

/**
 * Generate a WhatsApp link shortcode using the site-wide phone number.
 *
 * Pre-fills the WhatsApp message with "I'm contacting you from the [site name] website...".
 *
 * @param array $atts {
 *     Optional. Shortcode attributes.
 *
 *     @type string $class CSS class for the anchor element. Default empty.
 *     @type string $text  Custom link text to display. Default 'WhatsApp Us'.
 *     @type bool   $icon  Whether to show a WhatsApp icon. Default false.
 * }
 * @return string HTML anchor tag with the WhatsApp link, or an empty string if no phone is set.
 */
function whatsapp_link( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'class' => '',
			'text'  => 'WhatsApp Us',
			'icon'  => false,
		),
		$atts,
		'whatsapp_link'
	);

	$phone = lc_tidyjs2026_get_setting( 'phone' );

	if ( ! $phone ) {
		return '';
	}

	$number    = ltrim( parse_phone( $phone ), '+' );
	$site_name = get_bloginfo( 'name' );
	$message   = rawurlencode( "I'm contacting you from the {$site_name} website..." );
	$icon_html = ( 'true' === $atts['icon'] || true === $atts['icon'] ) ? lc_tidyjs2026_get_brand_icon( 'whatsapp', 'me-2' ) . ' ' : '';
	$link_text = $icon_html . wp_kses_post( $atts['text'] );
	$class     = esc_attr( $atts['class'] );

	return '<a href="https://wa.me/' . esc_attr( $number ) . '?text=' . $message . '" class="' . $class . '" target="_blank" rel="noopener noreferrer">' . $link_text . '</a>';
}
add_shortcode( 'whatsapp_link', 'whatsapp_link' );

/**
 * Generate a `tel:` link shortcode from the site-wide phone number.
 *
 * @param array $atts {
 *     Optional. Shortcode attributes.
 *
 *     @type string $class CSS class for the anchor element. Default empty.
 *     @type string $text  Custom link text. Default the phone number itself.
 *     @type bool   $icon  Whether to prefix a phone icon. Default false.
 * }
 * @return string HTML anchor tag, or an empty string if no phone is set.
 */
function contact_phone( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'class' => '',
			'text'  => '',
			'icon'  => false,
		),
		$atts,
		'contact_phone'
	);

	$phone = lc_tidyjs2026_get_setting( 'phone' );

	if ( ! $phone ) {
		return '';
	}

	$icon_html   = ( 'true' === $atts['icon'] || true === $atts['icon'] ) ? '<i class="fa-solid fa-phone"></i> ' : '';
	$anchor_text = $icon_html . ( ! empty( $atts['text'] ) ? wp_kses_post( $atts['text'] ) : esc_html( $phone ) );

	return '<a href="tel:' . esc_attr( parse_phone( $phone ) ) . '" class="' . esc_attr( $atts['class'] ) . '">' . $anchor_text . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $anchor_text is wp_kses_post()/esc_html() output above.
}
add_shortcode( 'contact_phone', 'contact_phone' );

/**
 * Generate a `mailto:` link shortcode from the site-wide email address,
 * obfuscated against harvesting via antispambot().
 *
 * @param array $atts {
 *     Optional. Shortcode attributes.
 *
 *     @type string $class CSS class for the anchor element. Default empty.
 *     @type string $text  Custom link text. Default the email address itself.
 *     @type bool   $icon  Whether to prefix an envelope icon. Default false.
 * }
 * @return string HTML anchor tag, or an empty string if no email is set.
 */
function contact_email( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'class' => '',
			'text'  => '',
			'icon'  => false,
		),
		$atts,
		'contact_email'
	);

	$email = lc_tidyjs2026_get_setting( 'email' );

	if ( ! $email ) {
		return '';
	}

	$obfuscated_email = antispambot( $email );
	$icon_html        = ( 'true' === $atts['icon'] || true === $atts['icon'] ) ? '<i class="fa-solid fa-envelope"></i> ' : '';
	$anchor_text      = $icon_html . ( ! empty( $atts['text'] ) ? wp_kses_post( $atts['text'] ) : esc_html( $obfuscated_email ) );

	return '<a href="mailto:' . esc_attr( $obfuscated_email ) . '" class="' . esc_attr( $atts['class'] ) . '">' . $anchor_text . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $anchor_text is wp_kses_post()/esc_html() output above.
}
add_shortcode( 'contact_email', 'contact_email' );

/**
 * Pluralise a word based on quantity.
 *
 * @param int         $quantity Quantity to check.
 * @param string      $singular Singular form.
 * @param string|null $plural   Explicit plural form, if the default suffix rules don't apply.
 * @return string
 */
function pluralise( $quantity, $singular, $plural = null ) {
	if ( 1 === $quantity || ! strlen( $singular ) ) {
		return $singular;
	}
	if ( null !== $plural ) {
		return $plural;
	}

	$last_letter = strtolower( $singular[ strlen( $singular ) - 1 ] );
	switch ( $last_letter ) {
		case 'y':
			return substr( $singular, 0, -1 ) . 'ies';
		case 's':
			return $singular . 'es';
		default:
			return $singular . 's';
	}
}

/**
 * List available icons from img/icons/ as slug => label pairs.
 *
 * Drop an .svg file into img/icons/ and it appears automatically — no
 * registration step. Useful as the options list for a generated block's
 * "select" field (see add_block.sh) when a block needs an icon picker.
 *
 * @return array Slug => human-readable label pairs.
 */
function get_icon_choices() {
	$choices = array();
	$files   = glob( get_template_directory() . '/img/icons/*.svg' );

	if ( ! $files ) {
		return $choices;
	}

	foreach ( $files as $file ) {
		$slug             = basename( $file, '.svg' );
		$choices[ $slug ] = ucwords( str_replace( array( '-', '_' ), ' ', $slug ) );
	}

	return $choices;
}

/**
 * Inline an SVG icon from img/icons/ by slug.
 *
 * @param string $name Icon slug — matches a filename in img/icons/ without the extension.
 * @return string SVG markup, or an empty string if the icon doesn't exist.
 */
function get_icon( $name ) {
	if ( ! $name ) {
		return '';
	}

	$path = get_template_directory() . '/img/icons/' . basename( $name ) . '.svg';

	if ( ! file_exists( $path ) ) {
		return '';
	}

	return file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
}

/**
 * Inline a vendored brand-logo SVG from img/icons/brands/ by slug — the same
 * shapes as Font Awesome's fa-brands glyphs (extracted from the exact
 * fa-brands-400 6.7.2 version already vendored in js/webfonts/, CC BY 4.0 —
 * see js/webfonts/LICENSE.txt), so [social_icons] and whatsapp_link() don't
 * need to load the ~116KB fa-brands-400.woff2 font file for the handful of
 * brand glyphs they actually use. Separate from get_icon() (a different
 * folder, brands/, so these don't show up in get_icon_choices()' editor
 * picker) but otherwise the same pattern.
 *
 * @param string $name        Icon slug — matches a filename in img/icons/brands/ without the extension.
 * @param string $extra_class Additional class(es) to add alongside the icon-brand class, e.g. a spacing utility.
 * @return string SVG markup, or an empty string if the icon doesn't exist.
 */
function lc_tidyjs2026_get_brand_icon( $name, $extra_class = '' ) {
	if ( ! $name ) {
		return '';
	}

	$path = get_template_directory() . '/img/icons/brands/' . basename( $name ) . '.svg';

	if ( ! file_exists( $path ) ) {
		return '';
	}

	$svg = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( $extra_class ) {
		$svg = str_replace( 'class="icon-brand"', 'class="icon-brand ' . esc_attr( $extra_class ) . '"', $svg );
	}

	return $svg;
}

/**
 * Queue Q&A pairs for the aggregated FAQPage JSON-LD schema, output once in
 * the footer by output_faq_schema(). Safe to call from multiple
 * FAQ-style blocks on the same page — everything queued is combined into a
 * single FAQPage block rather than one per block instance, matching
 * Google's own guidance of one FAQPage schema per page.
 *
 * @param array $items Array of ['question' => string, 'answer' => string] pairs.
 * @return void
 */
function queue_faq_schema( array $items ) {
	global $faq_schema_items;

	if ( ! isset( $faq_schema_items ) ) {
		$faq_schema_items = array();
	}

	foreach ( $items as $item ) {
		if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
			continue;
		}

		$faq_schema_items[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $item['question'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['answer'] ),
			),
		);
	}
}

/**
 * Output the aggregated FAQPage JSON-LD schema, if anything was queued.
 *
 * @return void
 */
function output_faq_schema() {
	global $faq_schema_items;

	if ( empty( $faq_schema_items ) ) {
		return;
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faq_schema_items,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_footer', 'output_faq_schema' );

/**
 * Estimate reading time for a piece of content.
 *
 * @param string $content          Content to estimate.
 * @param int    $words_per_minute Reading speed assumption.
 * @param bool   $with_gutenberg   Parse content as Gutenberg blocks before stripping tags.
 * @param bool   $formatted        Return a formatted sentence instead of a bare number.
 * @return int|string
 */
function estimate_reading_time_in_minutes( $content = '', $words_per_minute = 300, $with_gutenberg = false, $formatted = false ) {
	if ( $with_gutenberg ) {
		$blocks       = parse_blocks( $content );
		$content_html = '';

		foreach ( $blocks as $block ) {
			$content_html .= render_block( $block );
		}

		$content = $content_html;
	}

	$content = wp_strip_all_tags( $content );

	if ( ! $content ) {
		return 0;
	}

	$words_count = str_word_count( $content );
	$minutes     = ceil( $words_count / $words_per_minute );

	if ( $formatted ) {
		$minutes = '<p class="reading">Estimated reading time ' . $minutes . ' ' . pluralise( $minutes, 'minute' ) . '</p>';
	}

	return $minutes;
}

/**
 * Build breadcrumb items for the current singular view.
 *
 * @param int $post_id Current post ID.
 * @return array<int, array{label: string, url: string}>
 */
function lc_tidyjs2026_get_breadcrumbs( $post_id = 0 ) {
	$post_id     = $post_id ? (int) $post_id : get_the_ID();
	$breadcrumbs = array(
		array(
			'label' => __( 'Home', 'lc-tidyjs2026' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( ! $post_id ) {
		return $breadcrumbs;
	}

	if ( 'post' === get_post_type( $post_id ) ) {
		$blog_page_id = (int) get_option( 'page_for_posts' );

		if ( ! $blog_page_id ) {
			$blog_page    = get_page_by_path( 'blog' );
			$blog_page_id = $blog_page ? (int) $blog_page->ID : 0;
		}

		if ( $blog_page_id ) {
			$breadcrumbs[] = array(
				'label' => get_the_title( $blog_page_id ),
				'url'   => get_permalink( $blog_page_id ),
			);
		}
	} elseif ( is_page( $post_id ) || 'page' === get_post_type( $post_id ) ) {
		$ancestor_ids = array_reverse( get_post_ancestors( $post_id ) );

		foreach ( $ancestor_ids as $ancestor_id ) {
			$breadcrumbs[] = array(
				'label' => get_the_title( $ancestor_id ),
				'url'   => get_permalink( $ancestor_id ),
			);
		}
	}

	$breadcrumbs[] = array(
		'label' => get_the_title( $post_id ),
		'url'   => '',
	);

	return $breadcrumbs;
}

/**
 * Render breadcrumb markup with schema metadata.
 *
 * @param array  $breadcrumbs Breadcrumb items.
 * @param string $class_name  Wrapper class name.
 * @param string $extra_attrs Extra raw attributes for the <nav> tag (e.g. an
 *                             `id` from a block's anchor support) — caller's
 *                             responsibility to escape.
 * @return void
 */
function lc_tidyjs2026_render_breadcrumbs( $breadcrumbs, $class_name = 'lc-breadcrumbs', $extra_attrs = '' ) {
	if ( empty( $breadcrumbs ) || ! is_array( $breadcrumbs ) || is_front_page() ) {
		return;
	}
	?>
	<nav class="<?php echo esc_attr( $class_name ); ?>" aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList" <?php echo $extra_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- caller's responsibility, documented above. ?>>
		<div class="container">
			<ol class="lc-breadcrumbs__list">
				<?php foreach ( $breadcrumbs as $index => $breadcrumb ) { ?>
					<li class="lc-breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						<?php if ( ! empty( $breadcrumb['url'] ) ) { ?>
							<a href="<?php echo esc_url( $breadcrumb['url'] ); ?>" itemprop="item"><span itemprop="name"><?php echo esc_html( $breadcrumb['label'] ); ?></span></a>
						<?php } else { ?>
							<span itemprop="name" aria-current="page"><?php echo esc_html( $breadcrumb['label'] ); ?></span>
						<?php } ?>
						<meta itemprop="position" content="<?php echo esc_attr( $index + 1 ); ?>">
					</li>
				<?php } ?>
			</ol>
		</div>
	</nav>
	<?php
}

/**
 * Extract an in-page table of contents from rendered HTML, injecting an
 * `id` onto each matched heading so the returned items' anchors actually
 * resolve. Ids are slugified from the heading text and de-duplicated
 * (second "Overview" becomes "overview-2", etc.) — headings are free text,
 * not guaranteed unique.
 *
 * DOMDocument over a regex: content is real (if messy) HTML by this point
 * (post the_content filter — blocks, shortcodes, wpautop already applied),
 * and a regex heading-matcher breaks the moment a heading contains inline
 * markup (a `<strong>`, an `<a>`, an emoji span) rather than plain text.
 *
 * @param string $html     Rendered HTML (e.g. apply_filters( 'the_content', $post->post_content )).
 * @param string $selector Heading tag to extract, e.g. 'h2'.
 * @return array{content: string, items: array<int, array{id: string, text: string}>}
 */
function lc_tidyjs2026_extract_toc( $html, $selector = 'h2' ) {
	if ( '' === trim( $html ) ) {
		return array(
			'content' => $html,
			'items'   => array(),
		);
	}

	$dom = new DOMDocument();
	// The <body> wrapper this implies is what the reconstruction step
	// below reads back out of — LIBXML_HTML_NOIMPLIED would suppress it
	// entirely, leaving nothing to read. The leading XML-encoding processing
	// instruction prepended below is the standard workaround for DOMDocument
	// otherwise mangling multi-byte (e.g. emoji, curly quotes) UTF-8 content.
	// NOTE: that literal instruction can't be written out here in a comment —
	// its own closing marker would end this PHP block right here, the same
	// way a brace pair inside a comment has already broken two other parsers
	// (a naive one and cleancss's) earlier in this project. Comments in this
	// file must never contain that literal two-character closing sequence.
	libxml_use_internal_errors( true );
	$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	$headings   = $dom->getElementsByTagName( $selector );
	$items      = array();
	$used_slugs = array();

	// Collect first (getElementsByTagName is a live NodeList — mutating
	// element attributes mid-iteration is fine, but this keeps that
	// separate from the counting/slugging logic below for clarity).
	$heading_nodes = array();
	foreach ( $headings as $heading ) {
		$heading_nodes[] = $heading;
	}

	foreach ( $heading_nodes as $heading ) {
		$text = trim( $heading->textContent );
		if ( '' === $text ) {
			continue;
		}

		// Skip headings that belong to an embedded block (e.g. the LC CTA's
		// own <h2>) rather than the article's own prose — those blocks
		// render as a <section>, unlike the flat markup core content blocks
		// (paragraph, heading, image, list...) produce.
		$in_section = false;
		for ( $ancestor = $heading->parentNode; $ancestor; $ancestor = $ancestor->parentNode ) {
			if ( 'section' === $ancestor->nodeName ) {
				$in_section = true;
				break;
			}
		}
		if ( $in_section ) {
			continue;
		}

		$slug = sanitize_title( $text );
		if ( '' === $slug ) {
			$slug = 'section';
		}

		if ( isset( $used_slugs[ $slug ] ) ) {
			++$used_slugs[ $slug ];
			$id = $slug . '-' . $used_slugs[ $slug ];
		} else {
			$used_slugs[ $slug ] = 1;
			$id                  = $slug;
		}

		$heading->setAttribute( 'id', $id );

		$items[] = array(
			'id'   => $id,
			'text' => $text,
		);
	}

	if ( empty( $items ) ) {
		return array(
			'content' => $html,
			'items'   => array(),
		);
	}

	$body        = $dom->getElementsByTagName( 'body' )->item( 0 );
	$new_content = '';
	foreach ( $body->childNodes as $child ) {
		$new_content .= $dom->saveHTML( $child );
	}

	return array(
		'content' => $new_content,
		'items'   => $items,
	);
}

/**
 * Estimate reading time from rendered post HTML, at a standard 200 words
 * per minute. Always rounds up (and floors at 1) — "0 min read" reads as
 * broken, "1 min read" doesn't, even for a very short post.
 *
 * @param string $html Rendered HTML (e.g. apply_filters( 'the_content', $post->post_content )).
 * @return int Whole minutes, minimum 1.
 */
function lc_tidyjs2026_reading_time( $html ) {
	$word_count = str_word_count( wp_strip_all_tags( $html ) );
	return max( 1, (int) ceil( $word_count / 200 ) );
}

/**
 * Render one post card (image, title, date/reading-time meta, excerpt) —
 * shared by index.php's card grid and single.php's "More from the blog",
 * so the two don't drift out of sync with each other.
 *
 * Expects the loop to already be on this post (called between the_post()
 * and the next iteration), same as template tags like the_title().
 *
 * The reading-time figure needs the_content filtered (so dynamic blocks
 * expand to their real word count), but that filter is what runs every
 * block's render_callback — including lc-faq/lc-blog-faq, which queue
 * their Q&A pairs onto the page's aggregated FAQPage schema as a side
 * effect. Post cards render OTHER posts (latest guides, related posts),
 * so without guarding this, browsing to any page carrying a post-card
 * block queues every FAQ from those other posts' bodies too. Snapshot
 * and restore the queue around the throwaway render so only FAQ blocks
 * actually placed on the current page contribute to its schema.
 *
 * @return void
 */
function lc_tidyjs2026_render_post_card() {
	global $faq_schema_items;
	$faq_schema_items_snapshot = $faq_schema_items ?? array();
	$minutes                   = lc_tidyjs2026_reading_time( apply_filters( 'the_content', get_the_content() ) );
	$faq_schema_items          = $faq_schema_items_snapshot;
	?>
	<a class="related-post-card" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) { ?>
		<?php
		// the_post_thumbnail() (not the_post_thumbnail_url()) so the <img>
		// gets explicit width/height/srcset from WordPress — without those,
		// the browser can't reserve the card's image space before the CSS
		// (which is what actually sizes it) has loaded, so the layout jumps
		// once it does. 'large' rather than 'medium' (300w) — at the 3-up
		// desktop card width this card can render past 300px, and 'medium'
		// upscaled past its own intrinsic size looks visibly soft.
		the_post_thumbnail( 'large', array( 'class' => 'related-post-card__image' ) );
		?>
		<?php } ?>
		<span class="related-post-card__title"><?php the_title(); ?></span>
		<span class="related-post-card__meta">
			<span class="related-post-card__meta-item"><i class="fa-solid fa-calendar" aria-hidden="true"></i> <?php echo esc_html( get_the_date() ); ?></span>
			<span class="related-post-card__meta-item"><i class="fa-solid fa-clock" aria-hidden="true"></i> <?php echo esc_html( $minutes ); ?> <?php esc_html_e( 'min read', 'lc-tidyjs2026' ); ?></span>
		</span>
		<span class="related-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></span>
	</a>
	<?php
}
