<?php
function change_name($name) {
    return 'SteigerHoutTrend';
}

function change_email($email) {
    return 'info@steigerhouttrend.nl';
}

add_filter('wp_mail_from_name','change_name');
add_filter('wp_mail_from','change_email');


// Remove right sidebar from all pages
add_filter( 'body_class','sliding_child_set_layout', 99999999 );
function sliding_child_set_layout($classes)
{
    global $woo_options;
    $woo_options['woo_layout'] = 'layout-full';
    return $classes;
}

wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');


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
    $options = get_option('sliding_child_options');
    echo '<div class="after-cartform"><strong>'.$options[shipping_terms_line1].'</strong>'.$options[shipping_terms_line2].'</div>';
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



add_action('woocommerce_after_checkout_billing_form', 'sliding_child_woocommerce_after_checkout_billing_form');
function sliding_child_woocommerce_after_checkout_billing_form($checkout) {
?>
    <label class="checkbox">
        Graag wil ik op de hoogte gehouden worden van nieuwe acties
        <input class="input-checkbox" checked="checked" type="checkbox" name="sliding_child_mailchimp_subscribe" value="1">
    </label>
<?php
}
add_action('woocommerce_checkout_order_processed', 'sliding_child_woocommerce_checkout_order_processed', 20, 2);
function sliding_child_woocommerce_checkout_order_processed($order_id, $posted) {
    if(isset($_POST['sliding_child_mailchimp_subscribe'])) {
        $url = 'http://steigerhouttrend.us9.list-manage.com/subscribe/post';
        $data = array(
            'u' => 'd48352b93a6be86fe761e6167',
            'id' => '261f611fb3',
            'MERGE0' => $posted['billing_email'],
            'MERGE1' => $posted['billing_first_name'],
            'MERGE2' => $posted['billing_last_name'],
            'b_d48352b93a6be86fe761e6167_261f611fb3' => ''
        );
        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
    }
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



// Интеграция WooCommerce в Google Analytics
function devise_wc_ga_integration( $order_id ) {
	$order = new WC_Order( $order_id ); ?>
	
	<script type="text/javascript">
	ga('require', 'ecommerce', 'ecommerce.js'); // Подгружаем плагин отслеживания электронной коммерции
		
		// Данные о транзакциях
		ga('ecommerce:addTransaction', {
			'id': '<?php echo $order_id;?>',
			'affiliation': '<?php echo get_option( "blogname" );?>',
			'revenue': '<?php echo $order->get_total();?>',
			'shipping': '<?php echo $order->get_total_shipping();?>',
			'tax': '<?php echo $order->get_total_tax();?>',
			'currency': '<?php echo get_woocommerce_currency();?>'
		});

	
	<?php
		//Данные о товарах
	if ( sizeof( $order->get_items() ) > 0 ) {
		foreach( $order->get_items() as $item ) {
			$product_cats = get_the_terms( $item["product_id"], 'product_cat' );
				if ($product_cats) { 
					$cat = $product_cats[0];
				} ?>
			ga('ecommerce:addItem', {
				'id': '<?php echo $order_id;?>',
				'name': '<?php echo $item['name'];?>',
				'sku': '<?php echo get_post_meta($item["product_id"], '_sku', true);?>',
				'category': '<?php echo $cat->name;?>',
				'price': '<?php echo $item['line_subtotal'];?>',
				'quantity': '<?php echo $item['qty'];?>',
				'currency': '<?php echo get_woocommerce_currency();?>'
			});
	<?php
		}	
	} ?>
		ga('ecommerce:send');
		</script>
<?php }
add_action( 'woocommerce_thankyou', 'devise_wc_ga_integration' );


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