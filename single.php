<?php
/**
 * The template for displaying single posts
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
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
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
                        
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail( 'large' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-content">
                            <?php
                            the_content();
                            
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . __( 'Pages:', 'tsm-theme' ),
                                'after'  => '</div>',
                            ) );
                            ?>
                        </div>
                        
                        <footer class="entry-footer">
                            <?php
                            if ( has_tag() ) {
                                echo '<div class="tags-links">';
                                echo '<span class="tags-title">' . __( 'Tags:', 'tsm-theme' ) . '</span> ';
                                the_tags( '', ', ', '' );
                                echo '</div>';
                            }
                            ?>
                        </footer>
                    </article>
                    
                    <?php
                    // Post navigation
                    the_post_navigation( array(
                        'prev_text' => '<span class="nav-subtitle">' . __( 'Previous:', 'tsm-theme' ) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . __( 'Next:', 'tsm-theme' ) . '</span> <span class="nav-title">%title</span>',
                    ) );
                    
                    // Comments
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                    
                endwhile;
                ?>
            </div>
            
            <?php get_sidebar(); ?>
        </div>
    </div>
</main>

<?php
get_footer();
