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
 * Pre-process menu items to mark ancestors of active sub-sub menu items
 * This runs before the walker processes items
 */
function tsm_mark_active_sub_sub_ancestors( $sorted_menu_items, $args ) {
	// Only process if we have menu items
	if ( empty( $sorted_menu_items ) ) {
		return $sorted_menu_items;
	}

	/**
	 * IMPORTANT:
	 * Tighten this check so we only run for the mobile menu walker.
	 * Your logs show this filter runs more than once per request; globals can be overwritten if multiple menus are rendered.
	 */
	$is_mobile_menu = false;
	if ( isset( $args->walker ) && is_a( $args->walker, 'TSM_Mobile_Menu_Walker' ) ) {
		$is_mobile_menu = true;
	} elseif ( isset( $args->menu_id ) && $args->menu_id === 'mobile-menu' ) {
		$is_mobile_menu = true;
	}

	if ( ! $is_mobile_menu ) {
		return $sorted_menu_items;
	}


	// Get current URL
	$current_url             = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url_normalized  = rtrim( strtolower( $current_url ), '/' );

	/**
	 * Build maps:
	 * - $item_map:     itemID -> item object
	 * - $parent_map:   itemID -> DIRECT parentID  (CRITICAL FIX: do NOT overwrite with top ancestor)
	 * - $descendant_map: parentID -> array(childIDs)
	 * - $depth_map:    itemID -> depth (0,1,2,...)
	 */
	$item_map       = array();
	$parent_map     = array(); // direct parent only
	$descendant_map = array();
	$depth_map      = array();

	foreach ( $sorted_menu_items as $item ) {
		$item_map[ $item->ID ] = $item;
		$descendant_map[ $item->ID ] = array();
		if ( ! empty( $item->menu_item_parent ) ) {
			$parent_map[ $item->ID ] = (int) $item->menu_item_parent; // ✅ direct parent only
		}
	}

	// Build descendant map (reverse of parent map)
	foreach ( $sorted_menu_items as $item ) {
		if ( ! empty( $item->menu_item_parent ) ) {
			$p = (int) $item->menu_item_parent;
			if ( isset( $descendant_map[ $p ] ) ) {
				$descendant_map[ $p ][] = $item->ID;
			}
		}
	}

	// Calculate depths using the DIRECT parent map (stable)
	foreach ( $sorted_menu_items as $item ) {
		$depth = 0;
		$p = isset( $parent_map[ $item->ID ] ) ? $parent_map[ $item->ID ] : 0;

		while ( $p && isset( $item_map[ $p ] ) ) {
			$depth++;
			$p = isset( $parent_map[ $p ] ) ? $parent_map[ $p ] : 0;
		}

		$depth_map[ $item->ID ] = $depth;
	}

	// Find all active menu items (depth > 0) and mark their ancestors
	$active_ancestors = array();
	$active_item_ids  = array();

	foreach ( $sorted_menu_items as $item ) {
		$item_depth = isset( $depth_map[ $item->ID ] ) ? $depth_map[ $item->ID ] : 0;

		// Normalize item URL
		$item_url            = ! empty( $item->url ) ? $item->url : '';
		$item_url_normalized = rtrim( strtolower( $item_url ), '/' );
		$url_matches         = $item_url_normalized && $current_url_normalized === $item_url_normalized;

		// Only mark ancestors for items that are actually active via URL match (not WP classes)
		if ( $url_matches && $item_depth > 0 ) {
			$active_item_ids[] = $item->ID;

			/**
			 * ✅ CRITICAL FIX:
			 * Walk up the chain using the DIRECT parent map.
			 * For depth 2 items, this marks depth1 parent and depth0 grandparent.
			 */
			$levels_to_traverse = $item_depth;
			$level_count        = 0;

			$current_parent_id = isset( $parent_map[ $item->ID ] ) ? $parent_map[ $item->ID ] : 0;

			while ( $current_parent_id && $level_count < $levels_to_traverse ) {
				$active_ancestors[] = $current_parent_id;
				$level_count++;

				if ( $level_count < $levels_to_traverse ) {
					$current_parent_id = isset( $parent_map[ $current_parent_id ] ) ? $parent_map[ $current_parent_id ] : 0;
				} else {
					break;
				}
			}


		}
	}


	// Store active ancestors and parents with active children in a way the walker can access
	global $tsm_mobile_menu_active_ancestors, $tsm_mobile_menu_parents_with_active_children;

	if ( ! empty( $active_ancestors ) ) {
		$tsm_mobile_menu_active_ancestors = array_values( array_unique( $active_ancestors ) );
		$tsm_mobile_menu_parents_with_active_children = $tsm_mobile_menu_active_ancestors;

		// Also add a custom property to the walker args (optional)
		if ( is_object( $args ) ) {
			if ( ! isset( $args->active_ancestors ) ) {
				$args->active_ancestors = $tsm_mobile_menu_active_ancestors;
			}
			if ( ! isset( $args->parents_with_active_children ) ) {
				$args->parents_with_active_children = $tsm_mobile_menu_parents_with_active_children;
			}
		}
	} else {
		// Ensure globals are cleared (prevents stale data on pages with no matches)
		$tsm_mobile_menu_active_ancestors = array();
		$tsm_mobile_menu_parents_with_active_children = array();
	}

	return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'tsm_mark_active_sub_sub_ancestors', 5, 2 );

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
	 * Track the last item ID processed (to identify parent in start_lvl)
	 */
	private $last_item_id = 0;

	/**
	 * Track active sub-sub menu items and their ancestors
	 */
	private $active_ancestors = array(); // Array of item IDs that should be highlighted as ancestors

	/**
	 * Start the list before the elements are added.
	 */
	function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		// Use the last item ID as the parent (since start_lvl is called after start_el for the parent)
		$parent_id = $this->last_item_id > 0 ? $this->last_item_id : $this->current_parent_id;

		// Check if the current parent has active children
		$should_expand = isset( $this->parent_items_with_active_children[ $parent_id ] ) && $this->parent_items_with_active_children[ $parent_id ];

		$expanded_class = $should_expand ? ' expanded' : '';
		// Depth 1 means it's a sub-submenu (second level submenu)
		$depth_class = $depth === 1 ? ' mobile-sub-submenu' : '';
		// Inline submenu that expands and pushes content down
		$output .= "\n$indent<ul class=\"mobile-submenu w-full overflow-hidden max-h-0 transition-all duration-300 ease-in-out{$expanded_class}{$depth_class}\" data-depth=\"{$depth}\" data-parent-id=\"{$parent_id}\">\n";
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
		// Get active ancestors from global or args
		global $tsm_mobile_menu_active_ancestors, $tsm_mobile_menu_parents_with_active_children;

		// Initialize active_ancestors if not set
		if ( ! isset( $this->active_ancestors ) ) {
			$this->active_ancestors = array();
		}

		if ( isset( $args->active_ancestors ) && is_array( $args->active_ancestors ) ) {
			$this->active_ancestors = $args->active_ancestors;
		} elseif ( isset( $tsm_mobile_menu_active_ancestors ) && is_array( $tsm_mobile_menu_active_ancestors ) ) {
			$this->active_ancestors = $tsm_mobile_menu_active_ancestors;
		}

		// Get parents with active children from global or args
		$parents_with_active_children = array();
		if ( isset( $args->parents_with_active_children ) ) {
			$parents_with_active_children = $args->parents_with_active_children;
		} elseif ( isset( $tsm_mobile_menu_parents_with_active_children ) ) {
			$parents_with_active_children = $tsm_mobile_menu_parents_with_active_children;
		}


		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );

		// Get current URL and item URL for comparison
		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$item_url    = ! empty( $item->url ) ? $item->url : '';

		// Normalize URLs for comparison (remove trailing slashes, convert to lowercase)
		$current_url_normalized = rtrim( strtolower( $current_url ), '/' );
		$item_url_normalized    = rtrim( strtolower( $item_url ), '/' );
		$urls_match             = $item_url_normalized && $current_url_normalized === $item_url_normalized;

		// For submenu items (depth > 0), use stricter checking to avoid highlighting siblings
		if ( $depth > 0 && ! $has_children ) {
			// Submenu items: URLs MUST match for item to be active
			// Ignore WordPress classes - only use URL comparison to prevent sibling highlighting
			$is_active = $urls_match;
		} else {
			// Check if this item is in the active ancestors list (set by filter)
			// If so, mark it as active (this handles parent/grandparent highlighting)
			if ( in_array( $item->ID, $this->active_ancestors, true ) ) {
				$is_active = true;
			} else {
				// For items NOT in the ancestors list, use normal WordPress classes/URL check
				// But only for top-level items (depth 0) - parent items should only be active if in ancestors list
				if ( $depth === 0 ) {
					$is_active = in_array( 'current-menu-item', $classes, true ) ||
					             in_array( 'current-menu-parent', $classes, true ) ||
					             in_array( 'current-menu-ancestor', $classes, true ) ||
					             in_array( 'current_page_item', $classes, true ) ||
					             in_array( 'current_page_parent', $classes, true ) ||
					             in_array( 'current_page_ancestor', $classes, true ) ||
					             $urls_match;
				} else {
					// For parent items at depth > 0, only mark active if URL matches (not WordPress classes)
					$is_active = $urls_match;
				}
			}
		}

		// Track the last item ID (needed for start_lvl to identify parent)
		$this->last_item_id = $item->ID;

		// If this is a parent with children, check if it has active children
		if ( $has_children ) {
			$has_active_children = in_array( 'current-menu-ancestor', $classes, true ) ||
			                       in_array( 'current-menu-parent', $classes, true ) ||
			                       in_array( 'current_page_ancestor', $classes, true ) ||
			                       in_array( 'current_page_parent', $classes, true );

			// Check if this parent is an ancestor of an active descendant
			if ( in_array( $item->ID, $this->active_ancestors, true ) ) {
				$has_active_children = true;
				$is_active           = true; // Also mark as active for highlighting
			}

			// Also check the pre-processed list from the filter
			if ( in_array( $item->ID, $parents_with_active_children, true ) ) {
				$has_active_children = true;
				$is_active           = true; // Also mark as active for highlighting
			}

			$this->parent_items_with_active_children[ $item->ID ] = $has_active_children;
			$this->current_parent_id                              = $item->ID;
		}

		// Final check: if this item is in the active ancestors list, ensure it's marked as active
		if ( in_array( $item->ID, $this->active_ancestors, true ) ) {
			$is_active = true;
		}

		// Submenu items should not have horizontal padding (background needs to be full width)
		$padding_class = $depth > 0 ? 'px-0' : 'px-6';
		$depth_class   = $depth === 1 ? ' mobile-menu-sub-parent' : ( $depth === 2 ? ' mobile-menu-sub-sub-item' : '' );
		$output .= '<li class="mobile-menu-item w-full ' . $padding_class . ( $has_children ? ' mobile-menu-parent' : '' ) . ( $depth > 0 ? ' pt-1' : ' pt-2' ) . $depth_class . '" data-item-id="' . $item->ID . '">';

		if ( $has_children ) {
			// Parent item with children - use button to toggle submenu (works for depth 0 and depth 1)
			$is_ancestor = in_array( $item->ID, $this->active_ancestors, true );
			if ( $is_ancestor ) {
				$is_active = true; // Ancestors should be highlighted
			}

			// Add 'active' class if submenu should be expanded OR if this is an ancestor
			$has_active_children = isset( $this->parent_items_with_active_children[ $item->ID ] ) && $this->parent_items_with_active_children[ $item->ID ];
			if ( $is_ancestor ) {
				$has_active_children = true; // Force expansion for ancestors
			}
			$toggle_active_class = $has_active_children ? ' active' : '';

			// Set active_class for color
			$active_class = $is_active ? 'text-primary' : ( $depth === 0 ? 'text-accent' : 'text-gray-600 dark:text-gray-300' );


			$text_size_class     = $depth === 0 ? 'text-base font-medium' : 'text-sm';
			$padding_class_toggle = $depth === 0 ? 'pt-3' : 'pt-2';
			$pl_class            = $depth === 1 ? 'pl-8' : '';
			$output .= '<button type="button" class="mobile-menu-toggle w-full flex items-center justify-between ' . $padding_class_toggle . ' pb-0 ' . $text_size_class . ' ' . esc_attr( $active_class ) . ' hover:text-primary transition-colors' . $toggle_active_class . ' ' . $pl_class . '" data-submenu="submenu-' . $item->ID . '" data-depth="' . $depth . '" data-item-id="' . $item->ID . '">';
			$output .= '<span>' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span>';
			$output .= '<span class="material-symbols-outlined text-lg transition-transform text-gray-400 submenu-icon">chevron_right</span>';
			$output .= '</button>';
		} else {
			// Regular menu item or submenu item
			$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
			$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
			$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
			$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

			if ( $depth === 1 ) {
				// First-level submenu item
				$active_class = $is_active ? 'text-primary' : 'text-gray-600 dark:text-gray-300';
				$output .= '<a' . $attributes . ' class="block w-full pt-2 pb-0 pl-8 pr-0 ' . esc_attr( $active_class ) . ' text-sm hover:text-primary dark:hover:text-white transition-colors">';
			} elseif ( $depth === 2 ) {
				// Second-level submenu item (sub-sub menu)
				$active_class = $is_active ? 'text-primary' : 'text-gray-500 dark:text-gray-400';
				$output .= '<a' . $attributes . ' class="block w-full pt-2 pb-0 pr-0 ' . esc_attr( $active_class ) . ' text-sm hover:text-primary dark:hover:text-white transition-colors" style="padding-left: 1.5rem;">';
			} else {
				// Top-level menu item
				$active_class = $is_active ? 'text-primary' : 'text-accent';
				$output .= '<a' . $attributes . ' class="block w-full pt-3 pb-0 text-base font-medium ' . esc_attr( $active_class ) . ' hover:text-primary transition-colors">';
			}

			$item_output  = isset( $args->before ) ? $args->link_before : '';
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