<?php defined( 'ABSPATH' ) || die();

//libxml_use_internal_errors(true);

// Find modules in perent and child themes
if ( is_child_theme() ) {
	$child_theme_modules =  glob( STYLESHEETPATH . '/modules/*' , GLOB_ONLYDIR );
}
$parent_theme_modules = glob( TEMPLATEPATH . '/modules/*' , GLOB_ONLYDIR );
$modules = array_merge(
	is_array( $child_theme_modules ) ? $child_theme_modules : array(),
	is_array( $parent_theme_modules ) ? $parent_theme_modules : array()
);

// Get list of unique modules.
$modules = array_map( 'basename', $modules );
$modules = array_unique( $modules );

// Load lodules. If module exists in both perent and child themes - child wins.
foreach ( $modules as $module ) {
	$path = '/modules/' . $module . '/main.php';
	if ( is_readable( STYLESHEETPATH . $path ) ) {
		$path = STYLESHEETPATH . $path;
	} else {
		$path = TEMPLATEPATH . $path;
	}

	require_once( $path );
}

/******************************************************************************/
/* ScroogeFrog code                                                           */
/******************************************************************************/
if ( 'steigerhouttrend.nl' == $_SERVER['SERVER_NAME'] ) {
	include_once( WP_CONTENT_DIR . '/scroogefrog_tcp.php' );
	add_action( 'wp_footer', function() {
		include_once( WP_CONTENT_DIR . '/scroogefrog_counter_container.php' );
	} );
}
/******************************************************************************/
/* ScroogeFrog code - end                                                     */
/******************************************************************************/


// Common
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

add_action('after_setup_theme', 'canvas_child_setup');

add_action( 'wp_enqueue_scripts', 'canvas_child_wp_enqueue_script' );
add_action( 'admin_enqueue_scripts', 'canvas_child_admin_enqueue_script' );

add_action( 'widgets_init', 'canvas_child_register_sidebars' );

add_action( 'woo_nav_after', 'canvas_child_nav_after_module' );
add_action( 'woo_footer_top', 'canvas_child_social_media_footer_module' );

add_action( 'woocommerce_after_shop_loop_item', 'canvas_child_product_excerpt', 10 );

add_filter( 'wp_nav_menu_items', 'canvas_child_add_home_menu_items' );
add_filter( 'woo_breadcrumbs_trail', 'canvas_child_breadcrumbs_trail' );
//add_filter( 'woocommerce_cart_totals_order_total_html', 'canvas_child_cart_totals_order_total_html' );
add_filter( 'woocommerce_get_order_item_totals', 'canvas_child_get_order_item_totals', 10, 2 );
add_filter( 'woocommerce_cart_shipping_method_full_label', 'canvas_child_woocommerce_cart_shipping_method_full_label', 10, 2 );

add_filter( 'woocommerce_sale_flash', 'canvas_child_woocommerce_sale_flash', 10, 3 );
add_filter( 'formatted_woocommerce_price', 'canvas_child_formatted_woocommerce_price', 10, 5 );

add_filter( 'yith_wcwl_email_share_subject', 'canvas_child_yith_wcwl_email_share_subject', 10);

add_action( 'yith_wcwl_before_wishlist', 'display_print_button');
add_action( 'woocommerce_before_cart', 'display_print_button');
add_action( 'woocommerce_single_product_summary', 'display_print_button', 12);
// Common - end


// Home page
add_action( 'woo_main_before', 'canvas_child_home' );
// Home page - end


// Archive page
//add_action( 'woocommerce_archive_description', 'canvas_child_archive_description_sidebar', 20 );
add_action( 'woocommerce_archive_description', 'canvas_child_after_main_content_sidebar', 1);
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
add_action( 'woocommerce_archive_description', 'canvas_child_taxonomy_archive_description', 10 );
//add_action( 'woocommerce_after_main_content', 'canvas_child_after_main_content_sidebar');
add_filter( 'amp_post_template_file', 'canvas_child_amp_custom_template', 20, 3 );
add_filter( 'woo_title', 'canvas_child_woo_title', 999, 3 );
// Archive - end


// Single page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

//add_action( 'woo_top', 'canvas_child_connect_facebook_sdk', 0 );
add_action( 'woo_top_singular-product', 'canvas_child_connect_facebook_sdk', 0 );
add_action( 'woo_head_singular-product', 'canvas_child_connect_facebook_moderation' );
add_action( 'woocommerce_before_single_product', 'canvas_child_single_product_sidebar' );
add_action( 'woocommerce_product_thumbnails', 'canvas_child_product_description', 30 );
add_action( 'woocommerce_single_product_summary', 'canvas_child_single_product_summary_before', 1 );
add_action( 'woocommerce_single_product_summary', 'canvas_child_single_product_summary_after', 99 );
add_action( 'woocommerce_after_add_to_cart_form', 'canvas_child_shipping_terms' );
add_action( 'gform_post_submission_4', 'canvas_child_gform_post_submission_4' );
add_action( 'template_redirect', 'canvas_child_track_product_view', 30 );

