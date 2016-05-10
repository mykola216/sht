<?php defined( 'ABSPATH' ) || die(); ?>

<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-price"><?php _e( 'Price', 'woocommerce' ); ?></th>
			<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = $cart_item['data'];
				$product_id = $cart_item['product_id'];

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
					?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<td class="product-thumbnail">
								<?php
									printf(
										'<a href="%s">%s</a>',
										esc_url( $_product->get_permalink( $cart_item ) ),
										$_product->get_image()
									);
								?>
							</td>

							<td class="product-name">
								<?php
									echo sprintf( '<a href="%s">%s </a>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() );
									// Meta data
									echo WC()->cart->get_item_data( $cart_item );
								?>
							</td>

							<td class="product-price">
								<?php echo WC()->cart->get_product_price( $_product ); ?>
							</td>

							<td class="product-quantity">
								<?php echo $cart_item['quantity']; ?>
							</td>

							<td class="product-subtotal">
								<?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
							</td>
						</tr>
					<?php
				}
			}
		?>
	</tbody>
</table>

