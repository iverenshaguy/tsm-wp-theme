<?php
/**
 * Contact Page Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Contact Page Customizer settings
 */
function tsm_contact_page_customizer( $wp_customize ) {
	// Contact Page Section
	$wp_customize->add_section(
		'tsm_contact_page',
		array(
			'title'    => __( 'Contact Page', 'tsm-theme' ),
			'priority' => 50,
		)
	);

	// Hero Badge
	$wp_customize->add_setting(
		'contact_hero_badge',
		array(
			'default'           => 'Get in Touch',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_hero_badge',
		array(
			'label'   => __( 'Hero Badge', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// Hero Title
	$wp_customize->add_setting(
		'contact_hero_title',
		array(
			'default'           => 'Connect With Us',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_hero_title',
		array(
			'label'   => __( 'Hero Title', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// Hero Description
	$wp_customize->add_setting(
		'contact_hero_description',
		array(
			'default'           => 'Whether you have a general question, a booking inquiry, or just want to share a testimony, we would love to hear from you.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'contact_hero_description',
		array(
			'label'   => __( 'Hero Description', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'textarea',
		)
	);

	// Form Title
	$wp_customize->add_setting(
		'contact_form_title',
		array(
			'default'           => 'Send a Message',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_form_title',
		array(
			'label'   => __( 'Form Title', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// Booking Badge
	$wp_customize->add_setting(
		'contact_booking_badge',
		array(
			'default'           => 'Invitations',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_booking_badge',
		array(
			'label'   => __( 'Booking Card Badge', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// Booking Title
	$wp_customize->add_setting(
		'contact_booking_title',
		array(
			'default'           => 'Booking Inquiries',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_booking_title',
		array(
			'label'   => __( 'Booking Card Title', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// Booking Description
	$wp_customize->add_setting(
		'contact_booking_description',
		array(
			'default'           => 'Interested in inviting David or Sarah to speak at your church, conference, or seminar? We would love to review your invitation.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'contact_booking_description',
		array(
			'label'   => __( 'Booking Card Description', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'textarea',
		)
	);

	// Booking Form URL
	$wp_customize->add_setting(
		'contact_booking_form_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'contact_booking_form_url',
		array(
			'label'   => __( 'Booking Form URL', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'url',
		)
	);

	// Contact Info Title
	$wp_customize->add_setting(
		'contact_info_title',
		array(
			'default'           => 'Contact Information',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_info_title',
		array(
			'label'   => __( 'Contact Info Title', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// FAQ Title
	$wp_customize->add_setting(
		'contact_faq_title',
		array(
			'default'           => 'Frequently Asked Questions',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_faq_title',
		array(
			'label'   => __( 'FAQ Title', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// FAQ Subtitle
	$wp_customize->add_setting(
		'contact_faq_subtitle',
		array(
			'default'           => 'Before reaching out, you might find your answer here.',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_faq_subtitle',
		array(
			'label'   => __( 'FAQ Subtitle', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);

	// FAQ Items (format: Question|Answer|Question|Answer...)
	$wp_customize->add_setting(
		'contact_faq_items',
		array(
			'default'           => 'How far in advance should we book?|We typically recommend booking 6-12 months in advance for international travel and 3-6 months for domestic events.|Do you travel individually?|While we prioritize joint ministry as a couple, we do accept individual speaking engagements based on the specific context and need.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'contact_faq_items',
		array(
			'label'       => __( 'FAQ Items', 'tsm-theme' ),
			'description' => __( 'Format: Question|Answer|Question|Answer (separate pairs with |)', 'tsm-theme' ),
			'section'     => 'tsm_contact_page',
			'type'        => 'textarea',
		)
	);

	// Contact Phone 2
	$wp_customize->add_setting(
		'contact_phone_2',
		array(
			'default'           => '+234 (708) 143-6641',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_phone_2',
		array(
			'label'   => __( 'Contact Phone 2', 'tsm-theme' ),
			'section' => 'tsm_contact_page',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'tsm_contact_page_customizer' );
