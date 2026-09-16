<?php
/**
 * Lightweight nav walker. Outputs nav-link/dropdown-menu class names (kept
 * for familiarity) but has none of Bootstrap's navwalker complexity — no
 * linkmod/icon handling, no Bootstrap 4/5 branching. Submenus are shown via
 * dropdown-toggle buttons, which are accessible and work with keyboard navigation.
 *
 * @package lc-tidyjs2026
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LC_Tidy_JS_2026_Nav_Walker' ) ) {

	/**
	 * Custom nav walker.
	 */
	class LC_Tidy_JS_2026_Nav_Walker extends Walker_Nav_Menu {

		/**
		 * Holds the id of the submenu currently being opened, so start_lvl()
		 * can target the same id the preceding start_el() pointed its
		 * dropdown-toggle button's aria-controls at.
		 *
		 * @var string
		 */
		protected $current_submenu_id = '';

		/**
		 * Starts the list before the elements are added.
		 *
		 * @param string   $output Passed by reference.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu args.
		 * @return void
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) {
			$output .= '<ul class="dropdown-menu" id="' . esc_attr( $this->current_submenu_id ) . '">';
		}

		/**
		 * Ends the list after the elements are added.
		 *
		 * @param string   $output Passed by reference.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu args.
		 * @return void
		 */
		public function end_lvl( &$output, $depth = 0, $args = null ) {
			$output .= '</ul>';
		}

		/**
		 * Starts the element output.
		 *
		 * @param string   $output Passed by reference.
		 * @param WP_Post  $item   Menu item.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu args.
		 * @param int      $id     Menu item ID.
		 * @return void
		 */
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
			$has_children = in_array( 'menu-item-has-children', $item->classes, true );
			$is_current   = in_array( 'current-menu-item', $item->classes, true );

			// WP sets all three of these automatically:
			// - current-menu-parent / current-menu-ancestor: a dropdown
			//   parent whose own submenu contains the page being viewed.
			// - current_page_parent: specifically the item pointing at
			//   Settings → Reading's "Posts page" ("Guides" here), added by
			//   core's own wp_page_menu() back-compat rule for any non-Page
			//   request — which covers every single blog post.
			$is_current_parent = in_array( 'current-menu-parent', $item->classes, true )
				|| in_array( 'current-menu-ancestor', $item->classes, true )
				|| in_array( 'current_page_parent', $item->classes, true );

			$li_classes = array( 'nav-item' );
			if ( $has_children ) {
				$li_classes[] = 'dropdown';
			}

			$output .= '<li class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';

			if ( $has_children ) {
				// Dropdown parents never navigate — the whole item is the toggle.
				$toggle_classes = array( 'nav-link', 'dropdown-toggle' );
				if ( $is_current_parent ) {
					$toggle_classes[] = 'active';
				}

				$this->current_submenu_id = 'dropdown-' . $item->ID;
				$output                  .= '<button type="button" class="' . esc_attr( implode( ' ', $toggle_classes ) ) . '" aria-haspopup="true" aria-expanded="false" aria-controls="' . esc_attr( $this->current_submenu_id ) . '">';
				$output                  .= '<span>' . esc_html( $item->title ) . '</span>';
				$output                  .= '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2.5 4.5 6 8l3.5-3.5" /></svg>';
				$output                  .= '</button>';
			} else {
				// $item->classes carries the menu item's own custom CSS
				// classes (the "CSS Classes" field in wp-admin's menu
				// editor, e.g. a "btn" class on a CTA-styled item) —
				// without merging it in here, that field is silently
				// ignored for every non-dropdown link.
				$link_classes = array_merge( array( 'nav-link' ), array_filter( $item->classes ) );
				if ( $is_current || $is_current_parent ) {
					$link_classes[] = 'active';
				}

				$output .= '<a class="' . esc_attr( implode( ' ', $link_classes ) ) . '" href="' . esc_url( $item->url ) . '"';
				if ( $is_current ) {
					$output .= ' aria-current="page"';
				}
				$output .= '>' . esc_html( $item->title ) . '</a>';
			}
		}

		/**
		 * Ends the element output.
		 *
		 * @param string   $output Passed by reference.
		 * @param WP_Post  $item   Menu item.
		 * @param int      $depth  Depth of menu item.
		 * @param stdClass $args   Menu args.
		 * @return void
		 */
		public function end_el( &$output, $item, $depth = 0, $args = null ) {
			$output .= '</li>';
		}
	}
}