add_filter( 'woocommerce_product_tabs', 'canvas_child_product_tabs', 90 );
add_filter( 'gform_product_field_types', 'canvas_child_field_types' );
add_filter("gform_currencies", "canvas_child_gform_update_currency");


if (function_exists('YITH_WFBT_Frontend')) {
	remove_action( 'woocommerce_after_single_product_summary', array( YITH_WFBT_Frontend(), 'add_bought_together_form' ), 1 );
}
add_action( 'woocommerce_after_single_product', array( 'YITH_WFBT_Frontend', 'add_bought_together_form' ), 1 );

remove_action("woocommerce_after_single_product", "zwt_woocommerce_customer_also_viewed");
add_action("woocommerce_after_single_product", "canvas_child_woocommerce_customer_also_viewed", 2);

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 3 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_after_single_product', 'woocommerce_output_product_data_tabs', 4 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 5 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action('woocommerce_before_single_product', 'woocommerce_template_single_title', 1);

// Single page - end


// Cart page
//add_action( 'woocommerce_before_cart', 'canvas_child_woocommerce_before_cart' );
add_action( 'woo_top_singular-page-' . wc_get_page_id( 'cart' ), 'canvas_child_cart_woo_top' );
add_filter( 'woocommerce_get_cart_tax', 'canvas_child_get_cart_tax' );
add_action( 'woocommerce_after_cart_totals', 'canvas_child_after_cart_totals' );
// Cart page - end


// Checkout page
add_filter( 'woocommerce_order_button_html', 'canvas_child_add_сhange_button' );
add_filter( 'woocommerce_terms_is_checked_default', '__return_true' );
// Checkout page - end


// Orders
add_action( 'woocommerce_order_item_meta_start', 'canvas_child_order_item_meta_start', 10, 3 );
// Orders - end


/******************************************************************************/
/* Common                                                                     */
/******************************************************************************/
function canvas_child_setup(){
	load_theme_textdomain( 'woocommerce', get_stylesheet_directory() );

	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
}

function canvas_child_admin_enqueue_script() {
	$uri = get_stylesheet_directory_uri();
	if (!wp_is_mobile()) {
		wp_enqueue_style( 'canvas_child_admin', $uri . '/css/admin-custom.css' );
	}
}

function canvas_child_wp_enqueue_script() {
	global $st_options;
	$uri = get_stylesheet_directory_uri();

	wp_enqueue_style( 'theme-stylesheet', get_template_directory_uri() . '/style.css', array( 'dashicons' ) );
	wp_enqueue_style( 'theme-child-style', $uri . '/style.css', array( 'theme-stylesheet' ) );

	if ( is_home() ) {
		wp_enqueue_style( 'canvas_child_home', $uri . '/css/home.css', array( 'theme-child-style' ) );
	} elseif ( is_single() ) {
		wp_enqueue_style( 'canvas_child_single', $uri . '/css/single.css', array( 'theme-child-style' ) );
		wp_enqueue_script( 'canvas_child_single',  $uri . '/js/single.js', array('jquery'), '001', true );
		wp_enqueue_script( 'canvas-child-quantity-input',  $uri . '/js/quantity-input.js', array('canvas_child_single'), '001', true );
		wp_localize_script( 'canvas_child_single', 'Localize_JS_Canvas_Child_Single', $st_options );
	} elseif ( is_page( 'winkelwagen' ) ) {
		wp_enqueue_style( 'canvas_child_cart', $uri . '/css/cart.css', array( 'theme-child-style' ) );
		wp_enqueue_script( 'canvas_child_cart',  $uri . '/js/cart.js', 'jquery' );
		wp_enqueue_script( 'canvas-child-quantity-input',  $uri . '/js/quantity-input.js', 'canvas_child_cart' );
		$translation_array = array(
			'coupon_applied' => __( 'Coupon code applied successfully.', 'woocommerce' )
		);
		wp_localize_script( 'canvas_child_cart', 'translation', $translation_array );
	} elseif ( is_page( 'afrekenen' ) ) {
		wp_enqueue_style( 'canvas_child_checkout', $uri . '/css/checkout.css', array( 'theme-child-style' ) );
		if ( is_wc_endpoint_url( 'order-received' ) ) {
			wp_enqueue_style( 'canvas-child-checkout-thankyou', $uri . '/css/checkout-thankyou.css', array( 'theme-child-style' ) );
		}
	} elseif ( is_archive() ){
		wp_enqueue_style( 'canvas_child_archive', $uri . '/css/archive.css', array( 'theme-child-style' ) );
	}

	wp_enqueue_script( 'canvas-child-common-js',  $uri . '/js/common.js', array('jquery'), '001', true );

	wp_enqueue_script( 'sharethis', 'https://w.sharethis.com/button/buttons.js' );
	//wp_enqueue_script( 'sharethis_loader', 'https://ss.sharethis.com/loader.js', array('sharethis') );
}

