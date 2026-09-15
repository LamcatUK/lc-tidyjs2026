<?php
/**
 * Fallback template — also serves as the blog/"Guides" index (the static
 * page set as Settings → Reading → "Posts page"). That page's own content
 * (hero, intro blocks, whatever gets added in the editor) isn't part of
 * the post loop below — it has to be fetched and output separately, first,
 * before the post cards.
 *
 * @package lc-tidyjs2026
 */

get_header();

if ( is_home() && ! is_front_page() ) {
	$posts_page = get_post( (int) get_option( 'page_for_posts' ) );
	if ( $posts_page ) {
		echo apply_filters( 'the_content', $posts_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter output.
	}
}

if ( have_posts() ) {
	?>
	<div class="container pb-5">
		<div class="related-posts__grid">
			<?php
			while ( have_posts() ) {
				the_post();
				lc_tidyjs2026_render_post_card();
			}
			?>
		</div>

		<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
	</div>
	<?php
} else {
	?>
	<div class="container py-5">
		<p><?php esc_html_e( 'Nothing found.', 'lc-tidyjs2026' ); ?></p>
	</div>
	<?php
}

get_footer();
