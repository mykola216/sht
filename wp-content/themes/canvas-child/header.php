<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

$d_s_logo = get_field('d_s_logo');
$d_s_logo_image_url = $d_s_logo['url'];
$d_s_logo_link = get_field('d_s_logo_link', 'option');
$d_s_work_time = get_field('d_s_work_time', 'option');
$d_s_phone_number = get_field('d_s_phone_number', 'option');

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>" />
<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
<?php wp_head(); ?>
<?php woo_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php woo_top(); ?>
<section class="dark-section">
    <div class="flex-container">
        <a href="<?php echo $d_s_logo_link; ?>" target="_blank" class="d-s-logo" style="background-image: url('<?php echo $d_s_logo_image_url; ?>')"></a>
        <h6 class="d-s-work-time"><?php echo $d_s_work_time; ?></h6>
        <h6 class="d-s-contact">Maatwerk <span></span> <a href="<?php echo get_permalink(1816); ?>">Contact</a></h6>
        <a href="tel:<?php echo $d_s_phone_number; ?>" class="d-s-phone"><?php echo $d_s_phone_number; ?></a>
    </div>
</section>
<section class="advantages-header hidden-xs">
    <div class="wrap-adv">
        <?php $counter = 0; ?>
        <?php if ( have_rows('advantages_repeater', 'option') ) : ?>
                <?php while ( have_rows('advantages_repeater', 'option') ) : the_row();
                    $text = get_sub_field('text');
                    ?>
                    <div class="adv-item"><?php echo $text; ?></div>
                <?php endwhile; ?>

        <?php endif; ?>
    </div>
</section>
<div id="wrapper">

	<div id="inner-wrapper">

	<?php woo_header_before(); ?>

	<header id="header" class="col-full">

		<?php woo_header_inside(); ?>

	</header>
	<?php woo_header_after(); ?>