<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class="col-md-1 well well-sm">Croníco</th>
			<th class="col-md-10 well well-sm">Comentario</th>
		</tr>
	</thead>
	<tbody>
		<?php setlocale(LC_ALL,"es_ES"); foreach ($_['diagnosis'] as $diagnostic) : ?>
			<tr  <?php echo ($_['current_encounter_id'] === $diagnostic->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($diagnostic->date))-1].date("/d",strtotime($diagnostic->date)); ?></td>
				<td><?= ($diagnostic->chronic) ? 'Yes' : 'No'; ?></td>
				<td><?= $diagnostic->comment; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h5 class="text-right">Filas con color <b class="text-warning">amarillo</b> son la actual consulta </h5>