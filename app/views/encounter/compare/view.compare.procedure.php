<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<!-- <th class="col-md-4">Procedures</th> -->
			<th class="col-md-4 well well-sm">Educación del paciente</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['encounters'] as $encounter) : ?>
			<tr  <?php echo ($_['current_encounter_id'] === $encounter->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($encounter->date))-1].date("/d",strtotime($encounter->date)); ?></td>
				<!-- <td><?= $encounter->procedure_text; ?></td> -->
				<td><?= $encounter->procedure_patient_education; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h5 class="text-right">Filas con color <b class="text-warning">amarillo</b> son la actual consulta </h5>