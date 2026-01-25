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
 * Enqueue scripts and styles
 */
function tsm_theme_scripts() {
	// Enqueue theme stylesheet
	wp_enqueue_style( 'tsm-theme-style', get_stylesheet_uri(), array(), '1.0.0' );

	// Enqueue Google Fonts
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700;900&display=swap', array(), null );

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
