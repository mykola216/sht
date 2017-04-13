<?php

	$ftp_server = '';
	$ftp_user = '';
	$ftp_password = '';
	$use_ftps = '';
	$enable_ftp_ie = '';	
	$ftp_server_path = '';	
	if(!empty($ftp_settings)){
		$ftp_server = !empty($ftp_settings[ 'pro_ftp_server' ]) ? $ftp_settings[ 'pro_ftp_server' ] : '';
		$ftp_user = !empty($ftp_settings[ 'pro_ftp_user' ]) ? $ftp_settings[ 'pro_ftp_user' ] : '';
		$ftp_password = !empty($ftp_settings[ 'pro_ftp_password' ]) ? $ftp_settings[ 'pro_ftp_password' ] : '';
		$use_ftps = !empty($ftp_settings[ 'pro_use_ftps' ]) ? $ftp_settings[ 'pro_use_ftps' ] : '';
		$enable_ftp_ie = !empty($ftp_settings[ 'pro_enable_ftp_ie' ]) ? $ftp_settings[ 'pro_enable_ftp_ie' ] : '';
		$ftp_server_path = !empty($ftp_settings[ 'pro_ftp_server_path' ]) ? $ftp_settings[ 'pro_ftp_server_path' ] : '';
		
	}

?>
<div>
	<p><?php _e( 'You can import products (in CSV format) in to the shop using any of below methods.', 'wf_csv_import_export' ); ?></p>

	<?php if ( ! empty( $upload_dir['error'] ) ) : ?>
		<div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:' , 'wf_csv_import_export'); ?></p>
		<p><strong><?php echo $upload_dir['error']; ?></strong></p></div>
	<?php else : ?>
		<form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr(wp_nonce_url($action, 'import-upload')); ?>">
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="upload"><?php _e( 'Method 1: Select a file from your computer' , 'wf_csv_import_export' ); ?></label>
						</th>
						<td>
							<input type="file" id="upload" name="import" size="25" />
							<input type="hidden" name="action" value="save" />
							<input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
							<small><?php printf( __('Maximum size: %s' ), $size ); ?></small>
						</td>
					</tr>
                                        <?php
                                            $mapping_from_db = get_option('wf_prod_csv_imp_exp_mapping');
                                            if (!empty($mapping_from_db)) {
                                                ?>
                                                <tr>
                                                    <th>
                                                        <label for="profile"><?php _e('Select a mapping file.' , 'wf_csv_import_export'); ?></label>
                                                    </th>
                                                    <td>
                                                        <select name="profile">
                                                            <option value="">--Select--</option>
                                                            <?php foreach ($mapping_from_db as $key => $value) { ?>
                                                                <option value="<?php echo $key; ?>"><?php echo $key; ?></option>

                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php } ?>
					<tr>
						<th>
							<label for="ftp"><?php _e( 'Method 2: Provide FTP Details:', 'wf_csv_import_export'); ?></label>
						</th>
						<td>
							<table class="form-table">
								<tr>
									<th>
										<label for="pro_enable_ftp_ie"><?php _e( 'Enable FTP import/export',  'wf_csv_import_export' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="pro_enable_ftp_ie" id="pro_enable_ftp_ie" class="checkbox" <?php checked( $enable_ftp_ie, 1 ); ?> />
									</td>
								</tr>
								<tr>
									<th>
										<label for="pro_ftp_server"><?php _e( 'FTP Server Host/IP', 'wf_csv_import_export' ); ?></label>
									</th>
									<td>
										<input type="text" name="pro_ftp_server" id="pro_ftp_server" placeholder="<?php _e('XXX.XXX.XXX.XXX', 'wf_csv_import_export'); ?>" value="<?php echo $ftp_server; ?>" class="input-text" />
									</td>
								</tr>
								<tr>
									<th>
										<label for="pro_ftp_user"><?php _e( 'FTP User Name', 'wf_csv_import_export' ); ?></label>
									</th>
									<td>
										<input type="text" name="pro_ftp_user" id="pro_ftp_user" placeholder="" value="<?php echo $ftp_user; ?>" class="input-text" />
									</td>
								</tr>
								<tr>
									<th>
										<label for="pro_ftp_password"><?php _e( 'FTP Password', 'wf_csv_import_export' ); ?></label>
									</th>
									<td>
										<input type="password" name="pro_ftp_password" id="pro_ftp_password" placeholder="" value="<?php echo $ftp_password; ?>" class="input-text" />
									</td>
								</tr>
								<tr>
									<th>
										<label for="pro_ftp_server_path"><?php _e( 'FTP Server Path', 'wf_csv_import_export' ); ?></label>
									</th>
									<td>
										<input type="text" name="pro_ftp_server_path" id="pro_ftp_server_path" placeholder="" value="<?php echo $ftp_server_path; ?>" class="input-text" />
									</td>
								</tr>
								
								<tr>
									<th>
										<label for="use_ftps"><?php _e( 'Use FTPS', 'wf_csv_import_export' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="pro_use_ftps" id="pro_use_ftps" class="checkbox" <?php checked( $use_ftps, 1 ); ?> />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th><label><?php _e( 'Delimiter', 'wf_csv_import_export' ); ?></label><br/></th>
						<td><input type="text" name="delimiter" placeholder="," size="2" /></td>
					</tr>
					<tr>
						<th><label><?php _e( 'Merge empty cells', 'wf_csv_import_export' ); ?></label><br/></th>
						<td><input type="checkbox" name="merge_empty_cells" placeholder="," size="2" /> <span class="description"><?php _e( 'Check this box to merge empty cells - otherwise (when merging) the empty cells will be ignored when importing things such as attributes.', 'wf_csv_import_export' ); ?></span></td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" class="button button-primary" value="<?php _e( 'Upload file and import', 'wf_csv_import_export' ); ?>" />
			</p>
		</form>
	<?php endif; ?>
</div>