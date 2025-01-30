$(function(){


	$('.run-migration').on('click', function(){

		var autoRun = $("#autorun").is(':checked');

		if( !$("#clinic").val()  )
		{
			alert("Clinic Choose is requried");
			return false;
		}

		$("#clinic").attr("disabled", true);

		var key_code = $('#key_code').val(),
			btn 	 = this;

		var clinic   = $("#clinic").val();
		var url 	 = $(btn).data('url') + '/' + key_code + "?clinic="+$("#clinic").val();
		var name 	 = $(btn).data('completed');
		var title 	 = $(btn).text();
		

		$(btn)
			.html('Loading....')
			.attr('disabled','disabled');

		var nextID = $(btn).data('next') || '';
		
		$('#loading').show();
		
		$('#loading h3').html( title );
		
		$.get( url , function( response ){
			if(response.status === 1)
			{	
				$('#key_code').val(response.key_code);
				$('#log').append("\n....... "+title+" .......\n" + response.log);
				$(btn).html( name );
				$('#' + nextID).removeAttr('disabled');
				
				if(autoRun)
				{
					if(nextID)
					{	
						$('#' + nextID).trigger('click');
					}
					else
					{
						$('#loading').fadeOut('fast');
					}
				}
				else
				{
					$('#loading').fadeOut('fast');
				}
				
			}
			else
			{	
				$('#log').parent().append("<div class='alert alert-danger'>'"+response.message+"'</div>")
				Notify.error( title +" \n" + response.message);
				$('#loading').fadeOut('fast');
			}
		}, 'JSON');

	})


});