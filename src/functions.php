<?php
/**
 * TSM Theme functions and definitions
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Clear WordPress cache on theme activation/update
 * This ensures old cached asset paths are cleared
 */
function tsm_theme_clear_cache() {
	// Clear all transients
	global $wpdb;
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'" );
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%'" );
	
	// Clear object cache
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
	
	// Clear rewrite rules
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tsm_theme_clear_cache' );
add_action( 'upgrader_process_complete', 'tsm_theme_clear_cache', 10, 2 );

/**
 * Load theme functionality from modular files
 */
require_once get_template_directory() . '/functions/setup.php';
require_once get_template_directory() . '/functions/enqueue.php';
require_once get_template_directory() . '/functions/navigation.php';
require_once get_template_directory() . '/functions/mobile-menu-walker.php';
require_once get_template_directory() . '/functions/widgets.php';
require_once get_template_directory() . '/functions/post-types.php';
require_once get_template_directory() . '/functions/customizer.php';
require_once get_template_directory() . '/functions/forms.php';
require_once get_template_directory() . '/functions/lightbox.php';

/**
 * Use mission hero image as featured image for missions archive page
 * Filters WordPress thumbnail functions to use mission_hero_image when no featured image is set
 */
function tsm_mission_has_post_thumbnail( $has_thumbnail, $post ) {
	if ( 'mission' === get_post_type( $post ) && ! $has_thumbnail ) {
		$mission_hero_image = get_post_meta( $post->ID, 'mission_hero_image', true );
		if ( ! empty( $mission_hero_image ) ) {
			return true;
		}
	}
	return $has_thumbnail;
}
add_filter( 'has_post_thumbnail', 'tsm_mission_has_post_thumbnail', 10, 2 );

function tsm_mission_get_post_thumbnail_id( $thumbnail_id, $post ) {
	if ( 'mission' === get_post_type( $post ) && empty( $thumbnail_id ) ) {
		$mission_hero_image = get_post_meta( $post->ID, 'mission_hero_image', true );
		if ( ! empty( $mission_hero_image ) ) {
			return absint( $mission_hero_image );
		}
	}
	return $thumbnail_id;
}
add_filter( 'post_thumbnail_id', 'tsm_mission_get_post_thumbnail_id', 10, 2 );
