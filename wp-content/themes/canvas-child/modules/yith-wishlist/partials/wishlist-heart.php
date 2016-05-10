<?php defined('ABSPATH') || die();
/**
 * Add to wishlist template - "heart"
 */
?>

<div class="yith-wcwl-add-to-wishlist yith-wcwl-add-to-wishlist-loop add-to-wishlist-<?php echo $product_id; ?>">
	<div
		class="yith-wcwl-add-button <?php echo $exists ? 'hide' : 'show'; ?>"
		style="display: <?php echo $exists ? 'none' : 'block'; ?>;">
		<a
			href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) ); ?>"
			rel="nofollow"
			data-product-id="<?php echo $product_id; ?>"
			data-product-type="<?php echo $product_type; ?>"
			class="add_to_wishlist">
		</a>
	</div>
	<div class="yith-wcwl-wishlistaddedbrowse hide" style="display: none;">
		<a href="<?php echo esc_url( $wishlist_url ); ?>"></a>
	</div>
	<div
		class="yith-wcwl-wishlistexistsbrowse <?php echo $exists ? 'show' : 'hide'; ?>"
		style="display: <?php echo $exists ? 'block' : 'none'; ?>;">
		<a href="<?php echo esc_url( $wishlist_url ); ?>"></a>
	</div>
	<div style="clear: both;"></div>
	<div class="yith-wcwl-wishlistaddresponse"></div>
</div>
<div class="clear"></div>
