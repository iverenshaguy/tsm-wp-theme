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
 * Theme setup
 */
function tsm_theme_setup() {
    // Add theme support for various features
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );
    
    // Add theme support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    
    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'tsm-theme' ),
        'footer'  => __( 'Footer Menu', 'tsm-theme' ),
    ) );
    
    // Set content width
    if ( ! isset( $content_width ) ) {
        $content_width = 1200;
    }
}
add_action( 'after_setup_theme', 'tsm_theme_setup' );

/**
 * Enqueue scripts and styles
 */
function tsm_theme_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style( 'tsm-theme-style', get_stylesheet_uri(), array(), '1.0.0' );
    
    // Enqueue main CSS from assets
    wp_enqueue_style( 'tsm-theme-main', get_template_directory_uri() . '/assets/css/main.css', array( 'tsm-theme-style' ), '1.0.0' );
    
    // Enqueue theme JavaScript
    wp_enqueue_script( 'tsm-theme-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
    
    // Enqueue comment reply script on single posts
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'tsm_theme_scripts' );

/**
 * Register widget areas
 */
function tsm_theme_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'tsm-theme' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'tsm-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 1', 'tsm-theme' ),
        'id'            => 'footer-1',
        'description'   => __( 'Add widgets here to appear in your footer.', 'tsm-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 2', 'tsm-theme' ),
        'id'            => 'footer-2',
        'description'   => __( 'Add widgets here to appear in your footer.', 'tsm-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
        'name'          => __( 'Footer 3', 'tsm-theme' ),
        'id'            => 'footer-3',
        'description'   => __( 'Add widgets here to appear in your footer.', 'tsm-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'tsm_theme_widgets_init' );

/**
 * Custom excerpt length
 */
function tsm_theme_excerpt_length( $length ) {
    return 55;
}
add_filter( 'excerpt_length', 'tsm_theme_excerpt_length' );

/**
 * Custom excerpt more
 */
function tsm_theme_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'tsm_theme_excerpt_more' );
