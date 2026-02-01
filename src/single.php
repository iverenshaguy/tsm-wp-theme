<?php
/**
 * The template for displaying single posts
 *
 * @package TSM_Theme
 */

get_header();

// Get post data
while ( have_posts() ) :
	the_post();
	
	// Get featured image with fallback logic
	$featured_image_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	if ( ! $featured_image_url ) {
		// Try to get first image from post content
		$content = get_the_content();
		preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches );
		if ( ! empty( $matches[1] ) ) {
			$featured_image_url = $matches[1];
		} else {
			// Use category-specific placeholder
			$categories = get_the_category();
			$category_name = ! empty( $categories ) ? strtolower( $categories[0]->name ) : '';
			$placeholder_file = 'article-placeholder.png';
			
			// Determine placeholder based on category
			if ( stripos( $category_name, 'word for the month' ) !== false ) {
				$placeholder_file = 'word-for-the-month-placeholder.png';
			} elseif ( stripos( $category_name, 'favour for the week' ) !== false ) {
				$placeholder_file = 'favour-for-the-week-placeholder.png';
			}
			
			$placeholder_path = get_template_directory() . '/assets/images/' . $placeholder_file;
			$featured_image_url = get_template_directory_uri() . '/assets/images/' . $placeholder_file;
			
			// If specific placeholder doesn't exist, fallback to article placeholder, then book placeholder
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
	
	// Get categories (get again after featured image logic)
	$categories = get_the_category();
	$primary_category = ! empty( $categories ) ? $categories[0] : null;
	
	// Get author (use default if not set)
	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author();
	$default_author = get_theme_mod( 'single_article_default_author', 'Dr. Tor Terry Shaguy' );
	
	// Use default author if author name is empty or default
	if ( empty( $author_name ) || $author_name === 'admin' || $author_name === 'Administrator' ) {
		$author_name = $default_author;
	}
	
	// Get author avatar, fallback to default author image
	$author_avatar = get_avatar_url( $author_id, array( 'size' => 48 ) );
	$default_author_image = get_theme_mod( 'single_article_default_author_image', '' );
	if ( empty( $author_avatar ) || ! $author_avatar || strpos( $author_avatar, 'gravatar.com' ) !== false && empty( get_avatar_url( $author_id ) ) ) {
		if ( ! empty( $default_author_image ) ) {
			$author_avatar = $default_author_image;
		} else {
			$author_avatar = get_avatar_url( 0, array( 'size' => 48 ) ); // Fallback to default WordPress avatar
		}
	}
	
	// Get reading time
	$reading_time = tsm_get_reading_time();
	
	// Get tags
	$tags = get_the_tags();
	
	// Get related articles from same category
	$related_posts = array();
	if ( $primary_category ) {
		$related_query = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'category__in'   => array( $primary_category->term_id ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		$related_posts = $related_query->posts;
		wp_reset_postdata();
	}
	
	// Customizer settings
	$show_subscribe_form = get_theme_mod( 'single_article_show_subscribe_form', true );
	$newsletter_title = get_theme_mod( 'single_article_newsletter_title', 'Subscribe to Devotions' );
	$newsletter_description = get_theme_mod( 'single_article_newsletter_description', 'Join 12,000+ others receiving daily wisdom and spiritual encouragement in their inbox every morning.' );
	$newsletter_form_id = get_theme_mod( 'single_article_newsletter_form_id', '' );
	
	// Fallback to articles archive form ID if single article form ID is not set
	if ( empty( $newsletter_form_id ) ) {
		$newsletter_form_id = get_theme_mod( 'articles_newsletter_form_id', '' );
	}
	?>
	
	<!-- Progress Bar (Fixed Top) -->
	<div class="fixed top-0 left-0 w-full h-1 z-50 bg-gray-200 dark:bg-[#1a2e1f]" id="reading-progress">
		<div class="h-full transition-all duration-150 bg-primary dark:bg-primary" style="width: 0%;"></div>
	</div>
	
	<main class="flex-1">
		<!-- Hero Header Section -->
		<div class="w-full h-[60vh] relative overflow-hidden">
			<!-- Background image -->
			<div class="absolute inset-0 bg-center bg-cover" style="background-image: url('<?php echo esc_url( $featured_image_url ); ?>');" data-alt="<?php echo esc_attr( get_the_title() ); ?>"></div>
			<!-- Light mode overlay -->
			<div class="absolute inset-0 dark:hidden" style="background: linear-gradient(0deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0.1) 100%);"></div>
			<!-- Dark mode overlay -->
			<div class="hidden absolute inset-0 dark:block" style="background: linear-gradient(0deg, rgba(16, 34, 21, 1) 0%, rgba(16, 34, 21, 0.4) 50%, rgba(16, 34, 21, 0.2) 100%);"></div>
			
			<div class="absolute bottom-0 left-0 w-full px-6 lg:px-40 pb-12 max-w-[1200px] mx-auto right-0">
				<div class="flex flex-col gap-4">
					<?php if ( $primary_category ) : ?>
						<span class="px-3 py-1 text-xs font-bold tracking-widest uppercase rounded-full border bg-primary/10 dark:bg-primary/20 text-primary border-primary/30 w-fit">
							<?php echo esc_html( $primary_category->name ); ?>
						</span>
					<?php endif; ?>
					
					<h1 class="text-[#0d1b11] dark:text-white text-4xl md:text-6xl font-semibold leading-tight tracking-tight max-w-4xl">
						<?php the_title(); ?>
					</h1>
					
					<div class="flex gap-4 items-center mt-4">
						<?php if ( $author_avatar ) : ?>
							<div class="bg-center bg-cover rounded-full border-2 size-12 border-primary" style="background-image: url('<?php echo esc_url( $author_avatar ); ?>');" data-alt="<?php echo esc_attr( $author_name ); ?>"></div>
						<?php endif; ?>
						<div>
							<p class="text-[#0d1b11] dark:text-white font-semibold text-lg"><?php echo esc_html( $author_name ); ?></p>
							<p class="text-sm text-gray-500 dark:text-white/60">
								Published <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?> • <?php echo esc_html( $reading_time ); ?> min read
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Content Grid -->
		<div class="px-6 lg:px-40 py-12 max-w-[1200px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12">
			<!-- Main Article Column -->
			<article class="font-serif lg:col-span-8 reading-content">
				<?php
				// Output post content with custom styling
				$content = get_the_content();
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
				echo wp_kses_post( $content );
				?>
				
				<!-- Article Footer / Share -->
				<?php if ( $tags || true ) : // Always show share section ?>
					<div class="pt-8 mt-16 border-t border-gray-200 dark:border-white/10">
						<div class="flex flex-col gap-6 justify-between items-center sm:flex-row">
							<div class="flex gap-4 items-center">
								<span class="text-sm font-bold tracking-widest text-gray-400 uppercase dark:text-white/50 font-display">Share this</span>
								<div class="flex gap-2">
									<button class="flex justify-center items-center text-gray-600 bg-gray-100 rounded-lg transition-all size-10 dark:bg-white/5 dark:text-white/70 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary" onclick="window.location.href='mailto:?subject=<?php echo urlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>'">
										<span class="text-xl material-symbols-outlined">mail</span>
									</button>
									<button class="flex justify-center items-center text-gray-600 bg-gray-100 rounded-lg transition-all size-10 dark:bg-white/5 dark:text-white/70 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary" onclick="navigator.share({title: '<?php echo esc_js( get_the_title() ); ?>', url: '<?php echo esc_url( get_permalink() ); ?>'})">
										<span class="text-xl material-symbols-outlined">share</span>
									</button>
									<button class="flex justify-center items-center text-gray-600 bg-gray-100 rounded-lg transition-all size-10 dark:bg-white/5 dark:text-white/70 hover:bg-primary/10 dark:hover:bg-primary/20 hover:text-primary" onclick="navigator.clipboard.writeText('<?php echo esc_url( get_permalink() ); ?>'); alert('Link copied!');">
										<span class="text-xl material-symbols-outlined">link</span>
									</button>
								</div>
							</div>
							<?php if ( $tags ) : ?>
								<div class="flex flex-wrap gap-2">
									<?php foreach ( $tags as $tag ) : ?>
										<span class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg dark:bg-white/5 dark:text-white/60">
											#<?php echo esc_html( $tag->name ); ?>
										</span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</article>
			
			<!-- Sidebar Column -->
			<aside class="flex flex-col gap-8 lg:col-span-4">
				<?php if ( $show_subscribe_form ) : ?>
					<!-- Newsletter Signup -->
					<div class="sticky top-24 p-6 bg-white rounded-xl border border-gray-200 shadow-sm dark:bg-white/5 dark:border-white/10">
						<div class="flex flex-col gap-4">
							<div class="flex justify-center items-center rounded-lg size-12 bg-primary/10 dark:bg-primary/20 text-primary">
								<span class="text-3xl material-symbols-outlined">mail_lock</span>
							</div>
							<h3 class="text-xl font-bold text-[#0d1b11] dark:text-white"><?php echo esc_html( $newsletter_title ); ?></h3>
							<p class="text-sm leading-relaxed text-gray-600 dark:text-white/60">
								<?php echo esc_html( $newsletter_description ); ?>
							</p>
							<?php if ( function_exists( 'wpforms_display' ) && $newsletter_form_id ) : ?>
								<div class="mt-2">
									<?php wpforms_display( $newsletter_form_id ); ?>
								</div>
							<?php else : ?>
								<form class="flex flex-col gap-3 mt-2" method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>">
									<input class="bg-gray-50 dark:bg-[#1a2e1f] border-gray-200 dark:border-white/10 rounded-lg px-4 py-3 focus:ring-primary focus:border-primary text-sm text-[#0d1b11] dark:text-white placeholder:text-gray-400 dark:placeholder:text-white/40" placeholder="Your email address" type="email" name="email" required/>
									<button class="py-3 font-bold text-white rounded-lg shadow-md transition-all bg-primary dark:bg-primary dark:text-background-dark hover:brightness-110 dark:hover:opacity-90" type="submit">
										Subscribe Now
									</button>
									<p class="text-[10px] text-gray-400 dark:text-white/40 text-center uppercase tracking-tighter">No spam. Unsubscribe anytime.</p>
								</form>
							<?php endif; ?>
						</div>
						
						<?php if ( ! empty( $related_posts ) ) : ?>
							<!-- Related Articles -->
							<div class="mt-12">
								<h3 class="text-lg font-bold mb-6 border-b border-gray-100 dark:border-white/10 pb-2 text-[#0d1b11] dark:text-white">Related Articles</h3>
								<div class="flex flex-col gap-6">
									<?php foreach ( $related_posts as $related_post ) : ?>
										<?php
										setup_postdata( $related_post );
										$related_image = get_the_post_thumbnail_url( $related_post->ID, 'thumbnail' );
										if ( ! $related_image ) {
											// Try to get first image from post content
											$related_content = get_post_field( 'post_content', $related_post->ID );
											preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $related_content, $related_matches );
											if ( ! empty( $related_matches[1] ) ) {
												$related_image = $related_matches[1];
											} else {
												// Use category-specific placeholder
												$related_categories = get_the_category( $related_post->ID );
												$related_category_name = ! empty( $related_categories ) ? strtolower( $related_categories[0]->name ) : '';
												$related_placeholder_file = 'article-placeholder.png';
												
												// Determine placeholder based on category
												if ( stripos( $related_category_name, 'word for the month' ) !== false ) {
													$related_placeholder_file = 'word-for-the-month-placeholder.png';
												} elseif ( stripos( $related_category_name, 'favour for the week' ) !== false ) {
													$related_placeholder_file = 'favour-for-the-week-placeholder.png';
												}
												
												$related_placeholder_path = get_template_directory() . '/assets/images/' . $related_placeholder_file;
												$related_image = get_template_directory_uri() . '/assets/images/' . $related_placeholder_file;
												
												// If specific placeholder doesn't exist, fallback to article placeholder, then book placeholder
												if ( ! file_exists( $related_placeholder_path ) ) {
													$related_article_placeholder = get_template_directory() . '/assets/images/article-placeholder.png';
													if ( file_exists( $related_article_placeholder ) ) {
														$related_image = get_template_directory_uri() . '/assets/images/article-placeholder.png';
													} else {
														$related_image = get_template_directory_uri() . '/assets/images/book-placeholder.png';
													}
												}
											}
										}
										$related_author = get_the_author_meta( 'display_name', $related_post->post_author );
										$related_reading_time = tsm_get_reading_time( $related_post->ID );
										?>
										<a class="flex gap-4 group" href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>">
											<div class="bg-center bg-cover rounded-lg border border-gray-100 size-20 shrink-0 dark:border-white/10" style="background-image: url('<?php echo esc_url( $related_image ); ?>');" data-alt="<?php echo esc_attr( get_the_title( $related_post->ID ) ); ?>"></div>
											<div class="flex flex-col justify-center">
												<p class="text-sm font-bold leading-tight group-hover:text-primary transition-colors text-[#0d1b11] dark:text-white">
													<?php echo esc_html( get_the_title( $related_post->ID ) ); ?>
												</p>
												<p class="mt-1 text-xs text-gray-500 dark:text-white/50">
													<?php echo esc_html( $related_author ); ?> • <?php echo esc_html( $related_reading_time ); ?> min read
												</p>
											</div>
										</a>
									<?php endforeach; ?>
									<?php wp_reset_postdata(); ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				<?php elseif ( ! empty( $related_posts ) ) : ?>
					<!-- Related Articles Only (if subscribe form is hidden) -->
					<div class="sticky top-24 p-6 bg-white rounded-xl border border-gray-200 shadow-sm dark:bg-white/5 dark:border-white/10">
						<h3 class="text-lg font-bold mb-6 border-b border-gray-100 dark:border-white/10 pb-2 text-[#0d1b11] dark:text-white">Related Articles</h3>
						<div class="flex flex-col gap-6">
							<?php foreach ( $related_posts as $related_post ) : ?>
								<?php
								setup_postdata( $related_post );
								$related_image = get_the_post_thumbnail_url( $related_post->ID, 'thumbnail' );
								if ( ! $related_image ) {
									// Try to get first image from post content
									$related_content = get_post_field( 'post_content', $related_post->ID );
									preg_match( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $related_content, $related_matches );
									if ( ! empty( $related_matches[1] ) ) {
										$related_image = $related_matches[1];
									} else {
										// Use category-specific placeholder
										$related_categories = get_the_category( $related_post->ID );
										$related_category_name = ! empty( $related_categories ) ? strtolower( $related_categories[0]->name ) : '';
										$related_placeholder_file = 'article-placeholder.png';
										
										// Determine placeholder based on category
										if ( stripos( $related_category_name, 'word for the month' ) !== false ) {
											$related_placeholder_file = 'word-for-the-month-placeholder.png';
										} elseif ( stripos( $related_category_name, 'favour for the week' ) !== false ) {
											$related_placeholder_file = 'favour-for-the-week-placeholder.png';
										}
										
										$related_placeholder_path = get_template_directory() . '/assets/images/' . $related_placeholder_file;
										$related_image = get_template_directory_uri() . '/assets/images/' . $related_placeholder_file;
										
										// If specific placeholder doesn't exist, fallback to article placeholder, then book placeholder
										if ( ! file_exists( $related_placeholder_path ) ) {
											$related_article_placeholder = get_template_directory() . '/assets/images/article-placeholder.png';
											if ( file_exists( $related_article_placeholder ) ) {
												$related_image = get_template_directory_uri() . '/assets/images/article-placeholder.png';
											} else {
												$related_image = get_template_directory_uri() . '/assets/images/book-placeholder.png';
											}
										}
									}
								}
								$related_author = get_the_author_meta( 'display_name', $related_post->post_author );
								$related_reading_time = tsm_get_reading_time( $related_post->ID );
								?>
								<a class="flex gap-4 group" href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>">
									<div class="bg-center bg-cover rounded-lg border border-gray-100 size-20 shrink-0 dark:border-white/10" style="background-image: url('<?php echo esc_url( $related_image ); ?>');" data-alt="<?php echo esc_attr( get_the_title( $related_post->ID ) ); ?>"></div>
									<div class="flex flex-col justify-center">
										<p class="text-sm font-bold leading-tight group-hover:text-primary transition-colors text-[#0d1b11] dark:text-white">
											<?php echo esc_html( get_the_title( $related_post->ID ) ); ?>
										</p>
										<p class="mt-1 text-xs text-gray-500 dark:text-white/50">
											<?php echo esc_html( $related_author ); ?> • <?php echo esc_html( $related_reading_time ); ?> min read
										</p>
									</div>
								</a>
							<?php endforeach; ?>
							<?php wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>
			</aside>
		</div>
	</main>
	
	<style>
		.reading-content {
			color: #111827;
		}
		.dark .reading-content {
			color: rgba(255, 255, 255, 0.9);
		}
		.reading-content p {
			margin-bottom: 1.5rem;
			line-height: 1.8;
			font-size: 1.125rem;
			color: inherit;
		}
		.dark .reading-content p {
			color: inherit;
		}
		.reading-content > p:first-of-type {
			font-size: 1.125rem;
			line-height: 1.8;
			color: inherit;
		}
		.reading-content > p:first-of-type::first-letter {
			font-size: 4.5rem;
			font-weight: 700;
			color: rgb(51, 154, 70);
			float: left;
			margin-right: 0.75rem;
			line-height: 1;
		}
		/* Exclude paragraphs inside blockquotes from first-letter styling */
		.reading-content blockquote p::first-letter,
		.reading-content .wp-block-verse p::first-letter {
			font-size: inherit;
			font-weight: inherit;
			color: inherit;
			float: none;
			margin-right: 0;
		}
		.reading-content h2 {
			font-family: 'Inter', sans-serif;
			font-weight: 700;
			font-size: 1.875rem;
			margin-top: 2.5rem;
			margin-bottom: 1rem;
			color: inherit;
		}
		.reading-content blockquote,
		.reading-content .wp-block-verse {
			border-left: 4px solid rgb(51, 154, 70);
			padding-left: 1.5rem;
			padding-top: 0.5rem;
			padding-bottom: 0.5rem;
			font-family: 'Lora', serif;
			font-style: italic;
			font-size: 1.25rem;
			margin: 2.5rem 0;
			color: #374151;
			background-color: #f9fafb;
		}
		.dark .reading-content blockquote,
		.dark .reading-content .wp-block-verse {
			color: #9db9a4;
			background-color: transparent;
		}
		.reading-content a {
			color: rgb(51, 154, 70);
			text-decoration: underline;
			text-underline-offset: 4px;
			transition: opacity 0.2s;
		}
		.reading-content a:hover {
			opacity: 0.8;
		}
		.reading-content img {
			width: 100%;
			height: auto;
			border-radius: 0.75rem;
			margin: 2.5rem 0;
		}
		.reading-content ul,
		.reading-content ol {
			margin-bottom: 1.5rem;
			padding-left: 1.5rem;
			line-height: 1.8;
			font-size: 1.125rem;
		}
		.reading-content li {
			margin-bottom: 0.75rem;
		}
	</style>
	
	<script>
		// Reading progress bar
		(function() {
			const progressBar = document.getElementById('reading-progress');
			if (!progressBar) return;
			
			const progressFill = progressBar.querySelector('div');
			const article = document.querySelector('.reading-content');
			if (!article || !progressFill) return;
			
			function updateProgress() {
				const articleTop = article.offsetTop;
				const articleHeight = article.offsetHeight;
				const windowHeight = window.innerHeight;
				const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
				
				const articleBottom = articleTop + articleHeight;
				const windowBottom = scrollTop + windowHeight;
				
				if (scrollTop < articleTop) {
					progressFill.style.width = '0%';
				} else if (windowBottom > articleBottom) {
					progressFill.style.width = '100%';
				} else {
					const scrolled = scrollTop - articleTop;
					const total = articleHeight - windowHeight;
					const percentage = Math.min(100, Math.max(0, (scrolled / total) * 100));
					progressFill.style.width = percentage + '%';
				}
			}
			
			window.addEventListener('scroll', updateProgress);
			window.addEventListener('resize', updateProgress);
			updateProgress();
		})();
	</script>
	
	<?php
endwhile;

get_footer();
