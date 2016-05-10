<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://w3cm.ru/plugins/w3cm-sort-by-total-sales/
 * @since      1.0.0
 *
 * @package    W3CM_Sort_By_Total_Sales
 * @subpackage W3CM_Sort_By_Total_Sales/public
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    W3CM_Sort_By_Total_Sales
 * @subpackage W3CM_Sort_By_Total_Sales/admin
 * @author     Alexandr Levashov <me@w3cm.ru>
 */
class W3CM_Sort_By_Total_Sales_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_id    The ID of this plugin.
	 */
	private $plugin_id;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_id  The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_id, $version ) {

		$this->plugin_id = $plugin_id;
		$this->version = $version;

	}

	/**
	 * Add 'Donate!' link to the plugin action links.
	 *
	 * @since    1.0.0
	 *
	 * @param    array  $links  The links to show on the plugins overview page.
	 * @return   array          The links to show on the plugins overview page.
	 */
	public function add_link_for_donations( $links ) {

		$donate_link = sprintf(
			'<a href="%s" style="font-weight:bold;">%s</a>',
			esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=825QMNQ25QWNS' ),
			__( 'Donate!', 'w3cm-sort-by-total-sales' )
		);
		array_push( $links, $donate_link );

		return $links;

	}

	/**
	 * Add custom columns for products
	 *
	 * @since       1.0.0
	 * @param       array   $columns    Custom columns for products
	 * @return      array   $columns    Custom columns for products
	 */
	public function add_column_title( $columns ) {

		$columns['total_sales'] = '<span class="dashicons dashicons-chart-bar" title="' . esc_attr__( 'Total sales' ) . '"></span>';

		return $columns;

	}


	/**
	 * Ouput custom columns for products
	 *
	 * @since       1.0.0
	 * @param       array   $column     Custom column
	 * @param       array   $post_id    Product id
	 */
	public function add_column_data( $column, $post_id ) {

		if ( 'total_sales' == $column ) {
			echo get_post_meta( $post_id, 'total_sales', true );
		}

	}

	/**
	 * Make columns sortable
	 *
	 * @since       1.0.0
	 * @param       array   $columns    Custom columns for products
	 * @return      array   $columns    Custom columns for products
	 */
	public function make_column_sortable( $columns ) {

		$columns['total_sales'] = array( 'total_sales', true );;

		return $columns;

	}

	/**
	 * Add order by total sales to the current request.
	 *
	 * @since       1.0.0
	 * @param       array   $query_vars     The array of requested query variables.
	 * @return      array   $query_vars     The array of requested query variables.
	 */
	public function add_column_orderby( $query_vars ) {

		if ( isset( $query_vars['orderby'] ) && 'total_sales' == $query_vars['orderby'] ) {
			$query_vars = array_merge( $query_vars, array(
				'meta_key' => 'total_sales',
				'orderby' => 'meta_value_num'
			) );
		}

		return $query_vars;

	}

}
