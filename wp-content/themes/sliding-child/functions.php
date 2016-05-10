<?php
$GLOBALS[ 'sliding_child_options' ] = get_option( 'sliding_child_options' );


function woothemes_setup () {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	if ( locate_template( 'editor-style.css' ) != '' ) { add_editor_style(); }

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	if ( is_child_theme() ) {
		$theme_data = wp_get_theme();
		define( 'CHILD_THEME_URL', $theme_data->get('ThemeURI') );
		define( 'CHILD_THEME_NAME', $theme_data->get('Name') );
	}
}


function woo_generate_font_css($option, $em = '1') {
	// Test if font-face is a Google font
	global $google_fonts;

	foreach ( $google_fonts as $google_font ) {
		// Add single quotation marks to font name and default arial sans-serif ending
		if ( $option[ 'face' ] == $google_font[ 'name' ] ) {
			$option[ 'face' ] = "'" . $option[ 'face' ] . "', arial, sans-serif";
		}
	} // END foreach

	if ( empty( $option['style'] ) || empty( $option['size'] ) || empty( $option['unit'] ) || empty( $option['color'] ) ) {
		return 'font-family: '.stripslashes($option["face"]).';';
	} else {
		return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].';';
	}
}


// Remove right sidebar from all pages
add_filter( 'body_class','sliding_child_set_layout', 99999999 );
function sliding_child_set_layout($classes)
{
    global $woo_options;
    $woo_options['woo_layout'] = 'layout-full';
    return $classes;
}


function sliding_child_wp_enqueue_scripts() {
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
}
add_action( 'wp_enqueue_scripts', 'sliding_child_wp_enqueue_scripts' );


// sliding-child theme options page
add_action('admin_init', 'sliding_child_options_init' );
add_action('admin_menu', 'sliding_child_options_add_page');

// Init plugin options to white list our options
function sliding_child_options_init()
{
    register_setting('sliding_child_options', 'sliding_child_options', 'sliding_child_options_validate');
}

// Add menu page
function sliding_child_options_add_page() {
    add_options_page('sliding-child Theme Options', 'sliding-child Options', 'manage_options', 'sliding_child_options', 'sliding_child_options_do_page');
}

