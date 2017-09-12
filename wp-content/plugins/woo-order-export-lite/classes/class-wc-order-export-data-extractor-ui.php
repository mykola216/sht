<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Order_Export_Data_Extractor_UI extends WC_Order_Export_Data_Extractor {
	static $object_type = 'shop_order';

	// ADD custom fields for export
	public static function get_all_order_custom_meta_fields( $sql_order_ids='' ) {
		global $wpdb;

		$sql_in_orders = '';
		if( $sql_order_ids )
			$sql_in_orders  = " AND ID IN ($sql_order_ids) ";

		// must show all
		if( !$sql_in_orders ) {
			$fields = self::get_order_custom_fields();
			$user_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta}" );
		} else	{
			$fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE post_type = '" . self::$object_type . "' {$sql_in_orders}" );
			$user_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->posts} INNER JOIN {$wpdb->usermeta} ON {$wpdb->posts}.post_author = {$wpdb->usermeta}.user_id WHERE post_type = '" . self::$object_type . "' {$sql_in_orders}" );
		}

		foreach($user_fields as $k=>$v)
			$user_fields[$k] = 'USER_'.$v;
		$fields    = array_unique( array_merge( $fields, $user_fields ) );
		sort( $fields );
		return apply_filters( 'woe_get_all_order_custom_meta_fields', $fields );
	}

	//filter attributes by matched orders
	public static function get_all_product_custom_meta_fields_for_orders( $sql_order_ids ) {
		global $wpdb;

		$wc_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id IN
									(SELECT DISTINCT order_item_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'line_item' AND order_id IN ($sql_order_ids))" );

		// WC internal table add attributes
		$wc_attr_fields = $wpdb->get_results( "SELECT attribute_name FROM {$wpdb->prefix}woocommerce_attribute_taxonomies" );
		foreach ( $wc_attr_fields as $f ) {
			$wc_fields[] = 'pa_' . $f->attribute_name;
		}

		//sql to gather product id for orders
		$sql_products = "SELECT DISTINCT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key ='_product_id' AND order_item_id IN
									(SELECT DISTINCT order_item_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'line_item' AND order_id IN ($sql_order_ids))";

		$wp_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} WHERE post_id IN
									(SELECT DISTINCT ID FROM {$wpdb->posts} WHERE post_type = 'product' OR post_type = 'product_variation' AND ID IN ($sql_products))" );

		$fields    = array_unique( array_merge( $wp_fields, $wc_fields ) );
		sort( $fields );

		return apply_filters( 'get_all_product_custom_meta_fields_for_orders', $fields );
	}

	public static function get_all_product_custom_meta_fields() {
		global $wpdb;

		$wc_fields = self::get_product_itemmeta();

		// WC internal table add attributes
		$wc_attr_fields = $wpdb->get_results( "SELECT attribute_name FROM {$wpdb->prefix}woocommerce_attribute_taxonomies" );
		foreach ( $wc_attr_fields as $f ) {
			$wc_fields[] = 'pa_' . $f->attribute_name;
		}

		// WP internal table	, skip hidden and attributes
		$wp_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} INNER JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
											WHERE post_type = 'product' OR post_type = 'product_variation'" );

		$fields    = array_unique( array_merge( $wp_fields, $wc_fields ) );
		sort( $fields );

		return apply_filters( 'woe_get_all_product_custom_meta_fields', $fields );
	}

	public static function get_all_coupon_custom_meta_fields() {
		global $wpdb;

		// WP internal table	, skip hidden and attributes
		$fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} INNER JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
											WHERE post_type = 'shop_coupon'" );
		sort( $fields );

		return apply_filters( 'woe_get_all_coupon_custom_meta_fields', $fields );
	}

	//for FILTERS

	public static function get_products_like( $like ) {
		global $wpdb;
		$like     = $wpdb->esc_like( $like );
		$query    = "
                SELECT      post.ID as id,post.post_title as text,att.ID as photo_id,att.guid as photo_url
                FROM        " . $wpdb->posts . " as post
                LEFT JOIN  " . $wpdb->posts . " AS att ON post.ID=att.post_parent AND att.post_type='attachment'
                WHERE       post.post_title LIKE '%{$like}%'
                AND         post.post_type = 'product'
                AND         post.post_status <> 'trash'
                GROUP BY    post.ID
                ORDER BY    post.post_title
                LIMIT 0,5
                ";
		$products = $wpdb->get_results( $query );
		foreach ( $products as $key => $product ) {
			if ( $product->photo_id ) {
				$photo                       = wp_get_attachment_image_src( $product->photo_id, 'thumbnail' );
				$products[ $key ]->photo_url = $photo[0];
			}
			else
				unset( $products[ $key ]->photo_url );
		}
		return $products;
	}

	public static function get_users_like( $like ) {
		global $wpdb;
		$ret = array();

		$like  = '*' . $wpdb->esc_like( $like ) . '*';
		$users = get_users( array( 'search' => $like , 'orderby' => 'display_name' ) );

		foreach ( $users as $key => $user ) {
			$ret[] = array(
					'id'   => $user->ID,
					'text' => $user->display_name
			);
		}
		return $ret;
	}

	public static function get_coupons_like( $like ) {
		global $wpdb;

		$like  = $wpdb->esc_like( $like );
		$query = "
                SELECT      post.post_title as id, post.post_title as text
                FROM        " . $wpdb->posts . " as post
                WHERE       post.post_title LIKE '%{$like}%'
                AND         post.post_type = 'shop_coupon'
                AND         post.post_status <> 'trash'
                ORDER BY    post.post_title
                LIMIT 0,10
        ";
		return $wpdb->get_results( $query );
	}

	public static function get_categories_like( $like ) {
		$cat = array();
		foreach (get_terms( 'product_cat','hide_empty=0&hierarchical=1&name__like=' . $like . '&number=10' ) as $term ) {
			$cat[] = array( "id" => $term->term_id, "text" => $term->name );
		}
		return $cat;
	}

	public static function get_order_custom_fields_values( $key ) {
		global $wpdb;
		$values  = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s  AND post_id IN (SELECT DISTINCT ID FROM {$wpdb->posts} WHERE post_type = '" . self::$object_type . "' )" , $key ) );
		sort( $values );
		return $values;
	}

	public static function get_product_custom_fields_values( $key ) {
		global $wpdb;
		$values  = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s    AND post_id IN (SELECT DISTINCT ID FROM {$wpdb->posts} WHERE post_type = 'product_variation' OR post_type = 'product')" , $key ) );
		sort( $values );
		return $values;
	}

	public static function get_products_taxonomies_values( $key ) {
		$values = array();
		$terms = get_terms( array( 'taxonomy' => $key ) );
		if ( ! empty( $terms ) ) {
			$values = array_map( function ( $term ) {
				return $term->name;
			}, $terms );
		}
		sort( $values );
		return $values;
	}

	public static function get_products_itemmeta_values( $key ) {
        global $wpdb;
        $meta_key_ent = esc_html($key);
		$metas = $wpdb->get_col( $wpdb->prepare("SELECT DISTINCT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta where meta_key = '%s' OR meta_key='%s'", $key, $meta_key_ent ));
		sort( $metas );
		return $metas;
	}

	public static function get_products_attributes_values( $key ) {
		$data = array();
		$attrs = wc_get_attribute_taxonomies();
		foreach ( $attrs as $item ) {
			if ( $item->attribute_label == $key && $item->attribute_type != 'select' ) {
				break;
			} elseif ( $item->attribute_label == $key ) {
				$name = wc_attribute_taxonomy_name( $item->attribute_name );
				$values = get_terms( $name, array( 'hide_empty' => false ) );
				if ( is_array( $values ) ) {
					$data = array_map( function ( $elem ) {
						return $elem->slug;
					}, $values );
				}
				break;
			}
		}
		sort( $data );
		return $data;
	}

	public static function get_order_meta_values( $type, $key ) {
		global $wpdb;
		$query   = $wpdb->prepare( 'SELECT DISTINCT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = %s',array( $type . strtolower( $key ) ) );
		$results = $wpdb->get_col( $query );
		$data    = array_filter( $results );
		sort( $data );
		return $data;
	}


	public static function get_order_product_fields( $format ) {
		$map = array(
			'line_id'     => array( 'label' => 'Item #', 'checked' => 1 ),
			'sku'         => array( 'label' => 'SKU', 'checked' => 1 ),
			'name'        => array( 'label' => 'Name', 'checked' => 1 ),
			'product_variation' => array( 'label' => 'Product Variation', 'checked' => 0 ),
			'seller'      => array( 'label' => 'Item Seller', 'checked' => 0 ),
			'qty'         => array( 'label' => 'Quantity', 'checked' => 1 ),
			'qty_minus_refund' => array( 'label' => 'Quantity (- Refund)', 'checked' => 0 ),
			'item_price'  => array( 'label' => 'Item Cost', 'checked' => 1 ),
			'price'       => array( 'label' => 'Product Current Price', 'checked' => 0 ),
			'line_no_tax' => array( 'label' => 'Order Line (w/o tax)', 'checked' => 0 ),
			'line_tax'    => array( 'label' => 'Order Line Tax', 'checked' => 0 ),
			'line_tax_refunded'=> array( 'label' => 'Order Line Tax Refunded', 'checked' => 0 ),
			'line_tax_minus_refund'=> array( 'label' => 'Order Line Tax (- Refund)', 'checked' => 0 ),
			'line_subtotal'=>array( 'label' => 'Order Line Subtotal', 'checked' => 0 ),
			'line_total'  => array( 'label' => 'Order Line Total', 'checked' => 0 ),
			'line_total_refunded'  => array( 'label' => 'Order Line Total Refunded', 'checked' => 0 ),
			'line_total_minus_refund'  => array( 'label' => 'Order Line Total (- Refund)', 'checked' => 0 ),
			'type'        => array( 'label' => 'Type', 'checked' => 0 ),
			'category'    => array( 'label' => 'Category', 'checked' => 0 ),
			'tags'        => array( 'label' => 'Tags', 'checked' => 0 ),
			'width'       => array( 'label' => 'Width', 'checked' => 0 ),
			'length'      => array( 'label' => 'Length', 'checked' => 0 ),
			'height'      => array( 'label' => 'Height', 'checked' => 0 ),
			'weight'      => array( 'label' => 'Weight', 'checked' => 0 ),
			'download_url' => array( 'label' => 'Download Url', 'checked' => 0 ),
			'image_url'   => array( 'label' => 'Image Url', 'checked' => 0 ),
			'product_shipping_class' => array( 'label' => 'Product Shipping Class', 'checked' => 0 ),
			'post_content'=> array( 'label' => 'Description', 'checked' => 0 ),
			'post_excerpt'=> array( 'label' => 'Short Description', 'checked' => 0 ),
		);

		foreach ( $map as $key => $value ) {
			$map[ $key ]['colname'] = $value['label'];
			$map[ $key ]['default'] = 1;
		}

		return apply_filters( 'woe_get_order_product_fields', $map, $format );
	}

	public static function get_order_coupon_fields( $format ) {
		$map = array(
			'code'                => array( 'label' => 'Coupon Code', 'checked' => 1 ),
			'discount_amount'     => array( 'label' => 'Discount Amount', 'checked' => 1 ),
			'discount_amount_tax' => array( 'label' => 'Discount Amount Tax', 'checked' => 1 ),
			'discount_amount_plus_tax' => array( 'label' => 'Discount Amount + Tax', 'checked' => 0 ),
            'excerpt' => array( 'label' => 'Coupon Description', 'checked' => 0 ),
			'discount_type' => array( 'label' => 'Coupon Type', 'checked'=> 0 ),
			'coupon_amount' => array( 'label' => 'Coupon Amount', 'checked'=> 0 ),
		);

		foreach ( $map as $key => $value ) {
			$map[ $key ]['colname'] = $value['label'];
                        $map[ $key ]['default'] = 1;
		}

		return apply_filters( 'woe_get_order_coupon_fields', $map, $format );
	}


	public static function get_order_fields( $format ) {
		$map = array();
		foreach ( array( 'common', 'user', 'billing', 'shipping', 'product', 'coupon', 'cart', 'misc' ) as $segment ) {
			$method      = "get_order_fields_" . $segment;
			$map_segment = self::$method();

			foreach ( $map_segment as $key => $value ) {
				$map_segment[ $key ]['segment'] = $segment;
				$map_segment[ $key ]['colname'] = $value['label'];
				$map_segment[ $key ]['default'] = 1; //debug
			}
			// woe_get_order_fields_common	filter
			$map_segment = apply_filters( "woe_$method", $map_segment, $format );
			$map         = array_merge( $map, $map_segment );
		}

		return apply_filters( 'woe_get_order_fields', $map );
	}

	public static function get_order_fields_common() {
		return array(
			'order_id'       => array( 'label' => 'Order Id', 'checked' => 0 ),
			'order_number'   => array( 'label' => 'Order Number', 'checked' => 1 ),
			'order_status'   => array( 'label' => 'Order Status', 'checked' => 1 ),
			'order_date'     => array( 'label' => 'Order Date', 'checked' => 1 ),
			'transaction_id' => array( 'label' => 'Transaction Id', 'checked' => 0 ),
			'completed_date' => array( 'label' => 'Completed Date', 'checked' => 0 ),
			'paid_date'      => array( 'label' => 'Paid Date', 'checked' => 0 ),
			'customer_note'  => array( 'label' => 'Customer Note', 'checked' => 1 ),
			'order_notes'    => array( 'label' => 'Order Notes', 'checked' => 0 ),
		);
	}

	public static function get_order_fields_user() {
		return array(
			'customer_ip_address' => array( 'label' => 'Customer IP address', 'checked' => 0 ),
			'customer_user'       => array( 'label' => 'Customer User Id', 'checked' => 0 ),
			'user_login'          => array( 'label' => 'Customer Username', 'checked' => 0 ),
			'user_email'          => array( 'label' => 'Customer User Email', 'checked' => 0 ),
			'user_role'           => array( 'label' => 'Customer Role', 'checked' => 0 ),
		);
	}

	public static function get_order_fields_billing() {
		return array(
			'billing_first_name'   => array( 'label' => 'First Name (Billing)', 'checked' => 1 ),
			'billing_last_name'    => array( 'label' => 'Last Name (Billing)', 'checked' => 1 ),
			'billing_full_name'    => array( 'label' => 'Full Name (Billing)', 'checked' => 0 ),
			'billing_company'      => array( 'label' => 'Company (Billing)', 'checked' => 1 ),
			'billing_address_1'    => array( 'label' => 'Address 1 (Billing)', 'checked' => 0 ),
			'billing_address_2'    => array( 'label' => 'Address 2 (Billing)', 'checked' => 1 ),
			'billing_city'         => array( 'label' => 'City (Billing)', 'checked' => 1 ),
			'billing_state'        => array( 'label' => 'State Code (Billing)', 'checked' => 1 ),
			'billing_state_full'   => array( 'label' => 'State Name (Billing)', 'checked' => 0 ),
			'billing_postcode'     => array( 'label' => 'Zip (Billing)', 'checked' => 1 ),
			'billing_country'      => array( 'label' => 'Country Code (Billing)', 'checked' => 1 ),
			'billing_country_full' => array( 'label' => 'Country Name (Billing)', 'checked' => 0 ),
			'billing_email'        => array( 'label' => 'Email (Billing)', 'checked' => 1 ),
			'billing_phone'        => array( 'label' => 'Phone (Billing)', 'checked' => 1 ),
		);
	}

	public static function get_order_fields_shipping() {
		return array(
			'shipping_first_name'   => array( 'label' => 'First Name (Shipping)', 'checked' => 1 ),
			'shipping_last_name'    => array( 'label' => 'Last Name (Shipping)', 'checked' => 1 ),
			'shipping_full_name'    => array( 'label' => 'Full Name (Shipping)', 'checked' => 0 ),
			'shipping_company'      => array( 'label' => 'Company (Shipping)', 'checked' => 0 ),
			'shipping_address_1'    => array( 'label' => 'Address 1 (Shipping)', 'checked' => 1 ),
			'shipping_address_2'    => array( 'label' => 'Address 2 (Shipping)', 'checked' => 1 ),
			'shipping_city'         => array( 'label' => 'City (Shipping)', 'checked' => 1 ),
			'shipping_state'        => array( 'label' => 'State Code (Shipping)', 'checked' => 1 ),
			'shipping_state_full'   => array( 'label' => 'State Name (Shipping)', 'checked' => 0 ),
			'shipping_postcode'     => array( 'label' => 'Zip (Shipping)', 'checked' => 1 ),
			'shipping_country'      => array( 'label' => 'Country Code (Shipping)', 'checked' => 1 ),
			'shipping_country_full' => array( 'label' => 'Country Name(Shipping)', 'checked' => 0 ),
		);
	}

	// meta
	public static function get_order_fields_product() {
		return array(
			'products' => array( 'label' => 'Products', 'checked' => 1, 'repeat' => 'rows' , 'max_cols'=>10 ),
		);
	}

	// meta
	public static function get_order_fields_coupon() {
		return array(
			'coupons' => array( 'label' => 'Coupons', 'checked' => 1, 'repeat' => 'rows' , 'max_cols'=>10 ),
		);
	}

	public static function get_order_fields_cart() {
		return array(
			'shipping_method_title' => array( 'label' => 'Shipping Method Title', 'checked' => 1 ),
			'shipping_method'		=> array( 'label' => 'Shipping Method', 'checked' => 0 ),
			'payment_method_title'  => array( 'label' => 'Payment Method Title', 'checked' => 1 ),
			'payment_method'  		=> array( 'label' => 'Payment Method', 'checked' => 0 ),
			'coupons_used'          => array( 'label' => 'Coupons Used', 'checked' => 0 ),
			'cart_discount'         => array( 'label' => 'Cart Discount Amount', 'checked' => 1 ),
			'cart_discount_tax'     => array( 'label' => 'Cart Discount Tax Amount', 'checked' => 0 ),
			'order_subtotal'        => array( 'label' => 'Order Subtotal Amount', 'checked' => 1 ),
			'order_tax'             => array( 'label' => 'Order Tax Amount', 'checked' => 0 ),
			'order_shipping'        => array( 'label' => 'Order Shipping Amount', 'checked' => 1 ),
			'order_shipping_refunded' => array( 'label' => 'Order Shipping Amount Refunded', 'checked' => 0 ),
			'order_shipping_minus_refund' => array( 'label' => 'Order Shipping Amount (- Refund)', 'checked' => 0 ),
			'order_shipping_tax'    => array( 'label' => 'Order Shipping Tax Amount', 'checked' => 0 ),
			'order_shipping_tax_refunded' => array( 'label' => 'Order Shipping Tax Refunded', 'checked' => 0 ),
			'order_shipping_tax_minus_refund' => array( 'label' => 'Order Shipping Tax Amount (- Refund)', 'checked' => 0 ),
			'order_refund'          => array( 'label' => 'Order Refund Amount', 'checked' => 1 ),
			'order_total_inc_refund'=> array( 'label' => 'Order Total Amount (- Refund)', 'checked' => 0 ),
			'order_total'           => array( 'label' => 'Order Total Amount', 'checked' => 1 ),
			'order_total_no_tax'    => array( 'label' => 'Order Total Amount without Tax', 'checked' => 0 ),
			'order_total_tax'       => array( 'label' => 'Order Total Tax Amount', 'checked' => 1 ),
			'order_total_tax_refunded'    => array( 'label' => 'Order Total Tax Amount Refunded', 'checked' => 0 ),
			'order_total_tax_minus_refund' => array( 'label' => 'Order Total Tax Amount (- Refund)', 'checked' => 0 ),
			'order_currency'        => array( 'label' => 'Currency', 'checked' => 0 ),
		);
	}

	public static function get_order_fields_misc() {
		return array(
			'total_weight_items'    => array( 'label' => 'Total weight', 'checked' => 0 ),
			'count_total_items'     => array( 'label' => 'Total items', 'checked' => 0 ),
			'count_unique_products' => array( 'label' => 'Total products', 'checked' => 0 ),
		);
	}

	// for UI only
	public static function get_visible_segments( $fields ) {
		$sections = array();
		foreach ( $fields as $field ) {
			if ( $field['checked'] ) {
				$sections[ $field['segment'] ] = 1;
			}
		}

		return array_keys( $sections );
	}

	public static function get_order_segments() {
		return array(
			'common'   => 'Common',
			'user'     => 'User',
			'billing'  => "Billing",
			'shipping' => "Shipping",
			'product'  => "Products",
			'coupon'   => "Coupons",
			'cart'     => "Cart",
			'misc'     => "Others"
		);
	}


}