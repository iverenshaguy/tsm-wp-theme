<?php
/**
 * Theme customizer settings
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer settings and controls
 */
function tsm_theme_customize_register( $wp_customize ) {
	// Front Page Section
	$wp_customize->add_section(
		'tsm_front_page',
		array(
			'title'    => __( 'Front Page Settings', 'tsm-theme' ),
			'priority'  => 30,
		)
	);

	// Hero Badge
	$wp_customize->add_setting(
		'hero_badge',
		array(
			'default'           => 'Global Itinerant Ministry',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'hero_badge',
		array(
			'label'       => __( 'Hero Badge Text', 'tsm-theme' ),
			'description' => __( 'Small badge text displayed above the hero heading.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'text',
		)
	);

	// Hero Heading
	$wp_customize->add_setting(
		'hero_heading',
		array(
			'default'           => 'Welcome to<br/>Terry Shaguy Ministries',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'hero_heading',
		array(
			'label'       => __( 'Hero Heading', 'tsm-theme' ),
			'description' => __( 'Main heading displayed in the hero section. HTML line breaks (&lt;br/&gt;) are allowed.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'textarea',
		)
	);

	// Hero Description
	$wp_customize->add_setting(
		'hero_description',
		array(
			'default'           => 'United in heart and mission, we travel the world to share the transformative power of the Gospel through speaking, writing, and global outreach.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'hero_description',
		array(
			'label'       => __( 'Hero Description', 'tsm-theme' ),
			'description' => __( 'Subtitle/description text displayed below the hero heading.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'textarea',
		)
	);

	// Contact Page for "Invite Us to Speak" button
	$wp_customize->add_setting(
		'contact_page_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'contact_page_id',
		array(
			'label'       => __( 'Contact Page', 'tsm-theme' ),
			'description' => __( 'Select the page for the "Invite Us to Speak" button link.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'dropdown-pages',
		)
	);

	// Missions Page for "Our Missions Work" button
	$wp_customize->add_setting(
		'missions_page_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'missions_page_id',
		array(
			'label'       => __( 'Missions Page', 'tsm-theme' ),
			'description' => __( 'Select the page for the "Our Missions Work" button link.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'dropdown-pages',
		)
	);

	// About Image
	$wp_customize->add_setting(
		'about_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/about.png',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'about_image',
			array(
				'label'       => __( 'About Section Image', 'tsm-theme' ),
				'description' => __( 'Image displayed in the About section.', 'tsm-theme' ),
				'section'     => 'tsm_front_page',
			)
		)
	);

	// About Content
	$wp_customize->add_setting(
		'about_content',
		array(
			'default'           => 'With over 25 combined years in ministry, we have dedicated our lives to training leaders and reaching the unreached. Together, we bring a balanced perspective on faith, family, and global service. 

Our journey has taken us from rural villages in the Amazon to bustling metropolises in Asia, always with the singular focus of equipping the body of Christ for the harvest. David\'s theological depth and Debbie\'s compassionate heart for families create a unique synergy in their shared calling.',
			'sanitize_callback' => 'wp_kses_post',
		)
	);
	$wp_customize->add_control(
		'about_content',
		array(
			'label'       => __( 'About Content', 'tsm-theme' ),
			'description' => __( 'Content displayed in the About section. HTML is allowed.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'textarea',
		)
	);

	// About Quote
	$wp_customize->add_setting(
		'about_quote',
		array(
			'default'           => '"Dedicated to sharing hope across the nations."',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'about_quote',
		array(
			'label'       => __( 'About Quote', 'tsm-theme' ),
			'description' => __( 'Quote text displayed in the overlay box on the about image.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'text',
		)
	);

	// Villages Count
	$wp_customize->add_setting(
		'villages_count',
		array(
			'default'           => '40',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'villages_count',
		array(
			'label'       => __( 'Villages Count', 'tsm-theme' ),
			'description' => __( 'Number displayed in the "Villages" statistic (e.g., "40" will show as "40+ Villages").', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'text',
		)
	);

	// Books Count
	$wp_customize->add_setting(
		'books_count',
		array(
			'default'           => '12',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'books_count',
		array(
			'label'       => __( 'Books Count', 'tsm-theme' ),
			'description' => __( 'Number displayed in the "Books" statistic (e.g., "12" will show as "12+ Books").', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'text',
		)
	);

	// Services Badge
	$wp_customize->add_setting(
		'services_badge',
		array(
			'default'           => 'Our Services',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'services_badge',
		array(
			'label'       => __( 'Services Badge Text', 'tsm-theme' ),
			'description' => __( 'Small badge text displayed above the services heading.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'text',
		)
	);

	// Services Heading
	$wp_customize->add_setting(
		'services_heading',
		array(
			'default'           => 'How We Serve',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'services_heading',
		array(
			'label'       => __( 'Services Heading', 'tsm-theme' ),
			'description' => __( 'Main heading for the services section.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'text',
		)
	);

	// Services Description
	$wp_customize->add_setting(
		'services_description',
		array(
			'default'           => 'We are committed to serving the body of Christ through various ministries and outreach efforts.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'services_description',
		array(
			'label'       => __( 'Services Description', 'tsm-theme' ),
			'description' => __( 'Description text displayed below the services heading.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'textarea',
		)
	);

	// Service 1 Title
	$wp_customize->add_setting(
		'service_1_title',
		array(
			'default'           => 'Itinerant Teaching',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'service_1_title',
		array(
			'label'   => __( 'Service 1 Title', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'text',
		)
	);

	// Service 1 Description
	$wp_customize->add_setting(
		'service_1_description',
		array(
			'default'           => 'Available for conferences, revivals, and church gatherings globally.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'service_1_description',
		array(
			'label'   => __( 'Service 1 Description', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'textarea',
		)
	);

	// Service 2 Title
	$wp_customize->add_setting(
		'service_2_title',
		array(
			'default'           => 'Authorship',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'service_2_title',
		array(
			'label'   => __( 'Service 2 Title', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'text',
		)
	);

	// Service 2 Description
	$wp_customize->add_setting(
		'service_2_description',
		array(
			'default'           => 'Writing together and individually to equip the body of Christ.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'service_2_description',
		array(
			'label'   => __( 'Service 2 Description', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'textarea',
		)
	);

	// Service 3 Title
	$wp_customize->add_setting(
		'service_3_title',
		array(
			'default'           => 'Global Outreach',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'service_3_title',
		array(
			'label'   => __( 'Service 3 Title', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'text',
		)
	);

	// Service 3 Description
	$wp_customize->add_setting(
		'service_3_description',
		array(
			'default'           => 'Leading mission teams and humanitarian efforts across the globe.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'service_3_description',
		array(
			'label'   => __( 'Service 3 Description', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'textarea',
		)
	);

	// Featured Book Badge
	$wp_customize->add_setting(
		'featured_book_badge',
		array(
			'default'           => 'NEW RELEASE',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'featured_book_badge',
		array(
			'label'   => __( 'Featured Book Badge', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'text',
		)
	);

	// Featured Book Title
	$wp_customize->add_setting(
		'featured_book_title',
		array(
			'default'           => 'Walking the Narrow Road',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'featured_book_title',
		array(
			'label'   => __( 'Featured Book Title', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'text',
		)
	);

	// Featured Book Author
	$wp_customize->add_setting(
		'featured_book_author',
		array(
			'default'           => 'Terry Shaguy',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'featured_book_author',
		array(
			'label'   => __( 'Featured Book Author', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'text',
		)
	);

	// Featured Book Description
	$wp_customize->add_setting(
		'featured_book_description',
		array(
			'default'           => 'Discover the transformative power of surrendered living. In his latest book, Terry Shaguy shares profound lessons from two decades on the mission field, teaching us how to find God\'s voice in the midst of global noise.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'featured_book_description',
		array(
			'label'   => __( 'Featured Book Description', 'tsm-theme' ),
			'section' => 'tsm_front_page',
			'type'    => 'textarea',
		)
	);

	// Featured Book Image
	$wp_customize->add_setting(
		'featured_book_image',
		array(
			'default'           => get_template_directory_uri() . '/assets/images/book-cover.png',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'featured_book_image',
			array(
				'label'   => __( 'Featured Book Cover Image', 'tsm-theme' ),
				'section' => 'tsm_front_page',
			)
		)
	);

	// Featured Book Buy URL
	$wp_customize->add_setting(
		'featured_book_buy_url',
		array(
			'default'           => home_url( '/books/walking-the-narrow-road' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'featured_book_buy_url',
		array(
			'label'       => __( 'Featured Book Buy URL', 'tsm-theme' ),
			'description' => __( 'URL for the "Buy Now" button.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'url',
		)
	);

	// Featured Book Excerpt URL
	$wp_customize->add_setting(
		'featured_book_excerpt_url',
		array(
			'default'           => home_url( '/books/walking-the-narrow-road#excerpt' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'featured_book_excerpt_url',
		array(
			'label'       => __( 'Featured Book Excerpt URL', 'tsm-theme' ),
			'description' => __( 'URL for the "Read Excerpt" button.', 'tsm-theme' ),
			'section'     => 'tsm_front_page',
			'type'        => 'url',
		)
	);

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
			'default'           => 'Terry &amp; Debbie Shaguy',
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
			'default'           => 'Empowering the global church through joint missions, biblical literature, and prophetic teaching.',
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
			'default'           => '#',
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
			'default'           => '#',
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
			'default'           => '#',
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
			'default'           => "Joint Itinerant Ministry\nMarriage Seminars\nGlobal Mission Outreach\nLeadership Development",
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
	$quick_link_defaults = array(
		1 => array( 'title' => 'Meet Terry & Debbie', 'url' => '/about' ),
		2 => array( 'title' => 'Books & Resources', 'url' => '/books' ),
		3 => array( 'title' => 'Mission Itinerary', 'url' => '#itinerary' ),
		4 => array( 'title' => 'Financial Partnership', 'url' => '/partner' ),
	);

	for ( $i = 1; $i <= 4; $i++ ) {
		// Quick Link Title
		$wp_customize->add_setting(
			'quick_link_' . $i . '_title',
			array(
				'default'           => $quick_link_defaults[ $i ]['title'],
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
				'default'           => $quick_link_defaults[ $i ]['url'],
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
			'default'           => 'terry@terryshaguy.org',
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
			'default'           => '+234 (803) 030-8123',
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
			'default'           => '2, Kutamiti Street, Basorun. P.O. Box 19824 U.I., Ibadan, Nigeria.',
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
add_action( 'customize_register', 'tsm_theme_customize_register' );
