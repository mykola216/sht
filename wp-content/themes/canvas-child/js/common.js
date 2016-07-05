(function ($) {
    $(function () {
        $('.widget_recent_reviews').on('click', '> h3', null, function (e) {
            $(this).parent().toggleClass('opened');
        });
    });
})(jQuery);