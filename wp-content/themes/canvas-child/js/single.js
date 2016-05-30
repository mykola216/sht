jQuery(document).ready(function ($) {
    /* Product summary tabs */
    $( '#product-summary-tabs' ).on( 'init', function() {
        var id = ( '#tab-2' === window.location.hash ? '#tab-2' : '#tab-1' );

        $( 'ul > li.tab', this ).removeClass( 'active' );
        $( 'div.panel', this ).hide();

        $( 'a[href$="' + id + '"]', this ).closest( 'li.tab' ).addClass( 'active' );
        $( id ).show();

        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }).on( 'click', 'ul > li.tab a', function( e ) {
        e.preventDefault();
        var hash = ( '#tab-2' === $(this).attr('href') ? '#tab-2' : '' );
        if (hash === window.location.hash) {
            return false;
        } else if ( '#tab-2' === hash ) {
            window.location.hash = hash;
            window.location.reload();
        } else {
            window.location.href = window.location.origin + window.location.pathname;
        }
    }).trigger( 'init' );
    /* Product summary tabs - end */

    /* Kussens color picker */
    for (var i = 1; i <= 25; i++) {
        var x_offset = (i - 1) * 40 + 3,
            style = 'style="background-position: -' + x_offset + 'px 0;"',
            input = '<input id="color-input-' + i + '" type="radio" name="color" value="' + i + '" /></li>',
            color = '<label class="color" for="color-input-' + i + '" ' + style + '>' + input + '</label>';
        $('li.kussens').append(color);
    }
    $('input[name=color]').change(function (e) {
        $('input[type="text"]', 'li.kussens').val($(this).val());
    });
    /* Kussens color picker - end */

    /* Request for a custom product was sent successfully */
    jQuery(document).on( 'gform_confirmation_loaded', function( e, id ) {
        if(4===id) {
            $('#gforms_confirmation_message_4').siblings('h2').hide();
            $('#gforms_confirmation_message_4').siblings('p').hide();

            ga('send', {
                hitType: 'event',
                eventCategory: 'product',
                eventAction: 'quotation',
                eventLabel: 'success'
            });
        }
    });
    /* Request for a custom product was sent successfully - end */

    jQuery(".gfield_description").each(function () {
        jQuery(this).hide()
            .siblings('label')
            .append('<sup class="canvas-child-tooltip" title="%s">i</sup>')
            .children('.canvas-child-tooltip')
            .attr('title', jQuery(this).html());
    });
    jQuery(".canvas-child-tooltip").tooltip({
        show: 500,
        hide: 1000,
        content: function () {
            return jQuery(this).attr('title');
        }
    });

    /* Single product description */
    var $productDescription = $('#product-description');
    $productDescription.on('click', '.more', function () {
        $(this).siblings('.full-content').toggleClass('opened').toggleClass('closed');

        $(this).toggleClass('read-more').toggleClass('hide-more');
        if ($(this).hasClass('read-more')) {
            $(this).text($(this).attr('data-label-read-more'));
        }
        if ($(this).hasClass('hide-more')) {
            $(this).text($(this).attr('data-label-hide-more'));
        }

        $(this).closest("#main").find(".yith-wfbt-section, .customer_also_viewed, .related.products").toggleClass("float");
    });
    $productDescription.closest("#main").find(".yith-wfbt-section, .customer_also_viewed, .related.products").addClass("float");
    $productDescription.find(".full-content.closed").each(function () {
        var newHieght = 100 + $(this).children().first().height();
        $(this).css({
            'height': newHieght + 'px'
        });
    });



});//DOM ready