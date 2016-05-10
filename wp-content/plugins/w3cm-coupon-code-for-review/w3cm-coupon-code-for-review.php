<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * @link              http://w3cm.ru/plugins/w3cm-coupon-code-for-review/
 * @since             1.0.0
 * @package           W3CM_Coupon_Code_For_Review
 *
 * @wordpress-plugin
 * Plugin Name:       W3CM - Coupon Code For Review
 * Plugin URI:        http://w3cm.ru/plugins/w3cm-coupon-code-for-review/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Alexandr Levashov
 * Author URI:        http://w3cm.ru/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       w3cm-coupon-code-for-review
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
	require plugin_dir_path( __FILE__ ) . 'includes/class-w3cm-coupon-code-for-review.php';

	/**
	 * Begins execution of the plugin.
	 */
	$plugin = new W3CM_Coupon_Code_For_Review( __FILE__ );
	$plugin->run();
}
