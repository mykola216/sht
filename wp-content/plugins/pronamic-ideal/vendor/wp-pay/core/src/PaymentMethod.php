<?php

/**
 * Title: Payment method
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version unreleased
 * @since unreleased
 */
abstract class Pronamic_WP_Pay_PaymentMethod {
	/**
	 * The ID of this payment method.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The name of this payment method.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Is active flag.
	 *
	 * @var boolean
	 */
	protected $is_active = true;

	/////////////////////////////////////////////////

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function is_active() {
		return $this->is_active;
	}
}
