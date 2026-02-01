<?php
/**
 * Articles Archive Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Articles Archive Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_articles_archive( $wp_customize ) {
	// Articles Archive Section
	$wp_customize->add_section(
		'tsm_articles_archive',
		array(
			'title'    => __( 'Articles Archive Settings', 'tsm-theme' ),
			'priority' => 45,
		)
	);

	// Show Subscribe Form
	$wp_customize->add_setting(
		'articles_show_subscribe_form',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'articles_show_subscribe_form',
		array(
			'label'       => __( 'Show Subscribe Form', 'tsm-theme' ),
			'description' => __( 'Display the newsletter subscription form in the articles archive sidebar.', 'tsm-theme' ),
			'section'     => 'tsm_articles_archive',
			'type'        => 'checkbox',
		)
	);

	// Newsletter Form ID (for WPForms integration)
	$wp_customize->add_setting(
		'articles_newsletter_form_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'articles_newsletter_form_id',
		array(
			'label'       => __( 'Newsletter Form ID', 'tsm-theme' ),
			'description' => __( 'Enter the WPForms form ID for newsletter subscriptions. Leave empty to use the default form.', 'tsm-theme' ),
			'section'     => 'tsm_articles_archive',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
			),
		)
	);

	// Newsletter Title
	$wp_customize->add_setting(
		'articles_newsletter_title',
		array(
			'default'           => 'Weekly Resources',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'articles_newsletter_title',
		array(
			'label'   => __( 'Newsletter Title', 'tsm-theme' ),
			'section' => 'tsm_articles_archive',
			'type'    => 'text',
		)
	);

	// Newsletter Description
	$wp_customize->add_setting(
		'articles_newsletter_description',
		array(
			'default'           => 'Join 5,000+ others receiving weekly encouragement and articles directly in their inbox.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'articles_newsletter_description',
		array(
			'label'   => __( 'Newsletter Description', 'tsm-theme' ),
			'section' => 'tsm_articles_archive',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_articles_archive' );