function canvas_child_register_sidebars() {
	register_sidebar( array(
		'name' => 'Additional category description',
		'id' => 'canvas-child-archive-description',
		'before_widget' => '<div id="%1$s" class="widget canvas-child-archive-description %2$s">',
		'after_widget' => '</div>',
	) );
	register_sidebar( array(
		'name' => 'Single product info block',
		'id' => 'canvas-child-single-product',
		'before_widget' => '<div id="%1$s" class="widget canvas-child-single-product %2$s">',
		'after_widget' => '</div>',
	) );
	register_sidebar( array(
		'name' => 'After Main Content',
		'id' => 'canvas_child_after_main_content',
		'before_widget' => '<div id="%1$s" class="widget canvas_child_after_main_content %2$s">',
		'after_widget' => '</div>',
	) );
	register_sidebar( array(
		'name' => 'Before Footer Top',
		'id' => 'canvas_child_before_footer_top',
		'before_widget' => '<div id="%1$s" class="widget canvas_child_before_footer_top %2$s">',
		'after_widget' => '</div>',
	) );
}

/**
 * Prepending 'Home' link with main menu.
 */
function canvas_child_add_home_menu_items( $items ) {
	$homelink = '<li class="menu-item"><a href="/"><span class="dashicons dashicons-admin-home"></span></a></li>';
	$items = $homelink . $items;

	return $items;
}

/**
 * Remove tax from order total output.
 */
function canvas_child_get_order_item_totals( $total_rows, $order ) {
	$total_rows['order_total']['value'] = $order->get_formatted_order_total();
	$total_rows['order_total']['value'] .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), __( 'VAT', 'woocommerce' ) );

	return $total_rows;
}

/**
 * Remove tax from cart total output.
 */
function canvas_child_cart_totals_order_total_html( $value ) {
	$value = '<strong>' . WC()->cart->get_total() . '</strong> ';
	$value .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), __( 'VAT', 'woocommerce' ) );

	return $value;
}


/**
 * Add shortcode for 'before_footer_top' widget area
 */
add_shortcode('before_footer_top_widget_area', 'canvas_child_before_footer_top_widget_area');
function canvas_child_before_footer_top_widget_area() {
	if ( is_active_sidebar( 'canvas_child_before_footer_top' ) )
		dynamic_sidebar( 'canvas_child_before_footer_top' );
}

/**
 * Modify breadcrumbs.
 */
function canvas_child_breadcrumbs_trail( $trail ) {
	//unset( $trail['post_type_archive_link'] );
	$count = count( $trail );
	foreach ( $trail as $index => $value ) {
		if ( ! in_array( $index, array(0, 'post_type_archive_link', $count-3, 'trail_end') ) ) {
			unset( $trail[ $index ] );
		} elseif ( 'post_type_archive_link' === $index ) {
			$trail['post_type_archive_link'] = str_replace(
				__( 'Products', 'woocommerce' ),
				__( 'All Products', 'woocommerce' ),
				$value
			);
		}
	}
	return $trail;
}

/**
 * Output the nav_after module.
 */
function canvas_child_nav_after_module() {
	wc_get_template( 'module-nav-after.php', null, 'templates' );
}

/**
 * Output the social footer media module.
 */
function canvas_child_social_media_footer_module() {
	wc_get_template( 'module-social-media-footer.php', null, 'templates' );
}

/**
 * Output the products excerpt.
 */
function canvas_child_product_excerpt() {
	wc_get_template( 'product-excerpt.php', null, 'templates' );
}

/**
 * Output top products.
 */
function canvas_child_top_products( $params ) {
	wc_get_template( 'top-products.php', $params, 'templates' );
}

/**
 * Add product image to custom order.
 */
add_filter( 'gform_custom_merge_tags', function( $custom_tags ) {
	$custom_tags[] = array( 'tag' => '{product_image}', 'label' => __( 'Product Image', 'woocommerce' ) );

	return $custom_tags;
} );
add_filter( 'gform_replace_merge_tags', function( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
	if ( 'html' == $format) {
		$text = str_replace( '{product_image}', get_the_post_thumbnail(), $text );
	}

	return $text;
}, 10, 7 );


/**
 * Change shipping methods label.
 */
function canvas_child_woocommerce_cart_shipping_method_full_label( $label, $method ) {

	if ( 0 == $method->cost && 'free_shipping' != $method->id ) {

		$free = ' (' . __( 'Free', 'woocommerce' ) . ')';
		$label = str_replace( $free, '', $label );

	}

	return $label;

}

/**
 * Custom Format of Sale label for product
 */
function canvas_child_woocommerce_sale_flash($html, $post, $product) {
	global $post, $product, $st_options;
	$classes = array();
	$classes[] = 'onsale';
	if ( is_singular('product') && $post->ID == get_queried_object_id() ) {
		$classes[] = 'on-single-product';
	}
	if (!!$st_options['hide_sale_label']) {
		if (is_singular('product') && $post->ID == get_queried_object_id() ) {
			if (!$st_options['show_sale_label_only_sngl']) {
				$classes[] = 'hidden';
			}
		}
		else {
			$classes[] = 'hidden';
		}
	}
	$classes = implode(' ', $classes);
	return '<span class="'. $classes . '">' . __( 'Sale!', 'woocommerce' ) . '</span>';
}



