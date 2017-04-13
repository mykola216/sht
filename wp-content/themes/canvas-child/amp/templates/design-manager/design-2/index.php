<?php global $redux_builder_amp;  ?>
<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
  <link rel="dns-prefetch" href="https://cdn.ampproject.org">
	<?php
	global $redux_builder_amp;
	if ( is_home() || is_front_page()  || ( is_archive() && $redux_builder_amp['ampforwp-archive-support'] ) ){
		global $wp;
		$current_archive_url = home_url( $wp->request );
		$amp_url 	= trailingslashit($current_archive_url);
		$remove 	= '/'. AMPFORWP_AMP_QUERY_VAR;
		$amp_url 	= str_replace($remove, '', $amp_url) ;
	} ?>
	<link rel="canonical" href="<?php echo $amp_url ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>

	<style amp-custom>
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
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
		.current-menu-item a {
			font-weight: bold;
			color: #666;
		}
		main .taxonomy-description{
			overflow: hidden;
			padding: 0;
			background: transparent;
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;
		}
	</style>
</head>
<body class="amp_home_body design_2_wrapper">
<?php $this->load_parts( array( 'header-bar' ) ); ?>
<?php do_action( 'ampforwp_after_header', $this ); ?>
<?php do_action('ampforwp_home_above_loop') ?>
<main>
	<?php do_action('ampforwp_post_before_loop') ?>

	<?php
		if ( get_query_var( 'paged' ) ) {
	        $paged = get_query_var('paged');
	    } elseif ( get_query_var( 'page' ) ) {
	        $paged = get_query_var('page');
	    } else {
	        $paged = 1;
	    }

	    $exclude_ids = get_option('ampforwp_exclude_post');

		$args = array(
			'post_type'           => 'post',
			'orderby'             => 'date',
			'paged'               => esc_attr($paged),
			'post__not_in' 		  => $exclude_ids,
			'has_password' => false ,
			'post_status'=> 'publish'
		);
		if (is_page()) {
			$args['post_type'] = 'page';
		}
		if (is_singular('product')) {
			$args['post_type'] = 'product';
		}
		if (is_tax('product_cat')) {
			//$args['posts_per_page'] = -1;
			$args['post_type'] = 'product';
			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'id',
					'terms'    => array( get_queried_object_id() ), // ID of current cat
				),
			);
		}
		$filtered_args = apply_filters('ampforwp_query_args', $args);
		$q = new WP_Query( $filtered_args ); ?>

 	<?php if ( is_archive() ) {
 			the_archive_title( '<h3 class="page-title amp-wp-content">', '</h3>' );
 			the_archive_description( '<div class="taxonomy-description amp-wp-content">', '</div>' );
 		} ?>

	<?php if ( $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post();
		global $product;
		$ampforwp_amp_post_url = trailingslashit( trailingslashit( get_permalink() ) . AMPFORWP_AMP_QUERY_VAR ) ; ?>

		<div class="amp-wp-content amp-loop-list">
			<?php if ( has_post_thumbnail() ) { ?>
				<?php
				$thumb_id = get_post_thumbnail_id();
				$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail', true);
				$thumb_url = $thumb_url_array[0];
				?>
				<div class="home-post_image">
					<a href="<?php echo esc_url( $ampforwp_amp_post_url ); ?>">
						<amp-img src=<?php echo $thumb_url ?>
							<?php if( $redux_builder_amp['ampforwp-homepage-posts-image-modify-size'] ) { ?>
							 width=<?php global $redux_builder_amp; echo $redux_builder_amp['ampforwp-homepage-posts-design-1-2-width'] ?>
							 height=<?php global $redux_builder_amp; echo $redux_builder_amp['ampforwp-homepage-posts-design-1-2-height'] ?>
						 <?php } else { ?>
							 width=100
							 height=75
						 <?php } ?>
						></amp-img>
					</a>
			</div>
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
		        <p><?php echo wp_trim_words( strip_shortcodes( $content ) , '15'  ); ?></p>

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
	<?php wp_reset_postdata(); ?>

	<?php do_action('ampforwp_post_after_loop') ?>

</main>
<?php do_action('ampforwp_home_below_loop') ?>
<?php $this->load_parts( array( 'footer' ) ); ?>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>

</html>