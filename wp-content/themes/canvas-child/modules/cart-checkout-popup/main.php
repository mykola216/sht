<?php defined( 'ABSPATH' ) || die();

require_once( __DIR__ . '/admin-hooks.php' );

add_action( 'wp', function() {
	if ( is_page('winkelwagen') ) {
//	if ( is_page('winkelwagen') || ( is_page('afrekenen') && ! is_wc_endpoint_url('order-received') ) ) {
		require_once( __DIR__ . '/public-hooks.php' );
	}
} );
