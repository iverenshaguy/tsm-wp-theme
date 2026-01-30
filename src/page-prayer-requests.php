<?php
/**
 * The template for displaying the Prayer Request page
 *
 * @package TSM_Theme
 */

get_header();
?>

<!-- Prayer Hero Section -->
<section class="bg-gradient-to-br from-primary via-primary/90 to-[#1a4d2e] py-16 text-center relative overflow-hidden">
	<div class="absolute inset-0 opacity-10">
		<svg class="w-full h-full" fill="none" preserveAspectRatio="none" viewBox="0 0 100 100">
			<path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
		</svg>
	</div>
	<div class="max-w-[1280px] mx-auto px-6 relative z-10">
		<div class="mb-6 inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-sm rounded-full border border-primary/30">
			<span class="mdi mdi-hands-pray text-primary text-sm"></span>
			<span class="text-white text-xs font-bold tracking-widest uppercase">
				Intercession
			</span>
		</div>
		<h1 class="text-white text-4xl md:text-6xl font-black mb-6">How Can We Pray for You?</h1>
		<p class="text-white/80 text-lg max-w-2xl mx-auto">
			"Again, truly I tell you that if two of you on earth agree about anything they ask for, it will be done for them by my Father in heaven." — Matthew 18:19
		</p>
	</div>
</section>

