<div id="bestselling-products">
	<a href="<?php echo $options['best_sellers_url']; ?>" class="button alt2 fr">
		Best verkochte steigerhouten meubelen
	</a>
	<h2 class="widget-title">
		<?php _e($options['best_sellers_title'], 'woothemes'); ?>
	</h2>
	<div class="carousel">
		<ul class="bestselling-products carousel-products">
			<?php
				$best_selling_products_per_page = 3;
				$args = array( 'post_type' => 'product',
					'posts_per_page' => $best_selling_products_per_page,
					'meta_key' => 'total_sales',
					'orderby' => 'meta_value_num',
					'order' => 'DESC');
				$loop = new WP_Query( $args );
				while ($loop->have_posts()) :
					$loop->the_post();
					$_product = new WC_Product( $loop->post->ID );
			?>
				<li>
					<div class="img-wrap">
						<a href="<?php echo get_permalink($loop->post->ID ) ?>">
							<?php
								if (has_post_thumbnail( $loop->post->ID ))
									echo get_the_post_thumbnail($loop->post->ID, 'shop_single');
								else
									echo '<img src="'.woocommerce_placeholder_img_src().'"alt="Placeholder" />';
							?>
						</a>
					</div>
					<h3>
						<a href="<?php echo get_permalink( $loop->post->ID ) ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<span class="carousel-price">
						<?php echo $_product->get_price_html(); ?>
					</span>
					<div class="carousel-excerpt">
						<?php echo $post->post_excerpt; ?>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>
		<div style="clear: both;">
<!--                 <a href="/best-selling-products/" class="button fr">
				Best verkochte steigerhouten meubelen
			</a> -->
		</div>
	</div> <!--/.carousel-->
</div>