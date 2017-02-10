<?php defined( 'ABSPATH' ) || die();
/**
* Facebook moderation module.
*
* @author   Aleksandr Levashov
* @version  1.0.0
*/
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
?>
<meta property="og:title"       content="<?php echo esc_attr( get_the_title() ); ?>" />
<meta property="og:type"        content="website" />
<meta property="og:url"         content="<?php echo esc_attr( str_replace( 'https://', 'http://', get_permalink() ) );
?>" />
<meta property="og:image"       content="<?php echo esc_attr( $thumb['0'] ); ?>" />
<meta property="og:description" content="<?php echo esc_attr( get_the_excerpt() ); ?>" />
<meta property="fb:app_id"      content="1613892592185308" />


