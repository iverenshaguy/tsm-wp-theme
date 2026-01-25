<?php
/**
 * The template for displaying single book posts
 *
 * @package TSM_Theme
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="max-w-[1280px] mx-auto px-6 py-20">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'book-single' ); ?>>
				<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
					<!-- Book Cover -->
					<div class="flex justify-center lg:justify-start">
						<div class="relative w-full max-w-md aspect-[3/4] rounded-lg shadow-2xl overflow-hidden">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php
								the_post_thumbnail(
									'large',
									array(
										'class' => 'w-full h-full object-cover',
										'alt'   => get_the_title() . ' Book Cover',
									)
								);
								?>
							<?php else : ?>
								<img alt="<?php the_title_attribute(); ?> Book Cover" class="w-full h-full object-cover" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/book-placeholder.jpg' ); ?>"/>
							<?php endif; ?>
						</div>
					</div>

					<!-- Book Content -->
					<div class="flex flex-col">
						<h1 class="text-primary dark:text-white text-4xl md:text-5xl font-black leading-tight mb-4">
							<?php the_title(); ?>
						</h1>

						<div class="mb-6">
							<?php
							$book_author = get_post_meta( get_the_ID(), 'book_author', true );
							if ( $book_author ) {
								echo '<p class="text-gray-600 dark:text-gray-400 text-xl font-semibold">' . esc_html( 'By ' . $book_author ) . '</p>';
							} else {
								echo '<p class="text-gray-600 dark:text-gray-400 text-xl font-semibold">By Terry Shaguy</p>';
							}
							?>
						</div>

						<div class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed mb-8">
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

						<!-- Buy/Excerpt Buttons -->
						<div class="flex flex-wrap gap-4 mb-8">
							<?php
							$book_buy_url = get_post_meta( get_the_ID(), 'book_buy_url', true );
							if ( $book_buy_url ) :
								?>
								<a href="<?php echo esc_url( $book_buy_url ); ?>" target="_blank" rel="noopener noreferrer" class="bg-primary hover:bg-primary/90 text-white font-bold py-4 px-10 rounded-lg transition-colors flex items-center gap-2">
									Buy Now <span class="material-symbols-outlined">shopping_cart</span>
								</a>
								<?php
							endif;

							$book_excerpt_url = get_post_meta( get_the_ID(), 'book_excerpt_url', true );
							if ( $book_excerpt_url ) :
								?>
								<a href="<?php echo esc_url( $book_excerpt_url ); ?>" class="border-2 border-primary hover:bg-primary/10 text-primary dark:text-white font-bold py-4 px-10 rounded-lg transition-colors">
									Read Excerpt
								</a>
								<?php
							endif;
							?>
						</div>

						<!-- Book Meta -->
						<div class="border-t border-gray-200 dark:border-[#1d3a24] pt-6">
							<div class="text-sm text-gray-500 dark:text-gray-400">
								<span class="posted-on">
									<?php echo esc_html( __( 'Published:', 'tsm-theme' ) . ' ' . get_the_date() ); ?>
								</span>
							</div>
						</div>
					</div>
				</div>

				<!-- Post Navigation -->
				<?php
				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-subtitle">' . __( 'Previous Book:', 'tsm-theme' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . __( 'Next Book:', 'tsm-theme' ) . '</span> <span class="nav-title">%title</span>',
					)
				);
				?>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
