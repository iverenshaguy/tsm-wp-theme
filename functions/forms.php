<?php
/**
 * Form handlers
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle newsletter signup form submission
 */
function tsm_handle_newsletter_signup() {
	// Verify nonce
	if ( ! isset( $_POST['tsm_newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_newsletter_nonce'], 'tsm_newsletter_signup' ) ) {
		wp_die( 'Security check failed. Please try again.' );
	}

	// Get email
	$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

	if ( empty( $email ) || ! is_email( $email ) ) {
		$redirect_url = wp_get_referer();
		if ( ! $redirect_url ) {
			$redirect_url = home_url( '/' );
		}
		wp_redirect( add_query_arg( 'newsletter', 'error', $redirect_url ) );
		exit;
	}

	// Here you can:
	// 1. Save to database
	// 2. Send to email service (Mailchimp, etc.)
	// 3. Send email notification
	// 4. Save to custom post type or options

	// Example: Send email notification
	$to      = get_option( 'admin_email' );
	$subject = 'New Newsletter Signup';
	$message = "A new subscriber has signed up:\n\nEmail: {$email}";
	wp_mail( $to, $subject, $message );

	// Redirect back with success message
	$redirect_url = wp_get_referer();
	if ( ! $redirect_url ) {
		$redirect_url = home_url( '/' );
	}
	wp_redirect( add_query_arg( 'newsletter', 'success', $redirect_url ) );
	exit;
}
add_action( 'admin_post_tsm_newsletter_signup', 'tsm_handle_newsletter_signup' );
add_action( 'admin_post_nopriv_tsm_newsletter_signup', 'tsm_handle_newsletter_signup' );
