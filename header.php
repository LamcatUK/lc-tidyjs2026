<?php
/**
 * Header template.
 *
 * @package lc-tidyjs2026
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	/**
	 * LocalBusiness / ContactPage JSON-LD, ported verbatim from lc-tidy2026's
	 * header.php during the ACF-to-native-block migration — project-specific,
	 * not part of the lc-tidyjs2026 skeleton pattern. Phone/email read from
	 * Site-Wide Settings to stay in sync with the rest of the site; the
	 * remaining business data has no settings-page equivalent yet and is
	 * still hardcoded, same as it was in lc-tidy2026.
	 */
	if ( is_front_page() ) {
		$area_terms = get_terms(
			array(
				'taxonomy'   => 'area',
				'parent'     => 0,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);
		$area_terms = is_wp_error( $area_terms ) ? array() : $area_terms;

		$schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'LocalBusiness',
			'@id'             => home_url( '/#organization' ),
			'name'            => 'Tidy Solutions',
			'url'             => home_url( '/' ),
			'telephone'       => lc_tidyjs2026_get_setting( 'phone' ),
			'email'           => lc_tidyjs2026_get_setting( 'email' ),
			'priceRange'      => '££',
			'address'         => array(
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Isle of Man',
				'addressCountry'  => 'IM',
			),
			'openingHours'    => 'Mo-Fr 08:00-17:00',
			'areaServed'      => array_merge(
				array(
					array(
						'@type' => 'AdministrativeArea',
						'name'  => 'Isle of Man',
					),
				),
				array_map(
					function ( $term ) {
						return array(
							'@type' => 'City',
							'name'  => $term->name,
						);
					},
					$area_terms
				)
			),
			'aggregateRating' => array(
				'@type'       => 'AggregateRating',
				'ratingValue' => '5',
				'reviewCount' => '3',
			),
			'sameAs'          => array_values(
				array_filter(
					array(
						lc_tidyjs2026_get_setting( 'facebook_url' ),
						lc_tidyjs2026_get_setting( 'instagram_url' ),
						lc_tidyjs2026_get_setting( 'twitter_url' ),
						lc_tidyjs2026_get_setting( 'pinterest_url' ),
						lc_tidyjs2026_get_setting( 'youtube_url' ),
						lc_tidyjs2026_get_setting( 'linkedin_url' ),
					)
				)
			),
			'makesOffer'      => array_map(
				function ( $service_name ) {
					return array(
						'@type'       => 'Offer',
						'itemOffered' => array(
							'@type' => 'Service',
							'name'  => $service_name,
						),
					);
				},
				array(
					'Junk removal',
					'House clearance',
					'Garden waste and outdoor clearance',
					'Garage and shed clearance',
					'Furniture and appliance removal',
					'Builder’s waste removal',
					'Office and commercial clearance',
					'Light demolition and strip-outs',
				)
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode() output.
	}

	if ( is_page( 'contact' ) ) {
		$contact_schema = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'ContactPage',
			'@id'         => home_url( '/contact/#contactpage' ),
			'url'         => home_url( '/contact/' ),
			'name'        => 'Contact Tidy Solutions',
			'description' => 'Contact Tidy Solutions for junk removal and waste clearance across the Isle of Man.',
			'mainEntity'  => array(
				'@type'     => 'LocalBusiness',
				'name'      => 'Tidy Solutions',
				'url'       => home_url( '/' ),
				'telephone' => lc_tidyjs2026_get_setting( 'phone' ),
				'email'     => lc_tidyjs2026_get_setting( 'email' ),
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $contact_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode() output.
	}
	?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<a class="visually-hidden" href="#main">Skip to content</a>
<?php wp_body_open(); ?>

<!-- HEADER-NAV:START -->
<header id="masthead">
	<nav class="navbar container" aria-label="Primary navigation">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand logo" aria-label="<?php bloginfo( 'name' ); ?> Homepage"></a>

		<button class="navbar-toggler" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="Toggle navigation">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M2 5h16M2 10h16M2 15h16" />
			</svg>
		</button>

		<div class="navbar-collapse" id="primary-menu">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'navbar-nav',
					'container'      => false,
					'fallback_cb'    => false,
					'walker'         => new LC_Tidy_JS_2026_Nav_Walker(),
				)
			);
			?>
		</div>
	</nav>
</header>
<!-- HEADER-NAV:END -->

<main id="main">
