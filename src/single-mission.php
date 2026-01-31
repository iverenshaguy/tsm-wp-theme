<?php
/**
 * The template for displaying single mission posts
 *
 * @package TSM_Theme
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		
		$mission_location = get_post_meta( get_the_ID(), 'mission_location', true );
		$mission_year = get_post_meta( get_the_ID(), 'mission_year', true );
		// Get year: use meta if set, otherwise use post published year
		$display_year = $mission_year ? $mission_year : get_the_date( 'Y' );
		$mission_date = get_post_meta( get_the_ID(), 'mission_date', true );
		$mission_status = get_post_meta( get_the_ID(), 'mission_status', true );
		$mission_subtitle = get_post_meta( get_the_ID(), 'mission_subtitle', true );
		$mission_quote = get_post_meta( get_the_ID(), 'mission_quote', true );
		$mission_summary = get_post_meta( get_the_ID(), 'mission_summary', true );
		$mission_hero_image = get_post_meta( get_the_ID(), 'mission_hero_image', true );
		$mission_support_url = get_post_meta( get_the_ID(), 'mission_support_url', true );
		$mission_impact_title = get_post_meta( get_the_ID(), 'mission_impact_title', true );
		$mission_impact_description = get_post_meta( get_the_ID(), 'mission_impact_description', true );
		$mission_stats = get_post_meta( get_the_ID(), 'mission_stats', true );
		$mission_prayer_needs = get_post_meta( get_the_ID(), 'mission_prayer_needs', true );
		$mission_gallery_post_id = get_post_meta( get_the_ID(), 'mission_gallery_post', true );
		$mission_gallery_link = get_post_meta( get_the_ID(), 'mission_gallery_link', true );
		
		if ( ! is_array( $mission_stats ) ) {
			$mission_stats = array();
		}
		if ( ! is_array( $mission_prayer_needs ) ) {
			$mission_prayer_needs = array();
		}
		
		// Get gallery images from gallery post
		$gallery_images = array();
		if ( $mission_gallery_post_id ) {
			$gallery_post_images = get_post_meta( $mission_gallery_post_id, 'gallery_images', true );
			if ( is_array( $gallery_post_images ) && ! empty( $gallery_post_images ) ) {
				foreach ( $gallery_post_images as $image_id ) {
					if ( ! empty( $image_id ) ) {
						$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
						$gallery_images[] = array(
							'image' => $image_id,
							'alt'   => $image_alt ? $image_alt : get_the_title( $mission_gallery_post_id ),
						);
					}
				}
			}
		}
		
		// Determine gallery link URL
		$gallery_link_url = '';
		if ( $mission_gallery_link ) {
			$gallery_link_url = $mission_gallery_link;
		} elseif ( $mission_gallery_post_id ) {
			$gallery_link_url = get_permalink( $mission_gallery_post_id );
		}
		
		// Get hero image URL
		$hero_image_url = '';
		if ( $mission_hero_image ) {
			$hero_image_url = wp_get_attachment_image_url( $mission_hero_image, 'full' );
		} elseif ( has_post_thumbnail() ) {
			$hero_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		}
		
		$display_title = $mission_subtitle ? $mission_subtitle : get_the_title();
		
		// Build location tagline: first word + year
		$location_tagline = '';
		if ( $mission_location ) {
			$location_words = explode( ' ', trim( $mission_location ) );
			$first_word = ! empty( $location_words[0] ) ? rtrim( $location_words[0], ',' ) : '';
			if ( $first_word && $display_year ) {
				$location_tagline = $first_word . ' ' . $display_year;
			} elseif ( $first_word ) {
				$location_tagline = $first_word;
			}
		} elseif ( $display_year ) {
			$location_tagline = $display_year;
		}
		?>

		<!-- Hero Section -->
		<section class="w-full relative h-[450px] md:h-[500px] flex items-center justify-center overflow-hidden">
			<?php if ( $hero_image_url ) : ?>
				<div class="absolute inset-0 bg-cover bg-center" style='background-image: linear-gradient(rgba(10, 74, 28, 0.5) 0%, rgba(16, 34, 21, 0.9) 100%), url("<?php echo esc_url( $hero_image_url ); ?>");'></div>
			<?php else : ?>
				<div class="absolute inset-0 bg-gradient-to-br from-accent to-[#102215]"></div>
			<?php endif; ?>
			<div class="relative z-10 max-w-[1200px] px-6 text-center text-white">
				<div class="mb-6 flex flex-col items-center justify-center gap-3">
					<div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/20 backdrop-blur-sm rounded-full border border-primary/30">
						<span class="material-symbols-outlined text-primary text-sm">explore</span>
						<span class="text-primary text-xs font-bold tracking-widest uppercase">Mission Overview</span>
					</div>
					<?php if ( $mission_date ) : ?>
						<span class="text-sm font-medium opacity-80"><?php echo esc_html( $mission_date ); ?></span>
					<?php endif; ?>
				</div>
				<h1 class="text-4xl md:text-6xl font-bold leading-tight mb-4 drop-shadow-lg">
					<?php echo esc_html( $display_title ); ?>
				</h1>
				<?php if ( $mission_location ) : ?>
					<p class="text-lg md:text-xl font-medium opacity-90 max-w-3xl mx-auto flex items-center justify-center gap-2">
						<span class="material-symbols-outlined text-primary">location_on</span>
						<?php echo esc_html( $mission_location ); ?>
					</p>
				<?php endif; ?>
			</div>
		</section>

		<div class="max-w-[1200px] mx-auto px-6 -mt-16 relative z-20">
			<div class="mb-8 flex justify-between items-end">
				<a class="inline-flex items-center gap-2 text-white hover:text-primary transition-colors font-semibold bg-accent/40 backdrop-blur-sm px-4 py-2 rounded-lg" href="<?php echo esc_url( get_post_type_archive_link( 'mission' ) ); ?>">
					<span class="material-symbols-outlined text-sm">arrow_back</span>
					<?php _e( 'Back to Missions', 'tsm-theme' ); ?>
				</a>
			</div>

			<?php
			// Count valid stats
			$valid_stats = array();
			foreach ( $mission_stats as $stat ) {
				if ( ! empty( $stat['value'] ) || ! empty( $stat['label'] ) ) {
					$valid_stats[] = $stat;
				}
			}
			$stats_count = count( $valid_stats );
			
			// Determine grid classes based on count
			$grid_classes = '';
			if ( $stats_count > 0 ) {
				switch ( $stats_count ) {
					case 1:
						$grid_classes = 'grid-cols-1';
						break;
					case 2:
						$grid_classes = 'grid-cols-1 md:grid-cols-2';
						break;
					case 3:
						$grid_classes = 'grid-cols-1 md:grid-cols-3';
						break;
					default: // 4 or more
						$grid_classes = 'grid-cols-2 md:grid-cols-4';
						break;
				}
			}
			
			if ( $stats_count > 0 ) :
				?>
				<div class="grid <?php echo esc_attr( $grid_classes ); ?> gap-4 mb-12">
					<?php
					foreach ( $valid_stats as $stat ) :
						$icon = ! empty( $stat['icon'] ) ? $stat['icon'] : 'check';
						?>
						<div class="bg-accent text-white p-8 rounded-2xl shadow-2xl border border-white/10 flex flex-col items-center text-center">
							<span class="material-symbols-outlined text-primary mb-3 text-4xl"><?php echo esc_html( $icon ); ?></span>
							<p class="text-3xl font-bold"><?php echo esc_html( $stat['value'] ); ?></p>
							<p class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-60 mt-1"><?php echo esc_html( $stat['label'] ); ?></p>
						</div>
						<?php
					endforeach;
					?>
				</div>
			<?php endif; ?>

			<div class="flex flex-col lg:flex-row gap-12 py-8">
				<div class="flex-1 space-y-16">
					<?php if ( $mission_impact_title || $mission_impact_description || get_the_content() ) : ?>
						<div class="max-w-2xl">
							<h2 class="text-xs font-bold uppercase tracking-widest text-primary mb-3"><?php _e( 'The Impact', 'tsm-theme' ); ?></h2>
							<?php if ( $mission_impact_title ) : ?>
								<p class="text-2xl md:text-3xl font-bold leading-tight text-accent dark:text-white mb-4">
									<?php echo esc_html( $mission_impact_title ); ?>
								</p>
							<?php endif; ?>
							<?php if ( $mission_impact_description ) : ?>
								<p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-4">
									<?php echo esc_html( $mission_impact_description ); ?>
								</p>
							<?php endif; ?>
							<?php if ( get_the_content() ) : ?>
								<div class="prose prose-lg dark:prose-invert max-w-none">
									<?php the_content(); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $gallery_images ) ) : ?>
						<div>
							<div class="flex items-center justify-between mb-8">
								<h2 class="text-accent text-3xl font-bold tracking-tight flex items-center gap-3">
									<?php _e( 'Trip Gallery', 'tsm-theme' ); ?>
									<span class="h-1 w-24 bg-primary rounded-full"></span>
								</h2>
							</div>
							<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
								<?php
								$first_image = ! empty( $gallery_images[0] ) ? $gallery_images[0] : null;
								$second_image = ! empty( $gallery_images[1] ) ? $gallery_images[1] : null;
								$third_image = ! empty( $gallery_images[2] ) ? $gallery_images[2] : null;
								
								if ( $first_image ) :
									$first_image_url = wp_get_attachment_image_url( $first_image['image'], 'large' );
									$first_image_full = wp_get_attachment_image_url( $first_image['image'], 'full' );
									$first_image_alt = ! empty( $first_image['alt'] ) ? $first_image['alt'] : get_the_title();
									?>
									<div class="group overflow-hidden rounded-2xl h-[400px] relative shadow-lg cursor-pointer gallery-image" data-index="0">
										<img alt="<?php echo esc_attr( $first_image_alt ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="<?php echo esc_url( $first_image_url ); ?>" data-full="<?php echo esc_url( $first_image_full ); ?>" data-alt="<?php echo esc_attr( $first_image_alt ); ?>"/>
										<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
											<p class="text-white font-bold"><?php echo esc_html( $first_image_alt ); ?></p>
										</div>
									</div>
								<?php endif; ?>
								
								<div class="grid grid-rows-2 gap-6">
									<?php if ( $second_image ) :
										$second_image_url = wp_get_attachment_image_url( $second_image['image'], 'medium' );
										$second_image_full = wp_get_attachment_image_url( $second_image['image'], 'full' );
										$second_image_alt = ! empty( $second_image['alt'] ) ? $second_image['alt'] : get_the_title();
										?>
										<div class="group overflow-hidden rounded-2xl h-[188px] relative shadow-lg cursor-pointer gallery-image" data-index="1">
											<img alt="<?php echo esc_attr( $second_image_alt ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="<?php echo esc_url( $second_image_url ); ?>" data-full="<?php echo esc_url( $second_image_full ); ?>" data-alt="<?php echo esc_attr( $second_image_alt ); ?>"/>
											<div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
										</div>
									<?php endif; ?>
									
									<?php if ( $third_image ) :
										$third_image_url = wp_get_attachment_image_url( $third_image['image'], 'medium' );
										$third_image_full = wp_get_attachment_image_url( $third_image['image'], 'full' );
										$third_image_alt = ! empty( $third_image['alt'] ) ? $third_image['alt'] : get_the_title();
										?>
										<div class="group overflow-hidden rounded-2xl h-[188px] relative shadow-lg cursor-pointer gallery-image" data-index="2">
											<img alt="<?php echo esc_attr( $third_image_alt ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="<?php echo esc_url( $third_image_url ); ?>" data-full="<?php echo esc_url( $third_image_full ); ?>" data-alt="<?php echo esc_attr( $third_image_alt ); ?>"/>
											<div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
										</div>
									<?php endif; ?>
								</div>
							</div>
							
							<?php
							// Show 4th and 5th images + "View All" button in a 3-column grid
							$fourth_image = ! empty( $gallery_images[3] ) ? $gallery_images[3] : null;
							$fifth_image = ! empty( $gallery_images[4] ) ? $gallery_images[4] : null;
							$total_gallery_images = count( $gallery_images );
							$show_view_all = $total_gallery_images > 5;
							
							// Get first image URL for the "View all" button
							$first_image_full = '';
							if ( ! empty( $gallery_images[0] ) ) {
								$first_image_full = wp_get_attachment_image_url( $gallery_images[0]['image'], 'full' );
							}
							
							if ( $fourth_image || $fifth_image || $show_view_all ) :
								?>
								<div class="grid grid-cols-3 gap-6 mt-6">
									<?php if ( $fourth_image ) :
										$fourth_image_url = wp_get_attachment_image_url( $fourth_image['image'], 'medium' );
										$fourth_image_full = wp_get_attachment_image_url( $fourth_image['image'], 'full' );
										$fourth_image_alt = ! empty( $fourth_image['alt'] ) ? $fourth_image['alt'] : get_the_title();
										?>
										<div class="group overflow-hidden rounded-2xl h-48 relative shadow-md cursor-pointer gallery-image" data-index="3">
											<img alt="<?php echo esc_attr( $fourth_image_alt ); ?>" class="w-full h-full object-cover" src="<?php echo esc_url( $fourth_image_url ); ?>" data-full="<?php echo esc_url( $fourth_image_full ); ?>" data-alt="<?php echo esc_attr( $fourth_image_alt ); ?>"/>
										</div>
									<?php endif; ?>
									
									<?php if ( $fifth_image ) :
										$fifth_image_url = wp_get_attachment_image_url( $fifth_image['image'], 'medium' );
										$fifth_image_full = wp_get_attachment_image_url( $fifth_image['image'], 'full' );
										$fifth_image_alt = ! empty( $fifth_image['alt'] ) ? $fifth_image['alt'] : get_the_title();
										?>
										<div class="group overflow-hidden rounded-2xl h-48 relative shadow-md cursor-pointer gallery-image" data-index="4">
											<img alt="<?php echo esc_attr( $fifth_image_alt ); ?>" class="w-full h-full object-cover" src="<?php echo esc_url( $fifth_image_url ); ?>" data-full="<?php echo esc_url( $fifth_image_full ); ?>" data-alt="<?php echo esc_attr( $fifth_image_alt ); ?>"/>
										</div>
									<?php endif; ?>
									
									<?php if ( $show_view_all && $first_image_full ) : ?>
										<div class="group overflow-hidden rounded-2xl h-48 relative shadow-md bg-accent flex items-center justify-center text-white p-6 text-center hover:bg-accent/90 transition-colors cursor-pointer gallery-image">
											<img class="hidden" src="<?php echo esc_url( $first_image_full ); ?>" data-full="<?php echo esc_url( $first_image_full ); ?>" data-alt=""/>
											<p class="font-bold text-sm underline underline-offset-4">
												<?php printf( __( 'View all %d photos', 'tsm-theme' ), $total_gallery_images ); ?>
											</p>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<aside class="lg:w-[380px] space-y-8">
					<div class="sticky top-24">
						<?php if ( $mission_summary ) : ?>
							<div class="bg-white dark:bg-[#1a2e1e] rounded-3xl border-2 border-primary shadow-2xl p-8 mb-8">
								<h3 class="text-accent text-2xl font-semibold mb-3 flex items-center justify-center gap-2">
									<?php _e( 'Support This Work', 'tsm-theme' ); ?>
								</h3>
								<p class="text-gray-600 dark:text-gray-400 mb-4 text-sm leading-relaxed text-center">
									<?php echo esc_html( $mission_summary ); ?>
								</p>
								<div class="flex justify-center">
									<a href="<?php echo esc_url( home_url( '/partners' ) ); ?>" class="w-full flex cursor-pointer items-center justify-center rounded-lg h-14 px-8 bg-primary dark:bg-accent text-white hover:text-white text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:scale-105 transition-all active:scale-95 gap-2">
										<span class="material-symbols-outlined">volunteer_activism</span>
										<?php _e( 'Give to this Project', 'tsm-theme' ); ?>
									</a>
								</div>
							</div>
						<?php else : ?>
							<div class="bg-white dark:bg-[#1a2e1e] rounded-3xl border-2 border-primary shadow-2xl p-8 mb-8">
								<h3 class="text-accent text-2xl font-semibold mb-3 flex items-center justify-center">
									<?php _e( 'Support This Work', 'tsm-theme' ); ?>
								</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm leading-relaxed text-center">
                  Your partnership helps bring the Gospel alongside practical care to communities in need.
								</p>
								<div class="flex justify-center">
									<a href="<?php echo esc_url( home_url( '/partners' ) ); ?>" class="w-full flex cursor-pointer items-center justify-center rounded-lg h-14 px-8 bg-primary dark:bg-accent text-white hover:text-white text-sm font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:scale-105 transition-all active:scale-95 gap-2">
										<span class="material-symbols-outlined">volunteer_activism</span>
										<?php _e( 'Give to this Project', 'tsm-theme' ); ?>
									</a>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $mission_prayer_needs ) ) : ?>
							<div class="bg-accent text-white rounded-3xl p-8 shadow-xl">
								<h3 class="text-xl font-semibold mb-6 flex items-center gap-2">
									<span class="material-symbols-outlined text-primary">auto_awesome</span>
									<?php _e( 'Specific Prayer Needs', 'tsm-theme' ); ?>
								</h3>
								<ul class="space-y-6">
									<?php
									foreach ( $mission_prayer_needs as $need ) :
										if ( empty( trim( $need ) ) ) {
											continue;
										}
										?>
										<li class="flex gap-4">
											<div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center shrink-0">
												<span class="material-symbols-outlined text-primary text-sm font-bold">check</span>
											</div>
											<p class="text-sm opacity-90 leading-relaxed"><?php echo esc_html( $need ); ?></p>
										</li>
										<?php
									endforeach;
									?>
								</ul>
							</div>
						<?php endif; ?>

						<div class="mt-8">
							<a class="flex items-center justify-center gap-2 py-4 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg hover:border-primary hover:text-primary transition-all font-bold text-sm" href="<?php echo esc_url( get_post_type_archive_link( 'mission' ) ); ?>">
								<span class="material-symbols-outlined text-base">grid_view</span>
								<?php _e( 'All Mission Projects', 'tsm-theme' ); ?>
							</a>
						</div>
					</div>
				</aside>
			</div>
		</div>

		<?php
	endwhile;
	
		// Lightbox Modal
		if ( ! empty( $gallery_images ) ) :
			$mission_title = $mission_subtitle ? $mission_subtitle : get_the_title();
			
			// Prepare image data for lightbox
			$lightbox_images = array();
			foreach ( $gallery_images as $img ) {
				$lightbox_images[] = array(
					'full'  => wp_get_attachment_image_url( $img['image'], 'full' ),
					'thumb' => wp_get_attachment_image_url( $img['image'], 'thumbnail' ),
					'alt'   => ! empty( $img['alt'] ) ? $img['alt'] : get_the_title(),
				);
			}
			
			tsm_render_lightbox_gallery( array(
				'title'       => $mission_title,
				'location'    => $mission_location ? $mission_location : '',
				'images'      => $lightbox_images,
				'lightbox_id' => 'gallery-lightbox',
			) );
		endif;
else :
	?>
	<section class="max-w-[1200px] mx-auto px-6 py-20">
		<div class="text-center">
			<h1 class="text-4xl font-bold mb-4"><?php _e( 'Mission Not Found', 'tsm-theme' ); ?></h1>
			<p class="text-lg text-gray-500 dark:text-gray-400 mb-8"><?php _e( 'Sorry, the mission you are looking for could not be found.', 'tsm-theme' ); ?></p>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'mission' ) ); ?>" class="text-primary font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
				<?php _e( 'View All Missions', 'tsm-theme' ); ?> <span class="material-symbols-outlined !text-base">arrow_forward</span>
			</a>
		</div>
	</section>
	<?php
endif;

get_footer();