/**
 * Custom Format product price
 */
function canvas_child_formatted_woocommerce_price($number_format, $price, $decimals, $decimal_separator, $thousand_separator) {
	global $st_options;
	if ($st_options['is_custom_price_format']) {
		list($whole, $decimal_val) = explode($decimal_separator, $number_format);
		$number_format = (absint($decimal_val)) ? $number_format : $whole . $decimal_separator . $st_options['price_decimal_zero_symb'] ;
	}
	return $number_format;
}



/**
 * Display Instagram Feed
 */
function canvas_child_instagram_feed($echo = true) {
	global $st_options;
	$out = '';
	$styles = '';

	$styles = '
		<style>
			#sb_instagram #sbi_images{
				padding-left:0 !important;
				padding-right:0 !important;
			}
			#sb_instagram #sbi_images .sbi_item.sbi_type_image{
				padding-left:5px !important;
				padding-right:5px !important;
			}
			#sb_instagram #sbi_images .sbi_item.sbi_type_image:first-child{
				padding-left:0 !important;
			}
			#sb_instagram #sbi_images .sbi_item.sbi_type_image:last-child{
				padding-right:0 !important;
			}
		</style>';

	$out .= $styles;

	$is_show = $st_options['show_instagram_feed'];
	$is_show =  $st_options['show_instagram_feed_in_product_cat'] ? $is_show && (is_tax('product_cat') || is_home() || is_front_page()) : $is_show ;

	if ($is_show) {
		if ($st_options['instagram_feed_title']) {
			$out .= '<h4>' . $st_options['instagram_feed_title'] . '</h4>';
		}
		$out .= do_shortcode($st_options['instagram_feed_shortcode']);
	}

	if ($echo) echo $out;
	else return $out;
}

function canvas_child_yith_wcwl_email_share_subject($subject) {
	global $st_options;
	$subject = $st_options['wishlist_email_subject'];
	$subject = $subject ? $subject : sprintf(__('U heeft een verlanglijst van %1$s ontvangen', 'canvas_child'), get_bloginfo('name'));
	return $subject;
}

function print_button() {
	$id = get_queried_object_id();
	$link = home_url('/index.php?task=productprint&pid='.$id); //sets the URL for the post page
	$nonced_url = wp_nonce_url($link, $id); /*** adds a nonce to the URL ***/
?>
	<a href="<?php print $nonced_url; ?>" class="button print-button"  target="_blank" rel="nofollow" onclick="document.body.classList.add('canvas_child_print_button');window.print();return false;">Print</a>
<?php
}

function display_print_button() {
	print_button();
}


/******************************************************************************/
/* Common - end                                                               */
/******************************************************************************/


/******************************************************************************/
/* Home page ******************************************************************/
/******************************************************************************/
/**
 * Output the home page content.
 */
function canvas_child_home() {
	if ( is_home() ) {
		add_filter( 'post_thumbnail_size', function() { return 'shop_single'; } );
		wc_get_template( 'home.php', array(), 'templates' );
	}
}
/******************************************************************************/
/* Home page - end ************************************************************/
/******************************************************************************/


/******************************************************************************/
/* Archive page ***************************************************************/
/******************************************************************************/
function canvas_child_archive_description_sidebar() {
	dynamic_sidebar('canvas-child-archive-description');
	echo '<div class="clear"></div>';
}
function canvas_child_after_main_content_sidebar () {
	dynamic_sidebar('canvas_child_after_main_content');
}
function canvas_child_woo_title($raw_title, $sep, $raw_title) {
	global $st_options;
	$title = $raw_title;
	if (is_shop()) {
		$title = $st_options['shop_page_browser title'];
		$title = $title ? $title : get_the_title(wc_get_page_id( 'shop' )) . ' ' . $sep . ' ' . get_bloginfo('name');
	}
	return $title;
}
function canvas_child_taxonomy_archive_description() {
	global $st_options;

	ob_start();
	canvas_child_archive_description_sidebar();
	$archive_description_sidebar =  ob_get_clean();

	if ( is_tax( array( 'product_cat', 'product_tag' ) ) && 0 === absint( get_query_var( 'paged' ) ) ) {
		$description = wc_format_content( term_description() ) . $archive_description_sidebar;
		if ( $description ) {
			if (wp_is_mobile()) {
				$description_html =
				'<div class="description-wrapper">
					<div class="term-description full-content closed" data-offset="10">' . $description . '</div>
						<button
						class="button more read-more"
						data-target=".term-description"
						data-label-read-more="' . $st_options['read_more_text'] . '"
						data-label-hide-more="' . $st_options['hide_more_text'] . '" >
						' . $st_options['read_more_text'] . '
					</button>
				</div>';
			} else {
				$description_html = '<div class="term-description">' . $description . '</div>';
			}
			echo $description_html;
		}
	}
}
function canvas_child_amp_custom_template( $file, $type, $post ) {
	// Custom Homepage and Archive file
	global $redux_builder_amp;

	$ampforwp_design_selector = $redux_builder_amp ? $redux_builder_amp['amp-design-selector'] : 2;

	if ($redux_builder_amp['amp-frontpage-select-option'] == 0) {
		if ( is_home() || is_archive() ) {
			if ( 'single' === $type ) {
				$file = get_stylesheet_directory() . '/amp/templates/design-manager/design-'. $ampforwp_design_selector .'/index.php';
			}
		}
	}
	elseif ($redux_builder_amp['amp-frontpage-select-option'] == 1) {
		if ( is_home() ) {
			if ( 'single' === $type ) {
				$file = AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. $ampforwp_design_selector .'/frontpage.php';
			}
		}
		if ( is_archive() ) {
			if ( 'single' === $type ) {
				$file = get_stylesheet_directory() . '/amp/templates/design-manager/design-'. $ampforwp_design_selector .'/index.php';
			}
		}

	}
	// Custom Single file
	if ( is_single() || is_page() ) {
		if('single' === $type && !('product' === $post->post_type )) {
			$file = AMPFORWP_PLUGIN_DIR . '/templates/design-manager/design-'. $ampforwp_design_selector .'/single.php';
		}
	}

	return $file;
}
/******************************************************************************/
/* Archive page - end *********************************************************/
/******************************************************************************/


