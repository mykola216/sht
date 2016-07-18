<?php

/**
 * Title: WordPress pay Charitable payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Extensions_Charitable_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * The donation ID
	 */
	private $donation_id;

	/**
	 * Processor
	 */
	private $processor;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Charitable payment data object.
	 *
	 * @param mixed $processor
	 */
	public function __construct( $donation_id, $processor ) {
		parent::__construct();

		$this->donation_id = $donation_id;
		$this->processor   = $processor;

		$this->user_data = $processor->get_donation_data_value( 'user' );
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_source()
	 * @return string
	 */
	public function get_source() {
		return 'charitable';
	}

	public function get_source_id() {
		return $this->donation_id;
	}

	//////////////////////////////////////////////////

	public function get_title() {
		return sprintf( __( 'Charitable donation %s', 'pronamic_ideal' ), $this->get_order_id() );
	}

	/**
	 * Get description
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		return sprintf( __( 'Charitable donation %s', 'pronamic_ideal' ), $this->get_order_id() );
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
		$donation = new Charitable_Donation( $this->donation_id );

		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->get_order_id() );
		$item->setDescription( $this->get_description() );
		// @see http://plugins.trac.wordpress.org/browser/woocommerce/tags/1.5.2.1/classes/class-wc-order.php#L50
		$item->setPrice( $donation->get_total_donation_amount() );
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
		return charitable_get_currency();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		return $this->user_data['email'];
	}

	public function get_customer_name() {
		return $this->user_data['first_name'] . ' ' . $this->user_data['last_name'];
	}

	public function get_address() {
		return $this->user_data['address'];
	}

	public function get_city() {
		return $this->user_data['city'];
	}

	public function get_zip() {
		return $this->user_data['postcode'];
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
		return charitable_get_permalink( 'donation_receipt_page', array( 'donation_id' => $this->donation_id ) );
	}

	public function get_cancel_url() {
		return home_url();
	}

	public function get_success_url() {
		return charitable_get_permalink( 'donation_receipt_page', array( 'donation_id' => $this->donation_id ) );
	}

	public function get_error_url() {
		return home_url();
	}
}
