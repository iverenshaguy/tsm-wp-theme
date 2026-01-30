<?php
/**
 * Books Archive Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Books Archive Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_books_archive( $wp_customize ) {
	// Books Archive Section
	$wp_customize->add_section(
		'tsm_books_archive',
		array(
			'title'    => __( 'Books Archive Settings', 'tsm-theme' ),
			'priority' => 40,
		)
	);

	// Featured Book
	$wp_customize->add_setting(
		'books_featured_book',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	// Get all published books for dropdown
	$books = get_posts(
		array(
			'post_type'      => 'book',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	$book_choices = array( '' => __( 'None (use featured meta field)', 'tsm-theme' ) );
	foreach ( $books as $book ) {
		$book_choices[ $book->ID ] = $book->post_title;
	}

	$wp_customize->add_control(
		'books_featured_book',
		array(
			'label'       => __( 'Featured Book', 'tsm-theme' ),
			'description' => __( 'Select a book to feature in the hero section. If "None" is selected, the system will use the book marked as featured in the book editor.', 'tsm-theme' ),
			'section'     => 'tsm_books_archive',
			'type'        => 'select',
			'choices'     => $book_choices,
		)
	);

	// Newsletter Title
	$wp_customize->add_setting(
		'books_newsletter_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'books_newsletter_title',
		array(
			'label'   => __( 'Newsletter Title', 'tsm-theme' ),
			'section' => 'tsm_books_archive',
			'type'    => 'text',
		)
	);

	// Newsletter Description
	$wp_customize->add_setting(
		'books_newsletter_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'books_newsletter_description',
		array(
			'label'   => __( 'Newsletter Description', 'tsm-theme' ),
			'section' => 'tsm_books_archive',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_books_archive' );
