<?php defined( 'ABSPATH' ) || die(); // Exit if accessed directly
?>
<p><?php printf( __( 'You have received an order from %s. Payment by Ideal is not finished! The order is as follows:', 'w3cm-pending-order-email' ), $order->get_formatted_billing_full_name() ); ?></p>

<h2><a class="link" href="<?php echo admin_url( 'post.php?post=' . $order->id . '&action=edit' ); ?>"><?php printf( __( 'Order #%s', 'woocommerce'), $order->get_order_number() ); ?></a> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
    <thead>
        <tr>
            <th class="td" scope="col" style="text-align:left;"><?php _e( 'Product', 'woocommerce' ); ?></th>
            <th class="td" scope="col" style="text-align:left;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
            <th class="td" scope="col" style="text-align:left;"><?php _e( 'Price', 'woocommerce' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php echo $order->email_order_items_table( false, true ); ?>
    </tbody>
    <tfoot>
        <?php
            if ( $totals = $order->get_order_item_totals() ) {
                $i = 0;
                foreach ( $totals as $total ) {
                    $i++;
                    ?><tr>
                        <th class="td" scope="col" colspan="2" style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
                        <td class="td" scope="col" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
                    </tr><?php
                }
            }
        ?>
    </tfoot>
</table>

<?php do_action( 'canvas_child_email_order_meta', $order ); ?>
<?php if ( ! empty( $order->customer_note ) ) : ?>
    <p><strong><?php echo __( 'Note', 'woocommerce' ); ?>: </strong><?php echo wptexturize( $order->customer_note ); ?></p>
<?php endif; ?>

<?php do_action( 'canvas_child_email_customer_details', $order ); ?>
<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
<p><strong><?php _e( 'Email', 'woocommerce' ); ?>:</strong> <span class="text"><?php echo wptexturize( $order->billing_email ); ?></span></p>
<p><strong><?php _e( 'Tel', 'woocommerce' ); ?>:</strong> <span class="text"><?php echo wptexturize( $order->billing_phone ); ?></span></p>

<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0">
    <tr>
        <td class="td" style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">
            <h3><?php _e( 'Billing address', 'woocommerce' ); ?></h3>
            <p class="text"><?php echo $order->get_formatted_billing_address(); ?></p>
        </td>

        <?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>
            <td class="td" style="text-align:left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" valign="top" width="50%">
                <h3><?php _e( 'Shipping address', 'woocommerce' ); ?></h3>
                <p class="text"><?php echo $shipping; ?></p>
            </td>
        <?php endif; ?>
    </tr>
</table>