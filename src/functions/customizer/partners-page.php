<?php
/**
 * Partners Page Customizer Settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Partners Page Customizer settings
 */
function tsm_partners_page_customizer( $wp_customize ) {
	// Partners Page Section
	$wp_customize->add_section(
		'tsm_partners_page',
		array(
			'title'    => __( 'Partners Page', 'tsm-theme' ),
			'priority' => 60,
		)
	);

	// Partners Email
	$wp_customize->add_setting(
		'partners_email',
		array(
			'default'           => 'partners@terryshaguy.org',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'partners_email',
		array(
			'label'   => __( 'Partners Email', 'tsm-theme' ),
			'section' => 'tsm_partners_page',
			'type'    => 'email',
		)
	);

	// Ministry Account Details Section Title
	$wp_customize->add_setting(
		'partners_account_section_title',
		array(
			'default'           => 'Account Details for Contributions',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'partners_account_section_title',
		array(
			'label'       => __( 'Account Details Section Title', 'tsm-theme' ),
			'description' => __( 'Title shown above account details after form submission', 'tsm-theme' ),
			'section'     => 'tsm_partners_page',
			'type'        => 'text',
		)
	);

	// Success Message Title
	$wp_customize->add_setting(
		'partners_success_title',
		array(
			'default'           => 'Thank You for Your Heart to Partner!',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'partners_success_title',
		array(
			'label'   => __( 'Success Message Title', 'tsm-theme' ),
			'section' => 'tsm_partners_page',
			'type'    => 'text',
		)
	);

	// Success Message Description
	$wp_customize->add_setting(
		'partners_success_description',
		array(
			'default'           => 'Your interest has been received. A partnership coordinator will be in touch with you shortly to discuss our global mission.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'partners_success_description',
		array(
			'label'   => __( 'Success Message Description', 'tsm-theme' ),
			'section' => 'tsm_partners_page',
			'type'    => 'textarea',
		)
	);

	// Multiple Accounts (up to 4)
	for ( $i = 1; $i <= 4; $i++ ) {
		// Account Label/Type
		$wp_customize->add_setting(
			'partners_account_' . $i . '_label',
			array(
				'default'           => $i === 1 ? 'Local Account (NGN)' : ( $i === 2 ? 'International Account (USD)' : '' ),
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'partners_account_' . $i . '_label',
			array(
				'label'       => sprintf( __( 'Account %d - Label', 'tsm-theme' ), $i ),
				'description' => sprintf( __( 'Label for account %d (e.g., "Local Account (NGN)")', 'tsm-theme' ), $i ),
				'section'     => 'tsm_partners_page',
				'type'        => 'text',
			)
		);

		// Bank Name
		$wp_customize->add_setting(
			'partners_account_' . $i . '_bank_name',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'partners_account_' . $i . '_bank_name',
			array(
				'label'   => sprintf( __( 'Account %d - Bank Name', 'tsm-theme' ), $i ),
				'section' => 'tsm_partners_page',
				'type'    => 'text',
			)
		);

		// Account Name
		$wp_customize->add_setting(
			'partners_account_' . $i . '_account_name',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'partners_account_' . $i . '_account_name',
			array(
				'label'   => sprintf( __( 'Account %d - Account Name', 'tsm-theme' ), $i ),
				'section' => 'tsm_partners_page',
				'type'    => 'text',
			)
		);

		// Account Number
		$wp_customize->add_setting(
			'partners_account_' . $i . '_account_number',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'partners_account_' . $i . '_account_number',
			array(
				'label'   => sprintf( __( 'Account %d - Account Number', 'tsm-theme' ), $i ),
				'section' => 'tsm_partners_page',
				'type'    => 'text',
			)
		);

		// Routing Number
		$wp_customize->add_setting(
			'partners_account_' . $i . '_routing_number',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'partners_account_' . $i . '_routing_number',
			array(
				'label'   => sprintf( __( 'Account %d - Routing Number', 'tsm-theme' ), $i ),
				'section' => 'tsm_partners_page',
				'type'    => 'text',
			)
		);

		// SWIFT Code
		$wp_customize->add_setting(
			'partners_account_' . $i . '_swift_code',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'partners_account_' . $i . '_swift_code',
			array(
				'label'   => sprintf( __( 'Account %d - SWIFT Code', 'tsm-theme' ), $i ),
				'section' => 'tsm_partners_page',
				'type'    => 'text',
			)
		);
	}

	// Additional Notes
	$wp_customize->add_setting(
		'partners_account_notes',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'partners_account_notes',
		array(
			'label'       => __( 'Additional Account Notes', 'tsm-theme' ),
			'description' => __( 'Any additional instructions or information about the accounts', 'tsm-theme' ),
			'section'     => 'tsm_partners_page',
			'type'        => 'textarea',
		)
	);

	// Partnership Brochure URL
	$wp_customize->add_setting(
		'partners_brochure_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'partners_brochure_url',
		array(
			'label'       => __( 'Partnership Brochure URL', 'tsm-theme' ),
			'description' => __( 'URL to download the partnership brochure PDF. Leave empty to hide the download button.', 'tsm-theme' ),
			'section'     => 'tsm_partners_page',
			'type'        => 'url',
		)
	);
}
add_action( 'customize_register', 'tsm_partners_page_customizer' );
