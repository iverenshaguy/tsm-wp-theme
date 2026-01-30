<?php
/**
 * The front page template
 *
 * @package TSM_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="max-w-[1280px] mx-auto px-6 py-8">
	<div class="@container">
		<div class="relative overflow-hidden rounded-2xl">
			<div class="flex min-h-[660px] flex-col gap-6 bg-cover bg-center bg-no-repeat items-center justify-center p-8 text-center" style='background-image: linear-gradient(rgba(0, 0, 0, 0.4) 0%, rgba(26, 77, 46, 0.9) 100%), url("<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-bg.png");'>
				<div class="max-w-4xl flex flex-col gap-6">
					<span class="text-accent font-bold tracking-[0.2em] uppercase text-sm bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full w-fit mx-auto">
						<?php
						$hero_badge = get_theme_mod(
							'hero_badge',
							'Global Itinerant Ministry'
						);
						echo esc_html( $hero_badge );
						?>
					</span>
					<h1 class="text-white text-5xl font-bold leading-tight tracking-[-0.033em] md:text-7xl">
						<?php
						$hero_heading = get_theme_mod(
							'hero_heading',
							'Welcome to<br/>Terry Shaguy Ministries'
						);
						echo wp_kses_post( $hero_heading );
						?>
					</h1>
					<p class="text-white/90 text-lg md:text-xl font-medium max-w-2xl mx-auto leading-relaxed">
						<?php
						$hero_description = get_theme_mod(
							'hero_description',
							'Besides teaching, Terry and Debbie Shaguy consider it a calling and an urgent priority to help less fortunate people, especially in Africa, rise above the darkness and horrors of poverty.'
						);
						echo esc_html( $hero_description );
						?>
					</p>
				</div>
				<div class="flex flex-col sm:flex-row gap-4 mt-6">
					<?php
					// Get contact page URL from Customizer setting
					$contact_page_id = get_theme_mod( 'contact_page_id', 0 );
					$contact_url = $contact_page_id ? get_permalink( $contact_page_id ) : home_url( '/contact-us' );
					
					// Get missions page URL from Customizer setting
					$missions_page_id = get_theme_mod( 'missions_page_id', 0 );
					$missions_url = $missions_page_id ? get_permalink( $missions_page_id ) : home_url( '/missions' );
					?>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="flex min-w-[220px] cursor-pointer items-center justify-center rounded-lg h-14 px-8 bg-white text-primary hover:text-accent text-lg font-bold shadow-xl hover:bg-gray-100 transition-all">
						Invite Us to Speak
					</a>
					<a href="<?php echo esc_url( $missions_url ); ?>" class="flex min-w-[220px] cursor-pointer items-center justify-center rounded-lg h-14 px-8 bg-transparent border-2 border-white/40 backdrop-blur-md text-white hover:text-white text-lg font-bold hover:bg-white/10 transition-all">
						Our Missions Work
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- About Section -->
<section class="max-w-[1280px] mx-auto px-6 py-20">
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
		<div class="relative">
			<div class="aspect-square rounded-3xl overflow-hidden shadow-2xl border-8 border-white dark:border-primary/20 transform -rotate-2">
				<?php
				$about_image = get_theme_mod( 'about_image', get_template_directory_uri() . '/assets/images/about.png' );
				?>
				<img alt="Terry and Debbie Shaguy Professional Portrait" class="w-full h-full object-cover" src="<?php echo esc_url( $about_image ); ?>"/>
			</div>
			<div class="absolute -bottom-6 -right-6 bg-primary text-white p-8 rounded-2xl shadow-xl hidden md:block max-w-[240px]">
				<p class="text-lg font-bold italic leading-tight">
					<?php
					$about_quote = get_theme_mod(
						'about_quote',
						'"Dedicated to sharing hope across the nations."'
					);
					echo esc_html( $about_quote );
					?>
				</p>
			</div>
		</div>
		<div class="flex flex-col">
			<h2 class="text-accent uppercase tracking-widest text-sm font-bold mb-4">Our Heart</h2>
			<h3 class="text-primary dark:text-white text-4xl md:text-5xl font-bold leading-tight mb-8">
				About Terry and Debbie
			</h3>
			<div class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed mb-8">
				<?php
				$about_content = get_theme_mod(
					'about_content',
					'With over 25 years in ministry, we have dedicated our lives to training leaders and reaching the unreached. Together, we bring a balanced perspective on faith, family, and global service. 

Our journey has taken us from rural villages in Nigeria to bustling metropolises in Africa, always with the singular focus of equipping the body of Christ for the harvest.'
				);
				echo wp_kses_post( wpautop( $about_content ) );
				?>
			</div>
			<div class="h-1 w-20 bg-accent rounded-full mb-12"></div>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<div class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-[#1d3a24] bg-white dark:bg-[#0a140d]">
					<span class="material-symbols-outlined text-accent !text-3xl">public</span>
					<div>
						<h4 class="font-bold text-primary dark:text-white">
							<?php
							$villages_count = get_theme_mod( 'villages_count', '40' );
							echo esc_html( $villages_count ) . '+ Villages';
							?>
						</h4>
						<p class="text-sm text-gray-500">Reached with the Gospel message.</p>
					</div>
				</div>
				<div class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-[#1d3a24] bg-white dark:bg-[#0a140d]">
					<span class="material-symbols-outlined text-accent !text-3xl">menu_book</span>
					<div>
						<h4 class="font-bold text-primary dark:text-white">
							<?php
							$books_count = get_theme_mod( 'books_count', '12' );
							echo esc_html( $books_count ) . '+ Books';
							?>
						</h4>
						<p class="text-sm text-gray-500">Written to equip the Church.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Services Section -->
<section class="max-w-[1280px] mx-auto px-6 py-20">
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
		<div class="flex flex-col gap-5 rounded-2xl border border-[#cfe7d5] dark:border-[#1d3a24] bg-white dark:bg-[#0a140d] p-8 shadow-sm hover:shadow-lg transition-all border-b-4 border-b-primary">
			<div class="bg-primary/10 text-primary dark:text-accent p-3 rounded-lg w-fit">
				<span class="material-symbols-outlined !text-3xl">campaign</span>
			</div>
			<div class="flex flex-col gap-2">
				<h4 class="text-primary dark:text-white text-xl font-bold">
					<?php
					$service_1_title = get_theme_mod( 'service_1_title', 'Itinerant Teaching' );
					echo esc_html( $service_1_title );
					?>
				</h4>
				<p class="text-gray-600 dark:text-gray-400 text-base">
					<?php
					$service_1_description = get_theme_mod( 'service_1_description', 'Available for conferences, revivals, and church gatherings globally.' );
					echo esc_html( $service_1_description );
					?>
				</p>
			</div>
		</div>
		<div class="flex flex-col gap-5 rounded-2xl border border-[#cfe7d5] dark:border-[#1d3a24] bg-white dark:bg-[#0a140d] p-8 shadow-sm hover:shadow-lg transition-all border-b-4 border-b-primary">
			<div class="bg-primary/10 text-primary dark:text-accent p-3 rounded-lg w-fit">
				<span class="material-symbols-outlined !text-3xl">menu_book</span>
			</div>
			<div class="flex flex-col gap-2">
				<h4 class="text-primary dark:text-white text-xl font-bold">
					<?php
					$service_2_title = get_theme_mod( 'service_2_title', 'Authorship' );
					echo esc_html( $service_2_title );
					?>
				</h4>
				<p class="text-gray-600 dark:text-gray-400 text-base">
					<?php
					$service_2_description = get_theme_mod( 'service_2_description', 'Writing together and individually to equip the body of Christ.' );
					echo esc_html( $service_2_description );
					?>
				</p>
			</div>
		</div>
		<div class="flex flex-col gap-5 rounded-2xl border border-[#cfe7d5] dark:border-[#1d3a24] bg-white dark:bg-[#0a140d] p-8 shadow-sm hover:shadow-lg transition-all border-b-4 border-b-primary">
			<div class="bg-primary/10 text-primary dark:text-accent p-3 rounded-lg w-fit">
				<span class="material-symbols-outlined !text-3xl">volunteer_activism</span>
			</div>
			<div class="flex flex-col gap-2">
				<h4 class="text-primary dark:text-white text-xl font-bold">
					<?php
					$service_3_title = get_theme_mod( 'service_3_title', 'Global Outreach' );
					echo esc_html( $service_3_title );
					?>
				</h4>
				<p class="text-gray-600 dark:text-gray-400 text-base">
					<?php
					$service_3_description = get_theme_mod( 'service_3_description', 'Leading mission teams and humanitarian efforts across the globe.' );
					echo esc_html( $service_3_description );
					?>
				</p>
			</div>
		</div>
	</div>
</section>

<!-- Featured Book Section -->
<?php
// Get featured book - check front page customizer first, then books archive customizer, then meta field
$featured_book_id = get_theme_mod( 'front_page_featured_book', 0 );
if ( ! $featured_book_id || $featured_book_id === 0 ) {
	$featured_book_id = get_theme_mod( 'books_featured_book', 0 );
}
$featured_book_id = absint( $featured_book_id );

$featured_query = null;

if ( $featured_book_id > 0 ) {
	// Use customizer setting - verify post exists and is published
	$featured_post = get_post( $featured_book_id );
	if ( $featured_post && $featured_post->post_type === 'book' && $featured_post->post_status === 'publish' ) {
		// Use post__in for better reliability
		$featured_args = array(
			'post_type'      => 'book',
			'post__in'       => array( $featured_book_id ),
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'orderby'        => 'post__in',
		);
		$featured_query = new WP_Query( $featured_args );
	}
}

// If no featured book from customizer, fall back to meta field
if ( ! $featured_query || ! $featured_query->have_posts() ) {
	if ( $featured_query ) {
		wp_reset_postdata();
	}
	$featured_args = array(
		'post_type'      => 'book',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'   => 'book_featured',
				'value' => '1',
			),
		),
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	$featured_query = new WP_Query( $featured_args );
}

if ( $featured_query && $featured_query->have_posts() ) :
	$featured_query->the_post();
	$featured_price = get_post_meta( get_the_ID(), 'book_price', true );
	$featured_price_original = get_post_meta( get_the_ID(), 'book_price_original', true );
	$featured_buy_url = get_post_meta( get_the_ID(), 'book_buy_url', true );
	$featured_excerpt_url = get_post_meta( get_the_ID(), 'book_excerpt_url', true );
	$featured_summary = get_post_meta( get_the_ID(), 'book_summary', true );
	$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	$featured_author = get_post_meta( get_the_ID(), 'book_author', true );
	$featured_badge = get_post_meta( get_the_ID(), 'book_badge', true );
	
	// Use summary if available, otherwise use excerpt, otherwise fallback text
	$featured_description = ! empty( $featured_summary ) 
		? $featured_summary 
		: ( get_the_excerpt() ? get_the_excerpt() : 'Discover the latest insights from Terry Shaguy in this transformative guide to spiritual growth and enduring faith. A cornerstone for every believer\'s library.' );
	
	// Use badge from book or fallback to customizer
	$display_badge = $featured_badge ? $featured_badge : get_theme_mod( 'featured_book_badge', 'NEW RELEASE' );
	$display_author = $featured_author ? $featured_author : get_theme_mod( 'featured_book_author', 'Terry Shaguy' );
	?>
	<section class="bg-primary text-white py-24 overflow-hidden relative">
		<div class="absolute right-0 top-0 w-1/3 h-full bg-accent/10 -skew-x-12 translate-x-1/2"></div>
		<div class="max-w-[1280px] mx-auto px-6 relative z-10">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
				<div class="order-2 lg:order-1">
					<span class="bg-accent text-white px-4 py-1 rounded text-xs font-bold mb-6 inline-block">
						<?php echo esc_html( $display_badge ); ?>
					</span>
					<h2 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
						<?php the_title(); ?>
					</h2>
					<p class="text-white/80 text-lg mb-8 leading-relaxed max-w-xl">
						<?php echo esc_html( $featured_description ); ?>
					</p>
					<div class="flex flex-wrap gap-4">
						<?php if ( $featured_buy_url ) : ?>
							<a href="<?php echo esc_url( $featured_buy_url ); ?>" target="_blank" rel="noopener" class="bg-accent hover:bg-accent/90 text-white hover:text-white font-bold py-4 px-10 rounded-lg hover:scale-105 transition-transform active:scale-95 flex items-center gap-2">
								Buy Now <span class="material-symbols-outlined">shopping_cart</span>
							</a>
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="bg-accent hover:bg-accent/90 text-white hover:text-white font-bold py-4 px-10 rounded-lg hover:scale-105 transition-transform active:scale-95 flex items-center gap-2">
								View Details <span class="material-symbols-outlined">arrow_forward</span>
							</a>
						<?php endif; ?>
						<?php if ( $featured_excerpt_url ) : ?>
							<a href="<?php echo esc_url( $featured_excerpt_url ); ?>" target="_blank" rel="noopener" class="border border-white/30 hover:bg-white/10 text-white hover:text-white font-bold py-4 px-10 rounded-lg transition-colors">
								Read Excerpt
							</a>
						<?php else : ?>
							<a href="<?php the_permalink(); ?>" class="border border-white/30 hover:bg-white/10 text-white hover:text-white font-bold py-4 px-10 rounded-lg transition-colors">
								Learn More
							</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="order-1 lg:order-2 flex justify-center">
					<div class="relative w-full max-w-md aspect-[3/4] rounded-lg shadow-2xl overflow-hidden transform lg:rotate-6 hover:rotate-0 transition-transform duration-500 group">
						<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo esc_url( $featured_image ? $featured_image : get_theme_mod( 'featured_book_image', get_template_directory_uri() . '/assets/images/book-cover.png' ) ); ?>')">
							<div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex items-end p-10 group-hover:opacity-0 transition-opacity duration-500">
								<div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	// Store featured book ID before resetting postdata
	$featured_book_id_for_exclusion = get_the_ID();
	wp_reset_postdata();
else :
	// Fallback to customizer settings if no featured book found
	$featured_book_id_for_exclusion = 0;
	?>
	<section class="bg-primary text-white py-24 overflow-hidden relative">
		<div class="absolute right-0 top-0 w-1/3 h-full bg-accent/10 -skew-x-12 translate-x-1/2"></div>
		<div class="max-w-[1280px] mx-auto px-6 relative z-10">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
				<div class="order-2 lg:order-1">
					<span class="bg-accent text-white px-4 py-1 rounded text-xs font-bold mb-6 inline-block">
						<?php
						$featured_book_badge = get_theme_mod( 'featured_book_badge', 'NEW RELEASE' );
						echo esc_html( $featured_book_badge );
						?>
					</span>
					<h2 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
						<?php
						$featured_book_title = get_theme_mod( 'featured_book_title', 'Walking the Narrow Road' );
						echo esc_html( $featured_book_title );
						?>
					</h2>
					<p class="text-white/80 text-lg mb-8 leading-relaxed max-w-xl">
						<?php
						$featured_book_description = get_theme_mod(
							'featured_book_description',
							'Discover the transformative power of surrendered living. In his latest book, Terry Shaguy shares profound lessons from two decades on the mission field, teaching us how to find God\'s voice in the midst of global noise.'
						);
						echo esc_html( $featured_book_description );
						?>
					</p>
					<div class="flex flex-wrap gap-4">
						<a href="<?php echo esc_url( get_theme_mod( 'featured_book_buy_url', home_url( '/books/walking-the-narrow-road' ) ) ); ?>" class="bg-accent hover:bg-accent/90 text-white hover:text-white font-bold py-4 px-10 rounded-lg hover:scale-105 transition-transform active:scale-95 flex items-center gap-2">
							Buy Now <span class="material-symbols-outlined">shopping_cart</span>
						</a>
						<a href="<?php echo esc_url( get_theme_mod( 'featured_book_excerpt_url', home_url( '/books/walking-the-narrow-road#excerpt' ) ) ); ?>" class="border border-white/30 hover:bg-white/10 text-white hover:text-white font-bold py-4 px-10 rounded-lg transition-colors">
							Read Excerpt
						</a>
					</div>
				</div>
				<div class="order-1 lg:order-2 flex justify-center">
					<div class="relative w-full max-w-md aspect-[3/4] rounded-lg shadow-2xl overflow-hidden transform lg:rotate-6 hover:rotate-0 transition-transform duration-500">
						<div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo esc_url( get_theme_mod( 'featured_book_image', get_template_directory_uri() . '/assets/images/book-cover.png' ) ); ?>')">
							<div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex items-end p-10">
								<div>
									<p class="text-xs font-bold tracking-widest text-accent uppercase mb-2">
										<?php
										$featured_book_author = get_theme_mod( 'featured_book_author', 'Terry Shaguy' );
										echo esc_html( $featured_book_author );
										?>
									</p>
									<p class="text-3xl font-bold leading-tight">
										<?php
										echo esc_html( $featured_book_title );
										?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
endif;
?>

<!-- Books Collection Section -->
<section class="bg-[#f3f7f4] dark:bg-[#0c1a11] py-24">
	<div class="max-w-[1280px] mx-auto px-6">
		<div class="text-center mb-16">
			<h2 class="text-accent uppercase tracking-widest text-sm font-bold mb-4">The Collection</h2>
			<h3 class="text-primary dark:text-white text-4xl font-bold">From the Bookshelf</h3>
			<p class="text-gray-500 mt-4 max-w-2xl mx-auto">Explore more resources written to deepen your walk with God and strengthen your ministry leadership.</p>
		</div>
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
			<?php
			// Query books - exclude featured book
			$books_args = array(
				'post_type'      => 'book',
				'posts_per_page' => 4,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
			
			// Exclude featured book if one was found
			if ( isset( $featured_book_id_for_exclusion ) && $featured_book_id_for_exclusion > 0 ) {
				$books_args['post__not_in'] = array( $featured_book_id_for_exclusion );
			}
			
			$books_query = new WP_Query( $books_args );

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
						'title'  => 'The Silent Partner',
						'author' => 'By Debbie Shaguy',
						'image'  => 'book-1.jpg',
					),
					array(
						'title'  => 'Foundations of Faith',
						'author' => 'By Terry & Debbie Shaguy',
						'image'  => 'book-2.jpg',
					),
					array(
						'title'  => 'Missions Reimagined',
						'author' => 'By Terry Shaguy',
						'image'  => 'book-3.jpg',
					),
					array(
						'title'  => 'Kingdom Legacy',
						'author' => 'By Terry & Debbie Shaguy',
						'image'  => 'book-4.jpg',
					),
				);

				foreach ( $placeholder_books as $book ) :
					?>
					<div class="group cursor-pointer">
						<div class="aspect-[3/4] rounded-xl overflow-hidden shadow-md mb-6 transition-transform duration-300 group-hover:-translate-y-2">
							<img alt="<?php echo esc_attr( $book['title'] ); ?> Book Cover" class="w-full h-full object-cover" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $book['image'] ); ?>"/>
						</div>
						<h4 class="text-primary dark:text-white text-xl font-bold mb-2"><?php echo esc_html( $book['title'] ); ?></h4>
						<p class="text-gray-500 text-sm mb-4"><?php echo esc_html( $book['author'] ); ?></p>
						<a class="text-accent font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all" href="<?php echo esc_url( home_url( '/books' ) ); ?>">
							Learn More <span class="material-symbols-outlined !text-base">arrow_forward</span>
						</a>
					</div>
					<?php
				endforeach;
			} else {
				while ( $books_query->have_posts() ) :
					$books_query->the_post();
					?>
					<div class="group cursor-pointer">
						<a href="<?php the_permalink(); ?>" class="block aspect-[3/4] rounded-xl overflow-hidden shadow-md mb-6 transition-transform duration-300 group-hover:-translate-y-2">
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
								<img alt="<?php the_title_attribute(); ?> Book Cover" class="w-full h-full object-cover" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/book-placeholder.jpg' ); ?>"/>
							<?php endif; ?>
						</a>
						<h4 class="text-primary dark:text-white text-xl font-bold mb-2">
							<a href="<?php the_permalink(); ?>" class="hover:text-accent transition-colors"><?php the_title(); ?></a>
						</h4>
						<p class="text-gray-500 text-sm mb-4">
							<?php
							$author = get_post_meta( get_the_ID(), 'book_author', true );
							echo $author ? esc_html( 'By ' . $author ) : 'By Terry Shaguy';
							?>
						</p>
						<a class="text-accent font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all" href="<?php the_permalink(); ?>">
							Learn More <span class="material-symbols-outlined !text-base">arrow_forward</span>
						</a>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			}
			?>
		</div>
	</div>
</section>

<!-- Newsletter Section -->
<section class="max-w-[1280px] mx-auto px-6 py-20 border-t border-gray-100 dark:border-[#1d3a24]">
	<div class="bg-primary/5 dark:bg-primary/20 rounded-[2rem] p-10 md:p-20 flex flex-col items-center text-center">
		<div class="bg-primary text-white p-4 rounded-2xl mb-8">
			<span class="material-symbols-outlined !text-4xl">favorite</span>
		</div>
		<h2 class="text-3xl md:text-5xl font-bold mb-6 dark:text-white">Partner in Our Journey</h2>
		<p class="text-gray-600 dark:text-gray-300 max-w-xl mx-auto mb-10 text-lg leading-relaxed">
			Stay connected with our monthly mission updates, travel reports, and joint ministry resources.
		</p>
		<?php
		// Display success/error messages
		if ( isset( $_GET['newsletter'] ) ) {
			if ( 'success' === $_GET['newsletter'] ) {
				echo '<div id="newsletter-message" class="newsletter-message newsletter-success mb-6 p-6 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border-2 border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 text-center max-w-lg mx-auto shadow-lg flex items-center justify-center gap-3">
					<span class="material-symbols-outlined text-3xl">check_circle</span>
					<div>
						<p class="font-bold text-lg mb-1">Thank you for subscribing!</p>
						<p class="text-sm opacity-90">We\'ll keep you updated with our latest news.</p>
					</div>
				</div>';
			} elseif ( 'error' === $_GET['newsletter'] ) {
				echo '<div id="newsletter-message" class="newsletter-message newsletter-error mb-6 p-6 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/30 dark:to-rose-900/30 border-2 border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 text-center max-w-lg mx-auto shadow-lg flex items-center justify-center gap-3">
					<span class="material-symbols-outlined text-3xl">error</span>
					<div>
						<p class="font-bold text-lg mb-1">Oops!</p>
						<p class="text-sm opacity-90">Please enter a valid email address.</p>
					</div>
				</div>';
			}
		}
		?>
		<form id="newsletter-form" class="flex flex-col sm:flex-row gap-4 w-full max-w-lg" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="tsm_newsletter_signup">
			<?php wp_nonce_field( 'tsm_newsletter_signup', 'tsm_newsletter_nonce' ); ?>
			<input id="newsletter-email" class="newsletter-email flex-grow rounded-xl border-gray-200 dark:border-[#1d3a24] dark:bg-background-dark px-6 py-5 focus:ring-accent focus:border-accent transition-all" placeholder="Enter your email address" type="email" name="email" required/>
			<button id="newsletter-submit" type="submit" disabled class="bg-primary text-white font-bold px-10 py-5 rounded-xl hover:bg-primary/90 hover:scale-105 transition-all shadow-xl shadow-primary/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">Subscribe</button>
		</form>
	</div>
</section>

<?php
get_footer();
