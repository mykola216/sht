	/* remove via google analytics */
	remove_from_cart = function(element) {
		var cartItem = JSON.parse(element.dataset.item);
		ga('ec:addProduct', {
			'id':       cartItem.id,
			'name':     cartItem.name,
			'price':    cartItem.price,
			'quantity': cartItem.qty
		});
		ga('ec:setAction', 'remove');
		ga('send', 'event', 'UX', 'click', 'remove from cart', {
			'hitCallback': function() {
				//document.location = cartItem.href;
			}
		});
		document.location = cartItem.href;
	}
</script>
