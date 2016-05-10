<?php defined( 'ABSPATH' ) || die();

add_action( 'wp_enqueue_scripts', function() {
	$url = str_replace( array(ABSPATH, '\\'), '/', __DIR__);

	wp_enqueue_style( 'tooltip-popup', $url . '/css/tooltip-popup.css' );
	wp_enqueue_script( 'tooltip-popup', $url . '/js/tooltip-popup.js', array('jquery-ui-tooltip') );
} );


function canvas_child_field_description( $description ) {
	if ( ! is_admin() && $description ) {
		$description = sprintf(
			'<sup class="canvas-child-tooltip" title="%s">i</sup>',
			esc_attr( $description )
		);
	}

	return $description;
}


add_filter( 'gfield_description', 'canvas_child_field_description' );


add_filter( 'gfield_content_format', function( $gfield_content_format ) {
	if ( ! is_admin() ) {
		$gfield_content_format = '<div class="gfield-label-div"><label class="gfield_label" %2$s>%3$s%4$s</label>%5$s</div>{FIELD}%6$s';
	}

	return $gfield_content_format;
} );


add_filter( 'woocommerce_cart_shipping_method_full_label', function( $label, $method ) {
	switch ( $method->id ) {
		case 'flat_rate':
			$description = 'Als u kiest voor de standaard bezorging, dan leveren wij tot de eerste drempel begane grond.';
			break;
		case 'flat_rate:uitgebreide-bezorging':
			$description = 'Voor leveringen met montage, op een etage of verder dan de eerste drempel raden wij u aan om voor deze verzendmethode te kiezen.';
			break;
	}

	if ( isset( $description ) ) {
		$label .= canvas_child_field_description( $description );
	}

	return $label;
}, 10, 2 );
