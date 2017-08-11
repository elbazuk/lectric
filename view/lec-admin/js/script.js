function setFieldset(){
	var viewportHeight =  $(window).height();
	var wiewPortWidth = $(window).width(); 
	
	//fieldset
	$('.item_edit_fieldset').css('max-height', (viewportHeight-280)+'px');
}

$(window).resize(function(){
	setFieldset();
});

$(document).ready(function(){
	setFieldset();
});