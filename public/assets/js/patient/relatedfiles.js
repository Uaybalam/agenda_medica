

var app = angular.module('app_relatedfiles', ['angularUtils.directives.dirPagination']);

app.controller('ctrl_relatedfiles',function( $http, $scope, $filter){

	$scope.filter = {
	}

	$scope.default = {
		file: {}
	};

	$scope.randomID = 0;

	var currentDate = new Date().getTime();

	$scope.appPagination = new appPagination({
		$http:$http,
		$scope:$scope,
		url:'/patient/related-files/search',
		filters: $scope.filter,
		postQuery:function(response,$scope){
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	$scope.appPagination.sort['name'] = 'created_at';
	$scope.appPagination.sort['type'] = 'DESC';
	
	$scope.appPagination.getData(1);
	
	$scope.ngHelper = new ngHelper($scope);

	$scope.open = function( file ){
		$scope.randomID     = Math.floor(Math.random() * 99999 );	
		$scope.default.file = file;
		$('#patient-relatedfiles-modal-delete-files').modal();
	}

	$scope.openPreview = function( file ){
		$scope.randomID     = Math.floor(Math.random() * 99999 );	
		$scope.default.file = file;
		$('#patient-relatedfiles-modal-preview-files').modal();
	}
	
	$scope.confirmDelete = function( $event )
	{
		$event.preventDefault();
		
		var Data = $.param( $scope.default.file );
		
		$('.submit').attr('submit','submit');
		
		$http.post('/patient/related-files/remove', Data ,$httpConfig)
			.then( function(response){

				Notify.response( response.data );

				if(response.data.status)
				{
					$('#patient-relatedfiles-modal-delete-files').modal('hide');
					$scope.appPagination.getData(1);
				}

				$('.submit').removeAttr('submit');
			});
	}

});