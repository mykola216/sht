<?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
    get_header();
    global $woo_options;
    
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
    
    $settings = array(
    'thumb_w' => 100, 
    'thumb_h' => 100, 
    'thumb_align' => 'alignleft'
    );
                    
    $settings = woo_get_dynamic_values( $settings );
    $options = get_option('sliding_child_options');
?>

<div id="homepage">
    
    <?php
    $width = (isset($woo_options['woo_featured_image_width']) && $woo_options['woo_featured_image_width']) ? $woo_options['woo_featured_image_width'].'px' : '296px';
    $height = (isset($woo_options['woo_featured_image_height']) && $woo_options['woo_featured_image_height']) ? $woo_options['woo_featured_image_height'].'px' : '200px';
    ?>

    <div class="col-full">
        <a href="/shop" class="button alt2 fr">Alle steigerhouten produkten</a>
        <?php dynamic_sidebar( 'home-ad-sliding-child' ); ?>
        <!-- <?php dynamic_sidebar( 'home-ad' ); ?> -->
    </div>

    <div class="col-full">
        <?php 
            echo do_shortcode($options['slider_shortcode']); 
        ?>
    </div>
        <ul id="subslider" class="col-full">
            <a href="<?php echo $options['subslider_url1']; ?>">
                <li class="custom">
                    <div class="img custom1"></div>
                    <?php echo $options['subslider_text1']; ?>
                </li>
            </a>
            <a href="<?php echo $options['subslider_url2']; ?>">
                <li class="custom">
                    <div class="img custom2"></div>
                    <?php echo $options['subslider_text2']; ?>
                </li>
            </a>
            <a href="<?php echo $options['subslider_url3']; ?>">
                <li class="custom">
                    <div class="img custom3"></div>
                    <?php echo $options['subslider_text3']; ?>
                </li>
            </a>
            <a href="<?php echo $options['subslider_url4']; ?>">
                <li class="custom">
                    <div class="img custom4"></div>
                    <?php echo $options['subslider_text4']; ?>
                </li>
            </a>
        </ul>

    <div class="feature-wrap">
    
        <div class="col-full">
            
            <!-- The recent products -->
            <?php include(locate_template('templates/recent_products.php')); ?>

            <!-- The best selling products -->
            <?php include(locate_template('templates/selling_products.php')); ?>

            <!-- Home page band module -->
            <div id="home-band">
                <img src="<?php echo $options['home_band_logo']; ?>" />
                <?php echo $options['home_band_text']; ?>
            </div>

            <!-- Furniture production module -->
            <?php include(locate_template('templates/furniture_production_module.php')); ?>

            <!-- ShareThis social icons -->
            <?php include(locate_template('templates/social_media.php')); ?>

            <?php dynamic_sidebar( 'home-col1' ); ?>

            <?php dynamic_sidebar( 'home-col2' ); ?>

        </div><!-- /#col-full -->
        
    </div><!--/.feature-wrap-->

</div><!-- /#homepage -->
        
<?php get_footer(); ?>