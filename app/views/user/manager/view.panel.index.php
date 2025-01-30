<div class="panel panel-default panel-custom" >

	<div class="panel-heading">
		<div class="row">
			<div class="col-md-6">
				<label>Lista de usuarios <span class="badge" ng-cloak title="Total users" data-toggle="tooltip" data-placement="right"> {{ data.data_users.length}}</span></label>
				
			</div>
			<div class="col-md-6 text-right">
				<button ng-click="action_user.open()" type="button" class="btn btn-success btn-xs" title="Agregar usuario" data-toggle="tooltip" data-placement="bottom" ><i class="fa fa-user-plus" aria-hidden="true"></i></button>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<table class="table table-hover table-condensed table-bordered" style="font-size:12px;" ng-cloak >
			<div style="display: flex;">
				<input style="max-width:300px;display:inline-block; margin-right: 10px;" type="text" ng-model="search" class="form-control input-sm" placeholder="Buscar...." />
				<div class="custom-checkbox custom-checkbox-sm">
					<label>
				    	<input type="checkbox" ng-model="active_status" ng-true-value="1" ng-false-value="0"/> <span class="checkbox-text">Solo activos</span>
				    	<span class="checkbox"></span>
				  	</label>
				</div>
			</div>
			<thead>
				<tr>
					<th class="col-md-1"><button class="btn btn-default btn-xs max-width" ng-click="sort('nick_name')" >Usuario 
						<i class="pull-right fa " ng-show="sortKey=='nick_name'" ng-class="{'fa-angle-up':reverse,'fa-angle-down':!reverse}"></i>
					</button></th>
					<th class="col-md-2"><button class="btn btn-default btn-xs max-width" ng-click="sort('access_type')" >Tipo de acceso
						<i class="pull-right fa " ng-show="sortKey=='access_type'" ng-class="{'fa-angle-up':reverse,'fa-angle-down':!reverse}"></i>
					</button></th>
					<th class="col-md-3"><button class="btn btn-default btn-xs max-width" ng-click="sort('names')" >Nombre
						<i class="pull-right fa " ng-show="sortKey=='names'" ng-class="{'fa-angle-up':reverse,'fa-angle-down':!reverse}"></i>
					</button></th>
					<th class="col-md-2"><button class="btn btn-default btn-xs max-width" disabled="true" type="button">Email
					</button></th>
					<th class="col-md-1"><button class="btn btn-default btn-xs max-width disabled" >Fecha de nacimiento</button> </th>
					<th class="col-md-2"><button class="btn btn-default btn-xs max-width disabled" >Estatus</button> </th>
					<th class="col-md-1"></th>
				</tr>
			</thead>
			<tbody>
				<tr  dir-paginate="user in data.data_users | filter:search | filter:searchStatus | orderBy:sortKey:reverse | itemsPerPage:15 ">
					<td>{{ user.nick_name}}</td>
					<td> <b>{{ data.access_type_avalible[user.access_type] == undefined ? "Root" : data.access_type_avalible[user.access_type]}}</b></td>
					<td>{{ user.names+' '+user.last_name}}</td>
					<td>{{ user.email }}</td>
					<td>{{ user.date_of_birth}}</td>
					<td>	
						<span class="badge" ng-class="(user.status==0) ? 'alert-muted' : (user.status==1 ) ? 'alert-success' : 'alert-warning'">
							{{ data.catalog_status[user.status] }}
						</span> 
					</td>
					<td>
						<a  title="Editar usuario" data-toggle="tooltip" data-placement="bottom" ng-href="/user/manager/{{ user.id }}" class="btn btn-info btn-xs"> <i class="fa fa-pencil-square-o"></i></a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel-footer text-right">
		<dir-pagination-controls max-size="5" direction-links="true" boundary-links="false" ></dir-pagination-controls>
	</div>
</div>

