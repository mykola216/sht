<?php defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', function() {
	//$url = str_replace( array(ABSPATH, '\\'), '/', __DIR__);
	$url = str_replace( get_stylesheet_directory(), '', __DIR__);
	$url = get_stylesheet_directory_uri() . $url;

	wp_enqueue_style( 'wood-treatments', $url . '/css/wood-treatments.css' );
	wp_enqueue_script( 'wood-treatments', $url . '/js/wood-treatments.js', array('jquery-ui-selectmenu') );
} );

add_action( 'woocommerce_after_single_product_thumbnail_loop', function($image_class) {
	if ( ! has_term( 'kunststof-design-stoelen', 'product_cat' ) ) {
		echo esc_attr( $image_class );
		include( __DIR__ . '/partials/wood-treatments.php' );
	}
}, 20 );