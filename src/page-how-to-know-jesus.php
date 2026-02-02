<?php
/**
 * The template for displaying the "How to Know Jesus" page
 *
 * @package TSM_Theme
 */

get_header();
?>

<main>
	<!-- Hero Section -->
	<section class="max-w-[1280px] mx-auto px-6 py-12 md:py-20">
		<div class="relative overflow-hidden rounded-[2rem] bg-[#f0f7f2] dark:bg-[#0c1a11]">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
				<div class="p-8 md:p-16">
					<?php
					$hero_label = tsm_get_theme_mod_cached( 'know_jesus_hero_label', 'Your Spiritual Journey' );
					$hero_title = tsm_get_theme_mod_cached( 'know_jesus_hero_title', 'The Greatest Love Story Ever Told' );
					$hero_description = tsm_get_theme_mod_cached( 'know_jesus_hero_description', 'Whether you\'re searching for meaning, peace, or a fresh start, the message of the Gospel is for you. Discover the path to a personal relationship with Jesus Christ.' );
					$hero_image = tsm_get_theme_mod_cached( 'know_jesus_hero_image', '' );
					?>
					<span class="text-accent font-bold tracking-[0.2em] uppercase text-sm mb-6 block"><?php echo esc_html( $hero_label ); ?></span>
					<h1 class="text-primary dark:text-white text-5xl md:text-7xl font-black leading-tight mb-6">
						<?php echo esc_html( $hero_title ); ?>
					</h1>
					<p class="text-gray-600 dark:text-gray-300 text-lg md:text-xl font-medium leading-relaxed mb-10">
						<?php echo esc_html( $hero_description ); ?>
					</p>
					<a class="inline-flex items-center justify-center bg-primary text-white hover:text-white text-base font-bold px-6 py-3 rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all gap-2 animate-bounce-subtle" href="#the-problem">
						<?php echo esc_html__( 'Begin the Journey', 'tsm-theme' ); ?> <span class="material-symbols-outlined">expand_more</span>
					</a>
				</div>
				<div class="relative h-[400px] lg:h-full min-h-[500px]">
					<?php if ( ! empty( $hero_image ) ) : ?>
						<img alt="<?php echo esc_attr( $hero_label ); ?>" class="absolute inset-0 w-full h-full object-cover" src="<?php echo esc_url( $hero_image ); ?>"/>
					<?php else : ?>
						<img alt="<?php echo esc_attr( $hero_label ); ?>" class="absolute inset-0 w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuChe23OlgVhdcSenvefaKTjCZpheYlSTKVqEEgGqU0hhluzbPXaLtMYfOpsAtVa08Q3rUKMWbe8PcMn6eQC1CHJXne05DMzfulTz6GjWsUfnKS_9NKFilOwiGq_I77I5ma6JlBvXk8ubLHAlo1R3QvjDMgvoETMXV4ot3yQ5Y6KR2wLS8_Jpa5eI4jGdeivhUwyFB1knBmp2FfGNV9KpjtiI2AYd-HnUdrKiBnaRNWvQfwLQtQGQQog5Uu44DAmw0cMc0fnsdghI1E"/>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Section 1: The Problem -->
	<section class="max-w-[1280px] mx-auto px-6 py-20" id="the-problem">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
			<div class="order-2 lg:order-1">
				<?php
				$problem_image = tsm_get_theme_mod_cached( 'know_jesus_problem_image', '' );
				?>
				<div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl">
					<?php if ( ! empty( $problem_image ) ) : ?>
						<img alt="<?php echo esc_attr__( 'Person looking for direction', 'tsm-theme' ); ?>" class="w-full h-full object-cover object-top" src="<?php echo esc_url( $problem_image ); ?>"/>
					<?php else : ?>
						<img alt="<?php echo esc_attr__( 'Person looking for direction', 'tsm-theme' ); ?>" class="w-full h-full object-cover object-top" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCABjnrc0r9aF0ymXrsuXoA_y63qUmRceNfm7-b-W3QLFx-_jBhjjnnVGmUHi1JEm7l7FhHLUdZOEfMk1PzE_eU6F7HrwQEDmwto5RUCeRubAsNwfC-9wwMU0riAWwb0Y_9tmfmFWPo1XWkCNWP6QrTlkXaszhMM1ZlybOPKJyISU35jy1P6Aj3VtxaAQg4d1_v2vmZKQ61G6BQ2isHq300-n6xVg7vO4_K5Osxy0FwqVebo6oJfgNo73mJl-9GrCFFdBZEguEjzcg"/>
					<?php endif; ?>
				</div>
			</div>
			<div class="order-1 lg:order-2">
				<div class="step-number">01</div>
				<h2 class="text-accent uppercase tracking-widest text-sm font-bold mb-4"><?php echo esc_html__( 'The Need', 'tsm-theme' ); ?></h2>
				<?php
				$problem_title = tsm_get_theme_mod_cached( 'know_jesus_problem_title', 'The Problem: We are Separated from God' );
				$problem_text_1 = tsm_get_theme_mod_cached( 'know_jesus_problem_text_1', 'God created us to be in a perfect relationship with Him. However, we chose our own way. This independent streak is what the Bible calls "sin."' );
				$problem_verse_1 = tsm_get_theme_mod_cached( 'know_jesus_problem_verse_1', '"For all have sinned and fall short of the glory of God." — Romans 3:23' );
				$problem_text_2 = tsm_get_theme_mod_cached( 'know_jesus_problem_text_2', 'This separation creates a void in our hearts—a gap that we often try to fill with success, relationships, or even religion, but nothing seems to bridge the divide.' );
        $problem_verse_2 = tsm_get_theme_mod_cached( 'know_jesus_problem_verse_2', '"Neither is there salvation in any other; for there is none other name under heaven given among men, whereby we must be saved." — Acts 4:12' );
				?>
				<h3 class="text-primary dark:text-white text-4xl md:text-5xl font-black leading-tight mb-8">
					<?php echo esc_html( $problem_title ); ?>
				</h3>
				<p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed mb-6">
					<?php echo esc_html( $problem_text_1 ); ?>
				</p>
				<div class="bg-primary/5 dark:bg-white/5 border-l-4 border-accent p-6 rounded-r-xl italic text-gray-700 dark:text-gray-300 mb-8">
					<?php echo esc_html( $problem_verse_1 ); ?>
				</div>
				<p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed mb-6">
					<?php echo esc_html( $problem_text_2 ); ?>
				</p>
        <div class="bg-primary/5 dark:bg-white/5 border-l-4 border-accent p-6 rounded-r-xl italic text-gray-700 dark:text-gray-300 mb-8">
					<?php echo esc_html( $problem_verse_2 ); ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Section 2: The Solution -->
	<section class="bg-primary text-white py-24 overflow-hidden relative">
		<div class="absolute left-0 bottom-0 w-1/4 h-full bg-accent/10 skew-x-12 -translate-x-1/2"></div>
		<div class="max-w-[1280px] mx-auto px-6 relative z-10">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
				<div>
					<div class="flex items-center justify-center w-12 h-12 rounded-full bg-white text-primary font-black text-xl mb-6">02</div>
					<h2 class="text-accent uppercase tracking-widest text-sm font-bold mb-4"><?php echo esc_html__( 'The Bridge', 'tsm-theme' ); ?></h2>
					<?php
					$solution_title = tsm_get_theme_mod_cached( 'know_jesus_solution_title', 'The Solution: Jesus is the Only Way' );
					$solution_text_1 = tsm_get_theme_mod_cached( 'know_jesus_solution_text_1', 'Jesus Christ is God\'s only provision for our sin. Through His death on the cross and His resurrection, He paid the penalty for our sins and bridged the gap between us and God.' );
					$solution_verse_1 = tsm_get_theme_mod_cached( 'know_jesus_solution_verse_1', '"I am the way, the truth, and the life. No one comes to the Father except through Me." — John 14:6' );
					$solution_text_2 = tsm_get_theme_mod_cached( 'know_jesus_solution_text_2', 'It isn\'t about what we can do to reach God, but what God has already done to reach us.' );
          $solution_verse_2 = tsm_get_theme_mod_cached( 'know_jesus_solution_verse_2', '"That if thou shalt confess with thy mouth the Lord Jesus, and shalt believe in thine heart that God hath raised him from the dead, thou shalt be saved." — Romans 10:9' );
					$solution_image = tsm_get_theme_mod_cached( 'know_jesus_solution_image', '' );
					$solution_image_text = tsm_get_theme_mod_cached( 'know_jesus_solution_image_text', 'Grace is a Gift' );
					?>
					<h3 class="text-4xl md:text-6xl font-black mb-8 leading-tight">
						<?php echo esc_html( $solution_title ); ?>
					</h3>
					<p class="text-white/80 text-lg mb-8 leading-relaxed">
						<?php echo esc_html( $solution_text_1 ); ?>
					</p>
					<div class="flex items-center gap-4 mb-8">
						<div class="p-3 bg-white/10 rounded-full">
							<span class="material-symbols-outlined text-accent">flare</span>
						</div>
						<p class="text-xl font-medium italic leading-tight"><?php echo esc_html( $solution_verse_1 ); ?></p>
					</div>
					<p class="text-white/80 text-lg mb-8 leading-relaxed">
						<?php echo esc_html( $solution_text_2 ); ?>
					</p>
          <div class="flex items-center gap-4 mb-8">
						<div class="p-3 bg-white/10 rounded-full">
							<span class="material-symbols-outlined text-accent">flare</span>
						</div>
						<p class="text-xl font-medium italic leading-tight"><?php echo esc_html( $solution_verse_2 ); ?></p>
					</div>
				</div>
				<div class="flex justify-center">
					<div class="relative w-full max-w-md aspect-[3/4] rounded-3xl shadow-2xl overflow-hidden border-8 border-white/10">
						<?php if ( ! empty( $solution_image ) ) : ?>
							<img alt="<?php echo esc_attr__( 'The Cross', 'tsm-theme' ); ?>" class="w-full h-full object-cover" src="<?php echo esc_url( $solution_image ); ?>"/>
						<?php else : ?>
							<img alt="<?php echo esc_attr__( 'The Cross', 'tsm-theme' ); ?>" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDZj5zaEKylaEXuuhmZPfeuHwrr9ow44yTl4F_FVumuxaJI-ZRL5GgzRoXdrE76xX7FqeI3Axg-H7eyHTOeQh_-0Z8wJBlJFApZSEBx6NO3_kHI4nF984PAtfqMW9agdQ0sBLHX6CFxKQYu20cJAdxjiRk33cjzio9xoIqSz-P-HhC7hMQ3i3fJ9QuVbiwAHkjUQE1L5g7D7UOJ3H7vo9sBFOp7kRL8sl--z_IdXP0Cp4Rzn3_k3oC0aPH_023rplqu2Nvdlo2TJDE"/>
						<?php endif; ?>
						<div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex items-end p-10">
							<p class="text-2xl font-black"><?php echo esc_html( $solution_image_text ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Section 3: The Choice -->
	<section class="max-w-[1280px] mx-auto px-6 py-24">
		<div class="text-center max-w-3xl mx-auto mb-20">
			<div class="step-number mx-auto">03</div>
			<h2 class="text-accent uppercase tracking-widest text-sm font-bold mb-4"><?php echo esc_html__( 'The Choice', 'tsm-theme' ); ?></h2>
			<?php
			$choice_title = tsm_get_theme_mod_cached( 'know_jesus_choice_title', 'Your Response: Will You Receive Him?' );
			$choice_description = tsm_get_theme_mod_cached( 'know_jesus_choice_description', 'Knowing these truths is not enough. We must individually receive Jesus Christ as Savior and Lord; then we can know and experience God\'s love and plan for our lives.' );
			$believe_title = tsm_get_theme_mod_cached( 'know_jesus_believe_title', 'Believe' );
			$believe_text = tsm_get_theme_mod_cached( 'know_jesus_believe_text', 'Believe that Jesus is the Son of God and that He died and rose again for you.' );
			$repent_title = tsm_get_theme_mod_cached( 'know_jesus_repent_title', 'Repent' );
			$repent_text = tsm_get_theme_mod_cached( 'know_jesus_repent_text', 'Turn from your own way and decide to follow God\'s direction for your life.' );
			$receive_title = tsm_get_theme_mod_cached( 'know_jesus_receive_title', 'Receive' );
			$receive_text = tsm_get_theme_mod_cached( 'know_jesus_receive_text', 'Invite Jesus to enter your heart and life as your personal Savior.' );
			$confess_title = tsm_get_theme_mod_cached( 'know_jesus_confess_title', 'Confess' );
			$confess_text = tsm_get_theme_mod_cached( 'know_jesus_confess_text', 'Confess Jesus audibly as the Lord over your life.' );
			$prayer_title = tsm_get_theme_mod_cached( 'know_jesus_prayer_title', 'A Suggested Prayer' );
			$prayer_text = tsm_get_theme_mod_cached( 'know_jesus_prayer_text', '"Heavenly Father, I thank you for the gift of Jesus Christ. I believe I am a sinner and the sacrificial work of Jesus on the cross is for someone like me. Jesus, you who rose from the dead, save me. Please be my Lord and personal saviour. Thank you, Father, for I receive a New Heart and new Spirit by faith and I am Born again in Jesus name, Amen."' );
			$prayer_question = tsm_get_theme_mod_cached( 'know_jesus_prayer_question', 'Does this prayer express the desire of your heart?' );
			?>
			<h3 class="text-primary dark:text-white text-4xl md:text-5xl font-black leading-tight mb-6">
				<?php echo esc_html( $choice_title ); ?>
			</h3>
			<p class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed mb-8">
				<?php echo esc_html( $choice_description ); ?>
			</p>
			<div class="bg-[#f3f7f4] dark:bg-[#0c1a11] p-8 rounded-2xl italic text-gray-700 dark:text-gray-300 max-w-4xl mx-auto mt-8 text-center">
				<p class="text-xl leading-relaxed mb-4">"For with the heart man believeth unto righteousness; and with the mouth, confession is made unto salvation." For whosoever shall call upon the name of the Lord shall be saved".</p>
				<p class="text-accent font-semibold text-base not-italic">Romans 10:13</p>
			</div>
		</div>
		<div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-20">
			<div class="p-8 rounded-2xl bg-white dark:bg-[#0a140d] border border-gray-100 dark:border-[#1d3a24] shadow-sm text-center">
				<span class="material-symbols-outlined text-accent !text-5xl mb-6">psychology_alt</span>
				<h4 class="text-xl font-bold text-primary dark:text-white mb-4"><?php echo esc_html( $believe_title ); ?></h4>
				<p class="text-gray-500 text-sm"><?php echo esc_html( $believe_text ); ?></p>
			</div>
			<div class="p-8 rounded-2xl bg-white dark:bg-[#0a140d] border border-gray-100 dark:border-[#1d3a24] shadow-sm text-center">
				<span class="material-symbols-outlined text-accent !text-5xl mb-6">change_circle</span>
				<h4 class="text-xl font-bold text-primary dark:text-white mb-4"><?php echo esc_html( $repent_title ); ?></h4>
				<p class="text-gray-500 text-sm"><?php echo esc_html( $repent_text ); ?></p>
			</div>
			<div class="p-8 rounded-2xl bg-white dark:bg-[#0a140d] border border-gray-100 dark:border-[#1d3a24] shadow-sm text-center">
				<span class="material-symbols-outlined text-accent !text-5xl mb-6">front_hand</span>
				<h4 class="text-xl font-bold text-primary dark:text-white mb-4"><?php echo esc_html( $receive_title ); ?></h4>
				<p class="text-gray-500 text-sm"><?php echo esc_html( $receive_text ); ?></p>
			</div>
			<div class="p-8 rounded-2xl bg-white dark:bg-[#0a140d] border border-gray-100 dark:border-[#1d3a24] shadow-sm text-center">
				<span class="material-symbols-outlined text-accent !text-5xl mb-6">mic</span>
				<h4 class="text-xl font-bold text-primary dark:text-white mb-4"><?php echo esc_html( $confess_title ); ?></h4>
				<p class="text-gray-500 text-sm"><?php echo esc_html( $confess_text ); ?></p>
			</div>
		</div>
		<div class="bg-[#f3f7f4] dark:bg-[#0c1a11] rounded-[2rem] p-8 md:p-16 border border-accent/20">
			<div class="max-w-3xl mx-auto text-center">
				<h3 class="text-2xl md:text-3xl font-black text-primary dark:text-white mb-8"><?php echo esc_html( $prayer_title ); ?></h3>
				<p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed italic mb-10">
					<?php echo esc_html( $prayer_text ); ?>
				</p>
				<div class="h-1.5 w-24 rounded-full bg-accent mx-auto"></div>
			</div>
		</div>
	</section>

	<!-- Section 4: Contact Form -->
	<section class="max-w-[1280px] mx-auto px-6 py-20 border-t border-gray-100 dark:border-[#1d3a24]" id="connect">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
			<div>
				<?php
				$form_title = tsm_get_theme_mod_cached( 'know_jesus_form_title', 'I Made a Decision Today' );
				$form_description_1 = tsm_get_theme_mod_cached( 'know_jesus_form_description_1', 'If you just prayed that prayer or have questions about what it means to follow Jesus, we are happy to hear from you.' );
				$form_description_2 = tsm_get_theme_mod_cached( 'know_jesus_form_description_2', 'You can also download our free "Next Steps" guide to help you begin your journey of faith.' );
				$form_benefit = tsm_get_theme_mod_cached( 'know_jesus_form_benefit', 'Download Free digital "Next Steps" guide' );
				$form_download_file_id = tsm_get_theme_mod_cached( 'know_jesus_form_download_file', '' );
				$form_download_file = ! empty( $form_download_file_id ) ? wp_get_attachment_url( $form_download_file_id ) : '';
				?>
				<h2 class="text-primary dark:text-white text-4xl font-black mb-6"><?php echo esc_html( $form_title ); ?></h2>
				<p class="text-gray-600 dark:text-gray-400 text-lg mb-4 leading-relaxed">
					<?php echo esc_html( $form_description_1 ); ?>
				</p>
				<p class="text-gray-600 dark:text-gray-400 text-lg mb-8 leading-relaxed">
					<?php echo esc_html( $form_description_2 ); ?>
				</p>
				<div class="flex flex-col gap-6">
					<div class="flex items-center gap-4">
						<div class="bg-accent/10 p-3 rounded-lg text-accent">
							<span class="material-symbols-outlined">auto_stories</span>
						</div>
						<?php if ( ! empty( $form_download_file ) ) : ?>
							<a href="<?php echo esc_url( $form_download_file ); ?>" download class="font-medium text-primary hover:text-primary hover:underline transition-colors">
								<?php echo esc_html( $form_benefit ); ?>
							</a>
						<?php else : ?>
							<p class="font-medium"><?php echo esc_html( $form_benefit ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="bg-white dark:bg-[#0a140d] p-8 md:p-12 rounded-3xl border border-gray-100 dark:border-[#1d3a24] shadow-xl">
				<?php
				$form_download_file_id = tsm_get_theme_mod_cached( 'know_jesus_form_download_file', '' );
				$form_download_file = ! empty( $form_download_file_id ) ? wp_get_attachment_url( $form_download_file_id ) : '';
				$form_benefit = tsm_get_theme_mod_cached( 'know_jesus_form_benefit', 'Download Free digital "Next Steps" guide' );
				?>
				
				<!-- Success/Error Message Container (initially hidden) -->
				<div id="decision-message-container" class="hidden mb-6"></div>
				
				<form id="decision-form" class="space-y-6" method="post" data-download-file="<?php echo esc_attr( $form_download_file ); ?>" data-download-benefit="<?php echo esc_attr( $form_benefit ); ?>">
					<?php wp_nonce_field( 'tsm_decision_form', 'tsm_decision_nonce', false ); ?>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300" for="first_name"><?php echo esc_html__( 'First Name', 'tsm-theme' ); ?></label>
							<input class="w-full rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark px-4 py-3 focus:ring-accent focus:border-accent" id="first_name" name="first_name" placeholder="<?php echo esc_attr__( 'David', 'tsm-theme' ); ?>" type="text" required autocomplete="given-name"/>
						</div>
						<div>
							<label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300" for="last_name"><?php echo esc_html__( 'Last Name', 'tsm-theme' ); ?></label>
							<input class="w-full rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark px-4 py-3 focus:ring-accent focus:border-accent" id="last_name" name="last_name" placeholder="<?php echo esc_attr__( 'Graham', 'tsm-theme' ); ?>" type="text" required autocomplete="family-name"/>
						</div>
					</div>
					<div>
						<label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300" for="email"><?php echo esc_html__( 'Email Address', 'tsm-theme' ); ?></label>
						<input class="w-full rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark px-4 py-3 focus:ring-accent focus:border-accent" id="email" name="email" placeholder="<?php echo esc_attr__( 'you@example.com', 'tsm-theme' ); ?>" type="email" required autocomplete="email"/>
					</div>
					<div>
						<label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300" for="decision"><?php echo esc_html__( 'My Decision', 'tsm-theme' ); ?></label>
						<select class="w-full rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark px-4 py-3 focus:ring-accent focus:border-accent" id="decision" name="decision" required>
							<option value=""><?php echo esc_html__( 'Select an option...', 'tsm-theme' ); ?></option>
							<option value="prayed"><?php echo esc_html__( 'I prayed to receive Jesus today', 'tsm-theme' ); ?></option>
							<option value="questions"><?php echo esc_html__( 'I have some questions first', 'tsm-theme' ); ?></option>
							<option value="recommit"><?php echo esc_html__( 'I want to recommit my life', 'tsm-theme' ); ?></option>
						</select>
					</div>
					<div>
						<label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300" for="message"><?php echo esc_html__( 'Message (Optional)', 'tsm-theme' ); ?></label>
						<textarea class="w-full rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark px-4 py-3 focus:ring-accent focus:border-accent" id="message" name="message" placeholder="<?php echo esc_attr__( 'How can we pray for you?', 'tsm-theme' ); ?>" rows="3" autocomplete="off"></textarea>
					</div>
					<div class="flex justify-center">
						<button id="decision-submit" class="bg-primary text-white hover:text-white text-base font-bold px-6 py-3 rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed" type="submit" disabled>
							<?php echo esc_html__( 'Connect With Us', 'tsm-theme' ); ?>
						</button>
					</div>
				</form>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
