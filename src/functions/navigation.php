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

		// Get current URL for comparison
		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$item_url = ! empty( $item->url ) ? $item->url : '';
		
		// Normalize URLs for comparison (remove trailing slashes, convert to lowercase)
		$current_url_normalized = rtrim( strtolower( $current_url ), '/' );
		$item_url_normalized = rtrim( strtolower( $item_url ), '/' );
		$urls_match = $item_url_normalized && $current_url_normalized === $item_url_normalized;

		// Check if item has children (dropdown menu)
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		// For submenu items (depth > 0), use stricter checking to avoid highlighting siblings
		if ( $depth > 0 && ! $has_children ) {
			// Submenu items: URLs MUST match for item to be active
			// Ignore WordPress classes - only use URL comparison to prevent sibling highlighting
			$is_active = $urls_match;
		} else {
			// Parent items or top-level items: check WordPress classes and URL
			$is_active = in_array( 'current-menu-item', $classes, true ) || 
			             in_array( 'current-menu-parent', $classes, true ) || 
			             in_array( 'current-menu-ancestor', $classes, true ) ||
			             in_array( 'current_page_item', $classes, true ) ||
			             in_array( 'current_page_parent', $classes, true ) ||
			             in_array( 'current_page_ancestor', $classes, true ) ||
			             $urls_match;
		}

		// Base text color classes - use primary color for active items
		$text_color_class = $is_active ? 'text-primary' : 'text-accent dark:text-white';

		if ( $has_children && 0 === $depth ) {
			// Dropdown link for parent items (styled like button with expand icon)
			$item_output .= '<a' . $attributes . ' class="flex items-center gap-1 ' . esc_attr( $text_color_class ) . ' hover:text-primary transition-colors text-sm font-semibold py-2">';
			$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . ( isset( $args->link_after ) ? $args->link_after : '' );
			$item_output .= '<span class="material-symbols-outlined !text-sm">expand_more</span>';
			$item_output .= '</a>';
		} elseif ( $depth > 0 ) {
			// Submenu items (dropdown items)
			$submenu_text_color = $is_active ? 'text-primary' : 'text-accent dark:text-white';
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
			$item_output .= '<a' . $attributes . ' class="flex items-center ' . esc_attr( $text_color_class ) . ' hover:text-primary transition-colors text-sm font-semibold py-2">';
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

/**
 * Mark menu items as active on archive and single post pages
 * This ensures parent menu items are highlighted when viewing single posts/pages
 */
function tsm_highlight_books_menu_item( $classes, $item, $args ) {
	// Get current request URI
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';
	$current_path = trim( parse_url( $request_uri, PHP_URL_PATH ), '/' );
	
	// Normalize paths (remove leading/trailing slashes and convert to lowercase for comparison)
	$current_path_normalized = strtolower( trim( $current_path, '/' ) );
	
	// Check if we're on a book-related page
	if ( is_post_type_archive( 'book' ) || is_singular( 'book' ) || is_tax( 'book_category' ) ) {
		// Get menu item URL
		if ( ! empty( $item->url ) ) {
			$item_url_parts = parse_url( $item->url );
			$item_path = isset( $item_url_parts['path'] ) ? strtolower( trim( $item_url_parts['path'], '/' ) ) : '';
			
			// Check multiple conditions
			$should_highlight = false;
			
			// Condition 1: Menu item path is exactly 'books' and current path starts with 'books'
			if ( $item_path === 'books' && strpos( $current_path_normalized, 'books' ) === 0 ) {
				$should_highlight = true;
			}
			
			// Condition 2: Menu item is a page with slug 'books'
			if ( 'page' === $item->object && ! empty( $item->object_id ) ) {
				$page = get_post( $item->object_id );
				if ( $page && 'books' === $page->post_name ) {
					if ( strpos( $current_path_normalized, 'books' ) === 0 ) {
						$should_highlight = true;
					}
				}
			}
			
			// Condition 3: Menu item URL contains 'books' and current path contains 'books'
			if ( strpos( $item_path, 'books' ) !== false && strpos( $current_path_normalized, 'books' ) === 0 ) {
				$should_highlight = true;
			}
			
			if ( $should_highlight ) {
				if ( ! in_array( 'current-menu-item', $classes, true ) ) {
					$classes[] = 'current-menu-item';
				}
				if ( ! in_array( 'current-menu-ancestor', $classes, true ) ) {
					$classes[] = 'current-menu-ancestor';
				}
				if ( ! in_array( 'current-menu-parent', $classes, true ) ) {
					$classes[] = 'current-menu-parent';
				}
				if ( ! in_array( 'current_page_item', $classes, true ) ) {
					$classes[] = 'current_page_item';
				}
				if ( ! in_array( 'current_page_ancestor', $classes, true ) ) {
					$classes[] = 'current_page_ancestor';
				}
				return $classes;
			}
		}
	}
	
	// Check if we're on a mission-related page
	if ( is_post_type_archive( 'mission' ) || is_singular( 'mission' ) ) {
		// Get menu item URL
		if ( ! empty( $item->url ) ) {
			$item_url_parts = parse_url( $item->url );
			$item_path = isset( $item_url_parts['path'] ) ? strtolower( trim( $item_url_parts['path'], '/' ) ) : '';
			
			// Check if menu item URL contains 'mission' and current path contains 'mission'
			if ( strpos( $item_path, 'mission' ) !== false && strpos( $current_path_normalized, 'mission' ) === 0 ) {
				if ( ! in_array( 'current-menu-item', $classes, true ) ) {
					$classes[] = 'current-menu-item';
				}
				if ( ! in_array( 'current-menu-ancestor', $classes, true ) ) {
					$classes[] = 'current-menu-ancestor';
				}
				if ( ! in_array( 'current-menu-parent', $classes, true ) ) {
					$classes[] = 'current-menu-parent';
				}
				return $classes;
			}
		}
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'tsm_highlight_books_menu_item', 999, 3 );

/**
 * Mark ancestors of active submenu/sub-submenu items for desktop navigation
 * Similar to mobile menu but for TSM_Nav_Walker
 */
function tsm_mark_desktop_nav_ancestors( $sorted_menu_items, $args ) {
	// Only process desktop navigation (primary menu with TSM_Nav_Walker)
	$is_desktop_nav = false;
	if ( isset( $args->walker ) && is_a( $args->walker, 'TSM_Nav_Walker' ) ) {
		$is_desktop_nav = true;
	} elseif ( isset( $args->theme_location ) && $args->theme_location === 'primary' ) {
		$is_desktop_nav = true;
	}
	
	if ( ! $is_desktop_nav || empty( $sorted_menu_items ) ) {
		return $sorted_menu_items;
	}
	
	// Get current URL
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$current_url_normalized = rtrim( strtolower( $current_url ), '/' );
	
	// Build parent-child relationships
	$parent_map = array();
	$item_map = array();
	$depth_map = array();
	
	foreach ( $sorted_menu_items as $item ) {
		$item_map[ $item->ID ] = $item;
		if ( ! empty( $item->menu_item_parent ) ) {
			$parent_map[ $item->ID ] = (int) $item->menu_item_parent;
		}
	}
	
	// Calculate depths
	foreach ( $sorted_menu_items as $item ) {
		$depth = 0;
		$p = isset( $parent_map[ $item->ID ] ) ? $parent_map[ $item->ID ] : 0;
		while ( $p && isset( $item_map[ $p ] ) ) {
			$depth++;
			$p = isset( $parent_map[ $p ] ) ? $parent_map[ $p ] : 0;
		}
		$depth_map[ $item->ID ] = $depth;
	}
	
	// Find active submenu/sub-submenu items and mark them AND their ancestors
	$active_ancestors = array();
	$active_submenu_items = array();
	
	foreach ( $sorted_menu_items as $item ) {
		$item_depth = isset( $depth_map[ $item->ID ] ) ? $depth_map[ $item->ID ] : 0;
		
		// For submenu items (depth > 0), check URL match
		if ( $item_depth > 0 ) {
			$item_url = ! empty( $item->url ) ? $item->url : '';
			$item_url_normalized = rtrim( strtolower( $item_url ), '/' );
			$url_matches = $item_url_normalized && $current_url_normalized === $item_url_normalized;
			
			if ( $url_matches ) {
				// Mark this submenu item itself as active
				$active_submenu_items[] = $item->ID;
				
				// Mark ancestors up the chain
				$levels_to_traverse = $item_depth;
				$level_count = 0;
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
	}
	
	// Mark active submenu/sub-submenu items themselves as active
	// Only mark items that actually match the URL (already verified above)
	if ( ! empty( $active_submenu_items ) ) {
		foreach ( $sorted_menu_items as $item ) {
			if ( in_array( $item->ID, $active_submenu_items, true ) ) {
				// Verify URL match again before marking (safety check)
				$item_url = ! empty( $item->url ) ? $item->url : '';
				$item_url_normalized = rtrim( strtolower( $item_url ), '/' );
				if ( $item_url_normalized && $current_url_normalized === $item_url_normalized ) {
					// Add WordPress active classes so the walker highlights them
					if ( ! in_array( 'current-menu-item', $item->classes, true ) ) {
						$item->classes[] = 'current-menu-item';
					}
					if ( ! in_array( 'current_page_item', $item->classes, true ) ) {
						$item->classes[] = 'current_page_item';
					}
				}
			}
		}
	}
	
	// Mark ancestors as active by adding WordPress classes
	if ( ! empty( $active_ancestors ) ) {
		$unique_ancestors = array_unique( $active_ancestors );
		foreach ( $sorted_menu_items as $item ) {
			if ( in_array( $item->ID, $unique_ancestors, true ) ) {
				// Add WordPress active classes so the walker highlights them
				if ( ! in_array( 'current-menu-ancestor', $item->classes, true ) ) {
					$item->classes[] = 'current-menu-ancestor';
				}
				if ( ! in_array( 'current-menu-parent', $item->classes, true ) ) {
					$item->classes[] = 'current-menu-parent';
				}
				if ( ! in_array( 'current_page_ancestor', $item->classes, true ) ) {
					$item->classes[] = 'current_page_ancestor';
				}
				if ( ! in_array( 'current_page_parent', $item->classes, true ) ) {
					$item->classes[] = 'current_page_parent';
				}
			}
		}
	}
	
	return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'tsm_mark_desktop_nav_ancestors', 6, 2 );

/**
 * Also use wp_nav_menu_objects filter for more reliable menu item matching
 */
function tsm_highlight_menu_items_for_custom_post_types( $sorted_menu_items, $args ) {
	// Get current request URI
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';
	$current_path = trim( parse_url( $request_uri, PHP_URL_PATH ), '/' );
	$current_path_normalized = strtolower( trim( $current_path, '/' ) );
	
	// Check if we're on a book-related page
	$is_book_page = is_post_type_archive( 'book' ) || is_singular( 'book' ) || is_tax( 'book_category' );
	
	// Check if we're on a mission-related page
	$is_mission_page = is_post_type_archive( 'mission' ) || is_singular( 'mission' );
	
	if ( $is_book_page || $is_mission_page ) {
		$post_type = $is_book_page ? 'book' : 'mission';
		$slug = $is_book_page ? 'books' : 'missions';
		
		foreach ( $sorted_menu_items as $item ) {
			if ( ! empty( $item->url ) ) {
				$item_url_parts = parse_url( $item->url );
				$item_path = isset( $item_url_parts['path'] ) ? strtolower( trim( $item_url_parts['path'], '/' ) ) : '';
				
				// Check if this menu item matches the post type archive
				if ( $item_path === $slug || strpos( $item_path, $slug ) !== false ) {
					// Check if current path starts with the slug
					if ( strpos( $current_path_normalized, $slug ) === 0 ) {
						// Add active classes
						if ( ! in_array( 'current-menu-item', $item->classes, true ) ) {
							$item->classes[] = 'current-menu-item';
						}
						if ( ! in_array( 'current-menu-ancestor', $item->classes, true ) ) {
							$item->classes[] = 'current-menu-ancestor';
						}
						if ( ! in_array( 'current-menu-parent', $item->classes, true ) ) {
							$item->classes[] = 'current-menu-parent';
						}
					}
				}
				
				// Also check if menu item is a page with matching slug
				if ( 'page' === $item->object && ! empty( $item->object_id ) ) {
					$page = get_post( $item->object_id );
					if ( $page && $slug === $page->post_name ) {
						if ( strpos( $current_path_normalized, $slug ) === 0 ) {
							if ( ! in_array( 'current-menu-item', $item->classes, true ) ) {
								$item->classes[] = 'current-menu-item';
							}
							if ( ! in_array( 'current-menu-ancestor', $item->classes, true ) ) {
								$item->classes[] = 'current-menu-ancestor';
							}
							if ( ! in_array( 'current-menu-parent', $item->classes, true ) ) {
								$item->classes[] = 'current-menu-parent';
							}
							if ( ! in_array( 'current_page_item', $item->classes, true ) ) {
								$item->classes[] = 'current_page_item';
							}
							if ( ! in_array( 'current_page_ancestor', $item->classes, true ) ) {
								$item->classes[] = 'current_page_ancestor';
							}
						}
					}
				}
			}
		}
	}
	
	return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'tsm_highlight_menu_items_for_custom_post_types', 10, 2 );
