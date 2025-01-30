<?php if(count($_['encounters']) === 0)
{
	echo '<h1 class="text-info" style="margin-bottom:32px;">Nunca se registraron datos para mostrar en <b>Signos Vitales > Ojos</b></h1>';
	exit;
}

?>
<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class=" well well-sm">Izquierdo Con lentes</th>
			<th class=" well well-sm">Derecho Con lentes</th>
			<th class=" well well-sm">Ambos Con glases</th>
			<th class=" well well-sm">Izquierdo sin lentes</th>
			<th class=" well well-sm">Derecho sin lentes</th>
			<th class=" well well-sm">Ambos sin lentes</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['encounters'] as $encounter) : ?>
			<tr  <?php echo ($_['current_encounter_id'] === $encounter->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($encounter->date))-1].date("/d",strtotime($encounter->date)); ?></td>
				<td><?= $encounter->eye_withglasses_left; ?></td>
				<td><?= $encounter->eye_withglasses_right; ?></td>
				<td><?= $encounter->eye_withglasses_both; ?></td>
				<td><?= $encounter->eye_withoutglasses_left; ?></td>
				<td><?= $encounter->eye_withoutglasses_right; ?></td>
				<td><?= $encounter->eye_withoutglasses_both; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h5 class="text-right">Filas con color <b class="text-warning">amarillo</b> son la actual consulta </h5>