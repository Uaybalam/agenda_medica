
var app = angular.module('app_billing', ['angularUtils.directives.dirPagination']);

app.controller('ctrl_billing',function($http, $scope, $filter ){
	
	$scope.ngHelper     = new ngHelper( $scope );	
	$scope.billing      = [];
	$scope.currentPage  = 1;
	$scope.total_count  = 0;
	$scope.itemsPerPage = 20;
	$scope.arr_status   = [];
	$scope.selectAll    = "0";
	$scope.pendingPrint = 0;
	$scope.checkPrint  = 0;
	
	$scope.editComments = function(bill)
	{
		for(let i= 0; i < $scope.billing.length; i++)
			$scope.billing[i]['edit'] = 0;
		
		bill.edit = 1;
		setTimeout(function(){
			$('[ng-model="bill.comments"]').not('.ng-hide').focus();
		},11)
		
	}

	$scope.updateComments = function(keyEvent, bill){
		
		if(keyEvent.keyCode != 13)
		{
			return false;
		}

		let Data = {
			comments: bill.comments
		};

		$http({
			method: 'POST',
			url: '/billing/updateComments/'+bill.id,
			data:  $.param(  Data ) ,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then( response => {
			
			if(!response.data.status)				
				toastr.error(response.data.message);
			
			bill.edit = 0;
		});
	};

	$scope.goToPrintFilter = function(event){
		event.preventDefault();

		let link    = document.createElement('a');
		link.href   = '/billing/pdf/specialFilter?' + $.param($scope.filter);
		link.target = '_blank';
		link.click();
		
		let  url = '/billing/toggleChangeSpecialFilter?' + $.param($scope.filter);

		Swal.fire({
			title: '¿Fue exitosa la impresión?',
			text: "La fecha de impresión se actualizará y el estado cambiará a Enviado (si está completado)",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value) {
				
				$http.post(url).then(response => {
					
					$scope.getData(1);
					
					Swal.fire(
						'Successful!',
						response.data.message,
						'success'
					);
				});
			}
		});
	}
	
	$scope.showPrintFilter = function(){
		//no pending to prints

		if($scope.filter.status==0 && $scope.filter.print_date=='')
			return false;
		
		if($scope.filter.encounter_id)
			return true;
		if($scope.filter.start_date)
			return true;
		if($scope.filter.end_date)
			return true;
		if($scope.filter.insurance)
			return true;
		if($scope.filter.status>0)
			return true;
		if($scope.filter.biller)
			return true;
		if($scope.filter.print_date)
			return true;
		
		return false;
	}

	$scope.filter = {
		encounter_id:'',
		start_date:'',
		end_date:'',
		insurance:'',
		status:'',
		biller:'',
		print_date:''
	}

	$scope.sort = {
		name: 'encounter_id',
		type: 'desc'
	}

	$scope.numQuery = 0;
	
	$scope.toggleForPrint = function(){

		bills  = $filter('filter')( $scope.billing , $scope.canPrint, true );

		if(!bills.length)
			return false;

		billsPending = [];
		for(i=0; i<bills.length ;i++)
		{
			billsPending.push(bills[i].id);
		}
		
		let Data = {
			checkPrint: $scope.checkPrint,
			billsPending: billsPending
		};

		$http({
			method: 'POST',
			url: '/billing/togglePrint/',
			data:  $.param(  Data ) ,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then( response => {
			if(response.data.status)
			{
				$scope.getData($scope.currentPage)
			}
		});

	}

	$scope.canPrint = function( item ){
		
		if(item.status==0 || item.status==6)
		{
			return false;
		}

		return true;
	}

	$scope.goToPrint = function( event )
	{ 
		event.preventDefault();
		//
		//document.getElementById('printAll').click();
		//
		let url = "";

		if($scope.filter.biller ==  "" && 
		   $scope.filter.encounter_id ==  "" && 
		   $scope.filter.end_date ==  "" && 
		   $scope.filter.insurance ==  "" && 
		   $scope.filter.print_date ==  "" && 
		   $scope.filter.start_date ==  "" && 
		   $scope.filter.status ==  "")
		{
			document.getElementById('printAll').click();
			url = '/billing/toggleChange';
		}
		else
		{

			let link    = document.createElement('a');
			link.href   = '/billing/pdf/specialFilter?' + $.param($scope.filter);
			link.target = '_blank';
			url         = '/billing/toggleChangeSpecialFilter?' + $.param($scope.filter);
			link.click();
		}
		
		Swal.fire({
			title: '¿Fue exitosa la impresión?',
			text: "La fecha de impresión se actualizará y el estado cambiará a Enviado (si está completado)",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Cancelar',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value) {

				//$http.post('/billing/toggleChange').then(response => {
				$http.post(url).then(response => {	
					
					$scope.getData(1);
					
					Swal.fire(
						'Successful!',
						response.data.message,
						'success'
					);
				});

			}
		});
	}

	$scope.getData = function(pageno){ 
		
		$scope.currentPage = pageno;
		var numQuery = ++$scope.numQuery;

		filters = {
			encounter_id: $scope.filter.encounter_id,
			start_date:$scope.filter.start_date,
			end_date:$scope.filter.end_date,
			insurance:$scope.filter.insurance,
			status:$scope.filter.status,
			biller:$scope.filter.biller,
			print_date:$scope.filter.print_date
		};
		
		sort = {
			name: $scope.sort.name,
			type: $scope.sort.type
		}

		$data = {
			filters: filters,
			sort: sort
		}	

		$http.get("/billing/list/" + $scope.itemsPerPage+"/"+pageno+"?"+ $.param($data)  ).success(function(response){ 

			if(numQuery != $scope.numQuery)
			{
				//cancel query
				return false;
			}
						
			$scope.billing      = response.billings; 
			$scope.total_count  = response.total_count;
			$scope.pendingPrint = response.pendingPrint;
			setTimeout(function(){
				$('[data-toggle="tooltip"]').tooltip();

				onFocus();
			}, 1 );
		});
	};

	$scope.sortData = function( name ){
		$scope.sort.name = name;
		$scope.sort.type = ($scope.sort.type=='asc') ? 'desc' : 'asc';
		$scope.getData(1);
	}
	
	$scope.sortClass = function(keyName)
	{
		if(keyName != $scope.sort.name)
		{
			return 'fa-sort';
		}
		else if($scope.sort.type=== 'asc')
		{
			return 'fa-sort-asc'
		}
		else
		{
			return 'fa-sort-desc'
		}
	}

	$scope.disabledBill = function(bill)
	{
		var index = [0,1,5,6].indexOf(parseInt(bill.status));
		if(index>=0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	$scope.initialize = function( $status )
	{	
		$scope.arr_status = $status;
	}
	
	$scope.toggle_print = function( bill ){
		
		if(bill.status == 0)
		{
			return false;
		}

		$http.get('/billing/toggle-print/'+bill.id).then(function(response){
			$scope.pendingPrint = response.data.pendingPrint;
		});
	}
	
	$scope.editBill = function( bill ){
			
		if($scope.disabledBill(bill))
		{
			return false;
		}

		$http.get('/billing/data-edit/'+ bill.id ).then(function(response) {
			$scope.response                = response.data;
			$scope.response.billing.status = $scope.response.billing.status.toString();
			$("#billing-modal-edit-detail").modal();
		});	
	}

	$scope.updateStatus = function()
	{
		var Data = angular.copy($scope.response.billing);

		$('.submit').attr('disabled','disabled');

		$http({
			method: 'POST',
			url: '/billing/update-status/' + $scope.response.billing.id ,
			data:  $.param(  Data ) ,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			$('.submit').removeAttr('disabled');
			
			Notify.response( response.data );

			$("#billing-modal-edit-detail").modal("hide")

			$scope.getData(1);
		});
	}

	$scope.onChangePaid = function( $item )
	{
		
		if(  parseFloat($item.paid) > parseFloat($item.charges)   )
		{	
			Notify.error('Paid cant be less than total');
			
			$item.paid = 0.0;
		}

		$scope.onChangeTotalPaid();
	}

	$scope.onChangeWriteOff = function( $item )
	{
		var balance = 0;
		
		var maxWriteOff = parseFloat($item.charges) - parseFloat($item.paid);  
		
		if(  parseFloat($item.write_off) > maxWriteOff  )
		{	
			Notify.error('Write-Off cant be less than ('+maxWriteOff+')');
			
			$item.write_off = 0.0;
		}
		

		$scope.onChangeTotalWriteOff();
	}

	$scope.onChangeTotalPaid = function()
	{
		var services = $scope.response.billing.detail;
		var sum 	 = 0;
		for( i =0; i < services.length; i++)
		{
			sum+= parseFloat(services[i].paid) || 0;
		}
		
		$scope.response.billing.total_paid = parseFloat(sum).toFixed(2);

		$scope.onChangeTotalDUE();
	}

	$scope.onChangeTotalWriteOff = function()
	{
		var services = $scope.response.billing.detail;
		var sum 	 = 0;
		for( i =0; i < services.length; i++)
		{
			sum+= parseFloat(services[i].write_off) || 0;
		}
		
		$scope.response.billing.total_write_off = parseFloat(sum).toFixed(2);

		$scope.onChangeTotalDUE();
	}

	$scope.onChangeTotalDUE = function()
	{
		var total_charge    = parseFloat( $scope.response.billing.total_charge );
		var total_paid      = parseFloat( $scope.response.billing.total_paid );
		var total_write_off = parseFloat( $scope.response.billing.total_write_off );
		
		var sum = total_charge - ( total_paid + total_write_off );
		
		$scope.response.billing.total_due = parseFloat(sum).toFixed(2);
	}
	/*
	$scope.toggleChange = function(){

		$http.get('/billing/toggleChange/'+ $scope.selectAll).success(function( response){
			Notify.response( response );

			if(!response.status )
			{
				return false;
			}
			else
			{
				$scope.getData(1);
			}
		});
	}
	*/
	$scope.getData(1);

}).run(function($rootScope) {
		$rootScope.typeOf = function(value) {
			return typeof value;
		};
	})
	.directive('stringToNumber', function() {
		return {
			require: 'ngModel',
			link: function(scope, element, attrs, ngModel) {
				ngModel.$parsers.push( function(value) {
					if(!value)
					{
						return 0;
					}
					else
					{
						return '' + value;
					}
				});
				ngModel.$formatters.push(function(value) {
					return parseFloat(value) || 0;
				});
			}
		};
	});



$(function() {

	var start = null;//moment().subtract(29, 'days');
	var end   = null;//moment();

    function setDataRange(start, end) {
    	
    	if(start)
    	{
    		$('#rangeDate span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
    	}
    	else
    	{
    		$('#rangeDate span').html('');
    	}	
    }

    $('#rangeDate').on('cancel.daterangepicker', function(ev, picker) {
    	
    	$('#rangeDate span').html('');

    	$scope                      = angular.element($('[ng-controller="ctrl_billing"]')).scope();
		$scope.filter['start_date'] = '';
		$scope.filter['end_date']   = '';
		$scope.getData(1);
	});

    $('#rangeDate').daterangepicker({
        //startDate: start,
        //endDate: end,
        opens: "right",
        ranges: {
			'Hoy': [moment(), moment()],
			'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Ultimos 7 Días': [moment().subtract(6, 'days'), moment()],
			'Ultimos 30 Días': [moment().subtract(29, 'days'), moment()],
			'Mes actual': [moment().startOf('month'), moment().endOf('month')],
			'Ultimo Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
        	format: 'M/DD/YYYY',
        	cancelLabel:'Limpiar',
        	applyLabel: "Aplicar",
        	customRangeLabel:"Rango personalizado"
	    }
    }, setDataRange );

    $('#rangeDate').on('apply.daterangepicker', function(ev, picker) {
    	
		$scope                      =  angular.element($('[ng-controller="ctrl_billing"]')).scope();
		$scope.filter['start_date'] = picker.startDate.format('YYYYMMDD');
		$scope.filter['end_date']   = picker.endDate.format('YYYYMMDD');

  		$scope.getData(1);
	});

    setDataRange(start, end);

});

function onFocus(){
    $('.focus-selected').on('focus', function(){
    	this.select();
    })
}


/***
** refresh on view tab
****/
window.addEventListener("focus", function(event) 
{
    $scope =  angular.element($('[ng-controller="ctrl_billing"]')).scope();
	$scope.getData($scope.currentPage);
}, false);