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

        /* Replace protocol in URL from https: to http: for thumbnails in widget Product Of The Day */
        $('.woocommerce_products_of_the_day').find('img.wp-post-image').each(function () {
            var src, srcset, newSrc, newSrcSet;
            src = $(this).attr('src');
            srcset = $(this).attr('srcset');

            if (src) {
                newSrc = src.replace('https://', 'http://');
                $(this).attr('src', newSrc);
            }
            if (srcset) {
                newSrcSet = srcset.replace('https://', 'http://');
                $(this).attr('srcset', newSrcSet);
            }
        });

        /* Single Product description and Category description */
        var $description = $('.description-wrapper');
        $description.on('click', '.more', function () {
            $(this).parent().find($(this).data('target')).toggleClass('opened').toggleClass('closed');

            $(this).toggleClass('read-more').toggleClass('hide-more');

            if ($(this).hasClass('read-more')) {
                $(this).text($(this).attr('data-label-read-more'));
            }

            if ($(this).hasClass('hide-more')) {
                $(this).text($(this).attr('data-label-hide-more'));
            }

            $(this).closest('body.single-product').find("#main").find(".yith-wfbt-section, .customer_also_viewed, .related.products").toggleClass("float");
        });
        $description.closest('body.single-product').find("#main").find(".yith-wfbt-section, .customer_also_viewed, .related.products").addClass("float");
        $description.find(".full-content.closed").each(function () {
            var offset = $(this).data('offset') || 100;
            var newHeight = +offset + $(this).children().first().height();
            $(this).css({
                'height': newHeight + 'px'
            });
        });


        /* Init OWL carousel home page. */
        $(".owl-carousel").owlCarousel({
            loop:true,
            nav:true,
            margin:0,
            autoWidth:true,
            // autoplay:true,
            // autoplayTimeout:3000,
            // autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1
                },
                768:{
                    items:3
                },
                1198:{
                    items:7
                }
            }
        });


        $(".searchform").on('click', 'input', function () {
           $(this).addClass('active');
        }).append('<input type="hidden" name="post_type" value="product">');
    });// DOM ready
})(jQuery);
(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.10";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));