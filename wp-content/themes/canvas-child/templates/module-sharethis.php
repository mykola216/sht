<?php defined( 'ABSPATH' ) || die();
/**
 * Slider module.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */
?>
<div id="social-media">
	<?php
		if ( is_front_page() ) {
			echo '<span class="st_facebook_hcount" displayText="Facebook"></span>';
		} else {
			echo '<div class="fb-like" data-href="' . esc_attr( get_permalink() ) .'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>';
		}
	?>
	<span class='st_pinterest_hcount' displayText='Pinterest'></span>
	<span class='st_twitter_hcount' displayText='Tweet'></span>
	<span class='st_googleplus_hcount' displayText='Google +'></span>
</div>
<script type="text/javascript">
	stLight.options(
		{
			publisher: "c4c40d90-2189-4fa7-8b1c-192a6f2fe24f",
			doNotHash: false,
			doNotCopy: false,
			hashAddressBar: false
		}
	);
</script>
