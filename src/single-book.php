<?php
/**
 * The template for displaying single book posts
 *
 * @package TSM_Theme
 */

get_header();

while ( have_posts() ) :
	the_post();
	
	// Capture current post ID immediately to ensure it's always available
	$current_post_id = get_the_ID();
	
	// Get book meta fields
	$book_author = get_post_meta( $current_post_id, 'book_author', true );
	$book_buy_url = get_post_meta( get_the_ID(), 'book_buy_url', true );
	$book_excerpt_url = get_post_meta( get_the_ID(), 'book_excerpt_url', true );
	$book_amazon_url = get_post_meta( get_the_ID(), 'book_amazon_url', true );
	$book_selar_url = get_post_meta( get_the_ID(), 'book_selar_url', true );
	$book_free_download_file_id = get_post_meta( get_the_ID(), 'book_free_download_file_id', true );
	$book_sample_download_file_id = get_post_meta( get_the_ID(), 'book_sample_download_file_id', true );
	$book_price = get_post_meta( get_the_ID(), 'book_price', true );
	
	// Get download URLs - only use file uploads, not URLs
	$download_url = '';
	if ( $book_free_download_file_id ) {
		$download_url = add_query_arg( array(
			'download_book' => '1',
			'book_id'       => get_the_ID(),
		), home_url( '/' ) );
	}
	
	$sample_download_url = '';
	if ( $book_sample_download_file_id ) {
		$sample_download_url = add_query_arg( array(
			'download_sample' => '1',
			'book_id'         => get_the_ID(),
		), home_url( '/' ) );
	}
	$book_price_original = get_post_meta( get_the_ID(), 'book_price_original', true );
	$book_badge = get_post_meta( get_the_ID(), 'book_badge', true );
	$book_summary = get_post_meta( get_the_ID(), 'book_summary', true );
	$book_reviews = get_post_meta( get_the_ID(), 'book_reviews', true );
	if ( ! is_array( $book_reviews ) ) {
		$book_reviews = array();
	}
	$book_categories = get_the_terms( get_the_ID(), 'book_category' );
	$book_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	
	// Use summary if available, otherwise use excerpt, otherwise use content
	$book_description = ! empty( $book_summary ) 
		? $book_summary 
		: ( get_the_excerpt() ? get_the_excerpt() : get_the_content() );
	
	// Get subtitle from excerpt or first line of content
	$book_subtitle = '';
	if ( get_the_excerpt() ) {
		$excerpt_lines = explode( '.', get_the_excerpt() );
		$book_subtitle = ! empty( $excerpt_lines[0] ) ? trim( $excerpt_lines[0] ) : '';
	}
	
	// Get related books (same category, excluding current)
	// $current_post_id is already set above
	$excluded_ids = array( $current_post_id );
	
	// First, try to get books from same categories
	$related_args = array(
		'post_type'      => 'book',
		'posts_per_page' => 4,
		'post__not_in'   => $excluded_ids,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	
	if ( $book_categories && ! is_wp_error( $book_categories ) ) {
		$related_args['tax_query'] = array(
			array(
				'taxonomy' => 'book_category',
				'field'    => 'term_id',
				'terms'    => wp_list_pluck( $book_categories, 'term_id' ),
			),
		);
	}
	
	$related_books = new WP_Query( $related_args );
	
	// Collect IDs of fetched books (excluding current post)
	$fetched_ids = array();
	if ( $related_books->have_posts() ) {
		foreach ( $related_books->posts as $post ) {
			// Skip current post if it somehow appears in results
			if ( $post->ID !== $current_post_id ) {
				$fetched_ids[] = $post->ID;
			}
		}
		// Merge fetched IDs while ensuring current post ID is always excluded
		$excluded_ids = array_unique( array_merge( array( $current_post_id ), $fetched_ids ) );
		// Remove current post from posts array if it somehow got through and reset array keys
		$related_books->posts = array_values( array_filter( $related_books->posts, function( $post ) use ( $current_post_id ) {
			return $post->ID !== $current_post_id;
		} ) );
		$related_books->post_count = count( $related_books->posts );
	}
	
	// If not enough related books, get any other books (excluding already fetched ones)
	if ( $related_books->post_count < 4 ) {
		$related_args['tax_query'] = '';
		// Ensure current post ID is always excluded
		$related_args['post__not_in'] = array_unique( array_merge( array( $current_post_id ), $fetched_ids ) );
		$related_args['posts_per_page'] = 4 - $related_books->post_count;
		$related_books_extra = new WP_Query( $related_args );
		
		if ( $related_books_extra->have_posts() ) {
			// Merge posts without duplicates and exclude current post
			$merged_posts = $related_books->posts;
			foreach ( $related_books_extra->posts as $post ) {
				// Skip if already fetched or if it's the current post
				if ( ! in_array( $post->ID, $fetched_ids ) && $post->ID !== $current_post_id ) {
					$merged_posts[] = $post;
					$fetched_ids[] = $post->ID;
				}
			}
			
			// Final filter to ensure current post is never included, reset array keys
			$merged_posts = array_values( array_filter( $merged_posts, function( $post ) use ( $current_post_id ) {
				return $post->ID !== $current_post_id;
			} ) );
			
			$related_books->posts = $merged_posts;
			$related_books->post_count = count( $merged_posts );
		}
		wp_reset_postdata();
	}
	?>

	<main class="flex-1">
		<div class="max-w-[1280px] mx-auto px-6 py-8">

      <!-- Back to Books -->
      <div class="mb-8 flex justify-between items-end">
				<a class="inline-flex items-center gap-2 text-primary hover:text-accent transition-colors font-semibold bg-accent/20 backdrop-blur-sm px-4 py-2 rounded-lg" href="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>">
					<span class="material-symbols-outlined text-sm">arrow_back</span>
					<?php _e( 'Back to Books', 'tsm-theme' ); ?>
				</a>
			</div>

			<!-- Book Hero Section -->
			<div class="flex flex-col lg:flex-row gap-12 items-start bg-white dark:bg-[#162a1c] p-8 rounded-xl shadow-sm">
				<!-- Left: Book Cover -->
				<div class="w-full lg:w-1/3 flex-shrink-0">
					<div class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg shadow-2xl transition-transform hover:scale-[1.02]" style='background-image: url("<?php echo esc_url( $book_image ? $book_image : get_template_directory_uri() . '/assets/images/book-placeholder.jpg' ); ?>");'>
					</div>
				</div>

				<!-- Right: Content & Purchase -->
				<div class="flex-1 flex flex-col gap-6">
					<div>
						<?php if ( $book_badge ) : ?>
              <span class="text-primary font-bold tracking-widest text-xs uppercase"><?php echo esc_html( $book_badge ); ?></span>
						<?php endif; ?>
						<h1 class="text-accent dark:text-white text-4xl lg:text-5xl font-bold leading-tight font-display">
							<?php echo esc_html( get_the_title( $current_post_id ) ); ?>
						</h1>
						<p class="text-lg text-gray-500 dark:text-gray-400 mt-1 mb-6">
							By <span class="font-semibold text-accent/80 uppercase"><?php echo esc_html( $book_author ? $book_author : 'Dr. Tor Terry Shaguy' ); ?></span>
						</p>
            <div class="h-1.5 w-24 bg-primary rounded-full mb-6"></div>
					</div>

					<div class="border-b border-[#e7f3ea] dark:border-[#1a3321] pb-6">
						<h2 class="font-medium text-2xl text-accent dark:text-primary mb-4 sans-serif font-sans tracking-tight">About the Book</h2>
						<div class="text-gray-900 dark:text-gray-300 text-base leading-relaxed [&>p]:mb-4 [&>p:last-child]:mb-0 [&>ul]:mb-4 [&>ul]:list-disc [&>ul]:ml-6 [&>ul>li]:mb-2 [&>ol]:mb-4 [&>ol]:list-decimal [&>ol]:ml-6 [&>ol>li]:mb-2 [&>h1]:mb-4 [&>h2]:mb-4 [&>h3]:mb-4 [&>h4]:mb-4 [&>h5]:mb-4 [&>h6]:mb-4">
							<?php 
							the_content();
							
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . __( 'Pages:', 'tsm-theme' ),
									'after'  => '</div>',
								)
							);
							?>
						</div>
					</div>

					<!-- Download Free Chapter / Sample Section -->
					<?php if ( $sample_download_url && $book_sample_download_file_id ) : ?>
						<div class="flex flex-col gap-2">
            <p class="text-sm text-[#4c9a5f]">Get a sneak peek before you buy</p>
							<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="<?php echo esc_url( $sample_download_url ); ?>" class="flex items-center justify-center gap-2 border-2 border-primary text-primary hover:bg-primary/10 font-bold py-3 px-6 rounded-lg transition-all">
                  <span class="material-symbols-outlined">menu_book</span>
                  Download Free Chapter
                </a>
              </div>
						</div>
					<?php endif; ?>

					<!-- Free Download Section -->
					<?php if ( $download_url && $book_free_download_file_id ) : ?>
						<a href="<?php echo esc_url( $download_url ); ?>" class="inline-flex items-center justify-center gap-2 bg-primary text-white hover:text-white text-base font-bold px-6 py-3 rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all w-auto self-start">
							<span class="material-symbols-outlined">download</span>
							Free Download
						</a>
					<?php endif; ?>

					<!-- Purchase Section -->
					<?php 
					// Use Amazon URL if set, otherwise fall back to Buy URL
					$amazon_link = $book_amazon_url ? $book_amazon_url : $book_buy_url;
					if ( $amazon_link || $book_selar_url ) : ?>
						<div class="flex flex-col gap-4">
							<h3 class="text-sm font-bold uppercase tracking-wider text-[#4c9a5f]">Purchase Options</h3>
							<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
								<?php if ( $amazon_link ) : ?>
									<a href="<?php echo esc_url( $amazon_link ); ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 bg-[#0d1b11] dark:bg-black hover:bg-opacity-80 text-white hover:text-white font-bold py-3 px-6 rounded-lg transition-all shadow-md">
										<span class="material-symbols-outlined">menu_book</span>
										Amazon
									</a>
								<?php endif; ?>
								<?php if ( $book_selar_url ) : ?>
									<a href="<?php echo esc_url( $book_selar_url ); ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 bg-primary text-white hover:text-white text-base font-bold px-6 py-3 rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all">
										<span class="material-symbols-outlined">shopping_cart</span>
										Selar
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- Testimonials -->
			<?php if ( ! empty( $book_reviews ) && is_array( $book_reviews ) ) : ?>
				<?php
				// Filter out empty testimonials
				$valid_testimonials = array_filter( $book_reviews, function( $review ) {
					return ! empty( $review['name'] ) && ! empty( $review['text'] );
				} );
				
				if ( ! empty( $valid_testimonials ) ) :
					// Display up to 3 testimonials
					$testimonials_to_show = array_slice( $valid_testimonials, 0, 3 );
					?>
					<section class="mt-20">
						<div class="flex items-center justify-between mb-8">
							<h2 class="text-accent dark:text-white text-3xl font-bold font-display">What Readers Are Saying</h2>
						</div>
						<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
							<?php
							foreach ( $testimonials_to_show as $review ) {
								$rating = isset( $review['rating'] ) ? absint( $review['rating'] ) : 5;
								?>
								<div class="bg-white dark:bg-[#162a1c] p-6 rounded-xl border border-[#e7f3ea] dark:border-[#1a3321] relative">
									<span class="material-symbols-outlined text-primary text-4xl opacity-30 absolute top-4 right-4">format_quote</span>
									<div class="flex text-primary mb-3">
										<?php for ( $i = 0; $i < $rating; $i++ ) : ?>
											<span class="material-symbols-outlined text-sm">star</span>
										<?php endfor; ?>
									</div>
									<p class="text-sm italic mb-4 text-accent"><?php echo esc_html( $review['text'] ); ?></p>
									<p class="font-bold text-sm text-accent">— <?php echo esc_html( $review['name'] ); ?><?php if ( ! empty( $review['role'] ) ) : ?>, <span class="font-normal text-gray-500"><?php echo esc_html( $review['role'] ); ?></span><?php endif; ?></p>
								</div>
								<?php
							}
							?>
						</div>
					</section>
				<?php endif; ?>
			<?php endif; ?>

			<!-- Related Books -->
			<?php if ( $related_books->have_posts() ) : ?>
				<section class="mt-20 pb-16">
					<div class="flex items-center gap-4 mb-8">
						<h2 class="text-accent dark:text-white text-3xl font-bold font-display">More from Terry Shaguy</h2>
					</div>
					<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 pb-20">
						<?php
						$book_index = 0;
						while ( $related_books->have_posts() ) :
							$related_books->the_post();
							$related_post_id = get_the_ID();
							
							// Skip current book if it somehow appears
							if ( $related_post_id === $current_post_id ) {
								continue;
							}
							
							$book_index++;
							$related_image = get_the_post_thumbnail_url( $related_post_id, 'medium' );
							$related_categories = get_the_terms( $related_post_id, 'book_category' );
							$related_badge = get_post_meta( $related_post_id, 'book_badge', true );
							$related_book_title = get_the_title( $related_post_id );
							// Hide books based on screen size:
							// Book 3: hidden on small, visible on medium+ (hidden md:block)
							// Book 4: hidden on small/medium, visible on large (1024px+)
							$hide_classes = '';
							if ( $book_index === 3 ) {
								$hide_classes = 'hidden md:block'; // Hide on small (<768px), show on medium+ (>=768px)
							} elseif ( $book_index === 4 ) {
								$hide_classes = 'hidden lg:block'; // Hide on small/medium (<1024px), show on large (>=1024px)
							}
							?>
							<a href="<?php the_permalink(); ?>" class="book-card group flex flex-col bg-white dark:bg-[#162b1b] rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-emerald-50 dark:border-emerald-900/20 cursor-pointer <?php echo esc_attr( $hide_classes ); ?>">
								<div class="relative aspect-[3/4] w-full overflow-hidden bg-gray-100">
									<div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style='background-image: url("<?php echo esc_url( $related_image ? $related_image : get_template_directory_uri() . '/assets/images/book-placeholder.jpg' ); ?>");'>
									</div>
									<?php if ( $related_badge ) : ?>
										<div class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider"><?php echo esc_html( $related_badge ); ?></div>
									<?php endif; ?>
								</div>
								<div class="p-5 flex flex-col flex-1">
									<?php if ( $related_categories && ! is_wp_error( $related_categories ) && ! empty( $related_categories ) ) : ?>
										<span class="text-[10px] text-primary font-bold uppercase tracking-wider mb-1"><?php echo esc_html( $related_categories[0]->name ); ?></span>
									<?php endif; ?>
									<h3 class="text-accent dark:text-white font-bold text-lg leading-snug mb-2 transition-colors"><?php echo esc_html( $related_book_title ); ?></h3>
								</div>
							</a>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</main>

	<?php
endwhile;
get_footer();
