var canvas= document.getElementById('canvas_chart');

var ChartPayments  = new Chart(canvas, {
	type: 'line',
	data: {
		labels:[],
		datasets: [{
			label: 'Cash',
			yAxisID: 'PAYMENT',
			data: [],
			fill:false,
  			borderColor: chartColors.orange,
            backgroundColor: "rgba(255, 159, 64,0.5)",
		},{
			label: 'Insurance',
			yAxisID: 'PAYMENT',
			data: [],
			fill:false,
  			borderColor: chartColors.red,
  			backgroundColor: "rgba(255, 99, 132, 0.5)"
		}]
	},
  	options: {
		scales: {
			yAxes: [
				{
					id: 'PAYMENT',
					position: 'left',
					scaleLabel: {
                        display: true,
                        labelString: "$ Payment",
                    },
                    ticks: {
						// Create scientific notation labels
						callback: function(value, index, values) {
							return " $ " + value.toFixed(2).replace(/./g, function(c, i, a) {
							    return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
							});
						}
	                }
	     		}
			]
		},
		tooltips: {
	        callbacks: {
	            label: function(tooltipItem, data) {
	            	return " $ " + tooltipItem.yLabel.toFixed(2).replace(/./g, function(c, i, a) {
					    return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
					});
	            }	
	        }
	    }
	}
});

angular
	.module('app_payment_chart',  [] )
	.controller('ctrl_payment_chart', function($scope, $http ){
		
		$scope.data = {}
		
		$scope.encounters_of_day = [];

		$scope.default = {
			option:'ALL_YEARS',
			label:'' ,
			option_year:'',
			option_month:'',
			option_day:'',
			encounters_of_day: ''
		}
		
		$scope.changeType = function( option , label)
		{
			$scope.default.option = option;
			$scope.default.label  = label;
			var labelFilter       = label;

			if($scope.data.labels)
			{
				var position = $scope.data.label_value.indexOf( label );
				if( position >= 0)
				{
					labelFilter = $scope.data.labels[ position ];
				}
			}

			$scope.default.currentPag = 1;
			
			$http.get('/payment/data/',{params: { 'option': option, 'label' : labelFilter  } }).then(function(response) {
				
				$scope.data = response.data;
				
				ChartPayments.config.data.labels 		   = response.data.label_value;
				ChartPayments.config.data.datasets[0].data = response.data.cashData;
				ChartPayments.config.data.datasets[1].data = response.data.insuranceData;

				ChartPayments.update();
		    });
		}	

		$scope.initialize = function( )
		{
			console.log("initialize data");
			$scope.changeType('ALL_YEARS', '' );
		}

		$scope.filterLabelOption = function(evt)
		{
			var activePoints = ChartPayments.getElementsAtEvent(evt);       
			
			if(activePoints.length > 0)
		    {	
				var clickedElementindex = activePoints[0]["_index"];

				var label = ChartPayments.data.labels[clickedElementindex];

				var CurrentPosition = $scope.data.label_value.indexOf( label );

				if($scope.default.option === 'ALL_YEARS')
				{
					$scope.default.option_year = label;
					$scope.changeType('YEAR', label);
				}
				else if($scope.default.option === 'YEAR')
				{	
					$scope.default.option_month = $scope.data.labels[CurrentPosition];
					$scope.changeType('MONTH', label);
				}
				else if($scope.default.option === 'MONTH')
				{	
					$scope.default.option_day 		 = $scope.data.labels[CurrentPosition];
					$scope.default.encounters_of_day = $scope.default.option_day;
					$scope.encounters_of_day 		 = [];

					$http.get('/payment/data/',{params: { 'option': 'DAY', 'label' : $scope.default.option_day  } }).then(function(response) {
						
						$scope.encounters_of_day = response.data.encounters_of_day;
						
						setTimeout(function(){
							$('[data-toggle="tooltip"]').tooltip();
						}, 500 );
						

				    });

					$("#payment-modal-payment-by_encounter").modal();
					
				}
		   	}
		}

		$scope.refreshAction = function( $option , $label )
		{
			
			if($option === 'ALL_YEARS')
			{
				$scope.default.option_year  = '';
				$scope.default.option_month = '';
				$scope.default.option_day   = '';
			}
			else if($option === 'YEAR')
			{	
				$scope.default.option_month = '';
				$scope.default.option_day   = '';

			}
			else if($option === 'MONTH')
			{
				$scope.default.option_day   = '';
			}

			$scope.changeType( $option , $label  );
		}

		$scope.btnEnabled = function( $values )
		{
			var position = $values.indexOf($scope.default.option);
			
			if( position >= 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		$scope.btnActive = function( $values )
		{
			var position = $values.indexOf($scope.default.option);
			
			if( position >= 0)
			{
				return 'active';
			}
			else
			{
				return '';
			}
		}
		
	});


