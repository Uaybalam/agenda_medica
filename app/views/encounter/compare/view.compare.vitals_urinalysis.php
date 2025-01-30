<table class="table table-hover table-condensed table-bordered" style="font-size:12px;">
	<thead>
		<tr>
			<th class="col-md-1 well well-sm">Fecha</th>
			<th class="col-md-1">Color</th>
			<th class="col-md-1">Gravedad</th>
			<th class="col-md-1">PH</th>
			<th class="col-md-1">Proteina</th>
			<th class="col-md-1">Glucosa</th>
			<th class="col-md-1">Cetonas</th>
			<th class="col-md-1">Bilirrubina</th>
			<th class="col-md-1">Sangre</th>
			<th class="col-md-1">Leucocitos</th>
			<th class="col-md-1">Nitritos</th>
			<th class="col-md-1">HCG</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($_['encounters'] as $encounter) : ?>
			<tr  <?php echo ($_['current_encounter_id'] === $encounter->id) ? 'class="warning"' : '' ?>  >
				<td><?= $_['months'][date("m",strtotime($encounter->date))-1].date("/d",strtotime($encounter->date)); ?></td>
				<td><?= $encounter->urinalysis_color; ?></td>
				<td><?= $encounter->urinalysis_specific_gravity; ?></td>
				<td><?= $encounter->urinalysis_ph; ?></td>
				<td><?= $encounter->urinalysis_protein; ?></td>
				<td><?= $encounter->urinalysis_glucose; ?></td>
				<td><?= $encounter->urinalysis_ketones; ?></td>
				<td><?= $encounter->urinalysis_bilirubim; ?></td>
				<td><?= $encounter->urinalysis_blood; ?></td>
				<td><?= $encounter->urinalysis_leuktocytes; ?></td>
				<td><?= $encounter->urinalysis_nitrite; ?></td>
				<td><?= $encounter->urinalysis_human_chorionic_gonadotropin; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
