<?php
/**
 * Single post template — containerised featured image, then a 9/3 content
 * + in-page-TOC sidebar layout on desktop. The sidebar tracks which H2
 * section is currently in view (src/js/toc.js) via IntersectionObserver,
 * marking the corresponding link active — same technique src/js/reveal.js
 * already uses elsewhere in this theme, no new dependency.
 *
 * @package lc-tidyjs2026
 */

get_header();

while ( have_posts() ) {
	the_post();

	$toc = lc_tidyjs2026_extract_toc( apply_filters( 'the_content', get_the_content() ), 'h2' );
	?>

	<?php if ( has_post_thumbnail() ) { ?>
	<div class="container pt-3">
		<?php the_post_thumbnail( 'full', array( 'class' => 'single-featured-image' ) ); ?>
	</div>
	<?php } ?>

	<?php lc_tidyjs2026_render_breadcrumbs( lc_tidyjs2026_get_breadcrumbs(), 'lc-breadcrumbs single-breadcrumbs' ); ?>

	<div class="container pb-5">
		<div class="row gap-5">
			<div class="col-12 col-lg-9">
				<article <?php post_class(); ?>>
					<h1><?php the_title(); ?></h1>
					<ul class="single-meta">
						<li class="single-meta__item"><i class="fa-solid fa-calendar" aria-hidden="true"></i> <time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></li>
						<?php if ( get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' ) ) { ?>
						<li class="single-meta__item"><i class="fa-solid fa-rotate" aria-hidden="true"></i> <?php esc_html_e( 'Updated', 'lc-tidyjs2026' ); ?> <time datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time></li>
						<?php } ?>
						<li class="single-meta__item"><i class="fa-solid fa-user" aria-hidden="true"></i> <?php esc_html_e( 'Tidy Solutions', 'lc-tidyjs2026' ); ?></li>
						<li class="single-meta__item"><i class="fa-solid fa-clock" aria-hidden="true"></i> <?php echo esc_html( lc_tidyjs2026_reading_time( $toc['content'] ) ); ?> <?php esc_html_e( 'min read', 'lc-tidyjs2026' ); ?></li>
					</ul>
					<?php echo $toc['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter output, only mutated by lc_tidyjs2026_extract_toc() to add heading ids. ?>
				</article>

			</div>
			<?php if ( ! empty( $toc['items'] ) ) { ?>
			<div class="col-12 col-lg-3 d-none d-lg-block">
				<nav class="toc" aria-label="<?php esc_attr_e( 'Quick links', 'lc-tidyjs2026' ); ?>">
					<p class="toc__label"><?php esc_html_e( 'Quick links', 'lc-tidyjs2026' ); ?></p>
					<ul class="toc__list">
						<?php foreach ( $toc['items'] as $item ) { ?>
						<li>
							<a class="toc__link" href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
						</li>
						<?php } ?>
					</ul>
				</nav>
			</div>
			<?php } ?>
		</div>
	</div>

	<?php
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	if ( $prev_post || $next_post ) {
		?>
		<div class="container">
			<div class="single-nav">
				<?php if ( $prev_post ) { ?>
				<a class="single-nav__link single-nav__link--prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
					<span class="single-nav__label"><?php esc_html_e( '← Previous', 'lc-tidyjs2026' ); ?></span>
					<span class="single-nav__title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
				</a>
				<?php } else { ?>
				<span></span>
				<?php } ?>
				<?php if ( $next_post ) { ?>
				<a class="single-nav__link single-nav__link--next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
					<span class="single-nav__label"><?php esc_html_e( 'Next →', 'lc-tidyjs2026' ); ?></span>
					<span class="single-nav__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
				</a>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	$recent_posts = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( $recent_posts->have_posts() ) {
		?>
		<div class="container py-5">
			<h2 class="related-posts__heading"><?php esc_html_e( 'More Tidy Guides', 'lc-tidyjs2026' ); ?></h2>
			<div class="related-posts__grid">
				<?php
				while ( $recent_posts->have_posts() ) {
					$recent_posts->the_post();
					lc_tidyjs2026_render_post_card();
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
	}
	?>
	<?php
}

get_footer();
