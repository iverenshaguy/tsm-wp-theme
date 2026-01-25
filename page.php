<?php
/**
 * The template for displaying all pages
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
                        
                        <?php if ( get_edit_post_link() ) : ?>
                            <footer class="entry-footer">
                                <?php
                                edit_post_link(
                                    sprintf(
                                        wp_kses(
                                            __( 'Edit <span class="screen-reader-text">%s</span>', 'tsm-theme' ),
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                            )
                                        ),
                                        get_the_title()
                                    ),
                                    '<span class="edit-link">',
                                    '</span>'
                                );
                                ?>
                            </footer>
                        <?php endif; ?>
                    </article>
                    
                    <?php
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
