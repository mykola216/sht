<?php defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', function() {
	$url = str_replace( array(ABSPATH, '\\'), '/', __DIR__);

	wp_enqueue_style( 'wood-treatments', $url . '/css/wood-treatments.css' );
	wp_enqueue_script( 'wood-treatments', $url . '/js/wood-treatments.js', array('jquery-ui-selectmenu') );
} );

add_action( 'woocommerce_product_thumbnails', function() {
	if ( ! has_term( 'kunststof-design-stoelen', 'product_cat' ) ) {
		include( __DIR__ . '/partials/wood-treatments.php' );
	}
}, 20 );