
angular
	.module('app_settings',['angularUtils.directives.dirPagination'] )
	.controller('ctrl_settings', function( $scope, $http ){
		
		$scope.data = {
			settings:[]
		};
 
		$scope.initialize = function(){

			$http.get('/settings/init').success(function( response ){
				$scope.data = response;
			}).finally(function(){
				
				var Settings = $scope.data.settings;
				var mySetting;
				
				for(name in Settings)
				{
					//console.log("group",group);
					mySetting = Settings[name];
					mySetting.name = name;
					
					mySetting.appPagination = new appPagination({
						$http:$http,
						$scope:$scope,
						url:'/settings/'+ name + '/search',
						filters: {},
						postQuery:function( response, $scope, $self ){
							
							for( i=0; i < response.result_data.length ; i++)
							{
								response.result_data[i].name_initial     = response.result_data[i].name; 
								response.result_data[i].fullname_initial = response.result_data[i].fullname; 
							}

							$self.result_data = response.result_data;
						}
					});
					
					mySetting.appPagination.itemsPerPage = 5;

					mySetting.appPagination.getData(1);
				}
				
				setTimeout(function(){
					$('[data-toggle="tooltip"]').tooltip();
				},1 );

			});
		};

		$scope.action_settings = new function(){

			var $self = this;

			this.insert = function( settingManagment )
			{

				if(!settingManagment.new_name)
				{
					return false;
				}
				
				var Data = {
					name:settingManagment.new_name
				};
				
				$http({
				    method: 'POST',
				    url: '/settings/insert/'+settingManagment.name,
				    data:  $.param( Data ) ,
				    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					
					Notify.response( response.data );
					
					if( response.data.status )
					{	
						settingManagment.new_name = '';
						settingManagment.appPagination.getData(1);
						document.getElementById("setting-name "+settingManagment.name).focus();
					}

					setTimeout(function(){
						$('[data-toggle="tooltip"]').tooltip();
					});

				});	
			}
			
			this.update = function(  setting, Input )
			{
				if(!Input.name)
				{
					return false;
				}

				var Data = Input;

				$http({
				    method: 'POST',
				    url: '/settings/update/' + setting.name+'/'+Input.id,
				    data:  $.param( Data ) ,
				    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					Notify.response( response.data );
					
					if( response.data.status )
					{
						setting.appPagination.getData(setting.appPagination.currentPage);
					}
				});
			}

			this.delete = function( setting, Input )
			{
				$http.delete('/settings/delete/' + setting.name+'/'+Input.id).success(function( response ){
					
					if(response.status)
					{	
						setting.appPagination.getData(setting.appPagination.currentPage);
					}

				});
			}

		}
		

	});


