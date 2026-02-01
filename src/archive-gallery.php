<?php
/**
 * The template for displaying gallery archives
 *
 * @package TSM_Theme
 */

get_header();

// Get current category filter
$current_category = 'all';
if ( is_tax( 'gallery_category' ) ) {
	$current_category = get_queried_object()->slug;
} elseif ( isset( $_GET['category'] ) ) {
	$current_category = sanitize_text_field( $_GET['category'] );
}

// Modify query if category filter is set
if ( $current_category !== 'all' && ! is_tax( 'gallery_category' ) ) {
	$args = array(
		'post_type'      => 'gallery',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'tax_query'      => array(
			array(
				'taxonomy' => 'gallery_category',
				'field'    => 'slug',
				'terms'    => $current_category,
			),
		),
	);
	query_posts( $args );
}
?>

<!-- Page Heading -->
<section class="max-w-[1280px] mx-auto px-6 py-12">
	<div class="mb-12">
		<div class="max-w-3xl">
			<h2 class="text-accent dark:text-white text-4xl md:text-5xl font-black leading-tight tracking-tight mb-4">
				<?php
				if ( is_tax( 'gallery_category' ) ) {
					single_term_title( 'Gallery: ' );
				} else {
					_e( 'Event Gallery Archive', 'tsm-theme' );
				}
				?>
			</h2>
			<p class="text-primary dark:text-[#6ec184] text-lg font-normal leading-relaxed">
				<?php _e( 'Relive the moments and memories of our past events, missions, and community outreach efforts around the world. Every photo tells a story of faith in action.', 'tsm-theme' ); ?>
			</p>
		</div>
	</div>

	<!-- Filters Section -->
	<?php
	$gallery_categories = get_terms( array(
		'taxonomy'   => 'gallery_category',
		'hide_empty' => true,
	) );
	
	if ( ! empty( $gallery_categories ) && ! is_wp_error( $gallery_categories ) ) :
		?>
		<section class="mb-8 overflow-x-auto no-scrollbar">
			<div class="flex gap-3 pb-2">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'gallery' ) ); ?>" 
				   class="px-6 py-2 rounded-lg <?php echo $current_category === 'all' ? 'bg-primary text-white' : 'bg-[#e7f3ea] dark:bg-[#1a2e1d] text-accent dark:text-white hover:bg-primary/10'; ?> text-sm font-semibold whitespace-nowrap transition-colors">
					<?php _e( 'All Events', 'tsm-theme' ); ?>
				</a>
				<?php foreach ( $gallery_categories as $category ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'category', $category->slug, get_post_type_archive_link( 'gallery' ) ) ); ?>" 
					   class="px-6 py-2 rounded-lg <?php echo $current_category === $category->slug ? 'bg-primary text-white' : 'bg-[#e7f3ea] dark:bg-[#1a2e1d] text-accent dark:text-white hover:bg-primary/10'; ?> text-sm font-medium whitespace-nowrap transition-colors">
						<?php echo esc_html( ucwords( $category->name ) ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<!-- Gallery Grid -->
	<div id="galleries-feed" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				
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
				
				// Get date
				$gallery_date = get_the_date( 'F j, Y' );
				?>
				<?php
				$lightbox_id = 'gallery-lightbox-' . get_the_ID();
				?>
				<div class="group relative aspect-[4/5] overflow-hidden rounded-xl bg-slate-200 cursor-pointer gallery-item" 
				     data-gallery-id="<?php echo esc_attr( get_the_ID() ); ?>"
				     data-gallery-title="<?php echo esc_attr( get_the_title() ); ?>"
				     data-category-slug="<?php echo esc_attr( $category_slug ); ?>">
					<?php if ( $thumbnail_url ) : ?>
						<div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110 gallery-image" 
						     data-lightbox="<?php echo esc_attr( $lightbox_id ); ?>"
						     data-full="<?php echo esc_url( $thumbnail_url ); ?>"
						     data-alt="<?php echo esc_attr( $thumbnail_alt ?: get_the_title() ); ?>"
						     style='background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 50%), url("<?php echo esc_url( $thumbnail_url ); ?>");'></div>
					<?php else : ?>
						<div class="absolute inset-0 bg-gradient-to-br from-accent to-[#102215]"></div>
					<?php endif; ?>
					<div class="absolute inset-0 border-4 border-transparent group-hover:border-primary/40 rounded-xl transition-all pointer-events-none"></div>
					<div class="absolute bottom-0 left-0 p-6 w-full">
						<?php if ( $category_name ) : ?>
							<span class="inline-block px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase tracking-widest rounded mb-3">
								<?php echo esc_html( strtoupper( $category_name ) ); ?>
							</span>
						<?php endif; ?>
						<h3 class="text-white text-xl font-bold leading-tight mb-1">
							<?php the_title(); ?>
						</h3>
						<p class="text-white/80 text-sm">
							<?php echo esc_html( $gallery_date ); ?>
						</p>
						<?php if ( $image_count > 0 ) : ?>
							<div class="mt-4 flex items-center gap-2 text-white/90 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
								<span class="material-symbols-outlined text-sm">photo_library</span>
								<?php printf( _n( '%d Photo', '%d Photos', $image_count, 'tsm-theme' ), $image_count ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			endwhile;
		else :
			?>
			<div class="col-span-full text-center py-12">
				<p class="text-gray-500 dark:text-gray-400"><?php _e( 'No galleries found.', 'tsm-theme' ); ?></p>
			</div>
			<?php
		endif;
		?>
	</div>

	<!-- Load More Section -->
	<?php
	$total_galleries = $wp_query->found_posts;
	$galleries_per_page = get_option( 'posts_per_page' );
	$has_more = $wp_query->max_num_pages > 1;
	$galleries_loaded = min( $galleries_per_page, $total_galleries );
	?>
	<div id="galleries-load-more-container" class="mt-16 flex flex-col items-center gap-6">
		<!-- Status Counter -->
		<div id="galleries-status" class="text-sm text-primary dark:text-[#6ec184] font-medium">
			<?php if ( $total_galleries > 0 ) : ?>
				<?php printf( __( 'Viewing %d of %d albums', 'tsm-theme' ), $galleries_loaded, $total_galleries ); ?>
			<?php endif; ?>
		</div>
		
		<!-- Load More Button -->
		<?php if ( $has_more ) : ?>
			<button id="galleries-load-more-btn" class="flex items-center gap-2 px-8 py-3 rounded-lg border-2 border-[#e7f3ea] dark:border-[#1a2e1d] hover:border-primary/50 hover:bg-white dark:hover:bg-[#1a2e1d] transition-all font-semibold">
				<span class="material-symbols-outlined">expand_more</span>
				<?php _e( 'Load More Archives', 'tsm-theme' ); ?>
			</button>
		<?php endif; ?>
		
		<!-- Loading Indicator -->
		<div id="galleries-loading" class="hidden items-center gap-2 text-primary">
			<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
				<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
				<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
			</svg>
			<span class="text-sm font-medium"><?php _e( 'Loading galleries...', 'tsm-theme' ); ?></span>
		</div>
	</div>
</section>

<?php
// Prepare galleries data for lightbox
if ( have_posts() ) :
	rewind_posts();
	while ( have_posts() ) :
		the_post();
		
		$gallery_images = get_post_meta( get_the_ID(), 'gallery_images', true );
		if ( ! is_array( $gallery_images ) ) {
			$gallery_images = array();
		}
		$gallery_images = array_filter( $gallery_images );
		
		if ( ! empty( $gallery_images ) ) {
			// Prepare images for lightbox
			$lightbox_images = array();
			foreach ( $gallery_images as $image_id ) {
				$lightbox_images[] = array(
					'full'  => wp_get_attachment_image_url( $image_id, 'full' ),
					'thumb' => wp_get_attachment_image_url( $image_id, 'thumbnail' ),
					'alt'   => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
				);
			}
			
			// Get location from gallery post if available
			$location = '';
			$gallery_cats = get_the_terms( get_the_ID(), 'gallery_category' );
			if ( $gallery_cats && ! is_wp_error( $gallery_cats ) && ! empty( $gallery_cats ) ) {
				$location = $gallery_cats[0]->name;
			}
			
			// Render lightbox for this gallery
			tsm_render_lightbox_gallery( array(
				'title'       => get_the_title(),
				'location'    => $location,
				'images'      => $lightbox_images,
				'lightbox_id' => 'gallery-lightbox-' . get_the_ID(),
			) );
		}
	endwhile;
endif;
?>

<script>
// Global handler to prevent gallery clicks when closing lightbox
document.addEventListener('click', function(e) {
	// If clicking on a close button, mark it but don't set the flag yet
	// The closeLightbox() function will handle setting the flag
	if (e.target.closest('.tsm-lightbox-close') || 
	    e.target.classList.contains('tsm-lightbox-close') ||
	    (e.target.classList.contains('material-symbols-outlined') && e.target.closest('.tsm-lightbox-close'))) {
		// Just mark that we're clicking a close button, but let the handler execute
		// The closeLightbox function will set the proper flags
	}
}, true); // Capture phase

document.addEventListener('DOMContentLoaded', function() {
	if (!window.tsmGalleries) {
		return;
	}
	
	const galleriesFeed = document.getElementById('galleries-feed');
	const loadMoreBtn = document.getElementById('galleries-load-more-btn');
	const loadingIndicator = document.getElementById('galleries-loading');
	const statusCounter = document.getElementById('galleries-status');
	
	if (!galleriesFeed) {
		return;
	}
	
	let currentPage = 1;
	let isLoading = false;
	let hasMore = <?php echo $has_more ? 'true' : 'false'; ?>;
	let totalGalleriesLoaded = <?php echo $galleries_loaded; ?>;
	let totalGalleriesCount = <?php echo $total_galleries; ?>;
	
	// Get current category from URL
	const urlParams = new URLSearchParams(window.location.search);
	const currentCategory = urlParams.get('category') || 'all';
	
	// Handle gallery card clicks - opens lightbox when clicking anywhere on the card
	function attachGalleryClickHandlers() {
		const galleryItems = document.querySelectorAll('.gallery-item:not([data-handler-attached])');
		galleryItems.forEach(function(item) {
			item.setAttribute('data-handler-attached', 'true');
			
			item.addEventListener('click', function(e) {
				// CRITICAL: Stop propagation immediately to prevent gallery-image handler from firing
				// But only if we're actually going to handle this click
				// We'll stop propagation after we've checked everything
				
				// CRITICAL: Check if this click originated from a lightbox close button
				// Check the event path/composedPath to see if it went through a close button
				const path = e.composedPath ? e.composedPath() : (e.path || []);
				for (let i = 0; i < path.length; i++) {
					const el = path[i];
					if (el && el.classList && (
						el.classList.contains('tsm-lightbox-close') ||
						el.classList.contains('tsm-lightbox') ||
						el.closest && el.closest('.tsm-lightbox-close')
					)) {
						e.preventDefault();
						return false;
					}
				}
				
				// Check global flag first - prevent opening if lightbox is closing
				if (window.tsmLightboxClosing) {
					e.preventDefault();
					return false;
				}
				
				// Don't trigger if pointer events are disabled (lightbox is open)
				if (this.style.pointerEvents === 'none') {
					e.preventDefault();
					return false;
				}
				
				// Don't trigger if clicking on lightbox elements or buttons
				if (e.target.closest('.tsm-lightbox') || 
				    e.target.closest('.tsm-lightbox-close') ||
				    e.target.closest('.tsm-lightbox-prev') ||
				    e.target.closest('.tsm-lightbox-next') ||
				    e.target.closest('.tsm-lightbox-download') ||
				    e.target.classList.contains('tsm-lightbox-close') ||
				    e.target.classList.contains('material-symbols-outlined')) {
					e.preventDefault();
					return false;
				}
				
				// Check if a lightbox is currently open
				const openLightbox = document.querySelector('.tsm-lightbox:not(.hidden)');
				if (openLightbox) {
					e.preventDefault();
					return false;
				}
				
				// Final check global flag
				if (window.tsmLightboxClosing) {
					e.preventDefault();
					e.stopImmediatePropagation();
					e.stopPropagation();
					return false;
				}
				
				// NOW stop propagation to prevent gallery-image handler from firing
				e.stopImmediatePropagation();
				e.stopPropagation();
				e.preventDefault();
				
				// Find the gallery-image element within this card
				const galleryImage = this.querySelector('.gallery-image');
				if (galleryImage) {
					// Get the lightbox ID - this should be unique for each gallery
					const lightboxId = galleryImage.getAttribute('data-lightbox');
					if (lightboxId) {
						// Don't open if we're closing this specific lightbox
						if (window.tsmClosingLightboxId === lightboxId) {
							return false;
						}
						
						const lightbox = document.getElementById(lightboxId);
						if (lightbox && !lightbox._isClosing && !window.tsmLightboxClosing) {
							// Double-check: don't open if another lightbox is open
							const openLightbox = document.querySelector('.tsm-lightbox:not(.hidden)');
							if (openLightbox && openLightbox !== lightbox) {
								return false;
							}
							
							// Use the stored openLightbox function if available
							if (lightbox._openLightbox && typeof lightbox._openLightbox === 'function') {
								// Open the first image (index 0) of THIS specific gallery
								lightbox._openLightbox(0);
							} else {
								// Fallback: wait for initialization or trigger it
								// Check if lightbox is initialized
								if (!lightbox.dataset.initialized) {
									// Wait a bit for initGalleryLightboxes to run
									setTimeout(function() {
										if (lightbox._openLightbox && typeof lightbox._openLightbox === 'function') {
											lightbox._openLightbox(0);
										}
									}, 100);
								}
							}
						}
					}
				}
				
				return false;
			}, true); // Use CAPTURE phase to catch before gallery-image handler
		});
	}
	
	// Initial attachment
	attachGalleryClickHandlers();
	
	// Load galleries via AJAX
	function loadGalleries() {
		if (isLoading || !hasMore) {
			return;
		}
		
		isLoading = true;
		
		// Show loading, hide button
		if (loadingIndicator) {
			loadingIndicator.classList.remove('hidden');
			loadingIndicator.classList.add('inline-flex');
		}
		if (loadMoreBtn) {
			loadMoreBtn.classList.add('hidden');
			loadMoreBtn.classList.remove('inline-flex');
		}
		
		const formData = new FormData();
		formData.append('action', 'tsm_load_galleries');
		formData.append('nonce', window.tsmGalleries.nonce);
		formData.append('page', currentPage + 1);
		formData.append('category', currentCategory);
		
		fetch(window.tsmGalleries.ajaxUrl, {
			method: 'POST',
			body: formData,
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(data) {
			if (data.success && data.data.galleries) {
				const galleries = data.data.galleries;
				hasMore = Boolean(data.data.has_more);
				
				if (galleries.length === 0) {
					isLoading = false;
					if (loadingIndicator) {
						loadingIndicator.classList.add('hidden');
						loadingIndicator.classList.remove('inline-flex');
					}
					return;
				}
				
				// Create gallery HTML
				const fragment = document.createDocumentFragment();
				galleries.forEach(function(gallery) {
					const galleryDiv = document.createElement('div');
					galleryDiv.className = 'group relative aspect-[4/5] overflow-hidden rounded-xl bg-slate-200 cursor-pointer gallery-item';
					galleryDiv.setAttribute('data-gallery-id', gallery.id);
					galleryDiv.setAttribute('data-gallery-title', gallery.title);
					galleryDiv.setAttribute('data-category-slug', gallery.category_slug);
					
					let imageHtml = '';
					if (gallery.thumbnail_url) {
						imageHtml = '<div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110 gallery-image" ' +
							'data-lightbox="gallery-lightbox-' + gallery.id + '" ' +
							'data-full="' + gallery.thumbnail_url + '" ' +
							'data-alt="' + (gallery.thumbnail_alt || gallery.title) + '" ' +
							'style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 50%), url(\'' + gallery.thumbnail_url + '\');"></div>';
					} else {
						imageHtml = '<div class="absolute inset-0 bg-gradient-to-br from-accent to-[#102215]"></div>';
					}
					
					let categoryBadge = '';
					if (gallery.category_name) {
						categoryBadge = '<span class="inline-block px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase tracking-widest rounded mb-3">' +
							gallery.category_name.toUpperCase() + '</span>';
					}
					
					let photoCount = '';
					if (gallery.image_count > 0) {
						photoCount = '<div class="mt-4 flex items-center gap-2 text-white/90 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">' +
							'<span class="material-symbols-outlined text-sm">photo_library</span>' +
							gallery.image_count + (gallery.image_count === 1 ? ' Photo' : ' Photos') +
							'</div>';
					}
					
					galleryDiv.innerHTML = imageHtml +
						'<div class="absolute inset-0 border-4 border-transparent group-hover:border-primary/40 rounded-xl transition-all pointer-events-none"></div>' +
						'<div class="absolute bottom-0 left-0 p-6 w-full">' +
						categoryBadge +
						'<h3 class="text-white text-xl font-bold leading-tight mb-1">' + gallery.title + '</h3>' +
						'<p class="text-white/80 text-sm">' + gallery.date + '</p>' +
						photoCount +
						'</div>';
					
					fragment.appendChild(galleryDiv);
				});
				
				// Append to feed
				galleriesFeed.appendChild(fragment);
				
				// Update counters
				totalGalleriesLoaded += galleries.length;
				if (data.data.total_count !== undefined) {
					totalGalleriesCount = parseInt(data.data.total_count, 10);
				}
				
				if (statusCounter) {
					statusCounter.textContent = 'Viewing ' + totalGalleriesLoaded + ' of ' + totalGalleriesCount + ' albums';
				}
				
				// Create and append lightboxes for new galleries
				galleries.forEach(function(gallery) {
					if (gallery.lightbox_images && gallery.lightbox_images.length > 0) {
						createLightboxHTML(gallery);
					}
				});
				
				// Re-attach click handlers for new galleries
				attachGalleryClickHandlers();
				
				// Re-initialize lightboxes
				if (typeof initGalleryLightboxes === 'function') {
					initGalleryLightboxes();
				}
				if (typeof initLazyLoadingFallback === 'function') {
					initLazyLoadingFallback();
				}
				
				// Show/hide load more button
				if (hasMore && loadMoreBtn) {
					loadMoreBtn.classList.remove('hidden');
					loadMoreBtn.classList.add('inline-flex');
				} else if (loadMoreBtn) {
					loadMoreBtn.classList.add('hidden');
					loadMoreBtn.classList.remove('inline-flex');
				}
				
				// Increment page
				currentPage++;
			}
			
			isLoading = false;
			if (loadingIndicator) {
				loadingIndicator.classList.add('hidden');
				loadingIndicator.classList.remove('inline-flex');
			}
		})
		.catch(function(_error) {
			isLoading = false;
			if (loadingIndicator) {
				loadingIndicator.classList.add('hidden');
				loadingIndicator.classList.remove('inline-flex');
			}
			if (loadMoreBtn) {
				loadMoreBtn.classList.remove('hidden');
				loadMoreBtn.classList.add('inline-flex');
			}
		});
	}
	
	// Load More button click handler
	if (loadMoreBtn) {
		loadMoreBtn.addEventListener('click', function() {
			if (!isLoading && hasMore) {
				loadGalleries();
			}
		});
	}
	
	// Create lightbox HTML for a gallery
	function createLightboxHTML(gallery) {
		// Check if lightbox already exists
		const existingLightbox = document.getElementById(gallery.lightbox_id);
		if (existingLightbox) {
			return;
		}
		
		const lightbox = document.createElement('div');
		lightbox.id = gallery.lightbox_id;
		lightbox.className = 'tsm-lightbox fixed inset-0 z-[100] bg-[#0a120c]/95 backdrop-blur-sm flex-col text-white hidden';
		lightbox.style.display = 'none';
		lightbox.setAttribute('data-images', JSON.stringify(gallery.lightbox_images));
		
		let thumbnailsHTML = '';
		gallery.lightbox_images.forEach(function(img, index) {
			thumbnailsHTML += '<div class="tsm-lightbox-thumbnail flex-shrink-0 size-16 rounded-sm opacity-40 hover:opacity-100 cursor-pointer overflow-hidden transition-all ' +
				(index === 0 ? 'ring-2 ring-primary ring-offset-2 ring-offset-black scale-105 opacity-100' : '') +
				'" data-index="' + index + '" data-full="' + img.full + '" data-alt="' + (img.alt || '') + '">' +
				'<img class="w-full h-full object-cover" src="' + img.thumb + '" alt="' + (img.alt || '') + '" loading="lazy" decoding="async"/>' +
				'</div>';
		});
		
		lightbox.innerHTML = 
			'<div class="absolute top-0 left-0 right-0 h-20 flex items-center justify-between px-8 z-50">' +
			'<div class="flex flex-col">' +
			'<span class="text-xs font-bold uppercase tracking-[0.2em] text-primary">' + gallery.title + '</span>' +
			'<span class="tsm-lightbox-counter text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">Image 1 of ' + gallery.lightbox_images.length + '</span>' +
			'</div>' +
			'<div class="flex items-center gap-4">' +
			'<a class="tsm-lightbox-download flex items-center justify-center size-10 rounded-full hover:bg-white/10 transition-colors" href="#" download>' +
			'<span class="material-symbols-outlined text-[24px]">download</span>' +
			'</a>' +
			'<button class="tsm-lightbox-close flex items-center justify-center size-12 rounded-full bg-white/5 hover:bg-white/10 transition-colors border border-white/10">' +
			'<span class="material-symbols-outlined text-[32px]">close</span>' +
			'</button>' +
			'</div>' +
			'</div>' +
			'<div class="flex-1 relative flex items-center justify-center p-4 md:p-12">' +
			'<button class="tsm-lightbox-prev absolute left-6 z-20 group flex items-center justify-center size-16 rounded-full bg-white/5 backdrop-blur-md border border-white/10 hover:bg-white/20 hover:scale-105 transition-all">' +
			'<span class="material-symbols-outlined text-[40px] text-white/70 group-hover:text-white">chevron_left</span>' +
			'</button>' +
			'<div class="relative max-w-6xl w-full h-full flex flex-col items-center justify-center gap-6">' +
			'<div class="relative w-full h-full flex items-center justify-center">' +
			'<img class="tsm-lightbox-image max-h-full max-w-full object-contain shadow-[0_25px_50px_-12px_rgba(0,0,0,0.8)] rounded-sm" src="" alt=""/>' +
			'</div>' +
			'<div class="w-full max-w-3xl text-center pb-4">' +
			'<h3 class="tsm-lightbox-title text-2xl font-semibold tracking-tight"></h3>' +
			(gallery.location ? '<div class="flex items-center justify-center gap-2 mt-2 text-gray-400 text-sm font-medium uppercase tracking-wider">' +
			'<span class="material-symbols-outlined text-sm">location_on</span>' +
			'<span class="tsm-lightbox-location">' + gallery.location + '</span>' +
			'</div>' : '') +
			'</div>' +
			'</div>' +
			'<button class="tsm-lightbox-next absolute right-6 z-20 group flex items-center justify-center size-16 rounded-full bg-white/5 backdrop-blur-md border border-white/10 hover:bg-white/20 hover:scale-105 transition-all">' +
			'<span class="material-symbols-outlined text-[40px] text-white/70 group-hover:text-white">chevron_right</span>' +
			'</button>' +
			'</div>' +
			'<div class="h-32 bg-black/40 backdrop-blur-xl border-t border-white/5 flex flex-col items-center justify-center">' +
			'<div class="max-w-4xl w-full px-8 overflow-x-auto flex items-center gap-3 hide-scrollbar py-4 tsm-lightbox-thumbnails">' +
			thumbnailsHTML +
			'</div>' +
			'</div>';
		
		document.body.appendChild(lightbox);
	}
});
</script>

<?php
get_footer();
