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
            show: 500,
            hide: 1000,
            content: function () {
                return jQuery(this).prop('title');
            }
        });
    }
})( jQuery );
