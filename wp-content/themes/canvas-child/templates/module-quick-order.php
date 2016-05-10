<?php defined( 'ABSPATH' ) || die();
/**
 * Quick order module.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

global $woocommerce;
?>

<a href="#" id="quick-order" class="checkout-button button"><?php _e( 'Quick order', 'woocommerce' ); ?></a>
<script>
	jQuery(document).ready(function($) {
		$('#quick-order').click(function(e) {
			e.preventDefault();
			var admin_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
			var data = {
				'action':             'woocommerce_checkout',
				'_wpnonce':           '<?php echo wp_create_nonce('woocommerce-process_checkout'); ?>',
				'billing_country':    '<?php echo $woocommerce->customer->get_country(); ?>',
				'billing_first_name': 'billing_first_name',
				'billing_last_name':  'billing_last_name',
				'billing_address_1':  'billing_address_1',
				'billing_city':       '<?php echo $woocommerce->customer->get_city(); ?>',
				'billing_postcode':   '<?php echo $woocommerce->customer->get_postcode(); ?>',
				'billing_email':      'billing@email.com',
				'billing_phone':      '1234567',
				'payment_method':     'bacs',
				'terms':              1,
				'order_comments':     'Quick order'
//				shipping_method = {array} [1]
//					0 = "local_pickup"
//				billing_company = ""
//				billing_address_2 = ""
//				billing_state = ""
			};
			$.post(admin_url, data, function(responce) {
				if(responce['result'] === 'success') {
					window.location = responce['redirect'];
				} else {
					alert( $(responce['messages']).text() );
				}
			});
		});
	});
</script>
