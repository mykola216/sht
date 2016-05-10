<?php
/**
 * nav_after module.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $st_options;
?>

<div id="nav_after_module">
	<span><?php echo esc_html( $st_options['working_time'] ); ?></span>
	<?php get_search_form(); ?>
</div>