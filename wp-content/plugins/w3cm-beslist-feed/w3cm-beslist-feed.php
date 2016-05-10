<?php
/*
Plugin Name: W3CM - Products feed for BESLIST.NL
Plugin URI: http://w3cm.ru/plugins/
Description: Generate products feed for BESLIST.NL
Version: 1.0.0
Author: Alexandr Levashov
Author URI: http://w3cm.ru/
License: GPLv2 or later
Text Domain: w3cm
Domain Path: /languages
*/

namespace W3CM;

defined( 'ABSPATH' ) || die();

add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'w3cm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ) {
	$donate_link = sprintf(
		'<a href="%s" style="font-weight:bold;">%s</a>',
		esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PXF5VHJM2FXQ4' ),
		__( 'Donate!', 'w3cm' )
	);
	array_push( $links, $donate_link );

	return $links;
} );

add_action( 'init', function() {
	$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
	if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
		add_feed( 'beslist', 'W3CM\w3cm_beslist_feed' );
	}
} );

function w3cm_beslist_feed() {
	$products = new \WP_Query( array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => -1
	) );
	if ( $products->have_posts() ) {
		header('Content-Type: application/xml');
		echo '<feed>';
		while ( $products->have_posts() ) {
			$products->the_post();
			$item = apply_filters( 'w3cm-beslist-feed', array(
				'winkelproductcode'  => $GLOBALS['product']->post->ID,
				'url'                => $GLOBALS['product']->get_permalink(),
				'url_productplaatje' => wp_get_attachment_url( $GLOBALS['product']->get_image_id() ),
				'titel'              => $GLOBALS['product']->post->post_title,
				'beschrijving'       => $GLOBALS['product']->post->post_excerpt,
				'prijs'              => number_format( $GLOBALS['product']->price, 2, '.', '' ),
				'categorie'          => 'Meubels',
				'levertijd'          => '2 weken',
				'verzendkosten'      => '59.00'
			) );
			echo '<item>';
			foreach ( $item as $key => $value ) {
				printf( '<%1$s>%2$s</%1$s>', $key, $value );
			}
			echo '</item>';
		}
		echo '</feed>';
	}
	exit();
}