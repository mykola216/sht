<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://w3cm.ru/plugins/w3cm-facebook-product-feed/
 * @since      1.0.0
 *
 * @package    W3CM_Facebook_Product_Feed
 * @subpackage W3CM_Facebook_Product_Feed/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    W3CM_Facebook_Product_Feed
 * @subpackage W3CM_Facebook_Product_Feed/includes
 * @author     Alexandr Levashov <me@w3cm.ru>
 */
class W3CM_Facebook_Product_Feed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_id    The ID of this plugin.
	 */
	private $plugin_id;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_id  The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_id, $version ) {

		$this->plugin_id = $plugin_id;
		$this->version = $version;

	}

	/**
	 * Add 'Donate!' link to the plugin action links.
	 *
	 * @since    1.0.0
	 *
	 * @param    array  $links  The links to show on the plugins overview page.
	 * @return   array          The links to show on the plugins overview page.
	 */
	public function add_link_for_donations( $links ) {

		$donate_link = sprintf(
			'<a href="%s" style="font-weight:bold;">%s</a>',
			esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VCWZH8F84YM5W' ),
			__( 'Donate!', 'w3cm-facebook-product-feed' )
		);
		array_push( $links, $donate_link );

		return $links;

	}

	/**
	 * Add 'facebook-product-feed' feed.
	 *
	 * @since    1.0.0
	 */
	public function add_feed() {

		add_feed( 'facebook-product-feed', array( $this, 'facebook_product_feed' ) );

	}

	/**
	 * Generate 'facebook-product-feed' feed.
	 *
	 * @since    1.0.0
	 */
	public function facebook_product_feed() {
		// wp-content/plugins/woocommerce/includes/class-wc-shortcodes.php
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);
		if ( ! empty( $_REQUEST['categories'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy'      => 'product_cat',
					'terms'         => array_map( 'sanitize_title', explode( ',', $_GET['categories'] ) ),
					'field'         => 'slug',
					'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
				)
			);
		}
		$products = new WP_Query( $args );

		if ( $products->have_posts() ) {
			header('Content-Type: application/xml');
			echo '<?xml version="1.0"?>' . PHP_EOL;
			echo '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . PHP_EOL;
			echo '<channel>' . PHP_EOL;
				echo '<title><![CDATA[' . get_bloginfo( 'name' ) . ']]></title>' . PHP_EOL;
				echo '<description><![CDATA[' . get_bloginfo( 'description' ) . ']]></description>' . PHP_EOL;
				echo '<link>' . get_bloginfo( 'url' ) . '</link>' . PHP_EOL;
				while ( $products->have_posts() ) {
					$products->the_post();
					global $product;
					$item = apply_filters( 'w3cm-facebook-product-feed', array(
						'guid'              => $product->get_permalink(),
						'title'             => '<![CDATA[' . $product->post->post_title . ']]>',
						'g:id'              => $product->post->ID,
						'g:title'           => '<![CDATA[' . $product->post->post_title . ']]>',
						'g:description'     => '<![CDATA[' . $product->post->post_excerpt . ']]>',
						'g:link'            => $product->get_permalink(),
						'g:image_link'      => wp_get_attachment_url( $product->get_image_id() ),
						'g:price'           => number_format( $product->price, 2, '.', '' ) . ' ' . get_woocommerce_currency(),
						'g:brand'           => 'SteigerhoutTREND',
						'g:condition'       => 'new',
						'g:availability'    => 'in stock',
						'g:google_product_category' => 'Furniture',
					) );
					echo '<item>' . PHP_EOL;
					foreach ( $item as $key => $value ) {
						printf( '<%1$s>%2$s</%1$s>' . PHP_EOL, $key, $value );
					}
					echo '</item>' . PHP_EOL;
				}
			echo '</channel>' . PHP_EOL;
			echo '</rss>' . PHP_EOL;
		}
		exit();

	}

}
