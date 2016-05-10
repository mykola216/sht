<?php
/**
 * Template Name: Recent products
 */

get_header('shop'); 

/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');

printf( '<h1 class="page-title">%s</h1>', __( 'New scaffold wood furniture', 'woothemes' ) );

$args = apply_filters( 'canvas_child_recent_products_args', array(
	'ignore_sticky_posts' => 1,
	'meta_key'            => '_featured',
	'meta_value'          => 'no',
	'order'               => 'DESC',
	'orderby'             => 'date',
	'paged'               => (get_query_var('paged')) ? get_query_var('paged') : 1,
	'post_type'           => 'product',
	'posts_per_page'      => 20,
	'stock'               => 1
));
$products = new WP_Query( $args );

if ( $products->have_posts() ) {

	echo '<ul class="products qwerty">';

	woocommerce_product_subcategories();

	while ( $products->have_posts() ) {
		$products->the_post();
		wc_get_template_part( 'content', 'product' );
	}

	echo '</ul>';

	woo_pagenav( $products );
} else {
	wc_get_template( 'loop/no-products-found.php' );
}
wp_reset_postdata();

echo '<div class="clear"></div>';

get_footer('shop');