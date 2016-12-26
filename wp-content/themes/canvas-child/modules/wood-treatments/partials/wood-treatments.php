<?php defined( 'ABSPATH' ) || die();

global $st_options;

//$url = str_replace( array(ABSPATH, '\\'), '/', dirname(__DIR__) );
$url = str_replace( get_stylesheet_directory(), '', dirname(__DIR__) );
$url = get_stylesheet_directory_uri() . $url;

$wood_treatments_title = esc_attr($st_options['wood_treatments_title']);
$wood_treatments_title = $wood_treatments_title ? $wood_treatments_title : __('Behandeling van steigerhout', 'woothemes');

$wood_treatments_img_url = esc_attr($st_options['wood_treatments_img_url']);
$wood_treatments_img_url = $wood_treatments_img_url ? $wood_treatments_img_url : $url . '/images/wood-treatments-hor.png' ;
?>

<a href="<?php echo $wood_treatments_img_url; ?>" class="zoom last" title="<?php echo $wood_treatments_title; ?>" data-rel="prettyPhoto[product-gallery]">
	<img src="<?php echo $wood_treatments_img_url; ?>" class="wood-treatments-img" width="400" height="400" />
</a>