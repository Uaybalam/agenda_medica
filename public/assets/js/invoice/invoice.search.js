var app = angular.module('app_invoice_search', ['angularUtils.directives.dirPagination']);

app.controller('ctrl_invoice_search',function( $http, $scope, $filter){


	$scope.default 	= {
		patient: {
			name: '',
			middle_name: '',
			last_name: '',
			gender: "Male",
			phone: '',
			phone_memo: '',
			date_of_birth: '',
			how_found_us: '',
			interpreter_needed: false,
			advanced_directive_offered: false,
			advanced_directive_taken: false,
		}
	};

	var start = moment().subtract(29, 'days');
	var end   = moment();

	$scope.filter = {
		patient:'',
		date:'',
		start_date: start.format('YYYYMMDD'),
		end_date:end.format('YYYYMMDD')
	}
	
	//var currentDate = new Date().getTime();

	$scope.appPagination = new appPagination({
		$http:$http,
		$scope:$scope,
		url:'/encounter/invoice/search',
		filters: $scope.filter,
		postQuery: function(response,$scope){
			$scope.invoices = response.result_data;
		}
	});

	$scope.appPagination.itemsPerPage = "10";
	$scope.appPagination.sort = {
		name:'encounter_id',
		type:'desc'
	}
	$scope.appPagination.getData(1);
	
	$scope.ngHelper = new ngHelper($scope);

	$scope.createUrl = function(){
		var params = {};
		params['sort'] = $scope.appPagination.sort;
		params['filters'] = $scope.filter;

		return '/encounter/invoice/search/1/1/?format=pdf&' + $.param( params ) ;
	}

	$scope.getClassName = function( value ){
		if(value<0)
		{
			return 'text-danger bold';
		}
		else if(value>0)
		{
			return 'text-success bold';
		}
		else
		{
			return 'text-default';
		}
	}

});

$(function() {

	var start = moment().subtract(29, 'days');
	var end   = moment();

    function setDataRange(start, end) {
        $('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
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

    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
    	
    	$scope                      =  angular.element($('[ng-controller="ctrl_invoice_search"]')).scope();
		$scope.filter['start_date'] = picker.startDate.format('YYYYMMDD');
		$scope.filter['end_date']   = picker.endDate.format('YYYYMMDD');

  		$scope.appPagination.getData(1);
	});

    setDataRange(start, end);

});
