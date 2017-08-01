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
$title_our_cli = get_field('title_our_clients', 'option');
$link_our_cli = get_field('link_our_clients', 'option');
$text_our_cli = get_field('text_our_clients', 'option');

?>

<div id="slider">
	<?php echo do_shortcode( $st_options['slider_shortcode'] ); ?>
</div>
<section class="advantages-header visible-xs">
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


<a href="<?php echo  $st_options['best_sellers_url']; ?>" class="home-title-module without-border"><?php echo $st_options['best_sellers_title']; ?></a>
<!--Best Selling Products-->
<?php
	canvas_child_top_products( array(
//		'title'   => $st_options['best_sellers_title'],
//		'href'    => $st_options['best_sellers_url'],
//		'text'    => 'Best verkochte steigerhouten meubelen',
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
<?php if ( have_rows('custom_product_module_repeater', 'option') ) : ?>
    <?php while ( have_rows('custom_product_module_repeater', 'option') ) : the_row();
        $title = get_sub_field('title');
        $link_to_cat = get_sub_field('link_to_cat');
        $text = get_sub_field('text');
        $counter = 0;

        ?>
        <section class="custom_product_module clearfix">
            <a href="<?php echo $link_to_cat; ?>" class="home-title-module"><?php echo $title; ?></a>
                    <div class="first-col clearfix">
                        <?php echo $text; ?>
                        <a href="<?php echo $link_to_cat; ?>" class="module-btn">Meer <span></span></a>
                    </div>
                    <div class="second-col clearfix">
                        <?php

                            $count = count( get_sub_field( 'product_image_repeater', 'option' ) );

                            if ( have_rows('product_image_repeater', 'option') ) :
                                $i = 0;
                                ?>

                                <?php while ( have_rows('product_image_repeater', 'option') ) : the_row();
                                    $img = get_sub_field('img');
                                    $pro_link = get_sub_field('product_link');
                                    $i++; ?>

                                        <a href="<?php echo $pro_link; ?>" class="product-item <?php if($count > 2 && $i==1 || $i%4 == 0){echo 'large-img';} elseif($count< 3){echo 'one-row small-img';} else{echo 'small-img';}?>">
                                            <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                        </a>

                                <?php endwhile; ?>

                        <?php endif; ?>
                    </div>


        </section>
    <?php endwhile; ?>
<?php endif; ?>





<!--<!-- Custom Category Products -->
<?php
//	$product_cat_slug = end(explode('/', trim($st_options['custom_cat_products_url'], '/')));
//	canvas_child_top_products( array(
//		'title'   => $st_options['custom_cat_products_title'],
//		'href'    => $st_options['custom_cat_products_url'],
//		'text'    => $st_options['custom_cat_products_btn_label'],
//		'container_class' => 'custom-cat-products',
//		'columns' => 4,
//		'args'    => apply_filters( 'canvas_child_bestselling_products_args', array(
//			'ignore_sticky_posts' => 1,
//			'no_found_rows'       => 1,
//			'orderby'             => 'title',
//			'order'               => 'ASC',
//			'post_type'           => 'product',
//			'posts_per_page'      => 8,
//			'stock'               => 1,
//			'product_cat' => $product_cat_slug,
//			/*'tax_query' => array(
//				'taxonomy' => 'product_cat',
//				'field'    => 'id',
//				'terms'    => $product_cat_ID
//			),*/
//		))
//	));
//?>



<!--<!--Recent Products-->
<?php
//	canvas_child_top_products( array(
//		'title'   => $st_options['recent_products_title'],
//		'href'    => $st_options['recent_products_url'],
//		'text'    => 'Nieuwe steigerhouten meubelen',
//		'columns' => 3,
//		'args'    => apply_filters( 'canvas_child_recent_products_args', array(
//			'ignore_sticky_posts' => 1,
//			'meta_key'            => '_featured',
//			'meta_value'          => 'no',
//			'no_found_rows'       => 1,
//			'order'               => 'DESC',
//			'orderby'             => 'date',
//			'post_type'           => 'product',
//			'posts_per_page'      => 3,
//			'stock'               => 1
//		))
//	));
//?>


<!--Furniture Module-->
<div class="type-furniture">
	<div class="__container">
		<?php if ( have_rows('kinds_of_furniture_repeater', 'option') ) { ?>
			<div class="caption">
				<h2><?php echo esc_html( $st_options['type_furniture_title'] ); ?></h2>
			</div>
			<div class="row">
				<?php while ( have_rows('kinds_of_furniture_repeater', 'option') ) { the_row();
					$image = get_sub_field('img');
					$link = get_sub_field('link');
                    $counter = 0;
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

<section class="clients">
    <a href="<?php echo $link_our_cli; ?>" class="home-title-module"><?php echo $title_our_cli; ?></a>
    <?php echo $text_our_cli; ?>

    <?php if ( have_rows('list_of_our_clients_repeater', 'option') ) : ?>
        <div class="wrap-loop owl-carousel">
            <?php while ( have_rows('list_of_our_clients_repeater', 'option') ) : the_row();
                $img = get_sub_field('img');
                $link = get_sub_field('link');
                ?>
                <a  href="<?php echo $link; ?>" rel="nofollow">
                    <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                </a>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</section>