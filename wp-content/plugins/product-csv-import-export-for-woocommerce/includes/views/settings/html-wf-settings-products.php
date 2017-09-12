<?php
$settings 		= get_option( 'woocommerce_'.WF_PROD_IMP_EXP_ID.'_settings', null );

$pro_ftp_server  		= isset( $settings['pro_ftp_server'] ) ? $settings['pro_ftp_server'] : '';
$pro_ftp_user		= isset( $settings['pro_ftp_user'] ) ? $settings['pro_ftp_user'] : '';
$pro_ftp_password           = isset( $settings['pro_ftp_password'] ) ? $settings['pro_ftp_password'] : '';
$pro_use_ftps         	= isset( $settings['pro_use_ftps'] ) ? $settings['pro_use_ftps'] : '';
$pro_enable_ftp_ie         	= isset( $settings['pro_enable_ftp_ie'] ) ? $settings['pro_enable_ftp_ie'] : '';

$pro_auto_export         	= isset( $settings['pro_auto_export'] ) ? $settings['pro_auto_export'] : 'Disabled';
$pro_auto_export_start_time = isset( $settings['pro_auto_export_start_time'] ) ? $settings['pro_auto_export_start_time'] : '';
$pro_auto_export_interval   = isset( $settings['pro_auto_export_interval'] ) ? $settings['pro_auto_export_interval'] : '';
$pro_auto_export_profile    = isset( $settings['pro_auto_export_profile'] ) ? $settings['pro_auto_export_profile'] : '';

$pro_auto_import         	= isset( $settings['pro_auto_import'] ) ? $settings['pro_auto_import'] : 'Disabled';
$pro_auto_import_start_time = isset( $settings['pro_auto_import_start_time'] ) ? $settings['pro_auto_import_start_time'] : '';
$pro_auto_import_interval   = isset( $settings['pro_auto_import_interval'] ) ? $settings['pro_auto_import_interval'] : '';
$pro_auto_import_profile    = isset( $settings['pro_auto_import_profile'] ) ? $settings['pro_auto_import_profile'] : '';
$pro_auto_import_merge    = isset( $settings['pro_auto_import_merge'] ) ? $settings['pro_auto_import_merge'] : 0;
$pro_auto_import_skip    = isset( $settings['pro_auto_import_skip'] ) ? $settings['pro_auto_import_skip'] : 0;

//review

$rev_ftp_server  		= isset( $settings['rev_ftp_server'] ) ? $settings['rev_ftp_server'] : '';
$rev_ftp_user		= isset( $settings['rev_ftp_user'] ) ? $settings['rev_ftp_user'] : '';
$rev_ftp_password           = isset( $settings['rev_ftp_password'] ) ? $settings['rev_ftp_password'] : '';
$rev_use_ftps         	= isset( $settings['rev_use_ftps'] ) ? $settings['rev_use_ftps'] : '';
$rev_enable_ftp_ie         	= isset( $settings['rev_enable_ftp_ie'] ) ? $settings['rev_enable_ftp_ie'] : '';

$rev_auto_export         	= isset( $settings['rev_auto_export'] ) ? $settings['rev_auto_export'] : 'Disabled';
$rev_auto_export_start_time = isset( $settings['rev_auto_export_start_time'] ) ? $settings['rev_auto_export_start_time'] : '';
$rev_auto_export_interval   = isset( $settings['rev_auto_export_interval'] ) ? $settings['rev_auto_export_interval'] : '';

$rev_auto_import         	= isset( $settings['rev_auto_import'] ) ? $settings['rev_auto_import'] : 'Disabled';
$rev_auto_import_start_time = isset( $settings['rev_auto_import_start_time'] ) ? $settings['rev_auto_import_start_time'] : '';
$rev_auto_import_interval   = isset( $settings['rev_auto_import_interval'] ) ? $settings['rev_auto_import_interval'] : '';
$rev_auto_import_profile    = isset( $settings['rev_auto_import_profile'] ) ? $settings['rev_auto_import_profile'] : '';
$rev_auto_import_merge    = isset( $settings['rev_auto_import_merge'] ) ? $settings['rev_auto_import_merge'] : 0;



wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_product_csv_cron_params', array('pro_enable_ftp_ie' => $pro_enable_ftp_ie ,'pro_auto_export' => $pro_auto_export,'pro_auto_import' => $pro_auto_import ));
if ( $pro_scheduled_timestamp = wp_next_scheduled( 'wf_woocommerce_csv_im_ex_auto_export_products' ) ) {
	$pro_scheduled_desc = sprintf( __( 'The next export is scheduled on <code>%s</code>', 'wf_csv_import_export' ), get_date_from_gmt( date( 'Y-m-d H:i:s', $pro_scheduled_timestamp ), wc_date_format() . ' ' . wc_time_format() ) );
} else {
	$pro_scheduled_desc = __( 'There is no export scheduled.', 'wf_csv_import_export' );
}
if ( $pro_scheduled_import_timestamp = wp_next_scheduled( 'wf_woocommerce_csv_im_ex_auto_import_products' ) ) {
	$pro_scheduled_import_desc = sprintf( __( 'The next import is scheduled on <code>%s</code>', 'wf_csv_import_export' ), get_date_from_gmt( date( 'Y-m-d H:i:s', $pro_scheduled_import_timestamp ), wc_date_format() . ' ' . wc_time_format() ) );
} else {
	$pro_scheduled_import_desc = __( 'There is no import scheduled.', 'wf_csv_import_export' );
}



wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_review_csv_cron_params', array('rev_enable_ftp_ie' => $rev_enable_ftp_ie ,'rev_auto_export' => $rev_auto_export,'rev_auto_import' => $rev_auto_import ));
if ( $rev_scheduled_timestamp = wp_next_scheduled( 'wf_pr_rev_csv_im_ex_auto_export_products' ) ) {
	$rev_scheduled_desc = sprintf( __( 'The next export is scheduled on <code>%s</code>', 'wf_csv_import_export' ), get_date_from_gmt( date( 'Y-m-d H:i:s', $rev_scheduled_timestamp ), wc_date_format() . ' ' . wc_time_format() ) );
} else {
	$rev_scheduled_desc = __( 'There is no export scheduled.', 'wf_csv_import_export' );
}
if ( $rev_scheduled_import_timestamp = wp_next_scheduled( 'wf_pr_rev_csv_im_ex_auto_import_products' ) ) {
	$rev_scheduled_import_desc = sprintf( __( 'The next import is scheduled on <code>%s</code>', 'wf_csv_import_export' ), get_date_from_gmt( date( 'Y-m-d H:i:s', $rev_scheduled_import_timestamp ), wc_date_format() . ' ' . wc_time_format() ) );
} else {
	$rev_scheduled_import_desc = __( 'There is no import scheduled.', 'wf_csv_import_export' );
}


?>


