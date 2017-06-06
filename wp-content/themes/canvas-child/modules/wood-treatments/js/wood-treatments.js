(function( $ ) {
    'use strict';

    $(function() {
        $('li.gfield:not(.behandeling) select').selectmenu();
        $('li.gfield.behandeling select').selectmenu().selectmenu('menuWidget').addClass('behandeling');
        $('li.gfield select').on('selectmenuchange', function(event, ui) {
            var id = '#' + ui.item.element[0].parentElement.id;
            $(id).val(ui.item.value).change();
            $(id).next().find('span.ui-selectmenu-text').text(ui.item.element[0].label);
        } );
    });
    $(function() {
        $('li.gfield:not(.behandeling-kleur) select').selectmenu();
        $('li.gfield.behandeling-kleur select').selectmenu().selectmenu('menuWidget').addClass('behandeling-kleur');
        $('li.gfield select').on('selectmenuchange', function(event, ui) {
            var id = '#' + ui.item.element[0].parentElement.id;
            $(id).val(ui.item.value).change();
            $(id).next().find('span.ui-selectmenu-text').text(ui.item.element[0].label);
        } );
    });
    $(function() {
        $('li.gfield:not(.behandeling-nanolak) select').selectmenu();
        $('li.gfield.behandeling-nanolak select').selectmenu().selectmenu('menuWidget').addClass('behandeling-nanolak');
        $('li.gfield select').on('selectmenuchange', function(event, ui) {
            var id = '#' + ui.item.element[0].parentElement.id;
            $(id).val(ui.item.value).change();
            $(id).next().find('span.ui-selectmenu-text').text(ui.item.element[0].label);
        } );
    });
})( jQuery );
