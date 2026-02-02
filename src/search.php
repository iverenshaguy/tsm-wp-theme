<?php
/**
 * The template for displaying search results and category archives
 *
 * @package TSM_Theme
 */

get_header();

// Determine if this is a search or category archive
$is_search = is_search();
$is_category_archive = is_category();
$has_search_query = isset( $_GET['s'] ) && ! empty( $_GET['s'] );
$current_category = null;
$page_title = '';
$page_subtitle = '';

if ( $is_search ) {
	$page_title = 'Search Results';
	$page_subtitle = get_search_query();
} elseif ( $is_category_archive ) {
	$current_category = get_queried_object();
	if ( $has_search_query ) {
		// Category archive with search query
		$page_title = 'Search Results';
		$page_subtitle = sanitize_text_field( $_GET['s'] );
	} else {
		// Regular category archive
		$page_title = 'Category: ' . $current_category->name;
		$page_subtitle = 'Browsing Articles';
	}
}

// Get all categories for filter pills
$all_categories = get_categories( array(
	'orderby' => 'name',
	'order'   => 'ASC',
	'hide_empty' => true,
) );

// Find "Articles" category for "All" button
$articles_category = null;
$categories = array();
foreach ( $all_categories as $cat ) {
	if ( strtolower( $cat->slug ) === 'articles' || strtolower( $cat->name ) === 'articles' ) {
		$articles_category = $cat;
	} else {
		$categories[] = $cat;
	}
}

// Get current category slug for filter pills
$current_category_slug = 'all';
if ( $is_category_archive && $current_category ) {
	$current_category_slug = $current_category->slug;
} elseif ( $articles_category && $is_category_archive && $current_category && $current_category->term_id === $articles_category->term_id ) {
	$current_category_slug = 'all';
}

// Customizer settings for newsletter
$show_subscribe_form = tsm_get_theme_mod_cached( 'articles_show_subscribe_form', true );
$newsletter_title = tsm_get_theme_mod_cached( 'articles_newsletter_title', 'Weekly Resources' );
$newsletter_description = tsm_get_theme_mod_cached( 'articles_newsletter_description', 'Join 5,000+ others receiving weekly encouragement and articles directly in their inbox.' );
$newsletter_form_id = tsm_get_theme_mod_cached( 'articles_newsletter_form_id', '' );
?>

