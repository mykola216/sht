<?php defined('ABSPATH') || die();

// добавляю новый тип письма - предложение
add_filter( 'woocommerce_email_classes', 'woocommerce_email_classes_WQL4MDBU' );
// добавляю предложение в перечень возможных писем для отправки
add_filter( 'woocommerce_resend_order_emails_available', 'woocommerce_resend_order_emails_available_WQL4MDBU' );



function woocommerce_email_classes_WQL4MDBU( $emails ) {

	$emails['WC_Email_Customer_Offer'] = include( 'class-wc-email-customer-offer.php' );

	return $emails;
}

function woocommerce_resend_order_emails_available_WQL4MDBU( $available_emails ) {

	$available_emails[] = 'customer_offer';

	return $available_emails;
}
