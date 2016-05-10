<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */
?>

<?php
    $options = get_option('sliding_child_options');

    woo_breadcrumbs();

    /**
     * woocommerce_before_single_product hook
     *
     * @hooked woocommerce_show_messages - 10
     */
    remove_action('woocommerce_before_single_product_summary', 'woo_breadcrumbs', 10);
    do_action( 'woocommerce_before_single_product' );
?>

<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single_product_lc">
        <?php
            /**
             * woocommerce_show_product_images hook
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
            the_content();
        ?>
    </div>

    <div class="woocommerce_tabs single_product_rc">
        <ul class="tabs">
            <li><a href="#tabs-1">Direct bestellen</a></li>
            <li><a href="#tabs-2">Offerte voor maatwerk</a></li>
        </ul>
        <div id="tabs-1" class="panel">
            <?php
                /**
                 * woocommerce_single_product_summary hook
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 */
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_content', 25);
                do_action( 'woocommerce_single_product_summary' );
            ?>
        </div>
        <div id="tabs-2" class="panel">
            <h2>Staat jouw gewenste maat er niet bij?</h2>
            <p>Geen probleem! Bij Steigerhout trend maken we alle meubels op bestelling. We kunnen dus makkelijk een aanpassing in het model of de maten uitvoeren.</p>
            <p>Omschrijf in onderstaand formulier hoe het meubel eruit moet zien, dan sturen we binnen een dag een geheel vrijblijvende offerte op maat.</p>
            <?php
                gravity_form(4, $display_title=false, $display_description=false, $display_inactive=false, $field_values=null, $ajax=true);
            ?>
        </div>

        <div id="info-block">
            <div id="info-block-trustpilot">
                <?php echo $options['info_block_text1']; ?>
            </div>
            <div id="info-block-delivery">
                <?php echo $options['info_block_text2']; ?>
            </div>
            <div id="info-block-customer-service">
                <?php echo $options['info_block_text3']; ?>
            </div>
            <div id="info-block-order-check">
                <?php echo $options['info_block_text4']; ?>
            </div>
        </div>

        <!-- ShareThis social icons -->
        <?php include(locate_template('templates/social_media.php')); ?>
    </div>
    <?php
        /**
         * woocommerce_after_single_product_summary hook
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_output_related_products - 20
         */
        do_action( 'woocommerce_after_single_product_summary' );
    ?>
</div><!-- #product-<?php the_ID(); ?> -->

<?php
    remove_action('woocommerce_product_tabs','tab_names');
    remove_action('woocommerce_product_tab_panels','tab_panels');
    do_action( 'woocommerce_after_single_product' );
?>
<script>
    jQuery(document).ready(function(e) {
        e(".woocommerce_tabs .panel").hide();
        e(".woocommerce_tabs ul.tabs li a").click(function() {
            var t = e(this), n = t.closest(".woocommerce_tabs");
            e("ul.tabs li", n).removeClass("active");
            e("div.panel", n).hide();
            e("div" + t.attr("href")).show();
            t.parent().addClass("active");
            return !1
        });
        e(".woocommerce_tabs").each(function() {
            var t = window.location.hash;
            t.toLowerCase().indexOf("comment-") >= 0 ? e("ul.tabs li.reviews_tab a", e(this)).click() : e("ul.tabs li:first a", e(this)).click()
        });
        e("#rating").hide().before('<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>');
        e("p.stars a").click(function() {
            var t = e(this);
            e("#rating").val(t.text());
            e("p.stars a").removeClass("active");
            t.addClass("active");
            return !1
        });
        e("#review_form #submit").live("click", function() {
            var t = e("#rating").val();
            if (e("#rating").size() > 0 && !t && woocommerce_params.review_rating_required == "yes") {
                alert(woocommerce_params.required_rating_text);
                return !1
            }
        });
		var location = window.history.location || window.location;
		// select tab according to url hash
		if(location.hash) {
			var t = e('a[href$="' + location.hash + '"]'), n = t.closest(".woocommerce_tabs");
			e("ul.tabs li", n).removeClass("active");
			e("div.panel", n).hide();
			e("div" + t.attr("href")).show();
			t.parent().addClass("active");
		};
		// reload page on #tabs-2 click
		/**e('a[href$="#tabs-2"]').click(function() {
			location.hash = '#tabs-2';
			location.reload();
		});**/

		/* Kussens color picker */
		for (var i = 1; i <= 25; i++) {
			var x_offset = (i-1)*40+3,
				style = 'style="background-position: -' + x_offset +'px 0;"',
				input = '<input id="color-input-' + i + '" type="radio" name="color" value="' + i + '" /></li>',
				color = '<label class="color" for="color-input-' + i + '" ' + style + '>' + input + '</label>';
			jQuery('li.kussens').append( color );
		}
		jQuery('input[name=color]').change(function(e) {
			jQuery('input[type="text"]', 'li.kussens').val(jQuery(this).val());
		});
		/* Kussens color picker - end */
	});
	// prevent page scroll to #tabs-2 anchor
	document.documentElement.onscroll = document.body.onscroll = function() {
		document.body.scrollTop = document.documentElement.scrollTop = 0;
		this.onscroll = null;
	};
</script>
<style>
	/* Kussens color picker */
	li.kussens div.ginput_container {
		display: none;
	}
	li.kussens label.color {
		background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/kussenkleuren.png);
		background-repeat: no-repeat;
		display: block;
		float: left;
		height: 90px;
		width: 36px;
		border: 1px solid #c0c0c0;
		box-sizing: border-box;
		margin: 0 2px 2px 0;
		padding: 3px;
	}
	li.kussens label:last-child{
		margin-bottom: 15px;
	}
	/* Kussens color picker - end */
</style>