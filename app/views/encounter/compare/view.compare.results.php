<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class="col-md-1 well well-sm">Previsualización</th>
			<th class="col-md-2 well well-sm">Titulo</th>
			<th class="col-md-1 well well-sm">Tipo</th>
			<th class="col-md-7 well well-sm">Comentarios</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['encounters'] as $encounter) : 
			$data_id =  $encounter->encounter_result_id;
		?>	
			<tr  <?php echo ($_['current_encounter_id'] === $encounter->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($encounter->date))-1].date("/d",strtotime($encounter->date)); ?></td>
				<td>
					<a  style="height:50px;margin:0px;" href="/encounter/results/open/<?= $data_id;?>" target="_blank" class="thumbnail">
						<img style="height: 100%; width: 100%; display: block;object-fit: scale-down" 
							src="/encounter/results/<?= $data_id;?>/open-preview/" alt=""  /> 
					</a> 
				</td>
				<td><?= $encounter->title; ?></td>
				<td><?= $encounter->type_result; ?></td>
				<td><?= $encounter->comments; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h5 class="text-right">Filas con color <b class="text-warning">amarillo</b> son la actual consulta </h5>