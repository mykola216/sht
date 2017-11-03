jQuery(document).ready(function ($) {
    /* Product summary tabs */
    var tabsID = ['#tab-1', '#tab-2'];
    var productTabSelector = '#product-summary-tabs';

    var toggleTabs = function (wrapTabSelector, tabID) {
        var wrapTabSelector = wrapTabSelector || productTabSelector;

        $(wrapTabSelector).find( 'ul > li.tab' ).removeClass( 'active' );
        $(wrapTabSelector).find( 'div.panel' ).hide();

        $(wrapTabSelector).find( 'a[href$="' + tabID + '"]' ).closest( 'li.tab' ).addClass( 'active' );
        $(tabID).show();
    };

    $( productTabSelector ).on( 'init', function() {
        var id = ( tabsID.indexOf(window.location.hash) != -1 ) ? window.location.hash : '#tab-1';

        toggleTabs(productTabSelector, id);

        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }).on( 'click', 'ul > li.tab a', function( e ) {
        e.preventDefault();
        var id = $(this).attr('href');
        var hash = ( tabsID.indexOf(id) != -1 ) ? id : '#tab-1';

        if (hash == window.location.hash) {
            return false;
        }

        if (window.location.hash || id != '#tab-1') {
            window.location.hash = hash;
        }

        toggleTabs(productTabSelector, id);

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

    /* Replace mailchimp checkbox */
    $('#auto_mailchimp_subscribe')
        .siblings('.gform_wrapper')
        .find('input[value="send_a_copy"]')
        .closest('li')
        .append($('#auto_mailchimp_subscribe'));

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
        hover: true,
        show: 400,
        hide: { delay: 1000, duration: 400 },
        content: function () {
            return jQuery(this).attr('title');
        }
    });

    /* Stylizing of Texture DropDown menu and Reformat price decimal */
    setTimeout(function () {
        customDecimalPart('.formattedTotalPrice');

        $( "select" ).on( "selectmenuopen", function( e, ui ) {
            var that = $(this);
            var selectedItemID = that.siblings('.ui-selectmenu-button').attr('aria-labelledby');

            if (parseInt(Localize_JS_Canvas_Child_Single.is_custom_gf_price_format))
                customDecimalPart('#' + that.attr('id') + '-menu' + ' li', ',');

            if (parseInt(Localize_JS_Canvas_Child_Single.is_animated_textural_menu)) {
                $('#' + that.attr('id') + '-menu.behandeling').addClass('animated');
            }
            if (parseInt(Localize_JS_Canvas_Child_Single.is_animated_textural_menu)) {
                $('#' + that.attr('id') + '-menu.behandeling-kleur').addClass('animated');
            }
            if (parseInt(Localize_JS_Canvas_Child_Single.is_animated_textural_menu)) {
                $('#' + that.attr('id') + '-menu.behandeling-nanolak').addClass('animated');
            }

            $('#' + that.attr('id') + '-menu.behandeling.animated')
                .closest('.ui-selectmenu-menu')
                .css({
                     'left' : that.closest('li').offset().left + 'px',
                    'width' : that.closest('li').width() + 'px',
                });
            $('#' + that.attr('id') + '-menu.behandeling-kleur.animated')
                .closest('.ui-selectmenu-menu')
                .css({
                     'left' : that.closest('li').offset().left + 'px',
                    'width' : that.closest('li').width() + 'px',
                });
            $('#' + that.attr('id') + '-menu.behandeling-nanolak.animated')
                .closest('.ui-selectmenu-menu')
                .css({
                     'left' : that.closest('li').offset().left + 'px',
                    'width' : that.closest('li').width() + 'px',
                });

            $('#' + that.attr('id') + '-menu')
                .children('li').each(function () {
                    if (!$(this).children('span.price-value').length)
                        $(this).wrapInner('<span class="price-value"></span>');
                })
                .filter('#' + selectedItemID).addClass('selectedItem');

        }).on('selectmenuclose', function (e, ui) {
            var that = $(this);
            $('#' + that.attr('id') + '-menu').children('li').removeClass('selectedItem');
            $('.formattedTotalPrice').removeClass('custom-formatted');
            setTimeout( function () { customDecimalPart('.formattedTotalPrice');}, 0);
        });

    }, 100);

    /* Reformat price decimal part if zero */
    var customDecimalPart = function (selector, decimal_sep) {
        // If dont need apply custom reformat
        if (!parseInt(Localize_JS_Canvas_Child_Single.is_custom_price_format)) return;

        // Custom symbol for zero decimal part
        var decimal_zero_symb = (typeof Localize_JS_Canvas_Child_Single.price_decimal_zero_symb !== 'undefined') ? Localize_JS_Canvas_Child_Single.price_decimal_zero_symb : '';

        $(selector).not('.custom-formatted').each(function () {
            var oldPrice = $(this).text();
            var newPrice = oldPrice;
            var woo_decimal_sep = (typeof wc_gravityforms_params !== 'undefined') ? wc_gravityforms_params.currency_format_decimal_sep : ',';
            var priceNumberParts = (typeof decimal_sep !== 'undefined') ? oldPrice.split(decimal_sep) : oldPrice.split(woo_decimal_sep);

            // If decimal part exist and only zero
            if ( priceNumberParts[1] !== undefined && !parseInt(priceNumberParts[1]) && $.trim(priceNumberParts[1]) != decimal_zero_symb ) {
                priceNumberParts[1] = priceNumberParts[1].replace(/0/g, '');
                priceNumberParts[1] = decimal_zero_symb + priceNumberParts[1];
                newPrice = priceNumberParts.join(woo_decimal_sep);
            }

            $(this).html(newPrice).addClass('custom-formatted');
        });
    };

});//DOM ready