<?php
/**
 * TSM Theme functions and definitions
 *
 * @package TSM_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Clear WordPress cache on theme activation/update
 * This ensures old cached asset paths are cleared
 */
function tsm_theme_clear_cache() {
	// Clear all transients
	global $wpdb;
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'" );
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%'" );
	
	// Clear object cache
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
	
	// Clear rewrite rules
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'tsm_theme_clear_cache' );
add_action( 'upgrader_process_complete', 'tsm_theme_clear_cache', 10, 2 );

/**
 * Optimize WordPress debug log to prevent storage issues
 * Rotates log file when it exceeds the maximum size
 * Runs once per day via WordPress cron to minimize overhead
 */
function tsm_rotate_debug_log() {
	if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
		return;
	}

	$log_file = WP_CONTENT_DIR . '/debug.log';
	if ( ! file_exists( $log_file ) ) {
		return;
	}

	$max_size = defined( 'WP_DEBUG_LOG_MAX_SIZE' ) ? WP_DEBUG_LOG_MAX_SIZE : ( 5 * 1024 * 1024 ); // Default 5MB
	$file_size = @filesize( $log_file );
	
	// Check if file size check failed or file is within limits
	if ( false === $file_size || $file_size <= $max_size ) {
		return;
	}

	// Rotate: keep last 1MB of logs, archive the rest
	$keep_size = 1 * 1024 * 1024; // Keep last 1MB
	$archive_size = $file_size - $keep_size;

	// Read the last portion to keep
	$handle = @fopen( $log_file, 'r' );
	if ( ! $handle ) {
		return;
	}

	@fseek( $handle, $archive_size );
	$keep_content = @fread( $handle, $keep_size );
	@fclose( $handle );

	if ( false === $keep_content ) {
		return;
	}

	// Archive old logs with timestamp
	$archive_file = WP_CONTENT_DIR . '/debug-' . date( 'Y-m-d-His' ) . '.log';
	$archive_handle = @fopen( $archive_file, 'w' );
	if ( $archive_handle ) {
		$old_handle = @fopen( $log_file, 'r' );
		if ( $old_handle ) {
			@fseek( $old_handle, 0 );
			$archived = @fread( $old_handle, $archive_size );
			if ( false !== $archived ) {
				@fwrite( $archive_handle, $archived );
			}
			@fclose( $old_handle );
		}
		@fclose( $archive_handle );
	}

	// Write kept content back
	$new_handle = @fopen( $log_file, 'w' );
	if ( $new_handle ) {
		@fwrite( $new_handle, $keep_content );
		@fclose( $new_handle );
	}

	// Clean up old archive files (keep only last 3 archives to save space)
	$archive_files = @glob( WP_CONTENT_DIR . '/debug-*.log' );
	if ( is_array( $archive_files ) && count( $archive_files ) > 3 ) {
		usort( $archive_files, function( $a, $b ) {
			$time_a = @filemtime( $a );
			$time_b = @filemtime( $b );
			return ( false !== $time_a && false !== $time_b ) ? $time_a - $time_b : 0;
		});
		$files_to_delete = array_slice( $archive_files, 0, count( $archive_files ) - 3 );
		foreach ( $files_to_delete as $file ) {
			if ( $file !== $log_file ) { // Don't delete the main log file
				@unlink( $file );
			}
		}
	}
}

// Schedule log rotation to run once daily (more efficient than on every admin page load)
if ( ! wp_next_scheduled( 'tsm_rotate_debug_log_daily' ) ) {
	wp_schedule_event( time(), 'daily', 'tsm_rotate_debug_log_daily' );
}
add_action( 'tsm_rotate_debug_log_daily', 'tsm_rotate_debug_log' );

// Also run on admin init as a fallback (but only check once per hour via transient)
add_action( 'admin_init', function() {
	$last_check = get_transient( 'tsm_log_rotation_last_check' );
	if ( false === $last_check ) {
		tsm_rotate_debug_log();
		set_transient( 'tsm_log_rotation_last_check', time(), HOUR_IN_SECONDS );
	}
}, 999 );

