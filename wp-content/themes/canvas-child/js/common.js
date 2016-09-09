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
            }
            else if (!$(this).is('.wc-backward')) {
                $(this).wrapInner('<span/>');
            }
        });

        /* Replace protocol in URL from https: to http: for thumbnails in widget Product Of The Day*/
        $('.woocommerce_products_of_the_day').find('img.wp-post-image').each(function () {
            var newSrc, newSrcSet;
            newSrc = $(this).attr('src').replace('https://', 'http://');
            newSrcSet = $(this).attr('srcset').replace('https://', 'http://');
            $(this).attr('src', newSrc).attr('srcset', newSrcSet);
        });
    });// DOM ready
})(jQuery);