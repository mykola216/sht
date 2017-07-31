<?php
/**
 * Plugin Name: W3CM - Microcart Widget
 * Plugin URI: http://w3craftsman.com
 * Description: Microcart widget plugin.
 * Version: 1.0.0
 * Author: Aleksandr Levashov
 * Author URI: http://me.w3craftsman.com
 * Text Domain: w3cm
 * License: Proprietary software
 */
namespace W3CM;


class Microcart_Widget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'w3cm-microcart',
			'W3CM - Microcart widget'
		);
	}


	public function widget( $args, $instance ) {
		global $woocommerce;

		$cart = $woocommerce->cart;

		$items = sprintf(
			_n('%d', '%d', $cart->cart_contents_count, 'woothemes'),
			$cart->cart_contents_count
		);

		$widget_content = apply_filters(
			'w3cm-microcart-widget-content',
			sprintf(
				'<a href="/winkelwagen/" class="mini-cart-icon"></a> <a href="%s" class="mini-cart-items" title="%s">%s</a>',
				$cart->get_cart_url(),
				__('View Cart', 'woocommerce'),
				$items
			)
		);

		echo apply_filters(
			'w3cm-microcart-widget',
			$args['before_widget'] . $widget_content . $args['after_widget']
		);
	}
}


add_action(
	'widgets_init',
	function() {
		register_widget( 'W3CM\Microcart_Widget' );
	}
);