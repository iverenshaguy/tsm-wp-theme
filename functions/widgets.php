<?php
/**
 * Widget areas
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget areas
 */
function tsm_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'tsm-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'tsm-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 1 - Ministry Focus', 'tsm-theme' ),
			'id'            => 'footer-1',
			'description'   => __( 'Add widgets here to appear in the Ministry Focus section of your footer.', 'tsm-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="font-bold mb-8 text-primary dark:text-white uppercase tracking-wider text-sm">',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2 - Quick Links', 'tsm-theme' ),
			'id'            => 'footer-2',
			'description'   => __( 'Add widgets here to appear in the Quick Links section of your footer.', 'tsm-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="font-bold mb-8 text-primary dark:text-white uppercase tracking-wider text-sm">',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 3 - Contact', 'tsm-theme' ),
			'id'            => 'footer-3',
			'description'   => __( 'Add widgets here to appear in the Contact section of your footer.', 'tsm-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="font-bold mb-8 text-primary dark:text-white uppercase tracking-wider text-sm">',
			'after_title'   => '</h5>',
		)
	);
}
add_action( 'widgets_init', 'tsm_theme_widgets_init' );
