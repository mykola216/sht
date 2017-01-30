<?php defined( 'ABSPATH' ) || die();

add_action( 'wp_ajax_' . ( is_user_logged_in() ? '' : 'nopriv_' ) . 'send_me_cart_items', function() {
	global $st_options;
	$email = filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL );
	if ( $email ) {
		ob_start();
			$email_header = $st_options['send_me_cart_items_table_header'];
			$email_header = $email_header ? $email_header : __('Winkelmand', 'canvas_child');
			WC()->mailer()->email_header($email_header);
			include( __DIR__ . '/partials/email-cart-items.php' );
			WC()->mailer()->email_footer();
		$content = ob_get_clean();

		require_once WP_PLUGIN_DIR . '/woocommerce/includes/libraries/class-emogrifier.php';
		ob_start();
			wc_get_template('emails/email-styles.php');
		$css = apply_filters( 'woocommerce_email_styles', ob_get_clean() );
		try {
			// apply CSS styles inline for picky email clients
			$emogrifier = new Emogrifier( $content, $css );
			$content = $emogrifier->emogrify();
		} catch ( Exception $e ) {
			$logger = new WC_Logger();
			$logger->add( 'emogrifier', $e->getMessage() );
		}
		$subject = str_replace('%date%', date_i18n(get_option('date_format')), $st_options['send_me_cart_items_subject']);
		$subject = $subject ? $subject : sprintf(__('Geselecteerde artikelen op %1$s op %2$s', 'canvas_child'), get_bloginfo('name'), date_i18n(get_option('date_format')));
		WC()->mailer()->send($email, $subject, $content);
		wp_send_json_success('De lijst met geselecteerde items is succesvol verzonden.');
	} else {
		wp_send_json_error( 'This email address is invalid.' );
		wp_die();
	}
} );
