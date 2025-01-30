<div class="row" >
	<div class="col-lg-12" style="padding-bottom:20px;">
		<button class="btn btn-warning btn-sm submit" type="button"> <i class="fa fa-barcode" aria-hidden="true"></i> Scan </button>
		<label class="btn btn-warning btn-sm submit" > <i class="fa fa-upload" aria-hidden="true"></i> Cargar
			 <input type="file" style="display:none;" onchange="angular.element(this).scope().action_result.upload(this)"  >
		</label>
		<hr>
		<a ng-show="default.results.file!=''" ng-href="/encounter/laboratory/open/{{default.results.id}}?i={{default.results.random}}" target="_blank" class="thumbnail">
			<img width="100%" height="300px" ng-src="/encounter/laboratory/open/{{default.results.id+'?i='+default.results.random}}" alt="Description" />
		</a>
	</div>
</div>
