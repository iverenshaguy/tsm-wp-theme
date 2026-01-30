	</main>

	<footer class="bg-white dark:bg-[#060c08] border-t border-gray-100 dark:border-[#1d3a24] pt-20 pb-10" id="contact">
		<div class="max-w-[1280px] mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
			<div>
				<div class="flex items-center gap-3 mb-8">
					<div class="text-primary dark:text-accent size-full">
						<?php if ( has_custom_logo() ) : ?>
							<?php 
							$logo_id = get_theme_mod( 'custom_logo' );
							$logo = wp_get_attachment_image_src( $logo_id, 'full' );
							if ( $logo ) {
								echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="h-12 w-auto" />';
							}
							?>
						<?php else : ?>
							<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
								<path clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor" fill-rule="evenodd"></path>
							</svg>
						<?php endif; ?>
					</div>
				</div>
				<p class="text-gray-500 text-sm leading-relaxed mb-8">
					<?php echo esc_html( get_theme_mod( 'footer_description', 'Empowering the global church through joint missions, biblical literature, and prophetic teaching.' ) ); ?>
				</p>
				<div class="flex gap-4">
					<?php
					$social_facebook = get_theme_mod( 'social_facebook', '#' );
					$social_instagram = get_theme_mod( 'social_instagram', '#' );
					$social_linkedin = get_theme_mod( 'social_linkedin', '#' );
					$social_whatsapp = get_theme_mod( 'social_whatsapp', '' );
					
					// If WhatsApp URL not set, generate from contact phone
					if ( empty( $social_whatsapp ) ) {
						$contact_phone = get_theme_mod( 'contact_phone', '+234 (703) 030-8123' );
						if ( ! empty( $contact_phone ) ) {
							$phone_whatsapp = preg_replace( '/[^0-9]/', '', $contact_phone );
							$social_whatsapp = 'https://wa.me/' . $phone_whatsapp;
						}
					}
					?>
					<?php if ( ! empty( $social_facebook ) ) : ?>
						<a aria-label="Facebook" class="size-11 bg-primary text-white hover:text-white rounded-full flex items-center justify-center hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40" href="<?php echo esc_url( $social_facebook ); ?>" target="_blank" rel="noopener">
							<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $social_instagram ) ) : ?>
						<a aria-label="Instagram" class="size-11 bg-primary text-white hover:text-white rounded-full flex items-center justify-center hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40" href="<?php echo esc_url( $social_instagram ); ?>" target="_blank" rel="noopener">
							<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $social_linkedin ) ) : ?>
						<a aria-label="LinkedIn" class="size-11 bg-primary text-white hover:text-white rounded-full flex items-center justify-center hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40" href="<?php echo esc_url( $social_linkedin ); ?>" target="_blank" rel="noopener">
							<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path></svg>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $social_whatsapp ) ) : ?>
						<a aria-label="WhatsApp" class="size-11 bg-primary text-white hover:text-white rounded-full flex items-center justify-center hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40" href="<?php echo esc_url( $social_whatsapp ); ?>" target="_blank" rel="noopener">
							<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
							</svg>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<div>
				<h5 class="font-bold mb-8 text-primary dark:text-white uppercase tracking-wider text-sm">Ministry Focus</h5>
				<?php
				if ( is_active_sidebar( 'footer-1' ) ) {
					dynamic_sidebar( 'footer-1' );
				} else {
					$ministry_focus = get_theme_mod( 'ministry_focus', "Itinerant Ministry\nGlobal Mission Outreach\nLeadership Development\nTeaching Ministry" );
					$items = explode( "\n", $ministry_focus );
					?>
					<ul class="space-y-4 text-sm text-gray-500">
						<?php foreach ( $items as $item ) : ?>
							<?php if ( ! empty( trim( $item ) ) ) : ?>
								<li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-accent rounded-full"></span> <?php echo esc_html( trim( $item ) ); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<?php
				}
				?>
			</div>
			<div>
				<h5 class="font-bold mb-8 text-primary dark:text-white uppercase tracking-wider text-sm">Quick Links</h5>
				<?php
				if ( is_active_sidebar( 'footer-2' ) ) {
					dynamic_sidebar( 'footer-2' );
				} else {
					// Use individual quick link settings
					$has_links = false;
					for ( $i = 1; $i <= 4; $i++ ) {
						$title = get_theme_mod( 'quick_link_' . $i . '_title', '' );
						if ( ! empty( $title ) ) {
							$has_links = true;
							break;
						}
					}

					if ( $has_links ) {
						?>
						<ul class="space-y-4 text-sm text-gray-500">
							<?php
							for ( $i = 1; $i <= 4; $i++ ) {
								$title = get_theme_mod( 'quick_link_' . $i . '_title', '' );
								$url   = get_theme_mod( 'quick_link_' . $i . '_url', '' );

								if ( empty( $title ) ) {
									continue;
								}

								// Process URL
								if ( empty( $url ) ) {
									$url = '#';
								} elseif ( $url[0] === '#' ) {
									// Keep anchor links as-is
								} elseif ( $url[0] === '/' ) {
									$url = home_url( $url );
								} elseif ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
									$url = home_url( '/' . $url );
								}
								?>
								<li><a class="hover:text-accent transition-colors" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></li>
								<?php
							}
							?>
						</ul>
						<?php
					} else {
						// Fallback to footer menu or default links
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'menu_class'     => 'space-y-4 text-sm text-gray-500',
								'fallback_cb'    => function () {
									?>
								<ul class="space-y-4 text-sm text-gray-500">
									<li><a class="hover:text-accent transition-colors" href="<?php echo esc_url( home_url( '/about' ) ); ?>">Meet Terry &amp; Debbie</a></li>
									<li><a class="hover:text-accent transition-colors" href="<?php echo esc_url( home_url( '/books' ) ); ?>">Books &amp; Resources</a></li>
									<li><a class="hover:text-accent transition-colors" href="<?php echo esc_url( home_url( '/missions' ) ); ?>">Missions</a></li>
									<li><a class="hover:text-accent transition-colors" href="<?php echo esc_url( home_url( '/partners' ) ); ?>">Financial Partnership</a></li>
								</ul>
									<?php
								},
							)
						);
					}
				}
				?>
			</div>
			<div>
				<h5 class="font-bold mb-8 text-primary dark:text-white uppercase tracking-wider text-sm">Contact Us</h5>
				<div class="space-y-6">
					<?php
					$contact_email = get_theme_mod( 'contact_email', 'terry@terryshaguy.org' );
					$contact_phone = get_theme_mod( 'contact_phone', '+234 (703) 030-8123' );
					$contact_phone_2 = get_theme_mod( 'contact_phone_2', '+234 (708) 143-6641' );
					$contact_address = get_theme_mod( 'contact_address', '2, Kutamiti Street, Basorun. P.O. Box 19824 U.I., Ibadan, Nigeria.' );
					?>
					<div class="flex items-start gap-4 group">
						<span class="material-symbols-outlined text-accent group-hover:scale-110 transition-transform">mail</span>
						<div>
							<p class="text-xs font-bold text-gray-400 uppercase tracking-tighter mb-1">Email Us</p>
							<a class="text-sm font-bold text-primary dark:text-accent hover:underline" href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
						</div>
					</div>
					<?php if ( ! empty( $contact_phone ) || ! empty( $contact_phone_2 ) ) : ?>
						<div class="flex items-start gap-4 group">
							<span class="material-symbols-outlined text-accent group-hover:scale-110 transition-transform">call</span>
							<div class="flex-1">
								<p class="text-xs font-bold text-gray-400 uppercase tracking-tighter mb-2">Call Us</p>
								<div class="flex flex-col gap-2">
									<?php if ( ! empty( $contact_phone ) ) : ?>
										<?php
										// Clean phone number for tel: link (remove spaces, parentheses, dashes)
										$phone_clean = preg_replace( '/[^0-9+]/', '', $contact_phone );
										// Clean phone number for WhatsApp (digits only)
										$phone_whatsapp = preg_replace( '/[^0-9]/', '', $contact_phone );
										?>
										<div class="flex items-center gap-2">
											<a class="text-sm font-bold text-primary dark:text-accent hover:underline transition-colors" href="tel:<?php echo esc_attr( $phone_clean ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
											<a href="https://wa.me/<?php echo esc_attr( $phone_whatsapp ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center text-green-500 hover:text-green-600 transition-colors flex-shrink-0" aria-label="<?php echo esc_attr__( 'Open WhatsApp', 'tsm-theme' ); ?>">
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
										<a class="text-sm font-bold text-primary dark:text-accent hover:underline transition-colors" href="tel:<?php echo esc_attr( $phone_2_clean ); ?>"><?php echo esc_html( $contact_phone_2 ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<div class="flex items-start gap-4 group">
						<span class="material-symbols-outlined text-accent group-hover:scale-110 transition-transform">location_on</span>
						<div>
							<p class="text-xs font-bold text-gray-400 uppercase tracking-tighter mb-1">Visit Us</p>
							<p class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-relaxed">
								<?php echo wp_kses_post( $contact_address ); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="max-w-[1280px] mx-auto px-6 pt-10 border-t border-gray-100 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400 font-medium">
			<p>© <?php echo date( 'Y' ); ?> Terry Shaguy Ministries. All rights reserved.</p>
			<div class="flex gap-8">
				<a class="hover:text-accent transition-colors" href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>">Privacy Policy</a>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
