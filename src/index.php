<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package TSM_Theme
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container">
		<div class="site-content">
			<div class="content-area">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="post-thumbnail">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'large' ); ?>
									</a>
								</div>
							<?php endif; ?>
							
							<header class="entry-header">
								<h2 class="post-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="post-meta">
									<span class="posted-on">
										<?php echo get_the_date(); ?>
									</span>
									<span class="byline">
										<?php _e( 'by', 'tsm-theme' ); ?> 
										<span class="author"><?php the_author(); ?></span>
									</span>
									<?php if ( has_category() ) : ?>
										<span class="cat-links">
											<?php _e( 'in', 'tsm-theme' ); ?> <?php the_category( ', ' ); ?>
										</span>
									<?php endif; ?>
								</div>
							</header>
							
							<div class="entry-content">
								<?php
								if ( is_home() || is_front_page() || is_archive() ) {
									the_excerpt();
								} else {
									the_content();
								}
								?>
							</div>
							
							<?php if ( is_single() ) : ?>
								<footer class="entry-footer">
									<?php
									if ( has_tag() ) {
										echo '<div class="tags-links">';
										the_tags( '', ', ', '' );
										echo '</div>';
									}
									?>
								</footer>
							<?php endif; ?>
						</article>
						<?php
					endwhile;

					// Pagination
					the_posts_pagination(
						array(
							'mid_size'  => 2,
							'prev_text' => __( '&laquo; Previous', 'tsm-theme' ),
							'next_text' => __( 'Next &raquo;', 'tsm-theme' ),
						)
					);

				else :
					?>
					<article class="post">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'Nothing Found', 'tsm-theme' ); ?></h1>
						</header>
						<div class="entry-content">
							<p><?php _e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'tsm-theme' ); ?></p>
							<?php get_search_form(); ?>
						</div>
					</article>
					<?php
				endif;
				?>
			</div>
			
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php
get_footer();
