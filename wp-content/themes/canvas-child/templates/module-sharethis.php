<?php defined( 'ABSPATH' ) || die();
/**
 * Slider module.
 *
 * @author   Aleksandr Levashov
 * @editor   Maksim Petrenko
 * @version  1.0.0
 */

?>
<div id="social-media">
	<?php
	if ( is_front_page() ) {
		$st_url = str_replace( 'https://', 'http://', site_url() );
	} else {
		$st_url = str_replace( 'https://', 'http://', get_permalink() );
	}
	//echo '<span class="st_facebook_hcount" displayText="Facebook" st_url="' . esc_attr( $st_url ) .'" st_title="' . esc_attr( get_the_title() ) . '"></span>';
	//echo '<div class="fb-like" data-href="' . esc_attr( str_replace( 'https://', 'http://', get_permalink() ) ) .'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>';
	?>
	<span class="st_facebook_hcount" displayText="Facebook" st_url="<?php echo esc_attr( $st_url ); ?>" st_title="<?php echo esc_attr( get_the_title() ); ?>"></span>
	<span class='st_pinterest_hcount' displayText='Pinterest' st_url="<?php echo esc_attr( $st_url ); ?>" st_title="<?php echo esc_attr( get_the_title() ); ?>"></span>
	<span class='st_twitter_hcount' displayText='Tweet' st_url="<?php echo esc_attr( $st_url ); ?>" st_title="<?php echo esc_attr( get_the_title() ); ?>"></span>
	<span class='st_googleplus_hcount' displayText='Google +' st_url="<?php echo esc_attr( $st_url ); ?>" st_title="<?php echo esc_attr( get_the_title() ); ?>"></span>
</div>
<script type="text/javascript">
	if (typeof stLight != 'undefined') {
		stLight.options(
			{
				publisher: "c4c40d90-2189-4fa7-8b1c-192a6f2fe24f",
				doNotHash: false,
				doNotCopy: false,
				hashAddressBar: false
			}
		);
	}
</script>