<script>

	$('.test_do').on('click', function(){ 
		
		$.ajax({
			cache: false,
			type: 'POST',
			url: '/do/response/lectric/test',
			data: 'do=it',
			success: function (response){
				$('.response').html(response);
			},
			error: function(){
				alert('Failed to reach do script');
			}
		});
		
	});

</script>
