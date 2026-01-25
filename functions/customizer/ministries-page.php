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
			'default'           => 'The Heart of the Vision',
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
			'default'           => '"Breaking poverty through <span class="text-accent">wise enterprise</span> and <span class="text-accent font-bold not-italic">community spirit</span>."',
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
			'default'           => 'Our Daily Motto',
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
			'default'           => '"Win and Help Win"',
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
			'default'           => 'Our mission is to walk with you as we restore Kingdom wealth.',
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
			'default'           => 'We believe that financial freedom is not just about individuals, but about equipping the entire body of Christ for the final harvest.',
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
			'default'           => 'hub|Galvanizing the Church|Preparing the global body for the divine transfer of wealth through enterprise.|flare|Re-awakening Purpose|Raising a Joshua Generation focused on achieving Kingdom wealth for His glory.|handshake|Bridging the Gap|Restoring the essential family and community spirit that destroys systemic poverty.|shield|Equipping Every Saint|Providing the financial tools needed for the spiritual battle ahead.',
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
			'default'           => '"The church is rising to take her place in the economy of heaven."',
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
			'default'           => 'Our Journey Together',
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
			'default'           => 'How we practically bring the vision to life, step by step.',
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
			'default'           => 'payments|Wealth Creation Conferences|Intensive seminars sharing biblical strategies for generating and circulating wealth.|left|account_tree|REGAM Committees|Local support structures helping believers find stable, profitable employment.|right|volunteer_activism|"Helps" Ministry|Building financial foundations for fellow ministers to increase their reach.|left|model_training|Empowerment Workshops|Hands-on practical training specifically tailored for financial stewardship.|right|medical_services|Rural & Medical Outreaches|Healing bodies and sharing the Gospel in hard-to-reach communities.|left|psychology|Life & Career Coaching|One-on-one professional guidance for life development and career paths.|right|edit_note|Publishing Consultants|Mentoring authors and media creators to spread the Kingdom message.|left|support|Missions Supporters|A global network of financiers committed to funding gospel expansion.|right|local_library|Writers & Publishers|A community of storytellers dedicated to Christian media and books.|left|celebration|Soul Winning|The ultimate goal: leading every heart back to the Father.|right',
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
			'default'           => 'Equip your mind with truth.',
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
			'default'           => 'menu_book|Books & Publications|podcasts|Audio, Radio & TV Broadcasts',
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
			'default'           => 'Our Foundation',
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
			'default'           => 'The Holy Bible',
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
			'default'           => 'The primary source of all our wisdom, strategy, and inspiration for the work of the ministry.',
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
			'default'           => 'Ready to make a difference?',
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
			'default'           => 'Join the REGAM Global community and help us break the chains of poverty through faith and wise enterprise.',
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
