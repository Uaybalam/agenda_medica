<?php

	$data_labels = $data_systolic = $data_diastolic = Array();

	foreach ($_['encounters'] as $encounter) {
		$data_labels[]    = "$encounter->date";
		$data_systolic[]  = round($encounter->blood_pressure_sys,2);
		$data_diastolic[] = round($encounter->blood_pressure_dia,2);
	}
?>

<script type="text/javascript">

	var canvas = document.getElementById('canvas_chart');
	
	new Chart(canvas, {
		type: 'line',
		data: {
			labels: <?= json_encode($data_labels); ?>.map(element => moment(element).format("MMM/DD")),
			datasets: [
			{
				label: 'Systolic',
				yAxisID: 'MM_HG',
				data: <?= json_encode($data_systolic); ?>,
				fill:false,
      			borderColor: chartColors.red,
			},{
				label: 'Diastolic',
				yAxisID: 'MM_HG',
				data: <?= json_encode($data_diastolic); ?>,
				fill:false,
				borderColor: chartColors.green,
			},]
		},
	  	options: {
			scales: {
				yAxes: [
					{
						id: 'MM_HG',
						position: 'left',
						scaleLabel: {
	                        display: true,
	                        labelString: "mm Hg",
	                    },
		     		}
				]
			}
  		}
	});
	

</script>

<div class="row">
	<div class="col-md-12 text-center" >
		<canvas id="canvas_chart" height=100 ></canvas>
		<hr>
	</div>
</div>

