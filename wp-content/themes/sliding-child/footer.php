<?php
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */
    global $woo_options;

    $total = 4;
    if ( isset( $woo_options['woo_footer_sidebars'] ) && ( $woo_options['woo_footer_sidebars'] != '' ) ) {
        $total = $woo_options['woo_footer_sidebars'];
    }

    if ( ( woo_active_sidebar( 'footer-1' ) ||
           woo_active_sidebar( 'footer-2' ) ||
           woo_active_sidebar( 'footer-3' ) ||
           woo_active_sidebar( 'footer-4' ) ) && $total > 0 ) {

    $options = get_option('sliding_child_options');
?>
    <div id="social-footer" class="col-full">
        <ul>
            <li class="facebook">
                <a href="<?php echo $options['facebook_url']; ?>" target="_blank"></a>
            </li>
            <li class="google-plus">
                <a href="<?php echo $options['googleplus_url']; ?>" target="_blank"></a>
            </li>
            <li class="instagram">
                <a href="<?php echo $options['instagram_url']; ?>" target="_blank"></a>
            </li>
            <li class="twitter">
                <a href="<?php echo $options['twitter_url']; ?>" target="_blank"></a>
            </li>
            <li class="youtube">
                <a href="<?php echo $options['youtube_url']; ?>" target="_blank"></a>
            </li>
            <li class="pinterest">
                <a href="<?php echo $options['pinterest_url']; ?>" target="_blank"></a>
            </li>
        </ul>
    </div>

    <section id="footer-widgets" class="col-<?php echo $total; ?>">
        <div class="col-full fix">
        <hr />

        <?php $i = 0; while ( $i < $total ) { $i++; ?>
            <?php if ( woo_active_sidebar( 'footer-' . $i ) ) { ?>

        <div class="block footer-widget-<?php echo $i; ?>">
            <?php woo_sidebar( 'footer-' . $i ); ?>
        </div>

            <?php } ?>
        <?php } // End WHILE Loop ?>
        
        </div><!--/.col-full-->

    </section><!-- /#footer-widgets  -->
<?php } // End IF Statement ?>
    <footer id="footer">
        
        <div class="col-full">

        <div id="copyright" class="col-left">
        <?php if( isset( $woo_options['woo_footer_left'] ) && $woo_options['woo_footer_left'] == 'true' ) {

                echo stripslashes( $woo_options['woo_footer_left_text'] );

        } else { ?>
            <p><?php bloginfo(); ?> &copy; <?php echo date( 'Y' ); ?>. <?php _e( 'All Rights Reserved.', 'woothemes' ); ?></p>
        <?php } ?>
        </div>

        <div id="credit" class="col-right">
        <?php if( isset( $woo_options['woo_footer_right'] ) && $woo_options['woo_footer_right'] == 'true' ) {

            echo stripslashes( $woo_options['woo_footer_right_text'] );

        } else { ?>
            <p><?php _e( 'Powered by', 'woothemes' ); ?> <a href="http://www.wordpress.org">WordPress</a>. <?php _e( 'Designed by', 'woothemes' ); ?> <a href="<?php echo ( isset( $woo_options['woo_footer_aff_link'] ) && ! empty( $woo_options['woo_footer_aff_link'] ) ? esc_url( $woo_options['woo_footer_aff_link'] ) : 'http://www.woothemes.com' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/woothemes.png" width="74" height="19" alt="Woo Themes" /></a></p>
        <?php } ?>
        </div>
        
        </div><!--/.col-full-->

    </footer><!-- /#footer  -->

</div><!-- /#wrapper -->
<?php wp_footer(); ?>
<?php woo_foot(); ?>
</body>
</html>