
//filemanager stuff
	setFileManager();

	$('body').on('click', '.filemanager_button', function(){

		window.filemanager_field = $(this).attr('data-field');
		
		if(window.addEventListener){
			window.addEventListener('message', filemanager_onMessage, false);
		} else {
			window.attachEvent('onmessage', filemanager_onMessage);
		}
		
	});