/******************************************************************************/
/* Single page ****************************************************************/
/******************************************************************************/

function canvas_child_excerpt_length($length) {
	return 150;
}
add_filter('excerpt_length', 'canvas_child_excerpt_length');

/**
 * Add Facebook moderation.
 */
function canvas_child_connect_facebook_moderation() {
	wc_get_template_part( 'templates/module', 'facebook-moderation' );
}
/**
 * Connect Facebook SDK.
 */
function canvas_child_connect_facebook_sdk() {
	wc_get_template_part( 'templates/module', 'facebook-sdk' );
}

function canvas_child_single_product_sidebar() {
	dynamic_sidebar('canvas-child-single-product');
}

/**
 * Output the products description.
 */
function canvas_child_product_description() {
	wc_get_template( 'product-description.php', array(), 'templates/single-product' );
}

/**
 * Set single product page tabs.
 */
function canvas_child_product_tabs( $tabs = array() )
{
	unset( $tabs['description'] );

	$post_id = 1598;
	$tabs[ $post_id ] = array(
		'title'    => get_the_title( $post_id ),
		'priority' => 28,
		'callback' => 'canvas_child_get_post_filtered_content',
	);
	$post_id = 1619;
	$tabs[ $post_id ] = array(
		'title'    => get_the_title( $post_id ),
		'priority' => 29,
		'callback' => 'canvas_child_get_post_filtered_content',
	);

	return $tabs;
}

/**
 * Get post filtered content.
 */
function canvas_child_get_post_filtered_content( $id ) {
	$content = get_post( $id )->post_content;
	echo $content;
}

/**
 * Output the product's summary before content.
 */
function canvas_child_single_product_summary_before() {
	wc_get_template( 'product-summary-before.php', array(), 'templates/single-product' );
}

/**
 * Output the product's summary after content.
 */
function canvas_child_single_product_summary_after() {
	wc_get_template( 'product-summary-after.php', array(), 'templates/single-product' );
}

/**
 * Output shipping terms.
 */
function canvas_child_shipping_terms() {
	wc_get_template( 'shipping-terms.php', array(), 'templates/single-product' );
}

/**
 * Оформить подписку MailChimp при запросе товара на заказ
 */
function canvas_child_gform_post_submission_4( $args ) {
	preg_match( '/^(\w+)\W+?(.*)?/', $args[2], $matches );
	$url = 'http://steigerhouttrend.us9.list-manage.com/subscribe/post';
	$data = array(
		'u'      => 'd48352b93a6be86fe761e6167',
		'id'     => '261f611fb3',
		'MERGE0' => $args[3],
		'MERGE1' => isset( $matches[1] ) ? $matches[1] : ' ',   // first name
		'MERGE2' => isset( $matches[1] ) ? $matches[2] : ' ',   // last name
		'b_d48352b93a6be86fe761e6167_261f611fb3' => ''
	);
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query( $data ),
		),
	);

	$context = stream_context_create( $options );
	file_get_contents( $url, false, $context );
}



function canvas_child_field_types( $field_types ) {
	$field_types[] = 'select';

	return $field_types;
}



function canvas_child_gform_update_currency($currencies) {
	$currencies['EUR'] = array(
		"name" => __("Euro", "gravityforms"),
		"symbol_left" => ' €',
		"symbol_right" => "",
		"symbol_padding" => " ",
		"thousand_separator" => ' ',
		"decimal_separator" => ',',
		"decimals" => 2
	);
	$currencies['USD'] = array(
		"name" => __("Euro", "gravityforms"),
		"symbol_left" => ' €',
		"symbol_right" => "",
		"symbol_padding" => " ",
		"thousand_separator" => ' ',
		"decimal_separator" => ',',
		"decimals" => 2
	);

	return $currencies;
}


