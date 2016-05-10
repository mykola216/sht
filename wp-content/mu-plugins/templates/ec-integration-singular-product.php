	ga('ec:addProduct', {
		'id':   '<?php echo esc_js( the_ID() ); ?>',
		'name': '<?php echo esc_js( the_title() ); ?>',
	});
	ga('ec:setAction', 'detail');
	ga('send', 'pageview');

	jQuery(document).ready(function($) {
		$('button.single_add_to_cart_button').click(function() {
			ga('ec:addProduct', {
				'id':       '<?php echo esc_js( the_ID() ); ?>',
				'name':     '<?php echo esc_js( the_title() ); ?>',
				'price':    $('.formattedTotalPrice').text().replace(',', '.').replace(/[^\d\.]/g, ''),
				'quantity': parseInt($('input.qty').val())
			});
			ga('ec:setAction', 'add');
			ga('send', 'event', 'UX', 'click', 'add to cart');
		});
	});
