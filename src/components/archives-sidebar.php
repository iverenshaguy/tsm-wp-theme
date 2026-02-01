<?php
/**
 * Component: Archives sidebar section
 *
 * @package TSM_Theme
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type int    $latest_post_year Year to exclude from archives (only show older years). Default null (auto-detect).
 *     @type string $heading_color    Heading color class. Default 'text-accent dark:text-primary/80'.
 * }
 */

$args = wp_parse_args( isset( $args ) ? $args : array(), array(
	'latest_post_year' => null,
	'heading_color'     => 'text-accent dark:text-primary/80',
) );

// Auto-detect latest post year if not provided
if ( is_null( $args['latest_post_year'] ) ) {
	$latest_post_query = new WP_Query( array(
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
	
	$show_archives = false;
	$latest_post_year = null;
	if ( $latest_post_query->have_posts() ) {
		$latest_post_query->the_post();
		$latest_post_year = (int) get_the_date( 'Y' );
		wp_reset_postdata();
		
		// Check if there are posts from years before the latest post's year
		$older_posts_query = new WP_Query( array(
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'date_query'     => array(
				array(
					'before' => array(
						'year'  => $latest_post_year,
						'month' => 1,
						'day'   => 1,
					),
					'inclusive' => false,
				),
			),
		) );
		
		if ( $older_posts_query->have_posts() ) {
			$show_archives = true;
		}
		wp_reset_postdata();
	}
} else {
	$latest_post_year = $args['latest_post_year'];
	$show_archives = true; // Assume true if year is provided
}

// Only show archives if there are articles older than the latest post's year
if ( $show_archives && $latest_post_year ) :
	?>
	<!-- Archives -->
	<div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm dark:bg-gray-900 dark:border-gray-800">
		<h3 class="mb-6 text-sm font-bold tracking-widest uppercase <?php echo esc_attr( $args['heading_color'] ); ?>">Archives</h3>
		<div class="space-y-4">
			<?php
			$archives_by_year = array();
			
			// Get all published posts to build archive list
			$all_posts = get_posts( array(
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			) );
			
			// Group posts by year and month
			foreach ( $all_posts as $post ) {
				$post_year = get_the_date( 'Y', $post->ID );
				$post_month = get_the_date( 'F', $post->ID ); // Full month name
				$post_month_num = get_the_date( 'm', $post->ID );
				
				// Only include years older than latest post's year
				if ( $latest_post_year && (int) $post_year >= (int) $latest_post_year ) {
					continue;
				}
				
				if ( ! isset( $archives_by_year[ $post_year ] ) ) {
					$archives_by_year[ $post_year ] = array();
				}
				
				// Create archive URL for this month/year
				$archive_url = get_month_link( $post_year, $post_month_num );
				
				// Check if this month/year combo already exists
				$month_exists = false;
				foreach ( $archives_by_year[ $post_year ] as $existing_month ) {
					if ( $existing_month['month'] === $post_month && $existing_month['url'] === $archive_url ) {
						$month_exists = true;
						break;
					}
				}
				
				if ( ! $month_exists ) {
					$archives_by_year[ $post_year ][] = array(
						'month' => $post_month,
						'url'   => $archive_url,
					);
				}
			}
			
			// Sort years in descending order
			krsort( $archives_by_year );
			
			$has_archived_years = ! empty( $archives_by_year );
			
			foreach ( $archives_by_year as $year => $months ) :
				?>
				<div class="archive-year-item">
					<button type="button" class="flex justify-between items-center pb-2 w-full font-bold text-left text-gray-400 border-b border-transparent transition-colors archive-year-toggle hover:text-accent dark:hover:text-primary" data-year="<?php echo esc_attr( $year ); ?>">
						<span><?php echo esc_html( $year ); ?></span>
						<span class="text-sm transition-transform duration-200 material-symbols-outlined archive-year-icon">chevron_right</span>
					</button>
					<ul class="hidden overflow-hidden pl-2 mt-2 space-y-2 transition-all duration-300 ease-in-out archive-months">
						<?php foreach ( $months as $month_data ) : ?>
							<li>
								<a class="text-sm font-medium text-primary dark:text-primary hover:opacity-80" href="<?php echo esc_url( $month_data['url'] ); ?>">
									<?php echo esc_html( $month_data['month'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
			
			<?php if ( ! $has_archived_years ) : ?>
				<p class="text-sm text-gray-500 dark:text-gray-400">No archived articles found.</p>
			<?php endif; ?>
		</div>
	</div>
	<?php
endif;
?>

<script>
(function() {
	// Archive year toggle functionality
	const archiveYearToggles = document.querySelectorAll('.archive-year-toggle');
	
	archiveYearToggles.forEach(function(toggle) {
		toggle.addEventListener('click', function() {
			const yearItem = this.closest('.archive-year-item');
			const monthsList = yearItem.querySelector('.archive-months');
			const icon = this.querySelector('.archive-year-icon');
			
			// Collapse all other years first
			const allYearItems = document.querySelectorAll('.archive-year-item');
			allYearItems.forEach(function(otherYearItem) {
				if (otherYearItem !== yearItem) {
					const otherMonthsList = otherYearItem.querySelector('.archive-months');
					const otherToggle = otherYearItem.querySelector('.archive-year-toggle');
					const otherIcon = otherToggle ? otherToggle.querySelector('.archive-year-icon') : null;
					
					if (otherMonthsList && !otherMonthsList.classList.contains('hidden')) {
						// Animate collapse
						otherMonthsList.style.maxHeight = otherMonthsList.scrollHeight + 'px';
						setTimeout(function() {
							otherMonthsList.style.maxHeight = '0px';
						}, 10);
						
						setTimeout(function() {
							otherMonthsList.classList.add('hidden');
							otherMonthsList.style.maxHeight = '';
						}, 300);
						
						if (otherIcon) {
							otherIcon.style.transform = 'rotate(0deg)';
							setTimeout(function() {
								otherIcon.textContent = 'chevron_right';
							}, 150);
						}
						if (otherToggle) {
							otherToggle.classList.add('text-gray-400');
							otherToggle.classList.remove('text-accent', 'dark:text-primary', 'border-b', 'border-gray-100', 'dark:border-gray-800', 'mb-2');
						}
					}
				}
			});
			
			// Toggle current year's months visibility
			if (monthsList.classList.contains('hidden')) {
				// Expand
				monthsList.classList.remove('hidden');
				monthsList.style.maxHeight = '0px';
				setTimeout(function() {
					monthsList.style.maxHeight = monthsList.scrollHeight + 'px';
				}, 10);
				
				setTimeout(function() {
					monthsList.style.maxHeight = '';
				}, 300);
				
				if (icon) {
					setTimeout(function() {
						icon.textContent = 'expand_more';
						icon.style.transform = 'rotate(0deg)';
					}, 150);
				}
				this.classList.remove('text-gray-400');
				this.classList.add('text-accent', 'dark:text-primary', 'border-b', 'border-gray-100', 'dark:border-gray-800', 'mb-2');
			} else {
				// Collapse
				monthsList.style.maxHeight = monthsList.scrollHeight + 'px';
				setTimeout(function() {
					monthsList.style.maxHeight = '0px';
				}, 10);
				
				setTimeout(function() {
					monthsList.classList.add('hidden');
					monthsList.style.maxHeight = '';
				}, 300);
				
				if (icon) {
					icon.style.transform = 'rotate(0deg)';
					setTimeout(function() {
						icon.textContent = 'chevron_right';
					}, 150);
				}
				this.classList.add('text-gray-400');
				this.classList.remove('text-accent', 'dark:text-primary', 'border-b', 'border-gray-100', 'dark:border-gray-800', 'mb-2');
			}
		});
	});
})();
</script>
