
<input type="hidden" id="prevent-default-loading" value="1" />


<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-4"> <label >Patient information</label></div>
					<div class="col-sm-8 text-right">
						<a title="Patient chart" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs" ng-href="/patient/chart/{{data.patient.id}}" > <i class="icon-folder-plus"></i></a>
						<a title="Patient detail" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs" ng-href="/patient/detail/{{data.patient.id}}" ><i class="fa fa-user "></i></a>
					</div>
				</div>
			</div>
			<div class="panel-body body-normal">
				<div class="row">
					<div class="col-md-6">
						<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
							<tbody>
								<tr>
									<th class="col-xs-3 col-md-3">Patient id</th>
									<td class="col-xs-9 col-md-9">{{ data.patient.id }}</td>
								</tr>
								<tr>
									<th class="col-xs-3 col-md-3">Patient</th>
									<td class="col-xs-9 col-md-9">{{ data.patient.name +' '+data.patient.middle_name+' '+data.patient.last_name}}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<table ng-cloak  class="table table-hover-app table-condensend table-bordered">
							<tbody>
								<tr>
									<th class="col-xs-3 col-md-3">Age (DOB)</th>
									<td class="col-xs-9 col-md-9">{{ data.patient.age }} <span class="text-opacity">({{data.patient.date_of_birth}})<span> </td>
								</tr>
								<tr>
									<th class="col-xs-3 col-md-3">Allergies</th>
									<td class="col-xs-9 col-md-9 ">  {{ data.patient.prevention_allergies }} </td>
								</tr>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row"  ng-cloak>
	<div ng-show="data.encounter.status==1" class="col-md-3">
		<div class="panel panel-default custom-widget">
			<div class="panel-heading text-center">
				<b>Actions encounter</b>
			</div>
			<div class="panel-body body-normal">
				<button ng-click="action_vitals.open()" class="btn btn-success" style="text-align:left;margin:2px;display:block; width:100%;">Vitals</button>
				<button ng-click="action_results.open()" class="btn btn-success " style="text-align:left;margin:2px;display:block; width:100%;">Requests</button>
				<button ng-click="action_referrals.open()" class="btn btn-success" style="text-align:left;margin:2px;display:block; width:100%;">Referrals</button>
				<button ng-click="action_education.open()" class="btn btn-success" style="text-align:left;margin:2px;display:block; width:100%;">Education</button>
				<button ng-disabled="!data.encounter_child.id ? true : false" ng-click="action_childphysical.open()" class="btn btn-success" style="text-align:left;margin:2px;display:block; width:100%;">Child physical</button>
			</div>
		</div>
	</div>
	<div ng-class="(data.encounter.status == 1) ? 'col-md-9' : 'col-lg-12'"  >
		<div class="panel panel-default custom-widget">
			<div class="panel-heading">
				<div class="row">
					<div class="col-sm-10">
						<label style="margin-top:5px;" >General information </label>
					</div>
					<div class="col-sm-2 text-right">
						<a ng-show="data.encounter.status==2" target="_blank" ng-href="/encounter/pdf/{{ data.encounter.id }}/encounter" data-placement="bottom" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Print"> <i class="fa fa-print"></i> </a>
						<a ng-href="/encounter/request/{{ data.encounter.id }}" ng-show="data.encounter.status==2" title="Requests" data-placement="bottom" data-toggle="tooltip" class="btn btn-info btn-xs"><i class="fa fa-archive" aria-hidden="true"></i> Requests </a>
						<button ng-show="data.encounter.status==1" type="button" ng-click="action_sign.open('#encounter-detail-modal-sign')" class="btn btn-success btn-xs"> <i class="fa fa-pencil" aria-hidden="true"></i> Sign encounter</button>
					</div>
				</div>
			</div>
			<?php $this->template->render_view('encounter/view.encounter.body.content'); ?>
			<div class="panel-footer text-right">
				<p class="">(Medical Assistan only can edit: Vitals, Requests, Referals, Pt. Education and Child Physical)</p>
			</div>
		</div>
		
	</div>
</div>


