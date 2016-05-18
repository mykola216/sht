<?php defined( 'ABSPATH' ) || die(); ?>
<div id="popup-box-content-wrapper">
	<div id="popup-box-content-inner">
		<h1 class="popup-box-title">lijst winkelmand doorsturen</h1>
		<div id="popup-box-content" hidden>
			<?php
				/*$page = get_page_by_path('please-dont-leave-us');
				if ( $page ) {
					echo apply_filters( 'the_content', $page->post_content );
				}*/
			?>
		</div>
		<?php if ( ! WooCommerce::instance()->cart->is_empty() ) { ?>
			<div id="popup-box-send-email">
				<label>E-mail ontvanger:</label>
				<input type="email" class="email" placeholder="jouw email adres hier" />
				<a href="javascript:void(0)" class="button send alt">verstuur</a>
				<a href="javascript:void(0)" class="button cancel alt">annuleer</a>
			</div>
		<?php } ?>
		<div id="popup-box-cross" class="dashicons dashicons-dismiss"></div>
		<div id="sending-overlay"></div>
	</div>
</div>
