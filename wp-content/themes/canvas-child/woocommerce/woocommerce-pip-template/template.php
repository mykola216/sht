<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Print order details', 'woocommerce-pip'); ?></title>
	<link href="<?php echo woocommerce_pip_template('uri'); ?>css/woocommerce-pip-print.css" rel=" stylesheet" type="text/css" media="print" />
	<link href="<?php echo woocommerce_pip_template('uri'); ?>css/woocommerce-pip.css" rel=" stylesheet" type="text/css" media="screen,print" />
</head>
<body <?php echo woocommerce_pip_preview(); ?>>
	<?php $order = new WC_Order($_GET['post']); ?>
		<header>
          <a class="print" href="#" onclick="window.print()"><?php _e('Print', 'woocommerce-pip'); ?></a>
  		    <div style="float: left; width: 49%;">
  		      <?php echo woocommerce_pip_print_logo(); ?>
  		      <?php if ($_GET['type'] == 'invoice') { ?>
  		      <h3><?php _e('Invoice', 'woocommerce-pip'); ?> (<?php echo woocommerce_pip_invoice_number($_GET['post']); ?>)</h3>
  		      <?php } else { ?>
  		      <h3><?php _e('Packing list', 'woocommerce-pip'); ?></h3>
  		      <?php } ?>
  		      <h3><?php _e('Order', 'woocommerce-pip'); ?> #<?php echo $order->id; ?> &mdash; <time datetime="<?php echo date("Y/m/d", strtotime($order->order_date)); ?>"><?php echo date("Y/m/d", strtotime($order->order_date)); ?></time></h3>
  		    </div>
  		    <div style="float: right; width: 49%; text-align:right;">
  		      <?php echo woocommerce_pip_print_company_name(); ?>
  		      <?php echo woocommerce_pip_print_company_extra(); ?>
  		    </div>
  		    <div style="clear:both;"></div>

  	</header>
  	<section>
		<div class="article">
			<header>

      			<div style="float:left; width: 49%;">

      				<h3><?php _e('Billing address', 'woocommerce-pip'); ?></h3>

      				<p>
      					<?php echo $order->get_formatted_billing_address(); ?>
      				</p>
      				<?php if ($order->billing_email) : ?>
        				<p><strong><?php _e('Email:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_email; ?></p>
        			<?php endif; ?>
        			<?php if ($order->billing_phone) : ?>
        				<p><strong><?php _e('Tel:', 'woocommerce-pip'); ?></strong> <?php echo $order->billing_phone; ?></p>
        			<?php endif; ?>

      			</div>

      			<div style="float:right; width: 49%;">

      				<h3><?php _e('Shipping address', 'woocommerce-pip'); ?></h3>

      				<p>
      					<?php echo $order->get_formatted_shipping_address(); ?>
      				</p>

      			</div>

      			<div style="clear:both;"></div>
			</header>
			<div class="datagrid">
			<?php if ($_GET['type'] == 'invoice') { ?>
			<table>
				<thead>
					<tr>
					  <th scope="col" style="text-align:left; width: 15%;"><?php _e('SKU', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 40%;"><?php _e('Product', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 15%;"><?php _e('Quantity', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 30%;"><?php _e('Price', 'woocommerce-pip'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right; padding-top: 12px;"><?php _e('Subtotal:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left; padding-top: 12px;"><?php echo $order->get_subtotal_to_display(); ?></td>
					</tr>
					<tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right;"><?php _e('Shipping:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left;"><?php echo $order->get_shipping_to_display(); ?></td>
					</tr>
					<?php if ($order->order_discount > 0) : ?><tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right;"><?php _e('Discount:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left;"><?php echo woocommerce_price($order->order_discount); ?></td>
					</tr><?php endif; ?>
          <tr>
            <th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right;"><?php _e('Tax:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left;"><?php echo woocommerce_price($order->get_total_tax()); ?></td>
					</tr>
					<tr>
					  <th colspan="2" style="text-align:left; padding-top: 12px;">&nbsp;</th>
						<th scope="row" style="text-align:right;"><?php _e('Total:', 'woocommerce-pip'); ?></th>
						<td style="text-align:left;"><?php echo wc_price($order->order_total); ?></td>
					</tr>
				</tfoot>
				<tbody>
					<?php echo woocommerce_pip_order_items_table($order, TRUE); ?>
				</tbody>
			</table>
			<?php } 
			else { ?>
			<table>
				<thead>
					<tr>
					  <th scope="col" style="text-align:left; width: 25%;"><?php _e('SKU', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 60%;"><?php _e('Product', 'woocommerce-pip'); ?></th>
						<th scope="col" style="text-align:left; width: 15%;"><?php _e('Quantity', 'woocommerce-pip'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php echo woocommerce_pip_order_items_table($order); ?>
				</tbody>
			</table>
			<?php } ?>
			</div>
		</div>
	  <div class="article">
	    <?php echo woocommerce_pip_print_return_policy(); ?>
	  </div>
	</section>
	<div class="footer">
	  <?php echo woocommerce_pip_print_footer(); ?>
	</div>
</body>
</html>