function canvas_child_track_product_view () {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
		$viewed_products = array();
	else
		$viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );

	if ( ! in_array( $post->ID, $viewed_products ) ) {
		$viewed_products[] = $post->ID;
	}

	if ( sizeof( $viewed_products ) > 15 ) {
		array_shift( $viewed_products );
	}

	// Store for session only
	wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}


function canvas_child_woocommerce_customer_also_viewed ( $atts, $content = null ) {
		$per_page = get_option( 'total_items_display' );
		$plugin_title = get_option( 'customer_who_viewed_title' );
		$category_filter = get_option( 'category_filter' );
		$show_image_filter = get_option( 'show_image_filter' );
		$show_price_filter = get_option( 'show_price_filter' );
		$show_addtocart_filter = get_option( 'show_addtocart_filter' );
		$product_order = get_option( 'product_order' );
		// Get WooCommerce Global
		global $woocommerce;
		global $post;
		// Get recently viewed product data using get_option

		$customer_also_viewed = get_option('customer_also_viewed_'.$post->ID);
		if (!empty($customer_also_viewed))
		{
			$customer_also_viewed = explode(',',$customer_also_viewed);
			$customer_also_viewed = array_reverse($customer_also_viewed);

			//Skip same product on product page from the list
			if(($key = array_search($post->ID, $customer_also_viewed)) !== false) { unset($customer_also_viewed[$key] ); }

			$per_page = ($per_page == "")? $per_page = 5 : $per_page;
			$plugin_title = ($plugin_title == "")? $plugin_title = 'Customer Who Viewed This Item Also Viewed' : $plugin_title;

			// Create the object
			ob_start();

			$categories = get_the_terms( $post->ID, 'product_cat' );

			// Create query arguments array
			$query_args = array(
									'posts_per_page' => $per_page,
									'no_found_rows'  => 1,
									'post_status'    => 'publish',
									'post_type'      => 'product',
									'post__in'       => $customer_also_viewed
									);

			$query_args['orderby'] = ($product_order == '') ? 'ID(ID, explode('.$customer_also_viewed.'))' : $product_order;


			//Executes if category filter applied on product page
			if($category_filter == 1 && !empty($categories))
			{
				foreach ($categories as $category) {
				if($category->parent == 0){
					   $category_slug = $category->slug;
					}
				}
				$query_args['product_cat'] = $category_slug;
			}

			// Add meta_query to query args
			$query_args['meta_query'] = array();

			// Check products stock status
			$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();

			// Create a new query
			$products = new WP_Query($query_args);

			// If query return results
			if ( !$products->have_posts() ) {
				// If no data, quit
				exit;
			}
			else { //Displays title ?>
				<!-- div class="clear"></div -->
				<div class="woocommerce customer_also_viewed">
					<h2><?php _e( $plugin_title, 'woocommerce' ) ?></h2>
					<?php // Start the loop
					$count = 1;
					 woocommerce_product_loop_start();
					 while ( $products->have_posts() ) : $products->the_post(); ?>
						 <li class="product">
							 <?php do_action( 'woocommerce_before_shop_loop_item' );?>
								 <a href="<?php the_permalink(); ?>">
									 <?php if($show_image_filter == 1) { do_action( 'woocommerce_before_shop_loop_item_title' ); } ?>
										 <h3><?php the_title(); ?></h3>
									 <?php if($show_price_filter == 1){ do_action( 'woocommerce_after_shop_loop_item_title' ); } ?>
								 </a>
							 <?php if($show_addtocart_filter == 1) { do_action( 'woocommerce_after_shop_loop_item' ); } ?>
						 </li>
					 <?php endwhile; ?>
					 <?php woocommerce_product_loop_end(); ?>
				</div>
				<!-- div class="clear"></div -->
		<?php }
			wp_reset_postdata();
		}
}


/* Fixed if slider method is undefined */
foreach (array( 'flex', 'coin', 'nivo', 'responsive' ) as $type) {
	add_filter('metaslider_' . $type . '_slider_javascript_before', function ($js) {
		$customJS = '
			if (jQuery().flexslider === undefined) {
				jQuery.fn.flexslider = function () {};
			};
		';
		return $customJS . $js;
	}, 9);
}

add_action('wp_head', 'canvas_child_custom_css_textural_menu');
function canvas_child_custom_css_textural_menu() {
	global $st_options;
	$style = '<style>' .$st_options['custom_css_textural_menu']. '</style>';

	echo $style;
}
/******************************************************************************/
/* Single page - end **********************************************************/
/******************************************************************************/


