
toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": true,
    "preventDuplicates": true,
    "positionClass": "toast-top-center",
    "onclick": null,
    "showDuration": "400",
    "hideDuration": "1000",
    "timeOut": "7000",
    "extendedTimeOut": "3000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
}

var Notify = {
	response  : function( response )
	{	
		var status 	= (typeof response.status === 'undefined' ) ? 0 : response.status;
		var msg 	= (typeof response.message === 'undefined' ) ? 'Message not defined' :  response.message ;
		
		if(response.status)
		{		
			this.success( msg );
		}
		else
		{
			this.error( msg );
		}
	},
	error  : function( msg)
	{	
		toastr.options['timeOut']         = "60000"
		toastr.options['extendedTimeOut'] = "30000"
		toastr.error(msg, 'Error');
	},
	success  : function( msg )
	{	
		toastr.options['timeOut']         = "7000"
		toastr.options['extendedTimeOut'] = "3000"
		toastr.success(msg, 'Realizado');
	},
	initialize: function()
	{
		var messages = $('[name="flash-notify[]"]');
		if( messages.length )
		{
			$(messages).each(function( i ){
					
				var type  = $(this).data('type');
				var msg   = $(this).data('msg');
				
				if(type == 'error')
				{	
					Notify.error($(this).data('msg') );
				}
				else
				{
					Notify.success($(this).data('msg') );
				}
			});
		}
	}
}	

$(document).ready(function(){
	Notify.initialize();	
});

