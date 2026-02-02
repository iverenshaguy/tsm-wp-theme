<?php
/**
 * Enqueue scripts and styles
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add resource hints (preconnect, dns-prefetch) for external resources
 * 
 * Resource hints tell the browser to establish early connections to external domains,
 * reducing latency when loading resources from those domains.
 * 
 * - preconnect: Establishes early connection (DNS, TCP, TLS) - use for critical resources
 * - dns-prefetch: Only resolves DNS early - use for non-critical resources
 */
function tsm_theme_resource_hints() {
	// Preconnect for Google Fonts (critical - fonts load early)
	// Preconnect establishes full connection (DNS + TCP + TLS handshake)
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	
	// DNS prefetch for CDNs (non-critical - just resolve DNS early)
	// DNS prefetch only resolves DNS, doesn't establish connection
	// Use for resources that load later (like icons)
	echo '<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">' . "\n";
	
	// DNS prefetch for admin-ajax.php (used for AJAX requests)
	// Helps reduce latency for form submissions and AJAX calls
	echo '<link rel="dns-prefetch" href="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'tsm_theme_resource_hints', 1 );

/**
 * Preload critical fonts for better performance
 * Preloads Work Sans 700 and 900 weights used for headings
 * 
 * This function fetches the Google Fonts CSS for critical weights,
 * extracts the woff2 font file URLs, and preloads them directly.
 * This improves First Contentful Paint (FCP) and Largest Contentful Paint (LCP).
 */
function tsm_preload_critical_fonts() {
	// Fetch Google Fonts CSS for critical weights (700 and 900)
	$font_css_url = 'https://fonts.googleapis.com/css2?family=Work+Sans:wght@700;900&display=swap';
	$response = wp_remote_get( $font_css_url, array(
		'timeout' => 5,
		'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', // Required by Google Fonts
	) );
	
	// If fetch successful, extract woff2 URLs and preload them
	if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
		$css_content = wp_remote_retrieve_body( $response );
		
		// Extract woff2 font URLs from CSS
		// Pattern: url(https://fonts.gstatic.com/...) format('woff2')
		preg_match_all( '/url\((https:\/\/fonts\.gstatic\.com\/[^)]+\.woff2)\)\s+format\(\'woff2\'\)/', $css_content, $matches );
		
		if ( ! empty( $matches[1] ) ) {
			// Preload each font file
			foreach ( $matches[1] as $font_url ) {
				echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
			}
		}
	}
}
add_action( 'wp_head', 'tsm_preload_critical_fonts', 2 );

/**
 * Prevent scroll to top on form submission returns
 * This runs immediately in the head to prevent any scroll before other scripts
 */
