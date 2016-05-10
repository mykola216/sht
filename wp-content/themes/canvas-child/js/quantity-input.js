(function( $ ) {
    'use strict';

	$(function(){
		$("input.plus").click(function() {
			var item = $(this).closest(".quantity").find(".qty"),
				step = Number(item.attr('step')) || 1,
				max_value = Number(item.attr('max')),
				value = Number(item.val()) + step;
			if(max_value === max_value && max_value < value) {
				value = max_value;
			}
			item.val(value); 
		});
		$("input.minus").click(function() {
			var item = $(this).closest(".quantity").find(".qty"),
				step = Number(item.attr('step')) || 1,
				min_value = Number(item.attr('min')),
				value = Number(item.val()) - step;
			if(min_value === min_value && min_value > value) {
				value = min_value;
			}
			item.val(value); 
		});
	});
})( jQuery );
