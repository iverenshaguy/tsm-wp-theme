<?php
/**
 * The template for displaying the About page
 *
 * @package TSM_Theme
 */

get_header();
?>

<!-- About Section -->
<section class="max-w-[1280px] mx-auto px-6 py-20 lg:py-32">
	<div class="grid grid-cols-1 gap-16 items-start lg:grid-cols-12">
		<div class="flex flex-col gap-10 lg:col-span-5">
			<div class="relative group">
				<div class="aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl border border-gray-100 dark:border-primary/20">
					<?php
					$about_image = get_theme_mod( 'about_page_image', get_template_directory_uri() . '/assets/images/about.png' );
					?>
					<img alt="<?php echo esc_attr( get_theme_mod( 'about_page_name', 'Terry Shaguy' ) ); ?>" class="w-full h-full object-cover grayscale-[20%] group-hover:grayscale-0 transition-all duration-700" src="<?php echo esc_url( $about_image ); ?>"/>
				</div>
				<div class="absolute -bottom-6 -left-6 bg-primary text-white p-8 rounded-2xl shadow-xl max-w-[280px]">
					<h4 class="mb-2 text-xs font-bold tracking-widest uppercase text-accent">
						<?php
						$about_quote_label = get_theme_mod( 'about_quote_label', 'Core Philosophy' );
						echo esc_html( $about_quote_label );
						?>
					</h4>
					<p class="text-lg italic leading-relaxed serif-text">
						<?php
						$about_quote = get_theme_mod(
							'about_quote',
							'"The Gospel, lived with depth and expressed through love, stewardship, and shared responsibility."'
						);
						echo esc_html( $about_quote );
						?>
					</p>
				</div>
			</div>
			<div class="mt-12 space-y-8 bg-[#f3f7f4] dark:bg-primary/10 p-8 rounded-2xl border border-primary/5">
				<div>
					<h5 class="mb-4 text-sm font-bold tracking-wider uppercase text-accent dark:text-primary">
						<?php
						$fast_facts_title = get_theme_mod( 'about_fast_facts_title', 'Fast Facts' );
						echo esc_html( $fast_facts_title );
						?>
					</h5>
					<ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
						<?php
						$fast_facts = get_theme_mod(
							'about_fast_facts',
							"school|Ph.D. in Global Theology\npublic|20+ Years Global Field Experience\nedit_note|Author of 8 Best-selling Titles\nlocation_on|Based in Ibadan, Nigeria"
						);
						$facts = explode( "\n", $fast_facts );
						foreach ( $facts as $fact ) {
							if ( empty( trim( $fact ) ) ) {
								continue;
							}
							$parts = explode( '|', $fact, 2 );
							$icon = ! empty( $parts[0] ) ? trim( $parts[0] ) : 'check';
							$text = ! empty( $parts[1] ) ? trim( $parts[1] ) : '';
							if ( ! empty( $text ) ) {
								?>
								<li class="flex gap-3 items-center">
									<span class="material-symbols-outlined text-primary !text-base"><?php echo esc_html( $icon ); ?></span>
									<?php echo esc_html( $text ); ?>
								</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="lg:col-span-7">
			<header class="mb-12">
				<h2 class="mb-4 text-sm font-bold tracking-widest uppercase text-primary">
					<?php
					$about_subtitle = get_theme_mod( 'about_page_subtitle', 'The Life & Ministry Of' );
					echo esc_html( $about_subtitle );
					?>
				</h2>
				<h1 class="mb-6 text-5xl font-bold leading-tight text-accent dark:text-white md:text-6xl">
					<?php
					$about_name = get_theme_mod( 'about_page_name', 'Terry Shaguy' );
					echo esc_html( $about_name );
					?>
				</h1>
				<div class="mb-8 w-24 h-1.5 rounded-full bg-primary"></div>
			</header>
			<article class="space-y-10 text-lg leading-relaxed text-gray-700 serif-text dark:text-gray-300">
				<?php
				// Display page content if available, otherwise use customizer settings
				while ( have_posts() ) :
					the_post();
					if ( get_the_content() ) {
						the_content();
					} else {
						// Fallback to customizer content
						?>
							<div>
								<h3 class="mb-4 font-sans text-2xl font-medium tracking-tight text-accent dark:text-primary sans-serif">
									<?php
									$about_section_1_title = get_theme_mod( 'about_section_1_title', 'His Calling' );
									echo esc_html( $about_section_1_title );
									?>
								</h3>
								<div class="space-y-4 text-lg leading-relaxed text-gray-700 serif-text dark:text-gray-300">
									<?php
									$about_section_1_content = get_theme_mod(
										'about_section_1_content',
										'<p>Dr. Terry Shaguy\'s journey began with a distinct sense of purpose in the small rural communities of the Appalachian foothills. From an early age, Terry felt a profound pull toward the intersection of faith and global humanitarian needs. This wasn\'t merely a vocational choice but a transformative calling that has led him into some of the world\'s most remote corners.</p><p>His ministry is defined by a relentless pursuit of depth. Whether teaching in a crowded urban center or mentoring leaders in a quiet village, Terry\'s approach remains rooted in the belief that spiritual growth is the catalyst for all lasting societal change.</p>'
									);
									echo wp_kses_post( $about_section_1_content );
									?>
								</div>
							</div>
							<div class="p-8 my-8 italic rounded-r-xl border-l-4 bg-primary/5 dark:bg-white/5 border-primary">
								<p class="text-xl text-accent dark:text-white">
									<?php
									$about_highlight_quote = get_theme_mod(
										'about_highlight_quote',
										'"We do not travel to the ends of the earth to bring God there; we travel to discover where He is already working and join Him in that harvest."'
									);
									echo esc_html( $about_highlight_quote );
									?>
								</p>
								<cite class="block mt-4 text-sm not-italic font-bold tracking-widest uppercase text-primary">
									— <?php echo esc_html( $about_name ); ?>
								</cite>
							</div>
							<div>
								<h3 class="mb-4 font-sans text-2xl font-medium tracking-tight text-accent dark:text-primary sans-serif">
									<?php
									$about_section_2_title = get_theme_mod( 'about_section_2_title', 'Academic Background' );
									echo esc_html( $about_section_2_title );
									?>
								</h3>
								<div class="space-y-4 text-lg leading-relaxed text-gray-700 serif-text dark:text-gray-300">
									<?php
									$about_section_2_content = get_theme_mod(
										'about_section_2_content',
										'<p>Recognizing that zeal must be tempered with wisdom, Terry pursued a rigorous academic path. He holds a Master\'s of Divinity and a Ph.D. in Global Theology from the Trinity Evangelical Divinity School. His research focused on the indigenous expressions of faith in the Global South, a subject he continues to write and lecture on extensively.</p><p>Today, he serves as a visiting professor at several seminaries globally, helping to bridge the gap between traditional theological study and practical mission-field application.</p>'
									);
									echo wp_kses_post( $about_section_2_content );
									?>
								</div>
							</div>
							<div>
								<h3 class="mb-4 font-sans text-2xl font-medium tracking-tight text-accent dark:text-primary sans-serif">
									<?php
									$about_section_3_title = get_theme_mod( 'about_section_3_title', 'Personal Life' );
									echo esc_html( $about_section_3_title );
									?>
								</h3>
								<div class="space-y-4 text-lg leading-relaxed text-gray-700 serif-text dark:text-gray-300">
									<?php
									$about_section_3_content = get_theme_mod(
										'about_section_3_content',
										'<p>Beyond the pulpit and the lecture hall, Terry is a devoted husband to Debbie and a father of three. He often credits his family as his greatest grounding force. Terry is an avid hiker and can often be found exploring the trails of the Smoky Mountains when he is not on international assignment.</p><p>Terry and Debbie\'s partnership in ministry is a cornerstone of their work, demonstrating a model of shared leadership and mutual respect that they bring to every conference and mission they lead together.</p>'
									);
									echo wp_kses_post( $about_section_3_content );
									?>
								</div>
							</div>
							<?php
					}
				endwhile;
				?>
			</article>
		</div>
	</div>
</section>

<!-- Books Section -->
<section class="bg-[#f3f7f4] dark:bg-[#0c1a11] py-24">
	<div class="max-w-[1280px] mx-auto px-6">
		<div class="mb-16 text-center">
			<div class="inline-flex gap-2 items-center px-3 py-1 mb-6 rounded-full border backdrop-blur-sm bg-primary/20 border-primary/30">
				<span class="text-sm material-symbols-outlined text-primary">menu_book</span>
				<span class="text-xs font-bold tracking-widest uppercase text-primary">
					<?php
					$about_books_badge = get_theme_mod( 'about_books_badge', 'Resources' );
					echo esc_html( $about_books_badge );
					?>
				</span>
			</div>
			<h3 class="text-4xl font-black text-accent dark:text-white">
				<?php
				$about_books_title = get_theme_mod( 'about_books_title', 'Books by Terry' );
				echo esc_html( $about_books_title );
				?>
			</h3>
			<p class="mx-auto mt-4 max-w-2xl text-gray-500">
				<?php
				$about_books_description = get_theme_mod(
					'about_books_description',
					'Deepen your study with these selected works focusing on spiritual growth, global missions, and leadership.'
				);
				echo esc_html( $about_books_description );
				?>
			</p>
		</div>
		<div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
			<?php
			$books_page_id = get_theme_mod( 'books_page_id', 0 );
			$books_url = $books_page_id ? get_permalink( $books_page_id ) : home_url( '/books' );
			
			// Query books - try custom post type 'book' first, fallback to posts with 'books' category
			$books_query = new WP_Query(
				array(
					'post_type'      => 'book',
					'posts_per_page' => 4,
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);

			// Fallback: If no books found, try regular posts with 'books' category
			if ( ! $books_query->have_posts() ) {
				$books_query = new WP_Query(
					array(
						'post_type'      => 'post',
						'category_name'  => 'books',
						'posts_per_page' => 4,
						'post_status'    => 'publish',
						'orderby'        => 'date',
						'order'          => 'DESC',
					)
				);
			}

			if ( ! $books_query->have_posts() ) {
				// Fallback: Show placeholder books
				$placeholder_books = array(
					array(
						'title'  => 'Walking the Narrow Road',
						'badge'  => 'Newest Release',
						'image'  => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBEJOAyGvsvnntON8a6bqVkagclNJEhwKNvUDyJ3AH86a6H6MzaphuiQI9QDeI9fNKOm2gbccY1NAtJO9AcFXDiO7-xgAOoq21TXMIl1pOGTZcgMOLSAxJZfiMtQFT2MAuH6UtUsqyPVDM3CregnPc1d1Ym_WWCkBph2po6E_uXFDVpxR-Cxn9B5xO4S7fhY3MKjCvYFa_dZibNrrK8zojcBAwRPUlWXnhhSq1VApq5G1RtFVMFbKNOYl_gF1k0AC2aa1ShUeJEcRQ',
					),
					array(
						'title'  => 'Missions Reimagined',
						'badge'  => 'Global Strategy',
						'image'  => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBA_kRvl55YvaQBR4tjGni6CTt-Aa2B59U0mTTiLLFtOMARuuOHfgrZjaW_m4Z5_2nWnkPvecxoiyCYGj6qwFFURFKid11s3_6B0R836PMhGAw0ScLzFB513-5LC3Vwa1MIbFkJcw63AIzhFJliwusjPmlcE7r4hK_BjfruXk0yS8_cavI4QwCt2Nx2sKn3RGecnjsTJi4FTG2_M9KmZL2r6JlDYAQFEdOAUQTEVmDnrPkf8Jj_ttDQMXdUAVRDFH_77eZZmr1_bls',
					),
					array(
						'title'  => 'Foundations of Faith',
						'badge'  => 'Spiritual Growth',
						'image'  => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDR0aVc_dsPjJL2T1D4aJwKorNf8oaJoXxFqBSGJXkVA5QYtwdEYJf6kC-WRbokAyEfp0vz3ZjnqfY9b42UW1YObYdRr8gFY3XLyRnvgWCJj97xHU2Bq3PJYFNoTDn_EkAf_3rKglRH-3oZB0FDLRSBKL_0tBNu1wGB5ZHBvdVahfh2V_Tw496G4KyLsoB5Rd5HON0K809tf3OAs0CH-hYozht2NgOjtK9xOrDiKUI4-IdBtGKKyuNVuINAjlARkuiG9XU3SL6if68',
					),
					array(
						'title'  => 'Kingdom Legacy',
						'badge'  => 'Leadership',
						'image'  => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAcWMPJ6KWHu_M1JurRWVh7ILUT9uxfrP0NBITLIiqh5qS3arzRkX4CAj3RE1jmUaZCqqFLppMe7YflCiUrQAb-bzGL9TMv-vFnncoTDmsazGR2zkzUqf_AXEcw7IEnv4XTotUPivgBMQhudFpHAwjE-5c3fR_LW7EdsjrNd1oIQD18W0oL5mV93DD_c7fs8_XSi31m9gLQCfYFeDPAVwLJT9_k-J0DGbx-LYbFw7ikMbi-O2QN9yKmk2fouhymyzt_QmU17unF56w',
					),
				);

				foreach ( $placeholder_books as $book ) :
					?>
					<a href="<?php echo esc_url( $books_url ); ?>" class="block cursor-pointer group">
						<div class="aspect-[3/4] rounded-xl overflow-hidden shadow-md mb-6 transition-transform duration-300 group-hover:-translate-y-2">
							<img alt="<?php echo esc_attr( $book['title'] ); ?> Book Cover" class="object-cover w-full h-full" src="<?php echo esc_url( $book['image'] ); ?>"/>
						</div>
						<h4 class="mb-2 text-xl font-bold transition-colors text-accent dark:text-white group-hover:text-primary"><?php echo esc_html( $book['title'] ); ?></h4>
						<p class="mb-4 text-sm text-gray-500"><?php echo esc_html( $book['badge'] ); ?></p>
						<span class="flex gap-2 items-center text-sm font-bold transition-all text-primary group-hover:gap-3">
							Learn More <span class="material-symbols-outlined !text-base">arrow_forward</span>
						</span>
					</a>
					<?php
				endforeach;
			} else {
				while ( $books_query->have_posts() ) :
					$books_query->the_post();
					$author = get_post_meta( get_the_ID(), 'book_author', true );
					?>
					<a href="<?php the_permalink(); ?>" class="block cursor-pointer group">
						<div class="aspect-[3/4] rounded-xl overflow-hidden shadow-md mb-6 transition-transform duration-300 group-hover:-translate-y-2">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php
								the_post_thumbnail(
									'medium',
									array(
										'class' => 'w-full h-full object-cover',
										'alt'   => get_the_title(),
									)
								);
								?>
							<?php else : ?>
								<img alt="<?php the_title_attribute(); ?> Book Cover" class="object-cover w-full h-full" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/book-placeholder.jpg' ); ?>"/>
							<?php endif; ?>
						</div>
						<h4 class="mb-2 text-xl font-bold transition-colors text-accent dark:text-white group-hover:text-primary">
							<?php the_title(); ?>
						</h4>
						<p class="mb-4 text-sm text-gray-500">
							<?php echo $author ? esc_html( 'By ' . $author ) : 'By Terry Shaguy'; ?>
						</p>
						<span class="flex gap-2 items-center text-sm font-bold transition-all text-primary group-hover:gap-3">
							Learn More <span class="material-symbols-outlined !text-base">arrow_forward</span>
						</span>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
			}
			?>
		</div>
	</div>
</section>

<!-- CTA Section -->
<section class="max-w-[1280px] mx-auto px-6 py-20">
	<div class="overflow-hidden relative p-12 text-center text-white rounded-3xl bg-primary md:p-20">
		<?php
		$cta_bg_image = get_theme_mod( 'about_cta_bg_image', '' );
		if ( $cta_bg_image ) {
			?>
			<div class="absolute inset-0 bg-center bg-cover opacity-10" style="background-image: url('<?php echo esc_url( $cta_bg_image ); ?>')"></div>
			<?php
		}
		?>
		<div class="relative z-10 mx-auto max-w-2xl">
			<div class="p-4 mx-auto mb-8 bg-white rounded-2xl shadow-lg text-primary shadow-white/30 w-fit">
				<span class="material-symbols-outlined !text-4xl">campaign</span>
			</div>
			<h2 class="mb-6 text-3xl font-black md:text-5xl">
				<?php
				$about_cta_title = get_theme_mod( 'about_cta_title', 'Invite Terry to Your Event' );
				echo esc_html( $about_cta_title );
				?>
			</h2>
			<p class="mx-auto mb-10 max-w-xl text-lg leading-relaxed text-white/80">
				<?php
				$about_cta_description = get_theme_mod(
					'about_cta_description',
					'Dr. Terry Shaguy is available for keynote speaking, leadership seminars, and theological training worldwide.'
				);
				echo esc_html( $about_cta_description );
				?>
			</p>
			<div class="flex flex-col gap-4 justify-center sm:flex-row">
				<?php
				$contact_page_id = get_theme_mod( 'contact_page_id', 0 );
				$contact_url = $contact_page_id ? get_permalink( $contact_page_id ) : home_url( '/contact-us' );
				$speakers_kit_url = get_theme_mod( 'speakers_kit_url', '' );
				?>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="px-10 py-5 font-bold bg-white rounded-lg shadow-xl transition-all text-primary hover:text-accent hover:bg-gray-100 hover:scale-105 active:scale-95">
					Check Availability
				</a>
				<?php if ( $speakers_kit_url ) : ?>
					<a href="<?php echo esc_url( $speakers_kit_url ); ?>" class="px-10 py-5 font-bold text-white bg-transparent rounded-lg border-2 backdrop-blur-md transition-all border-white/40 hover:text-white hover:bg-white/10">
						Speaker's Kit (PDF)
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();