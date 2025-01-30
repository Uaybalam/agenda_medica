
<table class="table table-bordered table-condensed table-sm table-hover"  >
	<thead>
		<tr>
			<th class="well well-sm">Titulo</th>
			<th class="well well-sm">Paciente</th>
			<th class="well well-sm">Familiar</th>
		</tr>
	</thead>
	<tbody >
		<?php foreach ($_['data'] as $key => $value)  : ?>
			<tr >
				<td><?= $value->title ?></td>
				<td><?= $value->patient ?></td>
				<td><?= $value->family ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>