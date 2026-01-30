<?php
/**
 * About Page Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register About Page Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_about_page( $wp_customize ) {
	// About Page Section
	$wp_customize->add_section(
		'tsm_about_page',
		array(
			'title'    => __( 'About Page Settings', 'tsm-theme' ),
			'priority' => 35,
		)
	);

	// About Page Image
	$wp_customize->add_setting(
		'about_page_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/about.png',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'about_page_image',
			array(
				'label'   => __( 'About Page Image', 'tsm-theme' ),
				'section' => 'tsm_about_page',
			)
		)
	);

	// About Page Name
	$wp_customize->add_setting(
		'about_page_name',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_page_name',
		array(
			'label'   => __( 'Name', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// About Page Subtitle
	$wp_customize->add_setting(
		'about_page_subtitle',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_page_subtitle',
		array(
			'label'   => __( 'Subtitle', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Quote Label
	$wp_customize->add_setting(
		'about_quote_label',
		array(
			'default'           => 'Core Philosophy',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_quote_label',
		array(
			'label'   => __( 'Quote Label', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Quote
	$wp_customize->add_setting(
		'about_quote',
		array(
			'default'           => '"The Gospel, lived with depth and expressed through love, stewardship, and shared responsibility."',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'about_quote',
		array(
			'label'       => __( 'Quote', 'tsm-theme' ),
			'description' => __( 'Quote text displayed in the overlay box on the about image. This quote appears on both the About page and the front page About section.', 'tsm-theme' ),
			'section'     => 'tsm_about_page',
			'type'        => 'textarea',
		)
	);

	// Fast Facts Title
	$wp_customize->add_setting(
		'about_fast_facts_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_fast_facts_title',
		array(
			'label'   => __( 'Fast Facts Title', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Fast Facts
	$wp_customize->add_setting(
		'about_fast_facts',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'about_fast_facts',
		array(
			'label'       => __( 'Fast Facts', 'tsm-theme' ),
			'description' => __( 'Enter one fact per line. Format: icon_name|Fact text (e.g., school|Ph.D. in Global Theology)', 'tsm-theme' ),
			'section'     => 'tsm_about_page',
			'type'        => 'textarea',
		)
	);

	// Section 1 Title
	$wp_customize->add_setting(
		'about_section_1_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_section_1_title',
		array(
			'label'   => __( 'Section 1 Title', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Section 1 Content
	$wp_customize->add_setting(
		'about_section_1_content',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'about_section_1_content',
		array(
			'label'       => __( 'Section 1 Content', 'tsm-theme' ),
			'description' => __( 'Enter content using &lt;p&gt; tags for paragraphs. HTML formatting like &lt;strong&gt; is allowed.', 'tsm-theme' ),
			'section'     => 'tsm_about_page',
			'type'        => 'textarea',
		)
	);

	// Highlight Quote
	$wp_customize->add_setting(
		'about_highlight_quote',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'about_highlight_quote',
		array(
			'label'   => __( 'Highlight Quote', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'textarea',
		)
	);

	// Section 2 Title
	$wp_customize->add_setting(
		'about_section_2_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_section_2_title',
		array(
			'label'   => __( 'Section 2 Title', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Section 2 Content
	$wp_customize->add_setting(
		'about_section_2_content',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'about_section_2_content',
		array(
			'label'       => __( 'Section 2 Content', 'tsm-theme' ),
			'description' => __( 'Enter content using &lt;p&gt; tags for paragraphs. HTML formatting like &lt;strong&gt; is allowed.', 'tsm-theme' ),
			'section'     => 'tsm_about_page',
			'type'        => 'textarea',
		)
	);

	// Section 3 Title
	$wp_customize->add_setting(
		'about_section_3_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_section_3_title',
		array(
			'label'   => __( 'Section 3 Title', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Section 3 Content
	$wp_customize->add_setting(
		'about_section_3_content',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'about_section_3_content',
		array(
			'label'       => __( 'Section 3 Content', 'tsm-theme' ),
			'description' => __( 'Enter content using &lt;p&gt; tags for paragraphs. HTML formatting like &lt;strong&gt; is allowed.', 'tsm-theme' ),
			'section'     => 'tsm_about_page',
			'type'        => 'textarea',
		)
	);

	// Books Badge
	$wp_customize->add_setting(
		'about_books_badge',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_books_badge',
		array(
			'label'   => __( 'Books Section Badge', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Books Title
	$wp_customize->add_setting(
		'about_books_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_books_title',
		array(
			'label'   => __( 'Books Section Title', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// Books Description
	$wp_customize->add_setting(
		'about_books_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'about_books_description',
		array(
			'label'   => __( 'Books Section Description', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'textarea',
		)
	);

	// Books Page ID
	$wp_customize->add_setting(
		'books_page_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'books_page_id',
		array(
			'label'       => __( 'Books Page', 'tsm-theme' ),
			'description' => __( 'Select the page for the "View Entire Catalog" link.', 'tsm-theme' ),
			'section'     => 'tsm_about_page',
			'type'        => 'dropdown-pages',
		)
	);

	// CTA Title
	$wp_customize->add_setting(
		'about_cta_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_cta_title',
		array(
			'label'   => __( 'CTA Section Title', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'text',
		)
	);

	// CTA Description
	$wp_customize->add_setting(
		'about_cta_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'about_cta_description',
		array(
			'label'   => __( 'CTA Section Description', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'textarea',
		)
	);

	// CTA Background Image
	$wp_customize->add_setting(
		'about_cta_bg_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'about_cta_bg_image',
			array(
				'label'   => __( 'CTA Background Image', 'tsm-theme' ),
				'section' => 'tsm_about_page',
			)
		)
	);

	// Speakers Kit URL
	$wp_customize->add_setting(
		'speakers_kit_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'speakers_kit_url',
		array(
			'label'       => __( 'Speaker\'s Kit URL', 'tsm-theme' ),
			'description' => __( 'URL to the speaker\'s kit PDF file.', 'tsm-theme' ),
			'section'     => 'tsm_about_page',
			'type'        => 'url',
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_about_page' );
