

<div class="panel panel-default panel-custom">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-8"><b>Payments chart</b></div>
		</div>
	</div>
	<div class="panel-body" style="padding-right:40px;">
		<div class="row form-horizontal">
			<div class="form-group form-group-sm">
				<label class="col-md-2 control-label">Filters</label>
				<div class="col-md-10">
					<button ng-click="refreshAction('ALL_YEARS')" class="btn btn-default btn-sm active">All years</button>
					<button ng-click="refreshAction('YEAR', default.option_year)" 
						class="btn btn-default btn-sm" 
						ng-class="btnActive(['MONTH','DAY'])" 
						ng-disabled="btnEnabled(['MONTH','DAY'])">Year: {{ default.option_year }}</button>
					<button ng-click="refreshAction('MONTH', default.option_month)" 
						class="btn btn-default btn-sm" 
						ng-class="btnActive(['DAY'])" 
						ng-disabled="btnEnabled(['DAY'])">Month: {{ default.option_month }}</button>
				</div>
			</div>
		</div>
		<canvas id="canvas_chart" height=100  ng-click="filterLabelOption( $event )" ></canvas>
	</div>
</div>