// Draw the menu page itself
function sliding_child_options_do_page()
{
    include('templates/settings.php');
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function sliding_child_options_validate($input)
{
    $input['working_time']          = wp_filter_nohtml_kses($input['working_time']);
    $input['slider_shortcode']      = wp_filter_nohtml_kses($input['slider_shortcode']);
    $input['facebook_url']          = wp_filter_nohtml_kses($input['facebook_url']);
    $input['googleplus_url']        = wp_filter_nohtml_kses($input['googleplus_url']);
    $input['instagram_url']         = wp_filter_nohtml_kses($input['instagram_url']);
    $input['twitter_url']           = wp_filter_nohtml_kses($input['twitter_url']);
    $input['youtube_url']           = wp_filter_nohtml_kses($input['youtube_url']);
    $input['pinterest_url']         = wp_filter_nohtml_kses($input['pinterest_url']);
    $input['subslider_text1']       = wp_filter_nohtml_kses($input['subslider_text1']);
    $input['subslider_text2']       = wp_filter_nohtml_kses($input['subslider_text2']);
    $input['subslider_text3']       = wp_filter_nohtml_kses($input['subslider_text3']);
    $input['subslider_text4']       = wp_filter_nohtml_kses($input['subslider_text4']);
    $input['subslider_url1']        = wp_filter_nohtml_kses($input['subslider_url1']);
    $input['subslider_url2']        = wp_filter_nohtml_kses($input['subslider_url2']);
    $input['subslider_url3']        = wp_filter_nohtml_kses($input['subslider_url3']);
    $input['subslider_url4']        = wp_filter_nohtml_kses($input['subslider_url4']);
    $input['recent_products_url']   = wp_filter_nohtml_kses($input['recent_products_url']);
    $input['recent_products_title'] = wp_filter_nohtml_kses($input['recent_products_title']);
    $input['best_sellers_url']      = wp_filter_nohtml_kses($input['best_sellers_url']);
    $input['best_sellers_title']    = wp_filter_nohtml_kses($input['best_sellers_title']);
    // $input['production-title']     = wp_filter_nohtml_kses($input['production-title']);
    // $input['production-text']      = wp_filter_nohtml_kses($input['production-text']);
    // $input['production-img-url']       = wp_filter_nohtml_kses($input['production-img-url']);
    // $input['production-page-url']       = wp_filter_nohtml_kses($input['production-page-url']);
    $input['home_band_text']        = wp_filter_nohtml_kses($input['home_band_text']);
    $input['home_band_logo']        = wp_filter_nohtml_kses($input['home_band_logo']);

    $input['shipping_terms_line1']  = wp_filter_nohtml_kses($input['shipping_terms_line1']);
    $input['shipping_terms_line2']  = wp_filter_nohtml_kses($input['shipping_terms_line2']);

    $input['info_block_text1']  = wp_filter_nohtml_kses($input['info_block_text1']);
    $input['info_block_text2']  = wp_filter_nohtml_kses($input['info_block_text2']);
    $input['info_block_text3']  = wp_filter_nohtml_kses($input['info_block_text3']);
    $input['info_block_text4']  = wp_filter_nohtml_kses($input['info_block_text4']);

	$input['coupon_btn_text']  = wp_filter_nohtml_kses( $input['coupon_btn_text'] );

    return $input;
}



// Prepending 'Home' link with main menu
add_filter( 'wp_nav_menu_items', 'new_nav_menu_items' );
function new_nav_menu_items($items) {
    $homelink = '<li class="menu-item"><a href="' . home_url( '/' ) . '">' . __('Home') . '</a></li>';
    // add the home link to the end of the menu
    $items = $homelink . $items;
    return $items;
}



// Message customizing
add_filter('woocommerce_add_to_cart_message', 'sliding_child_add_to_cart_message_filter');

function sliding_child_add_to_cart_message_filter($message) {
    global $woocommerce;

    $button = '<a href="'.$woocommerce->cart->get_checkout_url().'" class="button checkout">'.__('Checkout &rarr;', 'woocommerce').'</a>';

    $script = <<<EOL
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.button', '.woocommerce_message').addClass('alt2')
            .delay(200).fadeOut('slow').fadeIn('')
            .delay(200).fadeOut('slow').fadeIn('')
            .delay(200).fadeOut('slow').fadeIn('')
            .delay(200).fadeOut('slow').fadeIn('');
    });
</script>
EOL;

    return $button . $message . $script;
}



// Register our callback to the appropriate filter
add_filter('mce_buttons', 'add_styleselect_buttons');
// Callback function to insert 'styleselect' into the $buttons array
function add_styleselect_buttons($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
}



// Output shipping terms
add_action('woocommerce_after_add_to_cart_form','shipping_terms',1);
function shipping_terms() {
    // First - remove parent theme shipping terms output function
    remove_action('woocommerce_after_add_to_cart_form','shipping_2_weeks');
    global $sliding_child_options;
    echo '<div class="after-cartform"><strong>'.$sliding_child_options['shipping_terms_line1'].'</strong>'.$sliding_child_options['shipping_terms_line2'].'</div>';
}