/**
 * Load theme functionality from modular files
 */
require_once get_template_directory() . '/functions/setup.php';
require_once get_template_directory() . '/functions/cache.php'; // Database optimization and caching
require_once get_template_directory() . '/functions/enqueue.php';
require_once get_template_directory() . '/functions/navigation.php';
require_once get_template_directory() . '/functions/mobile-menu-walker.php';
require_once get_template_directory() . '/functions/widgets.php';
require_once get_template_directory() . '/functions/post-types.php';
require_once get_template_directory() . '/functions/customizer.php';
require_once get_template_directory() . '/functions/forms.php';
require_once get_template_directory() . '/functions/lightbox.php';
require_once get_template_directory() . '/functions/image-optimization.php';

/**
 * AJAX handler to load missions for infinite scroll
 */
function tsm_load_missions_ajax() {
	check_ajax_referer( 'tsm_missions_nonce', 'nonce' );
	
	$page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
	$year = isset( $_POST['year'] ) ? sanitize_text_field( $_POST['year'] ) : '';
	$exclude_ids = array();
	
	// Handle exclude_ids if provided (for future use)
	if ( isset( $_POST['exclude_ids'] ) && is_array( $_POST['exclude_ids'] ) ) {
		$exclude_ids = array_map( 'intval', $_POST['exclude_ids'] );
		$exclude_ids = array_filter( $exclude_ids ); // Remove empty values
	}
	
	$args = array(
		'post_type'      => 'mission',
		'posts_per_page' => 9,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'paged'          => $page,
	);
	
	// Exclude missions if provided
	if ( ! empty( $exclude_ids ) ) {
		$args['post__not_in'] = $exclude_ids;
	}
	
	// Filter by year if provided (using post date)
	if ( ! empty( $year ) && $year !== 'all' && $year !== 'archives' ) {
		$args['date_query'] = array(
			array(
				'year' => intval( $year ),
			),
		);
	} elseif ( $year === 'archives' ) {
		// For archives, get missions older than the oldest pill year
		// Get available years to determine the oldest pill year
		$available_years = tsm_get_mission_years();
		$current_year = date( 'Y' );
		$recent_years = array();
		
		// Get recent years (last 4 years)
		foreach ( $available_years as $available_year ) {
			if ( $available_year >= ( $current_year - 3 ) ) {
				$recent_years[] = $available_year;
			}
		}
		
		// Limit to 4 most recent years
		$recent_years = array_slice( $recent_years, 0, 4 );
		
		// Find the oldest year in the pills (or use current year - 3 if no pills)
		$oldest_pill_year = ! empty( $recent_years ) ? min( $recent_years ) : ( $current_year - 3 );
		
		// Archives = missions older than the oldest pill year (using post date)
		$args['date_query'] = array(
			array(
				'before' => $oldest_pill_year . '-01-01',
			),
		);
	}
	
	$missions_query = new WP_Query( $args );
	
	$missions = array();
	// Ensure has_more is calculated correctly - max_num_pages should be > current page
	$max_pages = (int) $missions_query->max_num_pages;
	$current_page = (int) $page;
	$has_more = $max_pages > $current_page;
	
	if ( $missions_query->have_posts() ) {
		while ( $missions_query->have_posts() ) {
			$missions_query->the_post();
			
			$mission_location = get_post_meta( get_the_ID(), 'mission_location', true );
			$mission_year = get_post_meta( get_the_ID(), 'mission_year', true );
			$mission_date = get_post_meta( get_the_ID(), 'mission_date', true );
			$mission_status = get_post_meta( get_the_ID(), 'mission_status', true );
			$mission_subtitle = get_post_meta( get_the_ID(), 'mission_subtitle', true );
			$mission_quote = get_post_meta( get_the_ID(), 'mission_quote', true );
			$mission_summary = get_post_meta( get_the_ID(), 'mission_summary', true );
			
			// Get base title
			$base_title = $mission_subtitle ? $mission_subtitle : get_the_title();
			
			// Get year: use meta if set, otherwise use post published year
			$display_year = $mission_year ? $mission_year : get_the_date( 'Y' );
			
			// Append year to title if not already present and year exists
			$display_title = $base_title;
			if ( $display_year && strpos( $base_title, $display_year ) === false ) {
				// Append location and year to title if location exists
				if ( $mission_location ) {
					$location_words = explode( ' ', trim( $mission_location ) );
					$first_word = ! empty( $location_words[0] ) ? rtrim( $location_words[0], ',' ) : '';
					if ( $first_word ) {
						$display_title = $base_title . ', ' . $first_word . ' ' . $display_year;
					} else {
						$display_title = $base_title . ' ' . $display_year;
					}
				} else {
					$display_title = $base_title . ' ' . $display_year;
				}
			}
			
			// Determine icon based on mission status
			$icon = 'public';
			if ( $mission_status === 'completed' ) {
				$icon = 'check_circle';
			} elseif ( $mission_status === 'ongoing' ) {
				$icon = 'radio_button_checked';
			}
			
			// Don't show location display as separate tag - year is in title now
			$location_display = '';
			
			// Get thumbnail (large size for timeline cards)
			// Uses featured image, hero image, or first gallery image as fallback
			$thumbnail_url = '';
			$thumbnail_alt = '';
			$thumbnail_id = get_post_thumbnail_id();
			if ( $thumbnail_id ) {
				$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'large' );
				$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
			}
			
			$missions[] = array(
				'id'              => get_the_ID(),
				'title'           => $display_title,
				'permalink'       => get_permalink(),
				'thumbnail_url'   => $thumbnail_url,
				'thumbnail_alt'   => $thumbnail_alt,
				'location'        => $location_display,
				'date'            => $mission_date,
				'status'          => $mission_status,
				'quote'           => $mission_quote,
				'summary'         => $mission_summary ? $mission_summary : ( has_excerpt() ? get_the_excerpt() : '' ),
				'content'         => get_the_content() ? wp_trim_words( get_the_content(), 30 ) : '',
				'icon'            => $icon,
			);
		}
		wp_reset_postdata();
	}
	
	// Get total count for status display
	$total_count = (int) $missions_query->found_posts;
	
	wp_send_json_success( array(
		'missions'    => $missions,
		'has_more'    => $has_more,
		'page'        => $page,
		'total_count' => $total_count,
	) );
}
add_action( 'wp_ajax_tsm_load_missions', 'tsm_load_missions_ajax' );
add_action( 'wp_ajax_nopriv_tsm_load_missions', 'tsm_load_missions_ajax' );

