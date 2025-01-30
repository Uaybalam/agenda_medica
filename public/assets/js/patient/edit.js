$(function(){
	
	var lang  = $('#settings_language').val() || '';
	var found = $('#settings_how_found_us').val() || '';
	
	var settings_language     = (lang!='') ? lang.split(',') : [];	
	var settings_how_found_us =  (found!='') ? found.split(',') : [];	
	
	$('[name="language"]').tagsinput({
		typeahead: {
			source: settings_language ,
			afterSelect: function(val) { 
				this.$element.val(""); 
			},
		},
		tagClass: function(item) {	
			return 'label label-info';
		},
		freeInput:true
	});

	$('[name="how_found_us"]').typeahead({
		source: settings_how_found_us,
		minLength: 0,
		autoSelect: true,
		items: 20
	}).on("click", function() {
        var ev = $.Event("keydown");
        ev.keyCode = ev.which = 40;
        $(this).trigger(ev);
        return true;
 	});

	$('.remove-location').on('click', function(){
		$('[name="'+$(this).data('name')+'"]')
			.val(0)
			.trigger('change');
	});

	$(".create-location").select2({
		ajax: {
			url: "/location/filter",
			dataType: 'json',
			delay: 200,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page
				};
			},
			processResults: function (data, params) {
				params.page = params.page || 1;
				return {
					results: data.items,
					pagination: {
						more: (params.page * 30) < data.total_count
					}
				};
			},
			cache: true
		},
		escapeMarkup: function (markup) { 
			return markup; 
		},
		minimumInputLength: 2,
		templateResult: function( data, container){
			
			if( typeof data === 'undefined' )
			{
				return '';
			}
			else{
				var template = '';
				template = data.city;
				template+= '<span class="text-opacity"> ( '+data.state_full+' , '+data.county+' ) </span> ';
				return template;
			}
		},
		templateSelection: function( data ){
			if( data.text != '' )
			{
				return data.text;
			}
			else{
				var template = '';
				template = data.city;
				template+= '<span class="text-opacity"> ( '+data.state_full+' , '+data.county+' ) </span> ';
				return template;
			}	
		}
	});
	


	$('.disabled-if-check').on('change',function(){
		disabled_if_check(this);
	});
	
	disabled_if_check( $('[name="insurance_primary_status"]:checked') );
	disabled_if_check( $('[name="insurance_secondary_status"]:checked') );

	$('.autosave').on('change', function(){

		var name = $(this).attr('name'),
			value = ($(this).attr('type')==='radio') ? $(this,':checked').val() :  $('[name="'+name+'"]').val();


		var Data = {
			model: name,
			value: value
		};

		if( $(this).hasClass('create-datepicker') && Data.value!='' )
		{	
			var date = Data.value.split('/');
			
			if( date.length === 3 && date[2].length===4)
			{
				//Data.value = date[2] + '-' + date[0] + '-' + date[1];
			}
			else
			{
				return false;
			}
			
		}
	
		$.post( '/patient/update/' + $('body').data('patient_id'), $.param( Data) , function( response ){
			Notify.response( response );
		}, 'json');
	});

	var count = $('#warnings_active tr').length;
	 $('#warnings_active_count').html(count);
});	
	
disabled_if_check = function(self)
{	

	var elements = $(self).data('elements-disabled');
	var value 	 = $(self,':checked').val()
	
	if( parseInt(value) === 0 )
	{
		$(elements).attr('disabled', 'disabled');
	}
	else
	{
		$(elements).removeAttr('disabled');
	}
}