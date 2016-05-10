<?php defined( 'ABSPATH' ) || die(); ?>

<div id="popup-box-content-wrapper">
	<div id="popup-box-content">
		<?php
			$page = get_page_by_path('please-dont-leave-us');
			if ( $page ) {
				echo apply_filters( 'the_content', $page->post_content );
			}
		?>
	</div>
	<?php if ( ! WooCommerce::instance()->cart->is_empty() ) { ?>
		<div id="popup-box-send-email">
			<input type="email" placeholder="jouw email adres hier" />
			<a href="javascript:void(0)" class="button alt">Stuur naar me</a>
		</div>
	<?php } ?>
	<div id="popup-box-cross" class="dashicons dashicons-dismiss"></div>
</div>
