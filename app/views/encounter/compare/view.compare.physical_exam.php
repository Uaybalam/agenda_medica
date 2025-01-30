<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class="col-md-2 well well-sm">Titulo</th>
			<th class="col-md-9 well well-sm">Contenido</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['physical_exams'] as $physical_exam) : ?>
			<tr  <?php echo ($_['current_encounter_id'] === $physical_exam->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($physical_exam->date))-1].date("/d",strtotime($physical_exam->date)); ?></td>
				<td><?= $physical_exam->title; ?></td>
				<td><?= $physical_exam->content; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h5 class="text-right">Filas con color <b class="text-warning">amarillo</b> son la actual consulta </h5>