if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name'=> 'Home advertorial (sliding_child)',
        'id' => 'home-ad-sliding-child',
        'before_widget' => '<div class="home-ad-sliding-child col-full">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name'=> 'Banner (sliding_child)',
        'id' => 'banner-sliding-child',
        'before_widget' => '<div class="banner-sliding-child">',
        'after_widget' => '</div>',
    ));
    register_sidebar(array(
        'name'=> 'Additional category description',
        'id' => 'archive-description-sliding-child',
        'before_widget' => '<div class="archive-description-sliding-child col-full">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name'=> 'Single product info block',
        'id' => 'single-product-sliding-child',
        'before_widget' => '<div class="single-product-sliding-child col-full">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}


// Add short product description to carousel loop item
add_action('woocommerce_after_shop_loop_item', 'sliding_child_template_loop_excerpt', 1);
function sliding_child_template_loop_excerpt() {
    global $product;
    $post = $product->get_post_data();
    echo '<div class="carousel-excerpt">' . $post->post_excerpt . '</div>';
}

// Add tabs to single product page
add_filter('woocommerce_product_tabs', 'sliding_child_woocommerce_product_tabs', 90);
function sliding_child_woocommerce_product_tabs($tabs = array())
{
    unset($tabs['description']);

    $post_id = 1598;
    $tabs[$post_id] = array(
        'title'    => get_the_title($post_id),
        'priority' => 28,
        'callback' => 'sliding_child_get_post_filtered_content',
    );
    $post_id = 1619;
    $tabs[$post_id] = array(
        'title'    => get_the_title($post_id),
        'priority' => 29,
        'callback' => 'sliding_child_get_post_filtered_content',
    );

    return $tabs;
}
if (!function_exists('sliding_child_get_post_filtered_content')) {
    function sliding_child_get_post_filtered_content($key, $tab)
    {
        $content = get_post($key)->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]>', $content);

        echo $content;
    }
}

add_action('woocommerce_archive_description', 'sliding_child_archive_description', 1);
function sliding_child_archive_description() {
    dynamic_sidebar('archive-description-sliding-child');
}


add_filter('woocommerce_available_payment_gateways', 'sliding_child_woocommerce_available_payment_gateways', 1);
function sliding_child_woocommerce_available_payment_gateways($gateways) {
	$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
	$chosen_shipping_method =$chosen_shipping_methods[0];
	$methods_to_check = array(
//		'local_delivery',
		'flat_rate:rembours-inclusief-standaard-bezorging',
		'flat_rate:rembours-inclusief-uitgebreide-bezorging'
	);
	if(in_array($chosen_shipping_method, $methods_to_check)) {
		foreach($gateways as $gateway => $value) {
			if('cod' != $gateway) {
				unset($gateways[$gateway]);
			}
		}
	} else {
		unset($gateways['cod']);
	}
	return $gateways;
}

add_action('woocommerce_before_single_product', 'sliding_child_woocommerce_before_single_product');
function sliding_child_woocommerce_before_single_product() {
	dynamic_sidebar('single-product-sliding-child');
}


// Change remove from cart image
function sliding_child_woocommerce_cart_item_remove_link( $html, $cart_item_key ) {
	$html = sprintf(
		'<a href="%s" class="remove" title="%s"><i class="fa fa-trash fa-lg"></i></a>',
		esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), 
		__( 'Remove this item', 'woocommerce' )
	);
	
	return $html;
}
add_filter( 'woocommerce_cart_item_remove_link', 'sliding_child_woocommerce_cart_item_remove_link', 10, 2 );


// Add "Wijzigen" button to checkout page
function sliding_child_woocommerce_order_button_html( $html ) {
	$html .= '<a href="' . WC()->cart->get_cart_url() . '" class="button alt2">Wijzigen</a>';

	return $html;
}
add_filter( 'woocommerce_order_button_html', 'sliding_child_woocommerce_order_button_html' );


// Remove the note under Order Total on cart page
add_filter( 'woocommerce_get_cart_tax', function() { return false; } );


// Make 'Apply Coupon' button blinking
function sliding_child_woocommerce_cart_coupon() {
?>
	<script>
		jQuery(document).ready(function($) {
			setInterval(function() {
				$('input[name="apply_coupon"]').delay(200).fadeOut('slow').fadeIn('');
			}, 1000);
		});
	</script>
<?php
}
add_action( 'woocommerce_cart_coupon', 'sliding_child_woocommerce_cart_coupon' );


/* Uncomment only on production
// ScroogeFrog code
$path = WP_CONTENT_DIR . '/scroogefrog_tcp.php';
include_once( $path );
function sliding_child_wp_footer() {
	$path = WP_CONTENT_DIR . '/scroogefrog_counter_container.php';
	include_once( $path );
}
add_action( 'wp_footer', 'sliding_child_wp_footer' );
*/


/**
 * Remove tax from order total output.
 */
function sliding_child_get_order_item_totals( $total_rows, $order ) {
	$total_rows['order_total']['value'] = $order->get_formatted_order_total();
	$total_rows['order_total']['value'] .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), __( 'VAT', 'woocommerce' ) );

	return $total_rows;
}
add_filter( 'woocommerce_cart_totals_order_total_html', 'sliding_child_cart_totals_order_total_html' );


/**
 * Remove tax from cart total output.
 */
function sliding_child_cart_totals_order_total_html( $value ) {
	$value = '<strong>' . WC()->cart->get_total() . '</strong> ';
	$value .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), __( 'VAT', 'woocommerce' ) );

	return $value;
}
add_filter( 'woocommerce_get_order_item_totals', 'sliding_child_get_order_item_totals', 10, 2 );