/******************************************************************************/
/* Cart page ******************************************************************/
/******************************************************************************/
function canvas_child_woocommerce_before_cart() {
	?>
		<p>
			Wil je een vraag stellen over artikelen, die je hebt gekozen, dan geven we graag antwoord.<br />
			Bel onze klantenservice +31(0)502 301 066 of mail ons via <a href=<?php echo get_site_url(); ?>/klantenservice/contact/?utm_source=Shopping%20cart&amp;utm_medium=Link&amp;utm_campaign=Contact%2FShoppingcart">contact formulier</a>.
		</p>
	<?php
}
/**
 * Save WooCommerce session notices for future use.
 */
function canvas_child_cart_woo_top() {
	$GLOBALS['all_notices'] = WC()->session->get( 'wc_notices', array() );
}

/**
 * Remove the note under Order Total on cart page.
 */
function canvas_child_get_cart_tax() {
	return false;
}

/**
 * Add quick order module.
 */
function canvas_child_after_cart_totals() {
	if ( current_user_can('manage_options') ) {
		wc_get_template_part( 'templates/module', 'quick-order' );
	}
}
/******************************************************************************/
/* Cart page - end ************************************************************/
/******************************************************************************/


/******************************************************************************/
/* Checkout page **************************************************************/
/******************************************************************************/
/**
 * Add "Change" button to checkout page.
 */
function canvas_child_add_сhange_button( $html ) {
	$html .= sprintf(
		'<a href="%s" class="button">%s</a>',
		WC()->cart->get_cart_url(),
		__('Update Cart', 'woocommerce')
	);

	return $html;
}
/******************************************************************************/
/* Checkout page - end ********************************************************/
/******************************************************************************/


/******************************************************************************/
/* Orders *********************************************************************/
/******************************************************************************/
/**
 * Add product image to order email.
 */
function canvas_child_order_item_meta_start( $item_id, $item, $order ) {
	$_product = $order->get_product_from_item( $item );
	printf (
		'<img src="%s" alt="%s" style="vertical-align:middle; margin-right: 10px;" />',
		$_product->get_image_id() ?
			current( wp_get_attachment_image_src( $_product->get_image_id(), 'thumbnail' ) ) :
			wc_placeholder_img_src(),
		__( 'Product Image', 'woocommerce' )
	);
}
/******************************************************************************/
/* Orders - end ***************************************************************/
/******************************************************************************/


/******************************************************************************/
/* Admin 404 page setting - start *********************************************/
/******************************************************************************/
add_action( 'admin_menu', 'canvas_child_register_admin_menu' );

add_action( 'widgets_init', 'canvas_child_widgets_init_404' );

function canvas_child_register_admin_menu() {
	add_submenu_page('woothemes', 'Page 404', '404', 'manage_options', 'wootheme-404-setting', 'canvas_child_admin_404_setting');
	add_submenu_page('woothemes', 'Facebook Pixel', 'Facebook Pixel', 'manage_options', 'wootheme-facebook-api-setting', 'canvas_child_admin_facebook_api_setting');
}

function canvas_child_widgets_init_404() {
	register_sidebar( array(
		'name' => '404 Page',
		'id' => 'sidebar-404-page',
		'before_widget' => '<div id="%1$s" class="widget404 widget %2$s">',
		'after_widget'  => '</div>'
	) );
}

function canvas_child_admin_404_setting() {

	if (array_key_exists( 'error-title', $_POST ) &&
	    array_key_exists( 'error-description', $_POST )) {
		update_option( 'custom_404_title', htmlentities( stripslashes( $_POST['error-title'] ) ) );
		update_option( 'custom_404_description', htmlentities( stripslashes( $_POST['error-description'] ) ) );
	}

	$errorTitle = get_option( 'custom_404_title', '' );
	$errorDescription = get_option( 'custom_404_description', '' );

	?>
	<style>
		.tTitle {
			width: 100%;
		}

		.wpField {
			margin-top: 20px;
		}

		.inText {
			width: calc(100% - 25px);
			margin-left: 5px;
			margin-top: 5px;
		}

		.inDescription {
			line-height: 2;
			width: calc(100% - 25px);
			margin-left: 5px;
			margin-top: 5px;
		}

		.bSave {
			width: 85px;
			height: 30px;
			background-color: rgba(0, 3, 255, 0.74);
			border: 1px solid rgba(0, 0, 0, 0.2);
			border-radius: 2px;
			cursor: pointer;
			color: white;
			box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.58);
			margin: 0 auto;
			display: block;
			margin-right: 0;
			/*margin-right: );*/
		}

		.bSave:hover {
			box-shadow: inset 1px 1px 1px rgba(0, 0, 0, 0.27);
		}
	</style>
	<div class="wp404Setting">
		<form method="post">
			<div class="wpField">
				<div class="tTitle">Title</div>
				<input class="inText" name="error-title" value="<?php echo $errorTitle; ?>" type="text"/>
			</div>
			<div class="wpField">
				<div class="tTitle">Description</div>
				<textarea class="inDescription" name="error-description"><?php echo $errorDescription; ?></textarea>
			</div>
			<div class="wpField">
				<button class="bSave">Save</button>
			</div>
		</form>
	</div>
	<?php
}

