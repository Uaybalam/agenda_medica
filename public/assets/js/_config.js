var TokenCSRF = '[name="csrf_app_token"]';
 
moment.locale('es');

$(document).ready(function(){

	$('input[type="text"].form-control').on('focus',function(){
		this.select();
	});

	var prevent_default_loading = $('#prevent-default-loading').val() || 0;
	
	if( prevent_default_loading == 0 )
	{
		setTimeout(function(){
			$('#loading').fadeOut('fast');
		},250);
	}
	

	$('.modal').on('shown.bs.modal', function () {
		
		var element_str = 'input:not([readonly])'
			+ ',textarea:not([readonly])'
			+ ',select:not([readonly])';
		
		$(this).find( element_str ).filter(':visible:first').focus();
		
	});

	var create_select2 = $(".create-select2")
	if(create_select2.length > 0 )
	{	
		$( create_select2 ).select2();
		$('b[role="presentation"]').hide();
		$('.select2-selection__arrow').append('<i class="fa fa-caret-down"></i>')
	}
	
	var create_datepicker = $(".create-datepicker");
	if( create_datepicker.length > 0 )
	{	
		$( create_datepicker ).bind('keydown', function (event) {
            if (event.which == 13) {
                var e = jQuery.Event("keydown");
				e.which   = 9;//tab 
				e.keyCode = 9;
                $(this).trigger(e);
                return false;
            }
		}).datepicker({
	        format: 'mm/dd/yyyy',
	        language: 'es',
	        autoclose: true,
	        toggleActive: true,
	        todayHighlight: true,
	        todayBtn: false,
	        zIndexOffset: 1040
	    });
	}
	
	$('[data-toggle="tooltip"]').tooltip();
	
	var alerts       = $(".alert-date-created");
	var temporalHTML = "";
	for(var i=0;i<alerts.length;i++){
		temporalHTML = $(alerts)[i].innerHTML;
   		$(alerts)[i].innerHTML =  moment( temporalHTML, "YYYY-MM-DD").fromNow();
	}

});

$.fn.getObjects = function()
{
	var o          = {};
	var a          = this.serializeArray();
	var is_array   = false; 
    	   
    $.each(a, function() {
        name_temp = this.name;
        this.name = this.name.replace("[]",""); 
         
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {    	
            is_array =  /[[]]/.test(name_temp);
            if(is_array){ 
                o[this.name] = [];
                o[this.name].push(this.value || '');
            }else{
                o[this.name] = this.value || '';
            }   
        }	
    });
    
    return o;
}

var Submit = {
	before: function(){
		$('.submit').attr('disabled', 'disabled');
	},
	after: function( time ){
		var t = time || 1000;
		setTimeout(function(){
			$('.submit').removeAttr('disabled');
		} , t )
	}
}


$('[data-mask]').each(function(){$( this ).mask(  $(this).data('mask') );});
	
var copy_data = function(ngModelObject, dataCopy)
{
	var objData = angular.copy( dataCopy ); 
	var element;
	var value;
	var $output = {};

	for( e in objData)
	{	
		//element = $('[ng-model="default.history_active.'+e+'"]');
		element = document.querySelectorAll('[ng-model="'+ngModelObject+'.'+e+'"]');
		if( element.length === 0 )
		{
			value =  objData[e];
		}
		else if(element[0].getAttribute('type') === 'number')
		{
			if(element[0].getAttribute('step'))
				value = parseFloat(objData[e]) || '';
			else
				value = parseInt(objData[e]) || '';
		}
		else
		{
			value = objData[e];
		}
		
		$output[e] = value;
	}

	return $output;
}
	/*	
var get_age_patient = function( $dob )
{
	split_date = $dob.split('/');
	if(split_date.length!=3)
	{
		return 'Date not valid';
	}
		
	date       = new Date(split_date[2], (split_date[0] - 1 ),split_date[1] );
	
	if(age = moment().diff( date, "years") )
	{	
		return age + ( (age>1) ? " YRS" : " YR" );
	}else if(age = moment().diff( date, "months"))
	{
		return age + ( (age>1) ? " MTS" : " MTH" );
	}else if(age = moment().diff( date, "days"))
	{
		return age + ( (age>1) ? " DYS" : " DY" );
	}
		
	return "12 Hours ago";
}
*/
var get_age_patient = function( $dob   )
{
	split_date = $dob.split('/');
	var text   = "";

	if(split_date.length!=3)
	{
		return 'Date not valid';
	}

	var date   = moment([ split_date[2], parseInt(split_date[0]) -1  , split_date[1] ] );
	var today  = moment();
	
	var years  = moment().diff(date,'years');
	date.add(years, 'years');
	var months = moment().diff(date,'months');
	date.add(months, 'months');
	var days = moment().diff(date,'days');

	if(!years && !months && !days)
	{
		return "Hace 12 horas";
	}

	if(years)
	{
		text = years + " Años ";
	}
	if(months){
		text+= months + (months == 1 ? " mes " : " meses ");
	}
	if(days && (!years && !months)){
		text+= days + (days == 1 ? " día " : " días ");
	}
	
	return text;
}

