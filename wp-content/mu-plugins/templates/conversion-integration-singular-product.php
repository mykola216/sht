<?php defined( 'ABSPATH' ) || die();
global $product;
?>

<script type="text/javascript">
	var google_tag_params = {
		ecomm_prodid: '<?php echo esc_js( $product->id ); ?>',
		ecomm_pagetype: 'product',
		ecomm_totalvalue: <?php echo esc_js( $product->price ); ?>
	};
</script>
