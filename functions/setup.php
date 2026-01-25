<?php
/**
 * Theme setup and configuration
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup
 */
function tsm_theme_setup() {
	// Add theme support for various features
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for custom logo
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Register navigation menus
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'tsm-theme' ),
			'footer'  => __( 'Footer Menu', 'tsm-theme' ),
		)
	);

	// Set content width
	if ( ! isset( $content_width ) ) {
		$content_width = 1200;
	}
}
add_action( 'after_setup_theme', 'tsm_theme_setup' );

/**
 * Flush rewrite rules on theme activation to ensure permalinks work
 */
function tsm_theme_activation() {
	// Flush rewrite rules
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tsm_theme_activation' );
