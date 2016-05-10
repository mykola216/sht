<?php
/**
 * Product excetpt
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

printf( '<div class="product-excerpt">%s</div>', $product->post->post_excerpt );