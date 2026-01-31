<?php
/**
 * Enqueue scripts and styles
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add preconnect for Google Fonts
 */
function tsm_theme_fonts_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'tsm_theme_fonts_preconnect', 1 );

/**
 * Enqueue scripts and styles
 */
function tsm_theme_scripts() {
	// Get theme version for cache busting
	$theme_version = wp_get_theme()->get( 'Version' );
	$theme_version = $theme_version ? $theme_version : '1.0.0';
	
	// Enqueue theme stylesheet (minimal, required at root for WordPress)
	wp_enqueue_style( 'tsm-theme-style', get_stylesheet_uri(), array(), $theme_version );

	// Enqueue main theme styles from assets/css/style.css
	// Use filemtime for cache busting based on file modification time
	$style_css_path = get_template_directory() . '/assets/css/style.css';
	$style_css_version = file_exists( $style_css_path ) ? filemtime( $style_css_path ) : $theme_version;
	wp_enqueue_style( 'tsm-theme-styles', get_template_directory_uri() . '/assets/css/style.css', array( 'tsm-theme-style' ), $style_css_version );

	// Enqueue Google Fonts
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700;900&family=Lora:ital,wght@0,400;0,700;1,400&display=swap', array(), null, 'all' );

	// Enqueue Material Symbols
	wp_enqueue_style( 'material-symbols', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap', array(), null );

	// Enqueue Material Design Icons (MDI)
	wp_enqueue_style( 'mdi-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css', array(), null );

	// Enqueue compiled Tailwind CSS (built from input.css)
	// Use filemtime for cache busting based on file modification time
	$main_css_path = get_template_directory() . '/assets/css/main.css';
	$main_css_version = file_exists( $main_css_path ) ? filemtime( $main_css_path ) : $theme_version;
	wp_enqueue_style( 'tsm-theme-main', get_template_directory_uri() . '/assets/css/main.css', array( 'tsm-theme-styles', 'google-fonts' ), $main_css_version );

	// Enqueue theme JavaScript
	// Use filemtime for cache busting based on file modification time
	$main_js_path = get_template_directory() . '/assets/js/main.js';
	$main_js_version = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : $theme_version;
	wp_enqueue_script( 'tsm-theme-script', get_template_directory_uri() . '/assets/js/main.js', array(), $main_js_version, true );

	// Enqueue comment reply script on single posts
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'tsm_theme_scripts' );
