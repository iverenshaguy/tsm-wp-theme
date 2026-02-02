<?php
/**
 * The template for displaying the Contact page
 *
 * @package TSM_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary via-primary/90 to-[#1a4d2e] py-16 text-center relative overflow-hidden">
	<div class="absolute inset-0 opacity-10">
		<svg class="w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 100 100">
			<path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
		</svg>
	</div>
	<div class="max-w-[1280px] mx-auto px-6 relative z-10">
		<div class="mb-6 inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-sm rounded-full border border-primary/30">
			<span class="material-symbols-outlined text-primary text-sm">mail</span>
			<span class="text-white text-xs font-bold tracking-widest uppercase">
				<?php
				$contact_hero_badge = tsm_get_theme_mod_cached( 'contact_hero_badge', 'Get in Touch' );
				echo esc_html( $contact_hero_badge );
				?>
			</span>
		</div>
		<h1 class="text-white text-4xl md:text-6xl font-black mb-6">
			<?php
			$contact_hero_title = tsm_get_theme_mod_cached( 'contact_hero_title', 'Connect With Us' );
			echo esc_html( $contact_hero_title );
			?>
		</h1>
		<p class="text-white/80 text-lg max-w-2xl mx-auto">
			<?php
			$contact_hero_description = tsm_get_theme_mod_cached(
				'contact_hero_description',
				'Whether you have a general question, a booking inquiry, or just want to share a testimony, we would love to hear from you.'
			);
			echo esc_html( $contact_hero_description );
			?>
		</p>
	</div>
</section>

<!-- Contact Form & Info Section -->
<section class="max-w-[1280px] mx-auto px-6 py-20">
	<div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
		<!-- Contact Form -->
		<div class="lg:col-span-7">
			<div class="bg-white dark:bg-[#0a140d] p-8 md:p-10 rounded-3xl shadow-xl border border-gray-100 dark:border-[#1d3a24]">
				<h2 class="text-2xl font-black text-accent dark:text-white mb-8 flex items-center gap-3">
					<span class="material-symbols-outlined text-accent">mail</span>
					<?php
					$contact_form_title = tsm_get_theme_mod_cached( 'contact_form_title', 'Send a Message' );
					echo esc_html( $contact_form_title );
					?>
				</h2>
				
				<?php
				// Display success/error messages
				if ( isset( $_GET['contact'] ) ) {
					if ( $_GET['contact'] === 'success' ) {
						echo '<div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-xl text-green-800 dark:text-green-200">';
						echo esc_html__( 'Thank you! Your message has been sent successfully. We will get back to you soon.', 'tsm-theme' );
						echo '</div>';
					} elseif ( $_GET['contact'] === 'error' ) {
						echo '<div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-xl text-red-800 dark:text-red-200">';
						echo esc_html__( 'There was an error sending your message. Please try again.', 'tsm-theme' );
						echo '</div>';
					}
				}
				?>
				
				<!-- Success/Error Message Container (initially hidden) -->
				<div id="contact-message-container" class="hidden mb-6"></div>
				
				<form id="contact-form" class="space-y-6" method="post">
					<?php wp_nonce_field( 'tsm_contact_form', 'tsm_contact_nonce', false ); ?>
					
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div class="space-y-2">
							<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="name"><?php echo esc_html__( 'First Name', 'tsm-theme' ); ?></label>
							<input class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent" id="name" name="name" placeholder="<?php echo esc_attr__( 'John', 'tsm-theme' ); ?>" required type="text" autocomplete="given-name"/>
						</div>
						<div class="space-y-2">
							<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="email"><?php echo esc_html__( 'Email Address', 'tsm-theme' ); ?></label>
							<input class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent" id="email" name="email" placeholder="<?php echo esc_attr__( 'john@example.com', 'tsm-theme' ); ?>" required type="email" autocomplete="email"/>
						</div>
					</div>
					
					<div class="space-y-2">
						<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="subject"><?php echo esc_html__( 'Subject', 'tsm-theme' ); ?></label>
						<select class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent" id="subject" name="subject">
							<option value="general"><?php echo esc_html__( 'General Inquiry', 'tsm-theme' ); ?></option>
							<option value="booking"><?php echo esc_html__( 'Booking Request', 'tsm-theme' ); ?></option>
							<option value="partner"><?php echo esc_html__( 'Partnership Inquiry', 'tsm-theme' ); ?></option>
							<option value="admin"><?php echo esc_html__( 'Administrative Matter', 'tsm-theme' ); ?></option>
							<option value="testimony"><?php echo esc_html__( 'Share a Testimony', 'tsm-theme' ); ?></option>
						</select>
					</div>
					
					<div class="space-y-2">
						<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="message"><?php echo esc_html__( 'Your Message', 'tsm-theme' ); ?></label>
						<textarea class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent" id="message" name="message" placeholder="<?php echo esc_attr__( 'How can we help you?', 'tsm-theme' ); ?>" required rows="6" autocomplete="off"></textarea>
					</div>
					
					<div class="flex justify-center">
						<button id="contact-submit" class="bg-primary text-white hover:text-white text-base font-bold px-6 py-3 rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" type="submit" disabled>
							<?php echo esc_html__( 'Send Message', 'tsm-theme' ); ?> <span class="material-symbols-outlined !text-base">send</span>
						</button>
					</div>
				</form>
			</div>
		</div>
		
		<!-- Sidebar: Booking & Contact Info -->
		<div class="lg:col-span-5 flex flex-col gap-8">
			<!-- Booking Inquiries Card -->
			<div class="bg-accent text-white p-8 rounded-3xl shadow-xl relative overflow-hidden group">
				<div class="absolute -right-8 -top-8 size-40 bg-white/10 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-150"></div>
				<div class="relative z-10">
					<span class="inline-block bg-white/20 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase mb-4">
						<?php
						$booking_badge = tsm_get_theme_mod_cached( 'contact_booking_badge', 'Invitations' );
						echo esc_html( $booking_badge );
						?>
					</span>
					<h3 class="text-2xl font-black mb-4">
						<?php
						$booking_title = tsm_get_theme_mod_cached( 'contact_booking_title', 'Booking Inquiries' );
						echo esc_html( $booking_title );
						?>
					</h3>
					<p class="text-white/90 mb-6 leading-relaxed">
						<?php
						$booking_description = tsm_get_theme_mod_cached(
							'contact_booking_description',
							'Interested in inviting Terry or Debbie to speak at your church, conference, or seminar? We would love to review your invitation.'
						);
						echo esc_html( $booking_description );
						?>
					</p>
					<?php
					$booking_form_url = tsm_get_theme_mod_cached( 'contact_booking_form_url', '' );
					if ( ! empty( $booking_form_url ) ) :
						?>
						<a class="inline-flex items-center gap-2 bg-white text-primary hover:text-accent hover:bg-gray-100 font-bold py-4 px-10 rounded-lg shadow-xl transition-all" href="<?php echo esc_url( $booking_form_url ); ?>">
							<?php echo esc_html__( 'Request Form', 'tsm-theme' ); ?> <span class="material-symbols-outlined !text-base">calendar_today</span>
						</a>
					<?php endif; ?>
				</div>
			</div>
			
			<!-- Contact Information Card -->
			<div class="bg-white dark:bg-[#0a140d] p-8 md:p-10 rounded-3xl shadow-xl border border-gray-100 dark:border-[#1d3a24] flex-grow">
				<h3 class="text-xl font-black text-accent dark:text-white mb-8">
					<?php
					$contact_info_title = tsm_get_theme_mod_cached( 'contact_info_title', 'Contact Information' );
					echo esc_html( $contact_info_title );
					?>
				</h3>
				<div class="space-y-8">
					<?php
					$contact_email   = tsm_get_theme_mod_cached( 'contact_email', '' );
					$contact_phone   = tsm_get_theme_mod_cached( 'contact_phone', '' );
					$contact_phone_2 = tsm_get_theme_mod_cached( 'contact_phone_2', '+234 (708) 143-6641' );
					$contact_address = tsm_get_theme_mod_cached( 'contact_address', '' );
					?>
					
					<!-- Email -->
					<?php if ( ! empty( $contact_email ) ) : ?>
						<div class="flex items-start gap-4">
							<div class="bg-primary/5 dark:bg-primary/20 p-3 rounded-lg text-primary dark:text-accent">
								<span class="material-symbols-outlined">mail</span>
							</div>
							<div>
								<h4 class="font-bold text-accent dark:text-white mb-1">
									<?php echo esc_html__( 'Email Us', 'tsm-theme' ); ?>
								</h4>
								<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="text-gray-500 text-sm hover:text-primary dark:hover:text-accent transition-colors">
									<?php echo esc_html( $contact_email ); ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
					
					<!-- Phone -->
					<?php if ( ! empty( $contact_phone ) || ! empty( $contact_phone_2 ) ) : ?>
						<div class="flex items-start gap-4">
							<div class="bg-primary/5 dark:bg-primary/20 p-3 rounded-lg text-primary dark:text-accent">
								<span class="material-symbols-outlined">call</span>
							</div>
							<div class="flex-1">
								<h4 class="font-bold text-accent dark:text-white mb-2">
									<?php echo esc_html__( 'Call Us', 'tsm-theme' ); ?>
								</h4>
								<div class="flex flex-col gap-2">
									<?php if ( ! empty( $contact_phone ) ) : ?>
										<?php
										// Clean phone number for tel: link (remove spaces, parentheses, dashes)
										$phone_clean = preg_replace( '/[^0-9+]/', '', $contact_phone );
										// Clean phone number for WhatsApp (digits only)
										$phone_whatsapp = preg_replace( '/[^0-9]/', '', $contact_phone );
										?>
										<div class="flex items-center gap-2">
											<a href="tel:<?php echo esc_attr( $phone_clean ); ?>" class="text-gray-500 text-sm hover:text-primary dark:hover:text-accent transition-colors">
												<?php echo esc_html( $contact_phone ); ?>
											</a>
											<a href="https://wa.me/<?php echo esc_attr( $phone_whatsapp ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center text-gray-500 hover:text-primary dark:hover:text-accent transition-colors flex-shrink-0" aria-label="<?php echo esc_attr__( 'Open WhatsApp', 'tsm-theme' ); ?>">
												<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
													<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
												</svg>
											</a>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $contact_phone_2 ) ) : ?>
										<?php
										// Clean phone number for tel: link (remove spaces, parentheses, dashes)
										$phone_2_clean = preg_replace( '/[^0-9+]/', '', $contact_phone_2 );
										?>
										<a href="tel:<?php echo esc_attr( $phone_2_clean ); ?>" class="text-gray-500 text-sm hover:text-primary dark:hover:text-accent transition-colors">
											<?php echo esc_html( $contact_phone_2 ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					
					<!-- Physical Office -->
					<?php if ( ! empty( $contact_address ) ) : ?>
						<div class="flex items-start gap-4">
							<div class="bg-primary/5 dark:bg-primary/20 p-3 rounded-lg text-primary dark:text-accent">
								<span class="material-symbols-outlined">location_on</span>
							</div>
							<div>
								<h4 class="font-bold text-accent dark:text-white mb-1">
									<?php echo esc_html__( 'Visit Us', 'tsm-theme' ); ?>
								</h4>
								<p class="text-gray-500 text-sm leading-relaxed">
									<?php echo wp_kses_post( $contact_address ); ?>
								</p>
							</div>
						</div>
					<?php endif; ?>
					
					<!-- Social Media -->
					<div class="pt-6 border-t border-gray-100 dark:border-[#1d3a24]">
						<h4 class="font-bold text-accent dark:text-white mb-4">
							<?php echo esc_html__( 'Connect on Social Media', 'tsm-theme' ); ?>
						</h4>
						<div class="flex gap-4">
							<?php
							$social_facebook = tsm_get_theme_mod_cached( 'social_facebook', '' );
							$social_instagram = tsm_get_theme_mod_cached( 'social_instagram', '' );
							$social_linkedin = tsm_get_theme_mod_cached( 'social_linkedin', '' );
							$social_whatsapp = tsm_get_theme_mod_cached( 'social_whatsapp', '' );
							
							// If WhatsApp URL not set, generate from contact phone
							if ( empty( $social_whatsapp ) && ! empty( $contact_phone ) ) {
								$phone_whatsapp = preg_replace( '/[^0-9]/', '', $contact_phone );
								$social_whatsapp = 'https://wa.me/' . $phone_whatsapp;
							}
							?>
							<?php if ( ! empty( $social_facebook ) ) : ?>
								<a aria-label="Facebook" class="size-12 bg-gray-50 dark:bg-white/5 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition-all border border-gray-100 dark:border-transparent" href="<?php echo esc_url( $social_facebook ); ?>" target="_blank" rel="noopener">
									<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
								</a>
							<?php endif; ?>
							<?php if ( ! empty( $social_instagram ) ) : ?>
								<a aria-label="Instagram" class="size-12 bg-gray-50 dark:bg-white/5 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition-all border border-gray-100 dark:border-transparent" href="<?php echo esc_url( $social_instagram ); ?>" target="_blank" rel="noopener">
									<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
								</a>
							<?php endif; ?>
							<?php if ( ! empty( $social_linkedin ) ) : ?>
								<a aria-label="LinkedIn" class="size-12 bg-gray-50 dark:bg-white/5 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition-all border border-gray-100 dark:border-transparent" href="<?php echo esc_url( $social_linkedin ); ?>" target="_blank" rel="noopener">
									<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path></svg>
								</a>
							<?php endif; ?>
							<?php if ( ! empty( $social_whatsapp ) ) : ?>
								<a aria-label="WhatsApp" class="size-12 bg-gray-50 dark:bg-white/5 rounded-xl flex items-center justify-center hover:bg-primary hover:text-white transition-all border border-gray-100 dark:border-transparent" href="<?php echo esc_url( $social_whatsapp ); ?>" target="_blank" rel="noopener">
									<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
										<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
									</svg>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- FAQ Section -->
<?php
$faq_title = tsm_get_theme_mod_cached( 'contact_faq_title', 'Frequently Asked Questions' );
$faq_subtitle = tsm_get_theme_mod_cached( 'contact_faq_subtitle', 'Before reaching out, you might find your answer here.' );
$faq_items = tsm_get_theme_mod_cached(
	'contact_faq_items',
	'How far in advance should we book?|We typically recommend booking 6-12 months in advance for international travel and 3-6 months for domestic events.|Do you travel individually?|While we prioritize joint ministry as a couple, we do accept individual speaking engagements based on the specific context and need.'
);
$faqs = explode( '|', $faq_items );
$faq_count = count( $faqs ) / 2;

if ( ! empty( $faq_title ) && $faq_count > 0 ) :
	?>
	<section class="bg-[#f3f7f4] dark:bg-[#0c1a11] py-20">
		<div class="max-w-[1280px] mx-auto px-6">
			<div class="text-center mb-12">
				<h2 class="text-accent dark:text-white text-3xl font-black"><?php echo esc_html( $faq_title ); ?></h2>
				<?php if ( ! empty( $faq_subtitle ) ) : ?>
					<p class="text-gray-500 mt-4"><?php echo esc_html( $faq_subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
				<?php
				for ( $i = 0; $i < $faq_count; $i++ ) {
					$question = isset( $faqs[ $i * 2 ] ) ? trim( $faqs[ $i * 2 ] ) : '';
					$answer = isset( $faqs[ $i * 2 + 1 ] ) ? trim( $faqs[ $i * 2 + 1 ] ) : '';
					if ( empty( $question ) ) {
						continue;
					}
					?>
					<div class="bg-white dark:bg-[#0a140d] p-6 rounded-2xl shadow-sm border border-[#e7f3ea] dark:border-[#1d3a24]">
						<h4 class="font-bold text-accent dark:text-white mb-2"><?php echo esc_html( $question ); ?></h4>
						<?php if ( ! empty( $answer ) ) : ?>
							<p class="text-sm text-gray-500"><?php echo esc_html( $answer ); ?></p>
						<?php endif; ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
