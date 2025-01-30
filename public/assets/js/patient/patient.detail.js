
angular
	.module('ng_patient_detail',[])
	.controller('ctrl_patient_detail',function($scope, $http){
		
		$scope.data ={}
		
		$scope.default = {
			patient_about:{},
			patient_address:{},
			patient_primary_i:{},
			patient_secondary_i:{},
			patient_responsible:{},
			patient_member:{},
			patient_emergency:{}
		};
 
		$scope.initialize = function( $id )
		{
			$http.get('/patient/initialize/'+ $id ).then(function(response) {
				$scope.data             = response.data;
				$scope.data.patient.age = get_age_patient( response.data.patient.date_of_birth );
				
		        setTimeout(function(){

		        	$('[ng-model="default.patient_about.how_found_us"]').typeahead({
						source: $scope.data.settings_how_found_us,
						minLength: 0,
						autoSelect: true,
						items: 100
					}).on("click", function() {
				        var ev = $.Event("keydown");
				        ev.keyCode = ev.which = 40;
				        $(this).trigger(ev);
				        return true;
				 	});

				 	$('[ng-model="default.patient_about.language"]').tagsinput({
						typeahead: {
							source: response.data.settings_languages ,
							afterSelect: function(val) { 
								this.$element.val(""); 
							},
						},
						tagClass: function(item) {	
							return 'label label-info';
						},
						freeInput:true
					});

					$('[ng-model="default.preventions.allergies"]').tagsinput({
						typeahead: {
							source: response.data.catalog_allergies ,
							afterSelect: function(val) { 
								this.$element.val(""); 
							},
						},
						tagClass: function(item) {	
							if(item==='NKDA')
							{
								return 'label label-success';
							}
							else
							{
								return 'label label-danger';
							}
							
						}
					});

					PrevDiv = $('[ng-model="default.preventions.allergies"]').prev();
					
					$('input', PrevDiv).on("change", function( currentValue ){
						
						var value = this.value;
						if(value)
						{
							this.value = '';
							$('[ng-model="default.preventions.allergies"]').tagsinput('add',value);
						}
					});

					

		        }, 1 );
				
			
				for(i=0; i< $scope.data.warnings.length; i++)
				{
					$scope.data.warnings[i].lapse_time = moment($scope.data.warnings[i].create_at, '"YYYY-MM-DD hh:mm:ss"').fromNow();
				}
		    }).finally(function(){
		    	setTimeout(function(){
		    		$('#loading').fadeOut('fast');
		    	},1);
		    });
		};
		
		$scope.mergeModels = function($objGetters, $objSetters)
		{
			for(var name in $objGetters )
			{	
				if( name === 'idx' ) continue; //prevent idx
				$objSetters[name] = $objGetters[name];
			}
			return true;
		}	
		
		$scope.mergePatient = function( patient )
		{
			for(PatientColumn in patient)
			{
				if(typeof $scope.data.patient[PatientColumn] !== undefined) 
				{
					$scope.data.patient[PatientColumn] = patient[PatientColumn];
				}
			}
		}

		$scope.changeZipCode = {
			getUrl:function( zipCode ){
				var params = {
					zipCode: zipCode
				};
				
				return '/location/filter/?'+ $.param(params);
			},
			toPatient:function( zipCode ){
				$http.get( $scope.changeZipCode.getUrl(zipCode) ).then(function(response){
					if( response.data.status == 1 )
					{
						$scope.default.patient_address.address_city = response.data.location.city;
						$scope.default.patient_address.address_state = response.data.location.state_short;
					}
				});
			},
			toResponsible:function( zipCode ){
				$http.get( $scope.changeZipCode.getUrl(zipCode) ).then(function(response){
					if( response.data.status == 1 )
					{
						$scope.default.patient_responsible.responsible_address_city  = response.data.location.city;
						$scope.default.patient_responsible.responsible_address_state = response.data.location.state_short;
					}
				});
			},
			toEmergency:function( zipCode ){
				$http.get( $scope.changeZipCode.getUrl(zipCode) ).then(function(response){
					if( response.data.status == 1 )
					{
						$scope.default.patient_emergency.emergency_address_city = response.data.location.city;
						$scope.default.patient_emergency.emergency_address_state = response.data.location.state_short;
					}
				});
			}
		};
		
		$scope.action_about = {
			open:function(element){
				
				$scope.default.patient_about = {
					name: $scope.data.patient.name,
					middle_name: $scope.data.patient.middle_name,
					last_name: $scope.data.patient.last_name,
					gender: $scope.data.patient.gender,
					phone: $scope.data.patient.phone,
					phone_memo: $scope.data.patient.phone_memo,
					phone_alt: $scope.data.patient.phone_alt,
					phone_alt_memo: $scope.data.patient.phone_alt_memo,
					date_of_birth: $scope.data.patient.date_of_birth,
					how_found_us: $scope.data.patient.how_found_us,
					email: $scope.data.patient.email,
					ethnicity: $scope.data.patient.ethnicity,
					blood_type: $scope.data.patient.blood_type,
					language: $scope.data.patient.language,
					interpreter_needed: $scope.data.patient.interpreter_needed,
					advanced_directive_offered: $scope.data.patient.advanced_directive_offered,
					advanced_directive_taken: $scope.data.patient.advanced_directive_taken,
					discount_type: $scope.data.patient.discount_type,
					marital_status: $scope.data.patient.marital_status
				};
				console.log($scope.data.patient.date_of_birth);
				$('[ng-model="default.patient_about.language"]').tagsinput('add', $scope.default.patient_about.language );
				$('[ng-model="default.patient_about.date_of_birth"]').datepicker('setDate',$scope.data.patient.date_of_birth)
				$(element).modal();
			},
			submit:function(){

				$('.submit').attr('disabled','disabled');
				var Data = angular.copy($scope.default.patient_about);
				Data.id  = $scope.data.patient.id;
				
				$http
					.post('/patient/update/about', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){
						Notify.response(response.data );
						if(response.data.status)
						{
									
							$scope.data.patient.age   = get_age_patient( $scope.default.patient_about.date_of_birth );
							
							$scope.mergeModels($scope.default.patient_about, $scope.data.patient )
							$('#patient-detail-modal-patient-detail-about').modal('hide');
						}
						$('.submit').removeAttr('disabled');
					});
			}
		};
		
		$scope.action_address = {
			open:function(){
				
				$scope.default.patient_address = {
					address: $scope.data.patient.address,
					address_zipcode: $scope.data.patient.address_zipcode,
					address_city: $scope.data.patient.address_city,
					address_state: $scope.data.patient.address_state
				};	
				$('#patient-detail-modal-patient-detail-address').modal();
			},
			submit:function(){
				$('.submit').attr('disabled','disabled');
				var Data = angular.copy($scope.default.patient_address);
				Data.id  = $scope.data.patient.id;
				
				$http
					.post('/patient/update/address', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){
						Notify.response(response.data );
						if(response.data.status)
						{	
							$scope.mergeModels($scope.default.patient_address, $scope.data.patient )
							$('#patient-detail-modal-patient-detail-address').modal('hide');
						}
						$('.submit').removeAttr('disabled');	
					});


			}
		};
		
		$scope.action_insurance_primary = {
			open: function(){
				$scope.default.patient_primary_i = {
					insurance_primary_status: $scope.data.patient.insurance_primary_status,
					insurance_primary_plan_name: String($scope.data.patient.insurance_primary_plan_name),
					insurance_primary_identify: $scope.data.patient.insurance_primary_identify,
					insurance_primary_notes: $scope.data.patient.insurance_primary_notes
				};
				
				$('#patient-detail-modal-patient-detail-insurance_primary').modal();
			},
			submit: function(){

				var insurance_string = $('[ng-model="default.patient_primary_i.insurance_primary_plan_name"] option:selected').text();
						
				$('.submit').attr('disabled','disabled');
				var Data = angular.copy($scope.default.patient_primary_i);
				Data.id  = $scope.data.patient.id;
				
				$http
					.post('/patient/update/insurance_primary', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){
						Notify.response(response.data );
						if(response.data.status)
						{	
							$scope.mergeModels($scope.default.patient_primary_i, $scope.data.patient )
							$scope.data.patient.insurance_primary_string = insurance_string;
							$scope.data.patient.insurance_primary_status = response.data.new_status;
							$('#patient-detail-modal-patient-detail-insurance_primary').modal('hide');
						}
						$('.submit').removeAttr('disabled');	
					});
			},
			toggle_status:function( PreStatus ){
				
				if(PreStatus == $scope.data.patient.insurance_primary_status)
				{	
					return false;
				}

				var Data = {
					id:  $scope.data.patient.id,
					column: 'insurance_primary_status'
				}

				$http
					.post('/patient/update/insurance_status', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){
						Notify.response(response.data );
						$scope.data.patient.insurance_primary_status = response.data.patient.insurance_primary_status;
						
					});
			}
		};
		
		$scope.action_insurance_secondary = {
			open: function(){
				$scope.default.patient_secondary_i = {
					insurance_secondary_status: $scope.data.patient.insurance_secondary_status,
					insurance_secondary_plan_name: $scope.data.patient.insurance_secondary_plan_name,
					insurance_secondary_identify: $scope.data.patient.insurance_secondary_identify,
					insurance_secondary_notes: $scope.data.patient.insurance_secondary_notes
				};

				$('#patient-detail-modal-patient-detail-insurance_secondary').modal();
			},
			submit: function(){

				var insurance_string = $('[ng-model="default.patient_secondary_i.insurance_secondary_plan_name"] option:selected').text();
						
				$('.submit').attr('disabled','disabled');

				var Data = angular.copy($scope.default.patient_secondary_i);
				
				Data.id  = $scope.data.patient.id;
				
				$http
					.post('/patient/update/insurance_secondary', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){
						Notify.response(response.data );
						if(response.data.status)
						{		
							$scope.mergeModels($scope.default.patient_secondary_i, $scope.data.patient )
							$scope.data.patient.insurance_secondary_status = response.data.new_status;
							$('#patient-detail-modal-patient-detail-insurance_secondary').modal('hide');
						}
						$('.submit').removeAttr('disabled');	
					});
			},		
			toggle_status:function( PreStatus ){
				
				if(PreStatus == $scope.data.patient.insurance_secondary_status)
				{	
					return false;
				}
				var Data = {
					id:  $scope.data.patient.id,
					column: 'insurance_secondary_status'
				}
				
				$http
					.post('/patient/update/insurance_status', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){
						Notify.response(response.data );
						$scope.data.patient.insurance_secondary_status = response.data.patient.insurance_secondary_status;
					});
			}
		};
		
		$scope.action_member = {
			open: function(){
				$scope.default.patient_member = {
					membership_name: $scope.data.patient.membership_name,
					membership_type: $scope.data.patient.membership_type,
					membership_date: $scope.data.patient.membership_date,
					membership_notes: $scope.data.patient.membership_notes
				};
				$('#patient-detail-modal-patient-detail-member').modal();
			},
			submit: function(){

				$('.submit').attr('disabled','disabled');

				var Data = angular.copy($scope.default.patient_member);
				
				Data.id  = $scope.data.patient.id;
				
				$http
					.post('/patient/update/member', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){

						Notify.response(response.data );
						if(response.data.status)
						{	
							$scope.mergeModels($scope.default.patient_member, $scope.data.patient )
							
							$('#patient-detail-modal-patient-detail-member').modal('hide');
						}

						$('.submit').removeAttr('disabled');	
					});
			}
		}

		$scope.action_responsible = {
			changeSelf:function( value ){

				if(  value  === 'Yes' ) 
				{
					$scope.default.patient_responsible.responsible_self = 'Yes';
					$scope.default.patient_responsible.responsible_name = 'Self';
				}
				else
				{
					$scope.default.patient_responsible.responsible_self = 'No'
				}
				
			},
			open: function(){
				$scope.default.patient_responsible = {
					responsible_name: $scope.data.patient.responsible_name,
					responsible_middle_name: $scope.data.patient.responsible_middle_name,
					responsible_last_name: $scope.data.patient.responsible_last_name,
					responsible_gender: $scope.data.patient.responsible_gender,
					responsible_phone: $scope.data.patient.responsible_phone,
					responsible_phone_alt: $scope.data.patient.responsible_phone_alt,
					responsible_address: $scope.data.patient.responsible_address,
					responsible_address_zipcode: $scope.data.patient.responsible_address_zipcode,
					responsible_address_city: $scope.data.patient.responsible_address_city,
					responsible_address_state: $scope.data.patient.responsible_address_state,
					responsible_relationship: $scope.data.patient.responsible_relationship,
					responsible_self: $scope.data.patient.responsible_self
				};

				$('#patient-detail-modal-patient-detail-responsible').modal();
			},
			submit: function(){

				$('.submit').attr('disabled','disabled');

				var Data = angular.copy($scope.default.patient_responsible);
				
				Data.id  = $scope.data.patient.id;
				
				$http
					.post('/patient/update/responsible', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){

						Notify.response(response.data );
						if(response.data.status)
						{		
							$scope.mergeModels($scope.default.patient_responsible, $scope.data.patient )
							$('#patient-detail-modal-patient-detail-responsible').modal('hide');
						}

						$('.submit').removeAttr('disabled');	
					});
			},
			toggle_self:function( selfValue )
			{	
				if( selfValue === $scope.data.patient.responsible_self)
				{
					return false;
				}

				var Data = {
					id:  $scope.data.patient.id,
					column: 'responsible_self'
				}

				$http
					.post('/patient/update/responsible_self', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){
						Notify.response(response.data );
						if(response.data.status )
						{
							$scope.data.patient.responsible_self = selfValue;
							$scope.mergePatient( response.data.patient );
						}

					});
			}
		}

		$scope.action_emergency = {
			open: function(){
				$scope.default.patient_emergency = {
					emergency_name: $scope.data.patient.emergency_name,
					emergency_middle_name: $scope.data.patient.emergency_middle_name,
					emergency_last_name: $scope.data.patient.emergency_last_name,
					emergency_gender: $scope.data.patient.emergency_gender,
					emergency_phone: $scope.data.patient.emergency_phone,
					emergency_phone_alt: $scope.data.patient.emergency_phone_alt,
					emergency_address: $scope.data.patient.emergency_address,
					emergency_address_zipcode : $scope.data.patient.emergency_address_zipcode,
					emergency_address_city: $scope.data.patient.emergency_address_city,
					emergency_address_state: $scope.data.patient.emergency_address_state,
					emergency_relationship: $scope.data.patient.emergency_relationship,
				};
				
				$('#patient-detail-modal-patient-detail-emergency').modal();
			},
			submit: function(){

				$('.submit').attr('disabled','disabled');

				var Data = angular.copy($scope.default.patient_emergency);
				
				Data.id  = $scope.data.patient.id;
				
				$http
					.post('/patient/update/emergency', $.param( Data ) ,{headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}})
					.then( function(response){

						Notify.response(response.data );
						if(response.data.status)
						{		
							$scope.mergeModels($scope.default.patient_emergency, $scope.data.patient )
							$('#patient-detail-modal-patient-detail-emergency').modal('hide');
						}

						$('.submit').removeAttr('disabled');	
					});
			}
		}

		$scope.action_warning = {
			open: function(){
				
				$scope.default.warning = {
					description:'',
					patient_id: $scope.data.patient.id,
					request_reply: 0
				};
					
				$('#patient-warnings-modal-patient-warning-create').modal();
			},
			submit: function(){
				
				var add_warning;
				
				$('.submit').attr('disabled','disabled');
				
				$http({
				    method: 'POST',
				    url: '/patient/warning/create/',
				    data:  $.param( $scope.default.warning ) ,
				    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					
					Notify.response(response.data );
					
					if(response.data.status)
					{		
						add_warning            = response.data.warning;
						add_warning.lapse_time = moment(add_warning.create_at, '"YYYY-MM-DD hh:mm:ss"').fromNow();
						
						$scope.data.warnings.push( response.data.warning );

						$('#patient-warnings-modal-patient-warning-create').modal('hide');
					};

					$('.submit').removeAttr('disabled');
				});
			},
			remove: function( element, index ){
				
				$http.get('/patient/warning/remove/'+ element.id).then(function(response) {
			        Notify.response(response.data );
					element.update_at   = response.data.warning.update_at;
					element.user_remove = response.data.warning.user_remove;
					element.status      = "1";
			    });	
			},
			log: function(){
				$('#patient-warnings-modal-patient-warning-log').modal();
			}
		}

		$scope.action_preventions ={
			open:function()
			{

				$scope.default.preventions = {
					allergies: $scope.data.patient.prevention_allergies,
					alcohol: $scope.data.patient.prevention_alcohol,
					drugs: $scope.data.patient.prevention_drugs,
					tobacco: $scope.data.patient.prevention_tobacco
				};

				if($scope.default.preventions.allergies!='')
				{	
					$('[ng-model="default.preventions.allergies"]').tagsinput('add',$scope.default.preventions.allergies);
				};
				$('#patient-detail-modal-preventions').modal();
			},
			submit:function( event )
			{

				event.preventDefault();

				var Form = $(event.currentTarget);	
				var Data = $.param( $scope.default.preventions ),
					Btn  = $('.submit' );
				$(Btn).attr( 'disabled', 'disabled' );
				
				$http({
				    method: 'POST',
				    url: '/preventions/update/' + $scope.data.patient.id ,
				    data:  Data ,
				    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					
					Notify.response(response.data );

					if(response.data.status === 1 )
					{		
						$scope.data.patient = response.data.patient;
						$('#patient-detail-modal-preventions').modal('hide');
						$(Btn).removeAttr( 'disabled' );
					}

				});
			},
			arr_allergies:function()
			{

				if($scope.data.patient === undefined)
				{
					return [];
				}
				
				if($scope.data.patient.prevention_allergies!='' && $scope.data.patient.prevention_allergies!=null)
				{	
					return $scope.data.patient.prevention_allergies.split(',');
				}
				
				return [];
			}
		}

		
		
		$scope.ngHelper = new ngHelper($scope);

	});