/**
 * AJAX handler to load galleries for pagination
 */
function tsm_load_galleries_ajax() {
	check_ajax_referer( 'tsm_galleries_nonce', 'nonce' );
	
	$page = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
	$category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : 'all';
	$posts_per_page = get_option( 'posts_per_page' );
	
	$args = array(
		'post_type'      => 'gallery',
		'posts_per_page' => $posts_per_page,
		'paged'          => $page,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	
	// Filter by category if specified
	if ( $category !== 'all' ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'gallery_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	
	$galleries_query = new WP_Query( $args );
	
	$galleries = array();
	$max_pages = (int) $galleries_query->max_num_pages;
	$current_page = (int) $page;
	$has_more = $max_pages > $current_page;
	
	if ( $galleries_query->have_posts() ) {
		while ( $galleries_query->have_posts() ) {
			$galleries_query->the_post();
			
			$gallery_images = get_post_meta( get_the_ID(), 'gallery_images', true );
			if ( ! is_array( $gallery_images ) ) {
				$gallery_images = array();
			}
			$gallery_images = array_filter( $gallery_images );
			$image_count = count( $gallery_images );
			
			// Get featured image or first gallery image
			$thumbnail_url = '';
			$thumbnail_alt = '';
			if ( has_post_thumbnail() ) {
				$thumbnail_id = get_post_thumbnail_id();
				$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'large' );
				$thumbnail_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
			} elseif ( ! empty( $gallery_images[0] ) ) {
				$thumbnail_url = wp_get_attachment_image_url( $gallery_images[0], 'large' );
				$thumbnail_alt = get_post_meta( $gallery_images[0], '_wp_attachment_image_alt', true );
			}
			
			// Get gallery category
			$gallery_cats = get_the_terms( get_the_ID(), 'gallery_category' );
			$category_name = '';
			$category_slug = '';
			if ( $gallery_cats && ! is_wp_error( $gallery_cats ) && ! empty( $gallery_cats ) ) {
				$category_name = $gallery_cats[0]->name;
				$category_slug = $gallery_cats[0]->slug;
			}
			
			// Prepare lightbox images
			$lightbox_images = array();
			if ( ! empty( $gallery_images ) ) {
				foreach ( $gallery_images as $image_id ) {
					$lightbox_images[] = array(
						'full'  => wp_get_attachment_image_url( $image_id, 'full' ),
						'thumb' => wp_get_attachment_image_url( $image_id, 'thumbnail' ),
						'alt'   => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
					);
				}
			}
			
			$galleries[] = array(
				'id'            => get_the_ID(),
				'title'         => get_the_title(),
				'date'          => get_the_date( 'F j, Y' ),
				'thumbnail_url' => $thumbnail_url,
				'thumbnail_alt' => $thumbnail_alt,
				'category_name' => $category_name,
				'category_slug' => $category_slug,
				'image_count'   => $image_count,
				'lightbox_id'   => 'gallery-lightbox-' . get_the_ID(),
				'lightbox_images' => $lightbox_images,
				'location'      => $category_name, // Use category as location
			);
		}
		wp_reset_postdata();
	}
	
	// Get total count
	$total_count = (int) $galleries_query->found_posts;
	
	wp_send_json_success( array(
		'galleries'    => $galleries,
		'has_more'     => $has_more,
		'page'         => $page,
		'total_count'  => $total_count,
	) );
}
add_action( 'wp_ajax_tsm_load_galleries', 'tsm_load_galleries_ajax' );
add_action( 'wp_ajax_nopriv_tsm_load_galleries', 'tsm_load_galleries_ajax' );

/**
 * Get available years for missions filter (from post date)
 */
function tsm_get_mission_years() {
	global $wpdb;
	
	// Use WP_Query instead of direct SQL for better compatibility
	$args = array(
		'post_type'      => 'mission',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	);
	
	$query = new WP_Query( $args );
	$years = array();
	
	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post_id ) {
			$post = get_post( $post_id );
			if ( $post && ! empty( $post->post_date ) ) {
				$year = (int) date( 'Y', strtotime( $post->post_date ) );
				if ( $year > 0 && ! in_array( $year, $years, true ) ) {
					$years[] = $year;
				}
			}
		}
		wp_reset_postdata();
	}
	
	// Sort descending
	rsort( $years );
	
	return $years;
}

