<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://w3cm.ru/plugins/w3cm-coupon-code-for-review/
 * @since      1.0.0
 *
 * @package    W3CM_Coupon_Code_For_Review
 * @subpackage W3CM_Coupon_Code_For_Review/public
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    W3CM_Coupon_Code_For_Review
 * @subpackage W3CM_Coupon_Code_For_Review/admin
 * @author     Alexandr Levashov <me@w3cm.ru>
 */
class W3CM_Coupon_Code_For_Review_Admin {

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
			esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z6JQG64M5F7QQ' ),
			__( 'Donate!', 'w3cm-coupon-code-for-review' )
		);
		array_push( $links, $donate_link );

		return $links;

	}

	/**
	 * Schedule to send the offer to get a coupon code for review
	 *
	 * @since    1.0.0
	 *
	 * @param    integer    $order_id  The order id.
	 */
	public function schedule_the_offer_sending( $order_id ) {

		wp_schedule_single_event( strtotime('+3 weeks'), 'w3cm_coupon_code_for_review', array( $order_id ) );

	}

	/**
	 * Send the offer to get a coupon code for review
	 *
	 * @since    1.0.0
	 *
	 * @param    integer    $order_id  The order id.
	 */
	public function send_offer( $order_id ) {

		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		$show_download_links = true;
		$show_sku = false;
		$show_purchase_note = true;
		$show_image = false;
		$image_size = array( 32, 32 );
		$plain_text = false;

		ob_start();
		include 'partials/w3cm-coupon-code-for-review-admin-display.php';
		$order_items_table = ob_get_clean();

		$template_post = get_page_by_path( 'w3cm-coupon-code-for-review', OBJECT );
		$content = apply_filters( 'the_content', $template_post->post_content );
		error_log($content);
		$content = str_replace( '{billing_full_name}', $order->billing_first_name . ' ' . $order->billing_last_name, $content );
		$content = str_replace( '{order_url}', $order->get_view_order_url(), $content );
		$content = str_replace( '{order_number}', $order->get_order_number(), $content );
		$content = str_replace( '{order_items_table}', $order_items_table, $content );
		error_log($content);
		$message = sprintf(
			'<!DOCTYPE html><html dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>%s</title></head><body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">%s</body></html>',
			$template_post->post_title,
			$content
		);
		error_log($message);

		wp_mail(
			WP_DEBUG ? 'test@w3cm.ru' : array( $order->billing_email, 'zsanting@gmail.com' ),
			$template_post->post_title,
			$message,
			'Content-Type: text/html'
		);
	}
}
