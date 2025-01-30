
angular
	.module('app_examinations', [])
	.controller('ctrl_examinations', function( $scope, $http ){
		
		$scope.default = {
			examination: {
				title: '',
				content: ''
			}
		};
		
		$scope.initialize = function( )
		{
			$http.get('/user/examinations/getInfoExaminations').success(function( response ){
				
				$scope.data = {
					examinations : response.myexaminations	
				}
				
				//var position = $scope.data.examinations.indexOf(Item);
				//$scope.data.examinations.splice( position ,1);
				
			});
		}

		$scope.insert = function( Item )
		{
			var Data = angular.copy(Item);

			$http({
			    method: 'POST',
			    url: '/user/examinations/insert',
			    data:  $.param( Data ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
					
				Notify.response( response.data );
				
				if( response.data.status )
				{	
					response.data.item.title_initial 	= Item.title;
					response.data.item.content_initial  = Item.content;
					Item.title   = '';
					Item.content = '';
					$scope.data.examinations.push( response.data.item )
				}	
			});
		};
		
		$scope.update = function( Item )
		{
			var Data = angular.copy( Item );
			
			$http({	
			    method: 'POST',
			    url:  '/user/examinations/update/' + Item.id,
			    data:  $.param( Data ) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				Notify.response( response.data );
				if( response.status )
				{
					Item.title_initial = Data.title;
				}
			});
		}

		$scope.delete = function( Item )
		{
			$http.get('/user/examinations/delete/'+Item.id).success(function( response ){
				
				Notify.response( response );
				
				if(response.status)
				{		
					var position = $scope.data.examinations.indexOf(Item);
					$scope.data.examinations.splice( position ,1);
				}
			});
		};

		$scope.disabled = function( Item )
		{
			if(Item.title_initial!=Item.title) return false;
			if(Item.content_initial!=Item.content) return false;
			return true;
		}
	});