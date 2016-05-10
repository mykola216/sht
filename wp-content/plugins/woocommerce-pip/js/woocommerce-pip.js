jQuery(document).ready(function($) {
  $('.pip-link').click(function (event){
 
    var url = $(this).attr("href");
    if ($.browser.webkit) {
      window.open(url, "Print", "width=800, height=600");
    }
    else {
      window.open(url, "Print", "scrollbars=1, width=800, height=600");
    }
    
    event.preventDefault();
 
    return false;
 
  });
  
  $('#woocommerce_pip_reset_start').live('click', function(){
    $('#woocommerce_pip_invoice_start').attr('readonly', 'readonly');
		if ($(this).is(':checked')) {
			$('#woocommerce_pip_invoice_start').removeAttr('readonly');
		}
	});
  
  $('#pip_settings').validate({
    rules: {
      woocommerce_pip_invoice_start: {
        min: 1,
        digits: true
      }
    }
  });
});