<?php
/**
 * Shipping terms.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $st_options;
?>

<div class="shipping-terms">
	<strong>
		<?php echo esc_html( $st_options['shipping_terms_line1'] ); ?>
	</strong>
	<br />
	<?php echo esc_html( $st_options['shipping_terms_line2'] ); ?>
</div>