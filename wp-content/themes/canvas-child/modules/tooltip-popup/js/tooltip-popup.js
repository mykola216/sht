(function( $ ) {
    'use strict';

    $(function($) {
        canvas_child_tooltip();

        $(document.body).on('updated_checkout', function() {
            canvas_child_tooltip();
        }).on('updated_shipping_method', function() {
            canvas_child_tooltip();
        });
    });

    function canvas_child_tooltip() {
        $(".canvas-child-tooltip").tooltip({
            show: 400,
            hide: { delay: 3000, duration: 400 },
            content: function () {
                return $(this).attr('title');
            }
        });
    }
})( jQuery );
