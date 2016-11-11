<?php global $st_options; ?>

<style>
	.sliding-child td input[type=text] {
		width: 100%;
	}
</style>

<div class="wrap sliding-child">

	<h2>steigerhouttrend.nl Site Options</h2>

	<form method="post" action="options.php">

		<?php settings_fields( 'steigerhouttrend_options' ); ?>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

		<table class="form-table">
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Options</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Show Usps sidebar</th>
				<td>
					<input type="checkbox" name="steigerhouttrend_options[show_usps_sidebar]" <?php checked( $st_options['show_usps_sidebar'], 'on' ); ?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The working time</th>
				<td>
					<input  type="text" name="steigerhouttrend_options[working_time]" value="<?php echo $st_options['working_time']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">AdWords Remarketing ID</th>
				<td>
					<input  type="text" name="steigerhouttrend_options[conversion_id]" value="<?php echo $st_options['conversion_id']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show Instagram Feed</th>
				<td>
					<input type="checkbox" name="steigerhouttrend_options[show_instagram_feed]" value="1" <?php checked( $st_options['show_instagram_feed'], 1 ); ?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show Instagram Feed Only in Product Category</th>
				<td>
					<input type="checkbox" name="steigerhouttrend_options[show_instagram_feed_in_product_cat]" value="1" <?php checked( $st_options['show_instagram_feed_in_product_cat'], 1 ); ?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Instagram Feed Title</th>
				<td>
					<input type="text" name="steigerhouttrend_options[instagram_feed_title]" value="<?php echo $st_options['instagram_feed_title']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Instagram Feed ShortCode</th>
				<td>
					<textarea name="steigerhouttrend_options[instagram_feed_shortcode]" style="resize: both;width: 100%;"><?php echo $st_options['instagram_feed_shortcode']; ?></textarea>
				</td>
			</tr>

			<!-- Home page band module -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Home page band module</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Home page band text</th>
				<td>
					<input  type="text" name="steigerhouttrend_options[home_band_text]" value="<?php echo $st_options['home_band_text']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Home page band logo</th>
				<td>
					<input  type="text" name="steigerhouttrend_options[home_band_logo]" value="<?php echo $st_options['home_band_logo']; ?>" />
				</td>
			</tr>

			<!-- Slider module -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Slider module</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">The slider title</th>
				<td>
					<input type="text" name="steigerhouttrend_options[slider_title]" value="<?php echo $st_options['slider_title']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The slider shortcode</th>
				<td>
					<input type="text" name="steigerhouttrend_options[slider_shortcode]" value="<?php echo $st_options['slider_shortcode']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The text under the slider 1</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_text1]" value="<?php echo $st_options['subslider_text1']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The url under the slider 1</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_url1]" value="<?php echo $st_options['subslider_url1']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The text under the slider 2</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_text2]" value="<?php echo $st_options['subslider_text2']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The url under the slider 2</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_url2]" value="<?php echo $st_options['subslider_url2']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The text under the slider 3</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_text3]" value="<?php echo $st_options['subslider_text3']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The url under the slider 3</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_url3]" value="<?php echo $st_options['subslider_url3']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The text under the slider 4</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_text4]" value="<?php echo $st_options['subslider_text4']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The url under the slider 4</th>
				<td>
					<input type="text" name="steigerhouttrend_options[subslider_url4]" value="<?php echo $st_options['subslider_url4']; ?>" />
				</td>
			</tr>

			<!-- Recent products module -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Recent products module</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Recent products URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[recent_products_url]" value="<?php echo $st_options['recent_products_url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Recent products module title</th>
				<td>
					<input type="text" name="steigerhouttrend_options[recent_products_title]" value="<?php echo $st_options['recent_products_title']; ?>" />
				</td>
			</tr>

			<!-- Best sellers module -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Best sellers module</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Best sellers URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[best_sellers_url]" value="<?php echo $st_options['best_sellers_url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Best sellers module title</th>
				<td>
					<input type="text" name="steigerhouttrend_options[best_sellers_title]" value="<?php echo $st_options['best_sellers_title']; ?>" />
				</td>
			</tr>

			<!-- Social media module -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Social media module</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">The Facebook URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[facebook_url]" value="<?php echo $st_options['facebook_url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The Google+ URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[googleplus_url]" value="<?php echo $st_options['googleplus_url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The Instagram URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[instagram_url]" value="<?php echo $st_options['instagram_url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The Twitter URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[twitter_url]" value="<?php echo $st_options['twitter_url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The Youtube URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[youtube_url]" value="<?php echo $st_options['youtube_url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">The Pinterest URL</th>
				<td>
					<input type="text" name="steigerhouttrend_options[pinterest_url]" value="<?php echo $st_options['pinterest_url']; ?>" />
				</td>
			</tr>

			<!-- Furniture production module -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Furniture production module</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Furniture production title</th>
				<td>
					<input type="text" name="steigerhouttrend_options[production-title]" value="<?php echo $st_options['production-title']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Furniture production text</th>
				<td>
					<input type="text" name="steigerhouttrend_options[production-text]" value="<?php echo $st_options['production-text']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Furniture production image url</th>
				<td>
					<input type="text" name="steigerhouttrend_options[production-img-url]" value="<?php echo $st_options['production-img-url']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Furniture production page url</th>
				<td>
					<input type="text" name="steigerhouttrend_options[production-page-url]" value="<?php echo $st_options['production-page-url']; ?>" />
				</td>
			</tr>

			<!-- Shipping terms -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Shipping terms</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Shipping terms line 1</th>
				<td>
					<input type="text" name="steigerhouttrend_options[shipping_terms_line1]" value="<?php echo $st_options['shipping_terms_line1']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Shipping terms line 2</th>
				<td>
					<input type="text" name="steigerhouttrend_options[shipping_terms_line2]" value="<?php echo $st_options['shipping_terms_line2']; ?>" />
				</td>
			</tr>

			<!-- Product page info block -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Product page info block</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Text 1</th>
				<td>
					<input type="text" name="steigerhouttrend_options[info_block_text1]" value="<?php echo $st_options['info_block_text1']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Text 2</th>
				<td>
					<input type="text" name="steigerhouttrend_options[info_block_text2]" value="<?php echo $st_options['info_block_text2']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Text 3</th>
				<td>
					<input type="text" name="steigerhouttrend_options[info_block_text3]" value="<?php echo $st_options['info_block_text3']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Text 4</th>
				<td>
					<input type="text" name="steigerhouttrend_options[info_block_text4]" value="<?php echo $st_options['info_block_text4']; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show "SALE" labels only for single product page </th>
				<td>
					<input type="checkbox" name="steigerhouttrend_options[show_sale_label_only_sngl]" value="1" <?php checked( $st_options['show_sale_label_only_sngl'], 1 ); ?> />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Hide "SALE" labels everywhere</th>
				<td>
					<input type="checkbox" name="steigerhouttrend_options[hide_sale_label]" value="1" <?php checked( $st_options['hide_sale_label'], 1 ); ?> />
				</td>
			</tr>

			<!-- Cart page -->
			<tr>
				<td colspan="2" bgcolor="#abc"><strong>Cart page</strong></td>
			</tr>
			<tr valign="top">
				<th scope="row">Coupon button text</th>
				<td>
					<input type="text" name="steigerhouttrend_options[coupon_btn_text]" value="<?php echo $st_options['coupon_btn_text']; ?>" />
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

	</form>
</div>