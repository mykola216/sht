<?php
/**
 * Plugin to add steigerhouttrend.nl specific features.
 *
 * -----------------------------------------------------------------------------
 *
 * Author: Aleksandr Levashov (w3craftsman@gmail.com)
 */

class Steigerhouttrend_Nl_MU_Plugin {
	static function instance() {
		static $Inst = null;

		if ( null === $Inst ) {
			$Inst = new Steigerhouttrend_Nl_MU_Plugin();
		}

		return $Inst;
	}

	private function __construct() {
		add_action( 'admin_init', array( $this, 'init_options' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'woocommerce_available_payment_gateways' ) );
		//add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'add_mailchimp_subscribe_checkbox' ) );
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'mailchimp_subscribe'), 1, 2 );
		add_action( 'woo_head', array( $this, 'ec_integration' ), 0 );
		//add_action( 'woo_foot', array( $this, 'conversion_integration' ), 999999 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'ec_integration_remove_from_cart' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts') );

		if ( !get_option( 'steigerhouttrend_options' ) ) {
			$defaults = array(
				'show_usps_sidebar'     => 'off',
				'working_time'          => 'ma-vr: 09.00-17.00u, za: 09.00-16.00u. Klantenservice 24/7',
				'home_band_text'        => 'Absolute kwaliteit van uit eigen fabriek en nergens goedkoper',
				'home_band_logo'        => '/wp-content/uploads/2014/12/logo-loodsxl.png',
				'slider_title'          => 'Slider Title',
				'slider_shortcode'      => '[metaslider id=2130]',
				//'conversion_id'         => '976593826',
				'subslider_text1'       => '100% kwaliteit uit eigen fabriek',
				'subslider_url1'        => '/onze-fabriek/',
				'subslider_text2'       => 'Ook voor maatwerk',
				'subslider_url2'        => '/bestellen-op-maat/',
				'subslider_text3'       => 'Snelle levering binnen 7 dagen',
				'subslider_url3'        => '/klantenservice/stappenplan/',
				'subslider_text4'       => 'Showroom',
				'subslider_url4'        => '/showroom/',
				'recent_products_url'   => '/nieuwe-steigerhouten-meubelen/',
				'recent_products_title' => 'Nieuwe steigerhouten meubelen  in ons assortiment 2015',
				'best_sellers_url'      => '/best-verkochte-steigerhouten-meubelen/',
				'best_sellers_title'    => 'Deze maand best verkochte steigerhouten meubelen',
				'facebook_url'          => 'https://www.facebook.com/steigerhouttrends',
				'googleplus_url'        => 'https://plus.google.com/101431661923115817801/about',
				'instagram_url'         => 'http://instagram.com/steigerhouttrend/',
				'twitter_url'           => 'https://twitter.com/SHTrend',
				'youtube_url'           => 'http://youtube.com/',
				'pinterest_url'         => 'http://pinterest.com/steigerhout4u/',
				'production-title'      => 'Steigerhouten meubelen gratis op maat laten ontwerpen door onze meubelmakers',
				'production-text'       => 'Geef ons uw ontwerp >> ',
				'production-img-url'    => '/wp-content/uploads/2014/12/steigerhouten-meubelen-fabriek.jpg',
				'production-page-url'   => '/bestellen-op-maat/',
				'shipping_terms_line1'  => 'Binnen 1 week bezorgd voor slechts €59,-',
				'shipping_terms_line2'  => 'Uiteraard leveren we altijd op afspraak',
				'info_block_text1'      => 'Klanten waarderen ons gemiddeld met een 9 voor onze producten en service.',
				'info_block_text2'      => 'Binnen 7 dagen bezorgd voor slechts €59. Uiteraard leveren we altijd op afspraak. Montage en etage leveringen zijn ook mogelijk.',
				'info_block_text3'      => 'Zonder afspraak is het mogelijk om de showroom te bezoeken. Heb je advies nodig bij je bestelling? Bel onze klantenservice 050 3642594 of mail info@steigerhouttrend.nl',
				'info_block_text4'      => 'Bij Steigerhouttrend maken we alle meubels op bestelling. We leveren maatwerk.',
				'read_more_text'      => 'Read More',
				'hide_more_text'      => 'Hide',
				'coupon_btn_text'       => 'Kortingsbon invullen',
			);
			$sliding_child_options = get_option( 'sliding_child_options' );
			$steigerhouttrend_options = (is_array($sliding_child_options)) ? array_merge( $sliding_child_options, $defaults ) : $sliding_child_options;
			update_option( 'steigerhouttrend_options', $steigerhouttrend_options );
		} else {
			$steigerhouttrend_options = get_option( 'steigerhouttrend_options' );
		}
		$GLOBALS['st_options'] = $steigerhouttrend_options;


		add_filter( 'wp_mail_from_name', function(){return 'SteigerHoutTrend';} );
		add_filter( 'wp_mail_from', function(){return 'info@steigerhouttrend.nl';} );
	}

	public function enqueue_scripts() {
		// Add fancyBox
		wp_enqueue_style( 'stnl_fancyBox_css', WPMU_PLUGIN_URL . '/fancybox/jquery.fancybox.css', array(), '2.1.5' );
		wp_enqueue_script( 'stnl_fancyBox_js', WPMU_PLUGIN_URL . '/fancybox/jquery.fancybox.pack.js', 'jquery', '2.1.5', true );
	}

	// Register_setting
	public function init_options() {
		register_setting(
			'steigerhouttrend_options',
			'steigerhouttrend_options',
			array( $this, 'validate_options' )
		);
	}

	// Add menu page
	public function add_options_page() {
		add_options_page(
			get_bloginfo('name') . ' Site Options',
			get_bloginfo('name') . ' Site Options',
			'manage_options',
			'steigerhouttrend_options',
			array( $this, 'output_options_page' )
		);
	}

	// Draw the menu page itself
	public function output_options_page() {
		include( 'templates/settings.php' );
	}

	// Sanitize and validate input. Accepts an array, return a sanitized array.
	public function validate_options( $options ) {
		$options['show_usps_sidebar']     = empty( $options['show_usps_sidebar'] ) ? 'off' : 'on';
		$options['working_time']          = wp_filter_nohtml_kses( $options['working_time'] );
		$options['slider_title']          = wp_filter_nohtml_kses( $options['slider_title'] );
		$options['slider_shortcode']      = wp_filter_nohtml_kses( $options['slider_shortcode'] );
		//$options['conversion_id']         = wp_filter_nohtml_kses( $options['conversion_id'] ) ;
		$options['facebook_url']          = wp_filter_nohtml_kses( $options['facebook_url']) ;
		$options['googleplus_url']        = wp_filter_nohtml_kses( $options['googleplus_url'] );
		$options['instagram_url']         = wp_filter_nohtml_kses( $options['instagram_url'] );
		$options['twitter_url']           = wp_filter_nohtml_kses( $options['twitter_url'] );
		$options['youtube_url']           = wp_filter_nohtml_kses( $options['youtube_url'] );
		$options['pinterest_url']         = wp_filter_nohtml_kses( $options['pinterest_url'] );
		$options['subslider_text1']       = wp_filter_nohtml_kses( $options['subslider_text1'] );
		$options['subslider_text2']       = wp_filter_nohtml_kses( $options['subslider_text2'] );
		$options['subslider_text3']       = wp_filter_nohtml_kses( $options['subslider_text3'] );
		$options['subslider_text4']       = wp_filter_nohtml_kses( $options['subslider_text4'] );
		$options['subslider_url1']        = wp_filter_nohtml_kses( $options['subslider_url1'] );
		$options['subslider_url2']        = wp_filter_nohtml_kses( $options['subslider_url2'] );
		$options['subslider_url3']        = wp_filter_nohtml_kses( $options['subslider_url3'] );
		$options['subslider_url4']        = wp_filter_nohtml_kses( $options['subslider_url4'] );
		$options['recent_products_url']   = wp_filter_nohtml_kses( $options['recent_products_url'] );
		$options['recent_products_title'] = wp_filter_nohtml_kses( $options['recent_products_title'] );
		$options['best_sellers_url']      = wp_filter_nohtml_kses( $options['best_sellers_url'] );
		$options['best_sellers_title']    = wp_filter_nohtml_kses( $options['best_sellers_title'] );
//		$options['production-title']      = wp_filter_nohtml_kse s( $options['production-title'] );
//		$options['production-text']       = wp_filter_nohtml_kse s( $options['production-text'] );
//		$options['production-img-url']    = wp_filter_nohtml _kses( $options['production-img-url'] );
//		$options['production-page-url']   = wp_filter_nohtm l_kses( $options['production-page-url'] );
		$options['home_band_text']        = wp_filter_nohtml_kses( $options['home_band_text'] );
		$options['home_band_logo']        = wp_filter_nohtml_kses( $options['home_band_logo'] );
		$options['shipping_terms_line1']  = wp_filter_nohtml_kses( $options['shipping_terms_line1'] );
		$options['shipping_terms_line2']  = wp_filter_nohtml_kses( $options['shipping_terms_line2'] );
		$options['info_block_text1']      = wp_filter_nohtml_kses( $options['info_block_text1'] );
		$options['info_block_text2']      = wp_filter_nohtml_kses( $options['info_block_text2'] );
		$options['info_block_text3']      = wp_filter_nohtml_kses( $options['info_block_text3'] );
		$options['info_block_text4']      = wp_filter_nohtml_kses( $options['info_block_text4'] );
		$options['read_more_text']      = wp_filter_nohtml_kses( $options['read_more_text'] );
		$options['hide_more_text']      = wp_filter_nohtml_kses( $options['hide_more_text'] );
		$options['coupon_btn_text']       = wp_filter_nohtml_kses( $options['coupon_btn_text'] );
		$options['coupon_text']       = wp_filter_nohtml_kses( $options['coupon_text'] );

		return $options;
	}

	// Payment gateways selection logic.
	public function woocommerce_available_payment_gateways( $gateways ) {
		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$chosen_shipping_method = $chosen_shipping_methods[0];
		$methods_to_check = array(
			'flat_rate:6',
			'flat_rate:7'
		);

		if ( in_array( $chosen_shipping_method, $methods_to_check ) ) {
			foreach ( $gateways as $gateway => $value ) {
				if ( 'cod' != $gateway ) {
					unset( $gateways[ $gateway ] );
				}
			}
		}
		else {
				unset( $gateways['cod'] );
		}

		return $gateways;
	}

	// Mailchimp subscribtion.
	public function add_mailchimp_subscribe_checkbox() {
		include( 'templates/mailchimp-subscribe-checkbox.php' );
	}
	public function mailchimp_subscribe( $order_id, $posted ) {
		if ( isset( $_POST['sliding_child_mailchimp_subscribe'] ) ) {
			$url = 'http://steigerhouttrend.us9.list-manage.com/subscribe/post';
			$data = array(
				'u'      => 'd48352b93a6be86fe761e6167',
				'id'     => '261f611fb3',
				'MERGE0' => $posted['billing_email'],
				'MERGE1' => $posted['billing_first_name'],
				'MERGE2' => $posted['billing_last_name'],
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
/*
//steigerhouttrend.us9.list-manage.com/subscribe/post?u=d48352b93a6be86fe761e6167&amp;id=261f611fb3" method="post"
<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
<input type="text" name="b_d48352b93a6be86fe761e6167_261f611fb3" tabindex="-1" value="">
<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
*/

			$context = stream_context_create( $options );
			file_get_contents( $url, false, $context );
		}
	}

	// Google Enhanced Ecommerce integration
	public function ec_integration() {
		include( 'templates/ec-integration-begin.php' );
		if ( is_product() ) {
			include( 'templates/ec-integration-singular-product.php' );
		} elseif ( is_cart() ) {
			include( 'templates/ec-integration-cart.php' );
		} elseif ( is_wc_endpoint_url( 'order-received' ) ) {
			include( 'templates/ec-integration-thankyou.php' );
		} elseif ( is_checkout() ) {
			include( 'templates/ec-integration-checkout.php' );
		}
		include( 'templates/ec-integration-end.php' );
	}

	// Динамический ремаркетинг
	public function conversion_integration() {
		global $st_options;
		if ( is_product() ) {
			include( 'templates/conversion-integration-singular-product.php' );
		} elseif ( is_cart() ) {
			include( 'templates/conversion-integration-cart.php' );
		} elseif ( is_wc_endpoint_url( 'order-received' ) ) {
			include( 'templates/conversion-integration-thankyou.php' );
		} elseif ( is_checkout() ) {
			include( 'templates/conversion-integration-checkout.php' );
		}
		include( 'templates/conversion-integration-end.php' );
	}

	public function ec_integration_remove_from_cart( $anchor, $cart_item_key ) {
		$cart_item = WC()->cart->cart_contents[ $cart_item_key ];
		$anchor = sprintf(
			'<a onclick="remove_from_cart(this);" href="javascript:void(0)" data-item="%s" class="remove" title="%s">&times;</a>',
			esc_attr(
				json_encode(
					array(
						'id'       => $cart_item['product_id'],
						'name'     => $cart_item['data']->post->post_title,
						'price'    => $cart_item['data']->price,
						'quantity' => $cart_item['quantity'],
						'href'     => esc_url( WC()->cart->get_remove_url( $cart_item_key ) )
					)
				)
			),
			__( 'Remove this item', 'woocommerce' )
		);
		return $anchor;
	}
}

Steigerhouttrend_Nl_MU_Plugin::instance();
