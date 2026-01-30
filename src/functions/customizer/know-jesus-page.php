<?php
/**
 * How to Know Jesus Page Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register How to Know Jesus Page Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_know_jesus_page( $wp_customize ) {
	// How to Know Jesus Page Section
	$wp_customize->add_section(
		'tsm_know_jesus_page',
		array(
			'title'    => __( 'How to Know Jesus Page Settings', 'tsm-theme' ),
			'priority' => 40,
		)
	);

	// Hero Section
	$wp_customize->add_setting(
		'know_jesus_hero_label',
		array(
			'default'           => 'Your Spiritual Journey',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_hero_label',
		array(
			'label'   => __( 'Hero Label', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_hero_title',
		array(
			'default'           => 'The Greatest Love Story Ever Told',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_hero_title',
		array(
			'label'   => __( 'Hero Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_hero_description',
		array(
			'default'           => 'Whether you\'re searching for meaning, peace, or a fresh start, the message of the Gospel is for you. Discover the path to a personal relationship with Jesus Christ.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_hero_description',
		array(
			'label'   => __( 'Hero Description', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'know_jesus_hero_image',
			array(
				'label'   => __( 'Hero Image', 'tsm-theme' ),
				'section' => 'tsm_know_jesus_page',
			)
		)
	);

	// Problem Section
	$wp_customize->add_setting(
		'know_jesus_problem_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'know_jesus_problem_image',
			array(
				'label'   => __( 'Problem Section Image', 'tsm-theme' ),
				'section' => 'tsm_know_jesus_page',
			)
		)
	);

	$wp_customize->add_setting(
		'know_jesus_problem_title',
		array(
			'default'           => 'The Problem: We are Separated from God',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_problem_title',
		array(
			'label'   => __( 'Problem Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_problem_text_1',
		array(
			'default'           => 'God created us to be in a perfect relationship with Him. However, we chose our own way. This independent streak is what the Bible calls "sin."',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_problem_text_1',
		array(
			'label'   => __( 'Problem Text (First Paragraph)', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_problem_verse_1',
		array(
			'default'           => '"For all have sinned and fall short of the glory of God." — Romans 3:23',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_problem_verse_1',
		array(
			'label'   => __( 'Problem Verse 1', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_problem_verse_2',
		array(
			'default'           => '"Neither is there salvation in any other; for there is none other name under heaven given among men, whereby we must be saved." — Acts 4:12',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_problem_verse_2',
		array(
			'label'   => __( 'Problem Verse 2', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_problem_text_2',
		array(
			'default'           => 'This separation creates a void in our hearts—a gap that we often try to fill with success, relationships, or even religion, but nothing seems to bridge the divide.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_problem_text_2',
		array(
			'label'   => __( 'Problem Text (Second Paragraph)', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	// Solution Section
	$wp_customize->add_setting(
		'know_jesus_solution_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'know_jesus_solution_image',
			array(
				'label'       => __( 'Solution Section Image (Grace is a Gift)', 'tsm-theme' ),
				'description' => __( 'The image displayed with the "Grace is a Gift" overlay text.', 'tsm-theme' ),
				'section'     => 'tsm_know_jesus_page',
			)
		)
	);

	$wp_customize->add_setting(
		'know_jesus_solution_image_text',
		array(
			'default'           => 'Grace is a Gift',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_solution_image_text',
		array(
			'label'   => __( 'Solution Image Overlay Text', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_solution_title',
		array(
			'default'           => 'The Solution: Jesus is the Only Way',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_solution_title',
		array(
			'label'   => __( 'Solution Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_solution_text_1',
		array(
			'default'           => 'Jesus Christ is God\'s only provision for our sin. Through His death on the cross and His resurrection, He paid the penalty for our sins and bridged the gap between us and God.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_solution_text_1',
		array(
			'label'   => __( 'Solution Text (First Paragraph)', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_solution_verse_1',
		array(
			'default'           => '"I am the way, the truth, and the life. No one comes to the Father except through Me." — John 14:6',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_solution_verse_1',
		array(
			'label'   => __( 'Solution Verse 1', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_solution_verse_2',
		array(
			'default'           => '"That if thou shalt confess with thy mouth the Lord Jesus, and shalt believe in thine heart that God hath raised him from the dead, thou shalt be saved." — Romans 10:9',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_solution_verse_2',
		array(
			'label'   => __( 'Solution Verse 2', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_solution_text_2',
		array(
			'default'           => 'It isn\'t about what we can do to reach God, but what God has already done to reach us.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_solution_text_2',
		array(
			'label'   => __( 'Solution Text (Second Paragraph)', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	// Choice Section
	$wp_customize->add_setting(
		'know_jesus_choice_title',
		array(
			'default'           => 'Your Response: Will You Receive Him?',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_choice_title',
		array(
			'label'   => __( 'Choice Section Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_choice_description',
		array(
			'default'           => 'Knowing these truths is not enough. We must individually receive Jesus Christ as Savior and Lord; then we can know and experience God\'s love and plan for our lives.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_choice_description',
		array(
			'label'   => __( 'Choice Section Description', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	// Three Steps
	$wp_customize->add_setting(
		'know_jesus_believe_title',
		array(
			'default'           => 'Believe',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_believe_title',
		array(
			'label'   => __( 'Believe Card Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_believe_text',
		array(
			'default'           => 'Believe that Jesus is the Son of God and that He died and rose again for you.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_believe_text',
		array(
			'label'   => __( 'Believe Card Text', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_repent_title',
		array(
			'default'           => 'Repent',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_repent_title',
		array(
			'label'   => __( 'Repent Card Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_repent_text',
		array(
			'default'           => 'Turn from your own way and decide to follow God\'s direction for your life.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_repent_text',
		array(
			'label'   => __( 'Repent Card Text', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_receive_title',
		array(
			'default'           => 'Receive',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_receive_title',
		array(
			'label'   => __( 'Receive Card Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_receive_text',
		array(
			'default'           => 'Invite Jesus to enter your heart and life as your personal Savior.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_receive_text',
		array(
			'label'   => __( 'Receive Card Text', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	// Prayer Section
	$wp_customize->add_setting(
		'know_jesus_prayer_title',
		array(
			'default'           => 'A Suggested Prayer',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_prayer_title',
		array(
			'label'   => __( 'Prayer Section Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_prayer_text',
		array(
			'default'           => '"Lord Jesus, I need You. Thank You for dying on the cross for my sins. I open the door of my life and receive You as my Savior and Lord. Thank You for forgiving my sins and giving me eternal life. Take control of the throne of my life. Make me the kind of person You want me to be. Amen."',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_prayer_text',
		array(
			'label'   => __( 'Prayer Text', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_prayer_question',
		array(
			'default'           => 'Does this prayer express the desire of your heart?',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_prayer_question',
		array(
			'label'   => __( 'Prayer Question', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	// Form Section
	$wp_customize->add_setting(
		'know_jesus_form_title',
		array(
			'default'           => 'I Made a Decision Today',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_form_title',
		array(
			'label'   => __( 'Form Section Title', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_form_description',
		array(
			'default'           => 'If you just prayed that prayer or have questions about what it means to follow Jesus, we would love to hear from you. We want to send you some resources to help you start your new journey.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_form_description',
		array(
			'label'   => __( 'Form Section Description', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_form_benefit',
		array(
			'default'           => 'Download Free digital "Next Steps" guide',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'know_jesus_form_benefit',
		array(
			'label'   => __( 'Form Benefit', 'tsm-theme' ),
			'section' => 'tsm_know_jesus_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'know_jesus_form_download_file',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'know_jesus_form_download_file',
			array(
				'label'       => __( 'Next Steps Guide Download File', 'tsm-theme' ),
				'description' => __( 'Select or upload the PDF or file for download.', 'tsm-theme' ),
				'section'     => 'tsm_know_jesus_page',
				'mime_type'   => 'application/pdf,application/zip,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			)
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_know_jesus_page' );
