<?php
/**
 * The template for displaying date archives (monthly/yearly)
 * This shows articles when clicking on archive month/year links
 *
 * @package TSM_Theme
 */

get_header();

// Get current archive date info
$current_year = get_query_var( 'year' );
$current_month = get_query_var( 'monthnum' );
$is_monthly = ! empty( $current_month );

// Get all published posts to build archive list for current year only
$all_posts = get_posts( array(
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

// Get latest post year
$latest_post_year = null;
if ( ! empty( $all_posts ) ) {
	$latest_post = $all_posts[0];
	$latest_post_year = (int) get_the_date( 'Y', $latest_post->ID );
}

// Build archives by year for sidebar (only for years older than latest)
$archives_by_year = array();
foreach ( $all_posts as $post ) {
	$post_year = get_the_date( 'Y', $post->ID );
	$post_month = get_the_date( 'F', $post->ID );
	$post_month_num = get_the_date( 'm', $post->ID );
	
	// Only include years older than latest post's year
	if ( $latest_post_year && (int) $post_year >= (int) $latest_post_year ) {
		continue;
	}
	
	if ( ! isset( $archives_by_year[ $post_year ] ) ) {
		$archives_by_year[ $post_year ] = array();
	}
	
	$archive_url = get_month_link( $post_year, $post_month_num );
	
	// Check if this month/year combo already exists
	$month_exists = false;
	foreach ( $archives_by_year[ $post_year ] as $existing_month ) {
		if ( $existing_month['month'] === $post_month ) {
			$month_exists = true;
			break;
		}
	}
	
	if ( ! $month_exists ) {
		$archives_by_year[ $post_year ][] = array(
			'url'        => $archive_url,
			'month'      => $post_month,
			'month_num'  => $post_month_num,
		);
	}
}

// Sort years descending
krsort( $archives_by_year );

// Sort months within each year (newest first)
foreach ( $archives_by_year as $year => &$months ) {
	$months = array_reverse( $months );
}
unset( $months );

// Build months for current year only
$current_year_months = array();
foreach ( $all_posts as $post ) {
	$post_year = get_the_date( 'Y', $post->ID );
	
	// Only include posts from current year
	if ( (int) $post_year !== (int) $current_year ) {
		continue;
	}
	
	$post_month = get_the_date( 'F', $post->ID );
	$post_month_num = get_the_date( 'm', $post->ID );
	
	// Count posts for this month
	$month_posts_count = 0;
	foreach ( $all_posts as $count_post ) {
		$count_post_year = get_the_date( 'Y', $count_post->ID );
		$count_post_month = get_the_date( 'F', $count_post->ID );
		if ( (int) $count_post_year === (int) $current_year && $count_post_month === $post_month ) {
			$month_posts_count++;
		}
	}
	
	// Check if this month already exists
	$month_exists = false;
	foreach ( $current_year_months as $existing_month ) {
		if ( $existing_month['month'] === $post_month ) {
			$month_exists = true;
			break;
		}
	}
	
	if ( ! $month_exists ) {
		$current_year_months[] = array(
			'month'      => $post_month,
			'month_num'  => $post_month_num,
			'year'       => $current_year,
			'count'      => $month_posts_count,
		);
	}
}

// Sort months descending (newest first)
usort( $current_year_months, function( $a, $b ) {
	return (int) $b['month_num'] - (int) $a['month_num'];
} );

// Get all categories for sidebar filter
$all_categories = get_categories( array(
	'orderby' => 'name',
	'order'   => 'ASC',
	'hide_empty' => true,
) );

// Organize categories by parent-child relationships
$articles_category = null;
$category_tree = array();
foreach ( $all_categories as $cat ) {
	if ( strtolower( $cat->slug ) === 'articles' || strtolower( $cat->name ) === 'articles' ) {
		$articles_category = $cat;
	} elseif ( $cat->parent == 0 ) {
		// Top-level category
		$category_tree[ $cat->term_id ] = array(
			'category' => $cat,
			'children' => array(),
		);
	}
}

// Add child categories to their parents
foreach ( $all_categories as $cat ) {
	if ( $cat->parent > 0 && isset( $category_tree[ $cat->parent ] ) ) {
		$category_tree[ $cat->parent ]['children'][] = $cat;
	} elseif ( $cat->parent > 0 && $articles_category && $cat->parent == $articles_category->term_id ) {
		// Child of Articles category
		if ( ! isset( $category_tree[ $articles_category->term_id ] ) ) {
			$category_tree[ $articles_category->term_id ] = array(
				'category' => $articles_category,
				'children' => array(),
			);
		}
		$category_tree[ $articles_category->term_id ]['children'][] = $cat;
	}
}
?>

<main class="max-w-[1200px] mx-auto px-6 py-12">
	<div class="mb-12">
		<?php if ( $is_monthly ) : ?>
			<h2 class="mb-4 font-serif text-4xl font-bold text-accent dark:text-white">Archives Library</h2>
			<p class="max-w-2xl text-gray-600 dark:text-gray-400">Explore articles from this month.</p>
		<?php else : ?>
			<h2 class="mb-4 font-serif text-4xl font-bold text-accent dark:text-white">Archives Library</h2>
			<p class="max-w-2xl text-gray-600 dark:text-gray-400">Explore our complete collection of articles, devotions, and messages organized by date and category. A scholarly resource for spiritual growth and study.</p>
		<?php endif; ?>
	</div>

	<div class="flex flex-col gap-12 lg:flex-row">
		<!-- Main Content -->
		<div class="flex-1 space-y-12">
			<?php if ( ! empty( $current_year_months ) ) : ?>
				<section class="archive-year-section" data-year="<?php echo esc_attr( $current_year ); ?>">
					<div class="flex gap-4 items-center mb-6">
						<h3 class="font-serif text-2xl font-bold text-primary"><?php echo esc_html( $current_year ); ?></h3>
						<div class="flex-1 h-px bg-gray-200 dark:bg-gray-800"></div>
					</div>
					<div class="space-y-4">
						<?php foreach ( $current_year_months as $month_data ) : ?>
							<?php
							// Get posts for this month to display in expanded view
							$month_posts_query = new WP_Query( array(
								'year'           => $month_data['year'],
								'monthnum'       => $month_data['month_num'],
								'posts_per_page' => -1,
								'post_status'    => 'publish',
								'orderby'        => 'date',
								'order'          => 'DESC',
							) );
							
							// Collect all category slugs for this month's articles (for filtering collapsed months)
							$month_category_slugs = array();
							if ( $month_posts_query->have_posts() ) {
								while ( $month_posts_query->have_posts() ) {
									$month_posts_query->the_post();
									$post_categories = get_the_category();
									if ( ! empty( $post_categories ) ) {
										foreach ( $post_categories as $cat ) {
											if ( ! in_array( $cat->slug, $month_category_slugs, true ) ) {
												$month_category_slugs[] = $cat->slug;
											}
										}
									}
								}
								wp_reset_postdata();
							}
							
							// Expand only if this is the current month being viewed (month must be in URL)
							$is_expanded = $is_monthly && (int) $month_data['month_num'] === (int) $current_month;
							?>
							<div class="overflow-hidden bg-white rounded-xl border border-gray-100 shadow-sm archive-month-item dark:bg-gray-900 dark:border-gray-800" data-month-categories="<?php echo esc_attr( implode( ',', $month_category_slugs ) ); ?>">
								<button type="button" class="flex justify-between items-center px-6 py-4 w-full transition-colors archive-month-toggle hover:bg-gray-50 dark:hover:bg-gray-800/50" data-month="<?php echo esc_attr( $month_data['month'] . '-' . $current_year ); ?>" data-year="<?php echo esc_attr( $current_year ); ?>" data-month-num="<?php echo esc_attr( $month_data['month_num'] ); ?>" data-original-count="<?php echo esc_attr( $month_data['count'] ); ?>">
									<div class="flex gap-4 items-center">
										<span class="text-lg font-bold"><?php echo esc_html( $month_data['month'] ); ?></span>
										<span class="px-2 py-0.5 text-xs font-bold rounded bg-primary/10 text-primary archive-month-count">
											<?php printf( _n( '%d Article', '%d Articles', $month_data['count'], 'tsm-theme' ), $month_data['count'] ); ?>
										</span>
									</div>
									<span class="text-gray-400 material-symbols-outlined archive-month-icon"><?php echo $is_expanded ? 'expand_less' : 'expand_more'; ?></span>
								</button>
								<div class="archive-month-content px-6 pb-6 <?php echo $is_expanded ? '' : 'hidden'; ?>">
									<?php if ( $month_posts_query->have_posts() ) : ?>
										<div class="divide-y divide-gray-100 dark:divide-gray-800">
											<?php while ( $month_posts_query->have_posts() ) : $month_posts_query->the_post(); ?>
												<?php
												$post_categories = get_the_category();
												$category_name = ! empty( $post_categories ) ? $post_categories[0]->name : '';
												?>
												<div class="flex flex-col gap-4 justify-between py-4 md:flex-row md:items-center archive-article-item" data-categories="<?php echo esc_attr( ! empty( $post_categories ) ? implode( ',', array_map( function( $cat ) { return $cat->slug; }, $post_categories ) ) : '' ); ?>">
													<div>
														<?php if ( $category_name ) : ?>
															<span class="inline-block text-[10px] font-bold uppercase tracking-widest text-primary mb-1"><?php echo esc_html( $category_name ); ?></span>
														<?php endif; ?>
														<h4 class="text-lg font-bold transition-colors cursor-pointer hover:text-primary">
															<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
														</h4>
														<p class="text-xs text-gray-400">Published: <?php echo esc_html( get_the_date( 'F d, Y' ) ); ?></p>
													</div>
													<a href="<?php the_permalink(); ?>" class="text-xs font-bold whitespace-nowrap text-primary hover:underline">View Article</a>
												</div>
											<?php endwhile; ?>
										</div>
									<?php else : ?>
										<p class="py-4 text-sm text-gray-500 dark:text-gray-400">No articles found.</p>
									<?php endif; ?>
									<?php wp_reset_postdata(); ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php else : ?>
				<p class="text-gray-600 dark:text-gray-400">No articles found for this year.</p>
			<?php endif; ?>
		</div>

		<!-- Sidebar -->
		<aside class="space-y-8 w-full lg:w-80">
			<!-- Search -->
			<?php
			tsm_get_component( 'search-form', array(
				'placeholder'   => 'Keywords, verses...',
				'input_id'      => 'archive-search',
				'heading_color' => 'text-primary',
			) );
			?>

			<!-- Filter Categories -->
			<div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm dark:bg-gray-900 dark:border-gray-800">
				<h3 class="mb-6 text-sm font-bold tracking-widest uppercase text-primary">Filter Categories</h3>
				<div class="space-y-2">
					<label class="flex gap-3 items-center cursor-pointer group">
						<input type="checkbox" class="rounded border-gray-300 archive-category-filter text-primary focus:ring-primary bg-background-light dark:bg-background-dark" value="all" checked/>
						<span class="text-sm font-medium text-gray-700 transition-colors dark:text-gray-300 group-hover:text-primary">All Categories</span>
					</label>
					<?php if ( $articles_category ) : ?>
						<label class="flex gap-3 items-center cursor-pointer group archive-category-parent" data-parent-id="<?php echo esc_attr( $articles_category->term_id ); ?>">
							<input type="checkbox" class="rounded border-gray-300 archive-category-filter archive-category-parent-checkbox text-primary focus:ring-primary bg-background-light dark:bg-background-dark" value="<?php echo esc_attr( $articles_category->slug ); ?>" data-parent-id="<?php echo esc_attr( $articles_category->term_id ); ?>"/>
							<span class="text-sm font-medium text-gray-700 transition-colors dark:text-gray-300 group-hover:text-primary"><?php echo esc_html( $articles_category->name ); ?></span>
						</label>
						<?php if ( ! empty( $category_tree[ $articles_category->term_id ]['children'] ) ) : ?>
							<div class="ml-8 space-y-2 dark:border-gray-700">
								<?php foreach ( $category_tree[ $articles_category->term_id ]['children'] as $child_cat ) : ?>
									<label class="flex gap-3 items-center cursor-pointer group archive-category-child" data-parent-id="<?php echo esc_attr( $articles_category->term_id ); ?>">
										<input type="checkbox" class="rounded border-gray-300 archive-category-filter archive-category-child-checkbox text-primary focus:ring-primary bg-background-light dark:bg-background-dark" value="<?php echo esc_attr( $child_cat->slug ); ?>" data-parent-id="<?php echo esc_attr( $articles_category->term_id ); ?>"/>
										<span class="text-sm font-medium text-gray-700 transition-colors dark:text-gray-300 group-hover:text-primary"><?php echo esc_html( $child_cat->name ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<?php foreach ( $category_tree as $term_id => $tree_item ) : ?>
						<?php if ( ! $articles_category || $term_id != $articles_category->term_id ) : ?>
							<label class="flex gap-3 items-center cursor-pointer group archive-category-parent" data-parent-id="<?php echo esc_attr( $term_id ); ?>">
								<input type="checkbox" class="rounded border-gray-300 archive-category-filter archive-category-parent-checkbox text-primary focus:ring-primary bg-background-light dark:bg-background-dark" value="<?php echo esc_attr( $tree_item['category']->slug ); ?>" data-parent-id="<?php echo esc_attr( $term_id ); ?>"/>
								<span class="text-sm font-medium text-gray-700 transition-colors dark:text-gray-300 group-hover:text-primary"><?php echo esc_html( $tree_item['category']->name ); ?></span>
							</label>
							<?php if ( ! empty( $tree_item['children'] ) ) : ?>
								<div class="ml-8 space-y-2 dark:border-gray-700">
									<?php foreach ( $tree_item['children'] as $child_cat ) : ?>
										<label class="flex gap-3 items-center cursor-pointer group archive-category-child" data-parent-id="<?php echo esc_attr( $term_id ); ?>">
											<input type="checkbox" class="rounded border-gray-300 archive-category-filter archive-category-child-checkbox text-primary focus:ring-primary bg-background-light dark:bg-background-dark" value="<?php echo esc_attr( $child_cat->slug ); ?>" data-parent-id="<?php echo esc_attr( $term_id ); ?>"/>
											<span class="text-sm font-medium text-gray-700 transition-colors dark:text-gray-300 group-hover:text-primary"><?php echo esc_html( $child_cat->name ); ?></span>
										</label>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>

			<?php
			tsm_get_component( 'newsletter-form', array(
				'bg_color' => 'bg-accent dark:bg-accent',
			) );
			?>
		</aside>
	</div>
</main>

<script>
(function() {
	// Scroll to expanded month on page load (only if a month is in the URL)
	function scrollToExpandedMonth() {
		// Check if URL contains a month (format: /articles/archives/YYYY/MM/)
		const url = window.location.pathname;
		const urlParts = url.split('/').filter(part => part);
		const hasMonthInUrl = urlParts.length >= 4 && urlParts[0] === 'articles' && urlParts[1] === 'archives' && urlParts.length >= 4;
		
		// Only scroll if there's a month in the URL
		if (!hasMonthInUrl) {
			return;
		}
		
		// Wait a bit longer to ensure DOM is fully ready
		setTimeout(function() {
			const expandedMonth = document.querySelector('.archive-month-content:not(.hidden)');
			if (expandedMonth) {
				const monthItem = expandedMonth.closest('.archive-month-item');
				if (monthItem) {
					// Determine offset based on screen size
					// 80px for mobile (hamburger menu), 112px for larger screens (main nav)
					const isMobile = window.innerWidth < 1024; // lg breakpoint
					const offset = isMobile ? 86 : 124;
					
					const elementPosition = monthItem.getBoundingClientRect().top;
					const offsetPosition = elementPosition + window.pageYOffset - offset;
					window.scrollTo({
						top: offsetPosition,
						behavior: 'smooth'
					});
				}
			}
		}, 200);
	}
	
	// Run on page load
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', scrollToExpandedMonth);
	} else {
		scrollToExpandedMonth();
	}
	
	// Handle browser back/forward buttons
	window.addEventListener('popstate', function(event) {
		const url = window.location.pathname;
		const urlParts = url.split('/').filter(part => part);
		
		// Find year and month from URL
		let targetYear = null;
		let targetMonth = null;
		
		// URL format: /articles/archives/YYYY/MM/
		if (urlParts.length >= 4 && urlParts[0] === 'articles' && urlParts[1] === 'archives') {
			targetYear = urlParts[2];
			if (urlParts.length >= 4) {
				targetMonth = parseInt(urlParts[3]);
			}
		}
		
		// Collapse all months first
		const allMonthContents = document.querySelectorAll('.archive-month-content');
		const allMonthIcons = document.querySelectorAll('.archive-month-icon');
		allMonthContents.forEach(function(content) {
			content.classList.add('hidden');
		});
		allMonthIcons.forEach(function(icon) {
			icon.textContent = 'expand_more';
		});
		
		// Expand the target month if specified
		if (targetYear && targetMonth) {
			const targetToggle = document.querySelector('.archive-month-toggle[data-year="' + targetYear + '"][data-month-num="' + targetMonth + '"]');
			if (targetToggle) {
				const monthItem = targetToggle.closest('.archive-month-item');
				const monthContent = monthItem.querySelector('.archive-month-content');
				const icon = targetToggle.querySelector('.archive-month-icon');
				
				if (monthContent) {
					monthContent.classList.remove('hidden');
					icon.textContent = 'expand_less';
					
					// Load articles if needed
					if (monthContent.textContent.trim() === 'Loading articles...' || monthContent.querySelector('.archive-article-item') === null) {
						fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>?action=get_month_articles&year=' + targetYear + '&monthnum=' + targetMonth)
							.then(response => response.json())
							.then(data => {
								if (data.success && data.html) {
									monthContent.innerHTML = data.html;
									if (typeof applyCategoryFilter === 'function') {
										applyCategoryFilter();
									}
								}
							})
							.catch(error => {
								monthContent.innerHTML = '<p class="py-4 text-sm text-red-500">Error loading articles.</p>';
							});
					} else {
						if (typeof applyCategoryFilter === 'function') {
							applyCategoryFilter();
						}
					}
					
					// Scroll to expanded month
					setTimeout(function() {
						const expandedMonth = document.querySelector('.archive-month-content:not(.hidden)');
						if (expandedMonth) {
							const monthItem = expandedMonth.closest('.archive-month-item');
							if (monthItem) {
								const offset = window.innerWidth < 1024 ? 80 : 112;
								const elementPosition = monthItem.getBoundingClientRect().top;
								const offsetPosition = elementPosition + window.pageYOffset - offset;
								window.scrollTo({
									top: offsetPosition,
									behavior: 'smooth'
								});
							}
						}
					}, 100);
				}
			}
		}
	});
	
	// Archive month toggle functionality
	const archiveMonthToggles = document.querySelectorAll('.archive-month-toggle');
	
	archiveMonthToggles.forEach(function(toggle) {
		toggle.addEventListener('click', function() {
			const monthItem = this.closest('.archive-month-item');
			const monthContent = monthItem.querySelector('.archive-month-content');
			const icon = this.querySelector('.archive-month-icon');
			
			// Collapse other months in the same year section
			const yearSection = monthItem.closest('.archive-year-section');
			const otherMonths = yearSection.querySelectorAll('.archive-month-item');
			otherMonths.forEach(function(otherMonth) {
				if (otherMonth !== monthItem) {
					const otherContent = otherMonth.querySelector('.archive-month-content');
					const otherIcon = otherMonth.querySelector('.archive-month-icon');
					if (otherContent && !otherContent.classList.contains('hidden')) {
						otherContent.classList.add('hidden');
						otherIcon.textContent = 'expand_more';
					}
				}
			});
			
			// Toggle current month
			const year = this.getAttribute('data-year');
			const monthNum = this.getAttribute('data-month-num');
			
			if (monthContent.classList.contains('hidden')) {
				monthContent.classList.remove('hidden');
				icon.textContent = 'expand_less';
				
				// Update URL to reflect selected month
				const monthPadded = String(monthNum).padStart(2, '0');
				const newUrl = '/articles/archives/' + year + '/' + monthPadded + '/';
				window.history.pushState({ year: year, month: monthNum }, '', newUrl);
				
				// Load articles for this month via AJAX if not loaded
				if (monthContent.textContent.trim() === 'Loading articles...' || monthContent.querySelector('.archive-article-item') === null) {
					// Fetch articles for this month
					fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>?action=get_month_articles&year=' + year + '&monthnum=' + monthNum)
						.then(response => response.json())
						.then(data => {
							if (data.success && data.html) {
								monthContent.innerHTML = data.html;
								// Apply current filter to newly loaded articles
								if (typeof applyCategoryFilter === 'function') {
									applyCategoryFilter();
								}
							} else {
								monthContent.innerHTML = '<p class="py-4 text-sm text-gray-500 dark:text-gray-400">No articles found.</p>';
							}
						})
						.catch(error => {
							monthContent.innerHTML = '<p class="py-4 text-sm text-red-500">Error loading articles.</p>';
						});
				} else {
					// Articles already loaded, just apply filter
					if (typeof applyCategoryFilter === 'function') {
						applyCategoryFilter();
					}
				}
			} else {
				monthContent.classList.add('hidden');
				icon.textContent = 'expand_more';
				
				// Update URL to show only year when month is collapsed
				const newUrl = '/articles/archives/' + year + '/';
				window.history.pushState({ year: year }, '', newUrl);
				
				// Apply filter to update collapsed month count
				if (typeof applyCategoryFilter === 'function') {
					applyCategoryFilter();
				}
			}
		});
	});

	// Search functionality
	const searchInput = document.getElementById('archive-search');
	if (searchInput) {
		searchInput.addEventListener('input', function() {
			const searchTerm = this.value.toLowerCase().trim();
			const monthItems = document.querySelectorAll('.archive-month-item');
			
			monthItems.forEach(function(monthItem) {
				const monthContent = monthItem.querySelector('.archive-month-content');
				if (monthContent && !monthContent.classList.contains('hidden')) {
					const articles = monthContent.querySelectorAll('.archive-article-item');
					let hasMatch = false;
					
					articles.forEach(function(article) {
						const title = article.querySelector('h4 a').textContent.toLowerCase();
						if (title.includes(searchTerm)) {
							article.style.display = '';
							hasMatch = true;
						} else {
							article.style.display = 'none';
						}
					});
					
					// Hide month if no matches
					if (searchTerm && !hasMatch) {
						monthItem.style.display = 'none';
					} else {
						monthItem.style.display = '';
					}
				}
			});
		});
	}

	// Category filter functionality with parent-child relationships
	const categoryFilters = document.querySelectorAll('.archive-category-filter');
	
	// Function to apply category filter to all articles
	function applyCategoryFilter() {
		// Get selected categories, including child categories when parent is selected
		const allCheckedFilters = document.querySelectorAll('.archive-category-filter:checked');
		const selectedCategories = [];
		const selectedParentIds = [];
		
		allCheckedFilters.forEach(function(filter) {
			if (filter.value !== 'all') {
				selectedCategories.push(filter.value);
				if (filter.classList.contains('archive-category-parent-checkbox')) {
					selectedParentIds.push(filter.getAttribute('data-parent-id'));
				}
			}
		});
		
		// Add child category slugs when parent is selected
		selectedParentIds.forEach(function(parentId) {
			const childCheckboxes = document.querySelectorAll('.archive-category-child-checkbox[data-parent-id="' + parentId + '"]');
			childCheckboxes.forEach(function(child) {
				if (!selectedCategories.includes(child.value)) {
					selectedCategories.push(child.value);
				}
			});
		});
		
		const isAllSelected = document.querySelector('.archive-category-filter[value="all"]').checked || selectedCategories.length === 0;
		
		// Filter articles by category and update counts for all months (expanded and collapsed)
		const monthItems = document.querySelectorAll('.archive-month-item');
		monthItems.forEach(function(monthItem) {
			const monthContent = monthItem.querySelector('.archive-month-content');
			const monthToggle = monthItem.querySelector('.archive-month-toggle');
			// Find count badge - it's inside the button, within a div
			let countBadge = null;
			if (monthToggle) {
				countBadge = monthToggle.querySelector('.archive-month-count');
				// If not found, try searching within the first child div
				if (!countBadge && monthToggle.firstElementChild) {
					countBadge = monthToggle.firstElementChild.querySelector('.archive-month-count');
				}
			}
			// Safely parse original count, defaulting to 0 if invalid
			const originalCountAttr = monthToggle ? monthToggle.getAttribute('data-original-count') : null;
			const originalCount = (originalCountAttr !== null && originalCountAttr !== '') ? 
				(parseInt(originalCountAttr, 10) || 0) : 0;
			
			if (monthContent && !monthContent.classList.contains('hidden')) {
				// Month is expanded - count visible articles that match the filter
				const articles = monthContent.querySelectorAll('.archive-article-item');
				let visibleCount = 0;
				
				articles.forEach(function(article) {
					const categoryData = article.getAttribute('data-categories');
					let shouldShow = false;
					
					if (isAllSelected) {
						shouldShow = true;
					} else if (!categoryData) {
						// No category data - hide it when filtering
						shouldShow = false;
					} else {
						// Split comma-separated category slugs
						const articleCategories = categoryData.toLowerCase().split(',').map(cat => cat.trim());
						
						// Check if any selected category matches any article category
						shouldShow = selectedCategories.some(selectedCat => {
							const selectedSlug = selectedCat.toLowerCase();
							return articleCategories.some(articleCat => articleCat === selectedSlug);
						});
					}
					
					// Update display
					if (shouldShow) {
						article.style.display = '';
						visibleCount++;
					} else {
						article.style.display = 'none';
					}
				});
				
				// Update count badge with filtered count - ensure we find it correctly
				// Ensure visibleCount is a valid number
				const safeVisibleCount = (typeof visibleCount === 'number' && !isNaN(visibleCount) && visibleCount >= 0) ? visibleCount : 0;
				const displayCount = isAllSelected ? originalCount : safeVisibleCount;
				
				// Store filtered count on the month toggle for use when collapsed
				if (monthToggle && !isAllSelected) {
					monthToggle.setAttribute('data-filtered-count', String(safeVisibleCount));
				} else if (monthToggle && isAllSelected) {
					monthToggle.removeAttribute('data-filtered-count');
				}
				
				if (countBadge) {
					countBadge.textContent = displayCount === 1 ? '1 Article' : displayCount + ' Articles';
				} else {
					// Fallback: try to find count badge again
					const fallbackBadge = monthToggle ? monthToggle.querySelector('.archive-month-count') : null;
					if (fallbackBadge) {
						fallbackBadge.textContent = displayCount === 1 ? '1 Article' : displayCount + ' Articles';
					}
				}
			} else {
				// Month is collapsed - use stored category data and filtered count if available
				const monthCategories = monthItem.getAttribute('data-month-categories');
				const storedFilteredCountAttr = monthToggle ? monthToggle.getAttribute('data-filtered-count') : null;
				const storedFilteredCount = storedFilteredCountAttr !== null ? parseInt(storedFilteredCountAttr, 10) : null;
				
				if (countBadge) {
					if (isAllSelected) {
						// Reset to original count when all categories selected
						countBadge.textContent = originalCount === 1 ? '1 Article' : originalCount + ' Articles';
						monthItem.style.display = '';
						// Clear stored filtered count
						if (monthToggle) {
							monthToggle.removeAttribute('data-filtered-count');
						}
					} else if (monthCategories) {
						// Check if month has any matching categories (including child categories)
						const monthCategoryArray = monthCategories.toLowerCase().split(',').map(cat => cat.trim());
						const hasMatchingCategory = selectedCategories.some(selectedCat => {
							const selectedSlug = selectedCat.toLowerCase();
							return monthCategoryArray.some(monthCat => monthCat === selectedSlug);
						});
						
						if (hasMatchingCategory) {
							// Month has matching categories - use stored filtered count if available and valid
							if (storedFilteredCount !== null && !isNaN(storedFilteredCount) && storedFilteredCount >= 0) {
								// Use stored count
								countBadge.textContent = storedFilteredCount === 1 ? '1 Article' : storedFilteredCount + ' Articles';
								monthItem.style.display = '';
							} else {
								// No stored count - load articles via AJAX to count them
								const year = monthToggle.getAttribute('data-year');
								const monthNum = monthToggle.getAttribute('data-month-num');
								
								// Temporarily show "..." or keep original count while loading
								countBadge.textContent = originalCount === 1 ? '1 Article' : originalCount + ' Articles';
								monthItem.style.display = '';
								
								// Load articles to count filtered ones
								if (year && monthNum) {
									fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>?action=get_month_articles&year=' + year + '&monthnum=' + monthNum)
										.then(response => response.json())
										.then(data => {
											if (data.success && data.html) {
												// Create temporary container to count articles
												const tempDiv = document.createElement('div');
												tempDiv.innerHTML = data.html;
												const tempArticles = tempDiv.querySelectorAll('.archive-article-item');
												let filteredCount = 0;
												
												tempArticles.forEach(function(article) {
													const categoryData = article.getAttribute('data-categories');
													if (categoryData) {
														const articleCategories = categoryData.toLowerCase().split(',').map(cat => cat.trim());
														const hasMatch = selectedCategories.some(selectedCat => {
															const selectedSlug = selectedCat.toLowerCase();
															return articleCategories.some(articleCat => articleCat === selectedSlug);
														});
														if (hasMatch) {
															filteredCount++;
														}
													}
												});
												
												// Update count badge and store the filtered count
												if (countBadge) {
													countBadge.textContent = filteredCount === 1 ? '1 Article' : filteredCount + ' Articles';
												}
												if (monthToggle) {
													monthToggle.setAttribute('data-filtered-count', String(filteredCount));
												}
												
												// Hide month if no articles match
												if (filteredCount === 0) {
													monthItem.style.display = 'none';
												}
											}
										})
										.catch(error => {
											// On error, show original count
											if (countBadge) {
												countBadge.textContent = originalCount === 1 ? '1 Article' : originalCount + ' Articles';
											}
										});
								}
							}
						} else {
							// Month has no matching categories - hide it
							monthItem.style.display = 'none';
						}
					} else {
						// No category data stored - use stored filtered count if available and valid, otherwise show original
						const displayCount = (storedFilteredCount !== null && !isNaN(storedFilteredCount) && storedFilteredCount >= 0) ? storedFilteredCount : originalCount;
						countBadge.textContent = displayCount === 1 ? '1 Article' : displayCount + ' Articles';
						monthItem.style.display = '';
					}
				}
			}
			
			// Also hide/show expanded months based on visible count
			if (monthContent && !monthContent.classList.contains('hidden')) {
				const articles = monthContent.querySelectorAll('.archive-article-item');
				const visibleArticles = Array.from(articles).filter(article => {
					const computedStyle = window.getComputedStyle(article);
					return computedStyle.display !== 'none';
				});
				if (!isAllSelected && visibleArticles.length === 0) {
					monthItem.style.display = 'none';
				} else {
					monthItem.style.display = '';
				}
			}
		});
	}
	
	// Apply filter on page load if filters are already selected
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			setTimeout(function() {
				if (typeof applyCategoryFilter === 'function') {
					applyCategoryFilter();
				}
			}, 100);
		});
	} else {
		setTimeout(function() {
			if (typeof applyCategoryFilter === 'function') {
				applyCategoryFilter();
			}
		}, 100);
	}
	
	// Handle parent-child category selection
	categoryFilters.forEach(function(filter) {
		filter.addEventListener('change', function() {
			const allCheckbox = document.querySelector('.archive-category-filter[value="all"]');
			const allFilters = document.querySelectorAll('.archive-category-filter:not([value="all"])');
			
			// Handle "All Categories" checkbox FIRST, before other logic
			if (this.value === 'all') {
				if (this.checked) {
					// If "All Categories" is checked, uncheck everything else (including parent and child checkboxes)
					allFilters.forEach(function(f) {
						f.checked = false;
					});
					// Also uncheck all parent and child checkboxes explicitly
					const parentCheckboxes = document.querySelectorAll('.archive-category-parent-checkbox');
					const childCheckboxes = document.querySelectorAll('.archive-category-child-checkbox');
					parentCheckboxes.forEach(function(p) {
						p.checked = false;
					});
					childCheckboxes.forEach(function(c) {
						c.checked = false;
					});
				}
			} else {
				// Handle parent-child category selection for non-"All" checkboxes
				const isParent = this.classList.contains('archive-category-parent-checkbox');
				const isChild = this.classList.contains('archive-category-child-checkbox');
				const parentId = this.getAttribute('data-parent-id');
				
				if (isParent && this.checked) {
					// When parent is checked, check all its children
					const childCheckboxes = document.querySelectorAll('.archive-category-child-checkbox[data-parent-id="' + parentId + '"]');
					childCheckboxes.forEach(function(child) {
						child.checked = true;
					});
				} else if (isParent && !this.checked) {
					// When parent is unchecked, uncheck all its children
					const childCheckboxes = document.querySelectorAll('.archive-category-child-checkbox[data-parent-id="' + parentId + '"]');
					childCheckboxes.forEach(function(child) {
						child.checked = false;
					});
				} else if (isChild) {
					// When a child is toggled, check/uncheck parent based on children state
					const parentCheckbox = document.querySelector('.archive-category-parent-checkbox[data-parent-id="' + parentId + '"]');
					const allChildren = document.querySelectorAll('.archive-category-child-checkbox[data-parent-id="' + parentId + '"]');
					const checkedChildren = document.querySelectorAll('.archive-category-child-checkbox[data-parent-id="' + parentId + '"]:checked');
					
					if (parentCheckbox) {
						// If all children are checked, check parent; otherwise uncheck parent
						parentCheckbox.checked = (checkedChildren.length === allChildren.length && allChildren.length > 0);
					}
				}
				
				// Update "All Categories" checkbox based on other filters
				const checkedFilters = document.querySelectorAll('.archive-category-filter:not([value="all"]):checked');
				if (allCheckbox) {
					allCheckbox.checked = (checkedFilters.length === 0);
				}
			}
			
			// Apply filter after checkbox changes
			applyCategoryFilter();
		});
	});
})();
</script>

<?php
get_footer();
