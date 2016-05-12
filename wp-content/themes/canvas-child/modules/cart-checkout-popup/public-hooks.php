<?php defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', function() {
	//$url = str_replace( array(ABSPATH, '\\'), '/', __DIR__);
	$url = str_replace( get_stylesheet_directory(), '', __DIR__ );
	$url = get_stylesheet_directory_uri() . $url;

	wp_enqueue_style( 'popup-box', $url . '/css/popup-box.css' );
	wp_enqueue_script( 'js_cookie_js',  $url . '/js/js.cookie.js', array( 'jquery' ) );
	wp_enqueue_script( 'popup-box', $url . '/js/popup-box.js', array( 'js_cookie_js' ) );
	wp_localize_script( 'popup-box', 'ajax_object', array( 'ajax_url' => admin_url('admin-ajax.php') ) );
} );

add_action( 'wp_footer', function() {
	include( __DIR__ . '/partials/popup-box.php');
} );
