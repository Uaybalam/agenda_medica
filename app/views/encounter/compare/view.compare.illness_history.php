<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class="col-md-11 well well-sm">Historial de enfermedades</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['encounters'] as $encounter) : ?>
			<tr  <?php echo ($_['current_encounter_id'] === $encounter->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($encounter->date))-1].date("/d",strtotime($encounter->date)); ?></td>
				<td><?= $encounter->present_illness_history; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>