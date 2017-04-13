<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WF_ProdImpExpCsv_Admin_Screen {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_print_styles', array( $this, 'admin_scripts' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
                
                add_action('admin_footer-edit.php', array($this, 'add_products_bulk_actions'));
                add_action('load-edit.php', array($this, 'process_products_bulk_actions'));
	}

	/**
	 * Notices in admin
	 */
	public function admin_notices() {
		if ( ! function_exists( 'mb_detect_encoding' ) ) {
			echo '<div class="error"><p>' . __( 'Product CSV Import Export requires the function <code>mb_detect_encoding</code> to import and export CSV files. Please ask your hosting provider to enable this function.', 'wf_csv_import_export' ) . '</p></div>';
		}
	}

	/**
	 * Admin Menu
	 */
	public function admin_menu() {
		$page = add_submenu_page( 'woocommerce', __( 'Product Im-Ex', 'wf_csv_import_export' ), __( 'Product Im-Ex', 'wf_csv_import_export' ), apply_filters( 'woocommerce_csv_product_role', 'manage_woocommerce' ), 'wf_woocommerce_csv_im_ex', array( $this, 'output' ) );
	}
         /**
	 * Get WC Plugin path without fail on any version
	 */
        public static function hf_get_wc_path(){
                if (function_exists('WC')){
                   $wc_path =  WC()->plugin_url();
                }else{
                   $wc_path = plugins_url() . '/woocommerce'; 
                }
                return $wc_path;
        }

        /**
	 * Admin Scripts
	 */
	public function admin_scripts() {
                $wc_path = self::hf_get_wc_path();
                wp_enqueue_script('wc-enhanced-select');
		wp_enqueue_style( 'woocommerce_admin_styles',  $wc_path. '/assets/css/admin.css' );
		wp_enqueue_style( 'woocommerce-product-csv-importer', plugins_url( basename( plugin_dir_path( WF_ProdImpExpCsv_FILE ) ) . '/styles/wf-style.css', basename( __FILE__ ) ), '', '1.0.0', 'screen' );
                
                    wp_enqueue_script('woocommerce-product-csv-importer', plugins_url( basename( plugin_dir_path( WF_ProdImpExpCsv_FILE ) ) . '/js/product-csv-import-export-for-woocommerce.min.js', basename( __FILE__ ) ), '', '1.0.0', 'screen');
                wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_product_csv_import_params', array('calendar_icon' => plugins_url(basename(plugin_dir_path(WF_ProdImpExpCsv_FILE)) . '/images/calendar.png', basename(__FILE__))));
        wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_product_csv_cron_params', array('pro_enable_ftp_ie' => '','pro_auto_export' => 'Disabled', 'pro_auto_import' => 'Disabled'));
                                      
    }

	/**
	 * Admin Screen output
	 */
	public function output() {
		$tab = 'import';
		if( ! empty( $_GET['tab'] ) ) {
			if( $_GET['tab'] == 'export' ) {
				$tab = 'export';
			}
			else if( $_GET['tab'] == 'review'){
				$tab='review' ;
			}
			else if ( $_GET['tab'] == 'settings' ) {
				$tab = 'settings';
			}
		}
		$plugin_name = 'productimportexport';
		include( 'wf_api_manager/html/html-wf-activation-window.php' );
		include( 'views/html-wf-admin-screen.php' );
	}
        
        /**
         * Product list page bulk export action add to action list
         * 
         */
        public function add_products_bulk_actions() {
                global $post_type, $post_status;

                if ($post_type == 'product' && $post_status != 'trash') {
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            var $downloadToCSV = $('<option>').val('download_to_csv_hf').text('<?php _e('Download Products as CSV', 'wf_csv_import_export') ?>');

                            $('select[name^="action"]').append($downloadToCSV);
                        });
                    </script>
                    <?php
                }
        }

        /**
         * Product page bulk export action
         * 
         */
        public function process_products_bulk_actions() {
                global $typenow;
                if ($typenow == 'product') {
                    // get the action list
                    $wp_list_table = _get_list_table('WP_Posts_List_Table');
                    $action = $wp_list_table->current_action();
                    if (!in_array($action, array('download_to_csv_hf'))) {
                        return;
                    }
                // security check
                check_admin_referer('bulk-posts');

                if (isset($_REQUEST['post'])) {
                    $prod_ids = array_map('absint', $_REQUEST['post']);
                }
                if (empty($prod_ids)) {
                    return;
                }
                // give an unlimited timeout if possible
                @set_time_limit(0);

                if ($action == 'download_to_csv_hf') {
                    include_once( 'exporter/class-wf-prodimpexpcsv-exporter.php' );
                    WF_ProdImpExpCsv_Exporter::do_export('product', $prod_ids);
                }
            }
        }
    
        
        
        

	/**
	 * Admin page for importing
	 */
	public function admin_import_page() {
		include( 'views/html-wf-getting-started.php' );
		include( 'views/import/html-wf-import-products.php' );
		$post_columns = include( 'exporter/data/data-wf-post-columns.php' );
		include( 'views/export/html-wf-export-products.php' );
	}

	/**
	 * Admin Page for exporting
	 */
	public function admin_export_page() {
        $post_columns = include( 'exporter/data/data-wf-post-columns.php' );
		include( 'views/export/html-wf-export-products.php' );
	}

	/**
	 * Admin Page for settings
	 */
	public function admin_settings_page() {
		include( 'views/settings/html-wf-settings-products.php' );
	}
}

new WF_ProdImpExpCsv_Admin_Screen();