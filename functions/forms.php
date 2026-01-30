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

/**
 * Handle "I Made a Decision Today" form submission
 */
function tsm_handle_decision_form() {
	// Verify nonce
	if ( ! isset( $_POST['tsm_decision_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_decision_nonce'], 'tsm_decision_form' ) ) {
		wp_die( 'Security check failed. Please try again.' );
	}

	// Get form fields
	$first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
	$last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
	$email      = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$decision   = isset( $_POST['decision'] ) ? sanitize_text_field( $_POST['decision'] ) : '';
	$message    = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

	// Validate required fields
	if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $decision ) || ! is_email( $email ) ) {
		$redirect_url = wp_get_referer();
		if ( ! $redirect_url ) {
			$redirect_url = home_url( '/how-to-know-jesus' );
		}
		wp_redirect( add_query_arg( 'decision', 'error', $redirect_url ) );
		exit;
	}

	// Map decision values to readable text
	$decision_text = array(
		'prayed'    => __( 'I prayed to receive Jesus today', 'tsm-theme' ),
		'questions' => __( 'I have some questions first', 'tsm-theme' ),
		'recommit'  => __( 'I want to recommit my life', 'tsm-theme' ),
	);
	$decision_display = isset( $decision_text[ $decision ] ) ? $decision_text[ $decision ] : $decision;

	// Send email notification
	$to      = get_option( 'admin_email' );
	$subject = __( 'New Decision Form Submission', 'tsm-theme' );
	
	// Build HTML email message
	$email_message = sprintf(
		"<html><body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
		<h2 style='color: #339a46;'>%s</h2>
		<p><strong>%s:</strong> %s %s</p>
		<p><strong>%s:</strong> <a href='mailto:%s'>%s</a></p>
		<p><strong>%s:</strong> %s</p>
		%s
		</body></html>",
		__( 'A new decision form has been submitted:', 'tsm-theme' ),
		__( 'Name', 'tsm-theme' ),
		esc_html( $first_name ),
		esc_html( $last_name ),
		__( 'Email', 'tsm-theme' ),
		esc_html( $email ),
		esc_html( $email ),
		__( 'Decision', 'tsm-theme' ),
		esc_html( $decision_display ),
		! empty( $message ) 
			? "<p><strong>" . __( 'Message', 'tsm-theme' ) . ":</strong><br>" . nl2br( esc_html( $message ) ) . "</p>" 
			: "<p><em>" . __( '(No message provided)', 'tsm-theme' ) . "</em></p>"
	);
	
	// Set headers for HTML email
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
		'Reply-To: ' . esc_html( $first_name . ' ' . $last_name ) . ' <' . esc_html( $email ) . '>',
	);
	
	wp_mail( $to, $subject, $email_message, $headers );

	// Redirect back with success message
	$redirect_url = wp_get_referer();
	if ( ! $redirect_url ) {
		$redirect_url = home_url( '/how-to-know-jesus' );
	}
	wp_redirect( add_query_arg( 'decision', 'success', $redirect_url ) );
	exit;
}
add_action( 'admin_post_tsm_decision_form', 'tsm_handle_decision_form' );
add_action( 'admin_post_nopriv_tsm_decision_form', 'tsm_handle_decision_form' );

/**
 * Handle contact form submission
 */
function tsm_handle_contact_form() {
	// Verify nonce
	if ( ! isset( $_POST['tsm_contact_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_contact_nonce'], 'tsm_contact_form' ) ) {
		wp_die( 'Security check failed. Please try again.' );
	}

	// Get form fields
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

	// Validate required fields
	if ( empty( $name ) || empty( $email ) || empty( $subject ) || empty( $message ) || ! is_email( $email ) ) {
		$redirect_url = wp_get_referer();
		if ( ! $redirect_url ) {
			$redirect_url = home_url( '/contact' );
		}
		wp_redirect( add_query_arg( 'contact', 'error', $redirect_url ) );
		exit;
	}

	// Map subject values to readable text
	$subject_text = array(
		'general'  => __( 'General Inquiry', 'tsm-theme' ),
		'booking'  => __( 'Booking Request', 'tsm-theme' ),
		'partner'  => __( 'Partnership Inquiry', 'tsm-theme' ),
		'admin'    => __( 'Administrative Matter', 'tsm-theme' ),
		'testimony' => __( 'Share a Testimony', 'tsm-theme' ),
	);
	$subject_display = isset( $subject_text[ $subject ] ) ? $subject_text[ $subject ] : $subject;

	// Send email notification
	$to      = get_option( 'admin_email' );
	$email_subject = sprintf( __( 'New Contact Form Submission: %s', 'tsm-theme' ), $subject_display );
	
	// Build HTML email message
	$email_message = sprintf(
		"<html><body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
		<h2 style='color: #339a46;'>%s</h2>
		<p><strong>%s:</strong> %s</p>
		<p><strong>%s:</strong> <a href='mailto:%s'>%s</a></p>
		<p><strong>%s:</strong> %s</p>
		<p><strong>%s:</strong><br>%s</p>
		</body></html>",
		__( 'A new contact form has been submitted:', 'tsm-theme' ),
		__( 'Name', 'tsm-theme' ),
		esc_html( $name ),
		__( 'Email', 'tsm-theme' ),
		esc_html( $email ),
		esc_html( $email ),
		__( 'Subject', 'tsm-theme' ),
		esc_html( $subject_display ),
		__( 'Message', 'tsm-theme' ),
		nl2br( esc_html( $message ) )
	);
	
	// Set headers for HTML email
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
		'Reply-To: ' . esc_html( $name ) . ' <' . esc_html( $email ) . '>',
	);
	
	wp_mail( $to, $email_subject, $email_message, $headers );

	// Redirect back with success message
	$redirect_url = wp_get_referer();
	if ( ! $redirect_url ) {
		$redirect_url = home_url( '/contact' );
	}
	wp_redirect( add_query_arg( 'contact', 'success', $redirect_url ) );
	exit;
}
add_action( 'admin_post_tsm_contact_form', 'tsm_handle_contact_form' );
add_action( 'admin_post_nopriv_tsm_contact_form', 'tsm_handle_contact_form' );

