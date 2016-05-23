jQuery(document).ready(function ($) {
    if ($('.coupon').length) {
        // Make 'Apply Coupon' button blinking if coupon code is not entered
        setInterval(function () {
            $('input[name="apply_coupon"]').delay(200).fadeOut('slow').fadeIn('');
        }, 1000);
        //$('.coupon').append('<br/><span>' + translation.coupon_applied + '</span><br/>');
        if ($('.woocommerce-message').length) {
            $('.coupon').append('<br/><span class="message">' + $('.woocommerce-message').text() + '</span><br/>');
        }
        if ($('.woocommerce-error').length) {
            $('.coupon').append('<br/><span class="error">' + $('.woocommerce-error').text() + '</span><br/>');
        }
    }
});