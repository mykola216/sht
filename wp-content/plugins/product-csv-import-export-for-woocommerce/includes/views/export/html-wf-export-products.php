<div class="tool-box">
    <h3 class="title"><?php _e('Export Product in CSV Format:', 'wf_csv_import_export'); ?></h3>
    <p><?php _e('Export and download your products in CSV format. This file can be used to import products back into your Woocommerce shop.', 'wf_csv_import_export'); ?></p>
    <form action="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&action=export'); ?>" method="post">

        <table class="form-table">
            <tr>
                <th>
                    <label for="v_offset"><?php _e('Offset', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <input type="text" name="offset" id="v_offset" placeholder="<?php _e('0', 'wf_csv_import_export'); ?>" class="input-text" />
                    <p style="font-size: 12px"><?php _e('The number of products to skip before returning.', 'wf_csv_import_export'); ?></p>
                </td>
            </tr>            
            <tr>
                <th>
                    <label for="v_limit"><?php _e('Limit', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <input type="text" name="limit" id="v_limit" placeholder="<?php _e('Unlimited', 'wf_csv_import_export'); ?>" class="input-text" />
                    <p style="font-size: 12px"><?php _e('The number of products to return.', 'wf_csv_import_export'); ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="v_prod_categories"><?php _e('Product Categories', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <select id="v_prod_categories" name="prod_categories[]" data-placeholder="<?php _e('Any Category', 'wf_csv_import_export'); ?>" class="wc-enhanced-select" multiple="multiple">
                        <?php
                        $product_categories   = get_terms( 'product_cat', array('fields' => 'id=>name'));
                        foreach ($product_categories as $category_id => $category_name) {
                            echo '<option value="' . $category_id . '">' . $category_name . '</option>';
                        }
                        ?>
                    </select>
                                                        
                    <p style="font-size: 12px"><?php _e('Products under these categories will be exported.', 'wf_csv_import_export'); ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="v_prod_types"><?php _e('Product Types', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <select id="v_prod_types" name="prod_types[]" data-placeholder="<?php _e('All Types', 'wf_csv_import_export'); ?>" class="wc-enhanced-select" multiple="multiple">
                        <?php
                        foreach ($export_types as $type_slug => $type_name) {
                            echo '<option value="' . $type_slug . '">' .  $type_name  . '</option>';
                        }
                        ?>
                    </select>
                                                        
                    <p style="font-size: 12px"><?php _e('Products under these types will be exported.', 'wf_csv_import_export'); ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="v_sortcolumn"><?php _e('Sort Columns', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <input type="text" name="sortcolumn" id="v_sortcolumn" placeholder="<?php _e('post_parent , ID', 'wf_csv_import_export'); ?>" class="input-text" />
                    <p style="font-size: 12px"><?php _e('What columns to sort pages by, comma-separated. Accepts post_author , post_date , post_title, post_name, post_modified, menu_order, post_modified_gmt , rand , comment_count.', 'wf_csv_import_export'); ?> </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="v_delimiter"><?php _e('Delimiter', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <input type="text" name="delimiter" id="v_delimiter" placeholder="<?php _e(',', 'wf_csv_import_export'); ?>" class="input-text" />
                    <p style="font-size: 12px"><?php _e('Column seperator for exported file', 'wf_csv_import_export'); ?></p>
                </td>
            </tr>
            
            

            <?php
            
            $export_mapping_from_db = get_option('xa_prod_csv_export_mapping');
            if (!empty($export_mapping_from_db)) {
                ?>
                <tr>
                    <th>
                        <label for="export_profile"><?php _e('Select a mapping file for export.' , 'wf_csv_import_export'); ?></label>
                    </th>
                    <td>
                        <select name="export_profile">
                            <option value="">--Select--</option>
                            <?php foreach ($export_mapping_from_db as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $key; ?></option>

                            <?php } ?>
                        </select>
                    </td>
                </tr>
            <?php } ?>
            
            
            <tr>
                <th>
                    <label for="v_columns"><?php _e('Columns', 'wf_csv_import_export'); ?></label>
                </th>
            <table id="datagrid">
                
                <!-- select all boxes -->
                  <tr>
                      <td style="padding: 10px;">
                          <a href="#" id="pselectall" onclick="return false;" >Select all</a> &nbsp;/&nbsp;
                          <a href="#" id="punselectall" onclick="return false;">Unselect all</a>
                      </td>
                  </tr>
                
                <th style="text-align: left;">
                    <label for="v_columns"><?php _e('Column', 'wf_csv_import_export'); ?></label>
                </th>
                <th style="text-align: left;">
                    <label for="v_columns_name"><?php _e('Column Name', 'wf_csv_import_export'); ?></label>
                </th>
                <?php 
                $post_columns['images'] = 'Images (featured and gallery)';
                $post_columns['file_paths'] = 'Downloadable file paths';
                $post_columns['taxonomies'] = 'Taxonomies (cat/tags/shipping-class)';
                $post_columns['attributes'] = 'Attributes';
                $post_columns['meta'] = 'Meta (custom fields)';
                $post_columns['product_page_url'] = 'Product Page URL';
                if (function_exists('woocommerce_gpf_install'))
                    $post_columns['gpf'] = 'Google Product Feed fields';
                ?>
                <?php foreach ($post_columns as $pkey => $pcolumn) {
                            
                         ?>
            <tr>
                <td>
                    <input name= "columns[<?php echo $pkey; ?>]" type="checkbox" value="<?php echo $pkey; ?>" checked><?php _e('', 'wf_csv_import_export'); ?>
                    <label for="columns[<?php echo $pkey; ?>]"><?php _e($pcolumn, 'wf_csv_import_export'); ?></label>
                </td>
                <td>
                    <?php 
                    $tmpkey = $pkey;
                    if (strpos($pkey, 'yoast') === false) {
                            $tmpkey = ltrim($pkey, '_');
                        }
                    ?>
                     <input type="text" name="columns_name[<?php echo $pkey; ?>]"  value="<?php echo $tmpkey; ?>" class="input-text" />
                </td>
            </tr>
                <?php } ?>
                
            </table><br/>
            </tr>
            
            <tr>
                <th>
                    <label for="v_include_hidden_meta"><?php _e('Include hidden meta data', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="include_hidden_meta" id="v_include_hidden_meta" class="checkbox" />
                </td>
            </tr><br/><br/>
            
            
             <tr>
                <th>
                    <label for="v_new_profile"><?php _e('Save the export mapping', 'wf_csv_import_export'); ?></label>
                </th>
                <td>
                    <input type="text" name="new_profile" id="v_new_profile" class="input-text" />
                </td>
            </tr>
            
        </table>
        <p class="submit"><input type="submit" class="button button-primary" value="<?php _e('Export Products', 'wf_csv_import_export'); ?>" /></p>
    </form>
</div>