<div class="tool-box">
	<form action="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&action=settings'); ?>" method="post">
		<table class="form-table">
			<tr>
				<th>
					<h3 class="title"><?php _e('FTP Settings for Import/Export Products', 'wf_csv_import_export'); ?></h3>
				</th>
			</tr>
			<tr>
				<th>
					<label for="pro_enable_ftp_ie"><?php _e( 'Enable FTP', 'wf_csv_import_export' ); ?></label>
				</th>
				<td>
					<input type="checkbox" name="pro_enable_ftp_ie" id="pro_enable_ftp_ie" class="checkbox" <?php checked( $pro_enable_ftp_ie, 1 ); ?> />
				</td>
			</tr>
			<table class="form-table" id="pro_export_section_all">
				
				<tr>
					<th>
						<label for="pro_ftp_server"><?php _e( 'FTP Server Host/IP', 'wf_csv_import_export' ); ?></label>
					</th>
					<td>
						<input type="text" name="pro_ftp_server" id="pro_ftp_server" placeholder="XXX.XXX.XXX.XXX" value="<?php echo $pro_ftp_server; ?>" class="input-text" />
					</td>
				</tr>
				<tr>
					<th>
						<label for="pro_ftp_user"><?php _e( 'FTP User Name', 'wf_csv_import_export' ); ?></label>
					</th>
					<td>
						<input type="text" name="pro_ftp_user" id="pro_ftp_user"  value="<?php echo $pro_ftp_user; ?>" class="input-text" />
					</td>
				</tr>
				<tr>
					<th>
						<label for="pro_ftp_password"><?php _e( 'FTP Password', 'wf_csv_import_export' ); ?></label>
					</th>
					<td>
						<input type="password" name="pro_ftp_password" id="pro_ftp_password"  value="<?php echo $pro_ftp_password; ?>" class="input-text" />
					</td>
				</tr>
				<tr>
					<th>
						<label for="pro_use_ftps"><?php _e( 'Use FTPS', 'wf_csv_import_export' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="pro_use_ftps" id="pro_use_ftps" class="checkbox" <?php checked( $pro_use_ftps, 1 ); ?> />
					</td>
				</tr>
				
				
				
				<tr>
					<th>
						<label for="pro_auto_export"><?php _e( 'Automatically Export Products', 'wf_csv_import_export' ); ?></label>
					</th>
					<td>
						<select class="" style="" id="pro_auto_export" name="pro_auto_export">
							<option <?php if($pro_auto_export === 'Disabled' ) echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
							<option <?php if($pro_auto_export === 'Enabled' ) echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
						</select>
					</td>
				</tr>
				<tbody class="pro_export_section">
					<tr>
						<th>
							<label for="pro_auto_export_start_time"><?php _e( 'Export Start Time', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="text" name="pro_auto_export_start_time" id="pro_auto_export_start_time"  value="<?php echo $pro_auto_export_start_time; ?>"/>
							<span class="description"><?php echo sprintf( 	__( 'Local time is <code>%s</code>.', 'wf_csv_import_export' ), date_i18n( wc_time_format() ) ) . ' ' . $pro_scheduled_desc; ?></span>
							<br/>
							<span class="description"><?php _e( '<code>Enter like 6:18pm or 12:27am</code>', 'wf_csv_import_export' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="pro_auto_export_interval"><?php _e( 'Export Interval [ Minutes ]', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="text" name="pro_auto_export_interval" id="pro_auto_export_interval"  value="<?php echo $pro_auto_export_interval; ?>"  />
						</td>
					</tr>
                                        
                                        <?php
                                        $pro_exp_mapping_from_db = get_option('xa_prod_csv_export_mapping');
                                        if (!empty($pro_exp_mapping_from_db)) {
                                            ?>
                                            <tr>
                                                <th>
                                                    <label for="pro_auto_export_profile"><?php _e('Select an export mapping file.'); ?></label>
                                                </th>
                                                <td>
                                                    <select name="pro_auto_export_profile">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($pro_exp_mapping_from_db as $key => $value) { ?>
                                                            <option value="<?php echo $key; ?>" <?php selected($key, $pro_auto_export_profile); ?>><?php echo $key; ?></option>

                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        
				</tbody>
				
				
				
				
				
				<tr>
					<th>
						<label for="pro_auto_import"><?php _e( 'Automatically Import Products', 'wf_csv_import_export' ); ?></label>
					</th>
					<td>
						<select class="" style="" id="pro_auto_import" name="pro_auto_import">
							<option <?php if($pro_auto_import === 'Disabled' ) echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
							<option <?php if($pro_auto_import === 'Enabled' ) echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
						</select>
					</td>
				</tr>
				<tbody class="pro_import_section">
					<tr>
						<th>
							<label for="pro_auto_import_start_time"><?php _e( 'Import Start Time', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="text" name="pro_auto_import_start_time" id="pro_auto_import_start_time"  value="<?php echo $pro_auto_import_start_time; ?>"/>
							<span class="description"><?php echo sprintf( 	__( 'Local time is <code>%s</code>.', 'wf_csv_import_export' ), date_i18n( wc_time_format() ) ) . ' ' . $pro_scheduled_import_desc; ?></span>
							<br/>
							<span class="description"><?php _e( '<code>Enter like 6:18pm or 12:27am</code>', 'wf_csv_import_export' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="pro_auto_import_interval"><?php _e( 'Import Interval [ Minutes ]', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="text" name="pro_auto_import_interval" id="pro_auto_export_interval"  value="<?php echo $pro_auto_import_interval; ?>"  />
						</td>
					</tr>
					<tr>
						<th>
							<label for="pro_auto_import_merge"><?php _e( 'Merge Products if exist', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="checkbox" name="pro_auto_import_merge" id="pro_auto_import_merge"  class="checkbox" <?php checked( $pro_auto_import_merge , 1 ); ?> />
						</td>
					</tr>
                                        <tr>
						<th>
							<label for="pro_auto_import_skip"><?php _e( 'Skip new product', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="checkbox" name="pro_auto_import_skip" id="pro_auto_import_skip"  class="checkbox" <?php checked( $pro_auto_import_skip , 1 ); ?> />
						</td>
					</tr>
					
					<?php
					$pro_mapping_from_db = get_option('wf_prod_csv_imp_exp_mapping');
					if (!empty($pro_mapping_from_db)) {
						?>
						<tr>
							<th>
								<label for="pro_auto_import_profile"><?php _e('Select a mapping file.'); ?></label>
							</th>
							<td>
								<select name="pro_auto_import_profile">
									<option value="">--Select--</option>
									<?php foreach ($pro_mapping_from_db as $key => $value) { ?>
									<option value="<?php echo $key; ?>" <?php selected($key, $pro_auto_import_profile); ?>><?php echo $key; ?></option>

									<?php } ?>
								</select>
							</td>
						</tr>
						<?php } ?>
						
					</tbody>        
					
				</table>    
			</table>



			<table class="form-table">
				<tr>
					<th>
						<h3 class="title"><?php _e('FTP Settings for Import/Export Reviews', 'wf_csv_import_export'); ?></h3>
					</th>
				</tr>
				<tr>
					<th>
						<label for="rev_enable_ftp_ie"><?php _e( 'Enable FTP', 'wf_csv_import_export' ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="rev_enable_ftp_ie" id="rev_enable_ftp_ie" class="checkbox" <?php checked( $rev_enable_ftp_ie, 1 ); ?> />
					</td>
				</tr>
				<table class="form-table" id="rev_export_section_all">
					
					<tr>
						<th>
							<label for="rev_ftp_server"><?php _e( 'FTP Server Host/IP', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="text" name="rev_ftp_server" id="rev_ftp_server" placeholder="XXX.XXX.XXX.XXX" value="<?php echo $rev_ftp_server; ?>" class="input-text" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="rev_ftp_user"><?php _e( 'FTP User Name', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="text" name="rev_ftp_user" id="rev_ftp_user"  value="<?php echo $rev_ftp_user; ?>" class="input-text" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="rev_ftp_password"><?php _e( 'FTP Password', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="password" name="rev_ftp_password" id="rev_ftp_password"  value="<?php echo $rev_ftp_password; ?>" class="input-text" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="rev_use_ftps"><?php _e( 'Use FTPS', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="checkbox" name="rev_use_ftps" id="rev_use_ftps" class="checkbox" <?php checked( $rev_use_ftps, 1 ); ?> />
						</td>
					</tr>
					
					
					
					<tr>
						<th>
							<label for="rev_auto_export"><?php _e( 'Automatically Export Reviews', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<select class="" style="" id="rev_auto_export" name="rev_auto_export">
								<option <?php if($rev_auto_export === 'Disabled' ) echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
								<option <?php if($rev_auto_export === 'Enabled' ) echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
							</select>
						</td>
					</tr>
					<tbody class="rev_export_section">
						<tr>
							<th>
								<label for="rev_auto_export_start_time"><?php _e( 'Export Start Time', 'wf_csv_import_export' ); ?></label>
							</th>
							<td>
								<input type="text" name="rev_auto_export_start_time" id="rev_auto_export_start_time"  value="<?php echo $rev_auto_export_start_time; ?>"/>
								<span class="description"><?php echo sprintf( 	__( 'Local time is <code>%s</code>.', 'wf_csv_import_export' ), date_i18n( wc_time_format() ) ) . ' ' . $rev_scheduled_desc; ?></span>
								<br/>
								<span class="description"><?php _e( '<code>Enter like 6:18pm or 12:27am</code>', 'wf_csv_import_export' ); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="rev_auto_export_interval"><?php _e( 'Export Interval [ Minutes ]', 'wf_csv_import_export' ); ?></label>
							</th>
							<td>
								<input type="text" name="rev_auto_export_interval" id="rev_auto_export_interval"  value="<?php echo $rev_auto_export_interval; ?>"  />
							</td>
						</tr>
					</tbody>
					
					
					
					
					
					<tr>
						<th>
							<label for="rev_auto_import"><?php _e( 'Automatically Import Reviews', 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<select class="" style="" id="rev_auto_import" name="rev_auto_import">
								<option <?php if($rev_auto_import === 'Disabled' ) echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
								<option <?php if($rev_auto_import === 'Enabled' ) echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
							</select>
						</td>
					</tr>
					<tbody class="rev_import_section">
						<tr>
							<th>
								<label for="rev_auto_import_start_time"><?php _e( 'Import Start Time', 'wf_csv_import_export' ); ?></label>
							</th>
							<td>
								<input type="text" name="rev_auto_import_start_time" id="rev_auto_import_start_time"  value="<?php echo $rev_auto_import_start_time; ?>"/>
								<span class="description"><?php echo sprintf( 	__( 'Local time is <code>%s</code>.', 'wf_csv_import_export' ), date_i18n( wc_time_format() ) ) . ' ' . $rev_scheduled_import_desc; ?></span>
								<br/>
								<span class="description"><?php _e( '<code>Enter like 6:18pm or 12:27am</code>', 'wf_csv_import_export' ); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="rev_auto_import_interval"><?php _e( 'Import Interval [ Minutes ]', 'wf_csv_import_export' ); ?></label>
							</th>
							<td>
								<input type="text" name="rev_auto_import_interval" id="rev_auto_export_interval"  value="<?php echo $rev_auto_import_interval; ?>"  />
							</td>
						</tr>
						<tr>
							<th>
								<label for="rev_auto_import_merge"><?php _e( 'Merge Reviews if exist', 'wf_csv_import_export' ); ?></label>
							</th>
							<td>
								<input type="checkbox" name="rev_auto_import_merge" id="rev_auto_import_merge"  class="checkbox" <?php checked( $rev_auto_import_merge , 1 ); ?> />
							</td>
						</tr>
						
						<?php
						$rev_mapping_from_db = get_option('wf_prod_review_csv_imp_exp_mapping');
						if (!empty($rev_mapping_from_db)) {
							?>
							<tr>
								<th>
									<label for="rev_auto_import_profile"><?php _e('Select a mapping file.'); ?></label>
								</th>
								<td>
									<select name="rev_auto_import_profile">
										<option value="">--Select--</option>
										<?php foreach ($rev_mapping_from_db as $key => $value) { ?>
										<option value="<?php echo $key; ?>" <?php selected($key, $rev_auto_import_profile); ?>><?php echo $key; ?></option>

										<?php } ?>
									</select>
								</td>
							</tr>
							<?php } ?>
							
						</tbody>        
						
					</table>      
				</table>
				<p class="submit"><input type="submit" class="button button-primary" value="<?php _e('Save Settings', 'wf_csv_import_export'); ?>" /></p>

			</form>
		</div>
