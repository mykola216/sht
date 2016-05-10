<?php

global $pagenow;
if (is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php')
    add_action('init', 'woo_install_theme', 1);

function woo_install_theme()
{
    update_option('woocommerce_thumbnail_image_width', '90');
    update_option('woocommerce_thumbnail_image_height', '90');
    update_option('woocommerce_single_image_width', '300'); 
    update_option('woocommerce_single_image_height', '300'); 
    update_option('woocommerce_catalog_image_width', '150');
    update_option('woocommerce_catalog_image_height', '150');
}

// -(65668, 'woocommerce_single_image_height', '300', 'yes'),
// -(65669, 'woocommerce_thumbnail_image_width', '90', 'yes'),
// -(65670, 'woocommerce_thumbnail_image_height', '90', 'yes'),
// -(65665, 'woocommerce_catalog_image_width', '150', 'yes'),
// -(65666, 'woocommerce_catalog_image_height', '150', 'yes'),
// -(65667, 'woocommerce_single_image_width', '300', 'yes'),
// +(65668, 'woocommerce_single_image_height', '700', 'yes'),
// +(65669, 'woocommerce_thumbnail_image_width', '200', 'yes'),
// +(65670, 'woocommerce_thumbnail_image_height', '200', 'yes'),
// +(65665, 'woocommerce_catalog_image_width', '450', 'yes'),
// +(65666, 'woocommerce_catalog_image_height', '675', 'yes'),
// +(65667, 'woocommerce_single_image_width', '700', 'yes'),