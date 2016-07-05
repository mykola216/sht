(function ($) {
    $(function () {
        $('.widget_recently_viewed_products').on('click', '> h3', null, function (e) {
            $(this).parent().toggleClass('opened');
        });
    });
})(jQuery);