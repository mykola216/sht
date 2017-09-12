<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WF_ProdImpExpCsv_AJAX_Handler {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_woocommerce_csv_import_request', array( $this, 'csv_import_request' ) );
		add_action( 'wp_ajax_woocommerce_csv_import_regenerate_thumbnail', array( $this, 'regenerate_thumbnail' ) );
                add_action( 'wp_ajax_product_csv_export_mapping_change', array( $this, 'export_mapping_change_columns' ) );
	}
	
	/**
	 * Ajax event for importing a CSV
	 */
	public function csv_import_request() {
		define( 'WP_LOAD_IMPORTERS', true );
                WF_ProdImpExpCsv_Importer::product_importer();
	}

	/**
	 * From regenerate thumbnails plugin
	 */
	public function regenerate_thumbnail() {
		@error_reporting( 0 ); // Don't break the JSON result

		header( 'Content-type: application/json' );

		$id    = (int) $_REQUEST['id'];
		$image = get_post( $id );

		if ( ! $image || 'attachment' != $image->post_type || 'image/' != substr( $image->post_mime_type, 0, 6 ) )
			die( json_encode( array( 'error' => sprintf( __( 'Failed resize: %s is an invalid image ID.', 'wf_csv_import_export' ), esc_html( $_REQUEST['id'] ) ) ) ) );

		if ( ! current_user_can( 'manage_woocommerce' ) )
			$this->die_json_error_msg( $image->ID, __( "Your user account doesn't have permission to resize images", 'wf_csv_import_export' ) );

		$fullsizepath = get_attached_file( $image->ID );

		if ( false === $fullsizepath || ! file_exists( $fullsizepath ) )
			$this->die_json_error_msg( $image->ID, sprintf( __( 'The originally uploaded image file cannot be found at %s', 'wf_csv_import_export' ), '<code>' . esc_html( $fullsizepath ) . '</code>' ) );

		@set_time_limit( 900 ); // 5 minutes per image should be PLENTY

		$metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );

		if ( is_wp_error( $metadata ) )
			$this->die_json_error_msg( $image->ID, $metadata->get_error_message() );
		if ( empty( $metadata ) )
			$this->die_json_error_msg( $image->ID, __( 'Unknown failure reason.', 'wf_csv_import_export' ) );

		// If this fails, then it just means that nothing was changed (old value == new value)
		wp_update_attachment_metadata( $image->ID, $metadata );

		die( json_encode( array( 'success' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) was successfully resized in %3$s seconds.', 'wf_csv_import_export' ), esc_html( get_the_title( $image->ID ) ), $image->ID, timer_stop() ) ) ) );
	}	

	/**
	 * Die with a JSON formatted error message
	 */
	public function die_json_error_msg( $id, $message ) {
        die( json_encode( array( 'error' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) failed to resize. The error message was: %3$s', 'regenerate-thumbnails' ), esc_html( get_the_title( $id ) ), $id, $message ) ) ) );
    }
    
                    
    /**
     * Ajax event for changing mapping of export CSV
     */
    public function export_mapping_change_columns() {

        $selected_profile = !empty($_POST['v_new_profile']) ? $_POST['v_new_profile'] : '';
        
        $post_columns = array();
        if (!$selected_profile) {
            $post_columns = include( 'exporter/data/data-wf-post-columns.php' );

            $post_columns['images'] = 'Images (featured and gallery)';
            $post_columns['file_paths'] = 'Downloadable file paths';
            $post_columns['taxonomies'] = 'Taxonomies (cat/tags/shipping-class)';
            $post_columns['attributes'] = 'Attributes';
            $post_columns['meta'] = 'Meta (custom fields)';
            $post_columns['product_page_url'] = 'Product Page URL';
            if (function_exists('woocommerce_gpf_install'))
                $post_columns['gpf'] = 'Google Product Feed fields';
        }

        $export_profile_array = get_option('xa_prod_csv_export_mapping');

        if (!empty($export_profile_array[$selected_profile])) {
            $post_columns = $export_profile_array[$selected_profile];
        }


        $res = "<tr>
                      <td style='padding: 10px;'>
                          <a href='#' id='pselectall' onclick='return false;' >Select all</a> &nbsp;/&nbsp;
                          <a href='#' id='punselectall' onclick='return false;'>Unselect all</a>
                      </td>
                  </tr>
                  
                <th style='text-align: left;'>
                    <label for='v_columns'>Column</label>
                </th>
                <th style='text-align: left;'>
                    <label for='v_columns_name'>Column Name</label>
                </th>";


        foreach ($post_columns as $pkey => $pcolumn) {

            $res.="<tr>
                <td>
                    <input name= 'columns[$pkey]' type='checkbox' value='$pkey' checked>
                    <label for='columns[$pkey]'>$pkey</label>
                </td>
                <td>";

            $res.="<input type='text' name='columns_name[$pkey]'  value='$pcolumn' class='input-text' />
                </td>
            </tr>";
        }

        echo $res;
        exit;
    }

}

new WF_ProdImpExpCsv_AJAX_Handler();