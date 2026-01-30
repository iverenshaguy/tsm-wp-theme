<?php
/**
 * The template for displaying book archives
 *
 * @package TSM_Theme
 */

get_header();

// Get search query and category filter from URL
$search_query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
$category_filter = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';
$sort_by = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : 'newest';

// Modify the main query if filters are applied
global $wp_query;

// Get featured book - check customizer first, then meta field
$featured_book_id = get_theme_mod( 'books_featured_book', 0 );
$featured_book_id = absint( $featured_book_id );

// Initialize featured query variable
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

// Get all categories
$categories = get_terms(
	array(
		'taxonomy'   => 'book_category',
		'hide_empty' => true,
	)
);

// Build query args for books grid (excluding featured)
$books_args = array(
	'post_type'      => 'book',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
);

// Exclude featured book from grid - get ID without advancing query
$featured_id = 0;
if ( $featured_query && $featured_query->have_posts() ) {
	// Get the first post ID without calling the_post()
	$featured_id = $featured_query->posts[0]->ID;
	if ( $featured_id > 0 ) {
		$books_args['post__not_in'] = array( $featured_id );
	}
}

// Add search
if ( ! empty( $search_query ) ) {
	$books_args['s'] = $search_query;
}

// Add category filter
if ( ! empty( $category_filter ) && $category_filter !== 'all' ) {
	$books_args['tax_query'] = array(
		array(
			'taxonomy' => 'book_category',
			'field'    => 'slug',
			'terms'    => $category_filter,
		),
	);
}

// Add sorting
switch ( $sort_by ) {
	case 'title':
		$books_args['orderby'] = 'title';
		$books_args['order']   = 'ASC';
		break;
	case 'oldest':
		$books_args['orderby'] = 'date';
		$books_args['order']   = 'ASC';
		break;
	case 'newest':
	default:
		$books_args['orderby'] = 'date';
		$books_args['order']   = 'DESC';
		break;
}

$books_query = new WP_Query( $books_args );

?>

