<?php
/**
 * Nav walker for the "Footer Social" menu.
 *
 * @package Dlc_Designs
 */

/**
 * Renders each "Footer Social" menu item as an icon-only link
 * (auto-detected from its URL, see inc/social-icons.php), with the menu
 * item's own title used as the accessible name for screen readers.
 */
class DLC_Designs_Social_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the element output.
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes  = empty( $item->classes ) ? array() : (array) $item->classes;
		$label    = ! empty( $item->title ) ? $item->title : $item->url;
		$icon_svg = dlc_designs_get_social_icon_svg( $item->url );
		$target   = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$rel      = ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : ' rel="noopener"';

		$output .= '<li class="' . esc_attr( implode( ' ', array_map( 'sanitize_html_class', $classes ) ) ) . '">';
		$output .= '<a href="' . esc_url( $item->url ) . '"' . $target . $rel . ' aria-label="' . esc_attr( $label ) . '">';
		$output .= $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed internal SVG set, not user input.
		$output .= '<span class="screen-reader-text">' . esc_html( $label ) . '</span>';
		$output .= '</a></li>';
	}

	/**
	 * No-op: this menu is icon links only, one level deep.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}
