<?php
/**
 * The template for displaying the Partners page
 *
 * @package TSM_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="overflow-hidden relative px-6 py-24 bg-primary/5 dark:bg-primary/10">
	<div class="max-w-[1280px] mx-auto relative z-10 flex flex-col items-center text-center">
		<div class="inline-flex gap-2 items-center px-3 py-1 mb-6 rounded-full border backdrop-blur-sm bg-primary/20 border-primary/30">
			<span class="text-sm material-symbols-outlined text-primary">handshake</span>
			<span class="text-xs font-bold tracking-widest uppercase text-primary">
				<?php echo esc_html__( 'Kingdom Partnership', 'tsm-theme' ); ?>
			</span>
		</div>
		<h1 class="text-5xl md:text-7xl font-black leading-tight mb-8 text-[#0a2e16] dark:text-white">
			<?php echo esc_html__( 'Join Our Global Mission', 'tsm-theme' ); ?>
		</h1>
		<p class="text-[#4a4a4a] dark:text-white/80 text-xl md:text-2xl leading-relaxed mb-10 max-w-3xl">
			<?php echo esc_html__( 'Partner with Terry Shaguy Ministries to advance the Gospel and empower communities through wise enterprise and strategic missions.', 'tsm-theme' ); ?>
		</p>
		<div class="flex flex-wrap gap-6 justify-center">
      <a class="inline-flex gap-2 justify-center items-center px-6 py-3 text-base font-bold text-white rounded-lg shadow-lg transition-all bg-primary hover:text-white shadow-primary/20 hover:shadow-primary/40 animate-bounce-subtle" href="#inquiry-form"">
      <?php echo esc_html__( 'Express Your Interest', 'tsm-theme' ); ?> <span class="material-symbols-outlined">expand_more</span>
			</a>
		</div>
	</div>
	<div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full blur-3xl bg-primary/10"></div>
	<div class="absolute -top-24 -right-24 w-96 h-96 rounded-full blur-3xl bg-primary/10"></div>
</section>

<!-- Partnership Form & Info Section -->
<section class="px-6 py-24" id="inquiry-form">
	<div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-16">
		<!-- Sidebar: Partnership Info & Contact -->
		<div class="space-y-12 lg:col-span-4">
			<div>
				<h3 class="mb-4 text-sm font-bold tracking-widest uppercase text-primary">
					<?php echo esc_html__( 'The Vision', 'tsm-theme' ); ?>
				</h3>
				<h4 class="text-3xl font-black text-[#0a2e16] dark:text-white mb-6">
					<?php echo esc_html__( 'Why Partner with Us?', 'tsm-theme' ); ?>
				</h4>
				<p class="mb-6 leading-relaxed text-gray-600 dark:text-gray-400">
					<?php echo esc_html__( 'We believe in breaking the cycle of poverty through the application of biblical principles and wise enterprise. Our mission goes beyond aid—we build sustainable futures.', 'tsm-theme' ); ?>
				</p>
				<ul class="space-y-4">
					<li class="flex gap-3 items-start">
						<span class="material-symbols-outlined text-primary">verified</span>
						<span class="font-medium text-gray-700 dark:text-gray-300"><?php echo esc_html__( 'Sustainable Financial Empowerment', 'tsm-theme' ); ?></span>
					</li>
					<li class="flex gap-3 items-start">
						<span class="material-symbols-outlined text-primary">verified</span>
						<span class="font-medium text-gray-700 dark:text-gray-300"><?php echo esc_html__( 'Strategic Rural Outreaches', 'tsm-theme' ); ?></span>
					</li>
					<li class="flex gap-3 items-start">
						<span class="material-symbols-outlined text-primary">verified</span>
						<span class="font-medium text-gray-700 dark:text-gray-300"><?php echo esc_html__( 'Global Missionary Support', 'tsm-theme' ); ?></span>
					</li>
				</ul>
			</div>
			<div class="p-8 bg-white dark:bg-[#0d1f12] border border-gray-100 dark:border-[#1d3a24] rounded-3xl shadow-sm space-y-8">
				<h4 class="text-xl font-bold text-[#0a2e16] dark:text-white"><?php echo esc_html__( 'Direct Channels', 'tsm-theme' ); ?></h4>
				<div class="space-y-6">
					<?php
					$partners_email  = tsm_get_theme_mod_cached( 'partners_email', 'partners@terryshaguy.org' );
					$contact_phone   = tsm_get_theme_mod_cached( 'contact_phone', '' );
					$contact_phone_2 = tsm_get_theme_mod_cached( 'contact_phone_2', '+234 (708) 143-6641' );
					$contact_address = tsm_get_theme_mod_cached( 'contact_address', '' );
					?>
					
					<!-- Email -->
					<?php if ( ! empty( $partners_email ) ) : ?>
						<div class="flex gap-4 items-start">
							<div class="flex justify-center items-center rounded-lg size-10 bg-primary/10 text-primary shrink-0">
								<span class="material-symbols-outlined">mail</span>
							</div>
							<div>
								<p class="mb-1 text-xs font-bold tracking-tighter text-gray-400 uppercase"><?php echo esc_html__( 'Email Inquiry', 'tsm-theme' ); ?></p>
								<a href="mailto:<?php echo esc_attr( $partners_email ); ?>" class="font-bold text-primary hover:underline">
									<?php echo esc_html( $partners_email ); ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
					
					<!-- Phone -->
					<?php if ( ! empty( $contact_phone ) || ! empty( $contact_phone_2 ) ) : ?>
						<div class="flex gap-4 items-start">
							<div class="flex justify-center items-center rounded-lg size-10 bg-primary/10 text-primary shrink-0">
								<span class="material-symbols-outlined">call</span>
							</div>
							<div class="flex-1">
								<p class="mb-2 text-xs font-bold tracking-tighter text-gray-400 uppercase"><?php echo esc_html__( 'Direct Call', 'tsm-theme' ); ?></p>
								<div class="flex flex-col gap-2">
									<?php if ( ! empty( $contact_phone ) ) : ?>
										<?php
										$phone_clean = preg_replace( '/[^0-9+]/', '', $contact_phone );
										$phone_whatsapp = preg_replace( '/[^0-9]/', '', $contact_phone ); // Remove all non-digits for WhatsApp
										?>
										<div class="flex gap-2 items-center">
											<a href="tel:<?php echo esc_attr( $phone_clean ); ?>" class="font-bold text-primary hover:underline">
												<?php echo esc_html( $contact_phone ); ?>
											</a>
											<a href="https://wa.me/<?php echo esc_attr( $phone_whatsapp ); ?>" target="_blank" rel="noopener" class="inline-flex justify-center items-center transition-colors text-primary hover:text-primary/80" aria-label="<?php echo esc_attr__( 'Open WhatsApp', 'tsm-theme' ); ?>">
												<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
													<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
												</svg>
											</a>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $contact_phone_2 ) ) : ?>
										<?php
										$phone_2_clean = preg_replace( '/[^0-9+]/', '', $contact_phone_2 );
										?>
										<a href="tel:<?php echo esc_attr( $phone_2_clean ); ?>" class="font-bold text-primary hover:underline">
											<?php echo esc_html( $contact_phone_2 ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					
					<!-- Physical Office -->
					<?php if ( ! empty( $contact_address ) ) : ?>
						<div class="flex gap-4 items-start">
							<div class="flex justify-center items-center rounded-lg size-10 bg-primary/10 text-primary shrink-0">
								<span class="material-symbols-outlined">location_on</span>
							</div>
							<div>
								<p class="mb-1 text-xs font-bold tracking-tighter text-gray-400 uppercase"><?php echo esc_html__( 'Office Address', 'tsm-theme' ); ?></p>
								<p class="text-sm text-gray-600 dark:text-gray-400"><?php echo wp_kses_post( $contact_address ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		
		<!-- Partnership Form -->
		<div class="lg:col-span-8">
			<div class="bg-white dark:bg-[#0a140d] p-10 md:p-12 rounded-3xl border border-gray-100 dark:border-[#1d3a24] shadow-2xl">
				<h3 class="text-2xl font-black text-[#0a2e16] dark:text-white mb-2">
					<?php echo esc_html__( 'Partnership Interest Form', 'tsm-theme' ); ?>
				</h3>
				<p class="mb-10 text-gray-500">
					<?php echo esc_html__( 'Fill out this form and our partnership coordinator will reach out to you within 48 hours.', 'tsm-theme' ); ?>
				</p>
				
				<!-- Success/Error Message Container (initially hidden) -->
				<div id="partner-message-container" class="hidden mb-6"></div>
				
				<!-- Form Fields -->
				<form id="partner-form" class="grid grid-cols-1 md:grid-cols-2 gap-8" method="post">
					<?php wp_nonce_field( 'tsm_partner_form', 'tsm_partner_nonce', false ); ?>
					
					<div class="space-y-2">
						<label class="block text-sm font-bold text-gray-700 dark:text-gray-300" for="fullname"><?php echo esc_html__( 'Full Name', 'tsm-theme' ); ?></label>
						<input class="w-full bg-gray-50 dark:bg-[#112115] border-gray-200 dark:border-[#1d3a24] rounded-lg py-3 px-4 focus:ring-accent focus:border-accent dark:text-white" id="fullname" name="fullname" placeholder="<?php echo esc_attr__( 'John Doe', 'tsm-theme' ); ?>" required type="text" autocomplete="name"/>
					</div>
					<div class="space-y-2">
						<label class="block text-sm font-bold text-gray-700 dark:text-gray-300" for="email"><?php echo esc_html__( 'Email Address', 'tsm-theme' ); ?></label>
						<input class="w-full bg-gray-50 dark:bg-[#112115] border-gray-200 dark:border-[#1d3a24] rounded-lg py-3 px-4 focus:ring-accent focus:border-accent dark:text-white" id="email" name="email" placeholder="<?php echo esc_attr__( 'john@example.com', 'tsm-theme' ); ?>" required type="email" autocomplete="email"/>
					</div>
					<div class="space-y-2">
						<label class="block text-sm font-bold text-gray-700 dark:text-gray-300" for="phone"><?php echo esc_html__( 'Phone Number', 'tsm-theme' ); ?></label>
						<input class="w-full bg-gray-50 dark:bg-[#112115] border-gray-200 dark:border-[#1d3a24] rounded-lg py-3 px-4 focus:ring-accent focus:border-accent dark:text-white" id="phone" name="phone" placeholder="<?php echo esc_attr__( '+234...', 'tsm-theme' ); ?>" required type="tel" autocomplete="tel"/>
					</div>
					<div class="space-y-2">
						<label class="block text-sm font-bold text-gray-700 dark:text-gray-300" for="location"><?php echo esc_html__( 'Location', 'tsm-theme' ); ?></label>
						<input class="w-full bg-gray-50 dark:bg-[#112115] border-gray-200 dark:border-[#1d3a24] rounded-lg py-3 px-4 focus:ring-accent focus:border-accent dark:text-white" id="location" name="location" placeholder="<?php echo esc_attr__( 'City, Country', 'tsm-theme' ); ?>" required type="text" autocomplete="country-name"/>
					</div>
					<div class="space-y-2 md:col-span-2">
						<label class="block text-sm font-bold text-gray-700 dark:text-gray-300" for="interest"><?php echo esc_html__( 'Area of Interest', 'tsm-theme' ); ?></label>
						<select class="w-full bg-gray-50 dark:bg-[#112115] border-gray-200 dark:border-[#1d3a24] rounded-lg py-3 px-4 focus:ring-accent focus:border-accent dark:text-white" id="interest" name="interest" required>
							<option value=""><?php echo esc_html__( 'Select an area...', 'tsm-theme' ); ?></option>
							<option value="missions"><?php echo esc_html__( 'Missions', 'tsm-theme' ); ?></option>
							<option value="empowerment"><?php echo esc_html__( 'Financial Empowerment', 'tsm-theme' ); ?></option>
							<option value="rural"><?php echo esc_html__( 'Rural Outreaches', 'tsm-theme' ); ?></option>
						</select>
					</div>
					<div class="space-y-2 md:col-span-2">
						<label class="block text-sm font-bold text-gray-700 dark:text-gray-300" for="message"><?php echo esc_html__( 'Additional Comments (Optional)', 'tsm-theme' ); ?></label>
						<textarea class="w-full bg-gray-50 dark:bg-[#112115] border-gray-200 dark:border-[#1d3a24] rounded-lg py-3 px-4 focus:ring-accent focus:border-accent dark:text-white h-32" id="message" name="message" placeholder="<?php echo esc_attr__( 'How would you like to contribute?', 'tsm-theme' ); ?>" autocomplete="off"></textarea>
					</div>
					<div class="flex justify-center pt-4 md:col-span-2">
						<button id="partner-submit" class="flex gap-2 justify-center items-center px-6 py-3 text-base font-bold text-white rounded-lg shadow-lg transition-all bg-primary hover:text-white shadow-primary/20 hover:shadow-primary/40 disabled:opacity-50 disabled:cursor-not-allowed" type="submit" disabled>
							<?php echo esc_html__( 'Submit Interest Inquiry', 'tsm-theme' ); ?> <span class="material-symbols-outlined !text-base">send</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<!-- Quote Section -->
<section class="bg-[#0a2e16] py-24 px-6 relative overflow-hidden">
	<div class="max-w-[900px] mx-auto text-center relative z-10">
		<span class="material-symbols-outlined text-primary !text-6xl mb-8">format_quote</span>
		<h2 class="mb-10 text-3xl italic font-black leading-tight text-white md:text-5xl">
			<?php echo esc_html__( '"Prosperity is not just for survival, but for the expansion of the Kingdom. Wise enterprise is the vehicle for lasting impact."', 'tsm-theme' ); ?>
		</h2>
		<div class="flex flex-col items-center">
			<div class="mb-6 w-16 h-1 rounded-full bg-primary"></div>
			<p class="text-primary uppercase tracking-[0.3em] font-bold text-sm">
				<?php echo esc_html__( 'Vision for Wise Enterprise', 'tsm-theme' ); ?>
			</p>
		</div>
	</div>
	<div class="absolute top-0 left-0 w-64 h-64 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 bg-primary/10"></div>
</section>

<!-- Account Details Modal -->
<?php
// Get customizable success message
$success_title = tsm_get_theme_mod_cached( 'partners_success_title', __( 'Thank You for Your Heart to Partner!', 'tsm-theme' ) );
$success_description = tsm_get_theme_mod_cached( 'partners_success_description', '' ); // Optional - empty by default
$account_section_title = tsm_get_theme_mod_cached( 'partners_account_section_title', __( 'Account Details for Contributions', 'tsm-theme' ) );
$account_notes = tsm_get_theme_mod_cached( 'partners_account_notes', '' );
$contact_phone = tsm_get_theme_mod_cached( 'contact_phone', '+234 (703) 030-8123' );

// Collect all accounts (up to 4)
$accounts = array();
for ( $i = 1; $i <= 4; $i++ ) {
	$account_number = tsm_get_theme_mod_cached( 'partners_account_' . $i . '_account_number', '' );
	if ( ! empty( $account_number ) ) {
		$accounts[] = array(
			'label'         => tsm_get_theme_mod_cached( 'partners_account_' . $i . '_label', sprintf( __( 'Account %d', 'tsm-theme' ), $i ) ),
			'bank_name'     => tsm_get_theme_mod_cached( 'partners_account_' . $i . '_bank_name', '' ),
			'account_name' => tsm_get_theme_mod_cached( 'partners_account_' . $i . '_account_name', '' ),
			'account_number' => $account_number,
			'routing_number' => tsm_get_theme_mod_cached( 'partners_account_' . $i . '_routing_number', '' ),
			'swift_code'    => tsm_get_theme_mod_cached( 'partners_account_' . $i . '_swift_code', '' ),
		);
	}
}

// Check if any account details are configured
$has_account_details = ! empty( $accounts );

if ( $has_account_details ) :
	$phone_whatsapp = preg_replace( '/[^0-9]/', '', $contact_phone );
	$account_count = count( $accounts );
	// Determine grid columns: 2 columns for 2-4 accounts, 1 column for mobile
	$grid_cols = $account_count >= 2 ? 'md:grid-cols-2' : 'md:grid-cols-1';
	// If 3 accounts, use 3 columns on larger screens
	if ( $account_count === 3 ) {
		$grid_cols = 'md:grid-cols-2 lg:grid-cols-3';
	} elseif ( $account_count === 4 ) {
		$grid_cols = 'md:grid-cols-2';
	}
?>
<div id="account-details-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
	<!-- Backdrop -->
	<div class="fixed inset-0 backdrop-blur-sm transition-opacity bg-black/60" id="modal-backdrop"></div>
	
	<!-- Modal Panel -->
	<div class="bg-white dark:bg-[#0d1f12] w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-[2rem] shadow-2xl relative border border-white/20">
		<button type="button" id="close-account-modal" class="absolute top-6 right-6 text-gray-400 transition-colors hover:text-primary" aria-label="<?php echo esc_attr__( 'Close modal', 'tsm-theme' ); ?>">
			<span class="material-symbols-outlined">close</span>
		</button>
		
		<div class="p-8 md:p-12">
			<!-- Success Message -->
			<div class="mb-10 text-center">
				<div class="flex justify-center items-center mx-auto mb-6 rounded-full size-20 bg-primary/10 text-primary">
					<span class="material-symbols-outlined !text-5xl">check_circle</span>
				</div>
				<h2 class="text-3xl md:text-4xl font-black text-[#0a2e16] dark:text-white mb-4" id="modal-title">
					<?php echo esc_html( $success_title ); ?>
				</h2>
				<?php if ( ! empty( $success_description ) ) : ?>
					<p class="mx-auto max-w-md text-lg leading-relaxed text-gray-600 dark:text-gray-300">
						<?php echo esc_html( $success_description ); ?>
					</p>
				<?php endif; ?>
			</div>
			
			<!-- Account Details -->
			<div class="mb-10 space-y-6">
				<h3 class="text-sm font-bold tracking-widest text-center uppercase text-primary">
					<?php echo esc_html( $account_section_title ); ?>
				</h3>
				<div class="grid grid-cols-1 <?php echo esc_attr( $grid_cols ); ?> gap-4">
					<?php foreach ( $accounts as $index => $account ) : ?>
						<!-- Account Card -->
						<div class="overflow-hidden relative p-6 bg-gray-50 rounded-2xl border border-gray-100 dark:bg-white/5 dark:border-white/10 group">
							<div class="absolute top-0 right-0 p-3 opacity-10">
								<span class="material-symbols-outlined !text-4xl"><?php echo ( $index % 2 === 0 ) ? 'payments' : 'public'; ?></span>
							</div>
							<?php if ( ! empty( $account['label'] ) ) : ?>
								<?php
								// If only one account, show "Account" instead of "Account 1" or numbered label
								$display_label = $account['label'];
								if ( $account_count === 1 ) {
									// Check if label matches default pattern "Account 1" or similar
									if ( preg_match( '/^Account\s+\d+$/i', $account['label'] ) ) {
										$display_label = __( 'Account', 'tsm-theme' );
									}
								}
								?>
								<p class="text-[10px] font-black text-primary uppercase tracking-tighter mb-3">
									<?php echo esc_html( $display_label ); ?>
								</p>
							<?php endif; ?>
							<div class="space-y-2">
								<?php if ( ! empty( $account['bank_name'] ) ) : ?>
									<div>
										<p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo esc_html__( 'Bank Name', 'tsm-theme' ); ?></p>
										<p class="text-sm font-bold text-[#0a2e16] dark:text-white"><?php echo esc_html( $account['bank_name'] ); ?></p>
									</div>
								<?php endif; ?>
								
								<?php if ( ! empty( $account['account_name'] ) ) : ?>
									<div>
										<p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo esc_html__( 'Account Name', 'tsm-theme' ); ?></p>
										<p class="text-sm font-bold text-[#0a2e16] dark:text-white uppercase"><?php echo esc_html( $account['account_name'] ); ?></p>
									</div>
								<?php endif; ?>
								
								<?php if ( ! empty( $account['account_number'] ) ) : ?>
									<div class="flex justify-between items-end">
										<div>
											<p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo esc_html__( 'Account Number', 'tsm-theme' ); ?></p>
											<p class="text-lg font-black text-primary"><?php echo esc_html( $account['account_number'] ); ?></p>
										</div>
										<button type="button" class="copy-account-number size-8 rounded-lg bg-white dark:bg-[#1a2e1f] shadow-sm border border-gray-100 dark:border-white/10 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-all" data-account-number="<?php echo esc_attr( $account['account_number'] ); ?>" aria-label="<?php echo esc_attr__( 'Copy account number', 'tsm-theme' ); ?>">
											<span class="material-symbols-outlined !text-lg">content_copy</span>
										</button>
									</div>
								<?php endif; ?>
								
								<?php if ( ! empty( $account['routing_number'] ) ) : ?>
									<div>
										<p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo esc_html__( 'Routing Number', 'tsm-theme' ); ?></p>
										<p class="text-sm font-bold text-[#0a2e16] dark:text-white"><?php echo esc_html( $account['routing_number'] ); ?></p>
									</div>
								<?php endif; ?>
								
								<?php if ( ! empty( $account['swift_code'] ) ) : ?>
									<div>
										<p class="text-[10px] text-gray-400 uppercase font-bold"><?php echo esc_html__( 'SWIFT Code', 'tsm-theme' ); ?></p>
										<p class="text-sm font-bold text-[#0a2e16] dark:text-white"><?php echo esc_html( $account['swift_code'] ); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
					
					<?php if ( ! empty( $account_notes ) && $account_count < 4 ) : ?>
						<!-- Notes Card (only show if there's space) -->
						<div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 dark:bg-white/5 dark:border-white/10">
							<p class="text-[10px] font-black text-primary uppercase tracking-tighter mb-3">
								<?php echo esc_html__( 'Additional Information', 'tsm-theme' ); ?>
							</p>
							<p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300">
								<?php echo wp_kses_post( nl2br( esc_html( $account_notes ) ) ); ?>
							</p>
						</div>
					<?php endif; ?>
				</div>
				
				<?php if ( ! empty( $account_notes ) && $account_count >= 4 ) : ?>
					<!-- Notes Card (show below if 4 accounts) -->
					<div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 dark:bg-white/5 dark:border-white/10">
						<p class="text-[10px] font-black text-primary uppercase tracking-tighter mb-3">
							<?php echo esc_html__( 'Additional Information', 'tsm-theme' ); ?>
						</p>
						<p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300">
							<?php echo wp_kses_post( nl2br( esc_html( $account_notes ) ) ); ?>
						</p>
					</div>
				<?php endif; ?>
			</div>
			
			<!-- Action Buttons -->
			<div class="flex flex-col gap-4">
				<?php
				$brochure_url = tsm_get_theme_mod_cached( 'partners_brochure_url', '' );
				if ( ! empty( $brochure_url ) ) :
				?>
					<a href="<?php echo esc_url( $brochure_url ); ?>" target="_blank" rel="noopener" class="flex gap-3 justify-center items-center px-8 py-4 w-full text-sm font-black tracking-widest text-white uppercase rounded-xl shadow-xl transition-all bg-primary hover:bg-primary/90 shadow-primary/20">
						<span class="material-symbols-outlined">download</span>
						<?php echo esc_html__( 'Download Partnership Brochure', 'tsm-theme' ); ?>
					</a>
				<?php endif; ?>
				
				<?php if ( ! empty( $contact_phone ) ) : ?>
					<a href="https://wa.me/<?php echo esc_attr( $phone_whatsapp ); ?>" target="_blank" rel="noopener" class="w-full bg-[#25D366] hover:bg-[#128C7E] text-white font-black py-4 px-8 rounded-xl shadow-xl shadow-[#25D366]/20 transition-all flex items-center justify-center gap-3 text-sm uppercase tracking-widest">
						<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
						</svg>
						<?php echo esc_html__( 'WhatsApp for Instant Support', 'tsm-theme' ); ?>
					</a>
				<?php endif; ?>
				
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-2 w-full text-xs font-bold text-center text-gray-400 transition-colors hover:text-gray-600 dark:hover:text-white">
					<?php echo esc_html__( 'Return to Homepage', 'tsm-theme' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<script>
(function() {
	'use strict';
	
	// Wait for DOM to be fully ready
	document.addEventListener('DOMContentLoaded', function() {
		const modal = document.getElementById('account-details-modal');
		const openButton = document.getElementById('open-account-modal');
		const closeButton = document.getElementById('close-account-modal');
		const backdrop = document.getElementById('modal-backdrop');
		const copyButtons = document.querySelectorAll('.copy-account-number');
		
		if (!modal) return;
		
		function openModal() {
			modal.classList.remove('hidden');
			modal.classList.add('flex');
			document.body.style.overflow = 'hidden';
			// Focus trap - focus first focusable element
			setTimeout(() => {
				const firstFocusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
				if (firstFocusable) firstFocusable.focus();
			}, 100);
		}
		
		// Expose openModal function globally so it can be called from main.js
		window.openAccountModal = openModal;
		
		function closeModal() {
			modal.classList.add('hidden');
			modal.classList.remove('flex');
			document.body.style.overflow = '';
		}
		
		// Open modal button (if exists)
		if (openButton) {
			openButton.addEventListener('click', openModal);
		}
		
		// Close modal button
		if (closeButton) {
			closeButton.addEventListener('click', closeModal);
		}
		
		// Close on backdrop click
		if (backdrop) {
			backdrop.addEventListener('click', closeModal);
		}
		
		// Close on Escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
				closeModal();
			}
		});
		
		// Copy account number functionality
		copyButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				const accountNumber = this.getAttribute('data-account-number');
				if (accountNumber) {
					navigator.clipboard.writeText(accountNumber).then(function() {
						// Visual feedback
						const icon = button.querySelector('.material-symbols-outlined');
						if (icon) {
							const originalText = icon.textContent;
							icon.textContent = 'check';
							button.classList.add('text-green-500');
							setTimeout(function() {
								icon.textContent = originalText;
								button.classList.remove('text-green-500');
							}, 2000);
						}
					}).catch(function(err) {
						console.error('Failed to copy:', err);
					});
				}
			});
		});
		
		// Expose openModal function globally so it can be called from main.js
		window.openAccountModal = openModal;
	});
})();
</script>

<?php
get_footer();
