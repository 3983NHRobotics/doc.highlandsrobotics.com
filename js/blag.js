displayLoginError = function(type, errormessage) {

	if(type === 'error') {
	$("#errordiv").removeClass('alert-success').addClass('alert-error');
		
			$('#errordiv').html(errormessage + '.');

		//$('#errordiv').fadeIn(1000);
		$('#errordiv').fadeTo(600, 1);

	} else if (type === 'success') {
		$("#errordiv").removeClass('alert-error').addClass('alert-success');
		$('#errordiv').fadeTo(600, 1);
	}

	setTimeout(function() {
		//$('#errordiv').fadeOut(1000);
		$('#errordiv').fadeTo(1000, 0);
	}, 5000);
	
	$("#errordiv").removeClass('alert-success').addClass('alert-error');
}

updateForm = function(title, content) {
	$("input#title").val(title);
	$("textarea#content").html(content);
}