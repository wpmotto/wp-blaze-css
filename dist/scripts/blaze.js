'use strict';

document.addEventListener("DOMContentLoaded",function(){

	const log = Array.from(
		document.querySelectorAll('body, body :not(script)')
	).map((el) => {
		return {
			tag: el.tagName,
			id: el.id,
			class: el.classList,
			coordinates: el.getBoundingClientRect()
		}
	});

	fetch(blaze_ajax_object.ajax_url, {
		method: 'POST',
		credentials: 'same-origin',
		headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
		body: new URLSearchParams({
			action: 'blaze_ajax',
			_ajax_nonce: blaze_ajax_object.ajax_nonce,
			url: window.location.href,
			width: window.innerWidth,
			height: window.innerHeight,
			log: JSON.stringify(log),
		})
	}).then(response => console.log(response.json()));
});
