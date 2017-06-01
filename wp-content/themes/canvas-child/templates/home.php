<?php
/**
 * Home page.
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $st_options;

$text_our_team = get_field('our_team_home_text', 'option');
$img_our_team = get_field('our_team_home', 'option');

?>

<div id="slider">
	<div class="caption">
		<h2><?php echo esc_html( $st_options['slider_title'] ); ?></h2>
		<a href="/shop" class="button orange-button"><?php _e('All scaffold wood articles', 'woothemes'); ?></a>
	</div>

	<?php echo do_shortcode( $st_options['slider_shortcode'] ); ?>
	<ul>
		<a href="<?php echo esc_attr( $st_options['subslider_url1'] ); ?>">
			<li>
				<div class="custom1"></div>
				<?php echo esc_html( $st_options['subslider_text1'] ); ?>
			</li>
		</a>
		<a href="<?php echo esc_attr( $st_options['subslider_url2'] ); ?>">
			<li>
				<div class="custom2"></div>
				<?php echo esc_html( $st_options['subslider_text2'] ); ?>
			</li>
		</a>
		<a href="<?php echo esc_attr( $st_options['subslider_url3'] ); ?>">
			<li>
				<div class="custom3"></div>
				<?php echo esc_html( $st_options['subslider_text3'] ); ?>
			</li>
		</a>
		<a href="<?php echo esc_attr( $st_options['subslider_url4'] ); ?>">
			<li>
				<div class="custom4"></div>
				<?php echo esc_html( $st_options['subslider_text4'] ); ?>
			</li>
		</a>
	</ul>
</div>






<!--Best Selling Products-->
<?php
	canvas_child_top_products( array(
		'title'   => $st_options['best_sellers_title'],
		'href'    => $st_options['best_sellers_url'],
		'text'    => 'Best verkochte steigerhouten meubelen',
		'columns' => 3,
		'args'    => apply_filters( 'canvas_child_bestselling_products_args', array(
			'ignore_sticky_posts' => 1,
			'meta_key'            => 'total_sales',
			'no_found_rows'       => 1,
			'orderby'             => 'meta_value_num',
			'order'               => 'DESC',
			'post_type'           => 'product',
			'posts_per_page'      => 6,
			'stock'               => 1
		))
	));
?>


<!-- Custom Category Products -->
<?php
	$product_cat_slug = end(explode('/', trim($st_options['custom_cat_products_url'], '/')));
	canvas_child_top_products( array(
		'title'   => $st_options['custom_cat_products_title'],
		'href'    => $st_options['custom_cat_products_url'],
		'text'    => $st_options['custom_cat_products_btn_label'],
		'container_class' => 'custom-cat-products',
		'columns' => 4,
		'args'    => apply_filters( 'canvas_child_bestselling_products_args', array(
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'orderby'             => 'title',
			'order'               => 'ASC',
			'post_type'           => 'product',
			'posts_per_page'      => 8,
			'stock'               => 1,
			'product_cat' => $product_cat_slug,
			/*'tax_query' => array(
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => $product_cat_ID
			),*/
		))
	));
?>



<!--Recent Products-->
<?php
	canvas_child_top_products( array(
		'title'   => $st_options['recent_products_title'],
		'href'    => $st_options['recent_products_url'],
		'text'    => 'Nieuwe steigerhouten meubelen',
		'columns' => 3,
		'args'    => apply_filters( 'canvas_child_recent_products_args', array(
			'ignore_sticky_posts' => 1,
			'meta_key'            => '_featured',
			'meta_value'          => 'no',
			'no_found_rows'       => 1,
			'order'               => 'DESC',
			'orderby'             => 'date',
			'post_type'           => 'product',
			'posts_per_page'      => 3,
			'stock'               => 1
		))
	));
?>


<!--Furniture Module-->
<div class="type-furniture">
	<div class="__container">
		<?php $counter = 0; ?>
		<?php if ( have_rows('kinds_of_furniture_repeater', 'option') ) { ?>
			<div class="caption">
				<h2><?php echo esc_html( $st_options['type_furniture_title'] ); ?></h2>
			</div>
			<div class="row">
				<?php while ( have_rows('kinds_of_furniture_repeater', 'option') ) { the_row();
					$image = get_sub_field('img');
					$link = get_sub_field('link');
				?>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 wrap-item clearfix">
						<a href="<?php echo $link; ?>">
							<img src="<?php echo $image['url']; ?>" alt="<? echo $image['alt']; ?>">
						</a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>

<hr>

<!-- Our Team Module-->
<section class="our-team">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 wrap-item">
				<div class="wrapper">
					<?php echo $text_our_team; ?>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 wrap-item">
				<div class="wrapper">
					<?php echo $img_our_team; ?>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</section>


<!--Output the sharethis module-->
<?php wc_get_template_part( 'templates/module', 'sharethis' ); ?>