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
				<span class="text-xs font-bold tracking-widest uppercase">
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
				<a href="<?php echo esc_url( $vision_url ); ?>" class="min-w-[180px] rounded-lg h-14 px-8 bg-primary text-background-dark text-base font-bold hover:scale-105 transition-transform flex items-center justify-center">
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
<section class="max-w-[1200px] mx-auto px-4 -mt-12 relative z-10 mb-20">
	<div class="bg-white dark:bg-[#1a2e1e] rounded-xl shadow-xl border border-[#cfe7d5] dark:border-[#2a4431] p-2">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-2">
			<div class="flex flex-col items-center justify-center p-8 border-b md:border-b-0 md:border-r border-[#cfe7d5] dark:border-[#2a4431]">
				<p class="text-[#4c9a5f] text-sm font-bold uppercase tracking-wider mb-1">
					<?php echo esc_html( get_theme_mod( 'missions_stat_1_label', 'Villages Reached' ) ); ?>
				</p>
				<p class="text-3xl font-bold text-primary">
					<?php echo esc_html( get_theme_mod( 'missions_stat_1_value', '40+' ) ); ?>
				</p>
			</div>
			<div class="flex flex-col items-center justify-center p-8 border-b md:border-b-0 md:border-r border-[#cfe7d5] dark:border-[#2a4431]">
				<p class="text-[#4c9a5f] text-sm font-bold uppercase tracking-wider mb-1">
					<?php echo esc_html( get_theme_mod( 'missions_stat_2_label', 'Lives Touched' ) ); ?>
				</p>
				<p class="text-3xl font-bold text-primary">
					<?php echo esc_html( get_theme_mod( 'missions_stat_2_value', '10k+' ) ); ?>
				</p>
			</div>
			<div class="flex flex-col items-center justify-center p-8">
				<p class="text-[#4c9a5f] text-sm font-bold uppercase tracking-wider mb-1">
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
<section class="max-w-[1200px] mx-auto px-4 md:px-10 pb-20">
	<div class="flex flex-col lg:flex-row gap-16">
		<!-- Featured Missions Column -->
		<div class="flex-1">
			<div class="mb-12">
				<h2 class="text-3xl font-bold tracking-tight mb-4">
					<?php echo esc_html( get_theme_mod( 'missions_stories_title', 'The Journey: Where We Go' ) ); ?>
				</h2>
				<div class="h-1.5 w-20 bg-primary rounded-full"></div>
			</div>
			<!-- Timeline Stories -->
			<div class="space-y-16 mb-20">
				<?php
				// Query for featured missions: prioritize Ongoing, then Completed (up to 3 total)
				$ongoing_missions = new WP_Query( array(
					'post_type'      => 'mission',
					'posts_per_page' => 3,
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'     => 'mission_status',
							'value'   => 'ongoing',
							'compare' => '=',
						),
					),
					'orderby'        => 'date',
					'order'          => 'DESC',
				) );
				
				$completed_missions = new WP_Query( array(
					'post_type'      => 'mission',
					'posts_per_page' => 3,
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'     => 'mission_status',
							'value'   => 'completed',
							'compare' => '=',
						),
					),
					'orderby'        => 'date',
					'order'          => 'DESC',
				) );
				
				// Combine missions: Ongoing first, then Completed (up to 3 total)
				$featured_missions = array();
				$ongoing_count = 0;
				$completed_count = 0;
				
				if ( $ongoing_missions->have_posts() ) {
					while ( $ongoing_missions->have_posts() && $ongoing_count < 3 ) {
						$ongoing_missions->the_post();
						$featured_missions[] = get_the_ID();
						$ongoing_count++;
					}
					wp_reset_postdata();
				}
				
				$remaining_slots = 3 - count( $featured_missions );
				if ( $remaining_slots > 0 && $completed_missions->have_posts() ) {
					while ( $completed_missions->have_posts() && $completed_count < $remaining_slots ) {
						$completed_missions->the_post();
						$featured_missions[] = get_the_ID();
						$completed_count++;
					}
					wp_reset_postdata();
				}
				
				if ( ! empty( $featured_missions ) ) :
					$final_query = new WP_Query( array(
						'post_type'      => 'mission',
						'post__in'       => $featured_missions,
						'posts_per_page' => 3,
						'post_status'    => 'publish',
						'orderby'        => 'post__in',
					) );
					
					if ( $final_query->have_posts() ) :
						$mission_index = 0;
						while ( $final_query->have_posts() ) :
							$final_query->the_post();
							$mission_location = get_post_meta( get_the_ID(), 'mission_location', true );
							$mission_year = get_post_meta( get_the_ID(), 'mission_year', true );
							$mission_date = get_post_meta( get_the_ID(), 'mission_date', true );
							$mission_status = get_post_meta( get_the_ID(), 'mission_status', true );
							$mission_subtitle = get_post_meta( get_the_ID(), 'mission_subtitle', true );
							$mission_quote = get_post_meta( get_the_ID(), 'mission_quote', true );
							$mission_summary = get_post_meta( get_the_ID(), 'mission_summary', true );
							
							// Determine icon based on mission status or use default
							$icon = 'public';
							if ( $mission_status === 'completed' ) {
								$icon = 'check_circle';
							} elseif ( $mission_status === 'ongoing' ) {
								$icon = 'radio_button_checked';
							}
							
							// Build location display: first word of location + year
							$location_display = '';
							if ( $mission_location ) {
								$location_words = explode( ' ', trim( $mission_location ) );
								$first_word = ! empty( $location_words[0] ) ? rtrim( $location_words[0], ',' ) : '';
								if ( $first_word && $mission_year ) {
									$location_display = $first_word . ' ' . $mission_year;
								} elseif ( $first_word ) {
									$location_display = $first_word;
								}
							} elseif ( $mission_year ) {
								$location_display = $mission_year;
							}
							
							$display_title = $mission_subtitle ? $mission_subtitle : get_the_title();
							?>
							<div class="relative group">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="overflow-hidden rounded-xl mb-6 shadow-lg">
										<a href="<?php echo esc_url( get_permalink() ); ?>">
											<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
										</a>
									</div>
								<?php endif; ?>
								<div class="flex gap-6">
									<div class="flex flex-col items-center">
										<div class="size-10 rounded-full bg-primary flex items-center justify-center text-background-dark shrink-0">
											<span class="material-symbols-outlined font-bold"><?php echo esc_html( $icon ); ?></span>
										</div>
										<?php if ( $mission_index < 2 ) : ?>
											<div class="w-0.5 h-full bg-[#cfe7d5] dark:bg-[#2a4431] mt-4"></div>
										<?php endif; ?>
									</div>
									<div class="pb-8">
										<?php if ( $location_display ) : ?>
											<span class="text-primary font-bold text-sm uppercase"><?php echo esc_html( $location_display ); ?></span>
										<?php endif; ?>
										<h3 class="text-2xl font-bold mt-1 mb-3">
											<a href="<?php echo esc_url( get_permalink() ); ?>" class="hover:text-primary transition-colors">
												<?php echo esc_html( $display_title ); ?>
											</a>
										</h3>
										<?php if ( ! empty( $mission_quote ) ) : ?>
											<p class="text-[#4c9a5f] dark:text-[#8bc39d] leading-relaxed mb-4 italic">
												<?php echo esc_html( $mission_quote ); ?>
											</p>
										<?php endif; ?>
										<?php if ( ! empty( $mission_summary ) ) : ?>
											<p class="text-base leading-relaxed opacity-80">
												<?php echo esc_html( $mission_summary ); ?>
											</p>
										<?php elseif ( has_excerpt() ) : ?>
											<p class="text-base leading-relaxed opacity-80">
												<?php echo esc_html( get_the_excerpt() ); ?>
											</p>
										<?php elseif ( get_the_content() ) : ?>
											<p class="text-base leading-relaxed opacity-80">
												<?php echo esc_html( wp_trim_words( get_the_content(), 30 ) ); ?>
											</p>
										<?php endif; ?>
										<a href="<?php echo esc_url( get_permalink() ); ?>" class="inline-flex items-center gap-1 text-primary text-sm font-bold mt-4 hover:underline">
											<?php _e( 'Read More', 'tsm-theme' ); ?> <span class="material-symbols-outlined text-xs">arrow_forward</span>
										</a>
									</div>
								</div>
							</div>
							<?php
							$mission_index++;
						endwhile;
						wp_reset_postdata();
					endif;
				endif;
				?>
			</div>
			
			<!-- All Missions Grid -->
			<?php
			// Get all missions excluding featured ones for the grid
			$all_missions_args = array(
				'post_type'      => 'mission',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
			
			if ( ! empty( $featured_missions ) ) {
				$all_missions_args['post__not_in'] = $featured_missions;
			}
			
			$all_missions = new WP_Query( $all_missions_args );
			
			if ( $all_missions->have_posts() ) :
				?>
				<div class="mb-12">
					<h2 class="text-3xl font-bold tracking-tight mb-4"><?php _e( 'All Missions', 'tsm-theme' ); ?></h2>
					<div class="h-1.5 w-20 bg-primary rounded-full"></div>
				</div>
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
					<?php
					while ( $all_missions->have_posts() ) :
						$all_missions->the_post();
						$mission_location = get_post_meta( get_the_ID(), 'mission_location', true );
						$mission_year = get_post_meta( get_the_ID(), 'mission_year', true );
						$mission_date = get_post_meta( get_the_ID(), 'mission_date', true );
						$mission_status = get_post_meta( get_the_ID(), 'mission_status', true );
						$mission_subtitle = get_post_meta( get_the_ID(), 'mission_subtitle', true );
						$mission_summary = get_post_meta( get_the_ID(), 'mission_summary', true );
						$display_title = $mission_subtitle ? $mission_subtitle : get_the_title();
						
						// Build location display: first word of location + year
						$location_display = '';
						if ( $mission_location ) {
							$location_words = explode( ' ', trim( $mission_location ) );
							$first_word = ! empty( $location_words[0] ) ? rtrim( $location_words[0], ',' ) : '';
							if ( $first_word && $mission_year ) {
								$location_display = $first_word . ' ' . $mission_year;
							} elseif ( $first_word ) {
								$location_display = $first_word;
							}
						} elseif ( $mission_year ) {
							$location_display = $mission_year;
						}
						?>
						<article class="bg-white dark:bg-[#1a2e1e] rounded-xl border border-[#cfe7d5] dark:border-[#2a4431] overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php echo esc_url( get_permalink() ); ?>">
									<div class="overflow-hidden">
										<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-500' ) ); ?>
									</div>
								</a>
							<?php endif; ?>
							<div class="p-6">
								<?php if ( $mission_status ) : ?>
									<span class="inline-block px-2 py-1 text-xs font-bold uppercase rounded mb-3 <?php echo esc_attr( $mission_status === 'upcoming' ? 'bg-primary/20 text-primary' : ( $mission_status === 'ongoing' ? 'bg-primary/20 text-primary' : 'bg-gray-200 text-gray-600' ) ); ?>">
										<?php echo esc_html( ucfirst( $mission_status ) ); ?>
									</span>
								<?php endif; ?>
								<h2 class="text-xl font-bold mb-2">
									<a href="<?php echo esc_url( get_permalink() ); ?>" class="text-primary dark:text-white hover:text-primary transition-colors">
										<?php echo esc_html( $display_title ); ?>
									</a>
								</h2>
								<?php if ( $location_display || $mission_date ) : ?>
									<div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-3">
										<?php if ( $location_display ) : ?>
											<span class="flex items-center gap-1">
												<span class="material-symbols-outlined text-xs">location_on</span>
												<?php echo esc_html( $location_display ); ?>
											</span>
										<?php endif; ?>
										<?php if ( $mission_date ) : ?>
											<span class="flex items-center gap-1">
												<span class="material-symbols-outlined text-xs">event</span>
												<?php echo esc_html( $mission_date ); ?>
											</span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $mission_summary ) ) : ?>
									<p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
										<?php echo esc_html( $mission_summary ); ?>
									</p>
								<?php elseif ( has_excerpt() ) : ?>
									<p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
										<?php echo esc_html( get_the_excerpt() ); ?>
									</p>
								<?php endif; ?>
								<a href="<?php echo esc_url( get_permalink() ); ?>" class="inline-flex items-center gap-1 text-primary text-sm font-bold mt-4 hover:underline">
									<?php _e( 'Learn More', 'tsm-theme' ); ?> <span class="material-symbols-outlined text-xs">arrow_forward</span>
								</a>
							</div>
						</article>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				<?php
			endif;
			?>
		</div>
		
		<!-- Partner Sidebar -->
		<div class="lg:w-[360px] space-y-8">
			<div class="sticky top-24">
				<div class="bg-white dark:bg-[#1a2e1e] rounded-xl border border-[#cfe7d5] dark:border-[#2a4431] overflow-hidden shadow-sm">
					<div class="bg-primary p-6">
						<h3 class="text-background-dark text-xl font-bold">
							<?php echo esc_html( get_theme_mod( 'missions_sidebar_title', 'Partner with Us' ) ); ?>
						</h3>
						<p class="text-background-dark/80 text-sm mt-1">
							<?php echo esc_html( get_theme_mod( 'missions_sidebar_subtitle', 'Transform lives together' ) ); ?>
						</p>
					</div>
					<div class="p-6 space-y-6">
						<?php
						$prayer_url = get_theme_mod( 'missions_action_1_url', '' );
						$give_url = get_theme_mod( 'missions_action_2_url', '' );
						if ( empty( $give_url ) ) {
							$give_url = home_url( '/partner' );
						}
						$join_url = get_theme_mod( 'missions_action_3_url', '' );
						?>
						<!-- Action 1: Pray -->
						<div class="flex gap-4">
							<div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary shrink-0">
								<span class="material-symbols-outlined">auto_awesome</span>
							</div>
							<div>
								<h4 class="font-bold"><?php echo esc_html( get_theme_mod( 'missions_action_1_title', 'Pray with Us' ) ); ?></h4>
								<p class="text-sm opacity-70 mb-2">
									<?php echo esc_html( get_theme_mod( 'missions_action_1_description', 'Receive weekly prayer points from the field.' ) ); ?>
								</p>
								<?php if ( ! empty( $prayer_url ) ) : ?>
									<?php $prayer_text = get_theme_mod( 'missions_action_1_button', 'Join Prayer Team' ); ?>
									<a class="text-primary text-sm font-bold flex items-center gap-1 hover:underline" href="<?php echo esc_url( $prayer_url ); ?>">
										<?php echo esc_html( $prayer_text ); ?> <span class="material-symbols-outlined text-xs">arrow_forward</span>
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
								<h4 class="font-bold"><?php echo esc_html( get_theme_mod( 'missions_action_2_title', 'Give Generously' ) ); ?></h4>
								<p class="text-sm opacity-70 mb-2">
									<?php echo esc_html( get_theme_mod( 'missions_action_2_description', '100% of your gift goes directly to mission projects.' ) ); ?>
								</p>
								<?php if ( ! empty( $give_url ) ) : ?>
									<?php $give_text = get_theme_mod( 'missions_action_2_button', 'Donate Now' ); ?>
									<a class="text-primary text-sm font-bold flex items-center gap-1 hover:underline" href="<?php echo esc_url( $give_url ); ?>">
										<?php echo esc_html( $give_text ); ?> <span class="material-symbols-outlined text-xs">arrow_forward</span>
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
									<h4 class="font-bold"><?php echo esc_html( get_theme_mod( 'missions_action_3_title', 'Join a Mission' ) ); ?></h4>
									<p class="text-sm opacity-70 mb-2">
										<?php echo esc_html( get_theme_mod( 'missions_action_3_description', 'Applications for 2025 summer trips are now open.' ) ); ?>
									</p>
									<?php $join_text = get_theme_mod( 'missions_action_3_button', 'Apply to Join' ); ?>
									<a class="text-primary text-sm font-bold flex items-center gap-1 hover:underline" href="<?php echo esc_url( $join_url ); ?>">
										<?php echo esc_html( $join_text ); ?> <span class="material-symbols-outlined text-xs">arrow_forward</span>
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
							<p class="text-xs uppercase font-bold tracking-widest text-[#4c9a5f] mb-4">
								<?php echo esc_html( get_theme_mod( 'missions_trips_label', 'Upcoming Trips' ) ); ?>
							</p>
							<ul class="text-left space-y-3 mb-6">
								<?php
								while ( $upcoming_missions->have_posts() ) :
									$upcoming_missions->the_post();
									$mission_location = get_post_meta( get_the_ID(), 'mission_location', true );
									$mission_year = get_post_meta( get_the_ID(), 'mission_year', true );
									$mission_date = get_post_meta( get_the_ID(), 'mission_date', true );
									
									// Build location display: first word of location + year
									$location_display = '';
									if ( $mission_location ) {
										$location_words = explode( ' ', trim( $mission_location ) );
										$first_word = ! empty( $location_words[0] ) ? $location_words[0] : '';
										if ( $first_word && $mission_year ) {
											$location_display = $first_word . ' ' . $mission_year;
										} elseif ( $first_word ) {
											$location_display = $first_word;
										}
									} elseif ( $mission_year ) {
										$location_display = $mission_year;
									}
									?>
									<li class="flex justify-between text-sm">
										<a href="<?php echo esc_url( get_permalink() ); ?>" class="opacity-70 hover:text-primary transition-colors">
											<?php echo esc_html( $location_display ? $location_display : get_the_title() ); ?>
										</a>
										<span class="font-bold"><?php echo esc_html( $mission_date ? $mission_date : '' ); ?></span>
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
						<p class="text-sm opacity-80 leading-relaxed">
							<?php echo esc_html( $testimonial_text ); ?>
						</p>
						<?php if ( ! empty( $testimonial_author ) ) : ?>
							<p class="text-xs font-bold mt-4 not-italic">
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
