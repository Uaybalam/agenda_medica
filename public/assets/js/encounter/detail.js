
angular
	.module('ng_encounter_detail', ['bootstrap3-typeahead'] )
	.controller('ctrl_encounter_detail', function($scope, $http , $filter ){
		
		$scope.default = { 
			billing: {
				insurance_plan: '',
				insurance_id: '',
				pin: ''
			},
			filter_compare: '_diagnosis',
			encounter: {},
			encounter_child: {},
			statement:{
				bmi_text:[
					{'Bajo peso':'<18.5'},
					{'Normal':'>=18.5 && <=25'},
					{'Sobrepeso':'>=25 && <=30'},
					{'Obesidad':'>30'}
				],
				bmi_class:[
					{'text-danger':'<18.5'},
					{'text-success':'>=18.5 && <=25'},
					{'text-warning':'>=25 && <=30'},
					{'text-danger':'>=31'}
				],
				bp_sys_class:[
					{'text-warning':'<=90'},
					{'text-success':'>90 && <=120'},
					{'text-info':'>120 && <140'},
					{'text-warning':'>=140 && <150'},
					{'text-danger':'>=150'}
				],
				bp_sys_text:[
					{'Baja':'<=90'},
					{'Normal':'>90 && <=120'},
					{'Prehipertensión':'>120 && <140'},
					{'Hip. Etapa 1':'>=140 && <150'},
					{'Hip. Etapa 2':'>=150 && <180'},
					{'**Crisis hipertensiva**':'>=180'}
				],
				bp_dia_class:[
					{'text-warning':'<=60'},
					{'text-success':'>60 && <=80'},
					{'text-info':'>80 && <90'},
					{'text-warning':'>=90 && <100'},
					{'text-danger':'>=100'}
				],
				bp_dia_text:[
					{'Low':'<=60'},
					{'Normal':'>60 && <=80'},
					{'Prehipertensión':'>80 && <90'},
					{'Hip. Etapa 1':'>=90 && <100'},
					{'Hip. Etapa 2':'>=100 && <=110'},
					{'**Crisis hipertensiva**':'>110'}
				],
				temp_class:[
					{'text-warning':'>0 && <=95.8'},
					{'text-success':'>=95.9 && <=99.5'},
					{'text-danger':'>=99.6'}
				],
				temp_text:[
					{'Hipotermia':'>0 && <95.9'},
					{'Normal':'>=95.9 && <=99.5'},
					{'Fiebre':'>=99.6'}
				]
			}
		};

		$scope.initialize = function( encounter_id )
		{	
			
			$scope.activity  = {};
			$scope.vitals    = {};
			$scope.diagnosis = {};
			$scope.physical  = {};
			
			$http.get('/encounter/init/'+ encounter_id )
				.then(function(response) {

			        $scope.data = response.data;

					$scope.data.patient.age   = get_age_patient( response.data.patient.date_of_birth );


			   		$http.get('/encounter/activity/'+ $scope.data.encounter.id ).then(function(response) {
						$scope.activity = response.data.encounter_activity;
						$scope.activityTranslate = response.data.catalog_activity;

						$scope.vitals    = response.data.encounter_activity.filter(element => element.comments == "encounter_vitals_update");
						$scope.diagnosis = response.data.encounter_activity.filter(element => element.comments == "encounter_diagnosis_add");
						$scope.physical  = response.data.encounter_activity.filter(element => element.comments == "encounter_physicalexam_add");
					});
			        
		   		 }).finally(function(){
		   		 	setTimeout(function(){
						
						$('[ng-model="default.medication.title"]').typeahead({
							source: $scope.data.catalog_medications,
							minLength: 0,
							autoSelect: true,
							items: 8
						}).on("click", function() {
			                var ev = $.Event("keydown");
			                ev.keyCode = ev.which = 40;
			                $(this).trigger(ev);
			                return true;
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
						
					 	$('[ng-model="default.results.title"]').typeahead({
							source: $scope.data.catalog_results,
							minLength: 0,
							autoSelect: true,
							items: 8
						}).on("click", function() {
			                var ev = $.Event("keydown");
			                ev.keyCode = ev.which = 40;
			                $(this).trigger(ev);
			                return true;
					 	});

						$('[ng-model="default.encounter.procedure_patient_education"]')
							.select2();

						$('[ng-model="default.encounter_child.development_options"]')
							.select2({allowClear: true});
							
						$('[ng-model="default.encounter_child.ethnic_code"]')
							.select2();

						$('[ng-model="default.encounter_child.development_plan"]')
							.select2();

						$('[ng-model="default.encounter_child.educations"]')
							.select2();

						//	
					 	$('#loading').fadeOut('fast');
					 	$('[data-toggle="tooltip"]').tooltip();
			        }, 1);
		   		 });

		   		$http.get('/encounter/activity/'+ $scope.data.encounter.id ).then(function(response) {
					$scope.activity = response.data.encounter_activity;
					$scope.activityTranslate = response.data.catalog_activity;

					$(modal_name).modal();
				});
				
			setInterval(function(){ 
				$.get('/encounter/refresh/' + encounter_id ); 
			}, 6000 );
		}
		
		if (typeof action_vitals === "function") {
			$scope.action_vitals        = new action_vitals( $scope, $http);
		}
		if (typeof action_procedure === "function") {
			$scope.action_procedure        = new action_procedure( $scope, $http);
		}
		if (typeof action_diagnosis === "function") {
			$scope.action_diagnosis        = new action_diagnosis( $scope, $http);
		}
		if (typeof action_medication === "function") {
			$scope.action_medication        = new action_medication( $scope, $http);
		}
		if (typeof action_referrals === "function") {
			$scope.action_referrals        = new action_referrals( $scope, $http);
		}
		if (typeof action_physicalexam === "function") {
			$scope.action_physicalexam        = new action_physicalexam( $scope, $http, $filter);
		}
		if (typeof action_results === "function") {
			
			$scope.action_results        = new action_results( $scope, $http);
		}
		if (typeof action_sign === "function") {
			$scope.action_sign        = new action_sign( $scope, $http);
		}
		if (typeof action_addendum === "function") {
			$scope.action_addendum        = new action_addendum( $scope, $http);
		}
		if (typeof action_illness === "function") {
			$scope.action_illness        = new action_illness( $scope, $http);
		}
		if (typeof action_childphysical === "function") {
			$scope.action_childphysical        = new action_childphysical( $scope, $http);
		}
		if (typeof action_education === "function") {
			$scope.action_education        = new action_education( $scope, $http);
		}
		
		$scope.dinamicValues = function( defaultValue, statements )
		{
			if(	statements === undefined || 
				defaultValue=== undefined || 
				defaultValue==='' || 
				defaultValue===0)
			{
				return '';
			}
			
			for(var i = 0; i< statements.length; i ++)
			{
				for(var c in statements[i])
				{
					var str 	= defaultValue + " " +statements[i][c].replace("&&","&& " + defaultValue);
					var strStm 	= "if("+str+" ) {  return  '"+c+"' } else { return ''}";
					var strContent = new Function(strStm);
					var value = strContent();
					if(value)
					{	
						return value;
					}
				}
			}
		};

		$scope.open_activity = function( modal_name )
		{
			$http.get('/encounter/activity/'+ $scope.data.encounter.id ).then(function(response) {
				$scope.activity = response.data.encounter_activity;
				$scope.activityTranslate = response.data.catalog_activity;
				$(modal_name).modal();
			});
		}

		$scope.open_compare = function()
		{

			if($scope.default.filter_compare!= '' )
			{
				//refresh
				$scope.filter_compare();
			}
			$('#encounter-compare-modal-compare').modal();
		}

		$scope.filter_compare = function()
		{
			$http.get('/encounter/compare/'+ $scope.data.encounter.patient_id +'/'+$scope.data.encounter.id + '/' + $scope.default.filter_compare ).then(function(response) {
				setTimeout(function(){$('#encounter_compare_result').html(response.data)}, 1);
			});
		}

		$scope.submitCreateBilling = function( event ){
			event.preventDefault();
			var Form = $(event.currentTarget);
			
			var Data = $scope.default.billing,
				Btn  = $('.submit', Form );
			$(Btn).attr( 'disabled', 'disabled' );
			
			$http({
			    method: 'POST',
			    url: $(Form).attr('action') + '/' + $scope.data.encounter.id ,
			    data:  $.param(Data) ,
			    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function(response){
				
				Notify.response( response.data );
				if(response.data.status === 1 )
				{	
					$('#billing-modal-create').modal('hide');
					$scope.data.encounter.has_insurance = 1;
				}
				
				$(Btn).removeAttr( 'disabled' );
			});
		}
});
