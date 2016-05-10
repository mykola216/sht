<?php

/**
 * Title: WooCommerce payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.1.8
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Extensions_WooCommerce_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * Order
	 *
	 * @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php
	 * @var WC_Order
	 */
	private $order;

	/**
	 * Gateway
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/v2.1.3/includes/abstracts/abstract-wc-payment-gateway.php
	 * @var WC_Payment_Gateway
	 */
	private $gateway;

	/**
	 * Description
	 *
	 * @var string
	 */
	private $description;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an WooCommerce iDEAL data proxy
	 *
	 * @param WC_Order $order
	 */
	public function __construct( $order, $gateway, $description = null ) {
		parent::__construct();

		$this->order   = $order;
		$this->gateway = $gateway;

		$this->description = ( null === $description ) ? self::get_default_description() : $description;
	}

	//////////////////////////////////////////////////
	// Specific WooCommerce
	//////////////////////////////////////////////////

	/**
	 * Get default description
	 *
	 * @return string
	 */
	public static function get_default_description() {
		return __( 'Order {order_number}', 'pronamic_ideal' );
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'woocommerce';
	}

	public function get_source_id() {
		return $this->order->id;
	}

	//////////////////////////////////////////////////

	public function get_title() {
		return sprintf( __( 'WooCommerce order %s', 'pronamic_ideal' ), $this->get_order_id() );
	}

	/**
	 * Get description
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		// @see https://github.com/woothemes/woocommerce/blob/v2.0.19/classes/emails/class-wc-email-new-order.php
		$find    = array();
		$replace = array();

		$find[]    = '{blogname}';
		$replace[] = $this->get_blogname();

		$find[]    = '{site_title}';
		$replace[] = $this->get_blogname();

		$find[]    = '{order_date}';
		$replace[] = date_i18n( Pronamic_WP_Pay_Extensions_WooCommerce_WooCommerce::get_date_format(), strtotime( $this->order->order_date ) );

		$find[]    = '{order_number}';
		$replace[] = $this->order->get_order_number();

		// Description
		$description = str_replace( $find, $replace, $this->description );

		return $description;
	}

	/**
	 * Get order ID
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		// @see https://github.com/woothemes/woocommerce/blob/v1.6.5.2/classes/class-wc-order.php#L269
		$order_id = $this->order->get_order_number();

		/*
		 * An '#' charachter can result in the following iDEAL error:
		 * code             = SO1000
		 * message          = Failure in system
		 * detail           = System generating error: issuer
		 * consumer_message = Paying with iDEAL is not possible. Please try again later or pay another way.
		 *
		 * Or in case of Sisow:
		 * <errorresponse xmlns="https://www.sisow.nl/Sisow/REST" version="1.0.0">
		 *     <error>
		 *         <errorcode>TA3230</errorcode>
		 *         <errormessage>No purchaseid</errormessage>
		 *     </error>
		 * </errorresponse>
		 *
		 * @see http://wcdocs.woothemes.com/user-guide/extensions/functionality/sequential-order-numbers/#add-compatibility
		 *
		 * @see page 30 http://pronamic.nl/wp-content/uploads/2012/09/iDEAL-Merchant-Integratie-Gids-NL.pdf
		 *
		 * The use of characters that are not listed above will not lead to a refusal of a batch or post, but the
		 * character will be changed by Equens (formerly Interpay) to a space, question mark or asterisk. The
		 * same goes for diacritical characters (à, ç, ô, ü, ý etcetera).
		 */
		$order_id = str_replace( '#', '', $order_id );

		return $order_id;
	}

	/**
	 * Get items
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_items()
	 * @return Pronamic_IDeal_Items
	 */
	public function get_items() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Price
		// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php#L50
		$price = $this->order->order_total;

		// Support part payments with WooCommerce Deposits plugin
		// @since 1.1.6
		if ( method_exists( $this->order, 'has_status' ) && $this->order->has_status( 'partially-paid' ) && isset( $this->order->wc_deposits_remaining ) ) {
			$price = $this->order->wc_deposits_remaining;
		}

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( $this->get_description() );
		$item->setPrice( $price );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	/**
	 * Get currency
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_currency_alphabetic_code()
	 * @return string
	 */
	public function get_currency_alphabetic_code() {
		// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/admin/woocommerce-admin-settings.php#L32
		return get_option( 'woocommerce_currency' );
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php#L30
		return $this->order->billing_email;
	}

	public function get_customer_name() {
		// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php#L21
		return $this->order->billing_first_name . ' ' . $this->order->billing_last_name;
	}

	public function get_address() {
		// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php#L24
		return $this->order->billing_address_1;
	}

	public function get_city() {
		return $this->order->billing_city;
	}

	public function get_zip() {
		// http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php#L26
		return $this->order->billing_postcode;
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	/**
	 * Get normal return URL.
	 *
	 * @see https://github.com/woothemes/woocommerce/blob/v2.1.3/includes/abstracts/abstract-wc-payment-gateway.php#L52
	 * @return string
	 */
	public function get_normal_return_url() {
		return $this->gateway->get_return_url( $this->order );
	}

	public function get_cancel_url() {
		$url = $this->order->get_cancel_order_url();

		/*
		 * The WooCommerce developers changed the `get_cancel_order_url` function in version 2.1.0.
		 * In version 2.1.0 the WooCommerce plugin uses the `wp_nonce_url` function. This WordPress
		 * function uses the WordPress `esc_html` function. The `esc_html` function converts specials
		 * characters to HTML entities. This is causing redirecting issues, so we decode these back
		 * with the `wp_specialchars_decode` function.
		 *
		 * @see https://github.com/WordPress/WordPress/blob/4.1/wp-includes/functions.php#L1325-L1338
		 * @see https://github.com/WordPress/WordPress/blob/4.1/wp-includes/formatting.php#L3144-L3167
		 * @see https://github.com/WordPress/WordPress/blob/4.1/wp-includes/formatting.php#L568-L647
		 *
		 * @see https://github.com/woothemes/woocommerce/blob/v2.1.0/includes/class-wc-order.php#L1112
		 *
		 * @see https://github.com/woothemes/woocommerce/blob/v2.0.20/classes/class-wc-order.php#L1115
		 * @see https://github.com/woothemes/woocommerce/blob/v2.0.0/woocommerce.php#L1693-L1703
		 *
		 * @see https://github.com/woothemes/woocommerce/blob/v1.6.6/classes/class-wc-order.php#L1013
		 * @see https://github.com/woothemes/woocommerce/blob/v1.6.6/woocommerce.php#L1630
		 */
		$url = wp_specialchars_decode( $url );

		return $url;
	}

	public function get_success_url() {
		return $this->get_normal_return_url();
	}

	public function get_error_url() {
		return $this->order->get_checkout_payment_url();
	}
}
