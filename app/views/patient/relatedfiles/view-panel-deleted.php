<style type="text/css">
	.item-hover{
		display: none;
	}
	.element-hover:hover .item-hover{
		display:inline-block;
	}
</style>
<div class="row" style="font-size:12px;" >
	<div class="col-lg-12">
		<div class="panel panel-default" >
			<div class="panel-heading">
				<label class="text-warning" style="float:right;">These documents remain available for 60 days</label>
				<label>Documents deleted <label>
			</div>
			<div class="panel-body" >
				<table class="table table-condensed table-bordered table-hover" >
					<thead>
						<tr>
							<th >Patient </th>
							<th >Deleted at</th>
							<th >Deleted by </th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($_['documents'] as $doc )  : 
							$date = new DateTime($doc['date']);
							?>
							<tr>
								<td><b><?= $doc['patient_id']?> </b>-  <?= $doc['patient']?></td>
								<td><?= $date->format('d M, Y h:i A')?></td>
								<td><?= $doc['user']?></td>
								<td><a target="_blank" href="<?= site_url('patient/related-files/docDownload?fileName='.$doc['file'])?>" class="btn btn-default btn-xs"> Download </a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			
		</div>
	</div>
</div>