<!-- Prayer Request Form Section -->
<section class="max-w-[1280px] mx-auto px-6 -mt-10 mb-20 relative z-20">
	<div class="bg-white dark:bg-[#0a140d] p-8 md:p-12 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-[#1d3a24] max-w-4xl mx-auto">
		<?php
		// Display success/error messages
		if ( isset( $_GET['prayer'] ) ) {
			if ( 'success' === $_GET['prayer'] ) {
				?>
				<div class="mb-8 p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
					<p class="text-green-800 dark:text-green-200 font-semibold flex items-center gap-2">
						<span class="material-symbols-outlined">check_circle</span>
						Thank you! Your prayer request has been received. Our ministry team will be praying for you.
					</p>
				</div>
				<?php
			} elseif ( 'error' === $_GET['prayer'] ) {
				?>
				<div class="mb-8 p-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
					<p class="text-red-800 dark:text-red-200 font-semibold flex items-center gap-2">
						<span class="material-symbols-outlined">error</span>
						There was an error submitting your prayer request. Please try again or contact us directly.
					</p>
				</div>
				<?php
			}
		}
		?>

		<div class="mb-10 text-center">
			<h2 class="text-2xl font-black text-primary dark:text-white mb-2">Submit Your Request</h2>
			<p class="text-gray-500 dark:text-gray-400">Our ministry team intercedes daily for these requests.</p>
		</div>

		<form id="prayer-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="space-y-8" method="POST">
			<?php wp_nonce_field( 'tsm_prayer_request', 'tsm_prayer_nonce' ); ?>
			<input type="hidden" name="action" value="tsm_prayer_request">

			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<div class="space-y-2">
					<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="name">First Name</label>
					<input 
						class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent text-[#0d1b11] dark:text-white" 
						id="name" 
						name="name" 
						placeholder="John" 
						required 
						type="text"
						autocomplete="given-name"
						value="<?php echo isset( $_GET['name'] ) ? esc_attr( sanitize_text_field( $_GET['name'] ) ) : ''; ?>"
					/>
				</div>
				<div class="space-y-2">
					<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="email">Email Address</label>
					<input 
						class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent text-[#0d1b11] dark:text-white" 
						id="email" 
						name="email" 
						placeholder="john@example.com" 
						required 
						type="email"
						autocomplete="email"
						value="<?php echo isset( $_GET['email'] ) ? esc_attr( sanitize_email( $_GET['email'] ) ) : ''; ?>"
					/>
				</div>
			</div>

			<div class="space-y-2">
				<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="request-type">Request Type</label>
				<select 
					class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent text-[#0d1b11] dark:text-white" 
					id="request-type" 
					name="request-type"
				>
					<option value="healing">Healing &amp; Restoration</option>
					<option value="financial">Financial Breakthrough</option>
					<option value="family">Family &amp; Relationships</option>
					<option value="spiritual">Spiritual Growth</option>
					<option value="guidance">Career &amp; Guidance</option>
					<option value="other">Other</option>
				</select>
			</div>

			<div class="space-y-2">
				<label class="text-sm font-bold text-gray-700 dark:text-gray-300" for="message">Your Prayer Request</label>
				<textarea 
					class="w-full px-5 py-4 rounded-lg border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark focus:ring-accent focus:border-accent text-[#0d1b11] dark:text-white" 
					id="message" 
					name="message" 
					placeholder="Please share your heart with us..." 
					required 
					rows="6"
					autocomplete="off"
				><?php echo isset( $_GET['message'] ) ? esc_textarea( sanitize_textarea_field( $_GET['message'] ) ) : ''; ?></textarea>
			</div>

			<div class="flex items-center gap-3 bg-gray-50 dark:bg-white/5 p-4 rounded-xl">
				<input 
					class="size-5 rounded border-gray-300 text-primary focus:ring-accent" 
					id="confidential" 
					name="confidential" 
					type="checkbox" 
					value="1"
				/>
				<label class="text-sm font-medium text-gray-600 dark:text-gray-300" for="confidential">
					This is a confidential request (visible only to the lead ministry team)
				</label>
			</div>

			<div class="flex justify-center">
				<button id="prayer-submit" class="bg-primary text-white hover:text-white text-base font-bold px-6 py-3 rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" type="submit" disabled>
					Send Prayer Request <span class="material-symbols-outlined !text-base">auto_awesome</span>
				</button>
			</div>
		</form>
	</div>
</section>

<!-- Scriptures Section -->
<section class="bg-[#f3f7f4] dark:bg-[#0c1a11] py-24">
	<div class="max-w-[1280px] mx-auto px-6">
		<div class="text-center mb-16">
			<div class="mb-6 inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-sm rounded-full border border-primary/30">
				<span class="material-symbols-outlined text-primary text-sm">menu_book</span>
				<span class="text-primary text-xs font-bold tracking-widest uppercase">
					Word of God
				</span>
			</div>
			<h2 class="text-primary dark:text-white text-3xl md:text-4xl font-black">Scriptures for Encouragement</h2>
      <div class="h-1.5 w-24 bg-accent rounded-full mx-auto mt-6"></div>
		</div>
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<div class="bg-white dark:bg-[#0a140d] p-8 rounded-2xl shadow-sm border border-[#e7f3ea] dark:border-[#1d3a24] hover:shadow-md transition-shadow">
				<div class="text-accent mb-4">
					<span class="material-symbols-outlined !text-4xl">menu_book</span>
				</div>
				<p class="text-lg text-gray-700 dark:text-gray-300 mb-6 italic">"Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God."</p>
				<span class="font-bold text-primary dark:text-accent">— Philippians 4:6</span>
			</div>
			<div class="bg-white dark:bg-[#0a140d] p-8 rounded-2xl shadow-sm border border-[#e7f3ea] dark:border-[#1d3a24] hover:shadow-md transition-shadow">
				<div class="text-accent mb-4">
					<span class="material-symbols-outlined !text-4xl">self_improvement</span>
				</div>
				<p class="text-lg text-gray-700 dark:text-gray-300 mb-6 italic">"The prayer of a righteous person is powerful and effective."</p>
				<span class="font-bold text-primary dark:text-accent">— James 5:16</span>
			</div>
			<div class="bg-white dark:bg-[#0a140d] p-8 rounded-2xl shadow-sm border border-[#e7f3ea] dark:border-[#1d3a24] hover:shadow-md transition-shadow">
				<div class="text-accent mb-4">
					<span class="material-symbols-outlined !text-4xl">volunteer_activism</span>
				</div>
				<p class="text-lg text-gray-700 dark:text-gray-300 mb-6 italic">"Therefore I tell you, whatever you ask for in prayer, believe that you have received it, and it will be yours."</p>
				<span class="font-bold text-primary dark:text-accent">— Mark 11:24</span>
			</div>
			<div class="bg-white dark:bg-[#0a140d] p-8 rounded-2xl shadow-sm border border-[#e7f3ea] dark:border-[#1d3a24] hover:shadow-md transition-shadow">
				<div class="text-accent mb-4">
					<span class="material-symbols-outlined !text-4xl">favorite</span>
				</div>
				<p class="text-lg text-gray-700 dark:text-gray-300 mb-6 italic">"He heals the brokenhearted and binds up their wounds."</p>
				<span class="font-bold text-primary dark:text-accent">— Psalm 147:3</span>
			</div>
			<div class="bg-white dark:bg-[#0a140d] p-8 rounded-2xl shadow-sm border border-[#e7f3ea] dark:border-[#1d3a24] hover:shadow-md transition-shadow">
				<div class="text-accent mb-4">
					<span class="material-symbols-outlined !text-4xl">shield</span>
				</div>
				<p class="text-lg text-gray-700 dark:text-gray-300 mb-6 italic">"But the Lord is faithful, and he will strengthen you and protect you from the evil one."</p>
				<span class="font-bold text-primary dark:text-accent">— 2 Thessalonians 3:3</span>
			</div>
			<div class="bg-white dark:bg-[#0a140d] p-8 rounded-2xl shadow-sm border border-[#e7f3ea] dark:border-[#1d3a24] hover:shadow-md transition-shadow">
				<div class="text-accent mb-4">
					<span class="material-symbols-outlined !text-4xl">light_mode</span>
				</div>
				<p class="text-lg text-gray-700 dark:text-gray-300 mb-6 italic">"For with God nothing shall be impossible."</p>
				<span class="font-bold text-primary dark:text-accent">— Luke 1:37</span>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();