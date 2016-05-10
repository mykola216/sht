<div id="recent-products">
    <a href="<?php echo $options['recent_products_url']; ?>" class="button alt2 fr">Nieuwe steigerhouten meubelen</a>
    <h2 class="widget-title"><?php _e($options['recent_products_title'], 'woothemes'); ?></h2>
    <div class="carousel">
    <ul class="recent-products carousel-products">
            
    <?php
    $recent_products_per_page = 3;
    // $recent_products_per_page = get_option('woo_homepage_recent_products_number');
    $args = array( 'post_type' => 'product', 'posts_per_page' => $recent_products_per_page, 'meta_key' => '_featured', 'meta_value' => 'no' );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post(); $_product = new WC_Product( $loop->post->ID ); ?>
            
            <li>
                
                <?php // woocommerce_show_product_sale_flash( $post, $_product ); ?>
                <div class="img-wrap">
                <a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php // echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                    <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_single'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder"/>'; ?>
                </a>
                </div>
                
                <h3>
                    <a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php // echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
                        <?php the_title(); ?> 
                    </a>
                </h3>
                <span class="carousel-price"><?php echo $_product->get_price_html(); ?></span>
                <div class="carousel-excerpt">
                    <?php echo $post->post_excerpt; ?>
                </div>
                <?php // woocommerce_template_loop_add_to_cart( $loop->post, $_product ); ?>
                
            </li>
        
    <?php endwhile; ?>

    </ul>
<!--     <div style="clear: both;">
        <a href="/recent-products/" class="button fr">
            Nieuwe steigerhouten meubelen
        </a>
    </div> -->
    </div><!--/.carousel-->
    
</div>