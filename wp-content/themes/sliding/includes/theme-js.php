<?php
if ( ! is_admin() ) { add_action( 'wp_print_scripts', 'woothemes_add_javascript' ); }

if ( ! function_exists( 'woothemes_add_javascript' ) ) {
	function woothemes_add_javascript() {
/*
		// Use Google jQuery minified script instead
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery',  'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
*/
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'cycle', get_template_directory_uri() . '/includes/js/jquery.cycle.all.js', array( 'jquery' ) );
		wp_enqueue_script( 'easing', get_template_directory_uri() . '/includes/js/jquery.easing.1.3.js', array( 'jquery' ) );
		wp_enqueue_script( 'general', get_template_directory_uri() . '/includes/js/general.js', array( 'jquery' ) );
		
		// Only load the sliders on the homepage
		if (is_home()) :
			wp_enqueue_script( 'jscrollpane', get_template_directory_uri() . '/includes/js/jquery.slickscroll.js', array( 'jquery' ) );
			wp_enqueue_script( 'mousewheel', get_template_directory_uri() . '/includes/js/jquery.mousewheel.js', array( 'jquery' ) );
			wp_enqueue_script( 'carousel', get_template_directory_uri() . '/includes/js/jquery.jcarousel.min.js', array( 'jquery' ) );
			
			// Hook in a function to fire those scripts
			add_action ('wp_head' , 'fire_homepage_scripts');
		else : endif;
	}
}

// Fire slider / carousel scripts
function fire_homepage_scripts() {
	?>
		<script>
		jQuery(document).ready(function () {
			//jQuery('#recent-products').slickscroll({ "horizontalscrollbar": true,  mousewheel_scroll_speed: 20 });
			//jQuery('#bestselling-products').slickscroll({ "horizontalscrollbar": true });
			jQuery('#slider').jcarousel({
    			wrap: null,
    			buttonNextHTML: null,
    			buttonPrevHTML: null,
    			scroll: 3
    		});
    		jQuery('.jcarousel-next, .jcarousel-prev').click(function(){
    			return false;
    		});
		});
		</script>
	<?php
}
?>