var action_addendum = function($scope, $http)
{
	var SELF = this;
	this.modal = function(){
		return '#encounter-detail-modal-addendum';
	};
	this.open = function(){
		$scope.default.addendum = {
			notes: '',
			password: ''
		};
		$(SELF.modal()).modal();
	};
	this.submit = function( event ){
		
		event.preventDefault();
		var Form = $(event.currentTarget);
		
		var Data = $scope.default.addendum,
			Btn  = $('.submit', Form );
		$(Btn).attr( 'disabled', 'disabled' );
		
		$http({
		    method: 'POST',
		    url: '/encounter/request/create-addendum/' + $scope.data.encounter.id ,
		    data:  $.param(Data) ,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );
			if(response.data.status === 1 )
			{	
				$(SELF.modal()).modal('hide');
				
				$scope.data.addendums.unshift(  response.data.addendum );
			}
			
			$scope.default.addendum.password = '';

			$(Btn).removeAttr( 'disabled' );
		});
	};
};

var action_results = function($scope, $http )
{
	var vm = this;
	
	vm.change_status = function ( lab )
	{	

		$http.get( '/encounter/request/' + lab.id +'/change-result/'+ lab.status )
		.then( function(response) {
			Notify.response( response.data );
			if(response.data.status)
			{
				$('#pending-get_pending_results').html(response.data.pending);
			}
		});
	};	
};

var action_invoice = function( $scope, $http )
{
	angular.extend( this, {
		open: function(){

			$scope.default.invoice = copy_data('default.invoice', $scope.data.invoice  );
			
			$('#encounter-request-modal-invoice').modal();
		},
		submit: function( event ){
			event.preventDefault();

			var Data = angular.copy( $scope.default.invoice );

			$('.submit').attr('disabled' , true);
				
			$http.post('/encounter/request/invoice-update/' + $scope.data.encounter.id , $.param(Data) , $httpConfig )
				.then(function( response ){
					Notify.response( response.data  );

					if(response.data.status== 1)
					{
						$('#encounter-request-modal-invoice').modal('hide');

						$scope.data.invoice = response.data.invoice;
					}
					
					$('.submit').removeAttr('disabled');
				});
		},
		onChangeSubtotal: function(){
			var subtotal = 0;
				
			subtotal+= parseFloat($scope.default.invoice.office_visit) || 0;
			subtotal+= parseFloat($scope.default.invoice.laboratories) || 0;
			subtotal+= parseFloat($scope.default.invoice.injections) || 0;
			subtotal+= parseFloat($scope.default.invoice.medications) || 0;
			subtotal+= parseFloat($scope.default.invoice.procedures) || 0;
			subtotal+= parseFloat($scope.default.invoice.physical) || 0;
			subtotal+= parseFloat($scope.default.invoice.ecg) || 0;
			subtotal+= parseFloat($scope.default.invoice.ultrasound) || 0;
			subtotal+= parseFloat($scope.default.invoice.x_ray) || 0;
			subtotal+= parseFloat($scope.default.invoice.print_cost) || 0;
			
			$scope.default.invoice.subtotal = subtotal.toFixed(2);
			this.onChangeTotal();
		},
		onChangeTotal: function()
		{
			var total = parseFloat($scope.default.invoice.subtotal);
			total+= parseFloat( $scope.default.invoice.open_balance ) || 0;
			total-= parseFloat( $scope.default.invoice.discount ) || 0;
			if( total < 0 )
			{		
				$scope.default.invoice.discount = 0.00;
				Notify.error('Discount cant be less than total');
				return this.onChangeTotal();
			}
			
			$scope.default.invoice.total = total.toFixed(2);
			this.onChangePaid();
		},
		onChangePaid: function()
		{
			var balance = 0;
				
			var paid    = $scope.default.invoice.paid;
			var total   = parseFloat($scope.default.invoice.total);
			
			if(  paid > total  )
			{	
				Notify.error('Paid cant be less than total');
				
				$scope.default.invoice.paid = total;
			}	

			f = total - $scope.default.invoice.paid;
			
			$scope.default.invoice.balance_due = parseFloat( f.toFixed(2) );
		},
		toggleActive: function()
		{

			var Data = angular.copy( $scope.default.invoice );
			
			$http.post('/encounter/invoice/toggleActive/' + $scope.data.encounter.id , $.param(Data) , $httpConfig )
				.then( function( response ){ 
					if(!response.data.status)
					{
						Notify.error(response.data.message);
					}
				});
		}

	});
};

