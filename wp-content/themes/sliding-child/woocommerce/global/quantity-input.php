<?php
/**
 * Product quantity inputs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="quantity">
	<input type="number" step="<?php echo esc_attr( $step ); ?>" <?php if ( is_numeric( $min_value ) ) : ?>min="<?php echo esc_attr( $min_value ); ?>"<?php endif; ?> <?php if ( is_numeric( $max_value ) ) : ?>max="<?php echo esc_attr( $max_value ); ?>"<?php endif; ?> name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="input-text qty text" size="4" />
	<input type="button" value="+" class="plus">
	<input type="button" value="-" class="minus">
</div>
<script>
	jQuery(".plus").click(function() {
		var item = jQuery(this).closest(".quantity").find(".qty"),
			step = Number(item.attr('step')) || 1,
			max_value = Number(item.attr('max')),
			value = Number(item.val()) + step;
		if(max_value === max_value && max_value < value) {
			value = max_value;
		}
		item.val(value); 
	});
	jQuery(".minus").click(function() {
		var item = jQuery(this).closest(".quantity").find(".qty"),
			step = Number(item.attr('step')) || 1,
			min_value = Number(item.attr('min')),
			value = Number(item.val()) - step;
		console.log(step, min_value, value);
		if(min_value === min_value && min_value > value) {
			value = min_value;
		}
		item.val(value); 
	});
</script>