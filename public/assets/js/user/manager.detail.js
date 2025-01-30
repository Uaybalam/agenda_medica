angular
	.module('app_manager_detail', [] )
	.controller('ctrl_manager_detail', function( $scope, $http ){
		
		$scope.initialize = function( user_id )
		{
			$scope.default = {
				user_basic: {},
				user_address: {},
				user_contact_emergency: {},
				user_contact_emergency_other: {},
				user_medic_contact: {}
			}
			$http.get('/user/manager/' + user_id + '/init').then(function( response ){
				$scope.data = response.data;

			});
		}

		$scope.mergeModels = function($objGetters, $objSetters)
		{
			for(var name in $objGetters )
			{	
				if( name === 'idx' ) continue; //prevent idx
				$objSetters[name] = $objGetters[name];
			}
			return true;
		}
		
		$scope.changeZipCode = {
			getUrl:function( zipCode ){
				var params = {
					zipCode: zipCode
				};
				
				return '/location/filter/?'+ $.param(params);
			},
			toUser:function( zipCode ){
				$http.get( $scope.changeZipCode.getUrl(zipCode) ).then(function(response){
					if( response.data.status == 1 )
					{
						$scope.default.user_address.address_city = response.data.location.city;
						$scope.default.user_address.address_state = response.data.location.state_short;
					}
				});
			}
		};

		$scope.action_basic = {
			open:function(){
				
				$scope.default.user_basic = {
					email: $scope.data.user.email,
					digital_signature: $scope.data.user.digital_signature,
					nick_name: $scope.data.user.nick_name,
					date_of_birth: $scope.data.user.date_of_birth,
					names: $scope.data.user.names,
					last_name: $scope.data.user.last_name,
					access_type: $scope.data.user.access_type,
					gender: $scope.data.user.gender,
					marital_status: $scope.data.user.marital_status,
					phone: $scope.data.user.phone,
					medical_information: $scope.data.user.medical_information,
					edit_password: 0,
					password: '',
					status: $scope.data.user.status,
					medic_type: $scope.data.user.medic_type,
					medic_npi: $scope.data.user.medic_npi
				}

				$('#user-manager-modal-edit-basic').modal();
			},
			update:function(){

				$('.submit').attr('disabled','disabled');
				
				var Data = angular.copy( $scope.default.user_basic ),
					url  = '/user/manager/update/' + $scope.data.user.id + '/basic/';
				
				$http.post( url , $.param( Data ), $httpConfig ).then(function(response ){
						
					$scope.mergeModels( $scope.default.user_basic, $scope.data.user );
					
					Notify.response( response.data );
					
					if( response.data.status )
					{	
						$('#user-manager-modal-edit-basic').modal('hide');
					}

					$('.submit').removeAttr('disabled');
		        });
			}
		}

		$scope.action_address = {
			open: function(){

				$scope.default.user_address = {
					address: $scope.data.user.address,
					address_zipcode: $scope.data.user.address_zipcode,
					address_city : $scope.data.user.address_city,
					address_state: $scope.data.user.address_state
				}
				setTimeout(function(){
					$('#user-manager-modal-edit-address').modal();
				}, 1)
				
			},
			update:function(){
				$('.submit').attr('disabled','disabled');

				var Data = angular.copy( $scope.default.user_address ),
					url  = '/user/manager/update/' + $scope.data.user.id + '/address/';
				
				$http.post( url , $.param( Data ), $httpConfig ).then(function(response ){
						
					$scope.mergeModels($scope.default.user_address, $scope.data.user );

					Notify.response( response.data );
					
					if( response.data.status )
					{
						$scope.mergeModels( $scope.default.user_address , $scope.data.user )
						$('#user-manager-modal-edit-address').modal('hide');
					}	

					$('.submit').removeAttr('disabled');
		        });
			}
		}

		$scope.action_primarycontact = {
			open:function(){

				$scope.default.user_primarycontact = {
					emergency_contact_name: $scope.data.user.emergency_contact_name,
					emergency_contact_full_address: $scope.data.user.emergency_contact_full_address,
					emergency_contact_phone: $scope.data.user.emergency_contact_phone,
					emergency_contact_relation: $scope.data.user.emergency_contact_relation,
				}

				$('#user-manager-modal-edit-primarycontact').modal();
			},
			update:function(){

				$('.submit').attr('disabled','disabled');
				
				var Data = angular.copy( $scope.default.user_primarycontact ),
					url  = '/user/manager/update/' + $scope.data.user.id + '/primarycontact/';
				
				$http.post( url , $.param( Data ), $httpConfig ).then(function(response ){
						
					$scope.mergeModels($scope.default.user_primarycontact, $scope.data.user );

					Notify.response( response.data );
					
					if( response.data.status )
					{	
						$('#user-manager-modal-edit-primarycontact').modal('hide');
					}

					$('.submit').removeAttr('disabled');
		        });
			}
		}

		$scope.action_secondarycontact = {
			open:function(){
				
				$scope.default.user_secondarycontact = {
					emergency_contact_other_name: $scope.data.user.emergency_contact_other_name,
					emergency_contact_other_full_address: $scope.data.user.emergency_contact_other_full_address,
					emergency_contact_other_phone: $scope.data.user.emergency_contact_other_phone,
					emergency_contact_other_relation: $scope.data.user.emergency_contact_other_relation,
				}

				$('#user-manager-modal-edit-secondarycontact').modal();
			},
			update:function(){

				$('.submit').attr('disabled','disabled');
				
				var Data = angular.copy( $scope.default.user_secondarycontact ),
					url  = '/user/manager/update/' + $scope.data.user.id + '/secondarycontact/';
				
				$http.post( url , $.param( Data ), $httpConfig ).then(function(response ){
						
					$scope.mergeModels($scope.default.user_secondarycontact, $scope.data.user );

					Notify.response( response.data );
					
					if( response.data.status )
					{	
						$('#user-manager-modal-edit-secondarycontact').modal('hide');
					}

					$('.submit').removeAttr('disabled');
		        });
			}
		}

		$scope.action_doctorcontact = {
			open:function(){
					
				$scope.default.user_doctorcontact = {
					emergency_contact_doctor_name: $scope.data.user.emergency_contact_doctor_name,
					emergency_contact_doctor_address: $scope.data.user.emergency_contact_doctor_address,
					emergency_contact_doctor_phone: $scope.data.user.emergency_contact_doctor_phone,
				}
				
				$('#user-manager-modal-edit-doctorcontact').modal();
			},
			update:function(){

				$('.submit').attr('disabled','disabled');
				
				var Data = angular.copy( $scope.default.user_doctorcontact ),
					url  = '/user/manager/update/' + $scope.data.user.id + '/doctorcontact/';
				
				$http.post( url , $.param( Data ), $httpConfig ).then(function(response ){
						
					$scope.mergeModels($scope.default.user_doctorcontact, $scope.data.user );

					Notify.response( response.data );
					
					if( response.data.status )
					{	
						$('#user-manager-modal-edit-doctorcontact').modal('hide');
					}

					$('.submit').removeAttr('disabled');
		        });
			}
		}

		$scope.activate2f = () => 
		{ 
			var checked = $scope.data.user.active2fa;

		    $.ajax({
		        url: '/user/update2faUser',
		        type:'post',
		        data: {'active2fa': checked, 'user_id': $scope.data.user.id} ,
		        dataType:'json',
		        success:function( response ){
		            if(response.status)
		            {console.log(response.qr);
		                $scope.data.user.qrCodeUrl = response.qr;
		                $scope.data.user.active2fa = 1;
		            }
		            else
		            {
		                $scope.data.user.qrCodeUrl = "";
		                $scope.data.user.active2fa = 0;
		            }
		        }
		    });
		}

	});
