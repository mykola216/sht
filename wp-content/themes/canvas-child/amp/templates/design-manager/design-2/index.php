<?php global $redux_builder_amp; ?>
<!doctype html>
<html amp>
<head>
	<meta charset="utf-8">
    <link rel="dns-prefetch" href="https://cdn.ampproject.org">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>

	<style amp-custom>
	<?php $this->load_parts( array( 'style' ) ); ?>
	<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
	<style>
		.amp-wp-price .price{
			color: #f70;
			font: normal normal bold 14px helvetica, arial, sans-serif;
			margin: 0 0 0 5px;
			text-align: left;
		}
		.amp-wp-price .price del{
			color: #666;
			font-weight: normal;
			font-size: 10px;
		}
		.amp-wp-price .price ins{
			text-decoration: none;
		}
	</style>
</head>
<body class="amp_home_body">
<?php $this->load_parts( array( 'header-bar' ) ); ?>

<?php do_action( 'ampforwp_after_header', $this ); ?>

<main>

	<?php if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			global $product;
			$ampforwp_amp_post_url = trailingslashit( get_permalink() ) . AMP_QUERY_VAR ;

		?>

		<div class="amp-wp-content amp-loop-list">
			<?php if ( has_post_thumbnail() ) { ?>
				<?php
				$thumb_id = get_post_thumbnail_id();
				$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail', true);
				$thumb_url = $thumb_url_array[0];
				?>
				<div class="home-post_image"><a href="<?php echo esc_url( $ampforwp_amp_post_url ); ?>"><amp-img src=<?php echo $thumb_url ?> width=100 height=75></amp-img></a></div>
			<?php } ?>

			<div class="amp-wp-post-content">

				<h2 class="amp-wp-title"> <a href="<?php echo esc_url( $ampforwp_amp_post_url ); ?>"> <?php the_title(); ?></a></h2>
				<div class="amp-wp-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<div class="price"><?php echo $product->get_price_html(); ?></div>
					<meta itemprop="price" content="<?php echo esc_attr( $product->get_price() ); ?>" />
					<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
					<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
					<meta itemprop="itemCondition" content="http://schema.org/NewCondition" />
				</div>

				<?php

				if(has_excerpt()){
					$content = get_the_excerpt();
				}else{
					$content = get_the_content();
				}
				?>
		        <p><?php echo wp_trim_words( $content , '15' ); ?></p>

		    </div>
            <div class="cb"></div>
	</div>

	<?php endwhile;  ?>


	<div class="amp-wp-content pagination-holder">

		<div id="pagination">
			<div class="next"><?php next_posts_link( $redux_builder_amp['amp-translator-next-text'] . ' &raquo;', 0 ) ?></div>
			<div class="prev"><?php previous_posts_link( '&laquo; '. $redux_builder_amp['amp-translator-previous-text'] ); ?></div>

			<div class="clearfix"></div>
		</div>
	</div>

	<?php endif; ?>
</main>
<?php $this->load_parts( array( 'footer' ) ); ?>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>

</html>
