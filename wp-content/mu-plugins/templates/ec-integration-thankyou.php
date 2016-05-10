<?php
$order = wc_get_order( $GLOBALS['order-received'] );
foreach ( $order->get_items() as $index => $cart_item ) {
	$price = ( $cart_item['line_tax'] + $cart_item['line_total'] ) / $cart_item['qty'];
?>
	ga('ec:addProduct', {
		'id':       '<?php echo esc_js( $cart_item['product_id'] ); ?>',
		'name':     '<?php echo esc_js( $cart_item['name'] ); ?>',
		'price':    '<?php echo esc_js( wc_format_decimal( $price, '', true ) ); ?>',
		'quantity': '<?php echo esc_js( $cart_item['qty'] ); ?>',
	});
<?php
}
?>
	ga('ec:setAction', 'purchase', {
		'id':          '<?php echo $order->id; ?>',
		'affiliation': '<?php echo get_option("blogname"); ?>',
		'revenue':     '<?php echo $order->get_total(); ?>',
		'tax':         '<?php echo $order->get_total_tax(); ?>',
		'shipping':    '<?php echo $order->get_total_shipping(); ?>',
	});
	ga('send', 'pageview');
