<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class="col-md-1 well well-sm">Izquierdo 1000</th>
			<th class="col-md-1 well well-sm">Izquierdo 2000</th>
			<th class="col-md-1 well well-sm">Izquierdo 3000</th>
			<th class="col-md-1 well well-sm">Izquierdo 4000</th>
			<th class="col-md-1 well well-sm">Derecho 1000</th>
			<th class="col-md-1 well well-sm">Derecho 2000</th>
			<th class="col-md-1 well well-sm">Derecho 3000</th>
			<th class="col-md-1 well well-sm">Derecho 4000</th>

		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['encounters'] as $encounter) : ?>
			<tr  <?php echo ($_['current_encounter_id'] === $encounter->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($encounter->date))-1].date("/d",strtotime($encounter->date)); ?></td>
				<td><?= $encounter->audio_left_1000; ?></td>
				<td><?= $encounter->audio_left_2000; ?></td>
				<td><?= $encounter->audio_left_3000; ?></td>
				<td><?= $encounter->audio_left_4000; ?></td>
				<td><?= $encounter->audio_right_1000; ?></td>
				<td><?= $encounter->audio_right_2000; ?></td>
				<td><?= $encounter->audio_right_3000; ?></td>
				<td><?= $encounter->audio_right_4000; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h5 class="text-right">Filas con color <b class="text-warning">amarillo</b> son la actual consulta </h5>