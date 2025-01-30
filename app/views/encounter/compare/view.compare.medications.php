<?php 
	/*
	$group_medications = [];
	foreach ($_['encounters'] as $encounter){
		$group_medications[$encounter->date][] =  $encounter->title." <span class='text-opacity'>(".$encounter->amount.")</span>";
	}
	*/
?>

<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class="col-md-1 well well-sm">Croníco</th>
			<th class="col-md-2 well well-sm">Titulo</th>
			<th class="col-md-1 well well-sm">Cantidad</th>
			<th class="col-md-7 well well-sm">Indicaciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['encounters'] as $key => $med ) : ?>
			<tr <?php echo ($_['current_encounter_id'] === $med->id) ? 'class="warning"' : '' ?>  >
				<td><?= date("Y/",strtotime($med->create_at)).$_['months'][date("m",strtotime($med->create_at))-1].date("/d",strtotime($med->create_at)); ?></td>
				<td><?= $med->chronic; ?></td>
				<td><?= $med->title; ?></td>
				<td><?= $med->amount; ?></td>
				<td><?= $med->directions; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h5 class="text-right">Filas con color <b class="text-warning">amarillo</b> son la actual consulta </h5>