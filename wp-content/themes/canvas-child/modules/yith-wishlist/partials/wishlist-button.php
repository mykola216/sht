<?php defined('ABSPATH') || die();
/**
 * Add to wishlist template button
 */
?>

<div class="yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo $product_id ?>">
	<?php if( ! ( $disable_wishlist && ! is_user_logged_in() ) ): ?>
		<div class="yith-wcwl-add-button <?php echo ( $exists && ! $available_multi_wishlist ) ? 'hide': 'show' ?>" style="display:<?php echo ( $exists && ! $available_multi_wishlist ) ? 'none': 'block' ?>">
			<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) )?>" rel="nofollow" data-product-id="<?php echo $product_id ?>" data-product-type="<?php echo $product_type?>" class="<?php echo $link_classes ?>" >
				<?php echo $icon ?>
				<?php echo $label ?>
			</a>
			<img src="<?php echo esc_url( YITH_WCWL_URL . 'assets/images/wpspin_light.gif' ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
		</div>

		<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
			<a href="<?php echo esc_url( $wishlist_url )?>" >
				<?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text )?>
			</a>
			<span class="feedback"><?php echo $product_added_text ?></span>
		</div>

		<div class="yith-wcwl-wishlistexistsbrowse <?php echo ( $exists && ! $available_multi_wishlist ) ? 'show' : 'hide' ?>" style="display:<?php echo ( $exists && ! $available_multi_wishlist ) ? 'block' : 'none' ?>">
			<a href="<?php echo esc_url( $wishlist_url ) ?>">
				<?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text )?>
			</a>
			<span class="feedback"><?php echo $already_in_wishslist_text ?></span>
		</div>

		<div style="clear:both"></div>
		<div class="yith-wcwl-wishlistaddresponse"></div>
	<?php else: ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'wishlist_notice' => 'true', 'add_to_wishlist' => $product_id ), get_permalink( wc_get_page_id( 'myaccount' ) ) ) )?>" rel="nofollow" class="<?php echo str_replace( 'add_to_wishlist', '', $link_classes ) ?>" >
			<?php echo $icon ?>
			<?php echo $label ?>
		</a>
	<?php endif; ?>

</div>

<div class="clear"></div>