/**
 * Handle prayer request form submission
 */
function tsm_handle_prayer_request() {
	// Verify nonce
	if ( ! isset( $_POST['tsm_prayer_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_prayer_nonce'], 'tsm_prayer_request' ) ) {
		wp_die( 'Security check failed. Please try again.' );
	}

	// Get form fields
	$name         = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$request_type = isset( $_POST['request-type'] ) ? sanitize_text_field( $_POST['request-type'] ) : '';
	$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
	$confidential = isset( $_POST['confidential'] ) && '1' === $_POST['confidential'] ? true : false;

	// Validate required fields
	if ( empty( $name ) || empty( $email ) || empty( $message ) || ! is_email( $email ) ) {
		$redirect_url = wp_get_referer();
		if ( ! $redirect_url ) {
			$redirect_url = home_url( '/prayer' );
		}
		wp_redirect( add_query_arg( 'prayer', 'error', $redirect_url ) );
		exit;
	}

	// Map request type values to readable text
	$request_type_text = array(
		'healing'   => __( 'Healing & Restoration', 'tsm-theme' ),
		'financial' => __( 'Financial Breakthrough', 'tsm-theme' ),
		'family'    => __( 'Family & Relationships', 'tsm-theme' ),
		'spiritual' => __( 'Spiritual Growth', 'tsm-theme' ),
		'guidance'  => __( 'Career & Guidance', 'tsm-theme' ),
		'other'     => __( 'Other', 'tsm-theme' ),
	);
	$request_type_display = isset( $request_type_text[ $request_type ] ) ? $request_type_text[ $request_type ] : $request_type;

	// Send email notification
	$to      = get_option( 'admin_email' );
	$subject = __( 'New Prayer Request', 'tsm-theme' );
	
	// Build HTML email message
	$email_message = sprintf(
		"<html><body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
		<h2 style='color: #339a46;'>%s</h2>
		<p><strong>%s:</strong> %s</p>
		<p><strong>%s:</strong> <a href='mailto:%s'>%s</a></p>
		<p><strong>%s:</strong> %s</p>
		%s
		<p><strong>%s:</strong> %s</p>
		<p><strong>%s:</strong><br>%s</p>
		</body></html>",
		__( 'A new prayer request has been submitted:', 'tsm-theme' ),
		__( 'Name', 'tsm-theme' ),
		esc_html( $name ),
		__( 'Email', 'tsm-theme' ),
		esc_html( $email ),
		esc_html( $email ),
		__( 'Request Type', 'tsm-theme' ),
		esc_html( $request_type_display ),
		$confidential 
			? "<p style='background-color: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;'><strong>" . __( '⚠️ Confidential Request', 'tsm-theme' ) . "</strong><br>" . __( 'This request should only be visible to the lead ministry team.', 'tsm-theme' ) . "</p>" 
			: '',
		__( 'Confidential', 'tsm-theme' ),
		$confidential ? __( 'Yes', 'tsm-theme' ) : __( 'No', 'tsm-theme' ),
		__( 'Prayer Request', 'tsm-theme' ),
		nl2br( esc_html( $message ) )
	);
	
	// Set headers for HTML email
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
		'Reply-To: ' . esc_html( $name ) . ' <' . esc_html( $email ) . '>',
	);
	
	wp_mail( $to, $subject, $email_message, $headers );

	// Redirect back with success message
	$redirect_url = wp_get_referer();
	if ( ! $redirect_url ) {
		$redirect_url = home_url( '/prayer' );
	}
	wp_redirect( add_query_arg( 'prayer', 'success', $redirect_url ) );
	exit;
}
add_action( 'admin_post_tsm_prayer_request', 'tsm_handle_prayer_request' );
add_action( 'admin_post_nopriv_tsm_prayer_request', 'tsm_handle_prayer_request' );

