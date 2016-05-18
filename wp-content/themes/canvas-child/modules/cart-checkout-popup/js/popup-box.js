(function( $ ) {
    'use strict';

    $(function() {
        $('#popup-box-send-email-cart-items-btn').click(function () {
            $("#popup-box-content-wrapper").show();
        });

        $('#popup-box-cross, #popup-box-send-email .cancel').click(function () {
            $("#popup-box-content-wrapper").hide();
        });

        $('a.send', '#popup-box-send-email').click(function(e) {
            var sendBtn = $(this);
            var sendingOverlay = $('#sending-overlay');
            sendBtn.addClass('sending');
            sendingOverlay.show();
            $.post(
                ajax_object.ajax_url,
                {
                    'action': 'send_me_cart_items',
                    'email': $('input', '#popup-box-send-email').val()
                },
                function(response) {
                    //console.log(response);
                    if(response.success) {
                        alert(response.data);
                        $('#popup-box-content-wrapper').hide();
                    } else {
                        alert(response.data);
                    }
                    sendBtn.removeClass('sending');
                    sendingOverlay.hide();
                }
            );
        });

    });

    $(document).mouseleave(function() {
        $(document).unbind('mouseleave');
        //if(undefined === Cookies.get('popup-box')) {
        //    Cookies.set('popup-box', true);
            //$('#popup-box-content-wrapper').show();
        //}
    });

    $(document).keyup(function(e) {
        if (e.keyCode == 27) {  // esc
            $("#popup-box-content-wrapper").hide();
        }
    });
})( jQuery );
