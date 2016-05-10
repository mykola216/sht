<?php
foreach ( WC()->cart->cart_contents as $index => $cart_item ) {
?>
	ga('ec:addProduct', {
		'id':       '<?php echo esc_js( $cart_item['product_id'] ); ?>',
		'name':     '<?php echo esc_js( $cart_item['data']->post->post_title ); ?>',
		'price':    '<?php echo esc_js( $cart_item['data']->price ); ?>',
		'quantity': '<?php echo esc_js( $cart_item['quantity'] ); ?>',
	});
<?php
}
?>
	ga('ec:setAction','checkout', {'step': 2});
	ga('send', 'pageview');
