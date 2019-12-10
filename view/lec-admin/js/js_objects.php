<script>

	$('.admin_table_item_check').on('click', function(){

		//count checbox's that are full.
		var count = $('.admin_table_item_check:checked').length;
		
		if (count > 0){
			$('.delete_button').fadeIn('fast');
			$('.duplicate_button').fadeIn('fast');
		} else {
			$('.delete_button').fadeOut('fast');
			$('.duplicate_button').fadeOut('fast');
		}

	});
	
	$('.delete_button').on('click', function(){

		test = confirm('Are you sure you want to delete selected?');

		if (test){
			$('#adminTable').append('<input type="hidden" name="delete" id="delete" value="YES" />');
			$('#adminTable').submit();
		}
		
	});

	$('.duplicate_button').on('click', function(){

		test = confirm('Are you sure you want to Duplicate selected?');

		if (test){
			$('#adminTable').append('<input type="hidden" name="duplicate" id="duplicate" value="YES" />');
			$('#adminTable').submit();
		}
		
	});
	
		
	//filters
	setFilters();
	
	//tags inputs
	$('.tags').tagsInput();
	
	$('.object_form_submit').on('click', function(){
		
		var error = 0;
		
		$('.mandatory').each(function(){
			
			var text = $(this).val();
			text = text.trim();
			
			$(this).removeClass('input-error');
			
			if(text == ''){
				$(this).addClass('input-error');
				error = 1;
				return false;
			}
			
		});
		
		if (error === 0){
			
			var form = $(this).attr('data-form');
			$('#'+form).submit();
			
		}
		
	});

</script>
