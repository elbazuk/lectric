//filters!
		
		$('body').on('keyup change click','.filter_alphanumeric', function (){
			var text = $(this).val();
			var patt = new RegExp(/[0-9a-zA-Z]/);
			
			var caretPos = $(this).caret();
			var lastChar = text.substr((caretPos-1), 1);
			if (patt.test(lastChar)){
				//do nothing
			} else {
				//remove last character
				var newText = text.slice(0, (caretPos-1)) + text.slice(caretPos, text.length);
				$(this).val(newText);
				//put caret back to where it was
				$(this).caret(caretPos-1);
			}
		});
	
		 $('body').on('keyup change click','.filter_username', function (){
			var text = $(this).val();
			var patt = new RegExp(/[0-9a-zA-Z_\-]/);
			
			var caretPos = $(this).caret();
			var lastChar = text.substr((caretPos-1), 1);
			if (patt.test(lastChar)){
				//do nothing
			} else {
				//remove last character
				var newText = text.slice(0, (caretPos-1)) + text.slice(caretPos, text.length);
				$(this).val(newText);
				//put caret back to where it was
				$(this).caret(caretPos-1);
			}
		});
		
		 $('body').on('keyup change click', '.filter_email', function (){
			var text = $(this).val();
			var patt = new RegExp(/[0-9a-zA-Z_.\-@]/);
			
			var caretPos = $(this).caret();
			var lastChar = text.substr((caretPos-1), 1);
			if (patt.test(lastChar)){
				//do nothing
			} else {
				//remove last character
				var newText = text.slice(0, (caretPos-1)) + text.slice(caretPos, text.length);
				$(this).val(newText);
				//put caret back to where it was
				$(this).caret(caretPos-1);
			}
		});
		
		$('body').on('keyup change click', '.filter_name', function (){
			var text = $(this).val();
			var patt = new RegExp(/[0-9a-zA-Z_.\- ]/);
			
			var caretPos = $(this).caret();
			var lastChar = text.substr((caretPos-1), 1);
			if (patt.test(lastChar)){
				//do nothing
			} else {
				//remove last character
				var newText = text.slice(0, (caretPos-1)) + text.slice(caretPos, text.length);
				$(this).val(newText);
				//put caret back to where it was
				$(this).caret(caretPos-1);
			}
		});
		
		$('body').on('keyup change click', '.filter_number', function (){
			var text = $(this).val();
			var patt = new RegExp(/[0-9]/);
			
			var caretPos = $(this).caret();
			var lastChar = text.substr((caretPos-1), 1);
			if (patt.test(lastChar)){
				//do nothing
			} else {
				//remove last character
				var newText = text.slice(0, (caretPos-1)) + text.slice(caretPos, text.length);
				$(this).val(newText);
				//put caret back to where it was
				$(this).caret(caretPos-1);
			}
		});
		
		$( '.tooltip' ).tooltip({
			content: function () {
			  return $(this).prop('title');
			},
			close: function (event, ui) {
			$('div.ui-effects-wrapper').remove();  // Add a close function to remove the wrapper.
			}
		});