function canvas_child_admin_facebook_api_setting() {
	if (array_key_exists( 'pixel-code', $_POST )) {
		update_option( 'custom_facebook_api_pixel_code', htmlentities( stripslashes( $_POST['pixel-code'] ) ) );
	}

	$pixelCode = get_option( 'custom_facebook_api_pixel_code', '' );
	?>
	<style>
		.tTitle {
			width: 100%;
		}

		.wpField {
			margin-top: 20px;
		}

		.tCode {
			line-height: 2;
			width: calc(100% - 25px);
			margin-left: 5px;
			margin-top: 5px;
		}

		.bSave {
			width: 85px;
			height: 30px;
			background-color: rgba(0, 3, 255, 0.74);
			border: 1px solid rgba(0, 0, 0, 0.2);
			border-radius: 2px;
			cursor: pointer;
			color: white;
			box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.58);
			margin: 0 auto;
			display: block;
			margin-right: 0;
			/*margin-right: );*/
		}

		.bSave:hover {
			box-shadow: inset 1px 1px 1px rgba(0, 0, 0, 0.27);
		}
	</style>
	<div class="wpFacebookApi">
		<form method="post">
			<div class="wpField">
				<div class="tTitle">Pixel Code</div>
				<textarea rows="8" name="pixel-code" class="tCode"><?php echo $pixelCode; ?></textarea>
			</div>
			<div class="wpField">
				<button class="bSave">Save</button>
			</div>
		</form>
	</div>
	<?php
}

add_action( 'wp_head', 'canvas_child_insert_facebook_js' );

function canvas_child_insert_facebook_js() {
	echo html_entity_decode( get_option( 'custom_facebook_api_pixel_code', '' ) );
}

add_filter( 'woocommerce_email_classes', 'canvas_child_order_woocommerce_email_register' );

function canvas_child_order_woocommerce_email_register($email_classes) {
	require_once( 'classes/wc-pending-order-email.php' );

	$email_classes['WC_CCPending_Order_Email'] = new WC_CCPending_Order_Email();

	return $email_classes;
}

add_action( 'woocommerce_order_status_pending', 'canvas_child_woocommerce_order_status_pending_handler' );

function canvas_child_woocommerce_order_status_pending_handler($order_id) {
	$emails = WooCommerce::instance()->mailer()->get_emails();

	if (isset($emails['WC_CCPending_Order_Email']))
		$emails['WC_CCPending_Order_Email']->trigger($order_id);
}





/*-----------------------------------------------------------------------------------*/
/* Determine what layout to use */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'woo_get_layout' ) ) {
	function woo_get_layout() {

		global $post, $wp_query, $woo_options;

		// Reset the query
		if ( is_main_query() ) {
			wp_reset_query();
		}

		// Set default global layout
		$layout = 'two-col-left';
		if ( '' != get_option( 'woo_layout' ) ) {
			$layout = get_option( 'woo_layout' );
		}

		// Single post layout
		if ( is_singular() ) {
			// Get layout setting from single post Custom Settings panel
			if ( '' != get_post_meta( $post->ID, 'layout', true ) ) {
				$layout = get_post_meta( $post->ID, 'layout', true );

				// Portfolio single post layout option.
			} elseif ( 'portfolio' == get_post_type() ) {
				if ( '' != get_option( 'woo_portfolio_layout_single' ) ) {
					$layout = get_option( 'woo_portfolio_layout_single' );
				}

			} elseif ( 'project' == get_post_type() ) {
				if ( '' != get_option( 'woo_projects_layout_single' ) ) {
					$layout = get_option( 'woo_projects_layout_single' );
				} else {
					$layout = get_option( 'woo_layout' );
				}
			}
		}

		// Portfolio gallery layout option.
		if ( is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) || is_page_template( 'template-portfolio.php' ) ) {
			if ( '' != get_option( 'woo_portfolio_layout' ) ) {
				$layout = get_option( 'woo_portfolio_layout' );
			}
		}

		// Projects gallery layout option.
		if ( is_tax( 'project-category' ) || is_post_type_archive( 'project' ) ) {
			if ( '' != get_option( 'woo_projects_layout' ) ) {
				$layout = get_option( 'woo_projects_layout' );
			} else {
				$layout = get_option( 'woo_layout' );
			}
		}

		// WooCommerce Layout
		if ( is_woocommerce_activated() && is_woocommerce() ) {
			if (is_tax('product_cat')) {
				// Set default layout
				if ( '' != get_option( 'woo_wc_layout' ) ) {
					$layout = get_option( 'woo_wc_layout' );
				}
			}
			// WooCommerce single post/page
			if ( is_singular() ) {
				// Get layout setting from single post Custom Settings panel
				if ( '' != get_post_meta( $post->ID, 'layout', true ) ) {
					$layout = get_post_meta( $post->ID, 'layout', true );
				}
			}
		}

		return $layout;

	} // End woo_get_layout()
}
