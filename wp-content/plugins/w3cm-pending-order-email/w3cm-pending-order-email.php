<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * @link              http://w3cm.ru/plugins/w3cm-pending-order-email/
 * @since             1.0.0
 * @package           W3CM_Pending_Order_Email
 *
 * @wordpress-plugin
 * Plugin Name:       W3CM - Pending Order E-Mail
 * Plugin URI:        http://w3cm.ru/plugins/w3cm-pending-order-email/
 * Description:       Pending order notification emails are sent if a customer did not pay an order within 60 minutes.
 * Version:           1.0.0
 * Author:            Alexandr Levashov
 * Author URI:        http://w3cm.ru/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       w3cm-pending-order-email
 * Domain Path:       /languages
 */

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-w3cm-pending-order-email-activator.php
 */
register_activation_hook( __FILE__, function() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-w3cm-pending-order-email-activator.php';
	W3CM_Pending_Order_Email_Activator::activate();

} );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-w3cm-pending-order-email-deactivator.php
 */
register_deactivation_hook( __FILE__, function() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-w3cm-pending-order-email-deactivator.php';
	W3CM_Pending_Order_Email_Deactivator::deactivate();

} );

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-w3cm-pending-order-email.php';

	/**
	 * Begins execution of the plugin.
	 */
	$plugin = new W3CM_Pending_Order_Email( __FILE__ );
	$plugin->run();
}