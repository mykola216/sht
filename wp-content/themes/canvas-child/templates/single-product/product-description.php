<?php
/**
 * Product description
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div id="product-description">
	<button
			class="button more read-more"
			data-label-read-more="<?php _e('Read more', 'woocommerce'); ?>"
			data-label-hide-more="<?php _e('Verbergen', 'woocommerce'); ?>" >
			<?php _e('Read more', 'woocommerce'); ?>
	</button>
	<div class="full-content closed">
		<?php the_content(); ?>
	</div>
</div>