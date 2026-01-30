<?php
/**
 * Our Ministries Page Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Our Ministries Page Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_ministries_page( $wp_customize ) {
	// Our Ministries Page Section
	$wp_customize->add_section(
		'tsm_ministries_page',
		array(
			'title'    => __( 'Our Ministries Page Settings', 'tsm-theme' ),
			'priority' => 36,
		)
	);

	// Badge
	$wp_customize->add_setting(
		'ministries_badge',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_badge',
		array(
			'label'   => __( 'Hero Badge', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Vision Quote
	$wp_customize->add_setting(
		'ministries_vision',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'ministries_vision',
		array(
			'label'       => __( 'Vision Quote', 'tsm-theme' ),
			'description' => __( 'Main vision statement. HTML is allowed.', 'tsm-theme' ),
			'section'     => 'tsm_ministries_page',
			'type'        => 'textarea',
		)
	);

	// Motto Label
	$wp_customize->add_setting(
		'ministries_motto_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_motto_label',
		array(
			'label'   => __( 'Motto Label', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Motto Text
	$wp_customize->add_setting(
		'ministries_motto_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_motto_text',
		array(
			'label'   => __( 'Motto Text', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Mission Title
	$wp_customize->add_setting(
		'ministries_mission_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_mission_title',
		array(
			'label'   => __( 'Mission Title', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Mission Description
	$wp_customize->add_setting(
		'ministries_mission_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministries_mission_description',
		array(
			'label'   => __( 'Mission Description', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'textarea',
		)
	);

	// Pillars
	$wp_customize->add_setting(
		'ministries_pillars',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministries_pillars',
		array(
			'label'       => __( 'Ministry Pillars', 'tsm-theme' ),
			'description' => __( 'Format: icon|Title|Description| (repeat for each pillar). Separate pillars with |', 'tsm-theme' ),
			'section'     => 'tsm_ministries_page',
			'type'        => 'textarea',
		)
	);

	// Ministry Image
	$wp_customize->add_setting(
		'ministries_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/ministry-work.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'ministries_image',
			array(
				'label'   => __( 'Ministry Image', 'tsm-theme' ),
				'section' => 'tsm_ministries_page',
			)
		)
	);

	// Quote
	$wp_customize->add_setting(
		'ministries_quote',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministries_quote',
		array(
			'label'   => __( 'Quote', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'textarea',
		)
	);

	// Timeline Title
	$wp_customize->add_setting(
		'ministries_timeline_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_timeline_title',
		array(
			'label'   => __( 'Timeline Title', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Timeline Subtitle
	$wp_customize->add_setting(
		'ministries_timeline_subtitle',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_timeline_subtitle',
		array(
			'label'   => __( 'Timeline Subtitle', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Timeline Items
	$wp_customize->add_setting(
		'ministries_timeline_items',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministries_timeline_items',
		array(
			'label'       => __( 'Timeline Items', 'tsm-theme' ),
			'description' => __( 'Format: icon|Title|Description|alignment| (repeat). Alignment: left or right', 'tsm-theme' ),
			'section'     => 'tsm_ministries_page',
			'type'        => 'textarea',
		)
	);

	// Resources Title
	$wp_customize->add_setting(
		'ministries_resources_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_resources_title',
		array(
			'label'   => __( 'Resources Title', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Resource Image 1
	$wp_customize->add_setting(
		'ministries_resource_image_1',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/book-1.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'ministries_resource_image_1',
			array(
				'label'   => __( 'Resource Image 1', 'tsm-theme' ),
				'section' => 'tsm_ministries_page',
			)
		)
	);

	// Resource Image 2
	$wp_customize->add_setting(
		'ministries_resource_image_2',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/book-2.jpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'ministries_resource_image_2',
			array(
				'label'   => __( 'Resource Image 2', 'tsm-theme' ),
				'section' => 'tsm_ministries_page',
			)
		)
	);

	// Resource Items
	$wp_customize->add_setting(
		'ministries_resource_items',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministries_resource_items',
		array(
			'label'       => __( 'Resource Items', 'tsm-theme' ),
			'description' => __( 'Format: icon|Text| (repeat for each item)', 'tsm-theme' ),
			'section'     => 'tsm_ministries_page',
			'type'        => 'textarea',
		)
	);

	// Bible Label
	$wp_customize->add_setting(
		'ministries_bible_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_bible_label',
		array(
			'label'   => __( 'Bible Section Label', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Bible Title
	$wp_customize->add_setting(
		'ministries_bible_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_bible_title',
		array(
			'label'   => __( 'Bible Title', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// Bible Description
	$wp_customize->add_setting(
		'ministries_bible_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministries_bible_description',
		array(
			'label'   => __( 'Bible Description', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'textarea',
		)
	);

	// CTA Title
	$wp_customize->add_setting(
		'ministries_cta_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ministries_cta_title',
		array(
			'label'   => __( 'CTA Title', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'text',
		)
	);

	// CTA Description
	$wp_customize->add_setting(
		'ministries_cta_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministries_cta_description',
		array(
			'label'   => __( 'CTA Description', 'tsm-theme' ),
			'section' => 'tsm_ministries_page',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_ministries_page' );
