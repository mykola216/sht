<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WF_PrRevImpExpCsv_Exporter {

	/**
	 * Product Reviews Exporter Tool
	 */
	public static function do_export( $pr_rev_ids = array() ) {
		global $wpdb;

		if ( ! function_exists( 'get_current_screen' ) ){
			require_once(ABSPATH . 'wp-admin/includes/screen.php');
		}
		if(!empty($pr_rev_ids)){
			$selected_pr_rev_ids = implode(', ', $pr_rev_ids);
		}else{
			$selected_pr_rev_ids = '';
		}

		$export_reply				 = ! empty( $_POST['v_replycolumn'] ) ? '1' : '' ; 

		$export_limit                = ! empty( $_POST['limit'] ) ? intval( $_POST['limit'] ) : '';
		$delimiter                   = ! empty( $_POST['delimiter'] ) ? $_POST['delimiter']  : ',';
		$stars           			 = ! empty( $_POST['stars'] ) ? $_POST['stars'] : '';
		$owner           			 = ! empty( $_POST['owner'] ) ? $_POST['owner'] : '';
		$products           		 = ! empty( $_POST['products'] ) ? $_POST['products'] : '';
		$limit 						 = ! empty($limit) ? $limit : '';

		if ( $limit > $export_limit )
			$limit = $export_limit;

		$pr_rev_date_from        	 = ! empty( $_POST['pr_rev_date_from'] ) ? $_POST['pr_rev_date_from']  : date('Y-m-d 00:00', 0) ;
		$pr_rev_date_to          	 = ! empty( $_POST['pr_rev_date_to'] ) ? $_POST['pr_rev_date_to']  : date('Y-m-d 23:59', current_time('timestamp'));

		$csv_columns                 = include( 'data/data-wf-post-columns-review.php' );
		$user_columns_name           = ! empty( $_POST['columns_name'] ) ? $_POST['columns_name'] : $csv_columns;
		$export_columns              = ! empty( $_POST['columns'] ) ? $_POST['columns'] : '';

		if ( $limit > $export_limit )
			$limit = $export_limit;
		
		$settings 				= get_option( 'woocommerce_'.WF_PROD_IMP_EXP_ID.'_settings', null );
		$ftp_server  			= isset( $settings['rev_ftp_server'] ) ? $settings['rev_ftp_server'] : '';
		$ftp_user				= isset( $settings['rev_ftp_user'] ) ? $settings['rev_ftp_user'] : '';
		$ftp_password           = isset( $settings['rev_ftp_password'] ) ? $settings['rev_ftp_password'] : '';
		$use_ftps         		= isset( $settings['rev_use_ftps'] ) ? $settings['rev_use_ftps'] : '';
		$enable_ftp_ie         	= isset( $settings['rev_enable_ftp_ie'] ) ? $settings['rev_enable_ftp_ie'] : '';
		
		$wpdb->hide_errors();
		@set_time_limit(0);
		if ( function_exists( 'apache_setenv' ) )
			@apache_setenv( 'no-gzip', 1 );
		@ini_set('zlib.output_compression', 0);
		@ob_clean();
		
		if( $enable_ftp_ie ) {
			$file = "woocommerce-product-reviews-export-".date( 'Y_m_d_H_i_s', current_time( 'timestamp' ) ).".csv";
			$fp = fopen( $file, 'w' ); 
		}
		else {
			header( 'Content-Type: text/csv; charset=UTF-8' );
			header( 'Content-Disposition: attachment; filename=woocommerce-product-reviews-export-'.date( 'Y_m_d_H_i_s', current_time( 'timestamp' ) ).'.csv' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );
			
			$fp = fopen('php://output', 'w');
		}

   		// Headers
		$all_meta_keys    = array('rating','verified' ,'title');



		$found_coupon_meta = array();
		// Some of the values may not be usable (e.g. arrays of arrays) but the worse
        // that can happen is we get an empty column.
		foreach ( $all_meta_keys as $meta ) {
			if ( ! $meta ) continue;
			if ( ! in_array( $meta, array_keys( $csv_columns ) ) && substr( (string)$meta, 0, 1 ) == '_' )
				continue;
			if ( in_array( $meta, array_keys( $csv_columns ) ) )
				continue;
			$found_coupon_meta[] = $meta;
		}

		$found_coupon_meta = array_diff( $found_coupon_meta, array_keys( $csv_columns ) );

		// Variable to hold the CSV data we're exporting
		$row = array();

		

		// Export header rows
		foreach ( $csv_columns as $column => $value ) {

			$temp_head =    esc_attr( $user_columns_name[$column] );
			if (strpos($temp_head, 'yoast') === false) {
				$temp_head = ltrim($temp_head, '_');
			}
			if ( ! $export_columns || in_array( $column, $export_columns ) ) $row[] = $temp_head;
		}

		

		

		if ( ! $export_columns || in_array( 'meta', $export_columns ) ) {
			foreach ( $found_coupon_meta as $product_meta ) {
				$row[] = 'meta:' . self::format_data( $product_meta );
			}
		}

		
		$row = array_map( 'WF_PrRevImpExpCsv_Exporter::wrap_column', $row );
		fwrite( $fp, implode( $delimiter, $row ) . "\n" );
		unset( $row );
		$args = apply_filters( 'product_reviews_csv_product_export_args', array(
			'status' => 'all',
			'orderby' => 'comment_ID',
			'order' => 'ASC',
			'post_type' => 'product',
			'meta_key' => 'rating',
			'number' => $export_limit,
			'date_query' => array(
				array(
					'before' => $pr_rev_date_to,
					'after' => $pr_rev_date_from,
					'inclusive' => true,
					),
				),

			));
		
		if ( !empty($selected_pr_rev_ids) ) {
			$args['comment__in'] = $selected_pr_rev_ids;
		}
		if(!empty($products))
		{
			$args['post__in'] = implode(',', $products);
		}
		
		if(!empty( $stars ))
		{
			$args['meta_query'] = array(array('key'=>'rating','value'=>$stars));
		}
		
		if(!empty( $owner ))
		{
			if($owner == 'verified'){
				$args['meta_query'] = array(
					array('key'=>'verified',
						'value'=>1));
			}
			if($owner == 'non-verified'){
				$args['meta_query'] = array(array('key'=>'verified','value'=>0));
			}
		}

		
		global $wpdb;
		

		$comments_query = new WP_Comment_Query;
		$comments = $comments_query->query( $args );
		
		foreach($comments as $comment)
		{
			self::hf_import_to_csv($comment,$csv_columns,$export_columns,$delimiter,$fp,$comments);

			if($export_reply === '1')
			{
				$sub_reply = get_comments(array('parent' => $comment->comment_ID));
				if(!empty($sub_reply)){


					foreach ($sub_reply as $reply) {

						self::hf_import_to_csv($reply,$csv_columns,$export_columns,$delimiter,$fp,$sub_reply);

					}

				}
			}
			
		}
		if( $enable_ftp_ie ) {
			if( $use_ftps ) {
				$ftp_conn = ftp_ssl_connect($ftp_server) or die("Could not connect to $ftp_server");
			}
			else {
				$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
			}
			$login = ftp_login($ftp_conn, $ftp_user, $ftp_password);
			ftp_pasv($ftp_conn, TRUE);
			// upload file
			if (ftp_put($ftp_conn, $file, $file, FTP_ASCII)) {
				$wf_product_review_ie_msg = 1;
				wp_redirect( admin_url( '/admin.php?page=wf_pr_rev_csv_im_ex&wf_product_review_ie_msg='.$wf_product_review_ie_msg ) );
			}
			else {
				$wf_product_review_ie_msg = 2;
				wp_redirect( admin_url( '/admin.php?page=wf_pr_rev_csv_im_ex&wf_product_review_ie_msg='.$wf_product_review_ie_msg ) );
			}

			// close connection
			ftp_close($ftp_conn);
		}

		fclose( $fp );
		exit;
	}

	public static function hf_import_to_csv($comment,$csv_columns,$export_columns,$delimiter,$fp,$comments)
	{
		$row = array();
		$comment_ID = $comment->comment_ID;
		$obj  = new WF_PrRevImpExpCsv_Exporter();
		$meta_data = $obj->get_all_meta_data( $comment_ID );

		$comment->meta = new stdClass;
		$comment->meta->rating = get_comment_meta( $comment_ID, 'rating', true );
		$comment->meta->verified = get_comment_meta( $comment_ID, 'verified', true );
		$comment->meta->title = get_comment_meta( $comment_ID, 'title', true );


			// Meta data
		foreach ( $meta_data as $meta => $value ) 
		{
			if ( ! $meta ) {
				continue;
			}
			if ( ! in_array( $meta, array_keys( $csv_columns ) ) && substr( $meta, 0, 1 ) == '_' ) {
				continue;
			}


			$meta_value = maybe_unserialize( maybe_unserialize( $value ) );

			if ( is_array( $meta_value ) ) {
				$meta_value = json_encode( $meta_value );
			}

			$comment->meta->$meta = self::format_export_meta( $meta_value, $meta );
		}

		foreach ( $csv_columns as $column => $value ) {
			if ( ! $export_columns || in_array( $column, $export_columns ) ) {
				if ($column === 'comment_alter_id') {
					$row[] = self::format_data($comment_ID);
				}

				if ( isset( $comment->meta->$column ) ) {
					$row[] = self::format_data( $comment->meta->$column );
				} elseif ( isset( $comment->$column ) && ! is_array( $comments[0]->$column ) ) {
					if ( $column === 'post_title' ) {
						$row[] = sanitize_text_field( $comment->$column );
					} else {
						$row[] = self::format_data( $comment->$column );
					}
				} else {
					$row[] = '';
				}
			}
		}



		if ( ! $export_columns || in_array( 'meta', $export_columns ) ) 
		{
			if(!empty($found_coupon_meta)){
				foreach ( $found_coupon_meta as $product_meta ) 
				{
					if ( isset( $comment->meta->$product_meta ) ) 
					{
						$row[] = self::format_data( $comment->meta->$product_meta );
					} else {
						$row[] = '';
					}
				}
			}
		}
		$row = array_map( 'WF_PrRevImpExpCsv_Exporter::wrap_column', $row );
		fwrite( $fp, implode( $delimiter, $row ) . "\n" );
		unset( $row );


	}

	/**
	 * Format the data if required
	 * @param  string $meta_value
	 * @param  string $meta name of meta key
	 * @return string
	 */
	public static function format_export_meta( $meta_value, $meta ) {
		switch ( $meta ) {
			case '_sale_price_dates_from' :
			case '_sale_price_dates_to' :
			return $meta_value ? date( 'Y-m-d', $meta_value ) : '';
			break;
			case '_upsell_ids' :
			case '_crosssell_ids' :
			return implode( '|', array_filter( (array) json_decode( $meta_value ) ) );
			break;
			default :
			return $meta_value;
			break;
		}
	}

	public static function format_data( $data ) 
	{
		if(!is_array($data));
		$data = (string) urldecode( $data );
		$enc  = mb_detect_encoding( $data, 'UTF-8, ISO-8859-1', true );
		$data = ( $enc == 'UTF-8' ) ? $data : utf8_encode( $data );
		return $data;
	}

	/**
	 * Wrap a column in quotes for the CSV
	 * @param  string data to wrap
	 * @return string wrapped data
	 */
	public static function wrap_column( $data ) {
		return '"' . str_replace( '"', '""', $data ) . '"';
	}



	public static function get_all_meta_data($id)
	{
		$meta_data = array();
		$meta_data[] = array('key'=>'rating',
			'value'=>get_comment_meta( $id, 'rating', true ));
		$meta_data[] = array('key'=>'verified',
			'value'=>get_comment_meta( $id, 'verified', true ));
		return $meta_data;
	}
}