<main class="flex-grow">
	<div class="max-w-[1280px] mx-auto px-6 py-12">
		<!-- HeroSection: Featured Release -->
		<?php if ( $featured_query && $featured_query->have_posts() ) : ?>
			<?php
			$featured_query->the_post();
			$featured_price = get_post_meta( get_the_ID(), 'book_price', true );
			$featured_price_original = get_post_meta( get_the_ID(), 'book_price_original', true );
			$featured_amazon_url = get_post_meta( get_the_ID(), 'book_amazon_url', true );
			$featured_buy_url = get_post_meta( get_the_ID(), 'book_buy_url', true );
			// Use Amazon URL if available, otherwise fallback to buy URL
			// Prioritize Amazon URL, but also allow buy_url as fallback
			if ( ! empty( $featured_amazon_url ) && filter_var( $featured_amazon_url, FILTER_VALIDATE_URL ) ) {
				$featured_purchase_url = $featured_amazon_url;
			} elseif ( ! empty( $featured_buy_url ) && filter_var( $featured_buy_url, FILTER_VALIDATE_URL ) ) {
				$featured_purchase_url = $featured_buy_url;
			} else {
				$featured_purchase_url = '';
			}
			$featured_excerpt_url = get_post_meta( get_the_ID(), 'book_excerpt_url', true );
			$featured_summary = get_post_meta( get_the_ID(), 'book_summary', true );
			$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
			
			// Use summary if available, otherwise use excerpt, otherwise fallback text
			$featured_description = ! empty( $featured_summary ) 
				? $featured_summary 
				: ( get_the_excerpt() ? get_the_excerpt() : 'Discover the latest insights from Terry Shaguy in this transformative guide to spiritual growth and enduring faith. A cornerstone for every believer\'s library.' );
			?>
			<div class="@container my-10">
				<div class="flex flex-col gap-6 px-4 py-12 rounded-2xl bg-white dark:bg-[#162b1b] border border-emerald-100 dark:border-emerald-900/50 shadow-sm @[480px]:gap-8 @[960px]:flex-row items-center">
					<div class="w-full flex justify-center @[960px]:w-1/2">
						<div class="w-full max-w-[340px] aspect-[3/4] bg-cover bg-center rounded-lg shadow-2xl transform hover:scale-[1.02] transition-transform duration-500 border border-gray-100 dark:border-gray-800" style='background-image: url("<?php echo esc_url( $featured_image ? $featured_image : get_template_directory_uri() . '/assets/images/book-placeholder.jpg' ); ?>");'>
						</div>
					</div>
					<div class="flex flex-col gap-6 @[480px]:min-w-[400px] @[480px]:gap-8 @[960px]:w-1/2 @[960px]:justify-center">
						<div class="flex flex-col gap-3 text-left">
							<span class="text-primary font-bold tracking-widest text-xs uppercase">New Arrival</span>
							<h1 class="text-accent dark:text-white text-4xl font-bold leading-tight tracking-[-0.033em] @[480px]:text-5xl">
								<?php the_title(); ?>
							</h1>
							<p class="text-gray-600 dark:text-gray-400 text-base @[480px]:text-lg leading-relaxed">
								<?php echo wp_kses_post( $featured_description ); ?>
							</p>
						</div>
						<div class="flex flex-row flex-nowrap gap-4">
							<?php if ( ! empty( $featured_purchase_url ) ) : ?>
								<a href="<?php echo esc_url( $featured_purchase_url ); ?>" target="_blank" rel="noopener" class="flex flex-1 min-w-0 cursor-pointer items-center justify-center rounded-lg px-4 py-3 bg-primary text-white hover:text-white text-sm sm:text-base font-bold shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all whitespace-nowrap">
									<span class="truncate">Purchase Now</span>
								</a>
							<?php endif; ?>
							<a href="<?php the_permalink(); ?>" class="flex flex-1 min-w-0 cursor-pointer items-center justify-center rounded-lg px-4 py-3 bg-transparent border-2 border-primary text-primary text-sm sm:text-base font-bold hover:bg-primary/5 transition-all whitespace-nowrap">
								<span class="truncate">Learn More</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		endif;
		?>

		<!-- Search & Filters -->
		<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-8 border-t border-emerald-50 dark:border-emerald-900/20">
			<!-- Search -->
			<div class="flex-1 max-w-xl">
				<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'book' ) ); ?>">
					<label class="flex flex-col h-12 w-full">
						<div class="flex w-full flex-1 items-stretch rounded-xl h-full shadow-sm">
							<div class="text-primary flex border-none bg-white dark:bg-[#162b1b] items-center justify-center pl-4 rounded-l-xl" data-icon="search">
								<span class="material-symbols-outlined">search</span>
							</div>
							<input type="search" name="s" value="<?php echo esc_attr( $search_query ); ?>" class="form-input flex w-full min-w-0 flex-1 border-none bg-white dark:bg-[#162b1b] text-accent dark:text-white focus:ring-0 h-full placeholder:text-gray-400 px-4 rounded-r-xl pl-2 text-base font-normal leading-normal" placeholder="Search titles, topics, or keywords..." id="book-search-input"/>
							<?php if ( ! empty( $category_filter ) ) : ?>
								<input type="hidden" name="category" value="<?php echo esc_attr( $category_filter ); ?>">
							<?php endif; ?>
							<?php if ( ! empty( $sort_by ) ) : ?>
								<input type="hidden" name="sort" value="<?php echo esc_attr( $sort_by ); ?>">
							<?php endif; ?>
						</div>
					</label>
				</form>
			</div>
			<!-- Chips / Categories -->
			<div class="flex gap-2 overflow-x-auto no-scrollbar">
				<a href="<?php echo esc_url( remove_query_arg( array( 'category', 's', 'sort' ) ) ); ?>" id="clear-filters-link" class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo ( empty( $category_filter ) || $category_filter === 'all' ) ? 'bg-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
					<p class="<?php echo ( empty( $category_filter ) || $category_filter === 'all' ) ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>">All Resources</p>
				</a>
				<?php 
				// Show only first 4 categories
				$display_categories = array_slice( $categories, 0, 4 );
				foreach ( $display_categories as $category ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'category', $category->slug, remove_query_arg( array( 's', 'sort' ) ) ) ); ?>" class="category-filter-link flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo ( $category_filter === $category->slug ) ? 'bg-primary border-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
						<p class="<?php echo ( $category_filter === $category->slug ) ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>"><?php echo esc_html( $category->name ); ?></p>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- SectionHeader -->
		<div class="flex items-center justify-between px-4 pb-6 pt-2">
			<h2 class="text-accent dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]">All Publications</h2>
		</div>

		<!-- Books Grid -->
		<?php if ( $books_query->have_posts() ) : ?>
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pb-20" id="books-grid">
				<?php while ( $books_query->have_posts() ) : ?>
					<?php
					$books_query->the_post();
					$book_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
					$book_categories = get_the_terms( get_the_ID(), 'book_category' );
					$book_badge = get_post_meta( get_the_ID(), 'book_badge', true );
					$book_price = get_post_meta( get_the_ID(), 'book_price', true );
					$book_title = get_the_title();
					$book_title_lower = strtolower( $book_title );
					$book_category_names = '';
					if ( $book_categories && ! is_wp_error( $book_categories ) && ! empty( $book_categories ) ) {
						$book_category_names = strtolower( implode( ' ', wp_list_pluck( $book_categories, 'name' ) ) );
					}
					?>
					<a href="<?php the_permalink(); ?>" class="book-card group flex flex-col bg-white dark:bg-[#162b1b] rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-emerald-50 dark:border-emerald-900/20 cursor-pointer" data-title="<?php echo esc_attr( $book_title_lower ); ?>" data-categories="<?php echo esc_attr( $book_category_names ); ?>">
						<div class="relative aspect-[3/4] w-full overflow-hidden bg-gray-100">
							<div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style='background-image: url("<?php echo esc_url( $book_image ? $book_image : get_template_directory_uri() . '/assets/images/book-placeholder.jpg' ); ?>");'>
							</div>
							<?php if ( $book_badge ) : ?>
								<div class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider"><?php echo esc_html( $book_badge ); ?></div>
							<?php endif; ?>
						</div>
						<div class="p-5 flex flex-col flex-1">
							<?php if ( $book_categories && ! is_wp_error( $book_categories ) && ! empty( $book_categories ) ) : ?>
								<span class="text-[10px] text-primary font-bold uppercase tracking-wider mb-1"><?php echo esc_html( $book_categories[0]->name ); ?></span>
							<?php endif; ?>
							<h3 class="text-accent dark:text-white font-bold text-lg leading-snug mb-2 transition-colors"><?php echo esc_html( $book_title ); ?></h3>
						</div>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="my-10 text-center py-12" id="no-books-message" style="display: none;">
				<p class="text-gray-600 dark:text-gray-400 text-lg">No books found.</p>
				<a href="<?php echo esc_url( remove_query_arg( array( 'category', 's', 'sort' ) ) ); ?>" id="clear-filters-link-bottom" class="mt-4 inline-block px-6 py-2 rounded-lg border border-primary text-primary hover:bg-primary/10 font-semibold transition-colors">
					Clear filters
				</a>
			</div>
		<?php endif; ?>
	</div>
