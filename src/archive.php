<?php
/**
 * The template for displaying article archives
 *
 * @package TSM_Theme
 */

get_header();

// Get only categories that have posts (existing categories)
$all_categories = get_categories( array(
	'orderby' => 'name',
	'order'   => 'ASC',
	'hide_empty' => true, // Only show categories with posts
) );

// Find "Articles" category for "All" button
$articles_category = null;
$categories = array();
foreach ( $all_categories as $cat ) {
	// Check if this is the "Articles" category (case-insensitive)
	if ( strtolower( $cat->slug ) === 'articles' || strtolower( $cat->name ) === 'articles' ) {
		$articles_category = $cat;
	} else {
		// Add other categories to filter pills
		$categories[] = $cat;
	}
}

// Get current category if filtering
$current_category = null;
$current_category_slug = 'all';
$is_all_selected = true; // Default to "All" being selected
if ( is_category() ) {
	$current_category = get_queried_object();
	$current_category_slug = $current_category->slug;
	// Check if we're on the Articles category page
	if ( $articles_category && $current_category->term_id === $articles_category->term_id ) {
		$is_all_selected = true; // "All" is selected when on Articles category
	} else {
		$is_all_selected = false; // A specific category is selected
	}
}

// Get "All" button URL - use Articles category if found, otherwise fallback
if ( $articles_category ) {
	$posts_page_url = get_category_link( $articles_category->term_id );
} else {
	// Fallback: try posts page or home
	$posts_page_id = get_option( 'page_for_posts' );
	if ( $posts_page_id ) {
		$posts_page_url = get_permalink( $posts_page_id );
	} else {
		$posts_page_url = home_url( '/' );
	}
}

// Featured category - use selected category unless viewing "All"
$featured_category = null;
// Check if we're viewing "All" (Articles category page) or a specific category
if ( is_category() && $current_category && $is_all_selected ) {
	// When viewing "All" (Articles category), prioritize "Word for the Month" category
	// First, search for exact match "Word for the Month" (case-insensitive)
	$word_for_month_category = null;
	foreach ( $all_categories as $cat ) {
		$cat_name_lower = strtolower( trim( $cat->name ) );
		if ( $cat_name_lower === 'word for the month' || stripos( $cat_name_lower, 'word for the month' ) !== false ) {
			$word_for_month_category = $cat;
			break;
		}
	}
	
	// Use "Word for the Month" if found
	if ( $word_for_month_category ) {
		$featured_category = $word_for_month_category;
	} else {
		// Fallback to Articles category or first available category
		if ( $articles_category ) {
			$featured_category = $articles_category;
		} elseif ( ! empty( $categories ) ) {
			$featured_category = $categories[0];
		}
	}
} elseif ( is_category() && $current_category ) {
	// Use the selected category for featured article (when viewing a specific category, not "All")
	$featured_category = $current_category;
} else {
	// When not on a category page, prioritize "Word for the Month" category
	$word_for_month_category = null;
	foreach ( $all_categories as $cat ) {
		$cat_name_lower = strtolower( trim( $cat->name ) );
		if ( $cat_name_lower === 'word for the month' || stripos( $cat_name_lower, 'word for the month' ) !== false ) {
			$word_for_month_category = $cat;
			break;
		}
	}
	
	// Use "Word for the Month" if found
	if ( $word_for_month_category ) {
		$featured_category = $word_for_month_category;
	} else {
		// Fallback to Articles category or first available category
		if ( $articles_category ) {
			$featured_category = $articles_category;
		} elseif ( ! empty( $categories ) ) {
			$featured_category = $categories[0];
		}
	}
}

// Check if there's a search query on category archive
$has_search_query = isset( $_GET['s'] ) && ! empty( $_GET['s'] );

// Query for featured article
$featured_args = array(
	'posts_per_page' => 1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC', // Get the latest post
);
if ( $featured_category ) {
	$featured_args['cat'] = $featured_category->term_id;
}
// Add search query if present
if ( $has_search_query ) {
	$featured_args['s'] = sanitize_text_field( $_GET['s'] );
}
$featured_query = new WP_Query( $featured_args );

