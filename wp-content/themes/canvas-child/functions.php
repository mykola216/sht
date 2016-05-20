<?php defined( 'ABSPATH' ) || die();

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

add_action( 'widgets_init', 'canvas_child_register_sidebars' );

add_action( 'woo_nav_after', 'canvas_child_nav_after_module' );
add_action( 'woo_footer_top', 'canvas_child_social_media_footer_module' );

add_action( 'woocommerce_after_shop_loop_item', 'canvas_child_product_excerpt', 10 );

add_filter( 'wp_nav_menu_items', 'canvas_child_add_home_menu_items' );
add_filter( 'woo_breadcrumbs_trail', 'canvas_child_breadcrumbs_trail' );
add_filter( 'woocommerce_cart_totals_order_total_html', 'canvas_child_cart_totals_order_total_html' );
add_filter( 'woocommerce_get_order_item_totals', 'canvas_child_get_order_item_totals', 10, 2 );
add_filter( 'woocommerce_cart_shipping_method_full_label', 'canvas_child_woocommerce_cart_shipping_method_full_label', 10, 2 );
// Common - end


// Home page
add_action( 'woo_main_before', 'canvas_child_home' );
// Home page - end


// Archive page
add_action( 'woocommerce_archive_description', 'canvas_child_archive_description_sidebar', 20 );
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

add_filter( 'woocommerce_product_tabs', 'canvas_child_product_tabs', 90 );
add_filter( 'gform_product_field_types', 'canvas_child_field_types' );
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

function canvas_child_wp_enqueue_script() {
	$uri = get_stylesheet_directory_uri();

	wp_enqueue_style( 'theme-stylesheet', get_template_directory_uri() . '/style.css', array( 'dashicons' ) );
	wp_enqueue_style( 'theme-child-style', $uri . '/style.css', array( 'theme-stylesheet' ) );

	if ( is_home() ) {
		wp_enqueue_style( 'canvas_child_home', $uri . '/css/home.css', array( 'theme-child-style' ) );
	} elseif ( is_single() ) {
		wp_enqueue_style( 'canvas_child_single', $uri . '/css/single.css', array( 'theme-child-style' ) );
		wp_enqueue_script( 'canvas_child_single',  $uri . '/js/single.js', 'jquery' );
		wp_enqueue_script( 'canvas-child-quantity-input',  $uri . '/js/quantity-input.js', 'canvas_child_single' );
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

	wp_enqueue_script( 'sharethis', '//w.sharethis.com/button/buttons.js' );
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
 * Modify breadcrumbs.
 */
function canvas_child_breadcrumbs_trail( $trail ) {
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

function canvas_child_woocommerce_cart_shipping_method_full_label( $label, $method ) {

	if ( 0 == $method->cost && 'free_shipping' != $method->id ) {

		$free = ' (' . __( 'Free', 'woocommerce' ) . ')';
		$label = str_replace( $free, '', $label );

	}

	return $label;

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
}
/******************************************************************************/
/* Archive page - end *********************************************************/
/******************************************************************************/


/******************************************************************************/
/* Single page ****************************************************************/
/******************************************************************************/
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

/*
 *
 */
function canvas_child_field_types( $field_types ) {
	$field_types[] = 'select';

	return $field_types;
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
			Bel onze klantenservice +31(0)502 301 066 of mail ons via <a href="http://steigerhouttrend.nl/klantenservice/contact/?utm_source=Shopping%20cart&amp;utm_medium=Link&amp;utm_campaign=Contact%2FShoppingcart">contact formulier</a>.
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
