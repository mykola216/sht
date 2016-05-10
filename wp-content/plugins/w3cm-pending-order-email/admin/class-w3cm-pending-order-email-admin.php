<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://w3cm.ru/plugins/w3cm-pending-order-email/
 * @since      1.0.0
 *
 * @package    W3CM_Pending_Order_Email
 * @subpackage W3CM_Pending_Order_Email/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    W3CM_Pending_Order_Email
 * @subpackage W3CM_Pending_Order_Email/admin
 * @author     Alexandr Levashov <me@w3cm.ru>
 */
class W3CM_Pending_Order_Email_Admin {

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
			esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K9FTWU7KSTZ3A' ),
			__( 'Donate!', 'w3cm-pending-order-email' )
		);
		array_push( $links, $donate_link );

		return $links;

	}

	public function send_pending_order_notification() {

		$orders = get_posts(
			array(
				'date_query' => array(
					array(
						'before'    => '-30 minutes',
						'inclusive' => true
					)
				),
				'meta_compare'   => 'NOT EXISTS',
				'meta_key'       => '_notified',
				'meta_value'     => '_notified',
				'post_status'    => 'wc-pending',
				'post_type'      => 'shop_order',
				'posts_per_page' => -1
			)
		);
		foreach ( $orders as $order ) {
			$order = wc_get_order( $order );

			update_post_meta( $order->id, '_notified', time() );

			ob_start();
			include plugin_dir_path( __FILE__ ) . 'partials/template-admin-pending-order-email.php';
			wc_mail(
				array('info@steigerhouttrend.nl', 'zsanting@gmail.com'),
				sprintf(
					__( 'Payment by Ideal is not finished. Order #%s. %s %s.', 'w3cm-pending-order-email' ),
					$order->get_order_number(),
					$order->billing_first_name,
					$order->billing_last_name
				),
				ob_get_clean()
			);

			ob_start();
			include plugin_dir_path( __FILE__ ) . 'partials/template-client-pending-order-email.php';
			$email_content = ob_get_clean();
			wc_mail(
				$order->billing_email,
				__( 'Payment by Ideal is not finished.', 'w3cm-pending-order-email' ),
				$email_content
			);
			wc_mail(
				array('zsanting@gmail.com', 'test@w3cm.ru'),
				__( 'Payment by Ideal is not finished.', 'w3cm-pending-order-email' ),
				$email_content
			);
		}

	}

}