/**
 * Get total count of published missions
 */
function tsm_get_total_missions_count() {
	$count = wp_count_posts( 'mission' );
	return isset( $count->publish ) ? (int) $count->publish : 0;
}

/**
 * Get count of archived missions (missions older than the oldest pill year)
 */
function tsm_get_archived_missions_count() {
	$available_years = tsm_get_mission_years();
	$current_year = date( 'Y' );
	$recent_years = array();
	
	// Get recent years (last 4 years)
	foreach ( $available_years as $year ) {
		if ( $year >= ( $current_year - 3 ) ) {
			$recent_years[] = $year;
		}
	}
	
	// Limit to 4 most recent years
	$recent_years = array_slice( $recent_years, 0, 4 );
	
	// Find the oldest year in the pills (or use current year - 3 if no pills)
	$oldest_pill_year = ! empty( $recent_years ) ? min( $recent_years ) : ( $current_year - 3 );
	
	$archived_query = new WP_Query( array(
		'post_type'      => 'mission',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'date_query'     => array(
			array(
				'before' => $oldest_pill_year . '-01-01',
			),
		),
		'fields'         => 'ids',
	) );
	
	$count = $archived_query->found_posts;
	wp_reset_postdata();
	
	return $count;
}

/**
 * Use mission hero image as featured image for missions archive page
 * Filters WordPress thumbnail functions to use mission_hero_image when no featured image is set
 * Falls back to first gallery image if no hero image is available
 */
