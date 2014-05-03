displayLoginError = function(type, errortype) {

	if(type === 'error') {
	$("#errordiv").removeClass('alert-success').addClass('alert-error');
		if(errortype === 'name') {
			$('#errordiv').html('Incorrect username.');
		} else if (errortype === 'pass') {
			$('#errordiv').html('Incorrect password.');
		} else {
			$('#errordiv').html('Incorrect username or password.');
		}

		//$('#errordiv').fadeIn(1000);
		$('#errordiv').fadeTo(1000, 1);

	} else if (type === 'success') {
		$("#errordiv").removeClass('alert-error').addClass('alert-success');
		$('#errordiv').fadeTo(1000, 1);
	}

	setTimeout(function() {
		//$('#errordiv').fadeOut(1000);
		$('#errordiv').fadeTo(1000, 0);
	}, 5000);
	
	$("#errordiv").removeClass('alert-success').addClass('alert-error');
}