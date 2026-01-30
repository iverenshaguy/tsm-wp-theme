<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package TSM_Theme
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container">
		<div class="site-content">
			<div class="content-area">
				<article class="post error-404 not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'tsm-theme' ); ?></h1>
					</header>
					
					<div class="entry-content">
						<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'tsm-theme' ); ?></p>
						
						<?php get_search_form(); ?>
						
						<div class="widget">
							<h2 class="widget-title"><?php _e( 'Most Used Categories', 'tsm-theme' ); ?></h2>
							<ul>
								<?php
								wp_list_categories(
									array(
										'orderby'    => 'count',
										'order'      => 'DESC',
										'show_count' => 1,
										'title_li'   => '',
										'number'     => 10,
									)
								);
								?>
							</ul>
						</div>
						
						<div class="widget">
							<h2 class="widget-title"><?php _e( 'Archives', 'tsm-theme' ); ?></h2>
							<ul>
								<?php
								wp_get_archives(
									array(
										'type'  => 'monthly',
										'limit' => 12,
									)
								);
								?>
							</ul>
						</div>
					</div>
				</article>
			</div>
			
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php
get_footer();