function tsm_mission_has_post_thumbnail( $has_thumbnail, $post ) {
	if ( 'mission' === get_post_type( $post ) && ! $has_thumbnail ) {
		$mission_hero_image = get_post_meta( $post->ID, 'mission_hero_image', true );
		if ( ! empty( $mission_hero_image ) ) {
			return true;
		}
		
		// Fallback to first gallery image
		$mission_gallery_post_id = get_post_meta( $post->ID, 'mission_gallery_post', true );
		if ( $mission_gallery_post_id ) {
			$gallery_post_images = get_post_meta( $mission_gallery_post_id, 'gallery_images', true );
			if ( is_array( $gallery_post_images ) && ! empty( $gallery_post_images ) && ! empty( $gallery_post_images[0] ) ) {
				return true;
			}
		}
	}
	return $has_thumbnail;
}
add_filter( 'has_post_thumbnail', 'tsm_mission_has_post_thumbnail', 10, 2 );

function tsm_mission_get_post_thumbnail_id( $thumbnail_id, $post ) {
	if ( 'mission' === get_post_type( $post ) && empty( $thumbnail_id ) ) {
		$mission_hero_image = get_post_meta( $post->ID, 'mission_hero_image', true );
		if ( ! empty( $mission_hero_image ) ) {
			return absint( $mission_hero_image );
		}
		
		// Fallback to first gallery image
		$mission_gallery_post_id = get_post_meta( $post->ID, 'mission_gallery_post', true );
		if ( $mission_gallery_post_id ) {
			$gallery_post_images = get_post_meta( $mission_gallery_post_id, 'gallery_images', true );
			if ( is_array( $gallery_post_images ) && ! empty( $gallery_post_images ) && ! empty( $gallery_post_images[0] ) ) {
				return absint( $gallery_post_images[0] );
			}
		}
	}
	return $thumbnail_id;
}
add_filter( 'post_thumbnail_id', 'tsm_mission_get_post_thumbnail_id', 10, 2 );

/**
 * Add custom rewrite rules for date archives with articles/archives prefix
 */
function tsm_add_date_archive_rewrite_rules() {
	// Add rewrite rules for year/month archives with articles/archives prefix
	add_rewrite_rule(
		'^articles/archives/([0-9]{4})/([0-9]{1,2})/?$',
		'index.php?year=$matches[1]&monthnum=$matches[2]',
		'top'
	);
	
	// Add rewrite rule for year archives
	add_rewrite_rule(
		'^articles/archives/([0-9]{4})/?$',
		'index.php?year=$matches[1]',
		'top'
	);
}
add_action( 'init', 'tsm_add_date_archive_rewrite_rules' );

/**
 * Filter month link to use articles/archives prefix
 */
function tsm_filter_month_link( $link, $year, $month ) {
	$home_url = home_url( '/' );
	$link = $home_url . 'articles/archives/' . $year . '/' . zeroise( $month, 2 ) . '/';
	return $link;
}
add_filter( 'month_link', 'tsm_filter_month_link', 10, 3 );

/**
 * Filter year link to use articles/archives prefix
 */
function tsm_filter_year_link( $link, $year ) {
	$home_url = home_url( '/' );
	$link = $home_url . 'articles/archives/' . $year . '/';
	return $link;
}
add_filter( 'year_link', 'tsm_filter_year_link', 10, 2 );