var action_checkout_cancel = function($scope, $http )
{
	angular.extend(this,{
		open:function(){
			console.log("CANCEL" );
			$scope.default.data_cancel.pin = "";
			$('#encounter-request-modal-checkout-cancel').modal();
		},
		set_checked_out:function(){
			
			$(".submit").attr("disabled", "disabled");
			
			var url  = '/encounter/request/' + $scope.data.encounter.id + '/cancel/';
			var Data = angular.copy($scope.default.data_cancel);
			
			$http.post(url, $.param(Data), $httpConfig )
				.then(function( response ){
					
					Notify.response( response.data );
					
					if(response.data.status === 1 )
					{
						$('#pending-get_pending_results').html(response.data.pending);
						$('#encounter-request-modal-checkout-cancel').modal('hide');
						$scope.initialize($scope.data.encounter.id);
					}
				}).finally(function(){
					$(".submit").removeAttr("disabled");
				})
		}
	});
};

var action_encounter = function($scope, $http )
{
	angular.extend(this,{
		open:function(){
			$scope.default.data_done.pin = "";
			$('#encounter-request-modal-done').modal();
		},
		set_checked_out:function(){
			
			$(".submit").attr("disabled", "disabled");
			
			var url  = '/encounter/request/' + $scope.data.encounter.id + '/set-done/';
			var Data = angular.copy($scope.default.data_done);
			
			$http.post(url, $.param(Data), $httpConfig )
				.then(function( response ){
					
					Notify.response( response.data );
					
					if(response.data.status === 1 )
					{		
						$('#pending-get_pending_results').html(response.data.pending);
						$('#encounter-request-modal-done').modal('hide');
						$scope.initialize($scope.data.encounter.id);
						
						if(response.data.redirect)
						{
							let b = document.getElementById("printInvoicePdf");
							b.click();
							//window.location  = response.data.redirect;
						}
					}
					
				}).finally(function(){
					$(".submit").removeAttr("disabled");
				})
		}
	});
};

