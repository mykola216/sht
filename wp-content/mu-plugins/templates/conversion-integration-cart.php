<?php defined( 'ABSPATH' ) || die();
$ecomm_prodid = array();
foreach ( WC()->cart->cart_contents as $index => $cart_item ) {
	$ecomm_prodid[] = $cart_item['product_id'];
}
?>

<script type="text/javascript">
	var google_tag_params = {
		flight_destid: '<?php echo esc_js( implode( ',', $ecomm_prodid ) ); ?>',
		flight_pagetype: 'cart',
		flight_totalvalue: <?php echo esc_js( WC()->cart->total ); ?>
	};
</script>