<main class="max-w-[1200px] mx-auto px-6 py-12">
	<!-- Category Filter Pills -->
	<div class="mb-12">
		<div class="flex overflow-x-auto gap-2 no-scrollbar">
			<?php if ( $articles_category ) : ?>
				<?php
				$posts_page_url = get_category_link( $articles_category->term_id );
				?>
				<a href="<?php echo esc_url( $posts_page_url ); ?>" 
				   class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo ( $current_category_slug === 'all' ) ? 'bg-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
					<p class="<?php echo ( $current_category_slug === 'all' ) ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>">All</p>
				</a>
			<?php endif; ?>
			<?php if ( ! empty( $categories ) ) : ?>
				<?php foreach ( $categories as $cat ) : ?>
					<?php $is_category_selected = ( $current_category_slug === $cat->slug ); ?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" 
					   class="category-filter-link flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo $is_category_selected ? 'bg-primary border-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
						<p class="<?php echo $is_category_selected ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>"><?php echo esc_html( $cat->name ); ?></p>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	
	<?php if ( ( $is_search || $has_search_query ) && $page_title ) : ?>
		<div class="mb-12 border-b border-[#dbe6de] dark:border-gray-800 pb-8">
			<p class="text-sm font-bold uppercase tracking-widest text-[rgb(51,154,70)] dark:text-primary/80 mb-2">Articles</p>
			<h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-[#2d4a34] dark:text-white"><?php echo esc_html( $page_title ); ?></h2>
			<?php if ( $page_subtitle ) : ?>
				<p class="mt-2 text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( $page_subtitle ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<div class="flex flex-col gap-12 lg:flex-row">
		<div class="flex-1 space-y-10">
			<?php if ( have_posts() ) : ?>
				<section class="space-y-10">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
						// Get featured image with fallback
						$featured_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
						if ( ! $featured_image_url ) {
							$content = get_the_content();
							preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches );
							if ( ! empty( $matches[1] ) ) {
								$featured_image_url = $matches[1];
							} else {
								$post_categories = get_the_category();
								$category_name = ! empty( $post_categories ) ? strtolower( $post_categories[0]->name ) : '';
								$placeholder_file = 'article-placeholder.png';
								
								if ( stripos( $category_name, 'word for the month' ) !== false ) {
									$placeholder_file = 'word-for-the-month-placeholder.png';
								} elseif ( stripos( $category_name, 'favour for the week' ) !== false ) {
									$placeholder_file = 'favour-for-the-week-placeholder.png';
								}
								
								$placeholder_path = get_template_directory() . '/assets/images/' . $placeholder_file;
								$featured_image_url = get_template_directory_uri() . '/assets/images/' . $placeholder_file;
								
								if ( ! file_exists( $placeholder_path ) ) {
									$article_placeholder = get_template_directory() . '/assets/images/article-placeholder.png';
									if ( file_exists( $article_placeholder ) ) {
										$featured_image_url = get_template_directory_uri() . '/assets/images/article-placeholder.png';
									} else {
										$featured_image_url = get_template_directory_uri() . '/assets/images/book-placeholder.png';
									}
								}
							}
						}
						
						$post_categories = get_the_category();
						$primary_category = ! empty( $post_categories ) ? $post_categories[0] : null;
						$excerpt = get_the_excerpt();
						if ( empty( $excerpt ) ) {
							$excerpt = wp_trim_words( get_the_content(), 30 );
						}
						?>
						<a href="<?php the_permalink(); ?>" class="flex flex-col gap-8 p-6 bg-white rounded-2xl border border-gray-100 transition-all duration-300 group md:flex-row dark:bg-gray-900/50 dark:border-gray-800 hover:shadow-lg">
							<div class="w-full md:w-64 aspect-[4/3] relative overflow-hidden rounded-xl shrink-0">
								<div class="absolute inset-0 bg-center bg-cover transition-transform duration-700 group-hover:scale-105" style="background-image: url('<?php echo esc_url( $featured_image_url ); ?>');"></div>
							</div>
							<div class="flex flex-col justify-center">
								<?php if ( $primary_category ) : ?>
									<span class="text-[rgb(51,154,70)] dark:text-primary text-xs font-bold uppercase tracking-widest mb-2"><?php echo esc_html( $primary_category->name ); ?></span>
								<?php endif; ?>
								<h3 class="text-2xl font-bold mb-3 group-hover:text-[rgb(51,154,70)] dark:group-hover:text-primary transition-colors">
									<?php the_title(); ?>
								</h3>
								<p class="mb-4 text-sm leading-relaxed text-gray-600 dark:text-gray-400 line-clamp-2">
									<?php echo esc_html( $excerpt ); ?>
								</p>
								<div class="flex gap-4 items-center mt-auto">
									<span class="text-xs text-gray-400"><?php echo esc_html( get_the_date( 'M d, Y' ) ); ?></span>
									<span class="text-gray-300">•</span>
									<span class="inline-flex gap-2 items-center text-sm font-bold transition-all text-primary group-hover:gap-3">
										Read More
										<span class="material-symbols-outlined !text-base">arrow_forward</span>
									</span>
								</div>
							</div>
						</a>
					<?php endwhile; ?>
					
					<?php
					// Pagination
					global $wp_query;
					$paged = max( 1, get_query_var( 'paged' ) );
					$total_pages = $wp_query->max_num_pages;
					
					if ( $total_pages > 1 ) :
						?>
						<div class="flex gap-2 justify-center items-center pt-8">
							<?php if ( $paged > 1 ) : ?>
								<a href="<?php echo esc_url( get_pagenum_link( $paged - 1 ) ); ?>" class="flex justify-center items-center rounded-lg border border-gray-200 transition-colors size-10 dark:border-gray-800 hover:bg-white dark:hover:bg-gray-900">
									<span class="text-sm material-symbols-outlined">chevron_left</span>
								</a>
							<?php else : ?>
								<button class="flex justify-center items-center rounded-lg border border-gray-200 transition-colors size-10 dark:border-gray-800 hover:bg-white dark:hover:bg-gray-900" disabled>
									<span class="text-sm material-symbols-outlined">chevron_left</span>
								</button>
							<?php endif; ?>
							
							<?php
							for ( $i = 1; $i <= $total_pages; $i++ ) :
								if ( $i == 1 || $i == $total_pages || ( $i >= $paged - 1 && $i <= $paged + 1 ) ) :
									$is_current = ( $i == $paged );
									?>
									<a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" class="size-10 flex items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 hover:bg-white dark:hover:bg-gray-900 transition-colors <?php echo $is_current ? 'bg-[#2d4a34] text-white dark:bg-primary dark:text-background-dark font-bold border-primary' : ''; ?>">
										<?php echo esc_html( $i ); ?>
									</a>
								<?php elseif ( $i == $paged - 2 || $i == $paged + 2 ) : ?>
									<span class="flex justify-center items-center text-gray-400 size-10">...</span>
								<?php endif; ?>
							<?php endfor; ?>
							
							<?php if ( $paged < $total_pages ) : ?>
								<a href="<?php echo esc_url( get_pagenum_link( $paged + 1 ) ); ?>" class="flex justify-center items-center rounded-lg border border-gray-200 transition-colors size-10 dark:border-gray-800 hover:bg-white dark:hover:bg-gray-900">
									<span class="text-sm material-symbols-outlined">chevron_right</span>
								</a>
							<?php else : ?>
								<button class="flex justify-center items-center rounded-lg border border-gray-200 transition-colors size-10 dark:border-gray-800 hover:bg-white dark:hover:bg-gray-900" disabled>
									<span class="text-sm material-symbols-outlined">chevron_right</span>
								</button>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</section>
			<?php else : ?>
				<div class="p-6 bg-white rounded-2xl border border-gray-100 dark:bg-gray-900/50 dark:border-gray-800">
					<p class="text-gray-600 dark:text-gray-400"><?php _e( 'No articles found.', 'tsm-theme' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		
		<aside class="space-y-10 w-full lg:w-80">
			<!-- Search -->
			<?php tsm_get_component( 'search-form' ); ?>

			<?php tsm_get_component( 'archives-sidebar' ); ?>
			<?php tsm_get_component( 'newsletter-form' ); ?>
		</aside>
	</div>
</main>

<style>
	.no-scrollbar::-webkit-scrollbar {
		display: none;
	}
	.no-scrollbar {
		-ms-overflow-style: none;
		scrollbar-width: none;
	}
	.line-clamp-2 {
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}
</style>

<?php
get_footer();
