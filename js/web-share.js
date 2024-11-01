(function(){
	if(window.location.protocol != 'https:')
	{
		console.info('Sharekar Info: WebShare API only works on Secure connection')
		return;
	}
	
	window.addEventListener('load', function() {
		
		if(navigator.canshare)
		{
			console.log('This page or content is not shareable');
			return;
		}
		
		var share_data={};
		if (document.querySelector('meta[property="og:description"]') != null) {
			share_data.text = document.querySelector('meta[property="og:description"]').content;
		} else if (document.querySelector('meta[property="description"]') != null) {
			share_data.text = document.querySelector('meta[property="description"]').content;
		} else {
			share_data.text = document.title;
		}

		if (document.querySelector('meta[property="og:title"]') != null) {
			share_data.title = document.querySelector('meta[property="og:title"]').content;
		} else if (document.querySelector('meta[property="description"]') != null) {
			share_data.title = document.querySelector('meta[property="description"]').content;
		} else {
			share_data.title = document.title;
		}

		if (document.querySelector('meta[property="og:url"]') != null) {
			share_data.url = document.querySelector('meta[property="og:url"]').content;
		} else {
			share_data.url = window.location.href;
		}
		
		var sharekar_webshare = document.querySelector('.sharekar-webshare-wrapper');
		
		sharekar_webshare.addEventListener('click', function(){
			try {
				navigator.share(share_data);
			} catch (err) {
				console.log(`Error: ${err}`);
			}
		});
	});
	
})();
	