function tsm_prevent_scroll_to_top() {
	?>
	<script>
	(function() {
		'use strict';
		// Prevent browser's automatic scroll restoration
		if ('scrollRestoration' in history) {
			history.scrollRestoration = 'manual';
		}
		
		// Check for form submission parameters and restore scroll immediately
		const urlParams = new URLSearchParams(window.location.search);
		const formTypes = ['newsletter', 'contact', 'prayer', 'partner'];
		
		formTypes.forEach(function(formType) {
			if (urlParams.has(formType)) {
				const savedScroll = sessionStorage.getItem(formType + 'ScrollPosition');
				if (savedScroll) {
					const scrollPos = parseInt(savedScroll, 10);
					if (scrollPos > 0) {
						// Prevent scroll to top immediately
						window.scrollTo(0, scrollPos);
						
						// Also prevent scroll on load event
						window.addEventListener('load', function() {
							setTimeout(function() {
								window.scrollTo(0, scrollPos);
							}, 0);
						}, { once: true });
					}
				}
			}
		});
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'tsm_prevent_scroll_to_top', 2 );

/**
 * Enqueue scripts and styles
 */
function tsm_theme_scripts() {
	// Get theme version for cache busting
	$theme_version = wp_get_theme()->get( 'Version' );
	$theme_version = $theme_version ? $theme_version : '1.0.0';
	
	// Enqueue theme stylesheet (minimal, required at root for WordPress)
	wp_enqueue_style( 'tsm-theme-style', get_stylesheet_uri(), array(), $theme_version );

	// Enqueue main theme styles from assets/css/style.css
	// Use filemtime for cache busting based on file modification time
	$style_css_path = get_template_directory() . '/assets/css/style.css';
	$style_css_version = file_exists( $style_css_path ) ? filemtime( $style_css_path ) : $theme_version;
	wp_enqueue_style( 'tsm-theme-styles', get_template_directory_uri() . '/assets/css/style.css', array( 'tsm-theme-style' ), $style_css_version );

	// Enqueue Google Fonts
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700;900&family=Lora:ital,wght@0,400;0,700;1,400&display=swap', array(), null, 'all' );

	// Enqueue Material Symbols
	wp_enqueue_style( 'material-symbols', 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap', array(), null );

	// Enqueue Material Design Icons (MDI) - Used in prayer requests page
	wp_enqueue_style( 'mdi-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css', array(), null );

	// Enqueue compiled Tailwind CSS (built from input.css)
	// Use filemtime for cache busting based on file modification time
	$main_css_path = get_template_directory() . '/assets/css/main.css';
	$main_css_version = file_exists( $main_css_path ) ? filemtime( $main_css_path ) : $theme_version;
	wp_enqueue_style( 'tsm-theme-main', get_template_directory_uri() . '/assets/css/main.css', array( 'tsm-theme-styles', 'google-fonts' ), $main_css_version );

	// Enqueue theme JavaScript
	// Use filemtime for cache busting based on file modification time
	$main_js_path = get_template_directory() . '/assets/js/main.js';
	$main_js_version = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : $theme_version;
	wp_enqueue_script( 'tsm-theme-script', get_template_directory_uri() . '/assets/js/main.js', array(), $main_js_version, true );
	
	// Add defer attribute for better performance (non-blocking script execution)
	// Note: Defer ensures script executes after HTML parsing, before DOMContentLoaded
	// This prevents blocking while still allowing scripts to run early enough for interactivity
	// 
	// TROUBLESHOOTING: If you experience freezing or timing issues, temporarily comment out
	// the line below to test without defer. Defer should work fine, but if there are conflicts
	// with Service Workers or other scripts, removing defer may help identify the issue.
	wp_script_add_data( 'tsm-theme-script', 'defer', true );

	// Localize script for newsletter AJAX
	wp_localize_script( 'tsm-theme-script', 'tsmNewsletter', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'tsm_newsletter_signup' ),
	) );

	// Localize script for partner form AJAX
	wp_localize_script( 'tsm-theme-script', 'tsmPartner', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'tsm_partner_form' ),
	) );

	// Localize script for contact form AJAX
	wp_localize_script( 'tsm-theme-script', 'tsmContact', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'tsm_contact_form' ),
	) );

	// Localize script for prayer form AJAX
	wp_localize_script( 'tsm-theme-script', 'tsmPrayer', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'tsm_prayer_request' ),
	) );

	// Localize script for decision form AJAX
	wp_localize_script( 'tsm-theme-script', 'tsmDecision', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'tsm_decision_form' ),
	) );

	// Localize script for AJAX
	if ( is_post_type_archive( 'mission' ) || is_page_template( 'archive-mission.php' ) ) {
		wp_localize_script( 'tsm-theme-script', 'tsmMissions', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'tsm_missions_nonce' ),
		) );
	}
	
	// Localize script for gallery AJAX
	if ( is_post_type_archive( 'gallery' ) || is_tax( 'gallery_category' ) ) {
		wp_localize_script( 'tsm-theme-script', 'tsmGalleries', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'tsm_galleries_nonce' ),
		) );
	}

	// Enqueue comment reply script on single posts
	// This script is non-critical and can be deferred for better performance
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
		// Defer comment reply script (non-critical, only needed when user clicks reply)
		wp_script_add_data( 'comment-reply', 'defer', true );
	}
}
add_action( 'wp_enqueue_scripts', 'tsm_theme_scripts' );

/**
 * Register and enqueue service worker for image caching
 */
function tsm_register_service_worker() {
	// Service worker for caching theme assets
	// Note: Scope is limited to theme directory (/wp-content/themes/tsm-theme/)
	// For site-wide caching, sw.js would need to be in site root (requires plugin or manual setup)
	?>
	<script>
	(function() {
		'use strict';
		if ('serviceWorker' in navigator) {
			// Register service worker after page load to avoid blocking
			// Use DOMContentLoaded instead of load for faster registration
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', registerSW);
			} else {
				// DOM already loaded
				registerSW();
			}
			
			function registerSW() {
				// Check if page is still active (not navigating away)
				if (document.readyState === 'unload') {
					return; // Don't register if page is unloading
				}
				
				// Only register if service worker is supported and not already controlled
				if (navigator.serviceWorker.controller) {
					// Service worker already controlling this page, skip registration
					return;
				}
				
				try {
					// Register service worker with proper error handling
					// Note: Service Worker scope is limited to theme directory and subdirectories
					// For site-wide scope, sw.js would need to be in site root (not theme directory)
					var registrationPromise = navigator.serviceWorker.register('<?php echo esc_url( get_template_directory_uri() ); ?>/sw.js');
					
					// Handle registration result
					if (registrationPromise && typeof registrationPromise.then === 'function') {
						registrationPromise
							.then(function(registration) {
								// Registration successful - no action needed
								// Avoid setting up update intervals to prevent message channel issues
							})
							.catch(function(err) {
								// Only log actual errors (not expected errors like already registered)
								if (err && err.message && !err.message.includes('already') && !err.message.includes('channel')) {
									console.error('ServiceWorker registration failed:', err);
								}
							});
					}
				} catch (e) {
					// Silently fail if registration throws (e.g., invalid scope, security error)
					if (e.message && !e.message.includes('channel')) {
						console.error('ServiceWorker registration error:', e);
					}
				}
			}
		}
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'tsm_register_service_worker' );
