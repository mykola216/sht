<?php defined( 'ABSPATH' ) || die();
$order = wc_get_order( $GLOBALS['order-received'] );
$ecomm_prodid = array();
foreach ( $order->get_items() as $index => $cart_item ) {
	$ecomm_prodid[] = $cart_item['product_id'];
}
?>

<script type="text/javascript">
	var google_tag_params = {
		flight_destid: '<?php echo esc_js( implode( ',', $ecomm_prodid ) ); ?>',
		flight_pagetype: 'purchase',
		flight_totalvalue: <?php echo esc_js( $order->get_total() ); ?>
	};
</script>
