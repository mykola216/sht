<?php defined( 'ABSPATH' ) || die();

//$url = str_replace( array(ABSPATH, '\\'), '/', dirname(__DIR__) );
$url = str_replace( get_stylesheet_directory(), '', dirname(__DIR__) );
$url = get_stylesheet_directory_uri() . $url;
?>

<div id="wood-treatments">
	<h3><?php _e('Behandeling van steigerhout', 'woothemes'); ?></h3>
	<img src="<?php echo $url; ?>/images/wood-treatments-hor.png" width="100%" />
</div>

