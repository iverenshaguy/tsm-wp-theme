<?php
/**
 * Navigation menu functionality
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Navigation Walker for Tailwind classes
 */
class TSM_Nav_Walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		
		// For first-level submenus (depth 0), position below parent
		// For nested submenus (depth > 0), position to the right of parent
		if ( $depth === 0 ) {
			$position_classes = 'top-full left-0 mt-2';
			// First-level submenu shows on parent group hover
			$hover_classes = 'group-hover:opacity-100 group-hover:visible';
		} else {
			$position_classes = 'top-0 left-full ml-2';
			// Nested submenu visibility is controlled by custom CSS
			// The CSS ensures it only shows when the direct parent <li.group> is hovered
			$hover_classes = '';
		}
		
		$output .= "\n$indent<ul class=\"absolute {$position_classes} w-56 opacity-0 invisible {$hover_classes} transition-all duration-200 bg-white dark:bg-background-dark border border-[#e7f3ea] dark:border-[#1d3a24] rounded-xl shadow-xl z-[60] py-2\">\n";
	}

	function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Add 'group' and 'relative' classes for items with children (for dropdown hover)
		// This applies to all levels, not just top-level items
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'relative group';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= '<li' . $id . $class_names . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		$item_output = isset( $args->before ) ? $args->before : '';

		// Check if item is active/current
		$is_active = in_array( 'current-menu-item', $classes, true ) || 
		             in_array( 'current-menu-parent', $classes, true ) || 
		             in_array( 'current-menu-ancestor', $classes, true ) ||
		             in_array( 'current_page_item', $classes, true ) ||
		             in_array( 'current_page_parent', $classes, true ) ||
		             in_array( 'current_page_ancestor', $classes, true );

		// Check if item has children (dropdown menu)
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		// Base text color classes - use primary color for active items
		$text_color_class = $is_active ? 'text-primary' : 'text-[#0d1b11] dark:text-white';

		if ( $has_children && 0 === $depth ) {
			// Dropdown link for parent items (styled like button with expand icon)
			$item_output .= '<a' . $attributes . ' class="flex items-center gap-1 ' . esc_attr( $text_color_class ) . ' hover:text-primary transition-colors text-sm font-semibold py-2">';
			$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
			$item_output .= '<span class="material-symbols-outlined !text-sm">expand_more</span>';
			$item_output .= '</a>';
		} elseif ( $depth > 0 ) {
			// Submenu items (dropdown items)
			$submenu_text_color = $is_active ? 'text-primary' : 'text-[#0d1b11] dark:text-white';
			// If submenu item has children, add expand icon, flex layout, and peer class
			if ( $has_children ) {
				$item_output .= '<a' . $attributes . ' class="peer flex items-center justify-between px-4 py-2.5 text-sm font-medium ' . esc_attr( $submenu_text_color ) . ' hover:bg-accent/10 hover:text-primary">';
				$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
				$item_output .= '<span class="material-symbols-outlined !text-sm">chevron_right</span>';
			} else {
				$item_output .= '<a' . $attributes . ' class="block px-4 py-2.5 text-sm font-medium ' . esc_attr( $submenu_text_color ) . ' hover:bg-accent/10 hover:text-primary">';
				$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
			}
			$item_output .= '</a>';
		} else {
			// Regular menu items
			$item_output .= '<a' . $attributes . ' class="' . esc_attr( $text_color_class ) . ' hover:text-primary transition-colors text-sm font-semibold py-2">';
			$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
			$item_output .= '</a>';
		}

		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}

/**
 * Add classes to footer menu links
 */
function tsm_footer_menu_link_attributes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'footer' === $args->theme_location ) {
		$atts['class'] = 'hover:text-accent transition-colors';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'tsm_footer_menu_link_attributes', 10, 3 );
