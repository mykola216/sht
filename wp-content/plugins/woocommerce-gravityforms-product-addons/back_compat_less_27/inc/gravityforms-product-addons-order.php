<?php


class WC_GFPA_Order {

	private static $instance;

	public static function register() {
		if ( self::$instance == null ) {
			self::$instance = new WC_GFPA_Order();
		}
	}

	public function __construct() {
		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'custom_order_item_meta' ), 10, 3 );
	}

	public function custom_order_item_meta( $item_id, $item, $product ) {

		if ( is_object( $item ) ) {

			$meta_to_display = array();
			$meta_data_items = $item->get_formatted_meta_data( '' );

			foreach ( $meta_data_items as $meta_data_item ) {
				if ( strpos( $meta_data_item->key, '_gf_email_hidden_' ) === 0 ) {
					$meta_data_item->display_key = str_replace( '_gf_email_hidden_', '', $meta_data_item->display_key );
					$meta_to_display[]           = $meta_data_item;
				}
			}

			if ( ! empty( $meta_to_display ) ) {
				include( 'html-order-item-meta.php' );
			}
		}

	}
}