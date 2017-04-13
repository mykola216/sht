<?php
/*
Plugin Name: Product/Review CSV Import Export
Plugin URI: https://www.xadapter.com/product/product-import-export-plugin-for-woocommerce/
Description: Import and Export Products and Product Reviews From and To your WooCommerce Store.
Author: HikeForce
Author URI: https://www.xadapter.com/vendor/hikeforce/
Version: 3.2.1
Text Domain: wf_csv_import_export
*/

if ( ! defined( 'ABSPATH' )) {
	return;
}

define( "WF_PROD_IMP_EXP_ID", "wf_prod_imp_exp" );
define( "WF_WOOCOMMERCE_CSV_IM_EX", "wf_woocommerce_csv_im_ex" );

//review import export define Id's

define( "WF_PR_REV_IMP_EXP_ID", "wf_pr_rev_imp_exp" );
define( "WF_PR_REV_CSV_IM_EX", "wf_pr_rev_csv_im_ex" );

/**
 * Check if WooCommerce is active
 */
if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {	

	if ( ! class_exists( 'WF_Product_Import_Export_CSV' ) ) :

	/**
	 * Main CSV Import class
	 */
	class WF_Product_Import_Export_CSV {

                public $cron;
                public $cron_import;
                /**
                 * Constructor
                 */
		public function __construct() {
			define( 'WF_ProdImpExpCsv_FILE', __FILE__ );


			//to add api manager config
			include_once ( 'includes/wf_api_manager/wf-api-manager-config.php' );
			
			if ( is_admin() ) { 
				add_action('admin_notices', array( $this, 'wf_product_ie_admin_notice'), 15);
			}
		
			add_filter( 'woocommerce_screen_ids', array( $this, 'woocommerce_screen_ids' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'wf_plugin_action_links' ) );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'catch_export_request' ), 20 );
			add_action( 'init', array( $this, 'catch_save_settings' ), 20 );
			add_action( 'admin_init', array( $this, 'register_importers' ) );

			include_once( 'includes/class-wf-prodimpexpcsv-system-status-tools.php' );
			include_once( 'includes/class-wf-prodimpexpcsv-admin-screen.php' );
			include_once( 'includes/importer/class-wf-prodimpexpcsv-importer.php' );
                        
                        require_once( 'includes/class-wf-prodimpexpcsv-cron.php' );
                        $this->cron = new WF_ProdImpExpCsv_Cron();
                        register_activation_hook(__FILE__, array( $this->cron, 'wf_new_scheduled_export'));
                        register_deactivation_hook( __FILE__, array( $this->cron, 'clear_wf_scheduled_export' ) );
                        

			if ( defined('DOING_AJAX') ) {
				include_once( 'includes/class-wf-prodimpexpcsv-ajax-handler.php' );
			}
                        
                        require_once( 'includes/class-wf-prodimpexpcsv-import-cron.php' );
                        $this->cron_import = new WF_ProdImpExpCsv_ImportCron();
                        //$this->cron_import->wf_scheduled_import_products();
                        register_activation_hook(__FILE__, array( $this->cron_import, 'wf_new_scheduled_import'));
                        register_deactivation_hook( __FILE__, array( $this->cron_import, 'clear_wf_scheduled_import' ) );
                        
		}
		
		public function wf_plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=wf_woocommerce_csv_im_ex' ) . '">' . __( 'Product Import Export', 'wf_csv_import_export' ) . '</a>',
				'<a href="' . admin_url( 'admin.php?page=wf_pr_rev_csv_im_ex' ) . '">' . __( 'Review Import Export', 'wf_csv_import_export' ) . '</a>',
				'<a href="https://www.xadapter.com/category/product/product-import-export-plugin-for-woocommerce/" target="_blank">' . __('Documentation', 'wf_csv_import_export') . '</a>',
                '<a href="https://www.xadapter.com/online-support/" target="_blank">' . __('Support', 'wf_csv_import_export') . '</a>'
			);
			return array_merge( $plugin_links, $links );
		}
		
		function wf_product_ie_admin_notice() {
			global $pagenow;
			global $post;
			
			if(!isset($_GET["wf_product_ie_msg"]) && empty($_GET["wf_product_ie_msg"]) ) {
				return;
			}
		
			$wf_product_ie_msg = $_GET["wf_product_ie_msg"];
			
			switch ($wf_product_ie_msg) {
				case "1":
					echo '<div class="update"><p>'.__( 'Successfully uploaded via FTP.', 'wf_csv_import_export').'</p></div>';
					break;
				case "2":
					echo '<div class="error"><p>'.__( 'Error while uploading via FTP.', 'wf_csv_import_export').'</p></div>';
					break;
			}
		}

		/**
		 * Add screen ID
		 */
		public function woocommerce_screen_ids( $ids ) {
			$ids[] = 'admin'; // For import screen
			return $ids;
		}

		/**
		 * Handle localisation
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'wf_csv_import_export', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Catches an export request and exports the data. This class is only loaded in admin.
		 */
		public function catch_export_request() {
			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'wf_woocommerce_csv_im_ex' ) {
				switch ( $_GET['action'] ) {
					case "export" :
                                            $user_ok = $this->hf_user_permission();
                                            if ($user_ok) {
						include_once( 'includes/exporter/class-wf-prodimpexpcsv-exporter.php' );
						WF_ProdImpExpCsv_Exporter::do_export( 'product' );
                                            } else {
                                                wp_redirect(wp_login_url());
                                            }    
					break;
				}
			}
		}
		
		public function catch_save_settings() {
			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'wf_woocommerce_csv_im_ex' ) {
				switch ( $_GET['action'] ) {
					case "settings" :
						include_once( 'includes/settings/class-wf-prodimpexpcsv-settings.php' );
						WF_ProdImpExpCsv_Settings::save_settings( );
					break;
				}
			}
		}

		/**
		 * Register importers for use
		 */
		public function register_importers() {
			register_importer( 'woocommerce_csv', 'WooCommerce Products (CSV)', __('Import <strong>products</strong> to your store via a csv file.', 'wf_csv_import_export'), 'WF_ProdImpExpCsv_Importer::product_importer' );
                        register_importer( 'woocommerce_csv_cron', 'WooCommerce Products (CSV)', __('Cron Import <strong>products</strong> to your store via a csv file.', 'wf_csv_import_export'), 'WF_ProdImpExpCsv_ImportCron::product_importer' );
		}
                
                private function hf_user_permission() {
                        // Check if user has rights to export
                        $current_user = wp_get_current_user();
                        $user_ok = false;
                        $wf_roles = apply_filters('hf_user_permission_roles', array('administrator', 'shop_manager'));
                        if ($current_user instanceof WP_User) {
                            $can_users = array_intersect($wf_roles, $current_user->roles);
                            if (!empty($can_users)) {
                                $user_ok = true;
                            }
                        }
                        return $user_ok;
                }
	}
	endif;

	new WF_Product_Import_Export_CSV();

}




