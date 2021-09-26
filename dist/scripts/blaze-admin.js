(function( $ ) {
	'use strict';
	
	$( window ).load(function() {

		$( "#blaze_btn_generate_csv" ).click(function() {

			fetch(blaze_ajax_object.ajax_url, {
				method: 'POST',
				credentials: 'same-origin',
				headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
				body: new URLSearchParams({
					action: 'blaze_generate_csv',
					_ajax_nonce: blaze_ajax_object.ajax_nonce
				})
			})
			.then(response => {
				console.log('response', response);
				// return response.json();
			})
			// .then(data => {
			// 	console.log('Success:', data);
			// })
			// .catch(error => {
			// 	console.error('Error:', error);
			// });

		});

	});
	 

})( jQuery );
