<?php
/**
 * Frontend class
 *
 * @author Yithemes
 * @package YITH WooCommerce Frequently Bought Together Premium
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WFBT_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WFBT_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WFBT_Frontend
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WFBT_VERSION;

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WFBT_Frontend
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __construct() {

			// enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'woocommerce_after_single_product_summary', array( $this, 'add_bought_together_form' ), 1 );

            add_shortcode( 'ywfbt_form', array( $this, 'wfbt_shortcode' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function enqueue_scripts(){

			wp_enqueue_style( 'yith-wfbt-style', YITH_WFBT_ASSETS_URL . '/css/yith-wfbt.css' );

			$background         = get_option( "yith-wfbt-button-color" );
			$background_hover   = get_option( "yith-wfbt-button-color-hover" );
			$text_color         = get_option( "yith-wfbt-button-text-color" );
			$text_color_hover   = get_option( "yith-wfbt-button-text-color-hover" );

			$inline_css = "
                .yith-wfbt-submit-block .yith-wfbt-submit-button {
                        background: {$background};
                        color: {$text_color};
                }
                .yith-wfbt-submit-block .yith-wfbt-submit-button:hover {
                        background: {$background_hover};
                        color: {$text_color_hover};
                }";

			wp_add_inline_style( 'yith-wfbt-style', $inline_css );
		}

		/**
		 * Form Template
		 *
		 * @since 1.0.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 */
		public function add_bought_together_form(){

			global $product;
            $product_id = yit_get_base_product_id( $product );
			echo do_shortcode( '[ywfbt_form product_id="' . $product_id . '"]' );
		}


        /**
         * Frequently Bought Together Shortcode
         *
         * @since 1.0.5
         * @param array $atts
         * @return string
         * @author Francesco Licandro
         */
        public function wfbt_shortcode( $atts ){

            $atts = shortcode_atts(array(
                'product_id' => 0
            ), $atts );

            extract( $atts );

	        $product_id = intval( $product_id );
            $product = wc_get_product( $product_id );

            if( ! $product ) {
                return '';
            }

            // get meta for current product
            $group  = $this->get_meta_prevent_multiple( $product_id );

            if( empty( $group ) || $product->is_type( array( 'grouped', 'external' ) ) ) {
                return '';
            }

            if( $product->is_type( 'variable' ) ) {

                $variations = $product->get_children();

                if( empty( $variations ) ) {
                    return '';
                }
                // get first product variation
                $product_id = array_shift( $variations );
                $product = wc_get_product( $product_id );
            }

            $products[] = $product;
            foreach( $group as $the_id ) {
                $current = wc_get_product( $the_id );
                // add to main array
                $products[] = $current;
            }

            ob_start();

            wc_get_template( 'yith-wfbt-form.php', array( 'products' => $products ), '', YITH_WFBT_DIR . 'templates/' );

            return ob_get_clean();
        }

		/**
		 * Prevent multiple meta values
		 *
		 * @since 1.1.1
		 * @author Francesco Licandro
		 * @param integer|string $product_id
		 * @return array
		 */
		public function get_meta_prevent_multiple( $product_id ) {

			// check for multiple meta
			$group = get_post_meta( $product_id, YITH_WFBT_META, false );
			$new_group = array();
			if( ! empty( $group ) && count( $group ) > 1 ) {
				foreach ( $group as $elem => $single_group ){
					if( ! is_array( $single_group ) ) {
						continue;
					}
					$new_group = array_merge( $new_group, $single_group );
				}

				delete_post_meta( $product_id, YITH_WFBT_META );
				$new_group = array_filter( $new_group );
				$new_group = array_unique( $new_group );

				update_post_meta( $product_id, YITH_WFBT_META, $new_group );

				return $new_group;
			}
			else {
				return empty( $group ) ? array() : array_shift( $group );
			}
		}
	}
}
/**
 * Unique access to instance of YITH_WFBT_Frontend class
 *
 * @return \YITH_WFBT_Frontend
 * @since 1.0.0
 */
function YITH_WFBT_Frontend(){
	return YITH_WFBT_Frontend::get_instance();
}