<style>
    .sliding-child td input[type=text] {
        width: 100%;
    }
</style>
<div class="wrap sliding-child">
    <h2>sliding-child Theme Options</h2>
    <form method="post" action="options.php">
        <?php settings_fields('sliding_child_options'); ?>
        <?php $options = get_option('sliding_child_options'); ?>
        <table class="form-table">
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Options</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">Show Usps sidebar</th>
                <td>
                    <input type="checkbox" name="sliding_child_options[show_usps_sidebar]" <?php echo ($options['show_usps_sidebar']=='on')?'checked="checked"':''; ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The working time</th>
                <td>
                    <input  type="text" name="sliding_child_options[working_time]" value="<?php echo $options['working_time']; ?>" />
                </td>
            </tr>
            
            <!-- Home page band module -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Home page band module</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">Home page band text</th>
                <td>
                    <input  type="text" name="sliding_child_options[home_band_text]" value="<?php echo $options['home_band_text']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Home page band logo</th>
                <td>
                    <input  type="text" name="sliding_child_options[home_band_logo]" value="<?php echo $options['home_band_logo']; ?>" />
                </td>
            </tr>

            <!-- Slider module -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Slider module</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">The slider shortcode</th>
                <td>
                    <input type="text" name="sliding_child_options[slider_shortcode]" value="<?php echo $options['slider_shortcode']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The text under the slider 1</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_text1]" value="<?php echo $options['subslider_text1']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The url under the slider 1</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_url1]" value="<?php echo $options['subslider_url1']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The text under the slider 2</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_text2]" value="<?php echo $options['subslider_text2']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The url under the slider 2</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_url2]" value="<?php echo $options['subslider_url2']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The text under the slider 3</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_text3]" value="<?php echo $options['subslider_text3']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The url under the slider 3</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_url3]" value="<?php echo $options['subslider_url3']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The text under the slider 4</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_text4]" value="<?php echo $options['subslider_text4']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The url under the slider 4</th>
                <td>
                    <input type="text" name="sliding_child_options[subslider_url4]" value="<?php echo $options['subslider_url4']; ?>" />
                </td>
            </tr>

            <!-- Recent products module -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Recent products module</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">Recent products URL</th>
                <td>
                    <input type="text" name="sliding_child_options[recent_products_url]" value="<?php echo $options['recent_products_url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Recent products module title</th>
                <td>
                    <input type="text" name="sliding_child_options[recent_products_title]" value="<?php echo $options['recent_products_title']; ?>" />
                </td>
            </tr>

            <!-- Best sellers module -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Best sellers module</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">Best sellers URL</th>
                <td>
                    <input type="text" name="sliding_child_options[best_sellers_url]" value="<?php echo $options['best_sellers_url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Best sellers module title</th>
                <td>
                    <input type="text" name="sliding_child_options[best_sellers_title]" value="<?php echo $options['best_sellers_title']; ?>" />
                </td>
            </tr>

            <!-- Social media module -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Social media module</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">The Facebook URL</th>
                <td>
                    <input type="text" name="sliding_child_options[facebook_url]" value="<?php echo $options['facebook_url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The Google+ URL</th>
                <td>
                    <input type="text" name="sliding_child_options[googleplus_url]" value="<?php echo $options['googleplus_url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The Instagram URL</th>
                <td>
                    <input type="text" name="sliding_child_options[instagram_url]" value="<?php echo $options['instagram_url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The Twitter URL</th>
                <td>
                    <input type="text" name="sliding_child_options[twitter_url]" value="<?php echo $options['twitter_url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The Youtube URL</th>
                <td>
                    <input type="text" name="sliding_child_options[youtube_url]" value="<?php echo $options['youtube_url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">The Pinterest URL</th>
                <td>
                    <input type="text" name="sliding_child_options[pinterest_url]" value="<?php echo $options['pinterest_url']; ?>" />
                </td>
            </tr>

            <!-- Furniture production module -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Furniture production module</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">Furniture production title</th>
                <td>
                    <input type="text" name="sliding_child_options[production-title]" value="<?php echo $options['production-title']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Furniture production text</th>
                <td>
                    <input type="text" name="sliding_child_options[production-text]" value="<?php echo $options['production-text']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Furniture production image url</th>
                <td>
                    <input type="text" name="sliding_child_options[production-img-url]" value="<?php echo $options['production-img-url']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Furniture production page url</th>
                <td>
                    <input type="text" name="sliding_child_options[production-page-url]" value="<?php echo $options['production-page-url']; ?>" />
                </td>
            </tr>

            <!-- Shipping terms -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Shipping terms</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">Shipping terms line 1</th>
                <td>
                    <input type="text" name="sliding_child_options[shipping_terms_line1]" value="<?php echo $options['shipping_terms_line1']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Shipping terms line 2</th>
                <td>
                    <input type="text" name="sliding_child_options[shipping_terms_line2]" value="<?php echo $options['shipping_terms_line2']; ?>" />
                </td>
            </tr>

            <!-- Product page info block -->
            <tr>
                <td colspan="2" bgcolor="#abc"><strong>Product page info block</strong></td>
            </tr>
            <tr valign="top">
                <th scope="row">Text 1</th>
                <td>
                    <input type="text" name="sliding_child_options[info_block_text1]" value="<?php echo $options['info_block_text1']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Text 2</th>
                <td>
                    <input type="text" name="sliding_child_options[info_block_text2]" value="<?php echo $options['info_block_text2']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Text 3</th>
                <td>
                    <input type="text" name="sliding_child_options[info_block_text3]" value="<?php echo $options['info_block_text3']; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Text 4</th>
                <td>
                    <input type="text" name="sliding_child_options[info_block_text4]" value="<?php echo $options['info_block_text4']; ?>" />
                </td>
            </tr>
        </table>
        <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>
