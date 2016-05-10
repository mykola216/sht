<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Set path to WooFramework and theme specific functions
$functions_path = get_template_directory() . '/functions/';
$includes_path = get_template_directory() . '/includes/';

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', 'ztrnxk8qyl0g0o9dlsjoigod6qlwspy4e' );

// WooFramework
require_once ($functions_path . 'admin-init.php' );			// Framework Init

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-plugins.php', 			// Theme specific plugins integrated in a theme
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php',			// Theme widgets
				'includes/theme-install.php',			// Theme Installation
				'includes/theme-woocommerce.php'		// WooCommerce specific
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );
				
foreach ( $includes as $i ) {
	locate_template( $i, true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/


if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name'=> 'Home advertorial',
		'id' => 'home-ad',
		'before_widget' => '<div class="home-ad col-full">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	
	register_sidebar(array(
		'name'=> 'Home column 1',
		'id' => 'home-col1',
		'before_widget' => '<span class="home-column1">',
		'after_widget' => '</span>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	
	register_sidebar(array(
		'name'=> 'Home column 2',
		'id' => 'home-col2',
		'before_widget' => '<span class="home-column2">',
		'after_widget' => '</span>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	
		register_sidebar(array(
		'name'=> 'Usps',
		'id' => 'usps',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
	
	}
	
	/*-----------------------------------------------------------------------------------*/
/* Extra product tabs */
/*-----------------------------------------------------------------------------------*/
/**
 * tab_names()
 * tab_panels()
 *
 * Replace the default RSS feed link with the Feedburner URL, if one
 * has been provided by the user.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_action('woocommerce_product_tabs','tab_names');
add_action('woocommerce_product_tab_panels','tab_panels');

function tab_names() {

	global $post;

	$panel1_id = 1595;
	$panel2_id = 1598;
	$panel3_id = 1619;
	
	$panel1_title = get_the_title($panel1_id);
	$panel2_title = get_the_title($panel2_id);
	$panel3_title = get_the_title($panel3_id);

	echo '<li><a href="#tab-offerteaanvraag">Aangepaste maat?</a></li>';
	echo '<li><a href="#'.$panel1_id.'">'.$panel1_title.'</a></li>';
	echo '<li><a href="#'.$panel2_id.'">'.$panel2_title.'</a></li>';
	
	$post_vars = get_the_terms($post->ID, 'product_cat');
	
	$product_cat = get_object_vars($post_vars[28]);
	
	if(array_key_exists( 28, $post_vars) || array_key_exists( 29, $post_vars) || array_key_exists( 30, $post_vars) ) {
	
	echo '<li><a href="#'.$panel3_id.'">'.$panel3_title.'</a></li>';

	echo '
		<script>
			jQuery(document).ready(function () {
				jQuery(\'.kussen-info\').click(function () {
			
					jQuery(\'a[href="#1619"]\').trigger(\'click\');
					
					jQuery(\'html, body\').animate({
    					scrollTop: jQuery(\'a[href="#1619"]\').offset().top
    				}, 1000);
    			
				});
			});
		</script>';


	$post_vars = get_the_terms($post->ID, 'product_cat');
	
	$product_cat = get_object_vars($post_vars[28]);
	}	
}

function tab_panels() {
	
	$panel1_id = 1595;
	$panel2_id = 1598;
	$panel3_id = 1619;

	$content_post1 = get_post($panel1_id);
	$content1 = $content_post1->post_content;
	$content1 = apply_filters('the_content', $content1);
	$content1 = str_replace(']]>', ']]>', $content1);
	
	$content_post2 = get_post($panel2_id);
	$content2 = $content_post2->post_content;
	$content2 = apply_filters('the_content', $content2);
	$content2 = str_replace(']]>', ']]>', $content2);
	
	$content_post3 = get_post($panel3_id);
	$content3 = $content_post3->post_content;
	$content3 = apply_filters('the_content', $content3);
	$content3 = str_replace(']]>', ']]>', $content3);
	
	
	echo '<div class="panel" id="tab-offerteaanvraag">';
		echo '<h2>Staat jouw gewenste maat er niet bij?</h2>';
		echo '<p>Geen probleem! Bij Steigerhout trend maken we alle meubels op bestelling. We kunnen dus makkelijk een aanpassing in het model of de maten uitvoeren.</p>
		<p>Omschrijf in onderstaand formulier hoe het meubel eruit moet zien, dan sturen we binnen een dag een geheel vrijblijvende offerte op maat.</p>';
		
		gravity_form(4, $display_title=false, $display_description=false, $display_inactive=false, $field_values=null, $ajax=true);
	echo '</div>';
	
	echo '<div class="panel" id="'.$panel1_id.'">';
	
	echo $content1;
	
	echo '</div>';
	
	echo '<div class="panel" id="'.$panel2_id.'">';
	
	echo $content2;

	echo '</div>';
	
	$post_vars = get_the_terms($post->ID, 'product_cat');
	
	$product_cat = get_object_vars($post_vars[28]);
	
	if(array_key_exists( 28, $post_vars) || array_key_exists( 29, $post_vars) || array_key_exists( 30, $post_vars) ) {
	
		echo '<div class="panel" id="'.$panel3_id.'">';
	
		echo $content3;

		echo '</div>';
	} //end if category = loungebanken / tuinmeubelen
	
}

add_filter("gform_currencies", "update_currency");
  
function update_currency($currencies) {
    $currencies['EUR'] = array( "name" => __("Euro", "gravityforms"),
        "symbol_left" => '€',
        "symbol_right" => '',
        "symbol_padding" => '',
        "thousand_separator" => '.',
        "decimal_separator" => ',',
        "decimals" => 2);

    return $currencies;
}

add_action('woocommerce_after_add_to_cart_form','shipping_2_weeks');

function shipping_2_weeks() {
	echo '<div class="after-cartform"><strong>Binnen twee weken bezorgd voor slechts €59,-</strong>Uiteraard leveren we altijd op afspraak</div>';
}

update_option( 'woocommerce_thumbnail_image_crop', 1 );
update_option( 'woocommerce_single_image_crop', 1 ); 
update_option( 'woocommerce_catalog_image_crop', 1 );

//add_action('woocommerce_before_checkout_billing_form','vakantie_melding');

function vakantie_melding() {
	echo '<div class="after-cartform" style="background: #FCF0AD;margin-top: 0px;"><strong>Let op!</strong>In verband met de vakantie geldt een iets langere levertijd. Als je nu bestelt, vindt de levering vanaf week 34 plaats.</div>';
}
/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>