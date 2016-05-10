<?php defined( 'ABSPATH' ) || die();

$dependency = array(
	'woocommerce/woocommerce.php',
	'yith-woocommerce-wishlist-x/init.php'
);
if ( ! array_diff( $dependency, get_option('active_plugins') ) ) {
	require_once( __DIR__ . '/public-hooks.php' );
}
