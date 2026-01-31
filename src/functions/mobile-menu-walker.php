<?php
/**
 * Mobile Menu Walker for WordPress
 * Uses inline expandable submenus that push content down
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mobile Navigation Walker
 * Creates a mobile-friendly menu with inline expandable submenus
 */
class TSM_Mobile_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Track parent items that have active children
	 */
	private $parent_items_with_active_children = array();

	/**
	 * Track the current parent ID being processed
	 */
	private $current_parent_id = 0;

	/**
	 * Start the list before the elements are added.
	 */
	function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		
		// Check if the current parent has active children
		$should_expand = isset( $this->parent_items_with_active_children[ $this->current_parent_id ] ) && $this->parent_items_with_active_children[ $this->current_parent_id ];
		
		$expanded_class = $should_expand ? ' expanded' : '';
		// Inline submenu that expands and pushes content down
		$output .= "\n$indent<ul class=\"mobile-submenu w-full overflow-hidden max-h-0 transition-all duration-300 ease-in-out{$expanded_class}\">\n";
	}

	/**
	 * End the list after the elements are added.
	 */
	function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	/**
	 * Start the element output.
	 */
	function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );

		// Check if item is active/current
		$is_active = in_array( 'current-menu-item', $classes, true ) || 
		             in_array( 'current-menu-parent', $classes, true ) || 
		             in_array( 'current-menu-ancestor', $classes, true ) ||
		             in_array( 'current_page_item', $classes, true ) ||
		             in_array( 'current_page_parent', $classes, true ) ||
		             in_array( 'current_page_ancestor', $classes, true );

		// If this is a parent with children, check if it has active children
		if ( $has_children && 0 === $depth ) {
			$has_active_children = in_array( 'current-menu-ancestor', $classes, true ) || 
			                       in_array( 'current-menu-parent', $classes, true ) ||
			                       in_array( 'current_page_ancestor', $classes, true ) ||
			                       in_array( 'current_page_parent', $classes, true );
			$this->parent_items_with_active_children[ $item->ID ] = $has_active_children;
			$this->current_parent_id = $item->ID;
		}

		// Submenu items should not have horizontal padding (background needs to be full width)
		$padding_class = $depth > 0 ? 'px-0' : 'px-6';
		$output .= '<li class="mobile-menu-item w-full ' . $padding_class . ( $has_children && 0 === $depth ? ' mobile-menu-parent' : '' ) . ( $depth > 0 ? ' pt-1' : ' pt-2' ) . '">';

		if ( $has_children && 0 === $depth ) {
			// Parent item with children - use button to toggle submenu
			$active_class = $is_active ? 'text-primary' : 'text-accent';
			// Add 'active' class if submenu should be expanded
			$has_active_children = isset( $this->parent_items_with_active_children[ $item->ID ] ) && $this->parent_items_with_active_children[ $item->ID ];
			$toggle_active_class = $has_active_children ? ' active' : '';
			$output .= '<button type="button" class="mobile-menu-toggle w-full flex items-center justify-between pt-3 pb-0 text-base font-medium ' . esc_attr( $active_class ) . ' hover:text-primary transition-colors' . $toggle_active_class . '" data-submenu="submenu-' . $item->ID . '">';
			$output .= '<span>' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span>';
			$output .= '<span class="material-symbols-outlined text-lg transition-transform text-gray-400 submenu-icon">chevron_right</span>';
			$output .= '</button>';
		} else {
			// Regular menu item or submenu item
			$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
			$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
			$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
			$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

			if ( $depth > 0 ) {
				// Submenu item - add padding to the link itself so background stays full width
				// Only left padding, no right padding so background extends full width
				$active_class = $is_active ? 'text-primary' : 'text-gray-600 dark:text-gray-300';
				$output .= '<a' . $attributes . ' class="block w-full pt-2 pb-0 pl-8 pr-0 ' . esc_attr( $active_class ) . ' text-sm hover:text-primary dark:hover:text-white transition-colors">';
			} else {
				// Top-level menu item
				$active_class = $is_active ? 'text-primary' : 'text-accent';
				$output .= '<a' . $attributes . ' class="block w-full pt-3 pb-0 text-base font-medium ' . esc_attr( $active_class ) . ' hover:text-primary transition-colors">';
			}

			$item_output = isset( $args->before ) ? $args->link_before : '';
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
			$item_output .= isset( $args->link_after ) ? $args->link_after : '';

			$output .= $item_output;
			$output .= '</a>';
		}
	}

	/**
	 * End the element output.
	 */
	function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
