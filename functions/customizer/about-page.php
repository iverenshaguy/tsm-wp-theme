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
			'default'           => 'Terry Shaguy',
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
			'default'           => 'The Life & Ministry Of',
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
			'default'           => '"True leadership is the ability to hear God\'s whisper amidst the world\'s roar."',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'about_quote',
		array(
			'label'   => __( 'Quote', 'tsm-theme' ),
			'section' => 'tsm_about_page',
			'type'    => 'textarea',
		)
	);

	// Fast Facts Title
	$wp_customize->add_setting(
		'about_fast_facts_title',
		array(
			'default'           => 'Fast Facts',
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
			'default'           => "school|Ph.D. in Global Theology\npublic|20+ Years Global Field Experience\nedit_note|Author of 8 Best-selling Titles\nlocation_on|Based in Nashville, Tennessee",
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
			'default'           => 'His Calling',
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
			'default'           => '<p>Dr. Terry Shaguy\'s journey began with a distinct sense of purpose in the small rural communities of the Appalachian foothills. From an early age, Terry felt a profound pull toward the intersection of faith and global humanitarian needs. This wasn\'t merely a vocational choice but a transformative calling that has led him into some of the world\'s most remote corners.</p><p>His ministry is defined by a relentless pursuit of depth. Whether teaching in a crowded urban center or mentoring leaders in a quiet village, Terry\'s approach remains rooted in the belief that spiritual growth is the catalyst for all lasting societal change.</p>',
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
			'default'           => '"We do not travel to the ends of the earth to bring God there; we travel to discover where He is already working and join Him in that harvest."',
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
			'default'           => 'Academic Background',
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
			'default'           => '<p>Recognizing that zeal must be tempered with wisdom, Terry pursued a rigorous academic path. He holds a Master\'s of Divinity and a Ph.D. in Global Theology from the Trinity Evangelical Divinity School. His research focused on the indigenous expressions of faith in the Global South, a subject he continues to write and lecture on extensively.</p><p>Today, he serves as a visiting professor at several seminaries globally, helping to bridge the gap between traditional theological study and practical mission-field application.</p>',
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
			'default'           => 'Personal Life',
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
			'default'           => '<p>Beyond the pulpit and the lecture hall, Terry is a devoted husband to Debbie and a father of three. He often credits his family as his greatest grounding force. Terry is an avid hiker and can often be found exploring the trails of the Smoky Mountains when he is not on international assignment.</p><p>Terry and Debbie\'s partnership in ministry is a cornerstone of their work, demonstrating a model of shared leadership and mutual respect that they bring to every conference and mission they lead together.</p>',
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
			'default'           => 'Resources',
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
			'default'           => 'Books by Terry',
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
			'default'           => 'Deepen your study with these selected works focusing on spiritual growth, global missions, and leadership.',
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
			'default'           => 'Invite Terry to Your Event',
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
			'default'           => 'Dr. Terry Shaguy is available for keynote speaking, leadership seminars, and theological training worldwide.',
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
