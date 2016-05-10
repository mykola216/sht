<?php
/**
 * Slider module.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $st_options;
?>

<div id="social-media-footer" class="col-full">
	<ul>
		<li class="facebook">
			<a href="<?php echo esc_attr( $st_options['facebook_url'] ); ?>" target="_blank"></a>
		</li>
		<li class="google-plus">
			<a href="<?php echo esc_attr( $st_options['googleplus_url'] ); ?>" target="_blank"></a>
		</li>
		<li class="instagram">
			<a href="<?php echo esc_attr( $st_options['instagram_url'] ); ?>" target="_blank"></a>
		</li>
		<li class="twitter">
			<a href="<?php echo esc_attr( $st_options['twitter_url'] ); ?>" target="_blank"></a>
		</li>
		<li class="youtube">
			<a href="<?php echo esc_attr( $st_options['youtube_url'] ); ?>" target="_blank"></a>
		</li>
		<li class="pinterest">
			<a href="<?php echo esc_attr( $st_options['pinterest_url'] ); ?>" target="_blank"></a>
		</li>
	</ul>
</div>