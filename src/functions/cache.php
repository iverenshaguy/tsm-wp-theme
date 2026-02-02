<?php
/**
 * Database Query Optimization and Caching
 *
 * This file provides caching functions to reduce database queries
 * and improve site performance.
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get theme mod with caching
 * 
 * Caches Customizer options to reduce database queries.
 * Customizer options are frequently accessed but rarely change,
 * making them perfect candidates for caching.
 *
 * @param string $mod_name Theme modification name.
 * @param mixed  $default  Default value if option doesn't exist.
 * @return mixed Theme modification value.
 */
function tsm_get_theme_mod_cached( $mod_name, $default = false ) {
	// Fallback to get_theme_mod if WordPress functions aren't available yet
	if ( ! function_exists( 'get_theme_mod' ) ) {
		return $default;
	}
	
	if ( ! function_exists( 'wp_cache_get' ) || ! function_exists( 'wp_cache_set' ) ) {
		// Cache functions not available, use get_theme_mod directly
		return get_theme_mod( $mod_name, $default );
	}
	
	// Use WordPress object cache if available
	$cache_key = 'tsm_theme_mod_' . $mod_name;
	$cached_value = wp_cache_get( $cache_key, 'tsm_theme' );
	
	if ( false !== $cached_value ) {
		return $cached_value;
	}
	
	// Get value from database
	$value = get_theme_mod( $mod_name, $default );
	
	// Cache for 1 hour (3600 seconds)
	// Customizer options rarely change, so longer cache is safe
	wp_cache_set( $cache_key, $value, 'tsm_theme', 3600 );
	
	return $value;
}

/**
 * Clear theme mod cache
 * 
 * Call this when Customizer options are updated to clear cached values.
 *
 * @param string $mod_name Optional. Specific mod name to clear. If empty, clears all.
 */
function tsm_clear_theme_mod_cache( $mod_name = '' ) {
	if ( empty( $mod_name ) ) {
		// Clear all theme mod caches
		wp_cache_flush_group( 'tsm_theme' );
	} else {
		// Clear specific mod cache
		$cache_key = 'tsm_theme_mod_' . $mod_name;
		wp_cache_delete( $cache_key, 'tsm_theme' );
	}
}

/**
 * Get multiple theme mods at once (batch operation)
 * 
 * Reduces database queries by fetching multiple options in one go.
 * Useful when you need several Customizer options on the same page.
 *
 * @param array $mod_names Array of theme modification names.
 * @return array Associative array of mod_name => value.
 */
function tsm_get_theme_mods_cached( $mod_names ) {
	$results = array();
	$uncached = array();
	
	// Check cache for each mod
	foreach ( $mod_names as $mod_name ) {
		$cache_key = 'tsm_theme_mod_' . $mod_name;
		$cached_value = wp_cache_get( $cache_key, 'tsm_theme' );
		
		if ( false !== $cached_value ) {
			$results[ $mod_name ] = $cached_value;
		} else {
			$uncached[] = $mod_name;
		}
	}
	
	// Fetch uncached mods from database
	if ( ! empty( $uncached ) ) {
		// Get all theme mods at once (more efficient than individual queries)
		$theme_mods = get_theme_mods();
		
		foreach ( $uncached as $mod_name ) {
			$value = isset( $theme_mods[ $mod_name ] ) ? $theme_mods[ $mod_name ] : false;
			$results[ $mod_name ] = $value;
			
			// Cache the value
			$cache_key = 'tsm_theme_mod_' . $mod_name;
			wp_cache_set( $cache_key, $value, 'tsm_theme', 3600 );
		}
	}
	
	return $results;
}

/**
 * Cache WordPress options
 * 
 * Caches frequently accessed WordPress options to reduce queries.
 *
 * @param string $option_name Option name.
 * @param mixed  $default      Default value if option doesn't exist.
 * @param int    $cache_time   Cache duration in seconds (default: 1 hour).
 * @return mixed Option value.
 */
function tsm_get_option_cached( $option_name, $default = false, $cache_time = 3600 ) {
	$cache_key = 'tsm_option_' . $option_name;
	$cached_value = wp_cache_get( $cache_key, 'tsm_options' );
	
	if ( false !== $cached_value ) {
		return $cached_value;
	}
	
	$value = get_option( $option_name, $default );
	wp_cache_set( $cache_key, $value, 'tsm_options', $cache_time );
	
	return $value;
}

/**
 * Clear option cache
 *
 * @param string $option_name Option name to clear cache for.
 */
function tsm_clear_option_cache( $option_name ) {
	$cache_key = 'tsm_option_' . $option_name;
	wp_cache_delete( $cache_key, 'tsm_options' );
}

/**
 * Clear all theme caches
 * 
 * Useful when Customizer options are updated or theme is updated.
 */
function tsm_clear_all_caches() {
	// Clear theme mod caches
	wp_cache_flush_group( 'tsm_theme' );
	
	// Clear option caches
	wp_cache_flush_group( 'tsm_options' );
	
	// Clear WordPress object cache if available
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
	
	// Clear transients
	global $wpdb;
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_tsm_%'" );
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_tsm_%'" );
}

/**
 * Hook to clear caches when Customizer is saved
 */
function tsm_clear_cache_on_customizer_save() {
	tsm_clear_all_caches();
}
add_action( 'customize_save_after', 'tsm_clear_cache_on_customizer_save' );

/**
 * Optimize database queries by disabling unnecessary features
 */
function tsm_optimize_database_queries() {
	// Disable post revisions (already set in wp-config.php, but ensure it's respected)
	if ( ! defined( 'WP_POST_REVISIONS' ) ) {
		define( 'WP_POST_REVISIONS', 3 );
	}
	
	// Disable autosave interval (reduce database writes)
	// Note: This doesn't disable autosave, just reduces frequency
	if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
		define( 'AUTOSAVE_INTERVAL', 300 ); // 5 minutes instead of default 60 seconds
	}
	
	// Disable trash (permanently delete posts instead of moving to trash)
	// Uncomment if you want to disable trash functionality
	// define( 'EMPTY_TRASH_DAYS', 0 );
}
add_action( 'init', 'tsm_optimize_database_queries', 1 );

/**
 * Optimize WP_Query by setting reasonable defaults
 */
function tsm_optimize_wp_query( $query ) {
	// Only optimize front-end queries
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	
	// Set reasonable post limits
	if ( $query->is_archive() || $query->is_home() ) {
		// Limit posts per page to reduce query size
		if ( ! $query->get( 'posts_per_page' ) ) {
			$query->set( 'posts_per_page', 12 ); // Reasonable default
		}
		
		// Disable unnecessary meta queries if not needed
		// This reduces JOIN operations
		if ( ! $query->get( 'meta_query' ) ) {
			$query->set( 'update_post_meta_cache', false );
			$query->set( 'update_post_term_cache', true ); // Keep term cache for categories/tags
		}
	}
}
add_action( 'pre_get_posts', 'tsm_optimize_wp_query' );
