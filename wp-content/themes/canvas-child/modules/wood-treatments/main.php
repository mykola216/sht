<?php defined( 'ABSPATH' ) || die();

//require_once( __DIR__ . '/admin-hooks.php' );

add_action( 'wp', function() {
	if ( is_single() ) {
		require_once( __DIR__ . '/public-hooks.php' );
	}
} );
