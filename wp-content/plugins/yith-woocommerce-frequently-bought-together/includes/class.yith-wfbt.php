<?php
/**
 * Main class
 *
 * @author Yithemes
 * @package YITH WooCommerce Frequently Bought Together Premium
 * @version 1.0.0
 */


if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WFBT' ) ) {
	/**
	 * YITH WooCommerce Frequently Bought Together Premium
	 *
	 * @since 1.0.0
	 */
	class YITH_WFBT {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WFBT
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Action add to cart group
		 *
		 * @var \YITH_WFBT
		 * @since 1.0.0
		 */
		public $actionadd = 'yith_bought_together';

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WFBT_VERSION;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WFBT
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @return mixed YITH_WFBT_Admin | YITH_WFBT_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			// Class admin
			if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend' ) ) {

				// Load Plugin Framework
				add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );
				// admin class
				YITH_WFBT_Admin();
			}
			else {
				// frontend class
				YITH_WFBT_Frontend();
			}

			add_action( 'wp_loaded', array( $this, 'add_group_to_cart' ), 20 );
		}

		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT' ) || ! defined( 'YIT_CORE_PLUGIN' ) ) {
				require_once( YITH_WFBT_DIR . '/plugin-fw/yit-plugin.php' );
			}

		}

		/**
		 * Add upselling group to cart
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_group_to_cart(){

			if( ! ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == $this->actionadd && wp_verify_nonce( $_REQUEST[ '_wpnonce' ], $this->actionadd ) ) ) {
				return;
			}

			if( ! isset( $_POST['offeringID'] ) ) {
				return;
			}

			$mess = array();

			foreach( $_POST['offeringID'] as $id ) {

				$product = wc_get_product( $id );

				$attr = array();
				$variation_id = '';
				$product_id = $product->id;

				if( $product->product_type == 'variation' ) {
					$attr           = $product->get_variation_attributes();
					$variation_id   = $product->variation_id;
				}

				if( WC()->cart->add_to_cart( $product_id, 1, $variation_id, $attr ) ) {
					if( version_compare( WC()->version, '2.6', '>=' ) ) {
						$mess[$product_id] = 1;
					}
					else {
						$mess[] = $product_id;
					}
				}
			}

			if( ! empty( $mess ) ) {
				wc_add_to_cart_message( $mess );
			}

			if( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
				$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : WC()->cart->get_cart_url();
				wp_safe_redirect( $cart_url );
				exit;
			}
			else {
				//redirect to product page
				$dest = remove_query_arg( array( 'action', '_wpnonce' ) );
				wp_redirect( esc_url( $dest ) );
				exit;
			}

		}
	}
}

/**
 * Unique access to instance of YITH_WFBT class
 *
 * @return \YITH_WFBT
 * @since 1.0.0
 */
function YITH_WFBT(){
	return YITH_WFBT::get_instance();
}