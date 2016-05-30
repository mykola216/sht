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
	<div class="product-excerpt opened">
		<?php the_excerpt(); ?>
	</div>
	<button class="button more read-more" data-label-read-more="<?php _e('Read more', 'woocommerce'); ?>" data-label-hide-more="<?php _e('Hide', 'woocommerce'); ?>" >
		<?php _e('Read more', 'woocommerce'); ?>
	</button>
	<div class="product-full-content closed">
		<?php the_content(); ?>
	</div>
</div>