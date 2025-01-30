let chargesController = function( $scope, $http){
	
	let vm = this;
	
	vm.add = function( event ){
		event.preventDefault();
		$http.post('/billing/addCharges/'+ $scope.data.billing.id ).then(function(response) {
	       	if(response.data.status)
	       	{
	       		$scope.data.billing.extraCharges.push(response.data.charges);
	        	
		        setTimeout(function(){
		        	$('.nav a[href="#charges-'+ response.data.charges.id + '"]').tab('show');

					$('input.form-control').on('focus',function(){this.select();});
		        },1);
	       	}
	    });
	}

	vm.remove = function( charges , $index ){
		$http.post('/billing/removeCharges/'+ charges.id ).then(function(response) {
			if(response.data.status)
			{
				$scope.data.billing.extraCharges.splice($index, 1);
	        	$('.nav a[href="#charges-principal"]').tab('show');
			}
	        
	    });
	}	
}

angular
	.module('app_billing_detail',  [] )
	.controller('ctrl_billing_detail', function($scope, $http ){
		
		$scope.chargesController = new chargesController($scope,$http);
		$scope.ngHelper = new ngHelper();
		$scope.data = {
			billing: {
				detail:[],
				extraCharges:[]
			}
		}
		$scope.default_pin = '';
		$scope.default = {};
		
		$scope.previewDocument = function( file ){
			console.log(file);
			$scope.randomID     = Math.floor(Math.random() * 99999 );	
			$scope.default.file = file;
			$('#patient-relatedfiles-modal-preview-files').modal();
		}


		$scope.hasProcedureExtra = function( item , position )
		{
			console.log(item, position);
			if(!item['procedure_cpt_hcpcs_'+position])
				return item['date_of_service_'+position] = '';

			if(item['procedure_cpt_hcpcs_'+position] && !item['date_of_service_'+position])
				item['date_of_service_'+position] = $scope.data.encounter.create_at;
		}	

		$scope.hasProcedure = function( det )
		{
			if(!det.procedure_cpt_hcpcs)
				return det.date_of_service = '';
			
			if(det.procedure_cpt_hcpcs && !det.date_of_service)
				det.date_of_service = $scope.data.encounter.create_at;
			
		}

		$scope.initialize = function( encounter_id )
		{	
			$http.get('/billing/initialize/'+ encounter_id ).then(function(response) {
				$scope.data = response.data;
		    }).finally(function(){
				setTimeout(function(){
					$("[ng-model='data.billing.aditional_claim'").typeahead({
						source: $scope.data.additional_claim_data,
						minLength: 0,
						autoSelect: true,
						items: 12
					}).on("click", function() {
		                var ev = $.Event("keydown");
		                ev.keyCode = ev.which = 40;
		                $(this).trigger(ev);
		                return true;
				 	});
					
					$(".create-datepicker-range").datepicker({
						format: 'mm/dd/yyyy',
				        language: 'en',
				        autoclose: true,
				        toggleActive: true,
				        todayHighlight: true,
				        todayBtn: false,
				        zIndexOffset: 1040
					});

					var selectProvider = $scope.data.billing.provider_name+'|'+$scope.data.billing.provider_npi;
					$scope.data.billing.select_provider = selectProvider;
					$('[ng-model="data.billing.select_provider"]').select2({"language": {"noResults": () => {return "Sin resultados";},}}).val(selectProvider).trigger('change');
				},1);
		    });
		}	
		
		$scope.updateBilling = function()
		{	
			var Data = angular.copy($scope.data.billing);
			console.log("Data", Data)
			$('.submit').attr('disabled','disabled');

			$http({
			    method: 'POST',
			    url: '/billing/update/' + $scope.data.billing.id ,
			    data:  $.param(  Data ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				$('.submit').removeAttr('disabled');
				
				Notify.response( response.data );

				if(response.data.status)
				{	
					$scope.data.billing   = response.data.refresh.billing;
					$scope.data.can_print = response.data.refresh.can_print;
				}
				
				setTimeout(function(){
					$('.nav a[href="#charges-principal"]').tab('show');
				},1);
			});
		}

		$scope.doneBilling = function()
		{
			
			var Data = angular.copy($scope.data.billing);

			$('.submit').attr('disabled','disabled');

			$http({
			    method: 'POST',
			    url: '/billing/done/' + $scope.data.billing.id ,
			    data:  $.param(  Data ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				$('.submit').removeAttr('disabled');
				Notify.response( response.data );
				
				if(response.data.status)
				{	
					$scope.data.billing   = response.data.refresh.billing;
					$scope.data.can_print = response.data.refresh.can_print;
				}

				setTimeout(function(){
					$('.nav a[href="#charges-principal"]').tab('show');
				},1);
			});
		}

		$scope.change_charge = function()
		{
			var services = $scope.data.billing.detail;
			var sum 	 = 0;
			for( i =0; i < services.length; i++)
			{
				sum+= parseFloat(services[i].charges) || 0;
			}
			
			$scope.data.billing.total_charge = parseFloat(sum).toFixed(2);
		}
		
		$scope.change_benefit_plan = function()
		{
			
			if( $scope.data.billing.other_benefit_plan === 'No')
			{		
				$scope.data.billing.insured_other_last_name           = '';
				$scope.data.billing.insured_other_middle_initial      = '';
				$scope.data.billing.insured_other_first_name          = '';
				$scope.data.billing.insured_other_policy              = '';
				$scope.data.billing.insured_other_insurance_plan_name = '';
			}
		}

		$scope.openBillDenied = function(){
			$("#billing-modal-denied").modal();
		}



		$scope.openBillComments = function(){
			$("#billing-modal-comments").modal();
		}
		
		$scope.checkBtnComments = function(){
			console.log($scope.data.billing.status);
			if($scope.data.billing.status!=0)
				return true;

			return false;
		}

		$scope.submitBillComments = function( ev )
		{	
			ev.preventDefault();
			var Data = {
				comments: $scope.default_comments,
				pin: $scope.default_pin
			};

			$('.submit').attr('disabled','disabled');

			$http({
			    method: 'POST',
			    url: '/billing/setComments/' + $scope.data.billing.id ,
			    data:  $.param(  Data ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				$('.submit').removeAttr('disabled');
				
				Notify.response( response.data );
				
				if(response.data.status)
				{	
					$scope.data.billing   = response.data.refresh.billing;
					$scope.data.can_print = response.data.refresh.can_print;
					$scope.data.not_edit  = response.data.refresh.not_edit;

					$("#billing-modal-denied").modal('hide');
				}
				
				setTimeout(function(){
					$('.nav a[href="#charges-principal"]').tab('show');
				},1);
			});
		
		
		}

		$scope.submitBillDenied = function()
		{	
			var Data = {
				comments: $scope.default_comments,
				pin: $scope.default_pin
			};

			$('.submit').attr('disabled','disabled');

			$http({
			    method: 'POST',
			    url: '/billing/denied/' + $scope.data.billing.id ,
			    data:  $.param(  Data ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				$('.submit').removeAttr('disabled');
				
				Notify.response( response.data );
				
				if(response.data.status)
				{	
					$scope.data.billing   = response.data.refresh.billing;
					$scope.data.can_print = response.data.refresh.can_print;
					$scope.data.not_edit  = response.data.refresh.not_edit;

					$("#billing-modal-denied").modal('hide');
				}
				
				setTimeout(function(){
					$('.nav a[href="#charges-principal"]').tab('show');
				},1);
			});
		
		
		}
		
	})
	.run(function($rootScope) {
		$rootScope.typeOf = function(value) {
			return typeof value;
		};
	})
	.directive('stringToNumber', function() {
		return {
			require: 'ngModel',
			link: function(scope, element, attrs, ngModel) {
				ngModel.$parsers.push( function(value) {
					return '' + value;
				});
				ngModel.$formatters.push(function(value) {
					return parseFloat(value);
				});
			}
		};
	});



$(window).load(function(){
	$('input.form-control').on('focus',function(){
		this.select();
	});
});