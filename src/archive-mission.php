<?php
/**
 * The template for displaying mission archives
 *
 * @package TSM_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="w-full">
	<div class="relative min-h-[600px] flex items-center justify-center bg-cover bg-center" data-alt="<?php echo esc_attr( get_theme_mod( 'missions_hero_alt', 'Wide shot of a diverse mission team smiling together in a rural village' ) ); ?>" style='background-image: linear-gradient(rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.7) 100%), url("<?php echo esc_url( get_theme_mod( 'missions_hero_image', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCtzCEOTB5-TTBzqPY_TtOLn84ppGCAsXToDqXryoylyXhh9r7rNppAPxaYuniv-309Yg72GDc84WVHhQT-7wypZo-qBUU8tLNeGEiUTqVbFcDc41BrWYsZfUA7WjLpNm_MlKFfBk_Nlatn0-pZa6UpNm26Zn_BorXkuDMEGVnE3_xQNV68UJQS1CmG8g74VfV9A6W54YeYr6-mzilsHlPrgXUfsVRGk8AyR4YxGkX4pt1HFxgQIWhWVAJFqQ79L9553-nOUtRgrpg' ) ); ?>");'>
		<div class="max-w-[960px] px-6 text-center text-white">
			<div class="mb-6 inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-sm rounded-full border border-primary/30">
				<span class="material-symbols-outlined text-primary text-sm">public</span>
				<span class="text-white text-xs font-bold tracking-widest uppercase">
					<?php
					$missions_badge = get_theme_mod( 'missions_badge', 'Our Global Outreach' );
					echo esc_html( $missions_badge );
					?>
				</span>
			</div>
			<h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
				<?php
				$missions_hero_heading = get_theme_mod(
					'missions_hero_heading',
					'"Our heart is to see every nation touched by the love of Christ."'
				);
				echo wp_kses_post( $missions_hero_heading );
				?>
			</h1>
			<p class="text-lg md:text-xl font-normal opacity-90 mb-10 max-w-2xl mx-auto">
				<?php
				$missions_hero_description = get_theme_mod(
					'missions_hero_description',
					'David & Sarah Graham\'s vision for global transformation through mission, prayer, and sustainable community empowerment.'
				);
				echo esc_html( $missions_hero_description );
				?>
			</p>
			<div class="flex flex-col sm:flex-row gap-4 justify-center">
				<?php
				$vision_url = get_theme_mod( 'missions_vision_url', '' );
				if ( empty( $vision_url ) ) {
					$vision_url = home_url( '/our-ministries' );
				}
				$film_url = get_theme_mod( 'missions_film_url', '' );
				?>
				<a href="<?php echo esc_url( $vision_url ); ?>" class="min-w-[180px] rounded-lg h-14 px-8 bg-primary text-white hover:text-white text-base font-bold hover:scale-105 transition-transform flex items-center justify-center">
					<?php echo esc_html( get_theme_mod( 'missions_vision_button', 'Learn Our Vision' ) ); ?>
				</a>
				<?php if ( ! empty( $film_url ) ) : ?>
					<a href="<?php echo esc_url( $film_url ); ?>" class="min-w-[180px] rounded-lg h-14 px-8 bg-white/10 backdrop-blur-md text-white border border-white/30 text-base font-bold hover:bg-white/20 transition-all flex items-center justify-center">
						<?php echo esc_html( get_theme_mod( 'missions_film_button', 'Watch the Film' ) ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<!-- Impact Stats Bar -->
<section class="max-w-[1200px] mx-auto px-6 -mt-12 relative z-10 mb-20">
	<div class="bg-white dark:bg-[#1a2e1e] rounded-xl shadow-xl border border-[#cfe7d5] dark:border-[#2a4431] p-2">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-2">
			<div class="flex flex-col items-center justify-center p-8 border-b md:border-b-0 md:border-r border-[#cfe7d5] dark:border-[#2a4431]">
				<p class="text-accent text-sm font-bold uppercase tracking-wider mb-1">
					<?php echo esc_html( get_theme_mod( 'missions_stat_1_label', 'Villages Reached' ) ); ?>
				</p>
				<p class="text-3xl font-bold text-primary">
					<?php echo esc_html( get_theme_mod( 'missions_stat_1_value', '40+' ) ); ?>
				</p>
			</div>
			<div class="flex flex-col items-center justify-center p-8 border-b md:border-b-0 md:border-r border-[#cfe7d5] dark:border-[#2a4431]">
				<p class="text-accent text-sm font-bold uppercase tracking-wider mb-1">
					<?php echo esc_html( get_theme_mod( 'missions_stat_2_label', 'Lives Touched' ) ); ?>
				</p>
				<p class="text-3xl font-bold text-primary">
					<?php echo esc_html( get_theme_mod( 'missions_stat_2_value', '10k+' ) ); ?>
				</p>
			</div>
			<div class="flex flex-col items-center justify-center p-8">
				<p class="text-accent text-sm font-bold uppercase tracking-wider mb-1">
					<?php echo esc_html( get_theme_mod( 'missions_stat_3_label', 'Mission Partners' ) ); ?>
				</p>
				<p class="text-3xl font-bold text-primary">
					<?php echo esc_html( get_theme_mod( 'missions_stat_3_value', '100+' ) ); ?>
				</p>
			</div>
		</div>
	</div>
</section>

<!-- Main Content: Featured Missions + Sidebar -->
<section class="max-w-[1200px] mx-auto px-6 pb-20">
	<div class="flex flex-col lg:flex-row gap-16">
		<!-- Featured Missions Column -->
		<div class="flex-1">
			<div class="mb-12">
				<h2 class="text-3xl font-bold tracking-tight mb-4 text-accent">
					<?php echo esc_html( get_theme_mod( 'missions_stories_title', 'The Journey: Where We Go' ) ); ?>
				</h2>
				<div class="h-1.5 w-20 bg-primary rounded-full"></div>
			</div>
			
			<!-- Year Filter Pills -->
			<?php
			$total_missions = tsm_get_total_missions_count();
			$available_years = tsm_get_mission_years();
			
			// Show pills if we have 6+ missions AND there are years to filter by
			$show_pills = $total_missions >= 6 && ! empty( $available_years ) && is_array( $available_years ) && count( $available_years ) > 0;
			
			// Debug output only when explicitly enabled (reduces log file size)
			// Remove this block if not needed for debugging
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'TSM_DEBUG_MISSIONS' ) && TSM_DEBUG_MISSIONS ) {
				error_log( 'TSM Missions Debug - Total: ' . $total_missions . ', Years: ' . print_r( $available_years, true ) . ', Show Pills: ' . ( $show_pills ? 'YES' : 'NO' ) );
			}
			
			if ( $show_pills ) :
				$current_year = date( 'Y' );
				$recent_years = array();
				$older_years = array();
				
				// Separate recent years (last 4 years) from older years
				foreach ( $available_years as $year ) {
					$year_int = intval( $year );
					if ( $year_int >= ( $current_year - 3 ) ) {
						$recent_years[] = $year_int;
					} else {
						$older_years[] = $year_int;
					}
				}
				
				// Sort recent years descending
				rsort( $recent_years );
				
				// Limit to 4 most recent years for display
				$recent_years = array_slice( $recent_years, 0, 4 );
				
				// Check if there are older years (for Archives pill)
				$has_archives = ! empty( $older_years );
				
				// Only show filter pills if there are year categories to filter by
				$has_year_categories = ! empty( $recent_years ) || $has_archives;
				
				if ( $has_year_categories ) :
					?>
					<div class="mb-8 flex flex-wrap items-center justify-center gap-3" id="mission-year-filters">
					<button 
						class="mission-filter-pill active px-6 py-2 rounded-full font-semibold text-sm transition-all bg-primary text-white border border-primary"
						data-year="all"
						type="button"
					>
						All
					</button>
					<?php foreach ( $recent_years as $year ) : ?>
						<button 
							class="mission-filter-pill px-6 py-2 rounded-full font-semibold text-sm transition-all bg-white dark:bg-[#1a2e1e] text-primary border border-[#cfe7d5] dark:border-[#2a4431] hover:border-primary"
							data-year="<?php echo esc_attr( $year ); ?>"
							type="button"
						>
							<?php echo esc_html( $year ); ?>
						</button>
					<?php endforeach; ?>
					<?php if ( $has_archives ) : ?>
						<button 
							class="mission-filter-pill px-6 py-2 rounded-full font-semibold text-sm transition-all bg-white dark:bg-[#1a2e1e] text-primary border border-[#cfe7d5] dark:border-[#2a4431] hover:border-primary"
							data-year="archives"
							type="button"
						>
							Archives
						</button>
					<?php endif; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			
			<!-- Infinite Scroll Container -->
			<div id="missions-feed" class="space-y-16" data-exclude-ids="[]" style="contain: layout style paint;">
				<!-- Missions will be loaded here via AJAX -->
			</div>
			
			<!-- Loading Indicator -->
			<div id="missions-loading" class="hidden text-center py-8" style="min-height: 60px;">
				<div class="inline-flex items-center gap-2 text-primary">
					<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
						<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
						<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
					</svg>
					<span class="text-sm font-medium">Loading missions...</span>
				</div>
			</div>
			
			<!-- End of Feed Message -->
			<div id="missions-end" class="hidden text-center py-8">
				<p class="text-gray-500 dark:text-gray-400 text-sm">You've reached the end of the missions feed.</p>
			</div>
		</div>
		
		<!-- Partner Sidebar -->
		<div class="lg:w-[360px] space-y-8">
			<div class="sticky top-24">
				<div class="bg-white dark:bg-[#1a2e1e] rounded-xl border border-[#cfe7d5] dark:border-[#2a4431] overflow-hidden shadow-sm">
					<div class="bg-primary p-6">
						<h3 class="text-white text-xl font-bold">
							<?php echo esc_html( get_theme_mod( 'missions_sidebar_title', 'Partner with Us' ) ); ?>
						</h3>
						<p class="text-white/80 text-sm mt-1">
							<?php echo esc_html( get_theme_mod( 'missions_sidebar_subtitle', 'Transform lives together' ) ); ?>
						</p>
					</div>
					<div class="p-6 space-y-6">
						<?php
						$prayer_url = get_theme_mod( 'missions_action_1_url', '' );
						$give_url = get_theme_mod( 'missions_action_2_url', '' );
						if ( empty( $give_url ) ) {
							$give_url = home_url( '/partners' );
						}
						$join_url = get_theme_mod( 'missions_action_3_url', '' );
						?>
						<!-- Action 1: Pray -->
						<div class="flex gap-4">
							<div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
								<span class="material-symbols-outlined">auto_awesome</span>
							</div>
							<div>
								<h4 class="font-bold text-accent"><?php echo esc_html( get_theme_mod( 'missions_action_1_title', 'Pray with Us' ) ); ?></h4>
								<p class="text-sm opacity-70 mb-2 text-accent">
									<?php echo esc_html( get_theme_mod( 'missions_action_1_description', 'Receive weekly prayer points from the field.' ) ); ?>
								</p>
								<?php if ( ! empty( $prayer_url ) ) : ?>
									<?php $prayer_text = get_theme_mod( 'missions_action_1_button', 'Join Prayer Team' ); ?>
									<a class="text-primary font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all" href="<?php echo esc_url( $prayer_url ); ?>">
										<?php echo esc_html( $prayer_text ); ?> <span class="material-symbols-outlined !text-base">arrow_forward</span>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<div class="h-px bg-[#cfe7d5] dark:bg-[#2a4431]"></div>
						<!-- Action 2: Give -->
						<div class="flex gap-4">
							<div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
								<span class="material-symbols-outlined">volunteer_activism</span>
							</div>
							<div>
								<h4 class="font-bold text-accent"><?php echo esc_html( get_theme_mod( 'missions_action_2_title', 'Give Generously' ) ); ?></h4>
								<p class="text-sm opacity-70 mb-2 text-accent">
									<?php echo esc_html( get_theme_mod( 'missions_action_2_description', '100% of your gift goes directly to mission projects.' ) ); ?>
								</p>
								<?php if ( ! empty( $give_url ) ) : ?>
									<?php $give_text = get_theme_mod( 'missions_action_2_button', 'Donate Now' ); ?>
									<a class="text-primary font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all" href="<?php echo esc_url( $give_url ); ?>">
										<?php echo esc_html( $give_text ); ?> <span class="material-symbols-outlined !text-base">arrow_forward</span>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<!-- Action 3: Go -->
						<?php if ( ! empty( $join_url ) ) : ?>
							<div class="h-px bg-[#cfe7d5] dark:bg-[#2a4431]"></div>
							<div class="flex gap-4">
								<div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
									<span class="material-symbols-outlined">flight_takeoff</span>
								</div>
								<div>
									<h4 class="font-bold text-accent"><?php echo esc_html( get_theme_mod( 'missions_action_3_title', 'Join a Mission' ) ); ?></h4>
									<p class="text-sm opacity-70 mb-2 text-accent">
										<?php echo esc_html( get_theme_mod( 'missions_action_3_description', 'Applications for 2025 summer trips are now open.' ) ); ?>
									</p>
									<?php $join_text = get_theme_mod( 'missions_action_3_button', 'Apply to Join' ); ?>
									<a class="text-primary font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all" href="<?php echo esc_url( $join_url ); ?>">
										<?php echo esc_html( $join_text ); ?> <span class="material-symbols-outlined !text-base">arrow_forward</span>
									</a>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<?php
					// Query for upcoming missions only
					$upcoming_missions = new WP_Query( array(
						'post_type'      => 'mission',
						'posts_per_page' => 10,
						'post_status'    => 'publish',
						'meta_query'     => array(
							array(
								'key'     => 'mission_status',
								'value'   => 'upcoming',
								'compare' => '=',
							),
						),
						'orderby'         => 'meta_value',
						'meta_key'        => 'mission_date',
						'order'           => 'ASC',
					) );
					
					if ( $upcoming_missions->have_posts() ) :
						?>
						<div class="bg-[#f0f7f2] dark:bg-[#132818] p-6 text-center">
							<p class="text-xs uppercase font-bold tracking-widest text-accent mb-4">
								<?php echo esc_html( get_theme_mod( 'missions_trips_label', 'Upcoming Trips' ) ); ?>
							</p>
							<ul class="text-left space-y-3 mb-6">
								<?php
								while ( $upcoming_missions->have_posts() ) :
									$upcoming_missions->the_post();
									$mission_location = get_post_meta( get_the_ID(), 'mission_location', true );
									$mission_year = get_post_meta( get_the_ID(), 'mission_year', true );
									$mission_date = get_post_meta( get_the_ID(), 'mission_date', true );
									
									// Get year: use meta if set, otherwise use post published year
									$display_year = $mission_year ? $mission_year : get_the_date( 'Y' );
									
									// Build location display: first word of location + year (using display_year which falls back to post date)
									$location_display = '';
									if ( $mission_location ) {
										$location_words = explode( ' ', trim( $mission_location ) );
										$first_word = ! empty( $location_words[0] ) ? $location_words[0] : '';
										if ( $first_word && $display_year ) {
											$location_display = $first_word . ' ' . $display_year;
										} elseif ( $first_word ) {
											$location_display = $first_word;
										}
									} elseif ( $display_year ) {
										$location_display = $display_year;
									}
									?>
									<li class="flex justify-between text-sm">
										<a href="<?php echo esc_url( get_permalink() ); ?>" class="opacity-70 text-accent  transition-colors">
											<?php echo esc_html( $location_display ? $location_display : get_the_title() ); ?>
										</a>
										<span class="font-bold text-accent"><?php echo esc_html( $mission_date ? $mission_date : '' ); ?></span>
									</li>
									<?php
								endwhile;
								wp_reset_postdata();
								?>
							</ul>
						</div>
						<?php
					endif;
					?>
				</div>
				<!-- Testimonial Sidebar Mini -->
				<?php
				$testimonial_text = get_theme_mod( 'missions_testimonial_text', '' );
				$testimonial_author = get_theme_mod( 'missions_testimonial_author', '' );
				if ( ! empty( $testimonial_text ) ) :
					?>
					<div class="mt-8 p-6 bg-primary/5 rounded-xl border border-primary/20 italic">
						<span class="material-symbols-outlined text-primary mb-2">format_quote</span>
						<p class="text-sm opacity-80 leading-relaxed text-accent">
							<?php echo esc_html( $testimonial_text ); ?>
						</p>
						<?php if ( ! empty( $testimonial_author ) ) : ?>
							<p class="text-xs font-bold mt-4 not-italic text-accent">
								— <?php echo esc_html( $testimonial_author ); ?>
							</p>
						<?php endif; ?>
					</div>
					<?php
				endif;
				?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
