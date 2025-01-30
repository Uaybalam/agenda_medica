<style type="text/css">
	.content-bottom-right{
		position:absolute;
		right:15px;
		bottom:6px;
		opacity: 0;

		-webkit-transition: opacity .3s ease-in-out;
		-moz-transition: opacity .3s ease-in-out;
		-ms-transition: opacity .3s ease-in-out;
		-o-transition: opacity .3s ease-in-out;
		transition: opacity .3s ease-in-out;
	}

	.panel-with-content:hover .content-bottom-right{
		filter: alpha(opacity=50);
  		opacity: 1;
	}
	.input-setting_insurance
	{
		width: 50% !important;
	}
</style>
<div class="row">
		<div class="col-md-6 col-lg-4" ng-repeat="( groupSetting, setting ) in data.settings">
			<div class="panel panel-default panel-with-content" >
				<div class="panel-heading" style="font-size: 12px;">
					<form ng-submit="$event.preventDefault();action_settings.insert(setting)" class="input-group input-group-sm"> 
						<span class="input-group-addon" id="sizing-addon2"><i class="fa fa-question-circle-o" data-toggle="tooltip" title="{{ setting.helper}}"></i> <b>{{ setting.title }}</b></span>
						<input autocomplete="OFF" id="setting-name {{groupSetting}}" class="form-control" ng-model="setting.new_name" placeholder="Add {{ setting.title}}"> 
						<span class="input-group-btn"> 
							<button class="btn btn-success" ng-disabled="setting.new_name=='' ? true : false;" type="submit" >Add</button> 
						</span> 
					</form>
				</div>
				<div class="panel-body" style="position:relative;height: 250px; overflow-y:auto;">
					
					<form dir-paginate="inputModel in setting.appPagination.result_data  | itemsPerPage:setting.appPagination.itemsPerPage"  current-page="setting.appPagination.currentPage" total-items="setting.appPagination.total_count"  ng-submit="$event.preventDefault();action_settings.update(setting,inputModel)"  class="input-group input-group-sm input-group-xs" style="margin-bottom: 3px;" pagination-id="groupSetting + '_id'"  >
						<input autocomplete="OFF" class="{{'form-control input-'+groupSetting}}" ng-model="inputModel.name"/> 
						<input class="{{'form-control input-'+groupSetting}}"  ng-model="inputModel.fullname" ng-show="groupSetting == 'setting_insurance'" type="text" style="border-left:none;">
						<div class="input-group-btn"> 
							<button data-placement="left" data-toggle="tooltip" title="Update" type="submit" class="btn btn-default" ng-disabled="inputModel.name==inputModel.name_initial && inputModel.fullname==inputModel.fullname_initial" > <i class="fa fa-pencil"></i></button> 
							<button data-placement="left" data-toggle="tooltip" title="Remove" type="button" class="btn btn-default" ng-click="action_settings.delete(setting,inputModel)" > <i class="fa fa-trash"></i></button> 
						</div>
					</form>
					
					<div class="content-bottom-right" >
						<a class="btn btn-sm btn-warning" ng-href="/settings/{{groupSetting}}/pdf" data-toggle="tooltip" title="Print" target="_blank"> <i class="fa fa-print"></i></a>
					</div>
					
					<div style="position:absolute;bottom:0;left:5;">
						<dir-pagination-controls 
						 	pagination-id="groupSetting + '_id'" 
							auto-hide="true" 
							max-size="6" 
							direction-links="true" 
							boundary-links="false" 
							on-page-change="setting.appPagination.getData(newPageNumber)" ></dir-pagination-controls>
					</div>
					
				</div>

			</div>

		</div>

</div>