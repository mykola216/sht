<?php
/**
 * Product description
 *
 * @author   Aleksandr Levashov
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $st_options;

?>

    </div>
    <div id="tab-2" class="panel">
        <h2><?php _e( 'Can\'t find the size what you need?', 'woothemes' ); ?></h2>
        <p><?php _e( 'This is not a problem. At Steigerhouttrend we can produse any furnuture according your request. We can easily adjust any model or size.', 'woothemes' ); ?></p>
        <p><?php _e( 'Describe in the form below how the furniture should look like, within one day we will send a no-obligation quote.', 'woothemes' ); ?></p>
        <?php
            gravity_form(4, $display_title=false, $display_description=false, $display_inactive=false, $field_values=null, $ajax=true);
        ?>
    </div>
</div>

<div id="info-block">
    <div id="info-block-trustpilot">
        <?php echo $st_options['info_block_text1']; ?>
    </div>
    <div id="info-block-delivery">
        <?php echo $st_options['info_block_text2']; ?>
    </div>
    <div id="info-block-customer-service">
        <?php echo $st_options['info_block_text3']; ?>
    </div>
    <div id="info-block-order-check">
        <?php echo $st_options['info_block_text4']; ?>
    </div>
</div>

<?php
    wc_get_template_part( 'templates/module', 'sharethis' );
    wc_get_template_part( 'templates/module', 'facebook-comments' );