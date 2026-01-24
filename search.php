<?php
/**
 * The template for displaying search results pages
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
                        <h1 class="page-title">
                            <?php
                            printf(
                                esc_html__( 'Search Results for: %s', 'tsm-theme' ),
                                '<span>' . get_search_query() . '</span>'
                            );
                            ?>
                        </h1>
                    </header>
                    
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
                            <header class="entry-header">
                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="post-meta">
                                    <span class="posted-on">
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="post-type">
                                        <?php echo get_post_type_object( get_post_type() )->labels->singular_name; ?>
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
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => __( '&laquo; Previous', 'tsm-theme' ),
                        'next_text' => __( 'Next &raquo;', 'tsm-theme' ),
                    ) );
                    
                else :
                    ?>
                    <article class="post">
                        <header class="entry-header">
                            <h1 class="entry-title"><?php _e( 'Nothing Found', 'tsm-theme' ); ?></h1>
                        </header>
                        <div class="entry-content">
                            <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'tsm-theme' ); ?></p>
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