/**
 * Handle partnership inquiry form submission
 */
function tsm_handle_partner_form() {
	// Verify nonce
	if ( ! isset( $_POST['tsm_partner_nonce'] ) || ! wp_verify_nonce( $_POST['tsm_partner_nonce'], 'tsm_partner_form' ) ) {
		wp_die( 'Security check failed. Please try again.' );
	}

	// Get form fields
	$fullname  = isset( $_POST['fullname'] ) ? sanitize_text_field( $_POST['fullname'] ) : '';
	$email     = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$phone     = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$location  = isset( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : '';
	$interest  = isset( $_POST['interest'] ) ? sanitize_text_field( $_POST['interest'] ) : '';
	$message   = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

	// Validate required fields
	if ( empty( $fullname ) || empty( $email ) || empty( $phone ) || empty( $location ) || empty( $interest ) || ! is_email( $email ) ) {
		$redirect_url = wp_get_referer();
		if ( ! $redirect_url ) {
			$redirect_url = home_url( '/partners' );
		}
		wp_redirect( add_query_arg( 'partner', 'error', $redirect_url ) );
		exit;
	}

	// Map interest values to readable text
	$interest_text = array(
		'missions'    => __( 'Missions', 'tsm-theme' ),
		'empowerment' => __( 'Financial Empowerment', 'tsm-theme' ),
		'rural'       => __( 'Rural Outreaches', 'tsm-theme' ),
	);
	$interest_display = isset( $interest_text[ $interest ] ) ? $interest_text[ $interest ] : $interest;

	// Send email notification
	$to      = get_option( 'admin_email' );
	$subject = __( 'New Partnership Inquiry', 'tsm-theme' );
	
	// Build HTML email message
	$email_message = sprintf(
		"<html><body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
		<h2 style='color: #339a46;'>%s</h2>
		<p><strong>%s:</strong> %s</p>
		<p><strong>%s:</strong> <a href='mailto:%s'>%s</a></p>
		<p><strong>%s:</strong> <a href='tel:%s'>%s</a></p>
		<p><strong>%s:</strong> %s</p>
		<p><strong>%s:</strong> %s</p>
		%s
		</body></html>",
		__( 'A new partnership inquiry has been submitted:', 'tsm-theme' ),
		__( 'Name', 'tsm-theme' ),
		esc_html( $fullname ),
		__( 'Email', 'tsm-theme' ),
		esc_html( $email ),
		esc_html( $email ),
		__( 'Phone', 'tsm-theme' ),
		esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ),
		esc_html( $phone ),
		__( 'Location', 'tsm-theme' ),
		esc_html( $location ),
		__( 'Area of Interest', 'tsm-theme' ),
		esc_html( $interest_display ),
		! empty( $message ) 
			? "<p><strong>" . __( 'Additional Comments', 'tsm-theme' ) . ":</strong><br>" . nl2br( esc_html( $message ) ) . "</p>" 
			: "<p><em>" . __( '(No additional comments provided)', 'tsm-theme' ) . "</em></p>"
	);
	
	// Set headers for HTML email
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
		'Reply-To: ' . esc_html( $fullname ) . ' <' . esc_html( $email ) . '>',
	);
	
	wp_mail( $to, $subject, $email_message, $headers );

	// Check if account details are configured (check all 4 accounts)
	$has_account_details = false;
	for ( $i = 1; $i <= 4; $i++ ) {
		$account_number = get_theme_mod( 'partners_account_' . $i . '_account_number', '' );
		if ( ! empty( $account_number ) ) {
			$has_account_details = true;
			break;
		}
	}
	
	// Redirect back with success message
	$redirect_url = wp_get_referer();
	if ( ! $redirect_url ) {
		$redirect_url = home_url( '/partners' );
	}
	
	// Add account_details parameter if account details are configured
	$redirect_url = add_query_arg( 'partner', 'success', $redirect_url );
	if ( $has_account_details ) {
		$redirect_url = add_query_arg( 'account_details', 'show', $redirect_url );
	}
	
	wp_redirect( $redirect_url );
	exit;
}
add_action( 'admin_post_tsm_partner_form', 'tsm_handle_partner_form' );
add_action( 'admin_post_nopriv_tsm_partner_form', 'tsm_handle_partner_form' );
