(function( $ ) {
	'use strict';

	$(function(){
		$(document).on('click', 'input.plus', null, function() {
			var item = $(this).closest(".quantity").find(".qty"),
				step = Number(item.attr('step')) || 1,
				max_value = Number(item.attr('max')),
				value = Number(item.val()) + step;
			if(max_value === max_value && max_value < value) {
				value = max_value;
			}
			item.trigger('change').val(value);
		});

		$(document).on('click', 'input.minus', null, function() {
			var item = $(this).closest(".quantity").find(".qty"),
				step = Number(item.attr('step')) || 1,
				min_value = Number(item.attr('min')),
				value = Number(item.val()) - step;
			if(min_value === min_value && min_value > value) {
				value = min_value;
			}
			item.trigger('change').val(value);
		});
	});

})( jQuery );
