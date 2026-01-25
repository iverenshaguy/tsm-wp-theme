<?php
/**
 * Footer Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Footer Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function tsm_customize_register_footer( $wp_customize ) {
	// Footer Section
	$wp_customize->add_section(
		'tsm_footer',
		array(
			'title'    => __( 'Footer Settings', 'tsm-theme' ),
			'priority' => 40,
		)
	);

	// Footer Name
	$wp_customize->add_setting(
		'footer_name',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'footer_name',
		array(
			'label'   => __( 'Footer Name', 'tsm-theme' ),
			'section' => 'tsm_footer',
			'type'    => 'text',
		)
	);

	// Footer Description
	$wp_customize->add_setting(
		'footer_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'footer_description',
		array(
			'label'   => __( 'Footer Description', 'tsm-theme' ),
			'section' => 'tsm_footer',
			'type'    => 'textarea',
		)
	);

	// Social Links
	$wp_customize->add_setting(
		'social_facebook',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'social_facebook',
		array(
			'label'   => __( 'Facebook URL', 'tsm-theme' ),
			'section' => 'tsm_footer',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'social_instagram',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'social_instagram',
		array(
			'label'   => __( 'Instagram URL', 'tsm-theme' ),
			'section' => 'tsm_footer',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'social_twitter',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'social_twitter',
		array(
			'label'   => __( 'Twitter URL', 'tsm-theme' ),
			'section' => 'tsm_footer',
			'type'    => 'url',
		)
	);

	// Footer Logo
	$wp_customize->add_setting(
		'footer_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'footer_logo',
			array(
				'label'       => __( 'Footer Logo', 'tsm-theme' ),
				'description' => __( 'Upload a custom logo for the footer. If not set, the main site logo will be used.', 'tsm-theme' ),
				'section'     => 'tsm_footer',
			)
		)
	);

	// Ministry Focus
	$wp_customize->add_setting(
		'ministry_focus',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'ministry_focus',
		array(
			'label'       => __( 'Ministry Focus Items', 'tsm-theme' ),
			'description' => __( 'Enter one item per line. These will be displayed as bullet points in the footer.', 'tsm-theme' ),
			'section'     => 'tsm_footer',
			'type'        => 'textarea',
		)
	);

	// Quick Links
	for ( $i = 1; $i <= 4; $i++ ) {
		// Quick Link Title
		$wp_customize->add_setting(
			'quick_link_' . $i . '_title',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'quick_link_' . $i . '_title',
			array(
				'label'   => sprintf( __( 'Quick Link %d - Title', 'tsm-theme' ), $i ),
				'section' => 'tsm_footer',
				'type'    => 'text',
			)
		);

		// Quick Link URL
		$wp_customize->add_setting(
			'quick_link_' . $i . '_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'quick_link_' . $i . '_url',
			array(
				'label'       => sprintf( __( 'Quick Link %d - URL', 'tsm-theme' ), $i ),
				'description' => __( 'Enter a relative URL (e.g., /about) or full URL. Use # for anchor links.', 'tsm-theme' ),
				'section'     => 'tsm_footer',
				'type'        => 'url',
			)
		);
	}

	// Contact Information
	$wp_customize->add_setting(
		'contact_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'contact_email',
		array(
			'label'   => __( 'Contact Email', 'tsm-theme' ),
			'section' => 'tsm_footer',
			'type'    => 'email',
		)
	);

	$wp_customize->add_setting(
		'contact_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'contact_phone',
		array(
			'label'   => __( 'Contact Phone', 'tsm-theme' ),
			'section' => 'tsm_footer',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'contact_address',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'contact_address',
		array(
			'label'       => __( 'Contact Address', 'tsm-theme' ),
			'description' => __( 'Physical office address. HTML is allowed (e.g., &lt;br/&gt; for line breaks).', 'tsm-theme' ),
			'section'     => 'tsm_footer',
			'type'        => 'textarea',
		)
	);
}
add_action( 'customize_register', 'tsm_customize_register_footer' );