var date_to_number = function( dateString , complete_date  ){

	if( dateString === '')
	{
		return 0;
	}

	if(complete_date === true )
	{	
		var dateStringTmp = dateString.split(" ");
		var split_date    = dateStringTmp[0].split("-");
		var concat_date   = split_date[0] + split_date[1] + split_date[2]; 
	}
	else
	{
		var split_date 	= dateString.split("/");
		var concat_date = split_date[2] + split_date[0] + split_date[1]; 
	}

	return parseInt( concat_date );
}

/**
 * Angular http headers config
 */	
var $httpConfig = {
	headers :{
		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
	}
}

//or as a Number prototype method:

function padLeft(nr, n, str){
    return Array(n-String(nr).length+1).join(str||'0')+nr;
}

var chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(231,233,237)'
};

/**
|
|	General Helper For-Angular JS
|
**/
var ngHelper = function( $gantScope, optionsAjax )
{	
	this.sort = function(keyname){
		
	    $gantScope.sortKey = keyname;         
	    $gantScope.reverse = !$gantScope.reverse;
	    
	    setTimeout(function(){
			$('[data-toggle="tooltip"]').tooltip();
		}, 1 );
	}

	this.sortClass = function(keyName)
	{
		
		if(keyName != $gantScope.sortKey)
		{
			return 'fa-sort';
		}
		else if(!$gantScope.reverse)
		{
			return 'fa-sort-asc'
		}
		else
		{
			return 'fa-sort-desc'
		}
	}
	
	this.normalDate = function(d)
	{
		return moment( d, "YYYY-MM-DD hh:mm:ss").format('MM/DD/YYYY')
	}

	this.humanDate = function(d)
	{
		return moment( d, "YYYY-MM-DD hh:mm:ss").fromNow()
	}

	this.formatDate = function(d)
	{	
		return moment( d, "YYYY-MM-DD hh:mm:ss").format('lll');
	}

	this.parsePhone = function( phoneNumber )
	{
		if(!phoneNumber) return '';
		return phoneNumber.toString().replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2 $3');
	}
}


/**
|
|	@param options
|		$scope
|		$http
| 		url
| 		postQuery
|		filters
|	
**/
var appPagination = function( appOptions ){

	var $self = this;

	$self.url          = appOptions.url;
	$self.currentPage  = 1;
	$self.total_count  = 0;
	$self.itemsPerPage = 15;
	$self.numQuery     = 0;
	$self.lastQuery    = false;
	$self.loadingQuery = false;
	$self.result_data  = [];
		
	$self.sort = {
		name:'encounter_id',
		type:'desc',
	}
	
	$self.getData = function(pageno){ 
		
		var numQuery = ++$self.numQuery;

		$self.lastQuery   = true;
		$self.currentPage = pageno;

		var filters = angular.copy(appOptions.filters);

		var sort = {
			name: $self.sort.name,
			type: $self.sort.type
		}

		$data = {
			filters: filters,
			sort: sort
		}
		
		var full_url = $self.url+"/" + $self.itemsPerPage+"/"+pageno+"?"+ $.param($data);
		
		if(typeof appOptions.preQuery === 'function')
		{
			appOptions.preQuery(appOptions.$scope);
		}

		appOptions.$http.get( full_url  ).success(function(response){ 
			
			if(numQuery === $self.numQuery && $self.lastQuery)
			{	
				
				

				$self.result_data  = response.result_data;
				$self.total_count  = response.total_count;
				$self.lastQuery    = false;
				$self.loadingQuery = false;

				if(typeof appOptions.postQuery === 'function')
				{	
					appOptions.postQuery( response , appOptions.$scope , $self );
				}

			}
			else if($self.lastQuery)
			{	
				$self.loadingQuery = true;
				$self.result_data  = [];
			}

			
		}).finally(function(){
			
			if(typeof appOptions.finally === 'function')
			{
				appOptions.finally(appOptions.$scope  );
			}

			setTimeout(function(){
				$('[data-toggle="tooltip"]').tooltip();
			},1);
		});
	};
	
	$self.sortData = function( name , type ){
		$self.sort.name = name;
		if(typeof type === 'string')
		{
			$self.sort.type = type;
		}
		else
		{
			$self.sort.type = ($self.sort.type=='asc') ? 'desc' : 'asc';
		}
		$self.getData(1);
	};

	$self.sortClass = function(keyName)
	{
		if(keyName != $self.sort.name)
		{
			return 'fa-sort';
		}
		else if($self.sort.type=== 'asc')
		{
			return 'fa-sort-asc'
		}
		else
		{
			return 'fa-sort-desc'
		}
	}

}
