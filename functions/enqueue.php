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
	// Enqueue theme stylesheet
	wp_enqueue_style( 'tsm-theme-style', get_stylesheet_uri(), array(), '1.0.0' );

	// Enqueue Google Fonts
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700;900&family=Lora:ital,wght@0,400;0,700;1,400&display=swap', array(), null, 'all' );

	// Enqueue Material Symbols
	wp_enqueue_style( 'material-symbols', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap', array(), null );

	// Enqueue compiled Tailwind CSS (built from input.css)
	wp_enqueue_style( 'tsm-theme-main', get_template_directory_uri() . '/assets/css/main.css', array( 'tsm-theme-style', 'google-fonts' ), '1.0.0' );

	// Enqueue theme JavaScript
	wp_enqueue_script( 'tsm-theme-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );

	// Enqueue comment reply script on single posts
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'tsm_theme_scripts' );
