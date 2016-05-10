(function( $ ) {
    'use strict';

    $(function() {
        $('#popup-box-cross').click(function () {
            $("#popup-box-content-wrapper").hide();
        });

        $('a', '#popup-box-send-email').click(function(e) {
            $.post(
                ajax_object.ajax_url,
                {
                    'action': 'send_me_cart_items',
                    'email': $('input', '#popup-box-send-email').val()
                },
                function(response) {
                    if(response.success) {
                        $('#popup-box-send-email').hide();
                    } else {
                        alert(response.data);
                    }
                }
            );
        });
    });

    $(document).mouseleave(function() {
        $(document).unbind('mouseleave');
        //if(undefined === Cookies.get('popup-box')) {
        //    Cookies.set('popup-box', true);
            $('#popup-box-content-wrapper').show();
        //}
    });

    $(document).keyup(function(e) {
        if (e.keyCode == 27) {  // esc
            $("#popup-box-content-wrapper").hide();
        }
    });
})( jQuery );
