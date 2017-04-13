<div class="wrap woocommerce">
	<div class="icon32" id="icon-woocommerce-importer"><br></div>
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex') ?>" class="nav-tab <?php echo ($tab == 'import') ? 'nav-tab-active' : ''; ?>"><?php _e('Product Import / Export', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_pr_rev_csv_im_ex&tab=review') ?>" class="nav-tab <?php echo ($tab == 'review') ? 'nav-tab-active' : ''; ?>"><?php _e('Product Reviews Import / Export', 'wf_csv_import_export'); ?></a>
		<a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=settings') ?>" class="nav-tab <?php echo ($tab == 'settings') ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'wf_csv_import_export'); ?></a>
    </h2>

	<?php
		switch ($tab) {
			case "export" :
				$this->admin_export_page();
			break;
			case "settings" :
				$this->admin_settings_page();
			break;
			case "review" :
				$this->admin_review_page();
			break;
			default :
				$this->admin_import_page();
			break;
		}
	?>
</div>