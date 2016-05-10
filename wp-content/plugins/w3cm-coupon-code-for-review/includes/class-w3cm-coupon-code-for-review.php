<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://w3cm.ru/plugins/w3cm-coupon-code-for-review/
 * @since      1.0.0
 *
 * @package    W3CM_Coupon_Code_For_Review
 * @subpackage W3CM_Coupon_Code_For_Review/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    W3CM_Coupon_Code_For_Review
 * @subpackage W3CM_Coupon_Code_For_Review/includes
 * @author     Alexandr Levashov <me@w3cm.ru>
 */
class W3CM_Coupon_Code_For_Review {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      W3CM_Coupon_Code_For_Review_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_id    The string used to uniquely identify this plugin.
	 */
	protected $plugin_id;

	/**
	 * The main plugin file name.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_file_name    The main plugin file name.
	 */
	protected $plugin_file_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @param    string  $plugin_file_name  The main plugin file name.
	 */
	public function __construct( $plugin_file_name ) {

		$this->plugin_file_name = $plugin_file_name;
		$this->plugin_id = 'w3cm-coupon-code-for-review';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - W3CM_Coupon_Code_For_Review_Loader. Orchestrates the hooks of the plugin.
	 * - W3CM_Coupon_Code_For_Review_i18n. Defines internationalization functionality.
	 * - W3CM_Coupon_Code_For_Review_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-w3cm-coupon-code-for-review-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-w3cm-coupon-code-for-review-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-w3cm-coupon-code-for-review-admin.php';

		$this->loader = new W3CM_Coupon_Code_For_Review_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the W3CM_Coupon_Code_For_Review_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new W3CM_Coupon_Code_For_Review_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_id() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new W3CM_Coupon_Code_For_Review_Admin( $this->get_plugin_id(), $this->get_version() );

		$this->loader->add_action( 'plugin_action_links_' . plugin_basename( $this->get_plugin_file_name() ), $plugin_admin, 'add_link_for_donations' );
		$this->loader->add_action( 'woocommerce_order_status_processing', $plugin_admin, 'schedule_the_offer_sending' );
		$this->loader->add_action( 'woocommerce_order_status_on-hold', $plugin_admin, 'schedule_the_offer_sending' );
		$this->loader->add_action( 'w3cm_coupon_code_for_review', $plugin_admin, 'send_offer' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The main plugin file name.
	 *
	 * @since     1.0.0
	 * @return    string    The main plugin file name.
	 */
	public function get_plugin_file_name() {
		return $this->plugin_file_name;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_id() {
		return $this->plugin_id;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    W3CM_Coupon_Code_For_Review_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
