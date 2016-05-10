<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * Fired during plugin deactivation
 *
 * @link       http://w3cm.ru/plugins/w3cm-pending-order-email/
 * @since      1.0.0
 *
 * @package    W3CM_Pending_Order_Email
 * @subpackage W3CM_Pending_Order_Email/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    W3CM_Pending_Order_Email
 * @subpackage W3CM_Pending_Order_Email/includes
 * @author     Alexandr Levashov <me@w3cm.ru>
 */
class W3CM_Pending_Order_Email_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'w3cm_pending_order_email_notification' );
	}

}