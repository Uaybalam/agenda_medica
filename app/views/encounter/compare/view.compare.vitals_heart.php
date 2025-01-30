<?php

	$data_labels = $data_heart_pulse = $data_heart_respiratory = $data_heart_temperature = Array();

	foreach ($_['encounters'] as $encounter) {
		$data_labels[]            = "$encounter->date";
		$data_heart_pulse[]       = round($encounter->heart_pulse,2);
		$data_heart_respiratory[] = round($encounter->heart_respiratory,2);
		$data_heart_temperature[] = round($encounter->heart_temperature,2);
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
				label: 'Pulse',
				yAxisID: 'MINUTO',
				data: <?= json_encode($data_heart_pulse); ?>,
				fill:false,
      			borderColor: chartColors.red,
			},{
				label: 'Respiratory',
				yAxisID: 'MINUTO',
				data: <?= json_encode($data_heart_respiratory); ?>,
				fill:false,
				borderColor: chartColors.green,
			},{
				label: 'Temperature',
				yAxisID: 'FAHRENHEIT',
				data: <?= json_encode($data_heart_temperature); ?>,
				fill:false,
				borderColor: chartColors.blue,
			}]
		},
	  	options: {
			scales: {
				yAxes: [
					{
						id: 'MINUTO',
						position: 'left',
						scaleLabel: {
	                        display: true,
	                        labelString: "Minutos",
	                    },
		     		},
		     		{
						id: 'FAHRENHEIT',
						position: 'right',
						scaleLabel: {
	                        display: true,
	                        labelString: " ° Fahrenheit",
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
