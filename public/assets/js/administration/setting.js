$(document).ready(function(){
	
	$('.submit-name').on('submit', function( event ){
		event.preventDefault();

		var Input = $('.input-setting', this);
		
		updateAdministration( Input );
	});

	$('.input-setting').on('change', function( event ){
		var Input = $(this);
		
		updateAdministration( Input );
	});
});

var updateLogo = (Input) =>
{
	var newValue  = $(Input).val();
	var lastValue = $(Input).attr('data-last-value');
	var nameInput = $(Input).attr("name");

	var formData = new FormData(); 

	formData.append("file", Input.files[0]);

	$.ajax({
		url: 'administration/uploadLogo ',
		type:'post',
		data: formData, 
		processData: false, 
  		contentType: false,
  		dataType:'json',
		success:function( response )
		{
			if(!response.status)
			{
				toastr.error(response.message, 'Error');
			}
			else
			{
				$("#"+nameInput)[0].src = URL.createObjectURL(Input.files[0]); 
				toastr.success(response.message, 'Success');	
			}
			
		}
	});
}

var updateAdministration = function( Input ){

	var newValue  = $(Input).val();
	var lastValue = $(Input).attr('data-last-value');
	var nameInput = $(Input).attr("name");
	
	if(newValue===lastValue)
	{
		return false;
	}

	$(Input).attr('data-last-value', newValue )

	$.ajax({
		url: '/administration/update',
		type:'post',
		data: {'name': nameInput, 'value' : newValue} ,
		dataType:'json',
		success:function( response ){
			if(!response.status)
			{
				toastr.error(response.message, 'Error');
			}
			else
			{
				toastr.success(response.message, 'Success');	
			}
			
		}
	});

	
}