
$(document).ready(function() {

	var oldValue = $('td.LEFT').eq(0).text();
	// var username = "htvcenter";
	// var password = "htvcenter";

	$('td.LEFT').quickEdit({
		blur: false,
		checkold: true,
		space: false,
		maxLength: 50,
		showbtn: false,
		submit: function (dom, newValue) {
			var thisip = document.domain;
			dom.text('updating...');
			var url = 'http://' + thisip +'/htvcenter/chunk.php?label='+newValue;
			// var url = 'http://' + thisip +':9425/chunk.cgi?label='+newValue;
			// var url = 'http://' + thisip +'/htvcenter/base/api.php?action=get_aws_vm_count';

			$.ajax({
				url: url,
				type: 'GET',
				/*
				crossDomain: true,
				dataType: 'json',
				async: false,
				username: username,
				password: password, 
				*/
			})
			.done(function( data ) {
				
				if ( console && console.log ) {
					console.log(data.slice(0, 100));
				}

				if (data.indexOf("failed") >= 0) {
					dom.text(oldValue);
				} else {
					dom.text(newValue);
				}
			})
			.fail(function() {
				dom.text(oldValue);
			});
		}
	});
});