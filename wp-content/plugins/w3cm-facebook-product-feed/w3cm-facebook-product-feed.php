<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * @link              http://w3cm.ru/plugins/w3cm-facebook-product-feed/
 * @since             1.0.0
 * @package           W3CM_Facebook_Product_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       W3CM - Product feed for Facebook
 * Plugin URI:        http://w3cm.ru/plugins/w3cm-facebook-product-feed/
 * Description:       Generate product feed for Facebook.
 * Version:           1.0.0
 * Author:            Alexandr Levashov
 * Author URI:        http://w3cm.ru/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       w3cm-facebook-product-feed
 * Domain Path:       /languages
 */

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-w3cm-facebook-product-feed.php';

	/**
	 * Begins execution of the plugin.
	 */
	$plugin = new W3CM_Facebook_Product_Feed( __FILE__ );
	$plugin->run();
}
