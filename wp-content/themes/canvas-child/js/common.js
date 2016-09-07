(function ($) {
    $(function () {
        /* Widget h3 click handle*/
        $('.widget_recently_viewed_products').on('click', '> h3', null, function (e) {
            $(this).parent().toggleClass('opened');
        });

        /* Capitalize first-letter for ".button" */
        $('.button').each(function () {
            if ($(this).is('input')) {
                var oldVal = $(this).val();
                var newVal = oldVal.slice(0,1).toUpperCase() + oldVal.slice(1).toLowerCase();
                $(this).val(newVal);
                console.log('newVal', newVal);
            }
            else if (!$(this).is('.wc-backward')) {
                $(this).wrapInner('<span/>');
            }
        });
    });// DOM ready
})(jQuery);