if ( ! class_exists( 'WF_Product_Review_Import_Export_CSV' ) ) :

	/**
	 * Main CSV Import class
	 */
	class WF_Product_Review_Import_Export_CSV {

                public $cron;
                public $cron_import;
                /**
                 * Constructor
                 */
		public function __construct() {
			define( 'WF_PrRevImpExpCsv_FILE', __FILE__ );
			
			if ( is_admin() ) { 
				add_action('admin_notices', array( $this, 'wf_product_review_ie_admin_notice'), 15);
			}
		
			add_filter( 'woocommerce_screen_ids', array( $this, 'woocommerce_screen_ids' ) );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'catch_export_request' ), 20 );
			add_action( 'init', array( $this, 'catch_save_settings' ), 20 );
			add_action( 'admin_init', array( $this, 'register_importers' ) );

			include_once( 'includes/class-wf-pr_revimpexpcsv-system-status-tools.php' );
			include_once( 'includes/class-wf-pr_revimpexpcsv-admin-screen.php' );
			include_once( 'includes/importer/class-wf-pr_revimpexpcsv-importer.php' );
                        
            require_once( 'includes/class-wf-pr_revimpexpcsv-cron.php' );
            $this->cron = new WF_PrRevImpExpCsv_Cron();
            register_activation_hook(__FILE__, array( $this->cron, 'wf_new_scheduled_pr_rev_export'));
            register_deactivation_hook( __FILE__, array( $this->cron, 'clear_wf_scheduled_pr_rev_export' ) );
                        

			if ( defined('DOING_AJAX') ) {
				include_once( 'includes/class-wf-pr_revimpexpcsv-ajax-handler.php' );
			}
                        
            require_once( 'includes/class-wf-pr_revimpexpcsv-import-cron.php' );
            $this->cron_import = new WF_PrRevImpExpCsv_ImportCron();
            register_activation_hook(__FILE__, array( $this->cron_import, 'wf_new_scheduled_pr_rev_import'));
            register_deactivation_hook( __FILE__, array( $this->cron_import, 'clear_wf_scheduled_pr_rev_import' ) );
                        
		}
		
		function wf_product_review_ie_admin_notice() {
			global $pagenow;
			global $post;
			
			if(!isset($_GET["wf_product_review_ie_msg"]) && empty($_GET["wf_product_review_ie_msg"]) ) {
				return;
			}
		
			$wf_product_review_ie_msg = $_GET["wf_product_review_ie_msg"];
			
			switch ($wf_product_review_ie_msg) {
				case "1":
					echo '<div class="update"><p>'.__( 'Successfully uploaded via FTP.', 'wf_csv_import_export').'</p></div>';
					break;
				case "2":
					echo '<div class="error"><p>'.__( 'Error while uploading via FTP.', 'wf_csv_import_export').'</p></div>';
					break;
				case "3":
					echo '<div class="error"><p>'.__( 'Please choose the file in CSV format either using Method 1 or Method 2.', 'wf_csv_import_export').'</p></div>';
					break;
			}
		}

		/**
		 * Add screen ID
		 */
		public function woocommerce_screen_ids( $ids ) {
			$ids[] = 'admin'; // For import screen
			return $ids;
		}

		/**
		 * Handle localisation
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'wf_csv_import_export', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Catches an export request and exports the data. This class is only loaded in admin.
		 */
		public function catch_export_request() {
			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'wf_pr_rev_csv_im_ex' ) {
				switch ( $_GET['action'] ) {
					case "export" :
                                            $user_ok = $this->hf_user_permission();
                                            if ($user_ok) {
						include_once( 'includes/exporter/class-wf-pr_revimpexpcsv-exporter.php' );
						WF_PrRevImpExpCsv_Exporter::do_export( );
                                            } else {
                                                wp_redirect(wp_login_url());
                                            }    
					break;
				}
			}
		}
		
		public function catch_save_settings() {
			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'wf_pr_rev_csv_im_ex' ) {
				switch ( $_GET['action'] ) {
					case "settings" :
						include_once( 'includes/settings/class-wf-prodimpexpcsv-settings.php' );
						WF_ProdImpExpCsv_Settings::save_settings( );
					break;
				}
			}
		}

		/**
		 * Register importers for use
		 */
		public function register_importers() {
			register_importer( 'product_reviews_csv', 'WooCommerce Product Reviews (CSV)', __('Import <strong>product reviews</strong> to your store via a csv file.', 'wf_csv_import_export'), 'WF_PrRevImpExpCsv_Importer::product_importer' );
                        register_importer( 'product_reviews_csv_cron', 'WooCommerce Product Reviews (CSV)', __('Cron Import <strong>product reviews</strong> to your store via a csv file.', 'wf_csv_import_export'), 'WF_PrRevImpExpCsv_ImportCron::product_importer' );
		}
                
                private function hf_user_permission() {
                        // Check if user has rights to export
                        $current_user = wp_get_current_user();
                        $user_ok = false;
                        $wf_roles = apply_filters('hf_user_permission_roles', array('administrator', 'shop_manager'));
                        if ($current_user instanceof WP_User) {
                            $can_users = array_intersect($wf_roles, $current_user->roles);
                            if (!empty($can_users)) {
                                $user_ok = true;
                            }
                        }
                        return $user_ok;
                }
	}
	endif;

	new WF_Product_Review_Import_Export_CSV();