// Query for all articles (excluding featured)
$articles_args = array(
	'posts_per_page' => 12,
	'post_status'    => 'publish',
	'post__not_in'   => $featured_query->have_posts() ? array( $featured_query->posts[0]->ID ) : array(),
);
if ( is_category() ) {
	$articles_args['cat'] = $current_category->term_id;
} elseif ( $articles_category ) {
	// If not filtering by category and "All" is selected, show Articles category
	$articles_args['cat'] = $articles_category->term_id;
}
// Add search query if present
if ( $has_search_query ) {
	$articles_args['s'] = sanitize_text_field( $_GET['s'] );
}
$articles_query = new WP_Query( $articles_args );

// Get archive months for sidebar
$archives = wp_get_archives( array(
	'type'            => 'monthly',
	'limit'           => 12,
	'format'          => 'custom',
	'before'          => '',
	'after'           => '',
	'show_post_count' => false,
	'echo'            => false,
) );
?>

<main class="max-w-[1200px] mx-auto px-6 py-12">
	<!-- Category Filter Pills -->
	<div class="mb-12">
		<div class="flex overflow-x-auto gap-2 no-scrollbar">
			<a href="<?php echo esc_url( $posts_page_url ); ?>" 
			   class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo $is_all_selected ? 'bg-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
				<p class="<?php echo $is_all_selected ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>">All</p>
			</a>
			<?php if ( ! empty( $categories ) ) : ?>
				<?php foreach ( $categories as $cat ) : ?>
					<?php $is_category_selected = is_category() && $current_category && $current_category->term_id === $cat->term_id; ?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" 
					   class="category-filter-link flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg px-4 cursor-pointer transition-colors <?php echo $is_category_selected ? 'bg-primary border-primary' : 'bg-white dark:bg-[#162b1b] hover:bg-emerald-50 dark:hover:bg-emerald-900/30 border border-emerald-50 dark:border-emerald-900/30'; ?>">
						<p class="<?php echo $is_category_selected ? 'text-white text-sm font-semibold' : 'text-gray-700 dark:text-gray-300 text-sm font-medium'; ?>"><?php echo esc_html( $cat->name ); ?></p>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $has_search_query ) : ?>
		<div class="mb-12 border-b border-[#dbe6de] dark:border-gray-800 pb-8">
			<p class="text-sm font-bold uppercase tracking-widest text-[rgb(51,154,70)] dark:text-primary/80 mb-2">Articles</p>
			<h2 class="text-4xl lg:text-5xl font-bold tracking-tight text-[#2d4a34] dark:text-white">Search Results</h2>
			<p class="mt-2 text-lg text-gray-600 dark:text-gray-400"><?php echo esc_html( sanitize_text_field( $_GET['s'] ) ); ?></p>
		</div>
	<?php endif; ?>

	<div class="flex flex-col gap-12 lg:flex-row">
		<!-- Main Content -->
		<div class="flex-1 space-y-16">
			<?php if ( $featured_query->have_posts() && $featured_category ) : ?>
				<?php $featured_query->the_post(); ?>
				<section>
					<?php if ( $is_all_selected ) : ?>
						<div class="flex gap-2 items-center mb-6">
							<span class="w-8 h-px bg-primary"></span>
							<h2 class="text-sm font-bold tracking-widest uppercase text-accent dark:text-primary/80">
								<?php echo esc_html( $featured_category->name ); ?>
							</h2>
						</div>
					<?php endif; ?>
					<div class="overflow-hidden relative bg-white rounded-xl border border-gray-100 shadow-sm group dark:bg-gray-900 dark:border-gray-800">
						<div class="flex flex-col lg:flex-row">
							<?php
							// Get featured image first, fallback to first image in post content, then placeholder
							$thumbnail_id = get_post_thumbnail_id();
							$thumbnail_url = '';
							
							if ( $thumbnail_id ) {
								$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'large' );
							} else {
								// Fallback: extract first image from post content
								$content = get_the_content();
								preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches );
								if ( ! empty( $matches[1] ) ) {
									$thumbnail_url = $matches[1];
								}
							}
							
							// Final fallback: use category-specific placeholder image
							if ( empty( $thumbnail_url ) ) {
								$post_categories = get_the_category();
								$category_name = ! empty( $post_categories ) ? strtolower( $post_categories[0]->name ) : '';
								$placeholder_file = 'article-placeholder.png';
								
								// Determine placeholder based on category
								if ( stripos( $category_name, 'word for the month' ) !== false ) {
									$placeholder_file = 'word-for-the-month-placeholder.png';
								} elseif ( stripos( $category_name, 'favour for the week' ) !== false ) {
									$placeholder_file = 'favour-for-the-week-placeholder.png';
								}
								
								$placeholder_path = get_template_directory() . '/assets/images/' . $placeholder_file;
								$thumbnail_url = get_template_directory_uri() . '/assets/images/' . $placeholder_file;
								
								// If specific placeholder doesn't exist, fallback to article placeholder, then book placeholder
								if ( ! file_exists( $placeholder_path ) ) {
									$article_placeholder = get_template_directory() . '/assets/images/article-placeholder.png';
									if ( file_exists( $article_placeholder ) ) {
										$thumbnail_url = get_template_directory_uri() . '/assets/images/article-placeholder.png';
									} else {
										$thumbnail_url = get_template_directory_uri() . '/assets/images/book-placeholder.png';
									}
								}
							}
							?>
							<div class="w-full lg:w-1/2 aspect-[4/3] lg:aspect-auto relative overflow-hidden">
								<div class="absolute inset-0 bg-center bg-cover transition-transform duration-700 group-hover:scale-105" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
								<div class="absolute inset-0 bg-gradient-to-t to-transparent from-black/40 lg:hidden"></div>
							</div>
							<div class="flex flex-col justify-center p-8 w-full lg:w-1/2 lg:p-12">
								<?php 
								$post_categories = get_the_category();
								$category_name = ! empty( $post_categories ) ? $post_categories[0]->name : '';
								?>
								<?php if ( $category_name ) : ?>
									<span class="mb-2 text-xs font-bold tracking-wider uppercase text-primary dark:text-primary">
										<?php echo esc_html( $category_name ); ?>
									</span>
								<?php endif; ?>
								<p class="mb-2 text-sm text-gray-400">
									<?php echo esc_html( get_the_date( 'F Y' ) ); ?> | Featured Message
								</p>
								<h3 class="mb-6 text-3xl font-bold leading-tight lg:text-4xl text-accent dark:text-white">
									<?php the_title(); ?>
								</h3>
								<p class="mb-8 text-lg leading-relaxed text-gray-600 dark:text-gray-400">
									<?php 
									$excerpt = get_the_excerpt();
									if ( empty( $excerpt ) ) {
										$excerpt = wp_trim_words( get_the_content(), 30 );
									}
									echo esc_html( $excerpt );
									?>
								</p>
								<div>
									<a href="<?php the_permalink(); ?>" class="inline-flex gap-2 justify-center items-center px-6 py-3 text-base font-bold text-white rounded-lg shadow-lg transition-all bg-primary hover:text-white shadow-primary/20 hover:shadow-primary/40">
										Read Full Message
										<span class="material-symbols-outlined !text-base">arrow_forward</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</section>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

			<!-- All Articles Grid -->
			<section>
				<?php if ( $is_all_selected ) : ?>
					<div class="flex justify-between items-center mb-8">
						<div class="flex gap-2 items-center">
							<span class="w-8 h-px bg-primary"></span>
							<h2 class="text-sm font-bold tracking-widest uppercase text-accent dark:text-primary/80">
								<?php echo esc_html( $articles_category ? $articles_category->name : 'Explore All Resources' ); ?>
							</h2>
						</div>
						<div class="hidden gap-4 items-center sm:flex">
							<button class="text-xs font-bold tracking-tighter text-gray-400 uppercase transition-colors hover:text-accent dark:hover:text-primary">
								Newest
							</button>
							<button class="text-xs font-bold tracking-tighter text-gray-400 uppercase transition-colors hover:text-accent dark:hover:text-primary">
								Popular
							</button>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( $articles_query->have_posts() ) : ?>
					<div class="grid grid-cols-1 gap-8 md:grid-cols-2">
						<?php while ( $articles_query->have_posts() ) : $articles_query->the_post(); ?>
							<?php
							$post_categories = get_the_category();
							$category_name = ! empty( $post_categories ) ? $post_categories[0]->name : '';
							
							// Get featured image first, fallback to first image in post content, then placeholder
							$thumbnail_id = get_post_thumbnail_id();
							$thumbnail_url = '';
							
							if ( $thumbnail_id ) {
								$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'medium' );
							} else {
								// Fallback: extract first image from post content
								$content = get_the_content();
								preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches );
								if ( ! empty( $matches[1] ) ) {
									$thumbnail_url = $matches[1];
								}
							}
							
							// Final fallback: use category-specific placeholder image
							if ( empty( $thumbnail_url ) ) {
								$category_name_lower = strtolower( $category_name );
								$placeholder_file = 'article-placeholder.png';
								
								// Determine placeholder based on category
								if ( stripos( $category_name_lower, 'word for the month' ) !== false ) {
									$placeholder_file = 'word-for-the-month-placeholder.png';
								} elseif ( stripos( $category_name_lower, 'favour for the week' ) !== false ) {
									$placeholder_file = 'favour-for-the-week-placeholder.png';
								}
								
								$placeholder_path = get_template_directory() . '/assets/images/' . $placeholder_file;
								$thumbnail_url = get_template_directory_uri() . '/assets/images/' . $placeholder_file;
								
								// If specific placeholder doesn't exist, fallback to article placeholder, then book placeholder
								if ( ! file_exists( $placeholder_path ) ) {
									$article_placeholder = get_template_directory() . '/assets/images/article-placeholder.png';
									if ( file_exists( $article_placeholder ) ) {
										$thumbnail_url = get_template_directory_uri() . '/assets/images/article-placeholder.png';
									} else {
										$thumbnail_url = get_template_directory_uri() . '/assets/images/book-placeholder.png';
									}
								}
							}
							?>
							<article>
								<a href="<?php the_permalink(); ?>" class="flex overflow-hidden flex-col bg-white rounded-xl border border-gray-100 shadow-sm transition-all duration-300 article-card group dark:bg-gray-900 dark:border-gray-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" aria-label="<?php echo esc_attr( sprintf( __( 'Read article: %s', 'tsm-theme' ), get_the_title() ) ); ?>">
									<div class="overflow-hidden relative aspect-video" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>'); background-size: cover; background-position: center;"></div>
									<div class="flex flex-col flex-1 p-6">
										<div class="flex flex-col mb-3">
											<?php if ( $category_name ) : ?>
												<span class="text-primary dark:text-primary/90 text-[10px] font-bold uppercase tracking-wider mb-1">
													<?php echo esc_html( $category_name ); ?>
												</span>
											<?php endif; ?>
											<div class="flex justify-between items-center">
												<?php
												// Get tag if available, otherwise show empty
												$tag_name = '';
												if ( has_tag() ) {
													$tags = get_the_tags();
													$tag_name = ! empty( $tags ) ? $tags[0]->name : '';
												}
												?>
												<?php if ( $tag_name ) : ?>
													<span class="text-xs font-bold text-gray-500 uppercase"><?php echo esc_html( $tag_name ); ?></span>
												<?php else : ?>
													<span></span>
												<?php endif; ?>
												<span class="text-xs text-gray-400"><?php echo esc_html( get_the_date( 'M d, Y' ) ); ?></span>
											</div>
										</div>
										<h4 class="mb-3 text-xl font-bold transition-colors text-accent dark:text-white group-hover:text-primary">
											<?php the_title(); ?>
										</h4>
										<p class="flex-1 mb-6 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
											<?php 
											$excerpt = get_the_excerpt();
											if ( empty( $excerpt ) ) {
												$excerpt = wp_trim_words( get_the_content(), 20 );
											}
											echo esc_html( $excerpt );
											?>
										</p>
										<span class="inline-flex gap-2 items-center text-sm font-bold transition-all text-primary group-hover:gap-3">
											Read More
											<span class="material-symbols-outlined !text-base">arrow_forward</span>
										</span>
									</div>
								</a>
							</article>
						<?php endwhile; ?>
					</div>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="text-gray-600 dark:text-gray-400">No articles found.</p>
				<?php endif; ?>
			</section>
		</div>

		<!-- Sidebar -->
		<aside class="space-y-10 w-full lg:w-80">
			<!-- Search -->
			<?php tsm_get_component( 'search-form' ); ?>

			<?php tsm_get_component( 'archives-sidebar' ); ?>

			<?php tsm_get_component( 'newsletter-form' ); ?>
		</aside>
	</div>
</main>

<?php
get_footer();