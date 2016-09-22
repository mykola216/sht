<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
$methods_to_check = array('flat_rate:7', 'flat_rate:8');
$is_allowed_method = ($gateway->id == 'cod') ? !in_array($chosen_shipping_methods[0], $methods_to_check) : true;
?>
<li class="wc_payment_method payment_method_<?php echo $gateway->id; ?> <?php echo (!$is_allowed_method) ? 'not-allowed' : ''; ?>" <?php echo (!$is_allowed_method) ? 'hidden' : ''; ?>>
	<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php if ($is_allowed_method) checked( $gateway->chosen, true ); ?> <?php disabled( !$is_allowed_method ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo $gateway->id; ?>">
		<?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo $gateway->id; ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
