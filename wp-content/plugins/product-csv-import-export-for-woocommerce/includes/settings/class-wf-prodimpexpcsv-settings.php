<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WF_ProdImpExpCsv_Settings {

	/**
	 * Product Exporter Tool
	 */
	public static function save_settings( ) {
		global $wpdb;

		$pro_ftp_server                             = ! empty( $_POST['pro_ftp_server'] ) ? $_POST['pro_ftp_server'] : '';
		$pro_ftp_user                               = ! empty( $_POST['pro_ftp_user'] ) ? $_POST['pro_ftp_user'] : '';
		$pro_ftp_password                           = ! empty( $_POST['pro_ftp_password'] ) ? $_POST['pro_ftp_password'] : '';
		$pro_use_ftps                               = ! empty( $_POST['pro_use_ftps'] ) ? true : false;
		$pro_enable_ftp_ie                          = ! empty( $_POST['pro_enable_ftp_ie'] ) ? true : false;

                $rev_ftp_server                             = ! empty( $_POST['rev_ftp_server'] ) ? $_POST['rev_ftp_server'] : '';
                $rev_ftp_user                               = ! empty( $_POST['rev_ftp_user'] ) ? $_POST['rev_ftp_user'] : '';
                $rev_ftp_password                           = ! empty( $_POST['rev_ftp_password'] ) ? $_POST['rev_ftp_password'] : '';
                $rev_use_ftps                               = ! empty( $_POST['rev_use_ftps'] ) ? true : false;
                $rev_enable_ftp_ie                          = ! empty( $_POST['rev_enable_ftp_ie'] ) ? true : false;
                

                $pro_auto_export                            = ! empty( $_POST['pro_auto_export'] ) ? $_POST['pro_auto_export'] : 'Disabled';
                $pro_auto_export_start_time                 = ! empty( $_POST['pro_auto_export_start_time'] ) ? $_POST['pro_auto_export_start_time'] : '';
                $pro_auto_export_interval                   = ! empty( $_POST['pro_auto_export_interval'] ) ? $_POST['pro_auto_export_interval'] : '';
                $pro_auto_export_profile                    = ! empty( $_POST['pro_auto_export_profile'] ) ? $_POST['pro_auto_export_profile'] : '';
                
                $pro_auto_import                            = ! empty( $_POST['pro_auto_import'] ) ? $_POST['pro_auto_import'] : 'Disabled';
                $pro_auto_import_start_time                 = ! empty( $_POST['pro_auto_import_start_time'] ) ? $_POST['pro_auto_import_start_time'] : '';
                $pro_auto_import_interval                   = ! empty( $_POST['pro_auto_import_interval'] ) ? $_POST['pro_auto_import_interval'] : '';
                $pro_auto_import_profile                        = ! empty( $_POST['pro_auto_import_profile'] ) ? $_POST['pro_auto_import_profile'] : '';
                $pro_auto_import_merge                      = ! empty( $_POST['pro_auto_import_merge'] ) ?  true : false;
                $pro_auto_import_skip                      = ! empty( $_POST['pro_auto_import_skip'] ) ?  true : false;


                $rev_auto_export                            = ! empty( $_POST['rev_auto_export'] ) ? $_POST['rev_auto_export'] : 'Disabled';
                $rev_auto_export_start_time                 = ! empty( $_POST['rev_auto_export_start_time'] ) ? $_POST['rev_auto_export_start_time'] : '';
                $rev_auto_export_interval                   = ! empty( $_POST['rev_auto_export_interval'] ) ? $_POST['rev_auto_export_interval'] : '';
                
                $rev_auto_import                            = ! empty( $_POST['rev_auto_import'] ) ? $_POST['rev_auto_import'] : 'Disabled';
                $rev_auto_import_start_time                 = ! empty( $_POST['rev_auto_import_start_time'] ) ? $_POST['rev_auto_import_start_time'] : '';
                $rev_auto_import_interval                   = ! empty( $_POST['rev_auto_import_interval'] ) ? $_POST['rev_auto_import_interval'] : '';
                $rev_auto_import_profile                    = ! empty( $_POST['rev_auto_import_profile'] ) ? $_POST['rev_auto_import_profile'] : '';
                $rev_auto_import_merge                      = ! empty( $_POST['rev_auto_import_merge'] ) ?  true : false;


		        $settings				= array();
        		$settings[ 'pro_ftp_server' ]		= $pro_ftp_server;
        		$settings[ 'pro_ftp_user' ]			= $pro_ftp_user;
        		$settings[ 'pro_ftp_password' ]		= $pro_ftp_password;
        		$settings[ 'pro_use_ftps' ]			= $pro_use_ftps;
        		$settings[ 'pro_enable_ftp_ie' ]            = $pro_enable_ftp_ie;
                
                $settings[ 'pro_auto_export' ]		= $pro_auto_export;
                $settings[ 'pro_auto_export_start_time' ]	= $pro_auto_export_start_time;
                $settings[ 'pro_auto_export_interval' ]	= $pro_auto_export_interval;
                $settings[ 'pro_auto_export_profile' ]	= $pro_auto_export_profile;
                
                $settings[ 'pro_auto_import' ]		= $pro_auto_import;
                $settings[ 'pro_auto_import_start_time' ]	= $pro_auto_import_start_time;
                $settings[ 'pro_auto_import_interval' ]	= $pro_auto_import_interval;
                $settings[ 'pro_auto_import_profile' ]	= $pro_auto_import_profile;
                $settings[ 'pro_auto_import_merge' ]	= $pro_auto_import_merge;
                $settings[ 'pro_auto_import_skip' ]	= $pro_auto_import_skip;
                
                


                $settings[ 'rev_ftp_server' ]       = $rev_ftp_server;
                $settings[ 'rev_ftp_user' ]         = $rev_ftp_user;
                $settings[ 'rev_ftp_password' ]     = $rev_ftp_password;
                $settings[ 'rev_use_ftps' ]         = $rev_use_ftps;
                $settings[ 'rev_enable_ftp_ie' ]            = $rev_enable_ftp_ie;
                
                $settings[ 'rev_auto_export' ]      = $rev_auto_export;
                $settings[ 'rev_auto_export_start_time' ]   = $rev_auto_export_start_time;
                $settings[ 'rev_auto_export_interval' ] = $rev_auto_export_interval;
                
                $settings[ 'rev_auto_import' ]      = $rev_auto_import;
                $settings[ 'rev_auto_import_start_time' ]   = $rev_auto_import_start_time;
                $settings[ 'rev_auto_import_interval' ] = $rev_auto_import_interval;
                $settings[ 'rev_auto_import_profile' ]  = $rev_auto_import_profile;
                $settings[ 'rev_auto_import_merge' ]    = $rev_auto_import_merge;
                


                $settings_db = get_option( 'woocommerce_'.WF_PROD_IMP_EXP_ID.'_settings', null );

                
                $pro_orig_export_start_inverval =  '';
                if(isset($settings_db['pro_auto_export_start_time'])&& isset($settings_db['pro_auto_export_interval'])){
                $pro_orig_export_start_inverval = $settings_db['pro_auto_export_start_time'] . $settings_db['pro_auto_export_interval'];
                }

                $rev_orig_export_start_inverval =  '';
                if(isset($settings_db['rev_auto_export_start_time'])&& isset($settings_db['rev_auto_export_interval'])){
                $rev_orig_export_start_inverval = $settings_db['rev_auto_export_start_time'] . $settings_db['rev_auto_export_interval'];
                }
                

                $pro_orig_import_start_inverval =  '';
                if(isset($settings_db['pro_auto_import_start_time'])&& isset($settings_db['pro_auto_import_interval'])){
                $pro_orig_import_start_inverval = $settings_db['pro_auto_import_start_time'] . $settings_db['pro_auto_import_interval'];
                
                }


                $rev_orig_import_start_inverval =  '';
                if(isset($settings_db['rev_auto_import_start_time'])&& isset($settings_db['rev_auto_import_interval'])){
                $rev_orig_import_start_inverval = $settings_db['rev_auto_import_start_time'] . $settings_db['rev_auto_import_interval'];
                
                }


 
		       update_option( 'woocommerce_'.WF_PROD_IMP_EXP_ID.'_settings', $settings );
                // clear scheduled export event in case export interval was changed
               
                if ($pro_orig_export_start_inverval !== $settings['pro_auto_export_start_time'] . $settings['pro_auto_export_interval']) {
                    // note this resets the next scheduled execution time to the time options were saved + the interval
                    wp_clear_scheduled_hook('wf_woocommerce_csv_im_ex_auto_export_products');
                }



        		 if ($rev_orig_export_start_inverval !== $settings['rev_auto_export_start_time'] . $settings['rev_auto_export_interval']) {
                            // note this resets the next scheduled execution time to the time options were saved + the interval
                            wp_clear_scheduled_hook('wf_pr_rev_csv_im_ex_auto_export_products');
                        }
                
                // clear scheduled import event in case import interval was changed
                if ($pro_orig_import_start_inverval !== $settings['pro_auto_import_start_time'] . $settings['pro_auto_import_interval']) {
                    // note this resets the next scheduled execution time to the time options were saved + the interval
                    wp_clear_scheduled_hook('wf_woocommerce_csv_im_ex_auto_import_products');
                }
                if ($rev_orig_import_start_inverval !== $settings['rev_auto_import_start_time'] . $settings['rev_auto_import_interval']) {
                    // note this resets the next scheduled execution time to the time options were saved + the interval
                    wp_clear_scheduled_hook('wf_pr_rev_csv_im_ex_auto_import_products');
                }

                
		wp_redirect( admin_url( '/admin.php?page='.WF_WOOCOMMERCE_CSV_IM_EX.'&tab=settings' ) );
		exit;
	}
}