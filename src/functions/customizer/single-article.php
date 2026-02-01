<?php
/**
 * Single Article Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Single Article Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_single_article( $wp_customize ) {
	// Single Article Section
	$wp_customize->add_section(
		'tsm_single_article',
		array(
			'title'    => __( 'Single Article Settings', 'tsm-theme' ),
			'priority' => 46,
		)
	);

	// Show Subscribe Form
	$wp_customize->add_setting(
		'single_article_show_subscribe_form',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'single_article_show_subscribe_form',
		array(
			'label'       => __( 'Show Subscribe Form', 'tsm-theme' ),
			'description' => __( 'Display the newsletter subscription form in the single article sidebar.', 'tsm-theme' ),
			'section'     => 'tsm_single_article',
			'type'        => 'checkbox',
		)
	);

	// Newsletter Form ID (for WPForms integration)
	$wp_customize->add_setting(
		'single_article_newsletter_form_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'single_article_newsletter_form_id',
		array(
			'label'       => __( 'Newsletter Form ID', 'tsm-theme' ),
			'description' => __( 'Enter the WPForms form ID for newsletter subscriptions. Leave empty to use the default form.', 'tsm-theme' ),
			'section'     => 'tsm_single_article',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
			),
		)
	);

	// Newsletter Title
	$wp_customize->add_setting(
		'single_article_newsletter_title',
		array(
			'default'           => 'Subscribe to Devotions',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'single_article_newsletter_title',
		array(
			'label'   => __( 'Newsletter Title', 'tsm-theme' ),
			'section' => 'tsm_single_article',
			'type'    => 'text',
		)
	);

	// Newsletter Description
	$wp_customize->add_setting(
		'single_article_newsletter_description',
		array(
			'default'           => 'Join 12,000+ others receiving daily wisdom and spiritual encouragement in their inbox every morning.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'single_article_newsletter_description',
		array(
			'label'   => __( 'Newsletter Description', 'tsm-theme' ),
			'section' => 'tsm_single_article',
			'type'    => 'textarea',
		)
	);

	// Default Author Name
	$wp_customize->add_setting(
		'single_article_default_author',
		array(
			'default'           => 'Dr. Tor Terry Shaguy',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'single_article_default_author',
		array(
			'label'       => __( 'Default Author Name', 'tsm-theme' ),
			'description' => __( 'Default author name to display when post author is not set.', 'tsm-theme' ),
			'section'     => 'tsm_single_article',
			'type'        => 'text',
		)
	);

	// Default Author Picture
	$wp_customize->add_setting(
		'single_article_default_author_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'single_article_default_author_image',
			array(
				'label'       => __( 'Default Author Picture', 'tsm-theme' ),
				'description' => __( 'Upload a default author picture to display when post author avatar is not available.', 'tsm-theme' ),
				'section'     => 'tsm_single_article',
				'settings'    => 'single_article_default_author_image',
			)
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_single_article' );

