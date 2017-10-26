<?php
if ( !$email->type ):
?>
<div id="fue-email-variables-notice">
    <p class="meta-box-notice"><?php _e('Please set the email type first', 'follow_up_emails'); ?></p>
</div>
<?php else: ?>
<ul id="fue-email-variables-list">
	<p><span style="color:#7ad03a;" class="dashicons dashicons-warning"></span> Please <a href="http://docs.woothemes.com/document/automated-follow-up-emails-docs/email-variables-and-merge-tags/" target="_blank">review the documentation</a> for an exhaustive list of variables.</p>
    <?php do_action('fue_email_variables_list', $email); ?>
    <li class="var hideable var_web_version_url"><strong>{webversion_url}</strong> <img class="help_tip" title="<?php _e('The URL to the web version of the email.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_web_version_link"><strong>{webversion_link}</strong> <img class="help_tip" title="<?php _e('Renders a <em>View in browser</em> link that points to the web version of the email.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_customer_username"><strong>{customer_username}</strong> <img class="help_tip" title="<?php _e('The username of the customer who purchased from your store.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_customer_first_name"><strong>{customer_first_name}</strong> <img class="help_tip" title="<?php _e('The first name of the customer who purchased from your store.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_customer_name"><strong>{customer_name}</strong> <img class="help_tip" title="<?php _e('The full name of the customer who purchased from your store.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_customer_email"><strong>{customer_email}</strong> <img class="help_tip" title="<?php _e('The email address of the customer who purchased from your store.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_store_url"><strong>{store_url}</strong> <img class="help_tip" title="<?php _e('The URL/Address of your store.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_store_url_secure"><strong>{store_url_secure}</strong> <img class="help_tip" title="<?php _e('The secure URL/Address of your store (HTTPS).', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_store_url_path"><strong>{store_url=path}</strong> <img class="help_tip" title="<?php _e('The URL/Address of your store with path added at the end. Ex. {store_url=/categories}', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_store_name"><strong>{store_name}</strong> <img class="help_tip" title="<?php _e('The name of your store.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_cart"><strong>{cart_contents}</strong> <img class="help_tip" title="<?php _e('The cart items displayed in a table.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_cart"><strong>{cart_total}</strong> <img class="help_tip" title="<?php _e('The total amount of all items in the cart', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_cart"><strong>{cart_url}</strong> <img class="help_tip" title="<?php _e('The URL of the cart page.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_coupon var_interval_coupon"><strong>{coupon_code}</strong> <img class="help_tip" title="<?php _e('Coupon code generated/used', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_coupon var_interval_coupon"><strong>{coupon_code_used}</strong> <img class="help_tip" title="<?php _e('Coupon code used', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_coupon var_interval_coupon"><strong>{coupon_amount}</strong> <img class="help_tip" title="<?php _e('Coupon code discount amount', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_number non-signup"><strong>{order_number}</strong> <img class="help_tip" title="<?php _e('The generated Order Number for the puchase', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_date non-signup"><strong>{order_date}</strong> <img class="help_tip" title="<?php _e('The date that the order was made', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_datetime non-signup"><strong>{order_datetime}</strong> <img class="help_tip" title="<?php _e('The date and time that the order was made', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_subtotal non-signup"><strong>{order_subtotal}</strong> <img class="help_tip" title="<?php _e('The subtotal of the order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_tax non-signup"><strong>{order_tax}</strong> <img class="help_tip" title="<?php _e('The tax total of the order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_pay_method non-signup"><strong>{order_pay_method}</strong> <img class="help_tip" title="<?php _e('The payment method used to pay for the order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_pay_url non-signup"><strong>{order_pay_url}</strong> <img class="help_tip" title="<?php _e('URL for customer to pay their (unpaid - pending) order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_billing_address non-signup"><strong>{order_billing_address}</strong> <img class="help_tip" title="<?php _e('The billing address of the order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_shipping_address non-signup"><strong>{order_shipping_address}</strong> <img class="help_tip" title="<?php _e('The shipping address of the order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_billing_phone non-signup"><strong>{order_billing_phone}</strong> <img class="help_tip" title="<?php _e('The billing phone of the order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_order var_order_shipping_phone non-signup"><strong>{order_shipping_phone}</strong> <img class="help_tip" title="<?php _e('The shipping phone of the order', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_unsubscribe_url"><strong>{unsubscribe_url}</strong> <img class="help_tip" title="<?php _e('URL where users will be able to opt-out of the email list.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
    <li class="var hideable var_post_id"><strong>{post_id=xx}</strong> <img class="help_tip" title="<?php _e('Include the excerpt of the specified Post ID.', 'follow_up_emails'); ?>" src="<?php echo FUE_TEMPLATES_URL; ?>/images/help.png" width="16" height="16" /></li>
</ul>
<?php endif; ?>
