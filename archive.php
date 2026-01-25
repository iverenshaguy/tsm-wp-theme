<?php
/**
 * The template for displaying archive pages
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
					?>
					<header class="page-header">
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
						?>
					</header>
					
					<?php
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
								</div>
							</header>
							
							<div class="entry-content">
								<?php the_excerpt(); ?>
							</div>
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
