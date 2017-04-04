<?php
/**
 * Top Products
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce_loop;

$user = wp_get_current_user();

$allowed_roles = array('editor', 'administrator', 'author');

if (! array_intersect($allowed_roles, $user->roles ) ) {
    $args['post_status'] = 'publish';
}

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = $columns;

$container_class = $container_class ? $container_class : 'recent' ;

if ( $products->have_posts() ) : ?>

	<div class="products <?php echo $container_class; ?>">

		<div class="caption">
			<h2><?php echo esc_html( $title ); ?></h2>
			<a href="<?php echo esc_attr( $href ); ?>" class="button orange-button"><?php echo esc_html( $text ); ?></a>
		</div>

		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                <?php
                if (! array_intersect($allowed_roles, $user->roles ))
                {
                    wc_get_template_part( 'content', 'product' );
                }
                else if ($products->post->post_status == 'private')
                {
                    wc_get_template( 'content-product_top.php', array(), 'templates' );
                }
                else
                {
                    wc_get_template_part( 'content', 'product' );
                }
                ?>
			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>

<?php endif;

wp_reset_postdata();