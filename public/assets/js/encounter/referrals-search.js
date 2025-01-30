angular
	.module('ng_referrals', ['angularUtils.directives.dirPagination'] )
	.controller('ctrl_referrals', function($scope, $http  ){
		
		$scope.filter = {
			patient:'',
			insurance:'',
			date_signature:'',
			reason:'',
			acuity:'',
			webticket:'',
			specialty:'',
			status:'ALL',
			patient_id:'',
			comments: ''
		};

		$scope.default = {
			referral: null,
		}

		$scope.appPagination = new appPagination({
			$http:$http,
			$scope:$scope,
			url:'/encounter/referrals/search',
			filters: $scope.filter,
			postQuery:function(response,$scope){
				$('[data-toggle="tooltip"]').tooltip();
			}
		});

		$scope.appPagination.sort = {
			name:'date_of_signature',
			type:'desc',
		};
		
		$scope.initialize = function( availableStatus ){
			
			$scope.availableStatus = availableStatus;
			$scope.appPagination.getData(1);

			setTimeout(function(){
				var ajaxPatient = {
					url: "/patient/filter",
					dataType: 'json',
					delay: 200,
					data: function (params) {
						return {
							q: params.term, // search term
							page: params.page
						};
					},
					processResults: function (data, params) {
						console.log("processResults", data);
						params.page = params.page || 1;
						return {
							results: data.items,
							pagination: {
								more: (params.page * 30) < data.total_count
							}
						};
					},
					cache: true
				}

				$("#search-patient").select2({
					ajax: ajaxPatient,
					escapeMarkup: function (markup) { 
						return markup; 
					},
					minimumInputLength: 1,
					templateResult: function( data ){
						if(typeof data.name === 'undefined')
						{
							return '';
						}
						else
						{
							var template = '';
							template+= data.name+' '+data.middle_name+' '+data.last_name+': '+data.date_of_birth;
							return template;
						}
					},
					templateSelection: function( data ){
						if(  data.text.trim() === '' && 
							typeof data.name== 'string' &&
							typeof data.middle_name== 'string' &&
							typeof data.last_name== 'string' &&
							typeof data.date_of_birth== 'string' )
						{
							var template = data.name+' '+data.middle_name+' '+data.last_name+': '+data.date_of_birth;
							return template;
						}
						else
						{
							return "Buscar paciente";
						}
					},
					"language": {
				       "noResults": () => {
				           return "Sin resultados";
				       },
				       inputTooShort: function () {
					    	return "Ingrese 1 o más caracteres";
					  	}
				   },
					dropdownParent: $("#referr-modal-create"),
					placeholder: "Select a state",
				});
			
			})
		};
		
		var action_referral = function(){
			
			var $self = this;

			this.currentItem = undefined;
			
			this.modalName   = "#encounter-referrals-modal-detail";
			
			this.openModalCreate = function(){
				$scope.default.referral                 = {};
				$scope.default.referral['status']       = '6';
				$scope.default.referral['patient_id']   = 0;
				$scope.default.referral['encounter_id'] = 0;
				$('#referr-modal-create').modal();
			};

			this.openModal = function( setItem ){
				
				$self.currentItem              = setItem;
				$scope.default.referral        = angular.copy( setItem );
				$scope.default.referral.status = $scope.default.referral.status.toString();

				$("[ng-model='default.referral.refer_date']").datepicker('update',setItem.refer_date );
				$($self.modalName).modal();
			};

			this.submit = function( $event ){
				$event.preventDefault();

				var Form = $($event.currentTarget);
				
				var Data = $scope.default.referral,
					Btn  = $('.submit', Form );
				$(Btn).attr( 'disabled', 'disabled' );
				
				$http({
				    method: 'POST',
				    url:  '/encounter/referrals/'+Data.id+'/update/' ,
				    data:  $.param(Data) ,
				    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					
					Notify.response( response.data );
					if(response.data.status === 1 )
					{	
						for( e in response.data.referral )
                        {   
                            $self.currentItem[e] = response.data.referral[e];
                        }
                        
						$($self.modalName).modal('hide');
					}
					
					$(Btn).removeAttr( 'disabled' );
				});
			};

			this.submitCreate = function( $event ){
				$event.preventDefault();
				
				var Form = $($event.currentTarget);
				var Data = angular.copy($scope.default.referral);

				$('.disabled').attr( 'disabled', 'disabled' );
				
				$http({
				    method: 'POST',
				    url:  '/encounter/referrals/'+Data.patient_id+'/create/' ,
				    data:  $.param(Data) ,
				    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					
					Notify.response( response.data );

					if(response.data.status === 1 )
					{	
						$('#referr-modal-create').modal('hide');
						$scope.appPagination.getData(1);
					}

					$('.submit').removeAttr( 'disabled' );
					
				});
			};

			this.deleteExternal = function(){

				var Data = $scope.default.referral;
				
				$('.submit').attr( 'disabled', 'disabled' );
				
				$http({
				    method: 'POST',
				    url:  '/encounter/referrals/delete/'+Data.id+'/external/' ,
				    data:  $.param(Data) ,
				    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					
					Notify.response( response.data );

					if(response.data.status == 1 )
					{	
						$($self.modalName).modal('hide');
						$scope.appPagination.getData(1);
					}
					
					$('.submit').removeAttr( 'disabled' );
				});
			};
		}

		$scope.action_referral = new action_referral();
		
	});