</main>

<script>
(function() {
	// Save scroll position before navigation
	function saveScrollPosition() {
		sessionStorage.setItem('bookArchiveScrollPosition', window.pageYOffset || document.documentElement.scrollTop);
	}

	// Restore scroll position
	function restoreScrollPosition() {
		const savedPosition = sessionStorage.getItem('bookArchiveScrollPosition');
		if (savedPosition !== null) {
			window.scrollTo(0, parseInt(savedPosition, 10));
			sessionStorage.removeItem('bookArchiveScrollPosition');
		}
	}

	// Restore scroll on page load
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', restoreScrollPosition);
	} else {
		restoreScrollPosition();
	}

	// Category filter links
	const categoryLinks = document.querySelectorAll('.category-filter-link');
	categoryLinks.forEach(function(link) {
		link.addEventListener('click', function(e) {
			saveScrollPosition();
		});
	});

	// Clear filters link
	const clearFiltersLink = document.getElementById('clear-filters-link');
	if (clearFiltersLink) {
		clearFiltersLink.addEventListener('click', function(e) {
			saveScrollPosition();
		});
	}


	// Search input - filter without page reload
	let searchTimeout;
	const searchInput = document.getElementById('book-search-input');
	const booksGrid = document.getElementById('books-grid');
	const noBooksMessage = document.getElementById('no-books-message');
	
	if (searchInput && booksGrid) {
		function filterBooks() {
			const searchTerm = searchInput.value.toLowerCase().trim();
			const bookCards = booksGrid.querySelectorAll('.book-card');
			let visibleCount = 0;
			
			bookCards.forEach(function(card) {
				const title = card.getAttribute('data-title') || '';
				const categories = card.getAttribute('data-categories') || '';
				const searchableText = title + ' ' + categories;
				
				if (searchTerm === '' || searchableText.includes(searchTerm)) {
					card.style.display = '';
					visibleCount++;
				} else {
					card.style.display = 'none';
				}
			});
			
			// Show/hide no results message
			if (noBooksMessage) {
				if (visibleCount === 0 && searchTerm !== '') {
					noBooksMessage.style.display = 'block';
					booksGrid.style.display = 'none';
				} else {
					noBooksMessage.style.display = 'none';
					booksGrid.style.display = 'grid';
				}
			}
		}
		
		searchInput.addEventListener('input', function() {
			clearTimeout(searchTimeout);
			searchTimeout = setTimeout(filterBooks, 300);
		});
		
		// Prevent form submission
		const searchForm = searchInput.closest('form');
		if (searchForm) {
			searchForm.addEventListener('submit', function(e) {
				e.preventDefault();
				filterBooks();
			});
		}
	}
})();
</script>

<?php
get_footer();