/**
 * AJAX handler for loading month articles in archive library
 */
function tsm_get_month_articles() {
	$year = isset( $_GET['year'] ) ? absint( $_GET['year'] ) : 0;
	$monthnum = isset( $_GET['monthnum'] ) ? absint( $_GET['monthnum'] ) : 0;
	
	if ( ! $year || ! $monthnum ) {
		wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
	}
	
	$month_posts_query = new WP_Query( array(
		'year'           => $year,
		'monthnum'       => $monthnum,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
	
	ob_start();
	if ( $month_posts_query->have_posts() ) :
		?>
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
		<?php
	else :
		?>
		<p class="text-sm text-gray-500 dark:text-gray-400 py-4">No articles found.</p>
		<?php
	endif;
	wp_reset_postdata();
	
	$html = ob_get_clean();
	
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_get_month_articles', 'tsm_get_month_articles' );
add_action( 'wp_ajax_nopriv_get_month_articles', 'tsm_get_month_articles' );

/**
 * Calculate estimated reading time for a post
 *
 * @param int $post_id Post ID.
 * @return int Reading time in minutes.
 */
function tsm_get_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$content = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( strip_tags( $content ) );
	
	// Average reading speed: 200-250 words per minute
	// Using 225 as average
	$reading_time = ceil( $word_count / 225 );
	
	// Minimum 1 minute
	return max( 1, $reading_time );
}

/**
 * Get article thumbnail URL with fallback logic
 * 
 * Priority: Featured image > First image in content > Category-specific placeholder > Generic placeholder > Book placeholder
 *
 * @param int|null    $post_id      Post ID. Defaults to current post.
 * @param string      $image_size   Image size for featured image. Default 'medium'.
 * @param string|null $category_name Category name for placeholder selection. Default null (auto-detect).
 * @return string     Image URL
 */
function tsm_get_article_thumbnail_url( $post_id = null, $image_size = 'medium', $category_name = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$thumbnail_url = '';
	
	// Priority 1: Featured image
	$thumbnail_id = get_post_thumbnail_id( $post_id );
	if ( $thumbnail_id ) {
		$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, $image_size );
	}
	
	// Priority 2: First image in post content
	if ( empty( $thumbnail_url ) ) {
		$content = get_post_field( 'post_content', $post_id );
		preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches );
		if ( ! empty( $matches[1] ) ) {
			$thumbnail_url = $matches[1];
		}
	}
	
	// Priority 3-5: Placeholder images
	if ( empty( $thumbnail_url ) ) {
		// Auto-detect category if not provided
		if ( is_null( $category_name ) ) {
			$post_categories = get_the_category( $post_id );
			$category_name = ! empty( $post_categories ) ? $post_categories[0]->name : '';
		}
		
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
	
	return $thumbnail_url;
}

/**
 * Load a component template part
 * 
 * Wrapper around get_template_part() that ensures components can be found
 * in the components/ directory
 *
 * @param string $component Component name (without .php extension)
 * @param array  $args      Optional arguments to pass to the component
 */
function tsm_get_component( $component, $args = array() ) {
	// Try the components directory first
	$located = locate_template( "components/{$component}.php" );
	if ( $located ) {
		// Extract args into component scope (matching get_template_part behavior)
		if ( isset( $args ) && is_array( $args ) ) {
			extract( $args, EXTR_SKIP );
		}
		include $located;
		return;
	}
	
	// Fallback: try template-parts/components/ directory
	$located = locate_template( "template-parts/components/{$component}.php" );
	if ( $located ) {
		if ( isset( $args ) && is_array( $args ) ) {
			extract( $args, EXTR_SKIP );
		}
		include $located;
		return;
	}
	
	// Last resort: use get_template_part (won't work but won't crash)
	get_template_part( "components/{$component}", null, $args );
}

