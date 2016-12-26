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

<?php global $st_options; ?>

<div id="product-description" class="description-wrapper">
	<div id="product-description-content" class="full-content closed" data-offset="100">
		<?php the_content(); ?>
	</div>
	<?php if ( sanitize_text_field(get_the_content()) ) { ?>
	<button
		class="button more read-more"
		data-target="#product-description-content"
		data-label-read-more="<?php echo $st_options['read_more_text']; ?>"
		data-label-hide-more="<?php echo $st_options['hide_more_text']; ?>" >
		<?php echo $st_options['read_more_text']; ?>
	</button>
	<?php } ?>
</div>