<?php
defined( 'ABSPATH' ) or die();
/*
Plugin Name: Perfect-Sales CDN-plugin
Description: This plugin loads CDN-content using a simple shortcode and allows to empty the title on these pages. Shortcode example: [cdncontent]. 
Version: 2.0
*/
add_action( 'admin_menu', 'ps_cdn_plugin_smenu' );
function ps_cdn_plugin_smenu() {
	add_options_page( 'CDN-plugin Perfect-Sales', 'Perfect-Sales CDN', 'manage_options', 'ps-cdn-menu', 'ps_cdn_plugin_options' );
}

add_action( 'admin_init', 'ps_cdn_settings_init' );
function ps_cdn_settings_init(  ) {
	register_setting( 'pluginPage', 'ps_cdn_settings' );
	add_settings_section(
		'ps_cdn_pluginPage_section', 
		__( 'Instellingen', 'wordpress' ), 
		'ps_cdn_settings_section_callback', 
		'pluginPage'
	);
	add_settings_field( 
		'ps_cdn_text_field_0', 
		__( 'Klant-code', 'wordpress' ), 
		'ps_cdn_text_field_0_render', 
		'pluginPage', 
		'ps_cdn_pluginPage_section' 
	);
	add_settings_field( 
		'ps_cdn_checkbox_field_1', 
		__( 'Paginatitel verwijderen', 'wordpress' ), 
		'ps_cdn_checkbox_field_1_render', 
		'pluginPage', 
		'ps_cdn_pluginPage_section' 
	);
}
function ps_cdn_text_field_0_render(  ) { 
	$options = get_option( 'ps_cdn_settings' );
	?>
	<input type='text' name='ps_cdn_settings[ps_cdn_text_field_0]' value='<?php echo $options['ps_cdn_text_field_0']; ?>'>
	<?php
}
function ps_cdn_checkbox_field_1_render(  ) { 
	$options = get_option( 'ps_cdn_settings' );
	?>
	<input type='checkbox' name='ps_cdn_settings[ps_cdn_checkbox_field_1]' <?php checked( $options['ps_cdn_checkbox_field_1'], 1 ); ?> value='1'>
	<?php
}
function ps_cdn_settings_section_callback(  ) { 
	echo __( 'Neem contact op met Perfect-Sales voor meer informatie.', 'wordpress' );
}
function ps_cdn_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
<div class="wrap">
<form action='options.php' method='post'>
<h1>Perfect-Sales CDN-plugin</h1>
<?php
settings_fields( 'pluginPage' );
do_settings_sections( 'pluginPage' );
submit_button();
?>
</form>
</div>
<?php
}

function pscdn_func( $atts ) {
     extract( shortcode_atts( array(
    'client' => ''
    ), $atts ) );
if($client==''){
$pscdnoptions = get_option( 'ps_cdn_settings' );
$client = $pscdnoptions['ps_cdn_text_field_0'];
}
if(!$client==''){
ini_set('display_errors','off');
echo "<div class='contentcdn'>";
$local_url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url='http://cdn.perfect-sales.nl/'.$client.'/'.base64_encode($local_url);
try {echo file_get_contents($url);}
catch (Exception $exc) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    echo $data;
}
echo "</div>";
}}
add_shortcode( 'cdncontent', 'pscdn_func' );

function cdn_shortcode_check() {
global $post;
if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'cdncontent') ) {
$pscdnoptions = get_option( 'ps_cdn_settings' );
$notitle = $pscdnoptions['ps_cdn_checkbox_field_1'];
if(!$notitle==''){
add_filter( 'the_title', 'cdnonetitle' );
function cdnonetitle($title) {
if ( in_the_loop() ){
return '';
}else{
return $title;
}}}
}}
add_action( 'wp_head', 'cdn_shortcode_check');
?>