jQuery(document).ready(function ($) {
    if( ! $('tr.cart-discount').length ) {
        // Make 'Apply Coupon' button blinking if coupon code is not entered
        setInterval(function () {
            $('input[name="apply_coupon"]').delay(200).fadeOut('slow').fadeIn('');
        }, 1000);
    } else {
        $('.coupon').html('<span>' + translation.coupon_applied + '</span>');
    }
});