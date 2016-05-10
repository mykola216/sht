<?php defined('ABSPATH') || die(); ?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php _e('Customer invoice', 'woocommerce'); ?></title>
</head>
<body style="background: white; color: black; width: 95%; margin: 0 auto; font: normal 14px/150% Verdana, Arial, Helvetica, sans-serif; -webkit-print-color-adjust: exact;">
	<header>
		<div style="float: left; width: 49%;">
			<?php echo woocommerce_pip_print_logo(); ?>
			<h3><?php _e('Offerte', 'woocommerce-pip'); ?> (<?php echo woocommerce_pip_invoice_number( $order->id ); ?>)</h3>
			<h3><?php _e('Order', 'woocommerce-pip'); ?> #<?php echo $order->id; ?> &mdash; <time datetime="<?php echo date("Y/m/d", strtotime($order->order_date)); ?>"><?php echo date("Y/m/d", strtotime($order->order_date)); ?></time></h3>
		</div>
		<div style="float: right; width: 49%; text-align: right;">
			<?php echo woocommerce_pip_print_company_name(); ?>
			<?php echo woocommerce_pip_print_company_extra(); ?>
		</div>
		<div style="clear: both;"></div>
	</header>
	<section>
		<div class="article" style="padding: 20px 0;">
			<header>
				<div style="float: left; width: 49%;">
					<h3><?php _e('Billing address', 'woocommerce-pip'); ?></h3>
					<p><?php echo $order->get_formatted_billing_address(); ?></p>
					<?php if ( $order->billing_email ) : ?>
						<p><strong><?php _e('Email:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_email; ?></p>
					<?php endif; ?>
					<?php if ($order->billing_phone) : ?>
						<p><strong><?php _e('Tel:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_phone; ?></p>
					<?php endif; ?>
				</div>
				<div style="float: right; width: 49%;">
					<h3><?php _e('Shipping address', 'woocommerce-pip'); ?></h3>
					<p><?php echo $order->get_formatted_shipping_address(); ?></p>
				</div>
				<div style="clear: both;"></div>
			</header>
			<div class="datagrid" style="font: normal 14px/150% Verdana, Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #FFFFFF; margin-top: 15px; -webkit-print-color-adjust: exact;">
				<table style="border-collapse: collapse; text-align: left; width: 100%;">
					<thead>
						<tr>
							<th scope="col" style="text-align: left; width: 15%; padding: 3px 4px; background-color: #576099; color: #FFFFFF; font-size: 14px; font-weight: bold; border-left: 2px solid #FFFFFF; -webkit-print-color-adjust: exact;"><?php _e('SKU', 'woocommerce-pip'); ?></th>
							<th scope="col" style="text-align: left; width: 40%; padding: 3px 4px; background-color: #576099; color: #FFFFFF; font-size: 14px; font-weight: bold; border-left: 2px solid #FFFFFF; -webkit-print-color-adjust: exact;"><?php _e('Product', 'woocommerce-pip'); ?></th>
							<th scope="col" style="text-align: left; width: 15%; padding: 3px 4px; background-color: #576099; color: #FFFFFF; font-size: 14px; font-weight: bold; border-left: 2px solid #FFFFFF; -webkit-print-color-adjust: exact;"><?php _e('Quantity', 'woocommerce-pip'); ?></th>
							<th scope="col" style="text-align: left; width: 30%; padding: 3px 4px; background-color: #576099; color: #FFFFFF; font-size: 14px; font-weight: bold; border-left: 2px solid #FFFFFF; -webkit-print-color-adjust: exact;"><?php _e('Price', 'woocommerce-pip'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo woocommerce_pip_order_items_table($order, TRUE); ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2" style="text-align: left; padding-top: 12px; padding: 3px 4px;"> </th>
							<th scope="row" style="text-align: right; padding-top: 12px; padding: 3px 4px;"><?php _e('Subtotal:', 'woocommerce-pip'); ?></th>
							<td style="text-align: left; padding-top: 12px; padding: 3px 4px; color: #000000; border: 2px solid #FFFFFF; font-size: 14px; font-weight: normal; background-color: #F5F5F5; -webkit-print-color-adjust: exact;"><?php echo $order->get_subtotal_to_display(); ?></td>
						</tr>
						<tr>
							<th colspan="2" style="text-align: left; padding-top: 12px; padding: 3px 4px;"> </th>
							<th scope="row" style="text-align: right; padding: 3px 4px;"><?php _e('Shipping:', 'woocommerce-pip'); ?></th>
							<td style="text-align: left; padding: 3px 4px; color: #000000; border: 2px solid #FFFFFF; font-size: 14px; font-weight: normal; background-color: #F5F5F5; -webkit-print-color-adjust: exact;"><?php echo $order->get_shipping_to_display(); ?></td>
						</tr>
						<?php if ($order->order_discount > 0) : ?>
							<tr>
								<th colspan="2" style="text-align: left; padding-top: 12px; padding: 3px 4px;"> </th>
								<th scope="row" style="text-align: right; padding: 3px 4px;"><?php _e('Discount:', 'woocommerce-pip'); ?></th>
								<td style="text-align: left; padding: 3px 4px; color: #000000; border: 2px solid #FFFFFF; font-size: 14px; font-weight: normal; background-color: #F5F5F5; -webkit-print-color-adjust: exact;"><?php echo wc_price( $order->order_discount ); ?></td>
							</tr>
						<?php endif; ?>
						<tr>
							<th colspan="2" style="text-align: left; padding-top: 12px; padding: 3px 4px;"> </th>
							<th scope="row" style="text-align: right; padding: 3px 4px;"><?php _e('Tax:', 'woocommerce-pip'); ?></th>
							<td style="text-align: left; padding: 3px 4px; color: #000000; border: 2px solid #FFFFFF; font-size: 14px; font-weight: normal; background-color: #F5F5F5; -webkit-print-color-adjust: exact;"><?php echo wc_price( $order->get_total_tax() ); ?></td>
						</tr>
						<tr>
							<th colspan="2" style="text-align: left; padding-top: 12px; padding: 3px 4px;"> </th>
							<th scope="row" style="text-align: right; padding: 3px 4px;"><?php _e('Total:', 'woocommerce-pip'); ?></th>
							<td style="text-align: left; padding: 3px 4px; color: #000000; border: 2px solid #FFFFFF; font-size: 14px; font-weight: normal; background-color: #F5F5F5; -webkit-print-color-adjust: exact;"><?php echo wc_price($order->order_total); ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="article" style="padding: 20px 0;">
			<?php echo woocommerce_pip_print_return_policy(); ?>
		</div>
	</section>
	<div class="footer" style="border-top: 1px solid #eee; margin-top: 10px; padding-top: 7px; text-align: center; font: normal 13px/150% Verdana, Arial, Helvetica, sans-serif; -webkit-print-color-adjust: exact;">
		<?php echo woocommerce_pip_print_footer(); ?>
	</div>
</body>
</html>
