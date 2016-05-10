<?php defined('ABSPATH') || die();

add_action( 'wp_enqueue_scripts', 'wp_enqueue_scripts_5TET59' );
// добавить кнопку
add_action( 'woocommerce_after_add_to_cart_button', 'add_to_wishlist_B2XB94' );
// добавить "сердечко"
add_action( 'woocommerce_after_shop_loop_item', 'add_to_wishlist_B2XB94', -1 );
// выбор шаблона: кнопка или "сердечко"
add_filter( 'yith_wcwl_locate_template', 'locate_template_8W61AH' );
// добавить ссылку на страницу "Wishlist" возле ссылки на страницу "Корзина"
add_filter( 'w3cm-microcart-widget-content', 'link_to_wishlist_page_8ZRH37' );
// изменить текст на кнопке "Добавить в корзину" на странице "Wishlist"
add_filter( 'gravityforms_add_to_cart_text', 'add_to_cart_text_ES1K2A' );
// добавить "сердечко" к каждому товару на странице "Корзина"
add_filter( 'woocommerce_cart_item_remove_link', 'cart_item_remove_link_AT1XTF', PHP_INT_MAX, 2 );



function wp_enqueue_scripts_5TET59() {
	$url = str_replace( array(ABSPATH, '\\'), '/', __DIR__);

	wp_enqueue_style( 'wishlist', $url . '/css/wishlist.css' );
	if ( is_single() ) {
		wp_enqueue_style( 'wishlist-button', $url . '/css/wishlist-button.css', array( 'theme-child-style' ) );
	} elseif ( is_page( 'verlanglijst' ) ) {
		wp_enqueue_style( 'wishlist-page', $url . '/css/wishlist-page.css', array( 'theme-child-style' ) );
	}
}

function add_to_wishlist_B2XB94() {
	echo do_shortcode('[yith_wcwl_add_to_wishlist]');
}

function locate_template_8W61AH( $located ) {
	if ( doing_action('woocommerce_after_add_to_cart_button') ) {
		$located = __DIR__ . '/partials/wishlist-button.php';
	} elseif ( doing_action('woocommerce_after_shop_loop_item') ) {
		$located = __DIR__ . '/partials/wishlist-heart.php';
	} elseif ( doing_filter('woocommerce_cart_item_remove_link') ) {
		$located = __DIR__ . '/partials/wishlist-heart.php';
	}

	return $located;
}

function link_to_wishlist_page_8ZRH37( $widget_content ) {
//	if ( YITH_WCWL()->count_all_products() ) {
	$widget_content = sprintf(
		'<a href="%s" id="cart-widget-wishlist-link">%s</a>%s',
		YITH_WCWL()->get_wishlist_url(),
		__('Wishlist', 'yith-woocommerce-wishlist'),
		$widget_content
	);
//	}

	return $widget_content;
}

function add_to_cart_text_ES1K2A( $label ) {
	if ( is_page( 'verlanglijst' ) ) {
		$label = 'Naar product pagina ➤';
	}

	return $label;
}

function cart_item_remove_link_AT1XTF( $link, $item_key ) {
	$cart_item = WC()->cart->get_cart_item( $item_key );
	$link .= do_shortcode('[yith_wcwl_add_to_wishlist product_id=' . $cart_item['product_id'] . ']');
	return $link;
}
