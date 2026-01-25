<?php
/**
 * The footer template file
 *
 * @package TSM_Theme
 */
?>

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php
            // Display footer widgets if they exist
            if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) {
                ?>
                <div class="footer-widgets">
                    <div class="footer-widget-area">
                        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <div class="footer-column">
                                <?php dynamic_sidebar( 'footer-1' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <div class="footer-column">
                                <?php dynamic_sidebar( 'footer-2' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <div class="footer-column">
                                <?php dynamic_sidebar( 'footer-3' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            ?>
            
            <div class="site-info">
                <p>
                    &copy; <?php echo date( 'Y' ); ?> 
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
                    <?php _e( 'All rights reserved.', 'tsm-theme' ); ?>
                </p>
                <p>
                    <?php
                    printf(
                        __( 'Powered by %s', 'tsm-theme' ),
                        '<a href="' . esc_url( __( 'https://wordpress.org/', 'tsm-theme' ) ) . '">WordPress</a>'
                    );
                    ?>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
