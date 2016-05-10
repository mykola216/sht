<?php

/**
 * Title: WordPress pay Give payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.1
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Extensions_Give_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * The donation ID
	 */
	private $donation_id;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Charitable payment data object.
	 *
	 * @param mixed $processor
	 */
	public function __construct( $donation_id ) {
		parent::__construct();

		$this->donation_id = $donation_id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'give';
	}

	public function get_source_id() {
		return $this->donation_id;
	}

	//////////////////////////////////////////////////

	public function get_title() {
		return sprintf( __( 'Give donation %s', 'pronamic_ideal' ), $this->get_order_id() );
	}

	/**
	 * Get description
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return sprintf( __( 'Give donation %s', 'pronamic_ideal' ), $this->get_order_id() );
	}

	/**
	 * Get order ID
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		return $this->donation_id;
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

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( $this->get_description() );
		// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php#L50
		$item->setPrice( give_get_payment_amount( $this->donation_id ) );
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
		return give_get_payment_currency_code( $this->donation_id );
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		return give_get_payment_user_email( $this->donation_id );
	}

	public function get_customer_name() {
		$user_info = give_get_payment_meta_user_info( $this->donation_id );

		return $user_info['first_name'] . ' ' . $user_info['last_name'];
	}

	public function get_address() {
		$address = null;

		$user_info = give_get_payment_meta_user_info( $this->donation_id );

		if ( ! empty( $user_info['address'] ) ) {
			$address = sprintf(
				'%s %s',
				$user_info['address']['line1'],
				$user_info['address']['line2']
			);
		}

		return $address;
	}

	public function get_city() {
		$city = null;

		$user_info = give_get_payment_meta_user_info( $this->donation_id );

		if ( ! empty( $user_info['address'] ) ) {
			$city = $user_info['address']['city'];
		}

		return $city;
	}

	public function get_zip() {
		$zip = null;

		$user_info = give_get_payment_meta_user_info( $this->donation_id );

		if ( ! empty( $user_info['address'] ) ) {
			$zip = $user_info['address']['zip'];
		}

		return $zip;
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
	public function get_normal_return_url() {}

	public function get_cancel_url() {}

	public function get_success_url() {}

	public function get_error_url() {}
}
