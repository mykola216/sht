<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly
?>

<?php _e( 'Dear Customer', 'w3cm-pending-order-email' ); ?>,<br />
<br />
<?php _e( 'Something went wrong when finalizing your payment, the goods are reserved for you, you only need to make the payment so we can process your order.', 'w3cm-pending-order-email' ); ?><br />
<?php printf(
	__( 'Click <a href="%s">HERE</a> to finalize your payment after all. You can again choose our payment methods.', 'w3cm-pending-order-email' ),
	esc_url( $order->get_checkout_payment_url() )
); ?><br />
<br />
<?php printf(
	__( 'If you have any questions, please directly contact our <a href="%s">customer service</a> by phone %s or email %s.', 'w3cm-pending-order-email' ),
	'http://steigerhouttrend.nl/klantenservice/',
	'+31(0)502 301 066',
	'info@steigerhouttrend.nl'
); ?><br />
<br />
<?php _e( 'Yours sincerely', 'w3cm-pending-order-email' ); ?>,<br />
<?php _e( 'SteigerhoutTrend team', 'w3cm-pending-order-email' ); ?>