var action_referrals =  function( $scope, $http ){
	var SELF = this;

	this.form = {
		id: 0,
		speciality: '',
		service: '',
		reason: '',
		acuity:'',
		print_icd_code:'',
		print_diagnosis:'',
		print_extra_diagnosis:'',
		print_services_requested:'',
	}

	this.modal = function(){
		return '#encounter-detail-modal-referrals'
	};
	this.open = function(){
		SELF.clear();
		$(SELF.modal()).modal();
	};
	this.submit= function( event )
	{
		event.preventDefault();
		var Form = $(event.currentTarget);
		var Data = $scope.default.referrals,
			Btn  = $('.submit', Form );
		$(Btn).attr( 'disabled', 'disabled' );
		
		
		$http({
		    method: 'POST',
		    url: '/encounter/referrals/saveFromRequest/' + $scope.data.encounter.id ,
		    data:  $.param(  Data ),
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(response){
			
			Notify.response( response.data );
			
			if( response.data.status )
			{	
				if($scope.default.referrals.id > 0 )
				{
					$scope.data.referrals[$scope.default.referrals.idx] =  response.data.referral ;
					$(SELF.modal()).modal('hide');
				}
				else
				{
					$scope.data.referrals.push( response.data.referral  );
					SELF.clear();
					$(SELF.modal()).modal('hide');
				}
			}
				
			$(Btn).removeAttr( 'disabled' );
		});
	};
	this.clear= function()
	{	
		$scope.default.referrals = {
			idx: -1,
			id: 0,
			speciality: '',
			service: '',
			reason: '',
			acuity:'Routine',	
		};
	};
	this.edit= function( idx )
	{
		var ref            = $scope.data.referrals[idx];
		$scope.default.referrals = {
			idx: idx,
			id: parseInt(ref.id),
			speciality: ref.speciality,
			service: ref.service,
			reason: ref.reason,
			acuity: ref.acuity,
			refer_date: ref.refer_date,
			user_created_nickname: ref.user_created_nickname
		};

		$(SELF.modal()).modal();
	};
	this.delete= function( idx )
	{
		var ele = $scope.data.referrals[idx];
		
		$http.get('/encounter/referrals/delete/'+ ele.id +'/external').then(function(response) {
	        Notify.response( response.data );
	        $scope.data.referrals.splice( idx, 1);
	        $(SELF.modal()).modal('hide');
	    });
	};
	this.getStatus = function( status )
	{				
		return $scope.data.status_referrals[status];
	};
	this.print = function( ref )
	{
		ref.status = ref.status.toString();
		SELF.form = ref;
		
		$('#referr-modal-print').modal();

		var action = '/encounter/referrals/'+ref.id+'/pdf';
	}
	
};

angular
	.module('app_request_encounter', ['angularUtils.directives.dirPagination'] )
	.controller('ctrl_request_encounter', function( $scope , $http){	

		$scope.default = {
			invoice: {},
			data_done: {},
			data_cancel:{}
		}

		

		$scope.initialize = function( Encounter_ID )
		{
			$http.get('/encounter/request/initialize/' + Encounter_ID)
				.then(function( response ){
					
					response.data.results.map(element => element.status = element.status.toString());
					$scope.data = response.data;

					$scope.paginatePreviousCharges = new appPagination({
						$http:$http,
						$scope:$scope,
						url:'/encounter/invoice/search',
						filters: {
							patient_id: response.data.patient.id
						},
						postQuery: function(response, $scope){
							console.log("Response", response);
						}
					});

					$('[ng-model="default.referrals.speciality"]').typeahead({
						source: $scope.data.catalog_specialities,
						minLength: 0,
						autoSelect: true,
						items: 8
					}).on("click", function() {
		                var ev = $.Event("keydown");
		                ev.keyCode = ev.which = 40;
		                $(this).trigger(ev);
		                return true;
				 	});

				 	$('[ng-model="default.referrals.service"]').typeahead({
						source: $scope.data.catalog_refer_services,
						minLength: 0,
						autoSelect: true,
						items: 8
					}).on("click", function() {
		                var ev = $.Event("keydown");
		                ev.keyCode = ev.which = 40;
		                $(this).trigger(ev);
		                return true;
				 	});

				})
		}	
		
		$scope.previous_charges = function()
		{
			$("#encounter-request-modal-previous-charges").modal();
			
			$scope.paginatePreviousCharges.sort ={
				name:'encounter_date',
				type:'DESC'
			};
			$scope.paginatePreviousCharges.getData(1);
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

		$scope.checkDisabled = function( ){

			if( typeof($scope.data) === 'undefined')
				return false;
			
			if(!$scope.data.invoice.total)
				return false;

			if(   !$scope.data.invoice.balance_due )
				return false;
			
			if($scope.data.invoice.auth_balancedue==='On')
				return false;	
			else
				return true;

		}
		$scope.action_results         = new action_results($scope, $http );
		$scope.action_invoice         = new action_invoice($scope, $http );
		$scope.action_encounter       = new action_encounter($scope, $http);
		$scope.action_addendum        = new action_addendum( $scope, $http );
		$scope.action_checkout_cancel = new action_checkout_cancel( $scope, $http );
		$scope.action_referrals 	  = new action_referrals( $scope, $http );
		$scope.ngHelper  			  = new ngHelper();
	});
