<?php
/**
 * Missions Page Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Missions Page Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_missions_page( $wp_customize ) {
	// Missions Page Section
	$wp_customize->add_section(
		'tsm_missions_page',
		array(
			'title'    => __( 'Missions Page Settings', 'tsm-theme' ),
			'priority' => 36,
		)
	);

	// Hero Section
	$wp_customize->add_setting(
		'missions_hero_image',
		array(
			'default'           => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCtzCEOTB5-TTBzqPY_TtOLn84ppGCAsXToDqXryoylyXhh9r7rNppAPxaYuniv-309Yg72GDc84WVHhQT-7wypZo-qBUU8tLNeGEiUTqVbFcDc41BrWYsZfUA7WjLpNm_MlKFfBk_Nlatn0-pZa6UpNm26Zn_BorXkuDMEGVnE3_xQNV68UJQS1CmG8g74VfV9A6W54YeYr6-mzilsHlPrgXUfsVRGk8AyR4YxGkX4pt1HFxgQIWhWVAJFqQ79L9553-nOUtRgrpg',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'missions_hero_image',
			array(
				'label'   => __( 'Hero Background Image', 'tsm-theme' ),
				'section' => 'tsm_missions_page',
			)
		)
	);

	$wp_customize->add_setting(
		'missions_hero_alt',
		array(
			'default'           => 'Wide shot of a diverse mission team smiling together in a rural village',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_hero_alt',
		array(
			'label'   => __( 'Hero Image Alt Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_badge',
		array(
			'default'           => 'Our Global Outreach',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_badge',
		array(
			'label'   => __( 'Hero Badge Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_hero_heading',
		array(
			'default'           => '"Our heart is to see every nation touched by the love of Christ."',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'missions_hero_heading',
		array(
			'label'   => __( 'Hero Heading', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'missions_hero_description',
		array(
			'default'           => 'David & Sarah Graham\'s vision for global transformation through mission, prayer, and sustainable community empowerment.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'missions_hero_description',
		array(
			'label'   => __( 'Hero Description', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'missions_vision_button',
		array(
			'default'           => 'Learn Our Vision',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_vision_button',
		array(
			'label'   => __( 'Vision Button Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_vision_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'missions_vision_url',
		array(
			'label'   => __( 'Vision Button URL', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'missions_film_button',
		array(
			'default'           => 'Watch the Film',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_film_button',
		array(
			'label'   => __( 'Film Button Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_film_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'missions_film_url',
		array(
			'label'   => __( 'Film Button URL', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'url',
		)
	);

	// Stats Section
	$wp_customize->add_setting(
		'missions_stat_1_label',
		array(
			'default'           => 'Countries Reached',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_stat_1_label',
		array(
			'label'   => __( 'Stat 1 Label', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_stat_1_value',
		array(
			'default'           => '25+',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_stat_1_value',
		array(
			'label'   => __( 'Stat 1 Value', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_stat_2_label',
		array(
			'default'           => 'Lives Touched',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_stat_2_label',
		array(
			'label'   => __( 'Stat 2 Label', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_stat_2_value',
		array(
			'default'           => '10k+',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_stat_2_value',
		array(
			'label'   => __( 'Stat 2 Value', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_stat_3_label',
		array(
			'default'           => 'Mission Partners',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_stat_3_label',
		array(
			'label'   => __( 'Stat 3 Label', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_stat_3_value',
		array(
			'default'           => '500+',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_stat_3_value',
		array(
			'label'   => __( 'Stat 3 Value', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	// Stories Section
	$wp_customize->add_setting(
		'missions_stories_title',
		array(
			'default'           => 'The Journey: Where We Go',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_stories_title',
		array(
			'label'   => __( 'Stories Section Title', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_stories',
		array(
			'default'           => 'water_drop|Kenya 2023|A Village Transformed|"Before DSG Ministries came, our daughters walked 4 miles for water every day. Today, they are in school, and the well is the center of our community."|This mission focused on sustainable infrastructure, providing clean water access to over 300 families while launching a local school empowerment program.|https://lh3.googleusercontent.com/aida-public/AB6AXuAeJd6ZkDYDEPo8ufn6Dq4z0O87kExD4WDiyK5T7H1Uhf75m9dRocuhb4TYr1NkSLB3_5JP4ZVyHDfIhgAECUPyy_RAQ8ntsoSeLCWYvmmJWT5t0eFRhXhwQwSOJyuQMRLj9UtjRoZ9PUkPbWn4nnZzAcqIA4mk0SThx7mcEFpE53Nd57nOVzyokcfgzbGt-6raI_Jg45gcQTnL8L8_AM8p1lWgfc6aIdfVF79BaO1Uw2gDibGtFxYqhHfhxhgNk1gcTw-JOl0lkis|apartment|Brazil 2023|Urban Hope in São Paulo|"In the concrete jungle, the light of Christ shines brightest. We saw hope restored in the eyes of the forgotten."|Supporting local shelters and street outreach programs, our team worked through the night to provide food, medical checkups, and spiritual counseling to the urban poor.|https://lh3.googleusercontent.com/aida-public/AB6AXuBo5Z_CIRCJC1wZ_dCtjrl5sLVHQeRPGIjEI_OYjjpImFxGl6v5dusUuN5ETXnFWZBco-cORF2IQfi4Z1hdFKJkUNUgRYaLuwlizzqo4nKRZovMsQBpbb5dgUxf1xjEgsNduv01o6FdSMD9SCI-7M2ccIHN14XNv8mBnzNO38LAh5tm7pE2Td9xMXO6NlWuu1EcQUiwgwnbsZ_UUOX61CCNyl4LgkH8-TLMZaEW9SPufnB3IxnPYbzNh61erDkvRzMWeMlZq44eASA|medical_services|Thailand 2024|Healing Hands Outreach|"Physical healing often opens the door to spiritual awakening. We are honored to be the hands of Jesus in these remote hills."|Our most recent medical mission brought dental and primary care to over 1,200 villagers in Northern Thailand, partnering with local churches for long-term care.|https://lh3.googleusercontent.com/aida-public/AB6AXuCGNOrcl7xehmsEN-MprKn8oaZeUAQXfpDhlfxhayXkm4_hXclHHSZraL6NN6pICQhAvryHiIFU5HIaz1zUVIK_BcLgkkGvSDibLTWs9MUaHHpIc4GBE14dR_rVhRny1T3A2wZLJi_C3hY8buyBYDK5nv7nqAwqFJV3XC0P7hTD1y6hh4yHFtwjU9xi90qHFrFgQsy_zgrhpPLFLPWk6oh3nlhPlp3b5tMMec4mqbSc4HyR_SMFzPC1FU_1nxC943Hpgouhhg3ncmo',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'missions_stories',
		array(
			'label'       => __( 'Mission Stories', 'tsm-theme' ),
			'description' => __( 'Format: icon|Location Year|Title|Quote|Description|Image URL. Separate multiple stories with |. Example: water_drop|Kenya 2023|A Village Transformed|"Quote"|Description|image_url', 'tsm-theme' ),
			'section'     => 'tsm_missions_page',
			'type'        => 'textarea',
		)
	);

	// Sidebar Section
	$wp_customize->add_setting(
		'missions_sidebar_title',
		array(
			'default'           => 'Partner with Us',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_sidebar_title',
		array(
			'label'   => __( 'Sidebar Title', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_sidebar_subtitle',
		array(
			'default'           => 'Transform lives together',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_sidebar_subtitle',
		array(
			'label'   => __( 'Sidebar Subtitle', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	// Action 1: Pray
	$wp_customize->add_setting(
		'missions_action_1_title',
		array(
			'default'           => 'Pray with Us',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_1_title',
		array(
			'label'   => __( 'Action 1 Title (Pray)', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_action_1_description',
		array(
			'default'           => 'Receive weekly prayer points from the field.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_1_description',
		array(
			'label'   => __( 'Action 1 Description', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'missions_action_1_button',
		array(
			'default'           => 'Join Prayer Team',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_1_button',
		array(
			'label'   => __( 'Action 1 Button Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_action_1_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'missions_action_1_url',
		array(
			'label'   => __( 'Action 1 URL', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'url',
		)
	);

	// Action 2: Give
	$wp_customize->add_setting(
		'missions_action_2_title',
		array(
			'default'           => 'Give Generously',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_2_title',
		array(
			'label'   => __( 'Action 2 Title (Give)', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_action_2_description',
		array(
			'default'           => '100% of your gift goes directly to mission projects.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_2_description',
		array(
			'label'   => __( 'Action 2 Description', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'missions_action_2_button',
		array(
			'default'           => 'Donate Now',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_2_button',
		array(
			'label'   => __( 'Action 2 Button Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_action_2_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'missions_action_2_url',
		array(
			'label'   => __( 'Action 2 URL', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'url',
		)
	);

	// Action 3: Go
	$wp_customize->add_setting(
		'missions_action_3_title',
		array(
			'default'           => 'Join a Mission',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_3_title',
		array(
			'label'   => __( 'Action 3 Title (Go)', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_action_3_description',
		array(
			'default'           => 'Applications for 2025 summer trips are now open.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_3_description',
		array(
			'label'   => __( 'Action 3 Description', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'missions_action_3_button',
		array(
			'default'           => 'Apply to Join',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_action_3_button',
		array(
			'label'   => __( 'Action 3 Button Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_action_3_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'missions_action_3_url',
		array(
			'label'   => __( 'Action 3 URL', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'url',
		)
	);

	// Upcoming Trips
	$wp_customize->add_setting(
		'missions_trips_label',
		array(
			'default'           => 'Upcoming Trips',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_trips_label',
		array(
			'label'   => __( 'Trips Section Label', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_upcoming_trips',
		array(
			'default'           => 'Guatemala|July 2025|Vietnam|Oct 2025|South Africa|Dec 2025',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'missions_upcoming_trips',
		array(
			'label'       => __( 'Upcoming Trips', 'tsm-theme' ),
			'description' => __( 'Format: Location|Date. Separate multiple trips with |. Example: Guatemala|July 2025|Vietnam|Oct 2025', 'tsm-theme' ),
			'section'     => 'tsm_missions_page',
			'type'        => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'missions_trips_button',
		array(
			'default'           => 'View All Trips',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_trips_button',
		array(
			'label'   => __( 'Trips Button Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'missions_trips_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'missions_trips_url',
		array(
			'label'   => __( 'Trips Button URL', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'url',
		)
	);

	// Testimonial
	$wp_customize->add_setting(
		'missions_testimonial_text',
		array(
			'default'           => '"I thought I was going to change the world, but the people of Kenya changed me forever. My perspective on what truly matters has been completely shifted."',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'missions_testimonial_text',
		array(
			'label'   => __( 'Testimonial Text', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'missions_testimonial_author',
		array(
			'default'           => 'Sarah J., 2023 Team Member',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'missions_testimonial_author',
		array(
			'label'   => __( 'Testimonial Author', 'tsm-theme' ),
			'section' => 'tsm_missions_page',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_missions_page' );
