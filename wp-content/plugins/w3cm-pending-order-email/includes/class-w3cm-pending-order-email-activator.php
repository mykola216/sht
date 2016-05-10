<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * Fired during plugin activation
 *
 * @link       http://w3cm.ru/plugins/w3cm-pending-order-email/
 * @since      1.0.0
 *
 * @package    W3CM_Pending_Order_Email
 * @subpackage W3CM_Pending_Order_Email/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    W3CM_Pending_Order_Email
 * @subpackage W3CM_Pending_Order_Email/includes
 * @author     Alexandr Levashov <me@w3cm.ru>
 */
class W3CM_Pending_Order_Email_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		wp_clear_scheduled_hook( 'canvas_child_order_status_pending_notification' ); //TODO: remove after plugin activation
		if ( ! wp_next_scheduled( 'w3cm_pending_order_email_notification' ) ) {
			wp_schedule_event( time(), 'hourly', 'w3cm_pending_order_email_notification' );
		}
	}

}