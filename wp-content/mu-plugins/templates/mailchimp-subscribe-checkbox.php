<?php $mailchimp_woocommerce_options = get_option('mailchimp-woocommerce'); ?>
<p class="form-row form-row-wide checkbox-custom" id="new-subscribe">
	<input class="input-checkbox" id="info-checkbox" type="checkbox" name="mailchimp_woocommerce_newsletter" value="1" checked>
	<label class="checkbox" for="info-checkbox"><?php echo ($mailchimp_woocommerce_options['newsletter_label']) ? $mailchimp_woocommerce_options['newsletter_label'] : __('Ik wil op de hoogte zijn van aanbiedingen'); ?></label>
</p>