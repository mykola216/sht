<?php defined('ABSPATH') || die();

// выбор шаблона письма для счета-фактуры клиенту
add_filter( 'wc_get_template', 'get_customer_invoice_template_AN66UJ', 10, 2 );



function get_customer_invoice_template_AN66UJ( $located, $template_name ) {
	if ( 'emails/customer-invoice.php' == $template_name ) {
		$located = __DIR__ . '/partials/customer-invoice.php';
	}

	return $located;
}
