<div class="row">
	<div class="col-lg-12">
		<span class="" ng-repeat="type in typesOfCommunications" style="margin-right: 50px;" ng-class="type.class"> <i class="fa " ng-class="type.subclass"></i> {{ type.title}} </span>
		<br><br>
		<div class="table-responsive">
			<table class="table table-hover table-bordered table-condensend" style="font-size:12px;">
				<thead>	
					<tr>
						<th class="col-md-2">Fecha y Hora</th>
						<th class="col-md-1 text-center">Tipo</th>
						<th class="col-md-2">Creado por</th>
						<th class="col-md-4">Notas </th>
						<th class="col-md-3">Razón de contacto </th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="history in data.history_communications">
						<td class="col-md-2">{{ ngHelper.formatDate(history.create_at) }} </span></td>
						<td class="col-md-1 text-center" >
							<label ng-class="typesOfCommunications[history.type].class"> <i class="fa" ng-class="typesOfCommunications[history.type].subclass"></i> </label>
							
						<td class="col-md-2">{{history.user_full_name}}</td>
						<td class="col-md-4">{{history.notes}}</td>
						<td class="col-md-3">{{history.contact_reason}}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
